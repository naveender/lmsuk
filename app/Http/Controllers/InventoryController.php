<?php
//FILE: app/Http/Controllers/InventoryController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            // ✅ Filters from query
            $search = $request->input('search');
            $date = $request->input('date'); // Format: YYYY-MM-DD

            // ✅ Fetch all Wasabi files (use 'backups' if stored in a folder)
            $contents = Storage::disk('wasabi')->listContents('', true);

            $files = collect($contents)
                ->filter(fn($item) => $item['type'] === 'file')
                ->map(function ($file) {
                    return [
                        'name' => $file['path'] ?? '',
                        'size' => isset($file['file_size']) ? $this->formatBytes($file['file_size']) : 'N/A',
                        'last_modified' => isset($file['last_modified'])
                            ? date('Y-m-d H:i:s', $file['last_modified'])
                            : 'N/A',
                        'timestamp' => $file['last_modified'] ?? 0,
                        'date_only' => isset($file['last_modified'])
                            ? date('Y-m-d', $file['last_modified'])
                            : null,
                    ];
                });

            // ✅ Apply search filter
            if (!empty($search)) {
                $files = $files->filter(fn($f) => str_contains(strtolower($f['name']), strtolower($search)));
            }

            // ✅ Apply date filter
            if (!empty($date)) {
                $files = $files->filter(fn($f) => $f['date_only'] === $date);
            }

            // ✅ Sort by latest
            $files = $files->sortByDesc('timestamp')->values();

            // ✅ Paginate
            $page = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $currentPageItems = $files->slice(($page - 1) * $perPage, $perPage)->values();

            $paginatedFiles = new LengthAwarePaginator(
                $currentPageItems,
                $files->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('inventory', [
                'files' => $paginatedFiles,
                'search' => $search,
                'date' => $date,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching inventory: ' . $e->getMessage());
        }
    }

public function download($file)
{
    try {
        $file = urldecode($file);

        if (!Storage::disk('wasabi')->exists($file)) {
            return back()->with('error', 'File not found on Wasabi.');
        }

        // Generate a temporary signed URL valid for 1 hour
        $url = Storage::disk('wasabi')->temporaryUrl($file, now()->addHour());

        // Return JSON so the frontend can redirect the user
        return response()->json(['url' => $url]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    // New: serve the locally downloaded restore file for a finished batch
    public function serveRestore($batchId)
    {
        $cacheKey = 'restore_path_' . $batchId;
        $localRelative = Cache::get($cacheKey);

        if (! $localRelative) {
            abort(404, 'Restore not found or expired.');
        }

        $localFull = storage_path('app/' . $localRelative);
        if (! file_exists($localFull)) {
            abort(404, 'File not found on server.');
        }

        return response()->download($localFull);
    }

    /**
     * Format bytes to human-readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

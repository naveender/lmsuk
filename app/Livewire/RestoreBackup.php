<?php
// FILE: app/Livewire/RestoreBackup.php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use App\Jobs\DownloadBackupJob;

class RestoreBackup extends Component
{
    public string $file;
    public ?string $batchId = null;
    public int $progress = 0;
    public string $status = 'idle';
    public ?string $downloadUrl = null;
    public int $fileSize = 0; // bytes
    public string $fileSizeHuman = '';
    public string $estimatedTime = '';
    public string $estimatedTimeRemaining = '';
    protected float $averageSpeed = 10 * 1024 * 1024; // 10 MB/s
    public bool $isAnotherRestoreRunning = false;

    public function mount($file)
    {
        $this->file = $file;

        // Get file size from Wasabi
        try {
            $size = \Illuminate\Support\Facades\Storage::disk('wasabi')->size($file);
            $this->fileSize = $size;
            $this->fileSizeHuman = $this->formatBytes($size);
            $this->estimatedTime = $this->estimateTime($size);
        } catch (\Throwable $e) {
            $this->fileSize = 0;
            $this->fileSizeHuman = 'N/A';
            $this->estimatedTime = 'Unknown';
        }

        // Try to re-attach to an existing batch for this file (survives page refresh)
        $cachedBatch = Cache::get('restore_batch_for_file_' . $this->file);
        if ($cachedBatch) {
            $this->batchId = $cachedBatch;
            $this->status = 'running';
            // read any cached progress immediately
            $cachedProgress = Cache::get('restore_progress_' . $this->batchId);
            if (is_int($cachedProgress)) {
                $this->progress = $cachedProgress;
            }
        }

        $this->isAnotherRestoreRunning = Cache::has('restore_job_running');
    }

    public function startRestore()
    {
        if ($this->batchId || $this->isAnotherRestoreRunning) {
            return;
        }

        Cache::put('restore_job_running', true, now()->addHours(2));

        // Pass batchId to DownloadBackupJob
        $batch = Bus::batch([
            new DownloadBackupJob($this->file, null) // batchId will be set after batch creation
        ])->name('restore:' . $this->file)
          ->allowFailures()
          ->dispatch();

        $this->batchId = $batch->id;
        $this->status = 'queued';

        // Persist mapping file -> batch so mount can reattach after refresh
        Cache::put('restore_batch_for_file_' . $this->file, $batch->id, now()->addHours(2));
        // Initialize progress
        Cache::put('restore_progress_' . $batch->id, 0, now()->addHours(2));
        Cache::put('restore_overall_progress_' . $batch->id, 0, now()->addHours(2));

        // Cache an initial heuristic for processing overhead (seconds) so UI can show something immediately
        $overheadSeconds = 30;
        if ($this->fileSize > 0) {
            $overheadSeconds = max(15, (int) round($this->fileSize / (20 * 1024 * 1024)));
            $overheadSeconds = min($overheadSeconds, 900);
        }
        Cache::put('restore_overhead_seconds_' . $batch->id, $overheadSeconds, now()->addHours(2));
    }

    /**
     * Called by front-end polling (wire:poll) to update progress/status.
     */
    public function refreshStatus()
    {
        if (!$this->batchId) {
            return;
        }

        $batch = Bus::findBatch($this->batchId);

        // Read overall progress from cache
        $overallProgress = Cache::get('restore_' . $this->batchId . '_overall_progress');
        if (is_int($overallProgress)) {
            $this->progress = $overallProgress;
        } else {
            $cached = Cache::get('restore_progress_' . $this->batchId);
            if (is_int($cached)) {
                $this->progress = $cached;
            } else {
                $this->progress = $batch ? (int) $batch->progress() : 0;
            }
        }

        // Enhanced time estimation
        $bytesCopied = Cache::get('restore_bytes_' . $this->batchId) ?? 0;
        $startedAt = Cache::get('restore_started_at_' . $this->batchId);
        $overheadSeconds = Cache::get('restore_overhead_seconds_' . $this->batchId) ?? 30;

        if ($this->fileSize > 0 && $bytesCopied > 0 && $startedAt) {
            $elapsed = max(1, time() - intval($startedAt));
            $speed = $bytesCopied / $elapsed; // bytes per second

            // Dynamic speed adjustment based on recent progress
            $recentSpeed = Cache::get('restore_recent_speed_' . $this->batchId);
            if ($recentSpeed) {
                $speed = ($speed + $recentSpeed) / 2; // Average with recent speed
            }
            Cache::put('restore_recent_speed_' . $this->batchId, $speed, now()->addMinutes(5));

            $bytesRemaining = $this->fileSize - $bytesCopied;
            $transferTimeRemaining = $bytesRemaining / max(1, $speed);
            
            // Scale overhead based on remaining progress
            $progressPercent = $bytesCopied / $this->fileSize;
            $remainingOverhead = $overheadSeconds * (1 - $progressPercent);
            
            $totalTimeRemaining = (int) ceil($transferTimeRemaining + $remainingOverhead);
            $this->estimatedTimeRemaining = $this->formatSeconds($totalTimeRemaining);
        }

        // Use status from cache if available
        $statusCache = Cache::get('restore_' . $this->batchId . '_status');
        if ($statusCache) {
            $this->status = $statusCache === 'completed' ? 'finished' : $statusCache;
        } elseif ($batch->finished()) {
            $this->status = 'finished';
            $localPath = Cache::get('restore_path_' . $this->batchId);
            if ($localPath) {
                $this->downloadUrl = route('restore.download', ['batch' => $this->batchId]);
            }
            Cache::forget('restore_batch_for_file_' . $this->file);
        } elseif ($batch->cancelled() || $batch->cancelled) {
            $this->status = 'cancelled';
        } elseif ($batch->hasFailures()) {
            $this->status = 'failed';
        } else {
            $this->status = 'running';
        }

        if ($this->status === 'finished') {
            $this->resetRestoreState();
        }

        if ($this->status === 'transferring') {
            $this->estimatedTimeRemaining = 'Transferring file to remote server...';
        } elseif ($this->status === 'extracting') {
            $this->estimatedTimeRemaining = 'Extracting files on remote server...';
        }
    }

    private function resetRestoreState()
    {
        $this->status = 'idle';
        $this->batchId = null;
        $this->progress = 0;
        $this->downloadUrl = null;
        $this->isAnotherRestoreRunning = false;
        Cache::forget('restore_job_running');
        Cache::forget('restore_batch_for_file_' . $this->file);
    }

    // New: allow user to cancel the running batch
    public function cancelRestore()
    {
        if (! $this->batchId) {
            return;
        }

        $batch = Bus::findBatch($this->batchId);
        if ($batch && ! $batch->finished() && ! $batch->cancelled()) {
            $batch->cancel();
            // mark cached progress/state
            Cache::put('restore_progress_' . $this->batchId, 0, now()->addMinutes(30));
            $this->status = 'cancelled';
        }

        Cache::forget('restore_job_running');
        $this->isAnotherRestoreRunning = false;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function estimateTime($bytes)
    {
        if ($bytes <= 0) return 'Unknown';
        $seconds = $bytes / $this->averageSpeed;
        return $this->formatSeconds($seconds);
    }

    private function formatSeconds($seconds)
    {
        $seconds = (int) round($seconds);
        if ($seconds < 60) {
            return $seconds . ' seconds';
        }
        if ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $secs = $seconds % 60;
            return $minutes . ' min' . ($secs > 0 ? ' ' . $secs . ' sec' : '');
        }
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return $hours . ' hr' . ($minutes > 0 ? ' ' . $minutes . ' min' : '');
    }

    public function render()
    {
        return view('livewire.restore-backup');
    }
}

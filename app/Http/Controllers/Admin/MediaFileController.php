<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\YearGroup;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaFileController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaFile::with(['subject', 'class', 'yearGroup']);

        // Search filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Disk filter
        if ($request->filled('disk')) {
            $query->where('storage_disk', $request->disk);
        }

        // Subject filter
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Class filter
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $files = $query->orderBy('created_at', 'desc')->paginate(12);
        
        $subjects = Subject::orderBy('title')->get();
        $classes = Classes::orderBy('name')->get();

        return view('admin.media-files.index', compact('files', 'subjects', 'classes'));
    }

    public function create()
    {
        $maxUploadSize = setting('general.max_upload_size', '1024'); // in MB
        
        $subjects = Subject::orderBy('title')->get();
        $classes = Classes::orderBy('name')->get();
        $yearGroups = YearGroup::orderBy('title')->get();
        $academicYears = AcademicYear::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        return view('admin.media-files.create', compact(
            'maxUploadSize', 'subjects', 'classes', 'yearGroups', 'academicYears', 'courses'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:youtube,vimeo,video_url,google_drive,iframe',
            'path' => 'required|string',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'year_group_id' => 'required|exists:year_groups,id',
            'academic_year' => 'required|string',
            'duration' => 'nullable|string',
            'thumbnail_path' => 'nullable|string',
            'publication_status' => 'required|in:draft,published',
            
            // Course Assignment
            'course_id' => 'nullable|exists:courses,id',
            'week_mode' => 'nullable|in:existing,new',
            'selected_week_id' => 'nullable|exists:weeks,id',
            'new_week_name' => 'nullable|string|max:255',
            'new_week_due_date' => 'nullable|date',
            'create_new_course' => 'nullable|boolean',
            'new_course_name' => 'nullable|string|max:255',
        ]);

        $media = MediaFile::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'path' => $request->path,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'year_group_id' => $request->year_group_id,
            'academic_year' => $request->academic_year,
            'duration' => $request->duration,
            'thumbnail_path' => $request->thumbnail_path,
            'publication_status' => $request->publication_status,
            'status' => 'completed',
        ]);

        // Process course assignment
        $this->assignToCourse(
            $media,
            $request->course_id,
            $request->week_mode,
            $request->selected_week_id,
            $request->new_week_name,
            $request->new_week_due_date,
            $request->has('create_new_course'),
            $request->new_course_name
        );

        return redirect()->route('admin.media-files.index')->with('success', 'Media file linked successfully.');
    }

    public function edit(MediaFile $mediaFile)
    {
        $subjects = Subject::orderBy('title')->get();
        $classes = Classes::orderBy('name')->get();
        $yearGroups = YearGroup::orderBy('title')->get();
        $academicYears = AcademicYear::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        // Get currently assigned course details
        $assignedCourse = $mediaFile->courses()->first();
        $assignedCourseId = $assignedCourse ? $assignedCourse->id : '';
        $assignedWeekId = $assignedCourse ? $assignedCourse->pivot->week_id : '';
        
        $weeks = [];
        if ($assignedCourseId) {
            $weeks = Week::where('course_id', $assignedCourseId)->orderBy('name')->get();
        }

        return view('admin.media-files.edit', compact(
            'mediaFile', 'subjects', 'classes', 'yearGroups', 'academicYears', 'courses', 'weeks', 'assignedCourseId', 'assignedWeekId'
        ));
    }

    public function update(Request $request, MediaFile $mediaFile)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'path' => 'nullable|string', // nullable if they don't upload a new video
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'year_group_id' => 'required|exists:year_groups,id',
            'academic_year' => 'required|string',
            'duration' => 'nullable|string',
            'thumbnail_path' => 'nullable|string',
            'publication_status' => 'required|in:draft,published',
            
            // Course Assignment
            'course_id' => 'nullable|exists:courses,id',
            'week_mode' => 'nullable|in:existing,new',
            'selected_week_id' => 'nullable|exists:weeks,id',
            'new_week_name' => 'nullable|string|max:255',
            'new_week_due_date' => 'nullable|date',
            'create_new_course' => 'nullable|boolean',
            'new_course_name' => 'nullable|string|max:255',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'subject_id' => $request->subject_id,
            'class_id' => $request->class_id,
            'year_group_id' => $request->year_group_id,
            'academic_year' => $request->academic_year,
            'duration' => $request->duration,
            'publication_status' => $request->publication_status,
        ];

        // Update path if provided (e.g. for linked files)
        if ($request->filled('path')) {
            $data['path'] = $request->path;
            $data['type'] = $request->type;
        }

        // Update thumbnail path if provided
        if ($request->filled('thumbnail_path')) {
            // Delete old thumbnail if exists
            if ($mediaFile->thumbnail_path) {
                Storage::disk('public')->delete($mediaFile->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->thumbnail_path;
        }

        $mediaFile->update($data);

        // Process Course assignment updates
        $mediaFile->courses()->detach(); // detach old ones first
        
        $this->assignToCourse(
            $mediaFile,
            $request->course_id,
            $request->week_mode,
            $request->selected_week_id,
            $request->new_week_name,
            $request->new_week_due_date,
            $request->has('create_new_course'),
            $request->new_course_name
        );

        return redirect()->route('admin.media-files.index')->with('success', 'Media file updated successfully.');
    }

    /**
     * Upload custom video thumbnail.
     */
    public function uploadThumbnail(Request $request)
    {
        $request->validate([
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'thumb_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('thumbnails', $filename, 'public');

            return response()->json([
                'success' => true,
                'thumbnail_path' => $path,
                'url' => Storage::disk('public')->url($path),
            ]);
        }

        return response()->json(['success' => false, 'error' => 'No file received.'], 400);
    }

    /**
     * Get status of chunked upload. Checks which chunks are already uploaded.
     */
    public function uploadStatus(Request $request)
    {
        $uploadId = $request->query('upload_id');
        if (!$uploadId) {
            return response()->json(['error' => 'Missing upload_id'], 400);
        }

        $chunkDir = storage_path("app/chunks/{$uploadId}");
        $completedChunks = [];

        if (File::exists($chunkDir)) {
            $files = File::files($chunkDir);
            foreach ($files as $file) {
                $filename = $file->getFilename();
                if (preg_match('/^chunk_(\d+)$/', $filename, $matches)) {
                    $completedChunks[] = (int)$matches[1];
                }
            }
        }

        return response()->json([
            'upload_id' => $uploadId,
            'completed_chunks' => $completedChunks,
        ]);
    }

    /**
     * Process chunked uploads.
     */
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'chunk_index' => 'required|integer',
            'total_chunks' => 'required|integer',
            'filename' => 'required|string',
            'upload_id' => 'required|string',
            'storage_target' => 'required|in:local,s3,wasabi',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            
            // Metadata
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'year_group_id' => 'required|exists:year_groups,id',
            'academic_year' => 'required|string',
            'duration' => 'nullable|string',
            'thumbnail_path' => 'nullable|string',
            'publication_status' => 'required|in:draft,published',
        ]);

        $uploadId = $request->upload_id;
        $chunkIndex = (int)$request->chunk_index;
        $totalChunks = (int)$request->total_chunks;
        $filename = $request->filename;
        $storageTarget = $request->storage_target;

        $chunkDir = storage_path("app/chunks/{$uploadId}");

        // Create chunks directory if not exists
        if (!File::exists($chunkDir)) {
            File::makeDirectory($chunkDir, 0755, true);
        }

        // Store chunk
        $file = $request->file('file');
        $file->move($chunkDir, "chunk_{$chunkIndex}");

        // Verify if all chunks are uploaded
        $chunksUploaded = count(File::files($chunkDir));

        if ($chunksUploaded === $totalChunks) {
            // All chunks received. Let's merge them
            $safeName = Str::slug(pathinfo($filename, PATHINFO_FILENAME)) . '-' . time() . '.' . pathinfo($filename, PATHINFO_EXTENSION);
            
            // Create local videos folder in public disk path
            $videosDir = storage_path("app/public/videos");
            if (!File::exists($videosDir)) {
                File::makeDirectory($videosDir, 0755, true);
            }

            $outputPath = "{$videosDir}/{$safeName}";
            $out = fopen($outputPath, "wb");

            if ($out) {
                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = "{$chunkDir}/chunk_{$i}";
                    $in = fopen($chunkPath, "rb");
                    if ($in) {
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                        fclose($in);
                    }
                    // Delete chunk file immediately after reading
                    File::delete($chunkPath);
                }
                fclose($out);
            }

            // Clean up temporary chunks folder
            File::deleteDirectory($chunkDir);

            // Determine final path & move file if target is cloud storage
            $finalPath = "videos/{$safeName}";
            $mimeType = File::mimeType($outputPath);
            $fileSize = File::size($outputPath);

            if ($storageTarget === 's3' || $storageTarget === 'wasabi') {
                try {
                    // Upload to S3/Wasabi
                    $cloudDisk = Storage::disk($storageTarget);
                    
                    // Put file onto S3/Wasabi bucket
                    $cloudDisk->putFileAs('videos', new \Illuminate\Http\File($outputPath), $safeName, 'public');
                    
                    // Delete local file since it's hosted in the cloud now
                    File::delete($outputPath);
                    
                } catch (\Exception $e) {
                    // If cloud upload fails, fallback to local storage
                    logger()->error("Cloud upload to {$storageTarget} failed: " . $e->getMessage() . ". Falling back to local storage.");
                    $storageTarget = 'public';
                }
            } else {
                $storageTarget = 'public';
            }

            // Create db record
            $media = MediaFile::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->storage_target === 'local' ? 'video_file' : $request->storage_target,
                'path' => $finalPath,
                'storage_disk' => $storageTarget,
                'original_name' => $filename,
                'file_size' => $fileSize,
                'mime_type' => $mimeType,
                'status' => 'completed',
                'upload_id' => $uploadId,
                
                // Metadata
                'subject_id' => $request->subject_id,
                'class_id' => $request->class_id,
                'year_group_id' => $request->year_group_id,
                'academic_year' => $request->academic_year,
                'duration' => $request->duration,
                'thumbnail_path' => $request->thumbnail_path,
                'publication_status' => $request->publication_status,
            ]);

            // Assign course if provided in request parameters
            if ($request->filled('course_id')) {
                $this->assignToCourse(
                    $media,
                    $request->course_id,
                    $request->week_mode,
                    $request->selected_week_id,
                    $request->new_week_name,
                    $request->new_week_due_date,
                    $request->create_new_course === 'true' || $request->create_new_course === '1',
                    $request->new_course_name
                );
            }

            return response()->json([
                'success' => true,
                'status' => 'completed',
                'media_id' => $media->id,
                'url' => $media->url,
            ]);
        }

        // Return progress response
        return response()->json([
            'success' => true,
            'status' => 'uploading',
            'chunk_index' => $chunkIndex,
            'progress' => round(($chunksUploaded / $totalChunks) * 100, 2),
        ]);
    }

    public function destroy(MediaFile $mediaFile)
    {
        try {
            // Delete actual file if stored locally or in cloud
            if ($mediaFile->path && in_array($mediaFile->type, ['video_file', 's3', 'wasabi'])) {
                $disk = $mediaFile->storage_disk ?: 'public';
                if (Storage::disk($disk)->exists($mediaFile->path)) {
                    Storage::disk($disk)->delete($mediaFile->path);
                }
            }
            
            // Delete thumbnail if exists
            if ($mediaFile->thumbnail_path) {
                Storage::disk('public')->delete($mediaFile->thumbnail_path);
            }
        } catch (\Exception $e) {
            logger()->error('Failed to delete physical media file: ' . $e->getMessage());
        }

        $mediaFile->delete();

        return redirect()->route('admin.media-files.index')->with('success', 'Media file deleted successfully.');
    }

    /**
     * Handle Course Assignment Week-Pivot linkage.
     */
    private function assignToCourse(MediaFile $mediaFile, $courseId, $weekMode, $selectedWeekId, $newWeekName, $newWeekDueDate, $createNewCourse, $newCourseName)
    {
        $courseIdToAssign = null;
        if ($createNewCourse && !empty($newCourseName)) {
            $newCourse = Course::create([
                'name' => $newCourseName,
                'is_active' => true,
            ]);
            $courseIdToAssign = $newCourse->id;
        } elseif ($courseId) {
            $courseIdToAssign = $courseId;
        }

        if ($courseIdToAssign) {
            $weekId = null;
            $weekName = '';
            if ($weekMode === 'existing' && $selectedWeekId) {
                $weekId = $selectedWeekId;
                $weekModel = Week::find($weekId);
                $weekName = $weekModel ? $weekModel->name : '';
            } elseif ($weekMode === 'new' && !empty($newWeekName)) {
                $weekModel = Week::create([
                    'course_id' => $courseIdToAssign,
                    'name' => $newWeekName,
                    'due_date' => $newWeekDueDate ?: null,
                ]);
                $weekId = $weekModel->id;
                $weekName = $weekModel->name;
            }

            if ($weekId) {
                $weekNumber = 1;
                if (preg_match('/(\d+)/', $weekName, $matches)) {
                    $weekNumber = (int) $matches[1];
                }

                $mediaFile->courses()->syncWithoutDetaching([
                    $courseIdToAssign => [
                        'week' => $weekNumber,
                        'week_id' => $weekId
                    ]
                ]);

                $mediaFile->courses()->updateExistingPivot($courseIdToAssign, [
                    'week' => $weekNumber,
                    'week_id' => $weekId
                ]);
            }
        }
    }
}

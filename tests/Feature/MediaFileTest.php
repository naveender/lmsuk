<?php

use App\Models\User;
use App\Models\MediaFile;
use App\Models\Subject;
use App\Models\Classes;
use App\Models\YearGroup;
use App\Models\Course;
use App\Models\Week;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guest cannot access media files index', function () {
    $response = $this->get(route('admin.media-files.index'));
    $response->assertRedirect('/login');
});

test('admin can view media files index', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    
    $response = $this->actingAs($admin)
        ->get(route('admin.media-files.index'));

    $response->assertStatus(200);
});

test('admin can link YouTube video with metadata', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    
    $subject = Subject::create(['title' => 'Test Subject', 'is_active' => true]);
    $class = Classes::create(['name' => 'Test Class', 'group_year' => 'Year 1', 'academic_year' => '2024-2025', 'is_active' => true]);
    $yearGroup = YearGroup::create(['title' => 'Year 1', 'value' => 'Year 1', 'is_active' => true]);

    $response = $this->actingAs($admin)
        ->post(route('admin.media-files.store'), [
            'title' => 'Test YouTube Video',
            'description' => 'Test YouTube Description',
            'type' => 'youtube',
            'path' => 'dQw4w9WgXcQ',
            'subject_id' => $subject->id,
            'class_id' => $class->id,
            'year_group_id' => $yearGroup->id,
            'academic_year' => '2024-2025',
            'duration' => '10:30',
            'publication_status' => 'published',
        ]);

    $response->assertRedirect(route('admin.media-files.index'));
    $this->assertDatabaseHas('media_files', [
        'title' => 'Test YouTube Video',
        'type' => 'youtube',
        'subject_id' => $subject->id,
        'class_id' => $class->id,
        'year_group_id' => $yearGroup->id,
        'duration' => '10:30',
        'publication_status' => 'published',
    ]);
});

test('admin can view system configurations', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);

    $response = $this->actingAs($admin)
        ->get(route('admin.system-configs.index'));

    $response->assertStatus(200);
});

test('admin can save configurations', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);

    $response = $this->actingAs($admin)
        ->post(route('admin.system-configs.update'), [
            'smtp_host' => 'smtp.testmail.com',
            'smtp_port' => '587',
            'wasabi_bucket' => 'test-wasabi-bucket',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('aspire_settings', [
        'key' => 'smtp.host',
        'value' => 'smtp.testmail.com',
    ]);
    $this->assertDatabaseHas('aspire_settings', [
        'key' => 'wasabi.bucket',
        'value' => 'test-wasabi-bucket',
    ]);
});

test('admin can upload file in chunks with metadata', function () {
    Storage::fake('public');
    
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $file = UploadedFile::fake()->create('lesson.mp4', 1024); // 1KB mock file
    
    $subject = Subject::create(['title' => 'Test Subject', 'is_active' => true]);
    $class = Classes::create(['name' => 'Test Class', 'group_year' => 'Year 1', 'academic_year' => '2024-2025', 'is_active' => true]);
    $yearGroup = YearGroup::create(['title' => 'Year 1', 'value' => 'Year 1', 'is_active' => true]);

    // Upload first and only chunk (total_chunks = 1)
    $response = $this->actingAs($admin)
        ->post(route('admin.media-files.upload-chunk'), [
            'file' => $file,
            'chunk_index' => 0,
            'total_chunks' => 1,
            'filename' => 'lesson.mp4',
            'upload_id' => 'test_upload_123',
            'storage_target' => 'local',
            'title' => 'Direct Chunk Upload Video',
            'description' => 'Direct Description',
            'subject_id' => $subject->id,
            'class_id' => $class->id,
            'year_group_id' => $yearGroup->id,
            'academic_year' => '2024-2025',
            'duration' => '12:00',
            'publication_status' => 'published',
        ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'status' => 'completed',
    ]);

    $this->assertDatabaseHas('media_files', [
        'title' => 'Direct Chunk Upload Video',
        'original_name' => 'lesson.mp4',
        'storage_disk' => 'public',
        'status' => 'completed',
        'subject_id' => $subject->id,
        'class_id' => $class->id,
        'year_group_id' => $yearGroup->id,
        'duration' => '12:00',
    ]);
});

test('admin can upload thumbnail via AJAX', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg', 640, 360);

    $response = $this->actingAs($admin)
        ->post(route('admin.media-files.upload-thumbnail'), [
            'thumbnail' => $thumbnail
        ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true
    ]);
    
    $path = $response->json('thumbnail_path');
    Storage::disk('public')->assertExists($path);
});

test('admin can assign media to course weekly scheduling', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    
    $media = MediaFile::create([
        'title' => 'Unassigned Video',
        'type' => 'video_url',
        'path' => 'https://test.com/video.mp4',
        'status' => 'completed'
    ]);
    
    $course = Course::create(['name' => 'Math Course', 'is_active' => true]);
    $week = Week::create(['course_id' => $course->id, 'name' => 'Week 1 - Geometry']);

    $response = $this->actingAs($admin)
        ->post(route('admin.courses.media.add', $course->id), [
            'media_file_id' => $media->id,
            'week_mode' => 'existing',
            'week_id' => $week->id
        ]);

    $response->assertRedirect(route('admin.courses.media', $course->id));
    expect($course->mediaFiles()->where('media_files.id', $media->id)->exists())->toBeTrue();
    expect($course->mediaFiles()->first()->pivot->week_id)->toBe($week->id);
});

test('admin can replace existing media file with a new file and delete previous file', function () {
    Storage::fake('public');
    
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    
    // Create dependencies first to satisfy database foreign keys
    $subject = Subject::create(['title' => 'Test Subject', 'is_active' => true]);
    $class = Classes::create(['name' => 'Test Class', 'group_year' => 'Year 1', 'academic_year' => '2024-2025', 'is_active' => true]);
    $yearGroup = YearGroup::create(['title' => 'Year 1', 'value' => 'Year 1', 'is_active' => true]);

    // Create previous media file record & mock file
    $oldPath = 'videos/old_video.mp4';
    Storage::disk('public')->put($oldPath, 'Old Video Content');
    
    $media = MediaFile::create([
        'title' => 'Original Video',
        'type' => 'video_file',
        'path' => $oldPath,
        'storage_disk' => 'public',
        'status' => 'completed',
        'subject_id' => $subject->id,
        'class_id' => $class->id,
        'year_group_id' => $yearGroup->id,
        'academic_year' => '2024-2025',
        'publication_status' => 'published',
    ]);

    $newFile = UploadedFile::fake()->create('new_video.mp4', 512); // 512B new file

    // Upload first and only chunk (total_chunks = 1) for the replacement
    $response = $this->actingAs($admin)
        ->post(route('admin.media-files.upload-chunk'), [
            'file' => $newFile,
            'chunk_index' => 0,
            'total_chunks' => 1,
            'filename' => 'new_video.mp4',
            'upload_id' => 'replace_upload_999',
            'storage_target' => 'local',
            'media_file_id' => $media->id,
            'title' => 'Updated Video Title',
            'description' => 'Updated Description',
            'subject_id' => $subject->id,
            'class_id' => $class->id,
            'year_group_id' => $yearGroup->id,
            'academic_year' => '2024-2025',
            'publication_status' => 'published',
        ]);

    $response->assertStatus(200);
    
    // Check old file was deleted
    Storage::disk('public')->assertMissing($oldPath);
    
    // Check database was updated
    $media->refresh();
    expect($media->title)->toBe('Updated Video Title');
    expect($media->original_name)->toBe('new_video.mp4');
    expect($media->storage_disk)->toBe('public');
    
    // Clean up physically created file on the real filesystem
    $physicalPath = storage_path('app/public/' . $media->path);
    if (\Illuminate\Support\Facades\File::exists($physicalPath)) {
        \Illuminate\Support\Facades\File::delete($physicalPath);
    }
});

test('student can view video lessons category list matching visibility rules', function () {
    $subject = Subject::create(['title' => 'Maths Category', 'is_active' => true]);
    
    $class = Classes::create(['name' => 'Year 5 Advanced', 'group_year' => 'Year 5', 'academic_year' => '2025-2026', 'is_active' => true]);
    $student = User::factory()->create(['role' => 'student', 'username' => 'student_maths']);
    $student->classes()->attach($class->id);
    
    // Create Student Detail with matching group year & academic year
    \App\Models\StudentDetail::create([
        'user_id' => $student->id,
        'group_year' => 'Year 5',
        'academic_year' => '2025-2026',
        'status' => 'active'
    ]);

    $yearGroup = YearGroup::create(['title' => 'Year 5', 'value' => 'Year 5', 'is_active' => true]);

    // Create 1 general video matching student profile details
    $matchingMedia = MediaFile::create([
        'title' => 'Visible General Video',
        'type' => 'video_file',
        'path' => 'videos/vid1.mp4',
        'storage_disk' => 'public',
        'status' => 'completed',
        'subject_id' => $subject->id,
        'class_id' => $class->id,
        'year_group_id' => $yearGroup->id,
        'academic_year' => '2025-2026',
        'publication_status' => 'published',
    ]);

    // Create 1 general video NOT matching student profile (different class)
    $otherClass = Classes::create(['name' => 'Year 6 Advanced', 'group_year' => 'Year 6', 'academic_year' => '2025-2026', 'is_active' => true]);
    $nonMatchingMedia = MediaFile::create([
        'title' => 'Hidden General Video',
        'type' => 'video_file',
        'path' => 'videos/vid2.mp4',
        'storage_disk' => 'public',
        'status' => 'completed',
        'subject_id' => $subject->id,
        'class_id' => $otherClass->id,
        'year_group_id' => $yearGroup->id,
        'academic_year' => '2025-2026',
        'publication_status' => 'published',
    ]);

    $response = $this->actingAs($student)
        ->get(route('student.videolessonscategories'));

    $response->assertStatus(200);
    $response->assertSee('Maths Category');
    
    // Ensure controller counts matching visible videos
    $categoriesData = $response->viewData('progressBySubject');
    expect($categoriesData)->toHaveKey($subject->id);
    expect($categoriesData[$subject->id]['total'])->toBe(1); // Only visible matching video is counted
});

test('student can view video lessons list page for a specific subject', function () {
    $subject = Subject::create(['title' => 'Verbal Category', 'is_active' => true]);
    $student = User::factory()->create(['role' => 'student', 'username' => 'student_verbal']);

    // Create a matching general video
    $media = MediaFile::create([
        'title' => 'Visible Verbal Video',
        'type' => 'video_file',
        'path' => 'videos/verbal.mp4',
        'storage_disk' => 'public',
        'status' => 'completed',
        'subject_id' => $subject->id,
        'publication_status' => 'published',
    ]);

    $response = $this->actingAs($student)
        ->get(route('student.videolessonslist', ['subject_id' => $subject->id]));

    $response->assertStatus(200);
    $response->assertSee('Visible Verbal Video');
    $response->assertSee('Playlist (1 Videos)');
});


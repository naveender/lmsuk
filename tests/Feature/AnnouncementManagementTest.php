<?php

use App\Models\User;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Announcement;
use App\Models\AnnouncementTarget;
use App\Models\AnnouncementView;
use App\Models\AnnouncementAuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can create draft announcement without recipients', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $year = AcademicYear::firstOrCreate(['name' => '2028-2029'], ['is_active' => true]);

    $response = $this->actingAs($admin)
        ->post(route('admin.announcements.store'), [
            'type' => 1,
            'title' => 'Draft Announcement',
            'content' => 'Content here',
            'academic_year_id' => $year->id,
            'priority' => 'medium',
            'is_draft' => '1',
        ]);

    $response->assertRedirect(route('admin.announcements.index'));
    $announcement = Announcement::where('title', 'Draft Announcement')->first();
    expect($announcement)->not->toBeNull();
    expect($announcement->is_draft)->toBeTrue();
    expect(AnnouncementAuditLog::where('announcement_id', $announcement->id)->where('action', 'created')->exists())->toBeTrue();
});

test('admin cannot create active announcement without recipients', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $year = AcademicYear::firstOrCreate(['name' => '2028-2029'], ['is_active' => true]);

    $response = $this->actingAs($admin)
        ->from(route('admin.announcements.create'))
        ->post(route('admin.announcements.store'), [
            'type' => 1,
            'title' => 'Active Announcement',
            'content' => 'Content here',
            'academic_year_id' => $year->id,
            'priority' => 'medium',
        ]);

    $response->assertRedirect(route('admin.announcements.create'));
    $response->assertSessionHasErrors(['recipients']);
});

test('expiry date must be after show from date', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $year = AcademicYear::firstOrCreate(['name' => '2028-2029'], ['is_active' => true]);

    $response = $this->actingAs($admin)
        ->from(route('admin.announcements.create'))
        ->post(route('admin.announcements.store'), [
            'type' => 1,
            'title' => 'Active Announcement',
            'content' => 'Content here',
            'academic_year_id' => $year->id,
            'priority' => 'medium',
            'show_from' => '2026-06-02 12:00:00',
            'expires_at' => '2026-06-02 11:00:00',
            'target_all_tutors' => '1',
        ]);

    $response->assertSessionHasErrors(['expires_at']);
});

test('students only see targeted active announcements', function () {
    $student = User::factory()->create(['role' => 'student', 'username' => 'student_test']);
    $otherStudent = User::factory()->create(['role' => 'student', 'username' => 'other_student_test']);
    $year = AcademicYear::firstOrCreate(['name' => '2028-2029'], ['is_active' => true]);

    // Targeted announcement for student
    $ann1 = Announcement::create([
        'type' => 1,
        'title' => 'Targeted Student Notice',
        'content' => 'Hello student',
        'academic_year_id' => $year->id,
        'priority' => 'high',
        'is_draft' => false,
        'status' => 'active',
    ]);
    $ann1->targets()->create(['target_type' => 'user', 'target_id' => $student->id]);

    // Draft announcement
    $ann2 = Announcement::create([
        'type' => 1,
        'title' => 'Draft Notice',
        'content' => 'Hello student draft',
        'academic_year_id' => $year->id,
        'priority' => 'high',
        'is_draft' => true,
        'status' => 'active',
    ]);
    $ann2->targets()->create(['target_type' => 'all_active_students']);

    // Non-targeted notice
    $ann3 = Announcement::create([
        'type' => 1,
        'title' => 'Other Notice',
        'content' => 'Hello other student',
        'academic_year_id' => $year->id,
        'priority' => 'medium',
        'is_draft' => false,
        'status' => 'active',
    ]);
    $ann3->targets()->create(['target_type' => 'user', 'target_id' => $otherStudent->id]);

    $response = $this->actingAs($student)->get(route('student.announcements'));
    $response->assertStatus(200);
    $response->assertSee('Targeted Student Notice');
    $response->assertDontSee('Draft Notice');
    $response->assertDontSee('Other Notice');
});

test('viewing an announcement logs read status', function () {
    $student = User::factory()->create(['role' => 'student', 'username' => 'student_test']);
    $year = AcademicYear::firstOrCreate(['name' => '2028-2029'], ['is_active' => true]);

    $ann = Announcement::create([
        'type' => 1,
        'title' => 'Notice to view',
        'content' => 'View me',
        'academic_year_id' => $year->id,
        'priority' => 'high',
        'is_draft' => false,
        'status' => 'active',
    ]);
    $ann->targets()->create(['target_type' => 'user', 'target_id' => $student->id]);

    $response = $this->actingAs($student)
        ->post(route('student.announcements.view', $ann->id));

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    expect(AnnouncementView::where('announcement_id', $ann->id)->where('user_id', $student->id)->exists())->toBeTrue();
});

test('admin can retrieve announcement audit logs', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $year = AcademicYear::firstOrCreate(['name' => '2028-2029'], ['is_active' => true]);

    $ann = Announcement::create([
        'type' => 1,
        'title' => 'Audit Test',
        'content' => 'Audit me',
        'academic_year_id' => $year->id,
        'priority' => 'high',
        'is_draft' => false,
        'status' => 'active',
    ]);
    
    AnnouncementAuditLog::create([
        'announcement_id' => $ann->id,
        'user_id' => $admin->id,
        'action' => 'created',
        'details' => 'Test log details',
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.announcements.audit-logs', $ann->id));

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'admin' => $admin->name,
        'action' => 'Created',
        'details' => 'Test log details',
    ]);
});

test('tutors only see targeted active announcements', function () {
    $tutor = User::factory()->create(['role' => 'tutor', 'username' => 'tutor_test']);
    $otherTutor = User::factory()->create(['role' => 'tutor', 'username' => 'other_tutor_test']);
    $year = AcademicYear::firstOrCreate(['name' => '2028-2029'], ['is_active' => true]);

    // Targeted announcement for tutor
    $ann1 = Announcement::create([
        'type' => 1,
        'title' => 'Targeted Tutor Notice',
        'content' => 'Hello tutor',
        'academic_year_id' => $year->id,
        'priority' => 'high',
        'is_draft' => false,
        'status' => 'active',
    ]);
    $ann1->targets()->create(['target_type' => 'user', 'target_id' => $tutor->id]);

    // Draft announcement
    $ann2 = Announcement::create([
        'type' => 1,
        'title' => 'Draft Notice',
        'content' => 'Hello tutor draft',
        'academic_year_id' => $year->id,
        'priority' => 'high',
        'is_draft' => true,
        'status' => 'active',
    ]);
    $ann2->targets()->create(['target_type' => 'all_tutors']);

    // Non-targeted notice
    $ann3 = Announcement::create([
        'type' => 1,
        'title' => 'Other Notice',
        'content' => 'Hello other tutor',
        'academic_year_id' => $year->id,
        'priority' => 'medium',
        'is_draft' => false,
        'status' => 'active',
    ]);
    $ann3->targets()->create(['target_type' => 'user', 'target_id' => $otherTutor->id]);

    $response = $this->actingAs($tutor)->get(route('tutor.announcements'));
    $response->assertStatus(200);
    $response->assertSee('Targeted Tutor Notice');
    $response->assertDontSee('Draft Notice');
    $response->assertDontSee('Other Notice');
});

test('viewing a tutor announcement logs read status', function () {
    $tutor = User::factory()->create(['role' => 'tutor', 'username' => 'tutor_test']);
    $year = AcademicYear::firstOrCreate(['name' => '2028-2029'], ['is_active' => true]);

    $ann = Announcement::create([
        'type' => 1,
        'title' => 'Notice to view',
        'content' => 'View me',
        'academic_year_id' => $year->id,
        'priority' => 'high',
        'is_draft' => false,
        'status' => 'active',
    ]);
    $ann->targets()->create(['target_type' => 'user', 'target_id' => $tutor->id]);

    $response = $this->actingAs($tutor)
        ->post(route('tutor.announcements.view', $ann->id));

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    expect(AnnouncementView::where('announcement_id', $ann->id)->where('user_id', $tutor->id)->exists())->toBeTrue();
});


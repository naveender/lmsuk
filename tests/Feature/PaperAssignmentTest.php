<?php

use App\Models\User;
use App\Models\Paper;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\YearGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can retrieve paper assignment settings', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test1']);
    $paper = Paper::create([
        'title' => 'Test Assignment Paper',
        'type' => 'test',
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.papers.assignments', $paper->id));

    $response->assertStatus(200);
    $response->assertJson([
        'assign_type' => 'none',
        'assign_mode' => 'all',
        'selected_ids' => [],
    ]);
});

test('admin can assign paper to all class students', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test2']);
    $paper = Paper::create([
        'title' => 'Test Paper Classes',
        'type' => 'test',
    ]);

    $response = $this->actingAs($admin)
        ->postJson(route('admin.papers.assign', $paper->id), [
            'assign_type' => 'classes',
            'assign_mode' => 'all',
        ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('paper_assignments', [
        'paper_id' => $paper->id,
        'assign_type' => 'classes',
        'assign_mode' => 'all',
    ]);
});

test('admin can assign paper to specific classes', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test3']);
    $paper = Paper::create([
        'title' => 'Test Paper Specific Classes',
        'type' => 'test',
    ]);

    $class = Classes::create([
        'name' => 'Test Class A',
        'group_year' => 'Year 1',
        'academic_year' => '2026',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->postJson(route('admin.papers.assign', $paper->id), [
            'assign_type' => 'classes',
            'assign_mode' => 'specific',
            'target_ids' => [$class->id],
        ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('paper_assignments', [
        'paper_id' => $paper->id,
        'assign_type' => 'classes',
        'assign_mode' => 'specific',
        'target_id' => $class->id,
    ]);
});

test('paper visibility scope works correctly for class students', function () {
    $paperAll = Paper::create(['title' => 'All Classes Paper', 'type' => 'test']);
    $paperSpecific = Paper::create(['title' => 'Class A Paper', 'type' => 'test']);
    $paperUnassigned = Paper::create(['title' => 'Unassigned Paper', 'type' => 'test']);

    $classA = Classes::create([
        'name' => 'Class A',
        'group_year' => 'Year 1',
        'academic_year' => '2026',
        'is_active' => true,
    ]);
    
    $classB = Classes::create([
        'name' => 'Class B',
        'group_year' => 'Year 1',
        'academic_year' => '2026',
        'is_active' => true,
    ]);

    // Set up assignments
    $paperAll->assignments()->create([
        'assign_type' => 'classes',
        'assign_mode' => 'all',
    ]);

    $paperSpecific->assignments()->create([
        'assign_type' => 'classes',
        'assign_mode' => 'specific',
        'target_id' => $classA->id,
    ]);

    // Student 1 in Class A
    $student1 = User::factory()->create(['role' => 'student', 'username' => 'student1']);
    $classA->students()->attach($student1->id);

    // Student 2 in Class B
    $student2 = User::factory()->create(['role' => 'student', 'username' => 'student2']);
    $classB->students()->attach($student2->id);

    // Student 3 not in any class
    $student3 = User::factory()->create(['role' => 'student', 'username' => 'student3']);

    // Check Student 1 visibility (should see paperAll and paperSpecific, but not paperUnassigned)
    $student1Papers = Paper::visibleTo($student1)->pluck('id')->toArray();
    expect($student1Papers)->toContain($paperAll->id);
    expect($student1Papers)->toContain($paperSpecific->id);
    expect($student1Papers)->not->toContain($paperUnassigned->id);

    // Check Student 2 visibility (should see paperAll, but not paperSpecific or paperUnassigned)
    $student2Papers = Paper::visibleTo($student2)->pluck('id')->toArray();
    expect($student2Papers)->toContain($paperAll->id);
    expect($student2Papers)->not->toContain($paperSpecific->id);
    expect($student2Papers)->not->toContain($paperUnassigned->id);

    // Check Student 3 visibility (should not see any since they have no class)
    $student3Papers = Paper::visibleTo($student3)->pluck('id')->toArray();
    expect($student3Papers)->toBeEmpty();
});

test('paper visibility scope works correctly for sessions', function () {
    $paperAll = Paper::create(['title' => 'All Sessions Paper', 'type' => 'test']);
    $paperSpecific = Paper::create(['title' => 'Session 2026 Paper', 'type' => 'test']);

    $session2026 = AcademicYear::create(['name' => '2026', 'is_active' => true]);
    $session2027 = AcademicYear::create(['name' => '2027', 'is_active' => true]);

    $paperAll->assignments()->create([
        'assign_type' => 'sessions',
        'assign_mode' => 'all',
    ]);

    $paperSpecific->assignments()->create([
        'assign_type' => 'sessions',
        'assign_mode' => 'specific',
        'target_id' => $session2026->id,
    ]);

    $student = User::factory()->create(['role' => 'student', 'username' => 'student_sess']);
    $student->studentDetail()->create([
        'academic_year' => $session2026->id,
    ]);

    $student2 = User::factory()->create(['role' => 'student', 'username' => 'student_sess2']);
    $student2->studentDetail()->create([
        'academic_year' => $session2027->id,
    ]);

    // Student 1 (2026 session) should see both
    $visiblePapers1 = Paper::visibleTo($student)->pluck('id')->toArray();
    expect($visiblePapers1)->toContain($paperAll->id);
    expect($visiblePapers1)->toContain($paperSpecific->id);

    // Student 2 (2027 session) should only see paperAll
    $visiblePapers2 = Paper::visibleTo($student2)->pluck('id')->toArray();
    expect($visiblePapers2)->toContain($paperAll->id);
    expect($visiblePapers2)->not->toContain($paperSpecific->id);
});

test('paper visibility scope works correctly for group years', function () {
    $paperAll = Paper::create(['title' => 'All Group Years Paper', 'type' => 'test']);
    $paperSpecific = Paper::create(['title' => 'Year 1 Paper', 'type' => 'test']);

    $paperAll->assignments()->create([
        'assign_type' => 'group_years',
        'assign_mode' => 'all',
    ]);

    $paperSpecific->assignments()->create([
        'assign_type' => 'group_years',
        'assign_mode' => 'specific',
        'target_value' => 'Year 1',
    ]);

    $student = User::factory()->create(['role' => 'student', 'username' => 'student_grp1']);
    $student->studentDetail()->create([
        'group_year' => 'Year 1',
    ]);

    $student2 = User::factory()->create(['role' => 'student', 'username' => 'student_grp2']);
    $student2->studentDetail()->create([
        'group_year' => 'Year 2',
    ]);

    // Student 1 (Year 1) should see both
    $visiblePapers1 = Paper::visibleTo($student)->pluck('id')->toArray();
    expect($visiblePapers1)->toContain($paperAll->id);
    expect($visiblePapers1)->toContain($paperSpecific->id);

    // Student 2 (Year 2) should only see paperAll
    $visiblePapers2 = Paper::visibleTo($student2)->pluck('id')->toArray();
    expect($visiblePapers2)->toContain($paperAll->id);
    expect($visiblePapers2)->not->toContain($paperSpecific->id);
});

test('paper visibility scope works correctly for specific students', function () {
    $paperAll = Paper::create(['title' => 'All Students Paper', 'type' => 'test']);
    $paperSpecific = Paper::create(['title' => 'Specific Student Paper', 'type' => 'test']);

    $student1 = User::factory()->create(['role' => 'student', 'username' => 'stud1']);
    $student2 = User::factory()->create(['role' => 'student', 'username' => 'stud2']);

    $paperAll->assignments()->create([
        'assign_type' => 'students',
        'assign_mode' => 'all',
    ]);

    $paperSpecific->assignments()->create([
        'assign_type' => 'students',
        'assign_mode' => 'specific',
        'target_id' => $student1->id,
    ]);

    // Student 1 should see both
    $visiblePapers1 = Paper::visibleTo($student1)->pluck('id')->toArray();
    expect($visiblePapers1)->toContain($paperAll->id);
    expect($visiblePapers1)->toContain($paperSpecific->id);

    // Student 2 should only see paperAll
    $visiblePapers2 = Paper::visibleTo($student2)->pluck('id')->toArray();
    expect($visiblePapers2)->toContain($paperAll->id);
    expect($visiblePapers2)->not->toContain($paperSpecific->id);
});

test('admin can assign paper using assign_scope dropdown format', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test4']);
    $paper = Paper::create([
        'title' => 'Test Paper Scope Dropdown',
        'type' => 'test',
    ]);

    $class = Classes::create([
        'name' => 'Class D',
        'group_year' => 'Year 1',
        'academic_year' => '2026',
        'is_active' => true,
    ]);

    // Test assign_scope classes_specific
    $response = $this->actingAs($admin)
        ->postJson(route('admin.papers.assign', $paper->id), [
            'assign_scope' => 'classes_specific',
            'target_ids' => [$class->id],
        ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('paper_assignments', [
        'paper_id' => $paper->id,
        'assign_type' => 'classes',
        'assign_mode' => 'specific',
        'target_id' => $class->id,
    ]);

    // Test assign_scope none
    $response2 = $this->actingAs($admin)
        ->postJson(route('admin.papers.assign', $paper->id), [
            'assign_scope' => 'none',
        ]);

    $response2->assertStatus(200);
    $this->assertDatabaseMissing('paper_assignments', [
        'paper_id' => $paper->id,
    ]);
});

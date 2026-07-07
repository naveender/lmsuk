<?php

use App\Models\User;
use App\Models\Course;
use App\Models\Paper;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view courses index page', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $course = Course::create([
        'name' => 'Sample Prep Course',
        'description' => 'A test course description.',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.courses.index'));

    $response->assertStatus(200);
    $response->assertSee('Sample Prep Course');
});

test('admin can create a new course', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);

    $response = $this->actingAs($admin)
        ->post(route('admin.courses.store'), [
            'name' => 'New Course',
            'description' => 'Description here',
            'is_active' => '1',
        ]);

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('courses', [
        'name' => 'New Course',
        'description' => 'Description here',
        'is_active' => true,
    ]);
});

test('admin can edit an existing course', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $course = Course::create([
        'name' => 'Old Course Name',
        'description' => 'Old description',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->put(route('admin.courses.update', $course->id), [
            'name' => 'Updated Course Name',
            'description' => 'Updated description',
            'is_active' => '1',
        ]);

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'name' => 'Updated Course Name',
        'description' => 'Updated description',
        'is_active' => true,
    ]);
});

test('admin can delete a course', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $course = Course::create([
        'name' => 'Course to Delete',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->delete(route('admin.courses.destroy', $course->id));

    $response->assertRedirect(route('admin.courses.index'));
    $response->assertSessionHas('success');
    
    $this->assertDatabaseMissing('courses', [
        'id' => $course->id,
    ]);
});

test('admin can view manage papers page', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $course = Course::create([
        'name' => 'Course for Papers',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.courses.papers', $course->id));

    $response->assertStatus(200);
    $response->assertSee('Course for Papers');
});

test('admin can assign a paper to a course with a week number', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $course = Course::create([
        'name' => 'Weekly Course',
        'is_active' => true,
    ]);
    
    $paper = Paper::create([
        'title' => 'Weekly Math Practice 1',
        'type' => 'test',
    ]);

    $response = $this->actingAs($admin)
        ->post(route('admin.courses.papers.add', $course->id), [
            'paper_id' => $paper->id,
            'week' => 3,
        ]);

    $response->assertRedirect(route('admin.courses.papers', $course->id));
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('course_paper', [
        'course_id' => $course->id,
        'paper_id' => $paper->id,
        'week' => 3,
    ]);
});

test('admin can remove a paper from a course', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $course = Course::create([
        'name' => 'Course with Paper',
        'is_active' => true,
    ]);
    
    $paper = Paper::create([
        'title' => 'Weekly Verbal Reasoning',
        'type' => 'test',
    ]);

    $course->papers()->attach($paper->id, ['week' => 2]);

    $response = $this->actingAs($admin)
        ->delete(route('admin.courses.papers.remove', [$course->id, $paper->id]));

    $response->assertRedirect(route('admin.courses.papers', $course->id));
    $response->assertSessionHas('success');
    
    $this->assertDatabaseMissing('course_paper', [
        'course_id' => $course->id,
        'paper_id' => $paper->id,
    ]);
});

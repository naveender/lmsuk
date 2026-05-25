<?php

use App\Models\User;
use App\Models\Classes;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view class students page', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $class = Classes::create([
        'name' => 'Test Class A',
        'group_year' => 'Year 1',
        'academic_year' => '2026',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->get(route('admin.classes.students', $class->id));

    $response->assertStatus(200);
    $response->assertSee('Test Class A');
});

test('admin can add student to class', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $class = Classes::create([
        'name' => 'Test Class B',
        'group_year' => 'Year 1',
        'academic_year' => '2026',
        'is_active' => true,
    ]);
    
    $student = User::factory()->create(['role' => 'student', 'username' => 'student_test']);

    $response = $this->actingAs($admin)
        ->post(route('admin.classes.students.add', $class->id), [
            'student_ids' => [$student->id],
        ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
    expect($class->students()->where('users.id', $student->id)->exists())->toBeTrue();
});

test('admin can remove student from class', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_test']);
    $class = Classes::create([
        'name' => 'Test Class C',
        'group_year' => 'Year 1',
        'academic_year' => '2026',
        'is_active' => true,
    ]);
    
    $student = User::factory()->create(['role' => 'student', 'username' => 'student_test']);
    $class->students()->attach($student->id);

    $response = $this->actingAs($admin)
        ->post(route('admin.classes.students.remove', [$class->id, $student->id]));

    $response->assertRedirect();
    $response->assertSessionHas('success');
    expect($class->refresh()->students()->where('users.id', $student->id)->exists())->toBeFalse();
});

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

test('admin can assign class when creating student', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_create_test']);
    $class = Classes::create([
        'name' => 'Year 5 Alpha',
        'group_year' => 'Year 5',
        'academic_year' => '2026-2027',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)
        ->post(route('admin.students.store'), [
            'name' => 'Alice Smith',
            'username' => 'alice_smith',
            'email' => 'alice@test.com',
            'password' => 'password123',
            'date_of_birth' => '2015-05-10',
            'group_year' => 'Year 5',
            'academic_year' => '2026-2027',
            'class_id' => $class->id,
            'region' => 'London',
            'student_phone' => '07123456789',
            'gender' => 'female',
            'parent_mode' => 'new',
            'parent_name' => 'John Smith',
            'parent_username' => 'john_smith',
            'parent_email' => 'john@test.com',
            'parent_password' => 'password123',
            'parent_phone' => '07987654321',
            'parent_relation' => 'Father',
        ]);

    $response->assertRedirect(route('admin.students.index'));
    $response->assertSessionHas('success');

    $student = User::where('email', 'alice@test.com')->first();
    expect($student)->not->toBeNull();
    expect($student->classes()->where('classes.id', $class->id)->exists())->toBeTrue();
});

test('admin can assign and update class when updating student', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_edit_test']);
    $parent = User::factory()->create(['role' => 'parent', 'username' => 'parent_test']);
    
    $classA = Classes::create([
        'name' => 'Class A',
        'group_year' => 'Year 5',
        'academic_year' => '2026-2027',
        'is_active' => true,
    ]);
    $classB = Classes::create([
        'name' => 'Class B',
        'group_year' => 'Year 5',
        'academic_year' => '2026-2027',
        'is_active' => true,
    ]);

    $student = User::factory()->create(['role' => 'student', 'username' => 'bob_test', 'email' => 'bob@test.com']);
    $student->studentDetail()->create([
        'parent_id' => $parent->id,
        'date_of_birth' => '2015-01-01',
        'group_year' => 'Year 5',
        'academic_year' => '2026-2027',
        'region' => 'Manchester',
        'gender' => 'male',
    ]);

    // Update to assign class A
    $response = $this->actingAs($admin)
        ->put(route('admin.students.update', $student->id), [
            'name' => 'Bob Updated',
            'username' => 'bob_test',
            'email' => 'bob@test.com',
            'parent_id' => $parent->id,
            'date_of_birth' => '2015-01-01',
            'group_year' => 'Year 5',
            'academic_year' => '2026-2027',
            'class_id' => $classA->id,
            'region' => 'Manchester',
            'gender' => 'male',
        ]);

    $response->assertRedirect(route('admin.students.index'));
    expect($student->refresh()->classes()->where('classes.id', $classA->id)->exists())->toBeTrue();

    // Update to change from class A to class B
    $response2 = $this->actingAs($admin)
        ->put(route('admin.students.update', $student->id), [
            'name' => 'Bob Updated',
            'username' => 'bob_test',
            'email' => 'bob@test.com',
            'parent_id' => $parent->id,
            'date_of_birth' => '2015-01-01',
            'group_year' => 'Year 5',
            'academic_year' => '2026-2027',
            'class_id' => $classB->id,
            'region' => 'Manchester',
            'gender' => 'male',
        ]);

    $response2->assertRedirect(route('admin.students.index'));
    expect($student->refresh()->classes()->where('classes.id', $classB->id)->exists())->toBeTrue();
    expect($student->classes()->where('classes.id', $classA->id)->exists())->toBeFalse();

    // Update to unassign class
    $response3 = $this->actingAs($admin)
        ->put(route('admin.students.update', $student->id), [
            'name' => 'Bob Updated',
            'username' => 'bob_test',
            'email' => 'bob@test.com',
            'parent_id' => $parent->id,
            'date_of_birth' => '2015-01-01',
            'group_year' => 'Year 5',
            'academic_year' => '2026-2027',
            'class_id' => '',
            'region' => 'Manchester',
            'gender' => 'male',
        ]);

    $response3->assertRedirect(route('admin.students.index'));
    expect($student->refresh()->classes()->count())->toBe(0);
});

test('class appears in student views', function () {
    $admin = User::factory()->create(['role' => 'admin', 'username' => 'admin_view_test']);
    $class = Classes::create([
        'name' => 'Unique Class 9Z',
        'group_year' => 'Year 9',
        'academic_year' => '2026-2027',
        'is_active' => true,
    ]);

    $parent = User::factory()->create(['role' => 'parent', 'username' => 'parent_view_test']);
    $student = User::factory()->create(['role' => 'student', 'username' => 'view_student', 'name' => 'View Student']);
    $student->studentDetail()->create([
        'parent_id' => $parent->id,
        'date_of_birth' => '2012-01-01',
        'group_year' => 'Year 9',
        'academic_year' => '2026-2027',
        'region' => 'London',
        'gender' => 'male',
    ]);
    $student->classes()->attach($class->id);

    // Check create page
    $this->actingAs($admin)->get(route('admin.students.create'))
        ->assertStatus(200)
        ->assertSee('Assign to Class')
        ->assertSee('Unique Class 9Z');

    // Check edit page
    $this->actingAs($admin)->get(route('admin.students.edit', $student->id))
        ->assertStatus(200)
        ->assertSee('Assign to Class')
        ->assertSee('Unique Class 9Z');

    // Check index page
    $this->actingAs($admin)->get(route('admin.students.index'))
        ->assertStatus(200)
        ->assertSee('Unique Class 9Z');
});

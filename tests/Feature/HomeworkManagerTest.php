<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Classes;
use App\Models\YearGroup;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Week;
use App\Models\Homework;
use App\Livewire\Admin\HomeworkForm;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HomeworkManagerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $subject;
    protected $topic;
    protected $subtopic;
    protected $class;
    protected $yearGroup;
    protected $academicYear;
    protected $course;
    protected $week;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_hw_test',
        ]);

        $this->subject = Subject::create([
            'title' => 'Mathematics',
            'is_active' => true,
        ]);

        $this->topic = Topic::create([
            'name' => 'Algebra',
            'slug' => 'algebra',
            'subject_id' => $this->subject->id,
            'is_active' => true,
        ]);

        $this->subtopic = Topic::create([
            'name' => 'Linear Equations',
            'slug' => 'linear-equations',
            'subject_id' => $this->subject->id,
            'parent' => $this->topic->id,
            'is_active' => true,
        ]);

        $this->class = Classes::create([
            'name' => 'Class 10A',
            'group_year' => 'Year 10',
            'academic_year' => '2026-2027',
            'is_active' => true,
        ]);

        $this->yearGroup = YearGroup::create([
            'title' => 'Year 10',
            'value' => '10',
            'is_active' => true,
        ]);

        $this->academicYear = AcademicYear::firstOrCreate(['name' => '2026-2027'], ['is_active' => true]);

        $this->course = Course::create([
            'name' => 'GCSE Mathematics',
            'is_active' => true,
        ]);

        $this->week = Week::create([
            'course_id' => $this->course->id,
            'name' => 'Week 1',
            'due_date' => '2026-10-15',
        ]);
    }

    public function test_admin_can_access_homeworks_directory_and_create_pages()
    {
        $this->actingAs($this->admin);

        $responseIndex = $this->get(route('admin.homeworks.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Homeworks Directory');

        $responseCreate = $this->get(route('admin.homeworks.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Create Homework');
    }

    public function test_admin_can_create_homework_with_file_and_existing_course_assignment()
    {
        $this->actingAs($this->admin);

        $fakeFile = UploadedFile::fake()->create('algebra_homework.pdf', 500, 'application/pdf');

        Livewire::test(HomeworkForm::class)
            ->set('title', 'Weekly Algebra Assignment 1')
            ->set('description', '<p>Complete questions 1 through 10.</p>')
            ->set('subject_id', $this->subject->id)
            ->set('topic_id', $this->topic->id)
            ->set('subtopic_id', $this->subtopic->id)
            ->set('class_id', $this->class->id)
            ->set('year_group_id', $this->yearGroup->id)
            ->set('academic_year', $this->academicYear->name)
            ->set('user_id', $this->admin->id)
            ->set('course_id', $this->course->id)
            ->set('week_mode', 'existing')
            ->set('selected_week_id', $this->week->id)
            ->set('file', $fakeFile)
            ->call('save')
            ->assertRedirect(route('admin.homeworks.index'));

        $this->assertDatabaseHas('homeworks', [
            'title' => 'Weekly Algebra Assignment 1',
            'subject_id' => $this->subject->id,
            'file_name' => 'algebra_homework.pdf',
            'file_type' => 'pdf',
        ]);

        $homework = Homework::where('title', 'Weekly Algebra Assignment 1')->first();
        $this->assertNotNull($homework);
        $this->assertNotNull($homework->file_path);
        Storage::disk('public')->assertExists($homework->file_path);

        // Verify course pivot
        $this->assertTrue($homework->courses()->where('courses.id', $this->course->id)->exists());
        $this->assertEquals($this->week->id, $homework->courses->first()->pivot->week_id);
    }

    public function test_admin_can_create_homework_with_instant_course_and_week_creation()
    {
        $this->actingAs($this->admin);

        $fakeDocx = UploadedFile::fake()->create('geometry_assignment.docx', 800, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        Livewire::test(HomeworkForm::class)
            ->set('title', 'Instant Course Homework')
            ->set('subject_id', $this->subject->id)
            ->set('class_id', $this->class->id)
            ->set('year_group_id', $this->yearGroup->id)
            ->set('academic_year', $this->academicYear->name)
            ->set('user_id', $this->admin->id)
            ->set('create_new_course', true)
            ->set('new_course_name', 'Advanced Calculus')
            ->set('week_mode', 'new')
            ->set('new_week_name', 'Week 4 - Limits & Derivatives')
            ->set('new_week_due_date', '2026-11-20')
            ->set('file', $fakeDocx)
            ->call('save')
            ->assertRedirect(route('admin.homeworks.index'));

        $newCourse = Course::where('name', 'Advanced Calculus')->first();
        $this->assertNotNull($newCourse);

        $newWeek = Week::where('course_id', $newCourse->id)->first();
        $this->assertNotNull($newWeek);
        $this->assertEquals('Week 4 - Limits & Derivatives', $newWeek->name);

        $homework = Homework::where('title', 'Instant Course Homework')->first();
        $this->assertNotNull($homework);
        $this->assertTrue($homework->courses()->where('courses.id', $newCourse->id)->exists());
    }

    public function test_admin_can_download_attached_homework_file()
    {
        $this->actingAs($this->admin);

        $storedPath = Storage::disk('public')->put('homeworks/test_doc.txt', 'Homework instructions content');

        $homework = Homework::create([
            'title' => 'Reading Homework',
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id,
            'year_group_id' => $this->yearGroup->id,
            'academic_year' => $this->academicYear->name,
            'user_id' => $this->admin->id,
            'file_path' => 'homeworks/test_doc.txt',
            'file_name' => 'test_doc.txt',
            'file_type' => 'txt',
            'file_size' => '32 B',
        ]);

        $response = $this->get(route('admin.homeworks.download', $homework->id));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=test_doc.txt');
    }

    public function test_admin_can_delete_homework_and_clean_up_files()
    {
        $this->actingAs($this->admin);

        Storage::disk('public')->put('homeworks/to_delete.pdf', 'file content');

        $homework = Homework::create([
            'title' => 'Homework to delete',
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id,
            'year_group_id' => $this->yearGroup->id,
            'academic_year' => $this->academicYear->name,
            'user_id' => $this->admin->id,
            'file_path' => 'homeworks/to_delete.pdf',
            'file_name' => 'to_delete.pdf',
            'file_type' => 'pdf',
        ]);

        $response = $this->delete(route('admin.homeworks.destroy', $homework->id));
        $response->assertRedirect(route('admin.homeworks.index'));

        $this->assertSoftDeleted('homeworks', ['id' => $homework->id]);
        Storage::disk('public')->assertMissing('homeworks/to_delete.pdf');
    }

    public function test_cascading_subject_topic_and_subtopic_in_homework_form()
    {
        $this->actingAs($this->admin);

        $component = Livewire::test(HomeworkForm::class);

        // Initially topics and subtopics are empty
        $this->assertEmpty($component->get('topics'));
        $this->assertEmpty($component->get('subtopics'));

        // When subject_id is set
        $component->set('subject_id', $this->subject->id);
        $topics = $component->get('topics');
        $this->assertNotEmpty($topics);
        $this->assertEquals('Algebra', $topics[0]['name']);

        // When topic_id is set
        $component->set('topic_id', $this->topic->id);
        $subtopics = $component->get('subtopics');
        $this->assertNotEmpty($subtopics);
        $this->assertEquals('Linear Equations', $subtopics[0]['name']);

        // When subject_id changes, topic_id and subtopics reset
        $component->set('subject_id', '');
        $this->assertEmpty($component->get('topics'));
        $this->assertEmpty($component->get('subtopics'));
        $this->assertEquals('', $component->get('topic_id'));
        $this->assertEquals('', $component->get('subtopic_id'));
    }

    public function test_student_can_view_assigned_homework_in_weekly_tests_section_3()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'username' => 'student_' . uniqid(),
            'email_verified_at' => now(),
        ]);

        $student->studentDetail()->create([
            'group_year' => $this->yearGroup->value,
            'academic_year' => $this->academicYear->name,
            'date_of_birth' => '2015-01-01',
        ]);
        $student->classes()->attach($this->class->id);

        $homework = Homework::create([
            'title' => 'Weekly Algebra Homework Practice',
            'description' => 'Complete problems 1 through 10 in attached worksheet.',
            'subject_id' => $this->subject->id,
            'topic_id' => $this->topic->id,
            'subtopic_id' => $this->subtopic->id,
            'class_id' => $this->class->id,
            'year_group_id' => $this->yearGroup->id,
            'academic_year' => $this->academicYear->name,
            'user_id' => $this->admin->id,
            'file_path' => 'homeworks/algebra_wk1.pdf',
            'file_name' => 'algebra_wk1.pdf',
            'file_type' => 'pdf',
            'file_size' => 10240,
            'is_active' => true,
        ]);

        $homework->courses()->attach($this->course->id, [
            'week_id' => $this->week->id,
            'week' => $this->week->name,
        ]);

        $response = $this->actingAs($student)->get(route('student.weeklytests', [
            'course_id' => $this->course->id,
            'week' => $this->week->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('3. HOMEWORK TASKS');
        $response->assertSee('Weekly Algebra Homework Practice');
        $response->assertSee('Complete problems 1 through 10');
        $response->assertSee(route('student.homeworks.download', $homework->id));
    }

    public function test_student_can_download_attached_homework_file()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'username' => 'student_dl_' . uniqid(),
            'email_verified_at' => now(),
        ]);
        $student->classes()->attach($this->class->id);

        Storage::disk('public')->put('homeworks/math_assignment.docx', 'Assignment docx binary data');

        $homework = Homework::create([
            'title' => 'Math Assignment Sheet',
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id,
            'year_group_id' => $this->yearGroup->id,
            'academic_year' => $this->academicYear->name,
            'user_id' => $this->admin->id,
            'file_path' => 'homeworks/math_assignment.docx',
            'file_name' => 'math_assignment.docx',
            'file_type' => 'docx',
            'file_size' => 2048,
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->get(route('student.homeworks.download', $homework->id));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=math_assignment.docx');
    }

    public function test_student_from_different_class_cannot_view_or_download_homework()
    {
        $differentClass = Classes::create([
            'name' => 'Different Class B',
            'group_year' => $this->yearGroup->value,
            'academic_year' => $this->academicYear->name,
            'is_active' => true,
        ]);

        $otherStudent = User::factory()->create([
            'role' => 'student',
            'username' => 'other_student_' . uniqid(),
            'email_verified_at' => now(),
        ]);
        $otherStudent->classes()->attach($differentClass->id);

        $homework = Homework::create([
            'title' => 'Class A Exclusive Homework',
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id,
            'year_group_id' => $this->yearGroup->id,
            'academic_year' => $this->academicYear->name,
            'user_id' => $this->admin->id,
            'file_path' => 'homeworks/class_a.pdf',
            'file_name' => 'class_a.pdf',
            'is_active' => true,
        ]);
        $homework->courses()->attach($this->course->id, [
            'week_id' => $this->week->id,
            'week' => $this->week->name,
        ]);

        // Student in different class should NOT see it on weekly tests
        $response = $this->actingAs($otherStudent)->get(route('student.weeklytests', [
            'course_id' => $this->course->id,
            'week' => $this->week->id,
        ]));
        $response->assertStatus(200);
        $response->assertDontSee('Class A Exclusive Homework');

        // And downloading directly should be forbidden (403)
        $downloadResponse = $this->actingAs($otherStudent)->get(route('student.homeworks.download', $homework->id));
        $downloadResponse->assertStatus(403);
    }

    public function test_student_cannot_view_or_download_inactive_homework()
    {
        $student = User::factory()->create([
            'role' => 'student',
            'username' => 'inactive_tester_' . uniqid(),
            'email_verified_at' => now(),
        ]);
        $student->classes()->attach($this->class->id);

        $homework = Homework::create([
            'title' => 'Draft Inactive Homework',
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id,
            'year_group_id' => $this->yearGroup->id,
            'academic_year' => $this->academicYear->name,
            'user_id' => $this->admin->id,
            'file_path' => 'homeworks/draft.pdf',
            'file_name' => 'draft.pdf',
            'is_active' => false, // INACTIVE
        ]);
        $homework->courses()->attach($this->course->id, [
            'week_id' => $this->week->id,
            'week' => $this->week->name,
        ]);

        // Student should NOT see inactive homework
        $response = $this->actingAs($student)->get(route('student.weeklytests', [
            'course_id' => $this->course->id,
            'week' => $this->week->id,
        ]));
        $response->assertStatus(200);
        $response->assertDontSee('Draft Inactive Homework');

        // And download should redirect back with error
        $downloadResponse = $this->actingAs($student)->get(route('student.homeworks.download', $homework->id));
        $downloadResponse->assertRedirect();
        $downloadResponse->assertSessionHas('error');
    }

    public function test_admin_can_toggle_homework_active_status()
    {
        $this->actingAs($this->admin);

        $homework = Homework::create([
            'title' => 'Status Toggle Homework',
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id,
            'year_group_id' => $this->yearGroup->id,
            'academic_year' => $this->academicYear->name,
            'user_id' => $this->admin->id,
            'is_active' => true,
        ]);

        // Toggle to inactive
        $response = $this->patch(route('admin.homeworks.toggle-status', $homework->id));
        $response->assertRedirect();
        $this->assertFalse((bool)$homework->fresh()->is_active);

        // Toggle back to active
        $response2 = $this->patch(route('admin.homeworks.toggle-status', $homework->id));
        $response2->assertRedirect();
        $this->assertTrue((bool)$homework->fresh()->is_active);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Paper;
use App\Models\PaperAssignment;
use App\Models\User;
use Illuminate\Support\Str;

class AssessmentTestSeeder extends Seeder
{
    public function run()
    {
        // 1. Get or create a student user for testing
        $student = User::where('role', 'student')->first();
        if (!$student) {
            $student = User::create([
                'name' => 'Demo Student',
                'username' => 'student',
                'email' => 'student@test.com',
                'role' => 'student',
                'password' => bcrypt('password'),
            ]);
        }

        // 2. Get or create tutor/admin user to author papers
        $author = User::whereIn('role', ['admin', 'tutor'])->first();
        if (!$author) {
            $author = User::create([
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@test.com',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]);
        }

        // 3. Create or get Subject (Maths)
        $subject = Subject::firstOrCreate(
            ['title' => 'Mathematics'],
            [
                'description' => 'Practice and tests for mathematical logic and calculations.',
                'is_active' => true
            ]
        );

        // 4. Create Topic (Algebra)
        $topic = Topic::firstOrCreate(
            ['slug' => 'algebra'],
            [
                'name' => 'Algebra',
                'subject_id' => $subject->id,
                'code' => 'ALG-101',
                'parent' => null,
            ]
        );

        // 5. Create Subtopic (Linear Equations)
        $subtopic = Topic::firstOrCreate(
            ['slug' => 'linear-equations'],
            [
                'name' => 'Linear Equations',
                'parent' => $topic->id,
                'subject_id' => $subject->id,
                'code' => 'ALG-LE',
            ]
        );

        // 6. Create Questions
        // Question 1: Single Choice (Radio)
        $q1 = Question::create([
            'title' => 'Solve for x',
            'description' => '<p>Solve the following linear equation: <strong>2x + 5 = 15</strong>. What is the value of <strong>x</strong>?</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $subject->id,
            'topic_id' => $topic->id,
            'subtopic_id' => $subtopic->id,
            'difficulty' => 'easy',
            'marks' => 2,
            'explanation' => '<p>To solve: Subtract 5 from both sides: 2x = 10. Divide by 2: x = 5.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create([
            'question_id' => $q1->id,
            'option_text' => '5',
            'is_correct' => true,
            'sort_order' => 1,
        ]);
        QuestionOption::create([
            'question_id' => $q1->id,
            'option_text' => '10',
            'is_correct' => false,
            'sort_order' => 2,
        ]);
        QuestionOption::create([
            'question_id' => $q1->id,
            'option_text' => '2',
            'is_correct' => false,
            'sort_order' => 3,
        ]);

        // Question 2: Multiple Choice
        $q2 = Question::create([
            'title' => 'Prime Numbers Identification',
            'description' => '<p>Select all of the <strong>prime numbers</strong> from the choices below:</p>',
            'type' => 'multiple_choice',
            'subject_id' => $subject->id,
            'topic_id' => $topic->id,
            'subtopic_id' => $subtopic->id,
            'difficulty' => 'medium',
            'marks' => 3,
            'explanation' => '<p>2 and 3 are prime numbers because they have only two distinct positive divisors: 1 and themselves. 4 and 9 are composite.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create([
            'question_id' => $q2->id,
            'option_text' => '2',
            'is_correct' => true,
            'sort_order' => 1,
        ]);
        QuestionOption::create([
            'question_id' => $q2->id,
            'option_text' => '3',
            'is_correct' => true,
            'sort_order' => 2,
        ]);
        QuestionOption::create([
            'question_id' => $q2->id,
            'option_text' => '4',
            'is_correct' => false,
            'sort_order' => 3,
        ]);
        QuestionOption::create([
            'question_id' => $q2->id,
            'option_text' => '9',
            'is_correct' => false,
            'sort_order' => 4,
        ]);

        // Question 3: Fill in the Blanks
        $q3 = Question::create([
            'title' => 'Angles in a Triangle',
            'description' => '<p>The sum of all interior angles in any Euclidean triangle is <strong>______</strong> degrees.</p>',
            'type' => 'fill_in_the_blanks',
            'subject_id' => $subject->id,
            'topic_id' => $topic->id,
            'subtopic_id' => $subtopic->id,
            'difficulty' => 'medium',
            'marks' => 2,
            'explanation' => '<p>The sum of interior angles in a triangle is always 180 degrees.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create([
            'question_id' => $q3->id,
            'option_text' => '180',
            'is_correct' => true,
            'sort_order' => 1,
        ]);

        // 7. Create Paper
        $paper = Paper::create([
            'type' => 'test',
            'title' => 'Algebra Core Assessment',
            'instruction' => '<p>Answer all questions carefully. You can pause and resume this test anytime.</p>',
            'subject_id' => $subject->id,
            'topic_id' => $topic->id,
            'subtopic_id' => $subtopic->id,
            'difficulty' => 'easy',
            'total_time' => 15, // 15 minutes
            'default_marks' => 2,
            'user_id' => $author->id,
            'academic_year' => '2026-2027',
        ]);

        // 8. Attach Questions to Paper
        $paper->questions()->sync([
            $q1->id => ['sort_order' => 1, 'marks' => 2],
            $q2->id => ['sort_order' => 2, 'marks' => 3],
            $q3->id => ['sort_order' => 3, 'marks' => 2],
        ]);

        // 9. Assign Paper to all students
        PaperAssignment::create([
            'paper_id' => $paper->id,
            'assign_type' => 'students',
            'assign_mode' => 'all',
        ]);

        $this->command->info("Assessment test data seeded successfully!");
    }
}

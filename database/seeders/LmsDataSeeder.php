<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\StudentDetail;
use App\Models\ParentDetail;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\YearGroup;
use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Announcement;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Paper;
use App\Models\PaperAssignment;

class LmsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. Disable foreign key checks and truncate tables
        $this->command->info("Truncating existing tables...");
        Schema::disableForeignKeyConstraints();
        
        QuestionOption::truncate();
        Question::truncate();
        Topic::truncate();
        Subject::truncate();
        YearGroup::truncate();
        AcademicYear::truncate();
        Classes::truncate();
        Announcement::truncate();
        Paper::truncate();
        PaperAssignment::truncate();
        StudentDetail::truncate();
        ParentDetail::truncate();
        User::truncate();
        
        Schema::enableForeignKeyConstraints();

        // 1. Create Users for roles admin, student, parent, tutor: Each 1 user only
        $this->command->info("Seeding users...");

        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $tutor = User::create([
            'name' => 'Tutor User',
            'username' => 'tutor',
            'email' => 'tutor@test.com',
            'role' => 'tutor',
            'password' => bcrypt('password'),
        ]);

        $parent = User::create([
            'name' => 'Parent User',
            'username' => 'parent',
            'email' => 'parent@test.com',
            'role' => 'parent',
            'password' => bcrypt('password'),
        ]);

        ParentDetail::create([
            'user_id' => $parent->id,
            'phone' => '07123456789',
            'address' => '123 Parent Way, London',
            'relation' => 'Father',
        ]);

        $student = User::create([
            'name' => 'Student User',
            'username' => 'student',
            'email' => 'student@test.com',
            'role' => 'student',
            'password' => bcrypt('password'),
        ]);

        StudentDetail::create([
            'user_id' => $student->id,
            'parent_id' => $parent->id,
            'date_of_birth' => '2015-05-15',
            'group_year' => 'Year 5',
            'academic_year' => '2026-2027',
            'region' => 'London',
            'student_phone' => '07987654321',
            'gender' => 'Male',
        ]);

        // 2. Subjects Keep 3 Subject only: Maths & Number Reasoning, English, Verbal Reasoning
        $this->command->info("Seeding subjects...");

        $maths = Subject::create([
            'title' => 'Maths & Number Reasoning',
            'description' => 'Mathematics and numerical logic, problem solving, and analytical thinking.',
            'is_active' => true,
        ]);

        $english = Subject::create([
            'title' => 'English',
            'description' => 'English language study, comprehension, vocabulary, and grammar.',
            'is_active' => true,
        ]);

        $verbal = Subject::create([
            'title' => 'Verbal Reasoning',
            'description' => 'Verbal logic, word relationships, sequence completion, and puzzle solving.',
            'is_active' => true,
        ]);

        // 3. Topic Add 5 records and Subtopic - Add 5 records
        $this->command->info("Seeding topics and subtopics...");

        $topic1 = Topic::create([
            'subject_id' => $maths->id,
            'code' => 'MATH-NP',
            'name' => 'Numbers & Place Value',
            'slug' => 'numbers-and-place-value',
            'parent' => null,
        ]);

        $topic2 = Topic::create([
            'subject_id' => $english->id,
            'code' => 'ENG-RC',
            'name' => 'Reading Comprehension',
            'slug' => 'reading-comprehension',
            'parent' => null,
        ]);

        $topic3 = Topic::create([
            'subject_id' => $english->id,
            'code' => 'ENG-VS',
            'name' => 'Vocabulary & Synonyms',
            'slug' => 'vocabulary-and-synonyms',
            'parent' => null,
        ]);

        $topic4 = Topic::create([
            'subject_id' => $verbal->id,
            'code' => 'VR-AN',
            'name' => 'Analogies',
            'slug' => 'analogies',
            'parent' => null,
        ]);

        $topic5 = Topic::create([
            'subject_id' => $verbal->id,
            'code' => 'VR-CB',
            'name' => 'Code Breaking',
            'slug' => 'code-breaking',
            'parent' => null,
        ]);

        // 5 Subtopic records
        $subtopic1 = Topic::create([
            'subject_id' => $maths->id,
            'code' => 'MATH-NP-DF',
            'name' => 'Decimals & Fractions',
            'slug' => 'decimals-and-fractions',
            'parent' => $topic1->id,
        ]);

        $subtopic2 = Topic::create([
            'subject_id' => $english->id,
            'code' => 'ENG-RC-ID',
            'name' => 'Inference & Deduction',
            'slug' => 'inference-and-deduction',
            'parent' => $topic2->id,
        ]);

        $subtopic3 = Topic::create([
            'subject_id' => $english->id,
            'code' => 'ENG-VS-SA',
            'name' => 'Synonyms & Antonyms',
            'slug' => 'synonyms-and-antonyms',
            'parent' => $topic3->id,
        ]);

        $subtopic4 = Topic::create([
            'subject_id' => $verbal->id,
            'code' => 'VR-AN-WR',
            'name' => 'Word Relationships',
            'slug' => 'word-relationships',
            'parent' => $topic4->id,
        ]);

        $subtopic5 = Topic::create([
            'subject_id' => $verbal->id,
            'code' => 'VR-CB-NC',
            'name' => 'Letter-Number Codes',
            'slug' => 'letter-number-codes',
            'parent' => $topic5->id,
        ]);

        // 4. Year Group: Add 5 records
        $this->command->info("Seeding year groups...");
        $yg3 = YearGroup::create(['title' => 'Year 3', 'value' => 'Year 3', 'description' => 'Year Group 3', 'is_active' => true]);
        $yg4 = YearGroup::create(['title' => 'Year 4', 'value' => 'Year 4', 'description' => 'Year Group 4', 'is_active' => true]);
        $yg5 = YearGroup::create(['title' => 'Year 5', 'value' => 'Year 5', 'description' => 'Year Group 5', 'is_active' => true]);
        $yg6 = YearGroup::create(['title' => 'Year 6', 'value' => 'Year 6', 'description' => 'Year Group 6', 'is_active' => true]);
        $yg7 = YearGroup::create(['title' => 'Year 7', 'value' => 'Year 7', 'description' => 'Year Group 7', 'is_active' => true]);

        // 5. Sessions/Academic Year: Add 5 records
        $this->command->info("Seeding sessions/academic years...");
        $ay1 = AcademicYear::create(['name' => '2023-2024', 'description' => 'Academic Year 2023-2024', 'is_active' => true]);
        $ay2 = AcademicYear::create(['name' => '2024-2025', 'description' => 'Academic Year 2024-2025', 'is_active' => true]);
        $ay3 = AcademicYear::create(['name' => '2025-2026', 'description' => 'Academic Year 2025-2026', 'is_active' => true]);
        $ay4 = AcademicYear::create(['name' => '2026-2027', 'description' => 'Academic Year 2026-2027', 'is_active' => true]);
        $ay5 = AcademicYear::create(['name' => '2027-2028', 'description' => 'Academic Year 2027-2028', 'is_active' => true]);

        // 6. Class: Add 5 records
        $this->command->info("Seeding classes...");
        $class3 = Classes::create(['name' => 'Class 3A', 'group_year' => 'Year 3', 'academic_year' => '2026-2027', 'description' => 'Year 3 Class A', 'is_active' => true]);
        $class4 = Classes::create(['name' => 'Class 4A', 'group_year' => 'Year 4', 'academic_year' => '2026-2027', 'description' => 'Year 4 Class A', 'is_active' => true]);
        $class5 = Classes::create(['name' => 'Class 5A', 'group_year' => 'Year 5', 'academic_year' => '2026-2027', 'description' => 'Year 5 Class A', 'is_active' => true]);
        $class6 = Classes::create(['name' => 'Class 6A', 'group_year' => 'Year 6', 'academic_year' => '2026-2027', 'description' => 'Year 6 Class A', 'is_active' => true]);
        $class7 = Classes::create(['name' => 'Class 7A', 'group_year' => 'Year 7', 'academic_year' => '2026-2027', 'description' => 'Year 7 Class A', 'is_active' => true]);

        // Associate student user with Class 5A
        $student->classes()->sync([$class5->id]);

        // 7. Announcements: Add 5 records
        $this->command->info("Seeding announcements...");
        Announcement::create(['type' => 1, 'title' => 'Welcome to Aspire Learner', 'content' => '<p>Welcome all users to the brand new learning portal.</p>', 'description' => 'System welcome message', 'status' => true]);
        Announcement::create(['type' => 1, 'title' => 'Summer Mock Exams Scheduled', 'content' => '<p>Mock exams will start from June 15th. Please check your assigned papers.</p>', 'description' => 'Exam schedules', 'status' => true]);
        Announcement::create(['type' => 1, 'title' => 'New English Practice Materials Available', 'content' => '<p>We have added 50 new Reading Comprehension practice questions.</p>', 'description' => 'English resources', 'status' => true]);
        Announcement::create(['type' => 1, 'title' => 'Maintenance Downtime', 'content' => '<p>The portal will undergo scheduled maintenance on Sunday from 2 AM to 4 AM.</p>', 'description' => 'Maintenance notification', 'status' => true]);
        Announcement::create(['type' => 1, 'title' => 'Student of the Month Announcement', 'content' => '<p>Congratulations to the top performers of this month! Keep up the great work.</p>', 'description' => 'Student award notice', 'status' => true]);

        // 8. Questions: 5 question for each subject for Topic and Subtopic
        $this->command->info("Seeding questions...");

        // Keep track of Maths questions for sample paper
        $mathsQuestions = [];

        // --- MATHS QUESTIONS (5 total) ---
        $mq1 = Question::create([
            'title' => 'Fraction Conversion',
            'description' => '<p>What is <strong>0.75</strong> written as a fraction in its simplest form?</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $maths->id,
            'topic_id' => $topic1->id,
            'subtopic_id' => $subtopic1->id,
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => '<p>0.75 is three-quarters. Therefore, it is 3/4.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $mq1->id, 'option_text' => '3/4', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $mq1->id, 'option_text' => '1/2', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $mq1->id, 'option_text' => '4/5', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $mq1->id, 'option_text' => '2/3', 'is_correct' => false, 'sort_order' => 4]);
        $mathsQuestions[] = $mq1;

        $mq2 = Question::create([
            'title' => 'Decimal Addition',
            'description' => '<p>Calculate: <strong>1/4 + 0.5</strong>. What is the value in decimal form?</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $maths->id,
            'topic_id' => $topic1->id,
            'subtopic_id' => $subtopic1->id,
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => '<p>1/4 = 0.25. So, 0.25 + 0.5 = 0.75.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $mq2->id, 'option_text' => '0.75', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $mq2->id, 'option_text' => '0.55', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $mq2->id, 'option_text' => '0.60', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $mq2->id, 'option_text' => '1.25', 'is_correct' => false, 'sort_order' => 4]);
        $mathsQuestions[] = $mq2;

        $mq3 = Question::create([
            'title' => 'Place Value',
            'description' => '<p>Complete the statement: The value of the digit <strong>5</strong> in the number <strong>2.35</strong> is five ______.</p>',
            'type' => 'fill_in_the_blanks',
            'subject_id' => $maths->id,
            'topic_id' => $topic1->id,
            'subtopic_id' => $subtopic1->id,
            'difficulty' => 'medium',
            'marks' => 1,
            'explanation' => '<p>The digit 5 is in the hundredths place.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $mq3->id, 'option_text' => 'hundredths', 'is_correct' => true, 'sort_order' => 1]);
        $mathsQuestions[] = $mq3;

        $mq4 = Question::create([
            'title' => 'Decimal Comparison',
            'description' => '<p>Which of the following fractions is larger than <strong>0.6</strong>?</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $maths->id,
            'topic_id' => $topic1->id,
            'subtopic_id' => $subtopic1->id,
            'difficulty' => 'medium',
            'marks' => 1,
            'explanation' => '<p>2/3 is approximately 0.666, which is larger than 0.6. 1/2 is 0.5, 3/5 is 0.6, and 1/4 is 0.25.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $mq4->id, 'option_text' => '2/3', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $mq4->id, 'option_text' => '1/2', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $mq4->id, 'option_text' => '3/5', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $mq4->id, 'option_text' => '1/4', 'is_correct' => false, 'sort_order' => 4]);
        $mathsQuestions[] = $mq4;

        $mq5 = Question::create([
            'title' => 'Equivalent Fractions',
            'description' => '<p>Select all values that are equivalent to <strong>1/2</strong>.</p>',
            'type' => 'multiple_choice',
            'subject_id' => $maths->id,
            'topic_id' => $topic1->id,
            'subtopic_id' => $subtopic1->id,
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => '<p>0.5, 2/4, and 4/8 are all mathematically equivalent to 1/2.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $mq5->id, 'option_text' => '0.5', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $mq5->id, 'option_text' => '2/4', 'is_correct' => true, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $mq5->id, 'option_text' => '0.05', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $mq5->id, 'option_text' => '4/8', 'is_correct' => true, 'sort_order' => 4]);
        $mathsQuestions[] = $mq5;


        // --- ENGLISH QUESTIONS (5 total) ---
        $eq1 = Question::create([
            'title' => 'Atmosphere and Mood',
            'description' => '<p>In the sentence: <em>"The dark clouds gathered, and the wind began to howl"</em>, what is the likely mood?</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $english->id,
            'topic_id' => $topic2->id,
            'subtopic_id' => $subtopic2->id,
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => '<p>Dark clouds and howling wind create an ominous, stormy mood.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $eq1->id, 'option_text' => 'Ominous and stormy', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $eq1->id, 'option_text' => 'Cheerful and bright', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $eq1->id, 'option_text' => 'Calm and peaceful', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $eq1->id, 'option_text' => 'Silly and lighthearted', 'is_correct' => false, 'sort_order' => 4]);

        $eq2 = Question::create([
            'title' => 'Character Inference',
            'description' => '<p>If a character <em>"slammed the door and stomped up the stairs"</em>, what can you infer about their emotion?</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $english->id,
            'topic_id' => $topic2->id,
            'subtopic_id' => $subtopic2->id,
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => '<p>Slamming doors and stomping are physical indicators of anger.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $eq2->id, 'option_text' => 'They are angry', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $eq2->id, 'option_text' => 'They are tired', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $eq2->id, 'option_text' => 'They are excited', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $eq2->id, 'option_text' => 'They are nervous', 'is_correct' => false, 'sort_order' => 4]);

        $eq3 = Question::create([
            'title' => 'Implication Meaning',
            'description' => '<p>Fill in the blank: The word "implied" means something is suggested rather than directly ______.</p>',
            'type' => 'fill_in_the_blanks',
            'subject_id' => $english->id,
            'topic_id' => $topic2->id,
            'subtopic_id' => $subtopic2->id,
            'difficulty' => 'medium',
            'marks' => 1,
            'explanation' => '<p>Inference implies reading details that are not directly stated.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $eq3->id, 'option_text' => 'stated', 'is_correct' => true, 'sort_order' => 1]);

        $eq4 = Question::create([
            'title' => 'Vocabulary Traits',
            'description' => '<p>Which word best describes a person who <em>"does not give up easily"</em>?</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $english->id,
            'topic_id' => $topic3->id,
            'subtopic_id' => $subtopic3->id,
            'difficulty' => 'medium',
            'marks' => 1,
            'explanation' => '<p>"Tenacious" means holding fast or being persistent; not giving up easily.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $eq4->id, 'option_text' => 'Tenacious', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $eq4->id, 'option_text' => 'Timid', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $eq4->id, 'option_text' => 'Indifferent', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $eq4->id, 'option_text' => 'Fragile', 'is_correct' => false, 'sort_order' => 4]);

        $eq5 = Question::create([
            'title' => 'Autumn Indicators',
            'description' => '<p>Select all descriptive clues that suggest it is <strong>autumn</strong> in a story.</p>',
            'type' => 'multiple_choice',
            'subject_id' => $english->id,
            'topic_id' => $topic2->id,
            'subtopic_id' => $subtopic2->id,
            'difficulty' => 'medium',
            'marks' => 1,
            'explanation' => '<p>Orange and gold leaves, pumpkins on doorsteps, and dry leaves are classic indicators of autumn.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $eq5->id, 'option_text' => 'Leaves turning orange and gold', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $eq5->id, 'option_text' => 'People wearing heavy winter coats', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $eq5->id, 'option_text' => 'Pumpkins resting on doorsteps', 'is_correct' => true, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $eq5->id, 'option_text' => 'Crisp, cool breeze rustling dry leaves', 'is_correct' => true, 'sort_order' => 4]);


        // --- VERBAL REASONING QUESTIONS (5 total) ---
        $vq1 = Question::create([
            'title' => 'Analogy Pairs',
            'description' => '<p>Complete the analogy: <strong>Kitten</strong> is to <strong>Cat</strong> as <strong>Puppy</strong> is to ______.</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $verbal->id,
            'topic_id' => $topic4->id,
            'subtopic_id' => $subtopic4->id,
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => '<p>A kitten is a baby cat, and a puppy is a baby dog.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $vq1->id, 'option_text' => 'Dog', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $vq1->id, 'option_text' => 'Wolf', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $vq1->id, 'option_text' => 'Kangaroo', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $vq1->id, 'option_text' => 'Hamster', 'is_correct' => false, 'sort_order' => 4]);

        $vq2 = Question::create([
            'title' => 'Classification Odd One Out',
            'description' => '<p>Find the <strong>odd one out</strong> from the list below:</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $verbal->id,
            'topic_id' => $topic4->id,
            'subtopic_id' => $subtopic4->id,
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => '<p>Carrot is a root vegetable. Apple, Banana, and Orange are all tree fruits.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $vq2->id, 'option_text' => 'Carrot', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $vq2->id, 'option_text' => 'Apple', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $vq2->id, 'option_text' => 'Banana', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $vq2->id, 'option_text' => 'Orange', 'is_correct' => false, 'sort_order' => 4]);

        $vq3 = Question::create([
            'title' => 'Opposites Analogy',
            'description' => '<p>Complete the analogy: <strong>Hot</strong> is to <strong>Cold</strong> as <strong>High</strong> is to ______.</p>',
            'type' => 'fill_in_the_blanks',
            'subject_id' => $verbal->id,
            'topic_id' => $topic4->id,
            'subtopic_id' => $subtopic4->id,
            'difficulty' => 'easy',
            'marks' => 1,
            'explanation' => '<p>Hot and Cold are antonyms. The antonym of High is Low.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $vq3->id, 'option_text' => 'Low', 'is_correct' => true, 'sort_order' => 1]);

        $vq4 = Question::create([
            'title' => 'Alphabet Substitution Code',
            'description' => '<p>If <strong>A = 1, B = 2, C = 3, ... Z = 26</strong>, what is the code value for the word <strong>"CAB"</strong>?</p>',
            'type' => 'single_choice_radio',
            'subject_id' => $verbal->id,
            'topic_id' => $topic5->id,
            'subtopic_id' => $subtopic5->id,
            'difficulty' => 'medium',
            'marks' => 1,
            'explanation' => '<p>C = 3, A = 1, B = 2. Placing them together yields 312.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $vq4->id, 'option_text' => '312', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $vq4->id, 'option_text' => '123', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $vq4->id, 'option_text' => '321', 'is_correct' => false, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $vq4->id, 'option_text' => '213', 'is_correct' => false, 'sort_order' => 4]);

        $vq5 = Question::create([
            'title' => 'Antonyms Selection',
            'description' => '<p>Select all pairs of <strong>antonyms</strong> from the choices below.</p>',
            'type' => 'multiple_choice',
            'subject_id' => $verbal->id,
            'topic_id' => $topic4->id,
            'subtopic_id' => $subtopic4->id,
            'difficulty' => 'medium',
            'marks' => 1,
            'explanation' => '<p>"Fast / Slow" and "Large / Small" are opposite pairs. "Quick / Rapid" and "Joy / Happiness" are synonym pairs.</p>',
            'is_active' => true,
        ]);
        QuestionOption::create(['question_id' => $vq5->id, 'option_text' => 'Fast / Slow', 'is_correct' => true, 'sort_order' => 1]);
        QuestionOption::create(['question_id' => $vq5->id, 'option_text' => 'Quick / Rapid', 'is_correct' => false, 'sort_order' => 2]);
        QuestionOption::create(['question_id' => $vq5->id, 'option_text' => 'Large / Small', 'is_correct' => true, 'sort_order' => 3]);
        QuestionOption::create(['question_id' => $vq5->id, 'option_text' => 'Joy / Happiness', 'is_correct' => false, 'sort_order' => 4]);


        // 9. Create One Sample Paper
        $this->command->info("Seeding sample paper...");
        $paper = Paper::create([
            'type' => 'test',
            'title' => '11+ Maths Practice Paper 1',
            'instruction' => '<p>This is a practice paper covering Decimals, Fractions, and Place Value. Answer all questions. You have 15 minutes.</p>',
            'subject_id' => $maths->id,
            'topic_id' => $topic1->id,
            'subtopic_id' => $subtopic1->id,
            'class_id' => $class5->id,
            'year_group_id' => $yg5->id,
            'user_id' => $tutor->id,
            'academic_year' => '2026-2027',
            'difficulty' => 'medium',
            'total_time' => 15,
            'default_marks' => 1,
            'question_pooling' => false,
            'allow_attempt_without_signup' => false,
            'allow_reattempt_question' => true,
            'display_result_question_by_question' => true,
            'allow_instant_feedback' => true,
            'hide_result' => false,
            'shuffle_questions' => false,
        ]);

        // Attach all 5 Maths questions to this paper
        $syncData = [];
        foreach ($mathsQuestions as $index => $q) {
            $syncData[$q->id] = [
                'sort_order' => $index + 1,
                'marks' => 1,
            ];
        }
        $paper->questions()->sync($syncData);

        // Assign the paper to all students
        PaperAssignment::create([
            'paper_id' => $paper->id,
            'assign_type' => 'students',
            'assign_mode' => 'all',
        ]);

        $this->command->info("LMS Data Seeder completed successfully!");
    }
}

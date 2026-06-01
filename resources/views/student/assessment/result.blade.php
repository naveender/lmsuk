@extends('layouts.app')

@section('title', 'Test Results: ' . $attempt->paper->title)

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            
            <!-- Breadcrumbs -->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Test Results</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('student.assessments') }}">Assessments</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('student.topics.subtopics', $attempt->paper->topic_id) }}">Subtopics</a></li>
                                    <li class="breadcrumb-item active">Results</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                
                <!-- Performance Overview Card -->
                <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                    <div class="row no-gutters">
                        <!-- Score Badge -->
                        @php
                            $percentage = $attempt->max_score > 0 ? round(($attempt->score / $attempt->max_score) * 100) : 0;
                            $gradeColor = 'bg-danger';
                            $gradeText = 'Needs Practice';
                            if ($percentage >= 80) {
                                $gradeColor = 'bg-success';
                                $gradeText = 'Excellent!';
                            } elseif ($percentage >= 50) {
                                $gradeColor = 'bg-warning';
                                $gradeText = 'Good Effort';
                            }
                        @endphp
                        <div class="col-md-4 {{ $gradeColor }} text-white d-flex flex-column align-items-center justify-content-center py-4">
                            <h1 class="text-white display-3 mb-0 font-weight-bold">{{ $percentage }}%</h1>
                            <h4 class="text-white mb-2">{{ $gradeText }}</h4>
                            <p class="mb-0 text-white-50">Completed on {{ $attempt->completed_at->format('d M Y, h:i A') }}</p>
                        </div>

                        <!-- Stat Breakdown -->
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title text-dark font-weight-bold">{{ $attempt->paper->title }}</h4>
                                <div class="row mt-4">
                                    <div class="col-sm-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-rgba-primary p-2 mr-2">
                                                <i class="feather icon-award text-primary font-medium-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-weight-bold mb-0">{{ $attempt->score }} / {{ $attempt->max_score }}</h5>
                                                <small class="text-muted">Total Marks</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-rgba-success p-2 mr-2">
                                                <i class="feather icon-check-circle text-success font-medium-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-weight-bold mb-0 text-success">{{ $attempt->correct_answers }}</h5>
                                                <small class="text-muted">Correct Answers</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-rgba-danger p-2 mr-2">
                                                <i class="feather icon-x-circle text-danger font-medium-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-weight-bold mb-0 text-danger">{{ $attempt->total_questions - $attempt->correct_answers }}</h5>
                                                <small class="text-muted">Incorrect/Unanswered</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-rgba-warning p-2 mr-2">
                                                <i class="feather icon-clock text-warning font-medium-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-weight-bold mb-0 text-dark">
                                                    {{ gmdate('H:i:s', $attempt->time_spent) }}
                                                </h5>
                                                <small class="text-muted">Time Spent</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-rgba-info p-2 mr-2">
                                                <i class="feather icon-help-circle text-info font-medium-3"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-weight-bold mb-0">{{ $attempt->total_questions }}</h5>
                                                <small class="text-muted">Total Questions</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <a href="{{ route('student.topics.subtopics', $attempt->paper->topic_id) }}" class="btn btn-primary px-4 mr-2">
                                        Back to Tests
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($attempt->paper->display_result_question_by_question)
                @php
                    $correctCount = 0;
                    $incorrectCount = 0;
                    $unansweredCount = 0;
                    foreach ($questions as $q) {
                        $ans = $answers->get($q->id);
                        if ($ans) {
                            if ($ans->is_correct) {
                                $correctCount++;
                            } else {
                                $incorrectCount++;
                            }
                        } else {
                            $unansweredCount++;
                        }
                    }
                @endphp

                <!-- Detailed Questions Review -->
                <div class="card shadow-sm border-0 mb-4 overflow-hidden review-section">
                    <div class="card-header bg-transparent border-bottom pt-3 pb-3 d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title font-weight-bold text-dark mb-0">Question-by-Question Review</h4>
                            <p class="text-muted mb-0 font-small-3 mt-25">Detailed breakdown of each question and response status.</p>
                        </div>
                        
                        <!-- Filter Group -->
                        <div class="btn-group btn-group-toggle review-filters mt-1 mt-md-0" data-toggle="buttons">
                            <label class="btn btn-outline-primary active cursor-pointer btn-sm py-50 px-2" onclick="filterQuestions('all', this)">
                                <input type="radio" name="options" id="filter-all" checked> All ({{ $questions->count() }})
                            </label>
                            <label class="btn btn-outline-success cursor-pointer btn-sm py-50 px-2" onclick="filterQuestions('correct', this)">
                                <input type="radio" name="options" id="filter-correct"> Correct ({{ $correctCount }})
                            </label>
                            <label class="btn btn-outline-danger cursor-pointer btn-sm py-50 px-2" onclick="filterQuestions('incorrect', this)">
                                <input type="radio" name="options" id="filter-incorrect"> Incorrect ({{ $incorrectCount }})
                            </label>
                            <label class="btn btn-outline-secondary cursor-pointer btn-sm py-50 px-2" onclick="filterQuestions('unanswered', this)">
                                <input type="radio" name="options" id="filter-unanswered"> Unanswered ({{ $unansweredCount }})
                            </label>
                        </div>
                    </div>
                    
                    <div class="card-body pt-3">
                        <div class="row">
                            <!-- Question Cards Column -->
                            <div class="col-lg-9 col-md-8 col-12">
                                @foreach($questions as $index => $question)
                                    @php
                                        $ans = $answers->get($question->id);
                                        $isCorrect = $ans && $ans->is_correct;
                                        
                                        $statusType = 'unanswered';
                                        $cardAccentClass = 'review-card-unanswered';
                                        $correctText = 'Unanswered';
                                        $correctBadge = 'badge-light-secondary';
                                        $statusIcon = 'feather icon-alert-circle';
                                        
                                        if ($ans) {
                                            if ($isCorrect) {
                                                $statusType = 'correct';
                                                $cardAccentClass = 'review-card-correct';
                                                $correctText = 'Correct';
                                                $correctBadge = 'badge-light-success';
                                                $statusIcon = 'feather icon-check';
                                            } else {
                                                $statusType = 'incorrect';
                                                $cardAccentClass = 'review-card-incorrect';
                                                $correctText = 'Incorrect';
                                                $correctBadge = 'badge-light-danger';
                                                $statusIcon = 'feather icon-x';
                                            }
                                        }
                                    @endphp

                                    <div class="card mb-4 border review-card {{ $cardAccentClass }} review-card-item rounded-lg" 
                                         id="question-{{ $question->id }}"
                                         data-status="{{ $statusType }}">
                                        
                                        <!-- Header -->
                                        <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 bg-light-header border-bottom">
                                            <span class="font-weight-bold text-primary font-medium-1">
                                                Question {{ $index + 1 }}
                                            </span>
                                            <span class="badge badge-pill {{ $correctBadge }} px-3 py-50 font-weight-bold d-flex align-items-center">
                                                <i class="{{ $statusIcon }} mr-50 font-small-3"></i>
                                                {{ $correctText }}
                                            </span>
                                        </div>
                                        
                                        <div class="card-body p-3">
                                            <!-- Question Title -->
                                            @if(!empty($question->title) && trim(strip_tags($question->title)) !== trim(strip_tags($question->description)))
                                                <h5 class="font-weight-bold text-dark mb-2" style="font-size: 1.15rem; line-height: 1.45;">
                                                    {{ $question->title }}
                                                </h5>
                                            @endif

                                            <!-- Question Text -->
                                            <div class="question-desc text-dark font-medium-1 mb-3 pr-md-2" style="line-height: 1.6;">
                                                {!! $question->description !!}
                                            </div>

                                            <!-- Question Image -->
                                            @if($question->image)
                                                <div class="question-img-wrapper mb-3 text-left">
                                                    <a href="{{ asset('storage/' . $question->image) }}" target="_blank" title="Click to view full image">
                                                        <img src="{{ asset('storage/' . $question->image) }}" 
                                                             class="img-fluid rounded border shadow-sm question-img-preview" 
                                                             style="max-height: 280px; transition: transform 0.2s ease;" 
                                                             alt="Question Image">
                                                    </a>
                                                </div>
                                            @endif

                                            <!-- Comparative Answer Grid -->
                                            <div class="row mt-3">
                                                <!-- Student Response -->
                                                <div class="col-md-6 mb-3 mb-md-0 d-flex">
                                                    <div class="answer-card w-100 p-3 rounded-lg border d-flex flex-column {{ $isCorrect ? 'user-answer-correct' : ($ans ? 'user-answer-incorrect' : 'user-answer-unanswered') }}">
                                                        <div class="d-flex align-items-center mb-2 pb-1 border-bottom-subtle">
                                                            <div class="answer-icon-circle mr-2 d-flex align-items-center justify-content-center">
                                                                @if($isCorrect)
                                                                    <i class="feather icon-check text-success font-medium-2"></i>
                                                                @elseif($ans)
                                                                    <i class="feather icon-x text-danger font-medium-2"></i>
                                                                @else
                                                                    <i class="feather icon-alert-circle text-muted font-medium-2"></i>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <small class="text-uppercase text-muted font-weight-bold d-block font-small-1">Your Response</small>
                                                                <span class="font-weight-bold font-small-2 {{ $isCorrect ? 'text-success' : ($ans ? 'text-danger' : 'text-secondary') }}">
                                                                    @if($isCorrect)
                                                                        Correct
                                                                    @elseif($ans)
                                                                        Incorrect
                                                                    @else
                                                                        Not Attempted
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="response-text text-dark font-weight-bold font-small-3 flex-grow-1 d-flex align-items-center">
                                                            @if($question->type === 'single_choice_radio' || $question->type === 'single_choice_dropdown' || $question->type === 'picture_choice')
                                                                @if($ans && $ans->selectedOption)
                                                                    {{ $ans->selectedOption->option_text }}
                                                                @else
                                                                    <span class="text-muted font-italic font-weight-normal">No Answer Selected</span>
                                                                @endif
                                                            @elseif($question->type === 'multiple_choice')
                                                                @if($ans && is_array($ans->selected_options) && count($ans->selected_options) > 0)
                                                                    @php
                                                                        $selectedOptions = \App\Models\QuestionOption::whereIn('id', $ans->selected_options)->pluck('option_text')->toArray();
                                                                    @endphp
                                                                    {{ implode(', ', $selectedOptions) }}
                                                                @else
                                                                    <span class="text-muted font-italic font-weight-normal">No Answer Selected</span>
                                                                @endif
                                                            @elseif($question->type === 'matching_drag_drop' || $question->type === 'matching_text')
                                                                @if($ans && $ans->answer_text)
                                                                    @php
                                                                        $decoded = json_decode($ans->answer_text, true);
                                                                        $pairs = $question->metadata['matching_pairs'] ?? [];
                                                                        $studentPairTexts = [];
                                                                        if (is_array($decoded)) {
                                                                            foreach ($pairs as $pairIdx => $pair) {
                                                                                $studentMatch = $decoded[$pairIdx] ?? 'N/A';
                                                                                $studentPairTexts[] = $pair['left'] . ' ➔ ' . $studentMatch;
                                                                            }
                                                                        } else {
                                                                            $studentPairTexts[] = $ans->answer_text;
                                                                        }
                                                                    @endphp
                                                                    {{ implode(', ', $studentPairTexts) }}
                                                                @else
                                                                    <span class="text-muted font-italic font-weight-normal">No Answer Selected</span>
                                                                @endif
                                                            @else
                                                                @if($ans && $ans->answer_text)
                                                                    @php
                                                                        $decoded = json_decode($ans->answer_text, true);
                                                                    @endphp
                                                                    @if(is_array($decoded))
                                                                        {{ implode(', ', $decoded) }}
                                                                    @else
                                                                        {{ $ans->answer_text }}
                                                                    @endif
                                                                @else
                                                                    <span class="text-muted font-italic font-weight-normal">No Answer Entered</span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Correct Response -->
                                                <div class="col-md-6 d-flex">
                                                    <div class="answer-card w-100 p-3 rounded-lg border d-flex flex-column correct-answer-card">
                                                        <div class="d-flex align-items-center mb-2 pb-1 border-bottom-subtle">
                                                            <div class="answer-icon-circle mr-2 d-flex align-items-center justify-content-center bg-rgba-success">
                                                                <i class="feather icon-check-square text-success font-medium-2"></i>
                                                            </div>
                                                            <div>
                                                                <small class="text-uppercase text-muted font-weight-bold d-block font-small-1">Correct Response</small>
                                                                <span class="font-weight-bold font-small-2 text-success">Key Solution</span>
                                                            </div>
                                                        </div>
                                                        <div class="response-text text-success font-weight-bold font-small-3 flex-grow-1 d-flex align-items-center">
                                                            @if($question->type === 'fill_in_the_blanks')
                                                                @php
                                                                    $correctBlanks = $question->metadata['blank_answers'] ?? [];
                                                                @endphp
                                                                @if(!empty($correctBlanks))
                                                                    {{ implode(', ', $correctBlanks) }}
                                                                @else
                                                                    @php
                                                                        $correctOption = $question->options->where('is_correct', true)->first();
                                                                    @endphp
                                                                    {{ $correctOption ? $correctOption->option_text : 'N/A' }}
                                                                @endif
                                                            @elseif($question->type === 'single_choice_radio' || $question->type === 'single_choice_dropdown' || $question->type === 'picture_choice' || $question->type === 'multiple_choice')
                                                                @php
                                                                    $correctOptions = $question->options->where('is_correct', true)->pluck('option_text')->toArray();
                                                                @endphp
                                                                {{ implode(', ', $correctOptions) }}
                                                            @elseif($question->type === 'matching_drag_drop' || $question->type === 'matching_text')
                                                                @php
                                                                    $pairs = $question->metadata['matching_pairs'] ?? [];
                                                                    $correctPairTexts = [];
                                                                    foreach ($pairs as $pair) {
                                                                        $correctPairTexts[] = $pair['left'] . ' ➔ ' . $pair['right'];
                                                                    }
                                                                @endphp
                                                                {{ implode(', ', $correctPairTexts) }}
                                                            @else
                                                                @php
                                                                    $correctOption = $question->options->where('is_correct', true)->first();
                                                                @endphp
                                                                {{ $correctOption ? $correctOption->option_text : 'N/A' }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Explanation Block -->
                                            @if($question->explanation)
                                                <div class="explanation-card mt-3 p-3 rounded-lg border-left-primary bg-rgba-primary-light">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="feather icon-help-circle text-primary font-medium-3 mr-1 font-weight-bold"></i>
                                                        <h6 class="mb-0 text-primary font-weight-bold text-uppercase font-small-3">Explanation & Concept Review</h6>
                                                    </div>
                                                    <div class="explanation-content text-dark font-small-3" style="line-height: 1.55;">
                                                        {!! $question->explanation !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Navigator Sidebar Column -->
                            <div class="col-lg-3 col-md-4 col-12 d-none d-md-block">
                                <div class="card shadow-none border rounded-lg sticky-top review-navigator-sidebar" style="top: 110px; z-index: 5;">
                                    <div class="card-header bg-light border-bottom py-2 px-3">
                                        <h6 class="mb-0 font-weight-bold text-dark d-flex align-items-center">
                                            <i class="feather icon-navigation mr-50 text-primary"></i>
                                            Navigator
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="nav-grid">
                                            @foreach($questions as $index => $question)
                                                @php
                                                    $ans = $answers->get($question->id);
                                                    $dotStatus = 'unanswered';
                                                    if ($ans) {
                                                        $dotStatus = $ans->is_correct ? 'correct' : 'incorrect';
                                                    }
                                                @endphp
                                                <a href="#question-{{ $question->id }}" 
                                                   class="nav-dot nav-dot-{{ $dotStatus }} d-flex align-items-center justify-content-center text-decoration-none font-weight-bold font-small-3" 
                                                   data-question-id="{{ $question->id }}"
                                                   data-status="{{ $dotStatus }}"
                                                   onclick="scrollToQuestion(event, 'question-{{ $question->id }}')">
                                                    {{ $index + 1 }}
                                                </a>
                                            @endforeach
                                        </div>
                                        <div class="mt-3 pt-2 border-top">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="legend-color legend-correct mr-1"></span>
                                                <span class="font-small-2 font-weight-bold text-secondary">Correct</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="legend-color legend-incorrect mr-1"></span>
                                                <span class="font-small-2 font-weight-bold text-secondary">Incorrect</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="legend-color legend-unanswered mr-1"></span>
                                                <span class="font-small-2 font-weight-bold text-secondary">Unanswered</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-rgba-primary {
            background-color: rgba(115, 103, 240, 0.12);
        }
        .bg-rgba-success {
            background-color: rgba(40, 199, 111, 0.12);
        }
        .bg-rgba-danger {
            background-color: rgba(234, 84, 85, 0.12);
        }
        .bg-rgba-warning {
            background-color: rgba(255, 159, 67, 0.12);
        }
        .bg-rgba-info {
            background-color: rgba(0, 207, 221, 0.12);
        }
        .bg-light-success {
            background-color: rgba(40, 199, 111, 0.05);
        }
        .bg-light-danger {
            background-color: rgba(234, 84, 85, 0.05);
        }
        .italic {
            font-style: italic;
        }
        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            width: 45px;
            height: 45px;
        }

        /* Modernized Question-by-Question Review Styles */
        .review-section {
            border-radius: 12px;
        }
        
        .bg-light-header {
            background-color: #fafbfd;
        }
        
        .border-bottom-subtle {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .review-card {
            border: 1px solid #ebedf2 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
            transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: hidden;
        }
        
        .review-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(115, 103, 240, 0.08) !important;
        }
        
        .review-card-correct {
            border-left: 4px solid #28c76f !important;
        }
        
        .review-card-incorrect {
            border-left: 4px solid #ea5455 !important;
        }
        
        .review-card-unanswered {
            border-left: 4px solid #82868b !important;
        }
        
        /* Soft, Pastel Badges for Vuexy styling */
        .badge-light-success {
            background-color: #e8f9ee;
            color: #28c76f;
        }
        .badge-light-danger {
            background-color: #fbebeb;
            color: #ea5455;
        }
        .badge-light-secondary {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        
        /* Comparison Cards */
        .answer-card {
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        
        .user-answer-correct {
            background-color: #f3fbf6;
            border: 1px solid rgba(40, 199, 111, 0.15) !important;
        }
        
        .user-answer-incorrect {
            background-color: #fff6f6;
            border: 1px solid rgba(234, 84, 85, 0.15) !important;
        }
        
        .user-answer-unanswered {
            background-color: #f8f9fb;
            border: 1px solid rgba(130, 134, 139, 0.15) !important;
        }
        
        .correct-answer-card {
            background-color: #f3fbf6;
            border: 1px solid rgba(40, 199, 111, 0.2) !important;
        }
        
        .answer-icon-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-answer-correct .answer-icon-circle {
            background-color: rgba(40, 199, 111, 0.1);
        }
        
        .user-answer-incorrect .answer-icon-circle {
            background-color: rgba(234, 84, 85, 0.1);
        }
        
        .correct-answer-card .answer-icon-circle {
            background-color: rgba(40, 199, 111, 0.1);
        }
        
        .response-text {
            word-break: break-word;
            padding-top: 0.5rem;
            line-height: 1.5;
        }
        
        /* Explanation Blocks */
        .explanation-card {
            border-left: 4px solid #7367f0;
            border-top: 1px solid rgba(115, 103, 240, 0.08);
            border-right: 1px solid rgba(115, 103, 240, 0.08);
            border-bottom: 1px solid rgba(115, 103, 240, 0.08);
        }
        .bg-rgba-primary-light {
            background-color: rgba(115, 103, 240, 0.03);
        }
        
        /* Image Preview Hover Effect */
        .question-img-preview:hover {
            transform: scale(1.02);
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Question Navigator Sidebar */
        .review-navigator-sidebar {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
        }
        
        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(36px, 1fr));
            gap: 8px;
        }
        
        .nav-dot {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
            border: 1px solid #ebedf2;
            cursor: pointer;
        }
        
        .nav-dot-correct {
            background-color: #e8f9ee;
            color: #28c76f !important;
            border-color: rgba(40, 199, 111, 0.3);
        }
        
        .nav-dot-correct:hover {
            background-color: #28c76f;
            color: #ffffff !important;
            border-color: #28c76f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 199, 111, 0.2);
        }
        
        .nav-dot-incorrect {
            background-color: #fbebeb;
            color: #ea5455 !important;
            border-color: rgba(234, 84, 85, 0.3);
        }
        
        .nav-dot-incorrect:hover {
            background-color: #ea5455;
            color: #ffffff !important;
            border-color: #ea5455;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(234, 84, 85, 0.2);
        }
        
        .nav-dot-unanswered {
            background-color: #f3f4f6;
            color: #6b7280 !important;
            border-color: rgba(107, 114, 128, 0.3);
        }
        
        .nav-dot-unanswered:hover {
            background-color: #82868b;
            color: #ffffff !important;
            border-color: #82868b;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(130, 134, 139, 0.2);
        }
        
        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            display: inline-block;
        }
        .legend-correct { background-color: #e8f9ee; border: 1px solid rgba(40, 199, 111, 0.4); }
        .legend-incorrect { background-color: #fbebeb; border: 1px solid rgba(234, 84, 85, 0.4); }
        .legend-unanswered { background-color: #f3f4f6; border: 1px solid rgba(107, 114, 128, 0.4); }
        
        /* Highlight & Transitions */
        @keyframes pulseHighlight {
            0% {
                border-color: #7367f0 !important;
                box-shadow: 0 0 0 0px rgba(115, 103, 240, 0.4) !important;
                background-color: rgba(115, 103, 240, 0.03);
            }
            50% {
                border-color: #7367f0 !important;
                box-shadow: 0 0 0 12px rgba(115, 103, 240, 0) !important;
                background-color: rgba(115, 103, 240, 0.08);
            }
            100% {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
                background-color: transparent;
            }
        }
        
        .highlight-pulse {
            animation: pulseHighlight 1.5s ease-in-out;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Flex gap utility fallback for Bootstrap 4 */
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 1rem; }
    </style>
@endpush

@push('scripts')
    <script>
        function filterQuestions(status, element) {
            // Update active class on filters
            const labels = document.querySelectorAll('.review-filters label');
            labels.forEach(label => label.classList.remove('active'));
            if (element) {
                element.classList.add('active');
            }
            
            // Filter cards
            const cards = document.querySelectorAll('.review-card-item');
            cards.forEach(card => {
                if (status === 'all' || card.getAttribute('data-status') === status) {
                    card.style.display = 'block';
                    card.classList.add('animate-fade-in');
                } else {
                    card.style.display = 'none';
                    card.classList.remove('animate-fade-in');
                }
            });
            
            // Filter navigator dots
            const dots = document.querySelectorAll('.nav-dot');
            dots.forEach(dot => {
                if (status === 'all' || dot.getAttribute('data-status') === status) {
                    dot.style.opacity = '1';
                    dot.style.pointerEvents = 'auto';
                } else {
                    dot.style.opacity = '0.2';
                    dot.style.pointerEvents = 'none';
                }
            });
        }
        
        function scrollToQuestion(event, id) {
            event.preventDefault();
            const target = document.getElementById(id);
            if (target) {
                // Determine layout menu offset
                const offset = 120; // 120px offset for floating navbar
                const bodyRect = document.body.getBoundingClientRect().top;
                const targetRect = target.getBoundingClientRect().top;
                const targetPosition = targetRect - bodyRect;
                const offsetPosition = targetPosition - offset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Add class to pulse target card
                target.classList.add('highlight-pulse');
                setTimeout(() => {
                    target.classList.remove('highlight-pulse');
                }, 1500);
            }
        }
    </script>
@endpush

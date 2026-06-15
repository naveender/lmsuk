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
                <!-- Detailed Questions Review -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h4 class="card-title font-weight-bold text-dark">Question-by-Question Review</h4>
                    </div>
                    <div class="card-body">
                        @foreach($questions as $index => $question)
                            @php
                                $ans = $answers->get($question->id);
                                $isCorrect = $ans && $ans->is_correct;
                                $ansBorderColor = $isCorrect ? 'border-success' : 'border-danger';
                                $ansBgColor = $isCorrect ? 'bg-light-success' : 'bg-light-danger';
                                $correctText = $isCorrect ? 'Correct' : ($ans ? 'Incorrect' : 'Unanswered');
                                $correctBadge = $isCorrect ? 'badge-success' : ($ans ? 'badge-danger' : 'badge-secondary');
                            @endphp

                            <div class="card mb-4 border shadow-none rounded">
                                <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 bg-light">
                                    <span class="font-weight-bold text-secondary">
                                        Question {{ $index + 1 }}
                                    </span>
                                    <span class="badge badge-pill {{ $correctBadge }} px-3">
                                        {{ $correctText }}
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    
                                    <!-- Question Text -->
                                    <div class="question-desc text-dark font-medium-1 mb-3">
                                        {!! $question->description !!}
                                    </div>

                                    <!-- Question Images -->
                                    @if($question->image || ($question->images && count($question->images) > 0))
                                        <div class="question-images-wrapper mb-3 d-flex flex-wrap align-items-center" style="gap: 16px;">
                                            @if($question->image)
                                                <div class="question-img-item">
                                                    <img src="{{ asset('storage/' . $question->image) }}" class="img-fluid rounded border shadow-sm img-zoomable" style="max-height: 220px; object-fit: contain; cursor: zoom-in; transition: transform 0.2s;" alt="Question Image">
                                                </div>
                                            @endif
                                            @if($question->images)
                                                @foreach($question->images as $img)
                                                    <div class="question-img-item">
                                                        <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded border shadow-sm img-zoomable" style="max-height: 220px; object-fit: contain; cursor: zoom-in; transition: transform 0.2s;" alt="Question Image">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Student Answer & Correct Answer Review -->
                                    <div class="review-box p-3 rounded {{ $ansBgColor }} border {{ $ansBorderColor }} mb-3">
                                        <div class="row">
                                            <div class="col-md-6 mb-2 mb-md-0">
                                                <span class="font-weight-bold text-secondary d-block mb-1">Your Answer:</span>
                                                <span class="text-dark font-weight-bold">
                                                    @if($question->type === 'single_choice_radio' || $question->type === 'single_choice_dropdown' || $question->type === 'picture_choice')
                                                        @if($ans && $ans->selectedOption)
                                                            <div class="d-flex align-items-center">
                                                                @if($ans->selectedOption->option_image)
                                                                    <img src="{{ asset('storage/' . $ans->selectedOption->option_image) }}" class="mr-2 rounded border img-zoomable" style="max-height: 50px; cursor: zoom-in;" alt="Option Image">
                                                                @endif
                                                                <span>{{ $ans->selectedOption->option_text }}</span>
                                                            </div>
                                                        @else
                                                            <span class="text-muted italic">No Answer Selected</span>
                                                        @endif
                                                    @elseif($question->type === 'multiple_choice')
                                                        @if($ans && is_array($ans->selected_options) && count($ans->selected_options) > 0)
                                                            @php
                                                                $selectedOptions = \App\Models\QuestionOption::whereIn('id', $ans->selected_options)->get();
                                                            @endphp
                                                            <div class="d-flex flex-column" style="gap: 4px;">
                                                                @foreach($selectedOptions as $sOpt)
                                                                    <div class="d-flex align-items-center">
                                                                        @if($sOpt->option_image)
                                                                            <img src="{{ asset('storage/' . $sOpt->option_image) }}" class="mr-2 rounded border img-zoomable" style="max-height: 40px; cursor: zoom-in;" alt="Option Image">
                                                                        @endif
                                                                        <span>{{ $sOpt->option_text }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="text-muted italic">No Answer Selected</span>
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
                                                            <span class="text-muted italic">No Answer Selected</span>
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
                                                            <span class="text-muted italic">No Answer Entered</span>
                                                        @endif
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="font-weight-bold text-secondary d-block mb-1">Correct Answer:</span>
                                                <span class="text-success font-weight-bold">
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
                                                            $correctOptions = $question->options->where('is_correct', true);
                                                        @endphp
                                                        <div class="d-flex flex-column" style="gap: 4px;">
                                                            @foreach($correctOptions as $cOpt)
                                                                <div class="d-flex align-items-center">
                                                                    @if($cOpt->option_image)
                                                                        <img src="{{ asset('storage/' . $cOpt->option_image) }}" class="mr-2 rounded border img-zoomable" style="max-height: 40px; cursor: zoom-in;" alt="Option Image">
                                                                    @endif
                                                                    <span>{{ $cOpt->option_text }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
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
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Explanation Feedback -->
                                    @if($question->explanation || ($question->explanation_images && count($question->explanation_images) > 0))
                                        <div class="explanation-box p-3 border rounded bg-light mt-3">
                                            <span class="font-weight-bold text-secondary d-block mb-1"><i class="feather icon-info mr-1 text-info"></i> Explanation:</span>
                                            @if($question->explanation)
                                                <div class="mb-2 text-dark font-small-3">{!! $question->explanation !!}</div>
                                            @endif
                                            @if($question->explanation_images && count($question->explanation_images) > 0)
                                                <div class="explanation-images-wrapper d-flex flex-wrap align-items-center" style="gap: 8px;">
                                                    @foreach($question->explanation_images as $expImg)
                                                        <img src="{{ asset('storage/' . $expImg) }}" class="img-fluid rounded border img-zoomable" style="max-height: 120px; object-fit: contain; cursor: zoom-in; transition: transform 0.2s;" alt="Explanation Image">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endforeach
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
        .img-zoomable:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        }
        #close-lightbox-btn:hover {
            color: #ef4444 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inject lightbox HTML
            const lightboxHtml = `
                <div id="premium-image-lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 99999; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease; cursor: zoom-out;">
                    <span style="position: absolute; top: 20px; right: 25px; color: #f8fafc; font-size: 40px; font-weight: 200; cursor: pointer; transition: color 0.2s;" id="close-lightbox-btn">&times;</span>
                    <img id="lightbox-zoomed-img" src="" style="max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 8px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); transform: scale(0.9); transition: transform 0.3s ease;" alt="Zoomed Image">
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', lightboxHtml);

            const lightbox = document.getElementById('premium-image-lightbox');
            const lightboxImg = document.getElementById('lightbox-zoomed-img');
            const closeBtn = document.getElementById('close-lightbox-btn');

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('img-zoomable')) {
                    lightboxImg.src = e.target.src;
                    lightbox.style.display = 'flex';
                    // Trigger reflow for transition
                    lightbox.offsetHeight;
                    lightbox.style.opacity = '1';
                    lightboxImg.style.transform = 'scale(1)';
                }
            });

            function closeLightbox() {
                lightbox.style.opacity = '0';
                lightboxImg.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    lightbox.style.display = 'none';
                }, 300);
            }

            lightbox.addEventListener('click', function(e) {
                if (e.target !== lightboxImg) {
                    closeLightbox();
                }
            });

            closeBtn.addEventListener('click', closeLightbox);
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && lightbox.style.display === 'flex') {
                    closeLightbox();
                }
            });
        });
    </script>
@endpush

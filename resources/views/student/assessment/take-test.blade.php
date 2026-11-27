@extends('layouts.app')

@section('title', $attempt->paper->title . ' - Assessment')

@section('content')
    @php
        $studentUser = auth()->user();
        $totalQuestionsCount = $questions->count();

        // Classify paper format for header subtitle and subject icon
        $qTypes = $questions->pluck('type')->unique()->values()->all();
        $choiceTypes = ['single_choice_radio', 'single_choice_dropdown', 'multiple_choice', 'picture_choice'];
        $directTypes = ['fill_in_the_blanks', 'free_text', 'matching_text', 'matching_drag_drop', 'file_upload'];

        if (!empty($qTypes) && empty(array_diff($qTypes, $choiceTypes))) {
            $paperSubtitle = 'Answer the questions below and choose the best option.';
        } elseif (!empty($qTypes) && empty(array_diff($qTypes, $directTypes))) {
            $paperSubtitle = 'Answer the questions below by typing your answer.';
        } else {
            $paperSubtitle = 'Answer each question below carefully.';
        }

        $subjectNameLower = strtolower($attempt->paper->subject?->title ?? $attempt->paper->title ?? '');
        $subjectIconType = 'book';
        if (str_contains($subjectNameLower, 'math') || str_contains($subjectNameLower, 'numeracy') || str_contains($subjectNameLower, 'arithmetic')) {
            $subjectIconType = 'calculator';
        } elseif (str_contains($subjectNameLower, 'verbal') || str_contains($subjectNameLower, 'reasoning')) {
            $subjectIconType = 'brain';
        }

        $hasCustomInstruction = !empty(trim(strip_tags((string) $attempt->paper->instruction)));
    @endphp

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Distraction-free Exam Shell -->
    <div class="exam-player-shell">

        <!-- Top Global Navbar -->
        <header class="exam-topbar">
            <div class="exam-topbar-inner">
                <a href="javascript:void(0)" class="exam-brand-link" title="{{ $attempt->paper->title }}">
                    <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Horizontal-Full-Logo.png') }}"
                         alt="Aspire Learners" class="exam-logo-light">
                    <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-horizontal-white-Full-Logo.png') }}"
                         alt="Aspire Learners" class="exam-logo-dark">
                </a>

                <div class="exam-topbar-right">
                    <!-- Notification Bell -->
                    <div class="dropdown">
                        <button type="button" class="exam-bell-btn" id="examBellDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Test Status">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            <span class="exam-bell-badge">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right exam-dropdown-menu shadow-lg border-0" aria-labelledby="examBellDropdown">
                            <div class="px-3 py-2 border-bottom d-flex align-items-center justify-content-between">
                                <span class="font-weight-bold text-dark" style="font-size: 0.85rem;">Test Reminders</span>
                                <span class="badge badge-pill badge-light-primary font-small-1">Active</span>
                            </div>
                            <div class="p-3" style="font-size: 0.82rem; color: #475569; line-height: 1.5;">
                                <div class="mb-2"><strong>Auto-Save Active:</strong> Answers are saved automatically as you work.</div>
                                <div class="mb-2"><strong>Mark for Review:</strong> Flag questions to revisit before submitting.</div>
                                <div><strong>Save &amp; Exit:</strong> Safely save your progress and resume later.</div>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Pill -->
                    <div class="dropdown">
                        <button type="button" class="exam-user-pill dropdown-toggle" id="examUserDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                                 alt="{{ $studentUser->name ?? 'Student User' }}"
                                 class="exam-user-avatar">
                            <div class="exam-user-meta d-none d-sm-flex">
                                <span class="exam-user-name">{{ $studentUser->name ?? 'Student User' }}</span>
                                <span class="exam-user-role">Student</span>
                            </div>
                            <svg class="exam-user-chevron d-none d-sm-block" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right exam-dropdown-menu shadow-lg border-0" aria-labelledby="examUserDropdown">
                            <div class="px-3 py-2 border-bottom">
                                <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $studentUser->name ?? 'Student User' }}</div>
                                <div class="text-muted" style="font-size: 0.76rem;">{{ $studentUser->email ?? '' }}</div>
                            </div>
                            <a class="dropdown-item exam-menu-item theme-toggle-menu-item" href="{{ route('change.theme') }}" id="examThemeToggle">
                                <i class="feather icon-{{ session('theme', 'light') === 'dark' ? 'sun' : 'moon' }} mr-2" id="examThemeIcon"></i>
                                <span id="examThemeLabel">{{ session('theme', 'light') === 'dark' ? 'Light Mode' : 'Dark Mode' }}</span>
                            </a>
                            <button type="button" class="dropdown-item exam-menu-item text-primary font-weight-bold" onclick="submitTestForm('pause')">
                                <i class="feather icon-log-out mr-2"></i>
                                <span>Save &amp; Exit Test</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Exam Container -->
        <main class="exam-main-container">

            <!-- Paper Header Row -->
            <div class="exam-paper-header">
                <div class="exam-paper-title-wrap">
                    <div class="exam-subject-badge">
                        @if($subjectIconType === 'calculator')
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="2" width="16" height="20" rx="2"/>
                                <line x1="8" y1="6" x2="16" y2="6"/>
                                <line x1="16" y1="14" x2="16" y2="18"/>
                                <path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/>
                                <path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/>
                            </svg>
                        @elseif($subjectIconType === 'brain')
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 4.44-1.04"/>
                                <path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-4.44-1.04"/>
                            </svg>
                        @else
                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                        @endif
                    </div>

                    <div class="exam-title-meta">
                        <h1 class="exam-paper-title">{{ $attempt->paper->title }}</h1>
                        <p class="exam-paper-subtitle">
                            <span>{{ $paperSubtitle }}</span>
                            @if($hasCustomInstruction)
                                <button type="button" class="exam-instr-link" data-toggle="modal" data-target="#instructionsModal">
                                    View Instructions
                                </button>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="exam-header-actions">
                    <button type="button" class="btn-save-exit" onclick="submitTestForm('pause')">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        <span>Save &amp; Exit</span>
                    </button>

                    <button type="button" class="btn-submit-test" onclick="submitTestForm('submit')">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                        <span>Submit Test</span>
                    </button>
                </div>
            </div>

            <!-- 6-Stat Summary Bar -->
            <section class="exam-meta-bar" aria-label="Test overview">
                <div class="exam-meta-grid">
                    <!-- 1. Questions -->
                    <div class="exam-meta-item">
                        <div class="exam-meta-icon meta-bg-blue">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="4" y="2" width="16" height="20" rx="2"/>
                                <line x1="8" y1="6" x2="16" y2="6"/>
                                <line x1="8" y1="10" x2="16" y2="10"/>
                                <line x1="8" y1="14" x2="12" y2="14"/>
                                <line x1="8" y1="18" x2="12" y2="18"/>
                            </svg>
                        </div>
                        <div class="exam-meta-text">
                            <span class="exam-meta-label">Questions</span>
                            <span class="exam-meta-value val-blue">{{ $totalQuestionsCount }}</span>
                        </div>
                    </div>

                    <!-- 2. Duration -->
                    <div class="exam-meta-item">
                        <div class="exam-meta-icon meta-bg-amber">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <polyline points="12 7 12 12 15 14"/>
                            </svg>
                        </div>
                        <div class="exam-meta-text">
                            <span class="exam-meta-label">Duration</span>
                            <span class="exam-meta-value val-amber">
                                {{ $attempt->paper->total_time > 0 ? $attempt->paper->total_time . ' mins' : 'Unlimited' }}
                            </span>
                        </div>
                    </div>

                    <!-- 3. Time Left / Elapsed -->
                    <div class="exam-meta-item">
                        <div class="exam-meta-icon meta-bg-green">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="13" r="8"/>
                                <path d="M12 9v4l2 2"/>
                                <path d="M5 3L2 6"/>
                                <path d="M22 6l-3-3"/>
                                <line x1="10" y1="2" x2="14" y2="2"/>
                            </svg>
                        </div>
                        <div class="exam-meta-text">
                            <span class="exam-meta-label">{{ $remainingSeconds !== null ? 'Time Left' : 'Time Elapsed' }}</span>
                            <span class="exam-meta-value val-green" id="digital-clock">00:00</span>
                        </div>
                    </div>

                    <!-- 4. Answered -->
                    <div class="exam-meta-item">
                        <div class="exam-meta-icon meta-bg-green">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <div class="exam-meta-text">
                            <span class="exam-meta-count" id="stat-answered">0</span>
                            <span class="exam-meta-sublabel">Answered</span>
                        </div>
                    </div>

                    <!-- 5. Unanswered -->
                    <div class="exam-meta-item">
                        <div class="exam-meta-icon meta-bg-gray"></div>
                        <div class="exam-meta-text">
                            <span class="exam-meta-count" id="stat-unanswered">{{ $totalQuestionsCount }}</span>
                            <span class="exam-meta-sublabel">Unanswered</span>
                        </div>
                    </div>

                    <!-- 6. Marked for Review -->
                    <div class="exam-meta-item exam-meta-item-last">
                        <div class="exam-meta-icon meta-bg-purple-soft">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="#7c3aed" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                <line x1="4" y1="22" x2="4" y2="15"/>
                            </svg>
                        </div>
                        <div class="exam-meta-text">
                            <span class="exam-meta-count" id="stat-flagged">0</span>
                            <span class="exam-meta-sublabel">Marked for Review</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Two-Column Workspace -->
            <form id="test-taking-form" action="" method="POST" class="mb-0">
                @csrf
                <div class="exam-workspace-grid">

                    <!-- Left Column: Question Card -->
                    <div class="exam-question-col">
                        @forelse($questions as $index => $question)
                            @php
                                $ans = $answers->get($question->id);
                                $marks = $question->pivot->marks ?? $question->marks ?? $attempt->paper->default_marks ?? 1;
                                $isLocked = ($ans && !$attempt->paper->allow_reattempt_question && $ans->confidence !== null && $ans->confidence !== '');
                                $isFlagged = ($ans && $ans->is_flagged);

                                $rawDescription = (string) ($question->description ?? '');
                                $rawTitle = (string) ($question->title ?? '');
                                $cleanTitle = trim(strip_tags($rawTitle));
                                $cleanDesc = trim(strip_tags($rawDescription));

                                $description = $rawDescription;
                                $hasInlineBlanks = false;
                                if ($question->type === 'fill_in_the_blanks' && !empty($description)) {
                                    if (preg_match('/_{3,}/', $description)) {
                                        $hasInlineBlanks = true;
                                        $savedAnswers = [];
                                        if ($ans && $ans->answer_text) {
                                            $decoded = json_decode($ans->answer_text, true);
                                            if (is_array($decoded)) {
                                                $savedAnswers = $decoded;
                                            } else {
                                                $savedAnswers = [$ans->answer_text];
                                            }
                                        }
                                        $blankIndex = 0;
                                        $description = preg_replace_callback('/_{3,}/', function ($match) use (&$blankIndex, $savedAnswers, $question, $index, $isLocked) {
                                            $val = htmlspecialchars((string) ($savedAnswers[$blankIndex] ?? ''), ENT_QUOTES, 'UTF-8');
                                            $inputName = "answers[{$question->id}][answer_text][]";
                                            $inputId = "blank-{$question->id}-{$blankIndex}";
                                            $readonlyAttr = $isLocked ? 'readonly tabindex="-1"' : '';
                                            $lockedClass = $isLocked ? 'is-locked-input' : '';
                                            $html = '<input type="text" name="' . $inputName . '" id="' . $inputId . '" class="inline-blank-input test-input ' . $lockedClass . '" placeholder="Answer ' . ($blankIndex + 1) . '" value="' . $val . '" oninput="checkTextInput(' . ($index + 1) . ', this)" ' . $readonlyAttr . '>';
                                            $blankIndex++;
                                            return $html;
                                        }, $description);
                                    }
                                }

                                // Determine layout: passage + separate prompt (Image 1) vs single stem box (Image 2)
                                $hasSeparatePassage = !$hasInlineBlanks
                                    && $cleanDesc !== ''
                                    && $cleanTitle !== ''
                                    && $cleanDesc !== $cleanTitle
                                    && mb_strlen($cleanDesc) > 120;

                                $hasTitleAndSubDesc = !$hasInlineBlanks
                                    && !$hasSeparatePassage
                                    && $cleanDesc !== ''
                                    && $cleanTitle !== ''
                                    && $cleanDesc !== $cleanTitle;
                            @endphp

                            <div class="exam-question-card q-card {{ $index === 0 ? '' : 'd-none' }} {{ $isLocked ? 'question-locked' : '' }}"
                                 id="q-card-{{ $index + 1 }}"
                                 data-q-num="{{ $index + 1 }}"
                                 data-q-id="{{ $question->id }}"
                                 data-q-type="{{ $question->type }}"
                                 data-is-locked="{{ $isLocked ? 'true' : 'false' }}">

                                <!-- Hidden State Inputs -->
                                <input type="hidden" id="time-spent-{{ $question->id }}" name="answers[{{ $question->id }}][time_spent]" value="{{ $ans ? $ans->time_spent : 0 }}">
                                <input type="hidden" id="is-flagged-{{ $question->id }}" name="answers[{{ $question->id }}][is_flagged]" value="{{ $isFlagged ? 1 : 0 }}">
                                <input type="hidden" id="confidence-{{ $question->id }}" name="answers[{{ $question->id }}][confidence]" value="{{ $ans ? $ans->confidence : '' }}">

                                <!-- Question Card Header -->
                                <div class="q-card-header">
                                    <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                                        <h2 class="q-card-number mb-0">
                                            Question {{ $index + 1 }} of {{ $totalQuestionsCount }}
                                        </h2>
                                        @if($marks > 1)
                                            <span class="q-marks-pill">{{ $marks }} Marks</span>
                                        @endif
                                    </div>

                                    <button type="button"
                                            id="flag-btn-{{ $index + 1 }}"
                                            class="btn-mark-review {{ $isFlagged ? 'is-flagged' : '' }}"
                                            onclick="toggleFlag({{ $index + 1 }})">
                                        <svg class="flag-svg" width="15" height="15" viewBox="0 0 24 24" fill="{{ $isFlagged ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                            <line x1="4" y1="22" x2="4" y2="15"/>
                                        </svg>
                                        <span class="flag-btn-label">{{ $isFlagged ? 'Marked for Review' : 'Mark for Review' }}</span>
                                    </button>
                                </div>

                                <!-- Question Body -->
                                <div class="q-card-body">

                                    @if($hasSeparatePassage)
                                        <!-- Image 1 Style: Passage Box + Question Stem Below -->
                                        <div class="question-stem-box passage-box">
                                            <div class="passage-content">{!! $description !!}</div>
                                        </div>
                                        <div class="question-prompt-heading">
                                            {!! nl2br(e($question->title)) !!}
                                        </div>
                                    @elseif($hasTitleAndSubDesc)
                                        <div class="question-stem-box">
                                            <div class="question-stem-title">{!! nl2br(e($question->title)) !!}</div>
                                            <div class="question-stem-subdesc mt-2">{!! $description !!}</div>
                                        </div>
                                    @elseif($hasInlineBlanks)
                                        <div class="question-stem-box">
                                            @if($cleanTitle !== '' && $cleanTitle !== $cleanDesc)
                                                <div class="question-stem-title mb-2">{!! nl2br(e($question->title)) !!}</div>
                                            @endif
                                            <div class="question-inline-blanks">{!! $description !!}</div>
                                        </div>
                                    @else
                                        <!-- Image 2 Style: Question inside Tinted Box -->
                                        <div class="question-stem-box">
                                            <div class="question-stem-title">
                                                @if($cleanDesc !== '')
                                                    {!! $description !!}
                                                @else
                                                    {!! nl2br(e($question->title)) !!}
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Question Images -->
                                    @if($question->image || ($question->images && count($question->images) > 0))
                                        <div class="question-images-wrapper">
                                            @if($question->image)
                                                <div class="question-img-item">
                                                    <img src="{{ asset('storage/' . $question->image) }}" class="img-fluid rounded-lg border shadow-xs img-zoomable" alt="Question Image">
                                                </div>
                                            @endif
                                            @if($question->images)
                                                @foreach($question->images as $img)
                                                    <div class="question-img-item">
                                                        <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded-lg border shadow-xs img-zoomable" alt="Question Image">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Answer Area -->
                                    <div class="answer-area">
                                        @if(in_array($question->type, ['single_choice_radio', 'multiple_choice']))
                                            @php
                                                $options = $question->options->sortBy('sort_order')->values();
                                                $letterMap = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                                                $isMulti = $question->type === 'multiple_choice';
                                            @endphp

                                            @if($isMulti)
                                                <p class="answer-subhint mb-2">Select all options that apply.</p>
                                            @endif

                                            <div class="options-group-list" role="{{ $isMulti ? 'group' : 'radiogroup' }}">
                                                @foreach($options as $k => $option)
                                                    @php
                                                        $letter = $letterMap[$k] ?? chr(65 + $k);
                                                        if ($isMulti) {
                                                            $isSelected = ($ans && is_array($ans->selected_options) && in_array($option->id, $ans->selected_options));
                                                        } else {
                                                            $isSelected = ($ans && $ans->selected_option_id == $option->id);
                                                        }
                                                    @endphp

                                                    <div class="option-row-item option-card {{ $isMulti ? 'is-checkbox-option' : 'is-radio-option' }} {{ $isSelected ? 'selected-option-card' : '' }}"
                                                         id="option-container-{{ $option->id }}"
                                                         tabindex="{{ $isLocked ? '-1' : '0' }}"
                                                         role="{{ $isMulti ? 'checkbox' : 'radio' }}"
                                                         aria-checked="{{ $isSelected ? 'true' : 'false' }}"
                                                         onclick="@if($isLocked) return; @else selectOption({{ $question->id }}, {{ $option->id }}, '{{ $question->type }}') @endif"
                                                         onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); this.click(); }">

                                                        @if($isMulti)
                                                            <input type="checkbox"
                                                                   id="opt-{{ $option->id }}"
                                                                   name="answers[{{ $question->id }}][selected_options][]"
                                                                   class="d-none test-input checkbox-input-field"
                                                                   value="{{ $option->id }}"
                                                                   {{ $isSelected ? 'checked' : '' }}>
                                                        @else
                                                            <input type="radio"
                                                                   id="opt-{{ $option->id }}"
                                                                   name="answers[{{ $question->id }}][selected_option_id]"
                                                                   class="d-none test-input radio-input-field"
                                                                   value="{{ $option->id }}"
                                                                   {{ $isSelected ? 'checked' : '' }}>
                                                        @endif

                                                        <span class="option-indicator {{ $isMulti ? 'indicator-checkbox' : 'indicator-radio' }}">
                                                            @if($isMulti)
                                                                <svg class="checkbox-check-svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                                                    <polyline points="20 6 9 17 4 12"/>
                                                                </svg>
                                                            @endif
                                                        </span>

                                                        <span class="option-letter-label">{{ $letter }}.</span>

                                                        <div class="option-content-wrap">
                                                            @if($option->option_image)
                                                                <img src="{{ asset('storage/' . $option->option_image) }}" class="option-inline-img img-zoomable" alt="Option {{ $letter }}" onclick="event.stopPropagation()">
                                                            @endif
                                                            @if(!empty($option->option_text))
                                                                <span class="option-text-label">{{ $option->option_text }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($question->type === 'picture_choice')
                                            @php
                                                $options = $question->options->sortBy('sort_order')->values();
                                                $letterMap = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                                            @endphp
                                            <div class="picture-options-grid">
                                                @foreach($options as $k => $option)
                                                    @php
                                                        $letter = $letterMap[$k] ?? chr(65 + $k);
                                                        $isSelected = ($ans && $ans->selected_option_id == $option->id);
                                                    @endphp
                                                    <div class="picture-option-card option-card {{ $isSelected ? 'selected-option-card' : '' }}"
                                                         id="option-container-{{ $option->id }}"
                                                         tabindex="{{ $isLocked ? '-1' : '0' }}"
                                                         role="radio"
                                                         aria-checked="{{ $isSelected ? 'true' : 'false' }}"
                                                         onclick="@if($isLocked) return; @else selectOption({{ $question->id }}, {{ $option->id }}, '{{ $question->type }}') @endif"
                                                         onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); this.click(); }">
                                                        <input type="radio"
                                                               id="opt-{{ $option->id }}"
                                                               name="answers[{{ $question->id }}][selected_option_id]"
                                                               class="d-none test-input radio-input-field"
                                                               value="{{ $option->id }}"
                                                               {{ $isSelected ? 'checked' : '' }}>
                                                        <div class="picture-option-top">
                                                            <span class="option-indicator indicator-radio"></span>
                                                            <span class="option-letter-label">{{ $letter }}.</span>
                                                            @if(!empty($option->option_text))
                                                                <span class="option-text-label">{{ $option->option_text }}</span>
                                                            @endif
                                                        </div>
                                                        @if($option->option_image)
                                                            <div class="picture-option-img-box">
                                                                <img src="{{ asset('storage/' . $option->option_image) }}" alt="Option {{ $letter }}">
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                        @elseif($question->type === 'single_choice_dropdown')
                                            <div class="direct-answer-block">
                                                <label for="dropdown-{{ $question->id }}" class="direct-answer-label">Choose your answer below</label>
                                                <select id="dropdown-{{ $question->id }}"
                                                        name="answers[{{ $question->id }}][selected_option_id]"
                                                        class="direct-answer-input test-input select-input {{ $isLocked ? 'is-locked-input' : '' }}"
                                                        onchange="selectDropdownOption({{ $index + 1 }}, this)"
                                                        @if($isLocked) tabindex="-1" @endif>
                                                    <option value="">-- Choose an option --</option>
                                                    @foreach($question->options->sortBy('sort_order') as $option)
                                                        <option value="{{ $option->id }}" {{ ($ans && $ans->selected_option_id == $option->id) ? 'selected' : '' }}>
                                                            {{ $option->option_text }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        @elseif($question->type === 'matching_text')
                                            @php
                                                $pairs = $question->metadata['matching_pairs'] ?? [];
                                                $rightOptions = collect($pairs)->pluck('right')->shuffle()->all();
                                                $savedMatches = [];
                                                if ($ans && $ans->answer_text) {
                                                    $decoded = json_decode($ans->answer_text, true);
                                                    $savedMatches = is_array($decoded) ? $decoded : [$ans->answer_text];
                                                }
                                            @endphp
                                            <div class="matching-text-container">
                                                <label class="direct-answer-label">Match each item below</label>
                                                <div class="row">
                                                    @foreach($pairs as $pairIndex => $pair)
                                                        <div class="col-md-6 col-12 mb-3">
                                                            <div class="matching-pair-row">
                                                                <span class="matching-left-text">{{ $pair['left'] }}</span>
                                                                <i class="feather icon-arrow-right text-primary mx-2"></i>
                                                                <select name="answers[{{ $question->id }}][answer_text][]"
                                                                        class="form-control test-input select-input matching-select {{ $isLocked ? 'is-locked-input' : '' }}"
                                                                        onchange="selectDropdownOption({{ $index + 1 }}, this)"
                                                                        @if($isLocked) tabindex="-1" @endif>
                                                                    <option value="">-- Match --</option>
                                                                    @foreach($rightOptions as $opt)
                                                                        <option value="{{ $opt }}" {{ ($savedMatches[$pairIndex] ?? '') === $opt ? 'selected' : '' }}>
                                                                            {{ $opt }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                        @elseif($question->type === 'matching_drag_drop')
                                            @php
                                                $pairs = $question->metadata['matching_pairs'] ?? [];
                                                $savedMatches = [];
                                                if ($ans && $ans->answer_text) {
                                                    $decoded = json_decode($ans->answer_text, true);
                                                    $savedMatches = is_array($decoded) ? $decoded : [$ans->answer_text];
                                                }
                                                $matchedRightItems = array_filter($savedMatches);
                                                $allRightItems = collect($pairs)->pluck('right')->all();
                                                $unmatchedRightItems = collect($allRightItems)
                                                    ->filter(fn($item) => !in_array($item, $matchedRightItems))
                                                    ->shuffle()
                                                    ->all();
                                            @endphp
                                            <div class="matching-drag-drop-container" id="matching-container-{{ $question->id }}">
                                                <div class="row">
                                                    <div class="col-md-7 col-12 mb-3 mb-md-0">
                                                        <label class="direct-answer-label">Matching Targets</label>
                                                        @foreach($pairs as $pairIndex => $pair)
                                                            @php $savedMatchVal = $savedMatches[$pairIndex] ?? ''; @endphp
                                                            <input type="hidden"
                                                                   name="answers[{{ $question->id }}][answer_text][]"
                                                                   id="matching-hidden-{{ $question->id }}-{{ $pairIndex }}"
                                                                   value="{{ $savedMatchVal }}"
                                                                   class="matching-hidden-input">
                                                            <div class="matching-target-row">
                                                                <div class="matching-target-label">{{ $pair['left'] }}</div>
                                                                <i class="feather icon-link text-primary mx-2"></i>
                                                                <div class="drop-zone"
                                                                     data-question-id="{{ $question->id }}"
                                                                     data-pair-index="{{ $pairIndex }}"
                                                                     ondragover="allowDrop(event)"
                                                                     ondrop="handleDrop(event)"
                                                                     onclick="handleDropzoneClick(this)">
                                                                    @if($savedMatchVal)
                                                                        <div class="draggable-card matched-card"
                                                                             draggable="{{ $isLocked ? 'false' : 'true' }}"
                                                                             ondragstart="handleDragStart(event)"
                                                                             onclick="handleCardClick(event, this)"
                                                                             data-value="{{ $savedMatchVal }}"
                                                                             data-question-id="{{ $question->id }}">
                                                                            {{ $savedMatchVal }}
                                                                        </div>
                                                                    @else
                                                                        <span class="drag-placeholder">Drop or click here</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="col-md-5 col-12">
                                                        <label class="direct-answer-label">Drag or Click Items</label>
                                                        <div class="draggables-pool"
                                                             data-question-id="{{ $question->id }}"
                                                             ondragover="allowDrop(event)"
                                                             ondrop="handleReturnToPool(event)"
                                                             onclick="handlePoolClick(this)">
                                                            @foreach($unmatchedRightItems as $rightItem)
                                                                <div class="draggable-card pool-card"
                                                                     draggable="{{ $isLocked ? 'false' : 'true' }}"
                                                                     ondragstart="handleDragStart(event)"
                                                                     onclick="handleCardClick(event, this)"
                                                                     data-value="{{ $rightItem }}"
                                                                     data-question-id="{{ $question->id }}">
                                                                    {{ $rightItem }}
                                                                </div>
                                                            @endforeach
                                                            @if(count($unmatchedRightItems) === 0 && count($matchedRightItems) === 0)
                                                                <span class="drag-placeholder">No matching items found.</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        @else
                                            <!-- Image 2 Style: Direct Answer / Input Based -->
                                            @if($question->type === 'fill_in_the_blanks' && $hasInlineBlanks)
                                                <p class="direct-answer-hint mb-0">Fill in the blanks in the text above.</p>
                                            @elseif($question->type === 'free_text')
                                                @php $wordLimit = $question->metadata['word_limit'] ?? null; @endphp
                                                <div class="direct-answer-block">
                                                    <label for="answer-input-{{ $question->id }}" class="direct-answer-label">Type your answer below</label>
                                                    <textarea id="answer-input-{{ $question->id }}"
                                                              name="answers[{{ $question->id }}][answer_text]"
                                                              class="direct-answer-textarea test-input text-input {{ $isLocked ? 'is-locked-input' : '' }}"
                                                              rows="5"
                                                              placeholder="Enter your answer here"
                                                              oninput="checkTextInput({{ $index + 1 }}, this); updateWordCount({{ $question->id }}, this, {{ $wordLimit ? (int)$wordLimit : 'null' }})"
                                                              @if($isLocked) readonly tabindex="-1" @endif>{{ $ans ? $ans->answer_text : '' }}</textarea>
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <span class="direct-answer-hint">Write your answer clearly.</span>
                                                        <span class="direct-answer-hint" id="word-count-{{ $question->id }}">
                                                            @if($wordLimit)
                                                                Max {{ $wordLimit }} words
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="direct-answer-block">
                                                    <label for="answer-input-{{ $question->id }}" class="direct-answer-label">Type your answer below</label>
                                                    <input type="text"
                                                           id="answer-input-{{ $question->id }}"
                                                           name="answers[{{ $question->id }}][answer_text]"
                                                           class="direct-answer-input test-input text-input {{ $isLocked ? 'is-locked-input' : '' }}"
                                                           placeholder="Enter your answer here"
                                                           value="{{ $ans ? $ans->answer_text : '' }}"
                                                           oninput="checkTextInput({{ $index + 1 }}, this)"
                                                           autocomplete="off"
                                                           @if($isLocked) readonly tabindex="-1" @endif>
                                                    <p class="direct-answer-hint mt-2 mb-0">Write only the final answer.</p>
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Instant Feedback Card (if paper allows instant feedback) -->
                                    @php
                                        $showFeedback = false;
                                        $feedbackClass = '';
                                        $feedbackTitle = '';
                                        $correctValText = '';
                                        $explanationText = $question->explanation;

                                        if ($attempt->paper->allow_instant_feedback && $ans && $ans->confidence !== null && $ans->confidence !== '') {
                                            $showFeedback = true;
                                            $isCorrect = $ans->is_correct;
                                            $feedbackClass = $isCorrect ? 'feedback-correct' : 'feedback-incorrect';
                                            $feedbackTitle = $isCorrect ? 'Correct!' : 'Incorrect';

                                            if ($question->type === 'fill_in_the_blanks') {
                                                $correctValText = implode(', ', $question->metadata['blank_answers'] ?? []);
                                                if (empty($correctValText)) {
                                                    $correctValText = $question->options->where('is_correct', true)->first()?->option_text ?? '';
                                                }
                                            } elseif ($question->type === 'matching_drag_drop' || $question->type === 'matching_text') {
                                                $pairs = $question->metadata['matching_pairs'] ?? [];
                                                $pairTexts = [];
                                                foreach ($pairs as $pair) {
                                                    $pairTexts[] = $pair['left'] . ' ➔ ' . $pair['right'];
                                                }
                                                $correctValText = implode(', ', $pairTexts);
                                            } else {
                                                $correctValText = implode(', ', $question->options->where('is_correct', true)->pluck('option_text')->toArray());
                                            }
                                        }
                                    @endphp

                                    <div class="instant-feedback-box mt-4 {{ $showFeedback ? '' : 'd-none' }} {{ $feedbackClass }}" id="feedback-box-{{ $question->id }}">
                                        <div class="feedback-title">{{ $feedbackTitle }}</div>
                                        <div class="feedback-correct-row {{ $correctValText !== '' ? '' : 'd-none' }}">
                                            <strong>Correct Answer:</strong> <span class="feedback-correct-answer">{{ $correctValText }}</span>
                                        </div>
                                        <div class="feedback-explanation {{ $explanationText || ($question->explanation_images && count($question->explanation_images) > 0) ? '' : 'd-none' }}" id="feedback-explanation-container-{{ $question->id }}">
                                            <strong>Explanation:</strong>
                                            <div class="explanation-content mt-1">{!! $explanationText !!}</div>
                                            @if($question->explanation_images && count($question->explanation_images) > 0)
                                                <div class="explanation-images-wrapper mt-2 d-flex flex-wrap align-items-center" style="gap: 8px;">
                                                    @foreach($question->explanation_images as $expImg)
                                                        <img src="{{ asset('storage/' . $expImg) }}" class="img-fluid rounded border img-zoomable" style="max-height: 120px; object-fit: contain; cursor: zoom-in;" alt="Explanation Image">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Footer: Previous & Next Question -->
                                <div class="q-card-footer">
                                    <button type="button"
                                            class="btn-nav-prev {{ $index === 0 ? 'is-disabled' : '' }}"
                                            onclick="prevQuestion()"
                                            {{ $index === 0 ? 'disabled' : '' }}>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="19" y1="12" x2="5" y2="12"/>
                                            <polyline points="12 19 5 12 12 5"/>
                                        </svg>
                                        <span>Previous</span>
                                    </button>

                                    <div class="d-flex align-items-center" style="gap: 10px;">
                                        @if($attempt->paper->allow_instant_feedback && !$isLocked)
                                            <button type="button"
                                                    class="btn-check-answer"
                                                    id="check-answer-btn-{{ $question->id }}"
                                                    onclick="checkInstantFeedback({{ $index + 1 }}, {{ $question->id }})">
                                                Check Answer
                                            </button>
                                        @endif

                                        @if($index + 1 < $totalQuestionsCount)
                                            <button type="button" class="btn-nav-next" onclick="nextQuestion()">
                                                <span>Next Question</span>
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                                    <polyline points="12 5 19 12 12 19"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button type="button" class="btn-nav-next" onclick="submitTestForm('submit')">
                                                <span>Submit Test</span>
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="22" y1="2" x2="11" y2="13"/>
                                                    <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="exam-question-card text-center py-5">
                                <h3 class="q-card-number mb-2">No Questions Available</h3>
                                <p class="text-muted mb-4">This paper currently has no questions configured.</p>
                                <button type="button" class="btn-save-exit mx-auto" onclick="submitTestForm('pause')">
                                    Save &amp; Exit
                                </button>
                            </div>
                        @endforelse
                    </div>

                    <!-- Right Column: Progress & Question Navigator -->
                    <aside class="exam-sidebar-col">
                        <!-- Progress Card -->
                        <div class="exam-sidebar-card mb-3">
                            <h3 class="sidebar-card-title">Progress</h3>
                            <div class="progress-meta-row">
                                <span id="progress-text">0 of {{ $totalQuestionsCount }} answered</span>
                                <strong id="progress-percent">0%</strong>
                            </div>
                            <div class="exam-progress-track">
                                <div class="exam-progress-fill" id="progress-bar-fill" style="width: 0%;"></div>
                            </div>
                        </div>

                        <!-- Question Navigator Card -->
                        <div class="exam-sidebar-card">
                            <h3 class="sidebar-card-title">Question Navigator</h3>

                            <div class="q-nav-grid" id="question-navigator-grid">
                                @foreach($questions as $index => $question)
                                    @php
                                        $ans = $answers->get($question->id);
                                        $hasSavedAns = false;
                                        if ($ans) {
                                            $hasSavedText = false;
                                            if (is_string($ans->answer_text) && trim($ans->answer_text) !== '') {
                                                $decodedAns = json_decode($ans->answer_text, true);
                                                if (is_array($decodedAns)) {
                                                    $hasSavedText = count(array_filter($decodedAns, fn($v) => trim((string) $v) !== '')) > 0;
                                                } else {
                                                    $hasSavedText = true;
                                                }
                                            }
                                            $hasSavedAns = !empty($ans->selected_option_id)
                                                || (is_array($ans->selected_options) && count($ans->selected_options) > 0)
                                                || $hasSavedText;
                                        }
                                        $isFlagged = ($ans && $ans->is_flagged);
                                    @endphp
                                    <button type="button"
                                            id="nav-btn-{{ $index + 1 }}"
                                            class="q-nav-btn {{ $index === 0 ? 'is-current' : '' }} {{ $hasSavedAns ? 'is-answered' : '' }} {{ $isFlagged ? 'is-flagged' : '' }}"
                                            onclick="jumpToQuestion({{ $index + 1 }})"
                                            aria-label="Go to question {{ $index + 1 }}">
                                        <span class="q-nav-num">{{ $index + 1 }}</span>
                                        <span class="q-nav-flag {{ $isFlagged ? '' : 'd-none' }}" aria-hidden="true">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                                <line x1="4" y1="22" x2="4" y2="15"/>
                                            </svg>
                                        </span>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Legend -->
                            <div class="q-nav-legend">
                                <div class="legend-item">
                                    <span class="legend-dot legend-dot-current"></span>
                                    <span>Current Question</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot legend-dot-answered"></span>
                                    <span>Answered</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot legend-dot-unanswered"></span>
                                    <span>Unanswered</span>
                                </div>
                                <div class="legend-item">
                                    <span class="legend-dot legend-dot-flagged"></span>
                                    <span>Marked for Review</span>
                                </div>
                            </div>
                        </div>
                    </aside>

                </div>
            </form>

        </main>
    </div>

    <!-- Instructions Modal -->
    @if($hasCustomInstruction)
        <div class="modal fade text-left" id="instructionsModal" tabindex="-1" role="dialog" aria-labelledby="instructionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: 0;">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                        <h5 class="modal-title text-white font-weight-bold" id="instructionsModalLabel">Paper Instructions</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-dark p-4" style="line-height: 1.65; font-size: 0.95rem;">
                        {!! $attempt->paper->instruction !!}
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 8px;">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Pause / Save & Exit Confirmation Modal -->
    <div class="modal fade" id="pauseConfirmModal" tabindex="-1" role="dialog" aria-labelledby="pauseConfirmModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered pause-modal-dialog" role="document">
            <div class="modal-content pause-modal-content">
                <button type="button" class="pause-modal-close-btn" data-dismiss="modal" aria-label="Close">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>

                <div class="pause-modal-body">
                    <!-- Pause Cloud Illustration -->
                    <div class="pause-modal-illustration" aria-hidden="true">
                        <svg width="240" height="96" viewBox="0 0 240 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="pauseCloudGrad" x1="120" y1="34" x2="120" y2="88" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#EBF3FF"/>
                                    <stop offset="100%" stop-color="#F7FAFF"/>
                                </linearGradient>
                            </defs>
                            <!-- Soft Clouds -->
                            <path d="M52 84H188C197.941 84 206 75.9411 206 66C206 56.6976 198.943 49.0468 189.882 48.1003C186.103 34.2367 173.413 24 158.3 24C151.14 24 144.519 26.2925 139.124 30.1816C133.482 24.4841 125.647 21 117 21C102.771 21 90.8923 30.8919 87.7438 44.1782C84.7491 42.7852 81.4119 42 77.9 42C65.4736 42 55.4 52.0736 55.4 64.5C55.4 65.1714 55.4294 65.8359 55.487 66.4926C47.8295 67.6285 42 74.2297 42 82.2C42 83.2 42.4 84 52 84Z" fill="url(#pauseCloudGrad)"/>
                            <!-- Accent Dashes -->
                            <line x1="64" y1="29" x2="72" y2="33" stroke="#7EB2FF" stroke-width="3" stroke-linecap="round"/>
                            <line x1="168" y1="28" x2="174" y2="23" stroke="#7EB2FF" stroke-width="3" stroke-linecap="round"/>
                            <line x1="173" y1="37" x2="180" y2="36" stroke="#7EB2FF" stroke-width="3" stroke-linecap="round"/>
                            <line x1="171" y1="45" x2="177" y2="47" stroke="#7EB2FF" stroke-width="3" stroke-linecap="round"/>
                            <!-- Central Circle -->
                            <circle cx="120" cy="46" r="36" fill="#CFE2FF"/>
                            <!-- Pause Bars -->
                            <rect x="106" y="30" width="10" height="32" rx="4" fill="#1D5DF0"/>
                            <rect x="124" y="30" width="10" height="32" rx="4" fill="#1D5DF0"/>
                        </svg>
                    </div>

                    <h2 class="pause-modal-title" id="pauseConfirmModalTitle">Pause Test?</h2>
                    <p class="pause-modal-desc">
                        Your answers and progress will be saved. You can continue this test later from where you stopped.
                    </p>

                    <!-- 3-Column Attempt Summary -->
                    <div class="pause-summary-box">
                        <div class="pause-summary-item">
                            <div class="pause-summary-icon pause-icon-blue">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="4" y="3" width="16" height="18" rx="2"/>
                                    <line x1="8" y1="8" x2="16" y2="8"/>
                                    <line x1="8" y1="12" x2="16" y2="12"/>
                                    <line x1="8" y1="16" x2="12" y2="16"/>
                                </svg>
                            </div>
                            <span class="pause-summary-label">Questions Answered</span>
                            <span class="pause-summary-val val-dark" id="pause-modal-answered">0 / {{ $totalQuestionsCount }}</span>
                        </div>

                        <div class="pause-summary-item">
                            <div class="pause-summary-icon pause-icon-green">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="13" r="8"/>
                                    <path d="M12 9v4l2 2"/>
                                    <line x1="10" y1="2" x2="14" y2="2"/>
                                </svg>
                            </div>
                            <span class="pause-summary-label">{{ $remainingSeconds !== null ? 'Time Remaining' : 'Time Elapsed' }}</span>
                            <span class="pause-summary-val val-green" id="pause-modal-time">00:00</span>
                        </div>

                        <div class="pause-summary-item pause-summary-item-last">
                            <div class="pause-summary-icon pause-icon-purple">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="#7c3aed" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                    <line x1="4" y1="22" x2="4" y2="15"/>
                                </svg>
                            </div>
                            <span class="pause-summary-label">Marked for Review</span>
                            <span class="pause-summary-val val-purple" id="pause-modal-flagged">0</span>
                        </div>
                    </div>

                    <!-- Modal Action Buttons -->
                    <div class="pause-modal-actions">
                        <button type="button" class="btn-pause-continue" data-dismiss="modal">
                            Continue Test
                        </button>
                        <button type="button" class="btn-pause-save-exit" id="confirm-save-exit-btn" onclick="confirmSaveAndExit()">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8"/>
                                <polyline points="17 16 21 12 17 8"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            <span>Save &amp; Exit</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Override default theme shell */
        .header-navbar,
        .main-menu,
        .horizontal-menu-wrapper,
        .footer,
        .content-header,
        .sidenav-overlay,
        .drag-target,
        .header-navbar-shadow {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            height: 0 !important;
            width: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        html, body {
            font-family: 'Inter', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            background-color: #f2f6fc !important;
            margin: 0 !important;
            padding: 0 !important;
            min-height: 100% !important;
        }

        body.vertical-layout.navbar-floating .app-content,
        body.horizontal-layout.navbar-floating .app-content,
        body.student-theme-active .app-content,
        .app-content,
        .content-wrapper,
        .content-body {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            min-height: 100vh !important;
            background-color: #f2f6fc !important;
        }

        .exam-player-shell {
            position: relative;
            z-index: 1035;
            min-height: 100vh;
            background-color: #f2f6fc;
            display: flex;
            flex-direction: column;
        }

        .swal2-container {
            z-index: 999999 !important;
        }

        /* Top Navbar */
        .exam-topbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e6edf7;
            box-shadow: 0 1px 6px rgba(15, 23, 42, 0.03);
            position: sticky;
            top: 0;
            z-index: 1038;
        }

        .exam-topbar-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .exam-brand-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .exam-logo-light,
        .exam-logo-dark {
            height: 36px;
            width: auto;
            object-fit: contain;
        }

        .exam-logo-dark {
            display: none;
        }

        .exam-topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .exam-bell-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: none;
            background: transparent;
            color: #1e3a8a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .exam-bell-btn:hover {
            background-color: #f1f5fb;
        }

        .exam-bell-badge {
            position: absolute;
            top: 3px;
            right: 3px;
            min-width: 17px;
            height: 17px;
            border-radius: 999px;
            background-color: #ef4444;
            color: #ffffff;
            font-size: 0.65rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid #ffffff;
        }

        .exam-user-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            border: none;
            padding: 4px 8px;
            border-radius: 999px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .exam-user-pill::after {
            display: none !important;
        }

        .exam-user-pill:hover {
            background-color: #f1f5fb;
        }

        .exam-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dbeafe;
        }

        .exam-user-meta {
            flex-direction: column;
            text-align: left;
            line-height: 1.2;
        }

        .exam-user-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0b1f51;
        }

        .exam-user-role {
            font-size: 0.72rem;
            font-weight: 500;
            color: #64748b;
        }

        .exam-user-chevron {
            color: #64748b;
        }

        .exam-dropdown-menu {
            border-radius: 12px;
            min-width: 240px;
            margin-top: 8px;
        }

        .exam-menu-item {
            padding: 9px 16px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
        }

        /* Main Container */
        .exam-main-container {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
            padding: 22px 24px 36px;
            flex: 1;
        }

        /* Paper Header Row */
        .exam-paper-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .exam-paper-title-wrap {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
            flex: 1;
        }

        .exam-subject-badge {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.24);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .exam-title-meta {
            min-width: 0;
        }

        .exam-paper-title {
            color: #0b1f51;
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -0.4px;
            line-height: 1.25;
            margin: 0 0 2px;
        }

        .exam-paper-subtitle {
            color: #64748b;
            font-size: 0.92rem;
            font-weight: 500;
            margin: 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .exam-instr-link {
            background: none;
            border: none;
            padding: 0;
            color: #2563eb;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: underline;
        }

        .exam-header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .btn-save-exit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #ffffff;
            color: #0b1f51;
            border: 1px solid #dbe4f0;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-save-exit:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-submit-test {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #1677ff 0%, #0b5ed7 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(22, 119, 255, 0.28);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-submit-test:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(22, 119, 255, 0.36);
        }

        /* 6-Stat Summary Bar */
        .exam-meta-bar {
            background-color: #ffffff;
            border-radius: 14px;
            border: 1px solid #e6edf7;
            box-shadow: 0 3px 14px rgba(15, 23, 42, 0.04);
            padding: 14px 18px;
            margin-bottom: 20px;
        }

        .exam-meta-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            align-items: center;
            gap: 8px;
        }

        .exam-meta-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 12px;
            border-right: 1px solid #e6edf7;
            min-width: 0;
        }

        .exam-meta-item-last {
            border-right: none;
        }

        .exam-meta-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .meta-bg-blue {
            background-color: #1677ff;
            box-shadow: 0 3px 10px rgba(22, 119, 255, 0.2);
        }

        .meta-bg-amber {
            background-color: #f59e0b;
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.2);
        }

        .meta-bg-green {
            background-color: #16a34a;
            box-shadow: 0 3px 10px rgba(22, 163, 74, 0.2);
        }

        .meta-bg-gray {
            background-color: #e9eef5;
        }

        .meta-bg-purple-soft {
            background-color: #ede9fe;
        }

        .exam-meta-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .exam-meta-label {
            font-size: 0.76rem;
            color: #64748b;
            font-weight: 500;
            line-height: 1.2;
        }

        .exam-meta-value {
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1.25;
            margin-top: 2px;
            font-variant-numeric: tabular-nums;
        }

        .val-blue { color: #1677ff; }
        .val-amber { color: #f59e0b; }
        .val-green { color: #16a34a; }
        .val-warning { color: #d97706 !important; }
        .val-danger { color: #dc2626 !important; }

        .exam-meta-count {
            font-size: 1.02rem;
            font-weight: 800;
            color: #0b1f51;
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .exam-meta-sublabel {
            font-size: 0.76rem;
            color: #64748b;
            font-weight: 500;
            line-height: 1.2;
            margin-top: 2px;
            white-space: nowrap;
        }

        /* Two-Column Workspace */
        .exam-workspace-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 20px;
            align-items: start;
        }

        /* Question Card */
        .exam-question-card {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e6edf7;
            box-shadow: 0 3px 16px rgba(15, 23, 42, 0.04);
            padding: 24px 28px;
            display: flex;
            flex-direction: column;
            min-height: 490px;
        }

        .q-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .q-card-number {
            color: #0b1f51;
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.2px;
        }

        .q-marks-pill {
            background-color: #f1f5fb;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 2px 10px;
            font-size: 0.74rem;
            font-weight: 600;
        }

        .btn-mark-review {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background-color: #f8fafc;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 7px 14px;
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-mark-review:hover {
            background-color: #f1f5f9;
            border-color: #cbd5e1;
        }

        .btn-mark-review.is-flagged {
            background-color: #f3e8ff;
            border-color: #c084fc;
            color: #7c3aed;
        }

        /* Question Box & Stem */
        .q-card-body {
            flex: 1;
        }

        .question-stem-box {
            background-color: #f1f5fb;
            border-radius: 12px;
            padding: 18px 22px;
            margin-bottom: 18px;
        }

        .question-stem-title {
            color: #0b1f51;
            font-size: 1.04rem;
            font-weight: 700;
            line-height: 1.55;
        }

        .question-stem-title p:last-child,
        .passage-content p:last-child,
        .question-stem-subdesc p:last-child {
            margin-bottom: 0;
        }

        .passage-content {
            color: #1e293b;
            font-size: 0.94rem;
            line-height: 1.65;
        }

        .question-stem-subdesc {
            color: #334155;
            font-size: 0.94rem;
            line-height: 1.55;
        }

        .question-prompt-heading {
            color: #0b1f51;
            font-size: 1.05rem;
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .question-inline-blanks {
            color: #0b1f51;
            font-size: 1rem;
            font-weight: 500;
            line-height: 2.1;
        }

        .inline-blank-input {
            display: inline-block;
            width: 160px;
            height: 34px;
            padding: 4px 10px;
            margin: 0 4px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            background-color: #ffffff;
            color: #0b1f51;
            font-size: 0.94rem;
            font-weight: 600;
            vertical-align: middle;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .inline-blank-input:focus {
            border-color: #1677ff;
            box-shadow: 0 0 0 3px rgba(22, 119, 255, 0.14);
        }

        .question-images-wrapper {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 14px;
            margin-bottom: 18px;
        }

        .question-img-item img {
            max-height: 220px;
            object-fit: contain;
            cursor: zoom-in;
            transition: transform 0.2s ease;
        }

        /* Image 1: Option-Based List */
        .answer-subhint {
            font-size: 0.84rem;
            color: #64748b;
            font-weight: 500;
        }

        .options-group-list {
            border: 1px solid #dfe7f2;
            border-radius: 12px;
            overflow: hidden;
            background-color: #ffffff;
        }

        .option-row-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid #eef2f7;
            cursor: pointer;
            transition: background-color 0.15s ease, box-shadow 0.15s ease;
            user-select: none;
            outline: none;
        }

        .option-row-item:last-child {
            border-bottom: none;
        }

        .option-row-item:hover {
            background-color: #f8fbff;
        }

        .option-row-item:focus-visible {
            box-shadow: inset 0 0 0 2px #93c5fd;
        }

        .option-row-item.selected-option-card {
            background-color: #e8f1ff;
            box-shadow: inset 0 0 0 1.5px #4d90fe;
        }

        .option-indicator {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            margin-top: 2px;
            border: 1.6px solid #cbd5e1;
            background-color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .indicator-radio {
            border-radius: 50%;
        }

        .indicator-checkbox {
            border-radius: 5px;
        }

        .checkbox-check-svg {
            opacity: 0;
            transform: scale(0.7);
            transition: all 0.15s ease;
        }

        .selected-option-card .indicator-radio {
            border-color: #1677ff;
            background-color: #1677ff;
            box-shadow: inset 0 0 0 4px #1677ff;
            position: relative;
        }

        .selected-option-card .indicator-radio::after {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background-color: #ffffff;
            display: block;
        }

        .selected-option-card .indicator-checkbox {
            border-color: #1677ff;
            background-color: #1677ff;
        }

        .selected-option-card .checkbox-check-svg {
            opacity: 1;
            transform: scale(1);
        }

        .option-letter-label {
            color: #0b1f51;
            font-weight: 700;
            font-size: 0.94rem;
            min-width: 22px;
            flex-shrink: 0;
            line-height: 1.5;
        }

        .option-content-wrap {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .option-text-label {
            color: #1e293b;
            font-size: 0.94rem;
            font-weight: 500;
            line-height: 1.5;
        }

        .option-inline-img {
            max-height: 60px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        /* Picture Choice */
        .picture-options-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .picture-option-card {
            border: 1.5px solid #dfe7f2;
            border-radius: 12px;
            padding: 14px;
            background-color: #ffffff;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .picture-option-card:hover {
            border-color: #93c5fd;
            background-color: #f8fbff;
        }

        .picture-option-card.selected-option-card {
            background-color: #e8f1ff;
            border-color: #2563eb;
            box-shadow: 0 3px 12px rgba(37, 99, 235, 0.12);
        }

        .picture-option-top {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .picture-option-img-box {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 8px;
        }

        .picture-option-img-box img {
            max-height: 140px;
            max-width: 100%;
            object-fit: contain;
        }

        /* Image 2: Direct Answer / Input Based */
        .direct-answer-block {
            margin-top: 6px;
        }

        .direct-answer-label {
            display: block;
            color: #0b1f51;
            font-size: 0.96rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .direct-answer-input {
            width: 100%;
            height: 48px;
            padding: 10px 16px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            background-color: #ffffff;
            color: #0b1f51;
            font-size: 0.98rem;
            font-weight: 500;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .direct-answer-input::placeholder,
        .direct-answer-textarea::placeholder {
            color: #94a3b8;
        }

        .direct-answer-input:focus,
        .direct-answer-textarea:focus {
            border-color: #1677ff;
            box-shadow: 0 0 0 3px rgba(22, 119, 255, 0.14);
        }

        .direct-answer-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            background-color: #ffffff;
            color: #0b1f51;
            font-size: 0.96rem;
            font-weight: 500;
            line-height: 1.55;
            outline: none;
            resize: vertical;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .direct-answer-hint {
            color: #64748b;
            font-size: 0.84rem;
            font-weight: 500;
        }

        .is-locked-input {
            background-color: #f1f5f9 !important;
            border-color: #cbd5e1 !important;
            pointer-events: none;
            color: #64748b !important;
        }

        /* Matching Question Types */
        .matching-pair-row,
        .matching-target-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border: 1px solid #dfe7f2;
            border-radius: 10px;
            background-color: #f8fafc;
            margin-bottom: 10px;
        }

        .matching-left-text,
        .matching-target-label {
            color: #0b1f51;
            font-weight: 600;
            font-size: 0.9rem;
            flex: 1;
        }

        .matching-select {
            width: 185px;
            height: 38px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .drop-zone {
            border: 2px dashed #60a5fa;
            border-radius: 8px;
            width: 185px;
            min-height: 42px;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .drop-zone:hover,
        .drop-zone.drag-active {
            background-color: #eff6ff;
            border-color: #1677ff;
        }

        .drag-placeholder {
            font-size: 0.8rem;
            color: #94a3b8;
        }

        .draggables-pool {
            min-height: 140px;
            border: 1px solid #dfe7f2;
            border-radius: 10px;
            background-color: #f8fafc;
            padding: 12px;
            display: flex;
            flex-wrap: wrap;
            align-content: flex-start;
            gap: 8px;
        }

        .draggable-card {
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: grab;
            transition: all 0.15s ease;
        }

        .draggable-card.pool-card {
            background-color: #ffffff;
            color: #1d4ed8;
            border: 1.5px solid #93c5fd;
        }

        .draggable-card.matched-card {
            background-color: #1677ff;
            color: #ffffff;
            width: 100%;
            text-align: center;
        }

        .draggable-card.selected-card {
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.28);
            border-color: #16a34a !important;
        }

        /* Instant Feedback */
        .instant-feedback-box {
            border-radius: 12px;
            padding: 16px 18px;
            border: 1.5px solid transparent;
        }

        .instant-feedback-box.feedback-correct {
            background-color: #f0fdf4;
            border-color: #86efac;
            color: #166534;
        }

        .instant-feedback-box.feedback-incorrect {
            background-color: #fef2f2;
            border-color: #fca5a5;
            color: #991b1b;
        }

        .feedback-title {
            font-weight: 800;
            font-size: 1rem;
            margin-bottom: 6px;
        }

        .feedback-correct-row {
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .feedback-explanation {
            background-color: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 0.88rem;
            line-height: 1.55;
        }

        /* Question Card Footer */
        .q-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: auto;
            padding-top: 28px;
        }

        .btn-nav-prev {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #ffffff;
            color: #0b1f51;
            border: 1px solid #dbe4f0;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-nav-prev:hover:not(.is-disabled) {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-nav-prev.is-disabled,
        .btn-nav-prev:disabled {
            background-color: #ebf1fa;
            border-color: transparent;
            color: #93a7c4;
            cursor: not-allowed;
        }

        .btn-check-answer {
            display: inline-flex;
            align-items: center;
            background-color: #eef4ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-check-answer:hover {
            background-color: #dbeafe;
        }

        .btn-nav-next {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #1677ff 0%, #0b5ed7 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 22px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(22, 119, 255, 0.26);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-nav-next:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(22, 119, 255, 0.35);
        }

        /* Right Sidebar Cards */
        .exam-sidebar-card {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid #e6edf7;
            box-shadow: 0 3px 16px rgba(15, 23, 42, 0.04);
            padding: 20px 22px;
        }

        .sidebar-card-title {
            color: #0b1f51;
            font-size: 1.05rem;
            font-weight: 800;
            margin: 0 0 12px;
            letter-spacing: -0.2px;
        }

        .progress-meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.86rem;
            color: #334155;
            margin-bottom: 10px;
        }

        .progress-meta-row strong {
            color: #0b1f51;
            font-weight: 700;
        }

        .exam-progress-track {
            width: 100%;
            height: 10px;
            background-color: #e9eef6;
            border-radius: 999px;
            overflow: hidden;
        }

        .exam-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #16a34a 0%, #22c55e 100%);
            border-radius: 999px;
            transition: width 0.3s ease;
        }

        /* Question Navigator Grid */
        .q-nav-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 8px;
            max-height: 310px;
            overflow-y: auto;
            padding: 2px;
        }

        .q-nav-btn {
            position: relative;
            height: 40px;
            border-radius: 8px;
            border: 1.5px solid transparent;
            background-color: #f1f5fb;
            color: #0b1f51;
            font-size: 0.88rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .q-nav-btn:hover {
            background-color: #e2eaf6;
        }

        .q-nav-btn.is-answered {
            background-color: #dcfce7;
            color: #15803d;
            border-color: #bbf7d0;
        }

        .q-nav-btn.is-flagged {
            background-color: #f3e8ff;
            color: #7e22ce;
            border-color: #d8b4fe;
        }

        .q-nav-btn.is-answered.is-flagged {
            background-color: #f3e8ff;
            color: #6b21a8;
            border-color: #86efac;
        }

        .q-nav-btn.is-current {
            border-color: #1677ff !important;
            box-shadow: 0 0 0 2px rgba(22, 119, 255, 0.15);
        }

        .q-nav-btn.is-current:not(.is-answered):not(.is-flagged) {
            background-color: #e8f1ff;
            color: #1d4ed8;
        }

        .q-nav-flag {
            position: absolute;
            top: 3px;
            right: 4px;
            color: #7c3aed;
            line-height: 1;
            display: inline-flex;
        }

        /* Legend */
        .q-nav-legend {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px 12px;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #eef2f7;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #334155;
        }

        .legend-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .legend-dot-current {
            background-color: #e8f1ff;
            border: 1.5px solid #1677ff;
        }

        .legend-dot-answered {
            background-color: #4ade80;
        }

        .legend-dot-unanswered {
            background-color: #e2e8f0;
        }

        .legend-dot-flagged {
            background-color: #c084fc;
        }

        /* Responsive Layout */
        @media (max-width: 1199.98px) {
            .exam-meta-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 12px;
            }
            .exam-meta-item {
                border-right: none;
                background-color: #f8fafd;
                border-radius: 10px;
                padding: 8px 12px;
            }
        }

        @media (max-width: 991.98px) {
            .exam-workspace-grid {
                grid-template-columns: 1fr;
            }
            .exam-question-card {
                min-height: auto;
            }
        }

        @media (max-width: 575.98px) {
            .exam-topbar-inner {
                padding: 10px 14px;
            }
            .exam-main-container {
                padding: 16px 14px 28px;
            }
            .exam-paper-title {
                font-size: 1.25rem;
            }
            .exam-header-actions {
                width: 100%;
                justify-content: stretch;
            }
            .btn-save-exit,
            .btn-submit-test {
                flex: 1;
                justify-content: center;
            }
            .exam-meta-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .exam-question-card {
                padding: 18px 16px;
            }
            .picture-options-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Dark Layout Support */
        .dark-layout,
        .dark-layout .app-content,
        .dark-layout .exam-player-shell {
            background-color: #0f172a !important;
        }

        .dark-layout .exam-topbar {
            background-color: #1e293b;
            border-bottom-color: #334155;
        }

        .dark-layout .exam-logo-light {
            display: none;
        }

        .dark-layout .exam-logo-dark {
            display: block;
        }

        .dark-layout .exam-bell-btn {
            color: #e2e8f0;
        }

        .dark-layout .exam-bell-btn:hover,
        .dark-layout .exam-user-pill:hover {
            background-color: #334155;
        }

        .dark-layout .exam-user-name,
        .dark-layout .exam-paper-title,
        .dark-layout .exam-meta-count,
        .dark-layout .q-card-number,
        .dark-layout .question-stem-title,
        .dark-layout .question-prompt-heading,
        .dark-layout .direct-answer-label,
        .dark-layout .sidebar-card-title,
        .dark-layout .progress-meta-row strong,
        .dark-layout .option-letter-label {
            color: #f8fafc !important;
        }

        .dark-layout .exam-user-role,
        .dark-layout .exam-paper-subtitle,
        .dark-layout .exam-meta-label,
        .dark-layout .exam-meta-sublabel,
        .dark-layout .direct-answer-hint,
        .dark-layout .legend-item,
        .dark-layout .progress-meta-row {
            color: #94a3b8;
        }

        .dark-layout .exam-meta-bar,
        .dark-layout .exam-question-card,
        .dark-layout .exam-sidebar-card,
        .dark-layout .options-group-list,
        .dark-layout .picture-option-card {
            background-color: #1e293b;
            border-color: #334155;
            box-shadow: none;
        }

        .dark-layout .exam-meta-item {
            border-right-color: #334155;
        }

        @media (max-width: 1199.98px) {
            .dark-layout .exam-meta-item {
                background-color: #0f172a;
            }
        }

        .dark-layout .question-stem-box {
            background-color: #0f172a;
        }

        .dark-layout .passage-content,
        .dark-layout .question-stem-subdesc,
        .dark-layout .question-inline-blanks,
        .dark-layout .option-text-label {
            color: #e2e8f0;
        }

        .dark-layout .option-row-item {
            border-bottom-color: #334155;
        }

        .dark-layout .option-row-item:hover {
            background-color: #263449;
        }

        .dark-layout .option-row-item.selected-option-card,
        .dark-layout .picture-option-card.selected-option-card {
            background-color: rgba(37, 99, 235, 0.22);
        }

        .dark-layout .direct-answer-input,
        .dark-layout .direct-answer-textarea,
        .dark-layout .inline-blank-input {
            background-color: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }

        .dark-layout .btn-save-exit,
        .dark-layout .btn-nav-prev,
        .dark-layout .btn-mark-review {
            background-color: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }

        .dark-layout .btn-nav-prev.is-disabled,
        .dark-layout .btn-nav-prev:disabled {
            background-color: #0f172a;
            color: #475569;
        }

        .dark-layout .q-nav-btn {
            background-color: #0f172a;
            color: #e2e8f0;
        }

        .dark-layout .q-nav-btn.is-answered {
            background-color: rgba(34, 197, 94, 0.2);
            color: #86efac;
            border-color: rgba(34, 197, 94, 0.35);
        }

        .dark-layout .q-nav-btn.is-flagged {
            background-color: rgba(168, 85, 247, 0.22);
            color: #d8b4fe;
            border-color: rgba(168, 85, 247, 0.4);
        }

        .dark-layout .exam-progress-track {
            background-color: #0f172a;
        }

        .dark-layout .q-nav-legend {
            border-top-color: #334155;
        }

        .dark-layout .exam-dropdown-menu {
            background-color: #1e293b;
            border: 1px solid #334155 !important;
        }

        .dark-layout .exam-dropdown-menu .text-dark,
        .dark-layout .exam-dropdown-menu .exam-menu-item {
            color: #f1f5f9 !important;
        }

        .dark-layout .exam-dropdown-menu .exam-menu-item:hover {
            background-color: #334155;
        }

        .dark-layout .matching-pair-row,
        .dark-layout .matching-target-row,
        .dark-layout .draggables-pool,
        .dark-layout .drop-zone {
            background-color: #0f172a;
            border-color: #334155;
        }

        .dark-layout .matching-left-text,
        .dark-layout .matching-target-label {
            color: #f1f5f9;
        }

        .dark-layout .matching-select {
            background-color: #1e293b;
            border-color: #334155;
            color: #f8fafc;
        }

        .dark-layout .feedback-explanation {
            background-color: #0f172a;
            border-color: #334155;
            color: #e2e8f0;
        }

        /* Pause / Save & Exit Confirmation Modal */
        #pauseConfirmModal {
            z-index: 1060 !important;
        }

        .pause-modal-dialog {
            max-width: 470px;
            margin: 1rem auto;
        }

        .pause-modal-content {
            position: relative;
            border: none;
            border-radius: 22px;
            background-color: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
            overflow: hidden;
        }

        .pause-modal-close-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: none;
            background: transparent;
            color: #0b1f51;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.18s ease;
            z-index: 2;
        }

        .pause-modal-close-btn:hover {
            background-color: #f1f5fb;
        }

        .pause-modal-body {
            padding: 26px 26px 24px;
            text-align: center;
        }

        .pause-modal-illustration {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .pause-modal-title {
            color: #0b1f51;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.4px;
            margin: 0 0 8px;
        }

        .pause-modal-desc {
            color: #475569;
            font-size: 0.93rem;
            line-height: 1.5;
            max-width: 360px;
            margin: 0 auto 20px;
        }

        .pause-summary-box {
            background-color: #f3f7fd;
            border: 1px solid #e6edf7;
            border-radius: 14px;
            padding: 16px 10px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 22px;
        }

        .pause-summary-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            border-right: 1px solid #dde6f3;
        }

        .pause-summary-item-last {
            border-right: none;
        }

        .pause-summary-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .pause-icon-blue {
            background-color: #1677ff;
            box-shadow: 0 3px 8px rgba(22, 119, 255, 0.22);
        }

        .pause-icon-green {
            background-color: #16a34a;
            box-shadow: 0 3px 8px rgba(22, 163, 74, 0.22);
        }

        .pause-icon-purple {
            background-color: #ede9fe;
        }

        .pause-summary-label {
            color: #475569;
            font-size: 0.76rem;
            font-weight: 500;
            line-height: 1.25;
            margin-bottom: 4px;
        }

        .pause-summary-val {
            font-size: 1.12rem;
            font-weight: 800;
            line-height: 1.2;
            font-variant-numeric: tabular-nums;
        }

        .pause-summary-val.val-dark { color: #0b1f51; }
        .pause-summary-val.val-green { color: #16a34a; }
        .pause-summary-val.val-purple { color: #6d28d9; }

        .pause-modal-actions {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .btn-pause-continue {
            height: 46px;
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            background-color: #ffffff;
            color: #1677ff;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-pause-continue:hover {
            background-color: #f8fbff;
            border-color: #93c5fd;
        }

        .btn-pause-save-exit {
            height: 46px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-pause-save-exit:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.38);
        }

        .btn-pause-save-exit:disabled {
            opacity: 0.75;
            cursor: wait;
        }

        @media (max-width: 575.98px) {
            .pause-modal-dialog {
                margin: 0.75rem;
            }
            .pause-modal-body {
                padding: 22px 18px 20px;
            }
            .pause-summary-box {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 14px 16px;
            }
            .pause-summary-item {
                flex-direction: row;
                justify-content: space-between;
                border-right: none;
                border-bottom: 1px solid #dde6f3;
                padding: 0 0 10px;
            }
            .pause-summary-item-last {
                border-bottom: none;
                padding-bottom: 0;
            }
            .pause-summary-icon {
                margin-bottom: 0;
                margin-right: 10px;
            }
            .pause-summary-label {
                margin-bottom: 0;
                margin-right: auto;
                text-align: left;
            }
            .pause-modal-actions {
                grid-template-columns: 1fr;
            }
        }

        .dark-layout .pause-modal-content {
            background-color: #1e293b;
            border: 1px solid #334155;
        }

        .dark-layout .pause-modal-title,
        .dark-layout .pause-summary-val.val-dark,
        .dark-layout .pause-modal-close-btn {
            color: #f8fafc;
        }

        .dark-layout .pause-modal-close-btn:hover {
            background-color: #334155;
        }

        .dark-layout .pause-modal-desc,
        .dark-layout .pause-summary-label {
            color: #94a3b8;
        }

        .dark-layout .pause-summary-box {
            background-color: #0f172a;
            border-color: #334155;
        }

        .dark-layout .pause-summary-item {
            border-color: #334155;
        }

        .dark-layout .btn-pause-continue {
            background-color: #0f172a;
            border-color: #334155;
            color: #60a5fa;
        }

        .dark-layout .btn-pause-continue:hover {
            background-color: #172554;
            border-color: #2563eb;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let remainingSeconds = @json($remainingSeconds);
        let isCountdown = remainingSeconds !== null;
        let elapsedSeconds = {{ max(0, (int) $attempt->time_spent) + ($attempt->started_at ? (int) $attempt->started_at->diffInSeconds(now()) : 0) }};

        let currentQuestion = 1;
        let totalQuestions = @json($questions->count());
        let allowInstantFeedback = @json((bool) $attempt->paper->allow_instant_feedback);
        let allowReattempt = @json((bool) $attempt->paper->allow_reattempt_question);

        let questionTimes = {};
        let questionFlagged = {};

        @foreach($questions as $question)
            @php $ans = $answers->get($question->id); @endphp
            questionTimes[{{ $question->id }}] = {{ $ans ? (int) $ans->time_spent : 0 }};
            questionFlagged[{{ $question->id }}] = {{ $ans && $ans->is_flagged ? 1 : 0 }};
        @endforeach

        let activeQuestionTimer = null;
        let overallTimerInterval = null;
        let autoSaveDebounceTimer = null;
        let isSubmittingForm = false;

        document.addEventListener('DOMContentLoaded', function() {
            if (totalQuestions > 0) {
                showQuestion(1);
            }

            if (!allowReattempt) {
                document.querySelectorAll('.q-card').forEach(card => {
                    let qId = card.getAttribute('data-q-id');
                    let confidenceInput = document.getElementById('confidence-' + qId);
                    if (confidenceInput && confidenceInput.value !== '') {
                        lockQuestionInputs(card);
                    }
                });
            }

            // Initialize word counts for free_text questions
            document.querySelectorAll('textarea.text-input').forEach(ta => {
                let card = ta.closest('.q-card');
                if (card) {
                    let qId = card.getAttribute('data-q-id');
                    let wordCountEl = document.getElementById('word-count-' + qId);
                    if (wordCountEl) {
                        let limitMatch = wordCountEl.textContent.match(/\d+/);
                        let limit = limitMatch ? parseInt(limitMatch[0], 10) : null;
                        updateWordCount(qId, ta, limit);
                    }
                }
            });

            startOverallTimer();
            if (totalQuestions > 0) {
                startActiveQuestionTimer();
            }

            for (let i = 1; i <= totalQuestions; i++) {
                updateNavigatorTile(i);
            }
            updateStatsAndProgress();
            initLightbox();

            // Periodic safety auto-save every 25 seconds
            setInterval(function() {
                if (!isSubmittingForm) {
                    autoSaveAnswers().catch(() => {});
                }
            }, 25000);

            // Sync theme icon/label inside exam topbar when toggled
            window.addEventListener('appThemeChanged', function(e) {
                let isDark = e.detail && e.detail.theme === 'dark';
                let icon = document.getElementById('examThemeIcon');
                let label = document.getElementById('examThemeLabel');
                if (icon) icon.className = 'feather icon-' + (isDark ? 'sun' : 'moon') + ' mr-2';
                if (label) label.textContent = isDark ? 'Light Mode' : 'Dark Mode';
            });

            // Resume active question timer if Pause / Save & Exit modal is dismissed without exiting
            if (window.jQuery) {
                $('#pauseConfirmModal').on('hidden.bs.modal', function () {
                    if (!isSubmittingForm) {
                        startActiveQuestionTimer();
                    }
                });
            }
        });

        function startOverallTimer() {
            updateDigitalClock();
            overallTimerInterval = setInterval(function() {
                if (isCountdown) {
                    if (remainingSeconds <= 0) {
                        clearInterval(overallTimerInterval);
                        submitTestForm('submit_auto');
                        return;
                    }
                    remainingSeconds--;
                    if (remainingSeconds <= 0) {
                        updateDigitalClock();
                        clearInterval(overallTimerInterval);
                        submitTestForm('submit_auto');
                        return;
                    }
                } else {
                    elapsedSeconds++;
                }
                updateDigitalClock();
            }, 1000);
        }

        function updateDigitalClock() {
            let clockEl = document.getElementById('digital-clock');
            if (!clockEl) return;

            let timeVal = Math.max(0, Math.floor(isCountdown ? remainingSeconds : elapsedSeconds));
            let hrs = Math.floor(timeVal / 3600);
            let mins = Math.floor((timeVal % 3600) / 60);
            let secs = timeVal % 60;

            let formattedTime;
            if (timeVal > 3600) {
                formattedTime =
                    (hrs < 10 ? '0' : '') + hrs + ':' +
                    (mins < 10 ? '0' : '') + mins + ':' +
                    (secs < 10 ? '0' : '') + secs;
            } else {
                let totalMins = Math.floor(timeVal / 60);
                formattedTime =
                    (totalMins < 10 ? '0' : '') + totalMins + ':' +
                    (secs < 10 ? '0' : '') + secs;
            }

            clockEl.textContent = formattedTime;

            let pauseModalTimeEl = document.getElementById('pause-modal-time');
            if (pauseModalTimeEl) {
                pauseModalTimeEl.textContent = formattedTime;
            }

            if (isCountdown) {
                clockEl.classList.remove('val-warning', 'val-danger');
                if (timeVal <= 60) {
                    clockEl.classList.add('val-danger');
                } else if (timeVal <= 300) {
                    clockEl.classList.add('val-warning');
                }
            }
        }

        function startActiveQuestionTimer() {
            if (activeQuestionTimer) clearInterval(activeQuestionTimer);
            if (totalQuestions <= 0) return;

            activeQuestionTimer = setInterval(function() {
                let card = document.getElementById('q-card-' + currentQuestion);
                if (!card) return;
                let qId = card.getAttribute('data-q-id');
                questionTimes[qId] = (questionTimes[qId] || 0) + 1;
                let timeInput = document.getElementById('time-spent-' + qId);
                if (timeInput) timeInput.value = questionTimes[qId];
            }, 1000);
        }

        function showQuestion(qNum) {
            if (qNum < 1 || qNum > totalQuestions) return;

            if (activeQuestionTimer) clearInterval(activeQuestionTimer);

            document.querySelectorAll('.q-card').forEach(card => card.classList.add('d-none'));

            let targetCard = document.getElementById('q-card-' + qNum);
            if (targetCard) {
                targetCard.classList.remove('d-none');
            }

            document.querySelectorAll('.q-nav-btn').forEach(btn => btn.classList.remove('is-current'));
            let activeNavBtn = document.getElementById('nav-btn-' + qNum);
            if (activeNavBtn) {
                activeNavBtn.classList.add('is-current');
                activeNavBtn.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            }

            currentQuestion = qNum;
            startActiveQuestionTimer();
        }

        function jumpToQuestion(qNum) {
            if (qNum === currentQuestion) return;
            scheduleAutoSave(150);
            showQuestion(qNum);
        }

        function nextQuestion() {
            if (currentQuestion < totalQuestions) {
                scheduleAutoSave(150);
                showQuestion(currentQuestion + 1);
            }
        }

        function prevQuestion() {
            if (currentQuestion > 1) {
                scheduleAutoSave(150);
                showQuestion(currentQuestion - 1);
            }
        }

        function toggleFlag(qNum) {
            let targetNum = qNum || currentQuestion;
            let card = document.getElementById('q-card-' + targetNum);
            if (!card) return;

            let qId = card.getAttribute('data-q-id');
            let hiddenInput = document.getElementById('is-flagged-' + qId);
            if (!hiddenInput) return;

            let isCurrentlyFlagged = hiddenInput.value === '1';
            let nextFlagged = !isCurrentlyFlagged;
            hiddenInput.value = nextFlagged ? '1' : '0';
            questionFlagged[qId] = nextFlagged ? 1 : 0;

            let flagBtn = document.getElementById('flag-btn-' + targetNum);
            if (flagBtn) {
                flagBtn.classList.toggle('is-flagged', nextFlagged);
                let label = flagBtn.querySelector('.flag-btn-label');
                let svg = flagBtn.querySelector('.flag-svg');
                if (label) label.textContent = nextFlagged ? 'Marked for Review' : 'Mark for Review';
                if (svg) svg.setAttribute('fill', nextFlagged ? 'currentColor' : 'none');
            }

            updateNavigatorTile(targetNum);
            updateStatsAndProgress();
            scheduleAutoSave(250);
        }

        function checkQuestionAnswered(qId) {
            let card = document.querySelector(`.q-card[data-q-id="${qId}"]`);
            if (!card) return false;

            if (card.querySelector('input[type="radio"]:checked')) return true;
            if (card.querySelector('input[type="checkbox"]:checked')) return true;

            let selectValued = false;
            card.querySelectorAll('select').forEach(sel => {
                if (sel.value !== '') selectValued = true;
            });
            if (selectValued) return true;

            let textValued = false;
            card.querySelectorAll('input[type="text"], textarea').forEach(txt => {
                if (txt.value.trim() !== '') textValued = true;
            });
            if (textValued) return true;

            let matchingValued = false;
            card.querySelectorAll('.matching-hidden-input').forEach(hidden => {
                if (hidden.value !== '') matchingValued = true;
            });

            return matchingValued;
        }

        function selectOption(questionId, optionId, type) {
            let container = document.getElementById('option-container-' + optionId);
            let input = document.getElementById('opt-' + optionId);
            if (!input || !container) return;

            let questionCard = container.closest('.q-card');
            if (!questionCard || questionCard.getAttribute('data-is-locked') === 'true') return;

            let qNum = parseInt(questionCard.getAttribute('data-q-num'), 10);

            if (type === 'single_choice_radio' || type === 'picture_choice') {
                let isAlreadyChecked = container.classList.contains('selected-option-card');

                questionCard.querySelectorAll('.option-card').forEach(box => {
                    box.classList.remove('selected-option-card');
                    box.setAttribute('aria-checked', 'false');
                });

                if (isAlreadyChecked) {
                    input.checked = false;
                } else {
                    input.checked = true;
                    container.classList.add('selected-option-card');
                    container.setAttribute('aria-checked', 'true');
                }
            } else if (type === 'multiple_choice') {
                input.checked = !input.checked;
                container.classList.toggle('selected-option-card', input.checked);
                container.setAttribute('aria-checked', input.checked ? 'true' : 'false');
            }

            updateNavigatorTile(qNum);
            updateStatsAndProgress();
            scheduleAutoSave(300);
        }

        function checkTextInput(qNum, element) {
            updateNavigatorTile(qNum);
            updateStatsAndProgress();
            scheduleAutoSave(700);
        }

        function selectDropdownOption(qNum, element) {
            updateNavigatorTile(qNum);
            updateStatsAndProgress();
            scheduleAutoSave(300);
        }

        function updateWordCount(qId, textarea, limit) {
            let el = document.getElementById('word-count-' + qId);
            if (!el) return;
            let text = textarea.value.trim();
            let words = text === '' ? 0 : text.split(/\s+/).length;
            if (limit) {
                el.textContent = words + ' / ' + limit + ' words';
                el.style.color = words > limit ? '#dc2626' : '';
            } else {
                el.textContent = words > 0 ? words + (words === 1 ? ' word' : ' words') : '';
            }
        }

        function updateNavigatorTile(qNum) {
            let card = document.getElementById('q-card-' + qNum);
            let navBtn = document.getElementById('nav-btn-' + qNum);
            if (!card || !navBtn) return;

            let qId = card.getAttribute('data-q-id');
            let hasAnswer = checkQuestionAnswered(qId);
            let isFlagged = document.getElementById('is-flagged-' + qId)?.value === '1';

            navBtn.classList.toggle('is-answered', hasAnswer);
            navBtn.classList.toggle('is-flagged', isFlagged);

            let flagBadge = navBtn.querySelector('.q-nav-flag');
            if (flagBadge) {
                flagBadge.classList.toggle('d-none', !isFlagged);
            }
        }

        function updateStatsAndProgress() {
            let answered = 0;
            let flagged = 0;

            for (let i = 1; i <= totalQuestions; i++) {
                let card = document.getElementById('q-card-' + i);
                if (!card) continue;
                let qId = card.getAttribute('data-q-id');

                if (checkQuestionAnswered(qId)) {
                    answered++;
                }
                if (document.getElementById('is-flagged-' + qId)?.value === '1') {
                    flagged++;
                }
            }

            let unanswered = Math.max(0, totalQuestions - answered);
            let pct = totalQuestions > 0 ? Math.round((answered / totalQuestions) * 100) : 0;

            let statAns = document.getElementById('stat-answered');
            let statUnans = document.getElementById('stat-unanswered');
            let statFlag = document.getElementById('stat-flagged');
            let progText = document.getElementById('progress-text');
            let progPct = document.getElementById('progress-percent');
            let progBar = document.getElementById('progress-bar-fill');

            if (statAns) statAns.textContent = answered;
            if (statUnans) statUnans.textContent = unanswered;
            if (statFlag) statFlag.textContent = flagged;
            if (progText) progText.textContent = answered + ' of ' + totalQuestions + ' answered';
            if (progPct) progPct.textContent = pct + '%';
            if (progBar) progBar.style.width = pct + '%';
        }

        function checkInstantFeedback(qNum, qId) {
            let card = document.getElementById('q-card-' + qNum);
            if (!card) return;

            if (!checkQuestionAnswered(qId)) {
                Swal.fire({
                    title: 'No Answer Provided',
                    text: 'Please select or enter an answer before checking.',
                    type: 'info',
                    confirmButtonColor: '#1677ff'
                });
                return;
            }

            let confidenceInput = document.getElementById('confidence-' + qId);
            if (confidenceInput && !confidenceInput.value) {
                confidenceInput.value = 'sure';
            }

            let checkBtn = document.getElementById('check-answer-btn-' + qId);
            if (checkBtn) {
                checkBtn.disabled = true;
                checkBtn.textContent = 'Checking...';
            }

            autoSaveAnswers()
                .then(data => {
                    if (checkBtn) {
                        checkBtn.disabled = false;
                        checkBtn.textContent = 'Check Answer';
                    }

                    if (data && data.evaluations) {
                        let evaluation = data.evaluations.find(e => e.question_id == qId);
                        if (evaluation) {
                            let feedbackBox = document.getElementById('feedback-box-' + qId);
                            if (feedbackBox) {
                                feedbackBox.classList.remove('d-none', 'feedback-correct', 'feedback-incorrect');
                                feedbackBox.classList.add(evaluation.is_correct ? 'feedback-correct' : 'feedback-incorrect');
                                feedbackBox.querySelector('.feedback-title').textContent = evaluation.is_correct ? 'Correct!' : 'Incorrect';

                                let correctRow = feedbackBox.querySelector('.feedback-correct-row');
                                let correctAnsSpan = feedbackBox.querySelector('.feedback-correct-answer');
                                if (evaluation.correct_answer && evaluation.correct_answer.trim() !== '') {
                                    correctAnsSpan.textContent = evaluation.correct_answer;
                                    correctRow.classList.remove('d-none');
                                } else {
                                    correctRow.classList.add('d-none');
                                }

                                let explanationBox = document.getElementById('feedback-explanation-container-' + qId);
                                if (explanationBox) {
                                    let hasExpImgs = !!explanationBox.querySelector('.explanation-images-wrapper');
                                    if (evaluation.explanation || hasExpImgs) {
                                        if (evaluation.explanation) {
                                            explanationBox.querySelector('.explanation-content').innerHTML = evaluation.explanation;
                                        }
                                        explanationBox.classList.remove('d-none');
                                    } else {
                                        explanationBox.classList.add('d-none');
                                    }
                                }
                            }
                        }
                    }

                    if (!allowReattempt) {
                        lockQuestionInputs(card);
                        if (checkBtn) checkBtn.classList.add('d-none');
                    }
                })
                .catch(() => {
                    if (checkBtn) {
                        checkBtn.disabled = false;
                        checkBtn.textContent = 'Check Answer';
                    }
                    Swal.fire('Error', 'Failed to evaluate answer. Please try again.', 'error');
                });
        }

        function lockQuestionInputs(card) {
            if (!card) return;
            card.setAttribute('data-is-locked', 'true');
            card.querySelectorAll('.option-card').forEach(box => {
                box.style.pointerEvents = 'none';
            });
            card.querySelectorAll('select').forEach(sel => {
                sel.classList.add('is-locked-input');
                sel.setAttribute('tabindex', '-1');
            });
            card.querySelectorAll('input[type="text"], textarea').forEach(input => {
                input.readOnly = true;
                input.classList.add('is-locked-input');
                input.setAttribute('tabindex', '-1');
            });
            card.querySelectorAll('.draggable-card').forEach(c => {
                c.setAttribute('draggable', 'false');
                c.style.pointerEvents = 'none';
                c.style.cursor = 'default';
            });
            card.querySelectorAll('.drop-zone, .draggables-pool').forEach(zone => {
                zone.style.pointerEvents = 'none';
            });
        }

        function confirmSaveAndExit() {
            if (isSubmittingForm) return;
            let form = document.getElementById('test-taking-form');
            if (!form) return;

            isSubmittingForm = true;
            if (activeQuestionTimer) clearInterval(activeQuestionTimer);
            if (autoSaveDebounceTimer) clearTimeout(autoSaveDebounceTimer);

            let confirmBtn = document.getElementById('confirm-save-exit-btn');
            if (confirmBtn) {
                confirmBtn.disabled = true;
                let span = confirmBtn.querySelector('span');
                if (span) span.textContent = 'Saving...';
            }

            form.action = "{{ route('student.attempts.pause', $attempt->id) }}";
            form.submit();
        }

        function submitTestForm(action) {
            if (isSubmittingForm) return;
            let form = document.getElementById('test-taking-form');
            if (!form) return;

            if (activeQuestionTimer) clearInterval(activeQuestionTimer);
            if (autoSaveDebounceTimer) clearTimeout(autoSaveDebounceTimer);

            if (action === 'pause') {
                let answeredCount = 0;
                let flaggedCount = 0;
                for (let i = 1; i <= totalQuestions; i++) {
                    let card = document.getElementById('q-card-' + i);
                    if (!card) continue;
                    let qId = card.getAttribute('data-q-id');
                    if (checkQuestionAnswered(qId)) answeredCount++;
                    if (document.getElementById('is-flagged-' + qId)?.value === '1') flaggedCount++;
                }

                let modalAnsEl = document.getElementById('pause-modal-answered');
                let modalTimeEl = document.getElementById('pause-modal-time');
                let modalFlagEl = document.getElementById('pause-modal-flagged');
                let mainClockEl = document.getElementById('digital-clock');

                if (modalAnsEl) modalAnsEl.textContent = answeredCount + ' / ' + totalQuestions;
                if (modalTimeEl && mainClockEl) modalTimeEl.textContent = mainClockEl.textContent;
                if (modalFlagEl) modalFlagEl.textContent = flaggedCount;

                if (window.jQuery) {
                    $('#pauseConfirmModal').modal('show');
                }
            } else if (action === 'submit_auto') {
                isSubmittingForm = true;
                form.action = "{{ route('student.attempts.submit', $attempt->id) }}";
                form.submit();
            } else {
                let unansweredCount = 0;
                for (let i = 1; i <= totalQuestions; i++) {
                    let card = document.getElementById('q-card-' + i);
                    if (card && !checkQuestionAnswered(card.getAttribute('data-q-id'))) {
                        unansweredCount++;
                    }
                }

                let confirmText = unansweredCount > 0
                    ? `You still have ${unansweredCount} unanswered ${unansweredCount === 1 ? 'question' : 'questions'}. Once submitted, you cannot change your answers.`
                    : 'Are you sure you want to submit your test? Once submitted, you cannot change your answers.';

                Swal.fire({
                    title: 'Submit Test?',
                    text: confirmText,
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1677ff',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Submit Test',
                    cancelButtonText: 'Review Answers'
                }).then(function(result) {
                    if (result && (result.value || result.isConfirmed || result === true)) {
                        isSubmittingForm = true;
                        form.action = "{{ route('student.attempts.submit', $attempt->id) }}";
                        form.submit();
                    } else {
                        startActiveQuestionTimer();
                    }
                });
            }
        }

        function scheduleAutoSave(delay = 500) {
            if (isSubmittingForm) return;
            if (autoSaveDebounceTimer) clearTimeout(autoSaveDebounceTimer);
            autoSaveDebounceTimer = setTimeout(function() {
                autoSaveAnswers().catch(() => {});
            }, delay);
        }

        function autoSaveAnswers() {
            let form = document.getElementById('test-taking-form');
            if (!form || isSubmittingForm) return Promise.resolve(null);

            let formData = new FormData(form);

            return fetch("{{ route('student.attempts.save', $attempt->id) }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Save failed');
                return response.json();
            });
        }

        // Drag and Drop Matching Handlers
        let activeDraggedElement = null;
        let activeSelectedCard = null;

        function handleDragStart(e) {
            let qCard = e.target.closest('.q-card');
            if (qCard && qCard.getAttribute('data-is-locked') === 'true') {
                e.preventDefault();
                return;
            }
            activeDraggedElement = e.target;
            e.dataTransfer.setData("text/plain", e.target.getAttribute("data-value"));
            e.dataTransfer.effectAllowed = "move";
        }

        function allowDrop(e) {
            e.preventDefault();
        }

        function handleDrop(e) {
            e.preventDefault();
            let dropzone = e.target.closest('.drop-zone');
            if (!dropzone) return;
            let qCard = dropzone.closest('.q-card');
            if (qCard && qCard.getAttribute('data-is-locked') === 'true') return;

            let questionId = dropzone.getAttribute('data-question-id');
            let pairIndex = dropzone.getAttribute('data-pair-index');

            if (!activeDraggedElement || activeDraggedElement.getAttribute('data-question-id') != questionId) return;

            performMatch(dropzone, activeDraggedElement, questionId, pairIndex);
            activeDraggedElement = null;
        }

        function handleReturnToPool(e) {
            e.preventDefault();
            let pool = e.target.closest('.draggables-pool');
            if (!pool) return;
            let qCard = pool.closest('.q-card');
            if (qCard && qCard.getAttribute('data-is-locked') === 'true') return;

            let questionId = pool.getAttribute('data-question-id');
            if (!activeDraggedElement || activeDraggedElement.getAttribute('data-question-id') != questionId) return;

            returnCardToPool(pool, activeDraggedElement, questionId);
            activeDraggedElement = null;
        }

        function handleCardClick(e, card) {
            e.stopPropagation();
            let qCard = card.closest('.q-card');
            if (qCard && qCard.getAttribute('data-is-locked') === 'true') return;

            if (activeSelectedCard === card) {
                card.classList.remove('selected-card');
                activeSelectedCard = null;
            } else {
                if (activeSelectedCard) {
                    activeSelectedCard.classList.remove('selected-card');
                }
                card.classList.add('selected-card');
                activeSelectedCard = card;
            }
        }

        function handleDropzoneClick(dropzone) {
            let qCard = dropzone.closest('.q-card');
            if (qCard && qCard.getAttribute('data-is-locked') === 'true') return;

            if (!activeSelectedCard) {
                let matchedCard = dropzone.querySelector('.draggable-card');
                if (matchedCard) {
                    let questionId = dropzone.getAttribute('data-question-id');
                    let pool = document.querySelector(`.draggables-pool[data-question-id="${questionId}"]`);
                    if (pool) returnCardToPool(pool, matchedCard, questionId);
                }
                return;
            }

            let questionId = dropzone.getAttribute('data-question-id');
            let pairIndex = dropzone.getAttribute('data-pair-index');
            if (activeSelectedCard.getAttribute('data-question-id') != questionId) return;

            performMatch(dropzone, activeSelectedCard, questionId, pairIndex);
            activeSelectedCard.classList.remove('selected-card');
            activeSelectedCard = null;
        }

        function handlePoolClick(pool) {
            let qCard = pool.closest('.q-card');
            if (qCard && qCard.getAttribute('data-is-locked') === 'true') return;
            if (!activeSelectedCard) return;

            let questionId = pool.getAttribute('data-question-id');
            if (activeSelectedCard.getAttribute('data-question-id') != questionId) return;

            if (activeSelectedCard.closest('.drop-zone')) {
                returnCardToPool(pool, activeSelectedCard, questionId);
                activeSelectedCard.classList.remove('selected-card');
                activeSelectedCard = null;
            }
        }

        function performMatch(dropzone, card, questionId, pairIndex) {
            let hiddenInput = document.getElementById(`matching-hidden-${questionId}-${pairIndex}`);
            if (!hiddenInput) return;

            let existingCard = dropzone.querySelector('.draggable-card');
            let pool = document.querySelector(`.draggables-pool[data-question-id="${questionId}"]`);

            if (existingCard) {
                if (existingCard === card) return;
                returnCardToPool(pool, existingCard, questionId);
            }

            let placeholder = dropzone.querySelector('.drag-placeholder');
            if (placeholder) placeholder.style.display = 'none';

            card.classList.remove('pool-card');
            card.classList.add('matched-card');
            dropzone.appendChild(card);

            hiddenInput.value = card.getAttribute('data-value');

            let cardElement = document.querySelector(`.q-card[data-q-id="${questionId}"]`);
            if (cardElement) {
                checkTextInput(parseInt(cardElement.getAttribute('data-q-num'), 10), null);
            }
        }

        function returnCardToPool(pool, card, questionId) {
            let dropzone = card.closest('.drop-zone');
            if (dropzone) {
                let pairIndex = dropzone.getAttribute('data-pair-index');
                let hiddenInput = document.getElementById(`matching-hidden-${questionId}-${pairIndex}`);
                if (hiddenInput) hiddenInput.value = '';

                let placeholder = dropzone.querySelector('.drag-placeholder');
                if (placeholder) placeholder.style.display = 'block';
            }

            card.classList.remove('matched-card');
            card.classList.add('pool-card');
            pool.appendChild(card);

            let cardElement = document.querySelector(`.q-card[data-q-id="${questionId}"]`);
            if (cardElement) {
                checkTextInput(parseInt(cardElement.getAttribute('data-q-num'), 10), null);
            }
        }

        function initLightbox() {
            const lightboxHtml = `
                <div id="premium-image-lightbox" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 99999; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.25s ease; cursor: zoom-out;">
                    <span id="close-lightbox-btn" style="position: absolute; top: 20px; right: 26px; color: #f8fafc; font-size: 38px; font-weight: 300; cursor: pointer;">&times;</span>
                    <img id="lightbox-zoomed-img" src="" style="max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 10px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);" alt="Zoomed Image">
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
                    lightbox.offsetHeight;
                    lightbox.style.opacity = '1';
                }
            });

            function closeLightbox() {
                lightbox.style.opacity = '0';
                setTimeout(() => {
                    lightbox.style.display = 'none';
                }, 250);
            }

            lightbox.addEventListener('click', function(e) {
                if (e.target !== lightboxImg) closeLightbox();
            });
            closeBtn.addEventListener('click', closeLightbox);
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && lightbox.style.display === 'flex') closeLightbox();
            });
        }
    </script>
@endpush

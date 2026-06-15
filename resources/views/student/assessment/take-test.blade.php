@extends('layouts.app')

@section('title', 'TEST: ' . $attempt->paper->title)

@section('content')
    <!-- Google Fonts for Modern Styling -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    <!-- Outer sandboxed wrapper to prevent header/menu conflicts -->
    <div class="fullscreen-player-content">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div class="drawer-backdrop" onclick="toggleSidebar()"></div>
        
        <!-- Fullscreen Player Frame -->
        <div class="player-frame">
            
            <!-- Top Status Bar -->
            <div class="top-status-bar d-flex justify-content-between align-items-center px-3 px-md-4 py-2 border-bottom bg-white">
                <div class="d-flex align-items-center min-width-0 flex-grow-1 mr-2">
                    <span class="badge badge-pill badge-light-primary px-3 py-1 font-weight-bold text-uppercase mr-3 d-none d-md-inline" style="font-size: 0.7rem; letter-spacing: 0.5px; white-space: nowrap;">
                        {{ $attempt->paper->subject->title }} &nbsp;•&nbsp; {{ $attempt->paper->topic->name }}
                    </span>
                    <h5 class="mb-0 font-weight-bold text-dark text-truncate" style="letter-spacing: -0.5px;">{{ $attempt->paper->title }}</h5>
                </div>
                
                <div class="d-flex align-items-center justify-content-end flex-shrink-0">
                    <!-- Mobile Clock -->
                    <div class="d-md-none mr-2 bg-dark py-1 px-2 rounded" style="border: 1px solid #334155;">
                        <span id="mobile-digital-clock" class="font-weight-bold text-danger text-monospace" style="font-family: 'Share Tech Mono', monospace; font-size: 1rem; text-shadow: 0 0 5px rgba(248, 113, 113, 0.5);">00:00:00</span>
                    </div>

                    <!-- Mobile Drawer Toggle Button -->
                    <button type="button" class="btn btn-sm btn-outline-primary d-md-none mr-2" onclick="toggleSidebar()" style="border-radius: 6px; padding: 4px 8px; font-weight: 600;">
                        <i class="feather icon-menu mr-1"></i> Overview
                    </button>

                    <div class="d-none d-md-flex align-items-center">
                        <!-- Stat Card: Total Questions -->
                        <div class="stat-pill mr-3 d-flex align-items-center bg-light-primary text-primary px-3 py-1 rounded-lg">
                            <i class="feather icon-list mr-2 font-medium-2"></i>
                            <div>
                                <small class="d-block text-uppercase font-weight-bold text-muted" style="font-size: 0.55rem; letter-spacing: 0.5px;">Total Qs</small>
                                <span class="font-weight-bold" style="font-size: 0.9rem;">{{ $questions->count() }}</span>
                            </div>
                        </div>

                        <!-- Stat Card: Answered with Certainty counts -->
                        <div class="stat-pill mr-3 d-flex align-items-center bg-light-success text-success px-3 py-1 rounded-lg">
                            <i class="feather icon-check-circle mr-2 font-medium-2"></i>
                            <div>
                                <small class="d-block text-uppercase font-weight-bold text-muted" style="font-size: 0.55rem; letter-spacing: 0.5px;">Answered</small>
                                <span class="font-weight-bold" style="font-size: 0.9rem;">
                                    <span id="stat-answered">0</span>
                                    <small class="font-weight-normal text-muted font-weight-bold" style="font-size: 0.75rem;">
                                        [ <span id="stat-guess-bracket" style="color: #ff9f43;">0</span> | 
                                        <span id="stat-fairly-sure-bracket" style="color: #7367f0;">0</span> | 
                                        <span id="stat-sure-bracket" style="color: #28c76f;">0</span> ]
                                    </small>
                                </span>
                            </div>
                        </div>

                        <!-- Stat Card: Skipped -->
                        <div class="stat-pill mr-3 d-flex align-items-center bg-light-warning text-warning px-3 py-1 rounded-lg">
                            <i class="feather icon-skip-forward mr-2 font-medium-2"></i>
                            <div>
                                <small class="d-block text-uppercase font-weight-bold text-muted" style="font-size: 0.55rem; letter-spacing: 0.5px;">Skipped</small>
                                <span class="font-weight-bold" style="font-size: 0.9rem;" id="stat-skipped">0</span>
                            </div>
                        </div>

                        <!-- Stat Card: Unanswered -->
                        <div class="stat-pill mr-3 d-flex align-items-center bg-light-danger text-danger px-3 py-1 rounded-lg">
                            <i class="feather icon-info mr-2 font-medium-2"></i>
                            <div>
                                <small class="d-block text-uppercase font-weight-bold text-muted" style="font-size: 0.55rem; letter-spacing: 0.5px;">Unanswered</small>
                                <span class="font-weight-bold" style="font-size: 0.9rem;" id="stat-unanswered">{{ $questions->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Wrapper -->
            <form id="test-taking-form" action="" method="POST" class="mb-0 flex-grow-1 d-flex flex-column overflow-hidden">
                @csrf
                <div class="row no-gutters flex-grow-1 overflow-hidden">
                    
                    <!-- Left Panel: Single Question area -->
                    <div class="col-md-9 col-12 d-flex flex-column left-panel">
                        
                        @foreach($questions as $index => $question)
                            @php
                                $ans = $answers->get($question->id);
                                $paperQuestion = DB::table('paper_question')
                                    ->where('paper_id', $attempt->paper_id)
                                    ->where('question_id', $question->id)
                                    ->first();
                                $marks = $paperQuestion->marks ?? $question->marks ?? $attempt->paper->default_marks ?? 1;
                                $isLocked = ($ans && !$attempt->paper->allow_reattempt_question && $ans->confidence !== null && $ans->confidence !== '');
                                
                                $description = $question->description;
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
                                        $description = preg_replace_callback('/_{3,}/', function($match) use (&$blankIndex, $savedAnswers, $question, $index, $isLocked) {
                                            $val = htmlspecialchars($savedAnswers[$blankIndex] ?? '');
                                            $inputName = "answers[{$question->id}][answer_text][]";
                                            $inputId = "blank-{$question->id}-{$blankIndex}";
                                            $readonlyAttr = $isLocked ? 'readonly tabindex="-1"' : '';
                                            $borderStyle = $isLocked ? 'border: 2px solid #cbd5e1; background-color: #f1f5f9; pointer-events: none;' : 'border: 2px solid #7367f0;';
                                            $html = '<input type="text" name="' . $inputName . '" id="' . $inputId . '" class="form-control test-input blank-input-field d-inline-block" placeholder="blank ' . ($blankIndex + 1) . '" value="' . $val . '" oninput="checkTextInput(' . ($index + 1) . ', this)" ' . $readonlyAttr . ' style="' . $borderStyle . ' border-radius: 6px; height: 32px; padding: 2px 8px; font-size: 0.95rem; width: 150px; margin: 0 4px; vertical-align: middle;">';
                                            $blankIndex++;
                                            return $html;
                                        }, $description);
                                    }
                                }
                            @endphp

                            <!-- Question Card -->
                            <div class="q-card d-none" id="q-card-{{ $index + 1 }}" data-q-num="{{ $index + 1 }}" data-q-id="{{ $question->id }}">
                                
                                <!-- Hidden Inputs for Time, Flag, and Confidence -->
                                <input type="hidden" id="time-spent-{{ $question->id }}" name="answers[{{ $question->id }}][time_spent]" value="{{ $ans ? $ans->time_spent : 0 }}">
                                <input type="hidden" id="is-flagged-{{ $question->id }}" name="answers[{{ $question->id }}][is_flagged]" value="{{ $ans && $ans->is_flagged ? 1 : 0 }}">
                                <input type="hidden" id="confidence-{{ $question->id }}" name="answers[{{ $question->id }}][confidence]" value="{{ $ans ? $ans->confidence : '' }}">

                                <!-- Question Label Header -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="font-weight-bold mb-0 text-primary" style="letter-spacing: -0.5px;">
                                        Question {{ $index + 1 }}
                                    </h4>
                                    <span class="badge badge-light-secondary font-weight-bold px-3 py-1 rounded">
                                        {{ $marks }} {{ \Illuminate\Support\Str::plural('Mark', $marks) }}
                                    </span>
                                </div>
                                
                                <!-- Question Description -->
                                <div class="question-text mb-4 text-dark font-medium-3 leading-relaxed" style="line-height: 1.6; color: #2d3748 !important;">
                                    {!! $description !!}
                                </div>

                                <!-- Image if present -->
                                @if($question->image || ($question->images && count($question->images) > 0))
                                    <div class="question-images-wrapper mb-4 d-flex flex-wrap justify-content-center align-items-center" style="gap: 16px;">
                                        @if($question->image)
                                            <div class="question-img-item text-center">
                                                <img src="{{ asset('storage/' . $question->image) }}" class="img-fluid rounded-lg border shadow img-zoomable" style="max-height: 200px; object-fit: contain; cursor: zoom-in; transition: transform 0.2s;" alt="Question Image">
                                            </div>
                                        @endif
                                        @if($question->images)
                                            @foreach($question->images as $img)
                                                <div class="question-img-item text-center">
                                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded-lg border shadow img-zoomable" style="max-height: 200px; object-fit: contain; cursor: zoom-in; transition: transform 0.2s;" alt="Question Image">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif

                                <hr class="my-4" style="border-top: 1px solid #edf2f7;">

                                <!-- Option/Answer choices box layout -->
                                <div class="answer-section">
                                    <h6 class="font-weight-bold text-muted text-uppercase mb-3" style="font-size: 0.7rem; letter-spacing: 1px;">Answer Options</h6>
                                    
                                    <!-- Choice questions grid -->
                                    @if(in_array($question->type, ['single_choice_radio', 'multiple_choice', 'picture_choice']))
                                        @php
                                            $options = $question->options->sortBy('sort_order')->values();
                                            $letterMap = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                                        @endphp

                                        <div class="row">
                                            @foreach($options as $k => $option)
                                                @php
                                                    $letter = $letterMap[$k] ?? 'A';
                                                    $isSelected = false;
                                                    if ($question->type === 'multiple_choice') {
                                                        $isSelected = ($ans && is_array($ans->selected_options) && in_array($option->id, $ans->selected_options));
                                                    } else {
                                                        $isSelected = ($ans && $ans->selected_option_id == $option->id);
                                                    }
                                                @endphp
                                                <div class="col-md-6 col-12 mb-3">
                                                    <div class="option-card {{ $isSelected ? 'selected-option-card' : '' }}" 
                                                         id="option-container-{{ $option->id }}" 
                                                         onclick="@if($isLocked) return; @else selectOption({{ $question->id }}, {{ $option->id }}, '{{ $question->type }}') @endif">
                                                        
                                                        @if($question->type === 'multiple_choice')
                                                            <input type="checkbox" 
                                                                   id="opt-{{ $option->id }}" 
                                                                   name="answers[{{ $question->id }}][selected_options][]" 
                                                                   class="d-none test-input checkbox-input-field" 
                                                                   value="{{ $option->id }}"
                                                                   {{ $isSelected ? 'checked' : '' }}
                                                                   onchange="checkMultipleAnswered({{ $index + 1 }}, {{ $question->id }})">
                                                        @else
                                                            <input type="radio" 
                                                                   id="opt-{{ $option->id }}" 
                                                                   name="answers[{{ $question->id }}][selected_option_id]" 
                                                                   class="d-none test-input radio-input-field" 
                                                                   value="{{ $option->id }}"
                                                                   {{ $isSelected ? 'checked' : '' }}
                                                                   onchange="markAnswered({{ $index + 1 }})">
                                                        @endif
                                                        
                                                        <div class="option-letter mr-3">
                                                            {{ $letter }}
                                                        </div>
                                                        
                                                        <div class="d-flex align-items-center flex-grow-1">
                                                            @if($option->option_image)
                                                                <img src="{{ asset('storage/' . $option->option_image) }}" class="mr-2 rounded border" style="max-height: 40px;" alt="Option Image">
                                                            @endif
                                                            <span class="font-medium-2 text-dark font-weight-bold">{{ $option->option_text }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    <!-- Single Choice Dropdown -->
                                    @elseif($question->type === 'single_choice_dropdown')
                                        <div class="form-group col-md-6 col-12 pl-0">
                                            <select name="answers[{{ $question->id }}][selected_option_id]" 
                                                    class="form-control test-input select-input" 
                                                    onchange="selectDropdownOption({{ $index + 1 }}, this)" 
                                                    @if($isLocked) tabindex="-1" @endif 
                                                    style="border: 2px solid {{ $isLocked ? '#cbd5e1' : '#7367f0' }}; background-color: {{ $isLocked ? '#f1f5f9' : '#ffffff' }}; pointer-events: {{ $isLocked ? 'none' : 'auto' }}; border-radius: 8px; height: 46px; font-weight: 500;">
                                                <option value="">-- Choose Option --</option>
                                                @foreach($question->options as $option)
                                                    <option value="{{ $option->id }}" {{ ($ans && $ans->selected_option_id == $option->id) ? 'selected' : '' }}>
                                                        {{ $option->option_text }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    <!-- Matching Text -->
                                    @elseif($question->type === 'matching_text')
                                        <div class="matching-text-container">
                                            @php
                                                $pairs = $question->metadata['matching_pairs'] ?? [];
                                                // Extract all right values and shuffle them for the dropdown options
                                                $rightOptions = collect($pairs)->pluck('right')->shuffle()->all();
                                                $savedMatches = [];
                                                if ($ans && $ans->answer_text) {
                                                    $decoded = json_decode($ans->answer_text, true);
                                                    if (is_array($decoded)) {
                                                        $savedMatches = $decoded;
                                                    } else {
                                                        $savedMatches = [$ans->answer_text];
                                                    }
                                                }
                                            @endphp
                                            <div class="row">
                                                @foreach($pairs as $pairIndex => $pair)
                                                    <div class="col-md-6 col-12 mb-3">
                                                        <div class="p-3 border rounded d-flex align-items-center justify-content-between" style="border-radius: 8px; background-color: rgba(115, 103, 240, 0.04); border-color: #e2e8f0;">
                                                            <span class="font-weight-bold text-dark font-medium-1" style="font-size: 0.9rem;">{{ $pair['left'] }}</span>
                                                            <i class="feather icon-arrow-right text-primary mx-2"></i>
                                                            <select name="answers[{{ $question->id }}][answer_text][]" 
                                                                    class="form-control test-input select-input matching-select" 
                                                                    onchange="selectDropdownOption({{ $index + 1 }}, this)"
                                                                    @if($isLocked) tabindex="-1" @endif
                                                                    style="border: 2px solid {{ $isLocked ? '#cbd5e1' : '#7367f0' }}; background-color: {{ $isLocked ? '#f1f5f9' : '#ffffff' }}; pointer-events: {{ $isLocked ? 'none' : 'auto' }}; border-radius: 8px; width: 180px; height: 38px; font-weight: 500; font-size: 0.85rem;">
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

                                    <!-- Matching Drag and Drop -->
                                    @elseif($question->type === 'matching_drag_drop')
                                        <div class="matching-drag-drop-container" id="matching-container-{{ $question->id }}">
                                            @php
                                                $pairs = $question->metadata['matching_pairs'] ?? [];
                                                $savedMatches = [];
                                                if ($ans && $ans->answer_text) {
                                                    $decoded = json_decode($ans->answer_text, true);
                                                    if (is_array($decoded)) {
                                                        $savedMatches = $decoded;
                                                    } else {
                                                        $savedMatches = [$ans->answer_text];
                                                    }
                                                }
                                                
                                                // Find which right items are already matched
                                                $matchedRightItems = array_filter($savedMatches);
                                                
                                                // Draggable right items are those that are NOT already matched, shuffled
                                                $allRightItems = collect($pairs)->pluck('right')->all();
                                                $unmatchedRightItems = collect($allRightItems)
                                                    ->filter(fn($item) => !in_array($item, $matchedRightItems))
                                                    ->shuffle()
                                                    ->all();
                                            @endphp

                                            <div class="row">
                                                <!-- Targets Column (Left Items) -->
                                                <div class="col-md-7 col-12">
                                                    <h6 class="font-weight-bold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">MATCHING TARGETS</h6>
                                                    @foreach($pairs as $pairIndex => $pair)
                                                        @php
                                                            $savedMatchVal = $savedMatches[$pairIndex] ?? '';
                                                        @endphp
                                                        <!-- Hidden input to store match value -->
                                                        <input type="hidden" 
                                                               name="answers[{{ $question->id }}][answer_text][]" 
                                                               id="matching-hidden-{{ $question->id }}-{{ $pairIndex }}" 
                                                               value="{{ $savedMatchVal }}"
                                                               class="matching-hidden-input">

                                                        <div class="d-flex align-items-center mb-3 p-2 border rounded bg-white target-row" style="border-radius: 8px; border-color: #edf2f7;">
                                                            <!-- Left Item Text -->
                                                            <div class="font-weight-bold text-dark px-3 py-2 rounded flex-grow-1" style="min-width: 120px; font-size: 0.9rem; background-color: #f8fafc; border: 1px solid #edf2f7;">
                                                                {{ $pair['left'] }}
                                                            </div>
                                                            
                                                            <i class="feather icon-link text-muted mx-3"></i>
                                                            
                                                            <!-- Drop Zone / Match Zone -->
                                                            <div class="drop-zone border-dashed d-flex align-items-center justify-content-center" 
                                                                 data-question-id="{{ $question->id }}"
                                                                 data-pair-index="{{ $pairIndex }}"
                                                                 ondragover="allowDrop(event)"
                                                                 ondrop="handleDrop(event)"
                                                                 onclick="handleDropzoneClick(this)"
                                                                 style="border: 2px dashed #7367f0; border-radius: 8px; width: 180px; min-height: 42px; background-color: #fbfaff; cursor: pointer; transition: all 0.2s; padding: 4px;">
                                                                
                                                                @if($savedMatchVal)
                                                                    <div class="draggable-card p-2 bg-primary text-white font-weight-bold text-center rounded matched-card" 
                                                                         draggable="{{ $isLocked ? 'false' : 'true' }}"
                                                                         ondragstart="handleDragStart(event)"
                                                                         onclick="handleCardClick(event, this)"
                                                                         data-value="{{ $savedMatchVal }}"
                                                                         data-question-id="{{ $question->id }}"
                                                                         style="cursor: grab; width: 100%; font-size: 0.85rem; border-radius: 6px; box-shadow: 0 2px 4px rgba(115,103,240,0.2);">
                                                                        {{ $savedMatchVal }}
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted font-small-2 drag-placeholder">Drop or click here</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <!-- Draggables Pool Column (Right Items) -->
                                                <div class="col-md-5 col-12">
                                                    <h6 class="font-weight-bold text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">DRAG OR CLICK ITEMS</h6>
                                                    <div class="draggables-pool p-3 border rounded d-flex flex-wrap align-content-start" 
                                                         data-question-id="{{ $question->id }}"
                                                         ondragover="allowDrop(event)"
                                                         ondrop="handleReturnToPool(event)"
                                                         onclick="handlePoolClick(this)"
                                                         style="min-height: 150px; border-radius: 8px; background-color: #f8fafc; border-color: #e2e8f0; gap: 8px;">
                                                        
                                                        @foreach($unmatchedRightItems as $rightItem)
                                                            <div class="draggable-card p-2 bg-white text-primary font-weight-bold text-center border border-primary rounded" 
                                                                 draggable="{{ $isLocked ? 'false' : 'true' }}"
                                                                 ondragstart="handleDragStart(event)"
                                                                 onclick="handleCardClick(event, this)"
                                                                 data-value="{{ $rightItem }}"
                                                                 data-question-id="{{ $question->id }}"
                                                                 style="cursor: grab; width: fit-content; max-width: 100%; font-size: 0.85rem; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-width: 2px !important; transition: all 0.2s;">
                                                                {{ $rightItem }}
                                                            </div>
                                                        @endforeach
                                                        
                                                        @if(count($unmatchedRightItems) === 0 && count($matchedRightItems) === 0)
                                                            <span class="text-muted font-small-2 pool-empty-message">No matching items found.</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <!-- Text / Blanks / Free text -->
                                    @else
                                        @if($question->type === 'fill_in_the_blanks' && $hasInlineBlanks)
                                            <div class="text-muted font-small-3 mt-2"><i class="feather icon-info mr-1 text-info"></i> Please fill in the blanks above.</div>
                                        @else
                                            <div class="form-group mb-0">
                                                @if($question->type === 'free_text')
                                                    <textarea name="answers[{{ $question->id }}][answer_text]" 
                                                              class="form-control test-input text-input" 
                                                              rows="5" 
                                                              placeholder="Type your explanation or essay answer here..."
                                                              oninput="checkTextInput({{ $index + 1 }}, this)"
                                                              @if($isLocked) readonly tabindex="-1" @endif
                                                              style="border: 2px solid {{ $isLocked ? '#cbd5e1' : '#7367f0' }}; background-color: {{ $isLocked ? '#f1f5f9' : '#ffffff' }}; pointer-events: {{ $isLocked ? 'none' : 'auto' }}; border-radius: 12px; padding: 12px; font-size: 0.95rem;">{{ $ans ? $ans->answer_text : '' }}</textarea>
                                                @else
                                                    <input type="text" 
                                                           name="answers[{{ $question->id }}][answer_text]" 
                                                           class="form-control test-input text-input" 
                                                           placeholder="Type your answer here..."
                                                           value="{{ $ans ? $ans->answer_text : '' }}"
                                                           oninput="checkTextInput({{ $index + 1 }}, this)"
                                                           @if($isLocked) readonly tabindex="-1" @endif
                                                           style="border: 2px solid {{ $isLocked ? '#cbd5e1' : '#7367f0' }}; background-color: {{ $isLocked ? '#f1f5f9' : '#ffffff' }}; pointer-events: {{ $isLocked ? 'none' : 'auto' }}; border-radius: 8px; height: 46px; font-size: 0.95rem; max-width: 450px;">
                                                @endif
                                            </div>
                                        @endif
                                    @endif

                                </div>

                                <!-- Instant Feedback Card -->
                                @php
                                    $showFeedback = false;
                                    $feedbackClass = '';
                                    $feedbackTitle = '';
                                    $correctValText = '';
                                    $explanationText = $question->explanation;
                                    
                                    if ($attempt->paper->allow_instant_feedback && $ans && $ans->confidence !== null && $ans->confidence !== '') {
                                        $showFeedback = true;
                                        $isCorrect = $ans->is_correct;
                                        $feedbackClass = $isCorrect ? 'bg-light-success border-success text-success' : 'bg-light-danger border-danger text-danger';
                                        $feedbackTitle = $isCorrect ? 'Correct!' : 'Incorrect';
                                        
                                        if ($question->type === 'fill_in_the_blanks') {
                                            $correctValText = implode(', ', $question->metadata['blank_answers'] ?? []);
                                            if (empty($correctValText)) {
                                                $correctValText = $question->options->where('is_correct', true)->first()?->option_text ?? '';
                                            }
                                        } else {
                                            $correctValText = implode(', ', $question->options->where('is_correct', true)->pluck('option_text')->toArray());
                                        }
                                    }
                                @endphp
                                <div class="instant-feedback-box mt-4 p-3 rounded border {{ $showFeedback ? '' : 'd-none' }} {{ $feedbackClass }}" id="feedback-box-{{ $question->id }}" style="border-width: 2px !important;">
                                    <h5 class="font-weight-bold feedback-title mb-2">{{ $feedbackTitle }}</h5>
                                    <p class="mb-2"><strong>Correct Answer:</strong> <span class="feedback-correct-answer font-weight-bold">{{ $correctValText }}</span></p>
                                    <div class="feedback-explanation p-3 bg-white rounded text-dark font-small-3 border mt-2 {{ $explanationText || ($question->explanation_images && count($question->explanation_images) > 0) ? '' : 'd-none' }}" id="feedback-explanation-container-{{ $question->id }}">
                                        <strong>Explanation:</strong> 
                                        <div class="explanation-content">{!! $explanationText !!}</div>
                                        @if($question->explanation_images && count($question->explanation_images) > 0)
                                            <div class="explanation-images-wrapper mt-2 d-flex flex-wrap align-items-center" style="gap: 8px;">
                                                @foreach($question->explanation_images as $expImg)
                                                    <img src="{{ asset('storage/' . $expImg) }}" class="img-fluid rounded border img-zoomable" style="max-height: 120px; object-fit: contain; cursor: zoom-in; transition: transform 0.2s;" alt="Explanation Image">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @endforeach

                    </div>

                    <!-- Right Panel: Question Index & Stopwatch Timers Table -->
                    <div class="col-md-3 col-12 bg-light d-flex flex-column justify-content-between p-0 border-left right-panel">
                        
                        <div style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                            <div class="p-3 d-flex justify-content-between align-items-center right-panel-header" style="height: 55px; min-height: 55px;">
                                <h6 class="font-weight-bold text-primary text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 1.5px;">Questions Overview</h6>
                                <!-- Digital LED clock on top nearby the header -->
                                <div class="stopwatch-led-container py-1 px-2 d-none d-md-block">
                                    <h5 id="digital-clock" class="mb-0 font-weight-bold text-danger">00:00:00</h5>
                                </div>
                                <!-- Mobile close button -->
                                <button type="button" class="close d-md-none" onclick="toggleSidebar()" aria-label="Close" style="font-size: 1.5rem; line-height: 1; padding: 0; margin: 0; color: #475569; opacity: 0.8; background: none; border: 0;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="questions-list-wrapper" style="flex: 1; overflow-y: auto; padding: 15px;">
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    @foreach($questions as $index => $question)
                                        @php
                                            $ans = $answers->get($question->id);
                                            $statusText = 'Unanswered';
                                            $statusClass = 'status-badge-unanswered';
                                            if ($ans) {
                                                if ($ans->confidence !== null && $ans->confidence !== '') {
                                                    $statusText = ucwords(str_replace('_', ' ', $ans->confidence));
                                                    if ($ans->confidence === 'guess') {
                                                        $statusClass = 'status-badge-guess';
                                                    } elseif ($ans->confidence === 'fairly_sure') {
                                                        $statusClass = 'status-badge-fairly-sure';
                                                    } elseif ($ans->confidence === 'sure') {
                                                        $statusClass = 'status-badge-sure';
                                                    }
                                                } elseif ($ans->selected_option_id || $ans->selected_options || $ans->answer_text) {
                                                    $statusText = 'Answered';
                                                    $statusClass = 'status-badge-answered';
                                                }
                                            }
                                            $isFlagged = ($ans && $ans->is_flagged);
                                        @endphp
                                        <div id="sidebar-row-{{ $index + 1 }}" 
                                             class="question-sidebar-card clickable-row d-flex align-items-center justify-content-between position-relative" 
                                             onclick="jumpToQuestion({{ $index + 1 }})" 
                                             style="cursor: pointer; border-radius: 12px; padding: 10px 14px; margin-bottom: 8px;">
                                            
                                            <!-- Left Dot Indicator for Active Question -->
                                            <span class="active-dot d-none bg-primary" style="position: absolute; left: 6px; top: calc(50% - 4px); width: 8px; height: 8px; border-radius: 50%; box-shadow: 0 0 10px #7367f0;"></span>

                                            <div class="d-flex align-items-center pl-2">
                                                <span class="q-num-label font-weight-bold text-dark mr-2">Q{{ $index + 1 }}</span>
                                                
                                                <span class="flag-icon {{ $isFlagged ? '' : 'd-none' }} text-danger mr-2" style="line-height: 1;"><i class="feather icon-flag font-small-3"></i></span>
                                                
                                                <span class="status-dot-badge badge badge-pill {{ $statusClass }} font-weight-bold">
                                                    {{ $statusText }}
                                                </span>
                                            </div>

                                            <div class="time-pill-container">
                                                <i class="feather icon-clock text-primary mr-1" style="font-size: 0.7rem; vertical-align: middle;"></i>
                                                <span id="sidebar-time-{{ $index + 1 }}" class="time-pill text-monospace font-weight-bold text-primary text-right" style="width: 38px;">
                                                    00:00
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Bottom Navigation/Toolbar -->
                <div class="bottom-toolbar">
                    
                    <!-- Left Actions: Inst & Flag -->
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-outline-secondary btn-toolbar-instructions d-flex align-items-center shadow-sm mr-2" data-toggle="modal" data-target="#instructionsModal">
                            <i class="feather icon-info mr-2 font-medium-1"></i>
                            <span class="btn-text">Instructions</span>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-toolbar-flag d-flex align-items-center shadow-sm" id="flag-btn" onclick="toggleFlag()">
                            <i class="feather icon-flag mr-2 font-medium-1"></i>
                            <span class="btn-text">Flag Question</span>
                        </button>
                    </div>

                    <!-- Middle Actions: Input State & Skip OR Certainty Toggles -->
                    <div class="d-flex align-items-center justify-content-center flex-grow-1">
                        
                        <!-- Go Back Button (Visible in both states, except on Question 1) -->
                        <button type="button" id="go-back-btn" class="btn mr-2 btn-toolbar-back d-flex align-items-center shadow-sm" onclick="prevQuestion()">
                            <i class="feather icon-arrow-left mr-2 font-medium-1"></i>
                            <span class="btn-text">Go Back</span>
                        </button>

                        <!-- Skip Button (Shown only when NOT answered) -->
                        <div id="skip-action-wrapper" class="d-flex align-items-center">
                            <span class="mr-2 font-weight-bold text-primary px-3 py-1.5 rounded-lg border bg-light d-none d-sm-inline" style="font-size: 0.72rem; background-color: #f8fafc; border-color: #e2e8f0; line-height: 1.5;">
                                <span id="current-input-count">0</span> / 1 Selected
                            </span>
                            <button type="button" class="btn btn-primary btn-toolbar-skip d-flex align-items-center shadow" onclick="skipQuestion()">
                                <span class="btn-text mr-2">Skip</span>
                                <i class="feather icon-arrow-right font-medium-1"></i>
                            </button>
                        </div>

                        <!-- Certainty Buttons (Shown only when answered) -->
                        <div id="confidence-action-wrapper" class="d-none align-items-center">
                            <button type="button" class="btn mr-2 btn-toolbar-guess d-flex align-items-center shadow-sm" onclick="selectConfidence('guess')">
                                <i class="feather icon-help-circle mr-2 font-medium-1"></i>
                                <span class="btn-text">Guess</span>
                            </button>
                            <button type="button" class="btn mr-2 btn-toolbar-fairly-sure d-flex align-items-center shadow-sm" onclick="selectConfidence('fairly_sure')">
                                <i class="feather icon-smile mr-2 font-medium-1"></i>
                                <span class="btn-text">Fairly Sure</span>
                            </button>
                            <button type="button" class="btn btn-toolbar-sure d-flex align-items-center shadow-sm" onclick="selectConfidence('sure')">
                                <i class="feather icon-check-circle mr-2 font-medium-1"></i>
                                <span class="btn-text">Sure</span>
                            </button>
                        </div>

                        <!-- Feedback Loading (Instant Feedback) -->
                        <div id="feedback-loading-wrapper" class="d-none align-items-center">
                            <span class="spinner-border spinner-border-sm text-primary mr-2" role="status" aria-hidden="true"></span>
                            <span class="font-weight-bold text-muted" style="font-size: 0.8rem;">Evaluating answer...</span>
                        </div>

                        <!-- Continue Button (Instant Feedback) -->
                        <div id="continue-action-wrapper" class="d-none align-items-center">
                            <button type="button" class="btn btn-success d-flex align-items-center shadow" onclick="advanceAfterFeedback()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; border: none !important; border-radius: 30px; font-weight: 700; padding: 6px 20px;">
                                <span class="btn-text mr-2">Continue</span>
                                <i class="feather icon-arrow-right font-medium-1"></i>
                            </button>
                        </div>

                    </div>

                    <!-- Right Actions: Pause & Finish -->
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-outline-warning btn-toolbar-pause d-flex align-items-center shadow-sm mr-2" onclick="submitTestForm('pause')">
                            <i class="feather icon-pause mr-2 font-medium-1"></i>
                            <span class="btn-text">Pause</span>
                        </button>
                        <button type="button" class="btn btn-success btn-toolbar-finish d-flex align-items-center shadow" onclick="submitTestForm('submit')">
                            <i class="feather icon-check mr-2 font-medium-1"></i>
                            <span class="btn-text">Finish</span>
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <!-- Instructions Modal -->
    <div class="modal fade text-left" id="instructionsModal" tabindex="-1" role="dialog" aria-labelledby="instructionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: 0;">
                <div class="modal-header text-white" style="background-color: #0b5891;">
                    <h5 class="modal-title text-white font-weight-bold" id="instructionsModalLabel">Test Instructions</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-dark font-medium-1 p-4" style="line-height: 1.6;">
                    {!! $attempt->paper->instruction ?? '<p>No specific instructions provided. Answer all questions to the best of your ability.</p>' !!}
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" style="border-radius: 6px;">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Modern Jakarta Font */
        html, body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: #f1f5f9 !important;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* FORCE TRUE FULLSCREEN PLAYER VIEW OVERRIDING ALL THEME WRAPPERS & HORIZONTAL MENUS */
        .fullscreen-player-content {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            z-index: 99999 !important;
            background-color: #f1f5f9 !important;
            overflow: hidden !important;
        }

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

        body.vertical-layout.navbar-floating .app-content,
        body.horizontal-layout.navbar-floating .app-content,
        .app-content, 
        .content-wrapper, 
        .content-body {
            padding: 0 !important;
            margin: 0 !important;
            min-height: 100vh !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow: hidden !important;
            height: 100vh !important;
        }

        /* COMPACT INTERNAL SCROLLING LAYOUT */
        .player-frame {
            height: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            border: 0 !important;
            border-radius: 0 !important;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: none !important;
            background-color: #ffffff;
        }

        .top-status-bar {
            flex-shrink: 0;
            z-index: 100;
        }

        .left-panel {
            overflow-y: auto;
            flex-grow: 1;
            padding: 25px 40px !important;
            background-color: #ffffff;
            height: 100%;
        }

        .right-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-left: 1px solid rgba(226, 232, 240, 0.4) !important;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.35) 0%, rgba(241, 245, 249, 0.2) 100%) !important;
            backdrop-filter: blur(25px) saturate(210%);
            -webkit-backdrop-filter: blur(25px) saturate(210%);
            height: 100%;
            box-shadow: -5px 0 25px rgba(0, 0, 0, 0.015) !important;
        }

        .bottom-toolbar {
            flex-shrink: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.03);
            padding: 12px 24px;
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            align-items: center;
        }

        /* TOOLBAR BUTTONS */
        .bottom-toolbar .btn {
            border-radius: 30px !important;
            font-size: 0.72rem !important;
            font-weight: 700 !important;
            padding: 6px 14px !important;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            letter-spacing: 0.3px;
        }

        .bottom-toolbar .btn i {
            font-size: 0.85rem;
        }

        .btn-toolbar-instructions {
            border-color: #cbd5e1 !important;
            color: #475569 !important;
            background-color: #ffffff !important;
        }
        .btn-toolbar-instructions:hover {
            background-color: #f8fafc !important;
            border-color: #94a3b8 !important;
            color: #1e293b !important;
        }

        .btn-toolbar-flag {
            border-color: #fee2e2 !important;
            color: #dc2626 !important;
            background-color: #ffffff !important;
        }
        .btn-toolbar-flag:hover {
            background-color: #fef2f2 !important;
            border-color: #fca5a5 !important;
            color: #b91c1c !important;
        }

        .btn-toolbar-skip {
            background: linear-gradient(135deg, #7367f0 0%, #4839eb 100%) !important;
            border: none !important;
            color: #ffffff !important;
            padding: 6px 16px !important;
        }
        .btn-toolbar-skip:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(115, 103, 240, 0.2) !important;
        }

        .btn-toolbar-back {
            background-color: #f1f5f9 !important;
            border: 1px solid #cbd5e1 !important;
            color: #475569 !important;
        }
        .btn-toolbar-back:hover {
            background-color: #e2e8f0 !important;
            color: #1e293b !important;
        }

        .btn-toolbar-guess {
            background-color: #fffaf0 !important;
            border: 1px solid #ffe3b3 !important;
            color: #dd6b20 !important;
        }
        .btn-toolbar-guess:hover {
            background-color: #fff4e0 !important;
            color: #c05621 !important;
        }

        .btn-toolbar-fairly-sure {
            background-color: #f5f3ff !important;
            border: 1px solid #e9d5ff !important;
            color: #7c3aed !important;
        }
        .btn-toolbar-fairly-sure:hover {
            background-color: #ede9fe !important;
            color: #6d28d9 !important;
        }

        .btn-toolbar-sure {
            background-color: #f0fdf4 !important;
            border: 1px solid #bbf7d0 !important;
            color: #16a34a !important;
        }
        .btn-toolbar-sure:hover {
            background-color: #dcfce7 !important;
            color: #15803d !important;
        }

        .btn-toolbar-pause {
            border-color: #fef3c7 !important;
            color: #d97706 !important;
            background-color: #ffffff !important;
        }
        .btn-toolbar-pause:hover {
            background-color: #fffbeb !important;
            border-color: #fde68a !important;
            color: #b45309 !important;
        }

        .btn-toolbar-finish {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border: none !important;
            color: #ffffff !important;
            padding: 6px 16px !important;
        }
        .btn-toolbar-finish:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
        }

        .bg-light-primary {
            background-color: rgba(115, 103, 240, 0.08) !important;
        }
        .bg-light-success {
            background-color: rgba(40, 199, 111, 0.08) !important;
        }
        .bg-light-warning {
            background-color: rgba(255, 159, 67, 0.08) !important;
        }
        .bg-light-danger {
            background-color: rgba(234, 84, 85, 0.08) !important;
        }

        /* SELECTION CARD ANIMATIONS */
        .option-card {
            cursor: pointer;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: #ffffff;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            border-radius: 12px !important;
            padding: 14px 18px !important;
            display: flex;
            align-items: center;
        }
        .option-card:hover {
            border-color: #7367f0 !important;
            background-color: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(115, 103, 240, 0.06);
        }
        .selected-option-card {
            border-color: #7367f0 !important;
            border-width: 2px !important;
            background: linear-gradient(135deg, #f5f3ff 0%, #fbfaff 100%) !important;
            box-shadow: 0 5px 12px rgba(115, 103, 240, 0.1) !important;
        }
        .option-letter {
            width: 34px;
            height: 34px;
            background-color: #f1efff;
            color: #7367f0;
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }
        .selected-option-card .option-letter {
            background-color: #7367f0 !important;
            color: white !important;
            box-shadow: 0 2px 4px rgba(115, 103, 240, 0.2);
        }

        .clickable-row:hover {
            background-color: #f1f5f9;
        }
        .active-row {
            background-color: #eef2ff !important;
        }
        .active-row .active-dot {
            display: inline !important;
        }

        .stopwatch-led-container {
            border: 2px solid #334155;
            background-color: #0f172a;
            border-radius: 8px;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.5), 0 2px 4px rgba(0,0,0,0.1);
        }
        #digital-clock {
            font-family: 'Share Tech Mono', monospace;
            color: #f87171; /* Neon red-orange */
            text-shadow: 0 0 10px rgba(248, 113, 113, 0.7);
            letter-spacing: 1px;
            font-size: 1rem;
            margin: 0;
        }

        #flag-btn.flagged-active {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3) !important;
            animation: pulse 1s infinite alternate;
        }
        #flag-btn.flagged-active i {
            color: #ffffff !important;
        }

        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.08); }
        }

        .stat-pill {
            box-shadow: 0 1px 2px rgba(0,0,0,0.01);
            border: 1px solid #edf2f7;
            border-radius: 8px;
        }

        /* MOBILE RESPONSIVE DRAWER & TOOLBAR */
        .drawer-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 99999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .drawer-backdrop.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 767.98px) {
            .left-panel {
                padding: 20px 20px !important;
            }


            .drawer-backdrop {
                z-index: 999999 !important;
            }

            .right-panel {
                position: fixed !important;
                top: 0 !important;
                right: -300px !important;
                width: 300px !important;
                max-width: 300px !important;
                flex: 0 0 300px !important;
                height: 100vh !important;
                z-index: 1000000 !important;
                background: rgba(255, 255, 255, 0.6) !important;
                backdrop-filter: blur(25px) saturate(210%) !important;
                -webkit-backdrop-filter: blur(25px) saturate(210%) !important;
                display: flex !important;
                transition: right 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.05) !important;
                border-left: 1px solid rgba(255, 255, 255, 0.3) !important;
            }

            .right-panel.open {
                right: 0 !important;
            }

            /* Option cards stack on mobile */
            .answer-section .row > [class*="col-"] {
                max-width: 100%;
                flex: 0 0 100%;
            }

            /* Bottom Toolbar 2-Row Stack Layout on Mobile */
            .bottom-toolbar {
                padding: 10px 14px !important;
                display: flex !important;
                flex-wrap: wrap !important;
                justify-content: space-between !important;
                gap: 10px 4px !important;
                background: #ffffff !important;
            }
            
            /* Order containers on mobile */
            .bottom-toolbar > div:nth-child(1) { /* Left Actions (Instructions, Flag) */
                order: 1 !important;
                flex: 0 0 auto !important;
            }
            .bottom-toolbar > div:nth-child(3) { /* Right Actions (Pause, Finish) */
                order: 2 !important;
                flex: 0 0 auto !important;
            }
            .bottom-toolbar > div:nth-child(2) { /* Middle Actions (Back, Skip/Certainty) */
                order: 3 !important;
                flex: 0 0 100% !important;
                justify-content: center !important;
                margin-top: 4px !important;
            }

            /* Secondary actions (Instructions, Flag, Pause) -> icon only on mobile */
            .btn-toolbar-instructions,
            .btn-toolbar-flag,
            .btn-toolbar-pause {
                width: 38px !important;
                height: 38px !important;
                min-width: 38px !important;
                border-radius: 50% !important;
                padding: 0 !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                margin-right: 6px !important;
            }
            .btn-toolbar-instructions .btn-text,
            .btn-toolbar-flag .btn-text,
            .btn-toolbar-pause .btn-text {
                display: none !important;
            }
            .btn-toolbar-instructions i,
            .btn-toolbar-flag i,
            .btn-toolbar-pause i {
                margin-right: 0 !important;
                font-size: 1.05rem !important;
            }

            /* Primary actions (Go Back, Skip, Certainty buttons, Finish) -> keep text labels & nice touch sizes */
            .btn-toolbar-skip,
            .btn-toolbar-finish {
                border-radius: 20px !important;
                font-size: 0.75rem !important;
                padding: 8px 14px !important;
                height: 38px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                margin-right: 6px !important;
            }
            .btn-toolbar-skip i,
            .btn-toolbar-finish i {
                font-size: 0.9rem !important;
            }

            /* Certainty Buttons & Back Button: circular icon-only layout on all mobile devices under 768px */
            .btn-toolbar-back,
            .btn-toolbar-guess,
            .btn-toolbar-fairly-sure,
            .btn-toolbar-sure {
                width: 38px !important;
                height: 38px !important;
                min-width: 38px !important;
                border-radius: 50% !important;
                padding: 0 !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                margin-right: 6px !important;
            }
            .btn-toolbar-back .btn-text,
            .btn-toolbar-guess .btn-text,
            .btn-toolbar-fairly-sure .btn-text,
            .btn-toolbar-sure .btn-text {
                display: none !important;
            }
            .btn-toolbar-back i,
            .btn-toolbar-guess i,
            .btn-toolbar-fairly-sure i,
            .btn-toolbar-sure i {
                margin-right: 0 !important;
                font-size: 1.05rem !important;
            }

            #skip-action-wrapper span, 
            #confidence-action-wrapper span {
                font-size: 0.7rem !important;
                padding: 4px 8px !important;
                margin-right: 6px !important;
            }
        }

        @media (min-width: 768px) and (max-width: 991.98px) {
            .bottom-toolbar {
                padding: 8px 16px !important;
            }
            .bottom-toolbar .btn {
                font-size: 0.7rem !important;
                padding: 6px 10px !important;
            }
            .bottom-toolbar .btn i {
                font-size: 0.8rem !important;
            }
        }

        /* Ensure SweetAlert is on top of the fullscreen player wrapper */
        .swal2-container {
            z-index: 999999 !important;
        }

        /* Drag and Drop Styles */
        .border-dashed {
            border-style: dashed !important;
        }
        .drop-zone:hover {
            background-color: #f1efff !important;
            border-color: #4839eb !important;
        }
        .drop-zone.drag-active {
            background-color: #eef2ff !important;
            border-color: #7367f0 !important;
        }
        .draggable-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.08) !important;
        }
        .draggable-card.selected-card {
            border-color: #28c76f !important;
            box-shadow: 0 0 0 3px rgba(40, 199, 111, 0.25) !important;
        }

        /* Premium Glassmorphic Sidebar styles */
        .right-panel-header {
            background: rgba(255, 255, 255, 0.35) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5) !important;
        }

        .questions-list-wrapper::-webkit-scrollbar {
            width: 6px;
        }
        .questions-list-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }
        .questions-list-wrapper::-webkit-scrollbar-thumb {
            background: rgba(115, 103, 240, 0.15);
            border-radius: 4px;
        }
        .questions-list-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(115, 103, 240, 0.3);
        }

        .question-sidebar-card {
            background: rgba(255, 255, 255, 0.35) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            border-left: 4px solid transparent !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01) !important;
            border-radius: 12px !important;
            padding: 10px 14px 10px 10px !important;
            margin-bottom: 8px;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.05, 1) !important;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .question-sidebar-card:hover {
            background: rgba(255, 255, 255, 0.75) !important;
            border-color: rgba(115, 103, 240, 0.4) !important;
            border-left: 4px solid rgba(115, 103, 240, 0.4) !important;
            transform: translateY(-2px) scale(1.015);
            box-shadow: 0 6px 15px rgba(115, 103, 240, 0.06) !important;
        }

        .question-sidebar-card.active-row {
            background: linear-gradient(135deg, rgba(115, 103, 240, 0.06) 0%, rgba(115, 103, 240, 0.12) 100%) !important;
            border-color: rgba(115, 103, 240, 0.6) !important;
            border-left: 4px solid #7367f0 !important;
            box-shadow: 0 6px 18px rgba(115, 103, 240, 0.1) !important;
            transform: scale(1.02);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .question-sidebar-card.active-row .active-dot {
            display: inline !important;
        }

        .q-num-label {
            font-size: 0.88rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            letter-spacing: -0.3px;
        }

        /* Glass status badges */
        .status-dot-badge {
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.4px;
            padding: 4px 10px !important;
            border-radius: 20px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            text-transform: uppercase;
        }
        
        .status-badge-unanswered {
            background: rgba(239, 68, 68, 0.12) !important;
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.2) !important;
        }

        .status-badge-answered {
            background: rgba(59, 130, 246, 0.12) !important;
            color: #3b82f6 !important;
            border: 1px solid rgba(59, 130, 246, 0.2) !important;
        }

        .status-badge-guess {
            background: rgba(245, 158, 11, 0.12) !important;
            color: #f59e0b !important;
            border: 1px solid rgba(245, 158, 11, 0.2) !important;
        }

        .status-badge-fairly-sure {
            background: rgba(99, 102, 241, 0.12) !important;
            color: #6366f1 !important;
            border: 1px solid rgba(99, 102, 241, 0.2) !important;
        }

        .status-badge-sure {
            background: rgba(16, 185, 129, 0.12) !important;
            color: #10b981 !important;
            border: 1px solid rgba(16, 185, 129, 0.2) !important;
        }

        .time-pill-container {
            background: rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.03);
            border-radius: 20px;
            padding: 2px 8px;
            display: flex;
            align-items: center;
        }

        .time-pill {
            font-family: 'Share Tech Mono', monospace;
            font-size: 0.78rem !important;
            color: #475569 !important;
        }

        /* Glass stopwatch clock styling */
        .stopwatch-led-container {
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
            background: rgba(15, 23, 42, 0.65) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 30px !important;
            padding: 4px 12px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08), inset 0 1px 1px rgba(255, 255, 255, 0.1) !important;
            display: inline-flex;
            align-items: center;
        }

        #digital-clock {
            font-family: 'Share Tech Mono', monospace;
            color: #f43f5e !important;
            text-shadow: 0 0 8px rgba(244, 63, 94, 0.6);
            letter-spacing: 1px;
            font-size: 0.9rem;
            margin: 0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let remainingSeconds = @json($remainingSeconds); // overall test timer
        let isCountdown = remainingSeconds !== null;
        let elapsedSeconds = {{ max(0, $attempt->time_spent) + (int) $attempt->started_at->diffInSeconds(now()) }}; // countup backup timer if no remaining limit

        let currentQuestion = 1;
        let totalQuestions = @json($questions->count());
        let allowInstantFeedback = @json($attempt->paper->allow_instant_feedback);
        let allowReattempt = @json($attempt->paper->allow_reattempt_question);
        
        let questionTimes = {}; // maps question ID -> seconds spent
        let questionFlagged = {}; // maps question ID -> is flagged (0 or 1)
        let questionSkipped = {}; // maps question ID -> is skipped (true/false)

        // Initialize question times, flagged states from Database values
        @foreach($questions as $question)
            @php
                $ans = $answers->get($question->id);
            @endphp
            questionTimes[{{ $question->id }}] = {{ $ans ? $ans->time_spent : 0 }};
            questionFlagged[{{ $question->id }}] = {{ $ans && $ans->is_flagged ? 1 : 0 }};
            questionSkipped[{{ $question->id }}] = false;
        @endforeach

        let activeQuestionTimer = null;
        let overallTimerInterval = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Move right-panel to fullscreen-player-content on mobile to prevent clipping
            if (window.innerWidth < 768) {
                let panel = document.querySelector('.right-panel');
                let container = document.querySelector('.fullscreen-player-content');
                if (panel && container) {
                    container.appendChild(panel);
                }
            }

            // Show first question
            showQuestion(1);

            // Lock inputs for already answered questions if reattempt is not allowed
            if (!allowReattempt) {
                document.querySelectorAll('.q-card').forEach(card => {
                    let qId = card.getAttribute('data-q-id');
                    let confidence = document.getElementById('confidence-' + qId).value;
                    if (confidence !== '') {
                        lockQuestionInputs(card);
                    }
                });
            }

            // Start timers
            startOverallTimer();
            startActiveQuestionTimer();

            // Populate palette times and flags
            updateSidebarDisplay();
            updateStatsBanner();

            // Auto-save answers every 30 seconds
            setInterval(autoSaveAnswers, 30000);
        });

        // Starts the overall countdown or countup timer at the bottom right
        function startOverallTimer() {
            updateDigitalClock();
            overallTimerInterval = setInterval(function() {
                if (isCountdown) {
                    remainingSeconds--;
                    if (remainingSeconds <= 0) {
                        clearInterval(overallTimerInterval);
                        submitTestForm('submit_auto');
                    }
                } else {
                    elapsedSeconds++;
                }
                updateDigitalClock();
            }, 1000);
        }

        // Update the digital clock displays
        function updateDigitalClock() {
            let clockEl = document.getElementById('digital-clock');
            let mobClockEl = document.getElementById('mobile-digital-clock');
            if (!clockEl && !mobClockEl) return;

            let timeVal = Math.floor(isCountdown ? remainingSeconds : elapsedSeconds);
            if (timeVal < 0) timeVal = 0;

            let hrs = Math.floor(timeVal / 3600);
            let mins = Math.floor((timeVal % 3600) / 60);
            let secs = timeVal % 60;

            let formattedTime = 
                (hrs < 10 ? '0' : '') + hrs + ':' +
                (mins < 10 ? '0' : '') + mins + ':' +
                (secs < 10 ? '0' : '') + secs;

            if (clockEl) clockEl.textContent = formattedTime;
            if (mobClockEl) mobClockEl.textContent = formattedTime;
        }

        // Increments current question's time count every second
        function startActiveQuestionTimer() {
            if (activeQuestionTimer) clearInterval(activeQuestionTimer);
            
            activeQuestionTimer = setInterval(function() {
                let card = document.getElementById('q-card-' + currentQuestion);
                if (!card) return;

                let qId = card.getAttribute('data-q-id');
                questionTimes[qId]++;

                // Update hidden time spent input
                document.getElementById('time-spent-' + qId).value = questionTimes[qId];

                // Update sidebar cell display
                document.getElementById('sidebar-time-' + currentQuestion).textContent = formatMMSS(questionTimes[qId]);
            }, 1000);
        }

        // Format seconds to MM:SS
        function formatMMSS(sec) {
            let m = Math.floor(sec / 60);
            let s = sec % 60;
            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }

        // Show a specific question card
        function showQuestion(qNum) {
            if (qNum < 1 || qNum > totalQuestions) return;

            // Pause question timer during transition
            if (activeQuestionTimer) clearInterval(activeQuestionTimer);

            // Hide previous question card
            document.querySelectorAll('.q-card').forEach(card => card.classList.add('d-none'));

            // Show target question card
            let targetCard = document.getElementById('q-card-' + qNum);
            if (targetCard) {
                targetCard.classList.remove('d-none');
            }

            // Update active state in sidebar rows
            document.querySelectorAll('.clickable-row').forEach(row => row.classList.remove('active-row'));
            let activeRow = document.getElementById('sidebar-row-' + qNum);
            if (activeRow) {
                activeRow.classList.add('active-row');
            }

            // Update current state variables
            currentQuestion = qNum;

            // Update bottom toolbar counts
            updateCurrentInputLabel();

            // Toggle actions based on active question answer status
            let qId = targetCard.getAttribute('data-q-id');
            let hasAnswer = checkQuestionAnswered(qId);
            toggleBottomToolbarActions(hasAnswer);

            // Hide/Show Go Back button based on whether it is the first question
            let goBackBtn = document.getElementById('go-back-btn');
            if (goBackBtn) {
                if (currentQuestion === 1) {
                    goBackBtn.classList.add('d-none');
                    goBackBtn.classList.remove('d-flex');
                } else {
                    goBackBtn.classList.remove('d-none');
                    goBackBtn.classList.add('d-flex');
                }
            }

            // Set flagged button active state if current is flagged
            let isFlagged = document.getElementById('is-flagged-' + qId).value === '1';
            if (isFlagged) {
                document.getElementById('flag-btn').classList.add('flagged-active');
            } else {
                document.getElementById('flag-btn').classList.remove('flagged-active');
            }

            // Resume question timer
            startActiveQuestionTimer();
        }

        // Sidebar row clicks
        function jumpToQuestion(qNum) {
            showQuestion(qNum);
            // Close mobile sidebar if open
            let panel = document.querySelector('.right-panel');
            if (panel && panel.classList.contains('open')) {
                toggleSidebar();
            }
        }

        // Navigate to the previous question
        function prevQuestion() {
            if (currentQuestion > 1) {
                showQuestion(currentQuestion - 1);
            } else {
                showQuestion(totalQuestions); // Wrap
            }
        }

        // Toggle mobile sidebar drawer
        function toggleSidebar() {
            let panel = document.querySelector('.right-panel');
            let backdrop = document.querySelector('.drawer-backdrop');
            if (!panel || !backdrop) return;
            
            if (panel.classList.contains('open')) {
                panel.classList.remove('open');
                backdrop.classList.remove('show');
                setTimeout(() => {
                    backdrop.style.display = 'none';
                }, 300);
            } else {
                backdrop.style.display = 'block';
                // Force reflow
                backdrop.offsetHeight;
                panel.classList.add('open');
                backdrop.classList.add('show');
            }
        }

        // Trigger skip button action (advances index, marks skipped if empty)
        function skipQuestion() {
            let card = document.getElementById('q-card-' + currentQuestion);
            let prevQ = currentQuestion;
            if (card) {
                let qId = card.getAttribute('data-q-id');
                let hasAnswer = checkQuestionAnswered(qId);
                if (!hasAnswer) {
                    questionSkipped[qId] = true;
                }
            }
            updateSidebarCardStatus(prevQ);

            if (currentQuestion < totalQuestions) {
                showQuestion(currentQuestion + 1);
            } else {
                showQuestion(1); // Wrap
            }

            updateStatsBanner();
        }

        // Toggle Flag button action
        function toggleFlag() {
            let card = document.getElementById('q-card-' + currentQuestion);
            if (!card) return;
            
            let qId = card.getAttribute('data-q-id');
            let hiddenInput = document.getElementById('is-flagged-' + qId);
            if (!hiddenInput) return;

            let isFlagged = hiddenInput.value === '1';
            if (isFlagged) {
                hiddenInput.value = '0';
                document.getElementById('flag-btn').classList.remove('flagged-active');
                document.querySelector('#sidebar-row-' + currentQuestion + ' .flag-icon').classList.add('d-none');
            } else {
                hiddenInput.value = '1';
                document.getElementById('flag-btn').classList.add('flagged-active');
                document.querySelector('#sidebar-row-' + currentQuestion + ' .flag-icon').classList.remove('d-none');
            }
            
            updateStatsBanner();
        }

        // Check if question is answered
        function checkQuestionAnswered(qId) {
            let card = document.querySelector(`.q-card[data-q-id="${qId}"]`);
            if (!card) return false;

            let radioChecked = card.querySelector('input[type="radio"]:checked');
            let checkboxChecked = card.querySelector('input[type="checkbox"]:checked');
            
            // Check if any select has a value
            let selectValued = false;
            card.querySelectorAll('select').forEach(sel => {
                if (sel.value !== '') selectValued = true;
            });

            // Check if any text/textarea input has text
            let textValued = false;
            card.querySelectorAll('input[type="text"], textarea').forEach(txt => {
                if (txt.value.trim() !== '') textValued = true;
            });

            // Check if any matching hidden input has value
            let matchingValued = false;
            card.querySelectorAll('.matching-hidden-input').forEach(hidden => {
                if (hidden.value !== '') matchingValued = true;
            });

            return !!(radioChecked || checkboxChecked || selectValued || textValued || matchingValued);
        }

        // Toggle actions bar between Skip and Certainty options
        function toggleBottomToolbarActions(isAnswered) {
            let skipWrapper = document.getElementById('skip-action-wrapper');
            let confidenceWrapper = document.getElementById('confidence-action-wrapper');
            let continueWrapper = document.getElementById('continue-action-wrapper');
            let feedbackLoading = document.getElementById('feedback-loading-wrapper');
            if (!skipWrapper || !confidenceWrapper) return;

            skipWrapper.classList.replace('d-flex', 'd-none');
            confidenceWrapper.classList.replace('d-flex', 'd-none');
            if (continueWrapper) continueWrapper.classList.replace('d-flex', 'd-none');
            if (feedbackLoading) feedbackLoading.classList.replace('d-flex', 'd-none');

            let card = document.getElementById('q-card-' + currentQuestion);
            if (!card) return;
            let qId = card.getAttribute('data-q-id');
            let confidence = document.getElementById('confidence-' + qId).value;

            if (!allowReattempt && confidence !== '') {
                // Locked question, do not show skip or confidence buttons
            } else {
                if (isAnswered) {
                    confidenceWrapper.classList.replace('d-none', 'd-flex');
                } else {
                    skipWrapper.classList.replace('d-none', 'd-flex');
                }
            }
        }

        // Selected option handler from cards grid clicks (supports toggle check/uncheck)
        function selectOption(questionId, optionId, type) {
            let container = document.getElementById('option-container-' + optionId);
            let input = document.getElementById('opt-' + optionId);
            if (!input || !container) return;

            if (type === 'single_choice_radio' || type === 'picture_choice') {
                let isAlreadyChecked = container.classList.contains('selected-option-card');
                
                // Clear selected style on siblings
                let questionCard = container.closest('.q-card');
                questionCard.querySelectorAll('.option-card').forEach(box => {
                    box.classList.remove('selected-option-card');
                });
                
                if (isAlreadyChecked) {
                    input.checked = false;
                    // Clear confidence on uncheck
                    document.getElementById('confidence-' + questionId).value = '';
                    toggleBottomToolbarActions(false);
                } else {
                    input.checked = true;
                    container.classList.add('selected-option-card');
                    questionSkipped[questionId] = false;
                    toggleBottomToolbarActions(true);
                }
            } else if (type === 'multiple_choice') {
                input.checked = !input.checked;
                if (input.checked) {
                    container.classList.add('selected-option-card');
                    questionSkipped[questionId] = false;
                } else {
                    container.classList.remove('selected-option-card');
                }
                
                let questionCard = container.closest('.q-card');
                let checkboxes = questionCard.querySelectorAll('input[type="checkbox"]:checked');
                if (checkboxes.length > 0) {
                    toggleBottomToolbarActions(true);
                } else {
                    document.getElementById('confidence-' + questionId).value = '';
                    toggleBottomToolbarActions(false);
                }
            }

            updateCurrentInputLabel();
            updateStatsBanner();
            updateSidebarCardStatus(currentQuestion);
        }

        // Handles confidence selection Guess, Fairly Sure, Sure and advances question
        // Also triggers background save of answers
        function selectConfidence(level) {
            let card = document.getElementById('q-card-' + currentQuestion);
            if (!card) return;

            let qId = card.getAttribute('data-q-id');
            document.getElementById('confidence-' + qId).value = level;
            updateSidebarCardStatus(currentQuestion);

            if (allowInstantFeedback) {
                let confidenceWrapper = document.getElementById('confidence-action-wrapper');
                let feedbackLoading = document.getElementById('feedback-loading-wrapper');
                if (confidenceWrapper) confidenceWrapper.classList.replace('d-flex', 'd-none');
                if (feedbackLoading) {
                    feedbackLoading.classList.remove('d-none');
                    feedbackLoading.classList.add('d-flex');
                }

                autoSaveAnswers()
                    .then(data => {
                        if (feedbackLoading) feedbackLoading.classList.replace('d-flex', 'd-none');
                        
                        if (data && data.evaluations) {
                            let evaluation = data.evaluations.find(e => e.question_id == qId);
                            if (evaluation) {
                                let feedbackBox = document.getElementById('feedback-box-' + qId);
                                if (feedbackBox) {
                                    feedbackBox.classList.remove('d-none');
                                    feedbackBox.classList.remove('bg-light-success', 'border-success', 'text-success', 'bg-light-danger', 'border-danger', 'text-danger');
                                    if (evaluation.is_correct) {
                                        feedbackBox.classList.add('bg-light-success', 'border-success', 'text-success');
                                        feedbackBox.querySelector('.feedback-title').textContent = 'Correct!';
                                    } else {
                                        feedbackBox.classList.add('bg-light-danger', 'border-danger', 'text-danger');
                                        feedbackBox.querySelector('.feedback-title').textContent = 'Incorrect';
                                    }
                                    
                                    feedbackBox.querySelector('.feedback-correct-answer').textContent = evaluation.correct_answer;
                                    
                                    let explanationBox = document.getElementById('feedback-explanation-container-' + qId);
                                    if (evaluation.explanation || (explanationBox && explanationBox.querySelector('.explanation-images-wrapper'))) {
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

                        if (!allowReattempt) {
                            lockQuestionInputs(card);
                        }

                        let continueWrapper = document.getElementById('continue-action-wrapper');
                        if (continueWrapper) {
                            continueWrapper.classList.remove('d-none');
                            continueWrapper.classList.add('d-flex');
                        }
                        
                        updateStatsBanner();
                    })
                    .catch(err => {
                        if (feedbackLoading) feedbackLoading.classList.replace('d-flex', 'd-none');
                        if (confidenceWrapper) confidenceWrapper.classList.replace('d-none', 'd-flex');
                        Swal.fire('Error', 'Failed to save answer. Please try again.', 'error');
                    });
            } else {
                autoSaveAnswers();
                
                if (!allowReattempt) {
                    lockQuestionInputs(card);
                }

                if (currentQuestion < totalQuestions) {
                    showQuestion(currentQuestion + 1);
                } else {
                    showQuestion(1); // wrap
                }

                updateStatsBanner();
            }
        }

        function markAnswered(qNum) {
            // Placeholder
        }

        function checkMultipleAnswered(qNum, questionId) {
            // Placeholder
        }

        function checkTextInput(qNum, element) {
            let card = document.getElementById('q-card-' + qNum);
            if (card) {
                let qId = card.getAttribute('data-q-id');
                if (checkQuestionAnswered(qId)) {
                    questionSkipped[qId] = false;
                    toggleBottomToolbarActions(true);
                } else {
                    document.getElementById('confidence-' + qId).value = '';
                    toggleBottomToolbarActions(false);
                }
            }
            updateCurrentInputLabel();
            updateStatsBanner();
            updateSidebarCardStatus(qNum);
        }

        function selectDropdownOption(qNum, element) {
            let card = document.getElementById('q-card-' + qNum);
            if (card) {
                let qId = card.getAttribute('data-q-id');
                if (checkQuestionAnswered(qId)) {
                    questionSkipped[qId] = false;
                    toggleBottomToolbarActions(true);
                } else {
                    document.getElementById('confidence-' + qId).value = '';
                    toggleBottomToolbarActions(false);
                }
            }
            updateCurrentInputLabel();
            updateStatsBanner();
            updateSidebarCardStatus(qNum);
        }

        // Live input counters
        function updateCurrentInputLabel() {
            let card = document.getElementById('q-card-' + currentQuestion);
            if (!card) return;

            let hasAnswer = checkQuestionAnswered(card.getAttribute('data-q-id'));
            document.getElementById('current-input-count').textContent = hasAnswer ? '1' : '0';
        }

        function lockQuestionInputs(card) {
            if (!card) return;
            card.querySelectorAll('.option-card').forEach(box => {
                box.style.pointerEvents = 'none';
            });
            card.querySelectorAll('select').forEach(sel => {
                sel.style.pointerEvents = 'none';
                sel.setAttribute('tabindex', '-1');
                sel.style.backgroundColor = '#f1f5f9';
                sel.style.borderColor = '#cbd5e1';
            });
            card.querySelectorAll('input[type="text"], textarea').forEach(input => {
                input.readOnly = true;
                input.setAttribute('tabindex', '-1');
                input.style.pointerEvents = 'none';
                input.style.backgroundColor = '#f1f5f9';
                input.style.borderColor = '#cbd5e1';
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

        function advanceAfterFeedback() {
            let continueWrapper = document.getElementById('continue-action-wrapper');
            if (continueWrapper) {
                continueWrapper.classList.replace('d-flex', 'd-none');
            }

            if (currentQuestion < totalQuestions) {
                showQuestion(currentQuestion + 1);
            } else {
                showQuestion(1); // wrap
            }
        }

        // Update status badge on sidebar for a specific question card
        function updateSidebarCardStatus(qNum) {
            let card = document.getElementById('q-card-' + qNum);
            if (!card) return;

            let qId = card.getAttribute('data-q-id');
            let badge = document.querySelector('#sidebar-row-' + qNum + ' .status-dot-badge');
            if (!badge) return;

            let confidence = document.getElementById('confidence-' + qId).value;
            let hasAnswer = checkQuestionAnswered(qId);

            let statusText = 'Unanswered';
            let statusClass = 'status-badge-unanswered';

            if (confidence !== null && confidence !== '') {
                statusText = ucWords(confidence.replace('_', ' '));
                if (confidence === 'guess') {
                    statusClass = 'status-badge-guess';
                } else if (confidence === 'fairly_sure') {
                    statusClass = 'status-badge-fairly-sure';
                } else if (confidence === 'sure') {
                    statusClass = 'status-badge-sure';
                }
            } else if (hasAnswer) {
                statusText = 'Answered';
                statusClass = 'status-badge-answered';
            } else if (questionSkipped[qId]) {
                statusText = 'Skipped';
                statusClass = 'status-badge-unanswered';
            }

            badge.className = 'status-dot-badge badge badge-pill ' + statusClass + ' font-weight-bold';
            badge.textContent = statusText;
        }

        function ucWords(str) {
            return str.replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
        }

        // Refresh times and flags on sidebar table
        function updateSidebarDisplay() {
            for (let i = 1; i <= totalQuestions; i++) {
                let card = document.getElementById('q-card-' + i);
                if (!card) continue;

                let qId = card.getAttribute('data-q-id');
                
                // Set time spent cell text
                document.getElementById('sidebar-time-' + i).textContent = formatMMSS(questionTimes[qId]);

                // Set flag cell visibility
                let isFlagged = document.getElementById('is-flagged-' + qId).value === '1';
                if (isFlagged) {
                    document.querySelector('#sidebar-row-' + i + ' .flag-icon').classList.remove('d-none');
                } else {
                    document.querySelector('#sidebar-row-' + i + ' .flag-icon').classList.add('d-none');
                }

                // Update status badge
                updateSidebarCardStatus(i);
            }
        }

        // Computes real-time status stats on the top banner including certainty breakdowns
        function updateStatsBanner() {
            let answered = 0;
            let flagged = 0;
            let skipped = 0;
            let unanswered = 0;

            let guess = 0;
            let fairlySure = 0;
            let sure = 0;

            for (let i = 1; i <= totalQuestions; i++) {
                let card = document.getElementById('q-card-' + i);
                if (!card) continue;

                let qId = card.getAttribute('data-q-id');
                
                // Check flagged
                let isFlagged = document.getElementById('is-flagged-' + qId).value === '1';
                if (isFlagged) {
                    flagged++;
                }

                // Check confidence level
                let confidence = document.getElementById('confidence-' + qId).value;
                if (confidence === 'guess') {
                    guess++;
                } else if (confidence === 'fairly_sure') {
                    fairlySure++;
                } else if (confidence === 'sure') {
                    sure++;
                }

                // Check answered
                let hasAnswer = checkQuestionAnswered(qId);
                if (hasAnswer) {
                    answered++;
                } else {
                    if (questionSkipped[qId]) {
                        skipped++;
                    } else {
                        unanswered++;
                    }
                }
            }

            document.getElementById('stat-answered').textContent = answered;
            document.getElementById('stat-guess-bracket').textContent = guess;
            document.getElementById('stat-fairly-sure-bracket').textContent = fairlySure;
            document.getElementById('stat-sure-bracket').textContent = sure;

            document.getElementById('stat-skipped').textContent = skipped;
            document.getElementById('stat-unanswered').textContent = unanswered;
        }

        // Submits test form with Pause or Submit actions
        function submitTestForm(action) {
            let form = document.getElementById('test-taking-form');
            if (!form) return;

            // Stop question timer
            if (activeQuestionTimer) clearInterval(activeQuestionTimer);

            if (action === 'pause') {
                Swal.fire({
                    title: 'Pause Test?',
                    text: 'Are you sure you want to pause your test? Your current progress will be saved, and you can resume later.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff9f43',
                    cancelButtonColor: '#82868b',
                    confirmButtonText: 'Yes, pause it!',
                    cancelButtonText: 'No, keep going'
                }).then(function(result) {
                    if (result && (result.value || result.isConfirmed || result === true)) {
                        let f = document.getElementById('test-taking-form');
                        f.action = "{{ route('student.attempts.pause', $attempt->id) }}";
                        f.submit();
                    } else {
                        // Resume timer if cancelled
                        startActiveQuestionTimer();
                    }
                });
            } else {
                if (action === 'submit_auto') {
                    form.action = "{{ route('student.attempts.submit', $attempt->id) }}";
                    form.submit();
                } else {
                    Swal.fire({
                        title: 'Finish & Submit Test?',
                        text: 'Are you sure you want to finish and submit your test? You will not be able to change your answers once submitted.',
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28c76f',
                        cancelButtonColor: '#82868b',
                        confirmButtonText: 'Yes, submit it!',
                        cancelButtonText: 'No, keep working'
                    }).then(function(result) {
                        if (result && (result.value || result.isConfirmed || result === true)) {
                            let f = document.getElementById('test-taking-form');
                            f.action = "{{ route('student.attempts.submit', $attempt->id) }}";
                            f.submit();
                        } else {
                            // Resume timer if cancelled
                            startActiveQuestionTimer();
                        }
                    });
                }
            }
        }

        // AJAX background auto-saver
        function autoSaveAnswers() {
            let form = document.getElementById('test-taking-form');
            if (!form) return Promise.resolve(null);

            let formData = new FormData(form);
            
            return fetch("{{ route('student.attempts.save', $attempt->id) }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log("Answers auto-saved successfully in background.");
                return data;
            })
            .catch(error => {
                console.error("Auto-save error: ", error);
                throw error;
            });
        }

        // HTML5 Drag and Drop API handlers
        let activeDraggedElement = null;
        let activeSelectedCard = null;

        function handleDragStart(e) {
            @if($isLocked) e.preventDefault(); return; @endif
            activeDraggedElement = e.target;
            e.dataTransfer.setData("text/plain", e.target.getAttribute("data-value"));
            e.dataTransfer.effectAllowed = "move";
        }

        function allowDrop(e) {
            e.preventDefault();
        }

        function handleDrop(e) {
            e.preventDefault();
            @if($isLocked) return; @endif

            let dropzone = e.target.closest('.drop-zone');
            if (!dropzone) return;

            let questionId = dropzone.getAttribute('data-question-id');
            let pairIndex = dropzone.getAttribute('data-pair-index');

            if (!activeDraggedElement) return;
            if (activeDraggedElement.getAttribute('data-question-id') != questionId) return;

            performMatch(dropzone, activeDraggedElement, questionId, pairIndex);
            activeDraggedElement = null;
        }

        function handleReturnToPool(e) {
            e.preventDefault();
            @if($isLocked) return; @endif

            let pool = e.target.closest('.draggables-pool');
            if (!pool) return;

            let questionId = pool.getAttribute('data-question-id');

            if (!activeDraggedElement) return;
            if (activeDraggedElement.getAttribute('data-question-id') != questionId) return;

            returnCardToPool(pool, activeDraggedElement, questionId);
            activeDraggedElement = null;
        }

        // Click to Match implementation (highly accessible and mobile friendly)
        function handleCardClick(e, card) {
            e.stopPropagation();
            @if($isLocked) return; @endif

            let questionId = card.getAttribute('data-question-id');

            // Toggle select state
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
            @if($isLocked) return; @endif
            if (!activeSelectedCard) {
                // If they click a matched card inside the dropzone, we return it to the pool
                let matchedCard = dropzone.querySelector('.draggable-card');
                if (matchedCard) {
                    let questionId = dropzone.getAttribute('data-question-id');
                    let pool = document.querySelector(`.draggables-pool[data-question-id="${questionId}"]`);
                    if (pool) {
                        returnCardToPool(pool, matchedCard, questionId);
                    }
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
            @if($isLocked) return; @endif
            if (!activeSelectedCard) return;

            let questionId = pool.getAttribute('data-question-id');
            if (activeSelectedCard.getAttribute('data-question-id') != questionId) return;

            // Only return to pool if it's currently inside a dropzone
            if (activeSelectedCard.closest('.drop-zone')) {
                returnCardToPool(pool, activeSelectedCard, questionId);
                activeSelectedCard.classList.remove('selected-card');
                activeSelectedCard = null;
            }
        }

        function performMatch(dropzone, card, questionId, pairIndex) {
            let hiddenInput = document.getElementById(`matching-hidden-${questionId}-${pairIndex}`);
            if (!hiddenInput) return;

            // Check if dropzone already has a matched card
            let existingCard = dropzone.querySelector('.draggable-card');
            let pool = document.querySelector(`.draggables-pool[data-question-id="${questionId}"]`);

            if (existingCard) {
                if (existingCard === card) return; // dropped on itself
                // Return existing card to pool
                returnCardToPool(pool, existingCard, questionId);
            }

            // Remove placeholder if present
            let placeholder = dropzone.querySelector('.drag-placeholder');
            if (placeholder) placeholder.style.display = 'none';

            // Add classes for styling
            card.classList.add('matched-card');
            card.classList.remove('bg-white', 'text-primary', 'border', 'border-primary');
            card.classList.add('bg-primary', 'text-white');
            card.style.width = '100%';

            // Append card to dropzone
            dropzone.appendChild(card);

            // Update hidden input value
            hiddenInput.value = card.getAttribute('data-value');

            // Check input status for test validation
            let cardElement = document.querySelector(`.q-card[data-q-id="${questionId}"]`);
            if (cardElement) {
                let qNum = cardElement.getAttribute('data-q-num');
                checkTextInput(qNum, null);
            }
        }

        function returnCardToPool(pool, card, questionId) {
            // Find the dropzone it was in and clear its hidden input
            let dropzone = card.closest('.drop-zone');
            if (dropzone) {
                let pairIndex = dropzone.getAttribute('data-pair-index');
                let hiddenInput = document.getElementById(`matching-hidden-${questionId}-${pairIndex}`);
                if (hiddenInput) hiddenInput.value = '';

                // Restore placeholder text in the dropzone
                let placeholder = dropzone.querySelector('.drag-placeholder');
                if (placeholder) placeholder.style.display = 'block';
            }

            // Style back as pool item
            card.classList.remove('matched-card', 'bg-primary', 'text-white');
            card.classList.add('bg-white', 'text-primary', 'border', 'border-primary');
            card.style.width = 'auto';

            // Append to pool
            pool.appendChild(card);

            // Check input status for test validation
            let cardElement = document.querySelector(`.q-card[data-q-id="${questionId}"]`);
            if (cardElement) {
                let qNum = cardElement.getAttribute('data-q-num');
                checkTextInput(qNum, null);
            }
        }

        // ── Premium Image Lightbox / Zoom Logic ───────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            // Inject lightbox HTML
            const lightboxHtml = `
                <div id="premium-image-lightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 99999; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease; cursor: zoom-out;">
                    <span style="position: absolute; top: 20px; right: 25px; color: #f8fafc; font-size: 40px; font-weight: 200; cursor: pointer; transition: color 0.2s;" id="close-lightbox-btn">&times;</span>
                    <img id="lightbox-zoomed-img" src="" style="max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 8px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); transform: scale(0.9); transition: transform 0.3s ease;" alt="Zoomed Image">
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', lightboxHtml);

            // Inject CSS styles
            const style = document.createElement('style');
            style.innerHTML = `
                .img-zoomable:hover {
                    transform: scale(1.03);
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                }
                #close-lightbox-btn:hover {
                    color: #ef4444 !important;
                }
            `;
            document.head.appendChild(style);

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

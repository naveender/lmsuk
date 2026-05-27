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
            <div class="top-status-bar d-flex justify-content-between align-items-center px-4 py-2 border-bottom bg-white">
                <div class="d-flex align-items-center">
                    <span class="badge badge-pill badge-light-primary px-3 py-1 font-weight-bold text-uppercase mr-3 d-none d-md-inline" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        {{ $attempt->paper->subject->title }} &nbsp;•&nbsp; {{ $attempt->paper->topic->name }}
                    </span>
                    <h5 class="mb-0 font-weight-bold text-dark text-truncate" style="max-width: 220px; letter-spacing: -0.5px;">{{ $attempt->paper->title }}</h5>
                </div>
                
                <div class="d-flex align-items-center justify-content-end">
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
                                <span class="font-weight-bold" style="font-size: 0.9rem;">{{ $attempt->paper->questions->count() }}</span>
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
                                <span class="font-weight-bold" style="font-size: 0.9rem;" id="stat-unanswered">{{ $attempt->paper->questions->count() }}</span>
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
                        
                        @foreach($attempt->paper->questions as $index => $question)
                            @php
                                $ans = $answers->get($question->id);
                                $paperQuestion = DB::table('paper_question')
                                    ->where('paper_id', $attempt->paper_id)
                                    ->where('question_id', $question->id)
                                    ->first();
                                $marks = $paperQuestion->marks ?? $question->marks ?? $attempt->paper->default_marks ?? 1;
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
                                    {!! $question->description !!}
                                </div>

                                <!-- Image if present -->
                                @if($question->image)
                                    <div class="question-img-wrapper mb-4 text-center">
                                        <img src="{{ asset('storage/' . $question->image) }}" class="img-fluid rounded-lg border shadow-sm" style="max-height: 180px;" alt="Question Image">
                                    </div>
                                @endif

                                <hr class="my-4" style="border-top: 1px solid #edf2f7;">

                                <!-- Option/Answer choices box layout -->
                                <div class="answer-section">
                                    <h6 class="font-weight-bold text-muted text-uppercase mb-3" style="font-size: 0.7rem; letter-spacing: 1px;">Answer Options</h6>
                                    
                                    <!-- Choice questions grid (A, C, E vs B, D) -->
                                    @if(in_array($question->type, ['single_choice_radio', 'multiple_choice', 'picture_choice']))
                                        @php
                                            $options = $question->options->sortBy('sort_order')->values();
                                            $col1 = collect();
                                            $col2 = collect();
                                            foreach($options as $k => $opt) {
                                                if ($k % 2 == 0) {
                                                    $col1->push(['index' => $k, 'opt' => $opt]);
                                                } else {
                                                    $col2->push(['index' => $k, 'opt' => $opt]);
                                                }
                                            }
                                            $letterMap = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                                        @endphp

                                        <div class="row">
                                            <!-- Left Column (A, C, E) -->
                                            <div class="col-md-6 col-12">
                                                @foreach($col1 as $item)
                                                    @php
                                                        $letter = $letterMap[$item['index']] ?? 'A';
                                                        $option = $item['opt'];
                                                        $isSelected = false;
                                                        if ($question->type === 'multiple_choice') {
                                                            $isSelected = ($ans && is_array($ans->selected_options) && in_array($option->id, $ans->selected_options));
                                                        } else {
                                                            $isSelected = ($ans && $ans->selected_option_id == $option->id);
                                                        }
                                                    @endphp
                                                    <div class="mb-3">
                                                        <div class="option-card {{ $isSelected ? 'selected-option-card' : '' }}" 
                                                             id="option-container-{{ $option->id }}" 
                                                             onclick="selectOption({{ $question->id }}, {{ $option->id }}, '{{ $question->type }}')">
                                                            
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

                                            <!-- Right Column (B, D) -->
                                            <div class="col-md-6 col-12">
                                                @foreach($col2 as $item)
                                                    @php
                                                        $letter = $letterMap[$item['index']] ?? 'B';
                                                        $option = $item['opt'];
                                                        $isSelected = false;
                                                        if ($question->type === 'multiple_choice') {
                                                            $isSelected = ($ans && is_array($ans->selected_options) && in_array($option->id, $ans->selected_options));
                                                        } else {
                                                            $isSelected = ($ans && $ans->selected_option_id == $option->id);
                                                        }
                                                    @endphp
                                                    <div class="mb-3">
                                                        <div class="option-card {{ $isSelected ? 'selected-option-card' : '' }}" 
                                                             id="option-container-{{ $option->id }}" 
                                                             onclick="selectOption({{ $question->id }}, {{ $option->id }}, '{{ $question->type }}')">
                                                            
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
                                        </div>

                                    <!-- Single Choice Dropdown -->
                                    @elseif($question->type === 'single_choice_dropdown')
                                        <div class="form-group col-md-6 col-12 pl-0">
                                            <select name="answers[{{ $question->id }}][selected_option_id]" class="form-control test-input select-input" onchange="selectDropdownOption({{ $index + 1 }}, this)" style="border: 2px solid #7367f0; border-radius: 8px; height: 46px; font-weight: 500;">
                                                <option value="">-- Choose Option --</option>
                                                @foreach($question->options as $option)
                                                    <option value="{{ $option->id }}" {{ ($ans && $ans->selected_option_id == $option->id) ? 'selected' : '' }}>
                                                        {{ $option->option_text }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    <!-- Text / Blanks / Free text -->
                                    @else
                                        <div class="form-group mb-0">
                                            @if($question->type === 'free_text')
                                                <textarea name="answers[{{ $question->id }}][answer_text]" 
                                                          class="form-control test-input text-input" 
                                                          rows="5" 
                                                          placeholder="Type your explanation or essay answer here..."
                                                          oninput="checkTextInput({{ $index + 1 }}, this)"
                                                          style="border: 2px solid #7367f0; border-radius: 12px; padding: 12px; font-size: 0.95rem;"></textarea>
                                            @else
                                                <input type="text" 
                                                       name="answers[{{ $question->id }}][answer_text]" 
                                                       class="form-control test-input text-input" 
                                                       placeholder="Type your answer here..."
                                                       value="{{ $ans ? $ans->answer_text : '' }}"
                                                       oninput="checkTextInput({{ $index + 1 }}, this)"
                                                       style="border: 2px solid #7367f0; border-radius: 8px; height: 46px; font-size: 0.95rem; max-width: 450px;">
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endforeach

                    </div>

                    <!-- Right Panel: Question Index & Stopwatch Timers Table -->
                    <div class="col-md-3 col-12 bg-light d-flex flex-column justify-content-between p-0 border-left right-panel">
                        
                        <div style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                            <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center" style="height: 55px; min-height: 55px;">
                                <h6 class="font-weight-bold text-muted text-uppercase mb-0" style="font-size: 0.7rem; letter-spacing: 1px;">Questions Overview</h6>
                                <!-- Digital LED clock on top nearby the header -->
                                <div class="stopwatch-led-container py-1 px-2" style="border: 1px solid #334155; background-color: #0f172a; border-radius: 6px; box-shadow: inset 0 1px 3px rgba(0,0,0,0.5);">
                                    <h5 id="digital-clock" class="mb-0 font-weight-bold text-danger" style="font-family: 'Share Tech Mono', monospace; font-size: 0.95rem; text-shadow: 0 0 5px rgba(248, 113, 113, 0.7); letter-spacing: 1px;">00:00:00</h5>
                                </div>
                            </div>
                            <div class="table-responsive" style="flex: 1; overflow-y: auto;">
                                <table class="table table-hover table-sm text-center mb-0" style="border: 0;">
                                    <thead>
                                        <tr style="background-color: #f1f5f9; color: #475569;">
                                            <th style="width: 60px; padding: 8px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">Q</th>
                                            <th style="padding: 8px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; text-align: right; padding-right: 25px;">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attempt->paper->questions as $index => $question)
                                            <tr id="sidebar-row-{{ $index + 1 }}" class="clickable-row" onclick="jumpToQuestion({{ $index + 1 }})" style="cursor: pointer; transition: background-color 0.2s;">
                                                <td class="font-weight-bold p-2 position-relative text-dark" style="font-size: 0.9rem; border-top: 1px solid #f1f5f9;">
                                                    <span class="active-dot d-none bg-primary" style="position: absolute; left: 6px; top: 12px; width: 6px; height: 6px; border-radius: 50%;"></span>
                                                    <span class="q-num-label">{{ $index + 1 }}</span>
                                                    <span class="flag-icon d-none text-danger" style="position: absolute; right: 6px; top: 8px;"><i class="feather icon-flag font-small-3"></i></span>
                                                </td>
                                                <td id="sidebar-time-{{ $index + 1 }}" class="p-2 font-weight-bold text-danger text-monospace" style="font-size: 0.9rem; text-align: right; padding-right: 25px; border-top: 1px solid #f1f5f9;">
                                                    00:00
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                            <span class="mr-2 font-weight-bold text-secondary px-3 py-1.5 rounded-lg border bg-light d-none d-sm-inline" style="font-size: 0.72rem; background-color: #f8fafc; border-color: #e2e8f0; line-height: 1.5;">
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
        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: #f1f5f9 !important;
            overflow: hidden !important;
        }

        /* FORCE TRUE FULLSCREEN PLAYER VIEW OVERRIDING ALL THEME WRAPPERS & HORIZONTAL MENUS */
        .fullscreen-player-content {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            max-width: 100vw !important;
            max-height: 100vh !important;
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
            width: 100vw !important;
            max-width: 100vw !important;
            overflow: hidden !important;
            height: 100vh !important;
        }

        /* COMPACT INTERNAL SCROLLING LAYOUT */
        .player-frame {
            height: 100vh !important;
            max-height: 100vh !important;
            width: 100vw !important;
            max-width: 100vw !important;
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
            border-left: 1px solid #e2e8f0;
            background-color: #f8fafc;
            height: 100%;
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

            .right-panel {
                position: fixed;
                top: 0;
                right: -300px;
                width: 300px !important;
                max-width: 300px !important;
                flex: 0 0 300px !important;
                height: 100vh;
                z-index: 100000;
                transition: right 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
                border-left: none;
            }

            .right-panel.open {
                right: 0;
            }

            /* Option cards stack on mobile */
            .answer-section .row > [class*="col-"] {
                max-width: 100%;
                flex: 0 0 100%;
            }
        }

        @media (max-width: 991.98px) {
            .bottom-toolbar {
                padding: 8px 12px !important;
            }
            
            .bottom-toolbar .btn-text {
                display: none !important; /* Hide labels under buttons to save space on tablet/mobile */
            }
            
            .bottom-toolbar .btn {
                padding: 6px !important;
                margin-right: 6px !important;
                border-radius: 50% !important; /* Make buttons round icons */
                width: 34px !important;
                height: 34px !important;
                min-width: 34px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            .bottom-toolbar .btn i {
                margin-right: 0 !important;
                font-size: 0.9rem !important;
            }

            #skip-action-wrapper span, 
            #confidence-action-wrapper span {
                font-size: 0.65rem !important;
                padding: 3px 6px !important;
                margin-right: 6px !important;
            }
        }

        /* Ensure SweetAlert is on top of the fullscreen player wrapper */
        .swal2-container {
            z-index: 999999 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let remainingSeconds = @json($remainingSeconds); // overall test timer
        let isCountdown = remainingSeconds !== null;
        let elapsedSeconds = {{ max(0, $attempt->time_spent) + (int) $attempt->started_at->diffInSeconds(now()) }}; // countup backup timer if no remaining limit

        let currentQuestion = 1;
        let totalQuestions = @json($attempt->paper->questions->count());
        
        let questionTimes = {}; // maps question ID -> seconds spent
        let questionFlagged = {}; // maps question ID -> is flagged (0 or 1)
        let questionSkipped = {}; // maps question ID -> is skipped (true/false)

        // Initialize question times, flagged states from Database values
        @foreach($attempt->paper->questions as $question)
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
            // Show first question
            showQuestion(1);

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
            if (card) {
                let qId = card.getAttribute('data-q-id');
                let hasAnswer = checkQuestionAnswered(qId);
                if (!hasAnswer) {
                    questionSkipped[qId] = true;
                }
            }

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
            let selectVal = card.querySelector('select');
            let textVal = card.querySelector('input[type="text"], textarea');
            let checkboxChecked = card.querySelector('input[type="checkbox"]:checked');

            return !!(radioChecked || (selectVal && selectVal.value !== '') || (textVal && textVal.value.trim() !== '') || checkboxChecked);
        }

        // Toggle actions bar between Skip and Certainty options
        function toggleBottomToolbarActions(isAnswered) {
            let skipWrapper = document.getElementById('skip-action-wrapper');
            let confidenceWrapper = document.getElementById('confidence-action-wrapper');
            if (!skipWrapper || !confidenceWrapper) return;

            if (isAnswered) {
                skipWrapper.classList.remove('d-flex');
                skipWrapper.classList.add('d-none');
                confidenceWrapper.classList.remove('d-none');
                confidenceWrapper.classList.add('d-flex');
            } else {
                skipWrapper.classList.remove('d-none');
                skipWrapper.classList.add('d-flex');
                confidenceWrapper.classList.remove('d-flex');
                confidenceWrapper.classList.add('d-none');
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
        }

        // Handles confidence selection Guess, Fairly Sure, Sure and advances question
        // Also triggers background save of answers
        function selectConfidence(level) {
            let card = document.getElementById('q-card-' + currentQuestion);
            if (!card) return;

            let qId = card.getAttribute('data-q-id');
            document.getElementById('confidence-' + qId).value = level;

            // Trigger background save
            autoSaveAnswers();

            // Advance question
            if (currentQuestion < totalQuestions) {
                showQuestion(currentQuestion + 1);
            } else {
                showQuestion(1); // wrap
            }

            updateStatsBanner();
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
                if (element.value.trim() !== '') {
                    questionSkipped[qId] = false;
                    toggleBottomToolbarActions(true);
                } else {
                    document.getElementById('confidence-' + qId).value = '';
                    toggleBottomToolbarActions(false);
                }
            }
            updateCurrentInputLabel();
            updateStatsBanner();
        }

        function selectDropdownOption(qNum, element) {
            let card = document.getElementById('q-card-' + qNum);
            if (card) {
                let qId = card.getAttribute('data-q-id');
                if (element.value !== '') {
                    questionSkipped[qId] = false;
                    toggleBottomToolbarActions(true);
                } else {
                    document.getElementById('confidence-' + qId).value = '';
                    toggleBottomToolbarActions(false);
                }
            }
            updateCurrentInputLabel();
            updateStatsBanner();
        }

        // Live input counters
        function updateCurrentInputLabel() {
            let card = document.getElementById('q-card-' + currentQuestion);
            if (!card) return;

            let hasAnswer = checkQuestionAnswered(card.getAttribute('data-q-id'));
            document.getElementById('current-input-count').textContent = hasAnswer ? '1' : '0';
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
            if (!form) return;

            let formData = new FormData(form);
            
            fetch("{{ route('student.attempts.save', $attempt->id) }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log("Answers auto-saved successfully in background.");
            })
            .catch(error => {
                console.error("Auto-save error: ", error);
            });
        }
    </script>
@endpush

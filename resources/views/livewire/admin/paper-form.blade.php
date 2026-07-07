<div>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('theme/app-assets/vendors/css/forms/select/select2.min.css') }}">
        <style>
            /* Question bank scrolling card wrapper */
            .question-bank-card {
                max-height: 900px;
                overflow-y: auto;
            }

            /* High-end glassmorphism loader overlay */
            .glass-blur-loader {
                background: rgba(255, 255, 255, 0.75);
                backdrop-filter: blur(4px);
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 100;
                border-radius: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .border-left-3 {
                border-left-width: 3px !important;
            }
        </style>
    @endpush

    <div class="row">
        <!-- Left Side: Paper configuration & Selected Questions -->
        <div class="col-lg-7 col-md-12">
            <!-- Paper Info Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom py-2">
                    <h4 class="card-title font-weight-bold text-primary mb-0">
                        <i class="feather icon-edit mr-50"></i>{{ $isEdit ? 'Edit' : 'Create' }} Paper Configuration
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <!-- Type Selector (Radio Options) -->
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-dark mb-1 d-block">Paper Type <span
                                    class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" class="custom-control-input" id="type_test" value="test"
                                        wire:model.live="type">
                                    <label class="custom-control-label font-weight-bold cursor-pointer" for="type_test">
                                        <i class="feather icon-file-text text-warning mr-25"></i> Test
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="type_exam" value="exam"
                                        wire:model.live="type">
                                    <label class="custom-control-label font-weight-bold cursor-pointer" for="type_exam">
                                        <i class="feather icon-award text-success mr-25"></i> Exam
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="type_quiz" value="quiz"
                                        wire:model.live="type">
                                    <label class="custom-control-label font-weight-bold cursor-pointer" for="type_quiz">
                                        <i class="feather icon-award text-primary mr-25"></i> Quiz
                                    </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="type_homework" value="homework"
                                        wire:model.live="type">
                                    <label class="custom-control-label font-weight-bold cursor-pointer"
                                        for="type_homework">
                                        <i class="feather icon-award text-info mr-25"></i> Homework
                                    </label>
                                </div>
                            </div>
                            @error('type') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                        </div>

                        <!-- Title -->
                        <div class="form-group mb-2">
                            <label for="title" class="font-weight-bold text-dark">Paper Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="title" class="form-control" placeholder="Enter paper title..."
                                wire:model.defer="title">
                            @error('title') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                        </div>

                        <!-- Robust Alpine.js-wrapped Quill Rich Text Editor for Instruction -->
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-dark mb-50 d-block">Add Instruction / Description (Quill
                                Rich Text)</label>
                            <div wire:ignore x-data="{
                                content: @entangle('instruction'),
                                init() {
                                    const quill = new Quill($refs.editor, {
                                        modules: {
                                            toolbar: [
                                                [{ 'header': [1, 2, 3, false] }],
                                                ['bold', 'italic', 'underline', 'strike'],
                                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                ['clean']
                                            ]
                                        },
                                        placeholder: 'Type instructions or description here...',
                                        theme: 'snow'
                                    });
                                    
                                    // Load initial content securely
                                    if (this.content) {
                                        quill.root.innerHTML = this.content;
                                    }
                                    
                                    // Update editor from Livewire property updates (e.g. edit mode loads or resets)
                                    this.$watch('content', value => {
                                        if (value !== quill.root.innerHTML) {
                                            quill.root.innerHTML = value || '';
                                        }
                                    });
                                    
                                    // Sync typing updates to Livewire instantly
                                    quill.on('text-change', () => {
                                        let html = quill.root.innerHTML;
                                        if (html === '<p><br></p>') {
                                            html = '';
                                        }
                                        this.content = html;
                                    });
                                }
                            }">
                                <div x-ref="editor" class="border rounded bg-white" style="height: 180px;"></div>
                            </div>
                            @error('instruction') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                        </div>

                        <!-- Main Selection Grid -->
                        <div class="row">
                            <!-- Subject selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="subject_id" class="font-weight-bold text-dark">Subject <span
                                            class="text-danger">*</span></label>
                                    <div wire:ignore x-data="{
                                        value: @entangle('subject_id').live,
                                        init() {
                                            let select = $(this.$refs.select).select2({
                                                dropdownAutoWidth: true,
                                                width: '100%',
                                                placeholder: '-- Select Subject --',
                                                allowClear: true
                                            });
                                            select.val(this.value).trigger('change.select2');
                                            select.on('change', () => {
                                                this.value = select.val();
                                            });
                                            this.$watch('value', (val) => {
                                                select.val(val).trigger('change.select2');
                                            });
                                        }
                                    }">
                                        <select x-ref="select" id="subject_id" class="form-control select2">
                                            <option value="">-- Select Subject --</option>
                                            @foreach($subjects as $subj)
                                                <option value="{{ $subj->id }}">{{ $subj->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('subject_id') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Topic selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="topic_id" class="font-weight-bold text-dark">Topic</label>
                                    <div wire:ignore x-data="{
                                        value: @entangle('topic_id').live,
                                        options: @entangle('topics'),
                                        init() {
                                            let select = $(this.$refs.select).select2({
                                                dropdownAutoWidth: true,
                                                width: '100%',
                                                placeholder: '-- Select Topic --',
                                                allowClear: true
                                            });
                                            select.on('change', () => {
                                                this.value = select.val();
                                            });

                                            this.rebuildOptions(this.options);

                                            this.$watch('options', (newOptions) => {
                                                this.rebuildOptions(newOptions);
                                            });
                                            this.$watch('value', (val) => {
                                                select.val(val).trigger('change.select2');
                                            });
                                        },
                                        rebuildOptions(opts) {
                                            let select = $(this.$refs.select);
                                            select.html('<option value=\'\'>-- Select Topic --</option>');
                                            if (opts && Array.isArray(opts)) {
                                                opts.forEach(opt => {
                                                    let option = new Option(opt.name, opt.id, false, false);
                                                    select.append(option);
                                                });
                                            }
                                            select.val(this.value).trigger('change.select2');
                                        }
                                    }">
                                        <select x-ref="select" id="topic_id" class="form-control select2">
                                            <option value="">-- Select Topic --</option>
                                        </select>
                                    </div>
                                    @error('topic_id') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Subtopic selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="subtopic_id" class="font-weight-bold text-dark">Subtopic</label>
                                    <div wire:ignore x-data="{
                                        value: @entangle('subtopic_id').live,
                                        options: @entangle('subtopics'),
                                        init() {
                                            let select = $(this.$refs.select).select2({
                                                dropdownAutoWidth: true,
                                                width: '100%',
                                                placeholder: '-- Select Subtopic --',
                                                allowClear: true
                                            });
                                            select.on('change', () => {
                                                this.value = select.val();
                                            });

                                            this.rebuildOptions(this.options);

                                            this.$watch('options', (newOptions) => {
                                                this.rebuildOptions(newOptions);
                                            });
                                            this.$watch('value', (val) => {
                                                select.val(val).trigger('change.select2');
                                            });
                                        },
                                        rebuildOptions(opts) {
                                            let select = $(this.$refs.select);
                                            select.html('<option value=\'\'>-- Select Subtopic --</option>');
                                            if (opts && Array.isArray(opts)) {
                                                opts.forEach(opt => {
                                                    let option = new Option(opt.name, opt.id, false, false);
                                                    select.append(option);
                                                });
                                            }
                                            select.val(this.value).trigger('change.select2');
                                        }
                                    }">
                                        <select x-ref="select" id="subtopic_id" class="form-control select2">
                                            <option value="">-- Select Subtopic --</option>
                                        </select>
                                    </div>
                                    @error('subtopic_id') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Class, Year & Academic Year selection Grid -->
                        <div class="row">
                            <!-- Class selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="class_id" class="font-weight-bold text-dark">Class <span
                                            class="text-danger">*</span></label>
                                    <select id="class_id" class="form-control" wire:model.defer="class_id">
                                        <option value="">-- Select Class --</option>
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Group Year selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="year_group_id" class="font-weight-bold text-dark">Group Year <span
                                            class="text-danger">*</span></label>
                                    <select id="year_group_id" class="form-control" wire:model.defer="year_group_id">
                                        <option value="">-- Select Group Year --</option>
                                        @foreach($yearGroups as $yg)
                                            <option value="{{ $yg->id }}">{{ $yg->title }} ({{ $yg->value }})</option>
                                        @endforeach
                                    </select>
                                    @error('year_group_id') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Academic Year -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="academic_year" class="font-weight-bold text-dark">Academic Year <span
                                            class="text-danger">*</span></label>
                                    <select id="academic_year" class="form-control" wire:model.defer="academic_year">
                                        <option value="">-- Select Academic Year --</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year->name }}">{{ $year->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('academic_year') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Creator Dropdown -->
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="user_id" class="font-weight-bold text-dark">By Tutor / Admin <span
                                            class="text-danger">*</span></label>
                                    <select id="user_id" class="form-control" wire:model.defer="user_id">
                                        <option value="">-- Select Creator --</option>
                                        @foreach($tutors as $tutor)
                                            <option value="{{ $tutor->id }}">{{ $tutor->name }}
                                                ({{ ucfirst($tutor->role) }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Difficulty levels -->
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="difficulty" class="font-weight-bold text-dark">Difficulty Level <span
                                            class="text-danger">*</span></label>
                                    <select id="difficulty" class="form-control" wire:model.defer="difficulty">
                                        <option value="">-- Select Difficulty --</option>
                                        @foreach($difficulties as $key => $lbl)
                                            <option value="{{ $key }}">{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                    @error('difficulty') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Total time -->
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="total_time" class="font-weight-bold text-dark">Total Time (Minutes)
                                        <span class="text-danger">*</span></label>
                                    <input type="number" id="total_time" class="form-control" placeholder="e.g. 60"
                                        min="1" wire:model.defer="total_time">
                                    @error('total_time') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Default Marks -->
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="default_marks" class="font-weight-bold text-dark">Default Marks per
                                        Question <span class="text-danger">*</span></label>
                                    <input type="number" id="default_marks" class="form-control" placeholder="e.g. 1"
                                        min="1" wire:model.defer="default_marks">
                                    @error('default_marks') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <!-- Question Pooling Switch -->
                            <div class="col-md-12">
                                <div class="form-group mb-2">
                                    <label class="font-weight-bold text-dark d-block mb-25">Question Pooling</label>
                                    <div class="d-flex align-items-center mt-50">
                                        <div class="custom-control custom-switch custom-control-inline mr-1">
                                            <input type="checkbox" class="custom-control-input" id="question_pooling"
                                                wire:model.live="question_pooling">
                                            <label class="custom-control-label cursor-pointer"
                                                for="question_pooling"></label>
                                        </div>
                                        <span class="font-small-3 text-muted">
                                            Distribute questions across attempts. Users see different questions, no
                                            repeats.
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Course Assignment Section -->
                        <div class="row border-top pt-2 mt-1">
                            <div class="col-md-12">
                                <h5 class="font-weight-bold text-primary mb-1">
                                    <i class="feather icon-award mr-50"></i>Course Assignment (Optional)
                                </h5>
                            </div>

                            <div class="col-md-12 mb-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_new_course"
                                        wire:model.live="create_new_course">
                                    <label class="custom-control-label font-weight-bold cursor-pointer text-dark"
                                        for="create_new_course">
                                        Create a new course instantly
                                    </label>
                                </div>
                            </div>

                            @if($create_new_course)
                                <div class="col-md-8">
                                    <div class="form-group mb-2">
                                        <label for="new_course_name" class="font-weight-bold text-dark">New Course Name
                                            <span class="text-danger">*</span></label>
                                        <input type="text" id="new_course_name" class="form-control"
                                            placeholder="Enter new course name..." wire:model.defer="new_course_name">
                                        @error('new_course_name') <span
                                        class="text-danger font-small-3">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @else
                                <div class="col-md-8">
                                    <div class="form-group mb-2">
                                        <label for="course_id" class="font-weight-bold text-dark">Assign to Existing
                                            Course</label>
                                        <select id="course_id" class="form-control" wire:model.defer="course_id">
                                            <option value="">-- Do Not Assign --</option>
                                            @foreach($coursesList as $courseItem)
                                                <option value="{{ $courseItem->id }}">{{ $courseItem->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('course_id') <span class="text-danger font-small-3">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="week" class="font-weight-bold text-dark">Week Number <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="week" class="form-control" placeholder="e.g. 1" min="1"
                                        wire:model.defer="week">
                                    @error('week') <span class="text-danger font-small-3">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advance Configurations Fieldset -->
            <div class="card shadow-sm mt-2">
                <div class="card-header bg-white border-bottom py-2">
                    <h4 class="card-title font-weight-bold text-primary mb-0">
                        <i class="feather icon-settings mr-50"></i>Advance Configuration (Test/Exam)
                    </h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <fieldset class="border rounded p-2">
                            <legend class="w-auto px-1 font-weight-bold font-small-3 text-dark text-uppercase">Rules &
                                Options</legend>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="allow_attempt_without_signup"
                                            wire:model.defer="allow_attempt_without_signup">
                                        <label
                                            class="custom-control-label cursor-pointer text-dark font-small-3 font-weight-bold"
                                            for="allow_attempt_without_signup">
                                            Allow participant to attempt without signup
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="allow_reattempt_question" wire:model.defer="allow_reattempt_question">
                                        <label
                                            class="custom-control-label cursor-pointer text-dark font-small-3 font-weight-bold"
                                            for="allow_reattempt_question">
                                            Allow participant to reattempt the question
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-50">
                                <div class="col-md-6 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="display_result_question_by_question"
                                            wire:model.defer="display_result_question_by_question">
                                        <label
                                            class="custom-control-label cursor-pointer text-dark font-small-3 font-weight-bold"
                                            for="display_result_question_by_question">
                                            Display the Result Question By Question
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="allow_instant_feedback"
                                            wire:model.defer="allow_instant_feedback">
                                        <label
                                            class="custom-control-label cursor-pointer text-dark font-small-3 font-weight-bold"
                                            for="allow_instant_feedback">
                                            Allow participants to see instant feedback
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-50">
                                <div class="col-md-6 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="hide_result"
                                            wire:model.defer="hide_result">
                                        <label
                                            class="custom-control-label cursor-pointer text-dark font-small-3 font-weight-bold"
                                            for="hide_result">
                                            Hide result from participants
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="shuffle_questions"
                                            wire:model.defer="shuffle_questions">
                                        <label
                                            class="custom-control-label cursor-pointer text-dark font-small-3 font-weight-bold"
                                            for="shuffle_questions">
                                            Shuffle Questions
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>

            <!-- Selected Questions List -->
            <div class="card shadow-sm mt-2 border-left-3" style="border-left-color: #7367F0;">
                <div class="card-header bg-white border-bottom py-2 d-flex justify-content-between align-items-center">
                    <h4 class="card-title font-weight-bold text-primary mb-0">
                        <i class="feather icon-check-square mr-50"></i>Selected Questions
                        <span
                            class="badge badge-primary badge-pill ml-50 font-small-3">{{ count($selectedQuestionIds) }}</span>
                    </h4>
                    <span class="text-muted font-small-3">Total Marks: <strong
                            class="text-primary font-medium-1">{{ count($selectedQuestionIds) * ($default_marks ?: 1) }}</strong></span>
                </div>
                <div class="card-content">
                    <div class="card-body px-1 py-2">
                        @if($selectedQuestions->isEmpty())
                            <div class="text-center py-3">
                                <i class="feather icon-info text-muted font-large-2"></i>
                                <p class="text-muted mt-1 mb-0">No questions added yet. Use the selector panel on the right
                                    side to add questions easily.</p>
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($selectedQuestions as $index => $question)
                                    <div class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-1 mb-50 rounded shadow-sm border-left-3"
                                        style="border-left-color: #7367F0; transition: all 0.2s ease;"
                                        wire:key="selected-{{ $question->id }}">
                                        <!-- Question Details -->
                                        <div class="d-flex align-items-center flex-grow-1 mr-2" style="min-width: 0;">
                                            <span class="badge badge-primary badge-pill mr-1 font-weight-bold font-medium-1"
                                                style="min-width: 32px; text-align: center;">
                                                {{ $index + 1 }}
                                            </span>
                                            <div style="min-width: 0;">
                                                <h6 class="font-weight-bold mb-25 text-dark text-truncate"
                                                    title="{{ $question->title }}">
                                                    {{ $question->title }}
                                                </h6>
                                                <div class="d-flex flex-wrap align-items-center font-small-3 text-muted">
                                                    <span class="badge badge-light-primary py-25 px-50 mr-50">
                                                        {{ $questionTypes[$question->type] ?? $question->type }}
                                                    </span>
                                                    <span class="mr-1">
                                                        Subject: <strong>{{ $question->subject?->title ?: 'N/A' }}</strong>
                                                    </span>
                                                    <span class="mr-1">
                                                        Marks: <strong>{{ $default_marks ?: 1 }}</strong>
                                                    </span>
                                                    <span
                                                        class="badge badge-pill @if($question->difficulty === 'easy') badge-light-success @elseif($question->difficulty === 'medium') badge-light-warning @else badge-light-danger @endif">
                                                        {{ ucfirst($question->difficulty) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sorting & Removal controls -->
                                        <div class="d-flex align-items-center">
                                            <div class="btn-group btn-group-sm mr-75">
                                                <button type="button" class="btn btn-light rounded-circle p-50 mr-25"
                                                    style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                                    wire:click="moveUp({{ $index }})" @if($index === 0) disabled @endif
                                                    title="Move Up">
                                                    <i class="feather icon-arrow-up font-small-3 text-primary"></i>
                                                </button>
                                                <button type="button" class="btn btn-light rounded-circle p-50"
                                                    style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                                    wire:click="moveDown({{ $index }})" @if($index === count($selectedQuestionIds) - 1) disabled @endif title="Move Down">
                                                    <i class="feather icon-arrow-down font-small-3 text-primary"></i>
                                                </button>
                                            </div>
                                            <button type="button" class="btn btn-flat-danger btn-sm p-50"
                                                wire:click="removeQuestion({{ $question->id }})" title="Remove from Paper">
                                                <i class="feather icon-trash font-medium-2"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submit / Actions Card -->
            <div class="card shadow-sm mt-2">
                <div class="card-body d-flex justify-content-between align-items-center py-1">
                    <a href="{{ route('admin.papers.index') }}" class="btn btn-outline-warning">
                        <i class="feather icon-slash mr-25"></i> Cancel
                    </a>

                    <button type="button" class="btn btn-primary btn-md px-2" wire:click="save"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <i class="feather icon-save mr-25"></i> {{ $isEdit ? 'Update' : 'Save' }} Paper
                        </span>
                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm mr-25" role="status"
                                aria-hidden="true"></span> Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Side: Filter & Question Selector Bank -->
        <div class="col-lg-5 col-md-12 mt-lg-0 mt-2">
            <div class="card shadow-sm question-bank-card position-sticky border-left-3"
                style="top: 100px; border-left-color: #00CFE8;">
                <div class="card-header bg-white border-bottom py-2 d-flex justify-content-between align-items-center">
                    <h4 class="card-title font-weight-bold text-info mb-0">
                        <i class="feather icon-search mr-50"></i>Question Bank & Filters
                    </h4>

                    <!-- Realtime Connection Status -->
                    <span class="badge badge-pill badge-light-success font-small-2">
                        <span class="spinner-grow spinner-grow-sm mr-25" style="width: 8px; height: 8px;"
                            role="status"></span> Live Syncing
                    </span>
                </div>
                <div class="card-content">
                    <!-- Filters Body -->
                    <div class="card-body border-bottom bg-light px-2 py-2">
                        <div class="row">
                            <!-- Search -->
                            <div class="col-12 mb-1">
                                <div class="form-group mb-0 position-relative">
                                    <input type="text" class="form-control"
                                        placeholder="Type text to search instantly..."
                                        wire:model.live.debounce.300ms="search">
                                    <div class="form-control-position" style="top: 0; right: 0;">
                                        <i class="feather icon-search text-muted"></i>
                                    </div>
                                </div>
                                @if($search && strlen($search) < 3)
                                    <div class="alert alert-warning py-25 px-50 mt-50 mb-0 font-small-2 shadow-sm rounded">
                                        <i class="feather icon-alert-circle mr-25"></i> Type 3+ characters to search.
                                    </div>
                                @endif
                            </div>

                            <!-- Cascading Dropdowns -->
                            <!-- Subject -->
                            <div class="col-md-6 mb-1">
                                <div class="form-group mb-0">
                                    <label
                                        class="font-small-2 font-weight-bold text-muted text-uppercase">Subject</label>
                                    <select class="form-control form-control-sm border-info"
                                        wire:model.live="filterSubject">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects as $subj)
                                            <option value="{{ $subj->id }}">{{ $subj->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Topic (cascades) -->
                            <div class="col-md-6 mb-1">
                                <div class="form-group mb-0">
                                    <label class="font-small-2 font-weight-bold text-muted text-uppercase">Topic</label>
                                    <select
                                        class="form-control form-control-sm @if(!empty($filterTopics)) border-info @endif"
                                        wire:model.live="filterTopic" @if(empty($filterTopics)) disabled
                                        style="background-color: #e9ecef;" @endif>
                                        <option value="">All Topics</option>
                                        @foreach($filterTopics as $topic)
                                            <option value="{{ $topic['id'] }}">{{ $topic['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Subtopic (cascades) -->
                            <div class="col-md-6 mb-1">
                                <div class="form-group mb-0">
                                    <label
                                        class="font-small-2 font-weight-bold text-muted text-uppercase">Sub-Topic</label>
                                    <select
                                        class="form-control form-control-sm @if(!empty($filterSubtopics)) border-info @endif"
                                        wire:model.live="filterSubtopic" @if(empty($filterSubtopics)) disabled
                                        style="background-color: #e9ecef;" @endif>
                                        <option value="">All Sub-Topics</option>
                                        @foreach($filterSubtopics as $sub)
                                            <option value="{{ $sub['id'] }}">{{ $sub['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Difficulty -->
                            <div class="col-md-6 mb-1">
                                <div class="form-group mb-0">
                                    <label
                                        class="font-small-2 font-weight-bold text-muted text-uppercase">Difficulty</label>
                                    <select class="form-control form-control-sm" wire:model.live="filterDifficulty">
                                        <option value="">All Difficulties</option>
                                        @foreach($difficulties as $key => $lbl)
                                            <option value="{{ $key }}">{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Type -->
                            <div class="col-md-12 mb-1">
                                <div class="form-group mb-0">
                                    <label class="font-small-2 font-weight-bold text-muted text-uppercase">Question
                                        Type</label>
                                    <select class="form-control form-control-sm" wire:model.live="filterType">
                                        <option value="">All Types</option>
                                        @foreach($questionTypes as $key => $lbl)
                                            <option value="{{ $key }}">{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Reset/Clear Filters button -->
                            <div class="col-12 mt-50">
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-block font-weight-bold shadow-sm"
                                    wire:click="resetFilters" wire:loading.attr="disabled">
                                    <i class="feather icon-rotate-ccw mr-25"></i> Reset All Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Questions List Container (Real-time update panel) -->
                    <div class="card-body p-2 position-relative" style="min-height: 250px;">

                        <!-- Premium Loader Overlay -->
                        <div wire:loading
                            wire:target="filterSubject, filterTopic, filterSubtopic, filterDifficulty, filterType, search, resetFilters, loadMore"
                            class="glass-blur-loader">
                            <div class="text-center">
                                <div class="spinner-border text-info mb-1" style="width: 3rem; height: 3rem;"
                                    role="status"></div>
                                <h6 class="font-weight-bold text-info">Syncing Question Bank...</h6>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted font-small-3 font-weight-bold">
                                Displaying <strong class="text-info">{{ count($availableQuestions) }}</strong> matching
                                questions
                            </span>
                        </div>

                        @if($availableQuestions->isEmpty())
                            <div class="text-center py-4 border rounded bg-white">
                                <i class="feather icon-slash text-muted font-large-2 d-block mb-1"></i>
                                <h6 class="text-muted font-weight-bold mb-25">No questions matched filters</h6>
                                <p class="text-muted font-small-3 px-1 mb-0">Try typing a different keyword or resetting
                                    filters.</p>
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($availableQuestions as $q)
                                    @php
                                        $isAdded = in_array($q->id, $selectedQuestionIds);
                                    @endphp

                                    <!-- Question Item Row with Alpine Preview support -->
                                    <div x-data="{ open: false }"
                                        class="list-group-item list-group-item-action d-flex flex-column py-75 mb-50 rounded border border-light shadow-sm"
                                        style="transition: all 0.2s ease;" wire:key="bank-{{ $q->id }}">

                                        <!-- Question Title & Add Button -->
                                        <div class="d-flex align-items-start justify-content-between mb-50">
                                            <div class="mr-2" style="min-width: 0;">
                                                <h6 class="font-weight-bold mb-0 text-dark"
                                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
                                                    {{ $q->title }}
                                                </h6>
                                            </div>

                                            @if($isAdded)
                                                <button type="button"
                                                    class="btn btn-sm btn-light-success font-weight-bold px-75 py-25 text-nowrap"
                                                    wire:click="removeQuestion({{ $q->id }})">
                                                    <i class="feather icon-check-circle mr-25"></i> Added
                                                </button>
                                            @else
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-info font-weight-bold px-75 py-25 text-nowrap"
                                                    wire:click="addQuestion({{ $q->id }})">
                                                    <i class="feather icon-plus-circle mr-25"></i> Add
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Badges & Preview Toggle -->
                                        <div
                                            class="d-flex flex-wrap align-items-center justify-content-between font-small-2 text-muted mt-25">
                                            <div class="d-flex flex-wrap align-items-center">
                                                <span class="badge badge-pill badge-light-primary mr-50 px-50 py-25">
                                                    {{ $questionTypes[$q->type] ?? $q->type }}
                                                </span>
                                                <span class="mr-75 font-weight-bold">Marks:
                                                    <strong>{{ $q->marks }}</strong></span>
                                                <span class="mr-75 font-weight-bold">Subject:
                                                    <strong>{{ $q->subject?->title ?: 'N/A' }}</strong></span>
                                                <span
                                                    class="badge badge-pill @if($q->difficulty === 'easy') badge-light-success @elseif($q->difficulty === 'medium') badge-light-warning @else badge-light-danger @endif">
                                                    {{ ucfirst($q->difficulty) }}
                                                </span>
                                            </div>

                                            <!-- Preview Trigger Button -->
                                            <button type="button" @click="open = !open"
                                                class="btn p-0 text-info font-weight-bold font-small-2 border-0 bg-transparent mt-25"
                                                style="box-shadow: none;">
                                                <span x-show="!open"><i class="feather icon-eye mr-25"></i> Preview</span>
                                                <span x-show="open"><i class="feather icon-eye-off mr-25"></i> Hide</span>
                                            </button>
                                        </div>

                                        <!-- Alpine Collapsible Preview Pane -->
                                        <div x-show="open" x-collapse
                                            class="mt-75 p-1 text-white border rounded bg-primary font-small-3 shadow-inner"
                                            style="display: none;">
                                            @if($q->description)
                                                <div class="mb-50 font-weight-bold text-white border-bottom pb-25">Description:
                                                </div>
                                                <div class="mb-75 text-secondary pl-50 border-left-3"
                                                    style="border-left-color: #d1d1d1;">
                                                    {!! nl2br(e($q->description)) !!}
                                                </div>
                                            @endif

                                            @if($q->options->isNotEmpty())
                                                <div class="font-weight-bold text-white mb-50 border-bottom pb-25">Answer Options:
                                                </div>
                                                <ul class="list-unstyled mb-0 pl-50">
                                                    @foreach($q->options as $optIdx => $opt)
                                                        @php
                                                            $letter = chr(65 + $optIdx); // A, B, C, D...
                                                        @endphp
                                                        <li
                                                            class="d-flex align-items-center mb-25 py-25 px-50 rounded {{ $opt->is_correct ? 'bg-light-success font-weight-bold text-success' : 'text-secondary' }}">
                                                            <span
                                                                class="badge {{ $opt->is_correct ? 'badge-success' : 'badge-light' }} badge-pill mr-50 font-small-3"
                                                                style="min-width: 20px; text-align: center;">
                                                                {{ $letter }}
                                                            </span>
                                                            <span>{{ $opt->option_text }}</span>
                                                            @if($opt->is_correct)
                                                                <i class="feather icon-check ml-auto font-medium-1"></i>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            @if($q->metadata)
                                                @if(isset($q->metadata['matching_pairs']))
                                                    <div class="font-weight-bold text-muted mb-50 border-bottom pb-25">Matching Pairs:
                                                    </div>
                                                    <ul class="list-unstyled mb-0 pl-50">
                                                        @foreach($q->metadata['matching_pairs'] as $pair)
                                                            <li class="d-flex align-items-center mb-25 py-25 text-secondary">
                                                                <span class="badge badge-light-primary mr-50">{{ $pair['left'] }}</span>
                                                                <i class="feather icon-arrow-right mr-50 font-small-2 text-muted"></i>
                                                                <span class="badge badge-light-success">{{ $pair['right'] }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                                @if(isset($q->metadata['blank_answers']))
                                                    <div class="font-weight-bold text-muted mb-50 border-bottom pb-25">Blank Answers:
                                                    </div>
                                                    <div class="d-flex flex-wrap pl-50">
                                                        @foreach($q->metadata['blank_answers'] as $blankIdx => $ans)
                                                            <span class="badge badge-light-success mr-50 mb-25 font-small-2">
                                                                Blank {{ $blankIdx + 1 }}: <strong>{{ $ans }}</strong>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @endif

                                            @if($q->explanation || ($q->explanation_images && count($q->explanation_images) > 0))
                                                <div class="mt-75 pt-50 border-top">
                                                    <span class="font-weight-bold text-white d-block mb-25"><i
                                                            class="feather icon-info mr-25"></i> Explanation:</span>
                                                    @if($q->explanation)
                                                        <div class="text-white font-small-3 font-italic mb-50">{!! $q->explanation !!}
                                                        </div>
                                                    @endif
                                                    @if($q->explanation_images && count($q->explanation_images) > 0)
                                                        <div class="explanation-images-wrapper mt-2 d-flex flex-wrap align-items-center"
                                                            style="gap: 8px;">
                                                            @foreach($q->explanation_images as $expImg)
                                                                <a href="{{ asset('storage/' . $expImg) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $expImg) }}"
                                                                        class="img-fluid rounded border bg-white"
                                                                        style="max-height: 80px; object-fit: contain; cursor: pointer;"
                                                                        alt="Explanation Image">
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Optimized Infinite Scrolling "Load More" Pagination -->
                            @if($availableQuestions->count() >= $perPage)
                                <div class="text-center mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-info font-weight-bold shadow-sm"
                                        wire:click="loadMore" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="loadMore"><i
                                                class="feather icon-chevron-down mr-25"></i> Load More Questions</span>
                                        <span wire:loading wire:target="loadMore"><span
                                                class="spinner-border spinner-border-sm mr-25" role="status"></span>
                                            Loading...</span>
                                    </button>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('theme/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    @endpush
</div>
<div>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css"
            href="{{ asset('theme/app-assets/vendors/css/forms/select/select2.min.css') }}">
        <style>
            .file-upload-dropzone {
                border: 2px dashed #7367F0;
                border-radius: 12px;
                padding: 30px 20px;
                text-align: center;
                background: #f8fafc;
                cursor: pointer;
                transition: all 0.25s ease;
                position: relative;
            }
            .file-upload-dropzone:hover {
                background: #f1f5f9;
                border-color: #5e50ee;
                transform: translateY(-2px);
            }
            .file-preview-card {
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                padding: 14px 18px;
                background: #ffffff;
                box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            }
            .format-badge {
                font-size: 0.75rem;
                padding: 4px 10px;
                border-radius: 6px;
                font-weight: 600;
            }
            .badge-docx { background: #e0f2fe; color: #0369a1; }
            .badge-pdf { background: #fee2e2; color: #b91c1c; }
            .badge-txt { background: #f1f5f9; color: #475569; }
        </style>
    @endpush

    <form wire:submit.prevent="save">
        <div class="row">
            <!-- Left Side: Main Homework Details & File Upload -->
            <div class="col-lg-8 col-md-12">
                <!-- Homework Core Details Card -->
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white border-bottom py-2">
                        <h4 class="card-title font-weight-bold text-primary mb-0">
                            <i class="feather icon-book mr-50"></i>{{ $isEdit ? 'Edit' : 'Create' }} Homework Details
                        </h4>
                    </div>
                    <div class="card-body pt-2">
                        <!-- Homework Title -->
                        <div class="form-group mb-2">
                            <label for="title" class="font-weight-bold text-dark">Homework Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" class="form-control form-control-lg" placeholder="e.g. Week 3 - Algebra Fundamentals Practice"
                                wire:model.defer="title">
                            @error('title') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description (Quill Rich Text) -->
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-dark mb-50 d-block">Description & Instructions</label>
                            <div wire:ignore x-data="{
                                content: @entangle('description'),
                                init() {
                                    const initQuill = () => {
                                        if (typeof Quill === 'undefined') {
                                            setTimeout(initQuill, 40);
                                            return;
                                        }
                                        const quill = new Quill($refs.editor, {
                                            modules: {
                                                toolbar: [
                                                    [{ 'header': [1, 2, 3, false] }],
                                                    ['bold', 'italic', 'underline', 'strike'],
                                                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                    ['clean']
                                                ]
                                            },
                                            placeholder: 'Type detailed homework instructions, tasks, or guidelines for students...',
                                            theme: 'snow'
                                        });
                                        if (this.content) {
                                            quill.root.innerHTML = this.content;
                                        }
                                        this.$watch('content', value => {
                                            if (value !== quill.root.innerHTML) {
                                                quill.root.innerHTML = value || '';
                                            }
                                        });
                                        quill.on('text-change', () => {
                                            let html = quill.root.innerHTML;
                                            if (html === '<p><br></p>') {
                                                html = '';
                                            }
                                            this.content = html;
                                        });
                                    };
                                    initQuill();
                                }
                            }">
                                <div x-ref="editor" class="border rounded bg-white" style="height: 180px;"></div>
                            </div>
                            @error('description') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                        </div>

                        <!-- Academic & Class Targeting Grid -->
                        <div class="row">
                            <!-- Subject selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="subject_id" class="font-weight-bold text-dark">Subject <span class="text-danger">*</span></label>
                                    <div wire:ignore x-data="{
                                        value: @entangle('subject_id').live,
                                        init() {
                                            const initSelect = () => {
                                                if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
                                                    setTimeout(initSelect, 40);
                                                    return;
                                                }
                                                let select = $(this.$refs.select).select2({
                                                    dropdownAutoWidth: true,
                                                    width: '100%',
                                                    placeholder: '-- Select Subject --',
                                                    allowClear: true
                                                });
                                                select.val(this.value || '').trigger('change.select2');
                                                select.on('change', () => {
                                                    let val = select.val();
                                                    this.value = val;
                                                    $wire.set('subject_id', val);
                                                });
                                                this.$watch('value', (val) => {
                                                    select.val(val || '').trigger('change.select2');
                                                });
                                            };
                                            initSelect();
                                        }
                                    }">
                                        <select x-ref="select" id="subject_id" class="form-control select2">
                                            <option value="">-- Select Subject --</option>
                                            @foreach($subjects as $subj)
                                                <option value="{{ $subj->id }}">{{ $subj->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('subject_id') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
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
                                            const initSelect = () => {
                                                if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
                                                    setTimeout(initSelect, 40);
                                                    return;
                                                }
                                                let select = $(this.$refs.select).select2({
                                                    dropdownAutoWidth: true,
                                                    width: '100%',
                                                    placeholder: '-- Select Topic --',
                                                    allowClear: true
                                                });
                                                select.on('change', () => {
                                                    let val = select.val();
                                                    this.value = val;
                                                    $wire.set('topic_id', val);
                                                });
                                                this.rebuildOptions(this.options);
                                                this.$watch('options', (newOptions) => {
                                                    this.rebuildOptions(newOptions);
                                                });
                                                this.$watch('value', (val) => {
                                                    select.val(val || '').trigger('change.select2');
                                                });
                                            };
                                            initSelect();
                                        },
                                        rebuildOptions(opts) {
                                            let select = $(this.$refs.select);
                                            select.empty().append(new Option('-- Select Topic --', ''));
                                            const list = Array.isArray(opts) ? opts : Object.values(opts || {});
                                            list.forEach(opt => {
                                                let option = new Option(opt.name, opt.id, false, opt.id == this.value);
                                                select.append(option);
                                            });
                                            select.val(this.value || '').trigger('change.select2');
                                        }
                                    }">
                                        <select x-ref="select" id="topic_id" class="form-control select2">
                                            <option value="">-- Select Topic --</option>
                                        </select>
                                    </div>
                                    @error('topic_id') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
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
                                            const initSelect = () => {
                                                if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
                                                    setTimeout(initSelect, 40);
                                                    return;
                                                }
                                                let select = $(this.$refs.select).select2({
                                                    dropdownAutoWidth: true,
                                                    width: '100%',
                                                    placeholder: '-- Select Subtopic --',
                                                    allowClear: true
                                                });
                                                select.on('change', () => {
                                                    let val = select.val();
                                                    this.value = val;
                                                    $wire.set('subtopic_id', val);
                                                });
                                                this.rebuildOptions(this.options);
                                                this.$watch('options', (newOptions) => {
                                                    this.rebuildOptions(newOptions);
                                                });
                                                this.$watch('value', (val) => {
                                                    select.val(val || '').trigger('change.select2');
                                                });
                                            };
                                            initSelect();
                                        },
                                        rebuildOptions(opts) {
                                            let select = $(this.$refs.select);
                                            select.empty().append(new Option('-- Select Subtopic --', ''));
                                            const list = Array.isArray(opts) ? opts : Object.values(opts || {});
                                            list.forEach(opt => {
                                                let option = new Option(opt.name, opt.id, false, opt.id == this.value);
                                                select.append(option);
                                            });
                                            select.val(this.value || '').trigger('change.select2');
                                        }
                                    }">
                                        <select x-ref="select" id="subtopic_id" class="form-control select2">
                                            <option value="">-- Select Subtopic --</option>
                                        </select>
                                    </div>
                                    @error('subtopic_id') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Class, Year Group & Academic Year -->
                        <div class="row">
                            <!-- Class selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="class_id" class="font-weight-bold text-dark">Class <span class="text-danger">*</span></label>
                                    <select id="class_id" class="form-control" wire:model.defer="class_id">
                                        <option value="">-- Select Class --</option>
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Year Group selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="year_group_id" class="font-weight-bold text-dark">Group Year <span class="text-danger">*</span></label>
                                    <select id="year_group_id" class="form-control" wire:model.defer="year_group_id">
                                        <option value="">-- Select Group Year --</option>
                                        @foreach($yearGroups as $yg)
                                            <option value="{{ $yg->id }}">{{ $yg->title }} ({{ $yg->value }})</option>
                                        @endforeach
                                    </select>
                                    @error('year_group_id') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Academic Year selection -->
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label for="academic_year" class="font-weight-bold text-dark">Academic Year <span class="text-danger">*</span></label>
                                    <select id="academic_year" class="form-control" wire:model.defer="academic_year">
                                        <option value="">-- Select Academic Year --</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year->name }}">{{ $year->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('academic_year') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Creator -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="user_id" class="font-weight-bold text-dark">Assigned Tutor / Admin <span class="text-danger">*</span></label>
                                    <select id="user_id" class="form-control" wire:model.defer="user_id">
                                        <option value="">-- Select Creator --</option>
                                        @foreach($tutors as $tutor)
                                            <option value="{{ $tutor->id }}">{{ $tutor->name }} ({{ ucfirst($tutor->role) }})</option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Assignment Section (Exact match to PaperForm) -->
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white border-bottom py-2">
                        <h4 class="card-title font-weight-bold text-primary mb-0">
                            <i class="feather icon-award mr-50"></i>Course & Week Assignment (Optional)
                        </h4>
                    </div>
                    <div class="card-body pt-2">
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="create_new_course"
                                        wire:model.live="create_new_course">
                                    <label class="custom-control-label font-weight-bold cursor-pointer text-dark" for="create_new_course">
                                        Create a new course instantly
                                    </label>
                                </div>
                            </div>

                            @if($create_new_course)
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="new_course_name" class="font-weight-bold text-dark">New Course Name <span class="text-danger">*</span></label>
                                        <input type="text" id="new_course_name" class="form-control"
                                            placeholder="Enter new course name..." wire:model.defer="new_course_name">
                                        @error('new_course_name') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="course_id" class="font-weight-bold text-dark">Assign to Existing Course</label>
                                        <select id="course_id" class="form-control" wire:model.live="course_id">
                                            <option value="">-- Do Not Assign --</option>
                                            @foreach($coursesList as $courseItem)
                                                <option value="{{ $courseItem->id }}">{{ $courseItem->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('course_id') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            @if($create_new_course || $course_id)
                                <div class="col-md-12">
                                    <div class="card bg-light border p-2 mb-0">
                                        <h6 class="font-weight-bold mb-1 text-dark">Week Settings</h6>
                                        
                                        @if(!$create_new_course)
                                            <div class="form-group mb-2">
                                                <label class="font-weight-bold text-dark d-block">Week Selection Mode</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input type="radio" class="custom-control-input" id="week_mode_existing" value="existing"
                                                            wire:model.live="week_mode">
                                                        <label class="custom-control-label font-weight-bold cursor-pointer" for="week_mode_existing">
                                                            Choose Existing Week
                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="week_mode_new" value="new"
                                                            wire:model.live="week_mode">
                                                        <label class="custom-control-label font-weight-bold cursor-pointer" for="week_mode_new">
                                                            Create New Week
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($week_mode === 'existing' && !$create_new_course)
                                            <div class="form-group mb-0">
                                                <label for="selected_week_id" class="font-weight-bold text-dark">Select Week <span class="text-danger">*</span></label>
                                                <select id="selected_week_id" class="form-control" wire:model.defer="selected_week_id">
                                                    <option value="">-- Choose Week --</option>
                                                    @foreach($courseWeeks as $cWeek)
                                                        <option value="{{ $cWeek['id'] }}">
                                                            {{ $cWeek['name'] }} @if($cWeek['due_date']) (Due: {{ \Carbon\Carbon::parse($cWeek['due_date'])->format('d M Y') }}) @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('selected_week_id') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group mb-2">
                                                        <label for="new_week_name" class="font-weight-bold text-dark">Week Name / Title <span class="text-danger">*</span></label>
                                                        <input type="text" id="new_week_name" class="form-control" placeholder="e.g. Week 1 - Intro to Geometry"
                                                            wire:model.defer="new_week_name">
                                                        @error('new_week_name') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mb-2">
                                                        <label for="new_week_due_date" class="font-weight-bold text-dark">Due Date</label>
                                                        <input type="date" id="new_week_due_date" class="form-control" wire:model.defer="new_week_due_date">
                                                        @error('new_week_due_date') <span class="text-danger font-small-3">{{ $message }}</span> @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: File Upload & Action Summary Card -->
            <div class="col-lg-4 col-md-12">
                <!-- File Upload Card -->
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white border-bottom py-2">
                        <h4 class="card-title font-weight-bold text-primary mb-0">
                            <i class="feather icon-upload-cloud mr-50"></i>Homework File
                        </h4>
                    </div>
                    <div class="card-body pt-2">
                        <p class="text-muted font-small-3 mb-1">
                            Upload your homework document for students. Supported formats:
                        </p>
                        <div class="d-flex align-items-center mb-2">
                            <span class="format-badge badge-docx mr-1"><i class="feather icon-file-text mr-25"></i>DOCX / DOC</span>
                            <span class="format-badge badge-pdf mr-1"><i class="feather icon-file mr-25"></i>PDF</span>
                            <span class="format-badge badge-txt"><i class="feather icon-align-left mr-25"></i>TXT</span>
                        </div>

                        <!-- Dropzone input container -->
                        <div class="file-upload-dropzone" onclick="document.getElementById('homeworkFileInput').click()">
                            <input type="file" id="homeworkFileInput" class="d-none" wire:model="file" accept=".docx,.doc,.pdf,.txt">
                            <i class="feather icon-upload-cloud text-primary" style="font-size: 38px;"></i>
                            <h6 class="font-weight-bold mt-1 mb-25 text-dark">Click to select or drop file here</h6>
                            <span class="text-muted font-small-2">Maximum file size: 25 MB</span>
                        </div>

                        <!-- Upload Loading indicator -->
                        <div wire:loading wire:target="file" class="mt-2 text-center text-primary">
                            <div class="spinner-border spinner-border-sm mr-50" role="status"></div>
                            <span class="font-small-3 font-weight-bold">Uploading file, please wait...</span>
                        </div>

                        <!-- Current / Newly Selected File Preview -->
                        @if($file)
                            <div class="file-preview-card mt-2 border-primary bg-light">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <div class="mr-1 text-primary" style="font-size: 26px;">
                                            <i class="feather icon-file-text"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <h6 class="mb-0 text-truncate font-weight-bold text-dark" style="max-width: 170px;" title="{{ $file->getClientOriginalName() }}">
                                                {{ $file->getClientOriginalName() }}
                                            </h6>
                                            <span class="badge badge-primary font-small-1 text-uppercase">New Upload</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm p-50" wire:click="$set('file', null)" title="Remove selected file">
                                        <i class="feather icon-x"></i>
                                    </button>
                                </div>
                            </div>
                        @elseif($existing_file_name)
                            <div class="file-preview-card mt-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <div class="mr-1 text-info" style="font-size: 26px;">
                                            <i class="feather icon-file-text"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <h6 class="mb-0 text-truncate font-weight-bold text-dark" style="max-width: 170px;" title="{{ $existing_file_name }}">
                                                {{ $existing_file_name }}
                                            </h6>
                                            <span class="text-muted font-small-2">{{ $existing_file_size }} • {{ strtoupper($existing_file_type) }}</span>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        @if($homeworkId)
                                            <a href="{{ route('admin.homeworks.download', $homeworkId) }}" class="btn btn-outline-primary btn-sm p-50 mr-50" title="Download current file">
                                                <i class="feather icon-download"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-outline-danger btn-sm p-50" wire:click="removeExistingFile" title="Remove attached file">
                                            <i class="feather icon-trash-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @error('file')
                            <div class="text-danger font-small-3 mt-1">
                                <i class="feather icon-alert-circle mr-25"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Status & Save Actions Card -->
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white border-bottom py-1">
                        <h5 class="card-title font-weight-bold text-dark mb-0 font-small-3">
                            <i class="feather icon-settings mr-50 text-primary"></i>Publication & Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-dark d-block mb-50">Homework Status</label>
                            <div class="custom-control custom-switch custom-control-inline">
                                <input type="checkbox" class="custom-control-input" id="is_active" wire:model.live="is_active" value="1">
                                <label class="custom-control-label font-weight-bold cursor-pointer {{ $is_active ? 'text-success' : 'text-danger' }}" for="is_active">
                                    {{ $is_active ? 'Active' : 'Inactive' }}
                                </label>
                            </div>
                            <small class="form-text text-muted mt-50">
                                @if($is_active)
                                    <span class="text-success"><i class="feather icon-check-circle mr-25"></i>Active: Visible on student side to matching Class, Group Year & Academic Year.</span>
                                @else
                                    <span class="text-danger"><i class="feather icon-eye-off mr-25"></i>Inactive: Hidden from student side.</span>
                                @endif
                            </small>
                        </div>

                        <hr class="my-1">

                        <button type="submit" class="btn btn-primary btn-block btn-lg font-weight-bold shadow mb-1">
                            <span wire:loading.remove wire:target="save">
                                <i class="feather icon-check mr-50"></i>{{ $isEdit ? 'Update Homework' : 'Create Homework' }}
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm mr-50" role="status"></span>Saving...
                            </span>
                        </button>

                        <a href="{{ route('admin.homeworks.index') }}" class="btn btn-outline-secondary btn-block">
                            <i class="feather icon-x mr-50"></i>Cancel & Back to Directory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="{{ asset('theme/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    @endpush
</div>

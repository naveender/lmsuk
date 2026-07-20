@extends('layouts.app')
@section('title', 'Add New Media File')

@push('styles')
<style>
    .upload-box {
        border: 2px dashed #7367f0;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        background: rgba(115, 103, 240, 0.05);
        cursor: pointer;
        transition: background 0.3s;
    }
    .upload-box:hover {
        background: rgba(115, 103, 240, 0.1);
    }
    .progress-wrapper {
        background: #f3f2fe;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
        display: none;
    }
    .thumbnail-preview-box {
        position: relative;
        width: 150px;
        height: 100px;
        border: 1px solid #ddd;
        border-radius: 6px;
        background-color: #fafafa;
        background-size: cover;
        background-position: center;
    }
    /* Segmented Control & Premium UX for Scheduling */
    .scheduling-mode-selector {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    .scheduling-mode-card {
        flex: 1;
        border: 2px solid #ebe9f1;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
    }
    .scheduling-mode-card:hover {
        border-color: #7367f0;
        background-color: rgba(115, 103, 240, 0.02);
    }
    .scheduling-mode-card.active {
        border-color: #7367f0;
        background-color: rgba(115, 103, 240, 0.06);
        box-shadow: 0 4px 12px rgba(115, 103, 240, 0.15);
    }
    .scheduling-mode-card i {
        font-size: 1.8rem;
        margin-bottom: 8px;
        color: #6e6b7b;
        display: block;
    }
    .scheduling-mode-card.active i {
        color: #7367f0;
    }
    .scheduling-mode-card .mode-title {
        font-weight: 700;
        color: #5e5873;
    }
    .scheduling-mode-card .mode-desc {
        font-size: 0.8rem;
        color: #b9b9c3;
        margin-top: 4px;
    }
    .scheduling-card-body {
        border: 1px solid rgba(115, 103, 240, 0.15);
        border-radius: 8px;
        background-color: #fff;
        padding: 20px;
        margin-top: 15px;
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0 font-weight-bold">Add New Video/File</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.media-files.index') }}">Manage Files</a></li>
                                <li class="breadcrumb-item active">Add New</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="row">
                <div class="col-md-8 col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Integration & Upload Source</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <!-- Source Type Selector -->
                                <div class="form-group">
                                    <label class="font-weight-bold" for="media_type_select">Choose Video/File Type</label>
                                    <select class="form-control" id="media_type_select">
                                        <option value="">-- Select Source Type --</option>
                                        <option value="youtube">YouTube Video</option>
                                        <option value="vimeo">Vimeo Video</option>
                                        <option value="video_file">Local Video File (Direct Upload)</option>
                                        <option value="video_url">Video Direct Link (.mp4)</option>
                                        <option value="s3">Amazon S3 Storage Video</option>
                                        <option value="wasabi">Wasabi Cloud Storage Video</option>
                                        <option value="google_drive">Google Drive Video</option>
                                        <option value="iframe">Iframe Embed Code</option>
                                    </select>
                                </div>

                                <hr>

                                <!-- Shared Fields & Form details -->
                                <div id="formDetailsSection" style="display: none;">
                                    <h5 class="text-primary font-weight-bold mb-1"><i class="feather icon-edit-3"></i> General Details</h5>
                                    
                                    <div class="form-group">
                                        <label for="media_title" class="font-weight-bold">Title <span class="text-danger">*</span></label>
                                        <input type="text" id="media_title" class="form-control" placeholder="Enter video title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="media_description" class="font-weight-bold">Description</label>
                                        <textarea id="media_description" class="form-control" rows="3" placeholder="Enter video description..."></textarea>
                                    </div>

                                    <!-- Metadata Row 1 -->
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_subject_id" class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                                                <select id="media_subject_id" class="form-control" required>
                                                    <option value="">-- Select Subject --</option>
                                                    @foreach($subjects as $subj)
                                                        <option value="{{ $subj->id }}">{{ $subj->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_class_id" class="font-weight-bold">Class <span class="text-danger">*</span></label>
                                                <select id="media_class_id" class="form-control" required>
                                                    <option value="">-- Select Class --</option>
                                                    @foreach($classes as $cls)
                                                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Metadata Row 2 -->
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_year_group_id" class="font-weight-bold">Group Year <span class="text-danger">*</span></label>
                                                <select id="media_year_group_id" class="form-control" required>
                                                    <option value="">-- Select Group Year --</option>
                                                    @foreach($yearGroups as $yg)
                                                        <option value="{{ $yg->id }}">{{ $yg->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_academic_year" class="font-weight-bold">Academic Year <span class="text-danger">*</span></label>
                                                <select id="media_academic_year" class="form-control" required>
                                                    <option value="">-- Select Academic Year --</option>
                                                    @foreach($academicYears as $ay)
                                                        <option value="{{ $ay->name }}">{{ $ay->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Metadata Row 3 -->
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_duration" class="font-weight-bold">Duration</label>
                                                <input type="text" id="media_duration" class="form-control" placeholder="e.g. 15:30">
                                                <small class="text-muted">Format: MM:SS or HH:MM:SS</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_publication_status" class="font-weight-bold">Publication Status</label>
                                                <select id="media_publication_status" class="form-control">
                                                    <option value="published">Published</option>
                                                    <option value="draft">Draft</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thumbnail Upload -->
                                    <div class="form-group">
                                        <label class="font-weight-bold">Thumbnail Image</label>
                                        <div class="d-flex align-items-center">
                                            <div class="thumbnail-preview-box mr-2 d-flex align-items-center justify-content-center" id="thumbnailPreview" style="background-image: url('{{ asset('theme/app-assets/images/pages/graphic-2.png') }}')">
                                                <div class="spinner-border text-primary" role="status" id="thumbnailSpinner" style="display: none;">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="file" id="thumbnailFileInput" class="form-control-file mb-50" accept="image/*">
                                                <small class="text-muted">JPG, PNG, WEBP (Max 2MB). Ideal size: 16:9 ratio.</small>
                                                <input type="hidden" id="shared_thumbnail_path" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Course & Week Assignment Section -->
                                    <div class="card bg-light border-0 shadow-none mb-2">
                                        <div class="card-body">
                                            <h5 class="text-primary font-weight-bold mb-1"><i class="feather icon-award"></i> Course & Weekly Scheduling</h5>
                                            <p class="text-muted small">Optionally assign this video resource directly to a course and schedule it for a specific week.</p>
                                            
                                            <!-- Step 1: Assignment Card Selector -->
                                            <div class="scheduling-mode-selector">
                                                <div class="scheduling-mode-card active" id="mode_card_unassigned">
                                                    <i class="feather icon-slash"></i>
                                                    <div class="mode-title">General Video File</div>
                                                    <div class="mode-desc">Unassigned Repository Asset</div>
                                                </div>
                                                <div class="scheduling-mode-card" id="mode_card_assigned">
                                                    <i class="feather icon-award"></i>
                                                    <div class="mode-title">Schedule to Course</div>
                                                    <div class="mode-desc">Link to Week Timeline</div>
                                                </div>
                                            </div>

                                            <!-- Hidden checkbox managed programmatically -->
                                            <input type="checkbox" id="assign_to_course_switch" class="custom-control-input" style="display: none;">

                                            <div id="courseAssignmentSection" style="display: none;" class="scheduling-card-body">
                                                <!-- Step 2: Choose Course Mode -->
                                                <label class="font-weight-bold text-dark">Course Selection Mode</label>
                                                <div class="d-flex mb-2" style="gap: 12px;">
                                                    <button type="button" class="btn btn-outline-primary btn-sm flex-fill font-weight-bold active" id="btn_select_existing_course">
                                                        <i class="feather icon-list mr-25"></i> Select Existing Course
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary btn-sm flex-fill font-weight-bold" id="btn_create_new_course">
                                                        <i class="feather icon-plus mr-25"></i> Create New Course
                                                    </button>
                                                </div>

                                                <!-- Hidden uploader switcher -->
                                                <input type="checkbox" id="create_new_course_switch" class="custom-control-input" style="display: none;">

                                                <!-- Select Existing Course Input Group -->
                                                <div class="form-group" id="group_existing_course">
                                                    <label for="assignment_course_id" class="font-weight-bold">Select Target Course <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="feather icon-book"></i></span>
                                                        </div>
                                                        <select id="assignment_course_id" class="form-control">
                                                            <option value="">-- Select Course --</option>
                                                            @foreach($courses as $course)
                                                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Create New Course Input Group -->
                                                <div class="form-group" id="group_new_course" style="display: none;">
                                                    <label for="assignment_new_course_name" class="font-weight-bold">New Course Name <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="feather icon-edit-1"></i></span>
                                                        </div>
                                                        <input type="text" id="assignment_new_course_name" class="form-control" placeholder="e.g. Year 5 Mathematics Advanced">
                                                    </div>
                                                </div>

                                                <hr class="my-2">

                                                <!-- Step 3: Choose Week Mode -->
                                                <label class="font-weight-bold text-dark">Week Assignment Mode</label>
                                                <div class="d-flex mb-2" style="gap: 12px;">
                                                    <button type="button" class="btn btn-outline-primary btn-sm flex-fill font-weight-bold active" id="btn_select_existing_week">
                                                        <i class="feather icon-calendar mr-25"></i> Assign to Existing Week
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary btn-sm flex-fill font-weight-bold" id="btn_create_new_week">
                                                        <i class="feather icon-plus-circle mr-25"></i> Schedule a New Week
                                                    </button>
                                                </div>

                                                <!-- Hidden dropdown selector managed programmatically -->
                                                <select id="assignment_week_mode" class="form-control" style="display: none;">
                                                    <option value="existing" selected>existing</option>
                                                    <option value="new">new</option>
                                                </select>

                                                <!-- Select Existing Week Input Group -->
                                                <div class="form-group" id="group_existing_week">
                                                    <label for="assignment_selected_week_id" class="font-weight-bold">Select Scheduled Week <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="feather icon-clock"></i></span>
                                                        </div>
                                                        <select id="assignment_selected_week_id" class="form-control">
                                                            <option value="">-- Select Week --</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Create New Week Input Group -->
                                                <div id="group_new_week" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="assignment_new_week_name" class="font-weight-bold">New Week Title <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="feather icon-folder"></i></span>
                                                            </div>
                                                            <input type="text" id="assignment_new_week_name" class="form-control" placeholder="e.g. Week 15 - Geometry Concepts">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="assignment_new_week_due_date" class="font-weight-bold">Assignment Due Date (Optional)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                                                            </div>
                                                            <input type="date" id="assignment_new_week_due_date" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Link Form Fields (Youtube, Vimeo, Drive, Iframe, Url) -->
                                    <div id="linkFieldsSection" style="display: none;">
                                        <form action="{{ route('admin.media-files.store') }}" method="POST" id="linkForm">
                                            @csrf
                                            <!-- Hidden values populated by JS -->
                                            <input type="hidden" name="title" id="linkFormTitle">
                                            <input type="hidden" name="description" id="linkFormDesc">
                                            <input type="hidden" name="type" id="linkFormType">
                                            <input type="hidden" name="subject_id" id="linkFormSubj">
                                            <input type="hidden" name="class_id" id="linkFormClass">
                                            <input type="hidden" name="year_group_id" id="linkFormYG">
                                            <input type="hidden" name="academic_year" id="linkFormAY">
                                            <input type="hidden" name="duration" id="linkFormDur">
                                            <input type="hidden" name="thumbnail_path" id="linkFormThumb">
                                            <input type="hidden" name="publication_status" id="linkFormPubStatus">

                                            <!-- Hidden Course values -->
                                            <input type="hidden" name="course_id" id="linkFormCourseId">
                                            <input type="hidden" name="week_mode" id="linkFormWeekMode">
                                            <input type="hidden" name="selected_week_id" id="linkFormSelWeekId">
                                            <input type="hidden" name="new_week_name" id="linkFormNewWeekName">
                                            <input type="hidden" name="new_week_due_date" id="linkFormNewWeekDueDate">
                                            <input type="hidden" name="create_new_course" id="linkFormCreateNewCourse">
                                            <input type="hidden" name="new_course_name" id="linkFormNewCourseName">
                                            
                                            <div class="form-group">
                                                <label for="media_path" class="font-weight-bold" id="pathLabel">Video Source path</label>
                                                <input type="text" name="path" id="media_path" class="form-control" placeholder="" required>
                                                <small class="text-muted" id="pathHelpText"></small>
                                            </div>

                                            <div class="d-flex justify-content-end pt-1">
                                                <button type="submit" class="btn btn-primary font-weight-bold">
                                                    <i class="feather icon-link-2 mr-25"></i> Link Media Asset
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- File Upload Form Fields (Local, S3, Wasabi) -->
                                    <div id="uploadFieldsSection" style="display: none;">
                                        <div class="form-group">
                                            <label class="font-weight-bold" id="storageLabel">Select Storage Location</label>
                                            <select class="form-control" id="upload_storage_disk">
                                                <option value="local">Local Storage Disk</option>
                                                <option value="wasabi">Wasabi Cloud storage</option>
                                                <option value="s3">Amazon AWS S3 bucket</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="font-weight-bold">Select File (Max {{ $maxUploadSize }} MB)</label>
                                            <div class="upload-box" id="uploadDropZone">
                                                <i class="feather icon-upload-cloud text-primary" style="font-size: 3rem;"></i>
                                                <h5 class="mt-1 font-weight-bold">Click to browse or drop file here</h5>
                                                <p class="text-muted">Supports MP4, WebM, AVI, MOV up to 1GB</p>
                                                <input type="file" id="videoFileInput" style="display: none;" accept="video/*">
                                            </div>
                                        </div>

                                        <!-- Progress Details Widget -->
                                        <div class="progress-wrapper" id="uploaderProgressWidget">
                                            <div class="d-flex justify-content-between mb-50">
                                                <span class="font-weight-bold text-primary" id="lblProgressFilename">Uploading Video...</span>
                                                <span class="font-weight-bold text-primary" id="lblProgressPercentage">0%</span>
                                            </div>
                                            <div class="progress progress-bar-primary mb-1">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBarValue" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between text-muted mb-1 small">
                                                <span id="lblProgressSize">0 MB / 0 MB</span>
                                                <span id="lblProgressSpeed">0 KB/s</span>
                                                <span id="lblProgressEta">ETA: --</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="badge badge-light-warning" id="lblProgressStatus">Uploading Chunks...</span>
                                                </div>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-warning" id="btnPauseUpload"><i class="feather icon-pause"></i> Pause</button>
                                                    <button class="btn btn-success" id="btnResumeUpload" style="display: none;"><i class="feather icon-play"></i> Resume</button>
                                                    <button class="btn btn-info" id="btnBackgroundUpload"><i class="feather icon-external-link"></i> Background (Popup)</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Storage Information</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <h6><b>File Size limit</b></h6>
                                <p>This server is configured to accept large files in chunks of 5 MB. Files up to 1 GB are fully supported without PHP server timeout errors.</p>
                                
                                <h6><b>Supported Integration Notes</b></h6>
                                <ul class="pl-1">
                                    <li><b>YouTube:</b> Paste complete URL or 11-char ID.</li>
                                    <li><b>Vimeo:</b> Paste Vimeo video URL or ID.</li>
                                    <li><b>Direct link:</b> Enter the raw link ending with <code>.mp4</code>.</li>
                                    <li><b>Wasabi/S3:</b> Ensure bucket credentials are saved in System Configs page prior to file uploads.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('media_type_select');
        const formDetailsSection = document.getElementById('formDetailsSection');
        const linkFieldsSection = document.getElementById('linkFieldsSection');
        const uploadFieldsSection = document.getElementById('uploadFieldsSection');
        const pathLabel = document.getElementById('pathLabel');
        const pathHelpText = document.getElementById('pathHelpText');
        const mediaPath = document.getElementById('media_path');
        const storageLabel = document.getElementById('storageLabel');
        
        // Course Assignment bindings
        const assignSwitch = document.getElementById('assign_to_course_switch');
        const courseAssignmentSection = document.getElementById('courseAssignmentSection');
        const newCourseSwitch = document.getElementById('create_new_course_switch');
        const groupExistingCourse = document.getElementById('group_existing_course');
        const groupNewCourse = document.getElementById('group_new_course');
        const weekModeSelect = document.getElementById('assignment_week_mode');
        const groupExistingWeek = document.getElementById('group_existing_week');
        const groupNewWeek = document.getElementById('group_new_week');
        const courseSelect = document.getElementById('assignment_course_id');
        const weekSelect = document.getElementById('assignment_selected_week_id');

        // Course Weeks data map for offline filter cascade
        const courseWeeksMap = @json($courses->mapWithKeys(fn($c) => [$c->id => $c->weeks]));

        // Course Assignment Toggle
        assignSwitch.addEventListener('change', function() {
            courseAssignmentSection.style.display = this.checked ? 'block' : 'none';
        });

        // New Course Toggle
        newCourseSwitch.addEventListener('change', function() {
            if(this.checked) {
                groupExistingCourse.style.display = 'none';
                groupNewCourse.style.display = 'block';
                weekModeSelect.value = 'new';
                weekModeSelect.disabled = true;
                groupExistingWeek.style.display = 'none';
                groupNewWeek.style.display = 'block';
            } else {
                groupExistingCourse.style.display = 'block';
                groupNewCourse.style.display = 'none';
                weekModeSelect.disabled = false;
                weekModeSelect.value = 'existing';
                groupExistingWeek.style.display = 'block';
                groupNewWeek.style.display = 'none';
            }
        });

        // Week Mode Selection
        weekModeSelect.addEventListener('change', function() {
            if(this.value === 'existing') {
                groupExistingWeek.style.display = 'block';
                groupNewWeek.style.display = 'none';
            } else {
                groupExistingWeek.style.display = 'none';
                groupNewWeek.style.display = 'block';
            }
        });

        // Interactive Premium UI Event Listeners
        const modeCardUnassigned = document.getElementById('mode_card_unassigned');
        const modeCardAssigned = document.getElementById('mode_card_assigned');
        
        modeCardUnassigned.addEventListener('click', function() {
            modeCardUnassigned.classList.add('active');
            modeCardAssigned.classList.remove('active');
            assignSwitch.checked = false;
            assignSwitch.dispatchEvent(new Event('change'));
        });

        modeCardAssigned.addEventListener('click', function() {
            modeCardAssigned.classList.add('active');
            modeCardUnassigned.classList.remove('active');
            assignSwitch.checked = true;
            assignSwitch.dispatchEvent(new Event('change'));
        });

        const btnSelectExistingCourse = document.getElementById('btn_select_existing_course');
        const btnCreateNewCourse = document.getElementById('btn_create_new_course');

        btnSelectExistingCourse.addEventListener('click', function() {
            btnSelectExistingCourse.classList.add('active');
            btnCreateNewCourse.classList.remove('active');
            newCourseSwitch.checked = false;
            newCourseSwitch.dispatchEvent(new Event('change'));
        });

        btnCreateNewCourse.addEventListener('click', function() {
            btnCreateNewCourse.classList.add('active');
            btnSelectExistingCourse.classList.remove('active');
            newCourseSwitch.checked = true;
            newCourseSwitch.dispatchEvent(new Event('change'));
        });

        const btnSelectExistingWeek = document.getElementById('btn_select_existing_week');
        const btnCreateNewWeek = document.getElementById('btn_create_new_week');

        btnSelectExistingWeek.addEventListener('click', function() {
            btnSelectExistingWeek.classList.add('active');
            btnCreateNewWeek.classList.remove('active');
            weekModeSelect.value = 'existing';
            weekModeSelect.dispatchEvent(new Event('change'));
        });

        btnCreateNewWeek.addEventListener('click', function() {
            btnCreateNewWeek.classList.add('active');
            btnSelectExistingWeek.classList.remove('active');
            weekModeSelect.value = 'new';
            weekModeSelect.dispatchEvent(new Event('change'));
        });

        // Cascading weeks list on Course Select
        courseSelect.addEventListener('change', function() {
            const courseId = this.value;
            weekSelect.innerHTML = '<option value="">-- Select Week --</option>';
            
            if(courseId && courseWeeksMap[courseId]) {
                const weeks = courseWeeksMap[courseId];
                if (weeks.length === 0) {
                    weekModeSelect.value = 'new';
                    btnCreateNewWeek.classList.add('active');
                    btnSelectExistingWeek.classList.remove('active');
                    weekModeSelect.dispatchEvent(new Event('change'));
                } else {
                    weeks.forEach(w => {
                        const opt = document.createElement('option');
                        opt.value = w.id;
                        opt.textContent = w.name;
                        weekSelect.appendChild(opt);
                    });
                }
            }
        });

        // Live Thumbnail Upload via AJAX
        const thumbInput = document.getElementById('thumbnailFileInput');
        const thumbPreview = document.getElementById('thumbnailPreview');
        const thumbSpinner = document.getElementById('thumbnailSpinner');
        const thumbPathHidden = document.getElementById('shared_thumbnail_path');

        thumbInput.addEventListener('change', function() {
            if(this.files.length === 0) return;
            
            const formData = new FormData();
            formData.append('thumbnail', this.files[0]);

            thumbSpinner.style.display = 'block';

            axios.post('{{ route("admin.media-files.upload-thumbnail") }}', formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => {
                thumbSpinner.style.display = 'none';
                if(res.data.success) {
                    thumbPreview.style.backgroundImage = `url('${res.data.url}')`;
                    thumbPathHidden.value = res.data.thumbnail_path;
                    Swal.fire('Thumbnail Uploaded', 'Custom thumbnail uploaded successfully!', 'success');
                }
            })
            .catch(err => {
                thumbSpinner.style.display = 'none';
                Swal.fire('Error', 'Failed to upload thumbnail image: ' + err.message, 'error');
            });
        });
        
        // Chunk Upload Variables
        let selectedFile = null;
        let uploaderInstance = null; // tracking active uploader loop
        const CHUNK_SIZE = 1 * 1024 * 1024; // 1MB chunks (compatible with standard PHP 2MB upload limits)

        typeSelect.addEventListener('change', function() {
            const val = this.value;
            if(!val) {
                formDetailsSection.style.display = 'none';
                return;
            }

            formDetailsSection.style.display = 'block';

            if(['youtube', 'vimeo', 'video_url', 'google_drive', 'iframe'].includes(val)) {
                linkFieldsSection.style.display = 'block';
                uploadFieldsSection.style.display = 'none';

                // Customize label and inputs
                if(val === 'youtube') {
                    pathLabel.textContent = 'YouTube Video URL or ID';
                    pathHelpText.textContent = 'Example: https://www.youtube.com/watch?v=dQw4w9WgXcQ or dQw4w9WgXcQ';
                    mediaPath.placeholder = 'https://www.youtube.com/watch?v=...';
                } else if(val === 'vimeo') {
                    pathLabel.textContent = 'Vimeo Video URL or ID';
                    pathHelpText.textContent = 'Example: https://vimeo.com/76979871 or 76979871';
                    mediaPath.placeholder = 'https://vimeo.com/...';
                } else if(val === 'video_url') {
                    pathLabel.textContent = 'Direct Video File Link (.mp4)';
                    pathHelpText.textContent = 'Example: https://yourdomain.com/videos/lesson1.mp4';
                    mediaPath.placeholder = 'https://domain.com/path/to/video.mp4';
                } else if(val === 'google_drive') {
                    pathLabel.textContent = 'Google Drive Embed or Shared Link';
                    pathHelpText.textContent = 'Paste Google Drive shared link or preview iframe code.';
                    mediaPath.placeholder = 'https://drive.google.com/file/d/...';
                } else if(val === 'iframe') {
                    pathLabel.textContent = 'Raw Iframe HTML Code';
                    pathHelpText.textContent = 'Paste the complete <iframe>...</iframe> embed block.';
                    mediaPath.placeholder = '<iframe src="..."></iframe>';
                }
            } else {
                linkFieldsSection.style.display = 'none';
                uploadFieldsSection.style.display = 'block';
                
                // Adjust default storage selector target based on choice
                const storageDiskSelect = document.getElementById('upload_storage_disk');
                if(val === 'video_file') {
                    storageDiskSelect.value = 'local';
                } else if(val === 's3') {
                    storageDiskSelect.value = 's3';
                } else if(val === 'wasabi') {
                    storageDiskSelect.value = 'wasabi';
                }
            }
        });

        // Helper function to validate shared metadata fields
        function validateMetadata() {
            if(!document.getElementById('media_title').value) {
                Swal.fire('Required Field', 'Please enter a video Title.', 'error');
                return false;
            }
            if(!document.getElementById('media_subject_id').value) {
                Swal.fire('Required Field', 'Please select a Subject.', 'error');
                return false;
            }
            if(!document.getElementById('media_class_id').value) {
                Swal.fire('Required Field', 'Please select a Class.', 'error');
                return false;
            }
            if(!document.getElementById('media_year_group_id').value) {
                Swal.fire('Required Field', 'Please select a Year Group.', 'error');
                return false;
            }
            if(!document.getElementById('media_academic_year').value) {
                Swal.fire('Required Field', 'Please select an Academic Year.', 'error');
                return false;
            }

            // Validate Course Assignment fields if toggled
            if(assignSwitch.checked) {
                if(newCourseSwitch.checked) {
                    if(!document.getElementById('assignment_new_course_name').value) {
                        Swal.fire('Required Field', 'Please enter the New Course Name.', 'error');
                        return false;
                    }
                } else {
                    if(!document.getElementById('assignment_course_id').value) {
                        Swal.fire('Required Field', 'Please select a Course.', 'error');
                        return false;
                    }
                }

                if(weekModeSelect.value === 'existing') {
                    if(!document.getElementById('assignment_selected_week_id').value) {
                        Swal.fire('Required Field', 'Please select an existing Week.', 'error');
                        return false;
                    }
                } else {
                    if(!document.getElementById('assignment_new_week_name').value) {
                        Swal.fire('Required Field', 'Please enter the New Week Name.', 'error');
                        return false;
                    }
                }
            }

            return true;
        }

        // Intercept link forms and copy shared fields
        document.getElementById('linkForm').addEventListener('submit', function(e) {
            if(!validateMetadata()) {
                e.preventDefault();
                return;
            }

            // Copy metadata values into hidden inputs
            document.getElementById('linkFormTitle').value = document.getElementById('media_title').value;
            document.getElementById('linkFormDesc').value = document.getElementById('media_description').value;
            document.getElementById('linkFormType').value = typeSelect.value;
            document.getElementById('linkFormSubj').value = document.getElementById('media_subject_id').value;
            document.getElementById('linkFormClass').value = document.getElementById('media_class_id').value;
            document.getElementById('linkFormYG').value = document.getElementById('media_year_group_id').value;
            document.getElementById('linkFormAY').value = document.getElementById('media_academic_year').value;
            document.getElementById('linkFormDur').value = document.getElementById('media_duration').value;
            document.getElementById('linkFormThumb').value = thumbPathHidden.value;
            document.getElementById('linkFormPubStatus').value = document.getElementById('media_publication_status').value;

            // Copy Course details if assigned
            if (assignSwitch.checked) {
                document.getElementById('linkFormCourseId').value = document.getElementById('assignment_course_id').value;
                document.getElementById('linkFormWeekMode').value = weekModeSelect.value;
                document.getElementById('linkFormSelWeekId').value = document.getElementById('assignment_selected_week_id').value;
                document.getElementById('linkFormNewWeekName').value = document.getElementById('assignment_new_week_name').value;
                document.getElementById('linkFormNewWeekDueDate').value = document.getElementById('assignment_new_week_due_date').value;
                document.getElementById('linkFormCreateNewCourse').value = newCourseSwitch.checked ? '1' : '0';
                document.getElementById('linkFormNewCourseName').value = document.getElementById('assignment_new_course_name').value;
            }
        });

        // Dropzone interactions
        const dropZone = document.getElementById('uploadDropZone');
        const fileInput = document.getElementById('videoFileInput');

        dropZone.addEventListener('click', () => {
            if (validateMetadata()) {
                fileInput.click();
            }
        });
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#28c76f';
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#7367f0';
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#7367f0';
            if(!validateMetadata()) return;
            
            if(e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect(fileInput.files[0]);
            }
        });
        fileInput.addEventListener('change', (e) => {
            if(e.target.files.length) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            selectedFile = file;
            document.getElementById('lblProgressFilename').textContent = file.name;
            document.getElementById('lblProgressSize').textContent = `0 MB / ${roundSize(file.size)}`;
            document.getElementById('uploaderProgressWidget').style.display = 'block';
            
            // Auto start upload
            startChunkedUpload();
        }

        function roundSize(bytes) {
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        // Custom Chunk Uploader Object
        function startChunkedUpload() {
            if(!validateMetadata()) {
                document.getElementById('uploaderProgressWidget').style.display = 'none';
                return;
            }

            const title = document.getElementById('media_title').value;
            const targetDisk = document.getElementById('upload_storage_disk').value;
            const description = document.getElementById('media_description').value;

            // Generate unique upload ID based on file metadata
            const fileSignature = `${selectedFile.name}_${selectedFile.size}`;
            const uploadId = 'up_' + btoa(unescape(encodeURIComponent(fileSignature))).replace(/=/g, '').substr(0, 20);

            // UI switches
            document.getElementById('btnPauseUpload').style.display = 'inline-block';
            document.getElementById('btnResumeUpload').style.display = 'none';
            document.getElementById('lblProgressStatus').textContent = 'Uploading Chunks...';
            document.getElementById('lblProgressStatus').className = 'badge badge-light-warning';

            uploaderInstance = {
                file: selectedFile,
                uploadId: uploadId,
                title: title,
                description: description,
                targetDisk: targetDisk,
                totalChunks: Math.ceil(selectedFile.size / CHUNK_SIZE),
                isPaused: false,
                startTime: Date.now(),
                uploadedBytes: 0,
                
                // Track completed chunks list
                completedChunks: []
            };

            // 1. Ask server which chunks are already completed (resumability check!)
            axios.get(`/admin/media-files/upload-status?upload_id=${uploadId}`)
                .then(response => {
                    uploaderInstance.completedChunks = response.data.completed_chunks || [];
                    uploadNextChunk(0);
                })
                .catch(err => {
                    console.error('Failed to get status', err);
                    uploadNextChunk(0);
                });
        }

        function uploadNextChunk(index) {
            if(!uploaderInstance || uploaderInstance.isPaused) return;

            if(index >= uploaderInstance.totalChunks) {
                return;
            }

            // If this chunk was already uploaded, skip it!
            if(uploaderInstance.completedChunks.includes(index)) {
                const completedBytes = Math.min((index + 1) * CHUNK_SIZE, uploaderInstance.file.size);
                updateProgressUI(completedBytes);
                uploadNextChunk(index + 1);
                return;
            }

            const start = index * CHUNK_SIZE;
            const end = Math.min(start + CHUNK_SIZE, uploaderInstance.file.size);
            const chunk = uploaderInstance.file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('chunk_index', index);
            formData.append('total_chunks', uploaderInstance.totalChunks);
            formData.append('filename', uploaderInstance.file.name);
            formData.append('upload_id', uploaderInstance.uploadId);
            formData.append('storage_target', uploaderInstance.targetDisk);
            formData.append('title', uploaderInstance.title);
            formData.append('description', uploaderInstance.description);
            
            // Metadata parameters
            formData.append('subject_id', document.getElementById('media_subject_id').value);
            formData.append('class_id', document.getElementById('media_class_id').value);
            formData.append('year_group_id', document.getElementById('media_year_group_id').value);
            formData.append('academic_year', document.getElementById('media_academic_year').value);
            formData.append('duration', document.getElementById('media_duration').value);
            formData.append('thumbnail_path', thumbPathHidden.value);
            formData.append('publication_status', document.getElementById('media_publication_status').value);

            // Course assignment parameters
            if (assignSwitch.checked) {
                formData.append('course_id', document.getElementById('assignment_course_id').value);
                formData.append('week_mode', weekModeSelect.value);
                formData.append('selected_week_id', document.getElementById('assignment_selected_week_id').value);
                formData.append('new_week_name', document.getElementById('assignment_new_week_name').value);
                formData.append('new_week_due_date', document.getElementById('assignment_new_week_due_date').value);
                formData.append('create_new_course', newCourseSwitch.checked ? '1' : '0');
                formData.append('new_course_name', document.getElementById('assignment_new_course_name').value);
            }

            const chunkStartTime = Date.now();

            axios.post('{{ route("admin.media-files.upload-chunk") }}', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                onUploadProgress: (progressEvent) => {
                    const loadedInChunk = progressEvent.loaded;
                    const totalLoadedBytes = start + loadedInChunk;
                    updateProgressUI(totalLoadedBytes);
                }
            })
            .then(response => {
                if(response.data.status === 'completed') {
                    // Success completed!
                    document.getElementById('lblProgressStatus').textContent = 'Completed!';
                    document.getElementById('lblProgressStatus').className = 'badge badge-light-success';
                    document.getElementById('progressBarValue').style.width = '100%';
                    document.getElementById('lblProgressPercentage').textContent = '100%';
                    
                    Swal.fire({
                        title: 'Success',
                        text: 'File uploaded and processed successfully!',
                        type: 'success'
                    }).then(() => {
                        window.location.href = '{{ route("admin.media-files.index") }}';
                    });
                } else {
                    uploaderInstance.completedChunks.push(index);
                    uploadNextChunk(index + 1);
                }
            })
            .catch(error => {
                console.error(error);
                document.getElementById('lblProgressStatus').textContent = 'Failed';
                document.getElementById('lblProgressStatus').className = 'badge badge-light-danger';
                Swal.fire('Upload Error', 'Failed to upload file chunk. Click Resume to try again.', 'error');
                
                document.getElementById('btnPauseUpload').style.display = 'none';
                document.getElementById('btnResumeUpload').style.display = 'inline-block';
            });
        }

        function updateProgressUI(loadedBytes) {
            const file = uploaderInstance.file;
            const percentage = Math.round((loadedBytes / file.size) * 100);
            
            document.getElementById('progressBarValue').style.width = `${percentage}%`;
            document.getElementById('lblProgressPercentage').textContent = `${percentage}%`;
            document.getElementById('lblProgressSize').textContent = `${roundSize(loadedBytes)} / ${roundSize(file.size)}`;

            const elapsedSeconds = (Date.now() - uploaderInstance.startTime) / 1000;
            if(elapsedSeconds > 0) {
                const speedBytesPerSec = loadedBytes / elapsedSeconds;
                const speedKbps = speedBytesPerSec / 1024;
                if(speedKbps > 1024) {
                    document.getElementById('lblProgressSpeed').textContent = `${(speedKbps / 1024).toFixed(2)} MB/s`;
                } else {
                    document.getElementById('lblProgressSpeed').textContent = `${speedKbps.toFixed(2)} KB/s`;
                }

                const remainingBytes = file.size - loadedBytes;
                const etaSeconds = remainingBytes / speedBytesPerSec;
                if(etaSeconds > 60) {
                    document.getElementById('lblProgressEta').textContent = `ETA: ${Math.floor(etaSeconds / 60)}m ${Math.round(etaSeconds % 60)}s`;
                } else {
                    document.getElementById('lblProgressEta').textContent = `ETA: ${Math.round(etaSeconds)}s`;
                }
            }
        }

        // Pause Upload
        document.getElementById('btnPauseUpload').addEventListener('click', () => {
            if(uploaderInstance) {
                uploaderInstance.isPaused = true;
                document.getElementById('btnPauseUpload').style.display = 'none';
                document.getElementById('btnResumeUpload').style.display = 'inline-block';
                document.getElementById('lblProgressStatus').textContent = 'Paused';
                document.getElementById('lblProgressStatus').className = 'badge badge-light-secondary';
            }
        });

        // Resume Upload
        document.getElementById('btnResumeUpload').addEventListener('click', () => {
            if(uploaderInstance) {
                uploaderInstance.isPaused = false;
                uploaderInstance.startTime = Date.now();
                document.getElementById('btnPauseUpload').style.display = 'inline-block';
                document.getElementById('btnResumeUpload').style.display = 'none';
                document.getElementById('lblProgressStatus').textContent = 'Uploading Chunks...';
                document.getElementById('lblProgressStatus').className = 'badge badge-light-warning';
                
                let nextChunk = 0;
                for (let i = 0; i < uploaderInstance.totalChunks; i++) {
                    if(!uploaderInstance.completedChunks.includes(i)) {
                        nextChunk = i;
                        break;
                    }
                }
                uploadNextChunk(nextChunk);
            }
        });

        // Background Popup Upload logic
        document.getElementById('btnBackgroundUpload').addEventListener('click', () => {
            if(!selectedFile) {
                Swal.fire('Error', 'Please select a file first.', 'error');
                return;
            }

            if(!validateMetadata()) return;

            // Expose values globally on window object so the popup window can access them
            window.parentSelectedFile = selectedFile;
            window.parentUploadConfig = {
                title: document.getElementById('media_title').value,
                description: document.getElementById('media_description').value,
                storage_target: document.getElementById('upload_storage_disk').value,
                chunk_size: CHUNK_SIZE
            };

            // Expose course configuration values if toggled
            window.parentMetadataConfig = {
                subject_id: document.getElementById('media_subject_id').value,
                class_id: document.getElementById('media_class_id').value,
                year_group_id: document.getElementById('media_year_group_id').value,
                academic_year: document.getElementById('media_academic_year').value,
                duration: document.getElementById('media_duration').value,
                thumbnail_path: thumbPathHidden.value,
                publication_status: document.getElementById('media_publication_status').value,
                
                course_assigned: assignSwitch.checked,
                course_id: document.getElementById('assignment_course_id').value,
                week_mode: weekModeSelect.value,
                selected_week_id: document.getElementById('assignment_selected_week_id').value,
                new_week_name: document.getElementById('assignment_new_week_name').value,
                new_week_due_date: document.getElementById('assignment_new_week_due_date').value,
                create_new_course: newCourseSwitch.checked ? '1' : '0',
                new_course_name: document.getElementById('assignment_new_course_name').value
            };

            // Pause parent uploader if running
            if(uploaderInstance) {
                uploaderInstance.isPaused = true;
                document.getElementById('btnPauseUpload').click();
            }

            // Open background uploader popup
            const popup = window.open('{{ route("admin.media-files.background-upload") }}', 'LMSBackgroundUploader', 'width=550,height=420,status=no,toolbar=no,menubar=no,location=no');
            
            if (popup) {
                Swal.fire({
                    title: 'Background Upload Initialized',
                    text: 'A popup window has been opened to process the upload. You can now freely navigate this dashboard without interrupting the upload.',
                    type: 'info'
                }).then(() => {
                    window.location.href = '{{ route("admin.media-files.index") }}';
                });
            } else {
                Swal.fire('Popup Blocked', 'Please allow popups for this site to upload in the background.', 'warning');
            }
        });
    });
</script>
@endpush

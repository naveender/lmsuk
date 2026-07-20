@extends('layouts.app')
@section('title', 'Edit Media File')

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
                        <h2 class="content-header-title float-left mb-0 font-weight-bold">Edit Video/File</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.media-files.index') }}">Manage Files</a></li>
                                <li class="breadcrumb-item active">Edit Media</li>
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
                            <h4 class="card-title">Edit Video Parameters</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                
                                <form action="{{ route('admin.media-files.update', $mediaFile->id) }}" method="POST" id="editForm">
                                    @csrf
                                    @method('PUT')

                                    <h5 class="text-primary font-weight-bold mb-1"><i class="feather icon-edit-3"></i> General Details</h5>
                                    
                                    <div class="form-group">
                                        <label for="media_title" class="font-weight-bold">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="media_title" class="form-control" placeholder="Enter video title" value="{{ $mediaFile->title }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="media_description" class="font-weight-bold">Description</label>
                                        <textarea name="description" id="media_description" class="form-control" rows="3" placeholder="Enter video description...">{{ $mediaFile->description }}</textarea>
                                    </div>

                                    <!-- Metadata Row 1 -->
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_subject_id" class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                                                <select name="subject_id" id="media_subject_id" class="form-control" required>
                                                    <option value="">-- Select Subject --</option>
                                                    @foreach($subjects as $subj)
                                                        <option value="{{ $subj->id }}" {{ $mediaFile->subject_id == $subj->id ? 'selected' : '' }}>{{ $subj->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_class_id" class="font-weight-bold">Class <span class="text-danger">*</span></label>
                                                <select name="class_id" id="media_class_id" class="form-control" required>
                                                    <option value="">-- Select Class --</option>
                                                    @foreach($classes as $cls)
                                                        <option value="{{ $cls->id }}" {{ $mediaFile->class_id == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
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
                                                <select name="year_group_id" id="media_year_group_id" class="form-control" required>
                                                    <option value="">-- Select Group Year --</option>
                                                    @foreach($yearGroups as $yg)
                                                        <option value="{{ $yg->id }}" {{ $mediaFile->year_group_id == $yg->id ? 'selected' : '' }}>{{ $yg->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_academic_year" class="font-weight-bold">Academic Year <span class="text-danger">*</span></label>
                                                <select name="academic_year" id="media_academic_year" class="form-control" required>
                                                    <option value="">-- Select Academic Year --</option>
                                                    @foreach($academicYears as $ay)
                                                        <option value="{{ $ay->name }}" {{ $mediaFile->academic_year == $ay->name ? 'selected' : '' }}>{{ $ay->name }}</option>
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
                                                <input type="text" name="duration" id="media_duration" class="form-control" placeholder="e.g. 15:30" value="{{ $mediaFile->duration }}">
                                                <small class="text-muted">Format: MM:SS or HH:MM:SS</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="media_publication_status" class="font-weight-bold">Publication Status</label>
                                                <select name="publication_status" id="media_publication_status" class="form-control">
                                                    <option value="published" {{ $mediaFile->publication_status == 'published' ? 'selected' : '' }}>Published</option>
                                                    <option value="draft" {{ $mediaFile->publication_status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thumbnail Upload -->
                                    <div class="form-group">
                                        <label class="font-weight-bold">Thumbnail Image</label>
                                        <div class="d-flex align-items-center">
                                            <div class="thumbnail-preview-box mr-2 d-flex align-items-center justify-content-center" id="thumbnailPreview" style="background-image: url('{{ $mediaFile->thumbnail_url ?: asset('theme/app-assets/images/pages/graphic-2.png') }}')">
                                                <div class="spinner-border text-primary" role="status" id="thumbnailSpinner" style="display: none;">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                            <div>
                                                <input type="file" id="thumbnailFileInput" class="form-control-file mb-50" accept="image/*">
                                                <small class="text-muted">JPG, PNG, WEBP (Max 2MB). Ideal size: 16:9 ratio.</small>
                                                <input type="hidden" name="thumbnail_path" id="shared_thumbnail_path" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Path or Direct link (for URL/Youtube type) -->
                                    <input type="hidden" name="type" value="{{ $mediaFile->type }}">
                                    @if(in_array($mediaFile->type, ['youtube', 'vimeo', 'video_url', 'google_drive', 'iframe']))
                                        <div class="form-group">
                                            <label for="media_path" class="font-weight-bold">Video Source Link / ID</label>
                                            <input type="text" name="path" id="media_path" class="form-control" value="{{ $mediaFile->path }}" required>
                                        </div>
                                    @else
                                        <div class="alert alert-info py-1">
                                            <i class="feather icon-video"></i> Video file is stored on: <b>{{ strtoupper($mediaFile->storage_disk) }}</b> <br>
                                            Original Name: <code>{{ $mediaFile->original_name }}</code> <br>
                                            Path: <code>{{ $mediaFile->path }}</code>
                                        </div>
                                    @endif

                                    <hr>

                                    <!-- Course & Week Assignment Section -->
                                    <div class="card bg-light border-0 shadow-none mb-2">
                                        <div class="card-body">
                                            <h5 class="text-primary font-weight-bold mb-1"><i class="feather icon-award"></i> Course & Weekly Scheduling</h5>
                                            <p class="text-muted small">Optionally assign this video resource directly to a course and schedule it for a specific week.</p>
                                            
                                            <!-- Step 1: Assignment Card Selector -->
                                            <div class="scheduling-mode-selector">
                                                <div class="scheduling-mode-card {{ !$assignedCourseId ? 'active' : '' }}" id="mode_card_unassigned">
                                                    <i class="feather icon-slash"></i>
                                                    <div class="mode-title">General Video File</div>
                                                    <div class="mode-desc">Unassigned Repository Asset</div>
                                                </div>
                                                <div class="scheduling-mode-card {{ $assignedCourseId ? 'active' : '' }}" id="mode_card_assigned">
                                                    <i class="feather icon-award"></i>
                                                    <div class="mode-title">Schedule to Course</div>
                                                    <div class="mode-desc">Link to Week Timeline</div>
                                                </div>
                                            </div>

                                            <!-- Hidden checkbox managed programmatically -->
                                            <input type="checkbox" id="assign_to_course_switch" class="custom-control-input" style="display: none;" {{ $assignedCourseId ? 'checked' : '' }}>

                                            <div id="courseAssignmentSection" style="display: {{ $assignedCourseId ? 'block' : 'none' }};" class="scheduling-card-body">
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
                                                <input type="checkbox" name="create_new_course" id="create_new_course_switch" class="custom-control-input" style="display: none;" value="1">

                                                <!-- Select Existing Course Input Group -->
                                                <div class="form-group" id="group_existing_course">
                                                    <label for="assignment_course_id" class="font-weight-bold">Select Target Course <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="feather icon-book"></i></span>
                                                        </div>
                                                        <select name="course_id" id="assignment_course_id" class="form-control">
                                                            <option value="">-- Select Course --</option>
                                                            @foreach($courses as $course)
                                                                <option value="{{ $course->id }}" {{ $assignedCourseId == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
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
                                                        <input type="text" name="new_course_name" id="assignment_new_course_name" class="form-control" placeholder="e.g. Year 5 Mathematics Advanced">
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
                                                <select name="week_mode" id="assignment_week_mode" class="form-control" style="display: none;">
                                                    <option value="existing" {{ $assignedWeekId ? 'selected' : '' }}>existing</option>
                                                    <option value="new">new</option>
                                                </select>

                                                <!-- Select Existing Week Input Group -->
                                                <div class="form-group" id="group_existing_week" style="display: {{ $assignedWeekId ? 'block' : 'none' }};">
                                                    <label for="assignment_selected_week_id" class="font-weight-bold">Select Scheduled Week <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="feather icon-clock"></i></span>
                                                        </div>
                                                        <select name="selected_week_id" id="assignment_selected_week_id" class="form-control">
                                                            <option value="">-- Select Week --</option>
                                                            @foreach($weeks as $w)
                                                                <option value="{{ $w->id }}" {{ $assignedWeekId == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                                                            @endforeach
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
                                                            <input type="text" name="new_week_name" id="assignment_new_week_name" class="form-control" placeholder="e.g. Week 15 - Geometry Concepts">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="assignment_new_week_due_date" class="font-weight-bold">Assignment Due Date (Optional)</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                                                            </div>
                                                            <input type="date" name="new_week_due_date" id="assignment_new_week_due_date" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary font-weight-bold mr-1">
                                            <i class="feather icon-save"></i> Save Changes
                                        </button>
                                        <a href="{{ route('admin.media-files.index') }}" class="btn btn-outline-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </form>

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

        // Course Weeks data map
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

        // Validation before submit
        document.getElementById('editForm').addEventListener('submit', function(e) {
            if(assignSwitch.checked) {
                if(newCourseSwitch.checked) {
                    if(!document.getElementById('assignment_new_course_name').value) {
                        e.preventDefault();
                        Swal.fire('Required Field', 'Please enter the New Course Name.', 'error');
                    }
                } else {
                    if(!document.getElementById('assignment_course_id').value) {
                        e.preventDefault();
                        Swal.fire('Required Field', 'Please select a Course.', 'error');
                    }
                }

                if(weekModeSelect.value === 'existing') {
                    if(!document.getElementById('assignment_selected_week_id').value) {
                        e.preventDefault();
                        Swal.fire('Required Field', 'Please select an existing Week.', 'error');
                    }
                } else {
                    if(!document.getElementById('assignment_new_week_name').value) {
                        e.preventDefault();
                        Swal.fire('Required Field', 'Please enter the New Week Name.', 'error');
                    }
                }
            }
        });
    });
</script>
@endpush

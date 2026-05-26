@extends('layouts.app')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Add Student</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a>
                                    </li>
                                    <li class="breadcrumb-item">Add</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="form" action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_mode" id="parent_mode" value="{{ old('parent_mode', 'new') }}">

                    {{-- ==================== SECTION 1: STUDENT DETAILS ==================== --}}
                    <section id="student-details-section">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><i class="feather icon-user"></i> Student Details</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Fullname</label>
                                                    <input type="text" id="name" class="form-control"
                                                        name="name" value="{{ old('name') }}"
                                                        placeholder="Enter Student Fullname" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Username</label>
                                                    <input type="text" id="username" class="form-control"
                                                        name="username" value="{{ old('username') }}"
                                                        placeholder="Enter Username" autocomplete="off"
                                                        required>
                                                    <small class="text-muted">Username must be unique.
                                                        Suggested: <a href="javascript:void(0)"
                                                            id="suggest_username"></a></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Email</label>
                                                    <input type="email" id="email" class="form-control"
                                                        name="email" value="{{ old('email') }}"
                                                        placeholder="Enter Student Email" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Password</label>
                                                    <input type="password" id="password"
                                                        class="form-control" name="password"
                                                        placeholder="Enter Student Password"
                                                        autocomplete="new-password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Date of Birth (DOB)</label>
                                                    <input type="date" class="form-control birthdate-picker"
                                                        id="date_of_birth" value="{{ old('date_of_birth') }}"
                                                        required placeholder="Date of Birth (DOB)"
                                                        name="date_of_birth">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label>Group Year</label>
                                                <select class="form-control" name="group_year" required>
                                                    <option value="">--Select Group Year--</option>
                                                    @foreach ($yearGroups as $year)
                                                        <option value="{{ $year->value }}" {{ old('group_year') == $year->value ? 'selected' : '' }}>
                                                            {{ $year->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label>Academic Year</label>
                                                <select class="form-control" name="academic_year" required>
                                                    <option value="">--Select Academic Year--</option>
                                                    @foreach ($academicYears as $year)
                                                        <option value="{{ $year->id }}" {{ old('academic_year') == $year->id ? 'selected' : '' }}>
                                                            {{ $year->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Region</label>
                                                    <input type="text" class="form-control" name="region"
                                                        required
                                                        placeholder="Geographic region or campus the student belongs to..."
                                                        value="{{ old('region') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <div class="controls">
                                                    <label>Student Phone Number</label>
                                                    <input type="text" class="form-control"
                                                        name="student_phone"
                                                        value="{{ old('student_phone') }}"
                                                        placeholder="Direct contact number of the student, if available...(optional)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-radio-con">
                                                                <input type="radio" name="gender"
                                                                    {{ old('gender', 'male') == 'male' ? 'checked' : '' }}
                                                                    value="male">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                Male
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-radio-con">
                                                                <input type="radio" name="gender"
                                                                    {{ old('gender') == 'female' ? 'checked' : '' }}
                                                                    value="female">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                Female
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-radio-con">
                                                                <input type="radio" name="gender"
                                                                    {{ old('gender') == 'other' ? 'checked' : '' }}
                                                                    value="other">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                Other
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ==================== SECTION 2: PARENT DETAILS ==================== --}}
                    <section id="parent-details-section">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><i class="feather icon-users"></i> Parent / Guardian Details</h4>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0" style="display:flex;gap:10px;">
                                        <li>
                                            <button type="button" class="btn btn-sm parent-mode-btn" id="btn-new-parent"
                                                onclick="setParentMode('new')">
                                                <i class="feather icon-plus-circle"></i> Create New Parent
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" class="btn btn-sm parent-mode-btn" id="btn-existing-parent"
                                                onclick="setParentMode('existing')">
                                                <i class="feather icon-link"></i> Select Existing Parent
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body">

                                    {{-- ===== MODE: CREATE NEW PARENT (default) ===== --}}
                                    <div id="new-parent-fields">
                                        <div class="row">
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Parent Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="parent_name" class="form-control"
                                                            name="parent_name" value="{{ old('parent_name') }}"
                                                            placeholder="Full name of the parent or guardian">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Parent Username <span class="text-danger">*</span></label>
                                                        <input type="text" id="parent_username" class="form-control"
                                                            name="parent_username" value="{{ old('parent_username') }}"
                                                            placeholder="Enter parent username" autocomplete="off">
                                                        <small class="text-muted">Username must be unique.
                                                            Suggested: <a href="javascript:void(0)"
                                                                id="suggest_parent_username"></a></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Parent Email <span class="text-danger">*</span></label>
                                                        <input type="email" id="parent_email" class="form-control"
                                                            name="parent_email" value="{{ old('parent_email') }}"
                                                            placeholder="Parent email address">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Parent Password <span class="text-danger">*</span></label>
                                                        <input type="password" id="parent_password" class="form-control"
                                                            name="parent_password"
                                                            placeholder="Parent login password"
                                                            autocomplete="new-password">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Relation to Student <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="parent_relation">
                                                            <option value="Father" {{ old('parent_relation') == 'Father' ? 'selected' : '' }}>Father</option>
                                                            <option value="Mother" {{ old('parent_relation') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                                            <option value="Guardian" {{ old('parent_relation') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Parent Phone <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            name="parent_phone" value="{{ old('parent_phone') }}"
                                                            placeholder="Primary contact number">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Alternate Phone</label>
                                                        <input type="text" class="form-control"
                                                            name="parent_alternate_phone" value="{{ old('parent_alternate_phone') }}"
                                                            placeholder="Secondary contact number (optional)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Emergency Contact</label>
                                                        <input type="text" class="form-control"
                                                            name="parent_emergency_contact" value="{{ old('parent_emergency_contact') }}"
                                                            placeholder="Emergency contact number (optional)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ===== MODE: SELECT EXISTING PARENT ===== --}}
                                    <div id="existing-parent-fields" style="display:none;">
                                        <div class="row">
                                            <div class="col-12 col-sm-8">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>Select Parent <span class="text-danger">*</span></label>
                                                        <select class="form-control" name="parent_id" id="parent_id_select">
                                                            <option value="">-- Select an Existing Parent --</option>
                                                            @foreach ($parents as $parent)
                                                                <option value="{{ $parent->id }}"
                                                                    {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                                    {{ $parent->name }} ({{ $parent->email }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4 d-flex align-items-end mb-1">
                                                @if($parents->isEmpty())
                                                    <p class="text-muted mb-0"><i class="feather icon-alert-triangle"></i> No parents exist yet. Use "Create New Parent" instead.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- ==================== SUBMIT ==================== --}}
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary mr-1 mb-1">
                                <i class="feather icon-check"></i> Create Student
                            </button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-outline-warning mb-1">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <style>
        .parent-mode-btn {
            transition: all 0.2s ease;
        }
        .parent-mode-btn.active-mode {
            background-color: #7367f0;
            color: #fff;
            border-color: #7367f0;
        }
        .parent-mode-btn:not(.active-mode) {
            background-color: #f8f8f8;
            color: #626262;
            border-color: #ddd;
        }
        .parent-mode-btn:not(.active-mode):hover {
            background-color: #e8e6fc;
            color: #7367f0;
            border-color: #7367f0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Student username suggestion ===
            const nameInput = document.getElementById('name');
            const usernameInput = document.getElementById('username');
            const suggestBtn = document.getElementById('suggest_username');

            nameInput.addEventListener('input', function() {
                if (nameInput.value) {
                    let baseName = nameInput.value.toLowerCase().replace(/[^a-z0-9]/g, '');
                    if (baseName.length > 0) {
                        let suggested = baseName + Math.floor(Math.random() * 1000);
                        suggestBtn.textContent = suggested;
                    } else {
                        suggestBtn.textContent = '';
                    }
                } else {
                    suggestBtn.textContent = '';
                }
            });

            suggestBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (suggestBtn.textContent) {
                    usernameInput.value = suggestBtn.textContent;
                }
            });

            // === Parent username suggestion ===
            const parentNameInput = document.getElementById('parent_name');
            const parentUsernameInput = document.getElementById('parent_username');
            const parentSuggestBtn = document.getElementById('suggest_parent_username');

            parentNameInput.addEventListener('input', function() {
                if (parentNameInput.value) {
                    let baseName = parentNameInput.value.toLowerCase().replace(/[^a-z0-9]/g, '');
                    if (baseName.length > 0) {
                        let suggested = baseName + Math.floor(Math.random() * 1000);
                        parentSuggestBtn.textContent = suggested;
                    } else {
                        parentSuggestBtn.textContent = '';
                    }
                } else {
                    parentSuggestBtn.textContent = '';
                }
            });

            parentSuggestBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (parentSuggestBtn.textContent) {
                    parentUsernameInput.value = parentSuggestBtn.textContent;
                }
            });

            // === Initialize parent mode from old value ===
            const initialMode = document.getElementById('parent_mode').value || 'new';
            setParentMode(initialMode);
        });

        function setParentMode(mode) {
            const hiddenInput = document.getElementById('parent_mode');
            const newFields = document.getElementById('new-parent-fields');
            const existingFields = document.getElementById('existing-parent-fields');
            const btnNew = document.getElementById('btn-new-parent');
            const btnExisting = document.getElementById('btn-existing-parent');

            hiddenInput.value = mode;

            if (mode === 'new') {
                newFields.style.display = 'block';
                existingFields.style.display = 'none';
                btnNew.classList.add('active-mode');
                btnExisting.classList.remove('active-mode');

                // Enable new parent fields, disable existing
                newFields.querySelectorAll('input, select').forEach(el => el.disabled = false);
                existingFields.querySelectorAll('input, select').forEach(el => el.disabled = true);
            } else {
                newFields.style.display = 'none';
                existingFields.style.display = 'block';
                btnExisting.classList.add('active-mode');
                btnNew.classList.remove('active-mode');

                // Enable existing parent field, disable new
                existingFields.querySelectorAll('input, select').forEach(el => el.disabled = false);
                newFields.querySelectorAll('input, select').forEach(el => el.disabled = true);
            }
        }
    </script>
@endsection

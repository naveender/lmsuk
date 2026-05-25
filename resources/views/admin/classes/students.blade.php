@extends('layouts.app')

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
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Class Members</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">Classes</a></li>
                                    <li class="breadcrumb-item active">{{ $class->name }} Students</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Notifications -->
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="feather icon-check-circle mr-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="feather icon-alert-triangle mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- Class Info Card -->
                <div class="card mb-4">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8 col-12">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xl bg-info mr-2" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items:center; justify-content:center; color: #fff;">
                                            <i class="feather icon-book" style="font-size: 1.8rem;"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 font-weight-bold">{{ $class->name }}</h3>
                                            <p class="text-muted mb-0">
                                                <span class="mr-2"><strong>Academic Year:</strong> {{ $class->academic_year }}</span>
                                                <span><strong>Group Year:</strong> {{ $class->group_year }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 text-md-right mt-1 mt-md-0">
                                    <span class="badge badge-pill {{ $class->is_active ? 'badge-success' : 'badge-danger' }} p-1">
                                        {{ $class->is_active ? 'Active Class' : 'Inactive Class' }}
                                    </span>
                                    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary ml-1">
                                        <i class="feather icon-arrow-left"></i> Back to Classes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- LEFT COLUMN: Current Students in Class -->
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title font-weight-bold">
                                    <i class="feather icon-users text-primary mr-1"></i> Current Students
                                    <span class="badge badge-pill badge-primary ml-1">{{ $currentStudents->total() }}</span>
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    @if ($currentStudents->total() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($currentStudents as $index => $student)
                                                        <tr>
                                                            <td>{{ $currentStudents->firstItem() + $index }}</td>
                                                            <td>
                                                                <span class="font-weight-bold text-dark">{{ $student->name }}</span>
                                                            </td>
                                                            <td>{{ $student->email }}</td>
                                                            <td>
                                                                <form action="{{ route('admin.classes.students.remove', [$class->id, $student->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove student {{ $student->name }} from this class?');">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove from class">
                                                                        <i class="feather icon-user-minus"></i> Remove
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div class="d-flex justify-content-center mt-2">
                                            {{ $currentStudents->appends(['available_page' => $availableStudents->currentPage()] + request()->except('current_page'))->links('pagination::bootstrap-5') }}
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="feather icon-user-x text-muted" style="font-size: 3.5rem;"></i>
                                            <p class="text-muted mt-1 font-weight-medium">No students currently in this class.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Add Students to Class -->
                    <div class="col-lg-6 col-12">
                        <!-- Filters Panel -->
                        <div class="card mb-3">
                            <div class="card-header collapse-header" data-toggle="collapse" data-target="#available-students-filter">
                                <h4 class="card-title font-weight-bold">
                                    <i class="feather icon-filter text-info mr-1"></i> Filter Available Students
                                </h4>
                                <div class="heading-elements">
                                    <i class="feather icon-chevron-down"></i>
                                </div>
                            </div>
                            <div id="available-students-filter" class="card-content collapse show">
                                <div class="card-body py-1">
                                    <form method="GET" action="{{ route('admin.classes.students', $class->id) }}" class="row align-items-end g-2">
                                        <div class="col-md-6 col-12 mb-1">
                                            <label for="search" class="form-label font-small-3">Search Name / Email</label>
                                            <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search student...">
                                        </div>
                                        <div class="col-md-3 col-6 mb-1">
                                            <label for="group_year" class="form-label font-small-3">Group Year</label>
                                            <select name="group_year" id="group_year" class="form-control form-control-sm">
                                                <option value="">All</option>
                                                @foreach ($yearGroups as $yg)
                                                    <option value="{{ $yg->title }}" {{ request('group_year') == $yg->title ? 'selected' : '' }}>{{ $yg->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-6 mb-1">
                                            <label for="academic_year" class="form-label font-small-3">Academic Year</label>
                                            <select name="academic_year" id="academic_year" class="form-control form-control-sm">
                                                <option value="">All</option>
                                                @foreach ($academicYears as $year)
                                                    <option value="{{ $year->name }}" {{ request('academic_year') == $year->name ? 'selected' : '' }}>{{ $year->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end mb-1">
                                            <a href="{{ route('admin.classes.students', $class->id) }}" class="btn btn-sm btn-secondary mr-1">Reset</a>
                                            <button type="submit" class="btn btn-sm btn-primary">Apply Filters</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Available Students Table -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title font-weight-bold">
                                    <i class="feather icon-plus-circle text-success mr-1"></i> Available Students
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    @if ($availableStudents->total() > 0)
                                        <form action="{{ route('admin.classes.students.add', $class->id) }}" method="POST" id="bulk-add-form">
                                            @csrf
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input type="checkbox" id="select-all-students">
                                                    <span class="vs-checkbox vs-checkbox-sm">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="font-small-3 font-weight-medium">Select All</span>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-success" id="bulk-add-btn" disabled>
                                                    <i class="feather icon-user-plus"></i> Add Selected
                                                </button>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-hover-animation">
                                                    <thead>
                                                        <tr>
                                                            <th width="40"></th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($availableStudents as $student)
                                                            <tr>
                                                                <td>
                                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" class="student-checkbox">
                                                                        <span class="vs-checkbox vs-checkbox-sm">
                                                                            <span class="vs-checkbox--check">
                                                                                <i class="vs-icon feather icon-check"></i>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span class="font-weight-bold text-dark">{{ $student->name }}</span>
                                                                </td>
                                                                <td>{{ $student->email }}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-outline-success inline-add-btn" data-student-id="{{ $student->id }}">
                                                                        <i class="feather icon-user-plus"></i> Add
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>

                                        <!-- Invisible form for inline single student additions -->
                                        <form action="{{ route('admin.classes.students.add', $class->id) }}" method="POST" id="single-add-form" style="display:none;">
                                            @csrf
                                            <input type="hidden" name="student_ids[]" id="single-student-id">
                                        </form>

                                        <!-- Pagination -->
                                        <div class="d-flex justify-content-center mt-2">
                                            {{ $availableStudents->appends(['current_page' => $currentStudents->currentPage()] + request()->except('available_page'))->links('pagination::bootstrap-5') }}
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="feather icon-users text-muted" style="font-size: 3.5rem;"></i>
                                            <p class="text-muted mt-1 font-weight-medium">No available students found.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Page Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('select-all-students');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const bulkAddBtn = document.getElementById('bulk-add-btn');
            const singleAddForm = document.getElementById('single-add-form');
            const singleStudentIdInput = document.getElementById('single-student-id');
            const inlineAddButtons = document.querySelectorAll('.inline-add-btn');

            // Function to update the disabled state of the bulk add button
            function updateBulkAddButtonState() {
                if (!bulkAddBtn) return;
                const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
                bulkAddBtn.disabled = checkedCount === 0;
            }

            // Handle "Select All" click
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    studentCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                    updateBulkAddButtonState();
                });
            }

            // Handle individual checkbox changes
            studentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    // Update Select All checkbox state based on child checkboxes
                    if (selectAllCheckbox) {
                        const totalCheckboxCount = studentCheckboxes.length;
                        const checkedCheckboxCount = document.querySelectorAll('.student-checkbox:checked').length;
                        selectAllCheckbox.checked = totalCheckboxCount === checkedCheckboxCount;
                    }
                    updateBulkAddButtonState();
                });
            });

            // Handle individual inline "Add" button click
            inlineAddButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const studentId = btn.getAttribute('data-student-id');
                    singleStudentIdInput.value = studentId;
                    singleAddForm.submit();
                });
            });
        });
    </script>
@endsection

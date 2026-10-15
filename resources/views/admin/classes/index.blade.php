@extends('layouts.app')

@section('content')
<!-- Include Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    
    <div class="content-wrapper">
        
        <!-- Page Header / Title & Breadcrumbs -->
        <div class="admin-page-header">
            <div class="admin-page-title-wrap">
                <div class="admin-title-row">
                    <h1 class="admin-page-heading">Classes Management</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.classes.index') }}">Classes</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>List</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Manage class enrollments and academic sessions</p>
            </div>
            <div>
                <a href="{{ route('admin.classes.create') }}" class="btn btn-admin-add">
                    <i data-lucide="plus" style="width: 17px; height: 17px;"></i>
                    <span>Add New Class</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px; background-color: var(--theme-success-light); border: 1px solid #A8E8C2; color: #155724;">
                <i class="feather icon-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 10px; background-color: var(--theme-danger-light); border: 1px solid #FCD6D9; color: var(--theme-danger);">
                <i class="feather icon-alert-circle mr-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filters Section -->
        <div class="card theme-card" id="classFilterCard">
            <div class="theme-card-header" style="cursor: pointer;" onclick="toggleClassFilters()">
                <h2 class="theme-card-title">
                    <i data-lucide="filter"></i>
                    <span>Filters</span>
                </h2>
                <button type="button" class="btn btn-link p-0" aria-label="Toggle filters" style="color: #7A8499;">
                    <i data-lucide="chevron-up" id="classFilterToggleIcon" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="theme-card-body" id="classFilterBody">
                <form method="GET" action="{{ route('admin.classes.index') }}">
                    <div class="row g-3">
                        
                        <!-- 1. Class Name -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="name">Class Name</label>
                            <div class="filter-input-group">
                                <i data-lucide="search" class="filter-icon"></i>
                                <input type="text" name="name" id="name" value="{{ request('name') }}"
                                    class="form-control" placeholder="Search class name...">
                            </div>
                        </div>

                        <!-- 2. Group Year -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="group_year">Group Year</label>
                            <div class="filter-input-group">
                                <i data-lucide="graduation-cap" class="filter-icon"></i>
                                <select name="group_year" id="group_year" class="custom-select">
                                    <option value="">All Years</option>
                                    @foreach($yearGroups as $yg)
                                        <option value="{{ $yg->title }}" {{ request('group_year') == $yg->title ? 'selected' : '' }}>{{ $yg->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- 3. Academic Year -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="academic_year">Academic Year</label>
                            <div class="filter-input-group">
                                <i data-lucide="calendar" class="filter-icon"></i>
                                <select name="academic_year" id="academic_year" class="custom-select">
                                    <option value="">All Academic Years</option>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->name }}" {{ request('academic_year') == $year->name ? 'selected' : '' }}>{{ $year->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- 4. Status -->
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="is_active">Status</label>
                            <div class="filter-input-group">
                                <i data-lucide="bar-chart-2" class="filter-icon"></i>
                                <select name="is_active" id="is_active" class="custom-select">
                                    <option value="">All Statuses</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- Filter Action Buttons -->
                    <div class="filter-actions-bar">
                        <button type="submit" class="btn btn-filter-apply">
                            <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                            <span>Apply Filters</span>
                        </button>
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-filter-reset">
                            <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Classes List Card -->
        <div class="card theme-card">
            
            <!-- Table Header Toolbar -->
            <div class="table-header-toolbar">
                <div class="table-title-area">
                    <h2 class="table-title-text">
                        <i data-lucide="layers" style="color: var(--theme-primary-bright); width: 22px; height: 22px;"></i>
                        <span>Classes List</span>
                    </h2>
                    <span class="badge-count-pill">{{ $classes->total() }} classes</span>
                </div>

                <div class="table-actions-toolbar">
                    <a href="{{ route('admin.classes.create') }}" class="btn btn-action-import" title="Add Class">
                        <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                        <span>Add Class</span>
                    </a>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-responsive">
                <table class="table theme-table">
                    <thead>
                        <tr>
                            <th style="width: 36px; text-align: center;">
                                <input type="checkbox" class="custom-theme-checkbox" id="selectAllCheckbox" title="Select All">
                            </th>
                            <th style="width: 40px;">#</th>
                            <th>CLASS NAME</th>
                            <th>GROUP YEAR</th>
                            <th>ACADEMIC YEAR</th>
                            <th>ENROLLED STUDENTS</th>
                            <th>STATUS</th>
                            <th style="text-align: center;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $index => $class)
                            @php
                                $studentCount = $class->students ? $class->students->count() : 0;
                            @endphp
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="custom-theme-checkbox row-checkbox" value="{{ $class->id }}">
                                </td>
                                <td class="font-weight-bold">{{ $classes->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="student-id-badge mr-1" style="background: rgba(36, 119, 249, 0.1); color: var(--theme-primary-bright); width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;">
                                            <i data-lucide="book-open" style="width: 16px; height: 16px;"></i>
                                        </div>
                                        <strong class="text-dark font-weight-bold">{{ $class->name }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(6, 48, 128, 0.08); color: var(--theme-primary); font-weight: 600; border-radius: 6px; padding: 5px 10px;">
                                        {{ $class->group_year ?: 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span>{{ $class->academic_year ?: 'N/A' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.classes.students', $class->id) }}" class="badge" style="background: rgba(36, 119, 249, 0.12); color: var(--theme-primary-bright); font-weight: 700; border-radius: 6px; padding: 6px 12px; text-decoration: none;">
                                        <i class="feather icon-users mr-25"></i> {{ $studentCount }} {{ Str::plural('student', $studentCount) }}
                                    </a>
                                </td>
                                <td>
                                    @if($class->is_active)
                                        <span class="status-pill status-active">
                                            <span class="status-dot"></span> Active
                                        </span>
                                    @else
                                        <span class="status-pill status-inactive">
                                            <span class="status-dot"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div class="action-icon-group">
                                        <!-- Manage Students Button -->
                                        <a href="{{ route('admin.classes.students', $class->id) }}" class="btn-table-action action-view" title="Manage Students in Class">
                                            <i data-lucide="users" style="width: 15px; height: 15px;"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn-table-action action-edit" title="Edit Class">
                                            <i data-lucide="pencil" style="width: 15px; height: 15px;"></i>
                                        </a>

                                        <!-- Soft Delete Button -->
                                        <button type="button" class="btn-table-action action-delete" title="Delete Class"
                                            onclick="confirmDeleteClass('{{ route('admin.classes.destroy', $class->id) }}', '{{ addslashes($class->name) }}')">
                                            <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i data-lucide="layers" style="width: 40px; height: 40px; opacity: 0.3; margin-bottom: 8px;"></i>
                                    <p class="mb-0">No classes found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer Toolbar -->
            <div class="table-footer-bar">
                <div class="table-summary-text">
                    Showing {{ $classes->firstItem() ?? 0 }} to {{ $classes->lastItem() ?? 0 }} of {{ $classes->total() }} classes
                </div>

                <div>
                    {{ $classes->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const selectAll = document.getElementById('selectAllCheckbox');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
            });
        }
    });

    function toggleClassFilters() {
        const body = document.getElementById('classFilterBody');
        const icon = document.getElementById('classFilterToggleIcon');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.style.transform = 'rotate(0deg)';
        } else {
            body.style.display = 'none';
            icon.style.transform = 'rotate(180deg)';
        }
    }

    function confirmDeleteClass(deleteUrl, className) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Class?',
                html: `Are you sure you want to delete class <strong>${className}</strong>?<br><small>This class will be removed and detached from current rosters.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FA2833',
                cancelButtonColor: '#7A8499',
                confirmButtonText: '<i class="feather icon-trash-2 mr-50"></i> Yes, delete class',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger font-weight-bold px-2 py-1',
                    cancelButton: 'btn btn-secondary font-weight-bold px-2 py-1 mr-1'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Network response was not ok');
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message || 'Class deleted successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(() => {
                        executeClassDelete(deleteUrl);
                    });
                }
            });
        } else {
            if (confirm(`Are you sure you want to delete ${className}?`)) {
                executeClassDelete(deleteUrl);
            }
        }
    }

    function executeClassDelete(url) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection

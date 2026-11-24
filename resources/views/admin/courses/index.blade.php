@extends('layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">

        <!-- Page Header / Title & Breadcrumbs -->
        <div class="admin-page-header">
            <div class="admin-page-title-wrap">
                <div class="admin-title-row">
                    <h1 class="admin-page-heading">Courses Management</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.courses.index') }}">Courses</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>List</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Configure courses, curate weekly paper schedules, and assign instructional video lessons</p>
            </div>
            <div>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-admin-add">
                    <i data-lucide="plus" style="width: 17px; height: 17px;"></i>
                    <span>Add Course</span>
                </a>
            </div>
        </div>

        <div class="content-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                    <div class="alert-body d-flex align-items-center">
                        <i data-lucide="check-circle" class="mr-1" style="width: 18px; height: 18px;"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                    <div class="alert-body d-flex align-items-center">
                        <i data-lucide="alert-circle" class="mr-1" style="width: 18px; height: 18px;"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Filters Section (Matches Students Reference) -->
            <div class="card theme-card" id="filterCard">
                <div class="theme-card-header" style="cursor: pointer;" onclick="toggleFilters()">
                    <h2 class="theme-card-title">
                        <i data-lucide="filter"></i>
                        <span>Filters</span>
                    </h2>
                    <button type="button" class="btn btn-link p-0" aria-label="Toggle filters" style="color: #7A8499;">
                        <i data-lucide="chevron-up" id="filterToggleIcon" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
                <div class="theme-card-body" id="filterBody">
                    <form method="GET" action="{{ route('admin.courses.index') }}">
                        <div class="row g-3">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="name">Course Name</label>
                                <div class="filter-input-group">
                                    <i data-lucide="search" class="filter-icon"></i>
                                    <input type="text" name="name" id="name" value="{{ request('name') }}"
                                        class="form-control" placeholder="Search by course title...">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-2 mb-xl-0">
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
                        <div class="filter-actions-bar">
                            <button type="submit" class="btn btn-filter-apply">
                                <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                                <span>Apply Filters</span>
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-filter-reset">
                                <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Courses Table Container -->
            <div class="theme-card">
                <!-- Toolbar with Count Badge -->
                <div class="table-header-toolbar">
                    <div class="toolbar-left">
                        <span class="badge-total-count">
                            <i data-lucide="book"></i>
                            <strong>{{ $courses->total() }}</strong> Total Courses
                        </span>
                    </div>
                    <div class="toolbar-right">
                        @if(request()->anyFilled(['name', 'is_active']))
                            <span class="badge badge-light-warning font-small-2">
                                <i data-lucide="filter" style="width: 12px; height: 12px; vertical-align: middle;"></i> Filtered Results
                            </span>
                        @endif
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="theme-table table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="min-width: 250px;">Course Name & Description</th>
                                <th style="width: 140px;" class="text-center">Assigned Papers</th>
                                <th style="width: 140px;" class="text-center">Assigned Videos</th>
                                <th style="width: 110px;" class="text-center">Status</th>
                                <th style="width: 280px;" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $index => $course)
                                <tr id="course-row-{{ $course->id }}">
                                    <td class="font-weight-bold">
                                        {{ $courses->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark font-medium-1 mb-1">
                                            {{ $course->name }}
                                        </div>
                                        @if($course->description)
                                            <div class="font-small-2 text-truncate" style="max-width: 380px;">
                                                {{ Str::limit(strip_tags($course->description), 80) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-primary font-weight-bold px-2 py-50" style="border-radius: 20px;">
                                            <i data-lucide="file-text" style="width: 13px; height: 13px; vertical-align: middle; margin-right: 3px;"></i>
                                            {{ $course->papers()->count() }} Papers
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light-info font-weight-bold px-2 py-50" style="border-radius: 20px;">
                                            <i data-lucide="video" style="width: 13px; height: 13px; vertical-align: middle; margin-right: 3px;"></i>
                                            {{ $course->mediaFiles()->count() }} Videos
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($course->is_active)
                                            <span class="badge badge-light-success font-small-2">Active</span>
                                        @else
                                            <span class="badge badge-light-danger font-small-2">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="d-inline-flex align-items-center" style="gap: 6px;">
                                            <!-- Manage Papers Link -->
                                            <a href="{{ route('admin.courses.papers', $course->id) }}"
                                               class="btn btn-sm btn-outline-info font-weight-bold"
                                               title="Manage Weekly Papers"
                                               style="border-radius: 6px; padding: 5px 9px;">
                                                <i data-lucide="file-text" style="width: 14px; height: 14px; vertical-align: middle; margin-right: 2px;"></i> Papers
                                            </a>

                                            <!-- Manage Media Videos Link -->
                                            <a href="{{ route('admin.courses.media', $course->id) }}"
                                               class="btn btn-sm btn-outline-warning font-weight-bold"
                                               title="Manage Video Lessons"
                                               style="border-radius: 6px; padding: 5px 9px;">
                                                <i data-lucide="video" style="width: 14px; height: 14px; vertical-align: middle; margin-right: 2px;"></i> Videos
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.courses.edit', $course->id) }}"
                                               class="btn btn-sm btn-icon btn-outline-primary"
                                               title="Edit Course"
                                               style="border-radius: 6px; padding: 5px 8px;">
                                                <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                            </a>

                                            <!-- Soft-Delete Button with SweetAlert -->
                                            <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger"
                                                    title="Delete Course"
                                                    onclick="confirmDeleteCourse({{ $course->id }}, '{{ addslashes($course->name) }}')"
                                                    style="border-radius: 6px; padding: 5px 8px;">
                                                <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                            </button>

                                            <form id="delete-course-form-{{ $course->id }}"
                                                  action="{{ route('admin.courses.destroy', $course->id) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="empty-state">
                                            <i data-lucide="book" style="width: 48px; height: 48px; stroke-width: 1.5;"></i>
                                            <h5 class="mt-2 font-weight-bold text-dark">No Courses Found</h5>
                                            <p class="font-small-3">No courses match your filter criteria, or none have been added yet.</p>
                                            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary btn-sm mt-1" style="border-radius: 6px;">
                                                <i data-lucide="plus" style="width: 14px; height: 14px; vertical-align: middle;"></i> Create New Course
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Symmetrical Table Footer -->
                @if($courses->hasPages() || $courses->total() > 0)
                    <div class="table-footer-bar">
                        <div class="footer-entries-info">
                            Showing {{ $courses->firstItem() ?? 0 }} to {{ $courses->lastItem() ?? 0 }} of {{ $courses->total() }} entries
                        </div>
                        <div class="footer-pagination-links">
                            {{ $courses->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Delete Course with SweetAlert2
    function confirmDeleteCourse(courseId, courseName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Course?',
                html: `Are you sure you want to delete <strong>"${courseName}"</strong>?<br><small>Associated weekly paper assignments and video links will also be removed.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FA2833',
                cancelButtonColor: '#6e7d88',
                confirmButtonText: 'Yes, Delete It',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn btn-danger font-weight-bold px-3 py-1',
                    cancelButton: 'btn btn-secondary font-weight-bold px-3 py-1 mr-1'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Removing course...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const csrfToken = document.querySelector('meta[name="csrf-token"]') 
                        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                        : '{{ csrf_token() }}';

                    fetch(`/admin/courses/${courseId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message || 'Course has been deleted.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                const row = document.getElementById(`course-row-${courseId}`);
                                if (row) {
                                    row.style.transition = 'all 0.4s ease';
                                    row.style.opacity = '0';
                                    setTimeout(() => row.remove(), 400);
                                } else {
                                    location.reload();
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Deletion failed');
                        }
                    })
                    .catch(err => {
                        const fallbackForm = document.getElementById(`delete-course-form-${courseId}`);
                        if (fallbackForm) {
                            fallbackForm.submit();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: err.message || 'Could not delete course.'
                            });
                        }
                    });
                }
            });
        } else {
            if (confirm(`Are you sure you want to delete course: "${courseName}"?`)) {
                document.getElementById(`delete-course-form-${courseId}`).submit();
            }
        }
    }
</script>
@endpush

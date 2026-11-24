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
                    <h1 class="admin-page-heading">Homework Directory</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.homeworks.index') }}">Homework</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>List</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Manage homework tasks, track student visibility, upload resources, and assign exercises</p>
            </div>
            <div>
                <a href="{{ route('admin.homeworks.create') }}" class="btn btn-admin-add">
                    <i data-lucide="plus" style="width: 17px; height: 17px;"></i>
                    <span>Create Homework</span>
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
                    <form method="GET" action="{{ route('admin.homeworks.index') }}">
                        <div class="row g-3">
                            <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="search">Search Title / Details</label>
                                <div class="filter-input-group">
                                    <i data-lucide="search" class="filter-icon"></i>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                           class="form-control" placeholder="Search keywords...">
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="subject_id">Subject</label>
                                <div class="filter-input-group">
                                    <i data-lucide="book" class="filter-icon"></i>
                                    <select name="subject_id" id="subject_id" class="custom-select">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="class_id">Class</label>
                                <div class="filter-input-group">
                                    <i data-lucide="users" class="filter-icon"></i>
                                    <select name="class_id" id="class_id" class="custom-select">
                                        <option value="">All Classes</option>
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                                                {{ $cls->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="year_group_id">Year Group</label>
                                <div class="filter-input-group">
                                    <i data-lucide="graduation-cap" class="filter-icon"></i>
                                    <select name="year_group_id" id="year_group_id" class="custom-select">
                                        <option value="">All Years</option>
                                        @foreach($yearGroups as $yg)
                                            <option value="{{ $yg->id }}" {{ request('year_group_id') == $yg->id ? 'selected' : '' }}>
                                                {{ $yg->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
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
                            <a href="{{ route('admin.homeworks.index') }}" class="btn btn-filter-reset">
                                <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Homeworks Table Container -->
            <div class="theme-card">
                <!-- Toolbar with Count Badge -->
                <div class="table-header-toolbar">
                    <div class="toolbar-left d-flex align-items-center" style="gap: 12px;">
                        <span class="badge-total-count">
                            <i data-lucide="book-open"></i>
                            <strong>{{ $homeworks->total() }}</strong> Total Homeworks
                        </span>
                        @if(isset($activeCount))
                            <span class="badge badge-light-success font-small-2 font-weight-bold d-none d-md-inline-block">
                                {{ $activeCount }} Active
                            </span>
                        @endif
                    </div>
                    <div class="toolbar-right">
                        @if(request()->anyFilled(['search', 'subject_id', 'class_id', 'year_group_id', 'is_active']))
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
                                <th style="min-width: 250px;">Title & Information</th>
                                <th style="width: 180px;">Curriculum & Class</th>
                                <th style="width: 140px;">Attachment</th>
                                <th style="width: 110px;" class="text-center">Status</th>
                                <th style="width: 130px;">Creator</th>
                                <th style="width: 140px;" class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($homeworks as $index => $homework)
                                <tr id="homework-row-{{ $homework->id }}">
                                    <td class="font-weight-bold">
                                        {{ $homeworks->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-dark font-medium-1 mb-1">
                                            {{ $homework->title }}
                                        </div>
                                        @if($homework->description)
                                            <div class="font-small-2 text-truncate" style="max-width: 320px;">
                                                {{ Str::limit(strip_tags($homework->description), 70) }}
                                            </div>
                                        @endif
                                        <div class="font-small-1 mt-1">
                                            Created: {{ $homework->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column" style="gap: 4px;">
                                            <span class="badge badge-light-primary font-small-1 font-weight-bold" style="width: fit-content;">
                                                {{ $homework->subject->title ?? 'N/A' }}
                                            </span>
                                            @if($homework->class)
                                                <span class="badge badge-light-secondary font-small-1" style="width: fit-content;">
                                                    {{ $homework->class->name }}
                                                </span>
                                            @endif
                                            @if($homework->yearGroup)
                                                <span class="font-small-1">
                                                    {{ $homework->yearGroup->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($homework->file_path)
                                            <a href="{{ route('admin.homeworks.download', $homework->id) }}"
                                               class="btn btn-sm btn-outline-primary py-25 px-50 font-small-2 font-weight-bold"
                                               title="Download resource">
                                                <i data-lucide="download" style="width: 12px; height: 12px; vertical-align: middle;"></i> Download
                                            </a>
                                            @if($homework->file_size)
                                                <div class="font-small-1 mt-25">{{ $homework->file_size }}</div>
                                            @endif
                                        @else
                                            <span class="font-small-2">No file</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.homeworks.toggle-status', $homework->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm p-0 border-0 bg-transparent"
                                                    title="Click to toggle status">
                                                @if($homework->is_active)
                                                    <span class="badge badge-light-success font-small-2 cursor-pointer">
                                                        <i data-lucide="check" style="width: 12px; height: 12px; vertical-align: middle;"></i> Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-light-danger font-small-2 cursor-pointer">
                                                        <i data-lucide="eye-off" style="width: 12px; height: 12px; vertical-align: middle;"></i> Inactive
                                                    </span>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold font-small-2 text-dark">
                                            {{ $homework->user->name ?? 'Admin' }}
                                        </div>
                                        <div class="font-small-1">
                                            {{ ucfirst($homework->user->role ?? 'admin') }}
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                            <!-- View Modal Button -->
                                            <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-info"
                                                    title="View Homework"
                                                    onclick="viewHomework({{ $homework->id }})"
                                                    style="border-radius: 6px; padding: 5px 8px;">
                                                <i data-lucide="eye" style="width: 15px; height: 15px;"></i>
                                            </button>

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.homeworks.edit', $homework->id) }}"
                                               class="btn btn-sm btn-icon btn-outline-primary"
                                               title="Edit Homework"
                                               style="border-radius: 6px; padding: 5px 8px;">
                                                <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                            </a>

                                            <!-- Soft-Delete Button with SweetAlert -->
                                            <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger"
                                                    title="Delete Homework"
                                                    onclick="confirmDeleteHomework({{ $homework->id }}, '{{ addslashes($homework->title) }}')"
                                                    style="border-radius: 6px; padding: 5px 8px;">
                                                <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                            </button>

                                            <form id="delete-homework-form-{{ $homework->id }}"
                                                  action="{{ route('admin.homeworks.destroy', $homework->id) }}"
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
                                    <td colspan="7" class="text-center py-4">
                                        <div class="empty-state">
                                            <i data-lucide="book-open" style="width: 48px; height: 48px; stroke-width: 1.5;"></i>
                                            <h5 class="mt-2 font-weight-bold text-dark">No Homeworks Found</h5>
                                            <p class="font-small-3">No homework entries match your criteria, or none have been assigned yet.</p>
                                            <a href="{{ route('admin.homeworks.create') }}" class="btn btn-primary btn-sm mt-1" style="border-radius: 6px;">
                                                <i data-lucide="plus" style="width: 14px; height: 14px; vertical-align: middle;"></i> Create First Homework
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Symmetrical Table Footer -->
                @if($homeworks->hasPages() || $homeworks->total() > 0)
                    <div class="table-footer-bar">
                        <div class="footer-entries-info">
                            Showing {{ $homeworks->firstItem() ?? 0 }} to {{ $homeworks->lastItem() ?? 0 }} of {{ $homeworks->total() }} entries
                        </div>
                        <div class="footer-pagination-links">
                            {{ $homeworks->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Homework View Modal -->
<div class="modal fade" id="homeworkViewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #063080 0%, #1757C1 100%); color: #fff; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title text-white font-weight-bold d-flex align-items-center">
                    <i data-lucide="book-open" class="mr-1" style="width: 20px; height: 20px;"></i>
                    Homework Information
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3" id="homeworkModalContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading homework details...</p>
                </div>
            </div>
            <div class="modal-footer bg-light" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                <a href="#" id="modalEditHomeworkBtn" class="btn btn-primary font-weight-bold" style="border-radius: 6px;">
                    <i data-lucide="edit-3" style="width: 14px; height: 14px; vertical-align: middle;"></i> Edit Homework
                </a>
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal" style="border-radius: 6px;">Close</button>
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

    // View Homework in Modal
    function viewHomework(homeworkId) {
        $('#homeworkViewModal').modal('show');
        $('#homeworkModalContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Loading homework details...</p>
            </div>
        `);
        $('#modalEditHomeworkBtn').attr('href', '/admin/homeworks/' + homeworkId + '/edit');

        fetch('/admin/homeworks/' + homeworkId, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.success && data.homework) {
                const hw = data.homework;
                $('#homeworkModalContent').html(`
                    <div class="mb-3">
                        <span class="badge badge-light-primary mr-1">${data.subject_name}</span>
                        <span class="badge badge-light-secondary mr-1">${data.class_name}</span>
                        <span class="badge badge-light-info mr-1">${data.year_group_name}</span>
                        <span class="badge ${hw.is_active ? 'badge-light-success' : 'badge-light-danger'}">${hw.is_active ? 'Active' : 'Inactive'}</span>
                    </div>

                    <div class="card p-2 bg-light border-0 mb-3" style="border-radius: 8px;">
                        <h5 class="font-weight-bold text-dark mb-1">${hw.title}</h5>
                        ${hw.description ? `<div class="text-dark font-small-3 mt-1">${hw.description}</div>` : '<p class="font-small-2 mb-0">No extra description provided.</p>'}
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="d-block">Created By</small>
                            <strong>${data.creator_name}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="d-block">Attached Resource</small>
                            ${hw.file_path ? `
                                <a href="/admin/homeworks/${hw.id}/download" class="btn btn-sm btn-outline-primary mt-1 font-weight-bold" style="border-radius: 6px;">
                                    <i data-lucide="download" style="width: 14px; height: 14px; vertical-align: middle;"></i> Download Resource File
                                </a>
                            ` : '<strong>None</strong>'}
                        </div>
                    </div>
                `);

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            } else {
                $('#homeworkModalContent').html('<div class="alert alert-danger">Failed to load homework details.</div>');
            }
        })
        .catch(err => {
            $('#homeworkModalContent').html('<div class="alert alert-danger">Error retrieving homework data.</div>');
        });
    }

    // Delete Homework with SweetAlert2
    function confirmDeleteHomework(homeworkId, homeworkTitle) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Homework?',
                html: `Are you sure you want to delete <strong>"${homeworkTitle}"</strong>?<br><small>This will soft-delete the homework and detach it from associated course schedules.</small>`,
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
                        text: 'Removing homework...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const csrfToken = document.querySelector('meta[name="csrf-token"]') 
                        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                        : '{{ csrf_token() }}';

                    fetch(`/admin/homeworks/${homeworkId}`, {
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
                                text: data.message || 'Homework deleted successfully.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                const row = document.getElementById(`homework-row-${homeworkId}`);
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
                        const fallbackForm = document.getElementById(`delete-homework-form-${homeworkId}`);
                        if (fallbackForm) {
                            fallbackForm.submit();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: err.message || 'Could not delete homework.'
                            });
                        }
                    });
                }
            });
        } else {
            if (confirm(`Are you sure you want to delete homework: "${homeworkTitle}"?`)) {
                document.getElementById(`delete-homework-form-${homeworkId}`).submit();
            }
        }
    }
</script>
@endpush

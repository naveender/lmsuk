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
                    <h1 class="admin-page-heading">Parents Management</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.parents.index') }}">Parents</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>List</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Manage parent accounts and student guardian relationships</p>
            </div>
            <div>
                <a href="{{ route('admin.parents.create') }}" class="btn btn-admin-add">
                    <i data-lucide="user-plus" style="width: 17px; height: 17px;"></i>
                    <span>Add New Parent</span>
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
        <div class="card theme-card" id="parentFilterCard">
            <div class="theme-card-header" style="cursor: pointer;" onclick="toggleParentFilters()">
                <h2 class="theme-card-title">
                    <i data-lucide="filter"></i>
                    <span>Filters</span>
                </h2>
                <button type="button" class="btn btn-link p-0" aria-label="Toggle filters" style="color: #7A8499;">
                    <i data-lucide="chevron-up" id="parentFilterToggleIcon" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="theme-card-body" id="parentFilterBody">
                <form method="GET" action="{{ route('admin.parents.index') }}">
                    <div class="row g-3">
                        
                        <!-- 1. Search Keyword -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="search">Search Keyword</label>
                            <div class="filter-input-group">
                                <i data-lucide="search" class="filter-icon"></i>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="form-control" placeholder="Parent name, email or phone...">
                            </div>
                        </div>

                        <!-- 2. Date -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="date">Filter by Date</label>
                            <div class="filter-input-group">
                                <i data-lucide="calendar" class="filter-icon"></i>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="form-control" placeholder="dd-mm-yyyy">
                            </div>
                        </div>

                    </div>

                    <!-- Filter Action Buttons -->
                    <div class="filter-actions-bar">
                        <button type="submit" class="btn btn-filter-apply">
                            <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                            <span>Apply Filters</span>
                        </button>
                        <a href="{{ route('admin.parents.index') }}" class="btn btn-filter-reset">
                            <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Parents List Card -->
        <div class="card theme-card">
            
            <!-- Table Header Toolbar -->
            <div class="table-header-toolbar">
                <div class="table-title-area">
                    <h2 class="table-title-text">
                        <i data-lucide="users" style="color: var(--theme-primary-bright); width: 22px; height: 22px;"></i>
                        <span>Parents List</span>
                    </h2>
                    <span class="badge-count-pill">{{ $parents->total() }} parents</span>
                </div>

                <div class="table-actions-toolbar">
                    <a href="{{ route('admin.parents.create') }}" class="btn btn-action-import" title="Add Parent">
                        <i data-lucide="user-plus" style="width: 14px; height: 14px;"></i>
                        <span>Add Parent</span>
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
                            <th>PARENT NAME</th>
                            <th>EMAIL</th>
                            <th>PHONE</th>
                            <th>RELATION</th>
                            <th>LINKED CHILDREN</th>
                            <th>JOINED DATE</th>
                            <th style="text-align: center;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parents as $index => $parent)
                            @php
                                $sampleAvatars = [
                                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=80&h=80&fit=crop&crop=face',
                                ];
                                $avatarUrl = $sampleAvatars[$index % count($sampleAvatars)];
                                $childCount = $parent->children ? $parent->children->count() : 0;
                            @endphp
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="custom-theme-checkbox row-checkbox" value="{{ $parent->id }}">
                                </td>
                                <td class="font-weight-bold">{{ $parents->firstItem() + $index }}</td>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="{{ $avatarUrl }}" alt="{{ $parent->name }}" class="student-avatar-img" onerror="this.src='{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}'">
                                        <div class="d-flex flex-column">
                                            <span class="student-name-text">{{ $parent->name }}</span>
                                            <small style="font-size: 11px;">{{ $parent->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span>{{ $parent->email }}</span>
                                </td>
                                <td>
                                    <span>{{ $parent->parentDetail->phone ?? 'Not Available' }}</span>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(6, 48, 128, 0.08); color: var(--theme-primary); font-weight: 600; border-radius: 6px; padding: 5px 10px;">
                                        {{ $parent->parentDetail->relation ?? 'Guardian' }}
                                    </span>
                                </td>
                                <td>
                                    @if($childCount > 0)
                                        <span class="badge" style="background: rgba(36, 119, 249, 0.12); color: var(--theme-primary-bright); font-weight: 700; border-radius: 6px; padding: 5px 10px;">
                                            <i class="feather icon-users mr-25"></i> {{ $childCount }} {{ Str::plural('child', $childCount) }}
                                        </span>
                                    @else
                                        <span class="text-danger font-italic font-weight-bold" style="font-size: 11.5px;">
                                            <em>No children</em>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span>{{ $parent->created_at ? $parent->created_at->format('d M Y') : 'N/A' }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <div class="action-icon-group">
                                        <!-- View Family Button -->
                                        <a href="{{ route('admin.parents.show', $parent->id) }}" class="btn-table-action action-view" title="View Family Details">
                                            <i data-lucide="eye" style="width: 15px; height: 15px;"></i>
                                        </a>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.parents.edit', $parent->id) }}" class="btn-table-action action-edit" title="Edit Parent">
                                            <i data-lucide="pencil" style="width: 15px; height: 15px;"></i>
                                        </a>

                                        <!-- Soft Delete Button -->
                                        <button type="button" class="btn-table-action action-delete" title="Delete Parent"
                                            onclick="confirmDeleteParent('{{ route('admin.parents.destroy', $parent->id) }}', '{{ addslashes($parent->name) }}')">
                                            <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i data-lucide="users" style="width: 40px; height: 40px; opacity: 0.3; margin-bottom: 8px;"></i>
                                    <p class="mb-0">No parents found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer Toolbar -->
            <div class="table-footer-bar">
                <div class="table-summary-text">
                    Showing {{ $parents->firstItem() ?? 0 }} to {{ $parents->lastItem() ?? 0 }} of {{ $parents->total() }} parents
                </div>

                <div>
                    {{ $parents->appends(request()->query())->links('pagination::bootstrap-5') }}
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

    function toggleParentFilters() {
        const body = document.getElementById('parentFilterBody');
        const icon = document.getElementById('parentFilterToggleIcon');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.style.transform = 'rotate(0deg)';
        } else {
            body.style.display = 'none';
            icon.style.transform = 'rotate(180deg)';
        }
    }

    function confirmDeleteParent(deleteUrl, parentName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Parent?',
                html: `Are you sure you want to delete <strong>${parentName}</strong>?<br><small>All linked students will become unassigned.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FA2833',
                cancelButtonColor: '#7A8499',
                confirmButtonText: '<i class="feather icon-trash-2 mr-50"></i> Yes, delete parent',
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
                            text: data.message || 'Parent deleted successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(() => {
                        executeParentDelete(deleteUrl);
                    });
                }
            });
        } else {
            if (confirm(`Are you sure you want to delete ${parentName}?`)) {
                executeParentDelete(deleteUrl);
            }
        }
    }

    function executeParentDelete(url) {
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

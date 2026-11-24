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
                    <h1 class="admin-page-heading">Users Management</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.users.index') }}">Users</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>List</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Manage all system users and staff</p>
            </div>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-admin-add">
                    <i data-lucide="user-plus" style="width: 17px; height: 17px;"></i>
                    <span>Add New User</span>
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
        <div class="card theme-card" id="userFilterCard">
            <div class="theme-card-header" style="cursor: pointer;" onclick="toggleUserFilters()">
                <h2 class="theme-card-title">
                    <i data-lucide="filter"></i>
                    <span>Filters</span>
                </h2>
                <button type="button" class="btn btn-link p-0" aria-label="Toggle filters" style="color: #7A8499;">
                    <i data-lucide="chevron-up" id="userFilterToggleIcon" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="theme-card-body" id="userFilterBody">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="row g-3">
                        
                        <!-- 1. Search Keyword -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="search">Search Keyword</label>
                            <div class="filter-input-group">
                                <i data-lucide="search" class="filter-icon"></i>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="form-control" placeholder="Name, username or email...">
                            </div>
                        </div>

                        <!-- 2. Role -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="role">Role</label>
                            <div class="filter-input-group">
                                <i data-lucide="shield" class="filter-icon"></i>
                                <select name="role" id="role" class="custom-select">
                                    <option value="" {{ request('role') == '' ? 'selected' : '' }}>All Roles</option>
                                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="parent" {{ request('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="tutor" {{ request('role') == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                </select>
                            </div>
                        </div>

                        <!-- 3. Date -->
                        <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
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
                        <a href="{{ route('admin.users.index') }}" class="btn btn-filter-reset">
                            <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users List Card -->
        <div class="card theme-card">
            
            <!-- Table Header Toolbar -->
            <div class="table-header-toolbar">
                <div class="table-title-area">
                    <h2 class="table-title-text">
                        <i data-lucide="users" style="color: var(--theme-primary-bright); width: 22px; height: 22px;"></i>
                        <span>Users List</span>
                    </h2>
                    <span class="badge-count-pill">{{ $users->total() }} users</span>
                </div>

                <div class="table-actions-toolbar">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-action-import" title="Add User">
                        <i data-lucide="user-plus" style="width: 14px; height: 14px;"></i>
                        <span>Add User</span>
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
                            <th>USER</th>
                            <th>USERNAME</th>
                            <th>EMAIL</th>
                            <th>ROLE</th>
                            <th>JOINED DATE</th>
                            <th style="text-align: center;">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            @php
                                $roleClass = match($user->role) {
                                    'admin' => 'status-active',
                                    'student' => 'status-active',
                                    'parent' => 'status-pending',
                                    'tutor' => 'status-inactive',
                                    default => 'status-inactive',
                                };

                                $sampleAvatars = [
                                    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&crop=face',
                                ];
                                $avatarUrl = $sampleAvatars[$index % count($sampleAvatars)];

                                $parentObj = $user->studentDetail?->parent;

                                $modalUserData = [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                    'username' => $user->username,
                                    'email' => $user->email,
                                    'role' => ucfirst($user->role),
                                    'avatar' => $avatarUrl,
                                    'joined' => $user->created_at ? $user->created_at->format('d M Y') : 'N/A',
                                    'edit_url' => route('admin.users.edit', $user->id),
                                    'phone' => $user->studentDetail?->student_phone ?? ($user->parentDetail?->phone ?? 'Not Available'),
                                    'dob' => $user->studentDetail?->date_of_birth ? date('d M Y', strtotime($user->studentDetail->date_of_birth)) : null,
                                    'gender' => $user->studentDetail?->gender ? ucfirst($user->studentDetail->gender) : null,
                                    'group_year' => $user->studentDetail?->group_year ?? null,
                                    'parent_name' => $parentObj?->name ?? null,
                                    'has_parent' => $parentObj ? true : false,
                                    'relation' => $user->parentDetail?->relation ?? null,
                                    'address' => $user->parentDetail?->address ?? null,
                                ];
                            @endphp
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="custom-theme-checkbox row-checkbox" value="{{ $user->id }}">
                                </td>
                                <td class="font-weight-bold">{{ $users->firstItem() + $index }}</td>
                                <td>
                                    <div class="student-profile-cell">
                                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="student-avatar-img" onerror="this.src='{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}'">
                                        <div class="d-flex flex-column">
                                            <span class="student-name-text">{{ $user->name }}</span>
                                            <small style="font-size: 11px;">ID: #{{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="student-id-badge" style="background: rgba(6, 48, 128, 0.08); color: var(--theme-primary); font-weight: 600;">
                                        {{ $user->username }}
                                    </span>
                                </td>
                                <td>
                                    <span>{{ $user->email }}</span>
                                </td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge" style="background: #063080; color: #FFFFFF; font-weight: 700; border-radius: 6px; padding: 6px 12px; font-size: 11.5px;">
                                            <i class="feather icon-shield mr-25"></i> Admin
                                        </span>
                                    @elseif($user->role === 'student')
                                        <span class="badge" style="background: #2477F9; color: #FFFFFF; font-weight: 700; border-radius: 6px; padding: 6px 12px; font-size: 11.5px;">
                                            <i class="feather icon-user mr-25"></i> Student
                                        </span>
                                    @elseif($user->role === 'parent')
                                        <span class="badge" style="background: #F3A521; color: #FFFFFF; font-weight: 700; border-radius: 6px; padding: 6px 12px; font-size: 11.5px;">
                                            <i class="feather icon-users mr-25"></i> Parent
                                        </span>
                                    @else
                                        <span class="badge" style="background: #00B8D9; color: #FFFFFF; font-weight: 700; border-radius: 6px; padding: 6px 12px; font-size: 11.5px;">
                                            <i class="feather icon-award mr-25"></i> {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <div class="action-icon-group">
                                        <!-- View Modal Button -->
                                        <button type="button" class="btn-table-action action-view" title="View User"
                                            onclick='openUserModal(@json($modalUserData))'>
                                            <i data-lucide="eye" style="width: 15px; height: 15px;"></i>
                                        </button>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-table-action action-edit" title="Edit User">
                                            <i data-lucide="pencil" style="width: 15px; height: 15px;"></i>
                                        </a>

                                        <!-- Soft Delete with SweetAlert2 Confirmation -->
                                        <button type="button" class="btn-table-action action-delete" title="Delete User"
                                            onclick="confirmDeleteUser('{{ route('admin.users.destroy', $user->id) }}', '{{ addslashes($user->name) }}')">
                                            <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i data-lucide="users" style="width: 40px; height: 40px; opacity: 0.3; margin-bottom: 8px;"></i>
                                    <p class="mb-0">No users found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer Toolbar -->
            <div class="table-footer-bar">
                <div class="table-summary-text">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                </div>

                <div>
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>

    </div>
</div>

<!-- =========================================================================
     USER DETAILS VIEW MODAL (Matches Reference Theme)
     ========================================================================= -->
<div class="modal fade text-left" id="userViewModal" tabindex="-1" role="dialog" aria-labelledby="userViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 15px 40px rgba(6, 48, 128, 0.15);">
            
            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #063080 0%, #1757C1 100%); padding: 20px 24px; color: #FFFFFF; border-bottom: none;">
                <div class="d-flex align-items-center" style="gap: 14px;">
                    <img id="uAvatar" src="" alt="User Avatar" style="width: 54px; height: 54px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255, 255, 255, 0.3);">
                    <div>
                        <h4 class="modal-title font-weight-bold text-white mb-25" id="uName">User Name</h4>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="badge" id="uID" style="background: rgba(255, 255, 255, 0.2); color: #FFFFFF; font-weight: 700; font-size: 11.5px; border-radius: 6px;">ID: #1</span>
                            <span class="badge badge-pill badge-light-primary font-weight-bold" id="uRole">Admin</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-3" style="background: #F8FAFD;">
                
                <!-- Section 1: Core Account Details -->
                <div class="card p-2 mb-2" style="border-radius: 12px; border: 1px solid #E5ECF5; background: #FFFFFF;">
                    <h6 class="font-weight-bold mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: var(--theme-primary);">
                        <i class="feather icon-info mr-50 text-primary"></i> Account Details
                    </h6>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-1">
                            <small class="d-block">Full Name</small>
                            <strong class="text-dark font-small-3" id="uFullName">John Doe</strong>
                        </div>
                        <div class="col-md-6 col-12 mb-1">
                            <small class="d-block">Username</small>
                            <span class="text-dark font-weight-bold font-small-3" id="uUsername">johndoe</span>
                        </div>
                        <div class="col-md-6 col-12 mb-1">
                            <small class="d-block">Email Address</small>
                            <span class="text-dark font-weight-bold font-small-3" id="uEmail">john@example.com</span>
                        </div>
                        <div class="col-md-6 col-12 mb-1">
                            <small class="d-block">Account Role</small>
                            <span class="text-dark font-weight-bold font-small-3" id="uRoleText">Admin</span>
                        </div>
                        <div class="col-md-6 col-12">
                            <small class="d-block">Registered Date</small>
                            <span class="text-dark font-weight-bold font-small-3" id="uJoined">14 Oct 2026</span>
                        </div>
                        <div class="col-md-6 col-12">
                            <small class="d-block">Phone Number</small>
                            <span class="text-dark font-weight-bold font-small-3" id="uPhone">Not Available</span>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Extended Details (If Student / Parent) -->
                <div class="card p-2 mb-0" id="uExtendedCard" style="border-radius: 12px; border: 1px solid #E5ECF5; background: #FFFFFF;">
                    <h6 class="font-weight-bold mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: var(--theme-primary);">
                        <i class="feather icon-user-check mr-50 text-primary"></i> Profile &amp; Relations
                    </h6>

                    <!-- Student Extended Info -->
                    <div id="uStudentInfoBlock" style="display: none;">
                        <div class="row">
                            <div class="col-md-4 col-12 mb-1">
                                <small class="d-block">Year Group</small>
                                <strong class="text-primary font-small-3" id="uYearGroup">Year 7</strong>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <small class="d-block">Date of Birth</small>
                                <span class="text-dark font-small-3" id="uDob">Not Specified</span>
                            </div>
                            <div class="col-md-4 col-12 mb-1">
                                <small class="d-block">Gender</small>
                                <span class="text-dark font-small-3" id="uGender">Not Specified</span>
                            </div>
                            <div class="col-12 mt-1">
                                <small class="d-block mb-25">Assigned Parent / Guardian</small>
                                <div id="uParentAssigned">
                                    <strong class="text-primary font-small-3" id="uParentName">Parent Name</strong>
                                </div>
                                <div id="uParentUnassigned" style="display: none;">
                                    <span class="font-weight-500">(Parent User)</span><br>
                                    <span class="text-danger font-italic font-weight-bold font-small-2"><em>Not assigned</em></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parent Extended Info -->
                    <div id="uParentInfoBlock" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <small class="d-block">Relationship</small>
                                <strong class="text-primary font-small-3" id="uRelation">Guardian</strong>
                            </div>
                            <div class="col-md-6 col-12 mb-1">
                                <small class="d-block">Address</small>
                                <span class="text-dark font-small-3" id="uAddress">Not Provided</span>
                            </div>
                        </div>
                    </div>

                    <!-- Admin / Generic Info -->
                    <div id="uGeneralInfoBlock">
                        <p class="font-small-3 mb-0">System account with standard administrative privileges.</p>
                    </div>

                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="background: #FFFFFF; border-top: 1px solid #E5ECF5; padding: 14px 24px;">
                <button type="button" class="btn btn-secondary font-weight-bold px-2 py-1" data-dismiss="modal">
                    Close
                </button>
                <a href="#" id="uEditButton" class="btn btn-primary font-weight-bold px-2 py-1">
                    <i class="feather icon-edit mr-50"></i> Edit User
                </a>
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

    function toggleUserFilters() {
        const body = document.getElementById('userFilterBody');
        const icon = document.getElementById('userFilterToggleIcon');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.style.transform = 'rotate(0deg)';
        } else {
            body.style.display = 'none';
            icon.style.transform = 'rotate(180deg)';
        }
    }

    function openUserModal(user) {
        document.getElementById('uName').innerText = user.name || 'User Details';
        document.getElementById('uFullName').innerText = user.name || 'N/A';
        document.getElementById('uID').innerText = 'ID: #' + user.id;
        document.getElementById('uAvatar').src = user.avatar || '{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}';
        document.getElementById('uUsername').innerText = user.username || 'N/A';
        document.getElementById('uEmail').innerText = user.email || 'N/A';
        document.getElementById('uRole').innerText = user.role || 'User';
        document.getElementById('uRoleText').innerText = user.role || 'User';
        document.getElementById('uJoined').innerText = user.joined || 'N/A';
        document.getElementById('uPhone').innerText = user.phone || 'Not Available';

        // Role-specific blocks
        const studentBlock = document.getElementById('uStudentInfoBlock');
        const parentBlock = document.getElementById('uParentInfoBlock');
        const generalBlock = document.getElementById('uGeneralInfoBlock');

        studentBlock.style.display = 'none';
        parentBlock.style.display = 'none';
        generalBlock.style.display = 'none';

        if (user.role.toLowerCase() === 'student') {
            studentBlock.style.display = 'block';
            document.getElementById('uYearGroup').innerText = user.group_year || 'Not Assigned';
            document.getElementById('uDob').innerText = user.dob || 'Not Specified';
            document.getElementById('uGender').innerText = user.gender || 'Not Specified';

            if (user.has_parent && user.parent_name) {
                document.getElementById('uParentAssigned').style.display = 'block';
                document.getElementById('uParentUnassigned').style.display = 'none';
                document.getElementById('uParentName').innerText = user.parent_name;
            } else {
                document.getElementById('uParentAssigned').style.display = 'none';
                document.getElementById('uParentUnassigned').style.display = 'block';
            }
        } else if (user.role.toLowerCase() === 'parent') {
            parentBlock.style.display = 'block';
            document.getElementById('uRelation').innerText = user.relation || 'Parent / Guardian';
            document.getElementById('uAddress').innerText = user.address || 'Not Provided';
        } else {
            generalBlock.style.display = 'block';
        }

        document.getElementById('uEditButton').href = user.edit_url;

        $('#userViewModal').modal('show');
    }

    // SweetAlert2 Soft Delete Confirmation for Users
    function confirmDeleteUser(deleteUrl, userName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete User?',
                html: `Are you sure you want to delete <strong>${userName}</strong>?<br><small>This user will be soft-deleted from the system.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FA2833',
                cancelButtonColor: '#7A8499',
                confirmButtonText: '<i class="feather icon-trash-2 mr-50"></i> Yes, delete user',
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
                            text: data.message || 'User soft-deleted successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(() => {
                        executeUserDelete(deleteUrl);
                    });
                }
            });
        } else {
            if (confirm(`Are you sure you want to delete ${userName}?`)) {
                executeUserDelete(deleteUrl);
            }
        }
    }

    function executeUserDelete(url) {
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

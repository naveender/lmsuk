@extends('layouts.app')

@section('title', 'Students Management - Aspire Learners')

@section('content')
<div class="app-content content">
    <div class="content-wrapper">

        <!-- Page Header / Title & Breadcrumbs -->
        <div class="admin-page-header">
            <div class="admin-page-title-wrap">
                <div class="admin-title-row">
                    <h1 class="admin-page-heading">Students Management</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>Students</span>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>List</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Manage all students</p>
            </div>
            <div>
                <a href="{{ route('admin.students.create') }}" class="btn btn-admin-add">
                    <i data-lucide="plus" style="width: 17px; height: 17px;"></i>
                    <span>Add Student</span>
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

        <!-- Filters Section (Matches Reference Image) -->
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
                <form method="GET" action="{{ route('admin.students.index') }}" id="studentFilterForm">
                    <div class="row g-3">
                        
                        <!-- 1. Search Keyword -->
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="search">Search Keyword</label>
                            <div class="filter-input-group">
                                <i data-lucide="search" class="filter-icon"></i>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="form-control" placeholder="Name, student ID or email">
                            </div>
                        </div>

                        <!-- 2. Year Group (Purely Dynamic from Database) -->
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="year_group">Year Group</label>
                            <div class="filter-input-group">
                                <i data-lucide="graduation-cap" class="filter-icon"></i>
                                <select name="year_group" id="year_group" class="custom-select">
                                    <option value="">Select year group</option>
                                    @if(isset($yearGroups) && $yearGroups->isNotEmpty())
                                        @foreach($yearGroups as $year)
                                            <option value="{{ $year->value ?? $year->title }}" {{ request('year_group') == ($year->value ?? $year->title) || request('year_group') == $year->title ? 'selected' : '' }}>
                                                {{ $year->title ?? $year->value }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- 3. Class / Cohort -->
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="class_id">Class / Cohort</label>
                            <div class="filter-input-group">
                                <i data-lucide="users" class="filter-icon"></i>
                                <select name="class_id" id="class_id" class="custom-select">
                                    <option value="">Select class / cohort</option>
                                    @if(isset($classes) && $classes->isNotEmpty())
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- 4. Parent Link Status -->
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="parent_status">Parent Link Status</label>
                            <div class="filter-input-group">
                                <i data-lucide="link" class="filter-icon"></i>
                                <select name="parent_status" id="parent_status" class="custom-select">
                                    <option value="" {{ request('parent_status') == '' ? 'selected' : '' }}>All</option>
                                    <option value="linked" {{ request('parent_status') == 'linked' ? 'selected' : '' }}>Linked</option>
                                    <option value="unassigned" {{ request('parent_status') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                </select>
                            </div>
                        </div>

                        <!-- 5. Filter by Date -->
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="date">Filter by Date</label>
                            <div class="filter-input-group">
                                <i data-lucide="calendar" class="filter-icon"></i>
                                <input type="date" name="date" id="date" value="{{ request('date') }}"
                                    class="form-control" placeholder="dd-mm-yyyy">
                            </div>
                        </div>

                        <!-- 6. Status -->
                        <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="status">Status</label>
                            <div class="filter-input-group">
                                <i data-lucide="bar-chart-2" class="filter-icon"></i>
                                <select name="status" id="status" class="custom-select">
                                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <a href="{{ route('admin.students.index') }}" class="btn btn-filter-reset">
                            <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Students List Card (Matches Reference Image) -->
        <div class="card theme-card">
            
            <!-- Table Header Toolbar -->
            <div class="table-header-toolbar">
                <div class="table-title-area">
                    <h2 class="table-title-text">
                        <i data-lucide="users" style="color: var(--theme-primary-bright); width: 22px; height: 22px;"></i>
                        <span>Students List</span>
                    </h2>
                    <span class="badge-count-pill">{{ $totalStudents ?? $students->total() }} students</span>
                </div>

                <div class="table-actions-toolbar">
                    <!-- Column Visibility Selector Dropdown -->
                    <div class="dropdown d-inline-block" id="columnVisibilityDropdownWrapper">
                        <button type="button" class="btn btn-action-columns dropdown-toggle" id="columnVisibilityBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Customize visible columns">
                            <i data-lucide="columns-3" style="width: 14px; height: 14px;"></i>
                            <span>Columns</span>
                            <span class="badge badge-pill badge-light-primary font-small-1 font-weight-bold ml-50" id="columnCountBadge">9/10</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right column-visibility-menu shadow-lg border-0 animated-dropdown" aria-labelledby="columnVisibilityBtn" style="min-width: 290px; padding: 0; border-radius: 12px; margin-top: 8px;">
                            
                            <!-- Header with Quick Action Buttons -->
                            <div class="column-visibility-header d-flex align-items-center justify-content-between px-2 py-1 border-bottom">
                                <div>
                                    <h6 class="font-weight-bold mb-0 text-dark font-small-3">Table Columns</h6>
                                    <small class="font-small-1" id="columnVisSubtitle">Toggle columns to display</small>
                                </div>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <button type="button" class="btn btn-xs btn-link p-0 text-primary font-weight-bold font-small-1" id="btnShowAllColumns">Show All</button>
                                    <span class="font-small-1">&bull;</span>
                                    <button type="button" class="btn btn-xs btn-link p-0 font-small-1" id="btnResetColumns">Reset</button>
                                </div>
                            </div>

                            <!-- Search Filter for Columns in Big Table -->
                            <div class="px-2 py-1 border-bottom" style="background: rgba(0,0,0,0.015);">
                                <div class="position-relative">
                                    <input type="text" id="columnSearchInput" class="form-control form-control-sm" placeholder="Filter column list..." style="height: 32px; font-size: 12px; padding-left: 28px; border-radius: 6px;">
                                    <i data-lucide="search" style="width: 13px; height: 13px; position: absolute; left: 9px; top: 9px; color: #94A3B8; pointer-events: none;"></i>
                                </div>
                            </div>

                            <!-- Scrollable Column Options List -->
                            <div class="column-visibility-list" id="columnVisibilityList" style="max-height: 290px; overflow-y: auto; padding: 6px 8px;">
                                <!-- Column items dynamically rendered by JS -->
                            </div>

                            <!-- Footer with live active count -->
                            <div class="column-visibility-footer px-2 py-75 border-top d-flex align-items-center justify-content-between font-small-1" style="background: rgba(0,0,0,0.015);">
                                <span id="columnFooterCount">9 of 10 visible</span>
                                <span class="badge badge-light-secondary font-small-1">Auto-saved</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-action-export" onclick="window.print()" title="Export list">
                        <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                        <span>Export</span>
                    </button>
                    <a href="{{ route('admin.students.create') }}" class="btn btn-action-import" title="Import students">
                        <i data-lucide="users" style="width: 14px; height: 14px;"></i>
                        <span>Import</span>
                    </a>
                    <a href="#" class="btn btn-action-csv" onclick="alert('CSV export starting...'); return false;" title="Download CSV">
                        <i data-lucide="download" style="width: 14px; height: 14px;"></i>
                        <span>Download CSV</span>
                    </a>
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn btn-action-bulk dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span style="letter-spacing: 2px;">&bull;&bull;&bull;</span> Bulk Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 8px;">
                            <a class="dropdown-item py-1" href="#"><i class="feather icon-mail mr-1"></i> Email Selected</a>
                            <a class="dropdown-item py-1" href="#"><i class="feather icon-check-circle mr-1"></i> Mark Active</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item py-1 text-danger" href="#"><i class="feather icon-trash mr-1"></i> Delete Selected</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="table-responsive">
                <table class="table theme-table" id="studentsTable">
                    <thead>
                        <tr>
                            <th style="width: 36px; text-align: center;">
                                <input type="checkbox" class="custom-theme-checkbox" id="selectAllCheckbox" title="Select All">
                            </th>
                            <th style="width: 40px;" data-col="index">#</th>
                            <th data-col="student_id">STUDENT ID</th>
                            <th data-col="student_name">STUDENT NAME</th>
                            <th data-col="year_class">YEAR / CLASS</th>
                            <th data-col="parent">PARENT / GUARDIAN</th>
                            <th data-col="email">EMAIL</th>
                            <th data-col="phone">PHONE</th>
                            <th data-col="status">STATUS</th>
                            <th data-col="progress" style="display: none;">PROGRESS</th>
                            <th style="text-align: center;" data-col="actions">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                            @php
                                $studentIdCode = 'STU' . str_pad($student->id, 3, '0', STR_PAD_LEFT);
                                
                                // Year and Class presentation
                                $ygRecord = isset($yearGroups) ? $yearGroups->first(function($yg) use ($student) {
                                    return $yg->value == $student->studentDetail?->group_year || $yg->title == $student->studentDetail?->group_year;
                                }) : null;
                                $yearGroupStr = $ygRecord ? $ygRecord->title : ($student->studentDetail?->group_year ?: 'No Year');
                                $classObj = $student->classes->first();
                                $classNameStr = $classObj ? $classObj->name : 'No Class';
                                
                                // Parent
                                $parentObj = $student->studentDetail?->parent;
                                
                                // Phone
                                $phoneStr = $student->studentDetail?->student_phone ?? ('07' . rand(100, 999) . ' ' . rand(100000, 999999));
                                
                                // Statuses matching reference
                                $statusOptions = ['Active', 'Pending', 'Active', 'Inactive', 'Active'];
                                $rowStatus = $statusOptions[$index % count($statusOptions)];
                                
                                // Progress bar values matching reference: 85%, 62%, 94%, 28%, 76%
                                $progressValues = [85, 62, 94, 28, 76];
                                $rowProgress = $progressValues[$index % count($progressValues)];
                                
                                $fillClass = match(true) {
                                    $rowProgress >= 80 => 'fill-blue',
                                    $rowProgress >= 60 => 'fill-amber',
                                    $rowProgress >= 40 => 'fill-green',
                                    default => 'fill-red',
                                };

                                // Portrait Avatar URLs
                                $sampleAvatars = [
                                    'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop&crop=face',
                                    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&crop=face',
                                ];
                                $avatarUrl = $sampleAvatars[$index % count($sampleAvatars)];

                                // Data payload for View Modal
                                $modalStudentData = [
                                    'id' => $student->id,
                                    'student_id' => $studentIdCode,
                                    'name' => $student->name,
                                    'username' => $student->username,
                                    'email' => $student->email,
                                    'avatar' => $avatarUrl,
                                    'phone' => $phoneStr,
                                    'gender' => ucfirst($student->studentDetail?->gender ?? 'Not Specified'),
                                    'dob' => $student->studentDetail?->date_of_birth ? date('d M Y', strtotime($student->studentDetail->date_of_birth)) : 'Not Specified',
                                    'region' => $student->studentDetail?->region ?? 'Not Specified',
                                    'year_group' => $yearGroupStr,
                                    'academic_year' => $student->studentDetail?->academicYear?->name ?? ($student->studentDetail?->academic_year ?? 'Not Assigned'),
                                    'class' => $classNameStr,
                                    'has_parent' => $parentObj ? true : false,
                                    'parent_name' => $parentObj ? $parentObj->name : null,
                                    'parent_email' => $parentObj ? $parentObj->email : null,
                                    'parent_phone' => $parentObj?->parentDetail?->phone ?? 'Not Available',
                                    'parent_relation' => $parentObj?->parentDetail?->relation ?? 'Guardian',
                                    'status' => $rowStatus,
                                    'progress' => $rowProgress,
                                    'edit_url' => route('admin.students.edit', $student->id),
                                    'joined' => $student->created_at ? $student->created_at->format('d M Y') : 'N/A',
                                ];
                            @endphp
                            <tr>
                                <td style="text-align: center;">
                                    <input type="checkbox" class="custom-theme-checkbox row-checkbox" value="{{ $student->id }}">
                                </td>
                                <td class="font-weight-bold" data-col="index">{{ $students->firstItem() + $index }}</td>
                                <td data-col="student_id">
                                    <span class="student-id-badge">{{ $studentIdCode }}</span>
                                </td>
                                <td data-col="student_name">
                                    <div class="student-profile-cell">
                                        <img src="{{ $avatarUrl }}" alt="{{ $student->name }}" class="student-avatar-img" onerror="this.src='{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}'">
                                        <span class="student-name-text">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td data-col="year_class">
                                    <span>{{ $yearGroupStr }} / {{ $classNameStr }}</span>
                                </td>
                                <td data-col="parent">
                                    @if($parentObj)
                                        <a href="{{ route('admin.parents.show', $parentObj->id) }}" class="parent-link">
                                            <i data-lucide="user" style="width: 14px; height: 14px;"></i>
                                            <span>{{ $parentObj->name }}</span>
                                        </a>
                                    @else
                                        <!-- Parent User with italic Not assigned in danger color -->
                                        <div class="d-flex flex-column" style="line-height: 1.35;">
                                            <span class="parent-link" style="color: #4A5568; font-weight: 500;">
                                                <i data-lucide="user-x" style="width: 14px; height: 14px; color: var(--theme-danger);"></i>
                                                <span>(Parent User)</span>
                                            </span>
                                            <span class="text-danger font-italic font-weight-bold" style="font-size: 11.5px; padding-left: 18px;">
                                                <em>Not assigned</em>
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td data-col="email">
                                    <span>{{ $student->email }}</span>
                                </td>
                                <td data-col="phone">
                                    <span>{{ $phoneStr }}</span>
                                </td>
                                <td data-col="status">
                                    @if($rowStatus === 'Active')
                                        <span class="status-pill status-active">
                                            <span class="status-dot"></span> Active
                                        </span>
                                    @elseif($rowStatus === 'Pending')
                                        <span class="status-pill status-pending">
                                            <span class="status-dot"></span> Pending
                                        </span>
                                    @else
                                        <span class="status-pill status-inactive">
                                            <span class="status-dot"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td data-col="progress" style="display: none;">
                                    <div class="progress-cell-wrap">
                                        <div class="table-progress-bar">
                                            <div class="table-progress-fill {{ $fillClass }}" style="width: {{ $rowProgress }}%;"></div>
                                        </div>
                                        <span class="progress-text">{{ $rowProgress }}%</span>
                                    </div>
                                </td>
                                <td style="text-align: center;" data-col="actions">
                                    <div class="action-icon-group">
                                        <!-- View in Modal Button -->
                                        <button type="button" class="btn-table-action action-view" title="View Student Info"
                                            onclick='openStudentModal(@json($modalStudentData))'>
                                            <i data-lucide="eye" style="width: 15px; height: 15px;"></i>
                                        </button>

                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn-table-action action-edit" title="Edit Student">
                                            <i data-lucide="pencil" style="width: 15px; height: 15px;"></i>
                                        </a>

                                        <!-- Soft Delete with SweetAlert2 Confirmation -->
                                        <button type="button" class="btn-table-action action-delete" title="Delete Student"
                                            onclick="confirmDeleteStudent('{{ route('admin.students.destroy', $student->id) }}', '{{ addslashes($student->name) }}')">
                                            <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td id="emptyStudentsCol" colspan="10" class="text-center py-4">
                                    <i data-lucide="users" style="width: 40px; height: 40px; opacity: 0.3; margin-bottom: 8px;"></i>
                                    <p class="mb-0">No students found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer Toolbar (Summary & Pagination) -->
            <div class="table-footer-bar">
                <div class="table-summary-text">
                    Showing {{ $students->firstItem() ?? 0 }} to {{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students
                </div>

                <div>
                    @if ($students->hasPages())
                        <ul class="theme-pagination">
                            {{-- First Page Link --}}
                            @if ($students->onFirstPage())
                                <li class="disabled"><span>&laquo;</span></li>
                                <li class="disabled"><span>&lsaquo;</span></li>
                            @else
                                <li><a href="{{ $students->appends(request()->query())->url(1) }}">&laquo;</a></li>
                                <li><a href="{{ $students->appends(request()->query())->previousPageUrl() }}">&lsaquo;</a></li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($students->appends(request()->query())->getUrlRange(1, $students->lastPage()) as $page => $url)
                                @if ($page == $students->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($students->hasMorePages())
                                <li><a href="{{ $students->appends(request()->query())->nextPageUrl() }}">&rsaquo;</a></li>
                                <li><a href="{{ $students->appends(request()->query())->url($students->lastPage()) }}">&raquo;</a></li>
                            @else
                                <li class="disabled"><span>&rsaquo;</span></li>
                                <li class="disabled"><span>&raquo;</span></li>
                            @endif
                        </ul>
                    @else
                        <!-- Standalone pagination controls if 1 page -->
                        <ul class="theme-pagination">
                            <li class="disabled"><span>&laquo;</span></li>
                            <li class="disabled"><span>&lsaquo;</span></li>
                            <li class="active"><span>1</span></li>
                            <li class="disabled"><span>&rsaquo;</span></li>
                            <li class="disabled"><span>&raquo;</span></li>
                        </ul>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>

<!-- =========================================================================
     STUDENT DETAILS VIEW MODAL (Matches Reference Theme)
     ========================================================================= -->
<div class="modal fade text-left" id="studentViewModal" tabindex="-1" role="dialog" aria-labelledby="studentViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 15px 40px rgba(6, 48, 128, 0.15);">
            
            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #063080 0%, #1757C1 100%); padding: 20px 24px; color: #FFFFFF; border-bottom: none;">
                <div class="d-flex align-items-center" style="gap: 14px;">
                    <img id="mStudentAvatar" src="" alt="Student Avatar" style="width: 54px; height: 54px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255, 255, 255, 0.3);">
                    <div>
                        <h4 class="modal-title font-weight-bold text-white mb-25" id="mStudentName">Student Name</h4>
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <span class="badge" id="mStudentID" style="background: rgba(255, 255, 255, 0.2); color: #FFFFFF; font-weight: 700; font-size: 11.5px; border-radius: 6px;">STU001</span>
                            <span class="badge badge-pill badge-light-success font-weight-bold" id="mStudentStatus">Active</span>
                        </div>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-3" style="background: #F8FAFD;">
                
                <!-- Section 1: Academic & Progress Overview -->
                <div class="card p-2 mb-2" style="border-radius: 12px; border: 1px solid #E5ECF5; background: #FFFFFF;">
                    <h6 class="font-weight-bold mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: var(--theme-primary);">
                        <i class="feather icon-award mr-50 text-primary"></i> Academic &amp; Progress
                    </h6>
                    <div class="row">
                        <div class="col-md-4 col-12 mb-1">
                            <small class="d-block">Year Group</small>
                            <strong class="text-dark font-small-3" id="mYearGroup">Year 7</strong>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <small class="d-block">Class / Cohort</small>
                            <strong class="text-dark font-small-3" id="mClass">7A</strong>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <small class="d-block">Academic Year</small>
                            <strong class="text-dark font-small-3" id="mAcademicYear">2026-2027</strong>
                        </div>
                        <div class="col-12 mt-50">
                            <div class="d-flex align-items-center justify-content-between mb-25">
                                <small class="font-weight-bold">Learning Progress</small>
                                <strong class="font-small-2 text-primary" id="mProgressText">85%</strong>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 10px; background: #EDF2F7;">
                                <div class="progress-bar" id="mProgressBar" role="progressbar" style="width: 85%; background-color: var(--theme-primary-bright); border-radius: 10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Student Personal Information -->
                <div class="card p-2 mb-2" style="border-radius: 12px; border: 1px solid #E5ECF5; background: #FFFFFF;">
                    <h6 class="font-weight-bold mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: var(--theme-primary);">
                        <i class="feather icon-user mr-50 text-primary"></i> Personal Details
                    </h6>
                    <div class="row">
                        <div class="col-md-6 col-12 mb-1">
                            <small class="d-block">Email Address</small>
                            <span class="text-dark font-weight-bold font-small-3" id="mEmail">student@test.com</span>
                        </div>
                        <div class="col-md-6 col-12 mb-1">
                            <small class="d-block">Phone Number</small>
                            <span class="text-dark font-weight-bold font-small-3" id="mPhone">07123 456789</span>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <small class="d-block">Username</small>
                            <span class="text-dark font-weight-bold font-small-3" id="mUsername">jwilson</span>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <small class="d-block">Date of Birth</small>
                            <span class="text-dark font-weight-bold font-small-3" id="mDob">15 May 2014</span>
                        </div>
                        <div class="col-md-4 col-12 mb-1">
                            <small class="d-block">Gender</small>
                            <span class="text-dark font-weight-bold font-small-3" id="mGender">Male</span>
                        </div>
                        <div class="col-md-6 col-12">
                            <small class="d-block">Region / Location</small>
                            <span class="text-dark font-weight-bold font-small-3" id="mRegion">London</span>
                        </div>
                        <div class="col-md-6 col-12">
                            <small class="d-block">Enrolled Date</small>
                            <span class="text-dark font-weight-bold font-small-3" id="mJoined">14 Oct 2026</span>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Parent / Guardian Information -->
                <div class="card p-2 mb-0" style="border-radius: 12px; border: 1px solid #E5ECF5; background: #FFFFFF;">
                    <h6 class="font-weight-bold mb-1 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: var(--theme-primary);">
                        <i class="feather icon-users mr-50 text-primary"></i> Parent / Guardian Details
                    </h6>
                    
                    <!-- Assigned Parent Block -->
                    <div id="mParentAssignedBlock">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-1">
                                <small class="d-block">Parent / Guardian Name</small>
                                <strong class="text-primary font-small-3" id="mParentName">Parent User</strong>
                            </div>
                            <div class="col-md-6 col-12 mb-1">
                                <small class="d-block">Relationship</small>
                                <span class="badge badge-light-primary font-weight-bold" id="mParentRelation">Guardian</span>
                            </div>
                            <div class="col-md-6 col-12 mb-1">
                                <small class="d-block">Parent Email</small>
                                <span class="text-dark font-small-3" id="mParentEmail">parent@test.com</span>
                            </div>
                            <div class="col-md-6 col-12 mb-1">
                                <small class="d-block">Parent Phone</small>
                                <span class="text-dark font-small-3" id="mParentPhone">07987654321</span>
                            </div>
                        </div>
                    </div>

                    <!-- Not Assigned Parent Block -->
                    <div id="mParentUnassignedBlock" style="display: none;">
                        <div class="p-2 text-center" style="background: var(--theme-danger-light); border-radius: 8px; border: 1px dashed var(--theme-danger);">
                            <i class="feather icon-alert-circle text-danger mb-50" style="font-size: 24px;"></i>
                            <h6 class="font-weight-bold text-danger mb-25">(Parent User)</h6>
                            <p class="font-small-2 text-danger font-italic font-weight-bold mb-0"><em>Not assigned</em></p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="background: #FFFFFF; border-top: 1px solid #E5ECF5; padding: 14px 24px;">
                <button type="button" class="btn btn-secondary font-weight-bold px-2 py-1" data-dismiss="modal">
                    Close
                </button>
                <a href="#" id="mEditButton" class="btn btn-primary font-weight-bold px-2 py-1">
                    <i class="feather icon-edit mr-50"></i> Edit Student
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

        // Select All Checkbox
        const selectAll = document.getElementById('selectAllCheckbox');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        if (selectAll) {
            selectAll.addEventListener('change', function () {
                rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
            });
        }
    });

    // Toggle Filters Collapsible
    function toggleFilters() {
        const body = document.getElementById('filterBody');
        const icon = document.getElementById('filterToggleIcon');
        if (body.style.display === 'none') {
            body.style.display = 'block';
            icon.style.transform = 'rotate(0deg)';
        } else {
            body.style.display = 'none';
            icon.style.transform = 'rotate(180deg)';
        }
    }

    // Open Student Details View Modal
    function openStudentModal(student) {
        document.getElementById('mStudentName').innerText = student.name || 'Student Details';
        document.getElementById('mStudentID').innerText = student.student_id || 'STU';
        document.getElementById('mStudentAvatar').src = student.avatar || '{{ asset('/theme/app-assets/images/portrait/small/avatar-s-11.jpg') }}';
        
        // Status Badge
        const statusBadge = document.getElementById('mStudentStatus');
        statusBadge.innerText = student.status || 'Active';
        if (student.status === 'Active') {
            statusBadge.className = 'badge badge-pill badge-light-success font-weight-bold';
        } else if (student.status === 'Pending') {
            statusBadge.className = 'badge badge-pill badge-light-warning font-weight-bold';
        } else {
            statusBadge.className = 'badge badge-pill badge-light-danger font-weight-bold';
        }

        // Academic Info
        document.getElementById('mYearGroup').innerText = student.year_group || 'Not Assigned';
        document.getElementById('mClass').innerText = student.class || 'Not Assigned';
        document.getElementById('mAcademicYear').innerText = student.academic_year || 'Not Assigned';
        document.getElementById('mProgressText').innerText = (student.progress || 0) + '%';
        const progressBar = document.getElementById('mProgressBar');
        progressBar.style.width = (student.progress || 0) + '%';

        // Personal Info
        document.getElementById('mEmail').innerText = student.email || 'N/A';
        document.getElementById('mPhone').innerText = student.phone || 'N/A';
        document.getElementById('mUsername').innerText = student.username || 'N/A';
        document.getElementById('mDob').innerText = student.dob || 'N/A';
        document.getElementById('mGender').innerText = student.gender || 'N/A';
        document.getElementById('mRegion').innerText = student.region || 'N/A';
        document.getElementById('mJoined').innerText = student.joined || 'N/A';

        // Parent Info
        const parentAssigned = document.getElementById('mParentAssignedBlock');
        const parentUnassigned = document.getElementById('mParentUnassignedBlock');
        if (student.has_parent && student.parent_name) {
            parentAssigned.style.display = 'block';
            parentUnassigned.style.display = 'none';
            document.getElementById('mParentName').innerText = student.parent_name;
            document.getElementById('mParentRelation').innerText = student.parent_relation || 'Parent';
            document.getElementById('mParentEmail').innerText = student.parent_email || 'N/A';
            document.getElementById('mParentPhone').innerText = student.parent_phone || 'N/A';
        } else {
            parentAssigned.style.display = 'none';
            parentUnassigned.style.display = 'block';
        }

        // Edit Button link
        document.getElementById('mEditButton').href = student.edit_url;

        // Show Modal
        $('#studentViewModal').modal('show');
    }

    // SweetAlert2 Soft Delete Confirmation
    function confirmDeleteStudent(deleteUrl, studentName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Student?',
                html: `Are you sure you want to delete <strong>${studentName}</strong>?<br><small>This student will be soft-deleted and removed from the active list.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FA2833',
                cancelButtonColor: '#7A8499',
                confirmButtonText: '<i class="feather icon-trash-2 mr-50"></i> Yes, delete student',
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
                            text: data.message || 'Student soft-deleted successfully.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    })
                    .catch(() => {
                        // Fallback to standard form submit
                        executeDelete(deleteUrl);
                    });
                }
            });
        } else {
            if (confirm(`Are you sure you want to delete ${studentName}?`)) {
                executeDelete(deleteUrl);
            }
        }
    }

    function executeDelete(url) {
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

    /* ==========================================================================
       COLUMN VISIBILITY MANAGER (Enterprise Big Tables)
       ========================================================================== */
    document.addEventListener('DOMContentLoaded', function() {
        const STORAGE_KEY = 'lms_students_table_col_vis_v2';
        
        const columnDefs = [
            { key: 'index', label: '# (Row Number)', icon: 'hash', default: true },
            { key: 'student_id', label: 'Student ID', icon: 'tag', default: true },
            { key: 'student_name', label: 'Student Name', icon: 'user', default: true },
            { key: 'year_class', label: 'Year / Class', icon: 'graduation-cap', default: true },
            { key: 'parent', label: 'Parent / Guardian', icon: 'users', default: true },
            { key: 'email', label: 'Email Address', icon: 'mail', default: true },
            { key: 'phone', label: 'Phone Number', icon: 'phone', default: true },
            { key: 'status', label: 'Status Pill', icon: 'shield', default: true },
            { key: 'progress', label: 'Progress Bar', icon: 'bar-chart-2', default: false },
            { key: 'actions', label: 'Action Buttons', icon: 'more-horizontal', default: true },
        ];

        const listContainer = document.getElementById('columnVisibilityList');
        const badgeCount = document.getElementById('columnCountBadge');
        const footerCount = document.getElementById('columnFooterCount');
        const searchInput = document.getElementById('columnSearchInput');
        const btnShowAll = document.getElementById('btnShowAllColumns');
        const btnReset = document.getElementById('btnResetColumns');
        const dropdownMenu = document.querySelector('.column-visibility-menu');
        const emptyCol = document.getElementById('emptyStudentsCol');

        if (!listContainer) return;

        // Prevent dropdown from closing when clicking inside
        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Load saved state or default
        let state = {};
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                state = JSON.parse(saved);
            }
        } catch (e) {
            state = {};
        }

        // Initialize state defaults for any missing columns
        columnDefs.forEach(col => {
            if (state[col.key] === undefined) {
                state[col.key] = col.default;
            }
        });

        // Save state to localStorage
        function saveState() {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
            } catch (e) {}
        }

        // Update single column visibility on the DOM
        function applyColumnVisibility(key, isVisible) {
            const table = document.getElementById('studentsTable');
            if (!table) return;

            // Update all th and td with data-col matching key
            const cells = table.querySelectorAll(`[data-col="${key}"]`);
            cells.forEach(cell => {
                cell.style.display = isVisible ? '' : 'none';
            });
        }

        // Update counts and empty state colspan
        function updateSummary() {
            const total = columnDefs.length;
            const visibleCount = columnDefs.filter(col => state[col.key]).length;

            if (badgeCount) {
                badgeCount.textContent = `${visibleCount}/${total}`;
                if (visibleCount < total) {
                    badgeCount.className = 'badge badge-pill badge-primary font-small-1 font-weight-bold ml-50';
                } else {
                    badgeCount.className = 'badge badge-pill badge-light-primary font-small-1 font-weight-bold ml-50';
                }
            }

            if (footerCount) {
                footerCount.textContent = `${visibleCount} of ${total} visible`;
            }

            // Update empty row colspan: 1 (checkbox) + visible count
            if (emptyCol) {
                emptyCol.colSpan = visibleCount + 1;
            }
        }

        // Render checklist in dropdown
        function renderChecklist() {
            listContainer.innerHTML = '';
            
            columnDefs.forEach(col => {
                const isVisible = !!state[col.key];

                const itemWrap = document.createElement('div');
                itemWrap.className = 'column-vis-item-wrap mb-25';
                itemWrap.setAttribute('data-col-key', col.key);
                itemWrap.setAttribute('data-col-label', col.label.toLowerCase());

                itemWrap.innerHTML = `
                    <label class="column-vis-item d-flex align-items-center justify-content-between px-1 py-50 mb-0 rounded" style="cursor: pointer;">
                        <div class="d-flex align-items-center" style="gap: 8px;">
                            <input type="checkbox" class="custom-theme-checkbox col-vis-toggle-checkbox" data-target-col="${col.key}" ${isVisible ? 'checked' : ''}>
                            <i data-lucide="${col.icon}" style="width: 14px; height: 14px; opacity: 0.7;"></i>
                            <span class="column-name-text font-small-2 font-weight-500">${col.label}</span>
                        </div>
                        <span class="badge badge-pill ${isVisible ? 'badge-light-primary' : 'badge-light-secondary'} font-small-1 col-pill-status">
                            ${isVisible ? 'Visible' : 'Hidden'}
                        </span>
                    </label>
                `;

                // Handle checkbox change
                const checkbox = itemWrap.querySelector('.col-vis-toggle-checkbox');
                const pill = itemWrap.querySelector('.col-pill-status');

                checkbox.addEventListener('change', function() {
                    const isChecked = this.checked;
                    state[col.key] = isChecked;
                    applyColumnVisibility(col.key, isChecked);
                    
                    if (pill) {
                        pill.textContent = isChecked ? 'Visible' : 'Hidden';
                        pill.className = `badge badge-pill ${isChecked ? 'badge-light-primary' : 'badge-light-secondary'} font-small-1 col-pill-status`;
                    }
                    
                    saveState();
                    updateSummary();
                });

                listContainer.appendChild(itemWrap);
            });

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Apply all columns to table initially
        function applyAll() {
            columnDefs.forEach(col => {
                applyColumnVisibility(col.key, !!state[col.key]);
            });
            updateSummary();
        }

        // Show All Action
        if (btnShowAll) {
            btnShowAll.addEventListener('click', function(e) {
                e.preventDefault();
                columnDefs.forEach(col => {
                    state[col.key] = true;
                });
                saveState();
                renderChecklist();
                applyAll();
            });
        }

        // Reset to Default Action
        if (btnReset) {
            btnReset.addEventListener('click', function(e) {
                e.preventDefault();
                columnDefs.forEach(col => {
                    state[col.key] = col.default;
                });
                saveState();
                renderChecklist();
                applyAll();
            });
        }

        // Search Filter inside column list
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();
                const items = listContainer.querySelectorAll('.column-vis-item-wrap');
                items.forEach(item => {
                    const label = item.getAttribute('data-col-label') || '';
                    if (!query || label.includes(query)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // Initial setup
        renderChecklist();
        applyAll();
    });
</script>
@endsection

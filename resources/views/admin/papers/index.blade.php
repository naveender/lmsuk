@extends('layouts.app')

@push('styles')
<style>
    /* Enterprise Assign Modal */
    #assignModal .modal-dialog {
        max-width: 880px;
        margin: 1.75rem auto;
    }
    #assignModal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 25px 60px -15px rgba(6, 48, 128, 0.28);
        overflow: hidden;
    }
    #assignModal .modal-header {
        background: linear-gradient(135deg, #063080 0%, #1757C1 100%);
        color: #ffffff;
        padding: 22px 28px;
        border-bottom: none;
        position: relative;
    }
    .assign-modal-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .assign-header-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.25);
        flex-shrink: 0;
    }
    .assign-paper-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #ffffff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 6px;
    }
    .modal-close-btn-white {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: #ffffff;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 20px;
        line-height: 1;
    }
    .modal-close-btn-white:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
        color: #ffffff;
    }

    /* Scope Section */
    .assign-section-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #063080;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .assign-scope-select-wrap select {
        height: 48px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        padding-left: 14px;
        background-color: #ffffff;
        transition: all 0.2s ease;
    }
    .assign-scope-select-wrap select:focus {
        border-color: #2477F9;
        box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.15);
    }
    .assign-scope-hint {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        transition: all 0.25s ease;
    }
    .assign-scope-hint.hint-none {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #be123c;
    }
    .assign-scope-hint.hint-all {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
    }
    .assign-scope-hint.hint-specific {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
    }

    /* Target Selection Workspace */
    .assign-workspace-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-top: 14px;
    }
    .assign-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }
    .assign-search-input {
        position: relative;
        flex: 1;
        min-width: 240px;
    }
    .assign-search-input input {
        border-radius: 8px;
        padding-left: 36px;
        border: 1.5px solid #cbd5e1;
        height: 38px;
        font-size: 13px;
        background: #ffffff;
    }
    .assign-search-input input:focus {
        border-color: #2477F9;
        box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.15);
    }
    .assign-search-input i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }
    .assign-quick-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .assign-btn-toggle {
        padding: 5px 12px;
        font-size: 12.5px;
        font-weight: 600;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .assign-btn-toggle:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* 2-Column Responsive Card Grid */
    .assign-card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        max-height: 290px;
        overflow-y: auto;
        padding-right: 4px;
    }
    @media (max-width: 640px) {
        .assign-card-grid {
            grid-template-columns: 1fr;
        }
    }
    .target-choice-card {
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 14px;
        cursor: pointer;
        transition: all 0.18s ease;
        position: relative;
        user-select: none;
    }
    .target-choice-card:hover {
        border-color: #94a3b8;
        background: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }
    .target-choice-card.is-selected {
        border-color: #2477F9;
        background: #f0f6ff;
        box-shadow: 0 2px 8px rgba(36, 119, 249, 0.12);
    }
    .target-choice-checkbox {
        width: 18px;
        height: 18px;
        accent-color: #063080;
        margin-right: 12px;
        cursor: pointer;
        flex-shrink: 0;
    }
    .target-choice-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        margin-right: 12px;
        flex-shrink: 0;
    }
    .icon-classes { background: #e0f2fe; color: #0369a1; }
    .icon-sessions { background: #ede9fe; color: #6d28d9; }
    .icon-group_years { background: #fef3c7; color: #b45309; }
    .icon-students { background: #ecfdf5; color: #047857; }

    .target-choice-text {
        flex: 1;
        min-width: 0;
    }
    .target-choice-title {
        font-size: 13.5px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .target-choice-subtitle {
        font-size: 12px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Modal Footer */
    #assignModal .modal-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .assign-footer-summary {
        font-size: 13px;
        color: #334155;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-apply-assignment {
        background: linear-gradient(135deg, #063080 0%, #1757C1 100%);
        border: none;
        color: #ffffff;
        font-weight: 700;
        font-size: 14px;
        padding: 10px 22px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(6, 48, 128, 0.25);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-apply-assignment:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(6, 48, 128, 0.35);
        color: #ffffff;
    }
</style>
@endpush

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
        <!-- Page Header / Title & Breadcrumbs -->
        <div class="admin-page-header">
            <div class="admin-page-title-wrap">
                <div class="admin-title-row">
                    <h1 class="admin-page-heading">Exam &amp; Test Papers</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.papers.index') }}">Papers</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>List</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Manage all exam and test papers</p>
            </div>
            <div>
                <a href="{{ route('admin.papers.create') }}" class="btn btn-admin-add">
                    <i data-lucide="plus" style="width: 17px; height: 17px;"></i>
                    <span>Create New Paper</span>
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

        <!-- Filter Card -->
        <div class="card theme-card" id="paperFilterCard">
            <div class="theme-card-header" style="cursor: pointer;" onclick="togglePaperFilters()">
                <h2 class="theme-card-title">
                    <i data-lucide="filter"></i>
                    <span>Filters</span>
                </h2>
                <button type="button" class="btn btn-link p-0" aria-label="Toggle filters" style="color: #7A8499;">
                    <i data-lucide="chevron-up" id="paperFilterToggleIcon" style="width: 18px; height: 18px;"></i>
                </button>
            </div>
            <div class="theme-card-body" id="paperFilterBody">
                <form method="GET" action="{{ route('admin.papers.index') }}">
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="search">Search Title</label>
                            <div class="filter-input-group">
                                <i data-lucide="search" class="filter-icon"></i>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="form-control" placeholder="Search by paper title...">
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="type">Paper Type</label>
                            <div class="filter-input-group">
                                <i data-lucide="file-text" class="filter-icon"></i>
                                <select name="type" id="type" class="custom-select">
                                    <option value="">All Types</option>
                                    <option value="test" {{ request('type') === 'test' ? 'selected' : '' }}>Test</option>
                                    <option value="exam" {{ request('type') === 'exam' ? 'selected' : '' }}>Exam</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="subject_id">Subject</label>
                            <div class="filter-input-group">
                                <i data-lucide="book" class="filter-icon"></i>
                                <select name="subject_id" id="subject_id" class="custom-select">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                            <label class="filter-label" for="class_id">Class</label>
                            <div class="filter-input-group">
                                <i data-lucide="users" class="filter-icon"></i>
                                <select name="class_id" id="class_id" class="custom-select">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $cls)
                                        <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="filter-actions-bar">
                        <button type="submit" class="btn btn-filter-apply">
                            <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                            <span>Apply Filters</span>
                        </button>
                        <a href="{{ route('admin.papers.index') }}" class="btn btn-filter-reset">
                            <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Papers Table Card -->
        <div class="card theme-card">
            <div class="table-header-toolbar">
                <div class="table-title-area">
                    <h2 class="table-title-text">
                        <i data-lucide="file-text" style="color: var(--theme-primary-bright); width: 22px; height: 22px;"></i>
                        <span>Papers List</span>
                    </h2>
                    <span class="badge-count-pill">{{ $papers->total() }} papers</span>
                </div>
                <div class="table-actions-toolbar">
                    <a href="{{ route('admin.papers.create') }}" class="btn btn-action-import" title="Create Paper">
                        <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                        <span>Create Paper</span>
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table theme-table">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="py-1">#</th>
                                                <th class="py-1">Paper Details</th>
                                                <th class="py-1">Type</th>
                                                <th class="py-1">Subject</th>
                                                <th class="py-1">Class / Year</th>
                                                <th class="py-1">Questions</th>
                                                <th class="py-1">Total Marks</th>
                                                <th class="py-1">Time</th>
                                                <th class="py-1 text-right pr-2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($papers as $index => $paper)
                                                <tr id="paper-row-{{ $paper->id }}">
                                                    <td class="align-middle">{{ $papers->firstItem() + $index }}</td>
                                                    <td class="align-middle">
                                                        <span class="font-weight-bold text-dark d-block">
                                                            {{ $paper->title }}
                                                            @if($paper->assignments->isEmpty())
                                                                <span
                                                                    class="badge badge-warning font-small-1 py-25 px-50 ml-50 rounded-pill">Unassigned</span>
                                                            @else
                                                                @php
                                                                    $first = $paper->assignments->first();
                                                                    $typeMap = [
                                                                        'classes' => 'Classes',
                                                                        'sessions' => 'Sessions',
                                                                        'group_years' => 'Group Years',
                                                                        'students' => 'Students'
                                                                    ];
                                                                    $scope = $typeMap[$first->assign_type] ?? 'Unknown';
                                                                    $mode = $first->assign_mode === 'all' ? 'All' : 'Specific';
                                                                @endphp
                                                                <span
                                                                    class="badge badge-success font-small-1 py-25 px-50 ml-50 rounded-pill">Assigned
                                                                    ({{ $scope }}: {{ $mode }})</span>
                                                            @endif
                                                        </span>
                                                        <span class="font-small-2">
                                                            By: {{ $paper->user->name ?? 'System' }} | Acad Year:
                                                            {{ $paper->academic_year ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        @if($paper->type === 'exam')
                                                            <span class="badge badge-success badge-pill">
                                                                <i class="feather icon-award"></i> Exam
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning badge-pill text-white">
                                                                <i class="feather icon-file-text"></i> Test
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle font-weight-bold">
                                                        {{ $paper->subject->title ?? '—' }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <span
                                                            class="text-dark d-block font-weight-bold">{{ $paper->class->name ?? '—' }}</span>
                                                        <span class="font-small-2">Difficulty:
                                                            {{ ucfirst($paper->difficulty ?? 'medium') }}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="badge badge-primary font-small-3 font-weight-bold px-75 py-25 rounded">
                                                            {{ $paper->questions_count ?? $paper->questions()->count() }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle text-center font-weight-bold text-primary">
                                                        {{ ($paper->questions_count ?? $paper->questions()->count()) * ($paper->default_marks ?: 1) }}
                                                    </td>
                                                    <td class="align-middle font-weight-bold">{{ $paper->total_time }} min</td>
                                                    <td class="align-middle text-right pr-2">
                                                        <button type="button"
                                                            class="btn btn-sm btn-flat-info mr-25 btn-assign-paper"
                                                            data-id="{{ $paper->id }}" data-title="{{ $paper->title }}"
                                                            title="Assign Paper">
                                                            <i class="feather icon-user-check font-medium-1"></i> Assign To
                                                        </button>
                                                        <a href="{{ route('admin.papers.edit', $paper->id) }}"
                                                            class="btn btn-sm btn-flat-primary mr-25" title="Edit Paper">
                                                            <i class="feather icon-edit font-medium-1"></i> Edit
                                                        </a>
                                                        <button type="button"
                                                            class="btn btn-sm btn-flat-danger"
                                                            title="Delete Paper"
                                                            onclick="confirmDeletePaper({{ $paper->id }}, '{{ addslashes($paper->title) }}')">
                                                            <i class="feather icon-trash font-medium-1"></i> Delete
                                                        </button>
                                                        <form id="delete-paper-form-{{ $paper->id }}"
                                                            action="{{ route('admin.papers.destroy', $paper->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-3">
                                                        <i class="feather icon-info font-large-1 d-block mb-50"></i>
                                                        No exam or test papers found matching the search criteria.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="table-footer-bar">
                                        <div class="table-summary-text">
                                            Showing {{ $papers->firstItem() ?? 0 }} to {{ $papers->lastItem() ?? 0 }} of {{ $papers->total() }} papers
                                        </div>
                                        <div>
                                            {{ $papers->appends(request()->query())->links('pagination::bootstrap-5') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- END: Content-->

    <!-- Enterprise Assign Paper Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="assign-modal-header-left">
                        <div class="assign-header-icon-box">
                            <i class="feather icon-shield font-medium-3"></i>
                        </div>
                        <div>
                            <h4 class="assign-modal-title" id="assignModalLabel">Assign Assessment Access</h4>
                            <p class="assign-subtitle">Control visibility and access permissions for this assessment</p>
                            <div class="assign-paper-badge-pill" id="modalPaperBadge">
                                <i class="feather icon-file-text"></i> <span id="modalPaperTitleText">Assessment Paper</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="modal-close-btn-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignPaperForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="paper_id" id="modalPaperId">
                    <div class="modal-body">
                        <!-- Loading Spinner -->
                        <div id="assignModalLoading" class="text-center py-4">
                            <div class="spinner-border text-primary" style="width: 2.75rem; height: 2.75rem;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2 font-weight-bold">Retrieving assignment configuration...</p>
                        </div>

                        <!-- Modal Form Content -->
                        <div id="assignModalContent" style="display: none;">
                            <div class="assign-scope-select-wrap">
                                <label for="assign_scope" class="assign-section-title">
                                    <i class="feather icon-target"></i> Select Access Scope Policy
                                </label>
                                <select class="form-control" name="assign_scope" id="assign_scope" required>
                                    <option value="none">🔒 Do Not Assign (Not Visible to Anyone)</option>
                                    <optgroup label="── Class Level Access ──">
                                        <option value="classes_all">🏫 To All Class Students</option>
                                        <option value="classes_specific">🎯 To Students of Specific Classes</option>
                                    </optgroup>
                                    <optgroup label="── Academic Session Access ──">
                                        <option value="sessions_all">📅 To All Academic Session Students</option>
                                        <option value="sessions_specific">🎯 To Students of Specific Academic Sessions</option>
                                    </optgroup>
                                    <optgroup label="── Group Year Access ──">
                                        <option value="group_years_all">👥 To All Group Year Students</option>
                                        <option value="group_years_specific">🎯 To Students of Specific Group Years</option>
                                    </optgroup>
                                    <optgroup label="── Individual Direct Student Access ──">
                                        <option value="students_all">🌐 To All Enrolled Students (Global Access)</option>
                                        <option value="students_specific">👤 Only Specific Selected Students</option>
                                    </optgroup>
                                </select>
                                <div id="scopeHintBanner" class="assign-scope-hint hint-none">
                                    <i class="feather icon-info"></i>
                                    <span id="scopeHintText">Paper is currently hidden from all students.</span>
                                </div>
                            </div>

                            <!-- Classes Workspace -->
                            <div class="specific-list-container assign-workspace-card" id="list_classes" style="display: none;">
                                <div class="assign-toolbar">
                                    <div class="assign-search-input">
                                        <i class="feather icon-search"></i>
                                        <input type="text" class="form-control search-filter" data-target="classes" placeholder="Filter classes by name or year...">
                                    </div>
                                    <div class="assign-quick-actions">
                                        <span class="badge badge-pill badge-light-primary selection-count" data-type="classes">0 selected</span>
                                        <button type="button" class="assign-btn-toggle btn-toggle-all" data-action="select" data-target="classes">Select All</button>
                                        <button type="button" class="assign-btn-toggle btn-toggle-all" data-action="clear" data-target="classes">Clear All</button>
                                    </div>
                                </div>
                                <div class="no-matches-found text-center py-3" style="display: none;">
                                    <i class="feather icon-alert-circle mr-1"></i> No matching classes found
                                </div>
                                <div class="assign-card-grid">
                                    @foreach($classes as $classItem)
                                        <div class="target-choice-card checkbox-wrapper-item">
                                            <input type="checkbox" class="target-choice-checkbox class-checkbox"
                                                name="target_ids[]" id="chk_class_{{ $classItem->id }}"
                                                value="{{ $classItem->id }}">
                                            <div class="target-choice-icon icon-classes">
                                                <i class="feather icon-layers"></i>
                                            </div>
                                            <div class="target-choice-text">
                                                <div class="target-choice-title" title="{{ $classItem->name }}">{{ $classItem->name }}</div>
                                                <div class="target-choice-subtitle">{{ $classItem->group_year ?? 'Standard' }} • {{ $classItem->academic_year ?? 'Current Session' }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sessions Workspace -->
                            <div class="specific-list-container assign-workspace-card" id="list_sessions" style="display: none;">
                                <div class="assign-toolbar">
                                    <div class="assign-search-input">
                                        <i class="feather icon-search"></i>
                                        <input type="text" class="form-control search-filter" data-target="sessions" placeholder="Filter academic sessions...">
                                    </div>
                                    <div class="assign-quick-actions">
                                        <span class="badge badge-pill badge-light-primary selection-count" data-type="sessions">0 selected</span>
                                        <button type="button" class="assign-btn-toggle btn-toggle-all" data-action="select" data-target="sessions">Select All</button>
                                        <button type="button" class="assign-btn-toggle btn-toggle-all" data-action="clear" data-target="sessions">Clear All</button>
                                    </div>
                                </div>
                                <div class="no-matches-found text-center py-3" style="display: none;">
                                    <i class="feather icon-alert-circle mr-1"></i> No matching sessions found
                                </div>
                                <div class="assign-card-grid">
                                    @foreach($academicYears as $yearItem)
                                        <div class="target-choice-card checkbox-wrapper-item">
                                            <input type="checkbox" class="target-choice-checkbox session-checkbox"
                                                name="target_ids[]" id="chk_session_{{ $yearItem->id }}"
                                                value="{{ $yearItem->id }}">
                                            <div class="target-choice-icon icon-sessions">
                                                <i class="feather icon-calendar"></i>
                                            </div>
                                            <div class="target-choice-text">
                                                <div class="target-choice-title" title="{{ $yearItem->name }}">{{ $yearItem->name }}</div>
                                                <div class="target-choice-subtitle">Academic Year Session</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Group Years Workspace -->
                            <div class="specific-list-container assign-workspace-card" id="list_group_years" style="display: none;">
                                <div class="assign-toolbar">
                                    <div class="assign-search-input">
                                        <i class="feather icon-search"></i>
                                        <input type="text" class="form-control search-filter" data-target="group_years" placeholder="Filter group years...">
                                    </div>
                                    <div class="assign-quick-actions">
                                        <span class="badge badge-pill badge-light-primary selection-count" data-type="group_years">0 selected</span>
                                        <button type="button" class="assign-btn-toggle btn-toggle-all" data-action="select" data-target="group_years">Select All</button>
                                        <button type="button" class="assign-btn-toggle btn-toggle-all" data-action="clear" data-target="group_years">Clear All</button>
                                    </div>
                                </div>
                                <div class="no-matches-found text-center py-3" style="display: none;">
                                    <i class="feather icon-alert-circle mr-1"></i> No matching group years found
                                </div>
                                <div class="assign-card-grid">
                                    @foreach($yearGroups as $groupItem)
                                        <div class="target-choice-card checkbox-wrapper-item">
                                            <input type="checkbox" class="target-choice-checkbox group-year-checkbox"
                                                name="target_values[]" id="chk_group_{{ $groupItem->id }}"
                                                value="{{ $groupItem->value }}">
                                            <div class="target-choice-icon icon-group_years">
                                                <i class="feather icon-users"></i>
                                            </div>
                                            <div class="target-choice-text">
                                                <div class="target-choice-title" title="{{ $groupItem->title }}">{{ $groupItem->title }}</div>
                                                <div class="target-choice-subtitle">Group Year Key: {{ $groupItem->value }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Students Workspace -->
                            <div class="specific-list-container assign-workspace-card" id="list_students" style="display: none;">
                                <div class="assign-toolbar">
                                    <div class="assign-search-input">
                                        <i class="feather icon-search"></i>
                                        <input type="text" class="form-control search-filter" data-target="students" placeholder="Search students by name or email...">
                                    </div>
                                    <div class="assign-quick-actions">
                                        <span class="badge badge-pill badge-light-primary selection-count" data-type="students">0 selected</span>
                                        <button type="button" class="assign-btn-toggle btn-toggle-all" data-action="select" data-target="students">Select All</button>
                                        <button type="button" class="assign-btn-toggle btn-toggle-all" data-action="clear" data-target="students">Clear All</button>
                                    </div>
                                </div>
                                <div class="no-matches-found text-center py-3" style="display: none;">
                                    <i class="feather icon-alert-circle mr-1"></i> No matching students found
                                </div>
                                <div class="assign-card-grid">
                                    @foreach($students as $studentItem)
                                        <div class="target-choice-card checkbox-wrapper-item">
                                            <input type="checkbox" class="target-choice-checkbox student-checkbox"
                                                name="target_ids[]" id="chk_student_{{ $studentItem->id }}"
                                                value="{{ $studentItem->id }}">
                                            <div class="target-choice-icon icon-students">
                                                <i class="feather icon-user"></i>
                                            </div>
                                            <div class="target-choice-text">
                                                <div class="target-choice-title" title="{{ $studentItem->name }}">{{ $studentItem->name }}</div>
                                                <div class="target-choice-subtitle" title="{{ $studentItem->email }}">{{ $studentItem->email }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="assign-footer-summary" id="assignFooterSummary">
                            <i class="feather icon-info text-primary"></i>
                            <span id="assignSummaryText">Configure assignment scope</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-outline-secondary font-weight-bold mr-1" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-apply-assignment" id="btnSaveAssignment" style="display: none;">
                                <i class="feather icon-check-circle"></i> Save Assignment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {
                // Initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

                // Update scope hint banner with informative descriptions
                function updateScopeHint(scope) {
                    scope = scope || $('#assign_scope').val();
                    var banner = $('#scopeHintBanner');
                    var text = $('#scopeHintText');

                    banner.removeClass('hint-none hint-all hint-specific');

                    if (scope === 'none') {
                        banner.addClass('hint-none');
                        text.html('<strong>Strict Lockdown:</strong> Paper is hidden from all students. No student can view or attempt this paper.');
                    } else if (scope.endsWith('_all')) {
                        banner.addClass('hint-all');
                        var scopeMap = {
                            'classes_all': 'All Class Students',
                            'sessions_all': 'All Academic Session Students',
                            'group_years_all': 'All Group Year Students',
                            'students_all': 'All Enrolled Students Globally'
                        };
                        var scopeTitle = scopeMap[scope] || 'All Students';
                        text.html('<strong>Automatic Broad Access:</strong> Paper is automatically available to <strong>' + scopeTitle + '</strong>.');
                    } else if (scope.endsWith('_specific')) {
                        banner.addClass('hint-specific');
                        var category = scope.replace('_specific', '');
                        var labelMap = { 
                            'classes': 'selected classes', 
                            'sessions': 'selected sessions', 
                            'group_years': 'selected group years', 
                            'students': 'individual students' 
                        };
                        text.html('<strong>Targeted Access:</strong> Access is restricted only to the <strong>' + (labelMap[category] || 'selected items') + '</strong> checked below.');
                    }
                    updateFooterSummary();
                }

                // Update bottom footer summary pill
                function updateFooterSummary() {
                    var scope = $('#assign_scope').val();
                    var summary = $('#assignSummaryText');
                    if (scope === 'none') {
                        summary.html('Current Status: <span class="text-danger font-weight-bold">Locked / Hidden from all students</span>');
                    } else if (scope.endsWith('_all')) {
                        summary.html('Current Status: <span class="text-success font-weight-bold">Open to all students in policy</span>');
                    } else if (scope.endsWith('_specific')) {
                        var type = scope.replace('_specific', '');
                        var count = $('#list_' + type).find('input[type="checkbox"]:checked').length;
                        var label = count === 1 ? 'item' : 'items';
                        if (count > 0) {
                            summary.html('Target Scope: <span class="text-primary font-weight-bold">' + count + ' ' + label + ' selected</span> for access');
                        } else {
                            summary.html('Target Scope: <span class="text-warning font-weight-bold">0 items selected</span> (select items below to assign)');
                        }
                    }
                }

                // Update selection badge and style dynamically
                function updateSelectionCount(type) {
                    var container = $('#list_' + type);
                    var checkedCount = container.find('input[type="checkbox"]:checked').length;
                    var badge = container.find('.selection-count');
                    
                    badge.text(checkedCount + ' selected');
                    if (checkedCount > 0) {
                        badge.removeClass('badge-light-primary').addClass('badge-primary');
                    } else {
                        badge.removeClass('badge-primary').addClass('badge-light-primary');
                    }
                    updateFooterSummary();
                }

                // Reset search filters and matching elements
                function resetFilters() {
                    $('.search-filter').val('');
                    $('.checkbox-wrapper-item').show();
                    $('.no-matches-found').hide();
                }

                // Show assignment modal and load configuration (delegated for full reliability)
                $(document).on('click', '.btn-assign-paper', function () {
                    var paperId = $(this).data('id');
                    var paperTitle = $(this).data('title');

                    // Set modal info
                    $('#assignModalLabel').text('Assign Assessment Access');
                    $('#modalPaperTitleText').text(paperTitle);
                    $('#modalPaperId').val(paperId);

                    // Safe dynamic URL generation using Laravel route templates
                    var routeUrl = "{{ route('admin.papers.assign', ':id') }}";
                    $('#assignPaperForm').attr('action', routeUrl.replace(':id', paperId));

                    // Open modal
                    $('#assignModal').modal('show');
                    resetFilters();

                    // Loading state
                    $('#assignModalLoading').show();
                    $('#assignModalContent').hide();
                    $('#btnSaveAssignment').hide();

                    // Fetch assignments using Axios or Fetch API
                    var getUrlTemplate = "{{ route('admin.papers.assignments', ':id') }}";
                    var fetchUrl = getUrlTemplate.replace(':id', paperId);

                    if (typeof axios !== 'undefined') {
                        axios.get(fetchUrl)
                            .then(function (response) {
                                renderAssignmentData(response.data);
                            })
                            .catch(function (error) {
                                console.error(error);
                                alert('Error retrieving assignment settings. Please try again.');
                                $('#assignModal').modal('hide');
                            });
                    } else {
                        fetch(fetchUrl, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            renderAssignmentData(data);
                        })
                        .catch(function(err) {
                            console.error(err);
                            alert('Error retrieving assignment settings. Please try again.');
                            $('#assignModal').modal('hide');
                        });
                    }
                });

                function renderAssignmentData(data) {
                    // Reset all form elements and selection styling
                    $('.specific-list-container').hide();
                    $('.specific-list-container').find('input').prop('disabled', true);
                    $('input[type="checkbox"]').prop('checked', false);
                    $('.target-choice-card').removeClass('is-selected');

                    // Set the correct scope selection
                    var scope = data.assign_type === 'none' ? 'none' : (data.assign_type + '_' + data.assign_mode);
                    $('#assign_scope').val(scope);

                    // Populate checkboxes if specific mode is active
                    if (data.assign_type !== 'none' && data.assign_mode === 'specific') {
                        var listId = '#list_' + data.assign_type;
                        $(listId).show();
                        $(listId).find('input').prop('disabled', false);

                        var checkboxClass = {
                            'classes': '.class-checkbox',
                            'sessions': '.session-checkbox',
                            'group_years': '.group-year-checkbox',
                            'students': '.student-checkbox'
                        }[data.assign_type];

                        if (data.selected_ids && Array.isArray(data.selected_ids)) {
                            data.selected_ids.forEach(function (val) {
                                var checkbox = $(checkboxClass + '[value="' + val + '"]');
                                checkbox.prop('checked', true);
                                checkbox.closest('.target-choice-card').addClass('is-selected');
                            });
                        }
                    }

                    // Update all counts
                    ['classes', 'sessions', 'group_years', 'students'].forEach(function(type) {
                        updateSelectionCount(type);
                    });

                    // Update scope hints & summary
                    updateScopeHint(scope);

                    // Switch loading state off
                    $('#assignModalLoading').hide();
                    $('#assignModalContent').fadeIn(200);
                    $('#btnSaveAssignment').show();
                }

                // Listen to Assign Scope dropdown change
                $('#assign_scope').on('change', function () {
                    var scope = $(this).val();

                    // Hide all lists and disable all checkbox inputs
                    $('.specific-list-container').hide();
                    $('.specific-list-container').find('input').prop('disabled', true);
                    resetFilters();

                    if (scope !== 'none' && scope.endsWith('_specific')) {
                        var type = scope.replace('_specific', '');
                        var listId = '#list_' + type;

                        $(listId).fadeIn(200);
                        $(listId).find('input').prop('disabled', false);
                        updateSelectionCount(type);
                    }

                    updateScopeHint(scope);
                });

                // Interactive click on choice card to toggle selection
                $(document).on('click', '.target-choice-card', function (e) {
                    if (!$(e.target).is('input[type="checkbox"]')) {
                        var chk = $(this).find('input[type="checkbox"]');
                        if (!chk.prop('disabled')) {
                            chk.prop('checked', !chk.prop('checked')).trigger('change');
                        }
                    }
                });

                // Handle checkbox changes directly to sync card visual and counts
                $(document).on('change', '.target-choice-checkbox', function () {
                    var isChecked = $(this).is(':checked');
                    $(this).closest('.target-choice-card').toggleClass('is-selected', isChecked);
                    var container = $(this).closest('.specific-list-container');
                    var type = container.attr('id').replace('list_', '');
                    updateSelectionCount(type);
                });

                // Real-time live filtering / search functionality
                $('.search-filter').on('input', function () {
                    var query = $(this).val().toLowerCase().trim();
                    var targetType = $(this).data('target');
                    var container = $('#list_' + targetType);
                    var wrapperItems = container.find('.checkbox-wrapper-item');
                    var noMatchesDiv = container.find('.no-matches-found');
                    var visibleCount = 0;

                    wrapperItems.each(function () {
                        var text = $(this).text().toLowerCase();
                        if (text.indexOf(query) > -1) {
                            $(this).show();
                            visibleCount++;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (visibleCount === 0) {
                        noMatchesDiv.show();
                    } else {
                        noMatchesDiv.hide();
                    }
                });

                // Select All / Clear All visible matching checkboxes
                $('.btn-toggle-all').on('click', function (e) {
                    e.preventDefault();
                    var action = $(this).data('action'); // 'select' or 'clear'
                    var targetType = $(this).data('target');
                    var container = $('#list_' + targetType);
                    var visibleCards = container.find('.checkbox-wrapper-item:visible');
                    var visibleCheckboxes = visibleCards.find('input[type="checkbox"]');
                    
                    if (action === 'select') {
                        visibleCheckboxes.prop('checked', true);
                        visibleCards.addClass('is-selected');
                    } else {
                        visibleCheckboxes.prop('checked', false);
                        visibleCards.removeClass('is-selected');
                    }
                    
                    updateSelectionCount(targetType);
                });

                // Client-side validation on form submit
                $('#assignPaperForm').on('submit', function (e) {
                    var scope = $('#assign_scope').val();
                    if (scope !== 'none' && scope.endsWith('_specific')) {
                        var type = scope.replace('_specific', '');
                        var checkboxClass = {
                            'classes': '.class-checkbox',
                            'sessions': '.session-checkbox',
                            'group_years': '.group-year-checkbox',
                            'students': '.student-checkbox'
                        }[type];

                        if ($(checkboxClass + ':checked').length === 0) {
                            e.preventDefault();
                            alert('Please select at least one item for the specific assignment.');
                            return false;
                        }
                    }
                });
            });

            function togglePaperFilters() {
                const body = document.getElementById('paperFilterBody');
                const icon = document.getElementById('paperFilterToggleIcon');
                if (body.style.display === 'none') {
                    body.style.display = 'block';
                    if (icon) icon.style.transform = 'rotate(0deg)';
                } else {
                    body.style.display = 'none';
                    if (icon) icon.style.transform = 'rotate(180deg)';
                }
            }

            // SweetAlert2 Soft Delete Paper
            function confirmDeletePaper(paperId, paperTitle) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Delete Paper?',
                        html: `Are you sure you want to delete <strong>"${paperTitle}"</strong>?<br><small>This will soft-delete the paper and remove its assembled question links.</small>`,
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
                                text: 'Removing paper...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            const csrfToken = document.querySelector('meta[name="csrf-token"]') 
                                ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                                : '{{ csrf_token() }}';

                            fetch(`/admin/papers/${paperId}`, {
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
                                        text: data.message || 'Paper deleted successfully.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        const row = document.getElementById(`paper-row-${paperId}`);
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
                                const fallbackForm = document.getElementById(`delete-paper-form-${paperId}`);
                                if (fallbackForm) {
                                    fallbackForm.submit();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: err.message || 'Could not delete paper.'
                                    });
                                }
                            });
                        }
                    });
                } else {
                    if (confirm(`Are you sure you want to delete paper: "${paperTitle}"?`)) {
                        document.getElementById(`delete-paper-form-${paperId}`).submit();
                    }
                }
            }
        </script>
    @endpush
@endsection
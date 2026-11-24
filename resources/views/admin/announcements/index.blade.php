@extends('layouts.app')

@section('content')
    <!-- Include Lucide Icons & Flatpickr -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .flatpickr-calendar {
            border-radius: 12px !important;
            box-shadow: 0 10px 30px rgba(6, 48, 128, 0.15) !important;
            border: 1px solid #DCE3EE !important;
            font-family: inherit !important;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange {
            background: #2477F9 !important;
            border-color: #2477F9 !important;
            color: #fff !important;
        }

        .flatpickr-day.inRange {
            background: rgba(36, 119, 249, 0.12) !important;
            border-color: transparent !important;
            box-shadow: -5px 0 0 rgba(36, 119, 249, 0.12), 5px 0 0 rgba(36, 119, 249, 0.12) !important;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700;
        }
    </style>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>

        <div class="content-wrapper">

            <!-- Page Header / Title & Breadcrumbs -->
            <div class="admin-page-header">
                <div class="admin-page-title-wrap">
                    <div class="admin-title-row">
                        <h1 class="admin-page-heading">Announcements Management</h1>
                        <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <a href="{{ route('admin.announcements.index') }}">Announcements</a>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <span>List</span>
                        </nav>
                    </div>
                    <p class="admin-page-subtitle">Publish system notices, parent notifications, and school updates</p>
                </div>
                <div>
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-admin-add">
                        <i data-lucide="plus" style="width: 17px; height: 17px;"></i>
                        <span>Add Announcement</span>
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert"
                    style="border-radius: 10px; background-color: var(--theme-success-light); border: 1px solid #A8E8C2; color: #155724;">
                    <i class="feather icon-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert"
                    style="border-radius: 10px; background-color: var(--theme-danger-light); border: 1px solid #FCD6D9; color: var(--theme-danger);">
                    <i class="feather icon-alert-circle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Filters Section -->
            <div class="card theme-card" id="announcementFilterCard">
                <div class="theme-card-header" style="cursor: pointer;" onclick="toggleAnnouncementFilters()">
                    <h2 class="theme-card-title">
                        <i data-lucide="filter"></i>
                        <span>Filters</span>
                    </h2>
                    <button type="button" class="btn btn-link p-0" aria-label="Toggle filters"
                        style="color: #7A8499;">
                        <i data-lucide="chevron-up" id="announcementFilterToggleIcon"
                            style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
                <div class="theme-card-body" id="announcementFilterBody">
                    <form method="GET" action="{{ route('admin.announcements.index') }}">
                        <div class="row g-3">

                            <!-- 1. Search Keyword -->
                            <div class="col-xl-4 col-lg-3 col-md-6 col-12 mb-2">
                                <label class="filter-label" for="search">Search Keyword</label>
                                <div class="filter-input-group">
                                    <i data-lucide="search" class="filter-icon"></i>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        class="form-control" placeholder="Search by title...">
                                </div>
                            </div>

                            <!-- 2. Recipient Scope -->
                            <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-2">
                                <label class="filter-label" for="recipient_type">Recipient Scope</label>
                                <div class="filter-input-group">
                                    <i data-lucide="users" class="filter-icon"></i>
                                    <select name="recipient_type" id="recipient_type" class="custom-select">
                                        <option value="">All Scopes</option>
                                        <option value="all_active_students" {{ request('recipient_type') === 'all_active_students' ? 'selected' : '' }}>All
                                            Students</option>
                                        <option value="all_tutors" {{ request('recipient_type') === 'all_tutors' ? 'selected' : '' }}>All Tutors</option>
                                        <option value="class" {{ request('recipient_type') === 'class' ? 'selected' : '' }}>
                                            Specific Classes</option>
                                        <option value="year_group" {{ request('recipient_type') === 'year_group' ? 'selected' : '' }}>Year Groups</option>
                                        <option value="user" {{ request('recipient_type') === 'user' ? 'selected' : '' }}>
                                            Individual Users</option>
                                        <option value="none" {{ request('recipient_type') === 'none' ? 'selected' : '' }}>No
                                            Recipients</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 3. Type -->
                            <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-2">
                                <label class="filter-label" for="type">Type</label>
                                <div class="filter-input-group">
                                    <i data-lucide="file-text" class="filter-icon"></i>
                                    <select name="type" id="type" class="custom-select">
                                        <option value="">All Types</option>
                                        <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Text Only</option>
                                        <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>Media Only</option>
                                        <option value="3" {{ request('type') == '3' ? 'selected' : '' }}>Text & Media</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 4. Status -->
                            <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="status">Status</label>
                                <div class="filter-input-group">
                                    <i data-lucide="bar-chart-2" class="filter-icon"></i>
                                    <select name="status" id="status" class="custom-select">
                                        <option value="">All Statuses</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>
                                            Scheduled</option>
                                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>
                                            Expired</option>
                                        <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>
                                            Deleted</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 5. Academic Year -->
                            <div class="col-xl-2 col-lg-3 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="academic_year_id">Academic Year</label>
                                <div class="filter-input-group">
                                    <i data-lucide="book" class="filter-icon"></i>
                                    <select name="academic_year_id" id="academic_year_id" class="custom-select">
                                        <option value="">All Years</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- 6. Date Range -->
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="date_range">Date Range</label>
                                <div class="filter-input-group position-relative">
                                    <i data-lucide="calendar" class="filter-icon"></i>
                                    <input type="text" name="date_range" id="date_range"
                                        value="{{ request('date_range', (request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : request('start_date', request('end_date', '')))) }}"
                                        class="form-control" placeholder="Select date range..." readonly
                                        style="background-color: #FFFFFF; cursor: pointer;">
                                    @if(request('date_range') || request('start_date') || request('end_date'))
                                        <button type="button" id="clear_date_range" class="btn btn-link p-0 position-absolute"
                                            style="right: 12px; z-index: 5; color: #8AA4CB; line-height: 1;"
                                            title="Clear date range">
                                            <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <!-- Filter Action Buttons -->
                        <div class="filter-actions-bar">
                            <button type="submit" class="btn btn-filter-apply">
                                <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                                <span>Apply Filters</span>
                            </button>
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-filter-reset">
                                <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Announcements List Card -->
            <div class="card theme-card">

                <!-- Table Header Toolbar -->
                <div class="table-header-toolbar">
                    <div class="table-title-area">
                        <h2 class="table-title-text">
                            <i data-lucide="bell"
                                style="color: var(--theme-primary-bright); width: 22px; height: 22px;"></i>
                            <span>Announcements List</span>
                        </h2>
                        <span class="badge-count-pill">{{ $announcements->total() }} announcements</span>
                    </div>

                    {{-- <div class="table-actions-toolbar">
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-action-import"
                            title="Add Announcement">
                            <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                            <span>Add Announcement</span>
                        </a>
                    </div> --}}
                </div>

                <!-- Table Container -->
                <div class="table-responsive">
                    <table class="table theme-table">
                        <thead>
                            <tr>
                                <th style="width: 36px; text-align: center;">
                                    <input type="checkbox" class="custom-theme-checkbox" id="selectAllCheckbox"
                                        title="Select All">
                                </th>
                                <th style="width: 40px;">#</th>
                                <th>TITLE</th>
                                <th>TYPE</th>
                                <th>ACADEMIC YEAR</th>
                                <th>RECIPIENT SCOPE</th>
                                <th>PRIORITY</th>
                                <th>STATUS</th>
                                <th>DELIVERY (VIEWS)</th>
                                <th style="text-align: center;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($announcements as $index => $announcement)
                                @php
                                    $computed = $announcement->computed_status;
                                @endphp
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="checkbox" class="custom-theme-checkbox row-checkbox"
                                            value="{{ $announcement->id }}">
                                    </td>
                                    <td class="font-weight-bold">{{ $announcements->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="student-id-badge mr-1"
                                                style="background: rgba(36, 119, 249, 0.1); color: var(--theme-primary-bright); width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                <i data-lucide="volume-2" style="width: 16px; height: 16px;"></i>
                                            </div>
                                            <div>
                                                <strong
                                                    class="text-dark font-weight-bold d-block">{{ $announcement->title }}</strong>
                                                @if($announcement->is_draft)
                                                    <span class="badge"
                                                        style="background: rgba(243, 165, 33, 0.15); color: #B26A00; font-size: 10px; font-weight: 700; border-radius: 4px; padding: 2px 6px;">Draft</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge"
                                            style="background: rgba(6, 48, 128, 0.08); color: var(--theme-primary); font-weight: 600; border-radius: 6px; padding: 5px 10px;">
                                            @if ($announcement->type == 1)
                                                Text Only
                                            @elseif($announcement->type == 2)
                                                Media Only
                                            @elseif($announcement->type == 3)
                                                Text & Media
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span>{{ optional($announcement->academicYear)->name ?? 'All Sessions' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap align-items-center" style="gap: 4px; max-width: 250px;">
                                            @forelse($announcement->recipient_scope_summaries as $scope)
                                                <span class="badge d-inline-flex align-items-center"
                                                    style="background: {{ $scope['badge_bg'] }}; color: {{ $scope['badge_color'] }}; font-weight: 600; font-size: 11px; border-radius: 6px; padding: 4px 8px; line-height: 1.2;"
                                                    title="{{ $scope['tooltip'] }}" data-toggle="tooltip">
                                                    <i data-lucide="{{ $scope['icon'] }}"
                                                        style="width: 12px; height: 12px; margin-right: 4px;"></i>
                                                    <span>{{ $scope['label'] }}</span>
                                                </span>
                                            @empty
                                                <span class="badge"
                                                    style="background: rgba(100, 116, 139, 0.1); color: #64748B; font-weight: 600; font-size: 11px; border-radius: 6px; padding: 4px 8px;">
                                                    No Recipients
                                                </span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        @if($announcement->priority === 'high')
                                            <span class="badge"
                                                style="background: rgba(250, 40, 51, 0.12); color: var(--theme-danger); font-weight: 700; border-radius: 6px; padding: 5px 10px;">High</span>
                                        @elseif($announcement->priority === 'medium')
                                            <span class="badge"
                                                style="background: rgba(243, 165, 33, 0.15); color: #B26A00; font-weight: 700; border-radius: 6px; padding: 5px 10px;">Medium</span>
                                        @else
                                            <span class="badge"
                                                style="background: rgba(100, 116, 139, 0.12); color: #64748B; font-weight: 700; border-radius: 6px; padding: 5px 10px;">Low</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($computed === 'Active')
                                            <span class="status-pill status-active">
                                                <span class="status-dot"></span> Active
                                            </span>
                                        @elseif($computed === 'Scheduled')
                                            <span class="status-pill status-pending">
                                                <span class="status-dot"></span> Scheduled
                                            </span>
                                        @elseif($computed === 'Expired')
                                            <span class="status-pill status-inactive">
                                                <span class="status-dot"></span> Expired
                                            </span>
                                        @elseif($computed === 'Deleted')
                                            <span class="status-pill"
                                                style="background: rgba(250, 40, 51, 0.1); color: var(--theme-danger);">
                                                <span class="status-dot" style="background: var(--theme-danger);"></span> Deleted
                                            </span>
                                        @else
                                            <span class="status-pill status-pending">
                                                <span class="status-dot"></span> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="mr-1 font-weight-bold"
                                                style="font-size: 12px;">{{ $announcement->views_count }} /
                                                {{ $announcement->recipients_count }}</span>
                                            <div class="table-progress-bar mr-1" style="width: 70px;">
                                                <div class="table-progress-fill fill-blue"
                                                    style="width: {{ $announcement->recipients_count > 0 ? ($announcement->views_count / $announcement->recipients_count) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <button class="btn-table-action action-view view-stats-btn"
                                                data-id="{{ $announcement->id }}" data-title="{{ $announcement->title }}"
                                                title="View Delivery Details">
                                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="action-icon-group">
                                            <!-- Logs Button -->
                                            <button type="button" class="btn-table-action action-view view-logs-btn"
                                                data-id="{{ $announcement->id }}" title="Audit Logs">
                                                <i data-lucide="file-text" style="width: 15px; height: 15px;"></i>
                                            </button>

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                                                class="btn-table-action action-edit" title="Edit Announcement">
                                                <i data-lucide="pencil" style="width: 15px; height: 15px;"></i>
                                            </a>

                                            <!-- Soft Delete Button -->
                                            @if($computed !== 'Deleted')
                                                <button type="button" class="btn-table-action action-delete"
                                                    title="Delete Announcement"
                                                    onclick="confirmDeleteAnnouncement('{{ route('admin.announcements.destroy', $announcement->id) }}', '{{ addslashes($announcement->title) }}')">
                                                    <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <i data-lucide="bell"
                                            style="width: 40px; height: 40px; opacity: 0.3; margin-bottom: 8px;"></i>
                                        <p class="mb-0">No announcements found matching your criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer Toolbar -->
                <div class="table-footer-bar">
                    <div class="table-summary-text">
                        Showing {{ $announcements->firstItem() ?? 0 }} to {{ $announcements->lastItem() ?? 0 }} of
                        {{ $announcements->total() }} announcements
                    </div>

                    <div>
                        {{ $announcements->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Stats Modal -->
    <div class="modal fade" id="statsModal" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content"
                style="border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 15px 40px rgba(6, 48, 128, 0.15);">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #063080 0%, #1757C1 100%); padding: 20px 24px; color: #FFFFFF; border-bottom: none;">
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-25" id="statsModalLabel">Delivery & Visibility
                            Details</h5>
                        <small class="text-white-50">Recipient delivery breakdown and read timestamps</small>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity: 0.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" style="background: #F8FAFD;">
                    <h6 class="mb-2">Announcement: <span id="modal-announcement-title"
                            class="text-primary font-weight-bold"></span></h6>
                    <div class="table-responsive bg-white rounded shadow-sm border">
                        <table class="table theme-table mb-0" id="statsTable">
                            <thead>
                                <tr>
                                    <th>Recipient Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Read Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="background: #FFFFFF; border-top: 1px solid #E5ECF5; padding: 14px 24px;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-2 py-1"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Modal -->
    <div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="logsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content"
                style="border-radius: 16px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 15px 40px rgba(6, 48, 128, 0.15);">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #063080 0%, #1757C1 100%); padding: 20px 24px; color: #FFFFFF; border-bottom: none;">
                    <div>
                        <h5 class="modal-title font-weight-bold text-white mb-25" id="logsModalLabel">Announcement Audit Log
                        </h5>
                        <small class="text-white-50">Detailed chronological audit record</small>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity: 0.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" style="background: #F8FAFD;">
                    <div class="table-responsive bg-white rounded shadow-sm border">
                        <table class="table theme-table mb-0" id="logsTable">
                            <thead>
                                <tr>
                                    <th>Admin</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="background: #FFFFFF; border-top: 1px solid #E5ECF5; padding: 14px 24px;">
                    <button type="button" class="btn btn-secondary font-weight-bold px-2 py-1"
                        data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Flatpickr Date Range Picker
            if (typeof flatpickr !== 'undefined') {
                const datePicker = flatpickr('#date_range', {
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd M Y',
                    altInputClass: 'form-control',
                    allowInput: false,
                    onChange: function (selectedDates, dateStr, instance) {
                        const clearBtn = document.getElementById('clear_date_range');
                        if (clearBtn) {
                            clearBtn.style.display = dateStr ? 'block' : 'none';
                        }
                    }
                });

                const clearBtn = document.getElementById('clear_date_range');
                if (clearBtn) {
                    clearBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        datePicker.clear();
                        if (datePicker.altInput) {
                            datePicker.altInput.value = '';
                        }
                        const rawInput = document.getElementById('date_range');
                        if (rawInput) rawInput.value = '';
                        clearBtn.style.display = 'none';
                    });
                }
            }

            if (typeof $ !== 'undefined' && $.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip();
            }

            const selectAll = document.getElementById('selectAllCheckbox');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                });
            }

            // Stats modal triggers
            $('.view-stats-btn').on('click', function () {
                const announcementId = $(this).data('id');
                const title = $(this).data('title');

                $('#modal-announcement-title').text(title);
                const tbody = $('#statsTable tbody');
                tbody.html('<tr><td colspan="5" class="text-center py-2">Loading delivery stats...</td></tr>');

                $('#statsModal').modal('show');

                axios.get(`/admin/announcements/${announcementId}/stats`)
                    .then(response => {
                        tbody.empty();
                        if (response.data.length === 0) {
                            tbody.append('<tr><td colspan="5" class="text-center py-2">No eligible recipients.</td></tr>');
                            return;
                        }
                        response.data.forEach(item => {
                            const badge = item.viewed
                                ? '<span class="status-pill status-active"><span class="status-dot"></span> Read</span>'
                                : '<span class="status-pill status-inactive"><span class="status-dot"></span> Unread</span>';
                            tbody.append(`
                                                                    <tr>
                                                                        <td><strong>${item.name}</strong></td>
                                                                        <td><span>${item.email}</span></td>
                                                                        <td><span class="badge badge-light-primary">${item.role}</span></td>
                                                                        <td>${badge}</td>
                                                                        <td><span>${item.viewed_at || '-'}</span></td>
                                                                    </tr>
                                                                `);
                        });
                    })
                    .catch(error => {
                        tbody.html('<tr><td colspan="5" class="text-center text-danger py-2">Failed to load statistics.</td></tr>');
                    });
            });

            // Logs modal triggers
            $('.view-logs-btn').on('click', function () {
                const announcementId = $(this).data('id');
                const tbody = $('#logsTable tbody');
                tbody.html('<tr><td colspan="4" class="text-center py-2">Loading audit logs...</td></tr>');

                $('#logsModal').modal('show');

                axios.get(`/admin/announcements/${announcementId}/audit-logs`)
                    .then(response => {
                        tbody.empty();
                        if (response.data.length === 0) {
                            tbody.append('<tr><td colspan="4" class="text-center py-2">No audit logs found.</td></tr>');
                            return;
                        }
                        response.data.forEach(item => {
                            tbody.append(`
                                                                    <tr>
                                                                        <td><strong>${item.admin}</strong></td>
                                                                        <td><span class="badge badge-light-primary">${item.action}</span></td>
                                                                        <td><span>${item.details || '-'}</span></td>
                                                                        <td><span>${item.created_at}</span></td>
                                                                    </tr>
                                                                `);
                        });
                    })
                    .catch(error => {
                        tbody.html('<tr><td colspan="4" class="text-center text-danger py-2">Failed to load audit logs.</td></tr>');
                    });
            });
        });

        function toggleAnnouncementFilters() {
            const body = document.getElementById('announcementFilterBody');
            const icon = document.getElementById('announcementFilterToggleIcon');
            if (body.style.display === 'none') {
                body.style.display = 'block';
                icon.style.transform = 'rotate(0deg)';
            } else {
                body.style.display = 'none';
                icon.style.transform = 'rotate(180deg)';
            }
        }

        function confirmDeleteAnnouncement(deleteUrl, title) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Announcement?',
                    html: `Are you sure you want to delete announcement <strong>${title}</strong>?<br><small>This will stop delivery to all recipients.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FA2833',
                    cancelButtonColor: '#7A8499',
                    confirmButtonText: '<i class="feather icon-trash-2 mr-50"></i> Yes, delete',
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
                                    text: data.message || 'Announcement deleted successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(() => {
                                executeAnnouncementDelete(deleteUrl);
                            });
                    }
                });
            } else {
                if (confirm(`Are you sure you want to delete ${title}?`)) {
                    executeAnnouncementDelete(deleteUrl);
                }
            }
        }

        function executeAnnouncementDelete(url) {
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
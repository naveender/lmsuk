@extends('layouts.app')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">
                                Announcements Management
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Announcements</a></li>
                                    <li class="breadcrumb-item">List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row mb-2">
                    <div class="col-9">
                        <p>Manage Announcements, Targeting, Scheduling, and Delivery Analytics</p>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary float-right">
                            <i class="feather icon-plus"></i> Add Announcement
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- filter start -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Filters</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="users-list-filter">
                                <form method="GET" action="{{ route('admin.announcements.index') }}"
                                    class="row mb-4 g-2 align-items-end">
                                    <div class="col-md-2">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Text Only</option>
                                            <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>Media Only</option>
                                            <option value="3" {{ request('type') == '3' ? 'selected' : '' }}>Text & Media</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">All Statuses</option>
                                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                            <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                            <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="academic_year_id" class="form-label">Academic Year</label>
                                        <select name="academic_year_id" id="academic_year_id" class="form-control">
                                            <option value="">All Years</option>
                                            @foreach($academicYears as $year)
                                                <option value="{{ $year->id }}" {{ request('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="recipient_type" class="form-label">Recipient Scope</label>
                                        <select name="recipient_type" id="recipient_type" class="form-control">
                                            <option value="">All Targets</option>
                                            <option value="all_active_students" {{ request('recipient_type') === 'all_active_students' ? 'selected' : '' }}>Active Students</option>
                                            <option value="all_tutors" {{ request('recipient_type') === 'all_tutors' ? 'selected' : '' }}>All Tutors</option>
                                            <option value="class" {{ request('recipient_type') === 'class' ? 'selected' : '' }}>Specific Classes</option>
                                            <option value="year_group" {{ request('recipient_type') === 'year_group' ? 'selected' : '' }}>Year Groups</option>
                                            <option value="user" {{ request('recipient_type') === 'user' ? 'selected' : '' }}>Individual Users</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="search" class="form-label">Search Keyword</label>
                                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                                            class="form-control" placeholder="Title keyword...">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="start_date" class="form-label">Show From (Start)</label>
                                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-2 mt-1">
                                        <label for="end_date" class="form-label">Show From (End)</label>
                                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-4 mt-1 d-flex">
                                        <button type="submit" class="btn btn-primary flex-fill mr-1">Apply Filters</button>
                                        <a href="{{ route('admin.announcements.index') }}"
                                            class="btn btn-secondary flex-fill text-center">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- filter end -->

                <!-- Data list view starts -->
                <section id="data-list-view" class="data-list-view-header">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table table-hover-animation">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Academic Year</th>
                                                <th>Priority</th>
                                                <th>Status</th>
                                                <th>Delivery (Views)</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($announcements as $index => $announcement)
                                                <tr>
                                                    <td>{{ $announcements->firstItem() + $index }}</td>
                                                    <td>
                                                        <strong class="text-primary">{{ $announcement->title }}</strong>
                                                        @if($announcement->is_draft)
                                                            <span class="badge badge-warning ml-1">Draft</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($announcement->type == 1)
                                                            Text Only
                                                        @elseif($announcement->type == 2)
                                                            Media Only
                                                        @elseif($announcement->type == 3)
                                                            Text & Media
                                                        @endif
                                                    </td>
                                                    <td>{{ optional($announcement->academicYear)->name ?? '-' }}</td>
                                                    <td>
                                                        @if($announcement->priority === 'high')
                                                            <span class="badge badge-danger text-uppercase">High</span>
                                                        @elseif($announcement->priority === 'medium')
                                                            <span class="badge badge-warning text-uppercase">Medium</span>
                                                        @else
                                                            <span class="badge badge-secondary text-uppercase">Low</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $computed = $announcement->computed_status;
                                                        @endphp
                                                        @if($computed === 'Active')
                                                            <span class="badge badge-success">Active</span>
                                                        @elseif($computed === 'Scheduled')
                                                            <span class="badge badge-info">Scheduled</span>
                                                        @elseif($computed === 'Expired')
                                                            <span class="badge badge-secondary">Expired</span>
                                                        @elseif($computed === 'Deleted')
                                                            <span class="badge badge-danger">Deleted</span>
                                                        @else
                                                            <span class="badge badge-warning">Draft</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="mr-1 font-weight-bold">{{ $announcement->views_count }} / {{ $announcement->recipients_count }}</span>
                                                            <div class="progress progress-bar-success" style="width: 80px; height: 6px; margin-bottom: 0;">
                                                                <div class="progress-bar" role="progressbar" 
                                                                     style="width: {{ $announcement->recipients_count > 0 ? ($announcement->views_count / $announcement->recipients_count) * 100 : 0 }}%"></div>
                                                            </div>
                                                            <button class="btn btn-sm btn-flat-success p-0 ml-1 view-stats-btn" 
                                                                    data-id="{{ $announcement->id }}" data-title="{{ $announcement->title }}"
                                                                    title="View Details">
                                                                <i class="feather icon-eye font-medium-1"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                                                            class="btn btn-sm btn-primary mb-1">
                                                            <i class="feather icon-edit"></i> Edit
                                                        </a>
                                                        <button class="btn btn-sm btn-info mb-1 view-logs-btn" data-id="{{ $announcement->id }}">
                                                            <i class="feather icon-list"></i> Logs
                                                        </button>
                                                        @if($computed !== 'Deleted')
                                                            <form action="{{ route('admin.announcements.destroy', $announcement->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Are you sure you want to delete this announcement? This will mark it as deleted and stop recipient delivery.');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger mb-1">
                                                                    <i class="feather icon-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">No announcements found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <nav aria-label="Page navigation">
                                        {{ $announcements->links('pagination::bootstrap-5') }}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Data list view end -->
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Stats Modal -->
    <div class="modal fade" id="statsModal" tabindex="-1" role="dialog" aria-labelledby="statsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statsModalLabel">Delivery & Visibility Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6 class="mb-2">Announcement: <span id="modal-announcement-title" class="text-primary font-weight-bold"></span></h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="statsTable">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Modal -->
    <div class="modal fade" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="logsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logsModalLabel">Announcement Audit Log</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="logsTable">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Stats modal triggers
            $('.view-stats-btn').on('click', function() {
                const announcementId = $(this).data('id');
                const title = $(this).data('title');
                
                $('#modal-announcement-title').text(title);
                const tbody = $('#statsTable tbody');
                tbody.html('<tr><td colspan="5" class="text-center">Loading delivery stats...</td></tr>');
                
                $('#statsModal').modal('show');

                axios.get(`/admin/announcements/${announcementId}/stats`)
                    .then(response => {
                        tbody.empty();
                        if (response.data.length === 0) {
                            tbody.append('<tr><td colspan="5" class="text-center">No eligible recipients.</td></tr>');
                            return;
                        }
                        response.data.forEach(item => {
                            const badge = item.viewed 
                                ? '<span class="badge badge-light-success">Read</span>' 
                                : '<span class="badge badge-light-secondary">Unread</span>';
                            tbody.append(`
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.email}</td>
                                    <td>${item.role}</td>
                                    <td>${badge}</td>
                                    <td>${item.viewed_at}</td>
                                </tr>
                            `);
                        });
                    })
                    .catch(error => {
                        tbody.html('<tr><td colspan="5" class="text-center text-danger">Failed to load statistics.</td></tr>');
                    });
            });

            // Logs modal triggers
            $('.view-logs-btn').on('click', function() {
                const announcementId = $(this).data('id');
                const tbody = $('#logsTable tbody');
                tbody.html('<tr><td colspan="4" class="text-center">Loading audit logs...</td></tr>');
                
                $('#logsModal').modal('show');

                axios.get(`/admin/announcements/${announcementId}/audit-logs`)
                    .then(response => {
                        tbody.empty();
                        if (response.data.length === 0) {
                            tbody.append('<tr><td colspan="4" class="text-center">No audit logs found.</td></tr>');
                            return;
                        }
                        response.data.forEach(item => {
                            tbody.append(`
                                <tr>
                                    <td><strong>${item.admin}</strong></td>
                                    <td><span class="badge badge-light-primary">${item.action}</span></td>
                                    <td>${item.details || '-'}</td>
                                    <td>${item.created_at}</td>
                                </tr>
                            `);
                        });
                    })
                    .catch(error => {
                        tbody.html('<tr><td colspan="4" class="text-center text-danger">Failed to load audit logs.</td></tr>');
                    });
            });
        });
    </script>
@endpush

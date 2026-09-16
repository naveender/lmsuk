@extends('layouts.app')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <!-- Breadcrumbs -->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">
                                Homeworks Directory
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Homeworks</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <i class="feather icon-check mr-50"></i>{{ session('success') }}
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="alert-body">
                            <i class="feather icon-alert-circle mr-50"></i>{{ session('error') }}
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Stats Overview Cards -->
                <!-- Stats Cards Row -->
                <div class="row mb-2">
                    <div class="col-xl-3 col-md-6 col-12 mb-1">
                        <div class="card shadow-sm mb-0">
                            <div class="card-body d-flex align-items-center justify-content-between py-1">
                                <div>
                                    <h2 class="font-weight-bolder mb-0 text-primary">{{ $totalHomeworks }}</h2>
                                    <p class="card-text text-muted mb-0 font-small-3">Total Homeworks</p>
                                </div>
                                <div class="avatar bg-rgba-primary p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-book-open text-primary font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12 mb-1">
                        <div class="card shadow-sm mb-0">
                            <div class="card-body d-flex align-items-center justify-content-between py-1">
                                <div>
                                    <h2 class="font-weight-bolder mb-0 text-success">{{ $activeCount }}</h2>
                                    <p class="card-text text-muted mb-0 font-small-3">Active (Visible)</p>
                                </div>
                                <div class="avatar bg-rgba-success p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-check-circle text-success font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12 mb-1">
                        <div class="card shadow-sm mb-0">
                            <div class="card-body d-flex align-items-center justify-content-between py-1">
                                <div>
                                    <h2 class="font-weight-bolder mb-0 text-danger">{{ $inactiveCount }}</h2>
                                    <p class="card-text text-muted mb-0 font-small-3">Inactive (Hidden)</p>
                                </div>
                                <div class="avatar bg-rgba-danger p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-eye-off text-danger font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-12 mb-1">
                        <div class="card shadow-sm mb-0">
                            <div class="card-body d-flex align-items-center justify-content-between py-1">
                                <div>
                                    <h2 class="font-weight-bolder mb-0 text-info">{{ $filesCount }}</h2>
                                    <p class="card-text text-muted mb-0 font-small-3">Documents Attached</p>
                                </div>
                                <div class="avatar bg-rgba-info p-50 m-0">
                                    <div class="avatar-content">
                                        <i class="feather icon-file-text text-info font-medium-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="row mb-2 align-items-center">
                    <div class="col-md-8">
                        <p class="text-muted mb-0 font-small-3">
                            Manage homework assignments, toggle active/inactive visibility for students, upload resource documents (Docx, PDF, TXT), and configure course weeks.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-right mt-1 mt-md-0">
                        <a href="{{ route('admin.homeworks.create') }}" class="btn btn-primary font-weight-bold shadow-sm">
                            <i class="feather icon-plus mr-50"></i>Create New Homework
                        </a>
                    </div>
                </div>

                <!-- Filter Card -->
                <div class="card shadow-sm mb-2">
                    <div class="card-header bg-white border-bottom py-1">
                        <h5 class="card-title font-weight-bold text-primary mb-0 font-small-3">
                            <i class="feather icon-filter mr-50"></i>Filter Homeworks
                        </h5>
                    </div>
                    <div class="card-body py-2">
                        <form method="GET" action="{{ route('admin.homeworks.index') }}" class="row align-items-end">
                            <div class="col-lg-3 col-md-6 col-12 mb-1">
                                <label for="search" class="font-weight-bold font-small-2 text-dark">Search Title / Instructions</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="form-control" placeholder="Search keywords...">
                            </div>
                            <div class="col-lg-2 col-md-6 col-12 mb-1">
                                <label for="subject_id" class="font-weight-bold font-small-2 text-dark">Subject</label>
                                <select name="subject_id" id="subject_id" class="form-control">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 col-12 mb-1">
                                <label for="class_id" class="font-weight-bold font-small-2 text-dark">Class</label>
                                <select name="class_id" id="class_id" class="form-control">
                                    <option value="">All Classes</option>
                                    @foreach($classes as $cls)
                                        <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                                            {{ $cls->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-6 col-12 mb-1">
                                <label for="year_group_id" class="font-weight-bold font-small-2 text-dark">Group Year</label>
                                <select name="year_group_id" id="year_group_id" class="form-control">
                                    <option value="">All Years</option>
                                    @foreach($yearGroups as $yg)
                                        <option value="{{ $yg->id }}" {{ request('year_group_id') == $yg->id ? 'selected' : '' }}>
                                            {{ $yg->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-6 col-12 mb-1">
                                <label for="is_active" class="font-weight-bold font-small-2 text-dark">Status</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="">All</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-12 col-12 mb-1 d-flex">
                                <button type="submit" class="btn btn-primary flex-fill mr-50">
                                    <i class="feather icon-search mr-25"></i>Apply
                                </button>
                                <a href="{{ route('admin.homeworks.index') }}" class="btn btn-outline-secondary">
                                    <i class="feather icon-rotate-ccw mr-25"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Homeworks Table Card -->
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 25%;">Title & Details</th>
                                        <th>Curriculum & Class</th>
                                        <th>Course / Week</th>
                                        <th>Attached File</th>
                                        <th class="text-center">Status</th>
                                        <th>Creator</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($homeworks as $homework)
                                        <tr>
                                            <!-- Title & Description -->
                                            <td>
                                                <div class="font-weight-bold text-dark font-medium-1 mb-25">
                                                    {{ $homework->title }}
                                                </div>
                                                @if($homework->description)
                                                    <div class="text-muted font-small-2 text-truncate" style="max-width: 320px;">
                                                        {{ strip_tags($homework->description) }}
                                                    </div>
                                                @endif
                                                <div class="font-small-1 text-muted mt-25">
                                                    Created: {{ $homework->created_at->format('d M Y, h:i A') }}
                                                </div>
                                            </td>

                                            <!-- Curriculum & Class -->
                                            <td>
                                                <span class="badge badge-light-primary font-small-2 font-weight-bold">
                                                    {{ $homework->subject->title ?? 'N/A' }}
                                                </span>
                                                @if($homework->topic)
                                                    <span class="badge badge-light-secondary font-small-1">
                                                        {{ $homework->topic->name }}
                                                    </span>
                                                @endif
                                                @if($homework->subtopic)
                                                    <span class="badge badge-light-info font-small-1">
                                                        {{ $homework->subtopic->name }}
                                                    </span>
                                                @endif
                                                <div class="font-small-2 text-dark mt-50">
                                                    <strong>Class:</strong> {{ $homework->class->name ?? 'N/A' }} | 
                                                    <strong>Year:</strong> {{ $homework->yearGroup->title ?? 'N/A' }}
                                                </div>
                                                <div class="font-small-1 text-muted">
                                                    Session: {{ $homework->academic_year }}
                                                </div>
                                            </td>

                                            <!-- Course / Week -->
                                            <td>
                                                @if($homework->courses->isNotEmpty())
                                                    @foreach($homework->courses as $c)
                                                        <div class="mb-50">
                                                            <span class="badge badge-light-warning font-small-2">
                                                                <i class="feather icon-award mr-25"></i>{{ $c->name }}
                                                            </span>
                                                            @php
                                                                $weekId = $c->pivot->week_id;
                                                                $weekObj = $weekId ? \App\Models\Week::find($weekId) : null;
                                                            @endphp
                                                            <div class="font-small-1 text-muted mt-25">
                                                                Week {{ $c->pivot->week }}
                                                                @if($weekObj)
                                                                    ({{ $weekObj->name }}@if($weekObj->due_date), Due: {{ \Carbon\Carbon::parse($weekObj->due_date)->format('d M') }}@endif)
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-light-secondary font-small-1">
                                                        Not assigned
                                                    </span>
                                                @endif
                                            </td>

                                            <!-- Attached File -->
                                            <td>
                                                @if($homework->file_path)
                                                    @php
                                                        $ext = strtolower($homework->file_type ?? pathinfo($homework->file_name ?? '', PATHINFO_EXTENSION));
                                                        $badgeClass = match($ext) {
                                                            'pdf' => 'badge-danger',
                                                            'doc', 'docx' => 'badge-info',
                                                            'txt' => 'badge-secondary',
                                                            default => 'badge-primary',
                                                        };
                                                    @endphp
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge {{ $badgeClass }} mr-50 text-uppercase font-small-1">
                                                            {{ $ext ?: 'FILE' }}
                                                        </span>
                                                        <div class="overflow-hidden" style="max-width: 140px;">
                                                            <div class="text-truncate font-weight-bold font-small-2 text-dark" title="{{ $homework->file_name }}">
                                                                {{ $homework->file_name }}
                                                            </div>
                                                            <div class="font-small-1 text-muted">
                                                                {{ $homework->file_size }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('admin.homeworks.download', $homework->id) }}" class="btn btn-sm btn-outline-primary mt-50 py-25 px-50 font-small-2">
                                                        <i class="feather icon-download mr-25"></i>Download
                                                    </a>
                                                @else
                                                    <span class="text-muted font-small-2">No file</span>
                                                @endif
                                            </td>

                                            <!-- Status -->
                                            <td class="text-center text-nowrap">
                                                <form action="{{ route('admin.homeworks.toggle-status', $homework->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm p-25 border-0 shadow-none bg-transparent" 
                                                            title="Click to {{ $homework->is_active ? 'deactivate' : 'activate' }} homework"
                                                            onclick="return confirm('Are you sure you want to make this homework {{ $homework->is_active ? 'Inactive (hidden from students)' : 'Active (visible to students)' }}?')">
                                                        @if($homework->is_active)
                                                            <span class="badge badge-light-success font-small-2 px-1 py-50 cursor-pointer">
                                                                <i class="feather icon-check-circle mr-25"></i>Active
                                                            </span>
                                                        @else
                                                            <span class="badge badge-light-danger font-small-2 px-1 py-50 cursor-pointer">
                                                                <i class="feather icon-eye-off mr-25"></i>Inactive
                                                            </span>
                                                        @endif
                                                    </button>
                                                </form>
                                            </td>

                                            <!-- Creator -->
                                            <td>
                                                <div class="font-weight-bold font-small-2 text-dark">
                                                    {{ $homework->user->name ?? 'Admin' }}
                                                </div>
                                                <div class="font-small-1 text-muted">
                                                    {{ ucfirst($homework->user->role ?? 'admin') }}
                                                </div>
                                            </td>

                                            <!-- Actions -->
                                            <td class="text-right text-nowrap">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.homeworks.edit', $homework->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Edit Homework">
                                                        <i class="feather icon-edit-2"></i>
                                                    </a>
                                                    @if($homework->file_path)
                                                        <a href="{{ route('admin.homeworks.download', $homework->id) }}"
                                                            class="btn btn-sm btn-outline-info" title="Download File">
                                                            <i class="feather icon-download"></i>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('admin.homeworks.destroy', $homework->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this homework?');" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Homework">
                                                            <i class="feather icon-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="empty-state">
                                                    <i class="feather icon-book-open text-muted" style="font-size: 48px;"></i>
                                                    <h5 class="mt-2 font-weight-bold text-dark">No Homeworks Found</h5>
                                                    <p class="text-muted font-small-3">No homework entries match your criteria, or none have been created yet.</p>
                                                    <a href="{{ route('admin.homeworks.create') }}" class="btn btn-primary btn-sm mt-1">
                                                        <i class="feather icon-plus mr-50"></i>Create First Homework
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($homeworks->hasPages())
                        <div class="card-footer bg-white border-top py-1 d-flex justify-content-between align-items-center">
                            <span class="text-muted font-small-2">
                                Showing {{ $homeworks->firstItem() }} to {{ $homeworks->lastItem() }} of {{ $homeworks->total() }} entries
                            </span>
                            <div>
                                {{ $homeworks->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@extends('layouts.app')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Parent Profile</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.parents.index') }}">Parents</a></li>
                                    <li class="breadcrumb-item">{{ $parent->name }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Parent Info Card -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><i class="feather icon-user"></i> Parent Information</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="text-center mb-2">
                                        <div class="avatar avatar-xl bg-primary" style="width:80px;height:80px;line-height:80px;font-size:2rem;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:#fff;">
                                            {{ strtoupper(substr($parent->name, 0, 1)) }}
                                        </div>
                                        <h4 class="mt-1 mb-0">{{ $parent->name }}</h4>
                                        <small class="text-muted">{{ optional($parent->parentDetail)->relation ?? 'Parent' }}</small>
                                    </div>
                                    <hr>
                                    <ul class="list-unstyled">
                                        <li class="mb-1">
                                            <i class="feather icon-mail mr-1"></i>
                                            <strong>Email:</strong> {{ $parent->email }}
                                        </li>
                                        <li class="mb-1">
                                            <i class="feather icon-user mr-1"></i>
                                            <strong>Username:</strong> {{ $parent->username }}
                                        </li>
                                        <li class="mb-1">
                                            <i class="feather icon-phone mr-1"></i>
                                            <strong>Phone:</strong> {{ optional($parent->parentDetail)->phone ?? 'N/A' }}
                                        </li>
                                        <li class="mb-1">
                                            <i class="feather icon-phone-call mr-1"></i>
                                            <strong>Alternate:</strong> {{ optional($parent->parentDetail)->alternate_phone ?? 'N/A' }}
                                        </li>
                                        <li class="mb-1">
                                            <i class="feather icon-alert-circle mr-1"></i>
                                            <strong>Emergency:</strong> {{ optional($parent->parentDetail)->emergency_contact ?? 'N/A' }}
                                        </li>
                                        <li class="mb-1">
                                            <i class="feather icon-calendar mr-1"></i>
                                            <strong>Joined:</strong> {{ $parent->created_at->format('d M Y') }}
                                        </li>
                                    </ul>
                                    <hr>
                                    <a href="{{ route('admin.parents.edit', $parent->id) }}" class="btn btn-primary btn-block">
                                        <i class="feather icon-edit"></i> Edit Parent
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <!-- Children / Family Hierarchy -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    <i class="feather icon-users"></i> Children
                                    <span class="badge badge-pill badge-primary ml-1">{{ $parent->children->count() }}</span>
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    @if($parent->children->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover-animation">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student Name</th>
                                                        <th>Email</th>
                                                        <th>Group Year</th>
                                                        <th>Academic Year</th>
                                                        <th>Gender</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($parent->children as $index => $child)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.students.edit', $child->user->id) }}" class="text-primary font-weight-bold">
                                                                    {{ $child->user->name }}
                                                                </a>
                                                            </td>
                                                            <td>{{ $child->user->email }}</td>
                                                            <td>
                                                                @if($child->group_year)
                                                                    {{ $child->group_year }}{{ $child->group_year == 1 ? 'st' : ($child->group_year == 2 ? 'nd' : ($child->group_year == 3 ? 'rd' : 'th')) }} year
                                                                @else
                                                                    <span class="text-muted">N/A</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $academicYears[$child->academic_year] ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ ucfirst($child->gender ?? 'N/A') }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.students.edit', $child->user->id) }}" class="btn btn-sm btn-primary mb-1" title="Edit Student">
                                                                    <i class="feather icon-edit"></i>
                                                                </a>
                                                                <form action="{{ route('admin.parents.unlink-student', [$parent->id, $child->user->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Unlink {{ $child->user->name }} from this parent?');">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-warning mb-1" title="Unlink Student">
                                                                        <i class="feather icon-x-circle"></i>
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3">
                                            <i class="feather icon-users" style="font-size: 3rem; color: #ccc;"></i>
                                            <p class="text-muted mt-1 mb-0">No children linked to this parent yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Link Existing Student Card -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"><i class="feather icon-link"></i> Link an Existing Student</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    @if($unassignedStudents->count() > 0)
                                        <form action="{{ route('admin.parents.link-student', $parent->id) }}" method="POST" class="row align-items-end">
                                            @csrf
                                            <div class="col-md-8">
                                                <label class="form-label">Select Unassigned Student</label>
                                                <select class="form-control" name="student_id" required>
                                                    <option value="">-- Select a Student --</option>
                                                    @foreach($unassignedStudents as $student)
                                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-success btn-block">
                                                    <i class="feather icon-link"></i> Link Student
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <p class="text-muted mb-1">All students are already assigned to a parent.</p>
                                        <a href="{{ route('admin.students.create') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="feather icon-plus"></i> Create New Student
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

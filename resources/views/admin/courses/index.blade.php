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
                                Courses Management
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Courses List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row mb-2">
                    <div class="col-9">
                        <p>Manage Courses & Assign Weekly Papers</p>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary float-right">Add Course</a>
                    </div>
                </div>

                <!-- filter start -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Filters</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.courses.index') }}" class="row mb-4 g-2 align-items-end">
                                <div class="col-md-4">
                                    <label for="name" class="form-label">Course Name</label>
                                    <input type="text" name="name" id="name" value="{{ request('name') }}" class="form-control" placeholder="Search by name...">
                                </div>
                                <div class="col-md-4">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="">All</option>
                                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary flex-fill mr-1">Apply Filters</button>
                                    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary flex-fill text-center">Reset</a>
                                </div>
                            </form>
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
                                                <th>Course Name</th>
                                                <th>Description</th>
                                                <th>Total Papers</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($courses as $index => $course)
                                                <tr>
                                                    <td>{{ $courses->firstItem() + $index }}</td>
                                                    <td><strong>{{ $course->name }}</strong></td>
                                                    <td>{{ Str::limit($course->description, 60) }}</td>
                                                    <td>
                                                        <span class="badge badge-pill badge-light-primary font-weight-bold">
                                                            {{ $course->papers()->count() }} Papers
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($course->is_active)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.courses.papers', $course->id) }}" class="btn btn-sm btn-info mb-1">
                                                            <i class="feather icon-file-text"></i> Manage Papers
                                                        </a>
                                                        <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-sm btn-primary mb-1">
                                                            <i class="feather icon-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course? All paper links for this course will be deleted.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger mb-1">
                                                                <i class="feather icon-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No courses found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <nav aria-label="Page navigation">
                                        {{ $courses->appends(request()->query())->links('pagination::bootstrap-5') }}
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
@endsection

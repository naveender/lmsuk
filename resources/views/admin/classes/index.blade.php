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
                                Classes Management
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.classes.index') }}">Classes</a></li>
                                    <li class="breadcrumb-item active">Classes List</li>
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
                        <p>Manage Classes</p>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('admin.classes.create') }}" class="btn btn-primary float-right">Add Class</a>
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
                            <form method="GET" action="{{ route('admin.classes.index') }}" class="row mb-4 g-2 align-items-end">
                                <div class="col-md-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" value="{{ request('name') }}" class="form-control" placeholder="Class Name...">
                                </div>
                                <div class="col-md-2">
                                    <label for="group_year" class="form-label">Group Year</label>
                                    <select name="group_year" id="group_year" class="form-control">
                                        <option value="">All</option>
                                        @foreach($yearGroups as $yg)
                                            <option value="{{ $yg->title }}" {{ request('group_year') == $yg->title ? 'selected' : '' }}>{{ $yg->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="academic_year" class="form-label">Academic Year</label>
                                    <select name="academic_year" id="academic_year" class="form-control">
                                        <option value="">All</option>
                                        @foreach($academicYears as $year)
                                            <option value="{{ $year->name }}" {{ request('academic_year') == $year->name ? 'selected' : '' }}>{{ $year->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="">All</option>
                                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary flex-fill mr-1">Apply Filters</button>
                                    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary flex-fill text-center">Reset</a>
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
                                                <th>Name</th>
                                                <th>Group Year</th>
                                                <th>Academic Year</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($classes as $index => $class)
                                                <tr>
                                                    <td>{{ $classes->firstItem() + $index }}</td>
                                                    <td>{{ $class->name }}</td>
                                                    <td>{{ $class->group_year }}</td>
                                                    <td>{{ $class->academic_year }}</td>
                                                    <td>
                                                        @if($class->is_active)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.classes.students', $class->id) }}" class="btn btn-sm btn-info mb-1">
                                                            <i class="feather icon-users"></i> Students
                                                        </a>
                                                        <a href="{{ route('admin.classes.edit', $class->id) }}" class="btn btn-sm btn-primary mb-1">
                                                            <i class="feather icon-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this class?');">
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
                                                    <td colspan="6" class="text-center">No classes found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <nav aria-label="Page navigation">
                                        {{ $classes->appends(request()->query())->links('pagination::bootstrap-5') }}
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

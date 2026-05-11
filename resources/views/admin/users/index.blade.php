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
                                Users Management
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item">Users</li>
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
                        <p>Manage All Users</p>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary float-right">Add User</a>
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
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                                <li><a data-action=""><i class="feather icon-rotate-cw users-data-filter"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="users-list-filter">
                                <form method="GET" action="{{ route('admin.users.index') }}" class="row mb-4 g-2 align-items-end">
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Search Keyword</label>
                                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Name or Email...">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select name="role" id="role" class="form-control">
                                            <option value="">All Roles</option>
                                            <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="parent" {{ request('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date" class="form-label">Filter by Date</label>
                                        <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-control">
                                    </div>
                                    <div class="col-md-4 d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary flex-fill mr-1">Apply Filters</button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary flex-fill text-center">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- filter end -->

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
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($users as $index => $user)
                                                <tr>
                                                    <td>{{ $users->firstItem() + $index }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td><span class="badge badge-info">{{ ucfirst($user->role) }}</span></td>
                                                    <td>
                                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary mb-1">
                                                            <i class="feather icon-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                                                    <td colspan="5" class="text-center">No users found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <nav aria-label="Page navigation">
                                        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

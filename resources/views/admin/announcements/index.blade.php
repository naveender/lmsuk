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
                        <p>Manage Announcements</p>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary float-right">Add
                            Announcement</a>
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
                                <form method="GET" action="{{ route('admin.announcements.index') }}"
                                    class="row mb-4 g-2 align-items-end">
                                    <div class="col-md-2">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>Text Only
                                            </option>
                                            <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>Media Only
                                            </option>
                                            <option value="3" {{ request('type') == '3' ? 'selected' : '' }}>Text &
                                                Media</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">All Statuses</option>
                                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="search" class="form-label">Search Keyword</label>
                                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                                            class="form-control" placeholder="Type here...">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date" class="form-label">Filter by Date</label>
                                        <input type="date" name="date" id="date" value="{{ request('date') }}"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-4 d-flex justify-content-between">
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
                                <!-- DataTable starts -->
                                <div class="table-responsive">
                                    <table class="table table-hover-animation">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($announcements as $index => $announcement)
                                                <tr>
                                                    <td>{{ $announcements->firstItem() + $index }}</td>
                                                    <td>{{ $announcement->title }}</td>
                                                    <td>
                                                        @if ($announcement->type == 1)
                                                            Text Only
                                                        @elseif($announcement->type == 2)
                                                            Media Only
                                                        @elseif($announcement->type == 3)
                                                            Text & Media
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($announcement->status)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-secondary">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $announcement->created_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                                                            class="btn btn-sm btn-primary mb-1">
                                                            <i class="feather icon-edit"></i> Edit
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.announcements.destroy', $announcement->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this announcement?');">
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
                                                    <td colspan="6" class="text-center">No announcements found.</td>
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
@endsection

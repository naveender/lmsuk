{{-- FILE: resources\views\inventory.blade.php --}}
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
                                Topics Management
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Topics</a></li>
                                    <li class="breadcrumb-item">Topics List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row mb-2">
                    <div class="col-9">
                        <p>Manage Topics</p>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('add.topic') }}" class="btn btn-primary float-right">Add Topic</a>
                    </div>
                </div>
                <!--  filter start -->
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
                                <form method="GET" action="{{ route('topics') }}" class="row mb-4 g-2 align-items-end">
                                    <div class="col-md-3">
                                        <label for="filter_by" class="form-label">Filter By</label>
                                        <select name="filter_by" id="filter_by" class="form-control">
                                            <option value="topic" {{ request('filter_by') == 'topic' ? 'selected' : '' }}>
                                                Topic</option>
                                            <option value="subtopic"
                                                {{ request('filter_by') == 'subtopic' ? 'selected' : '' }}>Subtopic</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Search Keyword</label>
                                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                                            class="form-control" placeholder="Type here...">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="date" class="form-label">Filter by Date</label>
                                        <input type="date" name="date" id="date" value="{{ request('date') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-3 d-flex justify-content-between">
                                        <button type="submit" class="btn btn-primary flex-fill mr-1">Apply Filters</button>
                                        <a href="{{ route('topics') }}"
                                            class="btn btn-secondary flex-fill text-center">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <!--  filter end -->
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
                                                <th>Name</th>
                                                <th>Slug</th>
                                                <th>Sub Topics</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topics as $index => $topic)
                                                <tr>
                                                    <td>{{ $topics->firstItem() + $index }}</td>
                                                    <td>{{ $topic->name }}</td>
                                                    <td>{{ $topic->slug }}</td>
                                                    <td>
                                                        <div class="d-flex flex-column align-items-start">
                                                            @foreach ($topic->subtopics as $subtopic)
                                                                <div
                                                                    class="d-flex align-items-center border p-1 rounded mb-1">
                                                                    <span
                                                                        class="badge badge-primary mr-1">{{ $subtopic->name }}</span>
                                                                    <a href="{{ route('topics.edit', $subtopic->id) }}"
                                                                        class="btn btn-sm btn-icon btn-outline-primary mr-1"
                                                                        title="Edit">
                                                                        <i class="feather icon-edit"></i>
                                                                    </a>
                                                                    <form
                                                                        action="{{ route('topics.destroy', $subtopic->id) }}"
                                                                        method="POST" class="m-0"
                                                                        onsubmit="return confirm('Are you sure you want to delete this subtopic?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-icon btn-outline-danger"
                                                                            title="Delete">
                                                                            <i class="feather icon-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('topics.edit', $topic->id) }}"
                                                            class="btn btn-sm btn-primary mb-1">
                                                            <i class="feather icon-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('topics.destroy', $topic->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this topic?');">
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
                                                    <td colspan="5" class="text-center">No topics found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- 📄 Pagination -->
                                    <nav aria-label="Page navigation">
                                        {{ $topics->links('pagination::bootstrap-5') }}
                                    </nav>

                                </div>

                            </div>
                        </div>
                    </div>
                </section>

                <div id="download-progress-container"
                    style="position: fixed; bottom: 20px; right: 20px; width: 300px;display:none; ">
                    <div class="card text-white bg-gradient-info p-3">

                        <h6 class="text-white">Downloading in progress...</h6>
                        <div class="progress progress-bar-default progress-xl">
                            <div id="download-progress-bar" class="progress-bar progress-bar-striped" role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
                        </div>


                    </div>
                </div>

                <!-- Data list view end -->
            </div>


        </div>
    </div>
    <!-- END: Content-->
@endsection

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
                <div class="row">
                    <div class="col-12">
                        <p>Manage Topics</p>
                    </div>
                    <a href="{{ route('add.topic') }}" class="btn btn-primary">Add Topic</a>
                </div>
                <!-- Data list view starts -->
                <section id="data-list-view" class="data-list-view-header">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <!-- 🔍 Filter Form -->
                                <form method="GET" action="{{ route('topics') }}" class="row mb-4 g-2 align-items-end">
                                    <div class="col-md-4">
                                        <label for="search" class="form-label">Search Topic Name</label>
                                        <input type="text" name="search" id="search" value=""
                                            class="form-control" placeholder="e.g. Topic Name">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="date" class="form-label">Filter by Date</label>
                                        <input type="date" name="date" id="date" value=""
                                            class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                                    </div>

                                    <div class="col-md-2">
                                        <a href="{{ route('topics') }}" class="btn btn-secondary w-100">Reset</a>
                                    </div>
                                </form>
                                <!-- DataTable starts -->
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Slug</th>
                                                <th>Parent</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($topics as $index => $topic)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $topic->name }}</td>
                                                    <td>{{ $topic->slug }}</td>
                                                    <td>{{ $topic->parent }}</td>

                                                    <td>
                                                        <a href="{{ route('topics.edit', $topic->id) }}"
                                                            class="btn btn-sm btn-primary download-btn">
                                                            <i class="feather icon-edit"></i> Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No backups found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>



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

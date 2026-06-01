@extends('layouts.app')

@section('title', 'Topics - ' . $subject->title)

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            
            <!-- Breadcrumbs -->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">{{ $subject->title }}</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('student.assessments') }}">Assessments</a></li>
                                    <li class="breadcrumb-item active">Topics</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Search & Filters Card -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <form method="GET" action="{{ route('student.assessments.topics', $subject->id) }}" class="row align-items-center">
                            <div class="col-md-8 col-sm-12 mb-2 mb-md-0">
                                <div class="position-relative has-icon-left">
                                    <input type="text" name="topic_name" class="form-control" placeholder="Search topics by name..." value="{{ request('topic_name') }}">
                                    <div class="form-control-position">
                                        <i class="feather icon-search"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12 d-flex justify-content-md-start justify-content-between">
                                <button type="submit" class="btn btn-primary mr-2 flex-grow-1 flex-md-grow-0">
                                    <i class="feather icon-filter mr-1"></i> Filter
                                </button>
                                @if(request()->filled('topic_name'))
                                    <a href="{{ route('student.assessments.topics', $subject->id) }}" class="btn btn-outline-danger flex-grow-1 flex-md-grow-0">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Topics Table Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-transparent border-0 pt-3 pb-0">
                        <h4 class="card-title font-weight-bold">Available Topics</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Topic</th>
                                            <th class="text-center">Test Available</th>
                                            <th class="text-center">Test Attempted</th>
                                            <th class="text-center">Easy</th>
                                            <th class="text-center">Medium</th>
                                            <th class="text-center">Hard</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topics as $topic)
                                            <tr>
                                                <td class="font-weight-bold text-dark text-truncate" style="max-width: 250px;">
                                                    {{ $topic->name }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-light-primary px-3 py-1 font-medium-1">
                                                        {{ $topic->test_available }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-light-success px-3 py-1 font-medium-1">
                                                        {{ $topic->test_attempted }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-success px-3 py-1 font-medium-1">
                                                        {{ $topic->easy_count }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-warning px-3 py-1 font-medium-1 text-white">
                                                        {{ $topic->medium_count }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-pill badge-danger px-3 py-1 font-medium-1">
                                                        {{ $topic->hard_count }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('student.topics.subtopics', $topic->id) }}" class="btn btn-sm btn-primary px-3 py-1">
                                                        Go To Tests <i class="feather icon-chevron-right ml-1"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <div class="my-2">
                                                        <i class="feather icon-info font-large-2 text-warning mb-2"></i>
                                                        <h5>No Topics Found</h5>
                                                        <p class="mb-0">We couldn't find any topics matching your search or configuration.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $topics->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .badge-light-primary {
            background-color: rgba(115, 103, 240, 0.1);
            color: #7367f0 !important;
        }
        .badge-light-success {
            background-color: rgba(40, 199, 111, 0.1);
            color: #28c76f !important;
        }
        .font-medium-1 {
            font-size: 0.95rem;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .thead-light th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
    </style>
@endpush

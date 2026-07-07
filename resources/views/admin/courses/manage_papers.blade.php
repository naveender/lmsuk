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
                                Manage Course Papers
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.courses.index') }}">Courses</a></li>
                                    <li class="breadcrumb-item active">{{ $course->name }}</li>
                                    <li class="breadcrumb-item active">Manage Papers</li>
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

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <!-- Link Paper Form -->
                    <div class="col-md-4 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Assign Paper to Course</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form action="{{ route('admin.courses.papers.add', $course->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="paper_id">Select Paper</label>
                                            <select name="paper_id" id="paper_id" class="form-control select2" required>
                                                <option value="">-- Choose Paper --</option>
                                                @foreach($allPapers as $paper)
                                                    <option value="{{ $paper->id }}">
                                                        {{ $paper->title }} 
                                                        ({{ ucfirst($paper->type) }} 
                                                        @if($paper->subject)
                                                            - {{ $paper->subject->title }}
                                                        @endif)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="week">Week Number (Parameter)</label>
                                            <input type="number" name="week" id="week" class="form-control" min="1" placeholder="e.g. 1, 2, 3..." required>
                                            <small class="text-muted">Specifies the weekly order of the paper in this course.</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block">Assign Paper</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Papers List -->
                    <div class="col-md-8 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Currently Assigned Papers ({{ $coursePapers->count() }})</h4>
                                <span class="badge badge-pill badge-light-info">Sorted weekly-wise</span>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-hover-animation">
                                            <thead>
                                                <tr>
                                                    <th style="width: 100px;">Week</th>
                                                    <th>Paper Name</th>
                                                    <th>Type</th>
                                                    <th>Subject</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($coursePapers as $paper)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-pill badge-primary font-weight-bold" style="font-size: 0.9rem; padding: 0.5em 0.8em;">
                                                                Week {{ $paper->pivot->week }}
                                                            </span>
                                                        </td>
                                                        <td><strong>{{ $paper->title }}</strong></td>
                                                        <td>
                                                            <span class="badge badge-light-warning">
                                                                {{ ucfirst($paper->type) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            {{ $paper->subject ? $paper->subject->title : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            <form action="{{ route('admin.courses.papers.remove', [$course->id, $paper->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this paper from the course?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="feather icon-trash-2"></i> Remove
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No papers assigned to this course yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

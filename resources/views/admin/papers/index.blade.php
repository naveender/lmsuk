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
                                Exam & Test Papers
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Papers</li>
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
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row mb-2">
                    <div class="col-9">
                        <p class="text-muted">Manage, configure, and assemble questions into tests or exams.</p>
                    </div>
                    <div class="col-3 text-right">
                        <a href="{{ route('admin.papers.create') }}" class="btn btn-primary">
                            <i class="feather icon-plus"></i> Create New Paper
                        </a>
                    </div>
                </div>

                <!-- Filter Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom py-1">
                        <h4 class="card-title font-weight-bold text-primary mb-0">Filters</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body py-2">
                            <form method="GET" action="{{ route('admin.papers.index') }}" class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="search" class="font-weight-bold font-small-3 text-dark">Search Title</label>
                                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Search by paper title...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label for="type" class="font-weight-bold font-small-3 text-dark">Paper Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="test" {{ request('type') === 'test' ? 'selected' : '' }}>Test</option>
                                            <option value="exam" {{ request('type') === 'exam' ? 'selected' : '' }}>Exam</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label for="subject_id" class="font-weight-bold font-small-3 text-dark">Subject</label>
                                        <select name="subject_id" id="subject_id" class="form-control">
                                            <option value="">All Subjects</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label for="class_id" class="font-weight-bold font-small-3 text-dark">Class</label>
                                        <select name="class_id" id="class_id" class="form-control">
                                            <option value="">All Classes</option>
                                            @foreach($classes as $cls)
                                                <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex">
                                    <button type="submit" class="btn btn-primary flex-fill mr-50">
                                        <i class="feather icon-filter"></i> Apply
                                    </button>
                                    <a href="{{ route('admin.papers.index') }}" class="btn btn-secondary flex-fill text-center">
                                        Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Papers Table -->
                <section id="data-list-view" class="data-list-view-header">
                    <div class="card shadow-sm">
                        <div class="card-content">
                            <div class="card-body card-dashboard p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover-animation mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="py-1">#</th>
                                                <th class="py-1">Paper Details</th>
                                                <th class="py-1">Type</th>
                                                <th class="py-1">Subject</th>
                                                <th class="py-1">Class / Year</th>
                                                <th class="py-1">Questions</th>
                                                <th class="py-1">Total Marks</th>
                                                <th class="py-1">Time</th>
                                                <th class="py-1 text-right pr-2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($papers as $index => $paper)
                                                <tr>
                                                    <td class="align-middle">{{ $papers->firstItem() + $index }}</td>
                                                    <td class="align-middle">
                                                        <span class="font-weight-bold text-dark d-block">{{ $paper->title }}</span>
                                                        <span class="text-muted font-small-2">
                                                            By: {{ $paper->user->name ?? 'System' }} | Acad Year: {{ $paper->academic_year ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        @if($paper->type === 'exam')
                                                            <span class="badge badge-success badge-pill">
                                                                <i class="feather icon-award"></i> Exam
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning badge-pill text-white">
                                                                <i class="feather icon-file-text"></i> Test
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle font-weight-bold text-muted">{{ $paper->subject->title ?? '—' }}</td>
                                                    <td class="align-middle">
                                                        <span class="text-dark d-block font-weight-bold">{{ $paper->class->name ?? '—' }}</span>
                                                        <span class="text-muted font-small-2">Difficulty: {{ ucfirst($paper->difficulty ?? 'medium') }}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="badge badge-primary font-small-3 font-weight-bold px-75 py-25 rounded">
                                                            {{ $paper->questions_count ?? $paper->questions()->count() }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle text-center font-weight-bold text-primary">
                                                        {{ ($paper->questions_count ?? $paper->questions()->count()) * ($paper->default_marks ?: 1) }}
                                                    </td>
                                                    <td class="align-middle font-weight-bold">{{ $paper->total_time }} min</td>
                                                    <td class="align-middle text-right pr-2">
                                                        <a href="{{ route('admin.papers.edit', $paper->id) }}" class="btn btn-sm btn-flat-primary mr-25" title="Edit Paper">
                                                            <i class="feather icon-edit font-medium-1"></i> Edit
                                                        </a>
                                                        <form action="{{ route('admin.papers.destroy', $paper->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this paper? All assembled question links will be removed.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-flat-danger" title="Delete Paper">
                                                                <i class="feather icon-trash font-medium-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-3 text-muted">
                                                        <i class="feather icon-info font-large-1 d-block mb-50"></i>
                                                        No exam or test papers found matching the search criteria.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div class="px-2 py-1 bg-white border-top d-flex justify-content-between align-items-center">
                                        <span class="text-muted font-small-3">
                                            Showing {{ $papers->firstItem() ?? 0 }} to {{ $papers->lastItem() ?? 0 }} of {{ $papers->total() }} papers
                                        </span>
                                        <nav aria-label="Page navigation" class="mb-0">
                                            {{ $papers->links('pagination::bootstrap-4') }}
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

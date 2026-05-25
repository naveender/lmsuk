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
                                Questions Directory
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Question Bank</a></li>
                                    <li class="breadcrumb-item active">All Questions</li>
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
                        <p>Manage all questions in the question bank.</p>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('admin.questions.create') }}" class="btn btn-primary float-right">
                            <i class="feather icon-plus"></i> Add Question
                        </a>
                    </div>
                </div>

                <!-- Filter Card -->
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
                            <form method="GET" action="{{ route('admin.questions.index') }}" class="row mb-4 g-2 align-items-end">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Search question...">
                                </div>
                                <div class="col-md-2">
                                    <label for="type" class="form-label">Question Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">All Types</option>
                                        @foreach(\App\Models\Question::TYPES as $key => $label)
                                            <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="subject_id" class="form-label">Subject</label>
                                    <select name="subject_id" id="subject_id" class="form-control">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="difficulty" class="form-label">Difficulty</label>
                                    <select name="difficulty" id="difficulty" class="form-control">
                                        <option value="">All</option>
                                        @foreach(\App\Models\Question::DIFFICULTIES as $key => $label)
                                            <option value="{{ $key }}" {{ request('difficulty') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary flex-fill mr-1">Apply Filters</button>
                                    <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary flex-fill text-center">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Questions Table -->
                <section id="data-list-view" class="data-list-view-header">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table table-hover-animation">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Question</th>
                                                <th>Type</th>
                                                <th>Subject</th>
                                                <th>Difficulty</th>
                                                <th>Marks</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($questions as $index => $question)
                                                <tr>
                                                    <td>{{ $questions->firstItem() + $index }}</td>
                                                    <td>{{ Str::limit($question->title, 60) }}</td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $question->type_label }}</span>
                                                    </td>
                                                    <td>{{ $question->subject->title ?? '—' }}</td>
                                                    <td>
                                                        @if($question->difficulty)
                                                            @php
                                                                $diffColors = ['easy' => 'success', 'medium' => 'warning', 'hard' => 'danger'];
                                                            @endphp
                                                            <span class="badge badge-{{ $diffColors[$question->difficulty] ?? 'secondary' }}">
                                                                {{ ucfirst($question->difficulty) }}
                                                            </span>
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td>{{ $question->marks }}</td>
                                                    <td>
                                                        @if($question->is_active)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn btn-sm btn-primary mb-1">
                                                            <i class="feather icon-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this question?');">
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
                                                    <td colspan="8" class="text-center">No questions found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <nav aria-label="Page navigation">
                                        {{ $questions->links('pagination::bootstrap-5') }}
                                    </nav>
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

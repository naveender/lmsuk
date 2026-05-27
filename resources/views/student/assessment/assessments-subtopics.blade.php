@extends('layouts.app')

@section('title', 'Subtopics & Tests - ' . $topic->name)

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
                            <h2 class="content-header-title float-left mb-0">{{ $topic->name }}</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('student.assessments') }}">Assessments</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('student.assessments.topics', $subject->id) }}">Topics</a></li>
                                    <li class="breadcrumb-item active">Subtopics</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Search & Filters -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <form method="GET" action="{{ route('student.topics.subtopics', $topic->id) }}" class="row">
                            <div class="col-md-5 col-sm-12 mb-2 mb-md-0">
                                <label for="subtopic_name" class="font-weight-bold text-dark">Filter by Subtopic</label>
                                <select name="subtopic_name" id="subtopic_name" class="form-control">
                                    <option value="">-- All Subtopics --</option>
                                    @foreach($allSubtopics as $sub)
                                        <option value="{{ $sub->name }}" {{ request('subtopic_name') == $sub->name ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 col-sm-12 mb-2 mb-md-0">
                                <label for="paper_name" class="font-weight-bold text-dark">Search Paper Name</label>
                                <div class="position-relative has-icon-left">
                                    <input type="text" name="paper_name" id="paper_name" class="form-control" placeholder="Search papers..." value="{{ request('paper_name') }}">
                                    <div class="form-control-position">
                                        <i class="feather icon-file-text"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-1 flex-grow-1">
                                    <i class="feather icon-filter mr-1"></i> Filter
                                </button>
                                @if(request()->filled('subtopic_name') || request()->filled('paper_name'))
                                    <a href="{{ route('student.topics.subtopics', $topic->id) }}" class="btn btn-outline-danger">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Subtopics Collapsible List -->
                <div class="subtopics-list">
                    @forelse($subtopics as $subtopic)
                        <div class="card shadow-sm border-0 mb-3 overflow-hidden">
                            <!-- Accordion Header -->
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3 clickable" 
                                 data-toggle="collapse" 
                                 data-target="#collapseSubtopic{{ $subtopic->id }}" 
                                 aria-expanded="true" 
                                 aria-controls="collapseSubtopic{{ $subtopic->id }}">
                                <div class="d-flex align-items-center">
                                    <div class="subtopic-bullet mr-2 bg-primary"></div>
                                    <h5 class="mb-0 text-dark font-weight-bold">{{ $subtopic->name }}</h5>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-pill badge-primary mr-2">
                                        {{ $subtopic->papers->count() }} {{ \Illuminate\Support\Str::plural('Paper', $subtopic->papers->count()) }}
                                    </span>
                                    <i class="feather icon-chevron-down font-medium-3 collapse-icon"></i>
                                </div>
                            </div>

                            <!-- Accordion Content -->
                            <div id="collapseSubtopic{{ $subtopic->id }}" class="collapse show">
                                <div class="card-body p-0">
                                    @forelse($subtopic->papers as $paper)
                                        <div class="paper-row border-bottom p-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                                            
                                            <!-- Paper Info -->
                                            <div class="mb-3 mb-md-0" style="max-width: 70%;">
                                                <div class="d-flex align-items-center flex-wrap mb-1">
                                                    <h6 class="mb-0 font-weight-bold text-dark mr-2">{{ $paper->title }}</h6>
                                                    
                                                    @php
                                                        $difficultyColors = [
                                                            'easy' => 'badge-success',
                                                            'medium' => 'badge-warning text-white',
                                                            'hard' => 'badge-danger'
                                                        ];
                                                        $diffColor = $difficultyColors[$paper->difficulty] ?? 'badge-secondary';
                                                    @endphp
                                                    <span class="badge badge-pill {{ $diffColor }} text-capitalize mr-2">
                                                        {{ $paper->difficulty }}
                                                    </span>

                                                    @if($paper->active_attempt)
                                                        @if($paper->active_attempt->status == 'paused')
                                                            <span class="badge badge-pill badge-warning text-white">
                                                                <i class="feather icon-pause mr-1"></i> Paused
                                                            </span>
                                                        @else
                                                            <span class="badge badge-pill badge-info">
                                                                <i class="feather icon-play mr-1"></i> In Progress
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                
                                                <div class="d-flex align-items-center text-muted font-small-3 flex-wrap">
                                                    <span class="mr-3"><i class="feather icon-help-circle mr-1"></i>{{ $paper->questions_count ?? $paper->questions()->count() }} Questions</span>
                                                    <span class="mr-3"><i class="feather icon-clock mr-1"></i>{{ $paper->total_time ? $paper->total_time . ' mins' : 'Unlimited' }}</span>
                                                    <span><i class="feather icon-check-square mr-1"></i>{{ $paper->completed_attempts_count }} Completed Attempts</span>
                                                </div>
                                            </div>

                                            <!-- Paper Action Buttons -->
                                            <div class="d-flex align-items-center">
                                                <!-- Action form -->
                                                <form action="{{ route('student.papers.start', $paper->id) }}" method="POST">
                                                    @csrf
                                                    @if($paper->active_attempt)
                                                        @if($paper->active_attempt->status == 'paused')
                                                            <button type="submit" class="btn btn-warning text-white btn-sm px-4 mr-2 font-weight-bold">
                                                                Resume <i class="feather icon-play ml-1"></i>
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-info btn-sm px-4 mr-2 font-weight-bold">
                                                                Resume <i class="feather icon-play ml-1"></i>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="submit" class="btn btn-primary btn-sm px-4 mr-2 font-weight-bold">
                                                            Start <i class="feather icon-arrow-right ml-1"></i>
                                                        </button>
                                                    @endif
                                                </form>

                                                <!-- Attempts History Button -->
                                                @if($paper->attempts->isNotEmpty())
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                            data-toggle="collapse" 
                                                            data-target="#collapseAttempts{{ $paper->id }}" 
                                                            title="View Attempt History">
                                                        <i class="feather icon-history"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Attempts History Collapsible -->
                                        @if($paper->attempts->isNotEmpty())
                                            <div id="collapseAttempts{{ $paper->id }}" class="collapse bg-light p-3 border-bottom">
                                                <h6 class="font-weight-bold mb-2 text-secondary">Attempt History</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered bg-white mb-0">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Status</th>
                                                                <th class="text-center">Score</th>
                                                                <th class="text-center">Percentage</th>
                                                                <th class="text-center">Time Spent</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($paper->attempts as $attempt)
                                                                <tr>
                                                                    <td>{{ $attempt->created_at->format('d M Y, h:i A') }}</td>
                                                                    <td>
                                                                        @if($attempt->status == 'completed')
                                                                            <span class="badge badge-pill badge-success">Completed</span>
                                                                        @elseif($attempt->status == 'paused')
                                                                            <span class="badge badge-pill badge-warning text-white">Paused</span>
                                                                        @else
                                                                            <span class="badge badge-pill badge-info">In Progress</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">{{ $attempt->score }} / {{ $attempt->max_score }}</td>
                                                                    <td class="text-center">
                                                                        @if($attempt->max_score > 0)
                                                                            {{ round(($attempt->score / $attempt->max_score) * 100) }}%
                                                                        @else
                                                                            0%
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ gmdate('H:i:s', $attempt->time_spent) }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($attempt->status == 'completed')
                                                                            <a href="{{ route('student.attempts.result', $attempt->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2">
                                                                                View Result
                                                                            </a>
                                                                        @else
                                                                            <a href="{{ route('student.attempts.take', $attempt->id) }}" class="btn btn-sm btn-outline-info py-0 px-2">
                                                                                Resume
                                                                            </a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="p-4 text-center text-muted">
                                            No papers available for this subtopic.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card shadow-sm border-0 p-5 text-center text-muted">
                            <i class="feather icon-info font-large-2 text-warning mb-2"></i>
                            <h5>No Subtopics Found</h5>
                            <p class="mb-0">There are no subtopics matching your filters or configured for this topic.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .subtopic-bullet {
            width: 8px;
            height: 18px;
            border-radius: 4px;
        }
        .clickable {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .clickable:hover {
            background-color: #f1f2f4 !important;
        }
        .paper-row {
            transition: background-color 0.15s;
        }
        .paper-row:hover {
            background-color: #fafbfc;
        }
        .collapse-icon {
            transition: transform 0.2s;
        }
        [aria-expanded="false"] .collapse-icon {
            transform: rotate(-90deg);
        }
        .thead-light th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
@endpush

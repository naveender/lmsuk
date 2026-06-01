@extends('layouts.app')

@section('title', 'Subtopics & Tests - ' . $topic->name)

@section('content')
    @php
        // Calculate statistics for header progress
        $totalSubtopicsCount = $subtopics->count();
        $totalPapersCount = 0;
        $completedPapersCount = 0;
        $activeAttemptCount = 0;
        $totalAttemptsCount = 0;

        foreach ($subtopics as $sub) {
            $totalPapersCount += $sub->papers->count();
            foreach ($sub->papers as $p) {
                $totalAttemptsCount += $p->attempts->count();
                if ($p->active_attempt) {
                    $activeAttemptCount++;
                }
                if ($p->completed_attempts_count > 0) {
                    $completedPapersCount++;
                }
            }
        }
        $completionRate = $totalPapersCount > 0 ? round(($completedPapersCount / $totalPapersCount) * 100) : 0;
    @endphp

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            <!-- Breadcrumbs -->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-3">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb-modern">
                                    <li class="breadcrumb-modern-item"><a
                                            href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-modern-item"><a
                                            href="{{ route('student.assessments') }}">Assessments</a></li>
                                    <li class="breadcrumb-modern-item"><a
                                            href="{{ route('student.assessments.topics', $subject->id) }}">Topics</a></li>
                                    <li class="breadcrumb-modern-item active">Subtopics</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">

                <!-- Topic Hero & Statistics Card -->
                <div class="topic-hero-card card border-0 shadow-sm overflow-hidden mb-4">
                    <div class="card-body p-4 position-relative">
                        <div class="hero-bg-shapes">
                            <div class="shape shape-1"></div>
                            <div class="shape shape-2"></div>
                        </div>
                        <div class="row align-items-center position-relative">
                            <div class="col-lg-8 col-md-12 mb-3 mb-lg-0">
                                <div class="d-flex align-items-center flex-wrap mb-2">
                                    <span class="badge badge-hero-category text-uppercase mr-2">{{ $subject->title }}</span>
                                    <span class="badge badge-hero-tag text-uppercase"><i
                                            class="feather icon-award mr-1"></i>Practicing</span>
                                </div>
                                <h1 class="text-white font-weight-extrabold hero-title mb-2">{{ $topic->name }}</h1>
                                <p class="text-white opacity-75 font-medium-1 mb-0">Master each subtopic by taking practice
                                    papers. Track your attempts and view immediate detailed feedback.</p>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="hero-stats-card p-3 rounded shadow-xs">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-white font-weight-bold">Topic Progress</span>
                                        <span
                                            class="text-white font-weight-bold font-medium-2">{{ $completionRate }}%</span>
                                    </div>
                                    <div class="progress progress-hero mb-3">
                                        <div class="progress-bar bg-white-progress" role="progressbar"
                                            style="width: {{ $completionRate }}%" aria-valuenow="{{ $completionRate }}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="row text-center text-white pt-2 border-top border-white-opacity">
                                        <div class="col-4 border-right border-white-opacity">
                                            <div class="font-weight-bold font-medium-3">{{ $totalSubtopicsCount }}</div>
                                            <div class="font-small-2 text-light-opacity">Subtopics</div>
                                        </div>
                                        <div class="col-4 border-right border-white-opacity">
                                            <div class="font-weight-bold font-medium-3">{{ $totalPapersCount }}</div>
                                            <div class="font-small-2 text-light-opacity">Papers</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="font-weight-bold font-medium-3 text-success-light">
                                                {{ $completedPapersCount }}</div>
                                            <div class="font-small-2 text-light-opacity">Done</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search & Filters -->
                <div class="card search-filter-card mb-4 shadow-sm border-0">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('student.topics.subtopics', $topic->id) }}"
                            class="row align-items-end">
                            <div class="col-md-5 col-sm-12 mb-3 mb-md-0">
                                <label for="subtopic_name" class="filter-label"><i
                                        class="feather icon-layers mr-1"></i>Filter by Subtopic</label>
                                <div class="custom-select-wrapper">
                                    <select name="subtopic_name" id="subtopic_name" class="form-control select-modern">
                                        <option value="">-- All Subtopics --</option>
                                        @foreach($allSubtopics as $sub)
                                            <option value="{{ $sub->name }}" {{ request('subtopic_name') == $sub->name ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="feather icon-chevron-down select-chevron"></i>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12 mb-3 mb-md-0">
                                <label for="paper_name" class="filter-label"><i
                                        class="feather icon-file-text mr-1"></i>Search Paper Name</label>
                                <div class="position-relative has-icon-left">
                                    <input type="text" name="paper_name" id="paper_name" class="form-control input-modern"
                                        placeholder="Search papers..." value="{{ request('paper_name') }}">
                                    <div class="form-control-position">
                                        <i class="feather icon-search text-muted"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-12 d-flex">
                                <button type="submit" class="btn btn-primary btn-modern mr-2 flex-grow-1">
                                    <i class="feather icon-filter mr-1"></i> Filter
                                </button>
                                @if(request()->filled('subtopic_name') || request()->filled('paper_name'))
                                    <a href="{{ route('student.topics.subtopics', $topic->id) }}"
                                        class="btn btn-outline-danger btn-modern-danger" title="Clear Filters">
                                        <i class="feather icon-x"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Subtopics List -->
                <div class="subtopics-list">
                    @forelse($subtopics as $subtopic)
                        @php
                            $subtopicPapersCount = $subtopic->papers->count();
                            $subtopicCompletedCount = $subtopic->papers->where('completed_attempts_count', '>', 0)->count();
                            $subtopicProgress = $subtopicPapersCount > 0 ? round(($subtopicCompletedCount / $subtopicPapersCount) * 100) : 0;
                        @endphp
                        <div class="subtopic-card card shadow-sm border-0 mb-3 overflow-hidden">
                            <!-- Accordion Header -->
                            <div class="subtopic-header d-flex justify-content-between align-items-center py-3 px-4 clickable"
                                data-toggle="collapse" data-target="#collapseSubtopic{{ $subtopic->id }}" aria-expanded="true"
                                aria-controls="collapseSubtopic{{ $subtopic->id }}">
                                <div class="d-flex align-items-center">
                                    <div class="subtopic-bullet mr-3"></div>
                                    <div>
                                        <h5 class="mb-1 text-dark font-weight-bold subtopic-title">{{ $subtopic->name }}</h5>
                                        <div class="d-flex align-items-center text-muted font-small-3 flex-wrap">
                                            <span class="mr-2">Completed {{ $subtopicCompletedCount }} of
                                                {{ $subtopicPapersCount }} papers</span>
                                            <div class="subtopic-mini-progress d-none d-sm-block">
                                                <div class="subtopic-mini-bar" style="width: {{ $subtopicProgress }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-square badge-info badge-papers-count mr-3">
                                        {{ $subtopicPapersCount }}
                                        {{ \Illuminate\Support\Str::plural('Paper', $subtopicPapersCount) }}
                                    </span>
                                    <div class="chevron-box">
                                        <i class="feather icon-chevron-down collapse-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Accordion Content -->
                            <div id="collapseSubtopic{{ $subtopic->id }}" class="collapse show">
                                <div class="card-body p-0 border-top-light">
                                    @forelse($subtopic->papers as $paper)
                                        <div
                                            class="paper-row border-bottom p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                                            <!-- Paper Info -->
                                            <div class="mb-3 mb-md-0 max-width-70">
                                                <div class="d-flex align-items-center flex-wrap mb-2">
                                                    <h6 class="mb-0 font-weight-bold text-dark paper-title mr-3">{{ $paper->title }}
                                                    </h6>

                                                    @php
                                                        $difficultyColors = [
                                                            'easy' => 'badge-soft-success',
                                                            'medium' => 'badge-soft-warning',
                                                            'hard' => 'badge-soft-danger'
                                                        ];
                                                        $diffColor = $difficultyColors[$paper->difficulty] ?? 'badge-soft-secondary';
                                                    @endphp
                                                    <span class="badge badge-pill {{ $diffColor }} text-capitalize mr-2">
                                                        <span class="badge-dot mr-1"></span>{{ $paper->difficulty }}
                                                    </span>

                                                    @if($paper->active_attempt)
                                                        @if($paper->active_attempt->status == 'paused')
                                                            <span class="badge badge-pill badge-soft-paused pulse-warning mr-2">
                                                                <span class="badge-dot mr-1"></span>Paused
                                                            </span>
                                                        @else
                                                            <span class="badge badge-pill badge-soft-progress pulse-info mr-2">
                                                                <span class="badge-dot mr-1"></span>In Progress
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>

                                                <div class="d-flex align-items-center text-muted font-small-3 flex-wrap paper-meta">
                                                    <span class="mr-4"><i
                                                            class="feather icon-help-circle text-primary mr-1"></i><strong>{{ $paper->questions_count ?? $paper->questions()->count() }}</strong>
                                                        Questions</span>
                                                    <span class="mr-4"><i
                                                            class="feather icon-clock text-success mr-1"></i><strong>{{ $paper->total_time ? $paper->total_time . ' mins' : 'Unlimited' }}</strong>
                                                        Duration</span>
                                                    <span><i
                                                            class="feather icon-check-square text-info mr-1"></i><strong>{{ $paper->completed_attempts_count }}</strong>
                                                        Completed</span>
                                                </div>
                                            </div>

                                            <!-- Paper Action Buttons -->
                                            <div class="d-flex align-items-center paper-actions">
                                                <!-- Action form -->
                                                <form action="{{ route('student.papers.start', $paper->id) }}" method="POST"
                                                    class="mb-0 mr-2">
                                                    @csrf
                                                    @if($paper->active_attempt)
                                                        @if($paper->active_attempt->status == 'paused')
                                                            <button type="submit"
                                                                class="btn btn-warning text-white btn-sm px-4 font-weight-bold btn-resume btn-resume-paused">
                                                                Resume <i class="feather icon-play ml-1"></i>
                                                            </button>
                                                        @else
                                                            <button type="submit"
                                                                class="btn btn-info btn-sm px-4 font-weight-bold btn-resume btn-resume-in-progress">
                                                                Resume <i class="feather icon-play ml-1"></i>
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm px-4 font-weight-bold btn-start btn-start-trigger"
                                                            data-paper-id="{{ $paper->id }}"
                                                            data-paper-title="{{ $paper->title }}"
                                                            data-paper-questions="{{ $paper->questions_count ?? $paper->questions()->count() }}"
                                                            data-paper-time="{{ $paper->total_time ? $paper->total_time . ' mins' : 'Unlimited' }}"
                                                            data-paper-difficulty="{{ strtolower($paper->difficulty) }}"
                                                            data-paper-instruction="{!! e($paper->instruction ?? 'No specific instructions provided. Answer all questions to the best of your ability.') !!}"
                                                            data-action-url="{{ route('student.papers.start', $paper->id) }}">
                                                            Start <i class="feather icon-arrow-right ml-1"></i>
                                                        </button>
                                                    @endif
                                                </form>

                                                <!-- Attempts History Button -->
                                                @if($paper->attempts->isNotEmpty())
                                                    <button class="btn-history" data-toggle="collapse"
                                                        data-target="#collapseAttempts{{ $paper->id }}" title="View Attempt History"
                                                        aria-expanded="false">
                                                        <i class="feather icon-bar-chart-2"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Attempts History Collapsible -->
                                        @if($paper->attempts->isNotEmpty())
                                            <div id="collapseAttempts{{ $paper->id }}" class="collapse history-collapse-wrapper">
                                                <div class="p-4 bg-light-history border-bottom border-top-light">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="history-bullet mr-2"></div>
                                                        <h6 class="font-weight-bold mb-0 text-dark">Attempt History Summary</h6>
                                                    </div>

                                                    <!-- Mobile View cards for Past Attempts -->
                                                    <div class="d-block d-md-none">
                                                        @foreach($paper->attempts as $attempt)
                                                            @php
                                                                $pct = $attempt->max_score > 0 ? round(($attempt->score / $attempt->max_score) * 100) : 0;
                                                                $pctColor = $pct >= 80 ? 'text-success' : ($pct >= 50 ? 'text-warning' : 'text-danger');
                                                            @endphp
                                                            <div
                                                                class="history-mobile-card mb-3 p-3 rounded shadow-xs bg-white border border-light">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <span class="font-small-3 font-weight-bold text-muted">
                                                                        <i
                                                                            class="feather icon-calendar mr-1"></i>{{ $attempt->created_at->format('d M Y, h:i A') }}
                                                                    </span>
                                                                    <span>
                                                                        @if($attempt->status == 'completed')
                                                                            <span class="badge badge-pill badge-soft-success">Completed</span>
                                                                        @elseif($attempt->status == 'paused')
                                                                            <span class="badge badge-pill badge-soft-warning">Paused</span>
                                                                        @else
                                                                            <span class="badge badge-pill badge-soft-info">In Progress</span>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                                <div class="row mb-2">
                                                                    <div class="col-6">
                                                                        <span class="font-small-3 text-muted">Score:</span>
                                                                        <div class="font-weight-bold text-dark">{{ $attempt->score }} /
                                                                            {{ $attempt->max_score }}</div>
                                                                    </div>
                                                                    <div class="col-6 text-right">
                                                                        <span class="font-small-3 text-muted">Accuracy:</span>
                                                                        <div class="font-weight-bold {{ $pctColor }}">{{ $pct }}%</div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                                    <span class="font-small-3 text-muted"><i
                                                                            class="feather icon-clock mr-1"></i>
                                                                        @php
                                                                            $h = floor($attempt->time_spent / 3600);
                                                                            $m = floor(($attempt->time_spent / 60) % 60);
                                                                            $s = $attempt->time_spent % 60;
                                                                            $spent = ($h > 0 ? $h . 'h ' : '') . ($m > 0 || $h > 0 ? $m . 'm ' : '') . $s . 's';
                                                                        @endphp
                                                                        {{ $spent }}
                                                                    </span>
                                                                    @if($attempt->status == 'completed')
                                                                        <a href="{{ route('student.attempts.result', $attempt->id) }}"
                                                                            class="btn btn-sm btn-outline-primary py-1 px-3">
                                                                            Result <i class="feather icon-arrow-right ml-1"></i>
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ route('student.attempts.take', $attempt->id) }}"
                                                                            class="btn btn-sm btn-outline-info py-1 px-3">
                                                                            Resume <i class="feather icon-play ml-1"></i>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <!-- Desktop table view -->
                                                    <div
                                                        class="table-responsive d-none d-md-block shadow-xs rounded bg-white overflow-hidden border">
                                                        <table class="table table-sm table-modern mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Attempt Date</th>
                                                                    <th>Status</th>
                                                                    <th class="text-center">Score Details</th>
                                                                    <th class="text-center">Accuracy Progress</th>
                                                                    <th class="text-center">Time Spent</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($paper->attempts as $attempt)
                                                                    @php
                                                                        $pct = $attempt->max_score > 0 ? round(($attempt->score / $attempt->max_score) * 100) : 0;
                                                                        $pctBg = $pct >= 80 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-danger');
                                                                        $pctColor = $pct >= 80 ? 'text-success' : ($pct >= 50 ? 'text-warning' : 'text-danger');
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="align-middle py-3 px-3">
                                                                            <i
                                                                                class="feather icon-calendar text-muted mr-1"></i>{{ $attempt->created_at->format('d M Y, h:i A') }}
                                                                        </td>
                                                                        <td class="align-middle">
                                                                            @if($attempt->status == 'completed')
                                                                                <span
                                                                                    class="badge badge-pill badge-soft-success">Completed</span>
                                                                            @elseif($attempt->status == 'paused')
                                                                                <span class="badge badge-pill badge-soft-warning">Paused</span>
                                                                            @else
                                                                                <span class="badge badge-pill badge-soft-info">In
                                                                                    Progress</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="text-center align-middle text-dark font-weight-bold">
                                                                            {{ $attempt->score }} <span
                                                                                class="text-muted font-weight-normal">/
                                                                                {{ $attempt->max_score }}</span>
                                                                        </td>
                                                                        <td class="align-middle">
                                                                            <div class="d-flex align-items-center justify-content-center">
                                                                                <span class="font-weight-bold {{ $pctColor }} mr-3"
                                                                                    style="min-width: 35px;">{{ $pct }}%</span>
                                                                                <div class="progress progress-sm w-50 mb-0 shadow-xs">
                                                                                    <div class="progress-bar {{ $pctBg }}"
                                                                                        role="progressbar" style="width: {{ $pct }}%"
                                                                                        aria-valuenow="{{ $pct }}" aria-valuemin="0"
                                                                                        aria-valuemax="100"></div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="text-center align-middle text-dark font-weight-medium">
                                                                            <i class="feather icon-clock text-muted mr-1"></i>
                                                                            @php
                                                                                $h = floor($attempt->time_spent / 3600);
                                                                                $m = floor(($attempt->time_spent / 60) % 60);
                                                                                $s = $attempt->time_spent % 60;
                                                                                $spent = ($h > 0 ? $h . 'h ' : '') . ($m > 0 || $h > 0 ? $m . 'm ' : '') . $s . 's';
                                                                            @endphp
                                                                            {{ $spent }}
                                                                        </td>
                                                                        <td class="text-center align-middle">
                                                                            @if($attempt->status == 'completed')
                                                                                <a href="{{ route('student.attempts.result', $attempt->id) }}"
                                                                                    class="btn btn-sm btn-outline-primary py-1 px-3 font-weight-bold btn-history-action">
                                                                                    View Result
                                                                                </a>
                                                                            @else
                                                                                <a href="{{ route('student.attempts.take', $attempt->id) }}"
                                                                                    class="btn btn-sm btn-outline-info py-1 px-3 font-weight-bold btn-history-action">
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
                                            </div>
                                        @endif
                                    @empty
                                        <div class="p-5 text-center text-muted empty-state-subtopic">
                                            <div class="empty-icon-box mb-3">
                                                <i class="feather icon-file font-large-1 text-warning"></i>
                                            </div>
                                            <h6 class="font-weight-bold text-dark mb-1">No Papers Available</h6>
                                            <p class="mb-0 font-small-3">There are no practice papers available for this subtopic
                                                currently.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card shadow-sm border-0 p-5 text-center text-muted empty-state-main">
                            <div class="empty-state-illustrate mb-3">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    class="text-warning animated-svg">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <h4 class="font-weight-bold text-dark mb-2">No Subtopics Found</h4>
                            <p class="mb-4">No subtopics matched your filter or are configured for this topic.</p>
                            @if(request()->filled('subtopic_name') || request()->filled('paper_name'))
                                <div>
                                    <a href="{{ route('student.topics.subtopics', $topic->id) }}"
                                        class="btn btn-primary btn-modern px-4 py-2">
                                        <i class="feather icon-rotate-ccw mr-1"></i> Clear Filters
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <!-- Instructions Modal -->
    <div class="modal fade" id="instructionsModal" tabindex="-1" role="dialog" aria-labelledby="instructionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg instructions-modal-content">
                <!-- Header Banner -->
                <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-bottom: 0;">
                    <div class="d-flex align-items-center">
                        <div class="header-icon-box mr-3" style="width: 40px; height: 40px; background-color: rgba(255, 255, 255, 0.1); border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="feather icon-file-text text-white font-medium-3"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-white font-weight-bold" id="instructionsModalLabel" style="font-size: 1.15rem; letter-spacing: 0.3px; line-height: 1.2;">Exam Readiness Review</h5>
                            <span class="text-light opacity-75 font-small-2" style="display: block; margin-top: 2px;">Please read the instructions carefully before starting.</span>
                        </div>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; outline: none; margin-top: -5px;">
                        <span aria-hidden="true" style="font-size: 1.6rem;">&times;</span>
                    </button>
                </div>
                <form id="modal-start-form" method="POST" action="">
                    @csrf
                    <div class="modal-body modal-body-custom p-4">
                        
                        <!-- Paper Card Summary -->
                        <div class="paper-summary-card p-4 mb-4">
                            <h3 class="font-weight-extrabold mb-3" id="modal-paper-title" style="font-size: 1.5rem; line-height: 1.3;">Paper Title</h3>
                            
                            <div class="d-flex align-items-center flex-wrap" style="gap: 12px; margin-top: 15px;">
                                <div class="stat-pill-modern d-flex align-items-center px-3 py-2" style="gap: 8px;">
                                    <i class="feather icon-help-circle text-primary" style="font-size: 1.15rem; margin-top: 2px;"></i>
                                    <div>
                                        <div class="text-muted font-small-1 text-uppercase font-weight-bold" style="letter-spacing: 0.5px; line-height: 1.1; font-size: 0.65rem;">Questions</div>
                                        <span class="font-weight-bold text-dark font-small-3 modal-stat-value" id="modal-paper-questions" style="font-size: 0.85rem;">0</span>
                                    </div>
                                </div>

                                <div class="stat-pill-modern d-flex align-items-center px-3 py-2" style="gap: 8px;">
                                    <i class="feather icon-clock text-success" style="font-size: 1.15rem; margin-top: 2px;"></i>
                                    <div>
                                        <div class="text-muted font-small-1 text-uppercase font-weight-bold" style="letter-spacing: 0.5px; line-height: 1.1; font-size: 0.65rem;">Duration</div>
                                        <span class="font-weight-bold text-dark font-small-3 modal-stat-value" id="modal-paper-time" style="font-size: 0.85rem;">0 mins</span>
                                    </div>
                                </div>

                                <div class="stat-pill-modern d-flex align-items-center px-3 py-2" style="gap: 8px;">
                                    <i class="feather icon-award text-warning" id="modal-paper-difficulty-icon" style="font-size: 1.15rem; margin-top: 2px;"></i>
                                    <div>
                                        <div class="text-muted font-small-1 text-uppercase font-weight-bold" style="letter-spacing: 0.5px; line-height: 1.1; font-size: 0.65rem;">Difficulty</div>
                                        <span class="font-weight-bold text-dark font-small-3 modal-stat-value" id="modal-paper-difficulty" style="font-size: 0.85rem;">Medium</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions Header -->
                        <div class="d-flex align-items-center mb-3">
                            <div style="width: 4px; height: 16px; background-color: #7367f0; border-radius: 2px;" class="mr-2"></div>
                            <h5 class="font-weight-extrabold mb-0 instructions-heading" style="font-size: 1.1rem; letter-spacing: -0.2px;">Guidelines & Instructions</h5>
                        </div>

                        <!-- Instruction Rich Text container -->
                        <div class="rich-text-container p-4 rounded mb-4" id="modal-paper-instruction">
                            <!-- Dynamic Content -->
                        </div>

                        <!-- Crucial Notice Box -->
                        <div class="alert alert-warning d-flex align-items-start border-0 p-3 mb-0 modal-notice-box" style="gap: 12px;">
                            <i class="feather icon-alert-triangle text-warning font-medium-3 mt-1"></i>
                            <div>
                                <h6 class="font-weight-bold text-warning mb-1" style="font-size: 0.9rem;">Important Notice</h6>
                                <p class="text-muted font-small-3 mb-0 modal-notice-text" style="line-height: 1.4;">Starting the test initiates a timer that cannot be paused. Ensure you have a stable internet connection and are in a quiet environment before proceeding.</p>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-between p-3">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 font-weight-bold" data-dismiss="modal" style="border-radius: 8px; font-size: 0.9rem;">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold d-flex align-items-center" style="background: linear-gradient(135deg, #7367f0 0%, #4f46e5 100%) !important; border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(115, 103, 240, 0.2); font-size: 0.9rem;">
                            Start Test Now <i class="feather icon-play-circle ml-2 font-medium-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom Styling for Rich Text inside Instructions Modal */
        .instructions-modal-content {
            border-radius: 20px !important;
        }

        .modal-body-custom {
            background-color: #f8fafc;
        }

        .paper-summary-card {
            background: #ffffff; 
            border: 1px solid #e2e8f0; 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        #modal-paper-title {
            color: #1e293b;
        }

        .stat-pill-modern {
            background-color: #f1f5f9; 
            border-radius: 10px; 
            border: 1px solid #e2e8f0;
        }

        .modal-stat-value {
            color: #1e293b !important;
        }

        .instructions-heading {
            color: #1e293b;
        }

        .rich-text-container {
            font-size: 0.95rem;
            color: #334155;
            line-height: 1.7;
            background-color: #ffffff; 
            border: 1px solid #e2e8f0; 
            max-height: 300px; 
            overflow-y: auto; 
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.01);
        }

        .rich-text-container p {
            margin-bottom: 1rem;
        }

        .rich-text-container p:last-child {
            margin-bottom: 0;
        }

        .rich-text-container h1, 
        .rich-text-container h2, 
        .rich-text-container h3, 
        .rich-text-container h4, 
        .rich-text-container h5, 
        .rich-text-container h6 {
            color: #0f172a;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .rich-text-container h1 { font-size: 1.5rem; }
        .rich-text-container h2 { font-size: 1.3rem; }
        .rich-text-container h3 { font-size: 1.15rem; }

        .rich-text-container ul, 
        .rich-text-container ol {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .rich-text-container li {
            margin-bottom: 0.5rem;
        }

        .rich-text-container blockquote {
            border-left: 4px solid #7367f0;
            padding: 0.5rem 1rem;
            background-color: #f8fafc;
            color: #475569;
            margin: 1.5rem 0;
            border-radius: 0 8px 8px 0;
            font-style: italic;
        }

        .rich-text-container strong {
            color: #0f172a;
            font-weight: 600;
        }

        .modal-notice-box {
            background-color: #fff9e6; 
            border-radius: 12px; 
            border-left: 4px solid #ffc107 !important;
        }

        .modal-notice-text {
            color: #64748b;
        }

        /* Beautiful Scrollbar for Rich Text Container */
        .rich-text-container::-webkit-scrollbar {
            width: 8px;
        }
        .rich-text-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .rich-text-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .rich-text-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* DARK THEME SUPPORT OVERRIDES */
        .dark-layout .instructions-modal-content {
            background-color: #1e2440 !important;
        }

        .dark-layout .modal-body-custom {
            background-color: #10163a;
        }

        .dark-layout .paper-summary-card {
            background-color: #1e2440;
            border-color: #2c3558;
            box-shadow: none;
        }

        .dark-layout #modal-paper-title {
            color: #f1f5f9;
        }

        .dark-layout .stat-pill-modern {
            background-color: #10163a;
            border-color: #2c3558;
        }

        .dark-layout .modal-stat-value {
            color: #f1f5f9 !important;
        }

        .dark-layout .instructions-heading {
            color: #f1f5f9;
        }

        .dark-layout .rich-text-container {
            background-color: #181d36;
            border-color: #2c3558;
            color: #cbd5e1;
            box-shadow: none;
        }

        .dark-layout .rich-text-container h1,
        .dark-layout .rich-text-container h2,
        .dark-layout .rich-text-container h3,
        .dark-layout .rich-text-container h4,
        .dark-layout .rich-text-container h5,
        .dark-layout .rich-text-container h6,
        .dark-layout .rich-text-container strong {
            color: #f1f5f9;
        }

        .dark-layout .rich-text-container blockquote {
            background-color: #10163a;
            color: #94a3b8;
        }

        .dark-layout .modal-notice-box {
            background-color: rgba(255, 193, 7, 0.08);
            border-left-color: #ffc107 !important;
        }

        .dark-layout .modal-notice-text {
            color: #94a3b8;
        }

        .dark-layout .modal-footer {
            background-color: #1e2440 !important;
            border-top-color: #2c3558 !important;
        }

        /* Breadcrumbs Modern styling */
        .breadcrumb-modern {
            display: flex;
            flex-wrap: wrap;
            padding: 0.8rem 1.2rem;
            margin-bottom: 0;
            list-style: none;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }

        .dark-layout .breadcrumb-modern {
            background-color: rgba(26, 32, 75, 0.7);
            border-color: rgba(255, 255, 255, 0.05);
        }

        .breadcrumb-modern-item {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .breadcrumb-modern-item+.breadcrumb-modern-item {
            padding-left: 0.6rem;
        }

        .breadcrumb-modern-item+.breadcrumb-modern-item::before {
            display: inline-block;
            padding-right: 0.6rem;
            color: #b4b7c5;
            content: "/";
            font-weight: 400;
        }

        .breadcrumb-modern-item a {
            color: #7367f0;
            transition: color 0.15s ease-in-out;
        }

        .breadcrumb-modern-item a:hover {
            color: #4f46e5;
            text-decoration: none;
        }

        .breadcrumb-modern-item.active {
            color: #b4b7c5;
        }

        /* Hero Topic Card Style */
        .topic-hero-card {
            border-radius: 16px;
            background: linear-gradient(135deg, #7367f0 0%, #4f46e5 100%);
            position: relative;
            box-shadow: 0 8px 30px rgba(115, 103, 240, 0.25) !important;
            margin-top: 10px;
        }

        .hero-bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 1;
        }

        .hero-bg-shapes .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.075);
            backdrop-filter: blur(15px);
        }

        .hero-bg-shapes .shape-1 {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -50px;
        }

        .hero-bg-shapes .shape-2 {
            width: 150px;
            height: 150px;
            bottom: -50px;
            left: 10%;
        }

        .hero-title {
            font-size: 2.1rem;
            line-height: 1.25;
            z-index: 2;
            letter-spacing: -0.5px;
        }

        .badge-hero-category {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 1px;
            padding: 5px 12px;
            border-radius: 6px;
            backdrop-filter: blur(5px);
        }

        .badge-hero-tag {
            background-color: rgba(40, 199, 111, 0.25);
            color: #5effa2;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid rgba(40, 199, 111, 0.3);
        }

        .hero-stats-card {
            background-color: rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            z-index: 2;
            position: relative;
        }

        .progress-hero {
            background-color: rgba(255, 255, 255, 0.2) !important;
            height: 8px !important;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .bg-white-progress {
            background: linear-gradient(90deg, #ffffff 0%, #bcfad8 100%) !important;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
        }

        .border-white-opacity {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .text-light-opacity {
            color: rgba(255, 255, 255, 0.75);
        }

        .text-success-light {
            color: #5effa2 !important;
        }

        /* Search & Filters Section */
        .search-filter-card {
            border-radius: 12px;
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.03);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        }

        .dark-layout .search-filter-card {
            background-color: #1e244b;
            border-color: rgba(255, 255, 255, 0.05);
        }

        .filter-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #4b4b4b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
        }

        .dark-layout .filter-label {
            color: #c2c6dc;
        }

        .filter-label i {
            color: #7367f0;
            font-size: 0.95rem;
        }

        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .select-modern {
            height: 42px !important;
            border-radius: 8px !important;
            border: 1px solid #d8d6de !important;
            background-color: #fff !important;
            color: #5e5873 !important;
            font-weight: 500;
            padding-right: 35px !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            transition: all 0.2s ease-in-out;
        }

        .dark-layout .select-modern {
            border-color: #414561 !important;
            background-color: #10163a !important;
            color: #c2c6dc !important;
        }

        .select-modern:focus {
            border-color: #7367f0 !important;
            box-shadow: 0 3px 8px rgba(115, 103, 240, 0.15) !important;
        }

        .select-chevron {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #b4b7c5;
            font-size: 0.9rem;
        }

        .input-modern {
            height: 42px !important;
            border-radius: 8px !important;
            border: 1px solid #d8d6de !important;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .dark-layout .input-modern {
            border-color: #414561 !important;
            background-color: #10163a !important;
            color: #c2c6dc !important;
        }

        .input-modern:focus {
            border-color: #7367f0 !important;
            box-shadow: 0 3px 8px rgba(115, 103, 240, 0.15) !important;
        }

        .btn-modern {
            height: 42px;
            border-radius: 8px !important;
            font-weight: 600;
            background: linear-gradient(135deg, #7367f0 0%, #4f46e5 100%) !important;
            border: none !important;
            transition: all 0.2s ease-in-out !important;
            box-shadow: 0 4px 12px rgba(115, 103, 240, 0.2) !important;
        }

        .btn-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(115, 103, 240, 0.3) !important;
        }

        .btn-modern-danger {
            height: 42px;
            width: 42px;
            border-radius: 8px !important;
            border: 1px solid #ea5455 !important;
            color: #ea5455 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent !important;
            transition: all 0.2s ease-in-out !important;
        }

        .btn-modern-danger:hover {
            background-color: rgba(234, 84, 85, 0.08) !important;
            transform: translateY(-1px);
        }

        /* Subtopics Collapsible layout */
        .subtopic-card {
            border-radius: 12px;
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.03);
            transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03) !important;
        }

        .dark-layout .subtopic-card {
            background-color: #1e244b;
            border-color: rgba(255, 255, 255, 0.05);
        }

        .subtopic-card:hover {
            box-shadow: 0 8px 24px rgba(115, 103, 240, 0.08) !important;
            transform: translateY(-2px);
        }

        .subtopic-header {
            background-color: transparent;
            transition: background-color 0.15s ease-in-out;
        }

        .clickable {
            cursor: pointer;
        }

        .clickable:hover {
            background-color: #fafbfc;
        }

        .dark-layout .clickable:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .subtopic-title {
            letter-spacing: -0.3px;
        }

        .dark-layout .text-dark {
            color: #fff !important;
        }

        .subtopic-bullet {
            width: 5px;
            height: 24px;
            background: linear-gradient(180deg, #7367f0 0%, #4f46e5 100%);
            border-radius: 3px;
        }

        .badge-papers-count {
            font-weight: 700;
            padding: 6px 14px;
            font-size: 0.8rem;
        }

        .dark-layout .badge-papers-count {
            background-color: rgba(115, 103, 240, 0.18);
        }

        .chevron-box {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f8f9fa;
            transition: all 0.2s ease;
        }

        .dark-layout .chevron-box {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .collapse-icon {
            transition: transform 0.25s ease-in-out;
            color: #7367f0;
            font-weight: bold;
        }

        [aria-expanded="false"] .collapse-icon {
            transform: rotate(-180deg);
        }

        [aria-expanded="false"] .chevron-box {
            background-color: rgba(115, 103, 240, 0.1);
        }

        .subtopic-mini-progress {
            display: inline-block;
            width: 60px;
            height: 4px;
            background-color: #e9ecef;
            border-radius: 2px;
            margin-left: 8px;
            vertical-align: middle;
            overflow: hidden;
        }

        .dark-layout .subtopic-mini-progress {
            background-color: #272c54;
        }

        .subtopic-mini-bar {
            height: 100%;
            background-color: #28c76f;
            border-radius: 2px;
        }

        .border-top-light {
            border-top: 1px solid #f1f2f4;
        }

        .dark-layout .border-top-light {
            border-top-color: rgba(255, 255, 255, 0.05);
        }

        /* Paper Row Layout styling */
        .paper-row {
            background-color: #ffffff;
            transition: background-color 0.2s ease;
        }

        .dark-layout .paper-row {
            background-color: #1e244b;
        }

        .paper-row:hover {
            background-color: rgba(115, 103, 240, 0.015);
        }

        .dark-layout .paper-row:hover {
            background-color: rgba(115, 103, 240, 0.03);
        }

        .paper-title {
            font-size: 1rem;
            letter-spacing: -0.2px;
        }

        .paper-meta .meta-item {
            display: flex;
            align-items: center;
        }

        .paper-meta i {
            font-size: 0.95rem;
        }

        /* Pastel Soft Badges */
        .badge-soft-success {
            background-color: #e6f4ea !important;
            color: #137333 !important;
            border: 1px solid rgba(19, 115, 51, 0.1);
            font-weight: 600;
        }

        .dark-layout .badge-soft-success {
            background-color: rgba(40, 199, 111, 0.15) !important;
            color: #28c76f !important;
            border-color: rgba(40, 199, 111, 0.2);
        }

        .badge-soft-warning {
            background-color: #fef7e0 !important;
            color: #b06000 !important;
            border: 1px solid rgba(176, 96, 0, 0.1);
            font-weight: 600;
        }

        .dark-layout .badge-soft-warning {
            background-color: rgba(255, 159, 67, 0.15) !important;
            color: #ff9f43 !important;
            border-color: rgba(255, 159, 67, 0.2);
        }

        .badge-soft-danger {
            background-color: #fce8e6 !important;
            color: #c5221f !important;
            border: 1px solid rgba(197, 34, 31, 0.1);
            font-weight: 600;
        }

        .dark-layout .badge-soft-danger {
            background-color: rgba(234, 84, 85, 0.15) !important;
            color: #ea5455 !important;
            border-color: rgba(234, 84, 85, 0.2);
        }

        .badge-soft-secondary {
            background-color: #f1f3f4 !important;
            color: #5f6368 !important;
            border: 1px solid rgba(95, 99, 104, 0.1);
            font-weight: 600;
        }

        .dark-layout .badge-soft-secondary {
            background-color: rgba(180, 183, 197, 0.12) !important;
            color: #b4b7c5 !important;
            border-color: rgba(180, 183, 197, 0.15);
        }

        .badge-soft-paused {
            background-color: rgba(255, 159, 67, 0.1) !important;
            color: #ff9f43 !important;
            border: 1px solid rgba(255, 159, 67, 0.15);
            font-weight: 600;
        }

        .badge-soft-progress {
            background-color: rgba(0, 207, 221, 0.1) !important;
            color: #00cfdd !important;
            border: 1px solid rgba(0, 207, 221, 0.15);
            font-weight: 600;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            vertical-align: middle;
        }

        .badge-soft-success .badge-dot {
            background-color: #137333;
        }

        .dark-layout .badge-soft-success .badge-dot {
            background-color: #28c76f;
        }

        .badge-soft-warning .badge-dot {
            background-color: #b06000;
        }

        .dark-layout .badge-soft-warning .badge-dot {
            background-color: #ff9f43;
        }

        .badge-soft-danger .badge-dot {
            background-color: #c5221f;
        }

        .dark-layout .badge-soft-danger .badge-dot {
            background-color: #ea5455;
        }

        .badge-soft-secondary .badge-dot {
            background-color: #5f6368;
        }

        .dark-layout .badge-soft-secondary .badge-dot {
            background-color: #b4b7c5;
        }

        .badge-soft-paused .badge-dot {
            background-color: #ff9f43;
        }

        .badge-soft-progress .badge-dot {
            background-color: #00cfdd;
        }

        /* Pulsing Indicators */
        .pulse-warning {
            animation: pulse-warn-border 2s infinite;
        }

        .pulse-info {
            animation: pulse-inf-border 2s infinite;
        }

        @keyframes pulse-warn-border {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 159, 67, 0.4);
            }

            70% {
                box-shadow: 0 0 0 5px rgba(255, 159, 67, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 159, 67, 0);
            }
        }

        @keyframes pulse-inf-border {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 207, 221, 0.4);
            }

            70% {
                box-shadow: 0 0 0 5px rgba(0, 207, 221, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 207, 221, 0);
            }
        }

        /* Action Buttons styling */
        .btn-resume {
            border-radius: 30px !important;
            padding: 8px 24px !important;
            font-size: 0.85rem !important;
            border: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            color: #ffffff !important;
        }

        .btn-resume-paused {
            background: linear-gradient(135deg, #ff9f43 0%, #ff8300 100%) !important;
            box-shadow: 0 4px 14px rgba(255, 159, 67, 0.25) !important;
        }

        .btn-resume-paused:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 159, 67, 0.4) !important;
        }

        .btn-resume-in-progress {
            background: linear-gradient(135deg, #00cfdd 0%, #00b4c0 100%) !important;
            box-shadow: 0 4px 14px rgba(0, 207, 221, 0.25) !important;
        }

        .btn-resume-in-progress:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 207, 221, 0.4) !important;
        }

        .btn-resume:active {
            transform: translateY(0) scale(0.96) !important;
        }

        .btn-resume i {
            transition: transform 0.2s ease;
        }

        .btn-resume:hover i {
            transform: scale(1.15) translateX(1px);
        }

        .btn-start {
            border-radius: 30px !important;
            padding: 8px 24px !important;
            font-size: 0.85rem !important;
            background: linear-gradient(135deg, #7367f0 0%, #4f46e5 100%) !important;
            border: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(115, 103, 240, 0.25) !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .btn-start:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(115, 103, 240, 0.45) !important;
        }

        .btn-start:active {
            transform: translateY(0) scale(0.96) !important;
        }

        .btn-start i {
            transition: transform 0.2s ease;
        }

        .btn-start:hover i {
            transform: translateX(4px);
        }

        .btn-history {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            border: 1px solid rgba(115, 103, 240, 0.15) !important;
            color: #7367f0 !important;
            background-color: rgba(115, 103, 240, 0.04) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02) !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .dark-layout .btn-history {
            background-color: rgba(115, 103, 240, 0.08) !important;
            border-color: rgba(115, 103, 240, 0.25) !important;
            color: #8c83f3 !important;
        }

        .btn-history i {
            font-size: 0.95rem;
            transition: transform 0.3s ease-in-out;
        }

        .btn-history:hover {
            transform: translateY(-2px);
            border-color: #7367f0 !important;
            background-color: #7367f0 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(115, 103, 240, 0.25) !important;
        }

        .btn-history:hover i {
            transform: rotate(-30deg);
        }

        .btn-history:active {
            transform: translateY(0) scale(0.92) !important;
        }

        .btn-history[aria-expanded="true"] {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3) !important;
        }

        .btn-history[aria-expanded="true"] i {
            transform: rotate(-360deg);
        }

        /* Attempts History Timeline & Tables styling */
        .history-collapse-wrapper {
            background-color: #fafbfc;
        }

        .dark-layout .history-collapse-wrapper {
            background-color: #161a3f;
        }

        .bg-light-history {
            background-color: #fafbfc !important;
        }

        .dark-layout .bg-light-history {
            background-color: #161a3f !important;
        }

        .history-bullet {
            width: 4px;
            height: 16px;
            background-color: #b4b7c5;
            border-radius: 2px;
        }

        .table-modern {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead th {
            background-color: #f3f2f7 !important;
            color: #5e5873 !important;
            border-bottom: 1px solid #ebe9f1 !important;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            padding: 10px 15px;
        }

        .dark-layout .table-modern thead th {
            background-color: #272c54 !important;
            color: #c2c6dc !important;
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
        }

        .table-modern tbody td {
            border-bottom: 1px solid #ebe9f1;
            padding: 12px 15px;
            font-size: 0.85rem;
        }

        .dark-layout .table-modern tbody td {
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        .progress-sm {
            height: 6px !important;
            border-radius: 3px;
            background-color: #f1f2f4;
            overflow: hidden;
        }

        .dark-layout .progress-sm {
            background-color: #272c54;
        }

        .btn-history-action {
            border-radius: 6px;
            font-size: 0.78rem;
            padding: 4px 12px;
            transition: all 0.15s ease-in-out;
        }

        /* Mobile cards attempts style */
        .history-mobile-card {
            border-color: #ebe9f1 !important;
            transition: transform 0.15s ease;
        }

        .dark-layout .history-mobile-card {
            background-color: #1e244b !important;
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        .history-mobile-card:hover {
            transform: scale(1.01);
        }

        /* Empty states styling */
        .empty-state-main {
            border-radius: 16px;
            padding: 60px 20px !important;
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
        }

        .dark-layout .empty-state-main {
            background-color: #1e244b;
        }

        .empty-state-subtopic {
            padding: 40px 20px !important;
        }

        .empty-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background-color: rgba(255, 159, 67, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .animated-svg {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-8px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* Responsive Layout Overrides */
        @media (max-width: 991.98px) {
            .hero-title {
                font-size: 1.8rem;
            }

            .paper-row {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .paper-actions {
                width: 100%;
                justify-content: space-between;
                margin-top: 15px;
            }

            .paper-actions form {
                flex-grow: 1;
            }

            .paper-actions form button {
                width: 100%;
                text-align: center;
            }

            .max-width-70 {
                max-width: 100% !important;
            }
        }
    </style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-start-trigger', function() {
            var button = $(this);
            var paperTitle = button.data('paper-title');
            var questionsCount = button.data('paper-questions');
            var totalTime = button.data('paper-time');
            var difficulty = button.data('paper-difficulty') || 'medium';
            var instruction = button.attr('data-paper-instruction');
            var actionUrl = button.data('action-url');

            // Update Modal Form Action
            $('#modal-start-form').attr('action', actionUrl);

            // Update Modal Title
            $('#modal-paper-title').text(paperTitle);

            // Update Stats
            $('#modal-paper-questions').text(questionsCount);
            $('#modal-paper-time').text(totalTime);

            // Update Difficulty Text & Icon color dynamically
            var difficultyColor = '#b06000'; // medium
            if (difficulty === 'easy') {
                difficultyColor = '#137333'; // green
            } else if (difficulty === 'hard') {
                difficultyColor = '#c5221f'; // red
            }
            $('#modal-paper-difficulty').css('color', difficultyColor).text(difficulty.charAt(0).toUpperCase() + difficulty.slice(1));
            $('#modal-paper-difficulty-icon').css('color', difficultyColor);

            // Update Instruction Content
            $('#modal-paper-instruction').html(instruction);

            // Show the modal
            $('#instructionsModal').modal('show');
        });
    });
</script>
@endpush
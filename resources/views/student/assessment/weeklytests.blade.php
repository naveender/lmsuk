@extends('layouts.app')

@section('title', 'Weekly Tests')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            
            <!-- Breadcrumbs -->
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Weekly Tests</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('student.assessments') }}">Assessments</a></li>
                                    <li class="breadcrumb-item active">Weekly Tests</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <!-- Filters Section -->
                <div class="card mb-3 shadow-sm border-0 filter-card">
                    <div class="card-body py-1">
                        <form method="GET" action="{{ route('student.weeklytests') }}" class="row align-items-end">
                            <!-- Subject Filter -->
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-1">
                                <label for="subject_filter" class="form-label font-weight-bold text-secondary font-small-2 mb-0">Subject</label>
                                <select name="subject_id" id="subject_filter" class="form-control form-control-sm filter-select">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Course Filter -->
                            <div class="col-lg-3 col-md-6 col-sm-12 mb-1">
                                <label for="course_filter" class="form-label font-weight-bold text-secondary font-small-2 mb-0">Course</label>
                                <select name="course_id" id="course_filter" class="form-control form-control-sm filter-select select2">
                                    <option value="">All Courses</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Week Filter -->
                            <div class="col-lg-2 col-md-6 col-sm-12 mb-1">
                                <label for="week_filter" class="form-label font-weight-bold text-secondary font-small-2 mb-0">Week</label>
                                <select name="week" id="week_filter" class="form-control form-control-sm filter-select select2">
                                    <option value="">All Weeks</option>
                                    @foreach($allWeeks as $wk)
                                        <option value="{{ $wk->id }}" data-course-id="{{ $wk->course_id }}" {{ request('week') == $wk->id ? 'selected' : '' }}>
                                            {{ $wk->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-lg-2 col-md-6 col-sm-12 mb-1">
                                <label for="status_filter" class="form-label font-weight-bold text-secondary font-small-2 mb-0">Status</label>
                                <select name="status" id="status_filter" class="form-control form-control-sm filter-select">
                                    <option value="">All Statuses</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Paused</option>
                                    <option value="Not completed" {{ request('status') === 'Not completed' ? 'selected' : '' }}>Not Completed</option>
                                </select>
                            </div>

                            <!-- Buttons -->
                            <div class="col-lg-2 col-md-12 col-sm-12 d-flex justify-content-between mb-1">
                                <button type="submit" class="btn btn-primary btn-sm flex-grow-1 mr-1 search-btn shadow-sm">
                                    <i class="feather icon-search"></i> Filter
                                </button>
                                @if(request()->anyFilled(['subject_id', 'course_id', 'week', 'status']))
                                    <a href="{{ route('student.weeklytests') }}" class="btn btn-outline-danger btn-sm flex-grow-1 text-center d-flex align-items-center justify-content-center">
                                        Clear
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- STUDENT PORTAL Header -->
                <div class="portal-header-wrapper mt-3 mb-2">
                    <span class="portal-badge font-weight-extrabold text-uppercase">Student Portal</span>
                    <h2 class="portal-title font-weight-bold text-dark">{{ $courseTitle }} | YR 5 — {{ $selectedWeekName }}</h2>
                </div>

                <!-- OVERVIEW METRICS BAR -->
                <div class="overview-metrics-card card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="metrics-grid">
                            <div class="metric-item border-right">
                                <span class="metric-label">Assignment Score</span>
                                <span class="metric-value text-dark">{{ $metrics['score'] }}</span>
                            </div>
                            <div class="metric-item border-right">
                                <span class="metric-label">Task Completion</span>
                                <span class="metric-value text-dark">{{ $metrics['completion'] }}</span>
                            </div>
                            <div class="metric-item border-right">
                                <span class="metric-label">Tracked Runtime</span>
                                <span class="metric-value text-muted font-small-3 font-weight-normal mt-1">{{ $metrics['runtime'] }}</span>
                            </div>
                            <div class="metric-item border-right">
                                <span class="metric-label">Exam System Metrics</span>
                                <span class="metric-value text-muted font-small-3 font-weight-normal mt-1">{{ $metrics['overdue_late'] }}</span>
                            </div>
                            <div class="metric-item">
                                <span class="metric-label text-danger">Final Deadline</span>
                                <span class="metric-value text-danger font-medium-1 font-weight-bold mt-1">{{ $metrics['deadline'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MAIN Split Layout (40% / 60%) -->
                <div class="row layout-split">
                    
                    <!-- Left Column (40% Hold - Videos & Mark Schemes) -->
                    <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 layout-left">
                        
                        <!-- 1. CORE VIDEO LECTURE STREAMS -->
                        <div class="video-section-wrapper mb-4">
                            <h5 class="section-heading mb-2 font-weight-extrabold text-dark">1. CORE VIDEO LECTURE STREAMS</h5>
                            
                            <div class="card shadow-sm border-0 overflow-hidden video-card">
                                <!-- Interactive Playable Video Box -->
                                <div class="video-container" id="videoContainer">
                                    <iframe id="videoIframe" width="100%" height="240" src="https://www.youtube.com/embed/dK4oXvKmsxM?enablejsapi=1" title="Coordinates Video Lecture" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                                </div>
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="video-icon-box mr-2">
                                            <i class="feather icon-play-circle text-primary font-large-1"></i>
                                        </div>
                                        <div>
                                            <h6 class="video-title font-weight-bold text-dark mb-0">Coordinates and Transformations I</h6>
                                            <span class="video-status text-success font-weight-bold font-small-2"><i class="feather icon-play mr-1"></i>Runtime: 35m 44s (Completed)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        

                    </div>
                    
                    <!-- Right Column (60% Hold - Online Assessment Tests) -->
                    <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 layout-right">
                        
                        <!-- 3. ONLINE ASSIGNMENT TASKS EVALUATION -->
                        <div class="assessment-section-wrapper mb-4">
                            <h5 class="section-heading mb-2 font-weight-extrabold text-dark">2. ONLINE ASSIGNMENT TASKS EVALUATION</h5>
                            
                            <div class="card shadow-sm border-0 overflow-hidden">
                                <div class="table-responsive">
                                    <table class="table table-hover evaluation-table mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">Difficulty</th>
                                                <th style="width: 10%;">Type</th>
                                                <th style="width: 40%;">Assignment Challenge Module Title</th>
                                                <th style="width: 15%;" class="text-center">Target</th>
                                                <th style="width: 15%;" class="text-center">Completed</th>
                                                <th style="width: 10%;" class="text-center">Score</th>
                                                <th style="width: 10%; text-align: right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($papers as $paper)
                                                @php
                                                    // Dynamic type tag based on difficulty or subject
                                                    // Q (Quiz - Blue), H (Homework - Red), S (Subject test - Light Red)
                                                    
                                                    if ($paper->difficulty === 'easy') {
                                                        $typeChar = 'E';
                                                        $typeClass = 'bg-light-success text-success';
                                                    } elseif ($paper->difficulty === 'medium') {
                                                        $typeChar = 'M';
                                                        $typeClass = 'bg-light-primary text-primary';
                                                    }elseif ($paper->difficulty === 'hard') {
                                                        $typeChar = 'H';
                                                        $typeClass = 'bg-light-rose text-rose';
                                                    } 
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="type-badge {{ $typeClass }} font-weight-extrabold text-center">
                                                            {{ $typeChar }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="badge {{ $paper->type }} font-weight-extrabold text-center">
                                                            {{ ucfirst($paper->type) }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="font-weight-bold text-dark module-title">{{ $paper->title }}</div>
                                                        <div class="text-muted font-small-1 mt-0">Subject: <span class="text-secondary font-weight-bold">{{ $paper->subject->title ?? 'N/A' }}</span></div>
                                                    </td>
                                                    <td class="text-center font-weight-bold text-muted font-small-2">
                                                        @if($paper->total_time > 0)
                                                            {{ $paper->total_time }}m 00s
                                                        @else
                                                            No Limit
                                                        @endif
                                                    </td>
                                                    <td class="text-center text-muted font-small-2">
                                                        @if($paper->attempt_status === 'completed')
                                                            {{ $paper->completed_at }}
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td class="text-center font-weight-extrabold font-small-3">
                                                        @if($paper->attempt_status === 'completed')
                                                            @php
                                                                $percentage = $paper->score_percentage ?? 0;
                                                                $colorClass = 'text-success';
                                                                if ($percentage < 50) {
                                                                    $colorClass = 'text-danger';
                                                                } elseif ($percentage < 80) {
                                                                    $colorClass = 'text-warning';
                                                                }
                                                            @endphp
                                                            <span class="{{ $colorClass }}">{{ $percentage }}%</span>
                                                        @elseif($paper->attempt_status === 'paused')
                                                            <span class="text-warning">Paused</span>
                                                        @else
                                                            <span class="text-muted">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td style="text-align: right;">
                                                        @if($paper->attempt_status === 'completed')
                                                            <a href="{{ route('student.attempts.result', $paper->completed_attempt_id) }}" class="review-btn-custom">
                                                                Review
                                                            </a>
                                                        @elseif($paper->attempt_status === 'paused')
                                                            <a href="{{ route('student.attempts.take', $paper->active_attempt->id) }}" class="resume-btn-custom">
                                                                Resume
                                                            </a>
                                                        @else
                                                            <form action="{{ route('student.papers.start', $paper->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="start-btn-custom">
                                                                    Start
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-5">
                                                        <div class="my-3">
                                                            <i class="feather icon-award font-large-3 text-warning mb-2 animate-bounce"></i>
                                                            <h5 class="text-dark font-weight-bold">No Weekly Tests Found</h5>
                                                            <p class="mb-0 text-secondary">We couldn't find any weekly tests assigned matching your filters.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Pagination -->
                                @if($papers->hasPages())
                                    <div class="d-flex justify-content-center py-2 border-top">
                                        {{ $papers->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <style>
        /* Premium Select2 Styling to match theme and filter card */
        .select2-container--default .select2-selection--single {
            border: 1px solid #dcdcdc !important;
            border-radius: 6px !important;
            height: 38px !important;
            display: flex;
            align-items: center;
            background-color: #ffffff !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #7367f0 !important;
            box-shadow: 0 3px 8px rgba(115, 103, 240, 0.15) !important;
            outline: 0;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #4e5154 !important;
            padding-left: 12px !important;
            font-size: 0.85rem !important;
            font-weight: 500;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #b4b4b4 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 8px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #8c8c8c transparent transparent transparent !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #8c8c8c transparent !important;
        }
        .select2-container--default .select2-dropdown {
            border: 1px solid #e3e6eb !important;
            border-radius: 6px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #dcdcdc !important;
            border-radius: 4px !important;
            padding: 6px 10px !important;
            font-size: 0.85rem !important;
        }
        .select2-container--default .select2-results__option {
            padding: 8px 12px !important;
            font-size: 0.85rem !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #7367f0 !important;
            color: #ffffff !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 20px !important;
            color: #ea5455 !important;
            font-size: 1.1rem !important;
        }
        .select2-container {
            width: 100% !important;
        }

        /* Premium CSS matching the reference image layout */
        
        /* Filters design */
        .filter-card {
            border-left: 4px solid #7367f0 !important;
            background: #ffffff;
        }

        .filter-select {
            border-radius: 6px;
            border: 1px solid #dcdcdc;
            font-size: 0.85rem;
        }

        .search-btn {
            border-radius: 6px;
            font-weight: bold;
        }

        /* YS Title style */
        .portal-badge {
            color: #8c8c8c;
            font-size: 0.75rem;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 2px;
        }

        .portal-title {
            font-size: 1.6rem;
            color: #0b2240 !important;
            letter-spacing: -0.5px;
        }

        /* Overview Metrics Bar Grid */
        .overview-metrics-card {
            background-color: #ffffff;
            border: 1px solid #e3e6eb;
            border-radius: 8px;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            text-align: left;
        }

        .metric-item {
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .border-right {
            border-right: 1px solid #e9ecf0;
        }

        .metric-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #7b8895;
            font-weight: 700;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }

        .metric-value {
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        /* Video Container design */
        .video-card {
            border-radius: 8px;
            border: 1px solid #e3e6eb;
            background: #ffffff;
        }

        .video-container {
            background: #111e2f;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            min-height: 240px;
        }

        .video-icon-box {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Document/Mark schemes design */
        .document-card {
            border-radius: 8px;
            border: 1px solid #e3e6eb;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .document-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .doc-icon-box {
            width: 42px;
            height: 42px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-light-danger {
            background-color: rgba(234, 84, 85, 0.1);
        }

        .text-danger {
            color: #ea5455 !important;
        }

        .bg-light-primary {
            background-color: rgba(115, 103, 240, 0.1);
        }

        .text-primary {
            color: #7367f0 !important;
        }

        .bg-light-rose {
            background-color: rgba(236, 72, 153, 0.1);
        }

        .text-rose {
            color: #ec4899 !important;
        }

        .document-btn {
            border-radius: 6px;
            font-size: 0.8rem;
            min-width: 75px;
            text-align: center;
        }

        /* Section Heading styles */
        .section-heading {
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            color: #0b2240;
            border-bottom: 2px solid transparent;
            padding-bottom: 2px;
        }

        /* Right column: Assignments table styling */
        .evaluation-table th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #ffffff;
            background-color: #0b2240 !important;
            border-bottom: none;
            padding: 12px 14px;
            font-weight: 700;
        }

        .evaluation-table td {
            vertical-align: middle;
            padding: 14px 14px;
            border-bottom: 1px solid #e9ecf0;
        }

        .type-badge {
            width: 28px;
            height: 28px;
            line-height: 28px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .module-title {
            font-size: 0.95rem;
            letter-spacing: -0.1px;
        }

        .review-btn-custom {
            border: 1.5px solid #4a5568 !important;
            color: #4a5568 !important;
            background-color: #ffffff !important;
            border-radius: 6px !important;
            font-size: 0.8rem !important;
            padding: 6px 12px !important;
            display: inline-block !important;
            text-align: center !important;
            font-weight: 700 !important;
            transition: all 0.2s ease-in-out !important;
            cursor: pointer !important;
            text-decoration: none !important;
            min-width: 95px !important;
            line-height: 1.5 !important;
            box-sizing: border-box !important;
        }

        .review-btn-custom:hover {
            background-color: #4a5568 !important;
            color: #ffffff !important;
            border-color: #4a5568 !important;
            transform: translateY(-1px) !important;
            text-decoration: none !important;
        }

        .start-btn-custom {
            background-color: #a91217 !important;
            border: 1px solid #a91217 !important;
            color: #ffffff !important;
            border-radius: 6px !important;
            font-size: 0.8rem !important;
            padding: 6px 12px !important;
            display: inline-block !important;
            text-align: center !important;
            font-weight: 700 !important;
            transition: all 0.2s ease-in-out !important;
            box-shadow: 0 2px 4px rgba(169, 18, 23, 0.2) !important;
            cursor: pointer !important;
            text-decoration: none !important;
            min-width: 95px !important;
            line-height: 1.5 !important;
            box-sizing: border-box !important;
        }

        .start-btn-custom:hover {
            background-color: #cd1a21 !important;
            border-color: #cd1a21 !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(169, 18, 23, 0.3) !important;
            text-decoration: none !important;
        }

        .resume-btn-custom {
            background-color: #ff9f43 !important;
            border: 1px solid #ff9f43 !important;
            color: #ffffff !important;
            border-radius: 6px !important;
            font-size: 0.8rem !important;
            padding: 6px 12px !important;
            display: inline-block !important;
            text-align: center !important;
            font-weight: 700 !important;
            transition: all 0.2s ease-in-out !important;
            box-shadow: 0 2px 4px rgba(255, 159, 67, 0.2) !important;
            cursor: pointer !important;
            text-decoration: none !important;
            min-width: 95px !important;
            line-height: 1.5 !important;
            box-sizing: border-box !important;
        }

        .resume-btn-custom:hover {
            background-color: #ffb063 !important;
            border-color: #ffb063 !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(255, 159, 67, 0.3) !important;
            text-decoration: none !important;
        }

        .document-btn-custom {
            border: 1.5px solid #4a5568 !important;
            color: #4a5568 !important;
            background-color: #ffffff !important;
            border-radius: 6px !important;
            font-size: 0.8rem !important;
            padding: 6px 12px !important;
            display: inline-block !important;
            text-align: center !important;
            font-weight: 700 !important;
            transition: all 0.2s ease-in-out !important;
            cursor: pointer !important;
            text-decoration: none !important;
            min-width: 80px !important;
            line-height: 1.5 !important;
            box-sizing: border-box !important;
        }

        .document-btn-custom:hover {
            background-color: #4a5568 !important;
            color: #ffffff !important;
            border-color: #4a5568 !important;
            transform: translateY(-1px) !important;
            text-decoration: none !important;
        }

        .document-btn-custom.red-btn-custom {
            background-color: #a91217 !important;
            border: 1px solid #a91217 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(169, 18, 23, 0.2) !important;
        }

        .document-btn-custom.red-btn-custom:hover {
            background-color: #cd1a21 !important;
            border-color: #cd1a21 !important;
            color: #ffffff !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 8px rgba(169, 18, 23, 0.3) !important;
            text-decoration: none !important;
        }

        /* Exact 40/60 split sizing on large devices */
        @media (min-width: 992px) {
            .layout-left {
                flex: 0 0 40% !important;
                max-width: 40% !important;
            }
            .layout-right {
                flex: 0 0 60% !important;
                max-width: 60% !important;
            }
        }

        /* Responsiveness for metrics bar */
        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            .border-right:nth-child(3) {
                border-right: none;
            }
        }

        @media (max-width: 768px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            .metric-item {
                border-right: none;
                border-bottom: 1px solid #e9ecf0;
                padding: 12px 16px;
            }
            .metric-item:last-child {
                border-bottom: none;
            }
            .portal-title {
                font-size: 1.3rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('theme/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseFilter = document.getElementById('course_filter');
            const weekFilter = document.getElementById('week_filter');
            
            if (courseFilter && weekFilter) {
                // Store all original week options from the dropdown
                const originalWeeks = Array.from(weekFilter.options).map(opt => ({
                    value: opt.value,
                    text: opt.text,
                    courseId: opt.getAttribute('data-course-id') || '',
                    selected: opt.selected
                }));

                // Initialize Select2 on Course and Week filters
                $(courseFilter).select2({
                    placeholder: "All Courses",
                    allowClear: true,
                    width: '100%'
                });

                $(weekFilter).select2({
                    placeholder: "All Weeks",
                    allowClear: true,
                    width: '100%'
                });
                
                function updateWeeks(isInitialLoad = false) {
                    const selectedCourseId = courseFilter.value;
                    const currentSelectedWeek = weekFilter.value;
                    
                    // Clear existing options
                    weekFilter.innerHTML = '';
                    
                    let hasSelected = false;
                    
                    // Rebuild options matching the selected course
                    originalWeeks.forEach(opt => {
                        // Always include "All Weeks" (empty value)
                        // If no course is selected (selectedCourseId is empty), include all weeks
                        // Otherwise, match the courseId of the week option with selectedCourseId
                        if (opt.value === '' || !selectedCourseId || opt.courseId === selectedCourseId) {
                            const newOpt = document.createElement('option');
                            newOpt.value = opt.value;
                            newOpt.text = opt.text;
                            if (opt.courseId) {
                                newOpt.setAttribute('data-course-id', opt.courseId);
                            }
                            
                            // Restore selection if it matches
                            if (opt.value === currentSelectedWeek) {
                                newOpt.selected = true;
                                hasSelected = true;
                            }
                            
                            weekFilter.appendChild(newOpt);
                        }
                    });
                    
                    // If the previously selected week is not valid for the new course, default to "All Weeks"
                    if (!hasSelected && !isInitialLoad) {
                        weekFilter.value = '';
                    }

                    // Synchronize Select2 with updated underlying native options
                    $(weekFilter).trigger('change.select2');
                }
                
                // Listen to changes on the Course filter dropdown (using JQuery to capture Select2 select/clear events)
                $(courseFilter).on('change', function() {
                    updateWeeks(false);
                });
                
                // Initial load filtering
                updateWeeks(true);
            }
        });
    </script>
@endpush

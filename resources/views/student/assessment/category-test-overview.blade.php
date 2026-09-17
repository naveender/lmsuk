@extends('layouts.app')

@section('title', 'Student Assessments - Aspire Learners')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                
                <!-- Hero Header Banner -->
                <div class="assessment-hero-card mb-2 mb-md-3">
                    <div class="assessment-hero-inner p-2 p-md-4">
                        <div class="row align-items-center">
                            <div class="col-lg-7 col-md-12 mb-2 mb-lg-0">
                                <div class="d-inline-flex align-items-center assessment-badge mb-1">
                                    <i class="feather icon-award mr-50"></i>
                                    <span>PRACTICE &amp; TEST PORTAL</span>
                                </div>
                                <h1 class="assessment-hero-title">My Assessments</h1>
                                <p class="assessment-hero-subtitle mb-0">
                                    Target your focus areas with structured practice papers, track your test scores, and build exam confidence.
                                </p>
                            </div>

                            <div class="col-lg-5 col-md-12">
                                <div class="hero-stats-matrix">
                                    <div class="hero-stat-box">
                                        <span class="hero-stat-num text-primary">{{ $subjects->count() }}</span>
                                        <span class="hero-stat-lbl">Enrolled Subjects</span>
                                    </div>
                                    <div class="hero-stat-box">
                                        <span class="hero-stat-num text-info">{{ $totalPapers ?? $subjects->sum('total_papers') }}</span>
                                        <span class="hero-stat-lbl">Total Tests</span>
                                    </div>
                                    <div class="hero-stat-box">
                                        <span class="hero-stat-num text-success">{{ $totalCompleted ?? $subjects->sum('completed_papers_count') }}</span>
                                        <span class="hero-stat-lbl">Finished</span>
                                    </div>
                                    <div class="hero-stat-box">
                                        <span class="hero-stat-num text-warning">{{ $overallProgress ?? 0 }}%</span>
                                        <span class="hero-stat-lbl">Overall Prep</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Search and Quick Filters -->
                <div class="assessment-controls-card mb-3">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between p-1 p-md-2">
                        <div class="search-input-group flex-grow-1 mr-md-2 mb-1 mb-md-0 position-relative">
                            <i class="feather icon-search search-icon"></i>
                            <input type="text" id="subjectSearchInput" class="form-control subject-search-control" placeholder="Search subjects by name or keywords..." autocomplete="off">
                            <button type="button" id="clearSearchBtn" class="clear-search-btn d-none" title="Clear search">
                                <i class="feather icon-x"></i>
                            </button>
                        </div>
                        
                        <div class="filter-pills-wrapper d-flex align-items-center flex-wrap">
                            <button type="button" class="filter-pill active" data-filter="all">All (<span id="count-all">{{ $subjects->count() }}</span>)</button>
                            <button type="button" class="filter-pill" data-filter="in-progress">In Progress (<span id="count-progress">0</span>)</button>
                            <button type="button" class="filter-pill" data-filter="completed">Completed (<span id="count-completed">0</span>)</button>
                            <button type="button" class="filter-pill" data-filter="not-started">Not Started (<span id="count-notstarted">0</span>)</button>
                        </div>
                    </div>
                </div>

                <!-- Subject Cards Grid Section -->
                <section id="student-assessments-section">
                    <div class="row" id="subjectsGrid">
                        @php
                            $defaultPalettes = [
                                [ // Indigo / Blue (Maths)
                                    'gradient' => 'linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%)',
                                    'light' => 'rgba(79, 70, 229, 0.08)',
                                    'color' => '#4f46e5',
                                    'shadow' => 'rgba(79, 70, 229, 0.28)',
                                    'border' => 'rgba(79, 70, 229, 0.22)',
                                    'icon' => 'feather icon-activity'
                                ],
                                [ // Sunset Pink / Rose (English)
                                    'gradient' => 'linear-gradient(135deg, #ec4899 0%, #f43f5e 100%)',
                                    'light' => 'rgba(236, 72, 153, 0.08)',
                                    'color' => '#ec4899',
                                    'shadow' => 'rgba(236, 72, 153, 0.28)',
                                    'border' => 'rgba(236, 72, 153, 0.22)',
                                    'icon' => 'feather icon-book-open'
                                ],
                                [ // Emerald / Cyan (Verbal Reasoning)
                                    'gradient' => 'linear-gradient(135deg, #10b981 0%, #06b6d4 100%)',
                                    'light' => 'rgba(16, 185, 129, 0.08)',
                                    'color' => '#10b981',
                                    'shadow' => 'rgba(16, 185, 129, 0.28)',
                                    'border' => 'rgba(16, 185, 129, 0.22)',
                                    'icon' => 'feather icon-help-circle'
                                ],
                                [ // Violet / Indigo (Non-Verbal Reasoning)
                                    'gradient' => 'linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%)',
                                    'light' => 'rgba(139, 92, 246, 0.08)',
                                    'color' => '#8b5cf6',
                                    'shadow' => 'rgba(139, 92, 246, 0.28)',
                                    'border' => 'rgba(139, 92, 246, 0.22)',
                                    'icon' => 'feather icon-cpu'
                                ],
                                [ // Amber / Orange (Science)
                                    'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #ea580c 100%)',
                                    'light' => 'rgba(245, 158, 11, 0.08)',
                                    'color' => '#f59e0b',
                                    'shadow' => 'rgba(245, 158, 11, 0.28)',
                                    'border' => 'rgba(245, 158, 11, 0.22)',
                                    'icon' => 'feather icon-zap'
                                ],
                                [ // Teal / Blue-green (Humanities / General)
                                    'gradient' => 'linear-gradient(135deg, #0284c7 0%, #0d9488 100%)',
                                    'light' => 'rgba(2, 132, 199, 0.08)',
                                    'color' => '#0284c7',
                                    'shadow' => 'rgba(2, 132, 199, 0.28)',
                                    'border' => 'rgba(2, 132, 199, 0.22)',
                                    'icon' => 'feather icon-layers'
                                ]
                            ];
                        @endphp

                        @forelse ($subjects as $subject)
                            @php
                                $titleLower = strtolower($subject->title ?? '');
                                if (strpos($titleLower, 'math') !== false) {
                                    $style = $defaultPalettes[0];
                                } elseif (strpos($titleLower, 'eng') !== false) {
                                    $style = $defaultPalettes[1];
                                } elseif (strpos($titleLower, 'verbal') !== false && strpos($titleLower, 'non') === false) {
                                    $style = $defaultPalettes[2];
                                } elseif (strpos($titleLower, 'non') !== false || strpos($titleLower, 'nvr') !== false) {
                                    $style = $defaultPalettes[3];
                                } elseif (strpos($titleLower, 'sci') !== false) {
                                    $style = $defaultPalettes[4];
                                } else {
                                    $style = $defaultPalettes[$loop->index % count($defaultPalettes)];
                                }

                                $completedCount = $subject->completed_papers_count ?? 0;
                                $pausedCount = $subject->paused_papers_count ?? 0;
                                $totalPapers = $subject->total_papers ?? 0;
                                $progressPercentage = $subject->progress_percentage ?? 0;

                                // Determine filter status
                                if ($totalPapers > 0 && $completedCount == $totalPapers) {
                                    $filterStatus = 'completed';
                                    $statusBadgeClass = 'badge-status-completed';
                                    $statusText = 'Completed';
                                    $statusIcon = 'feather icon-check-circle';
                                } elseif ($completedCount > 0 || $pausedCount > 0) {
                                    $filterStatus = 'in-progress';
                                    $statusBadgeClass = 'badge-status-progress';
                                    $statusText = 'In Progress';
                                    $statusIcon = 'feather icon-play-circle';
                                } else {
                                    $filterStatus = 'not-started';
                                    $statusBadgeClass = 'badge-status-pending';
                                    $statusText = 'Not Started';
                                    $statusIcon = 'feather icon-circle';
                                }
                            @endphp

                            <div class="col-lg-4 col-md-6 col-sm-12 subject-col" 
                                 data-title="{{ strtolower($subject->title) }}" 
                                 data-desc="{{ strtolower($subject->description ?? '') }}" 
                                 data-status="{{ $filterStatus }}">
                                <div class="subject-card" 
                                     style="--subject-gradient: {{ $style['gradient'] }}; 
                                            --subject-gradient-light: {{ $style['light'] }}; 
                                            --subject-color: {{ $style['color'] }}; 
                                            --subject-shadow-color: {{ $style['shadow'] }};
                                            --subject-border-color: {{ $style['border'] }};">
                                    
                                    <!-- Card Header Status Ribbon -->
                                    <div class="subject-card-top-bar">
                                        <span class="subject-status-badge {{ $statusBadgeClass }}">
                                            @if($filterStatus === 'in-progress')
                                                <span class="pulse-indicator"></span>
                                            @else
                                                <i class="{{ $statusIcon }} mr-25"></i>
                                            @endif
                                            {{ $statusText }}
                                        </span>
                                    </div>

                                    <div class="subject-card-body">
                                        <div class="text-center">
                                            <!-- Dynamic Subject Image / Illustration Container -->
                                            <div class="subject-image-container">
                                                <div class="subject-image-wrapper">
                                                    @if(!empty($subject->image))
                                                        <img src="{{ asset('storage/' . $subject->image) }}" 
                                                             alt="{{ $subject->title }}" 
                                                             class="subject-dynamic-img" 
                                                             loading="lazy"
                                                             onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                                                        <div class="subject-fallback-icon d-none">
                                                            <i class="{{ $style['icon'] }}"></i>
                                                        </div>
                                                    @else
                                                        <div class="subject-fallback-icon">
                                                            <i class="{{ $style['icon'] }}"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <h3 class="subject-title">{{ $subject->title }}</h3>
                                            <p class="subject-desc" title="{{ $subject->description }}">
                                                {{ $subject->description ?: 'Improve your scores with comprehensive practice papers and timed mock assessments.' }}
                                            </p>
                                        </div>

                                        <div>
                                            <!-- 3-Item Stats Matrix -->
                                            <div class="stats-grid">
                                                <div class="stat-item">
                                                    <span class="stat-value text-primary">{{ $totalPapers }}</span>
                                                    <span class="stat-label">Total Tests</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-value text-success">{{ $completedCount }}</span>
                                                    <span class="stat-label">Finished</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-value text-warning">{{ $pausedCount }}</span>
                                                    <span class="stat-label">Paused</span>
                                                </div>
                                            </div>

                                            <!-- Progress Section -->
                                            <div class="subject-progress-container">
                                                <div class="progress-label-wrapper">
                                                    <span>
                                                        <i class="feather icon-trending-up mr-25"></i> Prep Progress
                                                    </span>
                                                    <span class="progress-percent-badge">{{ $progressPercentage }}%</span>
                                                </div>
                                                <div class="subject-progress-bar">
                                                    <div class="subject-progress-fill" style="width: {{ $progressPercentage }}%"></div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-50 font-small-2 text-muted">
                                                    <span>{{ $completedCount }} of {{ $totalPapers }} Done</span>
                                                    <span>
                                                        <i class="feather icon-calendar mr-25"></i>
                                                        {{ $subject->last_completed_at ? $subject->last_completed_at->format('d M Y') : 'Not taken yet' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Action CTA Button -->
                                            <a href="{{ route('student.assessments.topics', ['subject' => $subject->id]) }}" class="action-btn">
                                                <span>Check Assessments</span>
                                                <i class="feather icon-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="empty-state-box">
                                    <div class="empty-state-icon">
                                        <i class="feather icon-folder"></i>
                                    </div>
                                    <h4 class="empty-state-title">No Subjects Available</h4>
                                    <p class="empty-state-desc">There are no subjects configured in the student portal at this time. Please check back later or contact your administrator.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- No Results Box for Search/Filter -->
                    <div id="noSearchResultsBox" class="text-center py-5 d-none">
                        <div class="empty-state-box">
                            <div class="empty-state-icon">
                                <i class="feather icon-search"></i>
                            </div>
                            <h4 class="empty-state-title">No matching subjects found</h4>
                            <p class="empty-state-desc">We couldn't find any subjects matching your current search query or active filter.</p>
                            <button type="button" id="resetFiltersBtn" class="btn btn-primary mt-1">
                                <i class="feather icon-refresh-cw mr-50"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('subjectSearchInput');
        var clearBtn = document.getElementById('clearSearchBtn');
        var filterPills = document.querySelectorAll('.filter-pill');
        var subjectCols = document.querySelectorAll('.subject-col');
        var noResultsBox = document.getElementById('noSearchResultsBox');
        var resetBtn = document.getElementById('resetFiltersBtn');

        // Dynamic Counters
        var countProgress = 0;
        var countCompleted = 0;
        var countNotStarted = 0;

        subjectCols.forEach(function (col) {
            var status = col.getAttribute('data-status');
            if (status === 'in-progress') countProgress++;
            else if (status === 'completed') countCompleted++;
            else if (status === 'not-started') countNotStarted++;
        });

        var elProgress = document.getElementById('count-progress');
        var elCompleted = document.getElementById('count-completed');
        var elNotStarted = document.getElementById('count-notstarted');
        if (elProgress) elProgress.textContent = countProgress;
        if (elCompleted) elCompleted.textContent = countCompleted;
        if (elNotStarted) elNotStarted.textContent = countNotStarted;

        var currentFilter = 'all';

        function applyFilters() {
            var query = (searchInput ? searchInput.value : '').trim().toLowerCase();
            var visibleCount = 0;

            if (clearBtn) {
                if (query.length > 0) {
                    clearBtn.classList.remove('d-none');
                } else {
                    clearBtn.classList.add('d-none');
                }
            }

            subjectCols.forEach(function (col) {
                var title = col.getAttribute('data-title') || '';
                var desc = col.getAttribute('data-desc') || '';
                var status = col.getAttribute('data-status') || '';

                var matchesQuery = !query || title.indexOf(query) !== -1 || desc.indexOf(query) !== -1;
                var matchesFilter = currentFilter === 'all' || status === currentFilter;

                if (matchesQuery && matchesFilter) {
                    col.style.display = 'flex';
                    visibleCount++;
                } else {
                    col.style.display = 'none';
                }
            });

            if (noResultsBox) {
                if (visibleCount === 0 && subjectCols.length > 0) {
                    noResultsBox.classList.remove('d-none');
                } else {
                    noResultsBox.classList.add('d-none');
                }
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                applyFilters();
                searchInput.focus();
            });
        }

        filterPills.forEach(function (pill) {
            pill.addEventListener('click', function () {
                filterPills.forEach(function (p) { p.classList.remove('active'); });
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter');
                applyFilters();
            });
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function () {
                if (searchInput) searchInput.value = '';
                currentFilter = 'all';
                filterPills.forEach(function (p) {
                    if (p.getAttribute('data-filter') === 'all') {
                        p.classList.add('active');
                    } else {
                        p.classList.remove('active');
                    }
                });
                applyFilters();
            });
        }
    });
</script>
@endpush

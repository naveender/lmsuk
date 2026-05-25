@extends('layouts.app')

@section('title', 'Student Assessments')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                
                <!-- Page Header -->
                <div class="assessment-header text-center text-md-left">
                    <h1 class="assessment-title">My Assessments</h1>
                    <p class="assessment-subtitle">Review, monitor, and start your assignments and practice tests.</p>
                </div>

                <section id="student-assessments-section">
                    <div class="row">
                        @forelse ($subjects as $subject)
                            @php
                                $gradients = [
                                    1 => [ // Maths (Indigo/Blue)
                                        'gradient' => 'linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%)',
                                        'light' => 'rgba(79, 70, 229, 0.08)',
                                        'color' => '#4f46e5',
                                        'shadow' => 'rgba(79, 70, 229, 0.3)',
                                        'icon' => 'feather icon-activity'
                                    ],
                                    2 => [ // English (Sunset/Pink/Orange)
                                        'gradient' => 'linear-gradient(135deg, #ec4899 0%, #f43f5e 100%)',
                                        'light' => 'rgba(236, 72, 153, 0.08)',
                                        'color' => '#ec4899',
                                        'shadow' => 'rgba(236, 72, 153, 0.3)',
                                        'icon' => 'feather icon-book-open'
                                    ],
                                    3 => [ // Verbal Reasoning (Emerald/Cyan)
                                        'gradient' => 'linear-gradient(135deg, #10b981 0%, #06b6d4 100%)',
                                        'light' => 'rgba(16, 185, 129, 0.08)',
                                        'color' => '#10b981',
                                        'shadow' => 'rgba(16, 185, 129, 0.3)',
                                        'icon' => 'feather icon-help-circle'
                                    ]
                                ];
                                
                                $style = $gradients[$subject->id] ?? [
                                    'gradient' => 'linear-gradient(135deg, #7367f0 0%, #ce9ffc 100%)',
                                    'light' => 'rgba(115, 103, 240, 0.08)',
                                    'color' => '#7367f0',
                                    'shadow' => 'rgba(115, 103, 240, 0.3)',
                                    'icon' => 'feather icon-award'
                                ];

                                // Compute mocked progress or placeholder logic
                                $completedCount = 0; // Placeholder until attempts table exists
                                $progressPercentage = $subject->total_papers > 0 ? round(($completedCount / $subject->total_papers) * 100) : 0;
                            @endphp

                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="subject-card" style="--subject-gradient: {{ $style['gradient'] }}; --subject-gradient-light: {{ $style['light'] }}; --subject-color: {{ $style['color'] }}; --subject-shadow-color: {{ $style['shadow'] }};">
                                    <div class="subject-card-body">
                                        <div>
                                            <div class="subject-icon-wrapper">
                                                <i class="{{ $style['icon'] }}"></i>
                                            </div>
                                            <h4 class="subject-title">{{ $subject->title }}</h4>
                                            <p class="subject-desc">{{ $subject->description ?? 'Improve your skills with specialized practice papers and test sets.' }}</p>
                                        </div>

                                        <div>
                                            <div class="stats-grid">
                                                <div class="stat-item">
                                                    <span class="stat-value">{{ $subject->total_papers }}</span>
                                                    <span class="stat-label">Total</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-value text-info">{{ $subject->tests_count }}</span>
                                                    <span class="stat-label">Tests</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-value text-success">{{ $subject->exams_count }}</span>
                                                    <span class="stat-label">Exams</span>
                                                </div>
                                            </div>

                                            <div class="subject-progress-container">
                                                <div class="progress-label-wrapper">
                                                    <span>Prep Progress</span>
                                                    <span>{{ $completedCount }} / {{ $subject->total_papers }} Done</span>
                                                </div>
                                                <div class="subject-progress-bar">
                                                    <div class="subject-progress-fill" style="width: {{ $subject->total_papers > 0 ? '10%' : '0%' }}"></div>
                                                </div>
                                            </div>

                                            <button type="button" class="action-btn" data-toggle="modal" data-target="#subject-modal-{{ $subject->id }}">
                                                Check Assessments <i class="feather icon-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Subject Modal -->
                            <div class="modal fade" id="subject-modal-{{ $subject->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-title-{{ $subject->id }}" aria-hidden="true" style="--subject-gradient: {{ $style['gradient'] }}; --subject-gradient-light: {{ $style['light'] }}; --subject-color: {{ $style['color'] }}; --subject-shadow-color: {{ $style['shadow'] }};">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal-title-{{ $subject->id }}">
                                                <i class="{{ $style['icon'] }}"></i> {{ $subject->title }} - Assigned Papers
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($subject->papers->isNotEmpty())
                                                <div class="paper-list">
                                                    @foreach ($subject->papers as $paper)
                                                        @php
                                                            $difficultyColors = [
                                                                'easy' => 'badge-light-success text-success',
                                                                'medium' => 'badge-light-warning text-warning',
                                                                'hard' => 'badge-light-danger text-danger'
                                                            ];
                                                            $diffBadge = $difficultyColors[$paper->difficulty] ?? 'badge-light-primary text-primary';
                                                            
                                                            $typeBadges = [
                                                                'test' => 'badge-light-info text-info',
                                                                'exam' => 'badge-light-success text-success'
                                                            ];
                                                            $typeBadge = $typeBadges[$paper->type] ?? 'badge-light-primary text-primary';
                                                        @endphp
                                                        <div class="paper-list-item difficulty-{{ $paper->difficulty }}">
                                                            <div>
                                                                <div class="paper-info-title">{{ $paper->title }}</div>
                                                                <div class="paper-meta">
                                                                    <span>
                                                                        <span class="badge badge-difficulty {{ $diffBadge }}">{{ $paper->difficulty ?? 'Medium' }}</span>
                                                                    </span>
                                                                    <span>
                                                                        <span class="badge badge-type {{ $typeBadge }}">{{ $paper->type === 'test' ? 'Test' : 'Exam' }}</span>
                                                                    </span>
                                                                    <span>
                                                                        <i class="feather icon-clock"></i> {{ $paper->total_time }} Mins
                                                                    </span>
                                                                    <span>
                                                                        <i class="feather icon-award"></i> {{ $paper->default_marks }} Marks
                                                                    </span>
                                                                    <span>
                                                                        <i class="feather icon-file-text"></i> {{ $paper->questions()->count() }} Questions
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <button type="button" class="paper-action-btn" onclick="alert('Exam system initialized. Ready to begin attempt.')">
                                                                    Start
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="empty-state">
                                                    <i class="feather icon-file-text"></i>
                                                    <h5>No Assessments Yet</h5>
                                                    <p>There are currently no active papers or practice tests assigned to you for this subject.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="empty-state">
                                    <i class="feather icon-folder"></i>
                                    <h5>No Subjects Available</h5>
                                    <p>There are no subjects configured in the portal at this time.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

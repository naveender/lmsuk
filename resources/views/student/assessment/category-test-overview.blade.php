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

                                // Compute progress based on attempts
                                $completedCount = $subject->completed_papers_count ?? 0;
                                $progressPercentage = $subject->progress_percentage ?? 0;
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
                                                    <span class="stat-value text-warning">{{ $subject->paused_papers_count }}</span>
                                                    <span class="stat-label">Paused</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-value text-info">{{ $subject->completed_papers_count }}/{{ $subject->total_papers }}</span>
                                                    <span class="stat-label">Finished</span>
                                                </div>
                                                <div class="stat-item">
                                                    <span class="stat-value text-success" style="font-size: 0.95rem;">{{ $subject->last_completed_at ? $subject->last_completed_at->format('d/m/Y') : 'N/A' }}</span>
                                                    <span class="stat-label">Last Completed</span>
                                                </div>
                                            </div>

                                            <div class="subject-progress-container">
                                                <div class="progress-label-wrapper">
                                                    <span>Prep Progress</span>
                                                    <span>{{ $completedCount }} / {{ $subject->total_papers }} Done</span>
                                                </div>
                                                <div class="subject-progress-bar">
                                                    <div class="subject-progress-fill" style="width: {{ $progressPercentage }}%"></div>
                                                </div>
                                            </div>

                                            <a href="{{ route('student.assessments.topics', ['subject' => $subject->id]) }}" class="action-btn">
                                                Check Assessments <i class="feather icon-arrow-right"></i>
                                            </a>
                                            
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

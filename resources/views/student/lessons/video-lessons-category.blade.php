@extends('layouts.app')

@section('title', 'Video Lessons Category')

@push('styles')
<style>
    /* Premium Styling for Video Lessons Portal */
    .lessons-header-card {
        background: linear-gradient(135deg, #7367f0 0%, #a83279 100%);
        border-radius: 16px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(115, 103, 240, 0.2);
    }
    
    .lessons-header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -30%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        pointer-events: none;
    }

    .subject-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        position: relative;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .subject-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    /* Individual Subject Accent Styles */
    .subject-card-maths {
        border-top: 5px solid #7367f0;
    }
    .subject-card-maths .subject-icon-box {
        background: rgba(115, 103, 240, 0.1);
        color: #7367f0;
    }
    .subject-card-maths .subject-btn {
        background: #7367f0;
        color: white;
    }
    .subject-card-maths .subject-btn:hover {
        background: #5e50eb;
        box-shadow: 0 4px 12px rgba(115, 103, 240, 0.3);
    }

    .subject-card-english {
        border-top: 5px solid #ff9f43;
    }
    .subject-card-english .subject-icon-box {
        background: rgba(255, 159, 67, 0.1);
        color: #ff9f43;
    }
    .subject-card-english .subject-btn {
        background: #ff9f43;
        color: white;
    }
    .subject-card-english .subject-btn:hover {
        background: #e08b35;
        box-shadow: 0 4px 12px rgba(255, 159, 67, 0.3);
    }

    .subject-card-verbal {
        border-top: 5px solid #00cfe8;
    }
    .subject-card-verbal .subject-icon-box {
        background: rgba(0, 207, 232, 0.1);
        color: #00cfe8;
    }
    .subject-card-verbal .subject-btn {
        background: #00cfe8;
        color: white;
    }
    .subject-card-verbal .subject-btn:hover {
        background: #00b5cc;
        box-shadow: 0 4px 12px rgba(0, 207, 232, 0.3);
    }

    /* Default Accent */
    .subject-card-default {
        border-top: 5px solid #28c76f;
    }
    .subject-card-default .subject-icon-box {
        background: rgba(40, 199, 111, 0.1);
        color: #28c76f;
    }
    .subject-card-default .subject-btn {
        background: #28c76f;
        color: white;
    }
    .subject-card-default .subject-btn:hover {
        background: #20a75a;
        box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
    }

    .subject-icon-box {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }

    .subject-card:hover .subject-icon-box {
        transform: scale(1.1) rotate(5deg);
    }

    .progress-bar-custom {
        height: 6px;
        border-radius: 4px;
        background-color: #f1f3f9;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.5s ease-in-out;
    }

    .subject-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 1.25rem;
    }

    .subject-desc {
        color: #64748b;
        font-size: 0.88rem;
        min-height: 40px;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .subject-meta-item {
        font-size: 0.8rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .subject-btn {
        border-radius: 8px;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.2s ease;
        border: none;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body py-2">
            
            <!-- Dynamic Welcome Header -->
            <div class="card lessons-header-card mb-3 border-0">
                <div class="card-body p-3 p-md-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-white font-weight-bold mb-1">
                                <i class="feather icon-video mr-50"></i> Video Lessons Category
                            </h2>
                            <p class="text-white opacity-75 mb-0 font-medium-1">
                                High-definition lessons tailored specifically to your learning track. Master your syllabus and track your progress in real-time.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-right mt-2 mt-md-0 d-none d-md-block">
                            <span class="badge badge-light-primary px-2 py-1 font-medium-1 text-white" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.4);">
                                <i class="feather icon-user-check mr-50"></i>{{ auth()->user()->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subject Grid Area -->
            <div class="row">
                @forelse($subjects as $subject)
                    @php
                        // Map subject names to specific layout cards dynamically
                        $titleLower = strtolower($subject->title);
                        $accentClass = 'subject-card-default';
                        $iconClass = 'icon-book';
                        $fillColor = '#28c76f';

                        if (strpos($titleLower, 'math') !== false) {
                            $accentClass = 'subject-card-maths';
                            $iconClass = 'icon-award';
                            $fillColor = '#7367f0';
                        } elseif (strpos($titleLower, 'english') !== false) {
                            $accentClass = 'subject-card-english';
                            $iconClass = 'icon-feather';
                            $fillColor = '#ff9f43';
                        } elseif (strpos($titleLower, 'verbal') !== false) {
                            $accentClass = 'subject-card-verbal';
                            $iconClass = 'icon-cpu';
                            $fillColor = '#00cfe8';
                        }

                        $stats = $progressBySubject[$subject->id] ?? ['total' => 0, 'completed' => 0, 'percent' => 0];
                    @endphp

                    <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
                        <div class="card subject-card {{ $accentClass }} h-100">
                            <div class="card-body p-2 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="subject-icon-box">
                                        <i class="feather {{ $iconClass }}"></i>
                                    </div>
                                    <h4 class="subject-title">{{ $subject->title }}</h4>
                                    <p class="subject-desc">{{ $subject->description ?: 'Browse our interactive library of ' . $subject->title . ' video lessons and quizzes.' }}</p>
                                    
                                    <!-- Progress Stats -->
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center font-small-3">
                                            <span class="font-weight-bold text-dark">Overall Progress</span>
                                            <span class="font-weight-bold" style="color: {{ $fillColor }};">{{ $stats['percent'] }}%</span>
                                        </div>
                                        <div class="progress-bar-custom">
                                            <div class="progress-fill" style="width: {{ $stats['percent'] }}%; background-color: {{ $fillColor }};"></div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-50 text-muted font-small-2">
                                            <span>{{ $stats['completed'] }} of {{ $stats['total'] }} completed</span>
                                            <span>Seek-proof tracking enabled</span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('student.videolessonslist', ['subject_id' => $subject->id]) }}" class="btn subject-btn">
                                    <span>Let's Watch Now</span>
                                    <i class="feather icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card text-center border-0 shadow-sm p-4 bg-white">
                            <div class="card-body">
                                <i class="feather icon-video text-muted font-large-3 mb-2"></i>
                                <h4 class="text-dark font-weight-bold">No Video Lessons Available</h4>
                                <p class="text-muted">There are no unassigned general video files published matching your class group, year, and session settings.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>
@endsection
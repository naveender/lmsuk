@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">

                <!-- Welcome Header -->
                @php
                    $theme = session('theme', 'light');
                    $hour = date('H');
                    if ($hour < 12) {
                        $greeting = 'Good Morning 🌅';
                    } elseif ($hour < 17) {
                        $greeting = 'Good Afternoon ☀️';
                    } else {
                        $greeting = 'Good Evening 🌙';
                    }
                @endphp
                <div class="welcome-card">
                    <div class="row align-items-center">
                        <div class="col-md-8 text-center text-md-left">
                            <h1 class="welcome-title text-white">{{ $greeting }}, {{ auth()->user()->name }}! 🚀</h1>
                            <p class="welcome-text">
                                Welcome back to your Aspire Learners command center. Here is a quick snapshot of the portal activity and system statistics.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- KPI Statistics Grid Section -->
                <section id="kpi-dashboard-stats">
                    <div class="row">
                        <!-- Total Students -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="kpi-stat-card kpi-card-gradient">
                                <a href="{{ route('admin.students.index') }}" class="d-block p-2 text-decoration-none">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="kpi-count text-primary font-weight-bold">{{ $stats['students'] }}</h2>
                                            <p class="text-muted font-weight-bold mb-0">Total Students</p>
                                        </div>
                                        <div class="kpi-icon-container bg-light-primary text-primary">
                                            <i class="feather icon-user"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Total Classes -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="kpi-stat-card kpi-card-gradient">
                                <a href="{{ route('admin.classes.index') }}" class="d-block p-2 text-decoration-none">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="kpi-count text-danger font-weight-bold">{{ $stats['classes'] }}</h2>
                                            <p class="text-muted font-weight-bold mb-0">Active Classes</p>
                                        </div>
                                        <div class="kpi-icon-container bg-light-danger text-danger">
                                            <i class="feather icon-users"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Total Questions -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="kpi-stat-card kpi-card-gradient">
                                <a href="{{ route('admin.questions.index') }}" class="d-block p-2 text-decoration-none">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="kpi-count text-warning font-weight-bold">{{ $stats['questions'] }}</h2>
                                            <p class="text-muted font-weight-bold mb-0">Question Bank</p>
                                        </div>
                                        <div class="kpi-icon-container bg-light-warning text-warning">
                                            <i class="feather icon-help-circle"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Total Papers -->
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="kpi-stat-card kpi-card-gradient">
                                <a href="{{ route('admin.papers.index') }}" class="d-block p-2 text-decoration-none">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h2 class="kpi-count text-info font-weight-bold">{{ $stats['papers'] }}</h2>
                                            <p class="text-muted font-weight-bold mb-0">Exam Papers</p>
                                        </div>
                                        <div class="kpi-icon-container bg-light-info text-info">
                                            <i class="feather icon-file-text"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Interactive Charts Section -->
                <section id="dashboard-charts">
                    <div class="row">
                        <!-- Class Enrollment Column Chart -->
                        <div class="col-md-6 col-12">
                            <div class="chart-wrapper-card">
                                <h4 class="card-title font-weight-bold mb-2">Class Enrollment Metrics</h4>
                                <div id="classStatsChart"></div>
                            </div>
                        </div>

                        <!-- Subject Question Distribution Pie Chart -->
                        <div class="col-md-6 col-12">
                            <div class="chart-wrapper-card">
                                <h4 class="card-title font-weight-bold mb-2">Subject Question Bank Spread</h4>
                                <div id="subjectStatsChart"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Admin Core Quick Actions Panel -->
                <section id="admin-actions-grid">
                    <div class="row mb-2">
                        <div class="col-12">
                            <h3 class="font-weight-bold mt-1 mb-2">System Quick Actions</h3>
                        </div>
                    </div>
                    
                    <div class="row" id="student-dashboard-grid">
                        <!-- Manage Students -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); --card-light: rgba(17, 153, 142, 0.08); --card-color: #11998e; --card-shadow: rgba(17, 153, 142, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/community.png') }}"
                                            alt="Manage Students" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Manage Students</h4>
                                    <p class="dashboard-card-text">View records, edit details, assign cohorts, and track learning progress.</p>
                                    <a href="{{ route('admin.students.index') }}" class="card-action-btn">
                                        Manage Students <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Manage Classes -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%); --card-light: rgba(255, 65, 108, 0.08); --card-color: #ff416c; --card-shadow: rgba(255, 65, 108, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/training.png') }}"
                                            alt="Manage Classes" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Manage Classes</h4>
                                    <p class="dashboard-card-text">Configure class rooms, enroll students, and link academic groups.</p>
                                    <a href="{{ route('admin.classes.index') }}" class="card-action-btn">
                                        Manage Classes <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Questions Bank -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #7367f0 0%, #ce9ffc 100%); --card-light: rgba(115, 103, 240, 0.08); --card-color: #7367f0; --card-shadow: rgba(115, 103, 240, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/cloud-upload.png') }}"
                                            alt="Questions Bank" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Questions Bank</h4>
                                    <p class="dashboard-card-text">Create, import, organize, and categorize assessment questions.</p>
                                    <a href="{{ route('admin.questions.index') }}" class="card-action-btn">
                                        Questions Bank <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Manage Announcements -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #f12711 0%, #f5af19 100%); --card-light: rgba(241, 39, 17, 0.08); --card-color: #f12711; --card-shadow: rgba(241, 39, 17, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/announcement.png') }}"
                                            alt="Manage Announcements" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Manage Announcements</h4>
                                    <p class="dashboard-card-text">Broadcast messages, notifications, and center news to members.</p>
                                    <a href="{{ route('admin.announcements.index') }}" class="card-action-btn">
                                        Announcements <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Users Directory -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #2f80ed 0%, #56ccf2 100%); --card-light: rgba(47, 128, 237, 0.08); --card-color: #2f80ed; --card-shadow: rgba(47, 128, 237, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/new-employee.png') }}"
                                            alt="Users Directory" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Users Directory</h4>
                                    <p class="dashboard-card-text">Oversee users across all roles: admins, tutors, students, parents.</p>
                                    <a href="{{ route('admin.users.index') }}" class="card-action-btn">
                                        Users Directory <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Create Exam Paper -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #3f51b5 0%, #00bcd4 100%); --card-light: rgba(63, 81, 181, 0.08); --card-color: #3f51b5; --card-shadow: rgba(63, 81, 181, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/report.png') }}"
                                            alt="Create Exam Paper" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Create Exam Paper</h4>
                                    <p class="dashboard-card-text">Design and customize mock test sheets, duration, styles, and instructions.</p>
                                    <a href="{{ route('admin.papers.create') }}" class="card-action-btn">
                                        Create Paper <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Papers Directory -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #8e2de2 0%, #4a00e0 100%); --card-light: rgba(142, 45, 226, 0.08); --card-color: #8e2de2; --card-shadow: rgba(142, 45, 226, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/archive.png') }}"
                                            alt="Papers Directory" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Papers Directory</h4>
                                    <p class="dashboard-card-text">List, assign, duplicate, edit, or delete existing evaluation exam papers.</p>
                                    <a href="{{ route('admin.papers.index') }}" class="card-action-btn">
                                        Papers Directory <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Create Announcement -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #f857a6 0%, #ff5858 100%); --card-light: rgba(248, 87, 166, 0.08); --card-color: #f857a6; --card-shadow: rgba(248, 87, 166, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/bill.png') }}"
                                            alt="Create Announcement" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">Create Announcement</h4>
                                    <p class="dashboard-card-text">Publish critical updates directly to students and parent dashboards.</p>
                                    <a href="{{ route('admin.announcements.create') }}" class="card-action-btn">
                                        Add Announcement <i class="feather icon-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Global Settings -->
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="dashboard-card"
                                style="--card-gradient: linear-gradient(135deg, #e52d27 0%, #b31217 100%); --card-light: rgba(229, 45, 39, 0.08); --card-color: #e52d27; --card-shadow: rgba(229, 45, 39, 0.35);">
                                <div class="dashboard-card-body">
                                    <div class="card-icon-container">
                                        <img src="{{ asset('theme/app-assets/images/lmsicon/more/line-chart.png') }}"
                                            alt="Global Settings" loading="lazy">
                                    </div>
                                    <h4 class="dashboard-card-title">System Settings</h4>
                                    <p class="dashboard-card-text">Configure default paths, notification preferences, and storage details.</p>
                                    <a href="{{ route('settings.index') }}" class="card-action-btn">
                                        Configure System <i class="feather icon-arrow-right"></i>
                                    </a>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dynamic top loading indicator for internal link transitions
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            const target = this.getAttribute('target');
            
            if (href && !href.startsWith('#') && !href.startsWith('javascript:') && (!target || target !== '_blank')) {
                if (!document.querySelector('.page-top-progress')) {
                    const progress = document.createElement('div');
                    progress.className = 'page-top-progress';
                    document.body.appendChild(progress);
                }
            }
        });
    });

    // Class Stats Column Chart Setup
    const classLabels = {!! json_encode($classStats->pluck('name')) !!};
    const classCounts = {!! json_encode($classStats->pluck('students_count')) !!};

    const classChartOptions = {
        chart: {
            type: 'bar',
            height: 320,
            toolbar: { show: false }
        },
        series: [{
            name: 'Students',
            data: classCounts.length ? classCounts : [0]
        }],
        xaxis: {
            categories: classLabels.length ? classLabels : ['No Active Classes'],
            labels: {
                style: {
                    colors: '#828690',
                    fontSize: '11px',
                    fontFamily: 'Montserrat, Helvetica, Arial, sans-serif'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#828690',
                    fontSize: '11px',
                    fontFamily: 'Montserrat, Helvetica, Arial, sans-serif'
                }
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 8,
                columnWidth: '40%',
                distributed: true
            }
        },
        colors: ['#7367f0', '#28c76f', '#ff9f43', '#ea5455', '#00cfe8'],
        dataLabels: { enabled: false },
        legend: { show: false },
        tooltip: { theme: '{{ $theme === "dark" ? "dark" : "light" }}' }
    };
    const classChart = new ApexCharts(document.querySelector("#classStatsChart"), classChartOptions);
    classChart.render();

    // Subject Stats Donut Chart Setup
    const subjectLabels = {!! json_encode($subjectStats->pluck('title')) !!};
    const subjectCounts = {!! json_encode($subjectStats->pluck('questions_count')) !!};

    const subjectChartOptions = {
        chart: {
            type: 'donut',
            height: 320
        },
        series: subjectCounts.length ? subjectCounts : [1],
        labels: subjectLabels.length ? subjectLabels : ['Empty Bank'],
        colors: ['#7367f0', '#ff9f43', '#28c76f', '#00cfe8', '#ea5455'],
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '14px',
                            fontWeight: 600,
                            colors: '#828690',
                            fontFamily: 'Montserrat, Helvetica, Arial, sans-serif'
                        },
                        value: {
                            show: true,
                            fontSize: '20px',
                            fontWeight: 800,
                            color: '{{ $theme === "dark" ? "#e3e8f0" : "#2c3e50" }}',
                            fontFamily: 'Montserrat, Helvetica, Arial, sans-serif'
                        },
                        total: {
                            show: true,
                            label: 'Total Questions',
                            color: '#828690',
                            fontFamily: 'Montserrat, Helvetica, Arial, sans-serif',
                            formatter: function (w) {
                                return subjectCounts.length ? w.globals.seriesTotals.reduce((a, b) => a + b, 0) : 0;
                            }
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        legend: {
            position: 'bottom',
            labels: {
                colors: '{{ $theme === "dark" ? "#a0aec0" : "#718096" }}'
            },
            fontFamily: 'Montserrat, Helvetica, Arial, sans-serif'
        },
        tooltip: { theme: '{{ $theme === "dark" ? "dark" : "light" }}' }
    };
    const subjectChart = new ApexCharts(document.querySelector("#subjectStatsChart"), subjectChartOptions);
    subjectChart.render();
});
</script>
@endpush

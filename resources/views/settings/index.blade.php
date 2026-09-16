@extends('layouts.app')

@section('title', 'Settings - Aspire Learners')

@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
    $currentTheme = session('theme', 'light');
@endphp

@push('styles')
<style>
    .theme-preview-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #fff;
        position: relative;
    }
    .theme-preview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }
    .theme-preview-card.active {
        border-color: #7367f0;
        background: rgba(115, 103, 240, 0.04);
    }
    .theme-preview-card .active-badge {
        display: none;
        position: absolute;
        top: 12px;
        right: 12px;
        background: #7367f0;
        color: #fff;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    .theme-preview-card.active .active-badge {
        display: flex;
    }
    .theme-box-mockup {
        height: 80px;
        border-radius: 8px;
        margin-bottom: 12px;
        display: flex;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.1);
    }
    .theme-mockup-light {
        background: #f8fafc;
    }
    .theme-mockup-light .mockup-sidebar {
        width: 28%;
        background: #ffffff;
        border-right: 1px solid #e2e8f0;
    }
    .theme-mockup-light .mockup-body {
        width: 72%;
        background: #f1f5f9;
        padding: 8px;
    }
    .theme-mockup-dark {
        background: #0f172a;
    }
    .theme-mockup-dark .mockup-sidebar {
        width: 28%;
        background: #1e293b;
        border-right: 1px solid #334155;
    }
    .theme-mockup-dark .mockup-body {
        width: 72%;
        background: #0f172a;
        padding: 8px;
    }
    .settings-nav-tabs .nav-link {
        font-weight: 600;
        padding: 12px 20px;
        border: none;
        border-bottom: 3px solid transparent;
        color: #64748b;
        background: transparent;
        transition: all 0.2s ease;
    }
    .settings-nav-tabs .nav-link.active {
        color: #7367f0;
        border-bottom-color: #7367f0;
    }
    .dark-layout .theme-preview-card {
        background: #262c49;
        border-color: #3b4253;
    }
    .dark-layout .theme-preview-card.active {
        border-color: #7367f0;
        background: rgba(115, 103, 240, 0.15);
    }
    .dark-layout .settings-nav-tabs .nav-link {
        color: #94a3b8;
    }
    .dark-layout .settings-nav-tabs .nav-link.active {
        color: #7367f0;
        border-bottom-color: #7367f0;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <!-- Content Header & Breadcrumbs -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0 font-weight-bold">Settings</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Application Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-check-circle mr-2 font-medium-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-alert-triangle mr-2 font-medium-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($isAdmin)
                <!-- Admin Tab Switcher -->
                <div class="card mb-3">
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs settings-nav-tabs" id="settingsTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="preferences-tab" data-toggle="tab" href="#preferences-pane" role="tab">
                                    <i class="feather icon-sliders mr-1"></i> User Preferences
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="system-tab" data-toggle="tab" href="#system-pane" role="tab">
                                    <i class="feather icon-server mr-1"></i> System &amp; Backup Configuration
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                <div class="tab-content" id="settingsTabContent">
                    <!-- Preferences Pane (All Users) -->
                    <div class="tab-pane fade show active" id="preferences-pane" role="tabpanel">
                        
                        <!-- Appearance & Theme -->
                        <div class="card mb-3">
                            <div class="card-header pb-1">
                                <h4 class="card-title font-weight-bold">
                                    <i class="feather icon-sun mr-1 text-warning"></i> Appearance &amp; Theme
                                </h4>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">Customize how the Aspire Learners platform looks on your screen.</p>
                                
                                <div class="row">
                                    <!-- Light Mode Option -->
                                    <div class="col-md-6 col-12 mb-3 mb-md-0">
                                        <label class="w-100 mb-0">
                                            <input type="radio" name="theme" value="light" class="d-none theme-radio" {{ $currentTheme === 'light' ? 'checked' : '' }}>
                                            <div class="theme-preview-card {{ $currentTheme === 'light' ? 'active' : '' }}" id="themeCardLight">
                                                <div class="active-badge">
                                                    <i class="feather icon-check"></i>
                                                </div>
                                                <div class="theme-box-mockup theme-mockup-light">
                                                    <div class="mockup-sidebar"></div>
                                                    <div class="mockup-body">
                                                        <div style="height: 10px; width: 60%; background: #cbd5e1; border-radius: 4px; margin-bottom: 6px;"></div>
                                                        <div style="height: 14px; width: 90%; background: #e2e8f0; border-radius: 4px;"></div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h5 class="font-weight-bold mb-0 text-dark">
                                                            <i class="feather icon-sun mr-1 text-warning"></i> Light Mode
                                                        </h5>
                                                        <small class="text-muted">Standard clean & crisp interface</small>
                                                    </div>
                                                    <span class="badge badge-light-primary">Light</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Dark Mode Option -->
                                    <div class="col-md-6 col-12">
                                        <label class="w-100 mb-0">
                                            <input type="radio" name="theme" value="dark" class="d-none theme-radio" {{ $currentTheme === 'dark' ? 'checked' : '' }}>
                                            <div class="theme-preview-card {{ $currentTheme === 'dark' ? 'active' : '' }}" id="themeCardDark">
                                                <div class="active-badge">
                                                    <i class="feather icon-check"></i>
                                                </div>
                                                <div class="theme-box-mockup theme-mockup-dark">
                                                    <div class="mockup-sidebar"></div>
                                                    <div class="mockup-body">
                                                        <div style="height: 10px; width: 60%; background: #475569; border-radius: 4px; margin-bottom: 6px;"></div>
                                                        <div style="height: 14px; width: 90%; background: #334155; border-radius: 4px;"></div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h5 class="font-weight-bold mb-0 text-dark">
                                                            <i class="feather icon-moon mr-1 text-primary"></i> Dark Mode
                                                        </h5>
                                                        <small class="text-muted">Sleek, low-light eye comfort</small>
                                                    </div>
                                                    <span class="badge badge-light-secondary">Dark</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="card mb-3">
                            <div class="card-header pb-1">
                                <h4 class="card-title font-weight-bold">
                                    <i class="feather icon-bell mr-1 text-info"></i> Notification Preferences
                                </h4>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">Manage how and when you receive automated updates from the academy.</p>

                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <h6 class="font-weight-bold mb-0">Announcement & Notice Alerts</h6>
                                        <small class="text-muted">Receive email notification whenever a new class announcement is published.</small>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="pref_announcements_email" value="0">
                                        <input type="checkbox" class="custom-control-input" id="announcementsEmailSwitch" 
                                               name="pref_announcements_email" value="1" 
                                               {{ setting('pref_announcements_email', '1') == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="announcementsEmailSwitch"></label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <h6 class="font-weight-bold mb-0">Assessments & Homework Reminders</h6>
                                        <small class="text-muted">Stay notified about upcoming weekly paper deadlines and published exam results.</small>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="pref_assessments_email" value="0">
                                        <input type="checkbox" class="custom-control-input" id="assessmentsEmailSwitch" 
                                               name="pref_assessments_email" value="1" 
                                               {{ setting('pref_assessments_email', '1') == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="assessmentsEmailSwitch"></label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <div>
                                        <h6 class="font-weight-bold mb-0">Audio Cues & Sound Alerts</h6>
                                        <small class="text-muted">Play helpful gentle sounds during practice tests and timer notifications.</small>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="hidden" name="pref_sound_alerts" value="0">
                                        <input type="checkbox" class="custom-control-input" id="soundAlertsSwitch" 
                                               name="pref_sound_alerts" value="1" 
                                               {{ setting('pref_sound_alerts', '1') == '1' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="soundAlertsSwitch"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Localization & Display -->
                        <div class="card mb-3">
                            <div class="card-header pb-1">
                                <h4 class="card-title font-weight-bold">
                                    <i class="feather icon-globe mr-1 text-primary"></i> Region &amp; Display Preferences
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Timezone</label>
                                            <input type="text" class="form-control bg-light" value="Europe/London (GMT / BST)" disabled readonly>
                                            <small class="text-muted">Standard academic calendar timezone.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Date Format</label>
                                            <select name="pref_date_format" class="form-control">
                                                <option value="d/m/Y" {{ setting('pref_date_format', 'd/m/Y') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (UK Standard)</option>
                                                <option value="m/d/Y" {{ setting('pref_date_format') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                                <option value="Y-m-d" {{ setting('pref_date_format') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (ISO)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mb-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold">
                                <i class="feather icon-save mr-1"></i> Save Settings
                            </button>
                        </div>

                    </div>

                    @if($isAdmin)
                        <!-- Admin System & Backup Pane -->
                        <div class="tab-pane fade" id="system-pane" role="tabpanel">
                            
                            <!-- Storage Configuration -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h4 class="card-title font-weight-bold">
                                        <i class="feather icon-server mr-1 text-primary"></i> Storage Configuration
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Default Storage Path</label>
                                                <input type="text" class="form-control" name="storage_default_path"
                                                       value="{{ setting('storage_default_path', '/app/storage/restore') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Storage Usage</label>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small class="text-muted">156.7 GB / 500 GB used</small>
                                                    <small class="text-primary font-weight-bold">31.3%</small>
                                                </div>
                                                <div class="progress progress-bar-primary progress-lg">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                         role="progressbar" style="width: 31.3%" aria-valuenow="31.3" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Available Space</label>
                                                <div class="p-2 bg-light rounded text-muted">
                                                    <i class="feather icon-hard-drive mr-1"></i> 343.3 GB remaining on backup volume
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Notifications -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h4 class="card-title font-weight-bold">
                                        <i class="feather icon-mail mr-1 text-primary"></i> Automated Backup Notifications
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="font-weight-bold mb-0">Email Notifications</h6>
                                            <small class="text-muted">Receive backup and system health alerts via email</small>
                                        </div>
                                        <div class="custom-control custom-switch">
                                            <input type="hidden" name="notification_email_enabled" value="0">
                                            <input type="checkbox" class="custom-control-input" name="notification_email_enabled"
                                                   id="EmailNotificationSwitch" value="1" {{ setting('notification_email_enabled') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="EmailNotificationSwitch"></label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="NotificationEmail" class="font-weight-bold">Notification Recipient Email</label>
                                        <input type="email" id="NotificationEmail" name="notification_email"
                                               class="form-control" placeholder="admin@aspire.com"
                                               value="{{ setting('notification_email', auth()->user()->email) }}">
                                    </div>
                                </div>
                            </div>

                            <!-- System Information -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h4 class="card-title font-weight-bold">
                                        <i class="feather icon-cpu mr-1 text-primary"></i> System Information
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12 mb-2">
                                            <label class="font-weight-bold text-muted">Backup Service Status</label>
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-success mr-2"><i class="feather icon-check"></i> Operational</span>
                                                <span class="small text-muted">{{ setting('system_backup_status', 'Healthy') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label class="font-weight-bold text-muted">Last System Backup</label>
                                            <div>
                                                <span class="font-weight-bold">{{ setting('system_last_backup', now()->subHours(6)->format('Y-m-d H:i')) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label class="font-weight-bold text-muted">System Framework Version</label>
                                            <div>
                                                <span class="badge badge-light-primary font-weight-bold">Laravel {{ app()->version() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 mb-2">
                                            <label class="font-weight-bold text-muted">Database Engine</label>
                                            <div>
                                                <span class="font-weight-bold">{{ setting('system_database_engine', config('database.default', 'mysql')) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mb-4">
                                <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold">
                                    <i class="feather icon-save mr-1"></i> Save All Settings
                                </button>
                            </div>

                        </div>
                    @endif

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Theme selection card interactions
        var themeRadios = document.querySelectorAll('.theme-radio');
        var cardLight = document.getElementById('themeCardLight');
        var cardDark = document.getElementById('themeCardDark');

        themeRadios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (this.value === 'light') {
                    cardLight.classList.add('active');
                    cardDark.classList.remove('active');
                } else {
                    cardDark.classList.add('active');
                    cardLight.classList.remove('active');
                }
            });
        });
    });
</script>
@endpush

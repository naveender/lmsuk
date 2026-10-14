@extends('layouts.app')
@section('title', 'Settings')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <!-- Page Header / Title & Breadcrumbs -->
            <div class="admin-page-header">
                <div class="admin-page-title-wrap">
                    <div class="admin-title-row">
                        <h1 class="admin-page-heading">Backup Settings</h1>
                        <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <span>Backup</span>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <span>Settings</span>
                        </nav>
                    </div>
                    <p class="admin-page-subtitle">Configure backup system preferences, cloud storage, and automation schedules</p>
                </div>
            </div>
            <div class="content-body">


                <form class="form form-vertical" action="{{ route('settings.update') }}" method="POST">
                    @csrf
                <!-- Storage Configuration -->
                <section id="floating-label-layouts">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="feather icon-server"></i> Storage Configuration
                                    </h4>
                                   

                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                       
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <h6 for="retention-period">Default Storage Path</h6>
                                                            <input type="text" id="retention-period"
                                                                class="form-control" name="storage_default_path"
                                                                placeholder="/var/backups/production"
                                                                value="{{ setting('storage_default_path', '/app/storage/restore') }}" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <h6 for="backup-frequency">Storage Usage</h6>
                                                            <small>156.7 GB / 500 GB used</small>
                                                            <div class="progress progress-bar-primary progress-lg">
                                                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                                    role="progressbar" aria-valuenow="40"
                                                                    aria-valuemin="40" aria-valuemax="100"
                                                                    style="width:40%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <h6 for="backup-time-id">Available Space</h6>
                                                            <small>343.3 GB remaining</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Notifications -->
                <section id="floating-label-layouts">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="feather icon-bell"></i> Notifications</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                       
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div
                                                            class="d-flex justify-content-between custom-control custom-switch mr-2 mb-1">
                                                            <div>
                                                                <h6 class="mb-0">Email Notifications</h6>
                                                                <small>Receive backup status updates via email</small>
                                                            </div>
                                                            <div>
                                                                <input type="hidden" name="notification_email_enabled" value="0">
                                                                <input type="checkbox" class="custom-control-input"  name="notification_email_enabled"
                                                                    id="EmailNotificationSwitch" value="1" {{ setting('notification_email_enabled') ? 'checked' : '' }}>
                                                                <label class="custom-control-label"
                                                                    for="EmailNotificationSwitch"></label>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div class="form-group">
                                                            <h6 for="NotificationEmail">Notification Email</h6>
                                                            <input type="email" id="NotificationEmail" name="notification_email"
                                                                class="form-control"
                                                                placeholder="admin@gmail.com"  value="{{ setting('notification_email') }}"
                                                                {{ setting('notification_email_enabled') ? '' : 'disabled' }}>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- System Information -->
                <section id="floating-label-layouts">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="feather icon-airplay"></i> System Information</h4>
                                   

                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                       
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <h6 for="backup-frequency">Backup Service Status</h6>
                                                            <small>{{ setting('system_backup_status') }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <h6 for="backup-time-id">Last Backup</h6>
                                                            <small>{{ setting('system_last_backup') }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <h6 for="backup-frequency">System Version</h6>
                                                            <small>{{ setting('system_version') }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <h6 for="backup-time-id">Database Engine</h6>
                                                            <small>{{ setting('system_database_engine') }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary mr-1 mb-1">Save Settings</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection

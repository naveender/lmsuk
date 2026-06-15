@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('theme/app-assets/vendors/css/forms/select/select2.min.css') }}">
    <style>
        .select2-container {
            width: 100% !important;
        }
        .recipient-group-card {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
        }
        .recipient-group-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .preview-card {
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fff;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .priority-high {
            border-left: 6px solid #ea5455;
            box-shadow: 0 10px 25px -5px rgba(234, 84, 85, 0.08), 0 8px 10px -6px rgba(234, 84, 85, 0.08);
        }
        .priority-medium {
            border-left: 6px solid #ff9f43;
            box-shadow: 0 10px 25px -5px rgba(255, 159, 67, 0.08), 0 8px 10px -6px rgba(255, 159, 67, 0.08);
        }
        .priority-low {
            border-left: 6px solid #82868b;
        }

        /* Figma style Canvas Workspace */
        .canvas-workspace {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1.2px, transparent 1.2px);
            background-size: 20px 20px;
            border-radius: 12px;
            padding: 2.5rem;
            min-height: 580px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px dashed #cbd5e1;
            overflow: hidden;
            width: 100%;
        }

        /* Segmented Device Toggle Switch */
        .segmented-toggle {
            background: #f1f5f9;
            border-radius: 30px;
            padding: 4px;
            display: inline-flex;
            border: 1px solid #e2e8f0;
        }
        .segmented-toggle .btn {
            border-radius: 30px !important;
            border: none !important;
            padding: 6px 18px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #64748b !important;
            background: transparent !important;
            transition: all 0.2s ease;
            box-shadow: none !important;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .segmented-toggle .btn.active {
            background: #ffffff !important;
            color: #7367f0 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06) !important;
        }

        /* MacBook / macOS Web Browser Mockup */
        .browser-mockup {
            width: 100%;
            max-width: 800px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .browser-header {
            background: #f1f5f9;
            height: 44px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }
        .browser-buttons {
            display: flex;
            gap: 6px;
            position: absolute;
            left: 16px;
        }
        .browser-button {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            display: inline-block;
        }
        .browser-button.close { background: #ff5f56; }
        .browser-button.minimize { background: #ffbd2e; }
        .browser-button.maximize { background: #27c93f; }
        
        .browser-tabs {
            margin-left: 70px;
            display: flex;
            align-items: flex-end;
            height: 100%;
        }
        .browser-tab {
            background: #ffffff;
            padding: 6px 16px 8px;
            border-radius: 6px 6px 0 0;
            font-size: 11px;
            font-weight: 500;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-bottom: none;
            display: flex;
            align-items: center;
            gap: 6px;
            height: 30px;
        }
        .browser-address-bar {
            flex: 1;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            height: 28px;
            margin: 0 16px 0 20px;
            padding: 0 12px;
            display: flex;
            align-items: center;
            font-size: 11px;
            color: #64748b;
            max-width: 480px;
        }
        .browser-address-bar i {
            font-size: 10px;
            margin-right: 6px;
            color: #10b981;
        }
        .browser-content {
            padding: 24px;
            background: #f8fafc;
            min-height: 380px;
        }

        /* iPhone 15 Pro Chassis Mockup */
        .iphone-mockup {
            width: 310px;
            height: 610px;
            background: #090d16;
            border-radius: 44px;
            padding: 9px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3), inset 0 0 2px 2px rgba(255,255,255,0.1), 0 0 0 1px #2e3545;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 4px solid #334155;
        }
        .iphone-inner {
            width: 100%;
            height: 100%;
            background: #f8fafc;
            border-radius: 35px;
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            box-shadow: inset 0 0 4px rgba(0,0,0,0.1);
        }
        .iphone-dynamic-island {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 90px;
            height: 25px;
            background: #000000;
            border-radius: 15px;
            z-index: 99;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
            transition: width 0.3s ease;
        }
        .iphone-status-bar {
            height: 36px;
            padding: 12px 22px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            font-weight: 600;
            color: #1e293b;
            z-index: 10;
        }
        .iphone-status-bar .status-right {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .iphone-screen-content {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            margin-top: 4px;
            scrollbar-width: none;
        }
        .iphone-screen-content::-webkit-scrollbar {
            display: none;
        }
        .iphone-nav-bar {
            height: 48px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
        }
        .iphone-home-indicator {
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 110px;
            height: 4px;
            background: #000000;
            border-radius: 2px;
            z-index: 90;
        }

        /* High Fidelity Preview Card Details */
        .preview-header-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }
        .preview-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7367f0 0%, #ce9ffc 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(115, 103, 240, 0.2);
        }
        .preview-author-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        .preview-author-name {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .preview-author-name i {
            font-size: 11px;
            color: #3b82f6;
            background: #dbeafe;
            border-radius: 50%;
            padding: 2px;
        }
        .preview-meta-date {
            font-size: 11px;
            color: #64748b;
        }
        .bg-light-primary {
            background-color: #e3e1fc !important;
            color: #7367f0 !important;
        }
    </style>
@endpush

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">
                                Add Announcement
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                                    <li class="breadcrumb-item">Add</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="form form-horizontal" action="{{ route('admin.announcements.store') }}"
                    method="POST" enctype="multipart/form-data" id="announcement-form">
                    @csrf
                    <div class="row match-height">
                        <!-- Left Panel: Announcement details -->
                        <div class="col-md-7 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="feather icon-edit-3"></i> Announcement Details</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Announcement Type <span class="text-danger">*</span></span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <select name="type" id="type" class="form-control" required>
                                                                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Text Only</option>
                                                                <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Media Only</option>
                                                                <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Text & Media</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Title <span class="text-danger">*</span></span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="text" id="title" class="form-control"
                                                                name="title" value="{{ old('title') }}"
                                                                placeholder="Announcement Title" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="content-group">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Content</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <textarea id="content" class="form-control" name="content" rows="6" placeholder="Announcement Content">{{ old('content') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="media-group" style="display:none;">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Media (Image/PDF)</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="file" id="media" class="form-control"
                                                                name="media" accept="image/*,.pdf">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="description-group" style="display:none;">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Small Description</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <textarea id="description" class="form-control" name="description" rows="3"
                                                                placeholder="Small description here...">{{ old('description') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Academic Year <span class="text-danger">*</span></span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <select name="academic_year_id" id="academic_year_id" class="form-control" required>
                                                                <option value="">--Select Academic Year--</option>
                                                                @foreach($academicYears as $year)
                                                                    <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>{{ $year->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Priority <span class="text-danger">*</span></span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <select name="priority" id="priority" class="form-control" required>
                                                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low (Standard Ordering)</option>
                                                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium (Featured Ordering)</option>
                                                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High (Prominent Display & Alerts)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Show From</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="datetime-local" name="show_from" id="show_from" 
                                                                   value="{{ old('show_from') }}" class="form-control">
                                                            <small class="text-muted">Defines when the announcement becomes visible. Leave blank for immediate visibility.</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Expire Date</span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <input type="datetime-local" name="expires_at" id="expires_at" 
                                                                   value="{{ old('expires_at') }}" class="form-control">
                                                            <small class="text-muted">Defines when the announcement automatically expires and is hidden.</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group row">
                                                        <div class="col-md-3">
                                                            <span>Publishing Status <span class="text-danger">*</span></span>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <div class="custom-control custom-switch custom-control-inline">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="is_draft" name="is_draft" {{ old('is_draft', '1') == '1' ? 'checked' : '' }}>
                                                                <label class="font-weight-bold" for="is_draft" id="is_draft_label">Save as Draft</label>
                                                            </div>
                                                            <small class="d-block text-muted mt-50" id="is_draft_help">
                                                                Invisible to recipients until published.
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Recipient Targeting -->
                        <div class="col-md-5 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="feather icon-users"></i> Recipient Targeting</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <p class="text-muted font-small-3">Choose one or more recipient groups. (Bypassed if saved as Draft, required if publishing immediately).</p>
                                        
                                        <!-- Active students checkbox -->
                                        <div class="recipient-group-card">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="target_all_active_students" name="target_all_active_students" value="1"
                                                    {{ old('target_all_active_students') ? 'checked' : '' }}>
                                                <label class="custom-control-label font-weight-bold" for="target_all_active_students">
                                                    All Students Active Within Last 12 Months
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Tutors checkbox -->
                                        <div class="recipient-group-card">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="target_all_tutors" name="target_all_tutors" value="1"
                                                    {{ old('target_all_tutors') ? 'checked' : '' }}>
                                                <label class="custom-control-label font-weight-bold" for="target_all_tutors">
                                                    All Tutors
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Class selector -->
                                        <div class="recipient-group-card">
                                            <label class="font-weight-bold text-dark">Specific Classes</label>
                                            <select class="form-control select2" name="target_classes[]" multiple id="target_classes">
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ is_array(old('target_classes')) && in_array($class->id, old('target_classes')) ? 'selected' : '' }}>
                                                        {{ $class->name }} ({{ $class->academic_year }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Year group selector -->
                                        <div class="recipient-group-card">
                                            <label class="font-weight-bold text-dark">Year Groups</label>
                                            <select class="form-control select2" name="target_year_groups[]" multiple id="target_year_groups">
                                                @foreach($yearGroups as $group)
                                                    <option value="{{ $group->id }}" {{ is_array(old('target_year_groups')) && in_array($group->id, old('target_year_groups')) ? 'selected' : '' }}>
                                                        {{ $group->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- User selector -->
                                        <div class="recipient-group-card">
                                            <label class="font-weight-bold text-dark">Individual Users</label>
                                            <select class="form-control select2" name="target_users[]" multiple id="target_users">
                                                <optgroup label="Students">
                                                    @foreach($users->where('role', 'student') as $user)
                                                        <option value="{{ $user->id }}" {{ is_array(old('target_users')) && in_array($user->id, old('target_users')) ? 'selected' : '' }}>
                                                            {{ $user->name }} ({{ $user->email }})
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="Tutors">
                                                    @foreach($users->where('role', 'tutor') as $user)
                                                        <option value="{{ $user->id }}" {{ is_array(old('target_users')) && in_array($user->id, old('target_users')) ? 'selected' : '' }}>
                                                            {{ $user->name }} ({{ $user->email }})
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="Parents">
                                                    @foreach($users->where('role', 'parent') as $user)
                                                        <option value="{{ $user->id }}" {{ is_array(old('target_users')) && in_array($user->id, old('target_users')) ? 'selected' : '' }}>
                                                            {{ $user->name }} ({{ $user->email }})
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom panel actions -->
                        <div class="col-12 mt-1">
                            <div class="card">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="submit" class="btn btn-primary mr-1">
                                            <i class="feather icon-save"></i> Save Announcement
                                        </button>
                                        <button type="button" class="btn btn-outline-info mr-1" id="preview-btn">
                                            <i class="feather icon-eye"></i> Live Preview
                                        </button>
                                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-warning">Cancel</a>
                                    </div>
                                    <div>
                                        <span id="validation-error" class="text-danger font-weight-bold"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                <div class="modal-header bg-dark text-white d-flex align-items-center justify-content-between" style="padding: 1rem 1.5rem; border-bottom: 1px solid #2e3545;">
                    <h5 class="modal-title text-white mb-0 d-flex align-items-center font-weight-bold">
                        <i class="feather icon-layout mr-50 text-primary font-medium-3"></i> Live Viewport Simulator
                    </h5>
                    <div class="segmented-toggle ml-auto mr-2">
                        <button type="button" class="btn active" id="viewport-desktop">
                            <i class="feather icon-monitor"></i> Desktop
                        </button>
                        <button type="button" class="btn" id="viewport-mobile">
                            <i class="feather icon-smartphone"></i> Mobile
                        </button>
                    </div>
                    <button type="button" class="close text-white ml-0" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; outline: none; margin-left: 0;">
                        <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0" style="background-color: #0f172a; overflow: hidden;">
                    <div class="canvas-workspace">
                        
                        <!-- Viewport: Desktop Canvas -->
                        <div id="desktop-canvas" class="w-100" style="display: block;">
                            <div class="browser-mockup">
                                <div class="browser-header">
                                    <div class="browser-buttons">
                                        <span class="browser-button close"></span>
                                        <span class="browser-button minimize"></span>
                                        <span class="browser-button maximize"></span>
                                    </div>
                                    <div class="browser-tabs">
                                        <div class="browser-tab">
                                            <i class="feather icon-book-open text-primary"></i>
                                            <span>Notice Board | Aspire Portal</span>
                                        </div>
                                    </div>
                                    <div class="browser-address-bar">
                                        <i class="feather icon-lock"></i>
                                        <span>https://portal.lmsuk.com/student/announcements</span>
                                    </div>
                                </div>
                                <div class="browser-content">
                                    <div class="card preview-card" id="desktop-preview-card" style="margin-bottom:0;">
                                        <div class="card-body">
                                            <div class="preview-header-profile">
                                                <div class="preview-avatar">AD</div>
                                                <div class="preview-author-info">
                                                    <span class="preview-author-name">System Administrator <i class="feather icon-check-circle" style="font-size: 11px; color: #3b82f6;"></i></span>
                                                    <span class="preview-meta-date"><i class="feather icon-calendar"></i> Just now</span>
                                                </div>
                                                <div class="ml-auto d-flex align-items-center">
                                                    <span class="preview-badge-status bg-light-success text-success mr-75" style="font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">
                                                        <i class="feather icon-circle font-small-1 mr-25"></i> Unread
                                                    </span>
                                                    <span class="preview-badge-priority" id="preview-priority-badge">MEDIUM PRIORITY</span>
                                                </div>
                                            </div>
                                            
                                            <h3 id="preview-title" class="font-weight-bold text-dark mb-1" style="font-size: 1.5rem; text-align: left;">Title</h3>
                                            
                                            <div id="preview-desc-container" class="alert alert-info py-75 px-1 mb-1 font-italic" style="display:none; border-left: 4px solid #00cfe8; background-color: #f0fbfc; text-align: left;">
                                                <p class="mb-0" id="preview-desc" style="font-size: 0.9rem; color: #00cfe8;"></p>
                                            </div>

                                            <div id="preview-content" class="text-secondary mb-2" style="white-space: pre-wrap; font-size: 1rem; line-height: 1.6; text-align: left;">
                                                Content
                                            </div>

                                            <div id="preview-media-container" class="mt-2 text-center" style="display:none;">
                                                <div class="border rounded p-2 bg-white d-flex align-items-center justify-content-center">
                                                    <div id="preview-image-box" style="display:none;" class="w-100">
                                                        <img src="" id="preview-img" class="img-fluid rounded" style="max-height: 250px; object-fit: contain;">
                                                    </div>
                                                    <div id="preview-pdf-box" style="display:none;" class="w-100">
                                                        <div class="d-flex align-items-center p-1 border rounded bg-light-danger" style="border-color: #ffcbd1 !important;">
                                                            <i class="feather icon-file-text font-large-1 text-danger mr-75"></i>
                                                            <div style="flex: 1; text-align: left;">
                                                                <div id="preview-pdf-name" class="font-weight-bold text-dark text-truncate" style="max-width: 220px; font-size: 0.9rem;">document.pdf</div>
                                                                <small class="text-muted">PDF Document</small>
                                                            </div>
                                                            <i class="feather icon-download-cloud font-medium-3 text-secondary"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Viewport: Mobile Canvas -->
                        <div id="mobile-canvas" style="display:none;">
                            <div class="iphone-mockup">
                                <div class="iphone-dynamic-island"></div>
                                <div class="iphone-inner">
                                    <div class="iphone-status-bar">
                                        <span>9:41</span>
                                        <div class="status-right">
                                            <i class="feather icon-wifi"></i>
                                            <i class="feather icon-battery"></i>
                                        </div>
                                    </div>
                                    <div class="iphone-nav-bar">
                                        <i class="feather icon-chevron-left text-primary mr-50"></i>
                                        <span class="font-weight-bold text-dark">Notice Details</span>
                                        <i class="feather icon-more-horizontal text-secondary"></i>
                                    </div>
                                    <div class="iphone-screen-content">
                                        <div class="card preview-card" id="mobile-preview-card" style="margin-bottom:0;">
                                            <div class="card-body p-1">
                                                <div class="preview-header-profile mb-1">
                                                    <div class="preview-avatar" style="width: 32px; height: 32px; font-size: 11px;">AD</div>
                                                    <div class="preview-author-info">
                                                        <span class="preview-author-name" style="font-size: 11px;">Admin <i class="feather icon-check-circle" style="font-size:9px; padding:1px; color:#3b82f6;"></i></span>
                                                        <span class="preview-meta-date" style="font-size: 9px;">Just now</span>
                                                    </div>
                                                    <div class="ml-auto d-flex flex-column align-items-end">
                                                        <span class="preview-badge-priority mb-25" id="m-preview-priority-badge" style="font-size: 8px; padding: 2px 6px;">MEDIUM</span>
                                                    </div>
                                                </div>
                                                
                                                <h4 id="m-preview-title" class="font-weight-bold text-dark mb-75 font-medium-1" style="text-align: left;">Title</h4>
                                                
                                                <div id="m-preview-desc-container" class="alert alert-info py-50 px-50 mb-1 font-italic" style="display:none; border-left: 3px solid #00cfe8; background-color: #f0fbfc; font-size: 0.75rem; text-align: left;">
                                                    <p class="mb-0" id="m-preview-desc" style="color: #00cfe8;"></p>
                                                </div>

                                                <div id="m-preview-content" class="text-secondary mb-1" style="white-space: pre-wrap; font-size: 0.85rem; line-height: 1.4; text-align: left;">
                                                    Content
                                                </div>

                                                <div id="m-preview-media-container" class="mt-1 text-center" style="display:none;">
                                                    <div class="border rounded p-50 bg-white d-flex align-items-center justify-content-center">
                                                        <div id="m-preview-image-box" style="display:none;" class="w-100">
                                                            <img src="" id="m-preview-img" class="img-fluid rounded" style="max-height: 150px; object-fit: contain;">
                                                        </div>
                                                        <div id="m-preview-pdf-box" style="display:none;" class="w-100">
                                                            <div class="d-flex align-items-center p-50 border rounded bg-light-danger" style="border-color: #ffcbd1 !important;">
                                                                <i class="feather icon-file-text font-medium-3 text-danger mr-50"></i>
                                                                <div style="flex: 1; text-align: left; overflow: hidden;">
                                                                    <div id="m-preview-pdf-name" class="font-weight-bold text-dark text-truncate" style="max-width: 100px; font-size: 0.75rem;">document.pdf</div>
                                                                    <small class="text-muted" style="font-size: 0.65rem;">PDF Document</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="iphone-home-indicator"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer" style="background-color: #1e293b; border-top: 1px solid #2e3545;">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close Preview</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select targets...",
                allowClear: true
            });

            const typeSelect = document.getElementById('type');
            const contentGroup = document.getElementById('content-group');
            const mediaGroup = document.getElementById('media-group');
            const descriptionGroup = document.getElementById('description-group');

            function toggleFields() {
                const val = typeSelect.value;
                contentGroup.style.display = (val == 1 || val == 3) ? 'block' : 'none';
                mediaGroup.style.display = (val == 2 || val == 3) ? 'block' : 'none';
                descriptionGroup.style.display = (val == 3) ? 'block' : 'none';
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields(); // Initial call

            // Live Viewport Toggle Handlers
            document.getElementById('viewport-desktop').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('viewport-mobile').classList.remove('active');
                document.getElementById('desktop-canvas').style.display = 'block';
                document.getElementById('mobile-canvas').style.display = 'none';
            });
            document.getElementById('viewport-mobile').addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('viewport-desktop').classList.remove('active');
                document.getElementById('desktop-canvas').style.display = 'none';
                document.getElementById('mobile-canvas').style.display = 'block';
            });

            // Live Preview Modal Trigger
            document.getElementById('preview-btn').addEventListener('click', function() {
                const title = document.getElementById('title').value || 'Announcement Title';
                const type = typeSelect.value;
                const priority = document.getElementById('priority').value;
                const content = document.getElementById('content').value || '';
                const description = document.getElementById('description').value || '';
                const mediaInput = document.getElementById('media');

                // Title & Priority Badge (Desktop & Mobile)
                document.getElementById('preview-title').textContent = title;
                document.getElementById('m-preview-title').textContent = title;
                
                const badge = document.getElementById('preview-priority-badge');
                const mBadge = document.getElementById('m-preview-priority-badge');
                badge.textContent = priority.toUpperCase() + ' PRIORITY';
                mBadge.textContent = priority.toUpperCase();
                
                badge.className = 'preview-badge-priority ';
                mBadge.className = 'preview-badge-priority ';
                if(priority === 'high') {
                    badge.classList.add('bg-light-danger', 'text-danger');
                    mBadge.classList.add('bg-light-danger', 'text-danger');
                } else if(priority === 'medium') {
                    badge.classList.add('bg-light-warning', 'text-warning');
                    mBadge.classList.add('bg-light-warning', 'text-warning');
                } else {
                    badge.classList.add('bg-light-secondary', 'text-secondary');
                    mBadge.classList.add('bg-light-secondary', 'text-secondary');
                }

                // Priority Border Class
                const card = document.getElementById('desktop-preview-card');
                const mCard = document.getElementById('mobile-preview-card');
                card.className = 'card preview-card ';
                mCard.className = 'card preview-card ';
                card.classList.add('priority-' + priority);
                mCard.classList.add('priority-' + priority);

                // Content display
                const contentDiv = document.getElementById('preview-content');
                const mContentDiv = document.getElementById('m-preview-content');
                if (type == 1 || type == 3) {
                    contentDiv.textContent = content || 'Announcement content will appear here.';
                    mContentDiv.textContent = content || 'Announcement content will appear here.';
                    contentDiv.style.display = 'block';
                    mContentDiv.style.display = 'block';
                } else {
                    contentDiv.style.display = 'none';
                    mContentDiv.style.display = 'none';
                }

                // Small description display
                const descContainer = document.getElementById('preview-desc-container');
                const mDescContainer = document.getElementById('m-preview-desc-container');
                if (type == 3 && description) {
                    document.getElementById('preview-desc').textContent = description;
                    document.getElementById('m-preview-desc').textContent = description;
                    descContainer.style.display = 'block';
                    mDescContainer.style.display = 'block';
                } else {
                    descContainer.style.display = 'none';
                    mDescContainer.style.display = 'none';
                }

                // Media display
                const mediaContainer = document.getElementById('preview-media-container');
                const mMediaContainer = document.getElementById('m-preview-media-container');
                const imgBox = document.getElementById('preview-image-box');
                const mImgBox = document.getElementById('m-preview-image-box');
                const pdfBox = document.getElementById('preview-pdf-box');
                const mPdfBox = document.getElementById('m-preview-pdf-box');
                
                mediaContainer.style.display = 'none';
                mMediaContainer.style.display = 'none';
                imgBox.style.display = 'none';
                mImgBox.style.display = 'none';
                pdfBox.style.display = 'none';
                mPdfBox.style.display = 'none';

                if ((type == 2 || type == 3) && mediaInput.files && mediaInput.files[0]) {
                    const file = mediaInput.files[0];
                    mediaContainer.style.display = 'block';
                    mMediaContainer.style.display = 'block';
                    
                    if (file.type.match('image.*')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('preview-img').src = e.target.result;
                            document.getElementById('m-preview-img').src = e.target.result;
                            imgBox.style.display = 'block';
                            mImgBox.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        document.getElementById('preview-pdf-name').textContent = file.name;
                        document.getElementById('m-preview-pdf-name').textContent = file.name;
                        pdfBox.style.display = 'block';
                        mPdfBox.style.display = 'block';
                    }
                }

                // Open modal
                $('#previewModal').modal('show');
            });

            // Client-side Validation helper
            const form = document.getElementById('announcement-form');
            form.addEventListener('submit', function(e) {
                const isDraft = document.getElementById('is_draft').checked;
                const showFromVal = document.getElementById('show_from').value;
                const expiresVal = document.getElementById('expires_at').value;
                const errSpan = document.getElementById('validation-error');
                errSpan.textContent = '';

                // Date Check
                if (showFromVal && expiresVal) {
                    const showDate = new Date(showFromVal);
                    const expireDate = new Date(expiresVal);
                    if (expireDate <= showDate) {
                        e.preventDefault();
                        errSpan.textContent = 'Expiry Date must be greater than Show From Date.';
                        return;
                    }
                }

                // Target Check for published items
                if (!isDraft) {
                    const activeCheck = document.getElementById('target_all_active_students').checked;
                    const tutorsCheck = document.getElementById('target_all_tutors').checked;
                    const classesCount = $('#target_classes').val().length;
                    const groupsCount = $('#target_year_groups').val().length;
                    const usersCount = $('#target_users').val().length;

                    if (!activeCheck && !tutorsCheck && classesCount === 0 && groupsCount === 0 && usersCount === 0) {
                        e.preventDefault();
                        errSpan.textContent = 'Please select at least one recipient targeting group before publishing.';
                    }
                }
            });

            // Draft Switch dynamic label text
            const isDraftCheckbox = document.getElementById('is_draft');
            const isDraftLabel = document.getElementById('is_draft_label');
            const isDraftHelp = document.getElementById('is_draft_help');

            function updateDraftToggleText() {
                if (isDraftCheckbox.checked) {
                    isDraftLabel.innerHTML = '<span class="text-warning"><i class="feather icon-edit-2"></i> Save as Draft</span>';
                    isDraftHelp.textContent = 'Announcement will be saved as draft and hidden from all recipients.';
                } else {
                    isDraftLabel.innerHTML = '<span class="text-success"><i class="feather icon-send"></i> Publish Immediately</span>';
                    isDraftHelp.textContent = 'Announcement will be visible to targeted recipients starting from the "Show From" date.';
                }
            }

            isDraftCheckbox.addEventListener('change', updateDraftToggleText);
            updateDraftToggleText(); // Initial call
        });
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Announcements')

@push('styles')
    <style>
        .announcement-card {
            border-radius: 14px;
            box-shadow: 0 4px 18px 0 rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            background-color: #fff;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            margin-bottom: 24px;
        }
        .announcement-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0,0,0,0.12), 0 4px 16px -10px rgba(0,0,0,0.06);
            border-color: #cbd5e1;
        }
        .priority-border-high {
            border-left: 6px solid #ea5455;
        }
        .priority-border-medium {
            border-left: 6px solid #ff9f43;
        }
        .priority-border-low {
            border-left: 6px solid #82868b;
        }
        
        /* Glowing Pulsing Unread Indicator */
        .unread-indicator-pulse {
            width: 8px;
            height: 8px;
            background-color: #28c76f;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(40, 199, 111, 0.7);
            animation: indicatorPulse 1.6s infinite;
        }
        @keyframes indicatorPulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(40, 199, 111, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 6px rgba(40, 199, 111, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(40, 199, 111, 0);
            }
        }

        /* Profile Header */
        .preview-header-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
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
            font-size: 13px;
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

        /* Modern Filter Pills & Search */
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
        .search-wrapper {
            position: relative;
            max-width: 320px;
        }
        .search-wrapper i.search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }
        .search-wrapper i.clear-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
            cursor: pointer;
            display: none;
            transition: color 0.2s;
        }
        .search-wrapper i.clear-icon:hover {
            color: #64748b;
        }
        .search-input {
            padding: 8px 16px 8px 36px;
            border-radius: 30px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
            width: 100%;
            transition: all 0.2s;
        }
        .search-input:focus {
            border-color: #7367f0;
            box-shadow: 0 0 0 3px rgba(115, 103, 240, 0.15);
            outline: none;
        }

        /* Attachment UI Card */
        .attachment-pill {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border: 1px solid #ffcbd1;
            background-color: #fff5f5;
            border-radius: 8px;
            margin-top: 10px;
            transition: all 0.2s;
        }
        .attachment-pill:hover {
            background-color: #ffe8e8;
            text-decoration: none !important;
        }

        .media-preview-container img {
            max-height: 180px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s;
            cursor: zoom-in;
        }
        .media-preview-container img:hover {
            transform: scale(1.02);
        }

        /* Action Buttons */
        .read-notice-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .read-notice-btn i {
            transition: transform 0.2s;
        }
        .read-notice-btn:hover i {
            transform: translateX(4px);
        }

        /* Detail Modal Design styling */
        .browser-mockup {
            width: 100%;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
        }
        .browser-header {
            background: #f1f5f9;
            height: 40px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }
        .browser-buttons {
            display: flex;
            gap: 6px;
        }
        .browser-button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .browser-button.close { background: #ff5f56; }
        .browser-button.minimize { background: #ffbd2e; }
        .browser-button.maximize { background: #27c93f; }
        
        .browser-tabs {
            margin-left: 20px;
            display: flex;
            align-items: flex-end;
            height: 100%;
        }
        .browser-tab {
            background: #ffffff;
            padding: 4px 12px 6px;
            border-radius: 6px 6px 0 0;
            font-size: 10px;
            font-weight: 500;
            color: #475569;
            border: 1px solid #e2e8f0;
            border-bottom: none;
            display: flex;
            align-items: center;
            gap: 4px;
            height: 26px;
        }
        .browser-address-bar {
            flex: 1;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            height: 24px;
            margin: 0 16px;
            padding: 0 10px;
            display: flex;
            align-items: center;
            font-size: 10px;
            color: #64748b;
            max-width: 320px;
        }
        .browser-address-bar i {
            font-size: 9px;
            margin-right: 4px;
            color: #10b981;
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
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Announcements</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('tutor.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Announcements</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Header Filters & Search Row -->
                <div class="row align-items-center mb-2">
                    <div class="col-md-7 col-12 mb-1 mb-md-0">
                        <p class="text-muted mb-0">Stay informed with the latest updates from the academy.</p>
                    </div>
                    <div class="col-md-5 col-12 d-flex justify-content-md-end justify-content-start align-items-center">
                        <div class="search-wrapper">
                            <i class="feather icon-search search-icon"></i>
                            <input type="text" class="search-input" id="announcement-search" placeholder="Search notices...">
                            <i class="feather icon-x clear-icon" id="search-clear"></i>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12">
                        <div class="segmented-toggle">
                            <button type="button" class="btn active" data-filter="all">
                                <i class="feather icon-grid"></i> All
                            </button>
                            <button type="button" class="btn" data-filter="unread">
                                <i class="feather icon-mail"></i> Unread
                                <span class="badge badge-pill badge-light-success font-small-1 ml-25 unread-count-badge">0</span>
                            </button>
                            <button type="button" class="btn" data-filter="high">
                                <i class="feather icon-alert-triangle"></i> High Priority
                                <span class="badge badge-pill badge-light-danger font-small-1 ml-25 high-count-badge">0</span>
                            </button>
                            <button type="button" class="btn" data-filter="media">
                                <i class="feather icon-paperclip"></i> Attachments
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notices Listing Grid -->
                <div class="row">
                    <div class="col-md-9 col-12" id="announcements-container">
                        @forelse($announcements as $announcement)
                            @php
                                $isRead = in_array($announcement->id, $viewedIds);
                            @endphp
                            <div class="card announcement-card priority-border-{{ $announcement->priority }}" 
                                 id="announcement-{{ $announcement->id }}"
                                 data-priority="{{ $announcement->priority }}"
                                 data-read="{{ $isRead ? 'true' : 'false' }}"
                                 data-media="{{ $announcement->media ? 'true' : 'false' }}">
                                
                                <div class="card-body p-2">
                                    <div class="preview-header-profile">
                                        <div class="preview-avatar">AD</div>
                                        <div class="preview-author-info">
                                            <span class="preview-author-name">System Administrator <i class="feather icon-check-circle" style="font-size: 11px; color: #3b82f6;"></i></span>
                                            <span class="preview-meta-date"><i class="feather icon-calendar"></i> {{ $announcement->show_from ? $announcement->show_from->format('M d, Y H:i') : $announcement->created_at->format('M d, Y H:i') }}</span>
                                        </div>
                                        <div class="ml-auto d-flex align-items-center">
                                            @if(!$isRead)
                                                <span class="badge badge-pill badge-light-success mr-50 new-badge-pill" id="unread-pill-{{ $announcement->id }}" style="font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">
                                                    <span class="unread-indicator-pulse mr-25"></span> New
                                                </span>
                                            @endif
                                            @if($announcement->priority === 'high')
                                                <span class="badge badge-light-danger text-uppercase font-weight-bold mr-50" style="font-size: 9px; padding: 3px 6px; border-radius: 12px;">High Priority</span>
                                            @elseif($announcement->priority === 'medium')
                                                <span class="badge badge-light-warning text-uppercase font-weight-bold mr-50" style="font-size: 9px; padding: 3px 6px; border-radius: 12px;">Featured</span>
                                            @endif
                                            <span class="badge badge-pill badge-light-secondary read-badge {{ !$isRead ? 'd-none' : '' }}" id="read-badge-{{ $announcement->id }}" style="font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 20px;">
                                                <i class="feather icon-check"></i> Read
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <h4 class="announcement-title mb-50 font-weight-bold text-dark" style="text-align: left;">{{ $announcement->title }}</h4>
                                    
                                    @if($announcement->type == 3 && $announcement->description)
                                        <p class="text-secondary font-italic font-small-3 mb-1" style="border-left: 2px solid #00cfe8; padding-left: 8px; text-align: left;">{{ $announcement->description }}</p>
                                    @endif

                                    @if($announcement->type == 1 || $announcement->type == 3)
                                        <p class="card-text text-secondary font-small-3 text-announcement-content" style="white-space: pre-wrap; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-align: left;">{{ $announcement->content }}</p>
                                    @endif

                                    @if($announcement->media)
                                        <div class="media-preview-container my-1 text-left">
                                            @if(preg_match('/\.(jpeg|jpg|gif|png)/i', $announcement->media))
                                                <img src="{{ asset('storage/' . $announcement->media) }}" class="img-fluid rounded shadow-sm" alt="Media content" style="max-height: 140px;">
                                            @else
                                                <a href="{{ asset('storage/' . $announcement->media) }}" target="_blank" class="attachment-pill">
                                                    <i class="feather icon-file-text font-medium-3 text-danger mr-50"></i>
                                                    <span class="font-small-3 font-weight-bold text-dark mr-50 text-truncate" style="max-width: 250px;">{{ basename($announcement->media) }}</span>
                                                    <i class="feather icon-download-cloud font-medium-1 text-muted"></i>
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="mt-1 text-right">
                                        <button class="btn btn-outline-primary btn-sm read-notice-btn view-announcement-btn" 
                                                data-id="{{ $announcement->id }}"
                                                data-title="{{ $announcement->title }}"
                                                data-content="{{ $announcement->content }}"
                                                data-desc="{{ $announcement->description }}"
                                                data-type="{{ $announcement->type }}"
                                                data-priority="{{ $announcement->priority }}"
                                                data-media="{{ $announcement->media ? asset('storage/' . $announcement->media) : '' }}"
                                                data-media-name="{{ basename($announcement->media) }}"
                                                data-date="{{ $announcement->show_from ? $announcement->show_from->format('M d, Y H:i') : $announcement->created_at->format('M d, Y H:i') }}"
                                                data-view-url="{{ route('tutor.announcements.view', $announcement->id) }}">
                                            Read Notice <i class="feather icon-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card p-3 text-center empty-state-card" style="border-radius: 14px;">
                                <div class="card-body py-3">
                                    <i class="feather icon-bell font-large-2 text-muted mb-2"></i>
                                    <h4>No announcements found for you.</h4>
                                    <p class="text-secondary mb-0">Check back later for any new academy notices.</p>
                                </div>
                            </div>
                        @endforelse

                        <!-- Empty state placeholder for DOM filters -->
                        <div class="card p-3 text-center empty-state-card" id="empty-state-placeholder" style="display:none; border-radius: 14px;">
                            <div class="card-body py-3">
                                <i class="feather icon-bell-off font-large-3 text-muted mb-2 d-inline-block" style="opacity: 0.6;"></i>
                                <h4 class="font-weight-bold text-dark">All Caught Up!</h4>
                                <p class="text-secondary mb-0">No academy announcements match your selected filter.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- Detail Modal -->
    <div class="modal fade" id="announcementDetailModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
                <div class="modal-header bg-dark text-white d-flex align-items-center justify-content-between" style="padding: 1rem 1.5rem; border-bottom: 1px solid #2e3545;">
                    <h5 class="modal-title text-white mb-0 d-flex align-items-center font-weight-bold" id="modalLabel">
                        <i class="feather icon-book-open mr-50 text-primary font-medium-3"></i> Academy Notice Details
                    </h5>
                    <button type="button" class="close text-white ml-auto" data-dismiss="modal" aria-label="Close" style="opacity: 0.8; outline: none; margin-left: auto;">
                        <span aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0" style="background-color: #f8fafc;">
                    <div class="p-2">
                        <div class="browser-mockup">
                            <div class="browser-header">
                                <div class="browser-buttons">
                                    <span class="browser-button close"></span>
                                    <span class="browser-button minimize"></span>
                                    <span class="browser-button maximize"></span>
                                </div>
                                <div class="browser-tabs">
                                    <div class="browser-tab">
                                        <i class="feather icon-file-text text-primary"></i>
                                        <span id="detail-tab-title">Notice Details</span>
                                    </div>
                                </div>
                                <div class="browser-address-bar">
                                    <i class="feather icon-lock"></i>
                                    <span>https://portal.lmsuk.com/announcements/detail</span>
                                </div>
                            </div>
                            <div class="p-2" style="background: #ffffff;">
                                <div class="card preview-card" id="detail-priority-card" style="margin-bottom: 0; box-shadow: none; border-radius: 0; border: none;">
                                    <div class="card-body p-0">
                                        <div class="preview-header-profile">
                                            <div class="preview-avatar">AD</div>
                                            <div class="preview-author-info">
                                                <span class="preview-author-name">System Administrator <i class="feather icon-check-circle" style="font-size: 11px; color: #3b82f6;"></i></span>
                                                <span class="preview-meta-date" id="detail-date-text"><i class="feather icon-calendar"></i> Just now</span>
                                            </div>
                                            <div class="ml-auto">
                                                <span class="preview-badge-priority" id="detail-priority-badge">MEDIUM PRIORITY</span>
                                            </div>
                                        </div>

                                        <h3 id="detail-title" class="font-weight-bold text-dark mb-1" style="font-size: 1.5rem; text-align: left;">Title</h3>
                                        
                                        <div id="detail-desc-container" class="alert alert-info py-75 px-1 mb-1 font-italic" style="display:none; border-left: 4px solid #00cfe8; background-color: #f0fbfc; text-align: left;">
                                            <p class="mb-0" id="detail-desc" style="font-size: 0.9rem; color: #00cfe8;"></p>
                                        </div>

                                        <div id="detail-content" class="text-secondary mb-2" style="white-space: pre-wrap; font-size: 1rem; line-height: 1.6; text-align: left;">
                                            Content
                                        </div>

                                        <div id="detail-media-container" class="mt-2 text-center" style="display:none;">
                                            <div class="border rounded p-2 bg-white d-flex align-items-center justify-content-center">
                                                <div id="detail-image-box" style="display:none;" class="w-100">
                                                    <a href="" id="detail-img-link" target="_blank">
                                                        <img src="" id="detail-img" class="img-fluid rounded shadow-sm" style="max-height: 350px; object-fit: contain;">
                                                    </a>
                                                </div>
                                                <div id="detail-file-box" style="display:none;" class="w-100">
                                                    <div class="d-flex align-items-center p-1 border rounded bg-light-danger" style="border-color: #ffcbd1 !important;">
                                                        <i class="feather icon-file-text font-large-1 text-danger mr-75"></i>
                                                        <div style="flex: 1; text-align: left;">
                                                            <div id="detail-file-name" class="font-weight-bold text-dark text-truncate" style="max-width: 280px; font-size: 0.9rem;">document.pdf</div>
                                                            <small class="text-muted">PDF Document</small>
                                                        </div>
                                                        <a href="" id="detail-file-download" class="btn btn-sm btn-danger ml-1" target="_blank">
                                                            <i class="feather icon-download"></i> Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #1e293b; border-top: 1px solid #2e3545;">
                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close Notice</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Stats updates
            function updateBadges() {
                const unreadCount = $('.announcement-card[data-read="false"]').length;
                const highCount = $('.announcement-card[data-priority="high"]').length;
                $('.unread-count-badge').text(unreadCount);
                $('.high-count-badge').text(highCount);
            }
            updateBadges();

            // Filters & Search logic
            let currentFilter = 'all';
            let currentSearch = '';

            function applyFilters() {
                let visibleCount = 0;
                const query = currentSearch.toLowerCase().trim();

                $('.announcement-card').each(function() {
                    const card = $(this);
                    const title = (card.find('.announcement-title').text() || '').toLowerCase();
                    const desc = (card.find('.font-italic').text() || '').toLowerCase();
                    const content = (card.find('.text-announcement-content').text() || '').toLowerCase();
                    
                    const isRead = card.attr('data-read') === 'true';
                    const isHigh = card.attr('data-priority') === 'high';
                    const hasMedia = card.attr('data-media') === 'true';

                    // 1. Filter match
                    let filterMatch = false;
                    if (currentFilter === 'all') filterMatch = true;
                    else if (currentFilter === 'unread' && !isRead) filterMatch = true;
                    else if (currentFilter === 'high' && isHigh) filterMatch = true;
                    else if (currentFilter === 'media' && hasMedia) filterMatch = true;

                    // 2. Search match
                    let searchMatch = true;
                    if (query) {
                        searchMatch = title.includes(query) || desc.includes(query) || content.includes(query);
                    }

                    if (filterMatch && searchMatch) {
                        card.fadeIn(250);
                        visibleCount++;
                    } else {
                        card.fadeOut(200);
                    }
                });

                // Show/hide empty state placeholder
                if (visibleCount === 0) {
                    $('#empty-state-placeholder').fadeIn(250);
                } else {
                    $('#empty-state-placeholder').fadeOut(100);
                }
            }

            // Filter button clicks
            $('.segmented-toggle .btn').on('click', function() {
                $('.segmented-toggle .btn').removeClass('active');
                $(this).addClass('active');
                currentFilter = $(this).data('filter');
                applyFilters();
            });

            // Search inputs
            const searchInput = $('#announcement-search');
            const clearIcon = $('#search-clear');

            searchInput.on('input', function() {
                currentSearch = $(this).val();
                if (currentSearch) {
                    clearIcon.show();
                } else {
                    clearIcon.hide();
                }
                applyFilters();
            });

            clearIcon.on('click', function() {
                searchInput.val('');
                currentSearch = '';
                clearIcon.hide();
                applyFilters();
            });

            // Read Notice details modal trigger
            $('.view-announcement-btn').on('click', function() {
                const id = $(this).data('id');
                const title = $(this).data('title');
                const content = $(this).data('content');
                const desc = $(this).data('desc');
                const type = $(this).data('type');
                const priority = $(this).data('priority');
                const media = $(this).data('media');
                const mediaName = $(this).data('media-name');
                const date = $(this).data('date');
                const viewUrl = $(this).data('view-url');

                // Populate modal
                $('#detail-title').text(title);
                $('#detail-date-text').html('<i class="feather icon-calendar"></i> ' + date);
                
                // Priority badges
                const pBadge = $('#detail-priority-badge');
                pBadge.text(priority.toUpperCase() + ' PRIORITY');
                pBadge.removeClass('bg-light-danger text-danger bg-light-warning text-warning bg-light-secondary text-secondary');
                if (priority === 'high') {
                    pBadge.addClass('bg-light-danger text-danger');
                } else if (priority === 'medium') {
                    pBadge.addClass('bg-light-warning text-warning');
                } else {
                    pBadge.addClass('bg-light-secondary text-secondary');
                }

                // Priority card border
                const pCard = $('#detail-priority-card');
                pCard.removeClass('priority-high priority-medium priority-low').addClass('priority-' + priority);

                // Content
                const contentDiv = $('#detail-content');
                if (type == 1 || type == 3) {
                    contentDiv.text(content).show();
                } else {
                    contentDiv.hide();
                }

                // Description
                const descContainer = $('#detail-desc-container');
                if (type == 3 && desc) {
                    $('#detail-desc').text(desc);
                    descContainer.show();
                } else {
                    descContainer.hide();
                }

                // Media
                const mediaContainer = $('#detail-media-container');
                const imgBox = $('#detail-image-box');
                const fileBox = $('#detail-file-box');

                mediaContainer.hide();
                imgBox.hide();
                fileBox.hide();

                if (media) {
                    mediaContainer.show();
                    if (media.match(/\.(jpeg|jpg|gif|png)/i)) {
                        $('#detail-img').attr('src', media);
                        $('#detail-img-link').attr('href', media);
                        imgBox.show();
                    } else {
                        $('#detail-file-name').text(mediaName);
                        $('#detail-file-download').attr('href', media);
                        fileBox.show();
                    }
                }

                $('#announcementDetailModal').modal('show');

                // Mark as viewed via Axios
                const card = $(`#announcement-${id}`);
                const isRead = card.attr('data-read') === 'true';

                if (!isRead) {
                    axios.post(viewUrl)
                        .then(response => {
                            if (response.data.success) {
                                // Update DOM data attribute
                                card.attr('data-read', 'true');
                                // Remove unread indicator dot and pill
                                $(`#unread-pill-${id}`).remove();
                                // Show read badge
                                $(`#read-badge-${id}`).removeClass('d-none');
                                // Update stats badges
                                updateBadges();
                            }
                        })
                        .catch(error => {
                            console.error('Failed to log view:', error);
                        });
                }
            });
        });
    </script>
@endpush

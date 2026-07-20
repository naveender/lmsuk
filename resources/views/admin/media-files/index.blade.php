@extends('layouts.app')
@section('title', 'Manage Files')

@push('styles')
<style>
    .media-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .media-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.15);
    }
    .thumbnail-wrapper {
        position: relative;
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #5b247a, #1bced6);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .thumbnail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .play-btn-overlay {
        position: absolute;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: transform 0.2s;
        cursor: pointer;
    }
    .play-btn-overlay:hover {
        transform: scale(1.1);
        background: rgba(115, 103, 240, 0.9); /* Primary theme color */
    }
    .media-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .media-size-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: #fff;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 11px;
    }
    /* List View Layout Styles */
    #media_container.layout-list .media-item {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1.5rem;
    }
    #media_container.layout-list .media-card {
        display: flex;
        flex-direction: row;
        align-items: center;
        background: #fff;
        border: 1px solid #ebe9f1;
        border-radius: 8px;
        height: 120px;
    }
    #media_container.layout-list .thumbnail-wrapper {
        width: 180px;
        height: 120px;
        border-radius: 8px 0 0 8px;
        flex-shrink: 0;
        position: relative;
    }
    #media_container.layout-list .thumbnail-img {
        border-radius: 8px 0 0 8px;
    }
    #media_container.layout-list .media-card .card-body {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 15px 25px !important;
        height: 100%;
    }
    #media_container.layout-list .media-info-block {
        flex: 1;
        min-width: 0;
        margin-right: 25px;
    }
    #media_container.layout-list .media-meta-block {
        width: 280px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin-right: 20px;
    }
    #media_container.layout-list .media-actions-block {
        width: 140px;
        flex-shrink: 0;
        display: flex;
        justify-content: flex-end;
        border-top: none !important;
        padding-top: 0 !important;
    }
    #media_container.layout-list .card-text {
        height: auto !important;
        margin-bottom: 0 !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block !important;
    }
    /* Action Buttons UI/UX Improvements */
    .action-btn-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .action-btn-group .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50% !important;
        transition: all 0.2s ease;
        border: none;
    }
    .action-btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }
    .action-btn-group .btn-light-primary {
        background-color: rgba(115, 103, 240, 0.1) !important;
        color: #7367f0 !important;
    }
    .action-btn-group .btn-light-warning {
        background-color: rgba(255, 159, 67, 0.1) !important;
        color: #ff9f43 !important;
    }
    .action-btn-group .btn-light-danger {
        background-color: rgba(234, 84, 85, 0.1) !important;
        color: #ea5455 !important;
    }
    #media_table_container td .thumbnail-wrapper:hover .play-btn-overlay {
        opacity: 1 !important;
    }
</style>@endpush

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-8 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0 font-weight-bold">Manage Files</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Manage Files</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-4 col-12 d-md-block d-none">
                <div class="form-group breadcrum-right text-right d-flex align-items-center justify-content-end">
                    <div class="btn-group mr-1" role="group" aria-label="Layout toggle">
                        <button type="button" class="btn btn-outline-primary active" id="btn_layout_grid" title="Grid View">
                            <i class="feather icon-grid"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="btn_layout_table" title="Table View">
                            <i class="feather icon-list"></i>
                        </button>
                    </div>
                    <a href="{{ route('admin.media-files.create') }}" class="btn btn-primary font-weight-bold">
                        <i class="feather icon-plus mr-25"></i> Add New Video / File
                    </a>
                </div>
            </div>
        </div>

        <div class="content-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <i class="feather icon-check-circle"></i> {{ session('success') }}
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <!-- Search and Filter Panel -->
            <div class="card mb-2">
                <div class="card-content">
                    <div class="card-body">
                        <form action="{{ route('admin.media-files.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-3 col-md-12 col-12 mb-1 mb-lg-0">
                                    <fieldset class="form-group position-relative has-icon-left mb-0">
                                        <input type="text" name="search" class="form-control" placeholder="Search by video title or description..." value="{{ request('search') }}">
                                        <div class="form-control-position">
                                            <i class="feather icon-search"></i>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12 mb-1 mb-md-0">
                                    <select name="type" class="form-control">
                                        <option value="">All Video Types</option>
                                        <option value="youtube" {{ request('type') == 'youtube' ? 'selected' : '' }}>YouTube Video</option>
                                        <option value="vimeo" {{ request('type') == 'vimeo' ? 'selected' : '' }}>Vimeo Video</option>
                                        <option value="video_file" {{ request('type') == 'video_file' ? 'selected' : '' }}>Local Video File</option>
                                        <option value="video_url" {{ request('type') == 'video_url' ? 'selected' : '' }}>Video Direct URL (.mp4)</option>
                                        <option value="s3" {{ request('type') == 's3' ? 'selected' : '' }}>Amazon S3 Storage</option>
                                        <option value="wasabi" {{ request('type') == 'wasabi' ? 'selected' : '' }}>Wasabi Cloud Storage</option>
                                        <option value="google_drive" {{ request('type') == 'google_drive' ? 'selected' : '' }}>Google Drive</option>
                                        <option value="iframe" {{ request('type') == 'iframe' ? 'selected' : '' }}>Iframe Embed Code</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12 mb-1 mb-md-0">
                                    <select name="disk" class="form-control">
                                        <option value="">All Storage Disks</option>
                                        <option value="public" {{ request('disk') == 'public' ? 'selected' : '' }}>Local Disk</option>
                                        <option value="wasabi" {{ request('disk') == 'wasabi' ? 'selected' : '' }}>Wasabi Bucket</option>
                                        <option value="s3" {{ request('disk') == 's3' ? 'selected' : '' }}>AWS S3 Bucket</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12 mb-1 mb-md-0">
                                    <select name="subject_id" class="form-control">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects as $subj)
                                            <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>{{ $subj->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-6 col-12 mb-1 mb-md-0">
                                    <select name="class_id" class="form-control">
                                        <option value="">All Classes</option>
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-12 col-12">
                                    <button type="submit" class="btn btn-primary btn-block font-weight-bold px-50">
                                        <i class="feather icon-filter"></i> Apply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

             <!-- Video Grid -->
            <div class="row" id="media_grid_container">
                @forelse($files as $file)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 media-item">
                        <div class="card media-card overflow-hidden">
                            <!-- Thumbnail Wrapper -->
                            <div class="thumbnail-wrapper">
                                @php
                                    $badgeClass = 'badge-secondary';
                                    $typeLabel = ucfirst($file->type);
                                    
                                    switch($file->type) {
                                        case 'youtube':
                                            $badgeClass = 'badge-danger';
                                            $typeLabel = 'YouTube';
                                            break;
                                        case 'vimeo':
                                            $badgeClass = 'badge-info';
                                            $typeLabel = 'Vimeo';
                                            break;
                                        case 'video_file':
                                            $badgeClass = 'badge-primary';
                                            $typeLabel = 'Video File';
                                            break;
                                        case 'video_url':
                                            $badgeClass = 'badge-success';
                                            $typeLabel = 'MP4 URL';
                                            break;
                                        case 's3':
                                            $badgeClass = 'badge-warning text-dark';
                                            $typeLabel = 'Amazon S3';
                                            break;
                                        case 'wasabi':
                                            $badgeClass = 'badge-light-info';
                                            $typeLabel = 'Wasabi';
                                            break;
                                        case 'google_drive':
                                            $badgeClass = 'badge-warning';
                                            $typeLabel = 'Google Drive';
                                            break;
                                        case 'iframe':
                                            $badgeClass = 'badge-light-primary';
                                            $typeLabel = 'Iframe';
                                            break;
                                    }
                                @endphp

                                <span class="badge {{ $badgeClass }} media-badge shadow-sm">{{ $typeLabel }}</span>

                                <!-- Dynamic Thumbnail Generator -->
                                @if($file->thumbnail_url)
                                    <img src="{{ $file->thumbnail_url }}" class="thumbnail-img" alt="Thumbnail">
                                @else
                                    <i class="feather icon-video text-white" style="font-size: 4rem;"></i>
                                @endif

                                <div class="play-btn-overlay" data-toggle="modal" data-target="#previewModal" 
                                     data-title="{{ $file->title }}" 
                                     data-embed="{{ $file->embed_url }}" 
                                     data-type="{{ $file->type }}">
                                    <i class="feather icon-play" style="margin-left: 3px;"></i>
                                </div>

                                @if($file->file_size)
                                    <span class="media-size-badge">{{ round($file->file_size / (1024 * 1024), 2) }} MB</span>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-1">
                                <div class="media-info-block">
                                    <div class="d-flex align-items-center mb-50">
                                        @if($file->publication_status === 'draft')
                                            <span class="badge badge-dot badge-danger mr-50" title="Draft" style="width: 8px; height: 8px;"></span>
                                        @else
                                            <span class="badge badge-dot badge-success mr-50" title="Published" style="width: 8px; height: 8px;"></span>
                                        @endif
                                        <h5 class="card-title text-truncate font-weight-bold mb-0" style="max-width: 85%;" title="{{ $file->title }}">
                                            {{ $file->title }}
                                        </h5>
                                    </div>
                                    <p class="card-text text-muted text-truncate mb-1" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; white-space: normal; height: 38px;">
                                        {{ $file->description ?? 'No description provided.' }}
                                    </p>
                                </div>
                                
                                <div class="media-meta-block">
                                    <div class="mb-50 d-flex flex-wrap align-items-center">
                                        @if($file->duration)
                                            <span class="badge badge-pill badge-light-secondary font-small-1 mr-25 mb-25"><i class="feather icon-clock"></i> {{ $file->duration }}</span>
                                        @endif
                                        @if($file->subject)
                                            <span class="badge badge-pill badge-light-info font-small-1 mr-25 mb-25">{{ $file->subject->title }}</span>
                                        @endif
                                        @if($file->class)
                                            <span class="badge badge-pill badge-light-primary font-small-1 mb-25">{{ $file->class->name }}</span>
                                        @endif
                                    </div>
                                    <small class="text-muted"><i class="feather icon-calendar"></i> {{ $file->created_at->format('M d, Y') }}</small>
                                </div>

                                <div class="media-actions-block border-top pt-1">
                                    <div class="action-btn-group">
                                        <button type="button" class="btn btn-light-primary" data-toggle="modal" data-target="#detailsModal"
                                                data-title="{{ $file->title }}"
                                                data-desc="{{ $file->description }}"
                                                data-type="{{ $typeLabel }}"
                                                data-url="{{ $file->url }}"
                                                data-disk="{{ $file->storage_disk ?: 'N/A' }}"
                                                data-size="{{ $file->file_size ? round($file->file_size / (1024 * 1024), 2) . ' MB' : 'N/A' }}"
                                                title="View Details">
                                            <i class="feather icon-info"></i>
                                        </button>
                                        
                                        <a href="{{ route('admin.media-files.edit', $file->id) }}" class="btn btn-light-warning" title="Edit Video">
                                            <i class="feather icon-edit-2"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.media-files.destroy', $file->id) }}" method="POST" class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light-danger btn-delete" title="Delete Video">
                                                <i class="feather icon-trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="feather icon-folder-open text-muted mb-1" style="font-size: 5rem;"></i>
                        <h4 class="text-muted">No media files found</h4>
                        <p class="text-muted">Upload a video or link an external source to get started.</p>
                        <a href="{{ route('admin.media-files.create') }}" class="btn btn-primary font-weight-bold">
                            <i class="feather icon-plus"></i> Add New Video / File
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Video Table View -->
            <div class="card" id="media_table_container" style="display: none;">
                <div class="card-content">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Video</th>
                                    <th>Title & Description</th>
                                    <th>Type</th>
                                    <th>Duration</th>
                                    <th>Subject</th>
                                    <th>Class</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($files as $file)
                                    @php
                                        $badgeClass = 'badge-secondary';
                                        $typeLabel = ucfirst($file->type);
                                        switch($file->type) {
                                            case 'youtube': $badgeClass = 'badge-danger'; $typeLabel = 'YouTube'; break;
                                            case 'vimeo': $badgeClass = 'badge-info'; $typeLabel = 'Vimeo'; break;
                                            case 'video_file': $badgeClass = 'badge-primary'; $typeLabel = 'Video File'; break;
                                            case 'video_url': $badgeClass = 'badge-success'; $typeLabel = 'MP4 URL'; break;
                                            case 's3': $badgeClass = 'badge-warning text-dark'; $typeLabel = 'Amazon S3'; break;
                                            case 'wasabi': $badgeClass = 'badge-light-info'; $typeLabel = 'Wasabi'; break;
                                            case 'google_drive': $badgeClass = 'badge-warning'; $typeLabel = 'Google Drive'; break;
                                            case 'iframe': $badgeClass = 'badge-light-primary'; $typeLabel = 'Iframe'; break;
                                        }
                                    @endphp
                                    <tr>
                                        <!-- Thumbnail with play overlay -->
                                        <td>
                                            <div class="thumbnail-wrapper position-relative" style="width: 70px; height: 42px; border-radius: 4px; overflow: hidden; background: #ebe9f1; display: flex; align-items: center; justify-content: center;">
                                                @if($file->thumbnail_url)
                                                    <img src="{{ $file->thumbnail_url }}" alt="Thumb" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <div style="background: linear-gradient(135deg, #7367f0, #ce9ffc); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="feather icon-video text-white" style="font-size: 1.1rem;"></i>
                                                    </div>
                                                @endif
                                                <div class="play-btn-overlay" data-toggle="modal" data-target="#previewModal" 
                                                     data-title="{{ $file->title }}" 
                                                     data-embed="{{ $file->embed_url }}" 
                                                     data-type="{{ $file->type }}"
                                                     style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s ease; cursor: pointer; color: #fff;">
                                                    <i class="feather icon-play" style="font-size: 0.9rem; margin-left: 1px;"></i>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- Title & Desc -->
                                        <td>
                                            <div class="font-weight-bold text-dark mb-25">{{ $file->title }}</div>
                                            @if($file->file_size)
                                                <small class="text-muted mr-50"><i class="feather icon-hard-drive"></i> {{ round($file->file_size / (1024 * 1024), 2) }} MB</small>
                                            @endif
                                            <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $file->description ?? 'No description provided' }}</small>
                                        </td>
                                        <!-- Type -->
                                        <td>
                                            <span class="badge {{ $badgeClass }}">{{ $typeLabel }}</span>
                                        </td>
                                        <!-- Duration -->
                                        <td>
                                            @if($file->duration)
                                                <span class="badge badge-pill badge-light-secondary"><i class="feather icon-clock"></i> {{ $file->duration }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <!-- Subject -->
                                        <td>
                                            @if($file->subject)
                                                <span class="badge badge-pill badge-light-info">{{ $file->subject->title }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <!-- Class -->
                                        <td>
                                            @if($file->class)
                                                <span class="badge badge-pill badge-light-primary">{{ $file->class->name }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <!-- Status -->
                                        <td>
                                            @if($file->publication_status === 'draft')
                                                <span class="badge badge-pill badge-light-danger">Draft</span>
                                            @else
                                                <span class="badge badge-pill badge-light-success">Published</span>
                                            @endif
                                        </td>
                                        <!-- Created At -->
                                        <td>
                                            <small class="text-muted"><i class="feather icon-calendar"></i> {{ $file->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <!-- Actions -->
                                        <td class="text-right">
                                            <div class="action-btn-group justify-content-end">
                                                <button type="button" class="btn btn-light-primary" data-toggle="modal" data-target="#detailsModal"
                                                        data-title="{{ $file->title }}"
                                                        data-desc="{{ $file->description }}"
                                                        data-type="{{ $typeLabel }}"
                                                        data-url="{{ $file->url }}"
                                                        data-disk="{{ $file->storage_disk ?: 'N/A' }}"
                                                        data-size="{{ $file->file_size ? round($file->file_size / (1024 * 1024), 2) . ' MB' : 'N/A' }}"
                                                        title="View Details">
                                                    <i class="feather icon-info"></i>
                                                </button>
                                                
                                                <a href="{{ route('admin.media-files.edit', $file->id) }}" class="btn btn-light-warning" title="Edit Video">
                                                    <i class="feather icon-edit-2"></i>
                                                </a>
                                                
                                                <form action="{{ route('admin.media-files.destroy', $file->id) }}" method="POST" class="delete-form d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-light-danger btn-delete" title="Delete Video">
                                                        <i class="feather icon-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-3">
                                            <i class="feather icon-folder-open text-muted mb-1" style="font-size: 3rem;"></i>
                                            <h5 class="text-muted">No media files found</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12 d-flex justify-content-center mt-2">
                    {{ $files->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade text-left" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="previewModalLabel">Video Preview</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="embed-responsive embed-responsive-16by9" id="videoPreviewContainer">
                    <!-- Dynamic Video Player Target -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade text-left" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <h4 class="modal-title white" id="detailsModalLabel">Media File Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th style="width: 35%;">Title</th>
                        <td id="detailTitle"></td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td id="detailType"></td>
                    </tr>
                    <tr>
                        <th>Storage Location</th>
                        <td id="detailDisk"></td>
                    </tr>
                    <tr>
                        <th>File Size</th>
                        <td id="detailSize"></td>
                    </tr>
                    <tr>
                        <th>File Link</th>
                        <td>
                            <a href="#" id="detailUrl" target="_blank" class="text-break">Click to open link</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td id="detailDesc"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Video Preview Player logic
        $('#previewModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const title = button.data('title');
            const embedUrl = button.data('embed');
            const type = button.data('type');
            
            const modal = $(this);
            modal.find('.modal-title').text(title);
            
            let playerHtml = '';
            if (type === 'iframe') {
                playerHtml = embedUrl; // Contains raw iframe code
            } else if (type === 'video_file' || type === 'video_url' || type === 's3' || type === 'wasabi') {
                playerHtml = `<video controls class="embed-responsive-item" src="${embedUrl}" autoplay></video>`;
            } else {
                playerHtml = `<iframe class="embed-responsive-item" src="${embedUrl}" allowfullscreen allow="autoplay"></iframe>`;
            }
            
            modal.find('#videoPreviewContainer').html(playerHtml);
        });
        
        $('#previewModal').on('hide.bs.modal', function() {
            $(this).find('#videoPreviewContainer').html(''); // Clear player to stop sound
        });

        // Details Modal logic
        $('#detailsModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            
            const modal = $(this);
            modal.find('#detailTitle').text(button.data('title'));
            modal.find('#detailType').text(button.data('type'));
            modal.find('#detailDisk').text(button.data('disk'));
            modal.find('#detailSize').text(button.data('size'));
            modal.find('#detailDesc').text(button.data('desc') || 'No description');
            
            const urlLink = modal.find('#detailUrl');
            urlLink.attr('href', button.data('url'));
            urlLink.text(button.data('url'));
        });

        // Safe delete verification using SweetAlert2
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the media record and its corresponding storage files!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            });
        });

        // Layout Grid/Table Toggle persistence
        const btnLayoutGrid = document.getElementById('btn_layout_grid');
        const btnLayoutTable = document.getElementById('btn_layout_table');
        const mediaGridContainer = document.getElementById('media_grid_container');
        const mediaTableContainer = document.getElementById('media_table_container');

        function setLayout(layout) {
            localStorage.setItem('media_layout', layout);
            if (layout === 'table' || layout === 'list') { // fallback list to table
                if (btnLayoutTable) btnLayoutTable.classList.add('active');
                if (btnLayoutGrid) btnLayoutGrid.classList.remove('active');
                if (mediaGridContainer) mediaGridContainer.style.display = 'none';
                if (mediaTableContainer) mediaTableContainer.style.display = 'block';
            } else {
                if (btnLayoutGrid) btnLayoutGrid.classList.add('active');
                if (btnLayoutTable) btnLayoutTable.classList.remove('active');
                if (mediaGridContainer) mediaGridContainer.style.display = 'flex';
                if (mediaTableContainer) mediaTableContainer.style.display = 'none';
            }
        }

        if (btnLayoutGrid && btnLayoutTable) {
            btnLayoutGrid.addEventListener('click', () => setLayout('grid'));
            btnLayoutTable.addEventListener('click', () => setLayout('table'));

            // Initialize layout from localStorage
            const savedLayout = localStorage.getItem('media_layout') || 'grid';
            setLayout(savedLayout);
        }
    });
</script>
@endpush

@extends('layouts.app')

@section('content')
    <!-- Include Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>

        <div class="content-wrapper">

            <!-- Page Header / Title & Breadcrumbs -->
            <div class="admin-page-header">
                <div class="admin-page-title-wrap">
                    <div class="admin-title-row">
                        <h1 class="admin-page-heading">Topics Management</h1>
                        <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <a href="{{ route('topics') }}">Topics</a>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <span>List</span>
                        </nav>
                    </div>
                    <p class="admin-page-subtitle">Manage subject topics, modular lessons, and learning objectives</p>
                </div>
                <div>
                    <a href="{{ route('add.topic') }}" class="btn btn-admin-add">
                        <i data-lucide="plus" style="width: 17px; height: 17px;"></i>
                        <span>Add New Topic</span>
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert"
                    style="border-radius: 10px; background-color: var(--theme-success-light); border: 1px solid #A8E8C2; color: #155724;">
                    <i class="feather icon-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert"
                    style="border-radius: 10px; background-color: var(--theme-danger-light); border: 1px solid #FCD6D9; color: var(--theme-danger);">
                    <i class="feather icon-alert-circle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Filters Section -->
            <div class="card theme-card" id="filterCard">
                <div class="theme-card-header" style="cursor: pointer;" onclick="toggleFilters()">
                    <h2 class="theme-card-title">
                        <i data-lucide="filter"></i>
                        <span>Filters</span>
                    </h2>
                    <button type="button" class="btn btn-link p-0" aria-label="Toggle filters"
                        style="color: #7A8499;">
                        <i data-lucide="chevron-up" id="filterToggleIcon" style="width: 18px; height: 18px;"></i>
                    </button>
                </div>
                <div class="theme-card-body" id="filterBody">
                    <form method="GET" action="{{ route('topics') }}">
                        <div class="row g-3">

                            <!-- 1. Filter By -->
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="filter_by">Filter Level</label>
                                <div class="filter-input-group">
                                    <i data-lucide="layers" class="filter-icon"></i>
                                    <select name="filter_by" id="filter_by" class="custom-select">
                                        <option value="topic" {{ request('filter_by') == 'topic' ? 'selected' : '' }}>Topic
                                        </option>
                                        <option value="subtopic" {{ request('filter_by') == 'subtopic' ? 'selected' : '' }}>
                                            Subtopic</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 2. Search Keyword -->
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="search">Search Keyword</label>
                                <div class="filter-input-group">
                                    <i data-lucide="search" class="filter-icon"></i>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        class="form-control" placeholder="Search by name...">
                                </div>
                            </div>

                            <!-- 3. Date -->
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                <label class="filter-label" for="date">Filter by Date</label>
                                <div class="filter-input-group">
                                    <i data-lucide="calendar" class="filter-icon"></i>
                                    <input type="date" name="date" id="date" value="{{ request('date') }}"
                                        class="form-control" placeholder="dd-mm-yyyy">
                                </div>
                            </div>

                        </div>

                        <!-- Filter Action Buttons -->
                        <div class="filter-actions-bar">
                            <button type="submit" class="btn btn-filter-apply">
                                <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                                <span>Apply Filters</span>
                            </button>
                            <a href="{{ route('topics') }}" class="btn btn-filter-reset">
                                <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Topics List Card -->
            <div class="card theme-card">

                <!-- Table Header Toolbar -->
                <div class="table-header-toolbar">
                    <div class="table-title-area">
                        <h2 class="table-title-text">
                            <i data-lucide="folder"
                                style="color: var(--theme-primary-bright); width: 22px; height: 22px;"></i>
                            <span>Topics List</span>
                        </h2>
                        <span class="badge-count-pill">{{ $topics->total() }} topics</span>
                    </div>


                </div>

                <!-- Table Container -->
                <div class="table-responsive">
                    <table class="table theme-table">
                        <thead>
                            <tr>
                                <th style="width: 36px; text-align: center;">
                                    <input type="checkbox" class="custom-theme-checkbox" id="selectAllCheckbox"
                                        title="Select All">
                                </th>
                                <th style="width: 40px;">#</th>
                                <th>CODE</th>
                                <th>TOPIC NAME</th>
                                <th>SUBTOPICS COUNT</th>
                                <th>CREATED DATE</th>
                                <th style="text-align: center;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topics as $index => $topic)
                                @php
                                    $subCount = $topic->subtopics ? $topic->subtopics->count() : 0;
                                @endphp
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="checkbox" class="custom-theme-checkbox row-checkbox"
                                            value="{{ $topic->id }}">
                                    </td>
                                    <td class="font-weight-bold">{{ $topics->firstItem() + $index }}</td>
                                    <td>
                                        <span class="badge"
                                            style="background: rgba(6, 48, 128, 0.08); color: var(--theme-primary); font-weight: 600; border-radius: 6px; padding: 5px 10px;">
                                            {{ $topic->code ?: 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($topic->thumbnail)
                                                <img src="{{ asset('storage/' . $topic->thumbnail) }}" alt="{{ $topic->name }}"
                                                    class="student-avatar-img mr-1" style="border-radius: 8px;">
                                            @else
                                                <div class="student-id-badge mr-1"
                                                    style="background: rgba(36, 119, 249, 0.1); color: var(--theme-primary-bright); width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                    <i data-lucide="folder" style="width: 16px; height: 16px;"></i>
                                                </div>
                                            @endif
                                            <strong class="text-dark font-weight-bold">{{ $topic->name }}</strong>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <button type="button"
                                            class="subtopic-count-btn {{ $subCount > 0 ? 'has-subtopics' : 'no-subtopics' }}"
                                            id="subtopic-trigger-{{ $topic->id }}"
                                            onclick="openSubtopicsModal({{ $topic->id }}, '{{ addslashes($topic->name) }}', '{{ addslashes($topic->code ?: '') }}')"
                                            title="Click to view & manage subtopics hierarchy">
                                            <div class="d-inline-flex align-items-center">
                                                <i data-lucide="git-branch" class="subtopic-btn-icon mr-1"
                                                    style="width: 14px; height: 14px;"></i>
                                                <span class="subtopic-count-num font-weight-bold"
                                                    id="subtopic-count-num-{{ $topic->id }}">{{ $subCount }}</span>
                                                <span
                                                    class="subtopic-count-label ml-1">{{ Str::plural('subtopic', $subCount) }}</span>
                                                <i data-lucide="chevron-right" class="subtopic-chevron ml-1"
                                                    style="width: 13px; height: 13px;"></i>
                                            </div>
                                        </button>
                                    </td>
                                    <td>
                                        <span>{{ $topic->created_at ? $topic->created_at->format('d M Y') : 'N/A' }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="action-icon-group">
                                            <!-- Edit Button -->
                                            <a href="{{ route('topics.edit', $topic->id) }}"
                                                class="btn-table-action action-edit" title="Edit Topic">
                                                <i data-lucide="pencil" style="width: 15px; height: 15px;"></i>
                                            </a>

                                            <!-- Soft Delete Button -->
                                            <button type="button" class="btn-table-action action-delete" title="Delete Topic"
                                                onclick="confirmDeleteTopic('{{ route('topics.destroy', $topic->id) }}', '{{ addslashes($topic->name) }}')">
                                                <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i data-lucide="folder"
                                            style="width: 40px; height: 40px; opacity: 0.3; margin-bottom: 8px;"></i>
                                        <p class="mb-0">No topics found matching your criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer Toolbar -->
                <div class="table-footer-bar">
                    <div class="table-summary-text">
                        Showing {{ $topics->firstItem() ?? 0 }} to {{ $topics->lastItem() ?? 0 }} of {{ $topics->total() }}
                        topics
                    </div>

                    <div>
                        {{ $topics->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Subtopics Tree Management Modal -->
    <div class="modal fade" id="subtopicsModal" tabindex="-1" role="dialog" aria-labelledby="subtopicsModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">

                <!-- Modal Header -->
                <div class="modal-header d-flex justify-content-between align-items-center py-2 px-3"
                    style="background: linear-gradient(135deg, #063080 0%, #1757C1 100%); color: #fff;">
                    <div class="d-flex align-items-center">
                        <div class="mr-2 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);">
                            <i data-lucide="git-merge" style="width: 22px; height: 22px; color: #fff;"></i>
                        </div>
                        <div>
                            <h4 class="modal-title font-weight-bold text-white mb-0" id="subtopicsModalTitle"
                                style="font-size: 17px; line-height: 1.2;">
                                Subtopics Hierarchy
                            </h4>
                            <div class="d-flex align-items-center mt-25">
                                <span class="badge text-white mr-1" id="modalTopicCodeBadge"
                                    style="background: rgba(255,255,255,0.22); font-weight: 600; font-size: 11px; border-radius: 4px; padding: 2px 7px;">
                                    TOPIC
                                </span>
                                <span class="text-white-50 font-small-2" id="modalTopicSubtitle">
                                    Manage subtopics in tree format
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm text-white font-weight-bold mr-2 d-flex align-items-center"
                            id="btnToggleAddSubtopic" onclick="toggleAddSubtopicForm()"
                            style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.35); border-radius: 8px; padding: 6px 12px; transition: all 0.2s;">
                            <i data-lucide="plus" style="width: 15px; height: 15px;" class="mr-25"></i>
                            <span id="btnToggleAddText">Add Subtopic</span>
                        </button>
                        <button type="button" class="close text-white p-0 m-0" data-dismiss="modal" aria-label="Close"
                            style="opacity: 0.85; font-size: 26px; line-height: 1; border: none; background: transparent;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-3" style="background: #F8FAFC; max-height: calc(85vh - 120px); overflow-y: auto;">

                    <!-- Add / Edit Subtopic Inline Panel -->
                    <div id="subtopicFormPanel" class="card shadow-sm border-0 mb-3"
                        style="border-radius: 12px; display: none; background: #fff; border: 1px solid #E2E8F0 !important;">
                        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center border-bottom"
                            style="background: #F1F5F9;">
                            <span class="font-weight-bold text-dark d-flex align-items-center" style="font-size: 14px;"
                                id="subtopicFormTitle">
                                <i data-lucide="plus-circle" class="mr-1 text-primary"
                                    style="width: 16px; height: 16px;"></i>
                                <span>Add New Subtopic</span>
                            </span>
                            <button type="button" class="btn btn-link btn-sm p-0" onclick="closeSubtopicForm()">
                                <i data-lucide="x" style="width: 16px; height: 16px;"></i>
                            </button>
                        </div>
                        <div class="card-body p-3">
                            <form id="subtopicActionForm" onsubmit="handleSubtopicFormSubmit(event)">
                                <input type="hidden" id="subtopicEditId" value="">

                                <div class="row g-2">
                                    <div class="col-md-7 col-12 mb-2">
                                        <label class="form-label font-weight-bold font-small-3" for="subtopicFormName">
                                            Subtopic Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i data-lucide="type"
                                                        style="width: 15px; height: 15px; color: #94A3B8;"></i></span>
                                            </div>
                                            <input type="text" id="subtopicFormName" class="form-control border-left-0"
                                                placeholder="e.g. Fractions and Decimals" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-12 mb-2">
                                        <label class="form-label font-weight-bold font-small-3" for="subtopicFormCode">
                                            Subtopic Code <span
                                                class="font-weight-normal font-small-2">(Optional)</span>
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white border-right-0"><i data-lucide="tag"
                                                        style="width: 15px; height: 15px; color: #94A3B8;"></i></span>
                                            </div>
                                            <input type="text" id="subtopicFormCode" class="form-control border-left-0"
                                                placeholder="e.g. SUB-01">
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2" id="parentSelectContainer">
                                        <label class="form-label font-weight-bold font-small-3" for="subtopicFormParentId">
                                            Nest Under (Hierarchy Level)
                                        </label>
                                        <select id="subtopicFormParentId" class="custom-select form-control">
                                            <!-- Dynamically populated -->
                                        </select>
                                        <small>Select whether this subtopic attaches directly to the main
                                            topic or nests as a sub-subtopic.</small>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end align-items-center mt-1">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary mr-2 font-weight-bold px-3"
                                        onclick="closeSubtopicForm()" style="border-radius: 8px;">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="btn btn-sm btn-primary font-weight-bold px-3 d-flex align-items-center"
                                        id="subtopicSubmitBtn"
                                        style="border-radius: 8px; background: linear-gradient(135deg, #063080 0%, #1757C1 100%); border: none;">
                                        <i data-lucide="check" style="width: 15px; height: 15px;" class="mr-1"></i>
                                        <span id="subtopicSubmitBtnText">Save Subtopic</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tree Loading State -->
                    <div id="treeLoadingState" class="text-center py-5">
                        <div class="spinner-border text-primary mb-2" role="status" style="width: 2.5rem; height: 2.5rem;">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mb-0 font-weight-bold">Loading subtopics tree...</p>
                    </div>

                    <!-- Tree Empty State -->
                    <div id="treeEmptyState" class="text-center py-5" style="display: none;">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px; border-radius: 50%; background: rgba(36,119,249,0.08); color: var(--theme-primary-bright, #2477F9);">
                            <i data-lucide="folder-plus" style="width: 30px; height: 30px;"></i>
                        </div>
                        <h5 class="font-weight-bold text-dark">No Subtopics Found</h5>
                        <p class="font-small-3 mb-3">This topic currently has no subtopics attached. Add your
                            first subtopic to build the hierarchy.</p>
                        <button type="button" class="btn btn-primary font-weight-bold px-3 py-1"
                            onclick="openAddSubtopicForm()"
                            style="border-radius: 8px; background: linear-gradient(135deg, #063080 0%, #1757C1 100%);">
                            <i data-lucide="plus" style="width: 16px; height: 16px;" class="mr-1"></i> Add First Subtopic
                        </button>
                    </div>

                    <!-- Tree Content Area -->
                    <div id="treeContentArea" style="display: none;">
                        <!-- Topic Root Node Card -->
                        <div class="topic-root-node p-3 mb-3 d-flex justify-content-between align-items-center shadow-sm"
                            style="background: #fff; border-radius: 12px; border: 2px solid #E2E8F0; border-left: 6px solid var(--theme-primary, #063080);">
                            <div class="d-flex align-items-center">
                                <div class="root-node-avatar mr-2 d-flex align-items-center justify-content-center"
                                    style="width: 44px; height: 44px; border-radius: 10px; background: rgba(6, 48, 128, 0.08); color: var(--theme-primary, #063080);">
                                    <i data-lucide="folder-tree" style="width: 24px; height: 24px;"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <h4 class="mb-0 font-weight-bold text-dark mr-2" id="rootTopicName"
                                            style="font-size: 16px;">Topic Name</h4>
                                        <span class="badge" id="rootTopicCodeBadge"
                                            style="background: rgba(6, 48, 128, 0.08); color: #063080; font-weight: 700; border-radius: 6px; font-size: 11px;">
                                            CODE
                                        </span>
                                    </div>
                                    <div class="font-small-2 mt-25">
                                        <span id="rootTopicSubject">Subject</span> &bull;
                                        <span class="font-weight-bold text-primary" id="rootSubtopicsSummary">0
                                            subtopics</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button type="button"
                                    class="btn btn-sm btn-outline-primary font-weight-bold d-flex align-items-center px-2 py-1"
                                    onclick="openAddSubtopicForm()" style="border-radius: 6px;">
                                    <i data-lucide="plus" style="width: 14px; height: 14px;" class="mr-1"></i>
                                    <span>Add Subtopic</span>
                                </button>
                            </div>
                        </div>

                        <!-- Visual Tree Hierarchy Container -->
                        <div class="subtopics-tree-wrapper pl-1 pr-1" id="subtopicsTreeHierarchy">
                            <!-- Dynamically populated tree -->
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer py-2 px-3 bg-white border-top d-flex justify-content-between align-items-center">
                    <div class="font-small-2 d-flex align-items-center">
                        <i data-lucide="info" style="width: 14px; height: 14px;" class="mr-1 text-primary"></i>
                        <span>Use <strong>+</strong>, <strong>edit</strong>, or <strong>delete</strong> buttons on any
                            subtopic node to manage the hierarchy directly.</span>
                    </div>
                    <button type="button" class="btn btn-secondary font-weight-bold px-3" data-dismiss="modal"
                        style="border-radius: 8px;">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Subtopic Trigger Pill Button */
        .subtopic-count-btn {
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 12.5px;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            border: 1px solid transparent;
        }

        .subtopic-count-btn.has-subtopics {
            background: rgba(36, 119, 249, 0.1);
            border-color: rgba(36, 119, 249, 0.25);
            color: var(--theme-primary, #063080);
        }

        .subtopic-count-btn.has-subtopics:hover {
            background: rgba(36, 119, 249, 0.18);
            border-color: var(--theme-primary-bright, #2477F9);
            color: var(--theme-primary-bright, #2477F9);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(36, 119, 249, 0.15);
        }

        .subtopic-count-btn.no-subtopics {
            background: #F8FAFC;
            border: 1px dashed #CBD5E1;
            color: #64748B;
        }

        .subtopic-count-btn.no-subtopics:hover {
            background: rgba(36, 119, 249, 0.08);
            border-color: var(--theme-primary-bright, #2477F9);
            border-style: solid;
            color: var(--theme-primary, #063080);
            transform: translateY(-1px);
        }

        .subtopic-count-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(36, 119, 249, 0.2);
        }

        /* Tree Structure & Connectors */
        .subtopics-tree-wrapper {
            position: relative;
            padding-left: 10px;
        }

        .tree-node-group {
            position: relative;
            list-style: none;
            padding-left: 26px;
            margin-bottom: 0;
        }

        .tree-node-group::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 22px;
            width: 2px;
            background: #CBD5E1;
        }

        .tree-node-item {
            position: relative;
            margin-bottom: 10px;
        }

        .tree-node-item::before {
            content: '';
            position: absolute;
            left: -18px;
            top: 24px;
            width: 18px;
            height: 2px;
            background: #CBD5E1;
        }

        .tree-node-item:last-child::after {
            content: '';
            position: absolute;
            left: -20px;
            top: 26px;
            bottom: 0;
            width: 4px;
            background: #F8FAFC;
        }

        .tree-node-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .tree-node-card:hover {
            border-color: #93C5FD;
            box-shadow: 0 4px 12px rgba(36, 119, 249, 0.09);
            transform: translateX(2px);
        }

        .tree-node-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(36, 119, 249, 0.08);
            color: #2477F9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 12px;
        }

        .tree-node-name {
            font-weight: 600;
            color: #1E293B;
            font-size: 13.5px;
            line-height: 1.3;
        }

        .tree-code-badge {
            background: rgba(6, 48, 128, 0.08);
            color: #063080;
            font-size: 11px;
            font-weight: 600;
            border-radius: 4px;
            padding: 2px 6px;
        }

        .tree-children-badge {
            background: rgba(36, 119, 249, 0.12);
            color: #2477F9;
            font-size: 10.5px;
            font-weight: 600;
            border-radius: 4px;
            padding: 2px 6px;
        }

        .tree-node-meta {
            font-size: 11px;
            color: #64748B;
            margin-top: 2px;
        }

        .tree-node-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .tree-btn-action {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: 1px solid #E2E8F0;
            background: #FFFFFF;
            color: #64748B;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            padding: 0;
            cursor: pointer;
        }

        .tree-btn-action:hover {
            background: #F1F5F9;
            color: #0F172A;
        }

        .tree-btn-action.action-add:hover {
            background: rgba(36, 119, 249, 0.1);
            color: #2477F9;
            border-color: #2477F9;
        }

        .tree-btn-action.action-edit:hover {
            background: rgba(243, 165, 33, 0.12);
            color: #D97706;
            border-color: #F59E0B;
        }

        .tree-btn-action.action-delete:hover {
            background: rgba(250, 40, 51, 0.1);
            color: #FA2833;
            border-color: #FA2833;
        }
    </style>

    <script>
        let activeTopicId = null;
        let activeTopicData = null;

        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const selectAll = document.getElementById('selectAllCheckbox');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
                });
            }
        });

        function escapeHtml(text) {
            if (!text) return '';
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // Open Subtopics Modal & load tree
        function openSubtopicsModal(topicId, topicName, topicCode) {
            activeTopicId = topicId;
            closeSubtopicForm();

            // Update modal title elements initially
            document.getElementById('subtopicsModalTitle').innerText = 'Subtopics: ' + topicName;
            document.getElementById('modalTopicCodeBadge').innerText = topicCode ? topicCode : 'TOPIC #' + topicId;
            document.getElementById('modalTopicSubtitle').innerText = 'Loading subtopics hierarchy...';

            document.getElementById('rootTopicName').innerText = topicName;
            document.getElementById('rootTopicCodeBadge').innerText = topicCode ? topicCode : 'ID #' + topicId;
            document.getElementById('rootTopicSubject').innerText = 'Topic Details';
            document.getElementById('rootSubtopicsSummary').innerText = '...';

            // Toggle visibility
            document.getElementById('treeLoadingState').style.display = 'block';
            document.getElementById('treeEmptyState').style.display = 'none';
            document.getElementById('treeContentArea').style.display = 'none';

            if (typeof $ !== 'undefined') {
                $('#subtopicsModal').modal('show');
            } else {
                const modalEl = document.getElementById('subtopicsModal');
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
            }

            loadSubtopicsTree(topicId);
        }

        // Fetch and render the subtopics tree
        function loadSubtopicsTree(topicId) {
            const treeUrl = `{{ url('/admin/topics') }}/${topicId}/tree`;

            fetch(treeUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Could not load subtopics tree.');
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.topic) {
                        activeTopicData = data.topic;
                        renderTreeHierarchy(data.topic);
                    } else {
                        throw new Error('Invalid response structure');
                    }
                })
                .catch(err => {
                    console.error('Tree loading error:', err);
                    document.getElementById('treeLoadingState').style.display = 'none';
                    document.getElementById('subtopicsTreeHierarchy').innerHTML = `
                            <div class="alert alert-danger" style="border-radius: 8px;">
                                <i data-lucide="alert-triangle"></i> Failed to load subtopics. Please try again.
                            </div>
                        `;
                    document.getElementById('treeContentArea').style.display = 'block';
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                });
        }

        // Render tree nodes recursively
        function renderTreeHierarchy(topicData) {
            document.getElementById('treeLoadingState').style.display = 'none';

            // Update header & root stats
            const totalCount = topicData.subtopics_count || 0;
            document.getElementById('rootTopicName').innerText = topicData.name;
            document.getElementById('rootTopicCodeBadge').innerText = topicData.code || ('ID #' + topicData.id);
            document.getElementById('rootTopicSubject').innerText = topicData.subject ? topicData.subject : 'Main Subject';
            document.getElementById('rootSubtopicsSummary').innerText = `${totalCount} ${totalCount === 1 ? 'subtopic' : 'subtopics'}`;
            document.getElementById('modalTopicSubtitle').innerText = `${totalCount} ${totalCount === 1 ? 'subtopic' : 'subtopics'} in tree`;

            // Update table row badge live
            updateSubtopicCountBadge(topicData.id, totalCount);

            const subtopics = topicData.subtopics || [];
            populateParentSelect(subtopics, topicData.id, topicData.name);

            if (subtopics.length === 0) {
                document.getElementById('treeEmptyState').style.display = 'block';
                document.getElementById('treeContentArea').style.display = 'none';
            } else {
                document.getElementById('treeEmptyState').style.display = 'none';
                document.getElementById('treeContentArea').style.display = 'block';

                let html = '<ul class="tree-node-group">';
                subtopics.forEach(node => {
                    html += generateTreeNodeHtml(node);
                });
                html += '</ul>';

                document.getElementById('subtopicsTreeHierarchy').innerHTML = html;
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Recursive HTML generator for tree nodes
        function generateTreeNodeHtml(node) {
            const hasChildren = node.children && node.children.length > 0;
            const childCount = hasChildren ? node.children.length : 0;
            const safeName = escapeHtml(node.name);
            const safeCode = escapeHtml(node.code || '');
            const jsEscapedName = safeName.replace(/'/g, "\\'");
            const jsEscapedCode = safeCode.replace(/'/g, "\\'");

            let html = `
                    <li class="tree-node-item" id="tree-node-${node.id}">
                        <div class="tree-node-card">
                            <div class="d-flex align-items-center flex-grow-1 mr-2" style="min-width: 0;">
                                <div class="tree-node-icon">
                                    <i data-lucide="${hasChildren ? 'folder-git-2' : 'file-text'}" style="width: 17px; height: 17px;"></i>
                                </div>
                                <div class="tree-node-info text-truncate">
                                    <div class="d-flex align-items-center flex-wrap">
                                        <span class="tree-node-name mr-2">${safeName}</span>
                                        ${node.code ? `<span class="badge tree-code-badge mr-1">${safeCode}</span>` : ''}
                                        ${hasChildren ? `<span class="badge tree-children-badge">${childCount} ${childCount === 1 ? 'child' : 'children'}</span>` : ''}
                                    </div>
                                    <div class="tree-node-meta">
                                        <span>Created: ${escapeHtml(node.created_at || 'N/A')}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="tree-node-actions">

                                <button type="button" class="tree-btn-action action-edit" title="Edit subtopic" onclick="editSubtopic(${node.id}, '${jsEscapedName}', '${jsEscapedCode}', ${node.parent || 'null'})">
                                    <i data-lucide="pencil" style="width: 13px; height: 13px;"></i>
                                </button>
                                <button type="button" class="tree-btn-action action-delete" title="Delete subtopic" onclick="deleteSubtopic(${node.id}, '${jsEscapedName}')">
                                    <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
                                </button>
                            </div>
                        </div>
                    `;

            if (hasChildren) {
                html += '<ul class="tree-node-group">';
                node.children.forEach(child => {
                    html += generateTreeNodeHtml(child);
                });
                html += '</ul>';
            }

            html += '</li>';
            return html;
        }

        // Populate Parent Dropdown
        function populateParentSelect(subtopics, rootId, rootName) {
            const select = document.getElementById('subtopicFormParentId');
            if (!select) return;

            let options = `<option value="${rootId}">${escapeHtml(rootName)} (Main Topic Root)</option>`;

            function appendOptions(nodes, depth = 1) {
                nodes.forEach(node => {
                    const prefix = '&nbsp;&nbsp;&nbsp;&nbsp;'.repeat(depth) + '└── ';
                    options += `<option value="${node.id}">${prefix}${escapeHtml(node.name)}</option>`;
                    if (node.children && node.children.length > 0) {
                        appendOptions(node.children, depth + 1);
                    }
                });
            }

            appendOptions(subtopics);
            select.innerHTML = options;
        }

        // Toggle Add/Edit Panel
        function toggleAddSubtopicForm() {
            const panel = document.getElementById('subtopicFormPanel');
            if (panel.style.display === 'none' || !panel.style.display) {
                openAddSubtopicForm();
            } else {
                closeSubtopicForm();
            }
        }

        function openAddSubtopicForm(parentId = null, parentName = '') {
            const panel = document.getElementById('subtopicFormPanel');
            document.getElementById('subtopicEditId').value = '';
            document.getElementById('subtopicFormName').value = '';
            document.getElementById('subtopicFormCode').value = '';

            const parentSelect = document.getElementById('subtopicFormParentId');
            if (parentSelect) {
                parentSelect.value = parentId ? parentId : activeTopicId;
            }

            document.getElementById('subtopicFormTitle').innerHTML = `
                        <i data-lucide="plus-circle" class="mr-1 text-primary" style="width: 16px; height: 16px;"></i>
                        <span>${parentId ? `Add Child Subtopic under "${escapeHtml(parentName)}"` : 'Add New Subtopic'}</span>
                    `;
            document.getElementById('subtopicSubmitBtnText').innerText = 'Add Subtopic';
            document.getElementById('btnToggleAddText').innerText = 'Close Form';

            panel.style.display = 'block';
            if (typeof lucide !== 'undefined') lucide.createIcons();
            document.getElementById('subtopicFormName').focus();
        }

        function editSubtopic(subtopicId, name, code, parentId) {
            const panel = document.getElementById('subtopicFormPanel');
            document.getElementById('subtopicEditId').value = subtopicId;
            document.getElementById('subtopicFormName').value = name;
            document.getElementById('subtopicFormCode').value = code || '';

            const parentSelect = document.getElementById('subtopicFormParentId');
            if (parentSelect) {
                parentSelect.value = parentId ? parentId : activeTopicId;
            }

            document.getElementById('subtopicFormTitle').innerHTML = `
                        <i data-lucide="pencil" class="mr-1 text-warning" style="width: 16px; height: 16px;"></i>
                        <span>Edit Subtopic: ${escapeHtml(name)}</span>
                    `;
            document.getElementById('subtopicSubmitBtnText').innerText = 'Update Subtopic';
            document.getElementById('btnToggleAddText').innerText = 'Close Form';

            panel.style.display = 'block';
            if (typeof lucide !== 'undefined') lucide.createIcons();
            document.getElementById('subtopicFormName').focus();
        }

        function closeSubtopicForm() {
            const panel = document.getElementById('subtopicFormPanel');
            if (panel) panel.style.display = 'none';
            const toggleText = document.getElementById('btnToggleAddText');
            if (toggleText) toggleText.innerText = 'Add Subtopic';
        }

        // Submit inline form (Create or Update)
        function handleSubtopicFormSubmit(e) {
            e.preventDefault();

            const editId = document.getElementById('subtopicEditId').value;
            const name = document.getElementById('subtopicFormName').value.trim();
            const code = document.getElementById('subtopicFormCode').value.trim();
            const parentId = document.getElementById('subtopicFormParentId').value;

            if (!name) {
                alert('Please enter a subtopic name.');
                return;
            }

            const isEdit = Boolean(editId);
            const url = isEdit ? `{{ url('/admin/topics') }}/${editId}` : `{{ route('topics.store') }}`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            const submitBtn = document.getElementById('subtopicSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm mr-1"></span> Saving...`;

            const payload = {
                name: name,
                topic_code: code,
                parent_id: parentId ? parentId : activeTopicId,
                subject_id: activeTopicData ? activeTopicData.subject_id : null
            };

            fetch(url, {
                method: isEdit ? 'PUT' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<i data-lucide="check" style="width: 15px; height: 15px;" class="mr-1"></i> Saved!`;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message || (isEdit ? 'Subtopic updated!' : 'Subtopic added!'),
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }

                    closeSubtopicForm();
                    loadSubtopicsTree(activeTopicId);
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<i data-lucide="check" style="width: 15px; height: 15px;" class="mr-1"></i> Save Subtopic`;
                    console.error('Save error:', err);
                    const msg = (err && err.message) ? err.message : 'Failed to save subtopic. Please check your input.';
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    } else {
                        alert(msg);
                    }
                });
        }

        // Delete subtopic with SweetAlert
        function deleteSubtopic(subtopicId, subtopicName) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Subtopic?',
                    html: `Are you sure you want to remove <strong>${escapeHtml(subtopicName)}</strong>?<br><small class="text-danger">Any nested subtopics will also be removed.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FA2833',
                    cancelButtonColor: '#7A8499',
                    confirmButtonText: '<i class="feather icon-trash-2 mr-50"></i> Yes, delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger font-weight-bold px-2 py-1',
                        cancelButton: 'btn btn-secondary font-weight-bold px-2 py-1 mr-1'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        executeSubtopicDelete(subtopicId);
                    }
                });
            } else {
                if (confirm(`Are you sure you want to delete subtopic "${subtopicName}"?`)) {
                    executeSubtopicDelete(subtopicId);
                }
            }
        }

        function executeSubtopicDelete(subtopicId) {
            const url = `{{ url('/admin/topics') }}/${subtopicId}`;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Deletion failed');
                    return response.json();
                })
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message || 'Subtopic removed.',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                    loadSubtopicsTree(activeTopicId);
                })
                .catch(err => {
                    console.error('Delete error:', err);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Could not delete subtopic.' });
                    } else {
                        alert('Could not delete subtopic.');
                    }
                });
        }

        // Update main table count badge
        function updateSubtopicCountBadge(topicId, newCount) {
            const countEl = document.getElementById(`subtopic-count-num-${topicId}`);
            const triggerBtn = document.getElementById(`subtopic-trigger-${topicId}`);
            if (countEl) {
                countEl.innerText = newCount;
            }
            if (triggerBtn) {
                const labelEl = triggerBtn.querySelector('.subtopic-count-label');
                if (labelEl) {
                    labelEl.innerText = newCount === 1 ? 'subtopic' : 'subtopics';
                }
                if (newCount > 0) {
                    triggerBtn.classList.add('has-subtopics');
                    triggerBtn.classList.remove('no-subtopics');
                } else {
                    triggerBtn.classList.remove('has-subtopics');
                    triggerBtn.classList.add('no-subtopics');
                }
            }
        }

        // Main Topic delete handler
        function confirmDeleteTopic(deleteUrl, title) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Topic?',
                    html: `Are you sure you want to delete topic <strong>${title}</strong>? All associated subtopics will also be deleted.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FA2833',
                    cancelButtonColor: '#7A8499',
                    confirmButtonText: '<i class="feather icon-trash-2 mr-50"></i> Yes, delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger font-weight-bold px-2 py-1',
                        cancelButton: 'btn btn-secondary font-weight-bold px-2 py-1 mr-1'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

                        fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                            .then(response => {
                                if (response.ok) {
                                    return response.json();
                                }
                                throw new Error('Network response was not ok');
                            })
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: data.message || 'Topic deleted successfully.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(() => {
                                executeTopicDelete(deleteUrl);
                            });
                    }
                });
            } else {
                if (confirm(`Are you sure you want to delete ${title}?`)) {
                    executeTopicDelete(deleteUrl);
                }
            }
        }

        function executeTopicDelete(url) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection
@extends('layouts.app')

@push('styles')
    <style>
        .question-options-preview {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: 8px;
        }

        .question-opt-item {
            display: flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 6px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .question-opt-item.is-correct {
            background: #ecfdf5;
            border-color: #10b981;
            color: #065f46;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            <!-- Page Header / Title & Breadcrumbs -->
            <div class="admin-page-header">
                <div class="admin-page-title-wrap">
                    <div class="admin-title-row">
                        <h1 class="admin-page-heading">Question Bank</h1>
                        <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <a href="{{ route('admin.questions.index') }}">Questions</a>
                            <span class="breadcrumb-sep">&raquo;</span>
                            <span>List</span>
                        </nav>
                    </div>
                    <p class="admin-page-subtitle">Manage, categorize, and configure assessment questions across subjects
                    </p>
                </div>
                <div class="page-actions d-flex flex-wrap align-items-center" style="gap: 10px;">
                    <a href="{{ route('admin.questions.import-form') }}" class="btn btn-admin-secondary">
                        <i data-lucide="upload" style="width: 16px; height: 16px;"></i>
                        <span>Import Questions</span>
                    </a>
                    <a href="{{ route('admin.questions.create') }}" class="btn btn-admin-add">
                        <i data-lucide="plus" style="width: 17px; height: 17px;"></i>
                        <span>Add Question</span>
                    </a>
                </div>
            </div>

            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                        <div class="alert-body d-flex align-items-center">
                            <i data-lucide="check-circle" class="mr-1" style="width: 18px; height: 18px;"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                        <div class="alert-body d-flex align-items-center">
                            <i data-lucide="alert-circle" class="mr-1" style="width: 18px; height: 18px;"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Filters Section (Matches Students Reference) -->
                <div class="card theme-card" id="filterCard">
                    <div class="theme-card-header" style="cursor: pointer;" onclick="toggleFilters()">
                        <h2 class="theme-card-title">
                            <i data-lucide="filter"></i>
                            <span>Filters</span>
                        </h2>
                        <button type="button" class="btn btn-link p-0" aria-label="Toggle filters" style="color: #7A8499;">
                            <i data-lucide="chevron-up" id="filterToggleIcon" style="width: 18px; height: 18px;"></i>
                        </button>
                    </div>
                    <div class="theme-card-body" id="filterBody">
                        <form method="GET" action="{{ route('admin.questions.index') }}">
                            <div class="row g-3">
                                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                    <label class="filter-label" for="search">Search Question</label>
                                    <div class="filter-input-group">
                                        <i data-lucide="search" class="filter-icon"></i>
                                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                                            class="form-control" placeholder="Keywords or prompt text...">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                    <label class="filter-label" for="subject_id">Subject</label>
                                    <div class="filter-input-group">
                                        <i data-lucide="book" class="filter-icon"></i>
                                        <select name="subject_id" id="subject_id" class="custom-select">
                                            <option value="">All Subjects</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-2 mb-xl-0">
                                    <label class="filter-label" for="type">Question Type</label>
                                    <div class="filter-input-group">
                                        <i data-lucide="help-circle" class="filter-icon"></i>
                                        <select name="type" id="type" class="custom-select">
                                            <option value="">All Types</option>
                                            @foreach(\App\Models\Question::TYPES as $key => $label)
                                                <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-12 mb-2 mb-xl-0">
                                    <label class="filter-label" for="difficulty">Difficulty</label>
                                    <div class="filter-input-group">
                                        <i data-lucide="award" class="filter-icon"></i>
                                        <select name="difficulty" id="difficulty" class="custom-select">
                                            <option value="">All Difficulties</option>
                                            @foreach(\App\Models\Question::DIFFICULTIES as $key => $label)
                                                <option value="{{ $key }}" {{ request('difficulty') === $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-lg-6 col-md-6 col-12 mb-2 mb-xl-0">
                                    <label class="filter-label" for="is_active">Status</label>
                                    <div class="filter-input-group">
                                        <i data-lucide="bar-chart-2" class="filter-icon"></i>
                                        <select name="is_active" id="is_active" class="custom-select">
                                            <option value="">All Statuses</option>
                                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-actions-bar">
                                <button type="submit" class="btn btn-filter-apply">
                                    <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                                    <span>Apply Filters</span>
                                </button>
                                <a href="{{ route('admin.questions.index') }}" class="btn btn-filter-reset">
                                    <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                                    <span>Reset</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Questions Table Container -->
                <div class="theme-card">
                    <!-- Toolbar with Count Badge -->
                    <div class="table-header-toolbar">
                        <div class="toolbar-left">
                            <span class="badge-total-count">
                                <i data-lucide="help-circle"></i>
                                <strong>{{ $questions->total() }}</strong> Total Questions
                            </span>
                        </div>
                        <div class="toolbar-right">
                            @if(request()->anyFilled(['search', 'subject_id', 'type', 'difficulty', 'is_active']))
                                <span class="badge badge-light-warning font-small-2">
                                    <i data-lucide="filter" style="width: 12px; height: 12px; vertical-align: middle;"></i>
                                    Filtered Results
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="theme-table table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th style="min-width: 280px;">Question Title & Curriculum</th>
                                    <th style="width: 180px;">Type</th>
                                    <th style="width: 110px;">Difficulty</th>
                                    <th style="width: 90px;" class="text-center">Marks</th>
                                    <th style="width: 110px;" class="text-center">Status</th>
                                    <th style="width: 140px;" class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($questions as $index => $question)
                                    <tr id="question-row-{{ $question->id }}">
                                        <td class="font-weight-bold">
                                            {{ $questions->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <div class="font-weight-bold text-dark font-medium-1 mb-1">
                                                {{ Str::limit($question->title, 75) }}
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center" style="gap: 6px;">
                                                @if($question->subject)
                                                    <span class="badge badge-light-primary font-small-1 font-weight-bold">
                                                        {{ $question->subject->title }}
                                                    </span>
                                                @endif
                                                @if($question->topic)
                                                    <span class="badge badge-light-secondary font-small-1">
                                                        {{ $question->topic->name }}
                                                    </span>
                                                @endif
                                                @if($question->subtopic)
                                                    <span class="badge badge-light-info font-small-1">
                                                        {{ $question->subtopic->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info font-small-2 font-weight-bold">
                                                {{ $question->type_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $diffClass = match ($question->difficulty) {
                                                    'easy' => 'badge-light-success',
                                                    'medium' => 'badge-light-warning',
                                                    'hard' => 'badge-light-danger',
                                                    default => 'badge-light-secondary'
                                                };
                                            @endphp
                                            @if($question->difficulty)
                                                <span class="badge {{ $diffClass }} font-small-2 font-weight-bold text-capitalize">
                                                    {{ $question->difficulty }}
                                                </span>
                                            @else
                                                <span class="text-muted font-small-2">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-primary font-weight-bold">
                                                {{ $question->marks }} pts
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($question->is_active)
                                                <span class="badge badge-light-success font-small-2">Active</span>
                                            @else
                                                <span class="badge badge-light-danger font-small-2">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="d-inline-flex align-items-center" style="gap: 5px;">
                                                <!-- View Modal Button -->
                                                <button type="button" class="btn btn-sm btn-icon btn-outline-info"
                                                    title="View Details" onclick="viewQuestion({{ $question->id }})"
                                                    style="border-radius: 6px; padding: 5px 8px;">
                                                    <i data-lucide="eye" style="width: 15px; height: 15px;"></i>
                                                </button>

                                                <!-- Edit Button -->
                                                <a href="{{ route('admin.questions.edit', $question->id) }}"
                                                    class="btn btn-sm btn-icon btn-outline-primary" title="Edit Question"
                                                    style="border-radius: 6px; padding: 5px 8px;">
                                                    <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                                </a>

                                                <!-- Soft-Delete Button with SweetAlert -->
                                                <button type="button" class="btn btn-sm btn-icon btn-outline-danger"
                                                    title="Delete Question"
                                                    onclick="confirmDeleteQuestion({{ $question->id }}, '{{ addslashes(Str::limit($question->title, 40)) }}')"
                                                    style="border-radius: 6px; padding: 5px 8px;">
                                                    <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                                </button>

                                                <form id="delete-question-form-{{ $question->id }}"
                                                    action="{{ route('admin.questions.destroy', $question->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="empty-state">
                                                <i data-lucide="help-circle"
                                                    style="width: 48px; height: 48px; stroke-width: 1.5;"></i>
                                                <h5 class="mt-2 font-weight-bold text-dark">No Questions Found</h5>
                                                <p class="font-small-3">No question items match your search or filter
                                                    criteria.</p>
                                                <a href="{{ route('admin.questions.create') }}"
                                                    class="btn btn-primary btn-sm mt-1" style="border-radius: 6px;">
                                                    <i data-lucide="plus"
                                                        style="width: 14px; height: 14px; vertical-align: middle;"></i> Create
                                                    New Question
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Symmetrical Table Footer -->
                    @if($questions->hasPages() || $questions->total() > 0)
                        <div class="table-footer-bar">
                            <div class="footer-entries-info">
                                Showing {{ $questions->firstItem() ?? 0 }} to {{ $questions->lastItem() ?? 0 }} of
                                {{ $questions->total() }} entries
                            </div>
                            <div class="footer-pagination-links">
                                {{ $questions->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Question View Modal -->
    <div class="modal fade" id="questionViewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #063080 0%, #1757C1 100%); color: #fff; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title text-white font-weight-bold d-flex align-items-center">
                        <i data-lucide="help-circle" class="mr-1" style="width: 20px; height: 20px;"></i>
                        Question Details
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="opacity: 0.85;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" id="questionModalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Loading question information...</p>
                    </div>
                </div>
                <div class="modal-footer bg-light"
                    style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    <a href="#" id="modalEditQuestionBtn" class="btn btn-primary font-weight-bold"
                        style="border-radius: 6px;">
                        <i data-lucide="edit-3" style="width: 14px; height: 14px; vertical-align: middle;"></i> Edit
                        Question
                    </a>
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal"
                        style="border-radius: 6px;">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        // View Question Details in Modal via AJAX
        function viewQuestion(questionId) {
            $('#questionViewModal').modal('show');
            $('#questionModalContent').html(`
                                                                                <div class="text-center py-4">
                                                                                    <div class="spinner-border text-primary" role="status">
                                                                                        <span class="sr-only">Loading...</span>
                                                                                    </div>
                                                                                    <p class="mt-2">Loading question details...</p>
                                                                                </div>
                                                                            `);
            $('#modalEditQuestionBtn').attr('href', '/admin/questions/' + questionId + '/edit');

            fetch('/admin/questions/' + questionId, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.question) {
                        const q = data.question;
                        let optionsHtml = '';
                        if (q.options && q.options.length > 0) {
                            optionsHtml = '<div class="question-options-preview mt-2">';
                            q.options.forEach((opt, idx) => {
                                const isCorrect = opt.is_correct ? '<span class="badge badge-success ml-auto font-small-1">Correct Answer</span>' : '';
                                optionsHtml += `
                                                                                                <div class="question-opt-item ${opt.is_correct ? 'is-correct' : ''}">
                                                                                                    <span class="font-weight-bold mr-2">${String.fromCharCode(65 + idx)}.</span>
                                                                                                    <span>${opt.option_text || ''}</span>
                                                                                                    ${isCorrect}
                                                                                                </div>
                                                                                            `;
                            });
                            optionsHtml += '</div>';
                        }

                        $('#questionModalContent').html(`
                                                                                        <div class="mb-3">
                                                                                            <span class="badge badge-light-primary mr-1">${data.subject_name}</span>
                                                                                            <span class="badge badge-light-secondary mr-1">${data.topic_name}</span>
                                                                                            <span class="badge badge-light-info mr-1">${data.subtopic_name}</span>
                                                                                            <span class="badge ${q.is_active ? 'badge-light-success' : 'badge-light-danger'}">${q.is_active ? 'Active' : 'Inactive'}</span>
                                                                                        </div>

                                                                                        <div class="card p-2 bg-light border-0 mb-3" style="border-radius: 8px;">
                                                                                            <h6 class="font-weight-bold text-dark mb-1">Question Title / Prompt:</h6>
                                                                                            <div class="text-dark font-medium-1">${q.title}</div>
                                                                                            ${q.description ? `<div class="mt-1 font-small-3">${q.description}</div>` : ''}
                                                                                        </div>

                                                                                        <div class="row mb-3">
                                                                                            <div class="col-md-4">
                                                                                                <small class="d-block">Type</small>
                                                                                                <strong>${data.type_label || q.type}</strong>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <small class="d-block">Difficulty</small>
                                                                                                <strong class="text-capitalize">${q.difficulty || 'N/A'}</strong>
                                                                                            </div>
                                                                                            <div class="col-md-4">
                                                                                                <small class="d-block">Marks</small>
                                                                                                <strong>${q.marks || 1} Points</strong>
                                                                                            </div>
                                                                                        </div>

                                                                                        ${optionsHtml ? `
                                                                                            <div class="mb-3">
                                                                                                <h6 class="font-weight-bold text-dark mb-1">Options:</h6>
                                                                                                ${optionsHtml}
                                                                                            </div>
                                                                                        ` : ''}

                                                                                        ${q.explanation ? `
                                                                                            <div class="alert alert-light border p-2 mb-0" style="border-radius: 8px;">
                                                                                                <h6 class="font-weight-bold text-primary mb-1"><i data-lucide="info" style="width: 14px; height: 14px; vertical-align: middle;"></i> Explanation:</h6>
                                                                                                <div class="font-small-3 text-dark">${q.explanation}</div>
                                                                                            </div>
                                                                                        ` : ''}
                                                                                    `);

                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    } else {
                        $('#questionModalContent').html('<div class="alert alert-danger">Failed to load question details.</div>');
                    }
                })
                .catch(err => {
                    $('#questionModalContent').html('<div class="alert alert-danger">Error retrieving question data.</div>');
                });
        }

        // Soft Delete Question with SweetAlert2
        function confirmDeleteQuestion(questionId, questionTitle) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Question?',
                    html: `Are you sure you want to delete <strong>"${questionTitle}"</strong>?<br><small>This will safely remove the question and its associated options.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FA2833',
                    cancelButtonColor: '#6e7d88',
                    confirmButtonText: 'Yes, Delete It',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger font-weight-bold px-3 py-1',
                        cancelButton: 'btn btn-secondary font-weight-bold px-3 py-1 mr-1'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Removing question...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                            ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            : '{{ csrf_token() }}';

                        fetch(`/admin/questions/${questionId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => { throw err; });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: data.message || 'The question has been removed.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        const row = document.getElementById(`question-row-${questionId}`);
                                        if (row) {
                                            row.style.transition = 'all 0.4s ease';
                                            row.style.opacity = '0';
                                            setTimeout(() => row.remove(), 400);
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    throw new Error(data.message || 'Deletion failed');
                                }
                            })
                            .catch(err => {
                                const fallbackForm = document.getElementById(`delete-question-form-${questionId}`);
                                if (fallbackForm) {
                                    fallbackForm.submit();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: err.message || 'Could not delete question.'
                                    });
                                }
                            });
                    }
                });
            } else {
                if (confirm(`Are you sure you want to delete question: "${questionTitle}"?`)) {
                    document.getElementById(`delete-question-form-${questionId}`).submit();
                }
            }
        }
    </script>
@endpush
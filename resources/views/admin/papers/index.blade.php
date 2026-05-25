@extends('layouts.app')
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
                                Exam & Test Papers
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Papers</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row mb-2">
                    <div class="col-9">
                        <p class="text-muted">Manage, configure, and assemble questions into tests or exams.</p>
                    </div>
                    <div class="col-3 text-right">
                        <a href="{{ route('admin.papers.create') }}" class="btn btn-primary">
                            <i class="feather icon-plus"></i> Create New Paper
                        </a>
                    </div>
                </div>

                <!-- Filter Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom py-1">
                        <h4 class="card-title font-weight-bold text-primary mb-0">Filters</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body py-2">
                            <form method="GET" action="{{ route('admin.papers.index') }}" class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="search" class="font-weight-bold font-small-3 text-dark">Search
                                            Title</label>
                                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                                            class="form-control" placeholder="Search by paper title...">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label for="type" class="font-weight-bold font-small-3 text-dark">Paper Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="test" {{ request('type') === 'test' ? 'selected' : '' }}>Test
                                            </option>
                                            <option value="exam" {{ request('type') === 'exam' ? 'selected' : '' }}>Exam
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label for="subject_id"
                                            class="font-weight-bold font-small-3 text-dark">Subject</label>
                                        <select name="subject_id" id="subject_id" class="form-control">
                                            <option value="">All Subjects</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label for="class_id" class="font-weight-bold font-small-3 text-dark">Class</label>
                                        <select name="class_id" id="class_id" class="form-control">
                                            <option value="">All Classes</option>
                                            @foreach($classes as $cls)
                                                <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex">
                                    <button type="submit" class="btn btn-primary flex-fill mr-50">
                                        <i class="feather icon-filter"></i> Apply
                                    </button>
                                    <a href="{{ route('admin.papers.index') }}"
                                        class="btn btn-secondary flex-fill text-center">
                                        Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Papers Table -->
                <section id="data-list-view" class="data-list-view-header">
                    <div class="card shadow-sm">
                        <div class="card-content">
                            <div class="card-body card-dashboard p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover-animation mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="py-1">#</th>
                                                <th class="py-1">Paper Details</th>
                                                <th class="py-1">Type</th>
                                                <th class="py-1">Subject</th>
                                                <th class="py-1">Class / Year</th>
                                                <th class="py-1">Questions</th>
                                                <th class="py-1">Total Marks</th>
                                                <th class="py-1">Time</th>
                                                <th class="py-1 text-right pr-2">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($papers as $index => $paper)
                                                <tr>
                                                    <td class="align-middle">{{ $papers->firstItem() + $index }}</td>
                                                    <td class="align-middle">
                                                        <span class="font-weight-bold text-dark d-block">
                                                            {{ $paper->title }}
                                                            @if($paper->assignments->isEmpty())
                                                                <span
                                                                    class="badge badge-warning font-small-1 py-25 px-50 ml-50 rounded-pill">Unassigned</span>
                                                            @else
                                                                @php
                                                                    $first = $paper->assignments->first();
                                                                    $typeMap = [
                                                                        'classes' => 'Classes',
                                                                        'sessions' => 'Sessions',
                                                                        'group_years' => 'Group Years',
                                                                        'students' => 'Students'
                                                                    ];
                                                                    $scope = $typeMap[$first->assign_type] ?? 'Unknown';
                                                                    $mode = $first->assign_mode === 'all' ? 'All' : 'Specific';
                                                                @endphp
                                                                <span
                                                                    class="badge badge-success font-small-1 py-25 px-50 ml-50 rounded-pill">Assigned
                                                                    ({{ $scope }}: {{ $mode }})</span>
                                                            @endif
                                                        </span>
                                                        <span class="text-muted font-small-2">
                                                            By: {{ $paper->user->name ?? 'System' }} | Acad Year:
                                                            {{ $paper->academic_year ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        @if($paper->type === 'exam')
                                                            <span class="badge badge-success badge-pill">
                                                                <i class="feather icon-award"></i> Exam
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning badge-pill text-white">
                                                                <i class="feather icon-file-text"></i> Test
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle font-weight-bold text-muted">
                                                        {{ $paper->subject->title ?? '—' }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <span
                                                            class="text-dark d-block font-weight-bold">{{ $paper->class->name ?? '—' }}</span>
                                                        <span class="text-muted font-small-2">Difficulty:
                                                            {{ ucfirst($paper->difficulty ?? 'medium') }}</span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span
                                                            class="badge badge-primary font-small-3 font-weight-bold px-75 py-25 rounded">
                                                            {{ $paper->questions_count ?? $paper->questions()->count() }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle text-center font-weight-bold text-primary">
                                                        {{ ($paper->questions_count ?? $paper->questions()->count()) * ($paper->default_marks ?: 1) }}
                                                    </td>
                                                    <td class="align-middle font-weight-bold">{{ $paper->total_time }} min</td>
                                                    <td class="align-middle text-right pr-2">
                                                        <button type="button"
                                                            class="btn btn-sm btn-flat-info mr-25 btn-assign-paper"
                                                            data-id="{{ $paper->id }}" data-title="{{ $paper->title }}"
                                                            title="Assign Paper">
                                                            <i class="feather icon-user-check font-medium-1"></i> Assign To
                                                        </button>
                                                        <a href="{{ route('admin.papers.edit', $paper->id) }}"
                                                            class="btn btn-sm btn-flat-primary mr-25" title="Edit Paper">
                                                            <i class="feather icon-edit font-medium-1"></i> Edit
                                                        </a>
                                                        <form action="{{ route('admin.papers.destroy', $paper->id) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this paper? All assembled question links will be removed.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-flat-danger"
                                                                title="Delete Paper">
                                                                <i class="feather icon-trash font-medium-1"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-3 text-muted">
                                                        <i class="feather icon-info font-large-1 d-block mb-50"></i>
                                                        No exam or test papers found matching the search criteria.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <!-- Pagination -->
                                    <div
                                        class="px-2 py-1 bg-white border-top d-flex justify-content-between align-items-center">
                                        <span class="text-muted font-small-3">
                                            Showing {{ $papers->firstItem() ?? 0 }} to {{ $papers->lastItem() ?? 0 }} of
                                            {{ $papers->total() }} papers
                                        </span>
                                        <nav aria-label="Page navigation" class="mb-0">
                                            {{ $papers->links('pagination::bootstrap-4') }}
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- END: Content-->

    <!-- Assign Paper Modal -->
    <div class="modal fade text-left" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="assignModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary white">
                    <h5 class="modal-title font-weight-bold" id="assignModalLabel">Assign Paper</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignPaperForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="paper_id" id="modalPaperId">
                    <div class="modal-body p-2">
                        <!-- Loading Spinner -->
                        <div id="assignModalLoading" class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-1 text-muted">Retrieving assignment configuration...</p>
                        </div>

                        <!-- Modal Form Content -->
                        <div id="assignModalContent" style="display: none;">
                            <div class="form-group mb-2">
                                <label for="assign_scope" class="font-weight-bold text-dark h5 mb-50">Who should have access
                                    to this paper?</label>
                                <select class="form-control" name="assign_scope" id="assign_scope" required>
                                    <option value="none">Do Not Assign (Not Visible to Anyone)</option>
                                    <optgroup label="Class Students">
                                        <option value="classes_all">To All Class Students</option>
                                        <option value="classes_specific">To Students of Specific Classes</option>
                                    </optgroup>
                                    <optgroup label="Session Students">
                                        <option value="sessions_all">To All Session Students</option>
                                        <option value="sessions_specific">To Students of Specific Sessions</option>
                                    </optgroup>
                                    <optgroup label="Group Year Students">
                                        <option value="group_years_all">To All Group Year Students</option>
                                        <option value="group_years_specific">To Students of Specific Group Years</option>
                                    </optgroup>
                                    <optgroup label="Individual Students">
                                        <option value="students_all">To All Students</option>
                                        <option value="students_specific">Only Specific Students</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Classes list -->
                            <div class="specific-list-container mt-1" id="list_classes" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-50">
                                    <label class="font-small-3 font-weight-bold text-muted mb-0">
                                        Select Classes: <span class="badge badge-pill badge-light-primary ml-25 selection-count" data-type="classes">0 selected</span>
                                    </label>
                                    <div class="selection-actions">
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0 mr-1 btn-toggle-all" data-action="select" data-target="classes">Select All</button>
                                        <button type="button" class="btn btn-sm btn-link text-muted p-0 btn-toggle-all" data-action="clear" data-target="classes">Clear All</button>
                                    </div>
                                </div>
                                <div class="input-group mb-50">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0 py-25"><i class="feather icon-search text-muted font-small-3"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm border-left-0 search-filter" data-target="classes" placeholder="Search classes...">
                                </div>
                                <div class="border rounded p-1 scrollable-checkbox-container"
                                    style="max-height: 200px; overflow-y: auto; background-color: #fafafa; border-color: #e4e6fc !important;">
                                    <div class="no-matches-found text-muted text-center py-2 font-small-3" style="display: none;">
                                        <i class="feather icon-info mr-25"></i> No matching classes found
                                    </div>
                                    @foreach($classes as $classItem)
                                        <div class="custom-control custom-checkbox my-50 checkbox-wrapper-item">
                                            <input type="checkbox" class="custom-control-input class-checkbox"
                                                name="target_ids[]" id="chk_class_{{ $classItem->id }}"
                                                value="{{ $classItem->id }}">
                                            <label class="custom-control-label text-dark font-small-3"
                                                for="chk_class_{{ $classItem->id }}">{{ $classItem->name }}
                                                <span class="text-muted">({{ $classItem->group_year }} - {{ $classItem->academic_year }})</span></label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Sessions list -->
                            <div class="specific-list-container mt-1" id="list_sessions" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-50">
                                    <label class="font-small-3 font-weight-bold text-muted mb-0">
                                        Select Academic Sessions: <span class="badge badge-pill badge-light-primary ml-25 selection-count" data-type="sessions">0 selected</span>
                                    </label>
                                    <div class="selection-actions">
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0 mr-1 btn-toggle-all" data-action="select" data-target="sessions">Select All</button>
                                        <button type="button" class="btn btn-sm btn-link text-muted p-0 btn-toggle-all" data-action="clear" data-target="sessions">Clear All</button>
                                    </div>
                                </div>
                                <div class="input-group mb-50">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0 py-25"><i class="feather icon-search text-muted font-small-3"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm border-left-0 search-filter" data-target="sessions" placeholder="Search sessions...">
                                </div>
                                <div class="border rounded p-1 scrollable-checkbox-container"
                                    style="max-height: 200px; overflow-y: auto; background-color: #fafafa; border-color: #e4e6fc !important;">
                                    <div class="no-matches-found text-muted text-center py-2 font-small-3" style="display: none;">
                                        <i class="feather icon-info mr-25"></i> No matching sessions found
                                    </div>
                                    @foreach($academicYears as $yearItem)
                                        <div class="custom-control custom-checkbox my-50 checkbox-wrapper-item">
                                            <input type="checkbox" class="custom-control-input session-checkbox"
                                                name="target_ids[]" id="chk_session_{{ $yearItem->id }}"
                                                value="{{ $yearItem->id }}">
                                            <label class="custom-control-label text-dark font-small-3"
                                                for="chk_session_{{ $yearItem->id }}">{{ $yearItem->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Group Years list -->
                            <div class="specific-list-container mt-1" id="list_group_years" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-50">
                                    <label class="font-small-3 font-weight-bold text-muted mb-0">
                                        Select Group Years: <span class="badge badge-pill badge-light-primary ml-25 selection-count" data-type="group_years">0 selected</span>
                                    </label>
                                    <div class="selection-actions">
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0 mr-1 btn-toggle-all" data-action="select" data-target="group_years">Select All</button>
                                        <button type="button" class="btn btn-sm btn-link text-muted p-0 btn-toggle-all" data-action="clear" data-target="group_years">Clear All</button>
                                    </div>
                                </div>
                                <div class="input-group mb-50">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0 py-25"><i class="feather icon-search text-muted font-small-3"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm border-left-0 search-filter" data-target="group_years" placeholder="Search group years...">
                                </div>
                                <div class="border rounded p-1 scrollable-checkbox-container"
                                    style="max-height: 200px; overflow-y: auto; background-color: #fafafa; border-color: #e4e6fc !important;">
                                    <div class="no-matches-found text-muted text-center py-2 font-small-3" style="display: none;">
                                        <i class="feather icon-info mr-25"></i> No matching group years found
                                    </div>
                                    @foreach($yearGroups as $groupItem)
                                        <div class="custom-control custom-checkbox my-50 checkbox-wrapper-item">
                                            <input type="checkbox" class="custom-control-input group-year-checkbox"
                                                name="target_values[]" id="chk_group_{{ $groupItem->id }}"
                                                value="{{ $groupItem->value }}">
                                            <label class="custom-control-label text-dark font-small-3"
                                                for="chk_group_{{ $groupItem->id }}">{{ $groupItem->title }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Students list -->
                            <div class="specific-list-container mt-1" id="list_students" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-50">
                                    <label class="font-small-3 font-weight-bold text-muted mb-0">
                                        Select Students: <span class="badge badge-pill badge-light-primary ml-25 selection-count" data-type="students">0 selected</span>
                                    </label>
                                    <div class="selection-actions">
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0 mr-1 btn-toggle-all" data-action="select" data-target="students">Select All</button>
                                        <button type="button" class="btn btn-sm btn-link text-muted p-0 btn-toggle-all" data-action="clear" data-target="students">Clear All</button>
                                    </div>
                                </div>
                                <div class="input-group mb-50">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0 py-25"><i class="feather icon-search text-muted font-small-3"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm border-left-0 search-filter" data-target="students" placeholder="Search students by name or email...">
                                </div>
                                <div class="border rounded p-1 scrollable-checkbox-container"
                                    style="max-height: 200px; overflow-y: auto; background-color: #fafafa; border-color: #e4e6fc !important;">
                                    <div class="no-matches-found text-muted text-center py-2 font-small-3" style="display: none;">
                                        <i class="feather icon-info mr-25"></i> No matching students found
                                    </div>
                                    @foreach($students as $studentItem)
                                        <div class="custom-control custom-checkbox my-50 checkbox-wrapper-item">
                                            <input type="checkbox" class="custom-control-input student-checkbox"
                                                name="target_ids[]" id="chk_student_{{ $studentItem->id }}"
                                                value="{{ $studentItem->id }}">
                                            <label class="custom-control-label text-dark font-small-3"
                                                for="chk_student_{{ $studentItem->id }}">{{ $studentItem->name }}
                                                <span class="text-muted font-small-2">({{ $studentItem->email }})</span></label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnSaveAssignment" style="display: none;">
                            <i class="feather icon-save"></i> Save Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {
                // Update selection badge and style dynamically
                function updateSelectionCount(type) {
                    var container = $('#list_' + type);
                    var checkedCount = container.find('input[type="checkbox"]:checked').length;
                    var badge = container.find('.selection-count');
                    
                    badge.text(checkedCount + ' selected');
                    if (checkedCount > 0) {
                        badge.removeClass('badge-light-primary').addClass('badge-primary');
                    } else {
                        badge.removeClass('badge-primary').addClass('badge-light-primary');
                    }
                }

                // Reset search filters and matching elements
                function resetFilters() {
                    $('.search-filter').val('');
                    $('.checkbox-wrapper-item').show();
                    $('.no-matches-found').hide();
                }

                // Show assignment modal and load configuration
                $('.btn-assign-paper').on('click', function () {
                    var paperId = $(this).data('id');
                    var paperTitle = $(this).data('title');

                    // Set modal info
                    $('#assignModalLabel').html('<i class="feather icon-user-check mr-50"></i>Assign Paper: <span class="text-warning">' + paperTitle + '</span>');
                    $('#modalPaperId').val(paperId);

                    // Safe dynamic URL generation using Laravel route templates
                    var routeUrl = "{{ route('admin.papers.assign', ':id') }}";
                    $('#assignPaperForm').attr('action', routeUrl.replace(':id', paperId));

                    // Open modal
                    $('#assignModal').modal('show');
                    resetFilters();

                    // Loading state
                    $('#assignModalLoading').show();
                    $('#assignModalContent').hide();
                    $('#btnSaveAssignment').hide();

                    // Fetch assignments using Axios
                    var getUrlTemplate = "{{ route('admin.papers.assignments', ':id') }}";
                    axios.get(getUrlTemplate.replace(':id', paperId))
                        .then(function (response) {
                            var data = response.data;

                            // Reset all form elements
                            $('.specific-list-container').hide();
                            $('.specific-list-container').find('input').prop('disabled', true);
                            $('input[type="checkbox"]').prop('checked', false);

                            // Set the correct scope selection
                            var scope = data.assign_type === 'none' ? 'none' : (data.assign_type + '_' + data.assign_mode);
                            $('#assign_scope').val(scope);

                            // Populate checkboxes if specific mode is active
                            if (data.assign_type !== 'none' && data.assign_mode === 'specific') {
                                var listId = '#list_' + data.assign_type;
                                $(listId).show();
                                $(listId).find('input').prop('disabled', false);

                                var checkboxClass = {
                                    'classes': '.class-checkbox',
                                    'sessions': '.session-checkbox',
                                    'group_years': '.group-year-checkbox',
                                    'students': '.student-checkbox'
                                }[data.assign_type];

                                data.selected_ids.forEach(function (val) {
                                    $(checkboxClass + '[value="' + val + '"]').prop('checked', true);
                                });
                            }

                            // Update all counts
                            ['classes', 'sessions', 'group_years', 'students'].forEach(function(type) {
                                updateSelectionCount(type);
                            });

                            // Switch loading state off
                            $('#assignModalLoading').hide();
                            $('#assignModalContent').fadeIn(200);
                            $('#btnSaveAssignment').show();
                        })
                        .catch(function (error) {
                            console.error(error);
                            alert('Error retrieving assignment settings. Please try again.');
                            $('#assignModal').modal('hide');
                        });
                });

                // Listen to Assign Scope dropdown change
                $('#assign_scope').on('change', function () {
                    var scope = $(this).val();

                    // Hide all lists and disable all checkbox inputs
                    $('.specific-list-container').hide();
                    $('.specific-list-container').find('input').prop('disabled', true);
                    resetFilters();

                    if (scope !== 'none' && scope.endsWith('_specific')) {
                        var type = scope.replace('_specific', '');
                        var listId = '#list_' + type;

                        $(listId).fadeIn(200);
                        $(listId).find('input').prop('disabled', false);
                    }
                });

                // Real-time live filtering / search functionality
                $('.search-filter').on('input', function () {
                    var query = $(this).val().toLowerCase().trim();
                    var targetType = $(this).data('target');
                    var container = $('#list_' + targetType);
                    var wrapperItems = container.find('.checkbox-wrapper-item');
                    var noMatchesDiv = container.find('.no-matches-found');
                    var visibleCount = 0;

                    wrapperItems.each(function () {
                        var text = $(this).text().toLowerCase();
                        if (text.indexOf(query) > -1) {
                            $(this).show();
                            visibleCount++;
                        } else {
                            $(this).hide();
                        }
                    });

                    if (visibleCount === 0) {
                        noMatchesDiv.show();
                    } else {
                        noMatchesDiv.hide();
                    }
                });

                // Select All / Clear All visible matching checkboxes
                $('.btn-toggle-all').on('click', function (e) {
                    e.preventDefault();
                    var action = $(this).data('action'); // 'select' or 'clear'
                    var targetType = $(this).data('target');
                    var container = $('#list_' + targetType);
                    
                    // Only target visible items (so filtered search isn't affected/unchecked/checked outside view)
                    var visibleCheckboxes = container.find('.checkbox-wrapper-item:visible input[type="checkbox"]');
                    
                    if (action === 'select') {
                        visibleCheckboxes.prop('checked', true);
                    } else {
                        visibleCheckboxes.prop('checked', false);
                    }
                    
                    updateSelectionCount(targetType);
                });

                // Handle individual checkbox changes to update count live
                $(document).on('change', '.specific-list-container input[type="checkbox"]', function() {
                    var container = $(this).closest('.specific-list-container');
                    var type = container.attr('id').replace('list_', '');
                    updateSelectionCount(type);
                });

                // Client-side validation on form submit
                $('#assignPaperForm').on('submit', function (e) {
                    var scope = $('#assign_scope').val();
                    if (scope !== 'none' && scope.endsWith('_specific')) {
                        var type = scope.replace('_specific', '');
                        var checkboxClass = {
                            'classes': '.class-checkbox',
                            'sessions': '.session-checkbox',
                            'group_years': '.group-year-checkbox',
                            'students': '.student-checkbox'
                        }[type];

                        if ($(checkboxClass + ':checked').length === 0) {
                            e.preventDefault();
                            alert('Please select at least one item for the specific assignment.');
                            return false;
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
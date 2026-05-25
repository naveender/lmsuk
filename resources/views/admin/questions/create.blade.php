@extends('layouts.app')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Add Question</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Questions</a></li>
                                    <li class="breadcrumb-item active">Add Question</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form class="form" action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" id="questionForm">
                    @csrf

                    {{-- Meta Information --}}
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Meta Information</h4></div>
                        <div class="card-content"><div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <select name="subject_id" id="subject_id" class="form-control">
                                            <option value="">-- Select Subject --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Topic</label>
                                        <select name="topic_id" id="topic_id" class="form-control">
                                            <option value="">-- Select Topic --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Subtopic</label>
                                        <select name="subtopic_id" id="subtopic_id" class="form-control">
                                            <option value="">-- Select Subtopic --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Difficulty</label>
                                        <select name="difficulty" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach(\App\Models\Question::DIFFICULTIES as $key => $label)
                                                <option value="{{ $key }}" {{ old('difficulty') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Marks</label>
                                        <input type="number" name="marks" class="form-control" value="{{ old('marks', 1) }}" min="1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <div class="custom-control custom-switch mt-1">
                                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></div>
                    </div>

                    {{-- Question Type Selector --}}
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Question Type <span class="text-danger">*</span></h4></div>
                        <div class="card-content"><div class="card-body">
                            <div class="row">
                                @foreach(\App\Models\Question::TYPES as $key => $label)
                                    <div class="col-md-4 col-lg-3 mb-2">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input question-type-radio" id="type_{{ $key }}" name="type" value="{{ $key }}" {{ old('type') === $key ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="type_{{ $key }}">{{ $label }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div></div>
                    </div>

                    {{-- Question Content --}}
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Question Content</h4></div>
                        <div class="card-content"><div class="card-body">
                            <div class="form-group">
                                <label>Question Text <span class="text-danger">*</span></label>
                                <textarea name="title" class="form-control" rows="3" required placeholder="Enter your question...">{{ old('title') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Description / Instructions (Optional)</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Additional instructions...">{{ old('description') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Question Image (Optional)</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Explanation (Optional)</label>
                                        <textarea name="explanation" class="form-control" rows="2" placeholder="Answer explanation...">{{ old('explanation') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div></div>
                    </div>

                    {{-- Choice Options Section (radio/dropdown/multiple) --}}
                    <div class="card type-section" id="section-choices" style="display:none;">
                        <div class="card-header">
                            <h4 class="card-title">Answer Options</h4>
                        </div>
                        <div class="card-content"><div class="card-body">
                            <p class="text-muted mb-2" id="choices-help">Add options and mark the correct one(s).</p>
                            <div id="options-container">
                                <div class="option-row d-flex align-items-center mb-1">
                                    <input type="text" name="options[]" class="form-control mr-1" placeholder="Option 1" value="{{ old('options.0') }}">
                                    <div class="custom-control custom-checkbox mr-1" id="correct-control-0">
                                        <input type="checkbox" class="custom-control-input correct-checkbox" id="correct_0" name="correct[]" value="0">
                                        <label class="custom-control-label" for="correct_0">Correct</label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger remove-option" title="Remove"><i class="feather icon-x"></i></button>
                                </div>
                                <div class="option-row d-flex align-items-center mb-1">
                                    <input type="text" name="options[]" class="form-control mr-1" placeholder="Option 2" value="{{ old('options.1') }}">
                                    <div class="custom-control custom-checkbox mr-1" id="correct-control-1">
                                        <input type="checkbox" class="custom-control-input correct-checkbox" id="correct_1" name="correct[]" value="1">
                                        <label class="custom-control-label" for="correct_1">Correct</label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger remove-option" title="Remove"><i class="feather icon-x"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-option">
                                <i class="feather icon-plus"></i> Add Option
                            </button>
                        </div></div>
                    </div>

                    {{-- Picture Choice Section --}}
                    <div class="card type-section" id="section-picture" style="display:none;">
                        <div class="card-header"><h4 class="card-title">Picture Options</h4></div>
                        <div class="card-content"><div class="card-body">
                            <p class="text-muted mb-2">Upload images as options and mark the correct one(s).</p>
                            <div id="picture-options-container">
                                <div class="picture-option-row row mb-2 align-items-center">
                                    <div class="col-md-4">
                                        <input type="file" name="option_images[]" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="option_texts[]" class="form-control" placeholder="Caption (optional)">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input picture-correct" id="pic_correct_0" name="correct[]" value="0">
                                            <label class="custom-control-label" for="pic_correct_0">Correct</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-danger remove-picture-option"><i class="feather icon-x"></i></button>
                                    </div>
                                </div>
                                <div class="picture-option-row row mb-2 align-items-center">
                                    <div class="col-md-4">
                                        <input type="file" name="option_images[]" class="form-control" accept="image/*">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="option_texts[]" class="form-control" placeholder="Caption (optional)">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input picture-correct" id="pic_correct_1" name="correct[]" value="1">
                                            <label class="custom-control-label" for="pic_correct_1">Correct</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-danger remove-picture-option"><i class="feather icon-x"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-picture-option">
                                <i class="feather icon-plus"></i> Add Picture Option
                            </button>
                        </div></div>
                    </div>

                    {{-- Fill in the Blanks --}}
                    <div class="card type-section" id="section-blanks" style="display:none;">
                        <div class="card-header"><h4 class="card-title">Blank Answers</h4></div>
                        <div class="card-content"><div class="card-body">
                            <p class="text-muted mb-2">Use <code>___</code> in the question text to indicate blanks. Add the correct answers below in order.</p>
                            <div id="blanks-container">
                                <div class="blank-row d-flex align-items-center mb-1">
                                    <span class="mr-1 font-weight-bold">Blank 1:</span>
                                    <input type="text" name="blank_answers[]" class="form-control mr-1" placeholder="Correct answer for blank 1">
                                    <button type="button" class="btn btn-sm btn-danger remove-blank"><i class="feather icon-x"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-blank">
                                <i class="feather icon-plus"></i> Add Blank
                            </button>
                        </div></div>
                    </div>

                    {{-- Matching Section --}}
                    <div class="card type-section" id="section-matching" style="display:none;">
                        <div class="card-header"><h4 class="card-title">Matching Pairs</h4></div>
                        <div class="card-content"><div class="card-body">
                            <p class="text-muted mb-2">Define left-right matching pairs.</p>
                            <div id="matching-container">
                                <div class="matching-row row mb-1">
                                    <div class="col-md-5">
                                        <input type="text" name="match_left[]" class="form-control" placeholder="Left item 1">
                                    </div>
                                    <div class="col-md-1 text-center pt-1"><i class="feather icon-arrow-right"></i></div>
                                    <div class="col-md-5">
                                        <input type="text" name="match_right[]" class="form-control" placeholder="Right item 1">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-sm btn-danger remove-match"><i class="feather icon-x"></i></button>
                                    </div>
                                </div>
                                <div class="matching-row row mb-1">
                                    <div class="col-md-5">
                                        <input type="text" name="match_left[]" class="form-control" placeholder="Left item 2">
                                    </div>
                                    <div class="col-md-1 text-center pt-1"><i class="feather icon-arrow-right"></i></div>
                                    <div class="col-md-5">
                                        <input type="text" name="match_right[]" class="form-control" placeholder="Right item 2">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-sm btn-danger remove-match"><i class="feather icon-x"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-match">
                                <i class="feather icon-plus"></i> Add Pair
                            </button>
                        </div></div>
                    </div>

                    {{-- Free Text Config --}}
                    <div class="card type-section" id="section-freetext" style="display:none;">
                        <div class="card-header"><h4 class="card-title">Free Text Settings</h4></div>
                        <div class="card-content"><div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-4"><label>Word Limit (Optional)</label></div>
                                <div class="col-md-4">
                                    <input type="number" name="word_limit" class="form-control" placeholder="e.g. 500" value="{{ old('word_limit') }}" min="1">
                                </div>
                            </div>
                        </div></div>
                    </div>

                    {{-- File Upload Config --}}
                    <div class="card type-section" id="section-fileupload" style="display:none;">
                        <div class="card-header"><h4 class="card-title">File Upload Settings</h4></div>
                        <div class="card-content"><div class="card-body">
                            <div class="form-group">
                                <label>Allowed File Types</label>
                                <div class="row">
                                    @foreach(['pdf' => 'PDF', 'doc' => 'DOC/DOCX', 'xls' => 'XLS/XLSX', 'ppt' => 'PPT/PPTX', 'jpg' => 'Images', 'zip' => 'ZIP/RAR'] as $ext => $lbl)
                                        <div class="col-md-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="ftype_{{ $ext }}" name="allowed_file_types[]" value="{{ $ext }}">
                                                <label class="custom-control-label" for="ftype_{{ $ext }}">{{ $lbl }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4"><label>Max File Size (MB)</label></div>
                                <div class="col-md-4">
                                    <input type="number" name="max_file_size" class="form-control" value="{{ old('max_file_size', 5) }}" min="1" max="100">
                                </div>
                            </div>
                        </div></div>
                    </div>

                    {{-- Submit --}}
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="save_and_add_another" id="save_and_add_another" value="0">
                            <button type="submit" class="btn btn-primary mr-1"><i class="feather icon-save"></i> Save Question</button>
                            <button type="submit" class="btn btn-success mr-1" id="btn-save-add-another"><i class="feather icon-plus"></i> Save and Add Another</button>
                            <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-warning">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('.question-type-radio');
    const typeSections = document.querySelectorAll('.type-section');
    const sectionMap = {
        'single_choice_radio': 'section-choices',
        'single_choice_dropdown': 'section-choices',
        'multiple_choice': 'section-choices',
        'picture_choice': 'section-picture',
        'fill_in_the_blanks': 'section-blanks',
        'matching_drag_drop': 'section-matching',
        'matching_text': 'section-matching',
        'free_text': 'section-freetext',
        'file_upload': 'section-fileupload',
    };

    function showSection(type) {
        typeSections.forEach(s => s.style.display = 'none');
        if (sectionMap[type]) {
            document.getElementById(sectionMap[type]).style.display = '';
        }
        // Toggle radio vs checkbox for correct answers
        const isSingle = ['single_choice_radio', 'single_choice_dropdown'].includes(type);
        document.querySelectorAll('.correct-checkbox').forEach(cb => {
            if (isSingle) {
                cb.type = 'radio';
                cb.name = 'correct';
            } else {
                cb.type = 'checkbox';
                cb.name = 'correct[]';
            }
        });
    }

    typeRadios.forEach(r => r.addEventListener('change', () => showSection(r.value)));
    const checked = document.querySelector('.question-type-radio:checked');
    if (checked) showSection(checked.value);

    // Cascading dropdowns
    const subjectSel = document.getElementById('subject_id');
    const topicSel = document.getElementById('topic_id');
    const subtopicSel = document.getElementById('subtopic_id');

    subjectSel.addEventListener('change', function() {
        topicSel.innerHTML = '<option value="">-- Select Topic --</option>';
        subtopicSel.innerHTML = '<option value="">-- Select Subtopic --</option>';
        if (this.value) {
            fetch("{{ url('admin/questions/get-topics') }}/" + this.value)
                .then(r => r.json())
                .then(data => data.forEach(t => {
                    topicSel.innerHTML += `<option value="${t.id}">${t.name}</option>`;
                }));
        }
    });

    topicSel.addEventListener('change', function() {
        subtopicSel.innerHTML = '<option value="">-- Select Subtopic --</option>';
        if (this.value) {
            fetch("{{ url('admin/questions/get-subtopics') }}/" + this.value)
                .then(r => r.json())
                .then(data => data.forEach(t => {
                    subtopicSel.innerHTML += `<option value="${t.id}">${t.name}</option>`;
                }));
        }
    });

    // Add/remove text options
    let optIndex = 2;
    document.getElementById('add-option').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'option-row d-flex align-items-center mb-1';
        const isSingle = ['single_choice_radio', 'single_choice_dropdown'].includes(document.querySelector('.question-type-radio:checked')?.value);
        row.innerHTML = `
            <input type="text" name="options[]" class="form-control mr-1" placeholder="Option ${optIndex + 1}">
            <div class="custom-control custom-checkbox mr-1">
                <input type="${isSingle ? 'radio' : 'checkbox'}" class="custom-control-input correct-checkbox" id="correct_${optIndex}" name="${isSingle ? 'correct' : 'correct[]'}" value="${optIndex}">
                <label class="custom-control-label" for="correct_${optIndex}">Correct</label>
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-option"><i class="feather icon-x"></i></button>
        `;
        document.getElementById('options-container').appendChild(row);
        optIndex++;
    });

    document.getElementById('options-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            const rows = this.querySelectorAll('.option-row');
            if (rows.length > 2) e.target.closest('.option-row').remove();
        }
    });

    // Add/remove picture options
    let picIndex = 2;
    document.getElementById('add-picture-option').addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'picture-option-row row mb-2 align-items-center';
        row.innerHTML = `
            <div class="col-md-4"><input type="file" name="option_images[]" class="form-control" accept="image/*"></div>
            <div class="col-md-4"><input type="text" name="option_texts[]" class="form-control" placeholder="Caption (optional)"></div>
            <div class="col-md-2"><div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input picture-correct" id="pic_correct_${picIndex}" name="correct[]" value="${picIndex}">
                <label class="custom-control-label" for="pic_correct_${picIndex}">Correct</label>
            </div></div>
            <div class="col-md-2"><button type="button" class="btn btn-sm btn-danger remove-picture-option"><i class="feather icon-x"></i></button></div>
        `;
        document.getElementById('picture-options-container').appendChild(row);
        picIndex++;
    });

    document.getElementById('picture-options-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-picture-option')) {
            const rows = this.querySelectorAll('.picture-option-row');
            if (rows.length > 2) e.target.closest('.picture-option-row').remove();
        }
    });

    // Add/remove blanks
    let blankIndex = 1;
    document.getElementById('add-blank').addEventListener('click', function() {
        blankIndex++;
        const row = document.createElement('div');
        row.className = 'blank-row d-flex align-items-center mb-1';
        row.innerHTML = `
            <span class="mr-1 font-weight-bold">Blank ${blankIndex}:</span>
            <input type="text" name="blank_answers[]" class="form-control mr-1" placeholder="Correct answer for blank ${blankIndex}">
            <button type="button" class="btn btn-sm btn-danger remove-blank"><i class="feather icon-x"></i></button>
        `;
        document.getElementById('blanks-container').appendChild(row);
    });

    document.getElementById('blanks-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-blank')) {
            const rows = this.querySelectorAll('.blank-row');
            if (rows.length > 1) e.target.closest('.blank-row').remove();
        }
    });

    // Add/remove matching pairs
    let matchIndex = 2;
    document.getElementById('add-match').addEventListener('click', function() {
        matchIndex++;
        const row = document.createElement('div');
        row.className = 'matching-row row mb-1';
        row.innerHTML = `
            <div class="col-md-5"><input type="text" name="match_left[]" class="form-control" placeholder="Left item ${matchIndex}"></div>
            <div class="col-md-1 text-center pt-1"><i class="feather icon-arrow-right"></i></div>
            <div class="col-md-5"><input type="text" name="match_right[]" class="form-control" placeholder="Right item ${matchIndex}"></div>
            <div class="col-md-1"><button type="button" class="btn btn-sm btn-danger remove-match"><i class="feather icon-x"></i></button></div>
        `;
        document.getElementById('matching-container').appendChild(row);
    });

    document.getElementById('matching-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-match')) {
            const rows = this.querySelectorAll('.matching-row');
            if (rows.length > 2) e.target.closest('.matching-row').remove();
        }
    });

    // Save and Add Another button handler
    document.getElementById('btn-save-add-another').addEventListener('click', function() {
        document.getElementById('save_and_add_another').value = '1';
    });
});
</script>
@endpush

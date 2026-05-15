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
                        <h2 class="content-header-title float-left mb-0 font-weight-bold">Edit Question</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Questions</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            @if ($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data" id="questionForm">
                @csrf
                @method('PUT')

                {{-- Meta --}}
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Meta Information</h4></div>
                    <div class="card-content"><div class="card-body"><div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Subject</label>
                            <select name="subject_id" id="subject_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" {{ old('subject_id', $question->subject_id) == $s->id ? 'selected' : '' }}>{{ $s->title }}</option>
                                @endforeach
                            </select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group"><label>Topic</label>
                            <select name="topic_id" id="topic_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($topics as $t)
                                    <option value="{{ $t->id }}" {{ old('topic_id', $question->topic_id) == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group"><label>Subtopic</label>
                            <select name="subtopic_id" id="subtopic_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($subtopics as $st)
                                    <option value="{{ $st->id }}" {{ old('subtopic_id', $question->subtopic_id) == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                @endforeach
                            </select>
                        </div></div>
                    </div><div class="row">
                        <div class="col-md-4"><div class="form-group"><label>Difficulty</label>
                            <select name="difficulty" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach(\App\Models\Question::DIFFICULTIES as $k => $l)
                                    <option value="{{ $k }}" {{ old('difficulty', $question->difficulty) === $k ? 'selected' : '' }}>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div></div>
                        <div class="col-md-4"><div class="form-group"><label>Marks</label>
                            <input type="number" name="marks" class="form-control" value="{{ old('marks', $question->marks) }}" min="1">
                        </div></div>
                        <div class="col-md-4"><div class="form-group"><label>Status</label>
                            <div class="custom-control custom-switch mt-1">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                        </div></div>
                    </div></div></div>
                </div>

                {{-- Type --}}
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Question Type <span class="text-danger">*</span></h4></div>
                    <div class="card-content"><div class="card-body"><div class="row">
                        @foreach(\App\Models\Question::TYPES as $k => $l)
                            <div class="col-md-4 col-lg-3 mb-2">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input question-type-radio" id="type_{{ $k }}" name="type" value="{{ $k }}" {{ old('type', $question->type) === $k ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="type_{{ $k }}">{{ $l }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div></div></div>
                </div>

                {{-- Content --}}
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Question Content</h4></div>
                    <div class="card-content"><div class="card-body">
                        <div class="form-group"><label>Question Text <span class="text-danger">*</span></label>
                            <textarea name="title" class="form-control" rows="3" required>{{ old('title', $question->title) }}</textarea>
                        </div>
                        <div class="form-group"><label>Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description', $question->description) }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><div class="form-group"><label>Question Image</label>
                                @if($question->image)
                                    <div class="mb-1"><img src="{{ asset('storage/' . $question->image) }}" style="max-height:100px;" class="rounded">
                                        <label class="ml-2"><input type="checkbox" name="remove_image" value="1"> Remove</label>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div></div>
                            <div class="col-md-6"><div class="form-group"><label>Explanation</label>
                                <textarea name="explanation" class="form-control" rows="2">{{ old('explanation', $question->explanation) }}</textarea>
                            </div></div>
                        </div>
                    </div></div>
                </div>

                {{-- Choices --}}
                @php $opts = $question->options; @endphp
                <div class="card type-section" id="section-choices" style="display:none;">
                    <div class="card-header"><h4 class="card-title">Answer Options</h4></div>
                    <div class="card-content"><div class="card-body">
                        <div id="options-container">
                            @foreach($opts as $i => $opt)
                            <div class="option-row d-flex align-items-center mb-1">
                                <input type="text" name="options[]" class="form-control mr-1" value="{{ $opt->option_text }}" placeholder="Option {{ $i+1 }}">
                                <div class="custom-control custom-checkbox mr-1">
                                    <input type="checkbox" class="custom-control-input correct-checkbox" id="correct_{{ $i }}" name="correct[]" value="{{ $i }}" {{ $opt->is_correct ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="correct_{{ $i }}">Correct</label>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger remove-option"><i class="feather icon-x"></i></button>
                            </div>
                            @endforeach
                            @if($opts->isEmpty())
                            @for($i = 0; $i < 2; $i++)
                            <div class="option-row d-flex align-items-center mb-1">
                                <input type="text" name="options[]" class="form-control mr-1" placeholder="Option {{ $i+1 }}">
                                <div class="custom-control custom-checkbox mr-1">
                                    <input type="checkbox" class="custom-control-input correct-checkbox" id="correct_{{ $i }}" name="correct[]" value="{{ $i }}">
                                    <label class="custom-control-label" for="correct_{{ $i }}">Correct</label>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger remove-option"><i class="feather icon-x"></i></button>
                            </div>
                            @endfor
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-option"><i class="feather icon-plus"></i> Add Option</button>
                    </div></div>
                </div>

                {{-- Picture Choice --}}
                <div class="card type-section" id="section-picture" style="display:none;">
                    <div class="card-header"><h4 class="card-title">Picture Options</h4></div>
                    <div class="card-content"><div class="card-body">
                        <div id="picture-options-container">
                            @foreach($opts as $i => $opt)
                            <div class="picture-option-row row mb-2 align-items-center">
                                <div class="col-md-4">
                                    @if($opt->option_image)<img src="{{ asset('storage/'.$opt->option_image) }}" style="max-height:60px;" class="mb-1 rounded"><input type="hidden" name="existing_option_images[{{ $i }}]" value="{{ $opt->option_image }}">@endif
                                    <input type="file" name="option_images[]" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-4"><input type="text" name="option_texts[]" class="form-control" value="{{ $opt->option_text }}" placeholder="Caption"></div>
                                <div class="col-md-2"><div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input picture-correct" id="pic_correct_{{ $i }}" name="correct[]" value="{{ $i }}" {{ $opt->is_correct ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="pic_correct_{{ $i }}">Correct</label>
                                </div></div>
                                <div class="col-md-2"><button type="button" class="btn btn-sm btn-danger remove-picture-option"><i class="feather icon-x"></i></button></div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-picture-option"><i class="feather icon-plus"></i> Add Picture</button>
                    </div></div>
                </div>

                {{-- Blanks --}}
                @php $blanks = $question->metadata['blank_answers'] ?? []; @endphp
                <div class="card type-section" id="section-blanks" style="display:none;">
                    <div class="card-header"><h4 class="card-title">Blank Answers</h4></div>
                    <div class="card-content"><div class="card-body">
                        <p class="text-muted mb-2">Use <code>___</code> in the question text for blanks.</p>
                        <div id="blanks-container">
                            @forelse($blanks as $i => $b)
                            <div class="blank-row d-flex align-items-center mb-1">
                                <span class="mr-1 font-weight-bold">Blank {{ $i+1 }}:</span>
                                <input type="text" name="blank_answers[]" class="form-control mr-1" value="{{ $b }}">
                                <button type="button" class="btn btn-sm btn-danger remove-blank"><i class="feather icon-x"></i></button>
                            </div>
                            @empty
                            <div class="blank-row d-flex align-items-center mb-1">
                                <span class="mr-1 font-weight-bold">Blank 1:</span>
                                <input type="text" name="blank_answers[]" class="form-control mr-1" placeholder="Correct answer">
                                <button type="button" class="btn btn-sm btn-danger remove-blank"><i class="feather icon-x"></i></button>
                            </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-blank"><i class="feather icon-plus"></i> Add Blank</button>
                    </div></div>
                </div>

                {{-- Matching --}}
                @php $pairs = $question->metadata['matching_pairs'] ?? []; @endphp
                <div class="card type-section" id="section-matching" style="display:none;">
                    <div class="card-header"><h4 class="card-title">Matching Pairs</h4></div>
                    <div class="card-content"><div class="card-body">
                        <div id="matching-container">
                            @forelse($pairs as $i => $p)
                            <div class="matching-row row mb-1">
                                <div class="col-md-5"><input type="text" name="match_left[]" class="form-control" value="{{ $p['left'] }}"></div>
                                <div class="col-md-1 text-center pt-1"><i class="feather icon-arrow-right"></i></div>
                                <div class="col-md-5"><input type="text" name="match_right[]" class="form-control" value="{{ $p['right'] }}"></div>
                                <div class="col-md-1"><button type="button" class="btn btn-sm btn-danger remove-match"><i class="feather icon-x"></i></button></div>
                            </div>
                            @empty
                            @for($i = 0; $i < 2; $i++)
                            <div class="matching-row row mb-1">
                                <div class="col-md-5"><input type="text" name="match_left[]" class="form-control" placeholder="Left item {{ $i+1 }}"></div>
                                <div class="col-md-1 text-center pt-1"><i class="feather icon-arrow-right"></i></div>
                                <div class="col-md-5"><input type="text" name="match_right[]" class="form-control" placeholder="Right item {{ $i+1 }}"></div>
                                <div class="col-md-1"><button type="button" class="btn btn-sm btn-danger remove-match"><i class="feather icon-x"></i></button></div>
                            </div>
                            @endfor
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1" id="add-match"><i class="feather icon-plus"></i> Add Pair</button>
                    </div></div>
                </div>

                {{-- Free Text --}}
                <div class="card type-section" id="section-freetext" style="display:none;">
                    <div class="card-header"><h4 class="card-title">Free Text Settings</h4></div>
                    <div class="card-content"><div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4"><label>Word Limit</label></div>
                            <div class="col-md-4"><input type="number" name="word_limit" class="form-control" value="{{ old('word_limit', $question->metadata['word_limit'] ?? '') }}" min="1"></div>
                        </div>
                    </div></div>
                </div>

                {{-- File Upload --}}
                @php $meta = $question->metadata ?? []; @endphp
                <div class="card type-section" id="section-fileupload" style="display:none;">
                    <div class="card-header"><h4 class="card-title">File Upload Settings</h4></div>
                    <div class="card-content"><div class="card-body">
                        <div class="form-group"><label>Allowed File Types</label><div class="row">
                            @foreach(['pdf'=>'PDF','doc'=>'DOC/DOCX','xls'=>'XLS/XLSX','ppt'=>'PPT/PPTX','jpg'=>'Images','zip'=>'ZIP/RAR'] as $ext => $lbl)
                                <div class="col-md-2"><div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ftype_{{ $ext }}" name="allowed_file_types[]" value="{{ $ext }}" {{ in_array($ext, $meta['allowed_file_types'] ?? []) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="ftype_{{ $ext }}">{{ $lbl }}</label>
                                </div></div>
                            @endforeach
                        </div></div>
                        <div class="form-group row">
                            <div class="col-md-4"><label>Max File Size (MB)</label></div>
                            <div class="col-md-4"><input type="number" name="max_file_size" class="form-control" value="{{ old('max_file_size', $meta['max_file_size'] ?? 5) }}" min="1" max="100"></div>
                        </div>
                    </div></div>
                </div>

                <div class="card"><div class="card-body">
                    <button type="submit" class="btn btn-primary mr-1"><i class="feather icon-save"></i> Update Question</button>
                    <a href="{{ route('admin.questions.index') }}" class="btn btn-outline-warning">Cancel</a>
                </div></div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sectionMap = {
        'single_choice_radio': 'section-choices', 'single_choice_dropdown': 'section-choices',
        'multiple_choice': 'section-choices', 'picture_choice': 'section-picture',
        'fill_in_the_blanks': 'section-blanks', 'matching_drag_drop': 'section-matching',
        'matching_text': 'section-matching', 'free_text': 'section-freetext', 'file_upload': 'section-fileupload',
    };

    function showSection(type) {
        document.querySelectorAll('.type-section').forEach(s => s.style.display = 'none');
        if (sectionMap[type]) document.getElementById(sectionMap[type]).style.display = '';
        const isSingle = ['single_choice_radio','single_choice_dropdown'].includes(type);
        document.querySelectorAll('.correct-checkbox').forEach(cb => {
            cb.type = isSingle ? 'radio' : 'checkbox';
            cb.name = isSingle ? 'correct' : 'correct[]';
        });
    }

    document.querySelectorAll('.question-type-radio').forEach(r => r.addEventListener('change', () => showSection(r.value)));
    const checked = document.querySelector('.question-type-radio:checked');
    if (checked) showSection(checked.value);

    // Cascading dropdowns
    document.getElementById('subject_id').addEventListener('change', function() {
        const ts = document.getElementById('topic_id'), st = document.getElementById('subtopic_id');
        ts.innerHTML = '<option value="">-- Select --</option>';
        st.innerHTML = '<option value="">-- Select --</option>';
        if (this.value) fetch("{{ url('admin/questions/get-topics') }}/" + this.value).then(r=>r.json()).then(d=>d.forEach(t=>ts.innerHTML+=`<option value="${t.id}">${t.name}</option>`));
    });
    document.getElementById('topic_id').addEventListener('change', function() {
        const st = document.getElementById('subtopic_id');
        st.innerHTML = '<option value="">-- Select --</option>';
        if (this.value) fetch("{{ url('admin/questions/get-subtopics') }}/" + this.value).then(r=>r.json()).then(d=>d.forEach(t=>st.innerHTML+=`<option value="${t.id}">${t.name}</option>`));
    });

    // Dynamic rows
    let optI = document.querySelectorAll('.option-row').length;
    document.getElementById('add-option').addEventListener('click', function() {
        const isSingle = ['single_choice_radio','single_choice_dropdown'].includes(document.querySelector('.question-type-radio:checked')?.value);
        const r = document.createElement('div'); r.className='option-row d-flex align-items-center mb-1';
        r.innerHTML=`<input type="text" name="options[]" class="form-control mr-1" placeholder="Option ${optI+1}"><div class="custom-control custom-checkbox mr-1"><input type="${isSingle?'radio':'checkbox'}" class="custom-control-input correct-checkbox" id="correct_${optI}" name="${isSingle?'correct':'correct[]'}" value="${optI}"><label class="custom-control-label" for="correct_${optI}">Correct</label></div><button type="button" class="btn btn-sm btn-danger remove-option"><i class="feather icon-x"></i></button>`;
        document.getElementById('options-container').appendChild(r); optI++;
    });
    document.getElementById('options-container').addEventListener('click', e => { if(e.target.closest('.remove-option') && document.querySelectorAll('.option-row').length>2) e.target.closest('.option-row').remove(); });

    let picI = document.querySelectorAll('.picture-option-row').length;
    document.getElementById('add-picture-option').addEventListener('click', function() {
        const r = document.createElement('div'); r.className='picture-option-row row mb-2 align-items-center';
        r.innerHTML=`<div class="col-md-4"><input type="file" name="option_images[]" class="form-control" accept="image/*"></div><div class="col-md-4"><input type="text" name="option_texts[]" class="form-control" placeholder="Caption"></div><div class="col-md-2"><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input picture-correct" id="pic_correct_${picI}" name="correct[]" value="${picI}"><label class="custom-control-label" for="pic_correct_${picI}">Correct</label></div></div><div class="col-md-2"><button type="button" class="btn btn-sm btn-danger remove-picture-option"><i class="feather icon-x"></i></button></div>`;
        document.getElementById('picture-options-container').appendChild(r); picI++;
    });
    document.getElementById('picture-options-container').addEventListener('click', e => { if(e.target.closest('.remove-picture-option') && document.querySelectorAll('.picture-option-row').length>2) e.target.closest('.picture-option-row').remove(); });

    let blI = document.querySelectorAll('.blank-row').length;
    document.getElementById('add-blank').addEventListener('click', function() {
        blI++; const r=document.createElement('div'); r.className='blank-row d-flex align-items-center mb-1';
        r.innerHTML=`<span class="mr-1 font-weight-bold">Blank ${blI}:</span><input type="text" name="blank_answers[]" class="form-control mr-1" placeholder="Answer"><button type="button" class="btn btn-sm btn-danger remove-blank"><i class="feather icon-x"></i></button>`;
        document.getElementById('blanks-container').appendChild(r);
    });
    document.getElementById('blanks-container').addEventListener('click', e => { if(e.target.closest('.remove-blank') && document.querySelectorAll('.blank-row').length>1) e.target.closest('.blank-row').remove(); });

    let mI = document.querySelectorAll('.matching-row').length;
    document.getElementById('add-match').addEventListener('click', function() {
        mI++; const r=document.createElement('div'); r.className='matching-row row mb-1';
        r.innerHTML=`<div class="col-md-5"><input type="text" name="match_left[]" class="form-control" placeholder="Left ${mI}"></div><div class="col-md-1 text-center pt-1"><i class="feather icon-arrow-right"></i></div><div class="col-md-5"><input type="text" name="match_right[]" class="form-control" placeholder="Right ${mI}"></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-danger remove-match"><i class="feather icon-x"></i></button></div>`;
        document.getElementById('matching-container').appendChild(r);
    });
    document.getElementById('matching-container').addEventListener('click', e => { if(e.target.closest('.remove-match') && document.querySelectorAll('.matching-row').length>2) e.target.closest('.matching-row').remove(); });
});
</script>
@endpush

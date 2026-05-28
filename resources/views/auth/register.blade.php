@extends('auth.layout.auth')

@section('title', 'Aspire Learners - Register')

@section('content')

    <section class="row flexbox-container">
        <div class="col-xl-8 col-10 d-flex justify-content-center">
            <div class="card bg-authentication rounded-0 mb-0">
                <div class="row m-0">
                    <div class="col-lg-6 d-lg-block d-none text-center align-self-center pl-0 pr-3 py-0">
                        <img src="{{ asset('theme/app-assets/images/logo/Aspire-Learner-Full-Logo.png') }}" width="50%" alt="branding logo"
                            width="100%">
                    </div>
                    <div class="col-lg-6 col-12 p-0">
                        <div class="card rounded-0 mb-0 p-2">
                            <div class="card-header pt-50 pb-1">
                                <div class="card-title">
                                    <h4 class="mb-0">Create Account</h4>
                                </div>
                            </div>
                            <p class="px-2">Fill the below form to create a new account.</p>
                            <div class="card-content">
                                @if ($errors->any())
                                    <div style="color:red;">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="card-body pt-0">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <div class="form-label-group">
                                            <!-- <input type="text" id="inputName" class="form-control" placeholder="Name" required> -->
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                placeholder="Name" value="{{ old('name') }}" required autocomplete="name"
                                                autofocus>
                                            <label for="name">Name</label>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-label-group position-relative">
                                            <input id="username" type="text"
                                                class="form-control @error('username') is-invalid @enderror" name="username"
                                                placeholder="Username" value="{{ old('username') }}" required autocomplete="username">
                                            <label for="username">Username</label>
                                            <span class="position-absolute" id="username-spinner" style="right: 15px; top: 12px; display: none; z-index: 10;">
                                                <i class="feather icon-loader" style="animation: spin 1s linear infinite; display: inline-block;"></i>
                                            </span>
                                            @error('username')
                                                <span class="invalid-feedback" role="alert" style="display:block;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div id="username-feedback" class="mt-25" style="display: none; font-size: 0.85rem;">
                                                <span id="username-feedback-text"></span>
                                            </div>
                                            <div id="username-suggestions" class="mt-50" style="display: none;">
                                                <div class="text-muted small mb-25" style="font-size: 0.75rem;">Suggested Usernames:</div>
                                                <div id="suggestions-list" class="d-flex flex-wrap align-items-center"></div>
                                            </div>
                                        </div>
                                        <div class="form-label-group">
                                            <!-- <input type="email" id="inputEmail" class="form-control" placeholder="Email" required> -->
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                placeholder="Email" value="{{ old('email') }}" required
                                                autocomplete="email">
                                            <label for="email">Email</label>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-label-group">
                                            <!-- <input type="password" id="inputPassword" class="form-control" placeholder="Password" required> -->
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="Password" required autocomplete="new-password">
                                            <label for="password">Password</label>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-label-group">
                                            <!-- <input type="password" id="inputConfPassword" class="form-control" placeholder="Confirm Password" required> -->
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" placeholder="Confirm Password" required
                                                autocomplete="new-password">
                                            <label for="password-confirm">Confirm Password</label>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-radio-con">
                                                                <input type="radio" name="role" checked=""
                                                                    value="student">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                <span class="">Student (Default)</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                    <li class="d-inline-block mr-2">
                                                        <fieldset>
                                                            <div class="vs-radio-con vs-radio-success">
                                                                <input type="radio" name="role" value="Parent">
                                                                <span class="vs-radio">
                                                                    <span class="vs-radio--border"></span>
                                                                    <span class="vs-radio--circle"></span>
                                                                </span>
                                                                <span class="">Parent</span>
                                                            </div>
                                                        </fieldset>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" checked>
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class=""> I accept the terms & conditions.</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <a href="login"
                                            class="btn btn-outline-primary float-left btn-inline mb-50">Login</a>
                                        <button type="submit"
                                            class="btn btn-primary float-right btn-inline mb-50">Register</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .suggestion-pill {
        cursor: pointer;
        background-color: rgba(115, 103, 240, 0.1);
        color: #7367f0;
        border: 1px solid rgba(115, 103, 240, 0.3);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.8rem;
        margin-right: 6px;
        margin-bottom: 6px;
        display: inline-block;
        transition: all 0.2s ease-in-out;
    }
    .suggestion-pill:hover {
        background-color: #7367f0;
        color: #fff;
        border-color: #7367f0;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const spinner = document.getElementById('username-spinner');
    const feedback = document.getElementById('username-feedback');
    const feedbackText = document.getElementById('username-feedback-text');
    const suggestionsDiv = document.getElementById('username-suggestions');
    const suggestionsList = document.getElementById('suggestions-list');
    const csrfToken = document.querySelector('input[name="_token"]').value;

    let debounceTimeout = null;
    let usernameManuallyEdited = false;

    // Helper to generate suggestions from Name
    function fetchSuggestionsFromName() {
        const nameVal = nameInput.value.trim();
        if (nameVal.length >= 2 && !usernameManuallyEdited && usernameInput.value.trim() === '') {
            spinner.style.display = 'block';
            axios.post('{{ route("register.check-username") }}', {
                username: nameVal.replace(/\s+/g, '_').toLowerCase(),
                name: nameVal
            }, {
                headers: { 'X-CSRF-TOKEN': csrfToken }
            }).then(response => {
                spinner.style.display = 'none';
                if (response.data && response.data.suggestions && usernameInput.value.trim() === '') {
                    renderSuggestions(response.data.suggestions);
                }
            }).catch(err => {
                spinner.style.display = 'none';
            });
        }
    }

    // Keyup on Name field to auto-suggest
    nameInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(fetchSuggestionsFromName, 400);
    });

    // Keyup on Username field
    usernameInput.addEventListener('input', function () {
        usernameManuallyEdited = true;
        const usernameVal = usernameInput.value.trim();
        
        clearTimeout(debounceTimeout);
        
        if (usernameVal === '') {
            feedback.style.display = 'none';
            suggestionsDiv.style.display = 'none';
            return;
        }

        if (usernameVal.length < 3) {
            feedback.style.display = 'block';
            feedbackText.className = 'text-danger';
            feedbackText.innerHTML = '<i class="feather icon-alert-triangle mr-25"></i>Username must be at least 3 characters.';
            suggestionsDiv.style.display = 'none';
            return;
        }

        // Regex check
        const regex = /^[a-zA-Z0-9_.-]+$/;
        if (!regex.test(usernameVal)) {
            feedback.style.display = 'block';
            feedbackText.className = 'text-danger';
            feedbackText.innerHTML = '<i class="feather icon-alert-triangle mr-25"></i>Only letters, numbers, dot, dash, and underscore are allowed.';
            suggestionsDiv.style.display = 'none';
            return;
        }

        spinner.style.display = 'block';
        
        debounceTimeout = setTimeout(function () {
            axios.post('{{ route("register.check-username") }}', {
                username: usernameVal,
                name: nameInput.value.trim()
            }, {
                headers: { 'X-CSRF-TOKEN': csrfToken }
            })
            .then(response => {
                spinner.style.display = 'none';
                feedback.style.display = 'block';
                
                if (response.data.available) {
                    feedbackText.className = 'text-success';
                    feedbackText.innerHTML = '<i class="feather icon-check-circle mr-25"></i>Username is available!';
                    suggestionsDiv.style.display = 'none';
                } else {
                    feedbackText.className = 'text-danger';
                    feedbackText.innerHTML = '<i class="feather icon-alert-circle mr-25"></i>' + response.data.message;
                    if (response.data.suggestions && response.data.suggestions.length > 0) {
                        renderSuggestions(response.data.suggestions);
                    } else {
                        suggestionsDiv.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                spinner.style.display = 'none';
                feedback.style.display = 'none';
            });
        }, 300);
    });

    function renderSuggestions(suggestions) {
        suggestionsList.innerHTML = '';
        suggestions.forEach(suggestion => {
            const span = document.createElement('span');
            span.className = 'suggestion-pill';
            span.textContent = suggestion;
            span.addEventListener('click', function () {
                usernameInput.value = suggestion;
                usernameManuallyEdited = true;
                // Dispatch input event to re-validate the username field
                const event = new Event('input', { bubbles: true });
                usernameInput.dispatchEvent(event);
            });
            suggestionsList.appendChild(span);
        });
        suggestionsDiv.style.display = 'block';
    }
});
</script>
@endpush

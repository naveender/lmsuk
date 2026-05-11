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
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Add Student</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.students.index') }}">Students</a>
                                    </li>
                                    <li class="breadcrumb-item">Add</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <form class="form form-horizontal" action="{{ route('admin.students.store') }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">

                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Fullname</label>
                                                                <input type="text" id="name" class="form-control"
                                                                    name="name" value="{{ old('name') }}"
                                                                    placeholder="Enter Your Fullname" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Username</label>
                                                                <input type="text" id="username" class="form-control"
                                                                    name="username" value="{{ old('username') }}"
                                                                    placeholder="Enter Username" required>
                                                                <small class="text-muted">Username must be unique. Suggested: <a href="javascript:void(0)" id="suggest_username"></a></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Email</label>
                                                                <input type="email" id="email" class="form-control"
                                                                    name="email" value="{{ old('email') }}"
                                                                    placeholder="Enter Your Email" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Password</label>
                                                                <input type="password" id="password" class="form-control"
                                                                    name="password" placeholder="Enter Your Password"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Date of Birth (DOB)</label>
                                                                <input type="date" class="form-control birthdate-picker"
                                                                    id="date_of_birth" value="{{ old('date_of_birth') }}"
                                                                    required placeholder="Date of Birth (DOB)"
                                                                    name="date_of_birth"
                                                                    data-validation-required-message="This birthdate field is required">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Group Year</label>
                                                            <select class="form-control" name="group_year" required>
                                                                <option value="">--Select Group Year--</option>
                                                                <option value="1"
                                                                    {{ old('group_year') == '1' ? 'selected' : '' }}>1st
                                                                    year</option>
                                                                <option value="2"
                                                                    {{ old('group_year') == '2' ? 'selected' : '' }}>2nd
                                                                    year</option>
                                                                <option value="3"
                                                                    {{ old('group_year') == '3' ? 'selected' : '' }}>3rd
                                                                    year</option>
                                                                <option value="4"
                                                                    {{ old('group_year') == '4' ? 'selected' : '' }}>4th
                                                                    year</option>
                                                                <option value="5"
                                                                    {{ old('group_year') == '5' ? 'selected' : '' }}>5th
                                                                    year</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Academic Year</label>
                                                            <select class="form-control" name="academic_year" required>
                                                                <option value="">--Select Academic Year--</option>
                                                                <option value="1"
                                                                    {{ old('academic_year') == '1' ? 'selected' : '' }}>
                                                                    2024-2025</option>
                                                                <option value="2"
                                                                    {{ old('academic_year') == '2' ? 'selected' : '' }}>
                                                                    2025-2026</option>
                                                                <option value="3"
                                                                    {{ old('academic_year') == '3' ? 'selected' : '' }}>
                                                                    2026-2027</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Region</label>
                                                                <input type="text" class="form-control" name="region"
                                                                    required
                                                                    placeholder="Geographic region or campus the student belongs to..."
                                                                    value="{{ old('region') }}"
                                                                    data-validation-required-message="This Region field is required">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Student Phone Number</label>
                                                                <input type="text" class="form-control"
                                                                    name="student_phone" value="{{ old('student_phone') }}"
                                                                    placeholder="Direct contact number of the student, if available...(optional)">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Student Email</label>
                                                                <input type="text" class="form-control"
                                                                    name="student_email"
                                                                    value="{{ old('student_email') }}"
                                                                    placeholder="Direct email address of the student, if available.(optional)">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <label>Gender</label>
                                                            <ul class="list-unstyled mb-0">
                                                                <li class="d-inline-block mr-2">
                                                                    <fieldset>
                                                                        <div class="vs-radio-con">
                                                                            <input type="radio" name="gender"
                                                                                {{ old('gender', 'male') == 'male' ? 'checked' : '' }}
                                                                                value="male">
                                                                            <span class="vs-radio">
                                                                                <span class="vs-radio--border"></span>
                                                                                <span class="vs-radio--circle"></span>
                                                                            </span>
                                                                            Male
                                                                        </div>
                                                                    </fieldset>
                                                                </li>
                                                                <li class="d-inline-block mr-2">
                                                                    <fieldset>
                                                                        <div class="vs-radio-con">
                                                                            <input type="radio" name="gender"
                                                                                {{ old('gender') == 'female' ? 'checked' : '' }}
                                                                                value="female">
                                                                            <span class="vs-radio">
                                                                                <span class="vs-radio--border"></span>
                                                                                <span class="vs-radio--circle"></span>
                                                                            </span>
                                                                            Female
                                                                        </div>
                                                                    </fieldset>
                                                                </li>
                                                                <li class="d-inline-block mr-2">
                                                                    <fieldset>
                                                                        <div class="vs-radio-con">
                                                                            <input type="radio" name="gender"
                                                                                {{ old('gender') == 'other' ? 'checked' : '' }}
                                                                                value="other">
                                                                            <span class="vs-radio">
                                                                                <span class="vs-radio--border"></span>
                                                                                <span class="vs-radio--circle"></span>
                                                                            </span>
                                                                            Other
                                                                        </div>
                                                                    </fieldset>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Submit</button>
                                                        <a href="{{ route('admin.students.index') }}"
                                                            class="btn btn-outline-warning mb-1">Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const usernameInput = document.getElementById('username');
            const suggestBtn = document.getElementById('suggest_username');

            nameInput.addEventListener('input', function() {
                if (nameInput.value) {
                    let baseName = nameInput.value.toLowerCase().replace(/[^a-z0-9]/g, '');
                    if (baseName.length > 0) {
                        let suggested = baseName + Math.floor(Math.random() * 1000);
                        suggestBtn.textContent = suggested;
                    } else {
                        suggestBtn.textContent = '';
                    }
                } else {
                    suggestBtn.textContent = '';
                }
            });

            suggestBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (suggestBtn.textContent) {
                    usernameInput.value = suggestBtn.textContent;
                }
            });
        });
    </script>
@endsection

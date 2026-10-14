@extends('layouts.app')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
        <!-- Page Header / Title & Breadcrumbs -->
        <div class="admin-page-header">
            <div class="admin-page-title-wrap">
                <div class="admin-title-row">
                    <h1 class="admin-page-heading">Edit User: {{ $user->name }}</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.users.index') }}">Users</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>Edit</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Update user profile details, roles, and status</p>
            </div>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-admin-secondary">
                    <i data-lucide="arrow-left" style="width: 17px; height: 17px;"></i>
                    <span>Back to Users</span>
                </a>
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

                                        <form class="form form-horizontal" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2"><span>Name</span></div>
                                                            <div class="col-md-10">
                                                                <input type="text" id="name" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2"><span>Username</span></div>
                                                            <div class="col-md-10">
                                                                <input type="text" id="username" class="form-control" name="username" value="{{ old('username', $user->username) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2"><span>Email</span></div>
                                                            <div class="col-md-10">
                                                                <input type="email" id="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2"><span>Password (Leave blank to keep current)</span></div>
                                                            <div class="col-md-10">
                                                                <input type="password" id="password" class="form-control" name="password">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2"><span>Role</span></div>
                                                            <div class="col-md-10">
                                                                <select name="role" id="role" class="form-control" required>
                                                                    <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Student</option>
                                                                    <option value="parent" {{ old('role', $user->role) == 'parent' ? 'selected' : '' }}>Parent</option>
                                                                    <option value="tutor" {{ old('role', $user->role) == 'tutor' ? 'selected' : '' }}>Tutor</option>
                                                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-10 offset-md-2">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Update</button>
                                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-warning mb-1">Cancel</a>
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
@endsection

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
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Edit Parent</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.parents.index') }}">Parents</a>
                                    </li>
                                    <li class="breadcrumb-item">Edit</li>
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

                                        <form class="form form-horizontal"
                                            action="{{ route('admin.parents.update', $parent->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Parent Name</label>
                                                                <input type="text" class="form-control" name="name"
                                                                    value="{{ old('name', $parent->name) }}" required
                                                                    placeholder="Full name of the parent or guardian.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Username</label>
                                                                <input type="text" id="username" class="form-control"
                                                                    name="username"
                                                                    value="{{ old('username', $parent->username) }}"
                                                                    placeholder="Enter Username" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Relation to the Student</label>
                                                                <select class="form-control" name="relation" required>
                                                                    <option value="Father"
                                                                        {{ old('relation', optional($parent->parentDetail)->relation) == 'Father' ? 'selected' : '' }}>
                                                                        Father</option>
                                                                    <option value="Mother"
                                                                        {{ old('relation', optional($parent->parentDetail)->relation) == 'Mother' ? 'selected' : '' }}>
                                                                        Mother</option>
                                                                    <option value="Guardian"
                                                                        {{ old('relation', optional($parent->parentDetail)->relation) == 'Guardian' ? 'selected' : '' }}>
                                                                        Guardian</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Parent Email</label>
                                                                <input type="email" class="form-control" name="email"
                                                                    value="{{ old('email', $parent->email) }}" required
                                                                    placeholder="Primary email address of the parent/guardian for communication.">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Parent Phone</label>
                                                                <input type="text" class="form-control" name="phone"
                                                                    value="{{ old('phone', optional($parent->parentDetail)->phone) }}"
                                                                    required
                                                                    placeholder="Primary contact number of the parent/guardian.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Parent Phone (Alternate)</label>
                                                                <input type="text" class="form-control"
                                                                    name="alternate_phone"
                                                                    value="{{ old('alternate_phone', optional($parent->parentDetail)->alternate_phone) }}"
                                                                    placeholder="Secondary contact number, in case the primary is unreachable">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Emergency Contact Number</label>
                                                                <input type="text" class="form-control"
                                                                    name="emergency_contact"
                                                                    value="{{ old('emergency_contact', optional($parent->parentDetail)->emergency_contact) }}"
                                                                    placeholder="A contact number to be reached in case of emergencies.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Password</label>
                                                                <input type="password" class="form-control" name="password"
                                                                    placeholder="Leave blank to keep current">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update</button>
                                                        <a href="{{ route('admin.parents.index') }}"
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
@endsection

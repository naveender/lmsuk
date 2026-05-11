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
                            <h2 class="content-header-title float-left mb-0 font-weight-bold">Add Parent</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.parents.index') }}">Parents</a>
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

                                        <form class="form form-horizontal" action="{{ route('admin.parents.store') }}"
                                            method="POST">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">

                                                    {{-- <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2"><span>Phone Number</span></div>
                                                            <div class="col-md-10">
                                                                <input type="text" id="phone" class="form-control" name="phone" value="{{ old('phone') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2"><span>Address</span></div>
                                                            <div class="col-md-10">
                                                                <textarea id="address" class="form-control" name="address" rows="3">{{ old('address') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Parent Name</label>
                                                                <input type="text" class="form-control" name="name"
                                                                    value="{{ old('name') }}" required
                                                                    placeholder="Full name of the parent or guardian."
                                                                    data-validation-required-message="This Parent Name field is required">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Relation to the Student</label>
                                                                <select class="form-control" name="relation" required>
                                                                    <option value="Father" {{ old('relation') == 'Father' ? 'selected' : '' }}>Father</option>
                                                                    <option value="Mother" {{ old('relation') == 'Mother' ? 'selected' : '' }}>Mother</option>
                                                                    <option value="Guardian" {{ old('relation') == 'Guardian' ? 'selected' : '' }}>Guardian</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Parent Email</label>
                                                                <input type="text" class="form-control" type="email"
                                                                    id="email" class="form-control" name="email"
                                                                    value="{{ old('email') }}" required
                                                                    placeholder="Primary email address of the parent/guardian for communication."
                                                                    data-validation-required-message="This Parent Email field is required">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Password</label>
                                                                <input type="password" class="form-control" name="password"
                                                                    value="{{ old('password') }}" required
                                                                    placeholder="Enter Your Password"
                                                                    data-validation-required-message="This Password field is required">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Parent Phone</label>
                                                                <input type="text" class="form-control" id="phone"
                                                                    name="phone" value="{{ old('phone') }}" required
                                                                    placeholder="Primary contact number of the parent/guardian."
                                                                    data-validation-required-message="This Parent Phone field is required">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Parent Phone (Alternate)</label>
                                                                <input type="text" class="form-control" name="alternate_phone" value="{{ old('alternate_phone') }}"
                                                                    placeholder="Secondary contact number, in case the primary is unreachable">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <div class="form-group">
                                                            <div class="controls">
                                                                <label>Emergency Contact Number</label>
                                                                <input type="text" class="form-control" name="emergency_contact" value="{{ old('emergency_contact') }}"
                                                                    placeholder="A contact number to be reached in case of emergencies.">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Submit</button>
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

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

                <!-- Linked Children Section -->
                <section id="linked-children">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">
                                <i class="feather icon-users"></i> Linked Children
                                <span class="badge badge-pill badge-primary ml-1">{{ $parent->children->count() }}</span>
                            </h4>
                            <a href="{{ route('admin.parents.show', $parent->id) }}" class="btn btn-sm btn-info">
                                <i class="feather icon-eye"></i> View Full Family Profile
                            </a>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if($parent->children->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover-animation">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Student Name</th>
                                                    <th>Email</th>
                                                    <th>Group Year</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($parent->children as $index => $child)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.students.edit', $child->user->id) }}" class="text-primary font-weight-bold">
                                                                {{ $child->user->name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $child->user->email }}</td>
                                                        <td>
                                                            @if($child->group_year)
                                                                {{ $child->group_year }}{{ $child->group_year == 1 ? 'st' : ($child->group_year == 2 ? 'nd' : ($child->group_year == 3 ? 'rd' : 'th')) }} year
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.students.edit', $child->user->id) }}" class="btn btn-sm btn-primary mb-1" title="Edit Student">
                                                                <i class="feather icon-edit"></i>
                                                            </a>
                                                            <form action="{{ route('admin.parents.unlink-student', [$parent->id, $child->user->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Unlink {{ $child->user->name }} from this parent?');">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-warning mb-1" title="Unlink Student">
                                                                    <i class="feather icon-x-circle"></i> Unlink
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-2">
                                        <p class="text-muted mb-1">No children linked to this parent yet.</p>
                                        <a href="{{ route('admin.parents.show', $parent->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="feather icon-plus"></i> Link a Student
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

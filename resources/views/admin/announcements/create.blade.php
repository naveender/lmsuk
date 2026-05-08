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
                                Add Announcement
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.announcements.index') }}">Announcements</a></li>
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

                                        <form class="form form-horizontal" action="{{ route('admin.announcements.store') }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Announcement Type</span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <select name="type" id="type" class="form-control"
                                                                    required>
                                                                    <option value="1"
                                                                        {{ old('type') == '1' ? 'selected' : '' }}>Text Only
                                                                    </option>
                                                                    <option value="2"
                                                                        {{ old('type') == '2' ? 'selected' : '' }}>Media
                                                                        Only</option>
                                                                    <option value="3"
                                                                        {{ old('type') == '3' ? 'selected' : '' }}>Text &
                                                                        Media</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Title</span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="text" id="title" class="form-control"
                                                                    name="title" value="{{ old('title') }}"
                                                                    placeholder="Title" required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12" id="content-group">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Content</span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <textarea id="content" class="form-control" name="content" rows="6" placeholder="Announcement Content">{{ old('content') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12" id="media-group" style="display:none;">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Media (Image/PDF)</span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <input type="file" id="media" class="form-control"
                                                                    name="media" accept="image/*,.pdf">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12" id="description-group" style="display:none;">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Small Description</span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <textarea id="description" class="form-control" name="description" rows="3"
                                                                    placeholder="Small description here...">{{ old('description') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group row">
                                                            <div class="col-md-2">
                                                                <span>Status</span>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <div
                                                                    class="custom-control custom-switch custom-control-inline">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="status" name="status" checked>
                                                                    <label class="custom-control-label"
                                                                        for="status">Active</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-10 offset-md-2">
                                                        <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Submit</button>
                                                        <a href="{{ route('admin.announcements.index') }}"
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
    <!-- END: Content-->
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const contentGroup = document.getElementById('content-group');
            const mediaGroup = document.getElementById('media-group');
            const descriptionGroup = document.getElementById('description-group');

            function toggleFields() {
                const val = typeSelect.value;
                contentGroup.style.display = (val == 1) ? 'block' : 'none';
                mediaGroup.style.display = (val == 2 || val == 3) ? 'block' : 'none';
                descriptionGroup.style.display = (val == 3) ? 'block' : 'none';
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields(); // Initial call
        });
    </script>
@endpush

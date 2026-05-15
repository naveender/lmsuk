{{-- FILE: resources\views\inventory.blade.php --}}
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
                                Topics Management
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.html">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="#">Topics</a></li>
                                    <li class="breadcrumb-item">Edit Topic</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">
                        <p>Edit Topic</p>
                    </div>
                </div>
                <section id="basic-vertical-layouts">
                    <div class="row match-height">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Vertical Form</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-vertical" action="{{ route('topics.update', $topic->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Topic code</label>
                                                            <input type="text" id="first-name-vertical"
                                                                class="form-control" name="topic_code"
                                                                placeholder="Topic code" value="{{ old('topic_code', $topic->code) }}">
                                                            @error('topic_code') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="email-id-vertical">Topic name</label>
                                                            <input type="text" id="email-id-vertical"
                                                                class="form-control" name="name"
                                                                placeholder="Topic name" value="{{ old('name', $topic->name) }}">
                                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="subject_id">Subject</label>
                                                            <select name="subject_id" id="subject_id" class="form-control">
                                                                <option value="">-- Select Subject --</option>
                                                                @foreach ($subjects as $subject)
                                                                    <option value="{{ $subject->id }}" {{ old('subject_id', $topic->subject_id) == $subject->id ? 'selected' : '' }}>
                                                                        {{ $subject->title }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="contact-info-vertical">Parent Topic</label>
                                                            <select name="parent_id" id="parent_id" class="form-control">
                                                                <option value="0">None</option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ old('parent_id', $topic->parent) == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('parent_id') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="password-vertical">Thumbnail</label>
                                                            <input type="file" id="password-vertical"
                                                                class="form-control" name="thumbnail"
                                                                placeholder="Thumbnail">
                                                            @if($topic->thumbnail)
                                                                <div class="mt-1">
                                                                    <img src="{{ asset('storage/' . $topic->thumbnail) }}" alt="Thumbnail" width="100">
                                                                </div>
                                                            @endif
                                                            @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Update</button>
                                                        <a href="{{ route('topics') }}" class="btn btn-outline-warning mr-1 mb-1">Cancel</a>
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

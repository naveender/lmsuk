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
                                    <li class="breadcrumb-item">Add Topic</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <section id="basic-vertical-layouts">
                    <div class="row match-height">
                        <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add New Topic </h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-vertical" action="{{ route('topics.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="first-name-vertical">Topic code</label>
                                                            <input type="text" id="first-name-vertical"
                                                                class="form-control" name="topic_code"
                                                                placeholder="Topic code" value="{{ old('topic_code') }}">
                                                            @error('topic_code') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="email-id-vertical">Topic name</label>
                                                            <input type="text" id="email-id-vertical"
                                                                class="form-control" name="name"
                                                                placeholder="Topic name" value="{{ old('name') }}">
                                                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="contact-info-vertical">Parent Topic</label>
                                                            <select name="parent_id" id="parent_id" class="form-control">
                                                                <option value="0">None</option>
                                                                @foreach ($topics as $topic)
                                                                    <option value="{{ $topic->id }}" {{ old('parent_id') == $topic->id ? 'selected' : '' }}>
                                                                        {{ $topic->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('parent_id') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="thumbnail">Thumbnail</label>
                                                            <input type="file" id="thumbnail" class="form-control"
                                                                name="thumbnail" placeholder="Thumbnail">
                                                            @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1">Submit</button>
                                                        <button type="reset"
                                                            class="btn btn-outline-warning mr-1 mb-1">Reset</button>
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

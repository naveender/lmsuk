@extends('layouts.app')
@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
        <!-- Page Header / Title & Breadcrumbs -->
        <div class="admin-page-header">
            <div class="admin-page-title-wrap">
                <div class="admin-title-row">
                    <h1 class="admin-page-heading">Create Exam Paper</h1>
                    <nav class="admin-breadcrumbs" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Home</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <a href="{{ route('admin.papers.index') }}">Papers</a>
                        <span class="breadcrumb-sep">&raquo;</span>
                        <span>Create</span>
                    </nav>
                </div>
                <p class="admin-page-subtitle">Configure paper metadata, exam questions, timing, and passing thresholds</p>
            </div>
            <div>
                <a href="{{ route('admin.papers.index') }}" class="btn btn-admin-secondary">
                    <i data-lucide="arrow-left" style="width: 17px; height: 17px;"></i>
                    <span>Back to Papers</span>
                </a>
            </div>
        </div>

            <!-- Content Body -->
            <div class="content-body">
                @livewire('admin.paper-form')
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

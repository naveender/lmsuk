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
                                Profile
                            </h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">Home</a>
                                    </li>
                                    <li class=" breadcrumb-item">Edit Profile
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-12">
                        <p>Manage your profile information and security settings.</p>
                    </div>
                </div>

                <!-- Profile Info section -->
                <section id="floating-label-layouts">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="feather icon-user"></i> Profile Information</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-vertical">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <label for="profile-first-name">First Name</label>
                                                            <input type="text" id="profile-first-name"
                                                                class="form-control profile-first-name" name="location"
                                                                placeholder="First Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <label for="profile-last-name">Last Name</label>
                                                            <input type="text" id="profile-last-name"
                                                                class="form-control profile-last-name" name="location"
                                                                placeholder="Last Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="profile-email">Email Address</label>
                                                            <input type="email" id="profile-email"
                                                                class="form-control profile-email" name="retention-period"
                                                                placeholder="demo123@gmail.com" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary mr-1 mb-1">Update
                                                    Profile</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Change password -->
                <section id="floating-label-layouts">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"><i class="feather icon-shield"></i> Account Security</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-vertical">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-12 mb-2">
                                                        <div class="form-group">
                                                            <label for="profile-current-pass">Current Password</label>
                                                            <div class="position-relative">
                                                                <input type="password" id="profile-current-pass"
                                                                    class="form-control profile-current-pass"
                                                                    name="current-password"
                                                                    placeholder="Enter Current Password">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <label for="profile-new-pass">New Password</label>
                                                            <div class="position-relative">
                                                                <input type="password" id="profile-new-pass"
                                                                    class="form-control profile-new-pass"
                                                                    name="new-password" placeholder="Enter New Password">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-12 col-12">
                                                        <div class="form-group">
                                                            <label for="profile-confirm-pass">Confirm Password</label>
                                                            <div class="position-relative">
                                                                <input type="password" id="profile-confirm-pass"
                                                                    class="form-control profile-confirm-pass"
                                                                    name="confirm-password"
                                                                    placeholder="Enter Confirm Password">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary mr-1 mb-1">Update
                                                            Password</button>
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
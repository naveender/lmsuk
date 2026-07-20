@extends('layouts.app')
@section('title', 'System Configurations')

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
                        <h2 class="content-header-title float-left mb-0 font-weight-bold">System Configurations</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">System Configs</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <i class="feather icon-check-circle"></i> {{ session('success') }}
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Manage Global Settings & Cloud Integrations</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <!-- Nav Tabs -->
                                <ul class="nav nav-tabs nav-justified" id="configTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="smtp-tab" data-toggle="tab" href="#smtp-pane" role="tab" aria-controls="smtp-pane" aria-selected="true">
                                            <i class="feather icon-mail mr-50"></i> SMTP Mail Config
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="wasabi-tab" data-toggle="tab" href="#wasabi-pane" role="tab" aria-controls="wasabi-pane" aria-selected="false">
                                            <i class="feather icon-cloud mr-50"></i> Wasabi Cloud Storage
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="s3-tab" data-toggle="tab" href="#s3-pane" role="tab" aria-controls="s3-pane" aria-selected="false">
                                            <i class="feather icon-server mr-50"></i> Amazon S3
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="global-tab" data-toggle="tab" href="#global-pane" role="tab" aria-controls="global-pane" aria-selected="false">
                                            <i class="feather icon-globe mr-50"></i> Global Settings
                                        </a>
                                    </li>
                                </ul>

                                <form action="{{ route('admin.system-configs.update') }}" method="POST" id="configForm">
                                    @csrf
                                    <!-- Tab Content -->
                                    <div class="tab-content pt-2">
                                        
                                        <!-- SMTP Configuration -->
                                        <div class="tab-pane active" id="smtp-pane" role="tabpanel" aria-labelledby="smtp-tab">
                                            <h5 class="mb-1 text-primary"><i class="feather icon-mail"></i> SMTP Mail Delivery Settings</h5>
                                            <p class="text-muted">Configure SMTP options for outbound transactional emails, announcements alerts, and account recovery.</p>
                                            
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="smtp_host" class="font-weight-bold">SMTP Host</label>
                                                        <input type="text" id="smtp_host" name="smtp_host" class="form-control" placeholder="smtp.mailtrap.io" value="{{ $configs['smtp_host'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="smtp_port" class="font-weight-bold">SMTP Port</label>
                                                        <input type="number" id="smtp_port" name="smtp_port" class="form-control" placeholder="587" value="{{ $configs['smtp_port'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="smtp_encryption" class="font-weight-bold">Encryption Type</label>
                                                        <select name="smtp_encryption" id="smtp_encryption" class="form-control">
                                                            <option value="" {{ $configs['smtp_encryption'] == '' ? 'selected' : '' }}>None</option>
                                                            <option value="ssl" {{ $configs['smtp_encryption'] == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                            <option value="tls" {{ $configs['smtp_encryption'] == 'tls' ? 'selected' : '' }}>TLS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="smtp_username" class="font-weight-bold">SMTP Username</label>
                                                        <input type="text" id="smtp_username" name="smtp_username" class="form-control" placeholder="user@smtp.com" value="{{ $configs['smtp_username'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="smtp_password" class="font-weight-bold">SMTP Password</label>
                                                        <input type="password" id="smtp_password" name="smtp_password" class="form-control" placeholder="••••••••••••" value="{{ $configs['smtp_password'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="smtp_from_address" class="font-weight-bold">Sender Email Address</label>
                                                        <input type="email" id="smtp_from_address" name="smtp_from_address" class="form-control" placeholder="no-reply@lms.com" value="{{ $configs['smtp_from_address'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="smtp_from_name" class="font-weight-bold">Sender Name</label>
                                                        <input type="text" id="smtp_from_name" name="smtp_from_name" class="form-control" placeholder="Aspire LMS" value="{{ $configs['smtp_from_name'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start mt-1">
                                                <button type="button" class="btn btn-outline-info" id="btnTestSmtp">
                                                    <i class="feather icon-play mr-25"></i> Test SMTP Connection
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Wasabi Cloud Storage -->
                                        <div class="tab-pane" id="wasabi-pane" role="tabpanel" aria-labelledby="wasabi-tab">
                                            <h5 class="mb-1 text-primary"><i class="feather icon-cloud"></i> Wasabi Cloud Storage Settings</h5>
                                            <p class="text-muted">Configure Wasabi S3 compatible cloud storage keys to directly host and retrieve learning resource videos.</p>
                                            
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="wasabi_key" class="font-weight-bold">Wasabi Access Key</label>
                                                        <input type="text" id="wasabi_key" name="wasabi_key" class="form-control" placeholder="Wasabi API Key ID" value="{{ $configs['wasabi_key'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="wasabi_secret" class="font-weight-bold">Wasabi Secret Key</label>
                                                        <input type="password" id="wasabi_secret" name="wasabi_secret" class="form-control" placeholder="Wasabi Secret Access Key" value="{{ $configs['wasabi_secret'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="wasabi_bucket" class="font-weight-bold">Bucket Name</label>
                                                        <input type="text" id="wasabi_bucket" name="wasabi_bucket" class="form-control" placeholder="lms-videos" value="{{ $configs['wasabi_bucket'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="wasabi_region" class="font-weight-bold">Region</label>
                                                        <input type="text" id="wasabi_region" name="wasabi_region" class="form-control" placeholder="us-east-1" value="{{ $configs['wasabi_region'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-12">
                                                    <div class="form-group">
                                                        <label for="wasabi_endpoint" class="font-weight-bold">S3 Endpoint URL</label>
                                                        <input type="text" id="wasabi_endpoint" name="wasabi_endpoint" class="form-control" placeholder="https://s3.wasabisys.com" value="{{ $configs['wasabi_endpoint'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start mt-1">
                                                <button type="button" class="btn btn-outline-info" id="btnTestWasabi">
                                                    <i class="feather icon-play mr-25"></i> Test Wasabi Connection
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Amazon S3 -->
                                        <div class="tab-pane" id="s3-pane" role="tabpanel" aria-labelledby="s3-tab">
                                            <h5 class="mb-1 text-primary"><i class="feather icon-server"></i> Amazon S3 storage settings</h5>
                                            <p class="text-muted">Configure AWS S3 storage credentials to distribute course media assets globally.</p>
                                            
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="s3_key" class="font-weight-bold">AWS Access Key ID</label>
                                                        <input type="text" id="s3_key" name="s3_key" class="form-control" placeholder="AWS Key" value="{{ $configs['s3_key'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="s3_secret" class="font-weight-bold">AWS Secret Access Key</label>
                                                        <input type="password" id="s3_secret" name="s3_secret" class="form-control" placeholder="AWS Secret Key" value="{{ $configs['s3_secret'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="s3_bucket" class="font-weight-bold">S3 Bucket Name</label>
                                                        <input type="text" id="s3_bucket" name="s3_bucket" class="form-control" placeholder="lms-assets-bucket" value="{{ $configs['s3_bucket'] }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="s3_region" class="font-weight-bold">AWS Region</label>
                                                        <input type="text" id="s3_region" name="s3_region" class="form-control" placeholder="us-east-1" value="{{ $configs['s3_region'] }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start mt-1">
                                                <button type="button" class="btn btn-outline-info" id="btnTestS3">
                                                    <i class="feather icon-play mr-25"></i> Test S3 Connection
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Global Configurations -->
                                        <div class="tab-pane" id="global-pane" role="tabpanel" aria-labelledby="global-tab">
                                            <h5 class="mb-1 text-primary"><i class="feather icon-globe"></i> Global Application Settings</h5>
                                            <p class="text-muted">Configure default media limitations, site-wide parameters, and global settings.</p>
                                            
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label for="max_upload_size" class="font-weight-bold">Maximum Upload File Size (MB)</label>
                                                        <div class="input-group">
                                                            <input type="number" id="max_upload_size" name="max_upload_size" class="form-control" placeholder="1024" value="{{ $configs['max_upload_size'] }}" min="1">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">MB</span>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">Default is 1024 MB (1 GB). Adjust this limit for chunked uploader capacity restrictions.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <hr class="mt-2">
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary font-weight-bold">
                                            <i class="feather icon-save mr-25"></i> Save Settings
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Test SMTP
        document.getElementById('btnTestSmtp').addEventListener('click', function() {
            const host = document.getElementById('smtp_host').value;
            const port = document.getElementById('smtp_port').value;
            const username = document.getElementById('smtp_username').value;
            const password = document.getElementById('smtp_password').value;
            const encryption = document.getElementById('smtp_encryption').value;
            const from_address = document.getElementById('smtp_from_address').value;
            const from_name = document.getElementById('smtp_from_name').value;

            if(!host || !port || !from_address) {
                Swal.fire('Error', 'Please fill in SMTP Host, Port, and Sender Email before testing.', 'error');
                return;
            }

            Swal.fire({
                title: 'Testing SMTP Connection...',
                text: 'Please wait while we attempt to send a test email.',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('{{ route("admin.system-configs.test-smtp") }}', {
                host, port, username, password, encryption, from_address, from_name
            })
            .then(response => {
                Swal.fire('Success', response.data.message, 'success');
            })
            .catch(error => {
                const message = error.response && error.response.data && error.response.data.message 
                    ? error.response.data.message 
                    : error.message;
                Swal.fire('Connection Failed', message, 'error');
            });
        });

        // Test Wasabi
        document.getElementById('btnTestWasabi').addEventListener('click', function() {
            const key = document.getElementById('wasabi_key').value;
            const secret = document.getElementById('wasabi_secret').value;
            const region = document.getElementById('wasabi_region').value;
            const bucket = document.getElementById('wasabi_bucket').value;
            const endpoint = document.getElementById('wasabi_endpoint').value;

            if(!key || !secret || !bucket || !region || !endpoint) {
                Swal.fire('Error', 'Please fill in all Wasabi configuration parameters before testing.', 'error');
                return;
            }

            Swal.fire({
                title: 'Testing Wasabi Connection...',
                text: 'Attempting to write, read, and delete a temporary file in the bucket...',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('{{ route("admin.system-configs.test-wasabi") }}', {
                key, secret, region, bucket, endpoint
            })
            .then(response => {
                Swal.fire('Success', response.data.message, 'success');
            })
            .catch(error => {
                const message = error.response && error.response.data && error.response.data.message 
                    ? error.response.data.message 
                    : error.message;
                Swal.fire('Connection Failed', message, 'error');
            });
        });

        // Test AWS S3
        document.getElementById('btnTestS3').addEventListener('click', function() {
            const key = document.getElementById('s3_key').value;
            const secret = document.getElementById('s3_secret').value;
            const region = document.getElementById('s3_region').value;
            const bucket = document.getElementById('s3_bucket').value;

            if(!key || !secret || !bucket || !region) {
                Swal.fire('Error', 'Please fill in AWS Access Key, Secret Key, Bucket Name, and Region before testing.', 'error');
                return;
            }

            Swal.fire({
                title: 'Testing S3 Connection...',
                text: 'Attempting to write, read, and delete a temporary file in your S3 bucket...',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('{{ route("admin.system-configs.test-s3") }}', {
                key, secret, region, bucket
            })
            .then(response => {
                Swal.fire('Success', response.data.message, 'success');
            })
            .catch(error => {
                const message = error.response && error.response.data && error.response.data.message 
                    ? error.response.data.message 
                    : error.message;
                Swal.fire('Connection Failed', message, 'error');
            });
        });
    });
</script>
@endpush

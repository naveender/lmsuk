@extends('layouts.app')

@section('title', 'My Account - Aspire Learners')

@push('styles')
<style>
    .account-hero-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #fff;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
    }
    .account-hero-card::after {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(115, 103, 240, 0.3) 0%, rgba(115, 103, 240, 0) 70%);
        pointer-events: none;
    }
    .account-avatar-wrapper {
        position: relative;
        display: inline-block;
    }
    .account-avatar {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.2);
        background: linear-gradient(135deg, #7367f0 0%, #ce9ffc 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        color: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    .role-badge-pill {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .nav-tabs-account .nav-link {
        font-weight: 600;
        padding: 12px 20px;
        border: none;
        border-bottom: 3px solid transparent;
        color: #64748b;
        background: transparent;
        transition: all 0.2s ease;
    }
    .nav-tabs-account .nav-link.active {
        color: #7367f0;
        border-bottom-color: #7367f0;
        background: transparent;
    }
    .nav-tabs-account .nav-link:hover:not(.active) {
        color: #334155;
    }
    .field-icon-btn {
        cursor: pointer;
        position: absolute;
        right: 12px;
        top: 38px;
        color: #94a3b8;
        background: none;
        border: none;
        padding: 0;
    }
    .field-icon-btn:hover {
        color: #7367f0;
    }
    .card-settings-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        padding-bottom: 12px;
        margin-bottom: 20px;
    }
    .dark-layout .account-hero-card {
        background: linear-gradient(135deg, #10163a 0%, #262c49 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .dark-layout .nav-tabs-account .nav-link {
        color: #94a3b8;
    }
    .dark-layout .nav-tabs-account .nav-link.active {
        color: #7367f0;
        border-bottom-color: #7367f0;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <!-- Content Header & Breadcrumbs -->
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0 font-weight-bold">My Account</h2>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Account & Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-check-circle mr-2 font-medium-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="feather icon-alert-triangle mr-2 font-medium-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center mb-1">
                        <i class="feather icon-alert-circle mr-2 font-medium-2"></i>
                        <strong>Please resolve the following errors:</strong>
                    </div>
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Account Hero Card -->
            <div class="card account-hero-card mb-3">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <div class="account-avatar-wrapper mr-md-4 mb-3 mb-md-0">
                            <div class="account-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="text-center text-md-left flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start mb-1">
                                <h3 class="font-weight-bold text-white mb-0 mr-2">{{ $user->name }}</h3>
                                @php
                                    $roleColor = match($user->role) {
                                        'admin' => 'badge-danger',
                                        'student' => 'badge-info',
                                        'tutor' => 'badge-success',
                                        'parent' => 'badge-warning',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $roleColor }} role-badge-pill">{{ ucfirst($user->role) }}</span>
                            </div>
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start text-white-50 small">
                                <span class="mr-3 mb-1"><i class="feather icon-at-sign mr-1"></i>{{ $user->username }}</span>
                                <span class="mr-3 mb-1"><i class="feather icon-mail mr-1"></i>{{ $user->email }}</span>
                                <span class="mb-1"><i class="feather icon-calendar mr-1"></i>Joined {{ $user->created_at ? $user->created_at->format('M Y') : 'Recently' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="card mb-3">
                <div class="card-body p-0">
                    <ul class="nav nav-tabs nav-tabs-account" id="accountTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ session('tab') === 'security' || $errors->has('current_password') || $errors->has('password') ? '' : 'active' }}" 
                               id="profile-tab" data-toggle="tab" href="#profile-tab-pane" role="tab">
                                <i class="feather icon-user mr-1"></i> Profile Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ session('tab') === 'security' || $errors->has('current_password') || $errors->has('password') ? 'active' : '' }}" 
                               id="security-tab" data-toggle="tab" href="#security-tab-pane" role="tab">
                                <i class="feather icon-shield mr-1"></i> Password & Security
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="tab-content" id="accountTabContent">
                <!-- TAB 1: Profile Information -->
                <div class="tab-pane fade {{ session('tab') === 'security' || $errors->has('current_password') || $errors->has('password') ? '' : 'show active' }}" 
                     id="profile-tab-pane" role="tabpanel">
                    
                    <form action="{{ route('account.updateProfile') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-header card-settings-header pb-2">
                                <h4 class="card-title font-weight-bold mb-0">
                                    <i class="feather icon-edit-2 mr-1 text-primary"></i> Personal Details
                                </h4>
                                <small class="text-muted">Update your public identification and account contact information.</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="name" class="font-weight-bold">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name', $user->name) }}" required placeholder="e.g. John Doe">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="username" class="font-weight-bold">Username <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">@</span>
                                                </div>
                                                <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" 
                                                       value="{{ old('username', $user->username) }}" required placeholder="username">
                                            </div>
                                            @error('username')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="email" class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                   value="{{ old('email', $user->email) }}" required placeholder="email@example.com">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Account Role</label>
                                            <input type="text" class="form-control bg-light" value="{{ ucfirst($user->role) }}" disabled readonly>
                                            <small class="text-muted">Role permissions are assigned by system administration.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Role Specific Fields -->
                                @if($user->isStudent())
                                    <div class="border-top pt-3 mt-2">
                                        <h5 class="font-weight-bold mb-3 text-secondary">
                                            <i class="feather icon-book-open mr-1"></i> Student Information
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="student_phone" class="font-weight-bold">Phone Number</label>
                                                    <input type="text" id="student_phone" name="student_phone" class="form-control"
                                                           value="{{ old('student_phone', $user->studentDetail?->student_phone) }}" 
                                                           placeholder="e.g. +44 7123 456789">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="gender" class="font-weight-bold">Gender</label>
                                                    <select id="gender" name="gender" class="form-control">
                                                        <option value="">Select Gender</option>
                                                        <option value="male" {{ old('gender', strtolower($user->studentDetail?->gender ?? '')) === 'male' ? 'selected' : '' }}>Male</option>
                                                        <option value="female" {{ old('gender', strtolower($user->studentDetail?->gender ?? '')) === 'female' ? 'selected' : '' }}>Female</option>
                                                        <option value="other" {{ old('gender', strtolower($user->studentDetail?->gender ?? '')) === 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label for="date_of_birth" class="font-weight-bold">Date of Birth</label>
                                                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control"
                                                           value="{{ old('date_of_birth', $user->studentDetail?->date_of_birth) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($user->isParent())
                                    <div class="border-top pt-3 mt-2">
                                        <h5 class="font-weight-bold mb-3 text-secondary">
                                            <i class="feather icon-users mr-1"></i> Parent Contact Details
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="phone" class="font-weight-bold">Primary Contact Phone</label>
                                                    <input type="text" id="phone" name="phone" class="form-control"
                                                           value="{{ old('phone', $user->parentDetail?->phone) }}" 
                                                           placeholder="e.g. +44 7123 456789">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="alternate_phone" class="font-weight-bold">Alternate Phone</label>
                                                    <input type="text" id="alternate_phone" name="alternate_phone" class="form-control"
                                                           value="{{ old('alternate_phone', $user->parentDetail?->alternate_phone) }}" 
                                                           placeholder="Optional backup phone">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="emergency_contact" class="font-weight-bold">Emergency Contact Person</label>
                                                    <input type="text" id="emergency_contact" name="emergency_contact" class="form-control"
                                                           value="{{ old('emergency_contact', $user->parentDetail?->emergency_contact) }}" 
                                                           placeholder="Name and contact number">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="address" class="font-weight-bold">Home Address</label>
                                                    <input type="text" id="address" name="address" class="form-control"
                                                           value="{{ old('address', $user->parentDetail?->address) }}" 
                                                           placeholder="Full postal address">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-4 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold">
                                        <i class="feather icon-check mr-1"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: Password & Security -->
                <div class="tab-pane fade {{ session('tab') === 'security' || $errors->has('current_password') || $errors->has('password') ? 'show active' : '' }}" 
                     id="security-tab-pane" role="tabpanel">
                    
                    <div class="row">
                        <div class="col-lg-8 col-12">
                            <form action="{{ route('account.updatePassword') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="card">
                                    <div class="card-header card-settings-header pb-2">
                                        <h4 class="card-title font-weight-bold mb-0">
                                            <i class="feather icon-lock mr-1 text-primary"></i> Change Password
                                        </h4>
                                        <small class="text-muted">Ensure your account uses a strong, unique password to maintain security.</small>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group position-relative">
                                            <label for="current_password" class="font-weight-bold">Current Password <span class="text-danger">*</span></label>
                                            <input type="password" id="current_password" name="current_password" 
                                                   class="form-control @error('current_password') is-invalid @enderror" 
                                                   required placeholder="Enter your current password">
                                            <button type="button" class="field-icon-btn toggle-password-btn" data-target="current_password" title="Toggle visibility">
                                                <i class="feather icon-eye"></i>
                                            </button>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group position-relative">
                                            <label for="password" class="font-weight-bold">New Password <span class="text-danger">*</span></label>
                                            <input type="password" id="password" name="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   required placeholder="At least 8 characters">
                                            <button type="button" class="field-icon-btn toggle-password-btn" data-target="password" title="Toggle visibility">
                                                <i class="feather icon-eye"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group position-relative">
                                            <label for="password_confirmation" class="font-weight-bold">Confirm New Password <span class="text-danger">*</span></label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                                   class="form-control" required placeholder="Re-enter new password">
                                            <button type="button" class="field-icon-btn toggle-password-btn" data-target="password_confirmation" title="Toggle visibility">
                                                <i class="feather icon-eye"></i>
                                            </button>
                                        </div>

                                        <div class="mt-4 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold">
                                                <i class="feather icon-shield mr-1"></i> Update Password
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Security Tips Column -->
                        <div class="col-lg-4 col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header pb-2">
                                    <h5 class="card-title font-weight-bold text-dark">
                                        <i class="feather icon-info text-info mr-1"></i> Security Guidelines
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-start mb-2">
                                            <i class="feather icon-check text-success mr-2 mt-1"></i>
                                            <span class="small">Use a minimum of 8 characters (longer is better).</span>
                                        </li>
                                        <li class="d-flex align-items-start mb-2">
                                            <i class="feather icon-check text-success mr-2 mt-1"></i>
                                            <span class="small">Combine upper and lower case letters, numbers, and symbols.</span>
                                        </li>
                                        <li class="d-flex align-items-start mb-2">
                                            <i class="feather icon-check text-success mr-2 mt-1"></i>
                                            <span class="small">Do not reuse passwords across multiple systems or websites.</span>
                                        </li>
                                        <li class="d-flex align-items-start">
                                            <i class="feather icon-check text-success mr-2 mt-1"></i>
                                            <span class="small">Never share your password with anyone or enter it on untrusted devices.</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var targetId = this.getAttribute('data-target');
                var input = document.getElementById(targetId);
                var icon = this.querySelector('i');
                if (input) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('icon-eye');
                        icon.classList.add('icon-eye-off');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('icon-eye-off');
                        icon.classList.add('icon-eye');
                    }
                }
            });
        });
    });
</script>
@endpush

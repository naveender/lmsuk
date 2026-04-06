@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container">
  <h2>Reset Password</h2>
  <form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
    <div>
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>
    <div>
      <label>New Password</label>
      <input type="password" name="password" required>
    </div>
    <div>
      <label>Confirm Password</label>
      <input type="password" name="password_confirmation" required>
    </div>
    <button type="submit">Reset Password</button>
  </form>
</div>
@endsection

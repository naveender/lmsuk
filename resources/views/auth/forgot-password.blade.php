@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container">
  <h2>Forgot Password</h2>
  <form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div>
      <label>Email</label>
      <input type="email" name="email" required autofocus>
    </div>
    <button type="submit">Send Reset Link</button>
  </form>
</div>
@endsection

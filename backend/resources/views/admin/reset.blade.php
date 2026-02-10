@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto card">
    <h1 class="text-xl font-bold mb-4">Reset Password</h1>

    <form class="space-y-4">
        <div class="space-y-1">
            <label class="text-sm">Email</label>
            <input type="email" placeholder="Your email" class="input">
        </div>
        <button class="btn-primary" type="submit">
            Send Reset Link
        </button>
    </form>
</div>
@endsection

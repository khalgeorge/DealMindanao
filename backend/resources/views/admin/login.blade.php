@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto card">
    <h1 class="text-xl font-bold mb-4">Admin Login</h1>

    <form method="POST" class="space-y-4">
        <div class="space-y-1">
            <label class="text-sm">Email</label>
            <input type="email" placeholder="Email" class="input">
        </div>
        <div class="space-y-1">
            <label class="text-sm">Password</label>
            <input type="password" placeholder="Password" class="input">
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button class="btn-primary" type="submit">
                Login
            </button>
            <a href="/admin/reset" class="btn-secondary">
                Forgot password?
            </a>
        </div>
    </form>
</div>
@endsection

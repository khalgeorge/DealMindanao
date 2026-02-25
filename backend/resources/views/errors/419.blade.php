{{--
    Custom 419 error view — "Page Expired" safety net.

    The global exception handler in bootstrap/app.php intercepts
    TokenMismatchException BEFORE this view is rendered, so under normal
    circumstances users never reach here.

    This view handles the edge-case where a browser reloads a page that was
    already displaying the 419 error (the exception handler only fires on a
    fresh exception; a reload of the rendered error page bypasses it).

    Strategy: flash the friendly message into the session, then immediately
    redirect via JavaScript + meta-refresh so the user lands on the right
    page with the toast/warning displayed.
--}}
@php
    $message  = 'Your session expired. Please log in again to continue.';
    $isAdmin  = request()->is('admin') || request()->is('admin/*');
    $dest     = $isAdmin ? route('admin.login') : route('home');
    session()->flash('warning', $message);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Instant redirect — user never sees this page --}}
    <meta http-equiv="refresh" content="0;url={{ $dest }}">
    <title>Redirecting…</title>
</head>
<body>
    <script>window.location.replace('{{ $dest }}');</script>
</body>
</html>

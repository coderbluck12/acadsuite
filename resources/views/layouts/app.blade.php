<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo.png') }}" />
    @stack('styles')
</head>
<body>
    @yield('body')
    @stack('scripts')
</body>
</html>

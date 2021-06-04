@php
    if (!isset($current_page))
        $current_page = "";
@endphp

    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="bg-dark" style="height: 100vh">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($title)
    <title>{{ $title . ' | ' . config('app.name', 'Laravel') }}</title>
    @else
    <title>{{ config('app.name', 'Laravel') }}</title>
    @endisset

    <!-- Bootstrap responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous"> --}}

    <link href="{{ asset("css/app.css") }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

    <!-- Styles -->
    <link href="{{ asset('css/authentication.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap_extension.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user_profile.css') }}" rel="stylesheet">

    <script>
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>

    <script src="{{ asset("js/app.js") }}"></script>
</head>

<body style="min-height: 100vh; display: flex; flex-direction:column;">

{{-- Header --}}
@include("partials.header", ['current_page' => $current_page])

{{-- Main --}}
<main id="content" class="big-boy">
    <div class="alert-section"></div>

    @yield('content')
</main>

{{-- Footer --}}
@include("partials.footer")
</body>

</html>

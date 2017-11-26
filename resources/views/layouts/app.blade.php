<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description', setting('seo_description', 'EasyBBS 社区。'))" />
    <meta name="keyword" content="@yield('keyword', setting('seo_keyword', 'EasyBBS,社区,论坛,开发者论坛'))" />
    <title>@yield('title', 'EasyBBS') - {{ setting('site_name', 'EasyBBS') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>

<body>
    <div id="app" class="{{ route_class() }}-page">

        @include('layouts._header')

        <div class="container">

            @include('layouts._message')
            @yield('content')

        </div>

        @include('layouts._footer')
    </div>

    @if (app()->isLocal())
        @include('sudosu::user-selector')
    @endif

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
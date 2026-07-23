<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Benedictio Dev - POS</title>
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net" />
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  <!-- Styles -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

  <!-- Map -->
  {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script> --}}

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.1/dist/L.Control.Locate.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.81.1/dist/L.Control.Locate.min.js"></script>
  </script>
</head>

<body class="bg-sky-50">
  @include('layouts.header')
  <div class="flex pt-16 overflow-hidden bg-sky-50">
    @if (Str::startsWith(Route::currentRouteName(), 'management.'))  
      @include('layouts.sidebar_management')
    @else  
      @if (Auth::user()->is_management && in_array(Route::currentRouteName(), [
        'dashboard.profile',
        'dashboard.change_password'
        ])
      )
        @include('layouts.sidebar_management')
      @else  
        @include('layouts.sidebar')
      @endif
    @endif

    <div id="main-content" class="relative w-full h-full overflow-y-auto bg-sky-50 lg:ml-64">
      <main class="h-[calc(100vh-72px)] bg-sky-50 px-4 pt-6">@yield('main')</main>
      {{-- @include('layouts.footer') --}}
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
  <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('assets/helpers/helpers.js') }}"></script>
  @stack('script')
</body>

</html>

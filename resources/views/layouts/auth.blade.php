<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Benedictio Dev - POS</title>
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png')}}">
  <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest')}}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
  {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
  <!-- Styles -->
</head>

<body class="bg-sky-100">
  <main>
    @yield('main')
  </main>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

  <script>
    const cek_screen_size = () => {
      alert(`Width : ${screen.width}, Height: ${screen.height}`)
    }
  </script>
</body>

</html>

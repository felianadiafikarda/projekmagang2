<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex min-h-screen">

        @include('layouts.sidebar')

        {{-- Bagian kanan (navbar + konten) --}}
        <div class="flex-1 ml-64 flex flex-col p-6">
            {{-- Navbar --}}
            @include('layouts.navbar')

            {{-- Main Content --}}
            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
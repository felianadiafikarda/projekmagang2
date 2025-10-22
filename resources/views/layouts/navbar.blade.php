{{-- resources/views/layouts/navbar.blade.php --}}
<div class="flex justify-between items-center mb-8 bg-white p-4 shadow rounded-lg">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            @yield('page_title', 'Dashboard')
        </h1>
        <p class="text-gray-600">
            @yield('page_subtitle', 'Kelola Author, Reviewer, dan Editor untuk jurnal')
        </p>
    </div>

    {{-- Avatar / Role Circle --}}
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-gray-800 text-white rounded-full flex items-center justify-center font-semibold uppercase">
            CM
        </div>
    </div>
</div>

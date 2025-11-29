<aside class="w-64 bg-gray-800 text-gray-100 flex flex-col justify-between fixed inset-y-0">
    <div>
        <div class="bg-gray-900 px-6 py-5 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white tracking-wide">Journal Management</h2>
            <p class="text-sm text-gray-400">2026</p>
        </div>

        @php
        $level = auth()->user()->roles->first()->level ?? 0;
        @endphp
        <nav class="mt-6 flex flex-col space-y-1">

            @if($level >= 6)
            <a href="{{ route('conference_manager.index') }}"
                class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
           {{ Request::routeIs('conference_manager*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-100' }}">
                <span>Conference Manager</span>
            </a>
            @endif

            @if($level >= 5)
            <a href="{{ route('editor.index') }}" class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
           {{ Request::routeIs('editor*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-100' }}">
                <span>Editor</span>
            </a>
            @endif

            @if($level >= 4)
            <a href="{{ route('section_editor.index') }}" class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
           {{ Request::routeIs('section_editor*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-100' }}">
                <span>Section Editor</span>
            </a>
            @endif

            @if($level >= 2)
            <a href="{{ route('reviewer.index') }}" class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
           {{ Request::routeIs('reviewer*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-100' }}">
                <span>Reviewer</span>
            </a>
            @endif

            {{-- Author (level 1+) → semua user minimal author --}}
            @if($level >= 1)
            <a href="{{ route('author.index') }}" class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
           {{ Request::routeIs('author*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-100' }}">
                <span>Author</span>
            </a>
            @endif

            @if($level >= 7)
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
           {{ Request::routeIs('users*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-100' }}">
                <span>Users</span>
            </a>
            @endif
        </nav>

    </div>

    <div class="border-t border-gray-700 p-4">
        <a href="{{ route('logout') }}"
            class="w-full flex items-center justify-center gap-2 bg-gray-700 text-gray-100 px-4 py-2 rounded-md hover:bg-gray-600 transition">
            Logout
        </a>
    </div>
</aside>
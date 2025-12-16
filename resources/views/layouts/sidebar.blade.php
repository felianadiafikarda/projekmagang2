<aside class="w-64 bg-gray-800 text-gray-100 flex flex-col justify-between fixed inset-y-0">
    <div>
        <div class="bg-gray-900 px-6 py-5 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white tracking-wide">Journal Management</h2>
            <p class="text-sm text-gray-400">2026</p>
        </div>

        @php
            $user = auth()->user();
            $userRoles = $user->roles->pluck('name')->toArray();
            
            $isAdmin = in_array('admin', $userRoles);
            $isConferenceManager = in_array('conference_manager', $userRoles);
            $isEditor = in_array('editor', $userRoles);
            $isSectionEditor = in_array('section_editor', $userRoles);
            $isReviewer = in_array('reviewer', $userRoles);
            $isAuthor = in_array('author', $userRoles);
        @endphp
        
        <nav class="mt-6 flex flex-col space-y-1">

            {{-- SUPER ADMIN: Tampilkan semua menu --}}
            @if($isAdmin)
                <a href="{{ route('conference_manager.index') }}"
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('conference_manager*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Conference Manager</span>
                </a>
                
                <a href="{{ route('editor.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('editor*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Editor</span>
                </a>
                
                <a href="{{ route('section_editor.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('section_editor*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Section Editor</span>
                </a>
                
                <a href="{{ route('reviewer.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('reviewer*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Reviewer</span>
                </a>
                
                <a href="{{ route('author.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('author*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Author</span>
                </a>
                
                <a href="{{ route('users.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('users*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Users</span>
                </a>

            {{-- USER BIASA: Tampilkan menu sesuai role yang dimiliki --}}
            @else
                {{-- CONFERENCE MANAGER: Hanya tampilkan Conference Manager --}}
                @if($isConferenceManager)
                <a href="{{ route('conference_manager.index') }}"
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('conference_manager*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Conference Manager</span>
                </a>
                @endif
                
                {{-- Editor --}}
                @if($isEditor)
                <a href="{{ route('editor.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('editor*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Editor</span>
                </a>
                @endif

                {{-- Section Editor (tidak bisa bersamaan dengan Editor) --}}
                @if($isSectionEditor && !$isEditor)
                <a href="{{ route('section_editor.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('section_editor*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Section Editor</span>
                </a>
                @endif

                {{-- Reviewer --}}
                @if($isReviewer)
                <a href="{{ route('reviewer.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('reviewer*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Reviewer</span>
                </a>
                @endif

                {{-- Author --}}
                @if($isAuthor)
                <a href="{{ route('author.index') }}" 
                    class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                    {{ Request::routeIs('author*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700' }}">
                    <span>Author</span>
                </a>
                @endif
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

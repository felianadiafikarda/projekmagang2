{{-- resources/views/layouts/navbar.blade.php --}}
<div class="flex justify-between items-center mb-8 bg-white p-4 shadow rounded-lg">

    {{-- Left Title --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            @yield('page_title', 'Dashboard')
        </h1>
        <p class="text-gray-600">
            @yield('page_subtitle', 'Manage submissions, assign reviewers & section editors')
        </p>
    </div>

    {{-- Right: Notifications + User Avatar --}}
    <div class="flex items-center space-x-5">

        {{-- Notification Icon --}}
        <div class="relative">
            <button id="notif-btn" class="relative">
                <svg width='40' height='40' viewBox='0 0 24 24'>
                    <rect width='40' height='40' fill='transparent' />
                    <g transform="matrix(1 0 0 1 12 12)">
                        <path fill="rgb(0,0,0)" transform="translate(-12, -12)"
                            d="M 12 2 C 11.172 2 10.5 2.672 10.5 3.5 L 10.5 4.1953125 
                                C 7.9131836 4.862095 6 7.2048001 6 10 L 6 16 L 4.4648438 17.15625 
                                C 4.174505 17.33988 3.999919 17.658114 4 18 C 4 18.552285 4.447715 19 5 19 
                                L 12 19 L 19 19 C 19.552285 19 20 18.552285 20 18 C 20.000081 17.658114 
                                19.825494 17.33988 19.537109 17.15625 L 18 16 L 18 10 
                                C 18 7.2048001 16.086816 4.862095 13.5 4.1953125 L 13.5 3.5 
                                C 13.5 2.672 12.828 2 12 2 z M 10 20 C 10 21.1 10.9 22 12 22 
                                C 13.1 22 14 21.1 14 20 L 10 20 z" />
                    </g>
                </svg>

                @if (($navbar_unread_count ?? 0) > 0)
                <span class="absolute top-1 right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                @endif
            </button>

            {{-- Dropdown --}}
            <div id="notif-dropdown"
                class="hidden absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-lg shadow-lg z-20">

                <div class="p-3 border-b font-semibold text-gray-700">
                    Notifications
                </div>

                {{-- LIST NOTIFIKASI --}}
                <div class="max-h-80 overflow-y-auto">

                    @forelse ($navbar_notifications as $notif)
                    <div class="p-3 border-b hover:bg-gray-50 {{ !$notif->is_read ? 'bg-blue-50' : '' }}">

                        @if(!$notif->is_read)
                        <span class="text-[10px] text-blue-600 font-semibold">NEW</span>
                        @endif

                        {{-- Judul --}}
                        <div class="font-semibold text-gray-800 text-sm">
                            {{ $notif->title }}
                        </div>

                        {{-- Pesan --}}
                        <div class="text-gray-600 text-xs mt-1">
                            {{ $notif->message }}
                        </div>

                        {{-- ACTION AREA --}}
                        @if ($notif->type === 'invite_review')
                        <div class="flex justify-end mt-4">
                            <a href="{{ route('reviewer.index') }}"
                                class="bg-gray-800 text-white text-xs px-4 py-2 rounded-full hover:bg-gray-900 transition">
                                View Detail
                            </a>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="p-3 text-gray-500 text-sm">
                        No notifications
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- USER AVATAR --}}
        @php $user = Auth::user(); @endphp

        <div
            class="w-10 h-10 bg-gray-800 text-white rounded-full flex items-center justify-center font-semibold uppercase">
            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
        </div>
    </div>
</div>

{{-- JS Toggle --}}
<script>
    document.getElementById('notif-btn').addEventListener('click', function() {
        document.getElementById('notif-dropdown').classList.toggle('hidden');
        fetch('{{ route("notifications.markRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    });
</script>
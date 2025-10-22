<aside class="w-64 bg-gray-800 text-gray-100 flex flex-col justify-between fixed inset-y-0">
    <div>
        <div class="bg-gray-900 px-6 py-5 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white tracking-wide">Journal Management</h2>
            <p class="text-sm text-gray-400">2026</p>
        </div>

        <nav class="mt-6 flex flex-col space-y-1">
            @foreach (App\Models\Role::orderByDesc('level')->get() as $role)
                @if (auth()->user()->canAccessRole($role->name))
                    <a href="{{ route($role->name . '.index') }}"
                        class="flex items-center gap-3 px-6 py-3 font-medium rounded-r-full
                            {{ Request::is($role->name . '*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-100' }}">
                        <span>{{ ucfirst($role->display_name) }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    <div class="border-t border-gray-700 p-4">
        <a href="{{ route('logout') }}" class="w-full flex items-center justify-center gap-2 bg-gray-700 text-gray-100 px-4 py-2 rounded-md hover:bg-gray-600 transition">
            Logout
        </a>
    </div>
</aside>

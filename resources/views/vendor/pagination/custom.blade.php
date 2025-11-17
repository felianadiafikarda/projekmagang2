@if ($paginator->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">

        {{-- Showing --}}
        <p class="text-sm text-gray-600">
            Showing
            {{ $paginator->firstItem() }}
            to
            {{ $paginator->lastItem() }}
            of
            {{ $paginator->total() }}
            users
        </p>

        {{-- Page Numbers --}}
        <div class="flex gap-2">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm opacity-50 cursor-not-allowed">
                    Previous
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                    Previous
                </a>
            @endif

            {{-- Page Links --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1 text-gray-500">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 bg-indigo-600 text-white rounded-md text-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                    Next
                </a>
            @else
                <button class="px-3 py-1 border border-gray-300 rounded-md text-sm opacity-50 cursor-not-allowed">
                    Next
                </button>
            @endif

        </div>
    </div>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Invitation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- HEADER WITH GRADIENT --}}
    <div class="bg-gray-900 text-white py-8 md:py-12 px-4 md:px-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl md:text-4xl font-light italic">Invitation to review</h1>
        </div>
    </div>

    {{-- MAIN CONTENT CARD --}}
    <div class="max-w-4xl mx-auto px-4 md:px-0 -mt-4 pb-8">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">

            {{-- PAPER TITLE --}}
            <div class="p-4 md:p-6 border-b border-gray-100">
                <h2 class="text-lg md:text-xl font-bold text-gray-900 leading-tight">
                    {{ $paper->judul }}
                </h2>
            </div>

            {{-- PAPER DETAILS --}}
            <div class="p-4 md:p-6">
                <div class="space-y-4">
                    {{-- Authors --}}
                    <div class="flex flex-col md:flex-row md:gap-4">
                        <div class="text-gray-500 md:w-32 flex-shrink-0 mb-1 md:mb-0">Authors</div>
                        <div class="text-gray-900">
                            {{ $paper->authors->map(fn($a) => $a->first_name . ' ' . $a->last_name)->implode(', ') ?: '-' }}
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Abstract --}}
                    <div class="flex flex-col md:flex-row md:gap-4">
                        <div class="text-gray-500 md:w-32 flex-shrink-0 mb-1 md:mb-0">Abstract</div>
                        <div class="text-gray-700">
                            <div id="abstractContainer">
                                <span id="abstractShort">{{ Str::limit($paper->abstrak, 300) }}</span>
                                <span id="abstractFull" class="hidden">{{ $paper->abstrak }}</span>
                                @if(strlen($paper->abstrak) > 300)
                                <button onclick="toggleAbstract()" id="seeMoreBtn" class="text-blue-600 hover:text-blue-800 hover:underline ml-1">
                                    See more
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Keywords --}}
                    @if($paper->keywords)
                    <hr class="border-gray-100">
                    <div class="flex flex-col md:flex-row md:gap-4">
                        <div class="text-gray-500 md:w-32 flex-shrink-0 mb-1 md:mb-0">Keywords</div>
                        <div class="text-gray-700">{{ $paper->keywords }}</div>
                    </div>
                    @endif

                    <hr class="border-gray-100">

                    {{-- Due Date --}}
                    <div class="flex flex-col md:flex-row md:gap-4">
                        <div class="text-gray-500 md:w-32 flex-shrink-0 mb-1 md:mb-0">Due date</div>
                        <div class="text-gray-900">
                            @php
                                $deadlineDate = \Carbon\Carbon::parse($deadline);
                                $daysLeft = (int) now()->startOfDay()->diffInDays($deadlineDate->startOfDay(), false);
                            @endphp
                            @if($daysLeft > 0)
                                in {{ $daysLeft }} days ({{ $deadlineDate->format('d M Y') }})
                            @elseif($daysLeft == 0)
                                Today ({{ $deadlineDate->format('d M Y') }})
                            @else
                                Overdue ({{ $deadlineDate->format('d M Y') }})
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            @if($status === 'assigned')
            <div class="px-4 md:px-6 py-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <form action="{{ route('reviewer.invitation.decline', $token) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" 
                        class="w-full sm:w-auto px-6 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition font-medium">
                        Decline
                    </button>
                </form>

                <form action="{{ route('reviewer.invitation.accept', $token) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" 
                        class="w-full sm:w-auto px-6 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 transition font-medium">
                        Accept
                    </button>
                </form>
            </div>
            @else
            <div class="px-4 md:px-6 py-4 border-t border-gray-100">
                <p class="text-gray-600 text-center text-sm md:text-base">
                    @if($status === 'accept_to_review')
                    You have accepted this invitation. Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to start your review.
                    @elseif($status === 'decline_to_review')
                    You have declined this invitation. Thank you for your response.
                    @elseif($status === 'completed')
                    You have completed this review. Thank you for your contribution.
                    @endif
                </p>
            </div>
            @endif

        </div>
    </div>

    <script>
        function toggleAbstract() {
            const shortText = document.getElementById('abstractShort');
            const fullText = document.getElementById('abstractFull');
            const btn = document.getElementById('seeMoreBtn');
            
            if (fullText.classList.contains('hidden')) {
                shortText.classList.add('hidden');
                fullText.classList.remove('hidden');
                btn.textContent = 'See less';
            } else {
                shortText.classList.remove('hidden');
                fullText.classList.add('hidden');
                btn.textContent = 'See more';
            }
        }
    </script>

</body>
</html>

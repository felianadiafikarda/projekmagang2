<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Author Registration</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(to bottom, #D9D9D9 0%, #38383C 100%);
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-lg shadow-md p-8 w-96 text-center">
    <h2 class="text-2xl font-bold mb-6">Author Registration</h2>

    {{-- Success message --}}
    @if(session('success'))
    <div class="bg-green-100 text-green-600 p-2 rounded mb-4 text-sm">
      {{ session('success') }}
    </div>
    @endif

    {{-- Error message --}}
    @if ($errors->any())
    <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm">
      <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form action="{{ route('register.author.post') }}" method="POST" class="space-y-4 text-left">
      @csrf

      <!-- Full Name -->
      <div>
        <label for="name" class="block text-sm font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
        <input
          type="text"
          id="name"
          name="name"
          value="{{ old('name') }}"
          required
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
        <input
          type="email"
          id="email"
          name="email"
          value="{{ old('email') }}"
          required
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        @error('email')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
        <input
          type="password"
          id="password"
          name="password"
          required
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        @error('password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div>
        <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirm Password <span class="text-red-500">*</span></label>
        <input
          type="password"
          id="password_confirmation"
          name="password_confirmation"
          required
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Institution/Affiliation -->
      <div>
        <label for="institution" class="block text-sm font-medium mb-1">Institution/Affiliation <span class="text-red-500">*</span></label>
        <input
          type="text"
          id="institution"
          name="institution"
          value="{{ old('institution') }}"
          required
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        @error('institution')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Phone Number -->
      <div>
        <label for="phone" class="block text-sm font-medium mb-1">Phone Number</label>
        <input
          type="tel"
          id="phone"
          name="phone"
          value="{{ old('phone') }}"
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
        @error('phone')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Buttons -->
      <div class="pt-2 flex gap-3">
        <button
          type="button"
          onclick="window.history.back()"
          class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 rounded-md transition-all shadow-sm">
          Cancel
        </button>
        <button
          type="submit"
          class="flex-1 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 rounded-md transition-all shadow-sm">
          Register
        </button>
      </div>
    </form>

    <!-- Already have an account -->
    <div class="mt-4 text-sm">
      <p>Already have an account?
        <a href="/login" class="text-indigo-500 hover:underline font-medium">Login</a>
      </p>
    </div>
  </div>

</body>

</html>
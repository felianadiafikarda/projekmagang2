<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(to bottom, #D9D9D9 0%, #38383C 100%);
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-lg shadow-md p-8 w-96 text-center">
    <h2 class="text-2xl font-bold mb-6">Reset Password</h2>

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

    <form action="{{ route('reset.password.post') }}" method="POST" class="space-y-4 text-left">
      @csrf

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

      <!-- New Password -->
      <div>
        <label for="password" class="block text-sm font-medium mb-1">New Password <span class="text-red-500">*</span></label>
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

      <!-- Confirm New Password -->
      <div>
        <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirm New Password <span class="text-red-500">*</span></label>
        <input
          type="password"
          id="password_confirmation"
          name="password_confirmation"
          required
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Button -->
      <div class="pt-2">
        <button
          type="submit"
          class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 rounded-md transition-all shadow-sm">
          Reset Password
        </button>
      </div>
    </form>

    <!-- Back to Login -->
    <div class="mt-4 text-sm">
      <p>
        <a href="/login" class="text-indigo-500 hover:underline font-medium">Back to Login</a>
      </p>
    </div>

    <!-- Back link -->
    <div class="mt-4">
      <a href="/" class="text-indigo-500 hover:underline text-sm flex items-center justify-center space-x-1">
        <span>&larr;</span>
        <span>Back to Main Website</span>
      </a>
    </div>
  </div>

</body>

</html>
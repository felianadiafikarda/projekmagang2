<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verify OTP Code</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(to bottom, #D9D9D9 0%, #38383C 100%);
    }
  </style>
</head>

<body class="flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-lg shadow-md p-8 w-96 text-center">
    <h2 class="text-2xl font-bold mb-6">Verify OTP Code</h2>

    <p class="text-sm text-gray-600 mb-6 text-left">
      We have sent an OTP code to your email <strong>{{ session('email') ?? 'address' }}</strong>. Please enter the code below.
    </p>

    {{-- Success message --}}
    @if(session('success'))
    <div class="bg-green-100 text-green-600 p-2 rounded mb-4 text-sm">
      {{ session('success') }}
    </div>
    @endif

    {{-- Error message --}}
    @if ($errors->any())
    <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm">
      {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('verify.otp.post') }}" method="POST" class="space-y-4 text-left">
      @csrf

      <!-- OTP Code -->
      <div>
        <label for="otp" class="block text-sm font-medium mb-1">OTP Code <span class="text-red-500">*</span></label>
        <input
          type="text"
          id="otp"
          name="otp"
          maxlength="6"
          required
          placeholder="Enter 6-digit OTP code"
          class="w-full border border-gray-400 rounded-md p-2 text-center text-2xl tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-400">
        @error('otp')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Button -->
      <div class="pt-2">
        <button
          type="submit"
          class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 rounded-md transition-all shadow-sm">
          Verify Code
        </button>
      </div>
    </form>

    <!-- Resend Code -->
    <div class="mt-4 text-sm">
      <p class="text-gray-600">
        Didn't receive the code? 
        <a href="#" onclick="event.preventDefault(); document.getElementById('resend-form').submit();" class="text-indigo-500 hover:underline font-medium">Resend</a>
      </p>
      <form id="resend-form" action="{{ route('forgot.password.post') }}" method="POST" class="hidden">
        @csrf
      </form>
    </div>

    <!-- Back -->
    <div class="mt-4 text-sm">
      <p>
        <a href="/forgot-password" class="text-indigo-500 hover:underline font-medium">Change Email</a>
      </p>
    </div>
  </div>

</body>

</html>

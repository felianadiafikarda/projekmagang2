<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: linear-gradient(to bottom, #D9D9D9 0%, #38383C 100%);
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen">

  <div class="bg-white rounded-lg shadow-md p-8 w-80 text-center">
    <h2 class="text-2xl font-bold mb-6">Login</h2>

    <form action="#" method="POST" class="space-y-4 text-left">
      <!-- Username -->
      <div>
        <label for="username" class="block text-sm font-medium mb-1">Username</label>
        <input 
          type="text" 
          id="username" 
          name="username" 
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium mb-1">Password</label>
        <input 
          type="password" 
          id="password" 
          name="password" 
          class="w-full border border-gray-400 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
      </div>

      <!-- Tombol Login -->
      <div class="pt-2">
        <button 
          type="submit"
          class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 rounded-md transition-all shadow-sm">
          Login
        </button>
      </div>

      <!-- Lupa Password -->
      <div class="text-right mt-1">
        <a href="#" class="text-sm text-indigo-500 hover:underline">Lupa Password?</a>
      </div>
    </form>

    <!-- Belum punya akun -->
    <div class="mt-4 text-sm">
      <p>Belum punya akun? 
        <a href="/author/regis" class="text-indigo-500 hover:underline font-medium">Registrasi</a>
      </p>
    </div>

    <!-- Link kembali -->
    <div class="mt-4">
      <a href="/" class="text-indigo-500 hover:underline text-sm flex items-center justify-center space-x-1">
        <span>&larr;</span>
        <span>Kembali ke Website Utama</span>
      </a>
    </div>
  </div>

</body>
</html>

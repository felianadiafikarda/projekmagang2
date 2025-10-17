<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Author Baru</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#D9D9D9] to-[#38383C] min-h-screen flex items-center justify-center font-sans">

  <!-- Card Form -->
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-8">
    <h1 class="text-2xl font-bold text-center mb-6">Registrasi Author Baru</h1>

    <form action="#" method="POST" class="space-y-5">
      <!-- Nama Lengkap -->
      <div>
        <label class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="name" required class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-gray-400 focus:outline-none">
      </div>

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" required class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-gray-400 focus:outline-none">
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
        <input type="password" name="password" required class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-gray-400 focus:outline-none">
      </div>

      <!-- Konfirmasi Password -->
      <div>
        <label class="block text-sm font-medium mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
        <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-gray-400 focus:outline-none">
      </div>

      <!-- Institusi/Afiliasi -->
      <div>
        <label class="block text-sm font-medium mb-1">Institusi/Afiliasi <span class="text-red-500">*</span></label>
        <input type="text" name="affiliation" required class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-gray-400 focus:outline-none">
      </div>

      <!-- Nomor Telepon -->
      <div>
        <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
        <input type="tel" name="phone" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-gray-400 focus:outline-none">
      </div>

      <!-- Tombol -->
      <div class="flex space-x-4 mt-6">
  <!-- Tombol Batal -->
  <button 
    type="button" 
    class="px-6 py-2 rounded-lg text-white font-medium bg-gray-300 hover:bg-gray-400 transition-all shadow-sm">
    Batal
  </button>

  <!-- Tombol Daftar (gradasi hijau ke abu-abu) -->
  <button 
    type="submit" 
    class="px-8 py-2 rounded-lg text-white font-semibold bg-gradient-to-r from-emerald-600 to-gray-400 hover:from-emerald-700 hover:to-gray-500 shadow-md transition-all">
    Daftar
  </button>
</div>


      <!-- Link login -->
      <p class="text-center text-sm mt-4">
        sudah punya akun? 
        <a href="/login" class="text-blue-600 hover:underline">Login</a>
      </p>
    </form>
  </div>

</body>
</html>

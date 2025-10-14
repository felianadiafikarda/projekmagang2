<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kirim Artikel</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

  <!-- HEADER -->
  <header class="bg-gradient-to-r from-[#D9D9D9] to-[#38383C] text-white py-4 shadow-md">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
      <h1 class="text-lg font-semibold">Jurnal Manajemen Sistem</h1>
      <div class="flex items-center space-x-6 text-sm">
        <span>Selamat datang, admin123</span>
        <a href="#" class="hover:underline">Lihat website</a>
        <a href="#" class="text-red-300 hover:text-red-500">Logout</a>
      </div>
    </div>
  </header>

  <!-- KONTEN UTAMA -->
  <div class="flex min-h-screen">
    <!-- SIDEBAR (warna abu gelap tanpa gradiasi) -->
    <aside class="w-64 bg-[#2F2F33] text-white p-6 space-y-4">
      <h2 class="text-lg font-semibold mb-4">Menu</h2>
      <ul class="space-y-2">
        <li><a href="#" class="block hover:bg-[#D9D9D9] p-2 rounded transition">Dashboard</a></li>
        <li><a href="#" class="block hover:bg-[#404045] p-2 rounded transition">HomeSelection</a></li>
        <li><a href="#" class="block bg-[#505056] p-2 rounded">Author</a></li>
        <li><a href="#" class="block hover:bg-[#404045] p-2 rounded transition">Reviewer</a></li>
        <li><a href="#" class="block hover:bg-[#404045] p-2 rounded transition">Editor</a></li>
        <li><a href="#" class="block hover:bg-[#404045] p-2 rounded transition">Conference</a></li>
      </ul>
    </aside>

    <!-- FORM KIRIM ARTIKEL -->
    <main class="flex-1 bg-white p-10">
      <h2 class="text-2xl font-semibold mb-6">Kirim Artikel</h2>

      <form class="space-y-8">

        <!-- 🧑 AUTHOR 1 -->
        <div class="border p-4 rounded-lg shadow-sm">
          <h3 class="font-semibold mb-3">Author 1 <span class="text-red-500">*</span></h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1">First Name <span class="text-red-500">*</span></label>
              <input type="text" required class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Last Name <span class="text-red-500">*</span></label>
              <input type="text" required class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Email <span class="text-red-500">*</span></label>
              <input type="email" required class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Country/Region <span class="text-red-500">*</span></label>
              <input type="text" required class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div class="md:col-span-2">
              <label class="block mb-1">Affiliation <span class="text-red-500">*</span></label>
              <input type="text" required class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div class="md:col-span-2">
              <label class="block mb-1">Web Page</label>
              <input type="url" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
          </div>
        </div>

        <!-- 👥 AUTHOR 2 -->
        <div class="border p-4 rounded-lg shadow-sm">
          <h3 class="font-semibold mb-3">Author 2 (Opsional)</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1">First Name</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Last Name</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Email</label>
              <input type="email" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Country/Region</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div class="md:col-span-2">
              <label class="block mb-1">Affiliation</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div class="md:col-span-2">
              <label class="block mb-1">Web Page</label>
              <input type="url" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
          </div>
        </div>

        <!-- 👥 AUTHOR 3 -->
        <div class="border p-4 rounded-lg shadow-sm">
          <h3 class="font-semibold mb-3">Author 3 (Opsional)</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1">First Name</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Last Name</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Email</label>
              <input type="email" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div>
              <label class="block mb-1">Country/Region</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div class="md:col-span-2">
              <label class="block mb-1">Affiliation</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
            <div class="md:col-span-2">
              <label class="block mb-1">Web Page</label>
              <input type="url" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>
          </div>
        </div>

        <!-- 📄 FORM ARTIKEL -->
        <div class="border p-4 rounded-lg shadow-sm">
          <h3 class="font-semibold mb-3">Detail Artikel</h3>
          <div class="space-y-4">
            <div>
              <label class="block mb-1">Judul Artikel <span class="text-red-500">*</span></label>
              <input type="text" required class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>

            <div>
              <label class="block mb-1">Abstrak</label>
              <textarea rows="4" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400"></textarea>
            </div>

            <div>
              <label class="block mb-1">Kata Kunci</label>
              <input type="text" class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-gray-400">
            </div>

            <div>
              <label class="block mb-1">Upload File Artikel (PDF/DOCX) <span class="text-red-500">*</span></label>
              <input type="file" accept=".pdf,.doc,.docx" required class="w-full border rounded p-2 bg-gray-50">
            </div>
          </div>
        </div>

        <!-- 🔘 BUTTONS -->
        <div class="flex space-x-4">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Artikel</button>
          <button type="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset Form</button>
        </div>
      </form>
    </main>
  </div>
</body>
</html>

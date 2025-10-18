<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Author - Jurnal Manajemen Sistem</title>
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

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#2F2F33] text-white p-6 space-y-4">
      <h2 class="text-lg font-semibold mb-4">Menu</h2>
      <ul class="space-y-2">
        <li><a href="#" class="block hover:bg-[#D9D9D9] p-2 rounded transition">Dashboard</a></li>
        <li><a href="#" class="block bg-[#505056] p-2 rounded">Author</a></li>
      </ul>
    </aside>

    <!-- AREA KONTEN -->
    <main class="flex-1 bg-white p-10">

      <!-- TAB NAV -->
      <div class="flex space-x-8 border-b mb-6">
        <button id="tabKirim" class="pb-2 border-b-2 border-teal-400 text-teal-500 font-semibold">Kirim Artikel</button>
        <button id="tabRevisi" class="pb-2 text-gray-600 hover:text-teal-500">Revisi Artikel</button>
      </div>

      <!-- ===================== KIRIM ARTIKEL ===================== -->
      <section id="kirimForm" class="space-y-8">
        <h2 class="text-2xl font-semibold mb-6">Kirim Artikel</h2>
        <form class="space-y-8">

          <!-- AUTHOR 1 -->
          <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Author 1 <span class="text-red-500">*</span></h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block mb-1">First Name <span class="text-red-500">*</span></label>
                <input type="text" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
              </div>
              <div>
                <label class="block mb-1">Last Name <span class="text-red-500">*</span></label>
                <input type="text" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
              </div>
              <div>
                <label class="block mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
              </div>
              <div>
                <label class="block mb-1">Country/Region <span class="text-red-500">*</span></label>
                <input type="text" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
              </div>
              <div class="md:col-span-2">
                <label class="block mb-1">Affiliation <span class="text-red-500">*</span></label>
                <input type="text" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
              </div>
            </div>
          </div>

          <!-- DETAIL ARTIKEL -->
          <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Detail Artikel</h3>
            <div class="space-y-4">
              <div>
                <label class="block mb-1">Judul Artikel <span class="text-red-500">*</span></label>
                <input type="text" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
              </div>
              <div>
                <label class="block mb-1">Abstrak</label>
                <textarea rows="4" class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400"></textarea>
              </div>
              <div>
                <label class="block mb-1">Upload File Artikel (PDF/DOCX) <span class="text-red-500">*</span></label>
                <input type="file" accept=".pdf,.doc,.docx" required class="w-full border rounded p-2 bg-gray-50">
              </div>
            </div>
          </div>

          <!-- BUTTON -->
          <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kirim Artikel</button>
            <button type="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</button>
          </div>
        </form>
      </section>

      <!-- ===================== REVISI ARTIKEL ===================== -->
      <section id="revisiForm" class="hidden space-y-8">
        <h2 class="text-2xl font-semibold mb-6">Revisi Artikel</h2>
        <form class="space-y-8">
          <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Informasi Artikel</h3>
            <div class="space-y-4">
              <div>
                <label class="block mb-1">Judul Artikel <span class="text-red-500">*</span></label>
                <input type="text" placeholder="Masukkan judul artikel yang direvisi" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
              </div>
              <div>
                <label class="block mb-1">Nomor Artikel / ID <span class="text-red-500">*</span></label>
                <input type="text" placeholder="Masukkan nomor ID artikel" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
              </div>
            </div>
          </div>

          <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Upload File Revisi</h3>
            <div class="space-y-4">
              <div>
                <label class="block mb-1">Upload File Revisi (PDF/DOCX) <span class="text-red-500">*</span></label>
                <input type="file" accept=".pdf,.doc,.docx" required class="w-full border rounded p-2 bg-gray-50">
              </div>
              <div>
                <label class="block mb-1">Catatan Revisi</label>
                <textarea rows="5" placeholder="Tuliskan perubahan atau perbaikan..." class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400"></textarea>
              </div>
            </div>
          </div>

          <div class="flex space-x-4">
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">Kirim Revisi</button>
            <button type="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</button>
          </div>
        </form>
      </section>
    </main>
  </div>

  <!-- SCRIPT TAB SWITCH -->
  <script>
    const tabKirim = document.getElementById("tabKirim");
    const tabRevisi = document.getElementById("tabRevisi");
    const kirimForm = document.getElementById("kirimForm");
    const revisiForm = document.getElementById("revisiForm");

    tabKirim.addEventListener("click", () => {
      kirimForm.classList.remove("hidden");
      revisiForm.classList.add("hidden");
      tabKirim.classList.add("border-b-2", "border-teal-400", "text-teal-500", "font-semibold");
      tabRevisi.classList.remove("border-b-2", "border-teal-400", "text-teal-500", "font-semibold");
      tabRevisi.classList.add("text-gray-600");
    });

    tabRevisi.addEventListener("click", () => {
      revisiForm.classList.remove("hidden");
      kirimForm.classList.add("hidden");
      tabRevisi.classList.add("border-b-2", "border-teal-400", "text-teal-500", "font-semibold");
      tabKirim.classList.remove("border-b-2", "border-teal-400", "text-teal-500", "font-semibold");
      tabKirim.classList.add("text-gray-600");
    });
  </script>
</body>
</html>

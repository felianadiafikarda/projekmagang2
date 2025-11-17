@extends('layouts.app')

@section('page_title', 'Kirim Artikel')
@section('page_subtitle', 'Ajukan artikel baru untuk ditinjau dan dipublikasikan')

@section('content')

<!-- TAB NAV -->
<div class="flex space-x-8 border-b mb-6">
  <button id="tabKirim" class="pb-2 border-b-2 border-teal-400 text-teal-500 font-semibold">
    Kirim Artikel
  </button>
  <button id="tabRevisi" class="pb-2 text-gray-600 hover:text-teal-500">
    Revisi Artikel
  </button>
</div>

<!-- ===================================== -->
<!-- =========== KIRIM ARTIKEL =========== -->
<!-- ===================================== -->
<section id="kirimForm" class="space-y-8">
  <h2 class="text-2xl font-semibold mb-6">Kirim Artikel</h2>

  <form class="space-y-8">

    <!-- DETAIL ARTIKEL -->
    <div class="border p-4 rounded-lg shadow-sm">
      <h3 class="font-semibold mb-3">Detail Artikel</h3>

      <div class="space-y-4">

        <!-- JUDUL -->
        <div>
          <label class="block mb-1">Judul Artikel <span class="text-red-500">*</span></label>
          <input type="text" required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
        </div>

        <!-- ABSTRAK -->
        <div>
          <label class="block mb-1">Abstrak</label>
          <textarea rows="4" class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400"></textarea>
        </div>

        <!-- KATA KUNCI -->
        <div>
          <label class="block mb-1">Kata Kunci <span class="text-red-500">*</span></label>
          <input type="text"
            placeholder="contoh: jaringan, prediksi, machine learning, data mining"
            required
            class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
          <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma (,)</p>
        </div>

      </div>
    </div>


    <!-- AUTHORS SECTION (DIPINDAH KE BAWAH KATA KUNCI) -->
    <div class="border p-4 rounded-lg shadow-sm">
      <h3 class="font-semibold mb-3">Authors <span class="text-red-500">*</span></h3>

      <table class="w-full text-left border mb-4" id="authorsTable">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2">Primary</th>
            <th class="p-2">Email</th>
            <th class="p-2">First Name</th>
            <th class="p-2">Last Name</th>
            <th class="p-2">Organization</th>
            <th class="p-2">Country</th>
            <th class="p-2">Action</th>
          </tr>
        </thead>

        <tbody>

          <!-- DEFAULT AUTHOR -->
          <tr>
            <td class="p-2 text-center">
              <input type="radio" name="primary" checked>
            </td>

            <td class="p-2">
              <input type="email" required class="border rounded p-1 w-full">
            </td>

            <td class="p-2">
              <input type="text" required class="border rounded p-1 w-full">
            </td>

            <td class="p-2">
              <input type="text" required class="border rounded p-1 w-full">
            </td>

            <td class="p-2">
              <input type="text" required class="border rounded p-1 w-full">
            </td>

            <!-- Negara Dropdown -->
            <td class="p-2">
              <select class="border rounded p-1 w-full">
                <option value="">-- Pilih Negara --</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Singapore">Singapore</option>
                <option value="Thailand">Thailand</option>
                <option value="Philippines">Philippines</option>
                <option value="Vietnam">Vietnam</option>
                <option value="China">China</option>
                <option value="Japan">Japan</option>
                <option value="South Korea">South Korea</option>
                <option value="India">India</option>
                <option value="Australia">Australia</option>
                <option value="United States">United States</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="France">France</option>
                <option value="Germany">Germany</option>
              </select>
            </td>

            <td class="p-2 text-center">
              <button type="button" class="text-red-500 removeAuthor">✖</button>
            </td>
          </tr>

        </tbody>
      </table>

      <button type="button" id="addAuthor"
        class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
        + Add Author
      </button>
    </div>


    <!-- UPLOAD FILE -->
    <div class="border p-4 rounded-lg shadow-sm">
      <h3 class="font-semibold mb-3">Upload File</h3>

      <div class="space-y-4">
        <label class="block mb-1">Upload File Artikel (PDF/DOCX) <span class="text-red-500">*</span></label>
        <input type="file" accept=".pdf,.doc,.docx" required class="w-full border rounded p-2 bg-gray-50">
      </div>
    </div>


    <!-- BUTTON -->
    <div class="flex space-x-4">
      <button type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Kirim Artikel
      </button>

      <button type="reset"
        class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
        Reset
      </button>
    </div>

  </form>
</section>


<!-- ===================================== -->
<!-- ========= REVISI ARTIKEL =========== -->
<!-- ===================================== -->
<section id="revisiForm" class="hidden space-y-8">
  <h2 class="text-2xl font-semibold mb-6">Revisi Artikel</h2>

  <form class="space-y-8">

    <div class="border p-4 rounded-lg shadow-sm">
      <h3 class="font-semibold mb-3">Informasi Artikel</h3>

      <div class="space-y-4">
        <div>
          <label class="block mb-1">Judul Artikel <span class="text-red-500">*</span></label>
          <input type="text" placeholder="Masukkan judul artikel yang direvisi"
            required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
        </div>

        <div>
          <label class="block mb-1">Nomor Artikel / ID <span class="text-red-500">*</span></label>
          <input type="text" placeholder="Masukkan nomor ID artikel"
            required class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
        </div>
      </div>
    </div>


    <div class="border p-4 rounded-lg shadow-sm">
      <h3 class="font-semibold mb-3">Upload File Revisi</h3>

      <div class="space-y-4">
        <label class="block mb-1">Upload File Revisi (PDF/DOCX) <span class="text-red-500">*</span></label>
        <input type="file" accept=".pdf,.doc,.docx"
          required class="w-full border rounded p-2 bg-gray-50">

        <label class="block mb-1">Catatan Revisi</label>
        <textarea rows="5" placeholder="Tuliskan perubahan atau perbaikan..."
          class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400"></textarea>
      </div>
    </div>


    <div class="flex space-x-4">
      <button type="submit"
        class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
        Kirim Revisi
      </button>

      <button type="reset"
        class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
        Reset
      </button>
    </div>

  </form>
</section>


<!-- ===================================== -->
<!-- ======== SCRIPT SECTION ============= -->
<!-- ===================================== -->
<script>

// ===== TAB SWITCH =====
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


// ===== AUTHORS TABLE DYNAMIC =====
const authorsTable = document.querySelector("#authorsTable tbody");
const addAuthorBtn = document.getElementById("addAuthor");

addAuthorBtn.addEventListener("click", () => {
  const row = document.createElement("tr");

  row.innerHTML = `
    <td class="p-2 text-center"><input type="radio" name="primary"></td>

    <td class="p-2"><input type="email" required class="border rounded p-1 w-full"></td>

    <td class="p-2"><input type="text" required class="border rounded p-1 w-full"></td>

    <td class="p-2"><input type="text" required class="border rounded p-1 w-full"></td>

    <td class="p-2"><input type="text" required class="border rounded p-1 w-full"></td>

    <td class="p-2">
      <select class="border rounded p-1 w-full">
        <option value="">-- Pilih Negara --</option>
        <option value="Indonesia">Indonesia</option>
        <option value="Malaysia">Malaysia</option>
        <option value="Singapore">Singapore</option>
        <option value="Thailand">Thailand</option>
        <option value="Philippines">Philippines</option>
        <option value="Vietnam">Vietnam</option>
        <option value="China">China</option>
        <option value="Japan">Japan</option>
        <option value="South Korea">South Korea</option>
        <option value="India">India</option>
        <option value="Australia">Australia</option>
        <option value="United States">United States</option>
        <option value="United Kingdom">United Kingdom</option>
        <option value="France">France</option>
        <option value="Germany">Germany</option>
      </select>
    </td>

    <td class="p-2 text-center">
      <button type="button" class="text-red-500 removeAuthor">✖</button>
    </td>
  `;

  authorsTable.appendChild(row);
});


document.addEventListener("click", (e) => {
  if (e.target.classList.contains("removeAuthor")) {
    const rows = authorsTable.querySelectorAll("tr");
    if (rows.length > 1) {
      e.target.closest("tr").remove();
    } else {
      alert("Minimal harus ada 1 author.");
    }
  }
});

</script>

@endsection

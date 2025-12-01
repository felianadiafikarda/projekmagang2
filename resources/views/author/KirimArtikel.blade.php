@extends('layouts.app')

@section('page_title', 'Send Article')
@section('page_subtitle', 'Submit new articles for review and publication')

@section('content')


<div class="flex space-x-8 border-b mb-6">
    <button id="tabKirim" class="pb-2 border-b-2 border-teal-400 text-teal-500 font-semibold">
        Send Article
    </button>
    <button id="tabRevisi" class="pb-2 text-gray-600 hover:text-teal-500">
        Article Revision
    </button>
</div>


<section id="kirimForm" class="space-y-8">
    <h2 class="text-2xl font-semibold mb-6">Send Article</h2>

    <form action="{{ route('author.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf


        <!-- DETAIL ARTIKEL -->
        <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Article Details</h3>

            <div class="space-y-4">

                <!-- JUDUL -->
                <div>
                    <label class="block mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
                    @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ABSTRAK -->
                <div>
                    <label class="block mb-1">Abstract<span class="text-red-500">*</span></label>
                    <textarea name="abstrak" rows="4" required
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400"></textarea>
                    @error('abstrak')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

                <!-- KATA KUNCI -->
                <div>
                    <label class="block mb-1">Keywords<span class="text-red-500">*</span></label>
                    <input type="text" name="keywords"
                        placeholder="Examples: networks, prediction, machine learning, data mining" required
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
                    <p class="text-xs text-gray-500 mt-1">Separate with commas(,)</p>
                    @error('keywords')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>


        <!-- AUTHORS SECTION (DIPINDAH KE BAWAH KATA KUNCI) -->
        <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Authors <span class="text-red-500">*</span></h3>

            @error('authors')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
            @enderror

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

                    @php
                    $authors = old('authors', [
                    [
                    'email' => $user->email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'organization' => $user->affiliation,
                    'country' => '',
                    ]
                    ]);

                    @endphp

                    @foreach ($authors as $i => $author)
                    <tr>
                        <td class="p-2 text-center">
                            <input type="radio" name="primary" value="{{ $i }}" required
                                {{ old('primary', 0) == $i ? 'checked' : '' }}>
                        </td>

                        <td class="p-2">
                            <input type="email" name="authors[{{ $i }}][email]" value="{{ $author['email'] ?? '' }}"
                                required class="border rounded p-1 w-full" @if($i==0) readonly @endif
                                style="{{ $i == 0 ? 'background:#e5e7eb; cursor:not-allowed;' : '' }}">

                            @error("authors.$i.email")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </td>

                        <td class="p-2">
                            <input type="text" name="authors[{{ $i }}][first_name]"
                                value="{{ $author['first_name'] ?? '' }}" required
                                class="border rounded p-1 w-full @error(" authors.$i.first_name") border-red-500
                                @enderror">
                            @error("authors.$i.first_name")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </td>

                        <td class="p-2">
                            <input type="text" name="authors[{{ $i }}][last_name]"
                                value="{{ $author['last_name'] ?? '' }}" required
                                class="border rounded p-1 w-full @error(" authors.$i.last_name") border-red-500
                                @enderror">
                            @error("authors.$i.last_name")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </td>

                        <td class="p-2">
                            <input type="text" name="authors[{{ $i }}][organization]"
                                value="{{ $author['organization'] ?? '' }}" required
                                class="border rounded p-1 w-full @error(" authors.$i.organization") border-red-500
                                @enderror">
                            @error("authors.$i.organization")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </td>

                        <td class="p-2">
                            <select name="authors[{{ $i }}][country]" required class="border rounded p-1 w-full @error("
                                authors.$i.country") border-red-500 @enderror">
                                <option value="">-- Pilih Negara --</option>

                                @php
                                $negaraList = [
                                "Indonesia", "Malaysia", "Singapore", "Thailand", "Philippines",
                                "Vietnam", "China", "Japan", "South Korea", "India", "Australia",
                                "United States", "United Kingdom", "France", "Germany"
                                ];
                                @endphp

                                @foreach ($negaraList as $n)
                                <option value="{{ $n }}" {{ ($author['country'] ?? '') == $n ? 'selected' : '' }}>
                                    {{ $n }}
                                </option>
                                @endforeach
                            </select>

                            @error("authors.$i.country")
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </td>

                        <td class="p-2 text-center">
                            <button type="button" class="text-red-500 removeAuthor">✖</button>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

            <button type="button" id="addAuthor" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                + Add Author
            </button>
        </div>


        <!-- UPLOAD FILE -->
        <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Upload File</h3>

            <div class="space-y-4">
                <label class="block mb-1">Upload File(PDF/DOC/DOCX) <span class="text-red-500">*</span></label>
                <input type="file" name="file_artikel" accept=".pdf,.doc,.docx" required
                    class="w-full border rounded p-2 bg-gray-50">
                @error('file_artikel')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>


        <!-- BUTTON -->
        <div class="flex space-x-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Submit Article
            </button>

            <button type="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                Reset
            </button>
        </div>

    </form>
</section>





<section id="revisiForm" class="hidden space-y-8">
    <h2 class="text-2xl font-semibold mb-6">Revisi Artikel</h2>

    <form class="space-y-8">

        <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Informasi Artikel</h3>

            <div class="space-y-4">
                <div>
                    <label class="block mb-1">Judul Artikel <span class="text-red-500">*</span></label>
                    <input type="text" placeholder="Masukkan judul artikel yang direvisi" required
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
                </div>

                <div>
                    <label class="block mb-1">Nomor Artikel / ID <span class="text-red-500">*</span></label>
                    <input type="text" placeholder="Masukkan nomor ID artikel" required
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
                </div>
            </div>
        </div>


        <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Upload File Revisi</h3>

            <div class="space-y-4">
                <label class="block mb-1">Upload File Revisi (PDF/DOCX) <span class="text-red-500">*</span></label>
                <input type="file" accept=".pdf,.doc,.docx" required class="w-full border rounded p-2 bg-gray-50">

                <label class="block mb-1">Catatan Revisi</label>
                <textarea rows="5" placeholder="Tuliskan perubahan atau perbaikan..."
                    class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400"></textarea>
            </div>
        </div>


        <div class="flex space-x-4">
            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
                Kirim Revisi
            </button>

            <button type="reset" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                Reset
            </button>
        </div>

    </form>
</section>



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

    const index = authorsTable.querySelectorAll("tr").length; // hitung jumlah row

    const row = document.createElement("tr");

    row.innerHTML = `
    <td class="p-2 text-center">
        <input type="radio" name="primary" value="${index}">
    </td>

    <td class="p-2">
        <input type="email" name="authors[${index}][email]" required class="border rounded p-1 w-full">
    </td>

    <td class="p-2">
        <input type="text" name="authors[${index}][first_name]" required class="border rounded p-1 w-full">
    </td>

    <td class="p-2">
        <input type="text" name="authors[${index}][last_name]" required class="border rounded p-1 w-full">
    </td>

    <td class="p-2">
        <input type="text" name="authors[${index}][organization]" required class="border rounded p-1 w-full">
    </td>

    <td class="p-2">
        <select name="authors[${index}][country]" class="border rounded p-1 w-full">
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
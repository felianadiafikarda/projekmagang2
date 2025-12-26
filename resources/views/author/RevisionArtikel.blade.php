@extends('layouts.app')

@section('page_title', 'Article Revision')
@section('page_subtitle', 'Submit revised article based on reviewer feedback')

@section('content')

<section class="space-y-8">

    <form action="{{ route('author.revision.store', $paper->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- DETAIL ARTIKEL -->
        <div class="border p-4 rounded-lg shadow-sm">
            <h3 class="font-semibold mb-3">Article Details</h3>

            <div class="space-y-4">

                <!-- JUDUL -->
                <div>
                    <label class="block mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ $paper->judul }}" required
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
                    @error('judul')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ABSTRAK -->
                <div>
                    <label class="block mb-1">Abstract<span class="text-red-500">*</span></label>
                    <textarea name="abstrak" rows="4" required
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">{{ $paper->abstrak }}</textarea>
                    @error('abstrak')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- KATA KUNCI -->
                <div>
                    <label class="block mb-1">Keywords<span class="text-red-500">*</span></label>
                    <input type="text" name="keywords" value="{{ $paper->keywords }}"
                        placeholder="Examples: networks, prediction, machine learning, data mining" required
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">
                    <p class="text-xs text-gray-500 mt-1">Separate with commas(,)</p>
                    @error('keywords')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- REFERENCES -->
                <div>
                    <label class="block mb-1">References</label>
                    <textarea name="references" rows="6"
                        placeholder="List your references here (APA, IEEE, or other citation format)&#10;Example:&#10;[1] Smith, J. (2023). Title of Paper. Journal Name, 10(2), 123-145.&#10;[2] Doe, J. et al. (2024). Another Paper Title. Conference Proceedings."
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400">{{ $paper->references }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Enter each reference on a new line</p>
                    @error('references')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>


        <!-- AUTHORS SECTION -->
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
                        <th class="p-2">ORCID <span class="text-gray-400 text-xs font-normal">(optional)</span></th>
                        <th class="p-2">Affiliation</th>
                        <th class="p-2">Country</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($paper->authors as $i => $author)
                    <tr>
                        <td class="p-2 text-center">
                            <input type="radio" name="primary" value="{{ $i }}" required
                                {{ $author->is_primary ? 'checked' : '' }}>
                        </td>

                        <td class="p-2">
                            <input type="email" name="authors[{{ $i }}][email]" value="{{ $author->email }}" required
                                class="border rounded p-1 w-full">
                        </td>

                        <td class="p-2">
                            <input type="text" name="authors[{{ $i }}][first_name]" value="{{ $author->first_name }}"
                                required class="border rounded p-1 w-full">
                        </td>

                        <td class="p-2">
                            <input type="text" name="authors[{{ $i }}][last_name]" value="{{ $author->last_name }}"
                                required class="border rounded p-1 w-full">
                        </td>

                        <td class="p-2">
                            <input type="text" name="authors[{{ $i }}][orcid]" value="{{ $author->orcid ?? '' }}"
                                class="border rounded p-1 w-full">
                        </td>

                        <td class="p-2">
                            <input type="text" name="authors[{{ $i }}][organization]"
                                value="{{ $author->organization }}" required class="border rounded p-1 w-full">
                        </td>

                        <td class="p-2">
                            <select name="authors[{{ $i }}][country]" required class="border rounded p-1 w-full">
                                <option value="">-- Pilih Negara --</option>

                                @php
                                $negaraList = [
                                "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda",
                                "Argentina", "Armenia", "Australia", "Austria",
                                "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium",
                                "Belize", "Benin", "Bhutan",
                                "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria",
                                "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia",
                                "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia",
                                "Comoros", "Congo", "Costa Rica",
                                "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica",
                                "Dominican Republic", "Ecuador", "Egypt",
                                "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia",
                                "Fiji", "Finland", "France", "Gabon",
                                "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea",
                                "Guinea-Bissau", "Guyana",
                                "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq",
                                "Ireland", "Israel",
                                "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo",
                                "Kuwait", "Kyrgyzstan",
                                "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein",
                                "Lithuania", "Luxembourg", "Madagascar",
                                "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania",
                                "Mauritius", "Mexico", "Micronesia",
                                "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar",
                                "Namibia", "Nauru", "Nepal",
                                "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North
                                Macedonia", "Norway", "Oman", "Pakistan",
                                "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines",
                                "Poland", "Portugal", "Qatar",
                                "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent
                                and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia",
                                "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia",
                                "Solomon Islands", "Somalia", "South Africa",
                                "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden",
                                "Switzerland", "Syria", "Taiwan",
                                "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and
                                Tobago", "Tunisia", "Turkey", "Turkmenistan",
                                "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United
                                States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City",
                                "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
                                ];
                                @endphp

                                @foreach ($negaraList as $n)
                                <option value="{{ $n }}" {{ $author->country == $n ? 'selected' : '' }}>
                                    {{ $n }}
                                </option>
                                @endforeach
                            </select>
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
            <h3 class="font-semibold mb-3">Upload Revised File</h3>

            <div class="space-y-4">
                <div>
                    <label class="block mb-1">Upload New File (PDF/DOC/DOCX) <span class="text-red-500">*</span></label>
                    <input type="file" name="file_artikel" accept=".pdf,.doc,.docx" required
                        class="w-full border rounded p-2 bg-gray-50">
                    @error('file_artikel')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-1">Revision Notes</label>
                    <textarea name="revision_notes" rows="4"
                        placeholder="Describe the changes you made based on reviewer feedback..."
                        class="w-full border rounded p-2 focus:ring-2 focus:ring-gray-400"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Optional: Explain what you have revised</p>
                </div>
            </div>
        </div>


        <!-- BUTTON -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('author.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                Back
            </a>
            <button type="submit" class="px-5 py-2 rounded bg-slate-700 text-white hover:bg-slate-800">
                Submit Revision
            </button>
        </div>
    </form>
</section>

<script>
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
        <input type="text" name="authors[${index}][orcid]" class="border rounded p-1 w-full">
    </td>

    <td class="p-2">
        <input type="text" name="authors[${index}][organization]" required class="border rounded p-1 w-full">
    </td>

    <td class="p-2">
        <select name="authors[${index}][country]" class="border rounded p-1 w-full">
            <option value="">-- Pilih Negara --</option>
            <option value="Afghanistan">Afghanistan</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
            <option value="Argentina">Argentina</option>
            <option value="Armenia">Armenia</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Bahamas">Bahamas</option>
            <option value="Bahrain">Bahrain</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Barbados">Barbados</option>
            <option value="Belarus">Belarus</option>
            <option value="Belgium">Belgium</option>
            <option value="Belize">Belize</option>
            <option value="Benin">Benin</option>
            <option value="Bhutan">Bhutan</option>
            <option value="Bolivia">Bolivia</option>
            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
            <option value="Botswana">Botswana</option>
            <option value="Brazil">Brazil</option>
            <option value="Brunei">Brunei</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burundi">Burundi</option>
            <option value="Cabo Verde">Cabo Verde</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Canada">Canada</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Congo">Congo</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="Croatia">Croatia</option>
            <option value="Cuba">Cuba</option>
            <option value="Cyprus">Cyprus</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Denmark">Denmark</option>
            <option value="Djibouti">Djibouti</option>
            <option value="Dominica">Dominica</option>
            <option value="Dominican Republic">Dominican Republic</option>
            <option value="Ecuador">Ecuador</option>
            <option value="Egypt">Egypt</option>
            <option value="El Salvador">El Salvador</option>
            <option value="Equatorial Guinea">Equatorial Guinea</option>
            <option value="Eritrea">Eritrea</option>
            <option value="Estonia">Estonia</option>
            <option value="Eswatini">Eswatini</option>
            <option value="Ethiopia">Ethiopia</option>
            <option value="Fiji">Fiji</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="Gabon">Gabon</option>
            <option value="Gambia">Gambia</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Greece">Greece</option>
            <option value="Grenada">Grenada</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guinea">Guinea</option>
            <option value="Guinea-Bissau">Guinea-Bissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Honduras">Honduras</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran">Iran</option>
            <option value="Iraq">Iraq</option>
            <option value="Ireland">Ireland</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Jamaica">Jamaica</option>
            <option value="Japan">Japan</option>
            <option value="Jordan">Jordan</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Kenya">Kenya</option>
            <option value="Kiribati">Kiribati</option>
            <option value="Kosovo">Kosovo</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Laos">Laos</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libya">Libya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mexico">Mexico</option>
            <option value="Micronesia">Micronesia</option>
            <option value="Moldova">Moldova</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montenegro">Montenegro</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="North Korea">North Korea</option>
            <option value="North Macedonia">North Macedonia</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palau">Palau</option>
            <option value="Palestine">Palestine</option>
            <option value="Panama">Panama</option>
            <option value="Papua New Guinea">Papua New Guinea</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Qatar">Qatar</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russia</option>
            <option value="Rwanda">Rwanda</option>
            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
            <option value="Saint Lucia">Saint Lucia</option>
            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
            <option value="Samoa">Samoa</option>
            <option value="San Marino">San Marino</option>
            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Senegal">Senegal</option>
            <option value="Serbia">Serbia</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra Leone">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Korea">South Korea</option>
            <option value="South Sudan">South Sudan</option>
            <option value="Spain">Spain</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syria">Syria</option>
            <option value="Taiwan">Taiwan</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania">Tanzania</option>
            <option value="Thailand">Thailand</option>
            <option value="Timor-Leste">Timor-Leste</option>
            <option value="Togo">Togo</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="United States">United States</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Vatican City">Vatican City</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Vietnam">Vietnam</option>
            <option value="Yemen">Yemen</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option>
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
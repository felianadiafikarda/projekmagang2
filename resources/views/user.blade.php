@extends('layouts.app')

@section('page_title', 'User Dashboard')
@section('page_subtitle', 'Kelola roles pengguna untuk sistem jurnal')

@section('content')
<!-- STATISTICS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Total Users</p>
    <p class="text-3xl font-bold text-indigo-600">{{ $totalUsers }}</p>
    <p class="text-xs text-gray-500 mt-1">All registered users</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Conference Managers</p>
    <p class="text-3xl font-bold text-red-600">{{ $conferenceManagers }}</p>
    <p class="text-xs text-gray-500 mt-1">System administrators</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Editors</p>
    <p class="text-3xl font-bold text-orange-600">{{ $editors }}</p>
    <p class="text-xs text-gray-500 mt-1">Active editors</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Reviewers</p>
    <p class="text-3xl font-bold text-purple-600">{{ $reviewers }}</p>
    <p class="text-xs text-gray-500 mt-1">Active reviewers</p>
  </div>
</div>

<!-- QUICK ACTIONS -->
<div class="flex gap-4 mb-8">
  <button id="btn-add-user" class="bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
    + Add New User
  </button>
</div>

<!-- FILTERS & SEARCH -->
<div class="bg-white p-4 rounded-lg shadow border border-gray-200 mb-6">
  <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">

    <select name="role" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
      <option value="">All Roles</option>
      @foreach ($roles as $role)
      <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
        {{ ucfirst($role->display_name) }}
      </option>
      @endforeach
    </select>

    <select name="status" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
      <option value="">All Status</option>
      <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
      <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>

    <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" class="border border-gray-300 rounded-md px-3 py-2 text-sm col-span-1">

    <button class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700 transition">
      Search
    </button>
  </form>
</div>

<!-- USERS TABLE -->
<div class="bg-white rounded-lg shadow border border-gray-200">
  <div class="overflow-x-auto">
    <table class="w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
            <input type="checkbox" id="select-all" class="rounded">
          </th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Current Roles</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Affiliation</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">

        @foreach ($users as $user)
        <tr class="hover:bg-gray-50"
          data-role="{{ $user->roles->pluck('name')->join(',') }}"
          data-status="{{ strtolower($user->status) }}"
          data-text="{{ strtolower($user->name . ' ' . $user->email . ' ' . $user->affiliation) }}">

          <td class="px-6 py-4">
            <input type="checkbox" class="rounded">
          </td>

          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-gray-600 font-semibold text-sm">
                  {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                </span>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                <p class="text-xs text-gray-500">{{ '@' . explode('@', $user->email)[0] }}</p>
              </div>
            </div>
          </td>

          <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>

          @php
          $colors = [
          'admin' => 'bg-green-100 text-gray-800',
          'conference_manager' => 'bg-red-100 text-red-800',
          'editor' => 'bg-orange-100 text-orange-800',
          'section_editor' => 'bg-yellow-100 text-yellow-800',
          'reviewer' => 'bg-purple-100 text-purple-800',
          'author' => 'bg-blue-100 text-blue-800',
          ];
          @endphp

          <td class="px-6 py-4">
            <div class="flex flex-wrap gap-1">
              @foreach($user->roles as $role)
              <span class="px-2 py-1 rounded text-xs font-medium {{ $colors[$role->name] ?? 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($role->display_name ?? $role->name) }}
              </span>
              @endforeach
            </div>
          </td>

          <td class="px-6 py-4 text-sm text-gray-600">{{ $user->affiliation ?? '-' }}</td>

          <td class="px-6 py-4">
            <button onclick="toggleActions({{ $user->id }})"
              class="p-2 hover:bg-gray-100 rounded text-gray-700">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
              </svg>
            </button>
          </td>

        </tr>

        <!-- Action Row -->
        <tr id="actions-{{ $user->id }}" class="hidden bg-gray-50">
          <td></td>
          <td colspan="6" class="px-6 py-4">
            <div class="flex items-center space-x-6 text-sm font-medium">

              <a href="#" class="text-blue-600 hover:underline">View Details</a>
              <button class="text-blue-600 hover:text-blue-800 text-sm font-medium edit-role-btn"
                data-user-id="{{ $user->id }}"
                data-user-name="{{ $user->name }}"
                data-user-email="{{ $user->email }}"
                data-user-affiliation="{{ $user->affiliation }}"
                data-user-status="{{ $user->status ?? 'active' }}"
                data-user-roles="{{ $user->roles->pluck('name')->join(',') }}">
                Edit Roles
              </button>
              <a href="#" class="text-blue-600 hover:underline">Edit</a>
              <form action="{{ route('users.delete', $user->id) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">
                  Delete
                </button>
              </form>

            </div>
          </td>
        </tr>
        @endforeach

      </tbody>

    </table>
  </div>

  <!-- Pagination -->
  {{ $users->links('vendor.pagination.custom') }}
</div>

<!-- EDIT ROLE MODAL -->
<div id="modal-edit-role" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-start mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Edit User Roles</h2>
        <p class="text-sm text-gray-600 mt-1">Assign or remove roles for this user</p>
      </div>
      <button id="close-edit-role" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <!-- User Info -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
      <div class="flex items-center gap-4">
        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
          <span class="text-indigo-600 font-semibold text-lg" id="modal-user-initials">JW</span>
        </div>
        <div>
          <h3 class="font-semibold text-gray-900" id="modal-user-name">Prof. James Wilson</h3>
          <p class="text-sm text-gray-600" id="modal-user-email">james.wilson@journal.edu</p>
          <p class="text-xs text-gray-500" id="modal-user-affiliation">University of Tech</p>
        </div>
      </div>
    </div>

    <!-- Role Assignment -->
    <div class="space-y-4">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Assign Roles</h3>

      @foreach ($roles as $role)
      <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
        <div class="flex items-start justify-between">
          <div class="flex items-start gap-3 flex-1">

            {{-- Checkbox Role --}}
            <input type="checkbox"
              data-role="{{ $role->name }}"
              value="{{ $role->name }}"
              class="mt-1 rounded role-checkbox">

            <div class="w-full">
              <label for="role-{{ $role->name }}"
                class="font-semibold text-gray-900 cursor-pointer flex items-center gap-2">
                {{ ucfirst(str_replace('_', ' ', $role->name)) }}

                @if ($role->level >= 5)
                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs">Highest Access</span>
                @elseif ($role->level > 3)
                <span class="bg-orange-100 text-orange-800 px-2 py-0.5 rounded text-xs">Editorial Access</span>
                @elseif ($role->level == 3)
                <span class="bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs">Administrator Access</span>
                @elseif ($role->level >= 2)
                <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-xs">Review Access</span>
                @else
                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">Basic Access</span>
                @endif
              </label>

              @if ($role->name == 'reviewer')
              <div class="mt-3 hidden" id="reviewer-expertise">
                <label class="text-sm font-medium text-gray-700 block mb-2">Expertise Area</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                  <option>Machine Learning</option>
                  <option>Computer Vision</option>
                  <option>Natural Language Processing</option>
                  <option>Cybersecurity</option>
                  <option>Data Science</option>
                  <option>Software Engineering</option>
                </select>
              </div>
              @endif

            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Additional Settings -->
    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
      <div class="flex gap-2">
        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <div>
          <p class="text-sm font-medium text-yellow-800">Role Permission Notice</p>
          <p class="text-xs text-yellow-700 mt-1">User akan menerima email notifikasi tentang perubahan role dan permission mereka.</p>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3 mt-6">
      <button id="save-roles-btn" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
        Save Changes
      </button>
      <button id="cancel-edit-role" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-semibold hover:bg-gray-300 transition">
        Cancel
      </button>
    </div>
  </div>
</div>

<!-- ADD NEW USER MODAL -->
<div id="modal-add-user" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New User with Roles</h2>

    <form action="{{ route('users.add') }}" method="POST">
      @csrf
      <div class="space-y-4">
        <!-- Basic Info -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">First Name *</label>
            <input type="text" name="first_name" placeholder="John" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">Last Name *</label>
            <input type="text" name="last_name" placeholder="Smith" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
          </div>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Email *</label>
          <input type="email" name="email" placeholder="user@university.edu" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Username *</label>
          <input type="text" name="username" placeholder="jsmith" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
        </div>

        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Password *</label>
          <input type="password" name="password" placeholder="••••••••" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
        </div>

        <!-- Country -->
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Country</label>
          <select id="country" class="w-full border border-gray-300 rounded-md px-3 py-2">
            <option value="">-- Select Country --</option>
          </select>
        </div>

        <!-- Affiliation -->
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Affiliation</label>
          <input type="text" name="affiliation" placeholder="University Name" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>

        <!-- Phone -->
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Phone Number</label>
          <input type="text" name="phone" placeholder="08xxxxxxxxxx" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>

        <!-- Address -->
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Address</label>
          <textarea name="address" placeholder="Full address..." class="w-full border border-gray-300 rounded-md px-3 py-2 h-24"></textarea>
        </div>

        <!-- Role Selection -->
        <div class="pt-4 border-t border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-3">Assign Initial Roles</h3>
          <div class="space-y-2">

            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="roles[]" value="conference_manager" class="rounded">
              <span class="text-sm">Conference Manager</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="roles[]" value="editor" class="rounded">
              <span class="text-sm">Editor</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="roles[]" value="reviewer" class="rounded">
              <span class="text-sm">Reviewer</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="roles[]" value="author" class="rounded" checked>
              <span class="text-sm">Author (Default)</span>
            </label>

          </div>
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
          Create User
        </button>
        <button id="close-add-user" type="button" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-semibold hover:bg-gray-300 transition">
          Cancel
        </button>
      </div>
    </form>
  </div>
</div>

<!-- SUCCESS NOTIFICATION -->
<div id="success-notification" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-3">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
  </svg>
  <span id="notification-message">Roles updated successfully!</span>
</div>

<script>
  // Dropdown Action Menu
  function toggleActions(id) {
    const row = document.getElementById("actions-" + id);
    row.classList.toggle("hidden");
  }

  // Edit Role Modal
  const editRoleModal = document.getElementById('modal-edit-role');
  const editRoleBtns = document.querySelectorAll('.edit-role-btn');
  const closeEditRoleBtn = document.getElementById('close-edit-role');
  const cancelEditRoleBtn = document.getElementById('cancel-edit-role');
  const saveRolesBtn = document.getElementById('save-roles-btn');

  // Add User Modal
  const addUserModal = document.getElementById('modal-add-user');
  const btnAddUser = document.getElementById('btn-add-user');
  const closeAddUserBtn = document.getElementById('close-add-user');

  // Reviewer checkbox and expertise field
  const reviewerCheckbox = document.getElementById('role-reviewer');
  const reviewerExpertise = document.getElementById('reviewer-expertise');

  // Success notification
  const successNotification = document.getElementById('success-notification');
  const notificationMessage = document.getElementById('notification-message');

  // Open Edit Role Modal
  editRoleBtns.forEach(btn => {
    btn.addEventListener('click', () => {

      const roles = btn.dataset.userRoles.split(',');

      document.getElementById('modal-user-name').textContent = btn.dataset.userName;
      document.getElementById('modal-user-email').textContent = btn.dataset.userEmail;
      document.getElementById('modal-user-affiliation').textContent = btn.dataset.userAffiliation ?? '-';
      document.getElementById('modal-user-initials').textContent = btn.dataset.userName.substring(0, 2).toUpperCase();

      // Reset semua checkbox terlebih dahulu
      document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = false);

      roles.forEach(role => {
        const cb = document.querySelector(`.role-checkbox[data-role="${role}"]`);
        if (cb) cb.checked = true;
      });

      // Tampilkan modal
      editRoleModal.classList.remove('hidden');

      // Simpan ID user untuk update nanti
      saveRolesBtn.dataset.userId = btn.dataset.userId;
    });
  });

  // Close Edit Role Modal
  closeEditRoleBtn.addEventListener('click', () => {
    editRoleModal.classList.add('hidden');
  });

  cancelEditRoleBtn.addEventListener('click', () => {
    editRoleModal.classList.add('hidden');
  });

  // Close modal when clicking outside
  editRoleModal.addEventListener('click', (e) => {
    if (e.target === editRoleModal) {
      editRoleModal.classList.add('hidden');
    }
  });

  // Save Roles
  saveRolesBtn.addEventListener('click', () => {
    const userId = saveRolesBtn.dataset.userId;

    const selectedRoles = [...document.querySelectorAll('.role-checkbox:checked')]
      .map(cb => cb.value);

    fetch(`/users/${userId}/update-roles`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          roles: selectedRoles
        })
      })
      .then(res => res.json())
      .then(() => {
        showNotification("Roles updated successfully!");
        location.reload();
      });
  });

  // Open Add User Modal
  btnAddUser.addEventListener('click', () => {
    addUserModal.classList.remove('hidden');
  });

  // Close Add User Modal
  closeAddUserBtn.addEventListener('click', () => {
    addUserModal.classList.add('hidden');
  });

  addUserModal.addEventListener('click', (e) => {
    if (e.target === addUserModal) {
      addUserModal.classList.add('hidden');
    }
  });

  // Toggle Reviewer Expertise Field
  reviewerCheckbox.addEventListener('change', () => {
    if (reviewerCheckbox.checked) {
      reviewerExpertise.classList.remove('hidden');
    } else {
      reviewerExpertise.classList.add('hidden');
    }
  });

  // Select All Checkbox
  const selectAllCheckbox = document.getElementById('select-all');
  const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

  selectAllCheckbox.addEventListener('change', () => {
    rowCheckboxes.forEach(checkbox => {
      checkbox.checked = selectAllCheckbox.checked;
    });
  });

  // Show Notification Function
  function showNotification(message) {
    notificationMessage.textContent = message;
    successNotification.classList.remove('hidden');

    setTimeout(() => {
      successNotification.classList.add('hidden');
    }, 3000);
  }

  window.addEventListener('pageshow', () => {
    document.getElementById('search-input').value = '';
  });

  // Country Dropdown Data
  const countries = [
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Argentina", "Armenia", "Australia",
    "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium",
    "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil",
    "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada",
    "Cape Verde", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros",
    "Congo (Congo-Brazzaville)", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic",
    "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador",
    "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France",
    "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea",
    "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia",
    "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya",
    "Kiribati", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya",
    "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives",
    "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia",
    "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar (Burma)",
    "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria",
    "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Panama",
    "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar",
    "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia",
    "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe",
    "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia",
    "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan",
    "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan",
    "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago",
    "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates",
    "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City",
    "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
  ];

  // Generate dropdown
  const countrySelect = document.querySelector("#country");
  countries.forEach(country => {
    const option = document.createElement("option");
    option.value = country;
    option.textContent = country;
    countrySelect.appendChild(option);
  });
</script>
@endsection
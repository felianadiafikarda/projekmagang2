@extends('layouts.app')

@section('page_title', 'User Dashboard')
@section('page_subtitle', 'Kelola roles pengguna untuk sistem jurnal')

@section('content')
<!-- STATISTICS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Total Users</p>
    <p class="text-3xl font-bold text-indigo-600">89</p>
    <p class="text-xs text-gray-500 mt-1">All registered users</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Conference Managers</p>
    <p class="text-3xl font-bold text-red-600">3</p>
    <p class="text-xs text-gray-500 mt-1">System administrators</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Editors</p>
    <p class="text-3xl font-bold text-orange-600">8</p>
    <p class="text-xs text-gray-500 mt-1">Active editors</p>
  </div>
  <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
    <p class="text-sm text-gray-500 mb-1">Reviewers</p>
    <p class="text-3xl font-bold text-purple-600">28</p>
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
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">

    <select id="filter-role" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
      <option value="">All Roles</option>
      <option value="conference_manager">Conference Manager</option>
      <option value="editor">Editor</option>
      <option value="reviewer">Reviewer</option>
      <option value="author">Author</option>
    </select>

    <select id="filter-status" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
      <option value="">All Status</option>
      <option value="active">Active</option>
      <option value="inactive">Inactive</option>
    </select>

    <input type="text" id="search-input" placeholder="Search..." autocomplete="new-password"
      readonly onfocus="this.removeAttribute('readonly')" class="border border-gray-300 rounded-md px-3 py-2 text-sm col-span-1">

    <button id="apply-filters" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700 transition">
      Search
    </button>
  </div>
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
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">

        @foreach ($users as $user)
        <tr class="hover:bg-gray-50"
          data-role="{{ strtolower($user->roles->pluck('name')->join(' ')) }}"
          data-status="{{ $user->status ?? 'active' }}"
          data-text="{{ strtolower($user->name . ' ' . $user->email . ' ' . ($user->affiliation ?? '')) }}">

          <td class="px-6 py-4">
            <input type="checkbox" class="rounded">
          </td>

          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-gray-600 font-semibold text-sm">
                  {{ strtoupper(substr($user->name, 0, 2)) }}
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
          'conference_manager' => 'bg-red-100 text-red-800',
          'editor' => 'bg-orange-100 text-orange-800',
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
            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
          </td>

          <td class="px-6 py-4">
            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium edit-role-btn"
              data-user-id="{{ $user->id }}"
              data-user-name="{{ $user->name }}"
              data-user-email="{{ $user->email }}"
              data-user-affiliation="{{ $user->affiliation }}"
              data-user-status="{{ $user->status ?? 'active' }}"
              data-user-roles="{{ $user->roles->pluck('name')->join(',') }}">
              Edit Roles
            </button>

          </td>
        </tr>
        @endforeach

      </tbody>

    </table>
  </div>

  <!-- Pagination -->
  <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
    <p class="text-sm text-gray-600">Showing 1 to 5 of 89 users</p>
    <div class="flex gap-2">
      <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">Previous</button>
      <button class="px-3 py-1 bg-indigo-600 text-white rounded-md text-sm">1</button>
      <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">2</button>
      <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">3</button>
      <button class="px-3 py-1 border border-gray-300 rounded-md text-sm hover:bg-gray-50">Next</button>
    </div>
  </div>
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

      <!-- Conference Manager Role -->
      <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
        <div class="flex items-start justify-between">
          <div class="flex items-start gap-3 flex-1">
            <input type="checkbox" id="role-conference-manager" class="mt-1 rounded">
            <div>
              <label for="role-conference-manager" class="font-semibold text-gray-900 cursor-pointer flex items-center gap-2">
                Conference Manager
                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs">Highest Access</span>
              </label>
              <p class="text-sm text-gray-600 mt-1">
                Pengelola penuh sistem konferensi dengan akses ke semua fitur manajemen user, submission, dan settings.
              </p>
              <div class="mt-2 flex flex-wrap gap-2 text-xs">
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Manage Users</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Manage Roles</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">System Settings</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">All Permissions</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Editor Role -->
      <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
        <div class="flex items-start justify-between">
          <div class="flex items-start gap-3 flex-1">
            <input type="checkbox" id="role-editor" class="mt-1 rounded">
            <div>
              <label for="role-editor" class="font-semibold text-gray-900 cursor-pointer flex items-center gap-2">
                Editor
                <span class="bg-orange-100 text-orange-800 px-2 py-0.5 rounded text-xs">Editorial Access</span>
              </label>
              <p class="text-sm text-gray-600 mt-1">
                Mengelola submission, assign reviewer, dan membuat keputusan editorial untuk paper yang di-assign.
              </p>
              <div class="mt-2 flex flex-wrap gap-2 text-xs">
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Review Submissions</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Assign Reviewers</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Make Decisions</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Reviewer Role -->
      <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
        <div class="flex items-start justify-between">
          <div class="flex items-start gap-3 flex-1">
            <input type="checkbox" id="role-reviewer" class="mt-1 rounded">
            <div class="w-full">
              <label for="role-reviewer" class="font-semibold text-gray-900 cursor-pointer flex items-center gap-2">
                Reviewer
                <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-xs">Review Access</span>
              </label>
              <p class="text-sm text-gray-600 mt-1">
                Melakukan peer review terhadap paper yang di-assign dan memberikan rekomendasi.
              </p>
              <div class="mt-2 flex flex-wrap gap-2 text-xs">
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Review Papers</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Submit Reviews</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">View Assignments</span>
              </div>

              <!-- Expertise Area (shown when reviewer is checked) -->
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
            </div>
          </div>
        </div>
      </div>

      <!-- Author Role -->
      <div class="border border-gray-200 rounded-lg p-4 hover:border-indigo-300 transition">
        <div class="flex items-start justify-between">
          <div class="flex items-start gap-3 flex-1">
            <input type="checkbox" id="role-author" class="mt-1 rounded">
            <div>
              <label for="role-author" class="font-semibold text-gray-900 cursor-pointer flex items-center gap-2">
                Author
                <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs">Basic Access</span>
              </label>
              <p class="text-sm text-gray-600 mt-1">
                Submit paper, upload revisi, dan berinteraksi dengan reviewer comments.
              </p>
              <div class="mt-2 flex flex-wrap gap-2 text-xs">
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Submit Papers</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Upload Revisions</span>
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">Track Status</span>
              </div>
            </div>
          </div>
        </div>
      </div>
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

    <div class="space-y-4">
      <!-- Basic Info -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">First Name *</label>
          <input type="text" placeholder="John" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Last Name *</label>
          <input type="text" placeholder="Smith" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
        </div>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 mb-1 block">Email *</label>
        <input type="email" placeholder="user@university.edu" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 mb-1 block">Username *</label>
        <input type="text" placeholder="jsmith" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 mb-1 block">Password *</label>
        <input type="password" placeholder="••••••••" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700 mb-1 block">Affiliation</label>
        <input type="text" placeholder="University Name" class="w-full border border-gray-300 rounded-md px-3 py-2">
      </div>

      <!-- Role Selection -->
      <div class="pt-4 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Assign Initial Roles</h3>
        <div class="space-y-2">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" class="rounded">
            <span class="text-sm">Conference Manager</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" class="rounded">
            <span class="text-sm">Editor</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" class="rounded">
            <span class="text-sm">Reviewer</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" class="rounded" checked>
            <span class="text-sm">Author (Default)</span>
          </label>
        </div>
      </div>
    </div>

    <div class="flex gap-3 mt-6">
      <button class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
        Create User
      </button>
      <button id="close-add-user" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-semibold hover:bg-gray-300 transition">
        Cancel
      </button>
    </div>
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

      // Set checkbox sesuai role user
      document.getElementById('role-conference-manager').checked = roles.includes('conference_manager');
      document.getElementById('role-editor').checked = roles.includes('editor');
      document.getElementById('role-reviewer').checked = roles.includes('reviewer');
      document.getElementById('role-author').checked = roles.includes('author');

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

    const selectedRoles = [];
    if (document.getElementById('role-conference-manager').checked) selectedRoles.push('conference_manager');
    if (document.getElementById('role-editor').checked) selectedRoles.push('editor');
    if (document.getElementById('role-reviewer').checked) selectedRoles.push('reviewer');
    if (document.getElementById('role-author').checked) selectedRoles.push('author');

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
      .then(data => {
        showNotification("Roles updated successfully!");
        location.reload(); // reload table
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

  // Filter and Search Functionality
  document.getElementById('apply-filters').addEventListener('click', function() {
    const roleValue = document.getElementById('filter-role').value.toLowerCase();
    const statusValue = document.getElementById('filter-status').value.toLowerCase();
    const searchValue = document.getElementById('search-input').value.toLowerCase();

    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
      const rowRoles = row.dataset.role.toLowerCase();
      const rowStatus = row.dataset.status.toLowerCase();
      const rowText = row.dataset.text.toLowerCase();

      const matchRole = !roleValue || rowRoles.includes(roleValue);
      const matchStatus = !statusValue || rowStatus.includes(statusValue);
      const matchSearch = !searchValue || rowText.includes(searchValue);

      if (matchRole && matchStatus && matchSearch) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  });
  window.addEventListener('pageshow', () => {
    document.getElementById('search-input').value = '';
  });
</script>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Conference Manager Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex bg-gray-100 text-gray-800">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-gray-800 text-gray-100 flex flex-col justify-between fixed inset-y-0">
    <div>
      <!-- HEADER SIDEBAR -->
      <div class="bg-gray-900 px-6 py-5 border-b border-gray-700">
        <h2 class="text-xl font-semibold text-white tracking-wide">Journal Management</h2>
        <p class="text-sm text-gray-400">2026</p>
      </div>

      <!-- NAVIGATION -->
      <nav class="mt-6 flex flex-col space-y-1">
        <a href="#" class="flex items-center gap-3 px-6 py-3 bg-gray-700 text-white font-medium rounded-r-full">
          <span>Conference Management</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-r-full transition">
          <span>Author</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-r-full transition">
          <span>Reviewer</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-r-full transition">
          <span>Editor</span>
        </a>
        
      </nav>
    </div>

    <!-- LOGOUT -->
    <div class="border-t border-gray-700 p-4">
      <button class="w-full flex items-center justify-center gap-2 bg-gray-700 text-gray-100 px-4 py-2 rounded-md hover:bg-gray-600 transition">
        Logout
      </button>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="ml-64 flex-1 p-8">
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Conference Manager Dashboard</h1>
        <p class="text-gray-600">Kelola Author, Reviewer, dan Editor untuk jurnal</p>
      </div>
      <div class="w-10 h-10 bg-gray-800 text-white rounded-full flex items-center justify-center font-semibold">
        CM
      </div>
    </div>

    <!-- STATISTICS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
      <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <p class="text-sm text-gray-500 mb-1">Total Authors</p>
        <p class="text-3xl font-bold text-blue-600">45</p>
        <p class="text-xs text-gray-500 mt-1">Registered authors</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <p class="text-sm text-gray-500 mb-1">Total Reviewers</p>
        <p class="text-3xl font-bold text-purple-600">28</p>
        <p class="text-xs text-gray-500 mt-1">Active reviewers</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <p class="text-sm text-gray-500 mb-1">Total Editors</p>
        <p class="text-3xl font-bold text-orange-600">8</p>
        <p class="text-xs text-gray-500 mt-1">Section editors</p>
      </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="flex gap-4 mb-8">
      <button id="btn-add-author" class="bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700 transition">
        + Add Author
      </button>
      <button id="btn-add-reviewer" class="bg-purple-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-purple-700 transition">
        + Add Reviewer
      </button>
      <button id="btn-add-editor" class="bg-orange-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-orange-700 transition">
        + Add Editor
      </button>
    </div>

    <!-- TABS -->
    <div class="flex gap-4 border-b border-gray-300 mb-6 overflow-x-auto">
      <button id="tab-overview" class="tab-btn pb-3 px-2 font-semibold border-b-2 border-gray-800 text-gray-900 whitespace-nowrap">
        Overview
      </button>
      <button id="tab-authors" class="tab-btn pb-3 px-2 font-semibold text-gray-500 hover:text-gray-800 transition whitespace-nowrap">
        Authors (45)
      </button>
      <button id="tab-reviewers" class="tab-btn pb-3 px-2 font-semibold text-gray-500 hover:text-gray-800 transition whitespace-nowrap">
        Reviewers (28)
      </button>
      <button id="tab-editors" class="tab-btn pb-3 px-2 font-semibold text-gray-500 hover:text-gray-800 transition whitespace-nowrap">
        Editors (8)
      </button>
    </div>

    <!-- OVERVIEW TAB -->
    <div id="content-overview">
      <!-- Submission Overview -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Submission Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="border-l-4 border-yellow-500 pl-4">
            <p class="text-sm text-gray-500">New Submissions</p>
            <p class="text-2xl font-bold text-gray-900">8</p>
            <p class="text-xs text-gray-600 mt-1">Menunggu review</p>
          </div>
          <div class="border-l-4 border-blue-500 pl-4">
            <p class="text-sm text-gray-500">Under Review</p>
            <p class="text-2xl font-bold text-gray-900">12</p>
            <p class="text-xs text-gray-600 mt-1">Sedang direview</p>
          </div>
          <div class="border-l-4 border-green-500 pl-4">
            <p class="text-sm text-gray-500">Accepted</p>
            <p class="text-2xl font-bold text-gray-900">15</p>
            <p class="text-xs text-gray-600 mt-1">Bulan ini</p>
          </div>
          <div class="border-l-4 border-red-500 pl-4">
            <p class="text-sm text-gray-500">Rejected</p>
            <p class="text-2xl font-bold text-gray-900">6</p>
            <p class="text-xs text-gray-600 mt-1">Bulan ini</p>
          </div>
        </div>
      </div>

      <!-- Author Activities -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Recent Author Activities</h3>
          <button class="text-sm text-blue-600 hover:text-blue-800">View All</button>
        </div>
        <div class="space-y-3">
          <div class="flex items-start gap-4 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
              <span class="text-blue-600 font-semibold text-sm">JS</span>
            </div>
            <div class="flex-1">
              <p class="font-medium text-gray-900">Dr. John Smith</p>
              <p class="text-sm text-gray-600">Submitted new paper: "Machine Learning in Healthcare"</p>
              <p class="text-xs text-gray-500 mt-1">2 hours ago • ID: SUB-2025-045</p>
            </div>
            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">New</span>
          </div>
          <div class="flex items-start gap-4 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
              <span class="text-blue-600 font-semibold text-sm">SC</span>
            </div>
            <div class="flex-1">
              <p class="font-medium text-gray-900">Dr. Sarah Chen</p>
              <p class="text-sm text-gray-600">Uploaded revision for paper: "AI in Education"</p>
              <p class="text-xs text-gray-500 mt-1">5 hours ago • ID: SUB-2025-038</p>
            </div>
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Revised</span>
          </div>
          <div class="flex items-start gap-4 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
              <span class="text-blue-600 font-semibold text-sm">MB</span>
            </div>
            <div class="flex-1">
              <p class="font-medium text-gray-900">Prof. Michael Brown</p>
              <p class="text-sm text-gray-600">Responded to reviewer comments</p>
              <p class="text-xs text-gray-500 mt-1">1 day ago • ID: SUB-2025-041</p>
            </div>
            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">Response</span>
          </div>
        </div>
      </div>

      <!-- Reviewer Performance -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Reviewer Performance</h3>
          <button class="text-sm text-blue-600 hover:text-blue-800">View All</button>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Reviewer</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Pending Reviews</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Completed</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Avg. Time</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium text-gray-900">Prof. Alice Johnson</td>
                <td class="px-4 py-3 text-sm text-gray-900">2 papers</td>
                <td class="px-4 py-3 text-sm text-gray-600">12 reviews</td>
                <td class="px-4 py-3 text-sm text-gray-600">8 days</td>
                <td class="px-4 py-3"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">On Time</span></td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium text-gray-900">Dr. Robert Lee</td>
                <td class="px-4 py-3 text-sm text-gray-900">1 paper</td>
                <td class="px-4 py-3 text-sm text-gray-600">8 reviews</td>
                <td class="px-4 py-3 text-sm text-gray-600">12 days</td>
                <td class="px-4 py-3"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Delayed</span></td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium text-gray-900">Dr. Lisa Wang</td>
                <td class="px-4 py-3 text-sm text-gray-900">3 papers</td>
                <td class="px-4 py-3 text-sm text-gray-600">15 reviews</td>
                <td class="px-4 py-3 text-sm text-gray-600">6 days</td>
                <td class="px-4 py-3"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Excellent</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Editor Workload -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Editor Workload</h3>
          <button class="text-sm text-blue-600 hover:text-blue-800">View All</button>
        </div>
        <div class="space-y-3">
          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <span class="text-orange-600 font-semibold">JW</span>
              </div>
              <div>
                <p class="font-semibold text-gray-900">Prof. James Wilson</p>
                <p class="text-sm text-gray-600">Editor in Chief</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-2xl font-bold text-gray-900">25</p>
              <p class="text-xs text-gray-500">Assigned Papers</p>
            </div>
          </div>
          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <span class="text-yellow-600 font-semibold">ET</span>
              </div>
              <div>
                <p class="font-semibold text-gray-900">Dr. Emma Thompson</p>
                <p class="text-sm text-gray-600">Section Editor</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-2xl font-bold text-gray-900">8</p>
              <p class="text-xs text-gray-500">Assigned Papers</p>
            </div>
          </div>
          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <span class="text-yellow-600 font-semibold">DG</span>
              </div>
              <div>
                <p class="font-semibold text-gray-900">Prof. Daniel Garcia</p>
                <p class="text-sm text-gray-600">Section Editor</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-2xl font-bold text-gray-900">12</p>
              <p class="text-xs text-gray-500">Assigned Papers</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Decisions -->
      <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Recent Editorial Decisions</h3>
          <button class="text-sm text-blue-600 hover:text-blue-800">View All</button>
        </div>
        <div class="space-y-3">
          <div class="flex justify-between items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <div class="flex-1">
              <p class="font-medium text-gray-900">Blockchain in Supply Chain Management</p>
              <p class="text-sm text-gray-600">ID: SUB-2025-032 • Editor: Dr. Emma Thompson</p>
              <p class="text-xs text-gray-500 mt-1">Decision made: 1 hour ago</p>
            </div>
            <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded font-medium">Accepted</span>
          </div>
          <div class="flex justify-between items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <div class="flex-1">
              <p class="font-medium text-gray-900">Quantum Computing Applications</p>
              <p class="text-sm text-gray-600">ID: SUB-2025-029 • Editor: Prof. Daniel Garcia</p>
              <p class="text-xs text-gray-500 mt-1">Decision made: 3 hours ago</p>
            </div>
            <span class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded font-medium">Revision Required</span>
          </div>
          <div class="flex justify-between items-start p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            <div class="flex-1">
              <p class="font-medium text-gray-900">Data Mining Techniques Overview</p>
              <p class="text-sm text-gray-600">ID: SUB-2025-025 • Editor: Prof. James Wilson</p>
              <p class="text-xs text-gray-500 mt-1">Decision made: Yesterday</p>
            </div>
            <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded font-medium">Rejected</span>
          </div>
        </div>
      </div>
    </div>

    <!-- AUTHORS TAB -->
    <div id="content-authors">
      <!-- Filter -->
      <div class="bg-white p-4 rounded-lg shadow border border-gray-200 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
          </select>
          <input type="text" placeholder="Search by name or email..." class="border border-gray-300 rounded-md px-3 py-2 text-sm col-span-2">
        </div>
      </div>

      <!-- Authors Table -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Affiliation</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submissions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Dr. John Smith</td>
                <td class="px-6 py-4 text-sm text-gray-600">john.smith@university.edu</td>
                <td class="px-6 py-4 text-sm text-gray-600">Stanford University</td>
                <td class="px-6 py-4 text-sm text-gray-900">3 papers</td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Dr. Sarah Chen</td>
                <td class="px-6 py-4 text-sm text-gray-600">sarah.chen@mit.edu</td>
                <td class="px-6 py-4 text-sm text-gray-600">MIT</td>
                <td class="px-6 py-4 text-sm text-gray-900">2 papers</td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Prof. Michael Brown</td>
                <td class="px-6 py-4 text-sm text-gray-600">m.brown@harvard.edu</td>
                <td class="px-6 py-4 text-sm text-gray-600">Harvard University</td>
                <td class="px-6 py-4 text-sm text-gray-900">5 papers</td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Dr. Emily Davis</td>
                <td class="px-6 py-4 text-sm text-gray-600">emily.davis@oxford.edu</td>
                <td class="px-6 py-4 text-sm text-gray-600">Oxford University</td>
                <td class="px-6 py-4 text-sm text-gray-900">1 paper</td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- REVIEWERS TAB -->
    <div id="content-reviewers" class="hidden">
      <!-- Filter -->
      <div class="bg-white p-4 rounded-lg shadow border border-gray-200 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
          </select>
          <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option>All Expertise</option>
            <option>Machine Learning</option>
            <option>Computer Vision</option>
            <option>Natural Language Processing</option>
            <option>Cybersecurity</option>
          </select>
          <input type="text" placeholder="Search by name or email..." class="border border-gray-300 rounded-md px-3 py-2 text-sm">
        </div>
      </div>

      <!-- Reviewers Table -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expertise</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reviews</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Prof. Alice Johnson</td>
                <td class="px-6 py-4 text-sm text-gray-600">alice.j@university.edu</td>
                <td class="px-6 py-4 text-sm text-gray-600">Machine Learning</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <div class="text-sm">12 completed</div>
                  <div class="text-xs text-gray-500">2 pending</div>
                </td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Dr. Robert Lee</td>
                <td class="px-6 py-4 text-sm text-gray-600">robert.lee@tech.edu</td>
                <td class="px-6 py-4 text-sm text-gray-600">Computer Vision</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <div class="text-sm">8 completed</div>
                  <div class="text-xs text-gray-500">1 pending</div>
                </td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Dr. Lisa Wang</td>
                <td class="px-6 py-4 text-sm text-gray-600">lisa.wang@institute.edu</td>
                <td class="px-6 py-4 text-sm text-gray-600">NLP</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <div class="text-sm">15 completed</div>
                  <div class="text-xs text-gray-500">3 pending</div>
                </td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Prof. David Martinez</td>
                <td class="px-6 py-4 text-sm text-gray-600">d.martinez@college.edu</td>
                <td class="px-6 py-4 text-sm text-gray-600">Cybersecurity</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <div class="text-sm">6 completed</div>
                  <div class="text-xs text-gray-500">0 pending</div>
                </td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- EDITORS TAB -->
    <div id="content-editors" class="hidden">
      <!-- Filter -->
      <div class="bg-white p-4 rounded-lg shadow border border-gray-200 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <select class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
          </select>
          <input type="text" placeholder="Search by name or email..." class="border border-gray-300 rounded-md px-3 py-2 text-sm col-span-2">
        </div>
      </div>

      <!-- Editors Table -->
      <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Papers</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Prof. James Wilson</td>
                <td class="px-6 py-4 text-sm text-gray-600">james.wilson@journal.edu</td>
                <td class="px-6 py-4 text-sm">
                  <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">Editor in Chief</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">25 papers</td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Dr. Emma Thompson</td>
                <td class="px-6 py-4 text-sm text-gray-600">emma.t@journal.edu</td>
                <td class="px-6 py-4 text-sm">
                  <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Section Editor</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">8 papers</td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Prof. Daniel Garcia</td>
                <td class="px-6 py-4 text-sm text-gray-600">d.garcia@journal.edu</td>
                <td class="px-6 py-4 text-sm">
                  <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Section Editor</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">12 papers</td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm font-medium text-gray-900">Dr. Sophia Anderson</td>
                <td class="px-6 py-4 text-sm text-gray-600">sophia.a@journal.edu</td>
                <td class="px-6 py-4 text-sm">
                  <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Section Editor</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">6 papers</td>
                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span></td>
                <td class="px-6 py-4 text-sm">
                  <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                  <button class="text-red-600 hover:text-red-800">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <!-- ADD AUTHOR MODAL -->
  <div id="modal-author" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New Author</h2>
      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">First Name</label>
            <input type="text" placeholder="John" class="w-full border border-gray-300 rounded-md px-3 py-2">
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">Last Name</label>
            <input type="text" placeholder="Smith" class="w-full border border-gray-300 rounded-md px-3 py-2">
          </div>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Email</label>
          <input type="email" placeholder="author@university.edu" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Affiliation</label>
          <input type="text" placeholder="University Name" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Username</label>
          <input type="text" placeholder="jsmith" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Password</label>
          <input type="password" placeholder="••••••••" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
      </div>
      <div class="flex gap-3 mt-6">
        <button class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-700 transition">
          Add Author
        </button>
        <button id="close-author" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-semibold hover:bg-gray-300 transition">
          Cancel
        </button>
      </div>
    </div>
  </div>

  <!-- ADD REVIEWER MODAL -->
  <div id="modal-reviewer" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New Reviewer</h2>
      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">First Name</label>
            <input type="text" placeholder="Alice" class="w-full border border-gray-300 rounded-md px-3 py-2">
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">Last Name</label>
            <input type="text" placeholder="Johnson" class="w-full border border-gray-300 rounded-md px-3 py-2">
          </div>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Email</label>
          <input type="email" placeholder="reviewer@university.edu" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Affiliation</label>
          <input type="text" placeholder="University Name" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Expertise Area</label>
          <select class="w-full border border-gray-300 rounded-md px-3 py-2">
            <option>Machine Learning</option>
            <option>Computer Vision</option>
            <option>Natural Language Processing</option>
            <option>Cybersecurity</option>
            <option>Data Science</option>
            <option>Artificial Intelligence</option>
            <option>Software Engineering</option>
          </select>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Username</label>
          <input type="text" placeholder="ajohnson" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Password</label>
          <input type="password" placeholder="••••••••" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
      </div>
      <div class="flex gap-3 mt-6">
        <button class="flex-1 bg-purple-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-purple-700 transition">
          Add Reviewer
        </button>
        <button id="close-reviewer" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-semibold hover:bg-gray-300 transition">
          Cancel
        </button>
      </div>
    </div>
  </div>

  <!-- ADD EDITOR MODAL -->
  <div id="modal-editor" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
      <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New Editor</h2>
      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">First Name</label>
            <input type="text" placeholder="James" class="w-full border border-gray-300 rounded-md px-3 py-2">
          </div>
          <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">Last Name</label>
            <input type="text" placeholder="Wilson" class="w-full border border-gray-300 rounded-md px-3 py-2">
          </div>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Email</label>
          <input type="email" placeholder="editor@journal.edu" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Affiliation</label>
          <input type="text" placeholder="University Name" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Editor Role</label>
          <select class="w-full border border-gray-300 rounded-md px-3 py-2">
            <option>Section Editor</option>
            <option>Editor in Chief</option>
            <option>Associate Editor</option>
          </select>
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Username</label>
          <input type="text" placeholder="jwilson" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
        <div>
          <label class="text-sm font-medium text-gray-700 mb-1 block">Password</label>
          <input type="password" placeholder="••••••••" class="w-full border border-gray-300 rounded-md px-3 py-2">
        </div>
      </div>
      <div class="flex gap-3 mt-6">
        <button class="flex-1 bg-orange-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-orange-700 transition">
          Add Editor
        </button>
        <button id="close-editor" class="flex-1 bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-semibold hover:bg-gray-300 transition">
          Cancel
        </button>
      </div>
    </div>
  </div>

  <script>
    // Tab switching
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = {
      'tab-overview': document.getElementById('content-overview'),
      'tab-authors': document.getElementById('content-authors'),
      'tab-reviewers': document.getElementById('content-reviewers'),
      'tab-editors': document.getElementById('content-editors')
    };

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => {
          t.classList.remove('border-b-2', 'border-gray-800', 'text-gray-900');
          t.classList.add('text-gray-500');
        });
        tab.classList.remove('text-gray-500');
        tab.classList.add('border-b-2', 'border-gray-800', 'text-gray-900');

        Object.values(contents).forEach(c => c.classList.add('hidden'));
        contents[tab.id].classList.remove('hidden');
      });
    });

    // Modal handlers
    const authorModal = document.getElementById('modal-author');
    const reviewerModal = document.getElementById('modal-reviewer');
    const editorModal = document.getElementById('modal-editor');

    const btnAddAuthor = document.getElementById('btn-add-author');
    const btnAddReviewer = document.getElementById('btn-add-reviewer');
    const btnAddEditor = document.getElementById('btn-add-editor');

    const closeAuthor = document.getElementById('close-author');
    const closeReviewer = document.getElementById('close-reviewer');
    const closeEditor = document.getElementById('close-editor');

    // Open modals
    btnAddAuthor.addEventListener('click', () => {
      authorModal.classList.remove('hidden');
    });

    btnAddReviewer.addEventListener('click', () => {
      reviewerModal.classList.remove('hidden');
    });

    btnAddEditor.addEventListener('click', () => {
      editorModal.classList.remove('hidden');
    });

    // Close modals
    closeAuthor.addEventListener('click', () => {
      authorModal.classList.add('hidden');
    });

    closeReviewer.addEventListener('click', () => {
      reviewerModal.classList.add('hidden');
    });

    closeEditor.addEventListener('click', () => {
      editorModal.classList.add('hidden');
    });

    // Close modal when clicking outside
    authorModal.addEventListener('click', (e) => {
      if (e.target === authorModal) {
        authorModal.classList.add('hidden');
      }
    });

    reviewerModal.addEventListener('click', (e) => {
      if (e.target === reviewerModal) {
        reviewerModal.classList.add('hidden');
      }
    });

    editorModal.addEventListener('click', (e) => {
      if (e.target === editorModal) {
        editorModal.classList.add('hidden');
      }
    });
  </script>
</body>
</html>
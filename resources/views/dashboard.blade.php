<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Dashboard</title>
    
</head>
<body>
   <!-- nav bar -->
<nav class="fixed bg-[#1f1f1f] top-0 z-50 w-full shadow">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-white rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <a href="#" class="flex items-center ms-2 md:me-24">
                    <img src="{{ asset('images/logo.png') }}" class="h-8 me-2" alt="Logo">
                    <span class="self-center text-xl font-extrabold sm:text-2xl whitespace-nowrap text-white">CaliCrane </span>
                </a>
            </div>

            <div class="flex items-center">
                <!-- Philippine Time Display - Now beside notification -->
                <div class="hidden md:flex items-center text-white mr-4">
                    <svg class="w-5 h-5 mr-2 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <span id="philippineTime" class="font-bold text-white"></span>
                </div>

                <div class="flex items-center ms-3">
                    <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full object-cover"
                                src="{{ Auth::check() ? (Auth::user()?->photo ? asset('storage/' . Auth::user()?->photo) : asset('images/uploadprof.png')) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </button>
                    </div>

                    <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-sm shadow-sm bg-white shadow" id="dropdown-user">
                        <!-- Profile Image in dropdown -->
                        <div class="flex justify-center items-center p-2">
                            <img class="w-20 h-20 rounded-full shadow-lg object-cover"
                               src="{{ Auth::check() ? (Auth::user()?->photo ? asset('storage/' . Auth::user()?->photo) : asset('images/uploadprof.png')) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </div>

                        <!-- User Info -->
                        <div class="px-4 py-3 text-center" role="none">
                            <p class="text-sm font-semibold text-gray-900">
                              {{ Auth::check() ? Auth::user()?->name : 'Guest' }}  {{ Auth::check() ? Auth::user()?->lastname : '' }}
                            </p>
                            <p class="text-sm font-medium text-gray-500 truncate">
                                {{ Auth::user()?->email }}
                            </p>
                        </div>

                        <!-- Dropdown Links -->
                        <ul class="py-1" role="none">
                            <li>
                                <a href="{{ route('profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('settings') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Settings
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- nav bar -->

<!-- side bar -->
<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full sm:translate-x-0 bg-black shadow shadow-xl" aria-label="Sidebar">
    <div class="h-full px-4 pb-4 overflow-y-scroll bg-black shadow [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
       
    <!-- Title -->
        <div class="flex justify-center items-center mb-6">
            <h1 class="text-3xl font-bold text-white tracking-wide">Core 3</h1>
        </div>

        <!-- Navigation Links -->
        <ul class="space-y-2 text-sm font-medium text-white">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <img src="{{ asset('svg/dashboard.svg') }}"
                        alt="Misc Icon"
                        class="w-4 h-4 mr-3 transition group-hover:brightness-0 group-hover:invert">
                    <span class="flex-1 text-center">Dashboard</span>
                </a>
            </li>

          <li x-data="{ open: false }" class="relative">
            <!-- Parent button -->
              <button @click="open = !open" 
                class="flex items-center w-full p-3 rounded-lg hover:bg-blue-900 transition focus:outline-none select-none">
                  <img src="{{ asset('svg/billing.svg') }}"
                    alt="Billing Icon"
                    class="w-4 h-4 mr-3">
                    <span class="ml-4">Billing and Invoicing</span>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-auto transition-transform"
                          fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M19 9l-7 7-7-7" />
                       </svg>
              </button>

    <!-- Dropdown menu -->
    <ul x-show="open" x-transition
        class="pl-10 mt-2 space-y-1 text-sm font-medium text-white overflow-hidden">
        <li>
            <a href="{{ route ('invoice') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
                Invoice Creation
            </a>
        </li>
       <li class="mb-2">
    <a href="{{ route('invoices.index') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
      View Invoices
    </a>
</li>
</ul>
           <li x-data="{ open: false }" class="relative">
  <!-- Parent button -->
  <button @click="open = !open" 
    class="flex items-center w-full p-3 rounded-lg hover:bg-blue-900 transition focus:outline-none select-none">
    <img src="{{ asset('svg/record.svg') }}" alt="Record Icon" class="w-4 h-4 mr-3" />
    <span class="ml-4 flex-1 min-w-0 break-words whitespace-normal">
        Record and Payment Management
    </span>

    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <!-- Dropdown menu -->
  <ul x-show="open" x-transition
      class="pl-10 mt-2 space-y-1 text-sm font-medium text-white overflow-hidden">
    <li>
      <a href="{{ route('manage-payment') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Manage Payments
      </a>
    </li>
    <li>
      <a href="{{ route('ledger-viewer') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Payment History
      </a>
    </li>
    <li>
      <a href="{{ route('payment-reminders') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Payment Reminders
      </a>
    </li>
  </ul>
</li>

            <li x-data="{ open: false }" class="relative">
  <!-- Parent button -->
  <button @click="open = !open"
    class="flex items-center w-full p-3 rounded-lg hover:bg-blue-900 transition focus:outline-none select-none">
    <img src="{{ asset('svg/schedule.svg') }}" alt="Schedule Icon" class="w-4 h-4 mr-3" />
    <span class="ml-4 flex-1 min-w-0 break-words whitespace-normal">
      Schedule Preventive Maintenance
    </span>

    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <!-- Dropdown menu -->
  <ul x-show="open" x-transition
      class="pl-10 mt-2 space-y-1 text-sm font-medium text-white overflow-hidden">
    <li>
      <a href="{{ route('maintenance-sched') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Maintenance Schedule
      </a>
    </li>
    <li>
      <a href="{{ route('maintenance-notif') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Maintenance Notifications
      </a>
    </li>
    <li>
      <a href="{{ route('maintenance-history') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Maintenance History Log
      </a>
    </li>
  </ul>
</li>

            <li x-data="{ open: false }" class="relative">
  <!-- Parent button -->
  <button @click="open = !open"
    class="flex items-center w-full p-3 rounded-lg hover:bg-blue-900 transition focus:outline-none select-none">
    <img src="{{ asset('svg/contract.svg') }}" alt="Contract Icon" class="w-4 h-4 mr-3" />
    <span class="ml-4 flex-1 min-w-0 break-words whitespace-normal">
      Contract and Permit Management
    </span>

    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>

  <!-- Dropdown menu -->
  <ul x-show="open" x-transition
      class="pl-10 mt-2 space-y-1 text-sm font-medium text-white overflow-hidden">
    <li>
      <a href="{{ route('make-contract') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Make Contracts
      </a>
    </li>
    <li>
      <a href="{{ route('manage-permits') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Manage Permits
      </a>
    </li>
    <li>
      <a href="{{ route('renewal-req') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Contract Renewal Requests
      </a>
    </li>
    <li>
      <a href="{{ route('expiry-notif') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Contract Expiry Notifications
      </a>
    </li>
  </ul>
</li>

            <li x-data="{ open: false }" class="relative">
  <!-- Parent button -->
  <button @click="open = !open" class="flex items-center w-full p-3 rounded-lg hover:bg-blue-900 transition focus:outline-none select-none">
    <img src="{{ asset('svg/reporting.svg') }}" alt="Reporting Icon" class="w-4 h-4 mr-3">

    <!-- Wrapper sa text para hindi sumobra -->
    <span class="ml-4">Reporting and Analytics</span>

    <!-- Dropdown Arrow -->
    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 ml text-white transition-transform"
        fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
 </button>

  <ul x-show="open" x-transition class="pl-10 mt-2 space-y-1 text-sm font-medium text-white overflow-hidden">
    <li>
      <a href="{{ route('financial-report') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition"> Billing Summary Report </a>
    </li>
    <li>
      <a href="{{ route('maintenance-report') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition"> Maintenance Reports </a>
    </li>
    <li>
      <a href="{{ route('contractpermit-report') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition"> Contract & Permit Reports </a>
    </li>
    <li>
      <a href="{{ route('ai-report') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition"> AI-Powered Predictive Analytics </a>
    </li>
  </ul>
</li>
  </ul>
    </div>
  </aside>
<!-- side bar -->

<!-- content -->
<div class="p-4 sm:ml-64">
    <div class="p-4   rounded-lg dark:border-gray-700 mt-14">

        <!-- breadcrumb -->
        <div class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-900 hover:text-blue-600">
                    <svg class="w-3 h-3 mr-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                Core 3
                </a>
                </li>
                <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="#" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Dashboard</a>
                </div>
                </li>
            </ol>
        </div>

        <!-- breadcrumb -->
    
        <!-- Main Content -->
         <!-- Main Content -->
<div class="p-4 rounded-lg dark:border-gray-700 mt-14">

  <!-- Breadcrumb -->
  <div class="flex mb-5" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
      <li class="inline-flex items-center">
        <a href="#" class="inline-flex items-center text-sm font-medium text-gray-900 hover:text-blue-600">
          <svg class="w-3 h-3 mr-2.5 text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
          </svg>
          Core 3
        </a>
      </li>
      <li>
        <div class="flex items-center">
          <svg class="w-3 h-3 text-gray-900 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
          </svg>
          <a href="#" class="ml-1 text-sm font-medium text-black text-gray-900 hover:text-blue-900 md:ml-2">Dashboard</a>
        </div>
      </li>
    </ol>
  </div>

  <!-- 🔽 FILTERS -->
  <div class="bg-white p-4 rounded-xl shadow-sm mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
      <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Date Range</label>
      <select class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option>Today</option>
        <option selected>This Week</option>
        <option>This Month</option>
        <option>Last 30 Days</option>
        <option>Custom Range</option>
      </select>
    </div>
    <div>
      <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Client</label>
      <select class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option>All Clients</option>
        <option>ABC Corp</option>
        <option>XYZ Industries</option>
      </select>
    </div>
    <div>
      <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
      <select class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option>All Statuses</option>
        <option>Paid</option>
        <option>Unpaid</option>
        <option>Overdue</option>
      </select>
    </div>
  </div>

  <!-- 📊 KPI CARDS -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4">
      <p class="text-xs text-blue-700 font-semibold">TOTAL REVENUE</p>
      <p class="text-lg font-bold mt-1">₱245,890</p>
      <p class="text-xs text-gray-500">Today</p>
    </div>
    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-4">
      <p class="text-xs text-amber-700 font-semibold">UNPAID INVOICES</p>
      <p class="text-lg font-bold mt-1">₱89,420</p>
      <p class="text-xs text-gray-500">12 clients</p>
    </div>
    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl p-4">
      <p class="text-xs text-emerald-700 font-semibold">PAID INVOICES</p>
      <p class="text-lg font-bold mt-1">₱156,470</p>
      <p class="text-xs text-gray-500">This week</p>
    </div>
    <div class="bg-gradient-to-br from-rose-50 to-rose-100 border border-rose-200 rounded-xl p-4">
      <p class="text-xs text-rose-700 font-semibold">OUTSTANDING BALANCES</p>
      <p class="text-lg font-bold mt-1">₱72,300</p>
      <p class="text-xs text-gray-500">Net 30</p>
    </div>
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 rounded-xl p-4">
      <p class="text-xs text-indigo-700 font-semibold">ACTIVE CONTRACTS</p>
      <p class="text-lg font-bold mt-1">28</p>
      <p class="text-xs text-gray-500">Ongoing</p>
    </div>
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-4">
      <p class="text-xs text-orange-700 font-semibold">EXPIRING CONTRACTS</p>
      <p class="text-lg font-bold mt-1">5</p>
      <p class="text-xs text-gray-500">Next 30 days</p>
    </div>
    <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 border border-cyan-200 rounded-xl p-4">
      <p class="text-xs text-cyan-700 font-semibold">ACTIVE PERMITS</p>
      <p class="text-lg font-bold mt-1">42</p>
      <p class="text-xs text-gray-500">Valid</p>
    </div>
    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-4">
      <p class="text-xs text-red-700 font-semibold">OVERDUE PAYMENTS</p>
      <p class="text-lg font-bold mt-1">9</p>
      <p class="text-xs text-gray-500">Past due</p>
    </div>
    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4">
      <p class="text-xs text-green-700 font-semibold">SCHEDULED MAINTENANCE</p>
      <p class="text-lg font-bold mt-1">7</p>
      <p class="text-xs text-gray-500">This week</p>
    </div>
    <div class="bg-gradient-to-br from-violet-50 to-violet-100 border border-violet-200 rounded-xl p-4">
      <p class="text-xs text-violet-700 font-semibold">EQUIPMENT UNDER MAINT.</p>
      <p class="text-lg font-bold mt-1">3</p>
      <p class="text-xs text-gray-500">In service</p>
    </div>
  </div>

  <!-- 📈 GRAPHS with VISUAL PLACEHOLDERS -->
  <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
    
    <!-- 1. Monthly Revenue Trend -->
    <div class="bg-white rounded-xl p-5 shadow-sm border">
      <h3 class="font-semibold text-gray-700 mb-3">Monthly Revenue Trend</h3>
      <div class="h-48 flex items-end justify-between px-2">
        <div class="flex flex-col items-center"><div class="w-2 bg-blue-300 rounded-t h-16 mb-1"></div><span class="text-xs text-gray-500">Jan</span></div>
        <div class="flex flex-col items-center"><div class="w-2 bg-blue-400 rounded-t h-20 mb-1"></div><span class="text-xs text-gray-500">Feb</span></div>
        <div class="flex flex-col items-center"><div class="w-2 bg-blue-500 rounded-t h-28 mb-1"></div><span class="text-xs text-gray-500">Mar</span></div>
        <div class="flex flex-col items-center"><div class="w-2 bg-blue-600 rounded-t h-32 mb-1"></div><span class="text-xs text-gray-500">Apr</span></div>
        <div class="flex flex-col items-center"><div class="w-2 bg-blue-500 rounded-t h-24 mb-1"></div><span class="text-xs text-gray-500">May</span></div>
      </div>
    </div>

    <!-- 2. Paid vs Unpaid -->
    <div class="bg-white rounded-xl p-5 shadow-sm border">
      <h3 class="font-semibold text-gray-700 mb-3">Paid vs Unpaid Invoices</h3>
      <div class="h-48 flex items-end justify-center space-x-8 pt-4">
        <div class="flex flex-col items-center">
          <div class="w-10 bg-emerald-500 rounded-t h-32"></div>
          <span class="mt-2 text-xs text-gray-600">Paid</span>
        </div>
        <div class="flex flex-col items-center">
          <div class="w-10 bg-amber-500 rounded-t h-20"></div>
          <span class="mt-2 text-xs text-gray-600">Unpaid</span>
        </div>
      </div>
    </div>

    <!-- 3. Payment Methods (Pie) -->
    <div class="bg-white rounded-xl p-5 shadow-sm border">
      <h3 class="font-semibold text-gray-700 mb-3">Payment Methods</h3>
      <div class="h-48 flex flex-col items-center justify-center">
        <svg width="120" height="120" viewBox="0 0 120 120">
          <circle cx="60" cy="60" r="50" fill="#f3f4f6" />
          <path d="M60,60 L60,10 A50,50 0 0,1 103,27 Z" fill="#3b82f6" opacity="0.9" />
          <path d="M60,60 L103,27 A50,50 0 0,1 85,105 Z" fill="#10b981" opacity="0.9" />
          <path d="M60,60 L85,105 A50,50 0 0,1 60,10 Z" fill="#f59e0b" opacity="0.9" />
        </svg>
        <div class="mt-3 flex space-x-4 text-xs text-gray-600">
          <span>Cash</span> <span>GCash</span> <span>Bank</span>
        </div>
      </div>
    </div>

    <!-- 4. Maintenance Cost -->
    <div class="bg-white rounded-xl p-5 shadow-sm border">
      <h3 class="font-semibold text-gray-700 mb-3">Maintenance Cost / Month</h3>
      <div class="h-48 flex items-end justify-between px-3 pt-4">
        <div class="flex flex-col items-center"><div class="w-3 bg-rose-400 rounded-t h-20"></div><span class="text-xs mt-1 text-gray-500">Jan</span></div>
        <div class="flex flex-col items-center"><div class="w-3 bg-rose-400 rounded-t h-24"></div><span class="text-xs mt-1 text-gray-500">Feb</span></div>
        <div class="flex flex-col items-center"><div class="w-3 bg-rose-500 rounded-t h-32"></div><span class="text-xs mt-1 text-gray-500">Mar</span></div>
        <div class="flex flex-col items-center"><div class="w-3 bg-rose-600 rounded-t h-28"></div><span class="text-xs mt-1 text-gray-500">Apr</span></div>
      </div>
    </div>

    <!-- 5. Equipment Availability (Gauge) -->
    <div class="bg-white rounded-xl p-5 shadow-sm border lg:col-span-2 xl:col-span-1">
      <h3 class="font-semibold text-gray-700 mb-3">Equipment Availability Rate</h3>
      <div class="h-48 flex flex-col items-center justify-center">
        <svg width="140" height="100" viewBox="0 0 140 100">
          <path d="M20,80 A60,60 0 1,1 120,80" fill="none" stroke="#e5e7eb" stroke-width="12"/>
          <path d="M20,80 A60,60 0 1,1 95,30" fill="none" stroke="#8b5cf6" stroke-width="12" stroke-linecap="round"/>
        </svg>
        <div class="text-center mt-2">
          <p class="text-2xl font-bold text-gray-800">87%</p>
          <p class="text-xs text-gray-500">Available</p>
        </div>
      </div>
    </div>

  </div>

</div>
<!-- End Main Content -->
        <!-- Main Content --> 
            
    </div>
</div>

</body>

<footer class="bg-[#1f1f1f] text-white fixed bottom-0 w-full p-3 shadow-lg flex justify-center items-center">
    <div class="flex gap-5 items-center justify-center">
        <img class="rounded-full w-10 h-10" src="{{ asset('images/logo.png') }}" alt="">
        <p class="text-sm text-gray-300">© 2025 CaliCrane All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<script>
    function displayPhilippineTime() {
        const options = {
            timeZone: 'Asia/Manila',
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };

        const philippineDateTime = new Date().toLocaleString('en-PH', options);

        const timeElement = document.getElementById('philippineTime');
        if (timeElement) {
            timeElement.textContent = philippineDateTime;
        }
    }

    displayPhilippineTime();

    setInterval(displayPhilippineTime, 1000);
    document.addEventListener('DOMContentLoaded', function() {
    displayPhilippineTime();
    });
</script>

</html>
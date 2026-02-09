<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <title>Dashboard</title>
    
    <style>
        /* Dashboard Layout */
        .dashboard-container {
            padding: 24px;
            background: #f5f6fa;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        /* Filters Section */
        .filters {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            align-items: end;
        }
        
        /* KPI Cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        
        .kpi-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }
        
        .kpi-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .kpi-content h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 4px;
        }
        
        .kpi-content p {
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 0;
        }
        
        .kpi-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
        }
        
        /* Charts Section */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .chart-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            height: 280px;
            display: flex;
            flex-direction: column;
        }
        
        .chart-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
        }
        
        .chart-placeholder {
            flex: 1;
            background: #ecf0f1;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        /* Project Status Card */
        .project-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }
        
        .project-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
        }
        
        .project-placeholder {
            height: 280px;
            background: #ecf0f1;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">
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
                    <span class="self-center text-xl font-extrabold sm:text-2xl whitespace-nowrap text-white">CaliCrane</span>
                </a>
            </div>

            <div class="flex items-center">
                <div class="flex items-center ms-3">
                    <div>
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full object-cover"
                                src="{{ Auth::check() && Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </button>
                    </div>

                    <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-sm shadow-sm bg-white shadow" id="dropdown-user">
                        <!-- Profile Image in dropdown -->
                        <div class="flex justify-center items-center p-2">
                            <img class="w-20 h-20 rounded-full shadow-lg object-cover"
                               src="{{ Auth::check() && Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </div>

                        <!-- User Info -->
                        <div class="px-4 py-3 text-center" role="none">
                            <p class="text-sm font-semibold text-gray-900">
                              {{ Auth::check() ? Auth::user()->name ?? 'Guest' : 'Guest' }}
                            </p>
                            <p class="text-sm font-medium text-gray-500 truncate">
                                {{ Auth::check() ? Auth::user()->email ?? 'guest@example.com' : 'guest@example.com' }}
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
<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-72 h-screen pt-20 transition-transform -translate-x-full lg:translate-x-0 bg-black shadow-xl"
    aria-label="Sidebar">
    <div class="h-full px-6 pb-6 overflow-y-auto bg-black">

        <!-- Title -->
        <div class="flex justify-center items-center mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-wide select-none cursor-default">
                Core 3
            </h1>
        </div>

        <!-- Toggle Button -->
        <button id="toggleSidebar"
            class="absolute -right-5 top-1/2 transform -translate-y-1/2 bg-black text-white rounded-full p-2 shadow hover:bg-black transition z-50">
            <svg id="arrowIcon" class="w-5 h-5 transition-transform transform rotate-0" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Navigation Links -->
        <ul class="space-y-2 text-sm font-medium text-white">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <img src="{{ asset('svg/dashboard.svg') }}" alt="Dashboard Icon" class="w-6 h-6 mr-3">
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>

            <!-- Billing & Invoicing (Updated to use your new system) -->
            <li>
                <a href="{{ route('billing.invoices.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <img src="{{ asset('svg/billing.svg') }}" alt="Billing Icon" class="w-6 h-6 mr-3">
                    <span class="ml-4">Billing & Invoicing</span>
                </a>
            </li>

            <!-- Record & Payment Management -->
            <li>
                <a href="{{ route('record.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <img src="{{ asset('svg/record.svg') }}" alt="Record Icon" class="w-6 h-6 mr-3" />
                    <span class="ml-4">Record & Payment</span>
                </a>
            </li>

            <!-- Schedule Preventive Maintenance (Keep as is - works well) -->
            <li x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center w-full p-3 rounded-lg hover:bg-blue-900 transition focus:outline-none select-none">
                    <img src="{{ asset('svg/schedule.svg') }}" alt="Schedule Icon" class="w-6 h-6 mr-3" />
                    <span class="ml-4 flex-1 min-w-0 break-words whitespace-normal">
                        Schedule Preventive Maintenance
                    </span>
                    <svg :class="{ 'rotate-180': open }" class="w-5 h-5 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <ul x-show="open" x-transition
                    class="pl-10 mt-2 space-y-1 text-sm font-medium text-white overflow-hidden">
                    <li>
                        <a href="{{ route('maintenance-sched') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
                            Maintenance Schedule
                        </a>
                    </li>

                    <li>
    <a href="{{ route('maintenance-dashboard') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
        Maintenance Dashboard
    </a>
</li>
 <li>
                        <a href="{{ route('maintenance-history') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
                            Maintenance History Log
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Contract & Permit Management (Fixed with sub-menu like Romeo's) -->
            <li x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center w-full p-3 rounded-lg hover:bg-blue-900 transition focus:outline-none select-none">
                    <img src="{{ asset('svg/contract.svg') }}" alt="Contract Icon" class="w-6 h-6 mr-3" />
                    <span class="ml-4 flex-1 min-w-0 break-words whitespace-normal">
                        Contract & Permit Management
                    </span>
                    <svg :class="{ 'rotate-180': open }" class="w-5 h-5 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <ul x-show="open" x-transition
                    class="pl-10 mt-2 space-y-1 text-sm font-medium text-white overflow-hidden">
                    <li>
                        <a href="{{ route('contract.management') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
                            Manage Contracts
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('manage-permits') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
                            Manage Permits
                        </a>
                    </li>
                    
                </ul>
            </li>

            <!-- Reporting & Analytics -->
            <li>
                <a href="{{ route('financial-report') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <img src="{{ asset('svg/reporting.svg') }}" alt="Reporting Icon" class="w-6 h-6 mr-3" />
                    <span class="ml-4">Reporting & Analytics</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<!-- side bar -->

<!-- content -->
<div class="flex-1 pt-20 pl-72 pb-16">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-14">

        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Header -->
            <div class="header">
                <div class="header-title">Dashboard</div>
            </div>

            <!-- Filters -->
            <div class="filters">
                <div class="filters-grid">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Period</label>
                        <select class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" class="w-full text-sm border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <button class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="kpi-grid">
                <!-- Total Revenue -->
                <div class="kpi-card">
                    <div class="kpi-icon bg-green-100">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="kpi-content">
                        <h4>Total Revenue</h4>
                        <p>Payment Management</p>
                    </div>
                    <div class="kpi-value">₱{{ number_format($totalRevenue, 2) }}</div>
                </div>

                <!-- Collection Rate -->
                <div class="kpi-card">
                    <div class="kpi-icon bg-blue-100">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="kpi-content">
                        <h4>Collection Rate</h4>
                        <p>Payment performance</p>
                    </div>
                    <div class="kpi-value">{{ $collectionRatePercent }}%</div>
                </div>

                <!-- Active Contracts -->
                <div class="kpi-card">
                    <div class="kpi-icon bg-purple-100">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3H6a3 3 0 00-3 3v2h5m14-4a2 2 0 012 2v2H3v-2a2 2 0 012-2h14m-9-4a2 2 0 012 2v2H8v-2a2 2 0 012-2h4M9 8a2 2 0 012-2h2a2 2 0 012 2v2H9V8z" />
                        </svg>
                    </div>
                    <div class="kpi-content">
                        <h4>Active Contracts</h4>
                        <p>Contract Management</p>
                    </div>
                    <div class="kpi-value">{{ $activeContracts }}</div>
                </div>

                <!-- Maintenance Status -->
                <div class="kpi-card">
                    <div class="kpi-icon bg-yellow-100">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="kpi-content">
                        <h4>Maintenance Status</h4>
                        <p>Completed this month</p>
                    </div>
                    <div class="kpi-value">{{ $completedThisMonth }}</div>
                </div>

                <!-- Compliance Reports -->
                <div class="kpi-card">
                    <div class="kpi-icon bg-red-100">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="kpi-content">
                        <h4>Compliance Reports</h4>
                        <p>Sent to CORE 2</p>
                    </div>
                    <div class="kpi-value">{{ $complianceReports }}</div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-title">Revenue Trend (Financial Intelligence)</div>
                    <div class="chart-placeholder">
                        Data from Financial Intelligence module
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-title">Payment Methods Distribution</div>
                    <div class="chart-placeholder">
                        Shows payment method breakdown
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-title">Equipment Availability Rate (Maintenance Scheduling)</div>
                    <div class="chart-placeholder">
                        Real-time maintenance status from Schedule Preventive Maintenance
                    </div>
                </div>
            </div>

            <!-- Project Status Updates -->
            <div class="project-card">
                <div class="project-title">Project Status Updates (CORE 4)</div>
                <div class="project-placeholder">
                    Project progress data from Project Management system
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<footer class="bg-[#1f1f1f] text-white py-3 px-4 shadow-lg flex justify-center items-center">
    <div class="flex gap-5 items-center">
        <img class="rounded-full w-10 h-10" src="{{ asset('images/logo.png') }}" alt="">
        <p class="text-sm text-gray-300">© 2025 CaliCrane All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
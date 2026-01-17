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

        <!-- Toggle Button (same style as sidebar.blade.php) -->
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
                    <img src="{{ asset('svg/dashboard.svg') }}"
                        alt="Dashboard Icon"
                        class="w-6 h-6 mr-3">
                    <span class="ml-4">Dashboard</span>
                </a>
            </li>

            <!-- Billing and Invoicing (Simple Link) -->
            <li>
                <a href="{{ route('invoices.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <img src="{{ asset('svg/billing.svg') }}" alt="Billing Icon" class="w-6 h-6 mr-3">
                    <span class="ml-4">Billing and Invoicing</span>
                </a>
            </li>

            <!-- Record and Payment (Simple Link) -->
            <li>
    <a href="{{ route('record.index') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
        <img src="{{ asset('svg/record.svg') }}" alt="Record Icon" class="w-6 h-6 mr-3" />
        <span class="ml-4">Record and Payment Management</span>
    </a>
</li>

            <!-- Schedule Preventive Maintenance -->
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

            <!-- Contract and Permit Management -->
                 <li>
                <a href="{{ route('contract.management') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <img src="{{ asset('svg/contract.svg') }}" alt="Record Icon" class="w-6 h-6 mr-3" />
                    <span class="ml-4">Contract and Permit Management</span>
                </a>
            </li>

            <!-- Reporting and Analytics -->
         <li>
                <a href="{{ route('financial-report') }}" class="flex items-center p-3 rounded-lg hover:bg-blue-900 transition">
                    <img src="{{ asset('svg/reporting.svg') }}" alt="Record Icon" class="w-6 h-6 mr-3" />
                    <span class="ml-4">Reporting and Analytics</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<!-- side bar -->


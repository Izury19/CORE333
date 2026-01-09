<style>
/* Scrollbar hide - cross-browser */
.scrollbar-hide::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Opera */
}
.scrollbar-hide {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;     /* Firefox */
}
#logo-sidebar a[role="link"] {
    text-decoration: none !important;
}
#logo-sidebar a {
    text-decoration: none !important;
}
 
</style>

<!-- Sidebar -->
<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-72 h-screen pt-20 transition-transform -translate-x-full lg:translate-x-0 bg-black shadow-xl"
    aria-label="Sidebar" 
    role="navigation" 
    aria-expanded="false"
    x-data="{ open: false }"
    :class="open ? 'translate-x-0' : '-translate-x-full'"
    @toggle-sidebar.window="open = !open"
>
    <div class="h-full px-6 pb-6 overflow-y-auto scrollbar-hide bg-black">

        <!-- Title -->
        <div class="flex justify-center items-center mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-wide select-none cursor-default">
                Core 3
            </h1>
        </div>

        <!-- Sidebar toggle button -->
        <button id="toggleSidebar"
    style="width: 40px; height: 40px; border-radius: 9999px;"
    class="absolute -right-6 top-1/2 transform -translate-y-1/2 bg-black text-white flex justify-center items-center shadow hover:bg-gray-900 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
    aria-label="Toggle Sidebar"
    @click="$dispatch('toggle-sidebar')"
>
    <svg id="arrowIcon"
        class="w-5 h-5 transition-transform transform"
        :class="{ 'rotate-180': open }"
        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"
    >
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
</button>


        <!-- Navigation Links -->
        <nav aria-label="Primary" class="space-y-3 text-white text-sm font-semibold">
            <a href="{{ route('dashboard') }}" style="color: white;" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-900 transition focus:outline-none" role="link"> 
                <img src="{{ asset('svg/dashboard.svg') }}" alt="" class="w-6 h-6 mr-3" aria-hidden="true">
                <span class="flex-grow text-left whitespace-normal break-words">Dashboard</span>
            </a>

            <!-- Dropdown Items -->
            @php
                $menus = [
                    [
                        'title' => 'Billing and Invoicing',
                        'icon' => 'billing.svg',
                        'routes' => [
        
                            ['name' => 'invoice', 'label' => 'Invoice Creation'],
                            
                        ],
                    ],
                    [
                        'title' => 'Record and Payment Management',
                        'icon' => 'record.svg',
                        'routes' => [
                            ['name' => 'manage-payment', 'label' => 'Manage Payments'],
                            ['name' => 'ledger-viewer', 'label' => 'Payment History'],
                            ['name' => 'payment-reminders', 'label' => 'Payment Reminders'],
                        ],
                    ],
                    [
                        'title' => 'Schedule Preventive Maintenance',
                        'icon' => 'schedule.svg',
                        'routes' => [
                            ['name' => 'maintenance-sched', 'label' => 'Maintenance Schedule'],
                            ['name' => 'maintenance-notif', 'label' => 'Maintenance Notifications'],
                            ['name' => 'maintenance-history', 'label' => 'Maintenance History Log'],
                        ],
                    ],
                    [
                        'title' => 'Contract and Permit Management',
                        'icon' => 'contract.svg',
                        'routes' => [
                            ['name' => 'make-contract', 'label' => 'Make Contracts'],
                            ['name' => 'manage-permits', 'label' => 'Manage Permits'],
                            ['name' => 'renewal-req', 'label' => 'Contract Renewal Requests'],
                            ['name' => 'expiry-notif', 'label' => 'Contract Expiry Notifications'],
                        ],
                    ],
                    [
                        'title' => 'Reporting and Analytics',
                        'icon' => 'reporting.svg',
                        'routes' => [
                            ['name' => 'financial-report', 'label' => 'Billing Summary Report'],
                            ['name' => 'maintenance-report', 'label' => 'Maintenance Reports'],
                            ['name' => 'contractpermit-report', 'label' => 'Contract & Permit Reports'],
                            ['name' => 'ai-report', 'label' => 'AI-Powered Predictive Analytics'],
                        ],
                    ],
                ];
            @endphp

            @foreach ($menus as $menu)
                <li x-data="{ open: false }" class="relative list-none">
                    <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 rounded-lg hover:bg-blue-900 transition focus:outline-none select-none"
                        aria-expanded="false"
                        :aria-expanded="open.toString()"
                        aria-controls="{{ \Illuminate\Support\Str::slug($menu['title']) }}-submenu"
                    >
                        <img src="{{ asset('svg/' . $menu['icon']) }}" alt="" class="w-6 h-6 mr-3" aria-hidden="true">
                        <span class="flex-grow text-left whitespace-normal break-words">{{ $menu['title'] }}</span>
                        <svg :class="{ 'rotate-180': open }" class="w-5 h-5 ml-auto transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <ul
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 max-h-0"
    x-transition:enter-end="opacity-100 max-h-screen"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 max-h-screen"
    x-transition:leave-end="opacity-0 max-h-0"
    id="{{ \Illuminate\Support\Str::slug($menu['title']) }}-submenu"
    class="pl-14 mt-1 space-y-1 text-sm font-medium text-white overflow-hidden"
>
    @foreach ($menu['routes'] as $item)
        <li>
            <a href="{{ route($item['name']) }}" 
                class="block p-2 rounded-lg text-white hover:bg-blue-800 hover:text-white transition"
            >
                {{ $item['label'] }}
            </a>
        </li>
    @endforeach
</ul>

                </li>
            @endforeach
        </nav>
    </div>
</aside>
<!-- End Sidebar -->

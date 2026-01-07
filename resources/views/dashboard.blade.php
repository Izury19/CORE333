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
                                src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </button>
                    </div>

                    <div class="z-50 hidden my-4 text-base list-none divide-y divide-gray-100 rounded-sm shadow-sm bg-white shadow" id="dropdown-user">
                        <!-- Profile Image in dropdown -->
                        <div class="flex justify-center items-center p-2">
                            <img class="w-20 h-20 rounded-full shadow-lg object-cover"
                                src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('images/uploadprof.png') }}"
                                alt="Profile Photo">
                        </div>

                        <!-- User Info -->
                        <div class="px-4 py-3 text-center" role="none">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ Auth::user()->name }} {{ Auth::user()->lastname }}
                            </p>
                            <p class="text-sm font-medium text-gray-500 truncate">
                                {{ Auth::user()->email }}
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
        <li>
            <a href="{{ route ('delivery') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
                Invoice Delivery
            </a>
        </li>
        <li>
            <a href="{{ route ('record') }}" class="block p-2 rounded-lg hover:bg-blue-800 transition">
                Biling Record
            </a>
        </li>
    </ul>
</li>

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
 <style>
/* ================= DASHBOARD CSS ================= */

.dashboard-container {
    padding: 24px;
    background: #f5f6fa;
}

/* FILTERS */
.filters {
    display: flex;
    gap: 12px;
    margin-bottom: 24px;
}

.filter-item select {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #fff;
}

/* KPI CARDS */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
}

.kpi-card {
    background: #ffffff;
    padding: 16px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.kpi-title {
    font-size: 12px;
    color: #777;
    margin-bottom: 6px;
}

.kpi-value {
    font-size: 26px;
    font-weight: bold;
    color: #2c3e50;
}

/* CHARTS */
.charts-grid {
    margin-top: 32px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.chart-card {
    background: #ffffff;
    padding: 16px;
    border-radius: 10px;
    height: 320px; /* mas roomy */
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
}


.chart-wide {
    grid-column: span 2;
}

.chart-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
}

.chart-placeholder {
    height: calc(100% - 30px);
    background: #ecf0f1;
    border-radius: 6px;
}

/* GAUGE PLACEHOLDER */
.chart-placeholder.gauge {
    background: linear-gradient(90deg, #2ecc71 70%, #ecf0f1 70%);
}
.kpi-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}
.chart-card canvas {
    flex: 1;
    width: 100% !important;
}


/* RESPONSIVE */
@media (max-width: 1200px) {
    .kpi-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .charts-grid {
        grid-template-columns: 1fr;
    }

    .chart-wide {
        grid-column: span 1;
    }

    .filters {
        flex-direction: column;
    }
}
</style>

<div class="dashboard-container">

    <!-- FILTER BAR -->
    <div class="filters">
        <div class="filter-item">
            <select><option>Date Range</option></select>
        </div>
        <div class="filter-item">
            <select><option>Client</option></select>
        </div>
        <div class="filter-item">
            <select><option>Status</option></select>
        </div>
        <div class="filter-item">
            <select><option>Equipment Type</option></select>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-title">Total Revenue</div>
            <div class="kpi-value">₱0.00</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Total Paid Invoices</div>
            <div class="kpi-value">₱0.00</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Total Unpaid Invoices</div>
            <div class="kpi-value">₱0.00</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Outstanding Balances</div>
            <div class="kpi-value">₱0.00</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Active Contracts</div>
            <div class="kpi-value">0</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Expiring Contracts</div>
            <div class="kpi-value">0</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Active Permits</div>
            <div class="kpi-value">0</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Overdue Payments</div>
            <div class="kpi-value">0</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Scheduled Maintenance (This Week)</div>
            <div class="kpi-value">0</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-title">Equipment Under Maintenance</div>
            <div class="kpi-value">0</div>
        </div>

    </div>

    <!-- CHARTS -->
    <div class="charts-grid">

<div class="chart-card">
    <div class="chart-title">Monthly Revenue Trend</div>
    <canvas id="revenueChart"></canvas>
</div>


<div class="chart-card">
    <div class="chart-title">Paid vs Unpaid Invoices</div>
    <canvas id="invoiceChart"></canvas>
</div>


<div class="chart-card">
    <div class="chart-title">Payment Methods Distribution</div>
    <canvas id="paymentMethodChart"></canvas>
</div>


<div class="chart-card">
    <div class="chart-title">Maintenance Cost per Month</div>
    <canvas id="maintenanceChart"></canvas>
</div>


<div class="chart-card chart-wide">
    <div class="chart-title">Equipment Availability Rate</div>
    <canvas id="availabilityChart"></canvas>
</div>


    </div>

</div>

        <!-- Main Content --> 
            
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<script>
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: {
                boxWidth: 14
            }
        }
    }
};

// Monthly Revenue (Line)
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Revenue (₱)',
            data: [120000, 150000, 170000, 140000, 190000, 210000],
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: commonOptions
});

// Paid vs Unpaid (Doughnut)
new Chart(document.getElementById('invoiceChart'), {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Unpaid'],
        datasets: [{
            data: [75, 25],
            backgroundColor: ['#16a34a', '#dc2626']
        }]
    },
    options: commonOptions
});

// Payment Methods (Pie)
new Chart(document.getElementById('paymentMethodChart'), {
    type: 'pie',
    data: {
        labels: ['Cash', 'Bank Transfer', 'Cheque'],
        datasets: [{
            data: [40, 45, 15],
            backgroundColor: ['#0ea5e9', '#6366f1', '#f59e0b']
        }]
    },
    options: commonOptions
});

// Maintenance Cost (Bar)
new Chart(document.getElementById('maintenanceChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May'],
        datasets: [{
            label: 'Cost (₱)',
            data: [30000, 25000, 40000, 20000, 35000],
            backgroundColor: '#ef4444'
        }]
    },
    options: commonOptions
});

// Equipment Availability (Semi Gauge)
new Chart(document.getElementById('availabilityChart'), {
    type: 'doughnut',
    data: {
        labels: ['Available', 'Unavailable'],
        datasets: [{
            data: [85, 15],
            backgroundColor: ['#22c55e', '#e5e7eb'],
            borderWidth: 0
        }]
    },
    options: {
        ...commonOptions,
        circumference: 180,
        rotation: 270,
        cutout: '70%',
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>



</html>
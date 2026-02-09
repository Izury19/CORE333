@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reporting and Analytics Hub</h1>
                    <p class="text-gray-600 mt-1">Centralized intelligence from all business units</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('financial-report.export.excel') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors"
                       target="_blank">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Excel
                    </a>
                    <a href="{{ route('financial-report.export.pdf') }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors"
                       target="_blank">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-6">
        <!-- Filters Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Data Filters</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                    <select id="periodFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" id="startDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" id="endDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button onclick="applyFilters()" 
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                        Apply
                    </button>
                </div>
            </div>
        </div>

       <!-- SECTION NAVIGATION BUTTONS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <a href="#financial" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:bg-gray-50 transition-colors">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-900">Financial Intelligence</span>
    </a>
    
    <a href="#maintenance" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:bg-gray-50 transition-colors">
        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-900">Maintenance Reports</span>
    </a>
    
    <a href="#projects" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:bg-gray-50 transition-colors">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-900">Project Status</span>
    </a>
    
    <a href="#compliance" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:bg-gray-50 transition-colors">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-900">Regulatory Compliance</span>
    </a>
</div>

        <!-- FINANCIAL INTELLIGENCE SECTION -->
        <div id="financial" class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-xl font-bold text-blue-900">Financial Intelligence</h2>
                    <p class="text-sm text-blue-700 mt-1">Revenue analytics from Payment Management system</p>
                </div>
                <div class="p-6">
                    <!-- Revenue Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                            <p class="text-xl font-bold text-gray-900 confidential">₱{{ number_format($totalRevenue, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Collection Rate</p>
                            <p class="text-xl font-bold text-gray-900">{{ $collectionRatePercent }}%</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Active Contracts</p>
                            <p class="text-xl font-bold text-gray-900">{{ $invoiceDetails->count() }}</p>
                        </div>
                    </div>

                    <!-- Revenue Breakdown -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Equipment Type</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($revenueBreakdown as $breakdown)
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">{{ ucfirst(str_replace('_', ' ', $breakdown->equipment_type)) }}</p>
                                <p class="text-lg font-bold text-gray-900 confidential">₱{{ number_format($breakdown->total, 2) }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Transaction Table -->
                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($invoiceDetails as $invoice)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">INV-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $invoice->client_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $invoice->equipment_type)) }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $invoice->hours_used }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 confidential">₱{{ number_format($invoice->total_amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- AI Explanation -->
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg mb-6">
                        <p class="text-sm text-blue-800">
                            🤖 <strong>AI Insight:</strong> Total revenue is calculated as sum of all billed hours × hourly rates from paid invoices.
                        </p>
                    </div>

                    <!-- Charts Section - REAL DATA -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Revenue Trend - REAL DATA -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h3>
                            <div class="space-y-2">
                                @php
                                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                                $maxRevenue = max($revenueData);
                                @endphp
                                
                                @for($i = 0; $i < count($months); $i++)
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 w-8">{{ $months[$i] }}</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 ml-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $maxRevenue > 0 ? ($revenueData[$i] / $maxRevenue) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500 w-12 text-right">₱{{ number_format($revenueData[$i]/1000, 0) }}K</span>
                                </div>
                                @endfor
                            </div>
                        </div>
                        
                        <!-- Payment Methods - REAL DATA -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h3>
                            <div class="space-y-3">
                                @foreach($paymentMethods as $method)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>{{ $method['name'] }}</span>
                                        <span>{{ $method['percentage'] }}%</span>
                                    </div>
                                    <div class="w-full bg-{{ $method['color'] }}-200 rounded-full h-3">
                                        <div class="bg-{{ $method['color'] }}-500 h-3 rounded-full" style="width: {{ $method['percentage'] }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoiceDetails as $invoice)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $invoice->client_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">INV-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 confidential">₱{{ number_format($invoice->total_amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            <span class="inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Bank Transfer
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Forward Files Button for Financial -->
                    <div class="mt-6">
                        <button type="button" 
                                onclick="openForwardModal('Financial Intelligence Report', 'Financial')" 
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Forward Financial Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAINTENANCE REPORTS SECTION -->
        <div id="maintenance" class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-yellow-50">
                    <h2 class="text-xl font-bold text-yellow-900">Maintenance Reports</h2>
                    <p class="text-sm text-yellow-700 mt-1">Equipment maintenance status from Maintenance Scheduling system</p>
                </div>
                <div class="p-6">
                    <!-- Maintenance Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Completed This Month</p>
                            <p class="text-xl font-bold text-gray-900">{{ $completedThisMonth }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Pending Maintenance</p>
                            <p class="text-xl font-bold text-gray-900">{{ $pendingCount }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Overdue Schedules</p>
                            <p class="text-xl font-bold text-gray-900">{{ $overdueCount }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">High Risk Equipment</p>
                            <p class="text-xl font-bold text-gray-900">{{ $highRiskCount }}</p>
                        </div>
                    </div>

                    <!-- AI Risk Distribution Chart -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">AI Risk Distribution</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-red-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-red-800">High Risk (≥80%)</span>
                                    <span class="text-xl font-bold text-red-900">{{ $highRiskCount }}</span>
                                </div>
                                <div class="w-full bg-red-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $highRiskCount + $mediumRiskCount + $lowRiskCount > 0 ? ($highRiskCount / ($highRiskCount + $mediumRiskCount + $lowRiskCount)) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="p-4 bg-yellow-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-yellow-800">Medium Risk (60-79%)</span>
                                    <span class="text-xl font-bold text-yellow-900">{{ $mediumRiskCount }}</span>
                                </div>
                                <div class="w-full bg-yellow-200 rounded-full h-2">
                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $highRiskCount + $mediumRiskCount + $lowRiskCount > 0 ? ($mediumRiskCount / ($highRiskCount + $mediumRiskCount + $lowRiskCount)) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="p-4 bg-green-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-green-800">Low Risk (<60%)</span>
                                    <span class="text-xl font-bold text-green-900">{{ $lowRiskCount }}</span>
                                </div>
                                <div class="w-full bg-green-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $highRiskCount + $mediumRiskCount + $lowRiskCount > 0 ? ($lowRiskCount / ($highRiskCount + $mediumRiskCount + $lowRiskCount)) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Maintenance Activity -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Maintenance Activity</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scheduled Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AI Risk</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($recentMaintenance as $schedule)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $schedule->equipment_name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $schedule->maintenanceType->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($schedule->status == 'completed')
                                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                            @elseif($schedule->status == 'pending' && \Carbon\Carbon::parse($schedule->scheduled_date)->isPast())
                                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">Overdue</span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($schedule->ai_risk_score > 0)
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium">
                                                        {{ number_format($schedule->ai_risk_score * 100, 0) }}%
                                                    </span>
                                                    @if($schedule->ai_risk_score >= 0.8)
                                                        <span class="w-2 h-2 bg-red-500 rounded-full" title="High Risk"></span>
                                                    @elseif($schedule->ai_risk_score >= 0.6)
                                                        <span class="w-2 h-2 bg-yellow-500 rounded-full" title="Medium Risk"></span>
                                                    @else
                                                        <span class="w-2 h-2 bg-green-500 rounded-full" title="Low Risk"></span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            No maintenance records found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- AI Insights -->
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg mb-6">
                        <p class="text-sm text-yellow-800">
                            🤖 <strong>AI Insight:</strong> Our predictive maintenance system analyzes equipment history and usage patterns to identify high-risk equipment requiring immediate attention.
                        </p>
                    </div>

                    <!-- Maintenance Trends Chart - REAL DATA -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Completion Trend</h3>
                        <div class="space-y-2">
                            @php
                            $maintenanceMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                            $maxMaintenance = max($maintenanceData);
                            @endphp
                            
                            @for($i = 0; $i < count($maintenanceMonths); $i++)
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 w-8">{{ $maintenanceMonths[$i] }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2 ml-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $maxMaintenance > 0 ? ($maintenanceData[$i] / $maxMaintenance) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8 text-right">{{ $maintenanceData[$i] }}</span>
                            </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Forward Files Button for Maintenance -->
                    <div class="mt-6">
                        <button type="button" 
                                onclick="openForwardModal('Maintenance Compliance Report', 'Maintenance')" 
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Forward Maintenance Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI-Powered Predictive Analytics -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-purple-50">
                    <h2 class="text-xl font-bold text-purple-900">🤖 AI-Powered Predictive Analytics</h2>
                    <p class="text-sm text-purple-700 mt-1">Machine learning insights for proactive maintenance management</p>
                </div>
                <div class="p-6">
                    <!-- AI Risk Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">High Risk Equipment</p>
                            <p class="text-xl font-bold text-red-600">{{ $aiInsights['high_risk_equipment'] }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">At-Risk Percentage</p>
                            <p class="text-xl font-bold text-orange-600">{{ $aiInsights['risk_percentage'] }}%</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Predicted Failures (30d)</p>
                            <p class="text-xl font-bold text-yellow-600">{{ $aiPredictions['upcoming_failures_30days'] }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Cost Savings</p>
                            <p class="text-xl font-bold text-green-600">₱{{ number_format($aiPredictions['maintenance_cost_savings'], 0) }}</p>
                        </div>
                    </div>

                    <!-- AI Recommendation -->
                    <div class="mt-4 p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-purple-800">
                            🤖 <strong>AI Recommendation:</strong> {{ $aiInsights['recommendation'] }}
                        </p>
                    </div>

                    <!-- Predictive Maintenance Chart - REAL DATA -->
                    <div class="bg-gray-50 rounded-xl p-4 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Predictive Maintenance Timeline</h3>
                        <div class="space-y-3">
                            @php
                            $weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                            $maxPredictive = max($predictiveData);
                            @endphp
                            
                            @for($i = 0; $i < count($weeks); $i++)
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span>{{ $weeks[$i] }}</span>
                                    <span>{{ $predictiveData[$i] }} failures</span>
                                </div>
                                <div class="w-full bg-red-200 rounded-full h-3">
                                    <div class="bg-red-500 h-3 rounded-full" style="width: {{ $maxPredictive > 0 ? ($predictiveData[$i] / $maxPredictive) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PROJECT STATUS UPDATES SECTION -->
        <div id="projects" class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-bold text-green-900">Project Status Updates</h2>
                    <p class="text-sm text-green-700 mt-1">Project progress sent to Project Management (CORE 4) system</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-900">Crane Installation - Manila</span>
                            <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">Completed</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-900">Truck Fleet Upgrade</span>
                            <span class="px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">In Progress</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-medium text-gray-900">Equipment Certification</span>
                            <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">Pending Review</span>
                        </div>
                    </div>
                    
                    <!-- Forward Files Button for Projects -->
                    <div class="mt-6">
                        <button type="button" 
                                onclick="openForwardModal('Project Status Update', 'Project')" 
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Forward Project Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- REGULATORY COMPLIANCE SECTION -->
        <div id="compliance" class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-red-50">
                    <h2 class="text-xl font-bold text-red-900">Regulatory Compliance Reports</h2>
                    <p class="text-sm text-red-700 mt-1">Compliance reports sent to Document Management & Regulatory Compliance (CORE 2) system</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Safety Compliance Report</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Equipment Certification</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Environmental Compliance</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Forward Files Button for Compliance -->
                    <div class="mt-6">
                        <button type="button" 
                                onclick="openForwardModal('Regulatory Compliance Report', 'Compliance')" 
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Forward Compliance Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

     
        
    </div>
</div>

<!-- Forward Files Modal -->
<div id="forwardModal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
    <span style="position:absolute; top:15px; right:20px; color:white; font-size:24px; cursor:pointer; z-index:1001;" onclick="closeForwardModal()">&times;</span>
    <div style="background:white; margin:2% auto; width:95%; max-width:800px; height:90vh; border-radius:12px; overflow:hidden;">
        <iframe src="/CORE333/public/forward-files" frameborder="0" style="width:100%; height:100%; border:none;"></iframe>
    </div>
</div>

<script>
// PROPER AUTO-BLUR WITH RESET TIMER
let blurTimeout;

function startBlurTimer() {
    if (blurTimeout) {
        clearTimeout(blurTimeout);
    }
    
    blurTimeout = setTimeout(function() {
        document.querySelectorAll('.confidential').forEach(el => {
            el.style.filter = 'blur(8px)';
        });
    }, 3000);
}

function resetBlurTimer() {
    if (blurTimeout) {
        clearTimeout(blurTimeout);
    }
    
    document.querySelectorAll('.confidential').forEach(el => {
        el.style.filter = 'blur(0px)';
    });
    
    startBlurTimer();
}

document.addEventListener('DOMContentLoaded', function() {
    startBlurTimer();
});

['mousemove', 'keypress', 'click', 'scroll', 'touchstart'].forEach(event => {
    document.addEventListener(event, resetBlurTimer, true);
});

// Forward Files Modal Functions
function openForwardModal(documentType, category) {
    localStorage.setItem('forwardDocumentType', documentType);
    localStorage.setItem('forwardCategory', category);
    document.getElementById('forwardModal').style.display = 'block';
}

function closeForwardModal() {
    document.getElementById('forwardModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('forwardModal');ss
    if (event.target === modal) {
        closeForwardModal();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeForwardModal();
    }
});
</script>
@endsection
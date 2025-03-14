@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-foreground">Welcome back, {{ Auth::user()->name }}</h1>
            <div class="flex space-x-3">
                <a href="" class="btn-primary">
                    Record Expense
                </a>
                <a href="" class="btn-secondary">
                    Update Inventory
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Birds -->
            <div class="card bg-primary-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-primary-100">
                        <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-primary-600">Total Birds</p>
                        <p class="text-2xl font-semibold text-primary-900">{{ $totalBirds ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Feed Stock -->
            <div class="card bg-secondary-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-secondary-100">
                        <svg class="h-6 w-6 text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-secondary-600">Feed Stock (kg)</p>
                        <p class="text-2xl font-semibold text-secondary-900">{{ $feedStock ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Expenses -->
            <div class="card bg-yellow-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Monthly Expenses</p>
                        <p class="text-2xl font-semibold text-yellow-900">{{ $monthlyExpenses ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Staff Performance -->
            <div class="card bg-green-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Active Staff</p>
                        <p class="text-2xl font-semibold text-green-900">{{ $activeStaff ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resource Management -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Low Stock Alerts -->
            <div class="card">
                <h2 class="text-lg font-semibold mb-4">Low Stock Alerts</h2>
                <div class="space-y-4">
                    @forelse($lowStockAlerts ?? [] as $feed)
                        <div class="p-4 bg-red-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-red-700">{{ $feed->feedType?->name ?? 'Feed Stock' }}</span>
                                    <p class="text-sm text-gray-500">{{ $feed->branch->name }}</p>
                                </div>
                                <span class="text-sm text-red-500">{{ number_format($feed->quantity_kg, 1) }} kg remaining</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No low stock alerts</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="card">
                <h2 class="text-lg font-semibold mb-4">Recent Expenses</h2>
                <div class="space-y-4">
                    @forelse($recentExpenses ?? [] as $expense)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium">{{ $expense->category }}</span>
                                    <p class="text-sm text-gray-500">{{ $expense->expense_date->format('M d, Y') }}</p>
                                </div>
                                <span class="font-semibold">{{ number_format($expense->amount, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No recent expenses</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
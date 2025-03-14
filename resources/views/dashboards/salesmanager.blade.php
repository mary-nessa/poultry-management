@extends('layouts.app')

@section('title', 'Sales Manager Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-foreground">Welcome back, {{ Auth::user()->name }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('sales.create') }}" class="btn-primary">
                    New Sale
                </a>
                <a href="{{ route('buyers.create') }}" class="btn-secondary">
                    Add Customer
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Today's Sales -->
            <div class="card bg-primary-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-primary-100">
                        <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-primary-600">Today's Sales</p>
                        <p class="text-2xl font-semibold text-primary-900">{{ $todaySales ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="card bg-green-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Monthly Revenue</p>
                        <p class="text-2xl font-semibold text-green-900">{{ $monthlyRevenue ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Customers -->
            <div class="card bg-secondary-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-secondary-100">
                        <svg class="h-6 w-6 text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-secondary-600">Active Customers</p>
                        <p class="text-2xl font-semibold text-secondary-900">{{ $activeCustomers ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Product Stock -->
            <div class="card bg-yellow-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Available Products</p>
                        <p class="text-2xl font-semibold text-yellow-900">{{ $availableProducts ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Management -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Sales -->
            <div class="card">
                <h2 class="text-lg font-semibold mb-4">Recent Sales</h2>
                <div class="space-y-4">
                    @forelse($recentSales ?? [] as $sale)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium">{{ $sale->buyer?->name ?? 'Walk-in Customer' }}</span>
                                    <p class="text-sm text-gray-500">{{ $sale->sale_date->format('M d, Y') }}</p>
                                </div>
                                <span class="font-semibold">{{ number_format($sale->total_amount, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No recent sales</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Products -->
            <div class="card">
                <h2 class="text-lg font-semibold mb-4">Top Selling Products</h2>
                <div class="space-y-4">
                    @forelse($topProducts ?? [] as $product)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium">{{ $product->product_type }}</span>
                                    <p class="text-sm text-gray-500">{{ $product->breed }}</p>
                                </div>
                                <span class="font-semibold">{{ $product->sale_items_count }} sold</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No product data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
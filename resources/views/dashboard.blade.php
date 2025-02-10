@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-foreground">Welcome back, {{ Auth::user()->name }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('birds.create') }}" class="btn-primary">
                    Add New Bird
                </a>
                <a href="{{ route('sales.create') }}" class="btn-secondary">
                    Record Sale
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

            <!-- Today's Egg Collection -->
            <div class="card bg-secondary-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-secondary-100">
                        <svg class="h-6 w-6 text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-secondary-600">Today's Eggs</p>
                        <p class="text-2xl font-semibold text-secondary-900">{{ $todayEggs ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="card bg-green-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Monthly Sales</p>
                        <p class="text-2xl font-semibold text-green-900">{{ $monthlySales ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Alerts -->
            <div class="card bg-red-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-600">Active Alerts</p>
                        <p class="text-2xl font-semibold text-red-900">{{ $activeAlerts ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
@endsection

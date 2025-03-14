@extends('layouts.app')

@section('title', 'Worker Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-foreground">Welcome back, {{ Auth::user()->name }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('egg-collections.create') }}" class="btn-primary">
                    Record Egg Collection
                </a>
                <a href="{{ route('health-checks.create') }}" class="btn-secondary">
                    Record Health Check
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Today's Egg Collection -->
            <div class="card bg-primary-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-primary-100">
                        <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-primary-600">Today's Eggs</p>
                        <p class="text-2xl font-semibold text-primary-900">{{ $todayEggs ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Birds Under Care -->
            <div class="card bg-secondary-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-secondary-100">
                        <svg class="h-6 w-6 text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-secondary-600">Birds Under Care</p>
                        <p class="text-2xl font-semibold text-secondary-900">{{ $birdsUnderCare ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="card bg-yellow-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Pending Tasks</p>
                        <p class="text-2xl font-semibold text-yellow-900">{{ $pendingTasks ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Health Alerts -->
            <div class="card bg-red-50">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-600">Health Alerts</p>
                        <p class="text-2xl font-semibold text-red-900">{{ $healthAlerts ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Tasks -->
        <div class="card">
            <h2 class="text-lg font-semibold mb-4">Today's Tasks</h2>
            <div class="space-y-4">
                @forelse($tasks ?? [] as $task)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-medium">{{ $task->message }}</span>
                                <p class="text-sm text-gray-500">{{ $task->created_at->format('h:i A') }}</p>
                            </div>
                            <span class="text-sm px-2 py-1 rounded {{ $task->type === 'health' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($task->type) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">No tasks scheduled for today</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
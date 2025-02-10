@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
        <div class="space-x-2">
            <a href="{{ route('users.edit', $user) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Edit User
            </a>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Users
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Name</label>
                            <p class="text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Role</label>
                            <p class="text-gray-900">{{ $user->role }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Branch</label>
                            <p class="text-gray-900">{{ $user->branch ? $user->branch->name : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                @if($user->role === 'WORKER')
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Daily Activities</h2>
                    <div class="space-y-4">
                        @forelse($user->dailyActivities as $activity)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $activity->activity_date }}
                                        </p>
                                        @if($activity->feeding_notes)
                                            <p class="text-sm text-gray-500">Feeding: {{ $activity->feeding_notes }}</p>
                                        @endif
                                        @if($activity->health_notes)
                                            <p class="text-sm text-gray-500">Health: {{ $activity->health_notes }}</p>
                                        @endif
                                        @if($activity->egg_collection_count)
                                            <p class="text-sm text-gray-500">Eggs Collected: {{ $activity->egg_collection_count }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No daily activities recorded.</p>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Role & Permission Management</h2>

    <!-- Optional: Display success messages -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row">
        <!-- Left Column: Roles List -->
        <div class="md:w-1/3 pr-4">
            <h3 class="text-xl font-semibold mb-3">Roles</h3>
            <ul class="space-y-2">
                @foreach($roles as $role)
                    <li>
                        <a href="{{ route('roles.split', ['role_id' => $role->id]) }}"
                           class="block p-2 border rounded 
                           @if(isset($selectedRole) && $selectedRole->id === $role->id)
                               bg-blue-500 text-white
                           @else
                               bg-gray-100 hover:bg-gray-200
                           @endif">
                           {{ $role->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Right Column: Permissions for Selected Role -->
        <div class="md:w-2/3 pl-4 border-t md:border-t-0 md:border-l">
            @if(isset($selectedRole))
                <h3 class="text-xl font-semibold mb-3">Permissions for: {{ $selectedRole->name }}</h3>
                <form action="{{ route('roles.assignPermissions', $selectedRole) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($permissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" id="perm-{{ $permission->id }}" value="{{ $permission->name }}" 
                                       class="h-5 w-5 text-blue-600 border-gray-300 rounded"
                                       @if($selectedRole->hasPermissionTo($permission->name)) checked @endif>
                                <label for="perm-{{ $permission->id }}" class="ml-2 text-gray-700">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" 
                            class="mt-4 px-4 py-2 bg-green-600 text-bg-primary rounded hover:bg-green-700 transition">
                        Save Changes
                    </button>
                </form>
            @else
                <p class="text-gray-600">Select a role from the left to view and modify its permissions.</p>
            @endif
        </div>
    </div>
</div>
@endsection

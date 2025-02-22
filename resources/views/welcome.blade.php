@extends('layouts.app')

{{-- Override the navigation section to remove it --}}
@section('navigation')
    {{-- Navigation is disabled on this page --}}
@endsection

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-md">
            <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">Login</h2>
            <form method="POST" action="{{ route('authenticate') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-600">Email</label>
                    <input id="email" type="email" name="email" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                </div>

                <!-- Password Field with Show/Hide Toggle -->
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-600">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-500 focus:outline-none">
                            Show
                        </button>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit"
                        class="w-full px-4 py-2 font-semibold text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Login
                </button>
            </form>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordField = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute using getAttribute and setAttribute
            const currentType = passwordField.getAttribute('type');
            const newType = currentType === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', newType);
            
            // Update the button text
            this.textContent = newType === 'password' ? 'Show' : 'Hide';
        });
    </script>
@endsection

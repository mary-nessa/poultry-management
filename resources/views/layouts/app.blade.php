<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poultry Management System - @yield('title')</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
<!-- Alpine.js state for toggling the sidebar and dropdown menus -->
<div x-data="{
          sidebarOpen: false,
          openUserManagement: false,
          openBirdManagement: false,
          openInventoryManagement: false,
          openSalesManagement: false
       }" class="flex h-screen overflow-hidden">

    <!-- Sidebar: Only visible when authenticated -->
    @auth
        <!-- Off-canvas sidebar for mobile -->
        <div x-show="sidebarOpen"
             class="fixed inset-0 flex z-40 md:hidden"
             role="dialog"
             aria-modal="true">
            <!-- Backdrop -->
            <div x-show="sidebarOpen"
                 x-transition.opacity
                 class="fixed inset-0 bg-gray-600 bg-opacity-75"
                 @click="sidebarOpen = false">
            </div>
            <!-- Sidebar panel -->
            <div x-show="sidebarOpen"
                 x-transition
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:bg-gray-600">
                        <svg class="h-6 w-6 text-white"
                             stroke="currentColor"
                             fill="none"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-4 border-b">
                    <h2 class="text-xl font-bold">Dashboard</h2>
                </div>
                <nav class="flex-1 px-2 py-4 space-y-1">
                    <!-- Direct Link: Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Dashboard
                    </a>

                    <!-- User Management Dropdown -->
                    <div class="space-y-1">
                        <button @click="openUserManagement = !openUserManagement" type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                            <span>User Management</span>
                            <svg :class="{'transform rotate-90': openUserManagement}" class="h-5 w-5 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6L14 10L6 14V6Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openUserManagement" class="space-y-1 pl-4">
                            <a href="{{ route('users.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Users
                            </a>
                            <a href="{{ route('managers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Managers
                            </a>
                        </div>
                    </div>

                    <!-- Bird Management Dropdown -->
                    <div class="space-y-1">
                        <button @click="openBirdManagement = !openBirdManagement" type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                            <span>Bird Management</span>
                            <svg :class="{'transform rotate-90': openBirdManagement}" class="h-5 w-5 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6L14 10L6 14V6Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openBirdManagement" class="space-y-1 pl-4">
                            <a href="{{ route('birds.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Birds
                            </a>
                            <a href="{{ route('bird-transfers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Bird Transfers
                            </a>
                            <a href="{{ route('egg-transfers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Egg Transfers
                            </a>
                            <a href="{{ route('bird-immunisations.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Bird Immunisations
                            </a>
                        </div>
                    </div>

                    <!-- Inventory Management Dropdown -->
                    <div class="space-y-1">
                        <button @click="openInventoryManagement = !openInventoryManagement" type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                            <span>Inventory Management</span>
                            <svg :class="{'transform rotate-90': openInventoryManagement}" class="h-5 w-5 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6L14 10L6 14V6Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openInventoryManagement" class="space-y-1 pl-4">
                            <a href="{{ route('feeds.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Feeds
                            </a>
                            <a href="{{ route('equipments.index') ?? '#' }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Equipment
                            </a>
                            <a href="{{ route('egg-trays.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Egg Trays
                            </a>
                        </div>
                    </div>

                    <!-- Sales Management Dropdown -->
                    <div class="space-y-1">
                        <button @click="openSalesManagement = !openSalesManagement" type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                            <span>Sales Management</span>
                            <svg :class="{'transform rotate-90': openSalesManagement}" class="h-5 w-5 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6L14 10L6 14V6Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openSalesManagement" class="space-y-1 pl-4">
                            <a href="{{ route('buyers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Buyers
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Suppliers
                            </a>
                            <a href="{{ route('sales.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Sales
                            </a>
                        </div>
                    </div>

                    <!-- Other Direct Links -->
                    <a href="{{ route('expenses.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Expenses
                    </a>
                    <a href="{{ route('daily-activities.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Daily Activities
                    </a>
                    <a href="{{ route('expense-limits.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Expense Limits
                    </a>
                    <a href="{{ route('alerts.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Alerts
                    </a>
                </nav>
            </div>
            <!-- Dummy element to force sidebar to shrink to fit close icon -->
            <div class="flex-shrink-0 w-14"></div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r">
                <div class="h-16 flex items-center justify-center border-b">
                    <h1 class="text-2xl font-semibold">Dashboard</h1>
                </div>
                <nav class="flex-1 px-2 py-4 space-y-1">
                    <!-- Direct Link: Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Dashboard
                    </a>

                    <!-- User Management Dropdown -->
                    <div class="space-y-1">
                        <button @click="openUserManagement = !openUserManagement" type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                            <span>User Management</span>
                            <svg :class="{'transform rotate-90': openUserManagement}" class="h-5 w-5 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6L14 10L6 14V6Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openUserManagement" class="space-y-1 pl-4">
                            <a href="{{ route('users.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Users
                            </a>
                            <a href="{{ route('managers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Managers
                            </a>
                            <a href="{{ route('branches.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                                Branches
                            </a>
                        </div>
                    </div>

                    <!-- Bird Management Dropdown -->
                    <div class="space-y-1">
                        <button @click="openBirdManagement = !openBirdManagement" type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                            <span>Bird Management</span>
                            <svg :class="{'transform rotate-90': openBirdManagement}" class="h-5 w-5 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6L14 10L6 14V6Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openBirdManagement" class="space-y-1 pl-4">
                            <a href="{{ route('birds.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Birds
                            </a>
                            <a href="{{ route('bird-transfers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Bird Transfers
                            </a>
                            <a href="{{ route('egg-transfers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Egg Transfers
                            </a>
                            <a href="{{ route('bird-immunisations.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Bird Immunisations
                            </a>
                        </div>
                    </div>

                    <!-- Inventory Management Dropdown -->
                    <div class="space-y-1">
                        <button @click="openInventoryManagement = !openInventoryManagement" type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                            <span>Inventory Management</span>
                            <svg :class="{'transform rotate-90': openInventoryManagement}" class="h-5 w-5 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6L14 10L6 14V6Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openInventoryManagement" class="space-y-1 pl-4">
                            <a href="{{ route('feeds.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Feeds
                            </a>
                            <a href="{{ route('equipments.index') ?? '#' }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Equipment
                            </a>
                            <a href="{{ route('medicine.index') ?? '#' }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Medicine
                            </a>
                            <a href="{{ route('egg-trays.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Egg Trays
                            </a>
                            <a href="{{ route('products.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Products
                            </a>
                        </div>
                    </div>

                    <!-- Sales Management Dropdown -->
                    <div class="space-y-1">
                        <button @click="openSalesManagement = !openSalesManagement" type="button"
                                class="w-full flex items-center justify-between px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                            <span>Sales Management</span>
                            <svg :class="{'transform rotate-90': openSalesManagement}" class="h-5 w-5 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 6L14 10L6 14V6Z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div x-show="openSalesManagement" class="space-y-1 pl-4">
                            <a href="{{ route('buyers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Buyers
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Suppliers
                            </a>
                            <a href="{{ route('sales.index') }}" class="block px-2 py-2 rounded text-gray-600 hover:bg-gray-200">
                                Sales
                            </a>
                        </div>
                    </div>

                    <!-- Other Direct Links -->
                    <a href="{{ route('expenses.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Expenses
                    </a>
                    <a href="{{ route('daily-activities.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Daily Activities
                    </a>
                    <a href="{{ route('expense-limits.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Expense Limits
                    </a>
                    <a href="{{ route('alerts.index') }}" class="block px-2 py-2 rounded text-gray-700 hover:bg-gray-200">
                        Alerts
                    </a>
                </nav>
            </div>
        </div>
    @endauth

    <!-- Main content area -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
        <!-- Header -->
        <header class="flex items-center justify-between h-16 bg-white border-b px-4">
            <!-- Mobile menu button: Only show when authenticated -->
            @auth
                <button @click="sidebarOpen = true"
                        class="md:hidden text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6"
                         stroke="currentColor"
                         fill="none"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            @endauth

            <!-- Top Right: Avatar with Dropdown for Profile & Logout -->
            @auth
                <div class="flex-1" x-data="{ open: false }">
                    <button @click="open = ! open" class="flex items-center focus:outline-none">
                        <!-- Replace with user image if available -->
                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->avatar ?? 'https://avatar.iran.liara.run/public' }}" alt="User Avatar">
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-4">
            @yield('content')
        </main>
    </div>

</div>
</body>
</html>

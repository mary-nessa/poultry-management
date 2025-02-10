<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poultry Management System - @yield('title')</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-background">
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
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-card">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
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
                <div class="flex items-center justify-between p-4 border-b border-border">
                    <h2 class="text-xl font-bold text-foreground">Poultry Management</h2>
                </div>
                <!-- Navigation Menu -->
                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    @include('layouts.navigation-items')
                </nav>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-card border-r border-border">
                <div class="flex items-center justify-between h-16 px-4 border-b border-border">
                    <h1 class="text-xl font-bold text-foreground">Poultry Management</h1>
                </div>
                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    @include('layouts.navigation-items')
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            <!-- Top navigation -->
            <div class="relative z-10 flex h-16 bg-card border-b border-border">
                <button @click="sidebarOpen = true"
                        class="px-4 border-r border-border text-muted-foreground md:hidden">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Top navigation right side -->
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex items-center">
                        <!-- Search bar could go here -->
                    </div>
                    
                    <!-- User dropdown -->
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Notifications -->
                        <button class="p-2 text-muted-foreground hover:text-foreground">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>

                        <!-- Profile dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center max-w-xs rounded-full focus:outline-none focus:ring-2 focus:ring-primary">
                                <img class="h-8 w-8 rounded-full object-cover" 
                                     src="{{ Auth::user()->avatar ?? 'https://avatar.iran.liara.run/public' }}" 
                                     alt="User avatar">
                            </button>
                            <div x-show="open" 
                                 @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-card rounded-md shadow-soft">
                                <div class="py-1">
                                    <a href="{{ route('profile') }}" 
                                       class="block px-4 py-2 text-sm text-foreground hover:bg-muted">
                                        Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="block w-full text-left px-4 py-2 text-sm text-foreground hover:bg-muted">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    @endauth
</div>
</body>
</html>

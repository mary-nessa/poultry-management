<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Poultry Management System - @yield('title')</title>
  @vite('resources/css/app.css')
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
  <div x-data="{
      sidebarOpen: false,
      openUserManagement: false,
      openBirdManagement: false,
      openInventoryManagement: false,
      openSalesManagement: false,
      darkMode: false
  }" :class="{ 'dark': darkMode }" class="flex h-screen overflow-hidden">

    @auth
      <!-- Mobile sidebar backdrop -->
      <div x-show="sidebarOpen"
           x-transition:enter="transition-opacity ease-linear duration-300"
           x-transition:enter-start="opacity-0"
           x-transition:enter-end="opacity-100"
           x-transition:leave="transition-opacity ease-linear duration-300"
           x-transition:leave-start="opacity-100"
           x-transition:leave-end="opacity-0"
           class="fixed inset-0 z-30 bg-gray-600 bg-opacity-75 md:hidden"
           @click="sidebarOpen = false">
      </div>

      <!-- Sidebar -->
      <div :class="{'translate-x-0 md:sticky': sidebarOpen || window.innerWidth >= 768, '-translate-x-full': !sidebarOpen && window.innerWidth < 768}"
           class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg transition-transform duration-300 transform md:translate-x-0 md:static md:h-screen overflow-y-auto">
        <!-- Sidebar header -->
        <div class="flex items-center justify-between h-16 px-4 bg-blue-600 text-white">
          <div class="flex items-center">
            <i class="fas fa-feather-alt text-2xl mr-2"></i>
            <span class="text-lg font-semibold">Poultry Manager</span>
          </div>
          <button @click="sidebarOpen = false" class="md:hidden">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <!-- Sidebar content -->
<nav class="px-4 py-4 space-y-2">

    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
        <i class="fas fa-home w-5 h-5 mr-3"></i>
        <span>Dashboard</span>
    </a>
  
    <!-- User Management -->
    @if(auth()->user()->can('manage role') || auth()->user()->can('manage user') || auth()->user()->can('manage branch'))
      <div class="space-y-1">
        <button @click="openUserManagement = !openUserManagement" class="flex items-center justify-between w-full px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
          <div class="flex items-center">
            <i class="fas fa-users w-5 h-5 mr-3"></i>
            <span>User Management</span>
          </div>
          <i class="fas fa-chevron-right transform transition-transform duration-200" :class="{'rotate-90': openUserManagement}"></i>
        </button>
        <div x-show="openUserManagement" class="pl-12 space-y-1">
            @can('manage role')
              <a href="{{ route('roles.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">RBAC</a>
            @endcan
            @can('manage user')
              <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Users</a>
            @endcan
            @can('manage branch')
              <a href="{{ route('branches.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Branches</a>
            @endcan
        </div>
      </div>
    @endif
  
    <!-- Bird Management -->
    @if(auth()->user()->can('manage chick-purchase') || auth()->user()->can('manage bird') || auth()->user()->can('manage bird-immunization'))
      <div class="space-y-1">
        <button @click="openBirdManagement = !openBirdManagement" class="flex items-center justify-between w-full px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
          <div class="flex items-center">
            <i class="fas fa-dove w-5 h-5 mr-3"></i>
            <span>Bird Management</span>
          </div>
          <i class="fas fa-chevron-right transform transition-transform duration-200" :class="{'rotate-90': openBirdManagement}"></i>
        </button>
        <div x-show="openBirdManagement" class="pl-12 space-y-1">
          @can('manage chick-purchase')
            <a href="{{ route('chick-purchases.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Bird Purchase</a>
          @endcan
          @can('manage bird')
            <a href="{{ route('birds.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Bird Stock</a>
          @endcan
          @can('manage bird-immunization')
            <a href="{{ route('bird-immunizations.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Immunisations</a>
          @endcan
        </div>
      </div>
    @endif
  
    <!-- Inventory Management -->
    @if(auth()->user()->can('manage feed-type') || auth()->user()->can('manage stock'))
      <div class="space-y-1">
        <button @click="openInventoryManagement = !openInventoryManagement" class="flex items-center justify-between w-full px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
          <div class="flex items-center">
            <i class="fas fa-warehouse w-5 h-5 mr-3"></i>
            <span>Inventory</span>
          </div>
          <i class="fas fa-chevron-right transform transition-transform duration-200" :class="{'rotate-90': openInventoryManagement}"></i>
        </button>
        <div x-show="openInventoryManagement" class="pl-12 space-y-1">
          @can('manage feed-type')
            <a href="{{ route('feedtypes.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Feed Types</a>
          @endcan
          @can('manage feed')
            <a href="{{ route('feeds.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Feeds</a>
          @endcan
          @can('manage equipment')
            <a href="{{ route('equipments.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Equipment</a>
          @endcan
          @can('manage medicine')
            <a href="{{ route('medicine.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Medicines</a>
          @endcan
          @can('manage transfer')
            <a href="{{ route('transfers.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Transfers</a>
          @endcan
        
          
        </div>
      </div>
    @endif
  
    <!-- Sales Management -->
    @if(auth()->user()->can('manage buyer') || auth()->user()->can('manage supplier') || auth()->user()->can('manage sale') || auth()->user()->can('manage egg-collection'))
      <div class="space-y-1">
        <button @click="openSalesManagement = !openSalesManagement" class="flex items-center justify-between w-full px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
          <div class="flex items-center">
            <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
            <span>Sales</span>
          </div>
          <i class="fas fa-chevron-right transform transition-transform duration-200" :class="{'rotate-90': openSalesManagement}"></i>
        </button>
        <div x-show="openSalesManagement" class="pl-12 space-y-1">
          @can('manage buyer')
            <a href="{{ route('buyers.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Buyers</a>
          @endcan
          @can('manage supplier')
            <a href="{{ route('suppliers.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Suppliers</a>
          @endcan
          @can('manage sale')
            <a href="{{ route('products.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Products</a>
            <a href="{{ route('sales.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Sales</a>
          @endcan
          @can('manage egg-collection')
            <a href="{{ route('egg-collections.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">Egg Collections</a>
          @endcan
        </div>
      </div>
    @endif
  
    <!-- Other links (Expenses, Alerts, etc.) -->
    @can('manage expense')
      <a href="{{ route('expenses.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
        <i class="fas fa-money-bill-wave w-5 h-5 mr-3"></i>
        <span>Expenses</span>
      </a>
    @endcan
  
    @can('manage alert')
      <a href="{{ route('alerts.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
        <i class="fas fa-bell w-5 h-5 mr-3"></i>
        <span>Alerts</span>
      </a>
    @endcan
  
  </nav>
  
      </div>
    @endauth

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top header -->
      <header class="bg-white shadow-sm">
        <div class="flex items-center justify-between h-16 px-4">
          <!-- Mobile menu button -->
          @auth
            <button @click="sidebarOpen = true" class="md:hidden text-gray-500 hover:text-gray-600 focus:outline-none">
              <i class="fas fa-bars"></i>
            </button>
          @endauth

          <!-- Header title -->
          <h1 class="text-xl font-semibold text-gray-800">@yield('title')</h1>

          <!-- User menu -->
          @auth
            <div class="relative" x-data="{ open: false }">
              <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                <div class="flex items-center space-x-4">
                  <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                  <img class="h-8 w-8 rounded-full object-cover border-2 border-blue-500"
                       src="{{ Auth::user()->avatar ?? 'https://avatar.iran.liara.run/public' }}"
                       alt="User Avatar">
                </div>
              </button>

              <!-- Dropdown menu -->
              <div x-show="open"
                   @click.away="open = false"
                   x-transition:enter="transition ease-out duration-100"
                   x-transition:enter-start="transform opacity-0 scale-95"
                   x-transition:enter-end="transform opacity-100 scale-100"
                   x-transition:leave="transition ease-in duration-75"
                   x-transition:leave-start="transform opacity-100 scale-100"
                   x-transition:leave-end="transform opacity-0 scale-95"
                   class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                  <i class="fas fa-user w-5 h-5 mr-3"></i>
                  Profile
                </a>
                <a href="#" @click="darkMode = !darkMode" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                  <i class="fas fa-moon w-5 h-5 mr-3"></i>
                  Dark Mode
                </a>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                    Logout
                  </button>
                </form>
              </div>
            </div>
          @endauth
        </div>
      </header>

      <!-- Main content area -->
      <main class="flex-1 overflow-y-auto bg-gray-50 p-4">
        @yield('content')
      </main>
    </div>
  </div>

  @stack('scripts')
  <script>
    // Initialize screen size check
    document.addEventListener('alpine:init', () => {
      Alpine.data('layout', () => ({
        init() {
          this.checkScreenSize();
          window.addEventListener('resize', () => this.checkScreenSize());
        },
        checkScreenSize() {
          if (window.innerWidth >= 768) {
            this.sidebarOpen = true;
          }
        }
      }));
    });
  </script>
</body>
</html>

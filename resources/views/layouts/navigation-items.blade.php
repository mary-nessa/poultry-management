<!-- Direct Link: Dashboard -->
<a href="{{ route('dashboard') }}"
   class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
    <svg class="mr-3 h-5 w-5 text-muted-foreground" viewBox="0 0 20 20" fill="currentColor">
        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
    </svg>
    Dashboard
</a>

<!-- User Management Dropdown -->
<div class="space-y-1">
    <button @click="openUserManagement = !openUserManagement"
            class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
        <div class="flex items-center">
            <svg class="mr-3 h-5 w-5 text-muted-foreground" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
            </svg>
            User Management
        </div>
        <svg :class="{'transform rotate-90': openUserManagement}"
             class="h-5 w-5 text-muted-foreground transition-transform duration-200"
             viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="openUserManagement"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="space-y-1 pl-11">
        <a href="{{ route('users.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Users</a>
        <a href="{{ route('managers.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Managers</a>
        <a href="{{ route('branches.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Branches</a>
    </div>
</div>

<!-- Bird Management Dropdown -->
<div class="space-y-1">
    <button @click="openBirdManagement = !openBirdManagement"
            class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
        <div class="flex items-center">
            <svg class="mr-3 h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
            </svg>
            Bird Management
        </div>
        <svg :class="{'transform rotate-90': openBirdManagement}"
             class="h-5 w-5 text-muted-foreground transition-transform duration-200"
             viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="openBirdManagement"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="space-y-1 pl-11">
        <a href="{{ route('birds.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Birds</a>
        <a href="{{ route('bird-transfers.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Bird Transfers</a>
        <a href="{{ route('egg-transfers.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Egg Transfers</a>
        <a href="{{ route('bird-immunisations.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Bird Immunisations</a>
    </div>
</div>

<!-- Inventory Management Dropdown -->
<div class="space-y-1">
    <button @click="openInventoryManagement = !openInventoryManagement"
            class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
        <div class="flex items-center">
            <svg class="mr-3 h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Inventory Management
        </div>
        <svg :class="{'transform rotate-90': openInventoryManagement}"
             class="h-5 w-5 text-muted-foreground transition-transform duration-200"
             viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="openInventoryManagement"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="space-y-1 pl-11">
        <a href="{{ route('feeds.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Feeds</a>
        <a href="{{ route('equipments.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Equipment</a>
        <a href="{{ route('medicine.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Medicine</a>
        <a href="{{ route('egg-trays.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Egg Trays</a>
        <a href="{{ route('products.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Products</a>
    </div>
</div>

<!-- Sales Management Dropdown -->
<div class="space-y-1">
    <button @click="openSalesManagement = !openSalesManagement"
            class="w-full flex items-center justify-between px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
        <div class="flex items-center">
            <svg class="mr-3 h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Sales Management
        </div>
        <svg :class="{'transform rotate-90': openSalesManagement}"
             class="h-5 w-5 text-muted-foreground transition-transform duration-200"
             viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="openSalesManagement"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="space-y-1 pl-11">
        <a href="{{ route('buyers.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Buyers</a>
        <a href="{{ route('suppliers.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Suppliers</a>
        <a href="{{ route('sales.index') }}" class="block px-2 py-2 text-sm text-muted-foreground rounded-md hover:bg-muted">Sales</a>
    </div>
</div>

<!-- Other Direct Links -->
<div class="pt-2 space-y-1">
    <a href="{{ route('expenses.index') }}"
       class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
        <svg class="mr-3 h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        Expenses
    </a>
    <a href="{{ route('daily-activities.index') }}"
       class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
        <svg class="mr-3 h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Daily Activities
    </a>
    <a href="{{ route('expense-limits.index') }}"
       class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
        <svg class="mr-3 h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Expense Limits
    </a>
    <a href="{{ route('alerts.index') }}"
       class="flex items-center px-2 py-2 text-sm font-medium rounded-md text-foreground hover:bg-muted">
        <svg class="mr-3 h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        Alerts
    </a>
</div> 
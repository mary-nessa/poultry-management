<div x-show="showAssignBranchModal" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-gray-900 opacity-50" @click="showAssignBranchModal = false"></div>
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <!-- Modal header -->
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h3 class="text-lg font-semibold" id="assignBranchModalLabel">
                Assign Branch <span x-text="assignUserBranchName"></span>
            </h3>
            <button @click="showAssignBranchModal = false" class="text-gray-600 hover:text-gray-900 text-2xl leading-none">&times;</button>
        </div>
        <!-- Modal body with form -->
        <form id="branchesForm" method="POST" action="{{ route('branches.assign') }}" class="px-4 py-4">
            @csrf
            <input type="hidden" name="branch_id" :value="assignUserBranchId">
            <div class="mb-3">
                <label for="manager_id" class="block text-sm font-medium text-gray-700">Assign Manager</label>
                <select name="manager_id" id="manager_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="" disabled selected>Select Manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end space-x-2">
                <button type="button" @click="showAssignBranchModal = false" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Close
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Assign Manager
                </button>
            </div>
        </form>
    </div>
</div>

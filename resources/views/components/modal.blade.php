<div x-data="{ open: false }">
    <button @click="open = true" class="px-4 py-2 bg-blue-600 text-white rounded-md">
        Open Modal
    </button>

    <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-lg font-bold">Modal Title</h2>
            <p class="mt-2 text-gray-600">This is a simple modal using Alpine.js and Tailwind.</p>

            <div class="mt-4 flex justify-end">
                <button @click="open = false" class="px-4 py-2 bg-gray-500 text-white rounded-md">Close</button>
            </div>
        </div>
    </div>
</div>

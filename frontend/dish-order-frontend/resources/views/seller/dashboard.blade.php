<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Welcome to the Seller Dashboard</h1>
        <p class="mb-6">Manage your dishes, view sold dishes, and add new dishes from here.</p>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Manage Dishes -->
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-lg font-semibold mb-2">Manage Dishes</h3>
                <p class="text-sm mb-4">View and manage all your dishes.</p>
                <a href="{{ route('seller.dishes.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Go to Dishes
                </a>
            </div>

            <!-- View Sold Dishes -->
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-lg font-semibold mb-2">View Sold Dishes</h3>
                <p class="text-sm mb-4">Check your sold dishes and customer details.</p>
                <a href="{{ route('seller.dishes.sold') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    View Sold Dishes
                </a>
            </div>

            <!-- Add New Dish -->
            <div class="bg-gray-100 p-4 rounded shadow">
                <h3 class="text-lg font-semibold mb-2">Add New Dish</h3>
                <p class="text-sm mb-4">Create a new dish to offer to customers.</p>
                <a href="{{ route('seller.dishes.create') }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                    Add Dish
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
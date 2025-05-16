<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">My Dishes</h1>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <a href="{{ route('seller.dishes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Add New Dish
        </a>

        <table class="w-full table-auto border border-gray-300 mt-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Price</th>
                    <th class="border p-2">Quantity</th>
                    <th class="border p-2">Calories</th> <!-- Added -->
                    <th class="border p-2">Description</th> <!-- Added -->
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dishes as $dish)
                <tr>
                    <td class="border p-2">{{ $dish['id'] }}</td>
                    <td class="border p-2">{{ $dish['name'] }}</td>
                    <td class="border p-2">${{ $dish['price'] }}</td>
                    <td class="border p-2">{{ $dish['quantity'] }}</td>
                    <td class="border p-2">{{ $dish['calories'] }}</td>
                    <td class="border p-2">{{ $dish['description'] }}</td>
                    <td class="border p-2">
                        <a href="{{ route('seller.dishes.edit', $dish['id']) }}" class="text-blue-600 hover:underline">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
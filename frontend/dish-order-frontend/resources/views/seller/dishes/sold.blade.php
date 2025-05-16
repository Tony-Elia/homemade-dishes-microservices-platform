<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Sold Dishes</h1>

        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">Dish Name</th>
                    <th class="border p-2">Customer</th>
                    <th class="border p-2">Shipping Address</th>
                    <th class="border p-2">Quantity Sold</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($soldDishes as $soldDish)
                <tr>
                    <td class="border p-2">{{ $soldDish['dish_name'] }}</td>
                    <td class="border p-2">{{ $soldDish['customer_name'] }}</td>
                    <td class="border p-2">{{ $soldDish['shipping_address'] }}</td>
                    <td class="border p-2">{{ $soldDish['quantity'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
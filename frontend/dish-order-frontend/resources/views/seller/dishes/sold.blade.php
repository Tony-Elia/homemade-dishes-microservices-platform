<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Sold Dishes</h1>

        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">Dish ID</th>
                    <th class="border p-2">Customer Email</th>
                    <th class="border p-2">Shipping Company</th>
                    <th class="border p-2">Quantity Sold</th>
                    <th class="border p-2">Price at Purchase</th>
                    <th class="border p-2">Order Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($soldDishes as $order)
                    @foreach ($order['items'] as $item)
                        <tr>
                            <td class="border p-2">{{ $item['dishId'] }}</td>
                            <td class="border p-2">{{ $order['userEmail'] }}</td>
                            <td class="border p-2">{{ $order['shippingCompany']['name'] ?? '-' }}</td>
                            <td class="border p-2">{{ $item['quantity'] }}</td>
                            <td class="border p-2">${{ $item['priceAtPurchase'] }}</td>
                            <td class="border p-2">{{ ucfirst(strtolower($order['status'])) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
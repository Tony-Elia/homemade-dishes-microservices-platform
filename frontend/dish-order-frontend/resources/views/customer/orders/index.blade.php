<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">My Orders</h1>

        @if(session('status'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('status') }}</div>
        @endif

        @if(empty($orders))
            <p>You have no orders yet.</p>
        @else
            <table class="w-full table-auto border border-gray-300 mt-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">Order ID</th>
                        <th class="border p-2">Date</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Total</th>
                        <th class="border p-2">Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr>
                        <td class="border p-2">{{ $order['id'] ?? '-' }}</td>
                        <td class="border p-2">{{ $order['created_at'] ?? '-' }}</td>
                        <td class="border p-2">{{ $order['status'] ?? '-' }}</td>
                        <td class="border p-2">${{ $order['total'] ?? '-' }}</td>
                        <td class="border p-2">
                            <ul>
                                @foreach ($order['items'] ?? [] as $item)
                                    <li>{{ $item['name'] ?? 'Dish' }} x{{ $item['quantity'] ?? 1 }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
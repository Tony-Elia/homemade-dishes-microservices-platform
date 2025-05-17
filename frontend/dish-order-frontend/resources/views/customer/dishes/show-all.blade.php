<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Available Dishes</h1>
        <a href="{{ route('customer.cart') }}" class="bg-blue-600 text-white px-4 py-2 rounded">View Cart</a>
        <table class="w-full table-auto border border-gray-300 mt-4">
            <thead class="bg-gray-200">
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Add to Cart</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($dishes) && count($dishes))
                    @foreach ($dishes as $dish)
                        <tr>
                            <td>{{ $dish['name'] }}</td>
                            <td>${{ $dish['price'] }}</td>
                            <td>
                                <form action="{{ route('customer.cart.add') }}" method="POST" class="flex items-center">
                                    @csrf
                                    <input type="hidden" name="dish_id" value="{{ $dish['id'] }}">
                                    <input type="number" name="quantity" value="1" min="1" class="border rounded w-16 mr-2">
                                    <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded">Add</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="3">No dishes available.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</x-app-layout>
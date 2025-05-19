<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Shopping Cart</h1>
        @if(session('status'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ $errors->first() }}</div>
        @endif

        @if(empty($cart))
            <p>Your cart is empty.</p>
        @else
            @php
                $cartTotal = 0;
                foreach ($cart as $item) {
                    $cartTotal += $item['price'] * $item['quantity'];
                }
            @endphp
            <form id="orderForm" action="{{ route('customer.cart.place_order') }}" method="POST">
                @csrf
                <table class="w-full table-auto border border-gray-300 mt-4">
                    <thead class="bg-gray-200">
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>${{ $item['price'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>
                                <form action="{{ route('customer.cart.remove', $item['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-red-600">Remove</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 font-bold">
                    Cart Total: $<span id="cartTotal">{{ number_format($cartTotal, 2) }}</span>
                </div>

                <!-- Shipping Company Selection -->
                <div class="mt-4">
                    <label for="shipping_company_id" class="block font-semibold mb-1">Choose Shipping Company:</label>
                    <select name="shipping_company_id" id="shipping_company_id" class="border rounded p-2 w-full" required>
                        <option value="">-- Select --</option>
                        @foreach($shippingCompanies as $company)
                            <option value="{{ $company['id'] }}" data-min-charge="{{ $company['minCharge'] }}">
                                {{ $company['name'] }} (Min Charge: ${{ $company['minCharge'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-4">Place Order</button>
            </form>
            <script>
                document.getElementById('orderForm').addEventListener('submit', function(e) {
                    var cartTotal = parseFloat(document.getElementById('cartTotal').innerText);
                    var select = document.getElementById('shipping_company_id');
                    var selectedOption = select.options[select.selectedIndex];
                    var minCharge = parseFloat(selectedOption.getAttribute('data-min-charge'));
                    if (!isNaN(minCharge) && cartTotal < minCharge) {
                        alert('Your cart total ($' + cartTotal.toFixed(2) + ') is less than the minimum charge for this shipping company ($' + minCharge.toFixed(2) + '). Please add more items or choose another company.');
                        e.preventDefault();
                    }
                });
            </script>
        @endif
    </div>
</x-app-layout>
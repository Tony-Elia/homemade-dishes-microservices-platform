<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Customer Accounts</h1>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Role</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($customers) && count($customers))
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="border p-2">{{ $customer['id'] }}</td>
                            <td class="border p-2">{{ $customer['name'] }}</td>
                            <td class="border p-2">{{ $customer['email'] }}</td>
                            <td class="border p-2">{{ ucfirst(strtolower($customer['role'])) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="border p-2 text-center">No customers found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</x-app-layout>

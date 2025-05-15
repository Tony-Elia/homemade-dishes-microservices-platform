<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Seller Representatives</h1>

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
                    <th class="border p-2">Company</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reps as $rep)
                <tr>
                    <td class="border p-2">{{ $rep['id'] }}</td>
                    <td class="border p-2">{{ $rep['name'] }}</td>
                    <td class="border p-2">{{ $rep['email'] }}</td>
                    <td class="border p-2">{{ $rep['companyName'] ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

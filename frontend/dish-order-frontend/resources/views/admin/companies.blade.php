<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Manage Companies</h1>

        @if (session('status'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if (isset($companies) && count($companies) > 0)
            <div class="mt-6">
                <h2 class="text-xl font-bold mb-4">Companies</h2>
                <ul class="list-disc pl-5">
                    @foreach ($companies as $company)
                        <li class="mb-3">
                            <div>
                                <span class="font-semibold">{{ $company['name'] }}</span> - <small class="text-gray-600">{{ $company['region'] }}</small>
                            </div>

                            @if (isset($company['representative']))
                                <div class="ml-4 text-sm text-gray-700">
                                    Representative: {{ $company['representative']['name'] }} ({{ $company['representative']['email'] }})
                                </div>
                            @else
                                <a href="{{ route('admin.seller.assign', ['company_id' => $company['id']]) }}" class="text-blue-600 hover:underline mt-1 inline-block">
                                    Create Sellers
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p>No companies found. <a href="{{ route('admin.companies.create') }}" class="text-blue-600 hover:underline">Create a company</a>.</p>
        @endif
    </div>
</x-app-layout>

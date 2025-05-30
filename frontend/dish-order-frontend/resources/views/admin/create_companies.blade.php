<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Create Companies</h1>

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

        <form action="{{ route('admin.companies.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 font-semibold">Company Name(s)</label>
                <input type="text" name="company_names" class="border rounded p-2 w-full" placeholder="Company names (comma-separated)">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Companies
            </button>
        </form>

        @if (isset($companies) && count($companies) > 0)
            <div class="mt-6">
                <h2 class="text-xl font-bold mb-4">Companies</h2>
                <ul class="list-disc pl-5">
                    @foreach ($companies as $company)
                        <li>
                            <a href="{{ route('admin.seller.assign', ['company_id' => $company->id]) }}" class="text-blue-600 hover:underline">
                                Create Sellers for {{ $company->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</x-app-layout>

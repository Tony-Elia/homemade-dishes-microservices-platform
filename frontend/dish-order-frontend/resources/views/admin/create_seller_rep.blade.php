<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Create Seller for Company ID: {{ $company_id }}</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.seller_reps.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="company_id" value="{{ $company_id }}">

            <div>
                <label class="block mb-1 font-semibold">Seller Name</label>
                <input type="text" name="name" class="border rounded p-2 w-full" placeholder="Seller Name">
            </div>

            <div>
                <label class="block mb-1 font-semibold">Username</label>
                <input type="email" name="email" class="border rounded p-2 w-full" placeholder="Username">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Seller
            </button>
        </form>
    </div>
</x-app-layout>
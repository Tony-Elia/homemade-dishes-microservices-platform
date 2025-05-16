<x-app-layout>
    <div class="container mx-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Edit Dish</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('seller.dishes.update', $dish['id']) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-1 font-semibold">Dish Name</label>
                <input type="text" name="name" class="border rounded p-2 w-full" value="{{ $dish['name'] }}" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Price</label>
                <input type="number" name="price" class="border rounded p-2 w-full" value="{{ $dish['price'] }}" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Quantity</label>
                <input type="number" name="quantity" class="border rounded p-2 w-full" value="{{ $dish['quantity'] }}" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Description</label>
                <textarea name="description" class="border rounded p-2 w-full" required>{{ $dish['description'] }}</textarea>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Calories</label>
                <input type="number" name="calories" class="border rounded p-2 w-full" value="{{ $dish['calories'] }}" required>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Dish
            </button>
        </form>
    </div>
</x-app-layout>
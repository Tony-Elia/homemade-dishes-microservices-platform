<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h1>
                    <p class="mb-6">You're logged in as <span class="font-semibold">{{ ucfirst(auth()->user()->role) }}</span>.</p>

                    @if (auth()->user()->role === 'admin')
                        <div class="mb-4 text-blue-700">
                            Use the navigation bar above to manage customers, sellers, and companies.
                        </div>
                    @elseif (auth()->user()->role === 'customer')
                        <div class="mb-4 text-green-700">
                            Use the navigation bar above to browse dishes, view your cart, and check your orders.
                        </div>
                    @endif

                    <div class="mt-8">
                        <p class="text-gray-500">If you need help, visit your profile or contact support.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

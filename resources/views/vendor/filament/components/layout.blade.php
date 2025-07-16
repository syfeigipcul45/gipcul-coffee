<x-filament::layouts.base>
    <!-- Header Custom -->
    <div class="bg-blue-600 text-white p-4">
        <h1 class="text-2xl font-bold">Custom Admin Dashboard</h1>
    </div>

    <!-- Sidebar & Content -->
    <div class="flex">
        <x-filament::sidebar />
        <x-filament::main>
            {{ $slot }}
        </x-filament::main>
    </div>
</x-filament::layouts.base>

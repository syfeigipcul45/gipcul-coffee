<x-filament::page>
    <!-- Dashboard Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4"> {{-- Added flexbox for layout --}}
            {{-- Icon for Sales --}}
            <div class="p-3 bg-blue-100 rounded-full" style="background-color: rgb(219 234 254 / var(--tw-bg-opacity, 1)); color: rgb(37 99 235)">
                <x-heroicon-o-currency-dollar class="h-6 w-6" /> {{-- Example icon for sales --}}
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Total Penjualan</h3>
                <p class="text-2xl font-bold" style="color: rgb(37 99 235)">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow flex items-center space-x-4"> {{-- Added flexbox for layout --}}
            {{-- Icon for Products --}}
            <div class="p-3 bg-green-100 rounded-full text-green-600" style=" background-color: rgb(220 252 231 / var(--tw-bg-opacity, 1)); color: rgb(22 163 74 / var(--tw-text-opacity, 1))">
                <x-heroicon-o-archive-box class="h-6 w-6" /> {{-- Example icon for products --}}
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Total Produk</h3>
                <p class="text-2xl font-bold" style="color: rgb(37 99 235)">{{ $totalProducts ?? 0 }}</p>
            </div>
        </div>
    </div>
</x-filament::page>

<?php

namespace App\Filament\Widgets;

use App\Models\DetailPenjualan;
use App\Models\PembelianBahan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Resep;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\HtmlString;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSales = Penjualan::sum('total_harga');
        $totalProducts = Produk::count();
        $totalPurchases = PembelianBahan::sum('total_harga');
        $hpps = Resep::all();
        $sales = DetailPenjualan::all();
        $keuntungan = 0;
        $produkTerlaris = DetailPenjualan::select('produk_id')
            ->selectRaw('SUM(qty) as total_jual')
            ->with('produk') // ambil data produk
            ->groupBy('produk_id')
            ->orderByDesc('total_jual')
            ->take(5)
            ->get();

        $produkList = $produkTerlaris
            ->map(fn($item) => "<span class='text-sm'>{$item->produk->nama_produk} - {$item->total_jual}</span>")
            ->implode('<br>');

        foreach ($sales as $item) {
            foreach ($hpps as $hpp) {
                // Assuming 'produk_id' is the foreign key in DetailPenjualan that relates to Resep
                // and 'hpp' is the cost price in Resep
                if ($item->produk_id == $hpp->produk_id) {
                    $keuntungan += $item->harga - $hpp->hpp;
                }
            }
        }

        return [
            Stat::make('Total Penjualan', 'Rp ' . number_format($totalSales, 0, ',', '.'))
                ->description('All-time sales')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Produk', $totalProducts)
                ->description('Available products')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('info'),
            Stat::make('Total Pembelian Bahan', 'Rp ' . number_format($totalPurchases))
                ->description('Total bahan purchased')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
            Stat::make('Keuntungan Bersih', 'Rp ' . number_format($keuntungan, 0, ',', '.'))
                ->description('Total keuntungan bersih dari penjualan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),
            Stat::make('Produk Terlaris', new HtmlString($produkList))
                ->description('Top selling products')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('secondary'),
        ];
    }
}

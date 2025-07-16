<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use App\Models\Produk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSales = Penjualan::sum('total_harga');
        $totalProducts = Produk::count();

        return [
            Stat::make('Total Penjualan', 'Rp ' . number_format($totalSales, 0, ',', '.'))
                ->description('All-time sales')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Produk', $totalProducts)
                ->description('Available products')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('info'),
        ];
    }
}

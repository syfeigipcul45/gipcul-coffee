<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Models\Penjualan;
use App\Models\Produk;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    // public function getHeaderWidget(): array
    // {
    //     return [
    //         StatsOverview::class
    //     ];
    // }

    public $totalSales;
    public $totalProducts;

    /**
     * The mount method is called when the component is initialized.
     * This is a good place to fetch your data.
     */
    public function mount(): void
    {
        // Fetch total sales from your Order model
        // Assuming 'total_amount' is the column that stores the sales value
        $this->totalSales = Penjualan::sum('total_harga');

        // Fetch total products from your Product model
        $this->totalProducts = Produk::count();
    }

    /**
     * This method automatically makes the public properties of the page
     * available to your Blade view.
     * So, $totalSales and $totalProducts will be accessible in dashboard.blade.php.
     */
    protected function getViewData(): array
    {
        return [
            'totalSales' => $this->totalSales,
            'totalProducts' => $this->totalProducts,
        ];
    }
}

<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Models\DetailPenjualan;
use App\Models\PembelianBahan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Resep;
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
    public $totalPurchases;
    public $keuntungan;
    public $hpps;
    public $sales;
    public $produkTerlaris;
    public $produkList;

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

        // Fetch total purchases from your PembelianBahan model
        $this->totalPurchases = PembelianBahan::sum('total_harga');
        $hpps = Resep::all();
        $sales = DetailPenjualan::all();
        $this->keuntungan = 0;
        foreach ($sales as $item) {
            foreach ($hpps as $hpp) {
                // Assuming 'produk_id' is the foreign key in DetailPenjualan that relates to Resep
                // and 'hpp' is the cost price in Resep
                if ($item->produk_id == $hpp->produk_id) {
                    $this->keuntungan += $item->harga - $hpp->hpp;
                }
            }
        }

        $produkTerlaris = DetailPenjualan::select('produk_id')
            ->selectRaw('SUM(qty) as total_jual')
            ->with('produk') // ambil data produk
            ->groupBy('produk_id')
            ->orderByDesc('total_jual')
            ->take(5)
            ->get();

        $this->produkList = $produkTerlaris
            ->map(fn($item) => "<span class='text-sm'>{$item->produk->nama_produk} - {$item->total_jual}</span>")
            ->implode('<br>');
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
            'totalPurchases' => $this->totalPurchases,
            'keuntungan' => $this->keuntungan,
            'produkList' => $this->produkList ?: 'Tidak ada penjualan',
        ];
    }
}

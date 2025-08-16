<?php

namespace App\Filament\Pages;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class SalesReport extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.sales-report';

    public ?array $data = [];
    protected $listeners = ['updateCharts' => '$refresh'];

    public function mount(): void
    {
        $this->form->fill([
            'month' => now()->month,
            'year' => now()->year,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('month')
                ->label('Bulan')
                ->options([
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ])
                ->live()
                ->afterStateUpdated(fn() => $this->dispatch('updateCharts'))
                ->default(now()->month)
                ->native(false),

            Select::make('year')
                ->label('Tahun')
                ->options(array_combine(
                    $years = range(2020, now()->year),
                    $years
                ))
                ->live()
                ->afterStateUpdated(fn() => $this->dispatch('updateCharts'))
                ->default(now()->year)
                ->native(false),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    public function getCurrentMonth(): int
    {
        return $this->form->getState()['month'] ?? now()->month;
    }

    public function getCurrentYear(): int
    {
        return $this->form->getState()['year'] ?? now()->year;
    }

    public function getSalesData(): array
    {
        $month = $this->getCurrentMonth();
        $year = $this->getCurrentYear();

        $sales = Penjualan::query()
            ->select(
                DB::raw('DAY(tanggal_beli) as day'),
                DB::raw('SUM(total_harga) as total_sales')
            )
            ->whereYear('tanggal_beli', $year)
            ->whereMonth('tanggal_beli', $month)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Pastikan labels dan data selalu berupa array
        $labels = array_map(fn($i) => "Tanggal " . ($i + 1), range(0, $daysInMonth - 1));
        $data = array_fill(0, $daysInMonth, 0);

        foreach ($sales as $sale) {
            if ($sale->day >= 1 && $sale->day <= $daysInMonth) {
                $data[$sale->day - 1] = (float)$sale->total_sales;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function getSumQty(): array
    {
        $month = $this->getCurrentMonth();
        $year = $this->getCurrentYear();

        $sumQty = DetailPenjualan::query()
            ->select(
                DB::raw('DAY(penjualans.tanggal_beli) as day'),
                DB::raw('SUM(detail_penjualans.qty) as total_quantity_sold')
            )
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->whereYear('penjualans.tanggal_beli', $year)
            ->whereMonth('penjualans.tanggal_beli', $month)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Pastikan labels dan data selalu berupa array
        $labels = array_map(fn($i) => "Tanggal " . ($i + 1), range(0, $daysInMonth - 1));
        $data = array_fill(0, $daysInMonth, 0);

        foreach ($sumQty as $sale) {
            if ($sale->day >= 1 && $sale->day <= $daysInMonth) {
                $data[$sale->day - 1] = (float)$sale->total_quantity_sold;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function getTopProductsData(): array
    {
        $month = $this->getCurrentMonth();
        $year = $this->getCurrentYear();

        $topProducts = DetailPenjualan::query()
            ->select('produks.nama_produk', DB::raw('SUM(detail_penjualans.qty) as total_quantity_sold'))
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->join('produks', 'detail_penjualans.produk_id', '=', 'produks.id')
            ->whereYear('penjualans.tanggal_beli', $year)
            ->whereMonth('penjualans.tanggal_beli', $month)
            ->groupBy('produks.nama_produk')
            ->orderByDesc('total_quantity_sold')
            ->limit(5)
            ->get();

        // Pastikan selalu mengembalikan array dengan format yang konsisten
        return [
            'labels' => $topProducts->isEmpty() ? ['Tidak ada data'] : $topProducts->pluck('nama_produk')->toArray(),
            'data' => $topProducts->isEmpty() ? [0] : $topProducts->pluck('total_quantity_sold')->map(fn($item) => (int)$item)->toArray(),
        ];
    }
}

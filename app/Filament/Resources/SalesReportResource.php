<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesReportResource\Pages;
use App\Filament\Resources\SalesReportResource\RelationManagers;
use App\Models\Penjualan;
use App\Models\SalesReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesReportResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Penjualan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->rowIndex()
                    ->label('No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_beli')
                    ->label('Tanggal Penjualan')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('idr', true)
                    ->sortable(),
            ])
            ->defaultSort('tanggal_beli', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('month')
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
                    ->query(function (Builder $query, $data) {
                        if (!empty($data['value'])) {
                            $query->whereMonth('tanggal_beli', $data['value']);
                        }
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!isset($data['value'])) {
                            return null;
                        }
                        return 'Bulan: ' . [
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
                        ][$data['value']];
                    }),

                Tables\Filters\SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(array_combine(
                        $years = range(2020, now()->year),
                        $years
                    ))
                    ->query(function (Builder $query, $data) {
                        if (!empty($data['value'])) {
                            $query->whereYear('tanggal_beli', $data['value']);
                        }
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!isset($data['value'])) {
                            return null;
                        }
                        return 'Tahun: ' . $data['value'];
                    }),
            ])->headerActions([])->bulkActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesReports::route('/'),
        ];
    }
}

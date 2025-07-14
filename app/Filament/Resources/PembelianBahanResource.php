<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianBahanResource\Pages;
use App\Filament\Resources\PembelianBahanResource\RelationManagers;
use App\Models\BahanPokok;
use App\Models\PembelianBahan;
use App\Models\Satuan;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembelianBahanResource extends Resource
{
    protected static ?string $model = PembelianBahan::class;

    protected static ?string $navigationLabel = 'Pembelian Bahan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Sales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pembelian')
                    ->schema([
                        DatePicker::make('tanggal_beli')
                            ->label('Tanggal Beli')
                            ->default(fn() => now()->format('Y-m-d')) // Use Laravel's now() helper
                            ->required()
                            ->placeholder('Pilih tanggal beli'),
                        TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->numeric()
                            ->required()
                            ->prefix('Rp ')
                            ->readonly()
                            // Only dehydrate if not null to save it. If you always want to save, remove this.
                            // Consider if total_harga should always be calculated from detailPembelians
                            // and not directly stored if you want to avoid discrepancies.
                            ->dehydrated(fn($state) => !is_null($state))
                            ->placeholder('Masukkan total harga'),
                    ]),
                Section::make('Detail Pembelian')
                    ->schema([
                        Repeater::make('detailPembelians')
                            ->relationship()
                            ->label('Daftar Bahan Pembelian')
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Ensure $state is a collection and calculate sum
                                $total = collect($state)->sum(fn($item) => (float)($item['harga'] ?? 0));
                                $set('total_harga', $total);
                            })
                            ->schema([
                                Select::make('bahan_id')
                                    ->label('Bahan')
                                    ->options(BahanPokok::all()->pluck('nama_bahan', 'id'))
                                    ->required()
                                    ->live()
                                    ->columnSpan(2)
                                    ->placeholder('Pilih bahan'),
                                TextInput::make('jumlah_berat')
                                    ->label('Jumlah Berat')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(1)
                                    ->live(),
                                Select::make('satuan_id')
                                    ->label('Satuan')
                                    ->options(Satuan::all()->pluck('nama_satuan', 'id'))
                                    ->required()
                                    ->live()
                                    ->columnSpan(2)
                                    ->placeholder('Pilih satuan'),
                                TextInput::make('harga')
                                    ->label('Harga')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->prefix('Rp ')
                                // You might want to make this live as well if you have quantity * price calculations
                                // ->live()
                                ,
                            ])
                            ->columns(7) // This refers to the grid layout *within* each repeater item
                            ->grid(1) // This means the repeater itself will be a single column.
                            // If you want each repeater item to be on its own line, this is fine.
                            ->collapsible()
                            ->reorderable()
                            ->defaultItems(1)
                            ->columnSpanFull()
                            ->label('Daftar Bahan Pembelian')
                            ->addActionLabel('+ Tambah Bahan'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                PembelianBahan::query()->withCount('detailPembelians')
            )
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->sortable()
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('tanggal_beli')
                    ->label('Tanggal Beli')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('detail_pembelians_count')
                    ->label('Jumlah Bahan')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Pembelian berhasil dihapus.')
                            ->body('Data pembelian telah dihapus dengan sukses.')
                    ),
            ]);
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
            'index' => Pages\ListPembelianBahans::route('/'),
            'create' => Pages\CreatePembelianBahan::route('/create'),
            'edit' => Pages\EditPembelianBahan::route('/{record}/edit'),
        ];
    }
}

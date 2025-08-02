<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Models\Penjualan;
use App\Models\Produk;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Sales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Penjualan')
                    ->schema([
                        TextInput::make('nama_pembeli')
                            ->label('Nama Pembeli')
                            ->required()
                            ->placeholder('Masukkan nama pembeli'),
                        DatePicker::make('tanggal_beli')
                            ->label('Tanggal Beli')
                            ->default(fn() => (new DateTime())->format('Y-m-d'))
                            ->required()
                            ->placeholder('Pilih tanggal beli'),
                        TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->numeric()
                            ->required()
                            ->prefix('Rp ')
                            ->readonly()
                            ->dehydrated(fn($state) => !is_null($state)) // Hanya simpan jika tidak null
                            ->placeholder('Masukkan total harga'),
                        Select::make('jenis_pembayaran')
                            ->label('Jenis Pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'qris' => 'QRIS',
                                'transfer' => 'Transfer',
                            ])
                            ->placeholder('Pilih jenis pembayaran'),
                        TextInput::make('kasir')
                            ->default(Auth::user()->name)
                            ->readonly()
                            ->label('Kasir')
                            ->dehydrated(false), // Jangan simpan ke database,
                        Hidden::make('user_id')
                            ->default(Auth::user()->id)
                    ]),
                Section::make('Detail Penjualan')
                    ->schema([
                        Repeater::make('detailPenjualans')
                            ->relationship() // Gunakan relationship
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Hitung total harga ketika ada perubahan
                                $total = collect($state)->sum(fn($item) => ($item['harga'] ?? 0) * ($item['qty'] ?? 0));
                                $set('total_harga', $total);
                            })
                            ->schema([
                                Select::make('produk_id')
                                    ->label('Produk')
                                    ->options(Produk::all()->pluck('nama_produk', 'id'))
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        // Ambil harga produk ketika produk dipilih
                                        $produk = Produk::find($state);
                                        if ($produk) {
                                            $set('harga', $produk->harga);
                                        }
                                    })
                                    ->columnSpan(2)
                                    ->placeholder('Pilih produk'),
                                TextInput::make('qty')
                                    ->label('Qty')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        // Hitung subtotal ketika qty atau harga berubah
                                        $harga = $get('harga') ?? 0;
                                        $qty = $state;
                                        $subtotal = $harga * $qty;
                                        $set('subtotal', $subtotal);
                                    }),
                                TextInput::make('harga')
                                    ->label('Harga')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->readonly()
                                    ->prefix('Rp '),
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->readonly()
                                    ->prefix('Rp ')
                                    ->columnSpan(2)
                                    ->dehydrated(false) // Jangan simpan subtotal ke database
                                    ->live()
                                    ->afterStateHydrated(function ($set, $get) {
                                        // Hitung ulang subtotal saat form di-load
                                        $harga = $get('harga') ?? 0;
                                        $qty = $get('qty') ?? 0;
                                        $subtotal = $harga * $qty;
                                        $set('subtotal', $subtotal);
                                    })
                            ])
                            ->columns(7)
                            ->grid(1)
                            ->collapsible()
                            ->reorderable()
                            ->defaultItems(1)
                            ->columnSpanFull()
                            ->label('Daftar Produk')
                            ->addActionLabel('+ Tambah Produk')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Penjualan::query()->withCount('detailPenjualans')
            )
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->sortable()
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nama_pembeli')
                    ->label('Nama Pembeli')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_beli')
                    ->label('Tanggal Beli')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('detail_penjualans_count')
                    ->label('Jumlah Produk'),
                Tables\Columns\TextColumn::make('jenis_pembayaran')
                    ->label('Jenis Pembayaran'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kasir')
                    ->sortable(),
            ])->filters([
                //
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
                            ->title('Penjualan berhasil dihapus.')
                            ->body('Data penjualan telah dihapus dengan sukses.')
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
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}

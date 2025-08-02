<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResepResource\Pages;
use App\Models\BahanPokok;
use App\Models\DetailResep;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\Satuan;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ResepResource extends Resource
{
    protected static ?string $model = Resep::class;

    protected static ?string $navigationLabel = 'Resep Minuman';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Resep')
                    ->schema([
                        Select::make('produk_id')
                            ->label('Produk')
                            ->options(Produk::all()->pluck('nama_produk', 'id'))
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Pilih produk untuk resep'),
                        Textarea::make('deskripsi')
                            ->label('Deskripsi Resep')
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Masukkan deskripsi resep'),
                        TextInput::make('hpp')
                            ->label('HPP (Harga Pokok Penjualan)')
                            ->numeric()
                            ->prefix('Rp ')
                            ->readonly()
                            // Only dehydrate if not null to save it. If you always want to save, remove this.
                            // Consider if total_harga should always be calculated from detailPembelians
                            // and not directly stored if you want to avoid discrepancies.
                            ->dehydrated(fn($state) => !is_null($state))
                            ->placeholder('Masukkan total harga'),
                    ]),

                Section::make('Bahan-bahan Resep')
                    ->schema([
                        Repeater::make('detailReseps')
                            ->relationship() // Gunakan relationship
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Ensure $state is a collection and calculate sum
                                $total = collect($state)->sum(fn($item) => (float)($item['harga_pokok'] ?? 0));
                                $set('hpp', $total);
                            })
                            ->schema([
                                Select::make('bahan_id')
                                    ->label('Bahan Pokok')
                                    ->options(BahanPokok::orderBy('nama_bahan', 'asc')->pluck('nama_bahan', 'id'))
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        // Ambil harga produk ketika produk dipilih
                                        $bahan = BahanPokok::find($state);
                                        if ($bahan) {
                                            $set('harga_bahan', $bahan->harga);
                                        }
                                    })
                                    ->columnSpan(2)
                                    ->placeholder('Pilih bahan pokok'),
                                TextInput::make('jumlah_berat')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        // Hitung subtotal ketika qty atau harga berubah
                                        $bahan = $get('bahan_id');
                                        $bahanPokok = BahanPokok::find($bahan);
                                        $harga = $bahanPokok->harga / $bahanPokok->jumlah_berat;
                                        $qty = $state;
                                        $subtotal = $harga * $qty;
                                        $set('harga_pokok', $subtotal);
                                    }),
                                Select::make('satuan_id')
                                    ->label('Satuan')
                                    ->options(Satuan::all()->pluck('nama_satuan', 'id'))
                                    ->required()
                                    ->columnSpan(1)
                                    ->placeholder('Pilih satuan'),
                                TextInput::make('harga_bahan')
                                    ->label('Harga Bahan')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(1)
                                    ->readonly(),
                                TextInput::make('harga_pokok')
                                    ->label('Harga Pokok')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(1)
                                    ->readonly(),
                            ])
                            ->columns(7)
                            ->grid(1)
                            ->collapsible()
                            ->reorderable()
                            ->defaultItems(1)
                            ->columnSpanFull()
                            ->label('Daftar Bahan Pokok')
                            ->addActionLabel('+ Tambah Bahan')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Nama Produk')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('hpp')
                    ->label('HPP')
                    ->sortable()
                    ->prefix('Rp ')
                    ->numeric(),
            ])
            ->defaultSort('produk.nama_produk', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->action(function (Resep $record) {
                        return Action::openModal('resep-detail-modal', [
                            'resep' => $record,
                        ]);
                    })
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Resep dihapus')
                            ->body('Resep telah berhasil dihapus.')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListReseps::route('/'),
            'create' => Pages\CreateResep::route('/create'),
            'edit' => Pages\EditResep::route('/{record}/edit'),
        ];
    }
}

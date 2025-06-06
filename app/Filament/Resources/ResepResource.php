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
                    ]),

                Section::make('Bahan-bahan Resep')
                    ->schema([
                        Repeater::make('detailReseps')
                            ->relationship() // Gunakan relationship
                            ->schema([
                                Select::make('bahan_id')
                                    ->label('Bahan Pokok')
                                    ->options(BahanPokok::all()->pluck('nama_bahan', 'id'))
                                    ->required()
                                    ->columnSpan(3)
                                    ->placeholder('Pilih bahan pokok'),
                                TextInput::make('jumlah_berat')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(2),
                                Select::make('satuan_id')
                                    ->label('Satuan')
                                    ->options(Satuan::all()->pluck('nama_satuan', 'id'))
                                    ->required()
                                    ->columnSpan(2)
                                    ->placeholder('Pilih satuan'),
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
                    ->searchable()
            ])
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

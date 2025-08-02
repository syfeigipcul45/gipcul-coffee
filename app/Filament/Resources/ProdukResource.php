<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('gambar')
                    ->collection('produk')
                    ->label('Foto Produk'),
                TextInput::make('nama_produk')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Produk'),
                TextInput::make('harga')
                    ->required()
                    ->label('Harga')
                    ->minValue(0)
                    ->maxValue(1000000000)
                    ->prefix('Rp')
                    ->placeholder('Masukkan harga produk'),
                Select::make('kategori')
                    ->options([
                        'kopi' => 'Kopi',
                        'non_kopi' => 'Non Kopi',
                    ])
                    ->label('Kategori Minuman')
                    ->placeholder('Pilih kategori produk'),
                RichEditor::make('deskripsi')
                    ->disableGrammarly()
                    ->label('Deskripsi Produk')
                    ->columnSpanFull()
                    ->placeholder('Masukkan deskripsi produk')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                SpatieMediaLibraryImageColumn::make('gambar')
                    ->disk('public')->visibility('public')
                    ->collection('produk')
                    ->label('Foto Produk'),
                TextColumn::make('nama_produk')
                    ->sortable()
                    ->searchable()
                    ->label('Nama Produk'),
                TextColumn::make('harga')
                    ->sortable()
                    ->searchable()
                    ->label('Harga (Rp)')
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.')),
                TextColumn::make('kategori')
                ->sortable(),
                TextColumn::make('deskripsi')
                    ->limit(50)
                    ->label('Deskripsi')
            ])
            ->defaultSort('nama_produk', 'asc')
            ->filters([
                SelectFilter::make('kategori')
                    ->label('Filter Kategori')
                    ->options([
                        'kopi' => 'Kopi',
                        'non_kopi' => 'Non Kopi',
                    ])
                    ->searchable() // Opsional: jika ingin bisa mencari di dropdown
                    ->placeholder('Pilih Kategori'), // Opsional: teks placeholder
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Produk dihapus')
                            ->body('Produk telah berhasil dihapus.')
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}

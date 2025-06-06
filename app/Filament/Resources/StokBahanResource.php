<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokBahanResource\Pages;
use App\Filament\Resources\StokBahanResource\RelationManagers;
use App\Models\StokBahan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StokBahanResource extends Resource
{
    protected static ?string $model = StokBahan::class;
    protected static ?string $navigationLabel = 'Stok Bahan';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('bahan_id')
                    ->label('Pilih Bahan Pokok')
                    ->relationship('bahanPokok', 'nama_bahan')
                    ->required()
                    ->placeholder('Pilih bahan pokok'),
                Forms\Components\TextInput::make('jumlah_berat')
                    ->label('Jumlah Berat')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(1000000)
                    ->placeholder('Masukkan jumlah berat bahan'),
                Forms\Components\Select::make('satuan_id')
                    ->label('Pilih Satuan')
                    ->relationship('satuan', 'nama_satuan')
                    ->required()
                    ->placeholder('Pilih satuan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('bahanPokok.nama_bahan')
                    ->label('Bahan Pokok')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_berat')
                    ->label('Jumlah Berat')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\TextColumn::make('satuan.nama_satuan')
                    ->label('Satuan')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Stok bahan dihapus')
                            ->body('Stok bahan telah berhasil dihapus.')
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
            'index' => Pages\ListStokBahans::route('/'),
            'create' => Pages\CreateStokBahan::route('/create'),
            'edit' => Pages\EditStokBahan::route('/{record}/edit'),
        ];
    }
}

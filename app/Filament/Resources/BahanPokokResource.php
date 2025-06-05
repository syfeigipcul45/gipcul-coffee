<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanPokokResource\Pages;
use App\Filament\Resources\BahanPokokResource\RelationManagers;
use App\Models\BahanPokok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BahanPokokResource extends Resource
{
    protected static ?string $model = BahanPokok::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_bahan')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Bahan Pokok'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('nama_bahan')
                    ->label('Nama Bahan Pokok')
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
                        Notification::make()
                            ->success()
                            ->title('Bahan pokok dihapus')
                            ->body('Bahan pokok telah berhasil dihapus.')
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
            'index' => Pages\ListBahanPokoks::route('/'),
            'create' => Pages\CreateBahanPokok::route('/create'),
            'edit' => Pages\EditBahanPokok::route('/{record}/edit'),
        ];
    }
}

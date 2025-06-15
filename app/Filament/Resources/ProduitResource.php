<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProduitResource\Pages;
use App\Models\Produit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProduitResource extends Resource
{
    protected static ?string $model = Produit::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Gestion des produits';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('categorie_id')
                    ->relationship('categorie', 'name')
                    ->required(),
                Forms\Components\Select::make('producer_id')
                    ->relationship('producer', 'name')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('measure')
                    ->options([
                        'kg' => 'Kilogramme',
                        'g' => 'Gramme',
                        'L' => 'Litre',
                        'unité' => 'Unité',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('isbio')
                    ->label('Produit bio')
                    ->default(false),
                Forms\Components\FileUpload::make('image_principale')
                    ->image()
                    ->directory('produits')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('images_secondaires')
                    ->multiple()
                    ->image()
                    ->directory('produits/secondaires')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('categorie.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('producer.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('measure')
                    ->badge(),
                Tables\Columns\IconColumn::make('isbio')
                    ->boolean(),
                Tables\Columns\ImageColumn::make('image_principale'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categorie')
                    ->relationship('categorie', 'name'),
                Tables\Filters\SelectFilter::make('producer')
                    ->relationship('producer', 'name'),
                Tables\Filters\TernaryFilter::make('isbio')
                    ->label('Produit bio'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProduits::route('/'),
            'create' => Pages\CreateProduit::route('/create'),
            'edit' => Pages\EditProduit::route('/{record}/edit'),
        ];
    }
} 
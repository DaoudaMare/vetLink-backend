<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommandeResource\Pages;
use App\Models\Commande;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommandeResource extends Resource
{
    protected static ?string $model = Commande::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Gestion des commandes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('num')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Forms\Components\Select::make('product_id')
                    ->relationship('produit', 'name')
                    ->required(),
                Forms\Components\TextInput::make('Quantity')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        0 => 'En attente',
                        1 => 'En cours',
                        2 => 'Validée',
                        3 => 'Annulée',
                    ])
                    ->required(),
                Forms\Components\Select::make('delivery_status')
                    ->options([
                        0 => 'Non livré',
                        1 => 'En cours de livraison',
                        2 => 'Livré',
                        3 => 'Retourné',
                    ])
                    ->required(),
                Forms\Components\Select::make('payment')
                    ->options([
                        0 => 'Non payé',
                        1 => 'Payé',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('num')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('produit.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 0,
                        'primary' => 1,
                        'success' => 2,
                        'danger' => 3,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'En attente',
                        '1' => 'En cours',
                        '2' => 'Validée',
                        '3' => 'Annulée',
                    }),
                Tables\Columns\BadgeColumn::make('delivery_status')
                    ->colors([
                        'warning' => 0,
                        'primary' => 1,
                        'success' => 2,
                        'danger' => 3,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'Non livré',
                        '1' => 'En cours de livraison',
                        '2' => 'Livré',
                        '3' => 'Retourné',
                    }),
                Tables\Columns\BadgeColumn::make('payment')
                    ->colors([
                        'danger' => 0,
                        'success' => 1,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '0' => 'Non payé',
                        '1' => 'Payé',
                    }),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        0 => 'En attente',
                        1 => 'En cours',
                        2 => 'Validée',
                        3 => 'Annulée',
                    ]),
                Tables\Filters\SelectFilter::make('delivery_status')
                    ->options([
                        0 => 'Non livré',
                        1 => 'En cours de livraison',
                        2 => 'Livré',
                        3 => 'Retourné',
                    ]),
                Tables\Filters\SelectFilter::make('payment')
                    ->options([
                        0 => 'Non payé',
                        1 => 'Payé',
                    ]),
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
            'index' => Pages\ListCommandes::route('/'),
            'create' => Pages\CreateCommande::route('/create'),
            'edit' => Pages\EditCommande::route('/{record}/edit'),
        ];
    }
} 
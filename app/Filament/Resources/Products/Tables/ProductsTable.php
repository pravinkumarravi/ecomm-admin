<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\ProductStatus;
use App\Filament\Imports\ProductImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->headerActions([
                ImportAction::make()
                ->importer(ProductImporter::class),
            ])
            ->columns([
                ImageColumn::make('images.image')
                    ->imageHeight(40)
                    ->disk('public')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->defaultImageUrl(url('/images/image-not-found.png')),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type'),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->sortable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable()
                    ->money('INR', divideBy: 100),
                TextColumn::make('sale_price')
                    ->label('Sale Price')
                    ->numeric()
                    ->sortable()
                    ->money('INR', divideBy: 100),
                TextColumn::make('cost_price')
                    ->label('Cost Price')
                    ->numeric()
                    ->sortable()
                    ->money('INR', divideBy: 100),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (ProductStatus $state): string => $state->color())
                    ->formatStateUsing(fn (ProductStatus $state): string => $state->label()),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

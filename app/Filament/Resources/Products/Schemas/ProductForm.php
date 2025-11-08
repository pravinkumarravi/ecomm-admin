<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->description('Core product details')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->native(false)
                            ->default(null),
                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->native(false)
                            ->default(null),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        Select::make('type')
                            ->options(ProductType::class)
                            ->native(false)
                            ->default(ProductType::PHYSICAL)
                            ->required(),
                        Select::make('status')
                            ->options(ProductStatus::class)
                            ->native(false)
                            ->default('draft')
                            ->required(),
                    ])->columns(2),

                Section::make('Pricing')
                    ->description('Price and cost information')
                    ->schema([
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('₹'),
                        TextInput::make('sale_price')
                            ->numeric()
                            ->default(null)
                            ->prefix('₹'),
                        TextInput::make('cost_price')
                            ->numeric()
                            ->default(null)
                            ->prefix('₹'),
                    ])->columns(2),

                Section::make('Inventory')
                    ->description('Stock and SKU')
                    ->schema([
                        TextInput::make('stock')
                            ->required()
                            ->integer()
                            ->minValue(0)
                            ->default(0),
                        Toggle::make('in_stock')
                            ->required(),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->default(null),
                    ])->columns(2),

                Section::make('Descriptions')
                    ->description('Product descriptions')
                    ->schema([
                        Textarea::make('short_description')
                            ->default(null)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->default(null)
                            ->columnSpanFull(),
                    ])->columns(1),

                Section::make('Flags')
                    ->description('Flags and visibility')
                    ->schema([
                        Toggle::make('is_featured')
                            ->required(),
                    ])->columns(1),

                Section::make('Media')
                    ->description('Upload product images and videos.')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Upload Product Images')
                            ->helperText('Upload multiple product images.')
                            ->multiple()
                            ->image()
                            ->disk('public')
                            ->directory('products')
                            ->visibility('public')
                            ->previewable(true)
                            ->reactive()
                            ->saveRelationshipsUsing(function ($component, $state, Product $product) {
                                foreach ($state as $filePath) {
                                    $product->images()->create([
                                        'image' => $filePath,
                                        'original_name' => $component->getStatePath('original_name')[$filePath] ?? null,
                                    ]);
                                }
                            }),
                    ])->columns(1),
                
            ])->columns(1);
    }
}

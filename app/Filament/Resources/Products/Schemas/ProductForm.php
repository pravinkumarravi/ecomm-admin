<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

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
                            ->default(null)
                            ->preload(true)
                            ->createOptionForm([
                                Select::make('parent_id')
                                    ->relationship('parent', 'name')
                                    ->native(false)
                                    ->default(null)
                                    ->preload(true),
                                TextInput::make('name')
                                    ->label('Category Name')
                                    ->reactive()
                                    ->required()
                                    ->afterStateUpdated(function (TextInput $component, $state, callable $set) {
                                        $set('slug', Str::slug($state));
                                    }),
                                TextInput::make('slug')
                                    ->label('Category Slug')
                                    ->required()
                                    ->unique(table: Category::class, ignoreRecord: true),
                                FileUpload::make('image')
                                    ->label('Category Image')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->previewable(true)
                                    ->image()
                                    ->directory('images/category')
                                    ->required(),
                                Toggle::make('is_popular')
                                    ->label('Is Popular')
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Is Active')
                                    ->default(true)
                                    ->required(),
                            ]),
                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->native(false)
                            ->default(null)
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Brand Name')
                                    ->reactive()
                                    ->required()
                                    ->afterStateUpdated(function (TextInput $component, $state, callable $set) {
                                        $set('slug', Str::slug($state));
                                    }),
                                TextInput::make('slug')
                                    ->label('Brand Slug')
                                    ->required()
                                    ->unique(table: Product::class, ignoreRecord: true),
                                FileUpload::make('image')
                                    ->label('Brand Image')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->previewable(true)
                                    ->image()
                                    ->directory('images/brand')
                                    ->required(),
                                Toggle::make('is_popular')
                                    ->label('Is Popular')
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Is Active')
                                    ->default(true)
                                    ->required(),
                            ]),
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
                            ->prefixIcon(Heroicon::OutlinedCurrencyRupee),
                        TextInput::make('sale_price')
                            ->numeric()
                            ->default(null)
                            ->prefixIcon(Heroicon::OutlinedCurrencyRupee),
                        TextInput::make('cost_price')
                            ->numeric()
                            ->default(null)
                            ->prefixIcon(Heroicon::OutlinedCurrencyRupee),
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
                    ->description('Upload product images.')
                    ->schema([
                        Repeater::make('images')
                            ->label('Product Images')
                            ->relationship('images')
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Is Featured')
                                    ->helperText('Mark this image as featured.'),
                                FileUpload::make('image')
                                    ->label('Upload Image')
                                    ->helperText('Upload a product image.')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->different('images/product')
                                    ->previewable(true)
                                    ->image()
                                    ->required(),
                            ]),
                    ])->columns(1),

                    Section::make('Attributes')
                        ->description('Manage product attributes.')
                        ->schema([
                            Repeater::make('attributes')
                                ->label('Product Attributes')
                                ->relationship('attributes')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Attribute Name')
                                        ->required(),
                                    TextInput::make('value')
                                        ->label('Attribute Value')
                                        ->required(),
                                ])->columns(2),
                    ])->columns(1),

                    Section::make('Specifications')
                        ->description('Manage product specifications.')
                        ->schema([
                            Repeater::make('specifications')
                                ->label('Product Specifications')
                                ->relationship('specifications')
                                ->schema([
                                    TextInput::make('key')
                                        ->label('Specification Key')
                                        ->required(),
                                    TextInput::make('value')
                                        ->label('Specification Value')
                                        ->required(),
                                ])->columns(2),
                    ])->columns(1),
                
            ])->columns(1);
    }
}

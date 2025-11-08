<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review Details')
                    ->description('Provide the details for the review.')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->native()
                            ->preload()
                            ->required(),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->native()
                            ->preload()
                            ->required(),
                        TextInput::make('rating')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5),
                        Textarea::make('comment')
                            ->default(null)
                            ->columnSpanFull(),
                    ])->columns(2),
            ])->columns(1);
    }
}

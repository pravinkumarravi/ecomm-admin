<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Brand Details')
                ->description('Provide the necessary information for the brand.')
                    ->schema([
                        TextInput::make('name')
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function (TextInput $component, ?string $state, callable $set) {
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->required(),
                        FileUpload::make('image')
                            ->label('Brand Image')
                            ->image()
                            ->disk('public')
                            ->directory('images/brand')
                            ->required()
                            ->columnSpanFull(),
                        Toggle::make('is_popular')
                            ->label('Popular')
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->required(),
                    ])->columns(2),
            ])->columns(1);
    }
}

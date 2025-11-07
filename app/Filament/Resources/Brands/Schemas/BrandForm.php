<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Brand Details')
                ->description('Provide the necessary information for the brand.')
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->required(),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        Toggle::make('is_popular')
                            ->required(),
                        Toggle::make('is_active')
                            ->required(),
                    ])->columns(1),
            ]);
    }
}

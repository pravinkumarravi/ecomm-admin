<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('Provide the necessary information for the category.')
                    ->schema([
                        TextInput::make('parent_id')
                            ->numeric()
                            ->default(null),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        FileUpload::make('image')
                            ->image()
                            ->required(),
                        Toggle::make('is_popular')
                            ->required(),
                        Toggle::make('is_active')
                            ->required(),
                    ])->columns(2),
            ]);
    }
}

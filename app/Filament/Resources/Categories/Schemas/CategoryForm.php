<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('Provide the necessary information for the category.')
                    ->schema([
                        Select::make('parent_id')
                            ->relationship('parent', 'name')
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
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        FileUpload::make('image')
                                    ->label('Category Image')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->previewable(true)
                                    ->image()
                                    ->directory('images/category')
                                    ->required(),
                        Toggle::make('is_popular')
                            ->required(),
                        Toggle::make('is_active')
                            ->required(),
                    ])->columns(2),
            ])->columns(1);
    }
}

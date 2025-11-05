<?php

namespace App\Filament\Resources\Addresses\Schemas;

use App\Enums\AddressTypeEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('type')
                    ->options(AddressTypeEnum::class)
                    ->default('shipping')
                    ->required(),
                TextInput::make('name')
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('address_line_1')
                    ->required(),
                TextInput::make('address_line_2')
                    ->default(null),
                TextInput::make('address_line_3')
                    ->default(null),
                TextInput::make('city')
                    ->required(),
                TextInput::make('state')
                    ->required(),
                TextInput::make('postal_code')
                    ->required(),
                TextInput::make('country')
                    ->required()
                    ->default('IN'),
                Toggle::make('is_default')
                    ->required(),
            ]);
    }
}

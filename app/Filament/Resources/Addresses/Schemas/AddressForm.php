<?php

namespace App\Filament\Resources\Addresses\Schemas;

use App\Enums\AddressType;
use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->description('Address details for the user.')
                    ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->preload()
                        ->required()
                        ->native(false)
                        ->createOptionForm([
                            Section::make()->schema([
                                FileUpload::make('avatar')
                                    ->avatar()
                                    ->columnSpanFull(),
                                Hidden::make('role')->default(UserRole::CUSTOMER),
                                TextInput::make('name')
                                    ->required(),
                                TextInput::make('phone')
                                    ->tel()
                                    ->default(null),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->required(),
                                DateTimePicker::make('email_verified_at')
                                    ->label('Email Verified At')
                                    ->native(false),
                                TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->helperText('Leave blank to keep the current password.'),
                                Toggle::make('is_active')
                                    ->label('Is active')
                                    ->required()
                                    ->default(true)
                                    ->columnSpanFull(),
                            ])->columns(2),
                        ]),
                    Select::make('type')
                        ->options(AddressType::class)
                        ->default('shipping')
                        ->native(false)
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
                        ->required()
                        ->default(false)
                        ->columnSpanFull(),
                ])->columns(2),
            ])->columns(1);
    }
}

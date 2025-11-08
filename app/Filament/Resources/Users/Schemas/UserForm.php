<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Provide the basic information for the customer.')
                    ->components([
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
                            ->label('Email verified at')
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
            ])->columns(1);
    }
}

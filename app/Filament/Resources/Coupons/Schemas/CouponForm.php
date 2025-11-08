<?php

namespace App\Filament\Resources\Coupons\Schemas;

use App\Enums\CouponType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Coupon Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->unique(ignoreRecord: true)
                            ->required(),
                        TextInput::make('name')
                            ->default(null),
                        Textarea::make('description')
                            ->default(null)
                            ->columnSpanFull(),
                        Select::make('type')
                            ->options(CouponType::class)
                            ->default('fixed')
                            ->native(false)
                            ->required(),
                        TextInput::make('value')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefixIcon(Heroicon::OutlinedCurrencyRupee),
                        TextInput::make('max_discount')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(null)
                            ->prefixIcon(Heroicon::OutlinedCurrencyRupee),
                        TextInput::make('min_order_amount')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->prefixIcon(Heroicon::OutlinedCurrencyRupee),
                        TextInput::make('usage_limit')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('usage_limit_per_user')
                            ->numeric()
                            ->minValue(0)
                            ->default(null),
                        TextInput::make('used_count')
                            ->required()
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make('starts_at')
                            ->label('Start Date and Time')
                            ->required()
                            ->default(null)
                            ->native(false)
                            ->minDate(now())
                            ->prefixIcon(Heroicon::OutlinedCalendarDays),
                        DateTimePicker::make('expires_at')
                            ->label('Expiry Date and Time')
                            ->required()
                            ->native(false)
                            ->default(null)
                            ->minDate(now())
                            ->prefixIcon(Heroicon::OutlinedCalendarDays),
                        Toggle::make('is_active')
                            ->required()
                            ->columnSpanFull(),
                ])->columns(2),
            ])->columns(1);
    }
}

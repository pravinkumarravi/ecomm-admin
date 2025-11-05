<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentMethodEnum;
use App\Enums\PaymentStatusEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('coupon_id')
                    ->relationship('coupon', 'name')
                    ->default(null),
                Select::make('address_id')
                    ->relationship('address', 'name')
                    ->default(null),
                TextInput::make('order_number')
                    ->required(),
                TextInput::make('currency')
                    ->default(null),
                Select::make('status')
                    ->options(OrderStatusEnum::class)
                    ->default('pending')
                    ->required(),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('shipping_cost')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('coupon_code')
                    ->default(null),
                TextInput::make('coupon_value')
                    ->numeric()
                    ->default(null),
                Select::make('payment_method')
                    ->options(PaymentMethodEnum::class)
                    ->default('cod')
                    ->required(),
                Select::make('payment_status')
                    ->options(PaymentStatusEnum::class)
                    ->default('pending')
                    ->required(),
                TextInput::make('transaction_id')
                    ->default(null),
                TextInput::make('tracking_number')
                    ->default(null),
                TextInput::make('carrier')
                    ->default(null),
                DateTimePicker::make('ordered_at')
                    ->required(),
            ]);
    }
}

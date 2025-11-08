<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\PaymentGateway;
use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('order_id')
                            ->relationship('order', 'id')
                            ->default(null),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->default(null),
                        TextInput::make('transaction_number')
                            ->required(),
                        TextInput::make('gateway_transaction_id')
                            ->default(null),
                        Select::make('payment_method')
                            ->options(PaymentMethod::class)
                            ->default('cod')
                            ->required(),
                        Select::make('type')
                            ->options(TransactionType::class)
                            ->default('payment')
                            ->required(),
                        Select::make('status')
                            ->options(TransactionStatus::class)
                            ->default('pending')
                            ->required(),
                        TextInput::make('amount')
                            ->required()
                            ->numeric(),
                        TextInput::make('currency')
                            ->required()
                            ->default('INR'),
                        TextInput::make('gateway_fee')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        Select::make('gateway')
                            ->options(PaymentGateway::class)
                            ->default('razorpay'),
                        Textarea::make('notes')
                            ->default(null)
                            ->columnSpanFull(),
                        Textarea::make('failure_reason')
                            ->default(null)
                            ->columnSpanFull(),
                        DateTimePicker::make('attempted_at'),
                        DateTimePicker::make('completed_at'),
                    ])->columns(2),
            ])->columns(1);
    }
}

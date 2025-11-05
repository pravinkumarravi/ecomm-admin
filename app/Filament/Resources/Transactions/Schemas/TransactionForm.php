<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('parent_transaction_id')
                    ->relationship('parentTransaction', 'id')
                    ->default(null),
                TextInput::make('transaction_number')
                    ->required(),
                TextInput::make('gateway_transaction_id')
                    ->default(null),
                Select::make('payment_method')
                    ->options([
            'cod' => 'Cod',
            'card' => 'Card',
            'upi' => 'Upi',
            'wallet' => 'Wallet',
            'paypal' => 'Paypal',
            'net_banking' => 'Net banking',
        ])
                    ->default('cod')
                    ->required(),
                Select::make('type')
                    ->options(['payment' => 'Payment', 'refund' => 'Refund', 'partial_refund' => 'Partial refund'])
                    ->default('payment')
                    ->required(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'processing' => 'Processing',
            'success' => 'Success',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
        ])
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
                TextInput::make('gateway')
                    ->default(null),
                Textarea::make('gateway_response')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('card_last_four')
                    ->default(null),
                TextInput::make('card_brand')
                    ->default(null),
                TextInput::make('bank_name')
                    ->default(null),
                TextInput::make('ip_address')
                    ->default(null),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('failure_reason')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('attempted_at'),
                DateTimePicker::make('completed_at'),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_number')
                    ->label('Transaction #')
                    ->searchable(),
                TextColumn::make('payment_method'),
                TextColumn::make('type'),
                TextColumn::make('status'),
                TextColumn::make('amount')
                    ->numeric()
                    ->money('INR'),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('gateway_fee')
                    ->label('Gateway Fee')
                    ->numeric()
                    ->money('INR'),
                TextColumn::make('attempted_at')
                    ->label('Attempted At')
                    ->dateTime(),
                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

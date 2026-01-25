<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Actions\Modal\Actions\Action;
use Filament\Tables\Actions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                ->label('Order Id')
                ->searchable(),

                TextColumn::make('customer.name')
                ->searchable(),

                TextColumn::make('grand_total')
                ->label('Grand Total')
                ->money('BDT')
                ->sortable(),

                TextColumn::make('status')
                ->label('Order Status')
                ->badge()
                ->color(fn (string $state):string => match($state){
                    'new' => 'info',
                    'processing'=> 'warning',
                    'shipped'=> 'success',
                    'deliverd'=> 'success',
                    'cancelled'=> 'danger',
                })
                ->icon(fn (string $state):string => match($state){
                    'new' => 'heroicon-m-sparkles',
                    'processing'=> 'heroicon-m-arrow-path',
                    'shipped'=> 'heroicon-m-truck',
                    'deliverd'=> 'heroicon-m-check-badge',
                    'cancelled'=> 'heroicon-m-x-circle',
                })
                ->sortable(),

                TextColumn::make('payment_method')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'sslCommerz' => 'Debit/Credit Card',
                    'bkash'    => 'bkash',
                    'Due'    => 'COD (Cash on Delivery)',
                    default  => $state,
                })
                ->searchable()
                ->sortable(),

                TextColumn::make('payment_status')
                ->formatStateUsing(fn ($state) => match ($state) {
                    'pending' => 'Pending',
                    'paid'    => 'Paid',
                    'failed'    => 'Failed',
                    default  => $state,
                })
                ->badge()
                ->searchable()
                ->sortable(),

                TextColumn::make('created_at')
                ->label('Created/Ordered')
                ->searchable()
                ->sortable()
                ->date('j M Y'),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('Details')
                    ->url(fn (Order $record): string => 
                        OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-m-eye')
                    ->openUrlInNewTab(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                
                TextColumn::make('id')
                ->label('Order Id')
                ->searchable(),

                TextColumn::make('grand_total')
                ->label('Grand Total')
                ->money('BDT')
                ->sortable(),

                TextColumn::make('status')
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
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([                    
                Action::make('View Order')
                ->url(fn(Order $record):string 
                    => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('info')
                    ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

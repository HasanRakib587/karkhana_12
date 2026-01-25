<?php

namespace App\Filament\Resources;

use Dom\Text;
use BcMath\Number;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;

use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\CustomerResource;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([

                    Section::make('Order Information')->schema([
                        Select::make('customer_id')
                        ->label('Customer')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),                       

                        Select::make('payment_method')
                        ->options([
                            // 'sslCommerz' => 'Debit/Credit Card',
                            'MFS'=> 'bkash',
                            'cod'=> 'COD (Cash on Delivery)',
                        ])
                        ->default('cod')
                        ->required(),

                        Select::make('payment_status')
                        ->options([
                            'pending'=> 'Pending',
                            'paid' => 'Paid',
                            'failed' => 'Failed'
                        ])
                        ->default('pending')
                        ->required(),

                        ToggleButtons::make('status')
                        ->inline()
                        ->default('new')
                        ->required()
                        ->options([
                            'new'=> 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled'=> 'Cancelled',
                        ])
                        ->colors([
                            'new'=> 'info',
                            'processing' => 'warning',
                            'shipped' => 'success',
                            'delivered' => 'success',
                            'cancelled'=> 'danger',
                        ])
                        ->icons([
                            'new'=> 'heroicon-m-sparkles',
                            'processing' => 'heroicon-m-arrow-path',
                            'shipped' => 'heroicon-m-truck',
                            'delivered' => 'heroicon-m-check-badge',
                            'cancelled'=> 'heroicon-m-x-circle'
                        ]),

                        Select::make('currency')
                        ->options([
                            'bdt' => 'BDT',
                            'usd' => 'USD',
                            'cad' => 'CAD'
                        ])->default('BDT')->disabled()->dehydrated(),

                        // Select::make('shipping_method')
                        // ->options([
                        //     'home'=> 'Home Delivery',
                        //     'pickup'=> 'Pickup',                            
                        // ]),
                        TextInput::make('bkash_last_digits')->disabled()->dehydrated(),
                        TextInput::make('bkash_trx_id')->disabled()->dehydrated(),

                        Textarea::make('notes')->columnSpanFull(),
                    ])->columns(2),

                    Section::make('Order Items')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([
                            
                            Select::make('product_id')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->distinct()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->columnSpan(4)
                            ->reactive()
                            ->afterStateUpdated(fn($state, Set $set)
                            => $set('unit_amount', Product::find($state)?->price ?? 0))
                            ->afterStateUpdated(fn($state, Set $set)
                            => $set('total_amount', Product::find($state)?->price ?? 0)),

                            TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(fn($state, Set $set, Get $get) 
                            => $set('total_amount', $state * $get('unit_amount'))),

                            TextInput::make('unit_amount')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(3),

                            TextInput::make('total_amount')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->columnSpan(3),
                        ])->columns(12),

                        Placeholder::make('grand_total')
                        ->label('Grand Total')
                        ->content(function(Get $get, Set $set){
                            $total = 0;
                            if(!$repeaters = $get('items')){
                                return $total;
                            }
                            foreach($repeaters as $key => $repeater){
                                $total += $get("items.{$key}.total_amount");
                            }
                            $set("grand_total", $total);
                            return \Illuminate\Support\Number::currency($total, 'BDT');
                        }),

                        Hidden::make('grand_total')
                        ->default(0)
                    ]),

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                        ->label('# Order')
                        ->sortable()
                        ->searchable(),
                
                TextColumn::make('customer.name')
                ->label('Customer')
                ->sortable()
                ->searchable(),

                TextColumn::make('grand_total')
                ->numeric()
                ->sortable()
                ->money('BDT'),

                // TextColumn::make('payment_method')
                //     ->formatStateUsing(fn ($state) => match ($state) {
                //         'stripe' => 'Debit/Credit Card',
                //         'MFS'    => 'bkash',
                //         'Due'    => 'COD (Cash on Delivery)',
                //         default  => $state,
                //     })
                //     ->searchable()
                //     ->sortable(),

                TextColumn::make('payment_status')
                ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pending',
                        'paid'    => 'Paid',
                        'failed'    => 'Failed',
                        default  => $state,
                    })
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('bkash_last_digits')
                    ->label('bKash digit')
                    ->searchable()
                    ->sortable(),
                
                // TextColumn::make('bkash_trx_id')
                //     ->label('TRX ID')
                //     ->searchable()
                //     ->sortable(),
                
                // ViewColumn::make('bkash_trx_id')
                //     ->label('TRX ID')
                //     ->view('filament.tables.columns.trx-verify'),
                IconColumn::make('confirmation_email_sent')
                    ->label('Email Sent')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),

                SelectColumn::make('status')
                ->label('Order Status')
                ->options([
                    'new'=> 'New',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled'=> 'Cancelled',
                ])->searchable()->sortable(),

                // TextColumn::make('currency')
                // ->searchable()
                // ->sortable(),

                TextColumn::make('created_at')
                ->label('Ordered')
                ->date('j M Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')->since()
                ->label('Updated')                
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),


            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\Action::make('view_invoice')
                //         ->label('Invoice')
                //         ->icon('heroicon-o-document')
                //         ->url(fn($record) => self::getUrl('invoice', ['record' => $record->id])),

                Tables\Actions\Action::make('sendConfirmation')
                        ->label('Send Email')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalHeading('Send payment confirmation email?')
                        ->visible(fn ($record) =>
                            filled($record->bkash_last_digits)
                            && ! $record->confirmation_email_sent
                        )
                        ->disabled(fn ($record) => $record->confirmation_email_sent)
                        
                        ->action(function ($record) {
                            Mail::to($record->customer->email)
                                ->send(new \App\Mail\PaymentConfirmedMail($record));
                            
                            $record->update([
                                'confirmation_email_sent' => true,
                                'confirmation_email_sent_at' => now(),
                                'payment_status' => 'paid',
                            ]);
                            
                            Notification::make()
                                ->title('Confirmation email sent')
                                ->success()
                                ->send();        
                        }),                        

                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),    
                    Tables\Actions\Action::make('view_invoice')
                        ->label('Invoice')
                        ->icon('heroicon-o-document')
                        ->url(fn($record) => self::getUrl('invoice', ['record' => $record->id])),                                    
                ]),                

            ])
            ->recordClasses(fn ($record) =>
                ! $record->confirmation_email_sent
                    ? 'border-l-4 border-yellow-500'
                    : null
            )

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::query()->where('status', 'new')->count();        
    }

    // public function verify($id)
    // {
    //     $record = Order::findOrFail($id);
    //     $record->update(['is_verified' => true]);
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            // 'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
            'invoice' => Pages\Invoice::route('/{record}/invoice'),
        ];
    }
}

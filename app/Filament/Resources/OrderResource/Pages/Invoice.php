<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\OrderResource;

class Invoice extends Page
{
    protected static string $resource = OrderResource::class;

    public $record;

    public $order;

    public function mount($record){
        $this->record = $record;
        $this->order = Order::find($record);
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')                    
                    ->action(function () {
                        return redirect()->away(
                            route('filament.admin.filament.admin.orders.print', $this->record)
                        );
            }),                    
        ];
    }

    protected static string $view = 'filament.resources.order-resource.pages.invoice';
}

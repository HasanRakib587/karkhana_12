<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Session;

#[Title('Success | KARKHANA')]
class SuccessPage extends Component
{
    public function mount()
    {
        // Check if user came from checkout or payment success
        if (! Session::pull('order_success', false)) {
            // If not set, block access
            abort(403, 'Unauthorized access to success page.');
        }
    }
    
    public function render()
    {
        $latest_order = Order::with('address')
        ->where('customer_id', auth('customer')->user()->id)->latest()->first();
        
        //verify ipn ?

        return view('livewire.success-page', [
            'order' => $latest_order,            
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;

class CancelPage extends Component
{
    public function mount()
    {
        if (! session()->pull('order_cancelled', false)) {
            abort(403, 'Unauthorized access to cancelled page.');
        }
    }
    
    public function render()
    {
        return view('livewire.cancel-page');
    }
}

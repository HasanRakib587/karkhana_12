<?php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AddToCartButton extends Component
{
    public $product_id;    

    public function addToCart(){
        $total_count = CartManagement::addItemToCart($this->product_id);

        $this->dispatch('update-cart-count', $total_count)->to(Navbar::class);

        LivewireAlert::title('Thank You !')
            ->text('Your Item is Added to the Cart')
            ->position('top-end')
            ->timer(3000)
            ->toast()
            ->show();
    }
    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}

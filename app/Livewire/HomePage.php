<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Title('KARKHANA | Home')]
class HomePage extends Component
{    
    public function render()
    {
        $categories = Category::where('is_active', 1)->get();
        $featuredProducts = Product::where('is_active', 1)->get();
        return view('livewire.home-page',[
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}

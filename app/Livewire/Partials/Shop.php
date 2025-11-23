<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use App\Models\Category;

class Shop extends Component
{
    public function render()
    {
        return view('livewire.partials.shop', [
            'categories' => Category::where('is_active', 1)->get(),
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Collection;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Title('Karkhana | Products')]
class ProductsPage extends Component
{
    use WithPagination;

    #[Url]
    public $selected_categories = [];
    
    #[Url]
    public $selected_collection = [];

    #[Url]
    public $sort = 'latest';

    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCart($product_id);

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
        $productQuery = Product::query()->where('is_active', 1); 

        if(!empty($this->selected_categories)){
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        if(!empty($this->selected_collection)){
            $productQuery->whereIn('collection_id', $this->selected_collection);
        }

        if($this->sort == 'latest'){
            $productQuery->latest();
        }

        if($this->sort == 'price'){
            $productQuery->orderBy('price');
        }

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(9),
            'collections' => Collection::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
        ]);
    }
}

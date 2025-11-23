<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use function Pest\Laravel\get;

use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Title('Product Detail | KARKHANA')]
class ProductDetailPage extends Component
{
    public $slug;

    public $quantity = 1;

    public function mount($slug){
        $this->slug = $slug;
    }

    public function increaseQty(){
        $this->quantity++;        
    }

    public function decreaseQty(){
        if($this->quantity > 1){
            $this->quantity--;
        }
    }

    public function addToCart($product_id){
        $total_count = CartManagement::addItemToCartWithQty($product_id, $this->quantity);

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
        $product = Product::where('slug', $this->slug)->firstOrFail();
        $relatedProducts = Product::where('collection_id', $product->collection_id)
                                    ->where('id', '!=', $product->id)->get();

        
                                    
        return view('livewire.product-detail-page',[
            'product' => $product, 
            'relatedProducts' => $relatedProducts,
            'categories' => Category::query()->where('is_active', 1)->get(),           
        ]);
    }
}

<button wire:click="addToCart" type="button" class="font-primary fw-bolder btn btn-light text-dark rounded-0 px-5 py-3">
    <span wire:loading.remove wire:target="addToCart">Add to Cart</span>
    <span wire:loading wire:target="addToCart">Adding...</span>
    <i class="bi bi-cart me-1"></i>
</button>
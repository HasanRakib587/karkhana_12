<div class="container py-5 mt-5">
    <h1 class="h3 fw-bold mb-4">Checkout</h1>
    <form wire:submit.prevent="placeOrder">
        <div class="row g-4">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- Shipping Address -->
                        <h2 class="h5 fw-bold text-decoration-underline mb-3">
                            Shipping Address
                        </h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input wire:model="first_name" type="text" class="form-control @error('first_name')
                                    border-danger
                                @enderror" id="first_name" />
                                @error('first_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input wire:model="last_name" type="text" class="form-control @error('last_name')
                                    border-danger
                                @enderror" id="last_name" />
                                @error('last_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="phone" class="form-label">Phone</label>
                                <input wire:model="phone" type="text" class="form-control @error('phone')
                                    border-danger
                                @enderror" id="phone" />
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Street Address</label>
                                <input wire:model="street_address" type="text" class="form-control @error('street_address')
                                    border-danger
                                @enderror" id="address" />
                                @error('street_address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="city" class="form-label">District</label>
                                <input wire:model="district" type="text" class="form-control @error('district')
                                    border-danger
                                @enderror" id="city" />
                                @error('district')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="zip" class="form-label">ZIP Code</label>
                                <input wire:model="zip_code" type="text" class="form-control @error('zip_code')
                                    border-danger
                                @enderror" id="zip" />
                                @error('zip_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Payment Method -->
                        <div class="mt-4">
                            <h5 class="fw-semibold mb-3">Select Payment Method</h5>
                            <div class="row g-3">
                                <ul class="list-unstyled">
                                    <li>
                                        <div class="col-md-4 px-3">
                                            <div class="form-check p-3">
                                                <input wire:model="payment_method" class="form-check-input" type="radio"
                                                    value="cod" id="cod" checked />
                                                <label class="form-check-label w-100" for="cod">
                                                    <div class="fw-bold">Cash on Delivery</div>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="col-md-4 px-3">
                                            <div class="form-check p-3">
                                                <input wire:model="payment_method" class="form-check-input" type="radio"
                                                    value="sslcommerze" id="sslcommerze" />
                                                <label class="form-check-label w-100" for="sslcommerze">
                                                    <div class="fw-bold">Debit/Credit Card</div>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    {{-- <li>
                                        <div class="col-md-4 px-3">
                                            <div class="form-check p-3">
                                                <input wire:model="payment_method" class="form-check-input" type="radio"
                                                    value="bkash" id="bkash" />
                                                <label class="form-check-label w-100" for="bkash">
                                                    <div class="fw-bold">bKash</div>
                                                </label>
                                            </div>
                                        </div>
                                    </li> --}}
                                    <li>
                                        @error('payment_method')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold text-decoration-underline mb-3">
                            ORDER SUMMARY
                        </h5>
                        <div class="d-flex justify-content-between fw-bold mb-2">
                            <span>Subtotal</span><span>{{ Number::currency($grand_total, 'BDT') }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold mb-2">
                            <span>Vat(15%)</span><span>{{ Number::currency($grand_total * .15, 'BDT') }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold mb-2">
                            <span>Delivery Charge</span><span>{{ Number::currency(60, 'BDT') }}</span>
                        </div>
                        <hr />
                        <div class="d-flex justify-content-between fw-bold mb-2">
                            <span>Grand
                                Total</span><span>{{ Number::currency($grand_total + $grand_total * .15 + 60, 'BDT') }}</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mb-4">
                    <span wire:loading.removed>Place Order</span>
                    <span wire:loading>Processing...</span>
                </button>

                <!-- Basket Summary -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold text-decoration-underline mb-3">
                            BASKET SUMMARY
                        </h5>
                        <ul class="list-group list-group-flush">
                            @foreach ($cart_items as $cart_item)
                                <li class="list-group-item d-flex align-items-center"
                                    wire:key="{{ $cart_item['product_id'] }}">
                                    <img src="{{ Storage::url($cart_item['image']) }}" alt="{{ $cart_item['name'] }}"
                                        class="rounded me-3" width="50" />
                                    <div class="flex-fill">
                                        <div class="fw-semibold">{{ $cart_item['name'] }}</div>
                                        <small class="text-muted">Quantity: {{ $cart_item['quantity'] }}</small>
                                    </div>
                                    <div class="fw-bold">{{ Number::currency($cart_item['total_amount'], 'BDT') }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
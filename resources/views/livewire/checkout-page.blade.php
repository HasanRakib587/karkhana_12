<div class="container py-5 mt-5">
    @if ($errors->any())
        <pre>{{ print_r($errors->all(), true) }}</pre>
    @endif

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
                            {{-- <div class="col-12">
                                <label for="city" class="form-label">District</label>
                                <input wire:model="district" type="text" class="form-control @error('district')
                                    border-danger
                                @enderror" id="city" />
                                @error('district')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            <div class="col-md-6">
                                <label for="zip" class="form-label">ZIP Code</label>
                                <input wire:model="zip_code" type="text" class="form-control @error('zip_code')
                                    border-danger
                                @enderror" id="zip" />
                                @error('zip_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- District --}}
                            <div class="col-12">
                                <label for="district" class="form-label">District</label>
                                <select wire:model.live="district" id="district"
                                    class="form-control @error('district') border-danger @enderror">
                                    <option value="">-- Select District --</option>
                                    <option value="insidedhaka">Inside Dhaka</option>
                                    <option value="outsidedhaka">Outside Dhaka</option>
                                </select>
                                {{-- Debug line (IMPORTANT) --}}
                                {{-- <p class="mt-2 text-muted">Selected: {{ $district }}</p> --}}

                                @error('district')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                {{-- Dynamic message --}}
                                {{-- @if ($district === 'insidedhaka')
                                @else
                                <div class="mt-4 alert alert-warning">
                                    <div class="card shadow-sm border-2">
                                        <div class="card-body">
                                            <h5 class="fw-bold text-danger mb-3">Pay with bKash</h5>

                                            <div class="row align-items-center">
                                                <div class="col-md-7 mb-3 mb-md-0">
                                                    <p class="mb-2">Send money to the following bKash number:</p>

                                                    <div class="d-flex align-items-center mb-3">
                                                        <span class="badge bg-danger me-2">bKash</span>
                                                        <span class="fs-5 fw-bold"> 01XXXXXXXXX </span>
                                                    </div>

                                                    <ul class="small text-muted ps-3 mb-0">
                                                        <li>Select <strong>Send Money</strong></li>
                                                        <li>Enter the bKash number above</li>
                                                        <li>Enter the exact amount</li>
                                                        <li>Complete the payment</li>
                                                    </ul>
                                                </div>

                                                <div class="col-md-5 text-center">
                                                    <p class="mb-2 fw-semibold">Scan to Pay</p>

                                                    <div class="border rounded p-2 d-inline-block">
                                                        <img src="{{ asset('images/qr-code.png') }}" alt="bKash QR Code"
                                                            width="160" />
                                                    </div>

                                                    <div class="small text-muted mt-2">Open bKash app → Scan QR</div>
                                                </div>
                                            </div>

                                            <hr class="my-4" />

                                            <form>
                                                <div class="row g-3 align-items-end">
                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Last 3 digits of your bKash number
                                                        </label>
                                                        <input type="text" class="form-control" maxlength="3"
                                                            placeholder="e.g. 123" required />
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label"> Transaction ID (optional) </label>
                                                        <input type="text" class="form-control"
                                                            placeholder="e.g. A7B9C2D" />
                                                    </div>
                                                </div>

                                                <button class="btn btn-danger mt-3">I Have Paid</button>
                                                <p class="small text-muted mt-2">
                                                    Payment verification may take up to 1–2 hours.
                                                </p>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif --}}
                                @if ($district !== 'insidedhaka')
                                    <div class="mt-4 alert alert-warning">
                                        <div class="card shadow-sm border-2">
                                            <div class="card-body">
                                                <h5 class="fw-bold text-danger mb-3">Pay with bKash</h5>

                                                <div class="row align-items-center">
                                                    <div class="col-md-7 mb-3 mb-md-0">
                                                        <p class="mb-2">Send money to the following bKash number:</p>

                                                        <div class="d-flex align-items-center mb-3">
                                                            <span class="badge bg-danger me-2">bKash</span>
                                                            <span class="fs-5 fw-bold">01779843134</span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5 text-center">
                                                        <img src="{{ asset('images/karkhana-bkash.jpeg') }}" width="160" />
                                                    </div>
                                                </div>

                                                <hr class="my-4" />

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Last 3 digits of your bKash number
                                                        </label>
                                                        <input type="text" wire:model.defer="bkash_last_digits"
                                                            maxlength="3"
                                                            class="form-control @error('bkash_last_digits') border-danger @enderror" />
                                                        @error('bkash_last_digits')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Transaction ID (optional)
                                                        </label>
                                                        <input type="text" wire:model.defer="bkash_trx_id"
                                                            class="form-control" />
                                                    </div>
                                                </div>

                                                <p class="small text-muted mt-3">
                                                    Payment verification may take up to 1–2 hours.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Payment Method -->
                        {{-- <div class="mt-4">
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
                                    <li>
                                        <div class="col-md-4 px-3">
                                            <div class="form-check p-3">
                                                <input wire:model="payment_method" class="form-check-input" type="radio"
                                                    value="bkash" id="bkash" />
                                                <label class="form-check-label w-100" for="bkash">
                                                    <div class="fw-bold">bKash</div>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        @error('payment_method')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
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
                        {{-- <div class="d-flex justify-content-between fw-bold mb-2">
                            <span>Vat(15%)</span><span>{{ Number::currency($grand_total * .15, 'BDT') }}</span>
                        </div> --}}
                        <div class="d-flex justify-content-between fw-bold mb-2">
                            <span>Delivery Charge</span><span>{{ Number::currency($deliveryCharge, 'BDT') }}</span>
                        </div>
                        <hr />
                        <div class="d-flex justify-content-between fw-bold mb-2">
                            <span>Grand
                                Total</span><span>{{ Number::currency($grand_total + $deliveryCharge, 'BDT') }}</span>
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
                                <li class="list-group-item d-flex align-items-center border-2"
                                    wire:key="{{ $cart_item['product_id'] }}">
                                    <img src="{{ asset('uploads/' . $cart_item['image']) }}" alt="{{ $cart_item['name'] }}"
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
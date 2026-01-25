<?php

namespace App\Livewire;

use App\Mail\CashOnDeliveryConfirmationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
// use App\Mail\OrderPlaced;
// use App\Mail\PaymentVerified;
use Livewire\Attributes\Title;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

#[Title('Checkout | KARKHANA')]
class CheckoutPage extends Component
{
    public $phone;
    public $zip_code;
    public $first_name;
    public $last_name;
    public $bkash_trx_id;
    public $street_address;
    public $payment_method;
    public $bkash_last_digits;
    public float $deliveryCharge = 0;
    public string $district = 'insidedhaka';

    public function mount(){
        $cart_items = CartManagement::getCartItemsFromCookie();

        if(count($cart_items) == 0){
            return redirect(route('all.products'));
        }
    }

    public function placeOrder()
    {
        // 1️⃣ Validate input
        $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'street_address' => 'required|string|max:255',
            'district' => 'required|string',
            'zip_code' => 'required|string|max:20',

            // Required ONLY when outside Dhaka
            'bkash_last_digits' => 'nullable|required_unless:district,insidedhaka|digits:3',
            'bkash_trx_id' => 'nullable|string|max:50',
        ]);

        // 2️⃣ Get cart items
        $cart_items = CartManagement::getCartItemsFromCookie();

        if (empty($cart_items)) {
            session()->flash('error', 'Your cart is empty.');
            return redirect()->back();
        }

        // 3️⃣ Create Order
        $order = new Order();
        $order->customer_id = auth('customer')->id();
        $order->transaction_reference = 'TXN_' . strtoupper(uniqid());
        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);
        $order->currency = 'bdt';
        $order->shipping_cost = 60;
        $order->shipping_method = 'home';
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->notes = 'Order placed by ' . auth('customer')->user()->name;

        // 4️⃣ Payment method logic
        if ($this->district !== 'insidedhaka') {
            $order->payment_method = 'bkash';
            $order->bkash_last_digits = $this->bkash_last_digits;
            $order->bkash_trx_id = $this->bkash_trx_id;
        } else {
            $order->payment_method = 'COD';
        }

        $order->save();

        // 5️⃣ Save Address
        $order->address()->create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'street_address' => $this->street_address,
            'district' => $this->district === 'insidedhaka' ? 'Dhaka' : 'Outside Dhaka',
            'zip_code' => $this->zip_code,
        ]);

        // 6️⃣ Save Order Items (QUANTITY FIXED HERE)
        $order->items()->createMany(
            array_map(fn ($item) => [
                'product_id' => $item['product_id'],
                'unit_amount' => $item['unit_amount'],
                'quantity' => $item['quantity'],
                'total_amount'=> $item['unit_amount'] * $item['quantity'],
            ], $cart_items)
        );

        // 7️⃣ Clear cart
        CartManagement::clearCart();

        // 8️⃣ Send email safely
        try {
            Mail::to($order->customer->email)
                ->send(new OrderConfirmationMail($order));
        } catch (\Throwable $e) {
            \Log::error('Order email failed: ' . $e->getMessage());
        }

        // 9️⃣ Redirect to success page
        session()->flash('order_success', true);
        return redirect()->route('success');
    }
    
    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        $this->deliveryCharge = ($this->district === 'insidedhaka') ? 60.0 : 180.0;
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total
        ]);
    }
}

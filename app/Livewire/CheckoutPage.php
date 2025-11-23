<?php

namespace App\Livewire;

use App\Mail\CashOnDeliveryConfirmationMail;
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
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $district;
    public $zip_code;
    public $payment_method;

    public function mount(){
        $cart_items = CartManagement::getCartItemsFromCookie();

        if(count($cart_items) == 0){
            return redirect(route('all.products'));
        }
    }

    public function placeOrder(){        

        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'district' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);
        
        $cart_items = CartManagement::getCartItemsFromCookie();        

        $tran_id = 'TXN_' . uniqid();

        $order = new Order();
        $order->customer_id = auth('customer')->user()->id;

        $order->transaction_reference = $tran_id;

        $order->grand_total = CartManagement::calculateGrandTotal($cart_items);        
        $order->payment_method = $this->setPaymentMethod();
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'bdt';
        $order->shipping_cost = 60;
        $order->shipping_method = 'home';
        $order->notes = 'Order Placed by ' . auth('customer')->user()->name;

        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->district = $this->district;
        $address->zip_code = $this->zip_code;        

        // Save order + address and items before redirecting to payment initiation
        $order->save();
        $address->order_id = $order->id;
        $address->save();
        // dd($cart_items);
        $order->items()->createMany($cart_items);

        // clear the cart from cookie when the order record is created; but don't mark paid yet
        CartManagement::clearCartItems();

        // ✅ Re-fetch order with all relationships
        $order = Order::with(['items.product', 'address', 'customer'])->find($order->id);

        // Decide redirect according payment method
        if ($this->payment_method === 'cod') {
            $order->payment_status = 'pending';
            $order->save();

            //here a mail will be sent
            // Send email (COD)
            try {
                Mail::to($order->customer->email)->send(new CashOnDeliveryConfirmationMail($order));
            } catch (\Throwable $e) {
                Log::error('Error sending payment verified email: '.$e->getMessage());
            }

            session(['order_success' => true]);

            return redirect(route('success')); // your success page for COD
        }

        if ($this->payment_method === 'sslcommerze') {
            // redirect to controller action that will create SSLCommerz session and redirect user to gateway
            return redirect()->route('sslcommerz.init', ['order' => $order->id]);
        }

        if ($this->payment_method === 'bkash') {
            // not implemented
            abort(501, 'bKash payment not implemented');
        }

        abort(400, 'Unknown payment method');        
    }

    private function setPaymentMethod(){
        if($this->payment_method == 'cod'){
            return 'Cash On Delivery (COD)';
        }

        if($this->payment_method == 'sslcommerze'){
            return 'Debit/Credit Card';
        }
        return 'Generic';
    }

    public function render()
    {
        $cart_items = CartManagement::getCartItemsFromCookie();
        $grand_total = CartManagement::calculateGrandTotal($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total
        ]);
    }
}

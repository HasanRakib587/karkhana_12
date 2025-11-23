<?php

namespace App\Http\Controllers;

use App\Mail\CardPaymentConfirmationMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
// use App\Mail\PaymentVerified;

class SslCommerzController extends Controller
{
    /**
     * Initialize SSLCommerz payment
     */
    public function init(Request $request, Order $order)
    {
        if ($order->payment_status === 'paid') {
            return redirect()->route('success')->with('message', 'Order already paid.');
        }

        $store_id = config('sslcommerz.store_id');
        $store_passwd = config('sslcommerz.store_passwd');
        $apiUrl = config('sslcommerz.api_url');

        // Prepare product names (comma separated)
        $productNames = $order->items->pluck('name')->join(',');

        $post_data = [
            'store_id' => $store_id,
            'store_passwd' => $store_passwd,
            'total_amount' => number_format($order->grand_total, 2, '.', ''),
            'currency' => strtoupper($order->currency ?? 'BDT'),
            'tran_id' => $order->transaction_reference,
            'product_category' => 'goods',
            'product_name' => $productNames ?: 'Goods',
            'product_profile' => 'physical-goods',
            'success_url' => route('sslcommerz.success'),
            'fail_url' => route('sslcommerz.fail'),
            'cancel_url' => route('sslcommerz.cancel'),
            'ipn_url' => route('sslcommerz.ipn'),

            // Customer info
            'cus_name' => $order->customer->name ?? '',
            'cus_email' => $order->customer->email ?? '',
            'cus_add1' => $order->address->street_address ?? '',
            'cus_city' => $order->address->district ?? '',
            'cus_postcode' => $order->address->zip_code ?? '',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $order->address->phone ?? '',

            // Shipping info
            'ship_name' => $order->customer->name ?? '',
            'ship_add1' => $order->address->street_address ?? '',
            'ship_city' => $order->address->district ?? '',
            'ship_postcode' => $order->address->zip_code ?? '',
            'ship_country' => 'Bangladesh',
            'num_of_item' => $order->items()->count() ?: 1,
            'shipping_method' => $order->shipping_method ?? 'home',
            'value_a' => config('sslcommerz.shared_secret'),
        ];

        $response = Http::asForm()->post($apiUrl, $post_data);

        if (! $response->ok()) {
            Log::error('SSLCommerz Gateway init failed', ['response_body' => $response->body(), 'post_data' => $post_data]);
            return redirect()->route('cartPage')->with('error', 'Payment initiation failed, please try again.');
        }

        $data = $response->json();

        if (! empty($data['GatewayPageURL'])) {
            return redirect()->to($data['GatewayPageURL']);
        }

        return redirect()->route('cartPage')->with('error', 'Payment page not returned by gateway.');
    }

    /**
     * Success callback
     */
    public function success(Request $request)
    {

        $sharedSecret = config('sslcommerz.shared_secret');
        $incomingSecret = $request->input('value_a');

        if ($incomingSecret !== $sharedSecret) {
            Log::warning('Invalid SSLCommerz secret in success callback', $request->all());
            abort(403, 'Unauthorized callback.');
        }

        $tran_id = $request->input('tran_id');
        $val_id = $request->input('val_id');

        $order = Order::where('transaction_reference', $tran_id)->first();

        if (! $order) {
            Log::warning("SSLCommerz success callback: Order not found for tran_id: {$tran_id}", $request->all());
            return redirect()->route('cartPage')->with('error', 'Order not found.');
        }

        $validation = $this->validateWithSslCommerz($val_id, $order);

        if ($validation['valid'] === true) {
            // Update order status
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->transaction_reference = $validation['data']['bank_tran_id'] ?? $tran_id;
            $order->save();

            //Send email
            // try {
            //     Mail::to($order->customer->email)->send(new CardPaymentConfirmationMail($order));
            // } catch (\Throwable $e) {
            //     Log::error('Error sending payment verified email: '.$e->getMessage());
            // }

            // Auto login customer
            if ($order->customer_id) {
                Auth::guard('customer')->loginUsingId($order->customer_id);
            }

            session(['order_success' => true]);

            return redirect()->route('success')->with('message', 'Payment successful and verified.');
        }

        Log::warning("SSLCommerz validation failed for order {$order->id}", ['validation' => $validation]);
        return redirect()->route('cartPage')->with('error', 'Payment could not be verified.');
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        // Log for debugging
        Log::warning("SSLCommerz payment failed for tran_id: {$tran_id}", $request->all());

        // Optionally mark the order as failed (if exists)
        if ($tran_id) {
            $order = Order::where('transaction_reference', $tran_id)->first();
            if ($order) {
                $order->payment_status = 'failed';
                $order->status = 'cancelled';
                $order->save();
            }
        }

        // Set a flag so cancel page can show
        session(['order_cancelled' => true]);

        return redirect()->route('cancelled')->with('error', 'Payment failed or was cancelled.');
    }

    /**
     * Validate payment via REST API
     */
    protected function validateWithSslCommerz($val_id, Order $order)
    {
        $validationUrl = config('sslcommerz.validation_url'); // REST URL, no ?wsdl

        $response = Http::get($validationUrl, [
            'val_id' => $val_id,
            'store_id' => config('sslcommerz.store_id'),
            'store_passwd' => config('sslcommerz.store_passwd'),
            'v' => 1,
            'format' => 'json',
        ]);

        if (! $response->ok()) {
            Log::warning('SSLCommerz validator API failed', ['response' => $response->body()]);
            return ['valid' => false, 'data' => []];
        }

        $data = $response->json();

        // Validate amount and currency
        $validAmount = abs(floatval($data['currency_amount'] ?? 0) - floatval($order->grand_total)) < 0.01;
        $validCurrency = strtoupper($data['currency'] ?? '') === strtoupper($order->currency ?? 'BDT');
        $validStatus = in_array($data['status'], ['VALID', 'VALIDATED']);

        $valid = $validStatus && $validAmount && $validCurrency;

        return [
            'valid' => $valid,
            'data' => $data,
            'raw' => $response->body(),
        ];
    }

    // Optionally implement fail(), cancel(), ipn() methods
    public function ipn(Request $request){
        // 1️⃣ Verify shared secret (just like success)
        $sharedSecret = config('sslcommerz.shared_secret');
        $incomingSecret = $request->input('value_a');

        if ($incomingSecret !== $sharedSecret) {
            Log::warning('Invalid SSLCommerz secret in IPN callback', $request->all());
            return response()->json(['error' => 'Unauthorized callback'], 403);
        }

        // 2️⃣ Validate input
        $tran_id = $request->input('tran_id');
        $val_id  = $request->input('val_id');

        if (! $tran_id || ! $val_id) {
            Log::warning('Invalid IPN received — missing tran_id or val_id', $request->all());
            return response()->json(['error' => 'Invalid IPN data'], 400);
        }

        // 3️⃣ Find the order by transaction_reference
        $order = Order::where('transaction_reference', $tran_id)->first();

        if (! $order) {
            Log::warning("IPN: Order not found for tran_id {$tran_id}", $request->all());
            return response()->json(['error' => 'Order not found'], 404);
        }

        // 4️⃣ Validate with SSLCommerz validation API
        $validation = $this->validateWithSslCommerz($val_id, $order);

        if ($validation['valid'] === true) {
            // 5️⃣ Update the order if not already paid
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'transaction_reference' => $validation['data']['bank_tran_id'] ?? $val_id,
                ]);

                try {
                    dd($order);
                    Mail::to($order->customer->email)->send(new CardPaymentConfirmationMail($order));
                } catch (\Throwable $e) {
                    Log::error('Error sending IPN payment email: '.$e->getMessage());
                }

                Log::info("Order {$order->id} marked as paid via IPN.");
            }

            return response()->json(['status' => 'success'], 200);
        }

        // 6️⃣ Log invalid attempts
        Log::warning("SSLCommerz IPN validation failed for order {$order->id}", ['validation' => $validation]);
        return response()->json(['status' => 'invalid'], 400);
    }

}

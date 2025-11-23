<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePrintController extends Controller
{
    public function print($record)
    {
        $order = Order::findOrFail($record);

        // You can return a view for printing
        //return view('filament.resources.order-resource.pages.invoice', compact('order'));

        // OR return a PDF if you want
        $pdf = Pdf::loadView('filament.resources.order-resource.pages.invoice-print', compact('order'));
        //return $pdf->download('invoice-'.$order->id.'.pdf');

        //$pdf = PDF::loadView('filament.resources.order-resource.pages.invoice');
        return $pdf->stream();
    }
}

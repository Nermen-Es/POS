<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceDetail;

class OrderDetailService
{
    public function create($invoice_id, $product, $quantity, $price)
    {
        $invoiceDetail = InvoiceDetail::create([
            'invoice_id' => $invoice_id,
            'product' =>$product,
            'price' => $price,
            'quantity' => $quantity,
            'sub_total' => $price * $quantity ,
        ]);

        return $invoiceDetail;

    }
}

<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceDetail;

class OrderDetailService
{
    public function create($invoice_id, $product, $quantity, $price ,$sub_total)
    {

        $invoiceDetail = InvoiceDetail::create([
            'invoice_id' => $invoice_id,
            'product' =>$product,
            'price' => $price,
            'quantity' => $quantity,
            'sub_total' => $sub_total,
        ]);

        return $invoiceDetail;

    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Product' => $this->product,
            'Price' => $this->price,
            'Quantity' => $this->quantity,
            'Sub_Total' =>  $this->sub_total,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'  => $this->id,
            'created_by'=> $this->user_id,
            'supplier_id' => $this->supplier_id,
            'invoice_number' => $this->invoice_number,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'note' => $this->note,
            'details' =>   InvoiceDetailsResource::collection($this->whenLoaded('invoice_details')),
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}

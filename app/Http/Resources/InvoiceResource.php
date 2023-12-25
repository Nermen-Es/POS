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
           'created_by'=> $this->user->name,
            'supplier_id' => $this->supplier->name,
            'invoice_number' => $this->invoice_number,
            'total' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'status' => $this->status,
            'note' => $this->note,
            'details' =>   InvoiceDetailsResource::collection($this->whenLoaded('invoice_details')),
            'partialies' =>   InvoicePartially::collection($this->whenLoaded('partialies')),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}

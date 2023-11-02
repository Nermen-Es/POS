<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Services\OrderDetailService;
use App\Http\Resources\InvoiceResource;
use Validator;


class InvoiceController extends Controller
{
    use ApiResponseTrait;

    public function __construct(OrderDetailService $OrderDetailService)
    {
        $this->OrderDetailService = $OrderDetailService;
    }

    public function index()
    {
        $invoices = Invoice::with(['invoice_details'])->get();
        return $this->apiResponse(InvoiceResource::collection($invoices), 'Success' , 200);
    }

       //store products
       public function store(Request $request)
       {
           try {

               $validator = Validator::make($request->all(), [
                   'invoice_date' => 'nullable|date',
                   'total_price' => 'required|numeric|min:0',
                   'note' => 'nullable|string',
               ]);

               if ($validator->fails()) {
                   return response()->json($validator->errors(), 400);
               }

               $invoice = Invoice::create([
                   'user_id' => $request->user_id,
                   'spplier_id' => $request->spplier_id,
                   'invoice_number' => $request->invoice_number,
                   'invoice_date' => $request->invoice_date,
                   'total_price' => $request->total_price,
                   'note' => $request->note,

               ]);

               $invoice_id = Invoice::latest()->first()->id;
               if($invoice_id){

                $OrderDetailService =  $this->OrderDetailService->create($invoice_id, $request->product, $request->price, $request->quantity);
               }

               return $this->apiResponse(new InvoiceResource($invoice), 'invoice created successfully' , 201);

           }catch (\Throwable $th) {
               return $this->errorResponse($th);
           }
       }

}

<?php

namespace App\Http\Controllers\Api;

use Validator;
use Carbon\Carbon;
use App\Models\{Invoice,InvoiceDetail};
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Services\OrderDetailService;
use App\Http\Resources\InvoiceResource;


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


    //show an invoice with details
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);

        //fetch  post from database and store in $posts
        if ($invoice) {

            $invoice = Invoice::query()->where('id', '=', $id)->with(['invoice_details'])->first();

            return $this->apiResponse(new InvoiceResource($invoice), 'ok', 200);
        }
        return $this->apiResponse(null, 'the invoice not found', 404);
    }


       //store invoice
       public function store(Request $request)
       {

        $input_invoice = $request->input('invoice');

           try {

               $validator = Validator::make( $input_invoice, [
                   'invoice_date' => 'nullable|date',
                   'total_amount' => 'required|numeric|min:0',
                   'paid_amount'  => 'required|numeric|min:0|lte:total_amount',
                   'remaining_amount' => 'required|numeric|min:0|lte:total_amount',
                   'note' => 'nullable|string',
                   'status' => 'nullable|in:Paid,Partially',
               ]);

               if ($validator->fails()) {
                   return response()->json($validator->errors(), 400);
               }
               //change status dynamic
               if($input_invoice['remaining_amount'] == 0){
                $status = "Paid";
               }else{
                $status = "Partially";
               }

               $invoice = Invoice::create([
                // 'user_id' => Auth::guard('api')->user()->id,
                   'user_id' => $input_invoice['user_id'],
                   'spplier_id' => $input_invoice['spplier_id'],
                   'invoice_number' => $input_invoice['invoice_number'],
                   'invoice_date' => Carbon::now()->format('Y-m-d'),
                   'total_amount' => $input_invoice['total_amount'],
                   'paid_amount' => $input_invoice['paid_amount'],
                   'remaining_amount' => $input_invoice['remaining_amount'],
                   'status'  =>   $status,
                   'note' => $input_invoice['note'],

               ]);

               if($invoice->id){
                foreach($request->items as $item){

                    $OrderDetailService =  $this->OrderDetailService->create($invoice->id, $item['product'], $item['price'],$item['quantity'] , $item['sub_total']);
                }

               }

               $invoice = Invoice::query()->with(['invoice_details'])->orderBy('id', 'Desc')->first();

               return $this->apiResponse(new InvoiceResource($invoice), 'invoice created successfully' , 201);

           }catch (\Throwable $th) {
               return $this->errorResponse($th);
           }
       }


        //update invoice
       public function update(Request $request , $id)
       {
        $input_invoice = $request->input('invoice');

        try{

            $invoice = Invoice::findOrFail($id);
            //array of details ids
            $invoice_details_ids = InvoiceDetail::where('invoice_id', $invoice->id)->pluck('id');
            //delete invoice_details
            InvoiceDetail::whereIn('id' ,$invoice_details_ids)->delete();


            $validator = Validator::make( $input_invoice, [
                'invoice_date' => 'nullable|date',
                'total_amount' => 'nullable|numeric|min:0',
                'paid_amount'  => 'nullable|numeric|min:0|lte:total_amount',
                'remaining_amount' => 'nullable|numeric|min:0|lte:total_amount',
                'note' => 'nullable|string',
                'status' => 'nullable|in:Paid,Partially',
            ]);

            //change status dynamic
            if($input_invoice['remaining_amount'] == 0){
            $status = "Paid";
            }else{
            $status = "Partially";
            }

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $invoice->update([
                 // 'user_id' => Auth::guard('api')->user()->id,
                'user_id' => $input_invoice['user_id'],
                'spplier_id' => $input_invoice['spplier_id'],
                'invoice_number' => $input_invoice['invoice_number'],
                'invoice_date' => Carbon::now()->format('Y-m-d'),
                'total_amount' => $input_invoice['total_amount'],
                'paid_amount' => $input_invoice['paid_amount'],
                'remaining_amount' => $input_invoice['remaining_amount'],
                'status'  =>   $status,
                'note' => $input_invoice['note'],
            ]);

            if($invoice->id){
                foreach($request->items as $item){

                    $OrderDetailService =  $this->OrderDetailService->create($invoice->id, $item['product'], $item['price'],$item['quantity'] , $item['sub_total']);
                }
            }

            $invoice = Invoice::query()->with(['invoice_details'])->orderBy('id', 'Desc')->first();

            return $this->apiResponse(new InvoiceResource($invoice), 'invoice created successfully' , 201);


           }catch (\Throwable $th) {
               return $this->errorResponse($th);
           }



       }



       //delete invoice
       public function destroy($id)
       {

           $invoice = Invoice::find($id);

           if ($invoice) {
               $invoice->delete();
               return $this->apiResponse(null, 'the invoice deleted successfully number '.$id, 200);
           }

           return $this->apiResponse(null, 'the invoice not found', 404);
       }

        //search
    public function search($term)

    {
        $invoices = Invoice::search($term)->get();
        if (count($invoices)) {
            return $this->apiResponse(InvoiceResource::collection($invoices), 'ok', 200);
        } else {
            return $this->apiResponse(null, 'There is no invoice like ' . $term, 404);
        }
    }

    public function InDetails($term)

    {
        $invoice_details = InvoiceDetail::search($term)->get();
        if (count($invoice_details)) {
            return $this->apiResponse($invoice_details, 'ok', 200);
        } else {
            return $this->apiResponse(null, 'There is no invoice details like ' . $term, 404);
        }
    }

}

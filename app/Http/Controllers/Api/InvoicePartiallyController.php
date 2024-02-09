<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Models\{Partially,Invoice};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\{InvoicePartially,InvoiceResource};


class InvoicePartiallyController extends Controller
{
    use ApiResponseTrait;

    public function index($id)
    {
             // foreach(  $invoices_Partially as   $invoices){
            //     dd( $invoices->invoice->invoice_number);
            // }
        $invoice = Invoice::where('id', $id)->first();

        try{
            $invoice = Invoice::query()->with('partialies')->orderBy('id','Desc')->first();

            return $this->apiResponse(new InvoiceResource($invoice), 'Success' , 200);

        }catch (\Throwable $th) {
                return $this->errorResponse($th);
        }

    }


    //create new invoicepartially
    public function store(Request $request , $id)
    {

        $invoice = Invoice::where('id', $id)->first();

        try {

            $validator = \Validator::make($request->all(), [
                'paid_amount' => 'required|numeric|min:0',
                'remaining_amount' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }


            $invoice_partially = Partially::create([
                   'user_id' => Auth::guard('api')->user()->id,
                   'invoice_id' => $id,
                   'supplier_name' => $invoice->supplier->first()->name,
                   'paid_amount' => $request->paid_amount,
                   'remaining_amount' => $request->remaining_amount,

               ]);

               $invoice->update([
               'paid_amount' => $invoice->paid_amount + $request->paid_amount,
               'remaining_amount' => $request->remaining_amount,

           ]);


        return $this->apiResponse(new InvoicePartially($invoice_partially), 'invoice created successfully' , 201);

        }catch(\Throwable $th) {
            return $this->errorResponse($th);
        }
    }

    //update partialy for invoice
    public function update(Request $request , $id)
    {

       // $invoice = Invoice::where('id', $id)->first();

        try {

            $invoice_partially = Partially::findOrFail($id);

            $old_paid_amount = $invoice_partially->paid_amount;

            $invoice = Invoice::where('id',  $invoice_partially->invoice_id)->first();

            $validator = \Validator::make($request->all(), [
                'paid_amount' => 'nullable|numeric|min:0',
                'remaining_amount' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }


            $invoice_partially->update([
                   'user_id' => Auth::guard('api')->user()->id,
                   'invoice_id' =>  $invoice->id,
                   'supplier_name' => $invoice->supplier->first()->name,
                   'paid_amount' => $request->paid_amount,
                   'remaining_amount' => $request->remaining_amount,

               ]);

               //minus old value
               $invoice->paid_amount = $invoice->paid_amount - $old_paid_amount;
               //plus new value
               $invoice->update([
               'paid_amount' => $invoice->paid_amount + $request->paid_amount,
               'remaining_amount' => $request->remaining_amount,

           ]);


        return $this->apiResponse(new InvoicePartially($invoice_partially), 'invoice created successfully' , 201);

        }catch(\Throwable $th) {
            return $this->errorResponse($th);
        }
    }



   //delete partialy
   public function destroy($id){

        try{
            $invoice_partially = Partially::findOrFail($id);
            $invoice_partially->delete();
            return $this->apiResponse(null, 'the invoice partially deleted successfully number '.$id , 200);
        }
        catch(\Throwable $th){

        }
    }

}

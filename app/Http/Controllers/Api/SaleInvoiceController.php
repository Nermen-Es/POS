<?php

namespace App\Http\Controllers\Api;


use Validator;
use Carbon\Carbon;
use App\Models\SaleInvoice;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\SaleInvoiceResource;

class SaleInvoiceController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $sale_invoices = \DB::table('sale_invoices')->get();
        return $this->apiResponse(SaleInvoiceResource::collection($sale_invoices), 'Success' , 200);
    }


    //show an sale_invoice
    public function show($id)
    {
        $sale_invoices = SaleInvoice::findOrFail($id);

        //fetch  post from database and store in $posts
        if ($sale_invoices) {

            $sale_invoices = SaleInvoice::query()->where('id', '=', $id)->first();

            return $this->apiResponse(new SaleInvoiceResource($sale_invoices), 'ok', 200);
        }
        return $this->apiResponse(null, 'the invoice not found', 404);
    }

     //store Sale Invoice
     public function store(Request $request)
     {
      $input_invoice = $request->input();
         try {

             $validator = Validator::make( $input_invoice, [
                 'start_day_date' => 'nullable|date',
                 'total_amount' => 'required|numeric|min:0',
                 'note' => 'nullable|string',
             ]);

             $sale_invoices = SaleInvoice::create([
                 'user_id' => Auth::guard('api')->user()->id,
                 //'user_id' => $input_invoice['user_id'],
                 'start_day_date' => $input_invoice['start_day_date'],
                 'total_amount' => $input_invoice['total_amount'],
                 'note' => $input_invoice['note'],

             ]);

             $sale_invoices = SaleInvoice::query()->orderBy('id', 'Desc')->first();

             return $this->apiResponse(new SaleInvoiceResource($sale_invoices), 'sale invoices created successfully' , 201);

         }catch (\Throwable $th) {
             return $this->errorResponse($th);
         }
     }


        //update invoice
        public function update(Request $request , $id)
        {

         try{

             $sale_invoices = SaleInvoice::findOrFail($id);

             $validator = Validator::make($request->input(), [
                'start_day_date' => 'nullable|date',
                'total_amount' => 'nullable|numeric|min:0',
                'note' => 'nullable|string',
             ]);

             if ($validator->fails()) {
                 return response()->json($validator->errors(), 400);
             }

             $sale_invoices->update($request->input());

             $sale_invoices = SaleInvoice::query()->first();

             return $this->apiResponse(new SaleInvoiceResource($sale_invoices), 'sale invoices created successfully' , 201);


            }catch (\Throwable $th) {
                return $this->errorResponse($th);
            }
        }

        //delete sale_invoice
        public function destroy($id)
        {
            try {

            $sale_invoices = SaleInvoice::find($id);

            if ($sale_invoices) {

                $sale_invoices->delete();
                return $this->apiResponse( "" ,'sale_invoice deleted successfully number '.$id , 200);
                }else{
                    return $this->notFoundResponse();
                }

            } catch (\Throwable $th) {
                return $this->errorResponse($th);
            }

    }


}

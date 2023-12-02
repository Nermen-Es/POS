<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Spplier;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;

class SupplierController extends Controller
{
      use ApiResponseTrait;

    //fetch all Suppliers
     public function index(){
        $Suppliers = Spplier::all();
        return $this->apiResponse(SupplierResource::collection($Suppliers), 'Success' , 200);

    }

      //store Suppliers
      public function store(Request $request)
      {
          try {

              $validator = Validator::make($request->all(), [
                  'name' => 'required|string|max:255',
                  'phone' => 'required|string|min:6',
                  'address' => 'required|string',
              ]);

              if ($validator->fails()) {
                  return response()->json($validator->errors(), 400);
              }

              $Supplier = Spplier::create([

                  'name' => $request->name,
                  'phone' => $request->phone,
                  'address' => $request->address,

              ]);


              return $this->apiResponse(new SupplierResource($Supplier), 'Supplier created successfully' , 201);

          }catch (\Throwable $th) {
              return $this->errorResponse($th);
          }
      }

        //update  Supplier
        public function update(Request $request, $id)
        {
            try {

                $Supplier = Spplier::find($id);
                if ($Supplier) {

                    $Supplier->update($request->input());

                 return $this->apiResponse(new SupplierResource($Supplier), 'Supplier updated successfully' , 200);

                }
                return $this->notFoundResponse();

            } catch (\Throwable $th) {
                return $this->errorResponse($th);
            }
        }

    //delete Supplier
    public function destroy($id)
    {
        try {
        $Supplier = Spplier::find($id);
        if ($Supplier) {

            $Supplier->delete();
            return $this->apiResponse( "" ,'Supplier deleted successfully' , 200);
            }else{
                return $this->notFoundResponse();
            }

        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

    }


    public function dropDownSupplier(){
        try{
            $suppliers = Spplier::pluck('name', 'id');

            return $this->apiResponse($suppliers , 'Success' , 200);

        }catch(\Throwable $th) {
            return $this->errorResponse($th);
        }
    }


    //search
    public function search($term)

    {
        $suppliers = Spplier::search($term)->get();
        if (count($suppliers)) {
            return $this->apiResponse($suppliers, 'ok', 200);
        } else {
            return $this->apiResponse(null, 'There is no supplier like ' . $term, 404);
        }
    }

}

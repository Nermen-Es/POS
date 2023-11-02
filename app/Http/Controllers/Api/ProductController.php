<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Validator;

class ProductController extends Controller
{
    use ApiResponseTrait;

     //fetch all products
     public function index(){
            $products = Product::all();
            return $this->apiResponse(ProductResource::collection($products), 'Success' , 200);

        }

    //store products
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'brand' => 'nullable|string',
                'unit' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $product = Product::create([

                'name' => $request->name,
                'brand' => $request->brand,
                'unit' => $request->unit,
                'description' => $request->description,

            ]);


            return $this->apiResponse(new ProductResource($product), 'Product created successfully' , 201);

        }catch (\Throwable $th) {
            return $this->errorResponse($th);
        }
    }


       //update  products
       public function update(Request $request, $id)
       {
           try {

               $product = Product::find($id);
               if ($product) {

                   $product->update($request->input());

                return $this->apiResponse(new ProductResource($product), 'Product updated successfully' , 200);

               }
               return $this->notFoundResponse();

           } catch (\Throwable $th) {
               return $this->errorResponse($th);
           }
       }

    //delete products
    public function destroy($id)
    {
        try {
        $product = Product::find($id);
        if ($product) {

            $product->delete();
            return $this->apiResponse( "" ,'Product deleted successfully' , 200);
            }else{
                return $this->notFoundResponse();
            }

        } catch (\Throwable $th) {
            return $this->errorResponse($th);
        }

    }


}

<?php
namespace App\Traits;
use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiResponseTrait{


    public function apiResponse($data= null , $message = null ,  $statusCode= null){
        $array = [
            'data' =>$data,
            'message'=>$message,
            'Status'=> $statusCode ,

        ];
        return response($array);
    }

    //error handling
     public function errorResponse($exception, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
      return response()->json([
        'data' => [
          'success' => false,
          'message' => $exception->getMessage()
        ]
      ], $statusCode);
    }

    //IF record not found
    public function notFoundResponse()
    {
        return response()->json([
            'message' => 'Invalid ID!',
            'status' => Response::HTTP_NOT_FOUND,
        ]);
    }


    //ValidationErrors handling
    protected function ValidationErrors(Validator $validator)
    {
        return   throw new HttpResponseException(response()->json([
            'data' => [
              'success' => false,
              'errors' => $validator->errors()
            ]
          ], 422));
    }

    }



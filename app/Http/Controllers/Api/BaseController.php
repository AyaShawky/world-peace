<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use stdClass;

class BaseController extends Controller
{
    public function sendResponse( $message , $result)
    {
        $response = [

            'status' => true,
            'statusCode'=>200,
            'message' => $message,
            'items'    => $result,

        ];

        return response()->json($response, 200);
    }


    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status' => false,
            'statusCode'=>404,
            'message' => $error,
            'items'=>new stdClass()
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}

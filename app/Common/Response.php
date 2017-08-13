<?php

namespace App\Common;


class Response
{
	static function success($data = [], $message = 'OK', $code = 200){
        return response()->json(['data' => $data, 'message' => $message], $code);
    }
    static function error($message = 'Internal Server Error', $code = 500){
        return response()->json(['message' => $message], $code);
    }
}

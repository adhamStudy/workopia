<?php

namespace App\Controllers;


class ErrorController
{
    /**
     * 404 not found
     * 
     */

    public static function notFound($message = 'Resource not found')
    {
        http_response_code(404);
        loadView('error', [
            'status' => '404', // Key => Value
            'message' => $message // Key => Value
        ]);
    }


    /**
     * 404 not found
     * 
     */

    public static function unAuthorized($message = 'you are not authorized to view this resource')
    {
        http_response_code(403);
        loadView('error', [
            'status' => '403', // Key => Value
            'message' => $message // Key => Value
        ]);
    }


    public function index() {}
}

<?php

namespace App\Core;

/**
 * Response class for sending HTTP response
 */
class Response
{
    /**
     * Instance of Response
     *
     * @var Response
     */
    private static $instance;
    
    /**
     * Send Response
     *
     * @param  mixed $data
     * @param  mixed $code
     */
    public static function send($data, $code = 200)
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data);

        return $data;
    }
    
    /**
     * Send a not found response
     *
     * @return void
     */
    public static function send404(): void
    {
        self::send([
            'error' => 'ERR_PAGE_NOT_FOUND',
            'message' => 'The page you requested was not found'
        ], 404);
    }

    /**
     * Get an instance of Response
     *
     * @return Response
     */
    public static function getInstance(): Response
    {
        if (self::$instance == null) {
            self::$instance = new Response();
        }
 
        return self::$instance;
    }
}

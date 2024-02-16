<?php

namespace App\Core;

/**
 * Handle Routing For the App
 */
class Router
{
    /**
     * Instance of Router
     *
     * @var Router
     */
    private static $instance;
    
    /**
     * Registerd routes based on method types
     *
     * @var array
     */
    public static $registeredRoutes = [
        'GET' => null,
        'POST' => null
    ];

    /**
     *
     * Links method based url defined in Routes.php file for GET methods
     *
     * @param string $url
     * @param string $callback
     */
    public static function get(string $url, string $callback): void
    {
        self::$registeredRoutes['GET'][$url] = $callback;
    }

    /**
     *
     * Links method based url defined in Routes.php file for POST methods
     *
     * @param string $url
     * @param string $callback
     */
    public static function post(string $url, string $callback): void
    {
        self::$registeredRoutes['POST'][$url] = $callback;
    }
    
    /**
     * Get registered routes for the given method
     *
     * @param  string $method
     * @return array
     */
    public function getRegisteredRoutes(string $method): array
    {
        return self::$registeredRoutes[$method];
    }

    /**
     * Get an instance of Router class
     *
     * @return Router
     */
    public static function getInstance(): Router
    {
        if (self::$instance == null) {
            self::$instance = new Router();
        }
 
        return self::$instance;
    }
}

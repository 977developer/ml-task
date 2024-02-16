<?php

namespace App\Core;

use App\Core\Helper;
use App\Core\Response;
use App\Core\Request;
use App\Core\Router;
use App\Controller;

/**
 * Main Application Class
 */
class App
{
    /**
     * @var App $instance
     */
    private static $instance;

    /**
     * @var Response $response
     */
    private $response;

    /**
     * @var Request $request
     */
    private $request;

    /**
     * @var array[]
     */
    private $registeredRoutes;

    /**
     * @var string
     */
    private $url;

    /**
     * Bootstrap the application
     *
     * This will bootstrap the application by setting class variables for request,
     * response, url and register routes based on the curren method.
     *
     * @return App
     */
    private function bootstrap(): App
    {
        $this->request  = new Request();
        $this->response = Response::getInstance();
        $this->url      = $this->request->url();
        $this->registeredRoutes = Router::getInstance()
            ->getRegisteredRoutes(
                $this->request->method()
            );

        return $this;
    }

    /**
     * Star the application
     *
     * Checks the routes based on current url and fires a controller method
     * or sends 404 for not appropriate controller response.
     *
     * @return Response|void
     */
    public function start()
    {
        $routes = array_keys($this->registeredRoutes);
        $currentUrl = Helper::getUrlInfo($this->url);

        foreach ($routes as $key => $url) {
            $routeUrl = Helper::getUrlInfo($url);
            $methodToInvoke = $this->registeredRoutes[$url];

            if ($currentUrl['base'] === $routeUrl['base']
                && $currentUrl['length'] === $routeUrl['length']) {
                $wildcard = Helper::routeContainsWildCard([$url]);
                if ($wildcard) {
                    $this->request->set($wildcard, $currentUrl['last']);
                }

                $controller = new Controller();
                return $controller->{$methodToInvoke}($this->request);
            }
        }

        return $this->response::send404();
    }

    /**
     * Get an instance of the App
     *
     * @return App
     */
    public static function getInstance(): App
    {
        if (self::$instance == null) {
            self::$instance = new App();
        }
 
        return self::$instance->bootstrap();
    }
}

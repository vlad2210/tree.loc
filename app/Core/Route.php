<?php

namespace App\Core;

/**
 * Class Route
 * @package App\Core
 */
class Route
{
    /**
     * Initialization routing
     */
    static function start()
    {
        $controllerName = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if ( !empty($routes[1]) ) {
            $controllerName = $routes[1];
        }

        if ( !empty($routes[2]) ) {
            $action_name = $routes[2];
        }

        $model_name = 'Model'.$controllerName;
        $controllerName = 'Controller'.$controllerName;
        $action_name = 'action'.$action_name;

        $model_file = strtolower($model_name).'.php';
        $model_path = "app/Models/".$model_file;

        if(file_exists($model_path)) {
            include "app/Models/".$model_file;
        }

        $controller_file = strtolower($controllerName).'.php';
        $controller_path = "app/Controllers/".$controller_file;

        if(file_exists($controller_path)) {
            include "app/Controllers/".$controller_file;
        } else {
            Route::ErrorPage404();
        }

        $controllerName = "\\App\\Controllers\\".$controllerName;

        $controller = new $controllerName;
        $action = $action_name;

        if(method_exists($controller, $action)) {
            $controller->$action();
        } else {
            Route::ErrorPage404();
        }

    }

    function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }
}
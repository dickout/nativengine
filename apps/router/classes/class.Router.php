<?php

namespace apps\router\classes;

use apps\core\classes\Config;
use apps\router\classes\Http;

class Router 
{
    private static $config;
    private static $routes;
    private static $currentRoute;

    public static function factory ()
    {
        define ("REQUEST", rtrim ($_SERVER["QUERY_STRING"], "/"));
        define ("PROTOCOL", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https" : "http");
        define ("DOMAIN", PROTOCOL . "://" . $_SERVER["HTTP_HOST"]);

        self::$config = new Config(APPS . "/router", "config", [ "routes" => [] ]);
        self::$routes = self::$config->getOption("routes");
    }

    public static function addApplication (string $name, array $data, array $routes, bool $importantRoutes = false)
    {
        foreach($routes as $pattern => $route)
            if($importantRoutes or !isset(self::$routes[$pattern]))
                self::$routes[$pattern] = ["app" => $name, "data" => $data, "path" => $route];
    }

    public static function match ($url)
    {
        foreach (self::$routes as $pattern => $appdata) {
            $route = $appdata["path"];

            if (preg_match ("#$pattern#i", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string ($k)) {
                        $route[$k] = $v;
                    }
                }

                if (!isset($route[ "action" ])) $route["action"] = "Main";

                $route["controller"] = $route["controller"];
                $route["action"] = $route["action"];
                $appdata["path"] = $route;

                self::$currentRoute = $appdata;
                return true;
            }
        }

        return false;
    }

    protected static function url ()
    {
        $p = explode ("&", REQUEST, 2);
        if (false === strpos ($p[0], "=")) return rtrim ($p[0], "/");
        else return "";
    }

    protected static function error ()
    {
        return Http::response("Ошибка 404");
    }

    public static function dispatch ()
    {
        if (self::match (self::url ())) {
            $appname = self::$currentRoute["app"];
            $dir = self::$currentRoute["data"]["webdir"];
            $path = self::$currentRoute["path"];

            $controller = class_make("apps/" . $appname . $dir . "/controllers/" . $path["controller"]);
            if(class_exists($controller))
            {
                $object = new $controller();
                $action = $path["action"];
                if ( method_exists ($object, $action) ) {
                    $controller::$action();
                    $controller::render(self::$currentRoute);
                } else
                    self::error();
            } else
                self::error();
        } else self::error();
    }
}
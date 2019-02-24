<?php

namespace apps\router\classes;

use apps\router\classes\View;

class Controller
{
    public static $template;
    public static $vars = [];

    public static function setTemplate ($template)
    {
        self::$template = $template;
    }

    public static function setVars (array $vars)
    {
        self::$vars = $vars;
    }

    public static function render ($route)
    {
        $object = new View($route);
        $object->render (self::$template, self::$vars);
    }
}
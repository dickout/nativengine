<?php

namespace apps\router\classes;

use apps\router\classes\Template;

class View
{
    public static $route = [];

    public function __construct ( $route )
    {
        self::$route = $route;
    }

    public static function render ($template, $vars = [])
    {
        $template = new Template(APPS . "/" . self::$route["app"] . self::$route["data"]["webdir"] . "/templates/tpl.wrapper.php", $template, self::$route);
        $template->render($vars);
    }
}

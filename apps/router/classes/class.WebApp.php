<?php

namespace apps\router\classes;

use apps\core\classes\App as NativeApp;

class WebApp extends NativeApp
{
    public $relations = ["core", "router"];
}
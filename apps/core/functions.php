<?php

function debug($data)
{
    echo '<pre class="native-debug">' . print_r($data, true) . '</pre>';
}

function class_make($class_path)
{
    return str_replace("/", "\\", $class_path);
}
<?php

namespace apps\core\classes;

class Autoloader
{
	public $name;
	public $prefix;
	public $postfix;

	public static function register ($name, $prefix = "", $postfix = ""): Autoloader
	{
		return new self($name, $prefix, $postfix);
	}

	private function handler (): callable
	{
		return function ( $class ) {
			$class_real = str_replace ("\\", "/", $class);
			$class_tmp = explode("/", $class_real);
			$class_tmp[count($class_tmp)-1] = $this->prefix . $class_tmp[count($class_tmp)-1];
			$class_real = implode("/", $class_tmp);
			$file = ROOT . "/" . $class_real . $this->postfix . ".php";

		    if (is_file ($file))
		        require_once $file;
		};
	}

	private function __construct($name, $prefix, $postfix)
	{
		$this->name = $name;
		$this->prefix = $prefix;
		$this->postfix = $postfix;
		spl_autoload_register ($this->handler());
	}
}
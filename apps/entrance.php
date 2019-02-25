<?php  

/**
 * Point of no return
 * Silence is golden
 */

 
require_once 'core/classes/class.Autoloader.php';
require_once 'core/functions.php';

$execution_time = microtime(true);

class Entrance
{
	private $system;
	private $classLoader;
	private $appLoader;
	private $callbacks = [];

	public function __construct (string $system) {
		define("ROOT", dirname(__DIR__));
		define("APPS", ROOT . "/apps");

		$this->system = $system;
		$this->classLoader = apps\core\classes\Autoloader::register("core", "class.");
		$this->appLoader = new apps\core\classes\AppManager($this);
	}

	public function getClassLoader ()
	{
		return $this->classLoader;
	}

	public function getAppLoader ()
	{
		return $this->appLoader;
	}

	public function invokeCallback(string $name, array $args = [])
	{
		if(isset($this->callbacks[$name]) and !empty($this->callbacks[$name]))
			foreach($this->callbacks[$name] as $callable)
				$callable($args);
	}

	public function createCallback(string $name, callable $callable)
	{
		if(!isset($this->callbacks[$name])) $this->callbacks[$name] = [];
		$this->callbacks[$name][] = $callable;
	}
	
	public function hasCallbacks(string $name)
	{
		return isset($this->callbacks[$name]) and !empty($this->callbacks[$name]);
	}
} 

$system = new Entrance(php_uname());
$config = new apps\core\classes\Config(APPS, "bootstrap", [ "app" => "core", "execution_time" => false ]);
$bootstrap = $config->getOption("app");

$system->getAppLoader()->$bootstrap()->dispatch();
$execution_time = microtime(true) - $execution_time;

$system->invokeCallback("shutdown");

if($config->getOption("execution_time") == true) 
	require (APPS . "/core/html/execution_time.php"); 

?>
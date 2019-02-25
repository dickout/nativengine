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

if($config->getOption("execution_time") == true) {
	$styles = '<style>.native-execution-time {margin: 50px 20px 10px; padding-top: 10px; border-top: 1px solid rgba(0, 0, 0, .3); color: rgba(0, 0, 0, .7)}</style>';
	$ms = $execution_time * 1000;
	if($ms <= 80) $speed = '<span style="color: green">Fast</span>';
	else if($ms > 80 and $ms <= 250) $speed = '<span style="color: blue">Normal</span>';
	else if($ms > 500 and $ms <= 3000) $speed = '<span style="color: orange">Slow</span>';
	else $speed = '<span style="color: red">Very slow!</span>';

	echo $styles . '<div class="native-execution-time"><span style="color: black">Script execution time: <b>'.$speed.'</b></span><br><b>'.number_format($execution_time / 60, 7).' minutes </b>&nbsp;|&nbsp;&nbsp;<b>'.number_format($execution_time, 5).' seconds</b>&nbsp;|&nbsp;&nbsp;<b>'.$ms.' milliseconds</b>&nbsp;|&nbsp;&nbsp;<b>'.number_format($execution_time * 1000000, 0, '.', ' ').' microseconds</b>&nbsp;|&nbsp;&nbsp;<b>'.number_format($execution_time * 1000000000, 0, '.', ' ').' nanoseconds</b></div>';
}
?>
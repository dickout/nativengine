<?

namespace apps\router\classes;

use apps\core\classes\Json;

class Http
{
	public static function response ($n) {
		if(is_numeric($n)) return http_response_code($n);
		else if(is_array($n)) echo Json::encode($n);
		else if(is_string($n)) echo $n;

		return self::KillRequest();
	}

	public static function getAction ($array) {
		if(isset($array["action"]) and trim($array["action"])) return $array["action"];
		else return "404";
	}

	public static function getData ($array) {
		if(isset($array["action"])) unset($array["action"]);
		return $array;
	}

	public static function isHttpRequest ()
    {
        return isset($_SERVER[ "HTTP_X_REQUESTED_WITH" ]) && $_SERVER[ "HTTP_X_REQUESTED_WITH" ] === "XMLHttpRequest";
    }

	public static function validateRequest ($data, $callback, $kill = false) {
		if(self::isHttpRequest()) {
			$action = self::getAction ($_POST);
			$data = self::getData ($_POST);
			if($action !== "404") $callback($action, $data);
			else self::response(404);
			return self::killRequest();
        } else self::response(404);
        
		if($kill) return self::killRequest();
	}

	public static function killRequest ()
    {	
    	return die;
    }
}
<?php

namespace apps\router\classes;

class SpecialCode 
{
	public static $symbols = [
		"OPEN_BRACKET" => "{",
		"CLOSE_BRACKET" => "}",
		"EQUALS" => "=",
		"QUOTES" => ["'", '"'],
		"DELEMITER" => " "
	];

	public static function parseArguments ($function, $string) 
	{
		$string = str_replace([self::$symbols["OPEN_BRACKET"].$function." ", self::$symbols["CLOSE_BRACKET"]], "", $string);
		$arr = explode(self::$symbols["DELEMITER"], $string);
		$returnable = [];

		foreach ($arr as $str) {
			$ar = explode(self::$symbols["EQUALS"], $str);
			$ar[1] = str_replace([self::$symbols["QUOTES"][0], self::$symbols["QUOTES"][1]], "", $ar[1]);
			$returnable[$ar[0]] = $ar[1];
		}

		return $returnable;
	}

	public static function getFunctions ($function, $string) 
	{
		preg_match_all("/".self::$symbols['OPEN_BRACKET'].$function." .*".self::$symbols['CLOSE_BRACKET']."/i", $string, $matches);
		return $matches[0];
	}
}
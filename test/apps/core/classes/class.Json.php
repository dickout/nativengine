<?php

namespace apps\core\classes;

class Json
{
	public static $options = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;

	public static function decode (string $content): array {
		return json_decode($content, true, 512, self::$options);
	}

	public static function encode (array $content): string {
		return json_encode($content, self::$options);
	}
}
<?php
declare(strict_types = 1);

/**
 * Class ConfigHelper
 * Манипулирует конфигами. Подключать через include, потому что автолоадер на этот момент ещё не отработал
 */
class ConfigHelper {

	/**
	 * Функция достаёт переменную окружения из окружения, позволяя ставить заглушки
	 * @param string $env Имя переменной окружения
	 * @param string|array|false|null $default Значение, возвращаемое, если переменной нет в окружении.
	 * @param bool $throw true: вместо возврата $default выбросить исключение
	 * @return string|array|false|null Значение переменной окружения
	 * @throws Exception
	 */
	public static function getenv(string $env, null|string|array|false $default = null, bool $throw = true):string|array|false|null {
		if (getenv($env)) return getenv($env);
		if (isset($_ENV[$env])) return $_ENV[$env];
		if ($throw) throw new Exception("Environment variable $env is required in config.");
		return $default;
	}

	/**
	 * Замена дефолтному require
	 * @param string $filePath
	 * @param string $dir
	 * @return mixed
	 * @throws Exception
	 */
	public static function require(string $filePath, string $dir = __DIR__) {
		$requiredFilePath = $dir.DIRECTORY_SEPARATOR.$filePath;
		if (is_file($requiredFilePath)) return require $requiredFilePath;

		throw new Exception("Unable to find file '$requiredFilePath'");
	}
}
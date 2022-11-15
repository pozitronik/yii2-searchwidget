<?php
declare(strict_types = 1);

namespace pozitronik\widgets;

use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class SearchHelper
 */
class SearchHelper {

	/**
	 * Creates or re-creates a object using the given configuration
	 * @param string|array|callable|object $type The object or object type (like in Yii::createObject)
	 * @param array $params The object configuration array
	 * @param bool $recreate If object is passed in $type, then recreate it with given configuration
	 * @return object The created object
	 * @throws InvalidConfigException
	 */
	public static function createObject(string|array|callable|object $type, array $params = [], bool $recreate = false):object {
		if (is_object($type)) {
			if ($recreate) {
				$type = $type::class;
			} else return $type;
		}
		return Yii::createObject($type, $params);
	}

	/**
	 * Switches between qwerty/йцукен layouts (used for independent searches)
	 * @param string $term
	 * @param bool $fromQWERTY
	 * @return string
	 */
	public static function SwitchKeyboard(string $term, bool $fromQWERTY = false):string {
		$converter = $fromQWERTY
			?[
				'f' => 'а', ',' => 'б', 'd' => 'в', 'u' => 'г', 'l' => 'д', 't' => 'е', '`' => 'ё',
				';' => 'ж', 'p' => 'з', 'b' => 'и', 'q' => 'й', 'r' => 'к', 'k' => 'л', 'v' => 'м',
				'y' => 'н', 'j' => 'о', 'g' => 'п', 'h' => 'р', 'c' => 'с', 'n' => 'т', 'e' => 'у',
				'a' => 'ф', '[' => 'х', 'w' => 'ц', 'x' => 'ч', 'i' => 'ш', 'o' => 'щ', 'm' => 'ь',
				's' => 'ы', ']' => 'ъ', "'" => "э", '.' => 'ю', 'z' => 'я',
				'F' => 'А', '<' => 'Б', 'D' => 'В', 'U' => 'Г', 'L' => 'Д', 'T' => 'Е', '~' => 'Ё',
				':' => 'Ж', 'P' => 'З', 'B' => 'И', 'Q' => 'Й', 'R' => 'К', 'K' => 'Л', 'V' => 'М',
				'Y' => 'Н', 'J' => 'О', 'G' => 'П', 'H' => 'Р', 'C' => 'С', 'N' => 'Т', 'E' => 'У',
				'A' => 'Ф', '{' => 'Х', 'W' => 'Ц', 'X' => 'Ч', 'I' => 'Ш', 'O' => 'Щ', 'M' => 'Ь',
				'S' => 'Ы', '}' => 'Ъ', '"' => 'Э', '>' => 'Ю', 'Z' => 'Я',
				'@' => '"', '#' => '№', '$' => ';', '^' => ':', '&' => '?', '/' => '.', '?' => ',']
			:[
				'а' => 'f', 'б' => ',', 'в' => 'd', 'г' => 'u', 'д' => 'l', 'е' => 't', 'ё' => '`',
				'ж' => ';', 'з' => 'p', 'и' => 'b', 'й' => 'q', 'к' => 'r', 'л' => 'k', 'м' => 'v',
				'н' => 'y', 'о' => 'j', 'п' => 'g', 'р' => 'h', 'с' => 'c', 'т' => 'n', 'у' => 'e',
				'ф' => 'a', 'х' => '[', 'ц' => 'w', 'ч' => 'x', 'ш' => 'i', 'щ' => 'o', 'ь' => 'm',
				'ы' => 's', 'ъ' => ']', 'э' => "'", 'ю' => '.', 'я' => 'z',
				'А' => 'F', 'Б' => '<', 'В' => 'D', 'Г' => 'U', 'Д' => 'L', 'Е' => 'T', 'Ё' => '~',
				'Ж' => ':', 'З' => 'P', 'И' => 'B', 'Й' => 'Q', 'К' => 'R', 'Л' => 'K', 'М' => 'V',
				'Н' => 'Y', 'О' => 'J', 'П' => 'G', 'Р' => 'H', 'С' => 'C', 'Т' => 'N', 'У' => 'E',
				'Ф' => 'A', 'Х' => '{', 'Ц' => 'W', 'Ч' => 'X', 'Ш' => 'I', 'Щ' => 'O', 'Ь' => 'M',
				'Ы' => 'S', 'Ъ' => '}', 'Э' => '"', 'Ю' => '>', 'Я' => 'Z',
				'"' => '@', '№' => '#', ';' => '$', ':' => '^', '?' => '&', '.' => '/', ',' => '?',
			];

		return strtr($term, $converter);
	}

	/**
	 * Assumes, which attributes can be used for searches
	 * @param string|Model $modelClass
	 * @return array
	 * @throws Exception
	 */
	public static function AssumeSearchAttributes(string|Model $modelClass):array {
		/** @var Model $model */
		$model = static::createObject($modelClass);
		$searchFields = [[]];
		foreach ($model->rules() as $rule) {
			if (in_array(ArrayHelper::getValue($rule, '1'), ['string', 'email'])) {
				$searchFields[] = (array)$rule[0];
			}
		}
		return array_merge(...$searchFields);
	}

}
<?php
declare(strict_types = 1);

namespace pozitronik\widgets;

use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownPropertyException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class SearchHelper
 */
class SearchHelper {

	public const SEARCH_TYPE_EQUAL = '=';
	public const SEARCH_TYPE_LIKE = 'like';
	public const SEARCH_TYPE_LIKE_BEGINNING = '%like';
	public const SEARCH_TYPE_LIKE_ENDING = 'like%';

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

	/**
	 * @param string $modelClass Имя класса ActiveRecord-модели (FQN), к которой подключается поиск
	 * @param string|null $term Поисковый запрос
	 * @param null|int $limit Лимит поиска
	 * @param array|null $searchAttributes Массив атрибутов, в которых производим поиск в формате
	 *    [
	 *        'attributeName',
	 *        'attributeName' => 'searchType'
	 *    ]
	 * где searchType - одна из SEARCH_TYPE_* - констант.
	 * Если параметр не задан, атрибуты подхватываются из правил валидации модели (все строковые атрибуты)
	 * @param string $method
	 * @return array
	 * @throws UnknownPropertyException
	 */
	public static function Search(string $modelClass, ?string $term, ?int $limit = SearchWidget::DEFAULT_LIMIT, ?array $searchAttributes = null, string $method = SearchWidget::DEFAULT_METHOD):array {
		/*В модели можно полностью переопределить поиск*/
		if (method_exists($modelClass, $method)) return $modelClass::$method($term, $limit, $searchAttributes);

		if (null === $searchAttributes) $searchAttributes = static::AssumeSearchAttributes($modelClass);

		/** @var ActiveRecord $modelClass */
		if ((null === $pk = ArrayHelper::getValue($modelClass::primaryKey(), 0))) {
			throw new UnknownPropertyException('Primary key not configured');
		}
		$tableName = $modelClass::tableName();
		$swTermCyr = static::SwitchKeyboard($term);
		$swTermLat = static::SwitchKeyboard($term, true);
		$searchQuery = $modelClass::find()->select("{$tableName}.{$pk}");
		foreach ($searchAttributes as $searchRule) {
			if (is_array($searchRule) && isset($searchRule[0], $searchRule[1])) {//attribute, search type
				[$searchAttribute, $searchType] = $searchRule;
			} else {
				$searchAttribute = $searchRule;
				$searchType = "like";
			}
			$searchQuery->addSelect("{$tableName}.{$searchAttribute} as {$searchAttribute}");
			switch ($searchType) {
				case static::SEARCH_TYPE_EQUAL:
					$searchQuery->orWhere(["=", "{$tableName}.{$searchAttribute}", $term]);
					$searchQuery->orWhere(["=", "{$tableName}.{$searchAttribute}", $swTermCyr]);
					$searchQuery->orWhere(["=", "{$tableName}.{$searchAttribute}", $swTermLat]);
				break;
				case static::SEARCH_TYPE_LIKE:
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$term%", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$swTermCyr%", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$swTermLat%", false]);
				break;
				case static::SEARCH_TYPE_LIKE_BEGINNING:
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$term", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$swTermCyr", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "%$swTermLat", false]);

				break;
				case static::SEARCH_TYPE_LIKE_ENDING:
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "$term%", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "$swTermCyr%", false]);
					$searchQuery->orWhere(["like", "{$tableName}.{$searchAttribute}", "$swTermLat%", false]);
				break;
			}
		}

		if (method_exists($searchQuery, 'active')) {
			$searchQuery->active();
		}
		return $searchQuery->distinct()
			->limit($limit)
			->asArray()
			->all();

	}

}
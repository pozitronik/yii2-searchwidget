<?php
declare(strict_types = 1);

namespace pozitronik\widgets;

use Yii;
use yii\base\Action;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;

/**
 * Base search action for SearchWidget
 */
class SearchAction extends Action {

	/**
	 * @param string $alias
	 * @param string|null $term
	 * @return string[][]
	 * @throws UnknownPropertyException
	 */
	public function run(string $alias, ?string $term):array {
		if (null !== $ARClass = ArrayHelper::getValue(Yii::$app, "params.searchConfig.{$alias}.class")) {
			return SearchHelper::Search(
				$ARClass,
				$term,
				ArrayHelper::getValue(Yii::$app, "params.searchConfig.{$alias}.limit", SearchWidget::DEFAULT_LIMIT),
				ArrayHelper::getValue(Yii::$app, "params.searchConfig.{$alias}.attributes"),
				ArrayHelper::getValue(Yii::$app, "params.searchConfig.{$alias}.method", SearchWidget::DEFAULT_METHOD));
		}
		return [];
	}
}
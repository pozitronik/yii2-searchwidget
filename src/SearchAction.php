<?php
declare(strict_types = 1);

namespace pozitronik\widgets;

use yii\base\Action;
use yii\base\UnknownPropertyException;

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
	public function run(string $alias, ?string $term, ?string $componentName = null):array {
		if (null !== $ARClass = SearchWidget::getConfigParam("models.{$alias}.class", null, $componentName)) {
			return SearchWidget::Search(
				$ARClass,
				$term,
				SearchWidget::getConfigParam("models.{$alias}.limit", SearchWidget::DEFAULT_LIMIT, $componentName),
				SearchWidget::getConfigParam("models.{$alias}.attributes", null, $componentName),
				SearchWidget::getConfigParam("models.{$alias}.method", SearchWidget::DEFAULT_METHOD, $componentName)
			);
		}
		return [];
	}
}
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
	public function run(string $alias, ?string $term):array {
		if (null !== $ARClass = SearchWidget::getConfigParam("models.{$alias}.class")) {
			return SearchHelper::Search(
				$ARClass,
				$term,
				SearchWidget::getConfigParam("models.{$alias}.limit", SearchWidget::DEFAULT_LIMIT),
				SearchWidget::getConfigParam("models.{$alias}.attributes"),
				SearchWidget::getConfigParam("models.{$alias}.method", SearchWidget::DEFAULT_METHOD)
			);
		}
		return [];
	}
}
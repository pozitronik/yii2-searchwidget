<?php
declare(strict_types = 1);

namespace pozitronik\widgets;

use Exception;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * Class SearchWidget
 * @package app\widgets\search
 */
class SearchWidget extends Widget {
	public const DEFAULT_LIMIT = 5;
	public const DEFAULT_METHOD = 'search';
	public const MAX_PENDING_REQUESTS = 25;//количество параллельных фоновых запросов поиска
	public const DEFAULT_TEMPLATE_VIEW = 'template';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init():void {
		parent::init();
		SearchWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Exception
	 */
	public function run():string {
		if ([] === $dataset = $this->prepareDataset()) {
			return '';
		}
		return $this->render('search', [
			'dataset' => $dataset
		]);
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	private function prepareDataset():array {
		$dataset = [];
		$searchConfig = ArrayHelper::getValue(Yii::$app, 'params.searchConfig', []);
		foreach ($searchConfig as $alias => $config) {
			if (null === $templateString = ArrayHelper::getValue($config, 'template')) {
				$templateString = $this->render(ArrayHelper::getValue($config, 'templateView', self::DEFAULT_TEMPLATE_VIEW));
			}

			$templateString = str_replace(["\r", "\n", "\t"], '', $templateString);

			$dataset[] = [
				'limit' => ArrayHelper::getValue($config, 'limit', self::DEFAULT_LIMIT),
				'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('html')",
				'display' => 'name',
				'templates' => [
					'suggestion' => new JsExpression("Handlebars.compile('{$templateString}')"),
					'header' => Html::tag('h3', ArrayHelper::getValue($config, 'header', $alias), ['class' => 'suggestion-header'])
				],
				'remote' => [
					'url' => AjaxController::to('search')."?term=QUERY&alias={$alias}",
					'wildcard' => 'QUERY',
					'maxPendingRequests' => self::MAX_PENDING_REQUESTS
				]
			];
		}
		return $dataset;
	}
}

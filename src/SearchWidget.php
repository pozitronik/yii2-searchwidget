<?php
declare(strict_types = 1);

namespace pozitronik\widgets;

use Exception;
use Throwable;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Class SearchWidget
 * @property string $ajaxEndpoint url for all ajax requests
 * @property array $models List of models searches configurations
 */
class SearchWidget extends Widget {
	public const COMPONENT_NAME = 'searchWidget';

	public const DEFAULT_LIMIT = 5;
	public const DEFAULT_METHOD = 'search';
	public const MAX_PENDING_REQUESTS = 25;//количество параллельных фоновых запросов поиска
	public const DEFAULT_TEMPLATE_VIEW = 'template';

	public string $ajaxEndpoint = '/site/search';
	public array $models = [];

	/**
	 * Allow to use widget as component: if no parameters passed, configured values will be loaded
	 * @inheritDoc
	 */
	public static function widget($config = []):string {
		if ([] === $config) $config = ArrayHelper::getValue(Yii::$app->components, static::COMPONENT_NAME);
		return parent::widget($config);
	}

	/**
	 * @inheritDoc
	 */
	public function init():void {
		parent::init();
		SearchWidgetAssets::register($this->getView());
	}

	/**
	 * @inheritDoc
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
		foreach ($this->models as $alias => $config) {
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
					'url' => Url::toRoute([$this->ajaxEndpoint, 'term' => 'QUERY', 'alias' => $alias]),
					'wildcard' => 'QUERY',
					'maxPendingRequests' => self::MAX_PENDING_REQUESTS
				]
			];
		}
		return $dataset;
	}
}

<?php
declare(strict_types = 1);

namespace pozitronik\widgets;

use Exception;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Class SearchWidget
 * @property string $componentName The component name with widget configuration ('searchWidget' is used by default)
 * @property string $ajaxEndpoint The url for all ajax requests
 * @property array $models List of models searches configurations
 */
class SearchWidget extends Widget {
	public const COMPONENT_NAME = 'searchWidget';

	public const DEFAULT_LIMIT = 5;
	public const DEFAULT_METHOD = 'search';
	public const MAX_PENDING_REQUESTS = 25;//количество параллельных фоновых запросов поиска
	public const DEFAULT_TEMPLATE_VIEW = 'template';

	public string $componentName = self::COMPONENT_NAME;
	public string $ajaxEndpoint = '/site/search';
	public array $models = [];

	/**
	 * Allow to use widget as component: if no parameters passed, configured values will be loaded
	 * @inheritDoc
	 */
	public static function widget($config = []):string {
		if ([] === $config) {
			$config = ArrayHelper::getValue(Yii::$app->components, static::COMPONENT_NAME);
		}
		if (null !== $componentName = ArrayHelper::getValue($config, 'componentName')) {
			$config = ArrayHelper::getValue(Yii::$app->components, $componentName);
		}
		return parent::widget($config);
	}

	/**
	 * Retrieve component configuration parameter
	 * @param string $name
	 * @param mixed $default
	 * @param string|null $componentName
	 * @return mixed
	 * @throws Exception
	 */
	public static function getConfigParam(string $name, mixed $default = null, ?string $componentName = null):mixed {
		return ArrayHelper::getValue(Yii::$app->components, sprintf("%s.%s", $componentName?:static::COMPONENT_NAME, $name), $default);
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
				$templateString = $this->render(ArrayHelper::getValue($config, 'templateView', static::DEFAULT_TEMPLATE_VIEW));
			}

			$templateString = str_replace(["\r", "\n", "\t"], '', $templateString);

			$dataset[] = [
				'limit' => ArrayHelper::getValue($config, 'limit', static::DEFAULT_LIMIT),
				'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('html')",
				'display' => 'name',
				'templates' => [
					'suggestion' => new JsExpression("Handlebars.compile('{$templateString}')"),
					'header' => Html::tag('h3', ArrayHelper::getValue($config, 'header', $alias), ['class' => 'suggestion-header'])
				],
				'remote' => [
					'url' => Url::toRoute([
						$this->ajaxEndpoint,
						'term' => 'QUERY',
						'alias' => $alias,
						'componentName' => static::COMPONENT_NAME === $this->componentName?null:$this->componentName
					]),
					'wildcard' => 'QUERY',
					'maxPendingRequests' => static::MAX_PENDING_REQUESTS
				]
			];
		}
		return $dataset;
	}
}

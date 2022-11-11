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
 */
class SearchWidget extends Widget {
	public const COMPONENT_NAME = 'searchWidget';

	public const DEFAULT_LIMIT = 5;
	public const DEFAULT_METHOD = 'search';
	public const MAX_PENDING_REQUESTS = 25;//количество параллельных фоновых запросов поиска
	public const DEFAULT_TEMPLATE_VIEW = 'template';

	public ?string $ajaxEndpoint = '/site/search';

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 * @throws Exception
	 */
	protected static function getParam(string $name, mixed $default = null):mixed {
		return ArrayHelper::getValue(Yii::$app->components, sprintf("%s.params.%s", static::COMPONENT_NAME, $name), $default);
	}

	/**
	 * @inheritDoc
	 */
	public function init():void {
		parent::init();
		SearchWidgetAssets::register($this->getView());
		$this->ajaxEndpoint = static::getParam('ajaxEndpoint', $this->ajaxEndpoint);
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
		$modelsConfigs = static::getParam('models', []);
		foreach ($modelsConfigs as $alias => $config) {
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

<?php
declare(strict_types = 1);

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module;
use pozitronik\widgets\SearchWidget;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class Unit
 * @package Helper
 */
class Unit extends Module {

	/**
	 * Allows to init component with different configurations
	 * @param array $config
	 * @return void
	 * @throws InvalidConfigException
	 */
	public static function InitComponent(array $config):void {
		Yii::$app->set(SearchWidget::COMPONENT_NAME, $config);
	}
}

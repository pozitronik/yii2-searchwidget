<?php
declare(strict_types = 1);

namespace pozitronik\widgets;

use yii\web\AssetBundle;

/**
 * Class SearchWidgetAssets
 */
class SearchWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init():void {
		$this->sourcePath = __DIR__ . '/assets';
		$this->css = ['css/search.css'];
		parent::init();
	}
}
<?php
declare(strict_types = 1);

use app\controllers\SiteController;
use app\models\Users;
use Codeception\Test\Unit;
use Helper\Unit as UnitHelper;
use pozitronik\widgets\SearchAction;
use pozitronik\widgets\SearchWidget;

/**
 * @covers \pozitronik\widgets\SearchAction
 */
class SearchActionTest extends Unit {

	/**
	 * @covers \pozitronik\widgets\SearchAction::run
	 * @return void
	 */
	public function testRun():void {
		UnitHelper::InitComponent([
			'class' => SearchWidget::class,
			'models' => [
				'Users' => [
					'class' => Users::class,
					'header' => 'Пользователи',
					'attributes' => [
						'username',
						'email'
					]
				]
			]
		]);
		$controller = new SiteController('site', Yii::$app);
		$action = new SearchAction('search', $controller);
		$result = $action->run('Users', 'иван');
		static::assertIsArray($result);
	}
}

<?php
declare(strict_types = 1);

namespace app\tests\unit;

use app\controllers\SiteController;
use app\fixtures\UsersFixture;
use app\models\Users;
use Codeception\Test\Unit;
use Helper\Unit as UnitHelper;
use pozitronik\widgets\SearchAction;
use pozitronik\widgets\SearchWidget;
use Yii;

/**
 * @covers \pozitronik\widgets\SearchAction
 */
class SearchActionTest extends Unit {

	/**
	 * @return string[][]
	 */
	public function _fixtures():array {
		return [
			'users' => [
				'class' => UsersFixture::class,
			],
		];
	}

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
		$result = $action->run('Users', 'gmail');
		static::assertIsArray($result);
		static::assertCount(5, $result);
	}
}

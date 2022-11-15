<?php
declare(strict_types = 1);
use app\models\Users;
use Codeception\Test\Unit;
use pozitronik\widgets\SearchWidget;

/**
 * @covers SearchWidget
 */
class SearchWidgetTest extends Unit {
	/**
	 * @covers SearchWidget::widget
	 * @return void
	 * @throws Throwable
	 */
	public function testSearchWidget():void {
		static::assertStringStartsWith('<div class="pull-left search-box"><input type="text" id="w0" class="form-control" name="search" placeholder="Поиск" autocomplete="off"', SearchWidget::widget());
	}

	/**
	 * Tests that widget can be configured in runtime
	 * @return void
	 * @throws Throwable
	 */
	public function testConfigureWidgetRuntime():void {
		SearchWidget::widget([
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
	}

	/**
	 * Test widget with different configurations
	 * @return void
	 */
	public function testDifferentConfigurations() {
		SearchWidget::widget([
			'componentName' => 'otherSearch'
		]);
	}
}
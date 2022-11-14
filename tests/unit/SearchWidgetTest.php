<?php
declare(strict_types = 1);
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
		static::assertStringStartsWith('<div class="pull-left search-box"><input type="text" id="w0" class="form-control" name="search" placeholder="Поиск" autocomplete="off"',SearchWidget::widget());
	}
}
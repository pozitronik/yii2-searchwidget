<?php
declare(strict_types = 1);

use Codeception\Test\Unit;
use pozitronik\widgets\SearchHelper;

/**
 * @covers \pozitronik\widgets\SearchHelper
 */
class SearchHelperTest extends Unit {

	/**
	 * @covers \pozitronik\widgets\SearchHelper::SwitchKeyboard
	 * @return void
	 */
	public function testSwitchKeyboard():void {
		static::assertEquals("qwertyuiop[]asdfghjkl;'zxcvbnm,./", SearchHelper::SwitchKeyboard("йцукенгшщзхъфывапролджэячсмитьбю."));
		static::assertEquals("QWERTYUIOP{}ASDFGHJKL:\"ZXCVBNM<>?", SearchHelper::SwitchKeyboard("ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ,"));
		static::assertEquals("йцукенгшщзхъфывапролджэячсмитьбю.", SearchHelper::SwitchKeyboard("qwertyuiop[]asdfghjkl;'zxcvbnm,./", true));
		static::assertEquals("ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ,", SearchHelper::SwitchKeyboard("QWERTYUIOP{}ASDFGHJKL:\"ZXCVBNM<>?", true));
	}
}
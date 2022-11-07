<?php
declare(strict_types = 1);

namespace Tests\Unit;

use Codeception\Test\Unit;
use pozitronik\widgets\SearchHelper;
use Tests\Support\UnitTester;

/**
 * @covers \pozitronik\widgets\SearchHelper
 */
class SearchHelperTest extends Unit {

	protected UnitTester $tester;

	/**
	 * @inheritDoc
	 */
	protected function _before() {
	}

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

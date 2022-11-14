<?php
declare(strict_types = 1);

use app\fixtures\UsersFixture;
use app\models\Users;
use Codeception\Test\Unit;
use pozitronik\widgets\SearchHelper;

/**
 * @covers \pozitronik\widgets\SearchHelper
 */
class SearchHelperTest extends Unit {

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
	 * @covers SearchHelper::SwitchKeyboard
	 * @return void
	 */
	public function testSwitchKeyboard():void {
		static::assertEquals("qwertyuiop[]asdfghjkl;'zxcvbnm,./", SearchHelper::SwitchKeyboard("йцукенгшщзхъфывапролджэячсмитьбю."));
		static::assertEquals("QWERTYUIOP{}ASDFGHJKL:\"ZXCVBNM<>?", SearchHelper::SwitchKeyboard("ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ,"));
		static::assertEquals("йцукенгшщзхъфывапролджэячсмитьбю.", SearchHelper::SwitchKeyboard("qwertyuiop[]asdfghjkl;'zxcvbnm,./", true));
		static::assertEquals("ЙЦУКЕНГШЩЗХЪФЫВАПРОЛДЖЭЯЧСМИТЬБЮ,", SearchHelper::SwitchKeyboard("QWERTYUIOP{}ASDFGHJKL:\"ZXCVBNM<>?", true));
	}

	/**
	 * @return void
	 * @covers SearchHelper::createObject
	 */
	public function testCreateObject():void {
		static::assertIsObject(SearchHelper::createObject(Users::class));
		static::assertIsObject(SearchHelper::createObject(new Users()));
	}

	/**
	 * @return void
	 * @covers SearchHelper::AssumeSearchAttributes
	 */
	public function testAssumeSearchAttributes():void {
		static::assertEquals(['username', 'login', 'password', 'comment', 'email'], SearchHelper::AssumeSearchAttributes(Users::class));
	}
}
<?php
declare(strict_types = 1);
use app\fixtures\UsersFixture;
use app\models\Users;
use Codeception\Test\Unit;
use pozitronik\widgets\SearchWidget;

/**
 * Tests searches with different configurations
 */
class SearchTest extends Unit {
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
	 * @covers SearchWidget::Search
	 * @return void
	 */
	public function testSearch():void {
		$result = SearchWidget::Search(Users::class, 'gmail', 3, ['email']);
		static::assertIsArray($result);
		static::assertCount(3, $result);

		$result = SearchWidget::Search(Users::class, 'gmail', 3, ['username']);
		static::assertIsArray($result);
		static::assertCount(0, $result);

		$result = SearchWidget::Search(Users::class, 'gmail', 3, ['username', 'email']);
		static::assertIsArray($result);
		static::assertCount(3, $result);

		$result = SearchWidget::Search(Users::class, 'gmail', null, ['email']);
		static::assertIsArray($result);
		static::assertCount(16, $result);

		$result = SearchWidget::Search(Users::class, 'пьфшд', null, ['email']);
		static::assertIsArray($result);
		static::assertCount(16, $result);

		$result = SearchWidget::Search(Users::class, 'gmail', null);
		static::assertIsArray($result);
		static::assertCount(16, $result);
	}
}
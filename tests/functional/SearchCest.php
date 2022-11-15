<?php
declare(strict_types = 1);
use app\fixtures\UsersFixture;
use app\models\Users;
use Helper\Unit as UnitHelper;
use pozitronik\widgets\SearchWidget;

/**
 * Functional tests for SearchWidget endpoint
 */
class SearchCest {
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
	 * Tests the default search endpoint
	 * @param FunctionalTester $I
	 * @return void
	 */
	public function searchTest(FunctionalTester $I):void {
		$I->sendAjaxGetRequest('/site/search', ['alias' => 'Users', 'term' => 'gmail']);
		$I->seeResponseCodeIs(200);
		$response = $I->grabResponse();
		$I->assertJson($response);
		$responseArray = json_decode($response, true);
		$I->assertCount(5, $responseArray);
	}

	/**
	 * Tests the alternative search endpoint for the same class
	 * @param FunctionalTester $I
	 * @return void
	 */
	public function searchEndpointsTest(FunctionalTester $I):void {
		UnitHelper::InitComponent([
			'class' => SearchWidget::class,
			'ajaxEndpoint' => '/site/search',
			'models' => [
				'Users' => [
					'class' => Users::class,
					'limit' => null,
					'attributes' => [
						'email'
					]
				],
				'UserNames' => [
					'class' => Users::class,
					'limit' => null,
					'ajaxEndpoint' => '/site/username-search',
					'attributes' => [
						'username'
					]
				]
			]
		]);
		$I->sendAjaxGetRequest('/site/search', ['alias' => 'Users', 'term' => 'ann']);
		$I->seeResponseCodeIs(200);
		$response = $I->grabResponse();
		$I->assertJson($response);
		$responseArray = json_decode($response, true);
		$I->assertCount(2, $responseArray);

		$I->sendAjaxGetRequest('/site/username-search', ['alias' => 'UserNames', 'term' => 'ann']);
		$I->seeResponseCodeIs(200);
		$response = $I->grabResponse();
		$I->assertJson($response);
		$responseArray = json_decode($response, true);
		$I->assertCount(5, $responseArray);
	}
}

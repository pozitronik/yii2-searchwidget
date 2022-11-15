<?php
declare(strict_types = 1);

namespace app\controllers;

use pozitronik\widgets\SearchAction;
use Yii;
use yii\filters\ContentNegotiator;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;

/**
 * class SiteController
 */
class SiteController extends Controller {

	public $enableCsrfValidation = false;

	/**
	 * @inheritDoc
	 */
	public function behaviors():array {
		return [
			[
				'class' => ContentNegotiator::class,
				'formats' => [
					'text/plain' => Response::FORMAT_JSON
				],
				'only' => [
					'search', 'username-search'
				]
			],
		];
	}

	/**
	 * @inheritDoc
	 */
	public function actions():array {
		return [
			'search' => SearchAction::class,
			'username-search' => SearchAction::class
		];
	}

	/**
	 * @return string
	 */
	public function actionError():string {
		$exception = Yii::$app->errorHandler->exception;

		if (null !== $exception) {
			return Html::encode($exception->getMessage());
		}
		return "Status: {$exception->statusCode}";
	}
}


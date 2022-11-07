<?php /** @noinspection UsingInclusionReturnValueInspection */
declare(strict_types = 1);

use yii\caching\DummyCache;
use yii\web\AssetManager;
use yii\web\ErrorHandler;


$config = [
	'id' => 'basic',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'aliases' => [
		'@vendor' => './vendor',
		'@bower' => '@vendor/bower-asset',
		'@npm' => '@vendor/npm-asset',
	],
	'modules' => [
	],
	'components' => [
		'request' => [
			'cookieValidationKey' => 'sosijopu',
		],
		'cache' => [
			'class' => DummyCache::class,
		],
		'errorHandler' => [
			'class' => ErrorHandler::class,
			'errorAction' => 'site/error',
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
			],
		],
		'assetManager' => [
			'class' => AssetManager::class,
			'basePath' => '@app/assets'
		],
	],
	'params' => [
		'bsVersion' => '4',
		'searchConfig' => require __DIR__ . '/search.php',
	],
];

return $config;
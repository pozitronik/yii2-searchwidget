<?php /** @noinspection UsingInclusionReturnValueInspection */
declare(strict_types = 1);

use app\models\Users;
use pozitronik\widgets\SearchWidget;
use yii\caching\DummyCache;
use yii\db\Connection;
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
		'searchWidget' => [
			'class' => SearchWidget::class,
			'ajaxEndpoint' => '/site/search',//todo:: посмотреть, как в почтовом компоненте задаются атрибуты, чтобы не пихать их в params
			'models' => [
				'Users' => [//<== алиас модели
					'class' => Users::class,//<== FQN-название ActiveRecord-класса
//					'method' => 'search', //<== название метода класса, используемого для выполнения поиска
//					'templateView' => '@app/views/users/search-template', //<== путь до шаблона отображения результата поиска, см SearchHelper::Search $method
					/*
					 * можно вписать строку шаблона напрямую, этот параметр приоритетнее
					 * 'template' => '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="clearfix"></div><div class="suggestion-secondary">{{controller}}</div><div class="suggestion-links"><a href="'.PermissionsController::to('edit').'?id={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Редактировать<a/></div><div class="clearfix"></div></div>',
					 */
					'header' => 'Пользователи', //<== заголовок в поисковом выводе
					//'limit' => 5,// <== лимит поиска,
					//'url' => AjaxController::to('search') // <== Url входящего поискового экшена
					'attributes' => [// <== поисковые атрибуты, см. SearchHelper::Search $searchAttributes
						'username',
						'email'
					]
				]
			]
		],
		'db' => [
			'class' => Connection::class,
			'dsn' => ConfigHelper::getenv('DB_DSN'),
			'username' => ConfigHelper::getenv('DB_USER'),
			'password' => ConfigHelper::getenv('DB_PASSWORD'),
			'charset' => 'utf8',
			'enableSchemaCache' => false,
		],
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
	],
];

return $config;
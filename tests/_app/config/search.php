<?php
declare(strict_types = 1);

use app\models\Users;

return [
	'Users' => [//<== алиас модели
		'class' => Users::class,//<== FQN-название ActiveRecord-класса
//		'method' => 'search', //<== название метода класса, используемого для выполнения поиска
		'templateView' => '@app/views/users/search-template', //<== путь до шаблона отображения результата поиска, см SearchHelper::Search $method
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
	],
];
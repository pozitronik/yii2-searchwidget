# yii2-searchwidget

Quicksearch widget implementation

# Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Run

```
php composer.phar require pozitronik/yii2-searchwidget "dev-master"
```

or add

```
"pozitronik/yii2-searchwidget": "dev-master"
```

to the require section of your `composer.json` file.

# Requirements

Yii2,
PHP >= 8.0

# Configuration

There are two ways for widget configuration. Preferred way is to configure it as a component, like:

```php
...
'components' => [
    'searchWidget' => [
        'class' => SearchWidget::class,
        'models' => [
            'Users' => [
                'class' => Users::class
            ],
            ...
        ],
        ...
    ],
    ...
```

Alternatively, you can pass configuration to widget itself:

```php
    SearchWidget::widget([
        'models' => [
            'Users' => [
                'class' => Users::class
            ],
            ...
        ]
]);
```

## Configuration parameters

- **class**: the component class, it always must be `SearchWidget::class`.
- **ajaxEndpoint**: the endpoint URL for all searches requests. It must be handled by `SearchAction::class`. Value by default
  is `'/site/search'`.
- **models**: the list of ActiveRecord models, which to search in. Every item key must contain arbitrary but unique model alias, like:

```php
'components' => [
    'searchWidget' => [
        'class' => SearchWidget::class,
        'models' => [
            'Model1Alias' => [
                'class' => Model1::class
            ],
            'Model2Alias' => [
                'class' => Model2::class
            ],
            ...
        ]
    ]
    ...
]
```

Searches for each model can be configured in there:

- **class**: (string) the model class name.
- **ajaxEndpoint**: the endpoint URL for current model searches requests. It overrides similar global option, but not set by default.
- **method**: (string) if the model implements it own search method, its name can be specified here. See [Search method](#Search method)
  section for
  details. By default,
  model will be threatened as ActiveRecord and standard SQL-searches will be used.
- **template**: (string) the search result raw template code. Ignored by default. If set, it has higher priority to **templateView**
  parameter. See also [Search result templates](#Search result templates) section.
- **templateView** (string) the path to search result template view. If not set, then default template view will be used.
- **header**: (string) the header for model search results list.
- **limit** (null|int) the limit for search results, set null to disable limitations. Default value is 5.
- **attributes**: (string[]) the list of model attributes names to search in them. Each attribute can be specified in two methods: just by
  attribute string name, or as array, where the first item is the attribute name, and the second item is attribute search type.
Supported search types are:
* `SearchWidget::SEARCH_TYPE_EQUAL`: attribute value must be equal to search term.
* `SearchWidget::SEARCH_TYPE_LIKE`: attribute value must contain the search term.
* `SearchWidget::SEARCH_TYPE_LIKE_BEGINNING`: attribute value must begins with the search term.
* `SearchWidget::SEARCH_TYPE_LIKE_ENDING`: attribute value must ends to search term.

All search types are case-insensitive.

Next, some configuration example is given:

```php
...
'components' => [
    'searchWidget' => [
        'class' => SearchWidget::class,
        'ajaxEndpoint' => '/site/search',
        'models' => [
            'Users' => [
                'class' => Users::class,
                'template' => '<div class="suggestion-item"><div class="suggestion-name">{{name}}</div><div class="clearfix"></div><div class="suggestion-secondary">{{controller}}</div><div class="suggestion-links"><a href="'.Url::to('users/edit').'?id={{id}}" class="dashboard-button btn btn-xs btn-info pull-left">Edit<a/></div><div class="clearfix"></div></div>',
                'header' => 'Users',
                'limit' => 3,
                'attributes' => [
                    'username',
                    ['email', SearchWidget::SEARCH_TYPE_LIKE_BEGINNING]
                ]
            ],
            'Products' => [
                'class' => Products::class,
                'ajaxEndpoint' => '/products/search',
                'templateView' => '@app/views/products/search-template'
                'header' => 'Products',
                'limit' => null,
                'attributes' => [
                    'name',
                     ['code', SearchWidget::SEARCH_TYPE_EQUAL]
                ]
            ]
        ],
        ...
    ],
    ...
```

## Adding alternative configuration

If you want to create widgets, which implementing different searches, you can configure them as separate components:

```php
...
'components' => [
    'usersSearchWidget' => [
        'class' => SearchWidget::class,
        'models' => [
            'Users' => [
                'class' => Users::class,
            ]
        ],
    ],
    'productsSearchWidget' => [
        'class' => SearchWidget::class,
        'models' => [
            'Products' => [
                'class' => Products::class,
            ]
        ],
    ],
```

Then you need to pass component name to each widget:

```php
    SearchWidget::widget(['componentName' => 'usersSearchWidget']);//search only in users
    SearchWidget::widget(['componentName' => 'productsSearchWidget']);//search only in products
```

Alternatively, you can pass configuration to widget itself, as described in [Configuration] section.

# Search method

todo

# Search result templates

todo

# License

GNU GPL v3.
<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $dataset
 */

use kartik\typeahead\Typeahead;
use yii\web\View;

?>
<?= Typeahead::widget([
	'container' => [
		'class' => 'pull-left search-box'
	],
	'name' => 'search',
	'readonly' => false,
	'options' => [
		'placeholder' => 'Поиск',
		'autocomplete' => 'off'
	],
	'pluginOptions' => [
		'highlight' => true,
		'minLength' => 3
	],
	'dataset' => $dataset
]) ?>

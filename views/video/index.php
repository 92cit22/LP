<?php

use app\models\Video;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Видео';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Добавить видео', ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			[
				'attribute' => 'Title',
				'format' => 'html',
				'value' => function (Video $data) {
					return Html::a($data->Title, Url::toRoute(["view", 'Id' => $data->Id]));
				},
			],
			'Description:ntext',
			'Likes',
			'Dislikes',
			[
				'attribute' => 'CategoryId',
				'value' => function (Video $data) {
					return $data->category->Title;
				},
			],
			[
				'label' => 'Автор',
				'attribute' => 'UserId',
				'value' => function (Video $data) {
					return $data->user->Username;
				},
			],
			'CreatedAt:datetime',

			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>


</div>
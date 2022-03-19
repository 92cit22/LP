<?php

use app\models\Video;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
					return Html::a($data->Title, Url::toRoute(["view", 'id' => $data->Id]));
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
			'CreatedAt:datetime',
			[
				'attribute' => 'Status',
				'format' => 'raw',
				'value' => function (Video $data) {
					if (Yii::$app->user->identity->isAdmin) {
						return Html::beginForm('', 'post', ['class' => 'form-inline'])
							. Html::activeDropDownList($data, 'Status', Video::StatusesDict(), ['selected' => $data->Status])
							. Html::activeHiddenInput($data, 'Id', ['value' => $data->Id])
							. Html::submitButton('Сменить статус', ['class' => 'btn btn-primary'])
							. Html::endForm();
					} else {
						return $data->Status;
					}
				}
			],

			['class' => (Yii::$app->user->identity->isAdmin) ? 'yii\grid\Column' : 'yii\grid\ActionColumn'],
		],
	]); ?>

</div>
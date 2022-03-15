<?php

/* @var $this yii\web\View */

use app\models\Video;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Видеохостинг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'Title',
                'format' => 'html',
                'value' => function (Video $data) {
                    return Html::a($data->Title, Url::toRoute(["/video/view", 'Id' => $data->Id]));
                },
            ],
            [
                'attribute' => 'Url',
                'format' => 'raw',
                'value' => function (Video $data) {
                    return "<video width=250 controls src=/"  . $data->Url . "></video>";
                },
            ],
            'CreatedAt:datetime',
        ],
    ]); ?>

</div>
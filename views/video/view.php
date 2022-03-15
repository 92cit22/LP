<?php

use app\models\Video;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Video */

if (!$model instanceof Video) {
    return;
}
$this->title = $model->Title;
$this->params['breadcrumbs'][] = ['label' => 'Videos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="video-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'Id' => $model->Id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'Id' => $model->Id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Title:ntext',
            'Description:ntext',
            'Likes',
            'Dislikes',
            [
                'attribute' => 'CreatedAt',
                'format' => ['datetime', DATE_FORMAT]
            ],
        ],
    ]) ?>

    <div class="commentaries">
        <? foreach ($model->getCommentaries()->orderBy(['CreatedAt' => SORT_DESC])->all() as $comment) : ?>
            <div class="card mt-3">
                <div class="card-header">
                    <span class="author">
                        <?= $comment->user->Username ?>
                    </span>
                    <span class="time badge">
                        <?= (is_string($comment->CreatedAt))
                            ? (new DateTime($comment->CreatedAt))->format(DATE_FORMAT)
                            : $comment->CreatedAt->format(DATE_FORMAT) ?>
                    </span>
                </div>
                <div class="card-body">
                    <?= $comment->Text ?>
                </div>
            </div>
        <? endforeach; ?>
    </div>
    <? if (!Yii::$app->user->isGuest) : ?>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($commentaries, 'Text')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton('Комментировать', ['class' => 'btn btn-success offset-10']) ?>
        </div>

        <?= $form->field($commentaries, 'VideoId')->hiddenInput(['value' => $model->Id])->label('') ?>

        <?= $form->field($commentaries, 'UserId')->hiddenInput(['value' => Yii::$app->user->id])->label('') ?>

        <? ActiveForm::end(); ?>
    <? endif; ?>
</div>
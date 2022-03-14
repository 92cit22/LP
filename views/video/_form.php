<?php

use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Video */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="video-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Title')->textInput() ?>

    <?= $form->field($model, 'Description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'CategoryId')->dropDownList(ArrayHelper::map(Categories::find()->all(), 'Id', 'Title')) ?>

    <?= $form->field($model, 'video')->fileInput()->label('') ?>

    <?= $form->field($model, 'UserId')->hiddenInput(['value' => Yii::$app->user->id])->label('') ?>

    <!-- <= $form->field($model, 'CreatedAt')->hiddenInput()->label('') ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
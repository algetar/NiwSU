<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Speller */
/* @var $form yii\widgets\ActiveForm */
//debug();
?>

<div class="speller-form">

    <?php 
    $form = ActiveForm::begin(['layout' => 'horizontal', 'options' => ['data' => ['pjax' => true]],]); 
    ?>

    <?= $form->field($model, 'Number')->textInput() ?>

    <?= $form->field($model, 'Item')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Format')->textInput(['maxlength' => true]) ?>
    
    <?php Pjax::begin(['id' => 'spell-block']); ?>
    
    <?= $form->field($model, 'Spelt')->textarea(['readonly' => true]) ?>
    
    <div class="form-group btn-group" style="text-align: center">
        <?= Html::a('Cancel', 'index', ['class' => 'btn btn-warning', 'data-pjax' => 0]) ?>
        <?= Html::submitButton('Spell number', ['class' => 'btn btn-success', 'data' => ['pjax' => true]]) ?>
    </div>
    
    <?php Pjax::end(); ?>

    <?php ActiveForm::end(); ?>

</div>

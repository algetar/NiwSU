<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Speller */

$this->title = 'Update Speller: ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Spellers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID.':'.$model->Number.'('.$model->Item.')', 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="speller-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

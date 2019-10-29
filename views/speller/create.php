<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Speller */

$this->title = 'Create Speller';
$this->params['breadcrumbs'][] = ['label' => 'Spellers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="speller-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

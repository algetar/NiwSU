<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Spellers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="speller-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Speller', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'Number',
            'Item',
            'Format',
            'Spelt',

            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 90px; text-align: center;'],],
        ],
    ]); ?>


</div>

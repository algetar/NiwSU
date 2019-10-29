<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Speller".
 *
 * @property int $ID ид записи
 * @property string $Number
 * @property string $Item Исчисляемое
 * @property string $Format Формат представления данных
 * @property string $Spelt Число прописью
 */
class tblSpeller extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Speller';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Number'], 'required'],
            [['Item'], 'string', 'max' => 7],
            [['Number', 'Format', 'Spelt'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ид записи',
            'Number' => 'Число',
            'Item' => 'Исчисляемое',
            'Format' => 'Формат представления данных',
            'Spelt' => 'Число прописью',
        ];
    }
}

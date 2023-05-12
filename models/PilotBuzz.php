<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pilot_buzz".
 *
 * @property int $ID
 * @property int $pilot_id
 * @property int $merchant_id
 * @property string $reg_date
 */
class PilotBuzz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pilot_buzz';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pilot_id', 'merchant_id'], 'required'],
            [['pilot_id', 'merchant_id'], 'integer'],
            [['reg_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'pilot_id' => 'Pilot ID',
            'merchant_id' => 'Merchant ID',
            'reg_date' => 'Reg Date',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_paytype_services".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $paytype_id
 * @property int $service_type_id
 * @property string $reg_date
 */
class MerchantPaytypeServices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_paytype_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'paytype_id', 'service_type_id'], 'required'],
            [['merchant_id', 'paytype_id', 'service_type_id'], 'integer'],
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
            'merchant_id' => 'Merchant ID',
            'paytype_id' => 'Paytype ID',
            'service_type_id' => 'Service Type ID',
            'reg_date' => 'Reg Date',
        ];
    }
}

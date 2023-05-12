<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property float $subscription_amount
 * @property string $subscription_start_date
 * @property string|null $executive_details
 * @property int $payment_status
 * @property int|null $created_by
 * @property string $reg_date
 * @property int|null $updated_by
 * @property string $updated_on
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'subscription_amount', 'subscription_start_date', 'payment_status'], 'required'],
            [['merchant_id', 'payment_status', 'created_by', 'updated_by'], 'integer'],
            [['subscription_amount'], 'number'],
            [['subscription_start_date', 'reg_date', 'updated_on'], 'safe'],
            [['executive_details'], 'string', 'max' => 255],
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
            'subscription_amount' => 'Subscription Amount',
            'subscription_start_date' => 'Subscription Start Date',
            'executive_details' => 'Executive Details',
            'payment_status' => 'Payment Status',
            'created_by' => 'Created By',
            'reg_date' => 'Reg Date',
            'updated_by' => 'Updated By',
            'updated_on' => 'Updated On',
        ];
    }
}

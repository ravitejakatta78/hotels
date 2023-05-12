<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "allocate_parking_token".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $valet_id
 * @property int $token_number
 * @property int $ticket_id
 * @property string $customer_name
 * @property string $customer_mobile
 * @property string $vehicle_number
 * @property string $parking_pic
 * @property string $recorded_date
 * @property int $from_time
 * @property int $to_time
 * @property int $ticket_status
 */
class AllocateParkingToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'allocate_parking_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'valet_id', 'token_number', 'ticket_id', 'ticket_status', 'recorded_date'], 'required'],
            [['ID','merchant_id', 'valet_id', 'token_number', 'ticket_id', 'ticket_status'], 'integer'],
            [['recorded_date'], 'safe'],
			['token_number','unique', 'on'=>'inserttoken', 'message' => 'This token has already been taken.'],
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
            'valet_id' => 'Valet ID',
            'token_number' => 'Token Number',
            'ticket_id' => 'Ticket ID',
            'customer_name' => 'Customer Name',
            'customer_mobile' => 'Customer Mobile',
            'vehicle_number' => 'Vehicle Number',
            'parking_pic' => 'Parking Pic',
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'ticket_status' => 'Ticket Status',
            'recorded_date' => 'Recorded Date',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parking_token".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $token_number
 * @property string $download_qr
 * @property string $multi_qr
 * @property int $status
 * @property string $created_date
 */
class ParkingToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parking_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'token_number', 'status', 'created_date'], 'required'],
            [['ID','merchant_id', 'token_number', 'download_qr','multi_qr','status'], 'integer'],
            [['created_date'], 'safe'],
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
            'token_number' => 'Token Number',
            'download_qr' => 'Download QR',
            'multi_qr' => 'Multi QR',
            'status' => 'Status',
            'created_date' => 'Created Date',
        ];
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "partner_with_us".
 *
 * @property int $id
 * @property int $business_type
 * @property string $business_name
 * @property string|null $contact_person
 * @property string|null $mobile_number
 * @property string|null $email_id
 * @property string|null $location_city
 * @property string $reg_date
 */
class PartnerWithUs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partner_with_us';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_type','contact_person', 'mobile_number', 'location_city'], 'required'],
            [['business_type'], 'integer'],
            [['reg_date'], 'safe'],
            [['business_name'], 'string', 'max' => 255],
            [['contact_person', 'email_id', 'location_city'], 'string', 'max' => 100],
            [['mobile_number'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_type' => 'Business Type',
            'business_name' => 'Business Name',
            'contact_person' => 'Contact Person',
            'mobile_number' => 'Mobile Number',
            'email_id' => 'Email ID',
            'location_city' => 'Location City',
            'reg_date' => 'Reg Date',
        ];
    }
}

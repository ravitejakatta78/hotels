<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "merchant_printers".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property string $printer_real_name
 * @property string $printer_alias_name
 * @property string $paper_size
 * @property int|null $default_printer 1.Yes , 2. No
 * @property int $created_by
 * @property string $reg_date
 * @property int|null $updated_by
 * @property string $updated_on
 */
class MerchantPrinters extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merchant_printers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'printer_real_name', 'printer_alias_name', 'paper_size', 'created_by'], 'required'],
            [['merchant_id', 'default_printer', 'created_by', 'updated_by'], 'integer'],
            [['reg_date', 'updated_on'], 'safe'],
            [['printer_real_name', 'printer_alias_name'], 'string', 'max' => 255],
            [['paper_size'], 'string', 'max' => 100],
     //       ['default_printer','checkdefaultprinterbyinsert', 'on'=>'insertprinter'],
     //       ['default_printer','checkdefaultprinterbyupdate', 'on'=>'updateprinter'],

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
            'printer_real_name' => 'Printer Real Name',
            'printer_alias_name' => 'Printer Alias Name',
            'paper_size' => 'Paper Size',
            'default_printer' => 'Default Printer',
            'created_by' => 'Created By',
            'reg_date' => 'Reg Date',
            'updated_by' => 'Updated By',
            'updated_on' => 'Updated On',
        ];
    }

    public function checkdefaultprinterbyinsert($attribute, $params)
    {
        $defaultPrinterAvailability = MerchantPrinters::find()
            ->where(['default_printer' => 1,'merchant_id' => Yii::$app->user->identity->merchant_id])->asArray()->One();

        if (!empty($defaultPrinterAvailability)) {
            $this->addError($attribute, 'Default Printer has already been taken.');
        }
    }

    public function checkdefaultprinterbyupdate($attribute, $params)
    {
        if($this->$attribute == 1){
            $sqlDefaultPrinterCheck = "select * from merchant_printers 
                where merchant_id = '".Yii::$app->user->identity->merchant_id."' and default_printer = 1 and ID != '".$this->ID."'";
            $merchantDefaultPrinterCheck = Yii::$app->db->createCommand($sqlDefaultPrinterCheck)->queryOne();
            if(!empty($merchantDefaultPrinterCheck)){
                $this->addError($attribute, 'Default Printer has already been taken.');
            }
        }    

    }

}

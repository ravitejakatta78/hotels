<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_removed_products".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $order_id
 * @property int $product_id
 * @property int $order_count
 * @property float $price
 * @property string $reg_date
 * @property int|null $created_by
 */
class OrderRemovedProducts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_removed_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'order_id', 'product_id', 'order_count', 'price'], 'required'],
            [['merchant_id', 'order_id', 'product_id', 'order_count', 'created_by'], 'integer'],
            [['price'], 'number'],
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
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'order_count' => 'Order Count',
            'price' => 'Price',
            'reg_date' => 'Reg Date',
            'created_by' => 'Created By',
        ];
    }
}

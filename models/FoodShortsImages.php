<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "food_shorts_images".
 *
 * @property int $ID
 * @property int $food_short_id
 * @property string $image
 */
class FoodShortsImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'food_shorts_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['food_short_id', 'image'], 'required'],
            [['food_short_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'food_short_id' => 'Food Short ID',
            'image' => 'Image',
        ];
    }
}

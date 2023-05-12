<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "food_shorts".
 *
 * @property int $ID
 * @property string $title
 * @property string $content
 * @property int $status
 * @property string $reg_date
 * @property int|null $created_by
 * @property string $updated_on
 * @property int|null $updated_by
 */
class FoodShorts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'food_shorts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'status'], 'required'],
            [['content'], 'string'],
            [['status', 'created_by', 'updated_by'], 'integer'],
            [['reg_date', 'updated_on'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'status' => 'Status',
            'reg_date' => 'Reg Date',
            'created_by' => 'Created By',
            'updated_on' => 'Updated On',
            'updated_by' => 'Updated By',
        ];
    }
}

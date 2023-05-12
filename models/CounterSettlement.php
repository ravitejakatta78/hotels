<?php

namespace app\models;

use app\helpers\MyConst;
use app\helpers\Utility;
use Yii;

/**
 * This is the model class for table "counter_settlement".
 *
 * @property int $ID
 * @property int $merchant_id
 * @property int $pilot_id
 * @property float $pending_amount
 * @property float $order_amount
 * @property float $paid_amount
 * @property int $status
 * @property int $orderid
 * @property string $reg_date
 * @property int|null $created_by
 * @property int $requested_by 1=Pilot,2=Merchant
 */
class CounterSettlement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'counter_settlement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pending_amount', 'order_amount', 'paid_amount'], 'required'],
            [['merchant_id', 'pilot_id', 'pilot_cut_order_id', 'created_by', 'status', 'cut_order_id', 'requested_by'], 'integer'],
            [['pending_amount', 'order_amount', 'paid_amount', 'settlement_amount'
            , 'pending_previous_amount', 'pilot_settled_amount'], 'number'],
            ['remarks','string'],
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
            'merchant_id' => 'Merchant Id',
            'pilot_id' => 'Pilot ID',
            'pending_amount' => 'Pending Amount',
            'order_amount' => 'Order Amount',
            'paid_amount' => 'Paid Amount',
            'settlement_amount' => 'Settlement amount',
            'status' => 'Status',
            'cut_order_id' => 'Cut Off Order',
            'pilot_cut_order_id' => 'Pilot Cut Order Id',
            'pilot_settled_amount' => 'Pilot Settled Amount',
            'remarks' => 'Remarks',
            'reg_date' => 'Reg Date',
            'created_by' => 'Created By',
            'requested_by' => 'Requested By'
        ];
    }

    /**
     * @param int $pilotId
     * @return array|\yii\db\DataReader
     * @throws \yii\db\Exception
     */
    public static function lastPilotSession(int $id , int $requestedBy = 1,$merchantId = null,$exactSession = false)
    {
        $lastMonthDate = Utility::lastDesiredDate(30);

        $sqlLastSession = "select * from counter_settlement where date(reg_date) between '".$lastMonthDate."' and '".date('Y-m-d')."' ";
        if($requestedBy == 1 && $id > 0){
            $sqlLastSession .= " AND pilot_id =  '".$id."' ";
        }
        else if($requestedBy == 2){
            $sqlLastSession .= " AND merchant_id = '".$id."'  ";
        }
        $sqlLastSession .= " AND merchant_id = '".$merchantId."' AND requested_by = '".$requestedBy."' ";
        if(!$exactSession){
            $sqlLastSession .= " AND status = '".MyConst::_COMPLETED."' ";
        }

        
        $sqlLastSession .= " ORDER BY reg_date DESC LIMIT 1 ";
        $resLastSession = Yii::$app->db->createCommand($sqlLastSession)->queryOne();

        return $resLastSession;
    }

    /**
     * @param int $pilotId
     * @param int $orderId
     * @return array
     * @throws \yii\db\Exception
     */
    public static function runningPilotSession(int $id,int $orderId,int  $requstedBy = 1) : array
    {
        $lastMonthDate = Utility::lastDesiredDate(30);

        $sqlRunningSession = "select ID,round(totalamount,2) totalamount from orders where ID > '".$orderId."'  and
        date(reg_date) between '".$lastMonthDate."' and '".date('Y-m-d')."' 
        and orderprocess = '4' ";
        if($requstedBy == 1){
            $sqlRunningSession .= " AND serviceboy_id = '".$id."'  ";
        }

        $sqlRunningSession .= " and closed_by = '".$id."' and paymenttype = '1' ORDER BY ID desc";
        $resRunningSession = Yii::$app->db->createCommand($sqlRunningSession)->queryAll();

        $totalSessionArray = array_column($resRunningSession,'totalamount','ID');

        $runningTotalSessionAmount = !empty($totalSessionArray) ? array_sum(array_values($totalSessionArray)) : 0;
        $runningCutOrderId = !empty($totalSessionArray) ? current(array_keys($totalSessionArray)) : 0;

        $runningSession = ['runningTotalSessionAmount' => round($runningTotalSessionAmount,2), 'runningCutOrderId' => $runningCutOrderId];

        return $runningSession;
    }

    public function saveSettlement(array $arr)
    {
        $model = new CounterSettlement;
        $model->attributes = $arr;
        if ($model->save()) {
            return true;
        }
        else {
            return false;
        }
    }
}

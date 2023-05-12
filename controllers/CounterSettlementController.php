<?php

namespace app\controllers;
use app\helpers\MyConst;
use app\helpers\Utility;
use app\models\CounterSettlement;
use app\models\Serviceboy;
use yii;
use yii\web\Response;

class CounterSettlementController extends GoController
{
    /**
     * @var string $merchantId
     */
    public $merchantId;

    /**
     * CounterSettlementController constructor.
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->merchantId = Yii::$app->user->identity->merchant_id;
    }

    /**
     * @return string
     * @throws yii\db\Exception
     */
    public function actionIndex()
    {
        $sdate = isset($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
        $edate = isset($_POST['edate']) ? $_POST['edate'] : date('Y-m-d');

        $sql = "select cs.ID, cs.reg_date, s.name, cs.pending_amount, cs.paid_amount, cs.order_amount
        , cs.status, cs.settlement_amount, cs.pending_previous_amount
        , case when cs.created_by = cs.merchant_id then m.name else me.emp_name end requested_by
        from counter_settlement cs 
        inner join serviceboy s on s.ID = cs.pilot_id
        left join merchant_employee me on me.ID = cs.created_by 
        left join merchant m on m.ID = cs.created_by 
        where date(cs.reg_date) between '".$sdate."' and '".$edate."' 
        and s.merchant_id = '".$this->merchantId."' and cs.requested_by = 1  
        order by cs.ID desc";
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        $pilot = Serviceboy::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();
        //echo "<pre>";print_r($pilot);exit;
        return $this->render('counter-settlement',['res'=>$res,'sdate'=>$sdate,'edate'=>$edate,'pilot'=>$pilot]);
    }
    public function actionMerchantcountersettlement()
    {
        $sdate = isset($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
        $edate = isset($_POST['edate']) ? $_POST['edate'] : date('Y-m-d');

        $sql = "select cs.ID, cs.reg_date, cs.pending_amount, cs.paid_amount, cs.order_amount
        , cs.status, cs.settlement_amount, cs.pending_previous_amount
        , me.emp_name requested_by
        from counter_settlement cs 
        left join merchant_employee me on me.ID = cs.created_by
        where date(cs.reg_date) between '".$sdate."' and '".$edate."' 
        and cs.merchant_id = '".$this->merchantId."' and cs.requested_by = 2  
        order by cs.ID desc";
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        $pilot = Serviceboy::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id])->asArray()->All();

        return $this->render('merchant-counter-settlement',['res'=>$res,'sdate'=>$sdate,'edate'=>$edate,'pilot'=>$pilot]);
    }

    public function actionConfirmSettlement()
    {
        extract($_POST);
        $model = CounterSettlement::findOne($sessionId);
        $model->status = $settlementStatus;
        $model->save();
    }

    public function actionMerchantSettlement()
    {
        $lastMonthDate = Utility::lastDesiredDate(30);
        $currentMerchantSession = Yii::$app->counter->getCurrentSettlementSession(['usersid' => $this->merchantId,'requestedBy' => 2,'merchantId' => $this->merchantId]);

        $sql = "SELECT s.section_name
                ,sum(case when o.orderprocess = '4' then 1 else 0 end) completedOrderCount
                ,sum(case when o.orderprocess = '3' then 1 else 0 end) cancelledOrderCount
                ,sum(case when o.orderprocess in ('0','1','2') then 1 else 0 end) runningOrderCount
                ,count(o.orderprocess) totalOrderCount
                ,sum(case when (o.paymenttype = '1' and o.orderprocess = '4') then totalamount else 0 end) cashAmount
                ,sum(case when (o.paymenttype = '2' and o.orderprocess = '4') then totalamount else 0 end) onlineAmount
                ,sum(case when (o.paymenttype = '3' and o.orderprocess = '4') then totalamount else 0 end) upiAmount
                ,sum(case when (o.paymenttype = '4' and o.orderprocess = '4') then totalamount else 0 end) cardAmount
                ,sum(case when  o.orderprocess = '4' then totalamount else 0 end) totalOrderAmount
                FROM orders o
                left join tablename tb  on o.tablename = tb.ID
                left join sections s on s.ID = tb.section_id
                where date(o.reg_date) between '".$lastMonthDate."' and '".date('Y-m-d')."' 
                and o.merchant_id = '".$this->merchantId."'
                and o.reg_date > (select reg_date from counter_settlement where merchant_id = '".$this->merchantId."' and requested_by = 2 order by ID desc limit 1)
                group by s.section_name
                ";
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        $unsettledPilots = [];
        $pilots = Serviceboy::find()->select('ID,name')->where(['status' => '1', 'merchant_id' => $this->merchantId])->asArray()->all();

        $lastPilotMerchantSession = CounterSettlement::lastPilotSession(0, 1,$this->merchantId);
        $lastMerchantSession = CounterSettlement::lastPilotSession($this->merchantId, 2,$this->merchantId);

        $pilotCashArray = $pilotPendingCashArray = [];
        foreach($pilots as $pilot) {
            $currentSession = Yii::$app->counter->getCurrentSettlementSession(['usersid' => $pilot['ID'],'requestedBy' => 1,'merchantId' => $this->merchantId ]);
            $currentSession['pilotId'] = $pilot['ID'];
            $currentSession['pilotName'] = $pilot['name'];
            if (($currentSession['current_session']['current_session_amount'] + $currentSession['pending_amount']) > 0) {
                $unsettledPilots[] = $currentSession;
            }

            $pilotPendingCashArray[] = $currentSession['pending_amount']+$currentSession['current_session']['current_session_amount'];

        }

        $sqlMerchantSettlements = 'select * from counter_settlement where merchant_id = \''.$this->merchantId.'\' 
            and requested_by = 2 order by ID desc limit 1';
        $resMerchantSettlements = Yii::$app->db->createCommand($sqlMerchantSettlements)->queryOne();

        if(!empty($resMerchantSettlements))
        {
            $sqlPilotCash = 'select sum(paid_amount) pilot_cash from counter_settlement where merchant_id = \''.$this->merchantId.'\' 
            and requested_by = 1 and status = \'33\' and reg_date > \''.$resMerchantSettlements['reg_date'].'\'';
            $resPilotCash = Yii::$app->db->createCommand($sqlPilotCash)->queryOne();

        } else {
            $sqlPilotCash = 'select sum(paid_amount) pilot_cash from counter_settlement where merchant_id = \''.$this->merchantId.'\' 
            and requested_by = 1 and status = \'33\'';
            $resPilotCash = Yii::$app->db->createCommand($sqlPilotCash)->queryOne();
        }


        return $this->render('merchant-settlement',['res' => $res, 'unsettledPilots' => $unsettledPilots
            ,'pilotCash' => !empty($resPilotCash) ? $resPilotCash['pilot_cash'] : 0, 'pilotPendingCash' => array_sum($pilotPendingCashArray)
            ,'currentMerchantSession' => $currentMerchantSession, 'merchantId' => $this->merchantId
        ]);
    }

    public function actionPilotsettlemetrequest()
    {
        $val = [];
        $val = $_POST;
        $val['total_amount'] = $_POST['settlement_amount'] + $_POST['pendingAmount'];
        $val['pending_previous_amount'] = $_POST['pendingAmount'];
        $val['pending_amount'] = $_POST['settlement_amount'] + $_POST['pendingAmount'] - $_POST['paid_amount'];
        $val['merchantId'] = $this->merchantId;
        $val['status'] = MyConst::_COMPLETED;
        $val['createdBy'] = $this->merchantId;
        $responce = Yii::$app->counter->saveSettlement($val);

        return  $this->asJson($responce);
    }

    public function actionMerchantSettlementRequest()
    {
        $val = $_POST;
        $val['reg_date'] = date('Y-m-d H:i:s');
        $model = new CounterSettlement();
        $model->attributes = $val;
        if($model->save()){
            $payload = ["status" => 1, 'message' => "Session Saved Successfully"];
        }
        else{
            echo "<pre>";print_r($model->getErrors());exit;
            $payload = ["status" => 0, 'message' => "Error While Session Saving"];

        }

        return  $this->asJson($payload);
    }

    public function actionGetPilotSettlementSession()
    {
        $currentSession = Yii::$app->counter->getCurrentSettlementSession(['usersid' => $_POST['pilotSelectedId'],'requestedBy' => 1,'merchantId' => $this->merchantId ]);
        return json_encode($currentSession);
    }
}

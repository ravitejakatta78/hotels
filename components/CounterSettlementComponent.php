<?php
namespace app\components;

use app\helpers\MyConst;
use app\models\CounterSettlement;
use app\models\Serviceboy;
use yii;
use yii\base\Component;

date_default_timezone_set("asia/kolkata");

class CounterSettlementComponent extends Component{

    public function init()
    {
        date_default_timezone_set("asia/kolkata");
        parent::init();
    }

    /**
     * @param array $val
     */
    public function getCurrentSettlementSession(array $val)
    {
        $payload = [];
        $requestedBy = !empty($val['requestedBy']) ? $val['requestedBy'] : 1;
        $merchantId = !empty($val['merchantId']) ? $val['merchantId'] : null;

        $lastPilotSession = CounterSettlement::lastPilotSession($val['usersid'], $requestedBy,$merchantId);
        $lastPilotExactSession = CounterSettlement::lastPilotSession($val['usersid'], $requestedBy,$merchantId,true);
        $payload['last_session'] = !empty($lastPilotSession['reg_date']) ? $lastPilotSession['reg_date'] : date('Y-m-d H:i:s A');
        $payload['last_session_amount'] = !empty($lastPilotSession['order_amount']) ? $lastPilotSession['order_amount'] : 0;
        $payload['last_session_status'] = !empty($lastPilotExactSession['status']) ? $lastPilotExactSession['status'] : 33;

        $payload['pending_amount'] = !empty($lastPilotSession['pending_amount']) ? $lastPilotSession['pending_amount'] : 0;

        $last_cut_order_id = !empty($lastPilotSession['cut_order_id']) ? (int)$lastPilotSession['cut_order_id'] : 0;

        $runningSession = CounterSettlement::runningPilotSession($val['usersid'], $last_cut_order_id, $requestedBy);
        $payload['cut_order_id'] = (int)$runningSession['runningCutOrderId'];



        $payload['running_total_amount'] = round($runningSession['runningTotalSessionAmount'],2);
        if ($last_cut_order_id >= $payload['cut_order_id']) {
            $cut_order_id = $last_cut_order_id;
        } else {
            $cut_order_id = $payload['cut_order_id'];
        }
        $lastPilotMerchantSession = CounterSettlement::lastPilotSession(0, 1, $merchantId);
        $latestPayload = ['status' => 1
            ,"requestedBy" => $requestedBy
            , 'last_session' =>
                    [
                        'last_session_date' => date('d-M-Y h:i:s A ',strtotime($payload['last_session'])),
                        'last_session_amount' => (string)round($payload['last_session_amount'],2),
                        'last_session_status' => (int)$payload['last_session_status']
                    ],
            'current_session' => [
                'current_session_date' =>date('d-M-Y h:i:s A'),
                'current_session_amount' => (string)round($payload['running_total_amount'],2),

            ],
            'cut_order_id' => $cut_order_id,
            'pilot_cut_order_id' => $lastPilotMerchantSession['ID'] ?? 0,
            'pending_amount' => (string)round($payload['pending_amount'],2)
        ];  
        return $latestPayload;
    }

    /**
     * @param array $val
     * @return array
     * @throws yii\db\Exception
     */
    public function settlementHistory(array $val)
    {
        $sql = "select ID,pilot_id,pending_amount,order_amount,paid_amount,settlement_amount,pending_previous_amount
        status,cut_order_id,created_by,reg_date , case 
			when status = '".MyConst::_NEW."' THEN 'New' 
			when status = '".MyConst::_COMPLETED."' THEN 'Completed'
			else 'Rejected' end status_text 
			from counter_settlement where date(reg_date) between '".$val['sdate']."' and '".$val['edate']."'
        and pilot_id = '".$val['header_user_id']."' ";
		if(!empty($val['status'])){
			$sql .= " and status in (".$val['status'].") ";	
		}
		$sql .= " order by ID desc";
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        $result = $singleResult = [];
        foreach ($res as $res) { 
            $singleResult['ID'] = $res['ID'];
            $singleResult['pilot_id'] = $res['pilot_id'];
            $singleResult['pending_amount'] = $res['pending_amount'];
            $singleResult['order_amount'] = $res['order_amount'];
            $singleResult['paid_amount'] = $res['paid_amount'];
            $singleResult['settlement_amount'] = $res['settlement_amount'];
            $singleResult['pending_previous_amount'] = $res['pending_previous_amount'];
            $singleResult['status'] = $res['status'];
            $singleResult['cut_order_id'] = $res['cut_order_id'];
            $singleResult['created_by'] = $res['created_by'];
            $singleResult['reg_date'] = date('d-M-Y h:i:s A',strtotime($res['reg_date']));
            $singleResult['status_text'] = $res['status_text'];
            $result[] = $singleResult;
        }
        return ['status' => '1','settlementHistory' => $result];
    }

    /**
     * @param array $val
     * @return string[]
     */
    public function saveSettlement(array $val)
    {
        $pendingSettlement = CounterSettlement::find()
        ->where(['status' => MyConst::_NEW,'pilot_id' => $val['header_user_id']])
        ->asArray()->All();

        if(empty($pendingSettlement)){
            $settlementArray = [];
            if(!empty($val['createdBy']) ){
                $createdBy = $val['createdBy'];
            }
            else {
                $serviceboyDet =  Serviceboy::findOne($val['header_user_id']);
                $createdBy = $serviceboyDet['employee_id'];
            }

            $settlementArray['merchant_id'] = !empty($val['merchantId']) ? $val['merchantId'] : $serviceboyDet['merchant_id']  ;
            $settlementArray['pilot_id'] = $val['header_user_id'];
            $settlementArray['order_amount'] = $val['total_amount'];
            $settlementArray['pending_previous_amount'] = ($val['total_amount']-$val['settlement_amount']);
            $settlementArray['pending_amount'] = ($val['total_amount']-$val['paid_amount']);
            $settlementArray['settlement_amount'] = $val['settlement_amount'];
            $settlementArray['paid_amount'] = $val['paid_amount'];
            $settlementArray['status'] = !empty($val['status']) ? $val['status'] : MyConst::_NEW;
            $settlementArray['cut_order_id'] = $val['cut_order_id'];
            $settlementArray['reg_date'] = date('Y-m-d H:i:s');
            $settlementArray['created_by'] = $createdBy;
            $settlementArray['requested_by'] = !empty($val['requestedBy']) ? $val['requestedBy'] : 1;
            if($val['paid_amount'] > $val['total_amount'] ) {
                $payload = ['status' => '0', 'message' => 'Entered amount cannot be greater than total payable'];
            }
            else{
                $result = CounterSettlement::saveSettlement($settlementArray);
                if($result) {
                    $payload = ['status' => '1', 'message' => 'Added Successfully'];
                }
                else{
                    $payload = ['status' => '0', 'message' => 'Error While Adding Details!!'];
                }
            }
            
        } else{
            $payload = ['status' => '0', 'message' => 'You have already pending requests!!'];
        }
        
        return $payload;
    }
}
?>
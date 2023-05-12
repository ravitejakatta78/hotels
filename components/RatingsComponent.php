<?php
namespace app\components;

use app\helpers\MyConst;
use app\models\PilotFactorRating;
use yii;
use yii\base\Component;

date_default_timezone_set("asia/kolkata");

class RatingsComponent extends Component{

    public function init()
    {
        date_default_timezone_set("asia/kolkata");
        parent::init();
    }

    public function pilotOrderRating()
    {

    }

    public function pilotRating()
    {

    }

    public function merchantOrderRating()
    {

    }

    public function merchantRating()
    {

    }

    public function merchantEachStarRatings()
    {

    }

    public function orderMerchantRated($val)
    {
        $sqlFeedback = "select mf.ID,u.name
        ,mf.feedback
        ,mf.reg_date reg_date
        ,o.order_id
        ,avg(rating) as rating 
        ,sum(case when mar.ambiance_id = 1 then rating else 0 end) ambiance_1
        ,sum(case when mar.ambiance_id = 2 then rating else 0 end) ambiance_2
        ,sum(case when mar.ambiance_id = 3 then rating else 0 end) ambiance_3
        from merchant_feedback mf
		inner join merchant_ambiance_rating mar on mf.ID = mar.merchant_feedback_id
        inner join users u on u.ID = mf.user_id
        inner join orders o on o.ID = mf.order_id and o.user_id = mf.user_id
		where mf.merchant_id =  '".$val['merchant_id']."' ";
		if (!empty($val['sdate']) && !empty($val['edate'])) {
            $sqlFeedback .= " and date(mf.reg_date) between '".$val['sdate']."' and '".$val['edate']."' ";
        }

        $sqlFeedback .= " group by mf.ID,u.name,mf.feedback,mf.reg_date,o.order_id order by mf.reg_date desc ";
        //echo $sqlFeedback;exit;
        $resFeedback = Yii::$app->db->createCommand($sqlFeedback)->queryAll();

        return $resFeedback;

    }

    public function orderPilotRated($val)
    {
        $sqlFeedback = "select f.ID,u.name
        ,s.name pilot_name        
        ,f.message
        ,o.order_id
        ,f.reg_date reg_date
        ,avg(rating) as rating 
        ,sum(case when par.factor_id = 1 then rating else 0 end) ambiance_1
        ,sum(case when par.factor_id = 2 then rating else 0 end) ambiance_2
        ,sum(case when par.factor_id= 3 then rating else 0 end) ambiance_3
        from feedback f
		inner join pilot_factor_rating par on f.ID = par.feedback_id
        inner join users u on u.ID = f.user_id
        inner join serviceboy s on s.ID = f.pilot_id
        inner join orders o on o.ID = f.order_id and o.user_id = f.user_id
		where f.merchant_id =  '".$val['merchant_id']."' ";
        if (!empty($val['sdate']) && !empty($val['edate'])) {
            $sqlFeedback .= " and date(f.reg_date) between '".$val['sdate']."' and '".$val['edate']."' ";
        }

        if(!empty($val['pilotId'])){
            $sqlFeedback .= " and f.pilot_id = '".$val['pilotId']."' ";
        }

        $sqlFeedback .= " group by f.ID,u.name,s.name,f.message,f.reg_date,o.order_id order by f.reg_date desc";
        //echo $sqlFeedback;exit;
        $resFeedback = Yii::$app->db->createCommand($sqlFeedback)->queryAll();

        return $resFeedback;

    }

    public function pilotFeedback($val){

        $sqlfeedbackrating = "select mfr.factor_id ID,avg(rating) as rating from feedback f
                                inner join pilot_factor_rating mfr on f.ID = mfr.feedback_id
                                where f.pilot_id =  '".$val['header_user_id']."' ";
        if(!empty($val['order_id'])){
            $sqlfeedbackrating .= " and f.order_id = '".$val['order_id']."' ";
        }
        $sqlfeedbackrating .= "	group by mfr.factor_id";
        $feedbackFactorRating = Yii::$app->db->createCommand($sqlfeedbackrating)->queryAll();
        $factorRatingArray =  array_column($feedbackFactorRating,'rating');
        if(!empty($factorRatingArray)){
            $overAllRating = array_sum($factorRatingArray)/count($factorRatingArray);
        }
        else{
            $overAllRating = "0";
        }
        $pilotFactors = PilotFactorRating::FACTORS;
        $singleFactor = $factorRating = [];
        $polishedFactorArray = array_column($feedbackFactorRating,'rating','ID');
        foreach($pilotFactors as $key => $value){
            $singleFactor['name'] =    $value;
            $singleFactor['rating'] = !empty(@$polishedFactorArray[$key]) ? $polishedFactorArray[$key]  : 0;
            $factorRating[] = $singleFactor;
        }

        return $payload = ['feedbackFactorRating' => $factorRating
            ,'overAllRating' => $overAllRating];
    }

}
?>
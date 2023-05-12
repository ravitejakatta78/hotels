<?php

namespace app\controllers;

use app\models\Serviceboy;
use Yii;
use app\models\MerchantFeedback;


class RatingsController extends GoController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMerchantRatings()
    {
        $sdate = !empty($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
        $edate = !empty($_POST['edate']) ? $_POST['edate'] : date('Y-m-d');
        $val = ['merchant_id' => Yii::$app->user->identity->merchant_id, 'sdate' => $sdate, 'edate' => $edate];
        $res = Yii::$app->ratings->orderMerchantRated($val);

        return $this->render('merchant-ratings',['res' => $res,'sdate' => $sdate, 'edate' => $edate]);
    }

    public function actionPilotRatings()
    {
        $sdate = !empty($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
        $edate = !empty($_POST['edate']) ? $_POST['edate'] : date('Y-m-d');
        $pilotId = !empty($_POST['pilotId']) ? $_POST['pilotId'] : null;
        $val = ['merchant_id' => Yii::$app->user->identity->merchant_id
            , 'sdate' => $sdate, 'edate' => $edate
            ,'pilotId' => $pilotId
        ];
        $pilots = Serviceboy::find()->where(['merchant_id' => Yii::$app->user->identity->merchant_id])->asArray()->All();
        $res = Yii::$app->ratings->orderPilotRated($val);

        return $this->render('pilot-ratings',['res' => $res,'sdate' => $sdate
            , 'edate' => $edate,'pilots' => $pilots
            ,'pilotId' => $pilotId]);

    }

}

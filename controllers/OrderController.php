<?php

namespace app\controllers;

use Yii;

class OrderController extends GoController
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCancelledOrders()
    {
        $sdate = !empty($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
        $edate = !empty($_POST['edate']) ? $_POST['edate'] : date('Y-m-d');
        $val = ['merchant_id' => Yii::$app->user->identity->merchant_id, 'sdate' => $sdate, 'edate' => $edate];
        $res = Yii::$app->order->cancelledOrders($val);
        //echo "<pre>";print_r($res);exit;

        return $this->render('cancell-orders',['res' => $res, 'sdate' => $sdate, 'edate' => $edate]);
    }

    public function actionRemovedProducts(){
        $sdate = !empty($_POST['sdate']) ? $_POST['sdate'] : date('Y-m-d');
        $edate = !empty($_POST['edate']) ? $_POST['edate'] : date('Y-m-d');
        $val = ['merchant_id' => Yii::$app->user->identity->merchant_id, 'sdate' => $sdate, 'edate' => $edate];
        $res = Yii::$app->order->removedProductsQuery($val);
        //echo "<pre>";print_r($res);exit;
        return $this->render('removed-products',['res'=>$res,'sdate'=>$sdate,'edate'=>$edate]);
    }
}

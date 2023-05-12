<?php
namespace app\components;

use app\helpers\MyConst;
use yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use app\helpers\Utility;
use app\models\Users;

date_default_timezone_set("asia/kolkata");

class NotificationComponent extends Component {

    public function init(){
        date_default_timezone_set("asia/kolkata");
        parent::init();
    }

    public function userStartedFoodStarted($data) 
	{		
		$userDet = Users::findOne($data['user_id']);
		$title = 'Your order is being Prepared';
		$message = 'Your order will be served in '.$data['preparetime'].' minutes';
		$notificationdet = ['type' => 'RUNNING_ORDER_UPDATE','username' => $userDet['name']];
		
		Utility::sendNewFCM($userDet['push_id'],$title,$message,null,null,null,$data['order_id'],$notificationdet);
	}

	public function userFoodPrepared($data) 
	{
		$userDet = Users::findOne($data['user_id']);
		$title = 'Order Prepared.';
		$message = 'Your order has been prepared. Our pilot will serve it shortly.';
		$notificationdet = ['type' => 'RUNNING_ORDER_UPDATE','username' => $userDet['name']];
		
		Utility::sendNewFCM($userDet['push_id'],$title,$message,null,null,null,$data['order_id'],$notificationdet);
	}

	public function userServeOrder($data) 
	{
		$title = 'Order served.';
		$image = null;
		$message = 'Hey '.$data['userName'].', your order has been served.';
		$notificationdet = ['type' => 'RUNNING_ORDER_UPDATE','username' => $data['userName']];

		Utility::sendNewFCM($data['push_id'],$title,$message,$image,null,null,$data['order_id'],$notificationdet);

	}

	public function userOrderAccepted($data)
	{
		$title = 'Your order has been accepted';
		$image = '';
		$message = "Hey ".ucwords($data['userName']).", ".$data['acceptedUserName']." has accepted your order.";			
		$notificationdet = ['type' => 'RUNNING_ORDER_UPDATE','username' => $data['userName']];
		
		Utility::sendNewFCM($data['push_id'],$title,$message,$image,'6',null,$data['order_id'],$notificationdet); 
	}
	
	public function userOrderComplete($data)
	{
		$title = 'Order Closed.';
		$image = '';
		$message = "Your order has been closed. Thank you for choosing us.";
		$notificationdet = ['type' => 'RUNNING_ORDER_UPDATE','username' => $data['userName']];

		Utility::sendNewFCM($data['push_id'],$title,$message,$image,null,null,$data['order_id'],$notificationdet);
	}

}

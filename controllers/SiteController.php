<?php

namespace app\controllers;

use app\models\MerchantPrinters;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\helpers\Utility;
use app\models\Tablename;
use app\models\Users;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
public $idty;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
public function beforeAction($action)
{
	$this->idty = Yii::$app->user->identity; 
    $this->enableCsrfValidation = false;
 
  //return true;
  return parent::beforeAction($action);
}
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
			  if(is_null(Yii::$app->user->identity)):
		   $url = Yii::$app->request->baseUrl."/site/login";
             $this->redirect($url);
            Yii::$app->end();
        endif;
        return $this->render('index');
    }
    public function actionTransactiondash()
    {
        return $this->render('transactiondash');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $roleId = Yii::$app->user->identity->emp_role;
             $merchant_id = Yii::$app->user->identity->merchant_id;

$sqlPermissionsArr = 'select * from merchant_permission_role_map mprp inner join merchant_permissions mp 
		on mp.ID = mprp.permission_id where merchant_id = \''.$merchant_id.'\' and mprp.employee_id=\''.$roleId.'\' and permission_status = \'1\'';
$permissionsArr = Yii::$app->db->createCommand($sqlPermissionsArr)->queryAll();

/*	$merchantDet =  \app\models\Merchant::findOne(Yii::$app->user->identity->merchant_id);
	 if(strtotime($merchantDet['subscription_date']) < strtotime(date('Y-m-d'))){
    die('Subscription date is out');
} else */
if($roleId == '0') {
    			$launchUrl = Yii::$app->request->baseUrl.'/merchant/tableplaceorder';
} else if(count($permissionsArr) > 0){
    			$launchUrl = Yii::$app->request->baseUrl.$permissionsArr[0]['process_action'];
}
else{
    $launchUrl = Yii::$app->request->baseUrl.'/merchant/accessforbidden';
}

	
				$oldStock = Utility::ingredientcbtoob();
				if(count($oldStock)>0)
				{
					Yii::$app->db
					->createCommand()
					->batchInsert('ingredient_stock_register', ['merchant_id','ingredient_id','ingredient_name'
					,'opening_stock','stock_in','stock_out','wastage','closing_stock', 'reg_date','created_on'],$oldStock)
					->execute();
				}

                $sqlPrevOldOrders = 'select * from orders where 
                date(reg_date) < \''.date('Y-m-d', strtotime('-120 days')).'\' 
                and merchant_id = \''.$merchant_id.'\' and orderprocess in (\'0\',\'1\',\'2\')';

                $resPrevOldOrders = Yii::$app->db->createCommand($sqlPrevOldOrders)->queryAll();
                foreach($resPrevOldOrders as $order) {
                    if(!empty($order['tablename'])) {
                        Yii::$app->merchant->autoCancelledOrders($order['ID'],$order['tablename']);
                    }
                }
                
                
			
			return $this->redirect($launchUrl);
        }

        $model->password = '';
        return $this->renderpartial('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	public function actionSignout()
    {

			$url = 'login';	
        Yii::$app->user->logout();
		return $this->redirect($url);
		
	}
    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    	public function actionForgetpassword()
	{
		
$MAILID = 'hello@foodqonline.com';

$MERCHANT_URL = 'https://foodqonline.com/development/foodq-merchant/';
		$model = new \app\models\MerchantEmployee;

        if ($model->load(Yii::$app->request->post()) ) {

        $arr = $_POST['MerchantEmployee'];
        $arr['email'] = $arr['emp_email'];

			$userdetils = \app\models\Merchant::find()->where(['email'=>$arr['emp_email']])->asArray()->One();
            


				if(!empty($userdetils['ID'])){ 

					$headers = 'MIME-Version: 1.0' . "\r\n";

								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

								$headers .= 'From:  info <'.$MAILID.'>' . " \r\n" .

											'Reply-To:  '.$MAILID.' '."\r\n" .

											'X-Mailer: PHP/' . phpversion();

				 $emailmessage = "Hello ".$userdetils['name'].",

			  <br /><br />

			 Forgot password<br/>

			  Just click following link to change your password<br/>

			  <br /><br />

			  <a href='".$MERCHANT_URL."forgot-password.php?type=".Utility::encrypt('forgotpass')."&id=".Utility::encrypt($userdetils['ID'])."'>Click HERE to change password :)</a>

			  <br /><br />

			  Thanks";

			  $to = $userdetils['email'];

			/*  var_dump(mail($userdetils['email'],'Forgot password mail',$emailmessage,$headers));exit(); */

			if(mail($userdetils['email'],'Forgot password mail',$emailmessage,$headers)){

				if (!filter_var($to, FILTER_VALIDATE_EMAIL) === false) {

				$loginmessage .= " Hi! ".$userdetils['name'].",  we have sent you a link to change your password on this - ".$userdetils['email']." ";

				

				} else {

				$userdetils['email'] = preg_replace('/(?<=.).(?=.*@)/', '*', $userdetils['email']);

				$loginmessage .= " Hi! ".$userdetils['name'].",  we have sent you a link to change your password on this -".$userdetils['email']." ";

				}

			}else{

				$loginmessage .= "Mail failed try again.";

			}

					 

				}else{



				$loginmessage .="Invalid email please use another email.";



			}

			

			
			return $this->refresh();
        }else{
			

		}
		return $this->renderPartial('forgetpassword',['model'=>$model]);
	}
	public function actionWebqrcode(){
	    return $this->renderPartial('qrcode');
	}
	public function actionClosetableorder(){
	    extract($_POST);
	    echo "sad".$_POST['STATUS'];exit;
	    if(isset($_POST['STATUS'])){
	        if($_POST['STATUS'] == 'TXN_SUCCESS'){
	            $orderDet = \app\models\Orders::findOne($_POST['ORDERID']);
	            $tableUpdate = \app\models\Tablename::findOne($orderDet['tablename']);
	            $merchant_pay_types_det = \app\models\MerchantPaytypes::find()->where(['merchant_id'=>Yii::$app->user->identity->merchant_id,'paymenttype' => 2])->One();
	    if(!empty($tableUpdate))
		{
			$table_status = null;
			$current_order_id = 0;
			$tableUpdate->table_status = $table_status;
			$tableUpdate->current_order_id = $current_order_id;
			$tableUpdate->save();
		}
		$orderDet->orderprocess = '4';
		$orderDet->paidstatus = '1';
		$orderDet->paymenttype = $merchant_pay_types_det['ID'];
		//$orderDet->ordercompany = $orderorigin;
		$orderDet->closed_by = Yii::$app->user->identity->merchant_id;
		$orderDet->save();
				return $this->redirect(['merchant/newpos','tableid'=>$tableUpdate['ID'],'tableName'=>$tableUpdate['name'],'current_order_id'=>0  ]);
	        }
	    }
	}

    public function actionKotresponse()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        
        
        $orderId = $_GET['orderId'];
        $sqlOrderDetails = "select o.*
        ,m.name merchant_name
        ,m.storename
        ,m.address
        ,m.city
        ,m.state
        ,m.location
from orders o inner join merchant m on m.ID = o.merchant_id
        where o.ID = '".$orderId."'";
        $orderDetails = Yii::$app->db->createCommand($sqlOrderDetails)->queryOne();
        
        if(!empty($orderDetails)) {
            $tableDetails = Tablename::findOne($orderDetails['tablename']);
            $userDetails = Users::findOne($orderDetails['user_id']);

            $sqlLatestKot = 'select max(CAST(reorder AS UNSIGNED)) as latestreorder from order_products where order_id = \''.$orderId.'\'';
            $resLatestKot = Yii::$app->db->createCommand($sqlLatestKot)->queryOne();
            
            $defaultPrinter = MerchantPrinters::find()
                ->where(['default_printer' => 1, 'merchant_id' => $orderDetails['merchant_id']])
                ->asArray()->One();

            $sqlOrderProducts = 'select 
            case when concat(title , " ",food_type_name) is null then title else concat(title , " ",food_type_name) end   titlewithqty
            ,fs.food_section_name
            ,fc.food_category
            ,op.price item_price
            ,op.count order_count
            ,tn.name as tablename
            ,op.reorder
            ,mp.printer_real_name
            ,case when mp.printer_alias_name is not null then printer_alias_name else \''.$defaultPrinter['printer_alias_name'].'\' end printer_name
            ,paper_size
            from orders o
            inner join order_products op on o.ID = op.order_id
            inner join tablename tn on tn.ID = o.tablename
            inner join product P on P.ID = op.product_id
            left join food_categeries fc on fc.ID = P.foodtype  
            left join food_category_types fct on fct.ID =  P.food_category_quantity and fct.merchant_id =  \''.$orderDetails['merchant_id'].'\'
            left join food_sections fs on fs.ID = fc.food_section_id
            left join merchant_printers mp on fc.category_printer = mp.ID
            where o.ID = \''.$orderId.'\' and op.reorder = \''.$resLatestKot['latestreorder'].'\' order by op.ID desc';
            $resOrderProducts = Yii::$app->db->createCommand($sqlOrderProducts)->queryAll();

            $getProductsReIndex = ArrayHelper::index($resOrderProducts, null, 'printer_name');

            $kotSerialNo = !empty($resLatestKot) ? ($resLatestKot['latestreorder']+1) : 1;

            $sql = "select fc.food_category category_name,fc.ID category_id,mp.printer_real_name,mp.printer_alias_name printer_name
            ,paper_size,(case when default_printer = 1 then true else false end) default_printer  from  food_categeries fc left join 
            merchant_printers mp on fc.category_printer = mp.ID 
            where fc.merchant_id = '".$orderDetails['merchant_id']."' order by printer_alias_name";
            $res = Yii::$app->db->createCommand($sql)->queryAll();
            $printers = array_column($res, 'printer_name', 'category_name');
            $printerRealNameArr = array_column($res, 'printer_real_name', 'printer_name');
            $printerPaperSizeArr = array_column($res, 'paper_size', 'printer_name');
            $printerDefaulteArr = array_column($res, 'default_printer', 'printer_name');




            $newProduclistArr = [];
            $newProduclistArr['tableName'] =  $tableDetails['name'];
            $newProduclistArr['sectionName'] =  $tableDetails->section['section_name'];
            $newProduclistArr['orderId'] = $orderDetails['order_id'];
            $newProduclistArr['billNumber'] = $orderDetails['order_id'];
            $newProduclistArr['KOTNo'] = $orderDetails['order_id'].'K'.$kotSerialNo;

            $newProduclistArr['orderDate'] = date('d-M-Y', strtotime($orderDetails['reg_date']));
            $newProduclistArr['orderTime'] = date('h:i:s A', strtotime($orderDetails['reg_date']));
            $newProduclistArr['merchantName'] = $orderDetails['merchant_name'];
            $newProduclistArr['storeName'] = $orderDetails['storename'];
            $newProduclistArr['address'] = $orderDetails['address'];
            $newProduclistArr['city'] = $orderDetails['city'];
            $newProduclistArr['state'] = $orderDetails['state'];
            $newProduclistArr['location'] = $orderDetails['location'];

            $newProduclistArr['processedBy'] = ['name' => Yii::$app->user->identity->emp_name
                                            ,'designation' => 'Manager'];
            $newProduclistArr['customerInfo']  = ['name' => @$userDetails['name'] ?? 'Guest',
                'mobile' => @$userDetails['mobile'] ?? null,
                'email' => @$userDetails['email'] ?? null
        ];                              
        $newProduclistArr['comments'] = $orderDetails['instructions'] ?? null;
        $newProduclistArr['orderDateTime'] = date('d/m/Y H:i:s',strtotime($orderDetails['reg_date'])) ?? null;
        $pr = 0;
        $sl = 1;
        $printerCount = array_values(array_filter(array_unique(array_values($printers))));
        $slipCountIncArray = [];
            foreach($getProductsReIndex as $printerName => $product) {
                
                $newProduclist[$pr]['foodCategoryName'] = $printerName;
                //$newProduclist[$pr]['printerName'] = (!empty($printers[$categoryName])) ? $printers[$categoryName] : (!empty($defaultPrinter) ? $defaultPrinter['printer_alias_name'] :   null);
                //$newProduclist[$pr]['printerRealName'] = (!empty($printerRealNameArr[$categoryName])) ? $printerRealNameArr[$categoryName] : (!empty($defaultPrinter) ? $defaultPrinter['printer_real_name'] :   null);
                //$newProduclist[$pr]['paperSize'] = (!empty($printerPaperSizeArr[$categoryName])) ? $printerPaperSizeArr[$categoryName] : (!empty($defaultPrinter) ? $defaultPrinter['paper_size'] :   null);
                //$newProduclist[$pr]['defaultPrinter'] = (!empty($printerDefaulteArr[$categoryName])) ? $printerDefaulteArr[$categoryName] : (!empty($defaultPrinter) ? $defaultPrinter['default_printer'] :   null);
                $newProduclist[$pr]['printerName'] = $printerName;
                $newProduclist[$pr]['printerRealName'] = (!empty($printerRealNameArr[$printerName])) ? $printerRealNameArr[$printerName] : (!empty($defaultPrinter) ? $defaultPrinter['printer_real_name'] :   null);
                $newProduclist[$pr]['paperSize'] = (!empty($printerPaperSizeArr[$printerName])) ? $printerPaperSizeArr[$printerName] : (!empty($defaultPrinter) ? $defaultPrinter['paper_size'] :   null);
                //$newProduclist[$pr]['defaultPrinter'] = (!empty($printerDefaulteArr[$categoryName])) ? $printerDefaulteArr[$categoryName] : (!empty($defaultPrinter) ? $defaultPrinter['default_printer'] :   null);
                if(!in_array($newProduclist[$pr]['printerName'],$slipCountIncArray) && $pr != 0 ) {
                    $sl++;    
                    
                }
                $newProduclist[$pr]['KOTSlipNo'] = ($sl).'/'.count($getProductsReIndex);
                $newProduclist[$pr]['list'] =$product;

                $slipCountIncArray[] = $newProduclist[$pr]['printerName'];
                
                $pr++;
                
            }
            $newProduclistArr['products'] = $newProduclist;
            $payload = ['status' => '1','message' => 'Success','response' => $newProduclistArr];
        } else {
            $payload = ['status' => '0','message' => 'Please provide a valid order id'];
        }
        return $this->asJson($payload);
    }

    public function actionCategories()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $sql = "select fc.food_category category_name,fc.ID category_id,mp.printer_real_name,mp.printer_alias_name printer_name,(case when default_printer = 1 then true else false end) default_printer  from  food_categeries fc left join 
        merchant_printers mp on fc.category_printer = mp.ID 
        where fc.merchant_id = '".$_GET['merchant_id']."'";
        $res = Yii::$app->db->createCommand($sql)->queryAll();

        $payload = ['status' => '1','message' => 'Success','data' => $res];
        return $this->asJson($payload);
    }

    public function actionGetOrderSequence()
    {
        return Utility::getOrderSequence($_GET['merchantId'],'DI');
    }
}

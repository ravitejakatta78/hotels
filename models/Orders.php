<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $ID
 * @property string $user_id
 * @property string $merchant_id
 * @property string $serviceboy_id
 * @property string $tablename
 * @property string $order_id
 * @property string $txn_id
 * @property string $txn_date
 * @property string $amount
 * @property float|null $paid_amount amount paid against the total bill amount
 * @property float|null $pending_amount amount pending against the total bill amount
 * @property string $tax
 * @property string $tips
 * @property string $subscription
 * @property string $couponamount
 * @property string $totalamount
 * @property string $paymenttype
 * @property string $orderline
 * @property string $coupon
 * @property string $orderprocess 0=pending,1=accept,2-served,3-cancel,4-deliver
 * @property string $orderprocessstatus 0=pending,1=show
 * @property string $status 0=pending,1=active,2-failed
 * @property string $paidstatus 0=pending,1=paid,2-failed,3-partial paid
 * @property string $orderalert
 * @property string $deliverdate
 * @property string $reorderprocess 0=pending,1=accept
 * @property int $preparetime
 * @property string $preparedate
 * @property int $paymentby
 * @property string $reg_date
 * @property string $mod_date
 * @property int $closed_by
 * @property int $ordertype 1=User,2=POS,3=WebQr,4=Pilot
 * @property string|null $cancelled_role_name
 * @property string|null $cancelled_by_name 
 * @property  int|null $order_performance
 * @property  int|null $preparation_time
 * @property int $extra_preptime_flag 0=no need,1 need extra time
 */
class Orders extends \yii\db\ActiveRecord
{
    const ORDER_PERFORMACE = ['1' => 'On Time','2' => 'Near End Time','3' => 'Extra Time', '4' => 'Late'];
    const _ORDER_BY_USER = 1;
    const _ORDER_BY_POS = 2;
    const _ORDER_BY_WEBQR = 3;
    const _ORDER_BY_PILOT = 4;

    const ONLINE_ORDER_TYPE = [1,3];
    const OFFLINE_ORDER_TYPE = [2,4];

    const ORDER_TYPE = [1 => 'User',2 => 'POS',3 => 'WebQR',4 => 'Pilot'];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'order_id', 'txn_id', 'txn_date', 'amount', 'tax'
			, 'totalamount', 'orderline', 'orderprocess', 'status', 'paymentby', 'reg_date'], 'required'],
            [['orderprocess', 'orderprocessstatus', 'status', 'paidstatus', 'reorderprocess','ordercompany','cancel_reason','instructions'], 'string'],
            [['paymentby','ordertype','closed_by', 'order_performance', 'preparation_time', 'extra_preptime_flag'], 'integer'],
            [['mod_date', 'discount_type', 'discount_number','preparetime', 'reg_date'], 'safe'],
            [['paid_amount', 'pending_amount'], 'number'],
            [['user_id', 'merchant_id', 'serviceboy_id', 'tablename', 'order_id', 'txn_id', 'txn_date', 'amount', 'tax', 'tips', 'subscription', 'totalamount', 'paymenttype', 'orderline', 'coupon', 'preparedate', 'cancelled_role_name', 'cancelled_by_name'], 'string', 'max' => 50],
            [['orderalert', 'deliverdate'], 'string', 'max' => 20],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'user_id' => 'User ID',
            'merchant_id' => 'Merchant ID',
            'serviceboy_id' => 'Serviceboy ID',
            'tablename' => 'Tablename',
            'order_id' => 'Order ID',
            'txn_id' => 'Txn ID',
            'txn_date' => 'Txn Date',
            'amount' => 'Amount',
            'paid_amount' => 'Paid Amount',
            'pending_amount' => 'Pending Amount',
            'tax' => 'Tax',
            'tips' => 'Tips',
            'subscription' => 'Subscription',
            'couponamount' => 'Couponamount',
            'totalamount' => 'Totalamount',
            'paymenttype' => 'Paymenttype',
            'orderline' => 'Orderline',
            'coupon' => 'Coupon',
            'orderprocess' => 'Orderprocess',
            'orderprocessstatus' => 'Orderprocessstatus',
            'status' => 'Status',
            'paidstatus' => 'Paidstatus',
            'orderalert' => 'Orderalert',
            'deliverdate' => 'Deliverdate',
            'reorderprocess' => 'Reorderprocess',
            'preparetime' => 'Preparetime',
            'preparedate' => 'Preparedate',
            'paymentby' => 'Paymentby',
            'ordertype' => 'ordertype',
            'ordercompany' => 'ordercompany',
            'reg_date' => 'Reg Date',
            'mod_date' => 'Mod Date',
            'cancel_reason' => 'Cancel Reason',
            'closed_by' => 'Closed By',
            'cancelled_role_name' => 'Cancelled Role Name',
            'cancelled_by_name' => 'Cancelled By Name',
            'order_performance' => 'Order Performance'
        ];
    }
}

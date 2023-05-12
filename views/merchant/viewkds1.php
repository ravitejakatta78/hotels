<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Orders;
$actionId = Yii::$app->controller->action->id;
?>

<style>
.ktchn-title{cursor:pointer;}
</style>

        <!-- Side Navbar -->
        
<div class="ktchn-mainpage">

<header class="page-header" style="padding: 5px 0px !important;">
	<!--<div class="text-right">
		<a href="<?= Url::to(['site/index'])?>"><button class="btn btn-add btn-sm"><i class="fa fa-mail-reply"></i> Back to Home</button></a>
	</div>-->
</header>

		<section class="col-md-12">
		    <?= \Yii::$app->view->renderFile('@app/views/merchant/_orders.php',['actionId'=>$actionId]); ?>
			<span style="margin-left:20px"><input type="checkbox" id="checkedAll"> Select All</span>
		    <button type="button" class="btn btn-info float-right" onclick="clearSelectedOrders()">Clear Selected (<span id="clearSelectedSpan">0</span>)</button>
		<div class="row">
		<div class="col-md-9">
		<div class="row">
		  <?php if(count($tableres) > 0) { 
		  $clr = 0;
		  $color1 = 'color1';
		  $color2 = 'color2';
		  
		  foreach($tabletimeres as $orderid => $tabletime) { ?>
		  	<div class="col-md-4">

		  		<div style="cursor: pointer;" class="kitchen-table-head <?php if($clr % 2 == 0) { echo $color1; }else { echo $color2; }  ?> col-md-12" id="<?= $orderid ?>">
				  <input type="hidden" value="<?= $tableIds[$orderid];?>" id="table_id_<?= $orderid; ?>">

		  			<div class="float-left">
					  
		  				<div class="time"><input type="checkbox" class="orderClear" id="check_order_id_<?= $orderid; ?>" onclick="orderClear('<?= $orderid; ?>')"><span class="kitchen-order-prepared"> <?= date('h:i A',strtotime($tabletime));?></span> </div>
		  				<div class="tableno kitchen-order-prepared"><?= $tableres[$orderid] ?? $orderid ; ?></div>
		  			</div>
		  			<div class="float-right kitchen-order-prepared">
		  				<div class="time"><span class="badge badge-dark"><?= in_array($tableOrderType[$orderid],Orders::ONLINE_ORDER_TYPE) ? 'Online' : 'Offline'; ?></span></div>
		  				<div class="tableno"><?= $tableorderidres[$orderid] ?></div>
		  			</div>
		  		</div>
		  		<div class="kitchen-table-body <?php if($clr % 2 == 0) { echo $color1; }else { echo $color2; }  ?> col-md-12">
		  			<?php 
					//echo "<pre>";print_r($resindex[$tableid]);exit;
					for($i=0;$i<count($resindex[$orderid]);$i++) { ?>
					<div class="row" onclick="productDeliver('<?= $resindex[$orderid][$i]['order_product_id'];?>','<?= $resindex[$orderid][$i]['item_deliver_status'];?>')">
		  				<div class="col-md-2">
		  					<div class="ktchn-qty">
			  					<?= $resindex[$orderid][$i]['orderCount'].'x' ?>
			  				</div>
		  				</div>
		  				<div class="col-md-8">
		  					<div class="ktchn-title">
		  						<?= $resindex[$orderid][$i]['title_quantity'] ;?>
		  					</div>

		  				</div>
						<?php if(!empty($resindex[$orderid][$i]['item_deliver_status'])) { ?>
						<div class="col-md-2 item-delivered" id="<?= $resindex[$orderid][$i]['order_product_id'];?>">
						<i class="fa fa-check color-green"></i>
						</div>							
						<?php } ?>

		  			</div>
					<?php if(count($resindex[$orderid]) - 1 != $i) { ?>
		  			<div class="divider"></div>
					<?php } } ?>
		  			
		  		</div>
		  	</div>
		  <?php 
		  $clr++;
		  } ?> 
		  <div class="clearfix"></div>
		  <?php } ?>
</div>
</div>
<div class="col-md-3 float-right">
<nav class="side-navbar kitchen-sidebar">
          <div class="kitchen-menu-list">
          	<?php 		$countSummary = \yii\helpers\ArrayHelper::index($res, null, 'food_category');?>
			<div class="col-md-12">
          		<div class="float-left">
          		<strong>Products</strong>
          	</div>
          	<div class="float-right">
          		<strong>Qty</strong>
          	</div>
          	</div>
          	<div class="clearfix"></div>
          	<?php foreach($countSummary as $foodtypename => $foodsummary) {
			for($c=0;$c<count($countSummary[$foodtypename]);$c++) {
				$foodtypeSummary[$countSummary[$foodtypename][$c]['title_quantity']][$c] =  $countSummary[$foodtypename][$c]['orderCount'];

			}
				?>
			<div class="kitchen-menu-head">
          		<h4 class="text-center"><?= $foodtypename;?></h4>
          	</div>
			<div class="kitchen-menu-body">
          		<ul>
			<?php foreach($foodtypeSummary as $productname => $orderCountArr) { ?>
          	
          			<li class="row">
          				<div class="float-left col-md-10 pl-0 pr-0"><?= $productname; ?></div>
          				<div class="float-right kitchen-item-count"><?= array_sum($foodtypeSummary[$productname])?></div>
          			</li>
          			          		
			<?php } 
			unset($foodtypeSummary); ?>
			
</ul>
          	</div>
			<?php } ?>
          </div>
         
        </nav>
</div>
</div>
	
        </section>
<script>
	
	var clearSelectedOrdersArray = new Array;
$(document).ready(function(){
	
	
		setTimeout(function() {
			var selectedOrders = parseInt($("#clearSelectedSpan").html());
				if(selectedOrders == 0) {
					location.reload(); 
				}
			}, 20000);
	
	
});
$(window).scroll(function(){
      if ($(this).scrollTop() > 135) {
          $('#task_flyout').addClass('fixed');
      } else {
          $('#task_flyout').removeClass('fixed');
      }
  });

$(".kitchen-order-prepared").click(function(){
	var tableId = $(this).closest(".kitchen-table-head").find("input").val();
	var orderId = $(this).closest(".kitchen-table-head").attr('id');
	swal({
				title: "Is Order Prepared?", 
				//text: "You will be redirected to https://utopian.io", 
				type: "warning",
				confirmButtonText: "Yes!",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
		
						var request = $.ajax({
  url: "tableorderstatuschange",
  type: "POST",
  data: { id : orderId,tableId:tableId,kdschange:1,chageStatusId:1},
}).done(function(msg) {
	location.reload();
});
					} else if (result.dismiss === 'cancel') {
					    
					}
				})
});

function productDeliver(orderProductId,deliverStatus){
	if(deliverStatus == '') {
		swal({
			title: "Is Item prepared ?", 
			//text: "You will be redirected to https://utopian.io", 
			type: "warning",
			confirmButtonText: "Yes!",
			showCancelButton: true
		})
		.then((result) => {
				if (result.value) {
					var request = $.ajax({
							url: "tableorderproductdeliver",
							type: "POST",
							data: {orderProductId : orderProductId},
						}).done(function(msg) {
							location.reload();
						});
				} else if (result.dismiss === 'cancel') {
							
				}
		});
	}				
}  
function deletetables(id){
//	var res = confirm('Are you sure want to delete??')
		    swal({
				title: "Are you sure want to delete??", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "deletetables",
						  type: "POST",
						  data: {id : id},
						}).done(function(msg) {
							
							location.reload();
						});
					}
				});

}

$('#checkedAll').click(function (e) {
	//e.preventDefault();
	var checkBox = document.getElementById("checkedAll");
	if(checkBox.checked) {
		$('.orderClear').prop('checked', true);
	} else {
		$('.orderClear').prop('checked', false);
		location.reload();
	}

    
	var clearSelected = '<?= count($tabletimeres) ?? 0 ; ?>';
	var clearOrderIdArray  = '<?= json_encode($orderIds); ?>';
	var clearOrderId = JSON.parse(clearOrderIdArray);
	if(clearSelected > 0) {
		$("#clearSelectedSpan").html(clearSelected);
		clearSelectedOrdersArray = clearOrderId;
	}
	
});

function orderClear(orderId){
	
	$('#checkedAll').prop('checked', false);
	// Get the checkbox
	var checkBox = document.getElementById("check_order_id_"+orderId);
	var clearSelected;
	if(checkBox.checked) {
		clearSelected = parseInt($("#clearSelectedSpan").html()) + 1;
		$("#clearSelectedSpan").html(clearSelected);
		clearSelectedOrdersArray.push(orderId);
	} else {
		clearSelected = parseInt($("#clearSelectedSpan").html())- 1;
		$("#clearSelectedSpan").html(clearSelected);
		const index = clearSelectedOrdersArray.indexOf(orderId);
		if (index > -1) { // only splice array when item is found
			clearSelectedOrdersArray.splice(index, 1); // 2nd parameter means remove one item only
		}
	}
}
	function clearSelectedOrders()
	{

		if(parseInt(clearSelectedOrdersArray.length) == 0 ) {
			swal(
                      'Warning!',
                      'Please Select Order',
                      'warning'
                    )
                    return false;
		} else {
			swal({
				title: "Are you sure?", 
				type: "warning",
				showCancelButton: true
		    })
		    	.then((result) => {
					if (result.value) {
					    var request = $.ajax({
						  url: "kds-prepared-orders",
						  type: "POST",
						  data: {'orderIds' : clearSelectedOrdersArray},
						}).done(function(msg) {
							location.reload();
						});
					}
				});
		}
		
	}

</script>
        </div>
      

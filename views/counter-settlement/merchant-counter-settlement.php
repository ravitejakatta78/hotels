
<?php

use app\helpers\MyConst;
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$actionId = Yii::$app->controller->action->id;
?>
<header class="page-header">

          </header>
          <section>
              <?= \Yii::$app->view->renderFile('@app/views/counter-settlement/_settlement_tabs.php',['actionId'=>$actionId]); ?>
              <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Merchant Counter Settlement</h3>
				<div class="col-md-6 text-right pr-0">
				<a href="<?= Url::to(['counter-settlement/merchant-settlement']); ?>"><button type="button" class="btn btn-add btn-xs" id="myBtn"><i class="fa fa-plus mr-1"></i> Add Counter Settlement</button></a>
				</div>
              </div>


              <div class="card-body">
			  <form class="form-inline" method="POST" action="merchantcountersettlement">
                  <div class="form-group">
                    <label class="control-label">Start Date:</label>
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker1" name="sdate" placeholder="Start Date" value="<?= $sdate ; ?>">
                </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label">End Date:</label>
                  <div class="input-group mb-3 mr-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                  </div>
                  <input type="text" class="form-control datepicker2" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                </div>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Search" class="btn btn-add btn-sm btn-search"/>
                  </div>
                  
                </form>
   
 
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                          <th>S No</th>
                          <th>Date & Time</th>
                          <th>Settlement Amount</th>
                          <th>Pending Amount</th>
                          <th>Payable Amount</th>
                          <th>Balance Amount</th>

                      </tr>
                    </thead>
		            <tbody>
					    <?php for($i=0;$i<count($res);$i++){ ?> 
					        <tr>
					           <td><?= ($i+1); ?></td>
					           <td><?= date('d-M-Y h:i A',strtotime($res[$i]['reg_date'])); ?></td>
                     <td><?=  $res[$i]['settlement_amount']; ?></td>
					           <td><?php echo $res[$i]['pending_previous_amount'];

                                   ?></td>
                                <td><?= $res[$i]['paid_amount'];  ?></td>
                                <td><?= $res[$i]['pending_amount'];  ?></td>


					       </tr>     
					    <?php } ?>			
                    </tbody>
                  </table>
              </div>
            </div>
          </div>



        </section>
		<?php
$script = <<< JS
    $('#example').DataTable();
JS;
$this->registerJs($script);
?>
<script>
$(document).ready(function(){
	

});
function settleSession(sessionId,settlementStatus){

    var request = $.ajax({
        url: "confirm-settlement",
        type: "POST",
        data: {sessionId : sessionId,settlementStatus:settlementStatus},
    }).done(function(msg) {
        location.reload()
    });
}
</script>

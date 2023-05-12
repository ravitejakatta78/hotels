
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
                <h3 class="h4 col-md-6 pl-0 tab-title">Pilot Settlement</h3>
				<div class="col-md-6 text-right pr-0">
                    <button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Pilot Settlement</button>
				</div>
              </div>


              <div class="card-body">
			  <form class="form-inline" method="POST" action="index">
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
                          <th>Pilot</th>
                          <th>Settlement Amount</th>
                          <th>Pending Amount</th>
                          <th>Payable Amount</th>
                          <th>Balance Amount</th>
                          <th>Status</th>
                          <th>Requested By</th>
                          <th>Action</th>
                      </tr>
                    </thead>
		            <tbody>
					    <?php for($i=0;$i<count($res);$i++){ ?> 
					        <tr>
					           <td><?= ($i+1); ?></td>
					           <td><?= date('d-M-Y h:i A',strtotime($res[$i]['reg_date'])); ?></td>
					           <td><?=  $res[$i]['name']; ?></td>
                     <td><?=  $res[$i]['settlement_amount']; ?></td>
					           <td><?php echo $res[$i]['pending_previous_amount'];
                                /* if($res[$i]['status'] == MyConst::_COMPLETED){
                                        echo $res[$i]['pending_amount'];
                                   }else{
                                        echo '-';
                                   } */
                                   ?></td>
                                <td><?= $res[$i]['paid_amount'];  ?></td>
                                <td><?= $res[$i]['pending_amount'];  ?></td>

                                <td>
                                    <?php
                                        if($res[$i]['status'] == \app\helpers\MyConst::_NEW) {
                                            echo 'New';
                                        }
                                        else if($res[$i]['status'] == \app\helpers\MyConst::_COMPLETED) {
                                            echo 'Approved';
                                        }
                                        else{
                                            echo 'Rejected';
                                        } 
                                        ?>
                                </td>
                                <td><?= $res[$i]['requested_by']; ?></td>
                                <td class="icons">
								<?php if($res[$i]['status'] == \app\helpers\MyConst::_NEW) { ?>
                                    <a onclick="settleSession('<?= $res[$i]['ID'];?>','33')"><span class="fa fa-check"></span></a>
                                    <a title="Product - Delete" onClick="settleSession('<?= $res[$i]['ID']; ?>','17')"><span class="fa fa-close"></span></a>
                                <?php } ?>
								</td>
					       </tr>     
					    <?php } ?>			
                    </tbody>
                  </table>
              </div>
            </div>
          </div>
              <div class="modal fade" id="myModal" role="dialog" data-backdrop="static" data-keyboard="false">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <!-- Modal Header -->
                          <div class="modal-header">
                              <h4 class="modal-title">Add Pilot Settlement</h4>
                              <button type="button" class="close" data-dismiss="modal" onclick="refreshorder()">&times;</button>
                          </div>
                          <!-- Modal body -->
                          <div class="modal-body">
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group row">
                                          <label class="control-label col-md-4">Pilot Name</label>
                                          <div class="col-md-8">
                                                <select id="pilotSelectedId" onchange="getPilotSession()">
                                                    <option value="">Select Pilot</option>
                                                    <?php for($p=0;$p < count($pilot); $p++){ ?>
                                                        <option value="<?= $pilot[$p]['ID']; ?>"><?= $pilot[$p]['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group row">
                                          <label class="control-label col-md-4">Last Session</label>
                                          <div class="col-md-8">
                                              <span id="lastSessionDateText"></span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group row">
                                          <label class="control-label col-md-4">This Session</label>
                                          <div class="col-md-8">
                                              <span id="currentSessionDateText"></span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group row">
                                          <label class="control-label col-md-4">Settled Amount</label>
                                          <div class="col-md-8">
                                              <span id="settledAmountText"></span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group row">
                                          <label class="control-label col-md-4">Pending Amount</label>
                                          <div class="col-md-8">
                                              <span id="pendingAmountText"></span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group row">
                                          <label class="control-label col-md-4">Payable Amount</label>
                                          <div class="col-md-8">
                                                <input type="text" class="form-control" onkeypress="return isNumberKey(event)" id="payable_amount" onchange="getBalance()" >
                                              <input type="hidden" class="form-control" id="total_amount" value="0" >
                                              <input type="hidden" class="form-control" id="cutOrderId" value="0" >

                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group row">
                                          <label class="control-label col-md-4">Balance Amount</label>
                                          <div class="col-md-8">
                                              <span id="balanceAmountText"></span>

                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="submit" class="btn btn-add btn-hide" onclick="settlementRequest()">Add Settlement</button>
                          </div>
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

function refreshorder() {
    location.reload();
}

function getPilotSession()
{
    var pilotSelectedId = $("#pilotSelectedId").val();
    $("#total_amount").val(0);
    var request = $.ajax({
        url: "get-pilot-settlement-session",
        type: "POST",
        data: {pilotSelectedId : pilotSelectedId},
    }).done(function(msg) {
        var res = JSON.parse(msg);
        $("#lastSessionDateText").html(res['last_session']['last_session_date']);
        $("#currentSessionDateText").html(res['current_session']['current_session_date']);
        $("#settledAmountText").html(parseFloat(res['current_session']['current_session_amount']) );
        $("#pendingAmountText").html(parseFloat(res['pending_amount']) );
        $("#payable_amount").attr('placeholder',parseFloat(res['current_session']['current_session_amount']) + parseFloat(res['pending_amount']));
        $("#total_amount").val(parseFloat(res['current_session']['current_session_amount']) + parseFloat(res['pending_amount']));
        $("#cutOrderId").val(res['cut_order_id']);
    });

}

function getBalance(){
    var paid_amount = parseFloat($("#payable_amount").val());
    var total_amount = parseFloat($("#total_amount").val());

    if(paid_amount > total_amount){
        swal(
            'Warning!',
            'Please Check The Amount You Paid',
            'warning'
        );
        $("#paid_amount").val('');
        return false;
    }
    else if(isNaN(paid_amount)) {
        swal(
            'Warning!',
            'Paid Amount Should Be Number',
            'warning'
        );
        //$("#balance_amount").val('');
        $("#balanceAmountText").html('');
        return false;
    }
    else{
        var pending_amount = (parseFloat($("#total_amount").val()) - parseFloat($("#payable_amount").val())).toFixed(2);
        //$("#balance_amount").val(pending_amount);
        $("#balanceAmountText").html(pending_amount);
    }

}

function settlementRequest()
{
    var pilotId = $("#pilotSelectedId").val();
    if(pilotId == ''){
        swal('Warning!',
            'Please select the pilot',
            'warning'
        );
        return false;
    }


    var payableAmount = $("#payable_amount").val();
    var currentSessionAmout = $("#settledAmountText").html();
    var pendingAmount = $("#pendingAmountText").html();
    var cutOrderId = $("#cutOrderId").val();

    $.ajax({
        url: "pilotsettlemetrequest",
        type: "POST",
        data: {
            'header_user_id':pilotId,
            'settlement_amount': currentSessionAmout,
            'pendingAmount':pendingAmount,
            'paid_amount':payableAmount,
            'cut_order_id':cutOrderId,
            'requestedBy': 1,
        },
    }).done(function(msg) {
        if(msg.status == 1) {
            var statusText = 'success';
        } else {
            var statusText = 'warning';
        }

        swal({
                            title: statusText.charAt(0).toUpperCase()+statusText.slice(1)+'!',
                            type: statusText,
                            showCancelButton: false
                        })
                            .then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            });

        //swal(
        //    statusText.charAt(0).toUpperCase()+statusText.slice(1)+'!',
        //    msg.message,
        //    statusText
        //);
        //location.reload();
    });
}

</script>

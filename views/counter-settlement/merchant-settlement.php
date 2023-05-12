
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
<!--              <div class="card-header d-flex align-items-center pt-0 pb-0">-->
<!--                <h3 class="h4 col-md-6 pl-0 tab-title">Merchant Counter Settlement</h3>-->
<!--              </div>-->
              <div class="card-body">
                  <h4>Last Settled On : <?= $currentMerchantSession['last_session']['last_session_date']; ?></h4>
                  <hr />
                  <table id="example" class="table table-striped table-bordered ">
                      <thead>
                          <tr>
                              <th rowspan="2" >Sections</th>
                              <th colspan="4" >Number Of Orders</th>
                              <th colspan="5">Sales Revenue</th>
                          </tr>
                          <tr>
                              <th >Completed</th>
                              <th >Running</th>
                              <th >Cancelled</th>
                              <th >Total Orders</th>
                              <th >Online Payment</th>
                              <th >UPI Scanner</th>
                              <th >Card Swipe</th>
                              <th >Cash On Dine</th>
                              <th >Total Amount</th>
                          </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($res as $res) { ?>
                          <tr>
                              <td><?= $res['section_name']; ?></td>
                              <td><?= $res['completedOrderCount']; ?></td>
                              <td><?= $res['runningOrderCount']; ?></td>
                              <td><?= $res['cancelledOrderCount']; ?></td>
                              <td><?= $res['totalOrderCount']; ?></td>
                              <td><?= round($res['onlineAmount'],2); ?></td>
                              <td><?= round($res['upiAmount'],2); ?></td>
                              <td><?= round($res['cardAmount'],2); ?></td>
                              <td><?= round($res['cashAmount'],2); ?></td>
                              <td><?= round($res['totalOrderAmount'],2); ?></td>
                          </tr>
                      <?php } ?>
                      </tbody>
                  </table>
                  <br>
                  <h4>Unsettled Pilots</h4>
                  <hr />
                  <table id="example1" class="table table-striped table-bordered ">
                      <thead>
                          <tr>
                              <th >S.No</th>
                              <th >Name</th>
                              <th >Settlement Amount</th>
                              <th >Pending Amount</th>
                              <th >Payable Amount</th>
                              <th >Action</th>
                          </tr>
                      </thead>
                      <tbody>
                            <?php $x = 1 ;
                            foreach ($unsettledPilots as $unsettledPilot) { ?>
                                <tr>
                                    <td><?= $x; ?></td>
                                    <td><?= $unsettledPilot['pilotName']; ?></td>
                                    <td><?= $unsettledPilot['current_session']['current_session_amount']; ?></td>
                                    <td><?= $unsettledPilot['pending_amount']; ?></td>
                                    <td><input type="text" onkeypress="return isNumberKey(event)" id="payable_<?= $unsettledPilot['pilotId']; ?>" class="form-control" placeholder="<?= $unsettledPilot['current_session']['current_session_amount'] + $unsettledPilot['pending_amount']; ?>"> </td>
                                    <td><button class="btn btn-success" onclick="settlementRequest('<?= $unsettledPilot['pilotId']; ?>','<?= $unsettledPilot['current_session']['current_session_amount'] ?>','<?= $unsettledPilot['pending_amount']; ?>','<?= $unsettledPilot['cut_order_id']; ?>')">Settle</button></td>
                                </tr>
                            <?php $x++;
                            } ?>
                      </tbody>
                  </table>
                  <hr />
                  <div class="col-md-6">
                      <table class="table table-striped table-bordered ">
                        <tr>
                            <th>Pilot Cash</th>
                            <th>Pilot Pending Amount</th>
                            <th>My Cash</th>
                            <th>My Pending Amount</th>
                        </tr>
                        <tr>
                            <td><?= $pilotCash ?? 0; ?></td>
                            <td><?= $pilotPendingCash; ?></td>
                            <td><?= $currentMerchantSession['current_session']['current_session_amount'] ;?></td>
                            <td><?= $currentMerchantSession['pending_amount']; ?></td>
                        </tr>
                      </table>
                  </div>



                      <div class="form-group row">
                          <label class="control-label col-md-3">Payable Amount</label>
                          <div class="col-md-3">
                              <input type="text" name="paid_amount" onchange="getBalance()" onkeypress="return isNumberKey(event)" id="paid_amount" class="form-control" placeholder="<?= $pilotCash + $currentMerchantSession['current_session']['current_session_amount'] + $currentMerchantSession['pending_amount']; ?>" >
                              <input type="hidden" name="settlement_amount" id="settlement_amount" class="form-control" value="<?= $currentMerchantSession['current_session']['current_session_amount']; ?>" >
                              <input type="hidden" name="total_amount" id="total_amount" class="form-control" value="<?=  $pilotCash + $currentMerchantSession['current_session']['current_session_amount'] + $currentMerchantSession['pending_amount']; ?>" >
                              <input type="hidden" name="cut_order_id" id="cut_order_id" class="form-control" value="<?= $currentMerchantSession['cut_order_id']; ?>" >
                              <input type="hidden" name="pilot_cut_order_id" id="pilot_cut_order_id" class="form-control" value="<?= $currentMerchantSession['pilot_cut_order_id']; ?>" >
                          </div>
                          <label class="control-label col-md-3">Balance</label>
                          <div class="col-md-3">
                              <div class="pending_amount_text"></div>
                              <input type="hidden" class="form-control" placeholder="Balance" id="pending_amount" name="previous_amount">
                          </div>
                      </div>
                          <div class="form-group row">
                          <label class="control-label col-md-3">Remarks</label>
                          <div class="col-md-9">
                              <textarea class="form-control" id="remarks" name="remarks" placeholder="Remarks"></textarea>
                          </div>
                      </div>
                      <button class="btn btn-success" onclick="saveMerchantSession()"> Submit</button>

              </div>
        </div>
    </div>
</section>

<?php
$script = <<< JS
    $('#example').DataTable({searching: false, paging: false, info: false});

JS;
$this->registerJs($script);
?>

<script>
    function settlementRequest(pilotId,currentSessionAmout,pendingAmount,cutOrderId)
    {
        var payableAmount = $("#payable_"+pilotId).val();
        swal({
            title: 'Are You Sure',
            type: 'warning',
            showCancelButton: true
        })
            .then((result) => {
                if (result.value) {
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
                            title: msg.message,
                            type: statusText,
                            showCancelButton: false
                        })
                            .then((result) => {
                                if (result.value) {
                                    location.reload();
                                }
                            });
                    });
                }
            });

    }


    function isNumber(id) {
        var textValue = $("#" + id).val();
        if (isNaN(textValue)) {
            $("#" + id).val('');
            return false;
        }
    }

    function getBalance(){
       var paid_amount = parseFloat($("#paid_amount").val());
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
            $("#pending_amount").val('');
            $(".pending_amount_text").html('');
            return false;
       }
       else{
        var pending_amount = (parseFloat($("#total_amount").val()) - parseFloat($("#paid_amount").val())).toFixed(2);
        $("#pending_amount").val(pending_amount);
        $(".pending_amount_text").html(pending_amount);
       }
       
    }

    function saveMerchantSession()
    {

        swal({
            title: 'Are You Sure',
            type: 'warning',
            showCancelButton: true
        })
            .then((result) => {
                if (result.value) {
                    $.ajax({
                        url: "merchant-settlement-request",
                        type: "POST",
                        data: {
                            'merchant_id':'<?= $merchantId; ?>',
                            'order_amount':$("#total_amount").val(),
                            'settlement_amount': $("#settlement_amount").val(),
                            'pending_amount':$("#pending_amount").val(),
                            'pending_previous_amount': '<?= $currentMerchantSession['pending_amount']; ?>',
                            'paid_amount':$("#paid_amount").val(),
                            'cut_order_id':$("#cut_order_id").val(),
                            'pilot_cut_order_id':$("#pilot_cut_order_id").val(),
                            'pilot_settled_amount':'<?= $pilotCash; ?>',
                            'requested_by': 2,
                            'status': 33,
                            'remarks':$("#remarks").val(),
                            'created_by':'<?= $merchantId; ?>',
                        },
                    }).done(function(msg) {
                        if(msg.status == 1) {
                            var statusText = 'success';
                        } else {
                            var statusText = 'warning';
                        }
                        swal(
                            statusText.charAt(0).toUpperCase()+statusText.slice(1)+'!',
                            msg.message,
                            statusText
                        );
                        window.location.replace('merchantcountersettlement');
                    });
                } else {
                    location.reload();
                }

            });


    }
</script>

<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<header class="page-header">
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery2.2.4.min.js';?>"></script>
<script src="<?= Yii::$app->request->baseUrl.'/js/jquery.table2excel.js'?>"></script>
</header>
<section>
    <div class="col-lg-12">
        <div class="card">

            <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Cancelled Orders</h3>
                <div class="col-md-6 text-right pr-0">
                </div>
            </div>
            <div class="card-body">
                <form class="form-inline" method="POST" action="cancelled-orders">

                    <div class="form-group col-md-3">
                        <label class="control-label">Start Date:</label>
                        <div class="input-group mb-3 mr-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control datepicker1" name="sdate" placeholder="Start Date" value="<?= $sdate ; ?>">
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="control-label">End Date:</label>
                        <div class="input-group mb-3 mr-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control datepicker2" name="edate" placeholder="End Date" value="<?= $edate ; ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-3   ">
                        <input type="submit" value="Search" class="btn btn-add btn-sm btn-search"/>
                    </div>

                    <div class="col">
                    <div class="form-group text-center">
                        <button class="exportToExcel btn btn-add btn-sm" >Download</button>
                    </div>
                    </div>

                </form>

                <table id="myTable" class="table table-striped table-bordered table2excel" cellspacing="0">
                    <thead>
                    <tr>
                        <th>S No</th>
                        <th>Order ID</th>
                        <th>Section</th>
                        <th>Spot</th>
                        <th>Order Amount</th>
                        <th>Date & Time</th>
                        <th>Cancellation Reason</th>
                        <th>Cancelled By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(!empty($res)){
                        $x=1;
                        foreach($res as $rating){
                            ?>
                            <tr>
                                <td><?php echo $x;?></td>
                                <td><?php echo $rating['order_id'];?></td>
                                <td><?php echo $rating['section_name'];?></td>
                                <td><?php echo $rating['table_name'];?></td>
                                <td><?= $rating['totalamount']; ?></td>
                                <td><?php echo date('d-M-Y h:i A',strtotime($rating['reg_date']));?></td>
                                <td><?= $rating['cancel_reason']; ?></td>
                                <td><?= $rating['cancelled_by_name']; ?></td>

                            </tr>
                            <?php $x++; }?>
                    <?php  }?>

                    </tbody>
                </table>


            </div>
        </div>
    </div>


</section>
<script>
$(function() {
    $(".exportToExcel").click(function(e){
	var tablelength = $('.table2excel tr').length;
	if(tablelength){
            var preserveColors = ($('.table2excel').hasClass('table2excel_with_colors') ? true : false);
            $('.table2excel').table2excel({
		exclude: ".noExl",
		name: "Excel Document Name",
		filename: "Cancel Orders Report" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
		fileext: ".xls",
		exclude_img: true,
		exclude_links: true,
		exclude_inputs: true,
		preserveColors: preserveColors
            });
	}
    });
});
</script>


<?php
$script = <<< JS
    $('#example').DataTable();
JS;
$this->registerJs($script);
?>

<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId = Yii::$app->controller->action->id;

?>
<script src="<?= Yii::$app->request->baseUrl.'/js/typeahead.js'?>"></script>
<header class="page-header">
<?php 
foreach (Yii::$app->session->getAllFlashes() as $message) {
    echo SweetAlert::widget([
        'options' => [
            'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
            'text' => (!empty($message['text'])) ? Html::encode($message['text']) : 'Text Not Set!',
            'type' => (!empty($message['type'])) ? $message['type'] : SweetAlert::TYPE_INFO,
            'timer' => (!empty($message['timer'])) ? $message['timer'] : 4000,
            'showConfirmButton' =>  (!empty($message['showConfirmButton'])) ? $message['showConfirmButton'] : true
        ]
    ]);
}
?>
</header>
	<div class="loading" style="display:none">
  		<img src="<?php echo Url::base(); ?>/img/load.gif" >
  	</div>
 <section>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Merchant Printers List</h3>
				
				<div class="col-md-6 text-right pr-0">
	<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal" ><i class="fa fa-plus mr-1"></i> Add Printer</button>			
				</div>
				
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th> 
                        <th>Printer Name</th>  
                        <th>Printer Alias Name</th>            
						<th>Paper Size</th>
                        <th>Default/Primary Printer</th>
                        <th>Action</th>
                      </tr>
                    </thead>
		    <tbody>
								<?php $x=1; 
									foreach($merchantPrinters as $printer){
								?>
                                  <tr>
                                 	<td><?php echo $x ;?></td>   
									<td><?= $printer['printer_real_name']; ?></td>
									<td><?= $printer['printer_alias_name']; ?></td>
									<td><?= $printer['paper_size']; ?></td>
                                      <td>
                                          <label class="switch">
                                              <input type="checkbox" <?php if($printer['default_printer']=='1' ){ echo 'checked';}?>
                                                     onChange="changedefaultprinter('<?= $printer['ID'];?>');"> <span class="slider round"></span> </label>
                                      </td>
                                      <td  class="icons">
										<a onclick="editprinterpopup('<?php echo $printer['ID']; ?>')"  ><span class="fa fa-pencil"></span></a>
									</td>
                                </tr>
									<?php $x++; }?>
                       </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add Printer</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			
<?php	$form = ActiveForm::begin([
    		'id' => 'printer-form',
		'options' => ['class' => 'form-horizontal','wrapper' => 'col-xs-12',],
        	'layout' => 'horizontal',
			 'fieldConfig' => [
        'horizontalCssClasses' => [
            
            'offset' => 'col-sm-offset-0',
            'wrapper' => 'col-sm-12 pl-0 pr-0',
        ],
		]
		]) ?>
		
<div class="row">	
<div class="col-md-6">	

	   <div class="form-group row">
	   <label class="control-label col-md-4">Printer Alias Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'printer_alias_name')->textinput(['class' => 'form-control'])->label(false); ?>
	   </div>
	   </div>
</div>

<div class="col-md-6">	

	   <div class="form-group row">
	   <label class="control-label col-md-4">Printer Real Name</label>
	   <div class="col-md-8">
			      <?= $form->field($model, 'printer_real_name')->textinput(['class' => 'form-control'])->label(false); ?>
	   </div>
	   </div>
</div>

<div class="col-md-6">	

	   <div class="form-group row">
	   <label class="control-label col-md-4">Paper Size</label>
	   <div class="col-md-8">
           <?= $form->field($model, 'paper_size')->textinput(['class' => 'form-control'])->label(false); ?>
	   </div>
	   </div>
</div>


</div>
	   </div>
	   <div class="modal-footer">
		<?= Html::submitButton('Add Printer', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 


<?php ActiveForm::end() ?>
</div>
</div>
</div>	

    

<div id="editprinter" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Printer</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="editprinterbody">
		
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
function editprinterpopup(id)
{

$.ajax({
  url: "editprinterpopup",
  type: "POST",
  data: {id : id},
}).done(function(msg) {
	$('#editprinterbody').html(msg);
	$('#editprinter').modal('show');
});		

}

function changedefaultprinter(id)
{
    $.ajax({
        url: "change-default-printer",
        type: "POST",
        data: {id : id},
    }).done(function(msg) {
        if(msg == 2) {
            swal({
                title: "Please enable other printer as default printer to disable this",
                type: "warning",
                showCancelButton: false
            }).then((result) => {
                location.reload();
            });

        }
        else{
            location.reload();
        }

    });

}

</script>

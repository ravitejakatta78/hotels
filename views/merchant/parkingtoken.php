
<?php
use app\helpers\Utility;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use aryelds\sweetalert\SweetAlert;
$actionId =  Yii::$app->controller->action->id;
?>
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
          <section>
		  
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header d-flex align-items-center pt-0 pb-0">
                <h3 class="h4 col-md-6 pl-0 tab-title">Parking Token</h3>
				<div class="col-md-6 text-right pr-0">
				<button type="button" class="btn btn-add btn-xs" id="myBtn" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus mr-1"></i> Add Token</button>

				</div>
              </div>


              <div class="card-body">
                <div class="table-responsive">   
                  <table id="example" class="table table-striped table-bordered ">
                    <thead>
                      <tr>
                        <th>S No</th>
						<th>Token Number</th>
						<th>Download QR Code</th>
						<th>Download Multi Code</th>
						<th>Status</th>
				   
                      </tr>
                    </thead>
					<tbody>
                    <?php $x=1; 
							foreach($tokenModel as $tokenModel){
						?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><?php echo $tokenModel['token_number']; ?></td>
                                <td class="icons">
                                <?php if(empty($tokenModel['qr_path'])) { ?>
                                  <a class="label label-xs label-warning "  
                                  onclick="qrdownload('<?= $tokenModel['ID'];?>')"><span class="fa fa-download"></span></a>
                                  <?php }else{ ?>
                                    <a class="label label-xs label-warning "  
                                  onclick="qrpreview('<?= $tokenModel['ID'];?>','<?= $tokenModel['token_number'];?>','<?= $merchantdetails['ID']; ?>','<?= $merchantdetails['qrlogo']; ?>','<?= $tokenModel['qr_path'];?>')" target="_blank" ><span class="fa fa-download"></span></a>
                                <?php } ?>
                                </td>		
                                <td class="icons"><a class="label label-xs label-warning " <?php if(!empty($merchantdetails['qrlogo'])){?>  
                                                  href="../../../test.php?table=<?= $tokenModel['ID'];?>&tablename=<?= $tokenModel['token_number'];?>&userid=<?= $merchantdetails['ID']; ?>&qrlogo=<?= $merchantdetails['qrlogo']; ?>&qrtype=new"	<?php }?> target="_blank" ><span class="fa fa-download"></span></a>
                                </td>
                                <td>
                                    <label class="switch">
                                    <input type="checkbox" <?php   if($tokenModel[ 'status']=='1' ){ echo 'checked';}?> 
									onChange="changestatus('parkingtoken',
                                    <?php echo $tokenModel['ID'];?>);"> <span class="slider round"></span> </label>
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
          <h4 class="modal-title">Create Parking Token</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
			<?php	$form = ActiveForm::begin([
    		'id' => 'parking-token-form',
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
 <td>
                               
    <div class="col-md-6">	
	   <div class="form-group row">
            <label class="control-label col-md-4">Token</label>
            <div class="col-md-8">
                        <?= $form->field($model, 'token_number')->textinput(['class' => 'form-control','autocomplete'=>'off'
                        ,'placeholder'=>'Token Number','onchange'=>'qrdownload()'])->label(false); ?>
            </div>
	   </div>
	   
	   
	</div>
</div>
	   <div class="modal-footer">
		<?= Html::submitButton('Add Token', ['class'=> 'btn btn-add btn-hide']); ?>

      </div> 


<?php ActiveForm::end() ?>
        
    

        
      </div>
    </div>
  </div>
  
    <div id="updateemployee" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Edit Employee</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
	    <div class="modal-body" id="employeebody">
		
		</div>	
		
		  
		
	</div>
	</div>
</div>
        </section>
		<?php
$script = <<< JS
 
   $('#example').DataTable({
  "scrollX": true
});


JS;
$this->registerJs($script);
?>

<script>

jQuery('body').on('click', '[data-toggle=dropdown]', function() {
    var opened = $(this).parent().hasClass("open");
    if (! opened) {
        $('.btn-group').addClass('open');
        $("button.multiselect").attr('aria-expanded', 'true');
    } else {
        $('.btn-group').removeClass('open');
        $("button.multiselect").attr('aria-expanded', 'false');
    }
});
function qrdownload(){
    var tableid = '1';//$('#tableid').val();
    var tablename = 'parking_token';//$('#tableid').val();
    var userid = '1';$('#userid').val();


    var request = $.ajax({
        url: "tokenqrdownload",
        type: "POST",
        data: {tablename: tablename,tableid : tableid,userid: userid},
    }).done(function(msg) {
        alert(msg);
        //location.reload();
    });
}

function qrpreview(tableid,tokennumber,userid,qrlogo,qrpath){


var form=document.createElement('form');
form.setAttribute('method','post');
form.setAttribute('action','showtoken');
form.setAttribute('target','_blank');

var hiddenField = document.createElement("input");
hiddenField.setAttribute("name", "tableid");
hiddenField.setAttribute("type", "hidden");
hiddenField.setAttribute("value", tableid);
form.appendChild(hiddenField);

var hiddenField = document.createElement("input");
hiddenField.setAttribute("name", "tokennumber");
hiddenField.setAttribute("type", "hidden");
hiddenField.setAttribute("value", tokennumber);
form.appendChild(hiddenField);	

var hiddenField = document.createElement("input");
hiddenField.setAttribute("name", "userid");
hiddenField.setAttribute("type", "hidden");
hiddenField.setAttribute("value", userid);
form.appendChild(hiddenField);	

var hiddenField = document.createElement("input");
hiddenField.setAttribute("name", "qrlogo");
hiddenField.setAttribute("type", "hidden");
hiddenField.setAttribute("value", qrlogo);
form.appendChild(hiddenField);		

var hiddenField = document.createElement("input");
hiddenField.setAttribute("name", "qrpath");
hiddenField.setAttribute("type", "hidden");
hiddenField.setAttribute("value", qrpath);
form.appendChild(hiddenField);		

document.body.appendChild(form);
form.submit();    


}
    
</script>

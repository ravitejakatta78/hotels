<?php
$merchant_id = Yii::$app->user->identity->merchant_id;
$roleId = Yii::$app->user->identity->emp_role;
$sqlPermissionsArr = 'select * from merchant_permission_role_map mprp inner join merchant_permissions mp 
		on mp.ID = mprp.permission_id where merchant_id = \''.$merchant_id.'\' and mprp.employee_id=\''.$roleId.'\' and permission_status = \'1\'';
$permissionsArr = Yii::$app->db->createCommand($sqlPermissionsArr)->queryAll();
$permissions = (array_column($permissionsArr,'process_name'));
?> 
 <div class="col-md-12">
		  <ul class="resp-tabs-list">
		   <?php  if($roleId == '0' || in_array('Valet List',$permissions) ) {?>
		   <?php } ?>		
		</ul>
		  </div>
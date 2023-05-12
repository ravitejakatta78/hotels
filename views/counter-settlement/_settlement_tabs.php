<?php

use \yii\helpers\Url;

$merchant_id = Yii::$app->user->identity->merchant_id;
$roleId = Yii::$app->user->identity->emp_role;
?>

  <div class="col-md-12">
		  <ul class="resp-tabs-list">
		  <?php if($roleId == '0') { ?>
		<a href="<?= Url::to(['counter-settlement/index']); ?>"><li class="resp-tab-item <?php if($actionId == 'index') { echo "resp-tab-active" ;} ?>">Pilot Settlement</li></a>
		  <?php }  if($roleId == '0') { ?>
			<a href="<?= Url::to(['counter-settlement/merchantcountersettlement']); ?>"><li class="resp-tab-item <?php if($actionId == 'merchantcountersettlement') { echo "resp-tab-active" ;} ?>" >Counter Settlement</li></a>
        <?php } ?>
        </ul>
    </div>






    
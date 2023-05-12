<?php 
$roleId = Yii::$app->user->identity->emp_role;

?>

  <div class="col-md-12">
		  <ul class="resp-tabs-list">
		  <?php if($roleId == '0') { ?>
		<a href="<?= \yii\helpers\Url::to(['ratings/merchant-ratings']); ?>"><li class="resp-tab-item <?php if($actionId == 'merchant-ratings') { echo "resp-tab-active" ;} ?>">Merchant Ratings</li></a>
		  <?php } if($roleId == '0' ) { ?>
			<a href="<?= \yii\helpers\Url::to(['ratings/pilot-ratings']); ?>"><li class="resp-tab-item <?php if($actionId == 'pilot-ratings') { echo "resp-tab-active" ;} ?>" >Pilot Ratings</li></a>	
        <?php } ?>
        </ul>
    </div>
    	
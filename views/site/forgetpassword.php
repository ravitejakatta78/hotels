<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap\ActiveForm;

?>
<!DOCTYPE html>
<html lang="zxx">

<head>
	<title>SuperPilot Merchant Password Reset</title>
	<link rel="shortcut icon" href="<?php echo Url::base(); ?>/Frame49.png" type="image/x-icon" />
	<!-- Meta tag Keywords -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8" />
	<meta name="keywords" content="" />
	<link rel="stylesheet" type="text/css" href="<?= Yii::$app->request->baseUrl.'/css/css/login.css';?>">
</head>

<body>
	<section class="w3l-forms-23">
		<div class="forms23-block-hny">
			<div class="wrapper">
		
				<div class="d-grid forms23-grids">
					<div class="form23">
						<div class="main-bg">
							<h6 class="sec-one">Forgot Password</h6>
							<div class="speci-login first-look">
								<img src="<?= Yii::$app->request->baseUrl.'/img/superpilot.png';?>" width="100%" alt="" class="img-responsive">
							</div>
						</div>
						<div class="bottom-content">
	<?php $form = ActiveForm::begin([
        'id' => 'forget-form',
        'layout' => 'horizontal',
	'options' => [
                'class' => 'form-validate',
         ],
	'fieldConfig' => [
		'template' => "{label}\n{input}\n{hint}\n{error}"
    	],

    ]); ?>
                    <div class="form-group">

				      <?= $form->field($model, 'emp_email')->textInput(['class' => 'input-material','placeholder'=>'Email'])->label(false); ?>

                    </div>
<?= Html::submitButton('Submit', ['class' => 'loginhny-btn btn', 'name' => 'login-button']) ?>
                    <!-- This should be submit button but I replaced it with <a> for demo purposes-->
<!--                  </form>-->
				    <?php ActiveForm::end(); ?>
					<p> <a href="<?= Url::to(['site/login']); ?>">Login Here</a></p>
						</div>
					</div>
				</div>
				<div class="w3l-copy-right text-center">
					<p>© 2021 SuperPilot Innovations</p>
				</div>
			</div>
		</div>
	</section>
	<!-- //login-section -->
</body>

</html>
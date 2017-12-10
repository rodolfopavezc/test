<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login"); ?>

<h1><?php //echo $title; ?></h1>

<div class="form">
	<div class="well" style="font-size: 20px; font-weight: bold; margin: 80px; text-align: center;">
		<?php echo $content; ?>
	</div>
	<?php echo CHtml::link('<span class="glyphicon glyphicon-arrow-left"></span> Volver',array('/user/login'), array('class'=>'btn btn-primary')); ?>
</div><!-- yiiForm -->
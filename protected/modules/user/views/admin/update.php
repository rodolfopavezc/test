<?php
$this->breadcrumbs=array(
	(UserModule::t('Users'))=>array('admin'),
	$model->username=>array('view','id'=>$model->id),
	(UserModule::t('Update')),
);
?>


<?php
	echo $this->renderPartial('_form', array('model'=>$model,'titulo'=>'Actualizar usuario'));
	
?>
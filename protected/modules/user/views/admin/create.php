<?php
$this->breadcrumbs=array(
	UserModule::t('Users')=>array('admin'),
	UserModule::t('Create'),
);

?>


<?php
	echo $this->renderPartial('_form', array('model'=>$model,'titulo'=>'Agregar usuario'));
?>
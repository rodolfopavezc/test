<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	
	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$rut = $_GET['rut'];
		$activkey = $_GET['activkey'];
		if ($rut&&$activkey) {
			$find = User::model()->notsafe()->findByAttributes(array('rut'=>$rut));
			if (isset($find)&&$find->status) {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>"Tu cuenta ha sido activada. Pincha <a href='".Yii::app()->request->baseUrl."/'>AQUÍ</a> para ir al <strong>Formulario de Postulación.</strong>"));
			} elseif(isset($find->activkey) && ($find->activkey==$activkey)) {
				$find->activkey = UserModule::encrypting(microtime());
				$find->status = 1;
				$find->save();
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>"Tu cuenta ha sido activada. Pincha <a href='".Yii::app()->request->baseUrl."/'>AQUÍ</a> para ir al <strong>Formulario de Postulación.</strong>"));
			} else {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
			}
		} else {
			$this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
		}
	}

}
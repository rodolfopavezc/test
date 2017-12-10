<?php

class RecoveryController extends Controller
{
	public $defaultAction = 'recovery';
	
	/**
	 * Recovery password
	 */
	public function actionRecovery () {
		$form = new UserRecoveryForm;
		if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->returnUrl);
		    } else {
				$username = ((isset($_GET['username']))?$_GET['username']:'');
				$activkey = ((isset($_GET['activkey']))?$_GET['activkey']:'');
				if ($username&&$activkey){
					$form2 = new UserChangePassword;
		    		$find = User::model()->notsafe()->findByAttributes(array('username'=>$username));
		    		if(isset($find)&&$find->activkey==$activkey) {
			    		if(isset($_POST['UserChangePassword'])) {
							$form2->attributes=$_POST['UserChangePassword'];
							if($form2->validate()) {
								$find->password = $form2->password;
								$find->activkey=Yii::app()->controller->module->encrypting(microtime().$form2->password);
								if ($find->status==0) {
									$find->status = 1;
								}
								$find->save();
								Yii::app()->user->setFlash('recoveryMessage',UserModule::t("New password is saved."));
								$this->redirect(Yii::app()->controller->module->recoveryUrl);
							}
						} 
						$this->render('changepassword',array('form'=>$form2));
		    		} else {
		    			Yii::app()->user->setFlash('recoveryMessage',UserModule::t("Incorrect recovery link."));
						$this->redirect(Yii::app()->controller->module->recoveryUrl);
		    		}
		    	} else {
			    	if(isset($_POST['UserRecoveryForm'])) {
			    		$form->attributes=$_POST['UserRecoveryForm'];
						
			    		if($form->validate()) {
			    			$user=null;
							$username_tmp=$form->user_name;
									    			
			    			$user = User::model()->notsafe()->findbyPk($form->user_id);
							if($user!==null){
								if(is_null($user->activkey)){
									$user->activkey=Yii::app()->controller->module->encrypting(microtime().$user->password);
									$user->save();
								}								
								$subject="Aviso de recuperación de contraseña";
	                            $activation_url = 'http://' . $_SERVER['HTTP_HOST'].$this->createUrl(implode(Yii::app()->controller->module->recoveryUrl),array("activkey" => $user->activkey, "username" => $user->username));
	                            $message="Estimado usuario(a):<br/><br/>
	                            	Hemos recibido tu solicitud de recuperación de contraseña en el <strong>Formulario de Postulacion Rezagada al Crédito CAE 2015</strong>. Para crear una nueva contraseña, pincha el siguiente link: <a href='".$activation_url."'>".$activation_url."</a> 
	                            	<br/><br/><br/>
	                            	<strong>Importante!</strong> Este mensaje fue generado automáticamente. Por favor, <u>NO</u> respondas a este correo electrónico. 
	                                <br/><br/><br/>
	                                Atentamente,
	                                <br/>
	                                Comisión Ingresa";
								
								
				    			UserModule::sendMail($user->email,$subject,$message);
								Yii::app()->user->setFlash('recoveryMessage',UserModule::t("Please check your email. An instructions was sent to your email address."));
				    			$this->refresh();
							}
								
			    			
			    		}
			    	}
		    		$this->render('recovery',array('form'=>$form));
		    	}
		    }
	}

}
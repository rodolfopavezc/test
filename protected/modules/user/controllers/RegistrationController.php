<?php

class RegistrationController extends Controller
{
	public $defaultAction = 'registration';
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}
	/**
	 * Registration user
	 */
	public function actionRegistration() {
            $model = new RegistrationForm;
            //$profile=new Profile;
            //$profile->regMode = true;
            
			// ajax validator
			if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
			{
				echo UActiveForm::validate(array($model/*,$profile*/));
				Yii::app()->end();
			}
			
		    if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->profileUrl);
		    } else {
		    	if(isset($_POST['RegistrationForm'])) {
					$model->attributes=$_POST['RegistrationForm'];
					//$profile->attributes=((isset($_POST['Profile'])?$_POST['Profile']:array()));
					if($model->validate()/*&&$profile->validate()*/)
					{
						$soucePassword = $model->password;
						$model->activkey=UserModule::encrypting(microtime().$model->password);
                        $model->username=$model->rut;
						$model->password=$model->password;
						$model->verifyPassword=$model->verifyPassword;
						$model->superuser=0;
						$model->status=((Yii::app()->controller->module->activeAfterRegister)?User::STATUS_ACTIVE:User::STATUS_NOACTIVE);
						
						if ($model->save()) {
						    Authassignment::model()->deleteAll('userid=:userid',array(':userid'=>$model->id));
                            $userperfiles= new Authassignment;
                            $userperfiles->itemname= 'alumno';
                            $userperfiles->userid= $model->id;
                            $userperfiles->save(false);
                            //Debemos crear con los datos que tenemos al usuario en la tabla Datos personales
                            $datosContacto=DatosContacto::model()->findByPk($model->rut);
                            if($datosContacto===null){
                                $datosContacto=new DatosContacto;
                                $datosContacto->nombres=$model->nombres;
                                $datosContacto->ape_paterno=$model->ape_paterno;
                                $datosContacto->ape_materno=$model->ape_materno;
                                $datosContacto->rut=$model->rut;
                                $datosContacto->dv=$model->dv;
                                $datosContacto->email=$model->email;
                                $datosContacto->save(false);
                            }
							//$profile->user_id=$model->id;
							//$profile->save();
							if (Yii::app()->controller->module->sendActivationMail){
								$activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $model->activkey, "rut" => $model->rut));
                                //ENVIAR CORREO DE AVISO
                                    $asunto="Aviso de activación de cuenta";
                                    $texto="Estimado usuario(a):<br/><br/>
                                    	Hemos recibido tu solicitud de registro en el <strong>Formulario de Postulacion Rezagada al Crédito CAE 2015</strong>. Para activar tu cuenta, pincha el siguiente link: <a href='".$activation_url."'>".$activation_url."</a> 
                                    	<br/><br/><br/>
                                    	<strong>Importante!</strong> Este mensaje fue generado automáticamente. Por favor, <u>NO</u> respondas a este correo electrónico. 
                                        <br/><br/><br/>
                                        Atentamente,
                                        <br/>
                                        Comisión Ingresa";
                                        //Mensaje a enviar para el interlocutor
                                        $message = new YiiMailMessage;
                                        $message->setBody($texto, 'text/html',''.Yii::app()->charset);
                                        $message->subject = $asunto; 
                                        $message->addTo($model->email);
                                        $message->from = Yii::app()->params['adminEmail'];
                                        Yii::app()->mail->send($message); 
                                
                                
								//UserModule::sendMail($model->email,UserModule::t("You registered from {site_name}",array('{site_name}'=>Yii::app()->name)),UserModule::t("Please activate you account go to {activation_url}",array('{activation_url}'=>$activation_url)));
							}
							
							if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
									$identity=new UserIdentity($model->username,$soucePassword);
									$identity->authenticate();
									Yii::app()->user->login($identity,0);
									$this->redirect(Yii::app()->controller->module->returnUrl);
							} else {
								if (!Yii::app()->controller->module->activeAfterRegister&&!Yii::app()->controller->module->sendActivationMail) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Contact Admin to activate your account."));
								} elseif(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(UserModule::t('Login'),Yii::app()->controller->module->loginUrl))));
								} elseif(Yii::app()->controller->module->loginNotActiv) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email or login."));
								} else {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email."));
								}
								$this->refresh();
							}
						}
					} /*else $profile->validate();*/
				}
			    $this->render('/user/registration',array('model'=>$model/*,'profile'=>$profile*/));
		    }
	}
}
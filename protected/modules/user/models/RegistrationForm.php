<?php
/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * user registration form data. It is used by the 'registration' action of 'UserController'.
 */
class RegistrationForm extends User {
	public $verifyPassword;
	public $verifyCode;
	
	public function rules() {
		$rules = array(
			array('rut', 'checkRutSinDV','message'=>'El Rut ingresado no es valido. Ej:123456789-0'),
			array('rut', 'checkRutNoEntrar','message'=>'El Rut ingresado no puede postular.'),
			array('rut,dv, password, verifyPassword, email,nombres,ape_paterno,ape_materno', 'required'),
			array('rut','numerical', 'integerOnly'=>true),
			//array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")),
			array('email', 'email'),
			array('rut', 'length', 'max'=>8),
			
			array('rut', 'unique', 'message' => UserModule::t("This user's name already exists.")),
			
			//array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
			array('nombres, ape_paterno, ape_materno', 'length', 'max'=>200),
			//array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Retype Password is incorrect.")),
			//array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
		);
		if (!(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')) {
			array_push($rules,array('verifyCode', 'captcha', 'allowEmpty'=>!UserModule::doCaptcha('registration')));
		}
		
		array_push($rules,array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Retype Password is incorrect.")));
		return $rules;
	}


	
}
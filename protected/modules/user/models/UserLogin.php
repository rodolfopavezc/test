<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UserLogin extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'safe'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>UserModule::t("Remember me next time"),
			'username'=>UserModule::t("username"),
			'password'=>UserModule::t("password"),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())  // we only want to authenticate when no input errors
		{
			$identity=new UserIdentity($this->username,$this->password);
			$identity->authenticate();
			switch($identity->errorCode)
			{
				case UserIdentity::ERROR_NONE:
					$duration=$this->rememberMe ? Yii::app()->controller->module->rememberMeTime : 0;
					Yii::app()->user->login($identity,$duration);
					break;
                case UserIdentity::ERROR_USERNAME_INVALID_AND_BENEFICIARIO:
                    $this->addError("username","El usuario es beneficiario pero no tiene clave asociada. Debe solicitar la clave en la siguiente dirección web: <a href='http://solicitarclave.ingresa.cl' target='_blank'>http://solicitarclave.ingresa.cl</a>");
                    break;
                case UserIdentity::ERROR_USERNAME_NOT_EXIST:
                    $this->addError("username","El usuario no existe. Para crearlo debe ir  <a href='".Yii::app()->request->baseUrl."/user/registration' target='_blank'>Aquí</a>");
                    break;
				case UserIdentity::ERROR_EMAIL_INVALID:
					$this->addError("username",UserModule::t("Email is incorrect."));
					break;
				case UserIdentity::ERROR_USERNAME_INVALID:
					$this->addError("username",UserModule::t("Username is incorrect."));
					break;
				case UserIdentity::ERROR_STATUS_NOTACTIV:
					$this->addError("status",UserModule::t("You account is not activated."));
					break;
				case UserIdentity::ERROR_STATUS_BAN:
					$this->addError("status",UserModule::t("You account is blocked."));
					break;
				case UserIdentity::ERROR_PASSWORD_INVALID:
					$this->addError("password",UserModule::t("Password is incorrect."));
					break;
                case UserIdentity::ERROR_PASSWORD_USERNAME_INVALID:
                    $this->addError("username",UserModule::t("Username or Password is incorrect."));
                    break;
			}
		}
	}
}

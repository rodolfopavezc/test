<?php

class User extends CActiveRecord
{
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_BANNED=-1;
	
	//TODO: Delete for next version (backward compatibility)
	const STATUS_BANED=-1;
	
	/**
	 * The followings are the available columns in table 'users':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $activkey
     * @var string $nombres
	 * @var integer $createtime
	 * @var integer $lastvisit
	 * @var integer $superuser
	 * @var integer $status
     * @var timestamp $create_at
     * @var timestamp $lastvisit_at
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
    
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return Yii::app()->getModule('user')->tableUsers;
	}
    
   
    public static function label($n = 1) {
        return Yii::t('app', 'Usuario|Usuarios', $n);
    }
    
    public static function representingColumn() {
        return 'username';
    }
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.CConsoleApplication
		return ((get_class(Yii::app())=='CConsoleApplication' || (get_class(Yii::app())!='CConsoleApplication' && Yii::app()->getModule('user')->isAdmin()))?array(
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Nombre de usuario incorrecto (El largo debe estar entre 3 y 10 caracteres).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Contraseña incorrecta (El largo debe ser minimo de 4 caracteres).")),
			//array('email', 'email','message'=>UserModule::t('El correo electrónico no es una dirección válida.')),
			array('username', 'unique', 'message' => UserModule::t("El nombre de usuario ingresado ya existe.")),			
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Sólo puede utilizar los siguientes caracteres (A-z0-9).")),
			array('status', 'in', 'range'=>array(self::STATUS_NOACTIVE,self::STATUS_ACTIVE)),//,self::STATUS_BANNED
			array('superuser', 'in', 'range'=>array(0,1)),			
            array('create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('lastvisit_at', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
			array('username, email, status,nombres, ape_paterno, password', 'required'),
			array('superuser, status','numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>200),
            array('email', 'email'),
			array('email', 'confirmarEmail','message'=>'El registro ingresado no coincide con el campo "Email"'),
			array('nombres, ape_paterno, ape_materno', 'length', 'max'=>200),	
			array('nombres, ape_paterno, ape_materno,email, username, password,create_at, lastvisit_at, superuser, status, activkey', 'default', 'setOnEmpty' => true, 'value' => null),
            array('id, nombres, ape_paterno, ape_materno,email, username, password,create_at, lastvisit_at, superuser, status, activkey', 'safe', 'on'=>'search'),
		):((Yii::app()->user->id==$this->id)?array(
			array('username, email,nombres, ape_paterno, password', 'required'),
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Nombre de usuario incorrecto (El largo debe estar entre 3 y 10 caracteres.).")),
			array('email', 'email'),
			array('email', 'confirmarEmail','message'=>'El registro ingresado no coincide con el campo "Email"'),
            array('nombres, ape_paterno, ape_materno', 'length', 'max'=>200),
			array('username', 'unique', 'message' => UserModule::t("El nombre de usuario ingresado ya existe.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Sólo puede utilizar los siguientes caracteres (A-z0-9).")),
			//array('email', 'unique', 'message' => UserModule::t("El email ingresado ya existe.")),
		):array()));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        $relations = Yii::app()->getModule('user')->relations;        
        $relations['authitems'] = array(self::MANY_MANY, 'Authitem', 'authassignment(userid, itemname)');    
		return $relations;
	}
    
    
    public function pivotModels() {
        return array(
            'authitems' => 'Authassignment',            
        );
    } 
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app', 'ID'),
            'nombres' => Yii::t('app', 'Nombres'),
            'ape_paterno' => Yii::t('app', 'Apellido Paterno'),
            'ape_materno' => Yii::t('app', 'Apellido Materno'),
            'email' => Yii::t('app', 'Email'),
            'username' => Yii::t('app', 'Nombre de usuario'),
            'password' => Yii::t('app', 'Contraseña'),
            'create_at' => Yii::t('app', 'Fecha creación'),
            'lastvisit_at' => Yii::t('app', 'última visita'),
            'superuser' => Yii::t('app', 'Es super-usuario'),
            'status' => Yii::t('app', 'Estado'),
            'activkey' => Yii::t('app', 'activkey'),
            'verifyCode'=>'Verificar Código',
            'verifyPassword'=>'Repita su contraseña',
            'authitems'=>'Perfiles'     
		);
	}
	
	public function scopes()
    {
        return array(
            'active'=>array(
                'condition'=>'status='.self::STATUS_ACTIVE,
            ),
            'notactive'=>array(
                'condition'=>'status='.self::STATUS_NOACTIVE,
            ),
            /*'banned'=>array(
                'condition'=>'status='.self::STATUS_BANNED,
            ),*/
            'superuser'=>array(
                'condition'=>'superuser=1',
            ),
            'notsafe'=>array(
            	'select' => 'id, username, password, email, activkey, create_at, lastvisit_at, superuser, status,nombres,ape_paterno,ape_materno',
            ),
        );
    }
	
	public function defaultScope()
    {
        return CMap::mergeArray(Yii::app()->getModule('user')->defaultScope,array(
            'alias'=>'users',
            'select' => 'users.id, users.nombres, users.ape_paterno, users.ape_materno,users.email, users.username, users.create_at, users.lastvisit_at, users.superuser, users.status',
        ));
    }
	
	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'UserStatus' => array(
				self::STATUS_ACTIVE => UserModule::t('Active'),
				self::STATUS_NOACTIVE => UserModule::t('Not active'),
				//self::STATUS_BANNED => UserModule::t('Banned'),
			),
			'AdminStatus' => array(
				'0' => UserModule::t('No'),
				'1' => UserModule::t('Yes'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}
	
/**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
  
    
     public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;
        
        $criteria->compare('id', $this->id);
        $criteria->compare('nombres', $this->nombres, true);
        $criteria->compare('ape_paterno', $this->ape_paterno, true);
        $criteria->compare('ape_materno', $this->ape_materno, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('create_at', $this->create_at, true);
        $criteria->compare('lastvisit_at', $this->lastvisit_at, true);
        $criteria->compare('superuser', $this->superuser);
        $criteria->compare('status', $this->status);
        $criteria->compare('activkey', $this->activkey, true);
		     
        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        	'pagination'=>array(
				'pageSize'=>Yii::app()->getModule('user')->user_page_size,
			),
        ));
    }

        
    
    public function getCreatetime() {
        return strtotime($this->create_at);
    }

    public function setCreatetime($value) {
        $this->create_at=date('Y-m-d H:i:s',$value);
    }

    public function getLastvisit() {
        return strtotime($this->lastvisit_at);
    }

    public function setLastvisit($value) {
        if($value!==null)
            $this->lastvisit_at=date('Y-m-d H:i:s',$value);
        else
            $this->lastvisit_at=null;
    }
    
    public function getNombrecompleto(){
    	return $this->nombres.' '.$this->ape_paterno.' '.$this->ape_materno;
    }
	
	public function getNombrecompletoYTipoUsuario(){
		$tipo="";
		if(isset($this->tipoUsuario)){
			$tipo=" - ".$this->tipoUsuario->tipot_descripcion;
		}
    	return $this->nombres.' '.$this->ape_paterno.' '.$this->ape_materno.$tipo;
    }
	
    public function getNombrePerfilCompleto(){
    	$perfilString="";
        foreach($this->authitems as $perfil){
            $perfilString=$perfil->description;
        }
    	return $this->nombres.' '.$this->ape_paterno.' '.$this->ape_materno.' - Perfil '.$perfilString;
    }

    
    /*public function getComunaAlumno(){
        if(empty($this->comun_cod) || is_null($this->comun_cod)){
            return "";
        }
        $data=Comuna::model()->findByPk($this->comun_cod);
        return $data->comut_descripcion;
    }

    public function getCiudadAlumno(){
        if(empty($this->ciudn_cod) || is_null($this->ciudn_cod)){
            return "";
        }
        $data=Ciudad::model()->findByPk($this->ciudn_cod);
        return $data->ciudt_descripcion;
    }
    
    public function getTipoInstitucionAlumno(){
        if(empty($this->tiesn_cod) || is_null($this->tiesn_cod)){
            return "";
        }
        $data=Ties::model()->find("tiesn_cod='".$this->tiesn_cod."'");
        return $data->tiest_descripcion;
    }
    
    public function getInstitucionAlumno(){
        if(empty($this->iesn_cod) || is_null($this->iesn_cod)){
            return "";
        }
     //   $data=Ies::model()->find("iesn_cod='".$this->iesn_cod."'");
	   $data=Ies::model()->find("tiesn_cod='".$this->tiesn_cod."' AND iesn_cod='".$this->iesn_cod."'");
        return $data->iest_nombre_ies;
    }*/

    
    public function getPerfiles(){
        $datos=false;
        $perfilString="";
        foreach($this->authitems as $perfil){
            if($datos){
                $perfilString.=",";
            }
            $datos=true;
            $perfilString.=$perfil->name;            
        }
        return $perfilString;
    }

    public function getPerfil(){
        return  $this;
    }
    
    public function getUnPerfil(){
        $perfilString="";
        foreach($this->authitems as $perfil){
            $perfilString=$perfil->description;
        }
        return $perfilString;
    }
    

    
    public function confirmarEmail($attribute,$params){        
       if(isset($_POST['email_confirmation'])){
           if($this->email!=$_POST['email_confirmation'])
               if(!$this->hasErrors('email_confirmation'))
                        $this->addError('email_confirmation', $params['message']);
       }        
    }
}

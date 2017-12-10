<?php

class AdminController extends Controller
{
        public $defaultAction = 'admin';
        public $layout='//layouts/main';
       
        private $_model;

        /**
         * @return array action filters
         */
        public function filters()
        {
                return CMap::mergeArray(parent::filters(),array(
                        'accessControl', // perform access control for CRUD operations
                ));
        }
        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         * @return array access control rules
         */
        public function accessRules()
        {
                return array(
                    array('allow', // allow admin user to perform 'admin' and 'delete' actions
                            'actions'=>array('index','admin','delete','create','update','sede'),
                            'roles'=>array('admin'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
                );
        }
        /**
         * Manages all models.
         */
         public function actionIndex()
        {
            $model=new User('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['User']))
                $model->attributes=$_GET['User'];
            $this->render('index',array(
                'model'=>$model,
            ));
        }
        public function actionAdmin()
        {
            $model=new User('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['User']))
                $model->attributes=$_GET['User'];
    
            $this->render('index',array(
                'model'=>$model,
            ));
        }


        /**
         * Displays a particular model.
         */
        public function actionView()
        {
            $model = $this->loadModel();
            $this->render('view',array(
                    'model'=>$model,
            ));
        }
        /**
         * Creates a new model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         */
        public function actionCreate()
        {
                $model=new User;
               // $profile=new Profile;
                
                //RCP $this->performAjaxValidation(array($model,$profile));
                $this->performAjaxValidation(array($model));
                if(isset($_POST['User']))
                {
                        $model->attributes=$_POST['User'];
                        $model->activkey=Yii::app()->controller->module->encrypting(microtime().$model->password);
                        if($model->validate()) {
                                $model->password=Yii::app()->controller->module->encrypting($model->password);
                                if($model->save()) {                                                                        
                                    //.. add checked materia to the alumno
                                    if(isset($_POST['User']['authitems'])){
                                        if(in_array("admin", $_POST['User']['authitems'])){
                                            $model->superuser=1;
                                            $model->save();
                                        }else{
                                            $model->superuser=null;
                                            $model->save();
                                        }
                                        foreach($_POST['User']['authitems'] as $k=>$v){
                                            $userperfiles= new Authassignment;
                                            $userperfiles->itemname= $v;
                                            $userperfiles->userid= $model->id;
                                            $userperfiles->save(false);
                                        }         
                                    }
									echo CHtml::script("$('#cerrarBtnuser').click();afterAjaxSaveSuccess();actualizarGrillauser();");
                                	Yii::app()->end();
                                }
                             
                        }
                }
				$this->layout = '//layouts/iframe';
                $this->render('create',array(
                        'model'=>$model,
                ));
        }

        /**
         * Updates a particular model.
         * If update is successful, the browser will be redirected to the 'view' page.
         */
        public function actionUpdate()
        {
                
                $model=$this->loadModel();
                $this->performAjaxValidation(array($model));
                if(isset($_POST['User']))
                {
                        $model->attributes=$_POST['User'];
                      //  $profile->attributes=$_POST['Profile'];
                        
                        if($model->validate()){
                                $old_password = User::model()->notsafe()->findByPk($model->id);
                                if ($old_password->password!=$model->password) {
                                        $model->password=Yii::app()->controller->module->encrypting($model->password);
                                        $model->activkey=Yii::app()->controller->module->encrypting(microtime().$model->password);
                                }
                               
                                if($model->save(false)){
                                    //..elimiando los UsersCargos                                    
                                    //.. add checked materia to the alumno
                                    if(isset($_POST['User']['authitems'])){
                                    	Authassignment::model()->deleteAll('userid=:userid',array(':userid'=>$model->id));
                                        if(in_array("admin", $_POST['User']['authitems'])){
                                            $model->superuser=1;
                                            $model->save();
                                        }else{
                                            $model->superuser=null;
                                            $model->save();
                                        }
                                        foreach($_POST['User']['authitems'] as $k=>$v){
                                            $userperfiles= new Authassignment;
                                            $userperfiles->itemname= $v;
                                            $userperfiles->userid= $model->id;
                                            $userperfiles->save(false);
                                        }         
                                    }                              
                                }
                                echo CHtml::script("$('#cerrarBtnuser').click();afterAjaxSaveSuccess();actualizarGrillauser();");
                                Yii::app()->end();
                        }
                }
				$this->layout = '//layouts/iframe';
                $this->render('update',array(
                        'model'=>$model,
                ));
        }


        /**
         * Deletes a particular model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         */
        public function actionDelete()
        {
                if(Yii::app()->request->isPostRequest)
                {
                        // we only allow deletion via POST request                        
                        $model = $this->loadModel();
                        $model->delete();
                        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                        if(!isset($_POST['ajax']))
                                $this->redirect(array('/user/admin'));
                }
                else
                        throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
        }
       
        /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($validate)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
        {
            echo CActiveForm::validate($validate);
            Yii::app()->end();
        }
    }
       
     
	 public function actionSede() {
	 	if(isset($_POST['User'])){
            if(isset($_POST['User']['id_institucion'])){
                $id_institucion=$_POST['User']['id_institucion'];
                $data=InstitucionSedePeriodo::model()->with('idSede')->findAll(array('select'=>'idSede.nombre as nombre_sede,t.id_sede','condition'=>'t.id_institucion='.$id_institucion,'group'=>'idSede.nombre,t.id_sede','order'=>'idSede.nombre'));
                $data=CHtml::listData($data,'id_sede','nombre_sede');
				echo CHtml::tag('option', array('value'=>''),'Todas las Sedes',true);
                foreach($data as $value=>$name){
                    echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
                }
            }                    
        }
        Yii::app()->end();
    }
	   
        /**
         * Returns the data model based on the primary key given in the GET variable.
         * If the data model is not found, an HTTP exception will be raised.
         */
        public function loadModel()
        {
                if($this->_model===null)
                {
                        if(isset($_GET['id']))
                                $this->_model=User::model()->notsafe()->findbyPk($_GET['id']);
                        if($this->_model===null)
                                throw new CHttpException(404,'The requested page does not exist.');
                }
                return $this->_model;
        }
       
}

<?php

class LoginController extends Controller
{
    public $defaultAction = 'login';

    /**
     * Displays the login page
     */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
					$this->lastViset();
					if (Yii::app()->user->returnUrl==Yii::app()->request->baseUrl.'/index.php'){
					    $this->redirect(Yii::app()->controller->module->returnUrl);
					}						
					else{
					    $this->redirect(Yii::app()->user->returnUrl);
					}						
				}
			}
			// display the login form
			$this->layout = '//layouts/main';
			$this->render('/user/login',array('model'=>$model));
		} else{
		    $this->redirect(Yii::app()->user->returnUrl);		    
		}			
	}
	
	
    public function actionLoginFromAjax()
    {  
        $model=new UserLogin;
        $resultado='';
        //Debe tener una estructura similar a la siguiente: {"estado":ok,"url":"/a.php","mensaje":"Mensaje de error"}        
        if(isset($_POST['UserLogin']))
        {
            $model->attributes=$_POST['UserLogin'];
            // validate user input and redirect to previous page if valid
            if($model->validate()) {
                $this->lastViset();
                $url="";
                if (Yii::app()->user->returnUrl=='/index.php'){
                    $url=Yii::app()->controller->module->returnUrl;
                }else{
                    $url=Yii::app()->user->returnUrl;
                }
                $resultado='{"estado":"ok","url":"'.$url.'","mensaje":""}';
            }else{
                $resultado='{"estado":"error","url":"","mensaje":"El usuario y/o la contraseña no coinciden."}';
            }
        }else{
            $resultado='{"estado":"error","url":"","mensaje":"El usuario y/o la contraseña no pueden ser nulos."}';
        }
        
        header("Content-type: application/json");
        echo CJSON::encode($resultado);
    }
    
    private function lastViset() {
        $lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
        $lastVisit->lastvisit_at=new CDbExpression('now()');     
        $lastVisit->save(false);		
    }


}

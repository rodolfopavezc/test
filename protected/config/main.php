<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Sistema de alerta de incidencias',
    'language'=>'es',
    'sourceLanguage'=>'00',
	'defaultController'=>'site/',
	//'charset'=>'iso-8859-1',
    'aliases' => array(
        'bootstrap' => 'application.modules.bootstrap'
    ),
	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.controllers.*',        
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'ext.giix-components.*', // giix components
        'application.modules.bootstrap.components.*',        
        'ext.tinymce.*',
        'ext.yii-mail.YiiMailMessage',
        
	),
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'bootstrap' => array(
            'class' => 'bootstrap.BootStrapModule'
        ),
        'coreMessages'=>array(
            'basePath'=>null,
        ),
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			//'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths' => array('ext.giix-core','bootstrap.gii'),
		),
		'user'=>array(
                # encrypting method (php hash function)
                'hash' => 'md5',

                # send activation email
                'sendActivationMail' => true,

                # allow access for non-activated users
                'loginNotActiv' => false,

                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => false,

                # automatically login from registration
                'autoLogin' => false,

                # registration path
                'registrationUrl' => array('/user/registration'),

                # recovery password path
                #'recoveryUrl' => array('/user/recovery'),

                # login form path                
                'loginUrl' =>array('/user/login'),
                # page after login
                'returnUrl' => array('/site'),
                # page after logout
                'returnLogoutUrl' => array('/user/login'),
            ),      
	),
    'preload'=>array('log'),
	// application components
	'components'=>array(        
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'smtp',
            'transportOptions' => array(
                'host' => 'smtp.gmail.com',
                'username' => 'proyectos0884@gmail.com',
                'password' => 'proyectos84',
                'port' => '587',
                'encryption'=>'tls',
            ),
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false
        ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl' => array('/user/login'),
			'class' => 'WebUser',
			'authTimeout' => 3600, //60 seg* 60 min=1 hora
		),
		'BSHtml' => array(
            'class' => 'application.modules.bootstrap.components.BSHtml'
        ),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),				
		'db'=>array(
		    'connectionString' => 'mysql:host=localhost;dbname=bd_alertas',
			'username' => 'root',
		    'password' => 'moroni',
		    'tablePrefix' => '',
		    'emulatePrepare' => true,
		    'charset' => 'utf8',
		),
		
		 'authManager'=>array(
            'class'=>'CDbAuthManager',
            'connectionID'=>'db',
            'itemTable'=>'authitem', // Tabla que contiene los elementos de autorizacion
            'itemChildTable'=>'authitemchild', // Tabla que contiene los elementos padre-hijo
            'assignmentTable'=>'authassignment', // Tabla que contiene la signacion usuario-autorizacion            
        ),        
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		/*'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    //'levels'=>'error, warning,trace, info',
                    'levels'=>'trace',
                ),
                // uncomment the following to show log messages on web pages
                array(
                    'class'=>'CWebLogRoute',
                ),
            ),
        ),*/
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'r.ceballos@tide.cl',
		'defaultPageSize'=>30,
        'pageSizeOptions'=>array(30=>30,50=>50,100=>100),
	),
);
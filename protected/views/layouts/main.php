<?php /* @var $this Controller */ 
header("Content-Type: text/html; charset=".Yii::app()->charset);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset;?>" />
	<meta name="language" content="en" />
    <!--<meta http-equiv="X-UA-Compatible" content="IE=edge">-->
    <meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
    <!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	
	
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->    
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	
	
	 
 
    <?php
    $cs        = Yii::app()->clientScript;
    $themePath = Yii::app()->request->baseUrl;
    
    /**
     * StyleSHeets
     */
    $cs->registerCssFile($themePath . '/css/bootstrap.css');
    $cs->registerCssFile($themePath . '/css/bootstrap-theme.css');
    $cs->registerCssFile($themePath . '/css/datepicker.css');
    $cs->registerCssFile($themePath . '/css/redmond/jquery-ui-1.10.3.custom.min.css');
    
    /**
     * JavaScripts
     */
    $cs->registerCoreScript('jquery', CClientScript::POS_END);
    $cs->registerCoreScript('jquery.ui', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/bootstrap.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath.'/js/bootbox.min.js', CClientScript::POS_END);
    $cs->registerScriptFile($themePath.'/js/codigo_funciones.js', CClientScript::POS_END);
    $cs->registerScript('tooltip', "$('[data-toggle=\"tooltip\"]').tooltip();$('[data-toggle=\"popover\"]').tooltip()", CClientScript::POS_READY);
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

	<div class="header container">
		<div class="well col-xs-12 col-sm-12 col-lg-12 col-md-12">
			<div class="col-xs-8 col-sm-8 col-md-7 col-lg-7">
	    		<h4>Sistema de alerta de incidencias</h4>
	    	</div>
		</div>
    	<!--<div class="img-header"></div>-->    	
    </div>
    <?php
    if(!Yii::app()->user->isGuest){

    ?>
        <div class="container">
            <div class="navbar navbar-default">
                <div>
                    <ul class="nav navbar-nav navbar-left">
                        <li><a href="<?php echo Yii::app()->request->baseUrl;?>/"><span class="glyphicon glyphicon-signal"></span> Reportes</a></li>
                        <li><a href="<?php echo Yii::app()->request->baseUrl;?>/user/admin/index"><span class="glyphicon glyphicon-user"></span> Usuarios</a></li>
                    </ul>
                </div>
                <div>
                    <ul class="nav navbar-nav navbar-right">
                        <li style="padding-top: 15px;">Bienvenido <?php echo Yii::app()->user->name;?> </li>
                        <li style="margin-right: 30px;"><a href="<?php echo Yii::app()->request->baseUrl;?>/user/logout"><span class="glyphicon glyphicon-off"></span> Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    <?php }?>
<div class="wrap">
        <div class="container">
            <div class="row">
			    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			        <?php echo $content; ?>
			    </div>
			</div>
        </div>
    </div>

<input type="hidden" id="urlSitioWeb" value="<?php echo Yii::app()->request->baseUrl;?>"/>

</body>
</html>

<?php /* @var $this Controller */ 
header("Content-Type: text/html; charset=".Yii::app()->charset);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset;?>" />
	<meta name="language" content="en" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->    
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/codigo_funciones.js"></script>
    <?php
    $cs        = Yii::app()->clientScript;
    $themePath = Yii::app()->request->baseUrl;
    
    /**
     * StyleSHeets
     */
    $cs->registerCssFile($themePath . '/css/bootstrap.css');
    $cs->registerCssFile($themePath . '/css/bootstrap-theme.css');
    
    /**
     * JavaScripts
     */
    $cs->registerCoreScript('jquery', CClientScript::POS_END);
    $cs->registerCoreScript('jquery.ui', CClientScript::POS_END);
    $cs->registerScriptFile($themePath . '/js/bootstrap.min.js', CClientScript::POS_END);
    $cs->registerScript('tooltip', "$('[data-toggle=\"tooltip\"]').tooltip();$('[data-toggle=\"popover\"]').tooltip()", CClientScript::POS_READY);
    ?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
    <?php 
    $cs        = Yii::app()->clientScript;
    $themePath = Yii::app()->request->baseUrl;
    if(!Yii::app()->user->isGuest){
        if(!Yii::app()->user->checkAccess("alumno")){
          $cs->registerScriptFile($themePath . '/js/codigo_funcionesAdmin.js', CClientScript::POS_END);
        }        
    }   
    ?> 
<input type="hidden" id="urlSitioWeb" value="<?php echo Yii::app()->request->baseUrl;?>"/>

<?php echo $content; ?>
<div class="clear"></div>


</body>
</html>

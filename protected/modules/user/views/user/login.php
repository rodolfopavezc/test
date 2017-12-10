<?php

$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
?>

   <script>
   	var isInIframe = (window.location != window.parent.location) ? true : false;
    if(isInIframe==true){
        	document.body.style.display = "none";
    		window.parent.location.reload();
	}
   </script>
    <div class="well text-center col-lg-12 col-md-12">
                
                <H3>Sistema de alerta de incidencias</H3>

    
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="col-lg-1 col-md-1"></div>
        <div class="col-lg-6 col-md-6">
            <br/><br/>
            <div align="center" style="font-size: 18px;">
                 <strong>¡Bienvenido(a)!</strong>
            </div>
            <br><br>
            <div style="text-align:justify;">                                        
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas nisl purus, elementum quis tincidunt at, accumsan quis sapien. Nunc tristique leo semper sollicitudin mattis. Nullam tincidunt leo ligula, at aliquet enim facilisis et. Proin gravida, turpis a rhoncus vehicula, ipsum felis tincidunt metus, vitae consequat tellus nulla vel lorem. Mauris sit amet ex a turpis varius ullamcorper. Vestibulum ultrices metus et diam vestibulum posuere. Aliquam malesuada tortor eget viverra malesuada. Quisque sapien erat, maximus non faucibus id, lobortis non est. Nulla lobortis augue sed hendrerit ultricies. Duis eu pharetra nulla. 
			</div>           
       		<br/>
        </div>
        <div class="col-lg-1 col-md-1"></div>
        <div class="col-lg-4 col-md-4 text-center">
        	
				<div class="panel panel-default">
					<div class="panel-heading">
						<strong> Para ingresar, digita tu nombre de usuario y tu contraseña</strong>
					</div>
					<div class="panel-body">
						<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>
		                    <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
		                <?php endif; ?>
		                
		                <?php echo CHtml::beginForm(); ?>    
	                    
						<form role="form" action="#" method="POST">
							<fieldset>								
								<div class="row">
									<div class="col-sm-12 col-md-10  col-md-offset-1 ">
										<div class="form-group">
											<?php echo CHtml::errorSummary($model); ?> 
										</div>										 
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-user"></i>
												</span>
												<?php echo CHtml::activeTextField($model,'username',array('class'=>'form-control','placeholder'=>'Nombre de usuario','autofocus'=>'')) ?>
											</div>
										</div>
										<div class="form-group">
											<div class="input-group">
												<span class="input-group-addon">
													<i class="glyphicon glyphicon-lock"></i>
												</span>												
												<?php echo CHtml::activePasswordField($model,'password',array('class'=>'form-control','placeholder'=>'Contraseña')) ?>
											</div>
										</div>
										<div class="form-group">
											<?php echo CHtml::submitButton(UserModule::t("ACCEDER"),array('class'=>'btn btn-primary btn-block')); ?>
										</div>
									</div>
								</div>
							</fieldset>
						</form>
	                    <br/>
	                                    
	                	<?php echo CHtml::endForm(); ?>
					</div>
					<div class="panel-footer ">
						
					</div>
                </div>
			
        	

        </div>
    </div>  
    

    
<?php
$form = new CForm(array(
    'elements'=>array(
        'username'=>array(
            'type'=>'text',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        )
    ),
    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'Login',
        ),
    ),
), $model);
?>
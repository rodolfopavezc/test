<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Registration");

?>



<?php if(Yii::app()->user->hasFlash('registration')): ?>
<div class="well" style="font-size: 20px; font-weight: bold; margin: 80px; text-align: center;">
<?php echo Yii::app()->user->getFlash('registration'); ?>
</div>

<?php echo CHtml::link('<span class="glyphicon glyphicon-arrow-left"></span> Volver',array('/user/login'), array('class'=>'btn btn-primary')); ?>        

<?php else: ?>

<div class="form">
<?php $form=$this->beginWidget('UActiveForm', array(
	'id'=>'registration-form',
	//'enableAjaxValidation'=>true,
	'disableAjaxValidationAttributes'=>array('RegistrationForm_verifyCode'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array('enctype'=>'multipart/form-data','class'=>'form-horizontal'),
)); ?>

	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
	
	<?php echo $form->errorSummary(array($model)); ?>
	<div class="well">
	    <h3>Registro</h3>
	    <div class="row">
	        <?php echo $form->labelEx($model,'rut',array('class'=>'control-label col-lg-2 col-md-2 col-xs-2')); ?>
            <div class="col-lg-4 col-md-4 col-xs-4">  
                <div class="row">          
                    <div class="col-lg-8 col-md-6 col-xs-6">
                        <?php echo $form->textField($model, 'rut',array('maxlength' => 8,'class'=>'form-control'));?>
                        <?php echo $form->error($model,'rut'); ?>
                    </div>
                    <div class="col-lg-1 col-md-1 col-xs-1">
                        <label class="control-label">-</label>
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-4">                
                          <?php echo $form->textField($model, 'dv', array('maxlength' => 1,'class'=>'form-control')); ?>   
                    </div>
                </div>
            </div>
        </div>
        <div class="row">      
            <?php echo $form->labelEx($model,'nombres',array('class'=>'control-label col-lg-2 col-md-2 col-xs-2')); ?>
            <div class="col-lg-4 col-md-4 col-xs-4">
                    <?php echo $form->textField($model, 'nombres', array('maxlength' => 255,'class'=>'form-control')); ?>
                    <?php echo $form->error($model,'nombres'); ?>
            </div>
        </div>
         <div class="row">  
             <?php echo $form->labelEx($model,'ape_paterno',array('class'=>'control-label col-lg-2 col-md-2 col-xs-2')); ?>       
            <div class="col-lg-4 col-md-4 col-xs-4">
                        <?php echo $form->textField($model, 'ape_paterno', array('maxlength' => 255,'class'=>'form-control')); ?>
                        <?php echo $form->error($model,'ape_paterno'); ?>
            </div>
            <?php echo $form->labelEx($model,'ape_materno',array('class'=>'control-label col-lg-2 col-md-2 col-xs-2')); ?>
             <div class="col-lg-4 col-md-4 col-xs-4">                
                <?php echo $form->textField($model, 'ape_materno', array('maxlength' => 255,'class'=>'form-control')); ?>
                <?php echo $form->error($model,'ape_materno'); ?>
            </div>
        </div>
        <div class="row">
            <?php echo $form->labelEx($model,'email',array('class'=>'control-label col-lg-2 col-md-2 col-xs-2')); ?>
             <div class="col-lg-4 col-md-4 col-xs-4">
                <?php echo $form->textField($model,'email',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'email'); ?>
            </div> 
        </div>     
        <div class="row">
            <?php echo $form->labelEx($model,'password',array('class'=>'control-label col-lg-2 col-md-2 col-xs-2')); ?>
            <div class="col-lg-4 col-md-4 col-xs-4">
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'password'); ?>
                <p class="hint">
                    <?php echo UserModule::t("Minimal password length 4 symbols."); ?>
                </p>
            </div>
            <?php echo $form->labelEx($model,'verifyPassword',array('class'=>'control-label col-lg-2 col-md-2 col-xs-2')); ?>
            <div class="col-lg-4 col-md-4 col-xs-4">
                <?php echo $form->passwordField($model,'verifyPassword',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'verifyPassword'); ?>
            </div>
        </div>
        
         <div class="row">&nbsp;</div>  
        <?php if (UserModule::doCaptcha('registration')): ?>
        	<div class="row">
        		
        		<div class="col-lg-8 col-md-8 col-xs-8">
        			Por favor, digite el siguiente código tal como se muestra en la imagen. 
        		</div>
        	</div>
            <div class="row">
            	<div class="col-lg-2 col-md-2 col-xs-2"></div>
                <?php //echo $form->labelEx($model,'verifyCode',array('class'=>'control-label col-lg-2 col-md-2 col-xs-2')); ?>
                <div class="col-lg-4 col-md-4 col-xs-4">
                    <?php $this->widget('CCaptcha'); ?>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-4">
                    <?php echo $form->textField($model,'verifyCode',array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'verifyCode'); ?>
                 </div>   
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-2 col-xs-2"></div>
                <div class="col-lg-10 col-md-10 col-xs-10">
                	<p class="hint"><?php echo UserModule::t("Letters are not case-sensitive."); ?></p>
                      
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="well text-center">        
            <div class="row submit">                
                <input type="submit" class="btn btn-primary" value="Registrar" name="yt0">
            </div>
            <div class="text-left">
		    <?php 
		    	
		    	echo CHtml::link('<span class="glyphicon glyphicon-arrow-left"></span> Volver',array('/'), array('class'=>'btn btn-primary'));
		    
		    ?>
		</div>
	</div>
	

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>
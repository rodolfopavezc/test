<br/><br/>
<h2>¿Olvidaste tu contraseña?</h2>

<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
<div class="well" style="font-size: 20px; font-weight: bold; margin: 80px; text-align: center;">
<?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
</div>
<?php echo CHtml::link('<span class="glyphicon glyphicon-arrow-left"></span> Volver',array('/user/login'), array('class'=>'btn btn-primary')); ?>        

<?php else: ?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

	<?php echo CHtml::errorSummary($form); ?>
	<div class="col-lg-3 col-md-3">&nbsp;</div>
	<div class="row well col-lg-6 col-md-6">
		<div class="row">
			<label>Por favor digite su nombre de usuario.</label>
			<?php echo CHtml::activeTextField($form,'login_or_email',array('class'=>'form-control','placeholder'=>'RUT sin puntos ni guión')) ?>
			
		</div>
	</div>
	<br/>
	<div class="row text-center col-lg-12 col-md-12">        
            <div class="row submit">                
                <input type="submit" class="btn btn-primary" value="Enviar" name="yt0">
            </div>
            <div class="text-left">
		    <?php 
		    	
		    	echo CHtml::link('<span class="glyphicon glyphicon-arrow-left"></span> Volver',array('/'), array('class'=>'btn btn-primary'));
		    
		    ?>
			</div>
	</div>
<?php echo CHtml::endForm(); ?>
</div><!-- form -->
<?php endif; ?>
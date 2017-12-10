<br/><br/>
<h2>Restablecer la contraseña</h2>


<div class="form">
<?php echo CHtml::beginForm(); ?>

	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
	<?php echo CHtml::errorSummary($form); ?>
	<div class="col-lg-3 col-md-3">&nbsp;</div>
	<div class="row well col-lg-6 col-md-6">
		<div class="row">
			<?php echo CHtml::activeLabelEx($form,'password'); ?>
			<?php echo CHtml::activePasswordField($form,'password',array('class'=>'form-control')); ?>
		</div>
		<div class="row">
			<?php echo CHtml::activeLabelEx($form,'verifyPassword'); ?>
			<?php echo CHtml::activePasswordField($form,'verifyPassword',array('class'=>'form-control')); ?>
		</div>
		<p class="hint">
			<?php echo UserModule::t("Minimal password length 4 symbols."); ?>
		</p>
	</div>
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
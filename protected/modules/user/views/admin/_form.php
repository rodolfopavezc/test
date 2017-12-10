<?php
Yii::app()->clientScript->registerScript('disenio', "
    $(document).ready(function() {
        $('.perfiles').change(function(){
             if($(this).is(':checked')){
                 if($(this).val()=='admin'){
                     $('input[value!=\"admin\"]').attr('checked', false);
                 }else{
                     $('input[value=\"admin\"]').attr('checked', false);
                 }                 
             } 
        });
        
        if($('.perfiles[value=\"admin\"]').is(':checked')){
            $('input[value!=\"admin\"]').attr('checked', false);
        }
    });
");

$form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
    'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype'=>'multipart/form-data'),
));
?>

<div class="form">


    
	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<?php 
	   //RCP echo $form->errorSummary(array($model,$profile));
	   echo $form->errorSummary(array($model));
	   $disabledKey="";
	   $disabledValue="";
	   $disabledString="";
	   
	   
	?>
	
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2"><?php echo $form->labelEx($model,'username'); ?></div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-4">
				<?php echo $form->textField($model,'username',array('maxlength'=>20,'class'=>'form-control',$disabledKey=>$disabledValue)); ?>
                <?php echo $form->error($model,'username'); ?>
         </div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2"><?php echo $form->labelEx($model,'password'); ?></div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-4">
			<?php echo $form->passwordField($model,'password',array('maxlength'=>128,'class'=>'form-control',$disabledKey=>$disabledValue)); ?>
                <?php echo $form->error($model,'password'); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2"><?php echo $form->labelEx($model,'email'); ?></div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-4">
			<?php echo $form->textField($model,'email',array('class'=>'form-control',$disabledKey=>$disabledValue)); ?>
                <?php echo $form->error($model,'email'); ?>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2"><?php echo $form->labelEx($model,'status'); ?></div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-4">
			<?php echo $form->dropDownList($model,'status',User::itemAlias('UserStatus'),array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'status'); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2"><?php echo $form->labelEx($model,'nombres'); ?></div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-4">
			<?php echo $form->textField($model,'nombres',array('maxlength'=>128,'class'=>'form-control',$disabledKey=>$disabledValue)); ?>
        <?php echo $form->error($model,'nombres'); ?>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2"><?php echo $form->labelEx($model,'ape_paterno'); ?></div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-4">
			<?php echo $form->textField($model,'ape_paterno',array('maxlength'=>128,'class'=>'form-control',$disabledKey=>$disabledValue)); ?>
        <?php echo $form->error($model,'ape_paterno'); ?>
		</div>
	</div>
	

	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-2"><?php echo $form->labelEx($model,'ape_materno'); ?></div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-4">
			<?php echo $form->textField($model,'ape_materno',array('maxlength'=>128,'class'=>'form-control',$disabledKey=>$disabledValue)); ?>
        <?php echo $form->error($model,'ape_materno'); ?>
		</div>
	</div>
	
	 <tr>
            <td align="right" width="150px"></td>
         <td>
         	
          </td>
            
            <td align="right"> </td>
            <td >   
            	
            </td>
        </tr>
	
	
	
 </div><!-- form -->   
 

	<div class=" rowright47">
              <h3><?php echo $form->labelEx($model,'authitems'); ?></h3>
              <?php echo $form->error($model,'authitems'); ?>

            <table class="items table table-striped">
            <thead>
              <tr><th width="70%">Perfiles</th><th>Acción</th></tr>
            </thead>
        <tbody>  
              
              <?php 
              $arrayPerfiles = CHtml::listData(Authitem::model()->findAll(), 'name', 'description');
              $arraySelectedPerfiles=CHtml::listData($model->authitems,'name','description');
              if(isset($_POST['User']['authitems'])){
                  $arraySelectedPerfiles=$_POST['User']['authitems'];
              }
              //echo $form->checkBoxList($model, 'authitems', $arrayPerfiles, array('class'=>'perfiles','separator'=>'','template'=>'<tr class="even"><td>{label}</td><td>{input}</td></tr>'));
              $x=0;   
                         
              foreach($arrayPerfiles as $k=>$v):
                  
                  echo "<tr>";
                  echo "<td><label for=\"User_authitems_".$x."\">".ucfirst($v)."</label></td>";
                  $disabledString=($k=='codebase')?"disabled='disabled'":"";
                  if (array_key_exists($k, $arraySelectedPerfiles) || in_array($k, $arraySelectedPerfiles)) {
                     echo "<td><input type=\"radio\" ".$disabledString." class=\"perfiles\" name=\"User[authitems][]\" checked=\"checked\" value=\"".$k."\" id=\"User_authitems_".$x."\"></td>";     
                  }else{
                      echo "<td><input type=\"radio\" ".$disabledString." class=\"perfiles\" name=\"User[authitems][]\" value=\"".$k."\" id=\"User_authitems_".$x."\"></td>";
                  }
                  echo "</tr>";
              $x++; endforeach; 
              
              ?>     
     </tbody>
     
            </table>
    </div>
    
    <div class="limpia"></div>
    
<?php
if($model->getPrimaryKey())
    $url = Yii::app()->request->baseUrl.'/user/admin/update/id/'.$model->getPrimaryKey();
else 
    $url = Yii::app()->request->baseUrl.'/user/admin/create';
echo CHtml::ajaxSubmitButton('ButtonName',$url,
    array(
        'type'=>'POST',
         'data'=>'js:$("#'.$form->id.'").serialize()', 
         'beforeSend'=>'function (){
             beforeAjaxUpdateSuccess();
         }',
         'success'=>'function(data){
                afterAjaxUpdateSuccess();         
                $("#modalEditar1user").html(data);
          }'         
    ),array('class'=>'someCssClass','id'=>'saveBtnuser','hidden'=>'true'));
$this->endWidget();
?>



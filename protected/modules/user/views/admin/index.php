<script type="text/javascript">

function actualizarGrillauser(){    
    $.ajax({             
            url: $('#urlSitioWeb').val()+'/user/admin/', 
            success: function(data) {  
                $(".formusers").html($(data).find('div#formusers').html());
                $('.loader_message').remove();
                }
            });
    mensajeCargando(); 
    
}
</script>

<?php 

$pageSize=(isset($_GET['pageSize']))?$_GET['pageSize']:Yii::app()->params['defaultPageSize'];//Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
    
    if(Yii::app()->user->hasFlash('success')){        
        Yii::app()->clientScript->registerScript('init',
            "mensajeModal('".Yii::app()->user->getFlash('success')."','Mensaje de Confirmación')"
       );
    }

?>
<br/>
<div class="page-header"><h3>Administrador de Usuarios</h3></div>
<div class="">
    <?php $form = $this->beginWidget('bootstrap.widgets.BsActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'layout' => BSHtml::FORM_LAYOUT_HORIZONTAL,
    )); ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><strong>Filtros</strong></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-6 col-md-2"><?php echo $form->label($model, 'username'); ?></div>
                            <div class="col-xs-6 col-md-2"><?php echo $form->textField($model, 'username', array('maxlength' => 65,'class'=>'form-control')); ?></div>
                            <div class="col-xs-6 col-md-2 col-md-offset-2"><label>Perfil:</label></div>
                            <div class="col-xs-6 col-md-2"><select class="form-control"><option value="" selected>Todas</option><option value="administrador">Administrador</option><option value="codebase">Codebase</option></select></div>
                            <div class="col-xs-6 col-md-2"><?php echo BSHtml::submitButton('Buscar',  array('color' => BSHtml::BUTTON_COLOR_PRIMARY,));?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php $this->endWidget(); ?>
</div>
<div class="panel panel-default">
    <div class="panel-heading text-right">
        
        <?php echo CHtml::button("Nuevo Usuario", array('class'=>'btn btn-primary',
        'data-toggle'=>'modal','data-target'=>'#modalEditaruser',
        'onclick' => ' 
            
            $("#modalEditar1user").html("<p style=\"text-align:center;\"><img src=\"../images/loading.gif\" alt=\"loading\" class=\"loading\"></p>");

         $.ajax({

                url: "'.Yii::app()->request->baseUrl.'/user/admin/create",
                success: function(data) {
                    $("#modalEditar1user").html(data);
                }
        });' )); ?>
        
                
    </div>
    <div id="formusers" class="panel-body formusers" >
<?php
$this->widget('bootstrap.widgets.BsGridView',array(
    'id'=>'user-grid',
    'dataProvider'=>$model->search(),
    'summaryText'=>'Desplegando {start}-{end} de {count} resultado(s).',
    'beforeAjaxUpdate'=>'function(id, data){mensajeCargando();}',
    'afterAjaxUpdate'=>'function(id, data){ocultarMensajeCargando()}',
    'template' => "{summary}\n{items}\n{pager}",
    'type' => BSHtml::GRID_TYPE_STRIPED,
     
    'columns'=>array(
        array('header'=>'N°',
                'htmlOptions'=>array('width'=>'50'),
                'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
            ),
         array(
            'header'=>'Usuario',
            'type'=>'raw',
            'value'=>'$data->username',
        ),array(
            'header'=>'Nombre',
            'type'=>'raw',
            'value'=>'$data->NombreCompleto',
        ),
        array(
            'header'=>'Perfil',
            'type'=>'raw',
            'value'=>'$data->UnPerfil',
        ),
        array(
            'header'=>'Email',
            //'name'=>'email',
            'type'=>'raw',
            'value'=>'CHtml::link(UHtml::markSearch($data,"email"), "mailto:".$data->email)',
        ),      
         array(
            'header'=>'última visita',
            'type'=>'raw',
            'value'=>'Yii::app()->dateFormatter->formatDateTime($data->lastvisit_at,"medium")',
        ),
        array(
            'header'=>'Estado',
            //'name'=>'status',
            'value'=>'User::itemAlias("UserStatus",$data->status)',
            'filter' => User::itemAlias("UserStatus"),
        ),array(
        'htmlOptions'=>array('width'=>'80'),
           'class'=>'CButtonColumn',
           'deleteButtonImageUrl'=>false,
           'deleteButtonLabel'=>'Eliminar',
           'template'=>'{editar}{delete}',            
           'buttons'=>array
        (
        
                'editar' => array
                (
                        'label'=>' <span class="glyphicon glyphicon-pencil"></span> ',
                        'options'=>array("data-toggle"=>"modal",'data-target'=>'#modalEditaruser', "title"=>"Editar"),
                        'click'=>'function(){
                         var id = $.fn.yiiGridView.getKey( "user-grid", $(this).closest("tr").prevAll().length);
                         $("#modalEditar1user").html("<p style=\"text-align:center;\"><img src=\"'.Yii::app()->request->baseUrl.'/images/loading.gif\" alt=\"loading\" class=\"loading\"></p>");
                        
                         $.ajax({       
                                url: $("#urlSitioWeb").val()+"/user/admin/update/id/"+id,                                
                                success: function(data) {                                	
                                    $("#modalEditar1user").html(data);
                                }
                        });
                        }',
                        'url'=>'"#"',
                ),
                'delete' => array
                (
                        
                        'label'=>'<i class="glyphicon glyphicon-remove"></i> ',
                        'visible'=>'($data->id!=1)',
                        //'options'=>array("title"=>"Borrar"),
                       'options'=>array("class"=>"delete", "title"=>"Eliminar","data-toggle"=>"tooltip"),
                        'ImageUrl'=>'',
                        'click'=>'function(){
                          var id = $.fn.yiiGridView.getKey( "user-grid", $(this).closest("tr").prevAll().length);
                          bootbox.confirm("¿Seguro que desea borrar al usuario?", function(r) {
                                if(r){
                                    mensajeCargando();
                                     $.ajax({                
                                            url: $("#urlSitioWeb").val()+"/user/admin/delete/id/"+id,
                                            type: "POST",
                                            success: function(data) {
                                                actualizarGrillauser();
                                            }
                                    });                                    
                                }//End IF
                            });
                         
                        }',
                        'url'=>'"#"',
                ),
                
        ),
        ),
        /*array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'header'=>'Opciones',
            'template' => '{view}{update}{delete}',
            'afterDelete'=>'function(link,success,data){if(success)mostrarMensajes(data); }',
            'buttons' => array(
                'view' => array(                    
                    'options'=>array(
                        'class'=>'btn-small view'
                    )
                ),
                'update' => array(                    
                    'options'=>array(
                        'class'=>'btn-small update'
                    )
                ),
                'delete' => array(                    
                    'options'=>array(
                        'class'=>'btn-small delete'
                    )
                )
            ),
            'htmlOptions'=>array('style'=>'width: 80px'),
        ),*/
    ),
)); ?>
<div class="wrapper" style="text-align: right;">
         
</div>
 <!-- Event Modal -->
<div id="modalVeruser" class="modal fade">
    <div class="modal-dialog" style="width:960px">
        <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" id="modalVer1user">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                  
                </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Event modal -->

 <!-- Event Modal -->
<div id="modalEditaruser" class="modal fade">
    <div class="modal-dialog"  style="width:80%">
        <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" id="modalEditar1user">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="cerrarBtnuser">Cerrar</button>
                    <button type="button" class="btn btn-primary" data-loading-text="Guardando..." onClick="$('#saveBtnuser').click();">Guardar</button>
                   
                </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Event modal -->
</div>
</div>
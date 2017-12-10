function buscarRut(){
	parent.asociarAlumnoAPregunta();
	if(validarut($('#persona_rut').val())){
		parent.mensajeCargando();
		$.getJSON($('#urlSitioWeb').val()+'/personas/searchPerson/'+limpiarRut($('#persona_rut').val()), function(data){			
			if(typeof(data.rut)!="undefined"){
				parent.mensajeCargando();
				parent.asociarAlumnoAPregunta(data.rut);
				parent.$("#iframePersona").attr("src", $('#urlSitioWeb').val()+'/personas/updatePerson/'+data.rut);				
			}else{
				$('.form-control,#enviarForm,.enviarForm,.file-input').attr('disabled','disabled');
				contentEditableTinyMCE(false);
				$('#persona_rut').removeAttr('disabled');
				//DEBEMOS VALIDAR SI EL USUARIO QUIERE CREAR AL ALUMNO
				var confirmModal = 
				      $('<div class="modal fade">' +
				    	  '<div class="modal-dialog">' +	
				    	  	  '<div class="modal-content">' +
						          '<div class="modal-header">' +
						            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
						            '<h4 class="modal-title">Mensaje de Confirmación</h4>' +
						          '</div>' +
						
						          '<div class="modal-body text-center">' +
						            '<p>El RUT ingresado no tiene registros asociados.<br/> ¿Desea crear el registro para el usuario con RUT '+$('#persona_rut').val()+' ?</p>' +
						          '</div>' +						
						          '<div class="modal-footer">' +
						            '<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>' +
						            '<button id="okButton" type="button" class="btn btn-primary">Aceptar</button>' +
						          '</div>' +
						      '</div>' +
				          '</div>' +
				        '</div>');

				    confirmModal.find('#okButton').click(function(event) {
				    	parent.mensajeCargando();
				    	//parent.asociarAlumnoAPregunta(limpiarRut($('#persona_rut').val()));				    	
				    	parent.$("#iframePersona").attr("src", $('#urlSitioWeb').val()+'/personas/updatePerson/'+limpiarRut($('#persona_rut').val()));
				    	//$('#enviarForm').html('Crear');
				    	//$('.form-control,#enviarForm,.enviarForm,.file-input').removeAttr('disabled');
				    	confirmModal.modal('hide');
				    });
				    confirmModal.modal('show');
			}
			parent.ocultarMensajeCargando();
		});
	}else{
		$('#errorRut').html('<span class="control-label">El rut ingresado no es valido</span>');		
	}
}

function validarRutReporte(){
	if(validarut($('#persona_rut').val())){
		mensajeCargando();
		$.getJSON($('#urlSitioWeb').val()+'/personas/searchPerson/'+limpiarRut($('#persona_rut').val()), function(data){			
			if(typeof(data.rut)!="undefined"){
				$('#reporte-form').submit();								
			}else{
				mensajeParaMantenedor('El rut ingresado no existe','alert-danger');
			}
			ocultarMensajeCargando();
		});
	}else{
		$('#errorRut').html('El rut ingresado no es valido');
	}
}


function asociarAlumnoAPregunta(idUser){
	$('.form-control,#enviarForm,.enviarForm.file-input').attr('disabled','disabled');
	contentEditableTinyMCE(false);
	$('#Preguntas_rutan_cod').val(idUser);
	if(typeof(idUser)!="undefined"){
		$('.form-control,#enviarForm,.enviarForm,.file-input').removeAttr('disabled');
		contentEditableTinyMCE(true);
	}
}

function enviarMensajeYRespuesta(idForm){
	var confirmacionMensaje='Las consultas y respuestas enviadas no se pueden modificar posteriormente.<br/> ¿Desea continuar con la operación?';
	var firmaConRespuesta=true;
	
	if($("#nombre_usuario_responsable").val()!="" && $("#nombre_usuario_responsable").length>0){		
		var txtFirma=$("#nombre_usuario_responsable").val();
		var txtRespuesta=$('#Preguntas_pregt_respuesta').val();
		txtRespuesta=txtRespuesta.replace(txtFirma,"").replace("Comisión Ingresa","");
		txtRespuesta=txtRespuesta.replace("\n","").replace(/\s/g,'');	
		txtRespuesta=$(txtRespuesta).text();	
		if(txtRespuesta.length==0){
			firmaConRespuesta=false;
		}
	}
	
	if($('#Preguntas_pregt_respuesta').val()!="" && firmaConRespuesta){
		$('#mensajeyrespuesta').val(1);//Lo dejamos en 1 para poder recibir la seleccion del usuario.
		$('#postearrespuesta').val(0);
		confirmSubmit(idForm,confirmacionMensaje);
	}else{
		$('#Preguntas_pregt_respuesta').addClass('error');
		$('#Preguntas_pregt_respuesta').parent().children('.help-inline').remove();
		$('#Preguntas_pregt_respuesta').parent().prev('.control-label').addClass('error');
		$('#Preguntas_pregt_respuesta').parent().append('<span class="help-inline">Respuesta no puede ser nulo.</span>');
	}
}

function enviarRespuesta(idForm){
	var confirmacionMensaje='La respuesta enviada no podrá ser modificada posteriormente. <br/>¿Desea continuar con la operación?';
	var firmaConRespuesta=true;	
	if($("#nombre_usuario_responsable").val()!="" && $("#nombre_usuario_responsable").length>0){		
		var txtFirma=$("#nombre_usuario_responsable").val();
		var txtRespuesta=$('#Preguntas_pregt_respuesta').val();
		txtRespuesta=txtRespuesta.replace(txtFirma,"").replace("Comisión Ingresa","");
		txtRespuesta=txtRespuesta.replace("\n","").replace(/\s/g,'');	
		txtRespuesta=$(txtRespuesta).text();	
		if(txtRespuesta.length==0){
			firmaConRespuesta=false;
		}
	}
	if($('#Preguntas_pregt_respuesta').val()!="" && firmaConRespuesta){
		$('#guardarespuesta').val(0);//Lo dejamos en 0 para asignar respuesta definitiva a la pregunta.
		$('#postearrespuesta').val(0);
		confirmSubmit(idForm,confirmacionMensaje);
	}else{
		$('#Preguntas_pregt_respuesta').addClass('error');
		$('#Preguntas_pregt_respuesta').parent().children('.help-inline').remove();
		$('#Preguntas_pregt_respuesta').parent().prev('.control-label').addClass('error');
		$('#Preguntas_pregt_respuesta').parent().append('<span class="help-inline">Respuesta no puede ser nulo.</span>');
	}
}

function enviarPost(idForm){
	var confirmacionMensaje='Se creará un nuevo evento o posteo con el texto ingresado en la zona de respuesta. El texto no podrá ser modificado posteriormente y pasará a estar disponible para la visualización del alumno. <br/> La pregunta aún estará disponible para ser respondida más adelante. <br/>¿Desea continuar con la operación?';
	var firmaConRespuesta=true;	
	if($("#nombre_usuario_responsable").val()!="" && $("#nombre_usuario_responsable").length>0){		
		var txtFirma=$("#nombre_usuario_responsable").val();
		var txtRespuesta=$('#Preguntas_pregt_respuesta').val();
		txtRespuesta=txtRespuesta.replace(txtFirma,"").replace("Comisión Ingresa","");
		txtRespuesta=txtRespuesta.replace("\n","").replace(/\s/g,'');
		txtRespuesta=$(txtRespuesta).text();		
		if(txtRespuesta.length==0){
			firmaConRespuesta=false;
		}
	}
	if($('#Preguntas_pregt_respuesta').val()!="" && firmaConRespuesta){
		$('#guardarespuesta').val(1);
		$('#postearrespuesta').val(1);//Lo dejamos en 1 para ingresar el post
		confirmSubmit(idForm,confirmacionMensaje);
	}else{
		$('#Preguntas_pregt_respuesta').addClass('error');
		$('#Preguntas_pregt_respuesta').parent().children('.help-inline').remove();
		$('#Preguntas_pregt_respuesta').parent().prev('.control-label').addClass('error');
		$('#Preguntas_pregt_respuesta').parent().append('<span class="help-inline">Post no puede ser nulo.</span>');
	}
}


function cerrarPregunta(idForm){
	var confirmacionMensaje='La pregunta pasará a un estado de "Extemporánea Fuera de Plazo" y se archivará sin respuesta. La pregunta no podrá ser modificada posteriormente. <br/>¿Desea continuar con la operación?';
	var firmaConRespuesta=true;	
	$('#cerrarpregunta').val(1);//Lo dejamos en 1 para poder cerrar la pregunta.
	confirmSubmit(idForm,confirmacionMensaje);
}



function eliminarArchivoAdjuntoRespuesta(){
	
    var confirmModal = 
      $('<div class="modal fade">' +
    	  '<div class="modal-dialog">' +	
    	  	  '<div class="modal-content">' +
		          '<div class="modal-header">' +
		            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
		            '<h4 class="modal-title">Mensaje de confirmación</h4>' +
		          '</div>' +
		
		          '<div class="modal-body text-center">' +
		            '<p>¿Está seguro de eliminar el archivo adjunto?</p>' +
		          '</div>' +
		
		          '<div class="modal-footer">' +
		            '<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>' +
		            '<button id="okButton" type="button" class="btn btn-primary">Aceptar</button>' +
		          '</div>' +
		      '</div>' +
          '</div>' +
        '</div>');

    confirmModal.find('#okButton').click(function(event) {
      $('#deleteFile').remove();
      //Agregar bandera a formulario
      $('#preguntas-form').append('<input type="hidden" value="1" name="Preguntas[eliminararchivorespuesta]"/>');
      confirmModal.modal('hide');
    });
    confirmModal.modal('show'); 
}


function eliminarAsociacion(idAtrr){
	var confirmModal = 
      $('<div class="modal fade">' +
    	  '<div class="modal-dialog">' +	
    	  	  '<div class="modal-content">' +
		          '<div class="modal-header">' +
		            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
		            '<h4 class="modal-title">Mensaje de confirmación</h4>' +
		          '</div>' +
		
		          '<div class="modal-body text-center">' +
		            '<p>¿Está seguro de eliminar su asociación con la pregunta?</p>' +
		          '</div>' +
		
		          '<div class="modal-footer">' +
		            '<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>' +
		            '<button id="okButton" type="button" class="btn btn-primary">Aceptar</button>' +
		          '</div>' +
		      '</div>' +
          '</div>' +
        '</div>');
	var hrefLink=$(idAtrr).attr('href');
    confirmModal.find('#okButton').click(function(event) {
      document.location.href=hrefLink;
      confirmModal.modal('hide');
    });
    confirmModal.modal('show');
    return false;
}

function devolverAdministrador(idAtrr){
	var confirmModal = 
      $('<div class="modal fade">' +
    	  '<div class="modal-dialog">' +	
    	  	  '<div class="modal-content">' +
		          '<div class="modal-header">' +
		            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
		            '<h4 class="modal-title">Mensaje de confirmación</h4>' +
		          '</div>' +
		
		          '<div class="modal-body text-center">' +
		            'En caso de que el RUT del alumno tenga más consultas que se encuentren actualmente sin responder, serán derivadas al administrador para ser reasignadas posteriormente. <p>¿Está seguro de devolver la asignación de la pregunta al administrador?.</p> ' +
		          '</div>' +
		
		          '<div class="modal-footer">' +
		            '<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>' +
		            '<button id="okButton" type="button" class="btn btn-primary">Aceptar</button>' +
		          '</div>' +
		      '</div>' +
          '</div>' +
        '</div>');
	var hrefLink=$(idAtrr).attr('href');
    confirmModal.find('#okButton').click(function(event) {
      document.location.href=hrefLink;
      confirmModal.modal('hide');
    });
    confirmModal.modal('show');
    return false;
}



function guardarRespuesta(id){
	var url=$('#urlSitioWeb').val()+'/consultasAsignadas/updateAnswer/'+id+'/';
	if(typeof(tinyMCE.get('Preguntas_pregt_respuesta'))!='undefined'){
        tinyMCE.get('Preguntas_pregt_respuesta').on('blur', function(e) {
        	$.post(url, {pregt_respuesta: $('#Preguntas_pregt_respuesta').val() } );
        });
        setTimeout(function() {
      		$.post(url, {pregt_respuesta: $('#Preguntas_pregt_respuesta').val() } );
		}, 1000*60*2);//2 minutos
	}	
}


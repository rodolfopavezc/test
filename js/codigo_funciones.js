function mensajeModal(mensaje,titulo){
	var html="<div id='myModal' class='modal fade'>" +
				"<div class='modal-dialog'>" +
					"<div class='modal-content'>" +
						"<div class='modal-header'>" +
							"<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>" +
							"<h4 class='modal-title'>"+titulo+"</h4>" +
						"</div>" +
						"<div class='modal-body'>" +
							"<p>"+mensaje+"</p>" +
						"</div>" +
						"<div class='modal-footer'>" +
							"<button type='button' class='btn btn-primary' data-dismiss='modal'>Cerrar</button>" +
						"</div>" +
					"</div>" +
				"</div>" +
			"</div>";
	$('#myModal').remove();
	$('body').append(html);
	$('#myModal').modal({backdrop:'static'});	
}


function mensajeParaIframe(mensaje,tipoMensaje){
	//tipoMensaje: alert,alert-danger,alert-success,alert-info
	if(typeof mensaje =="undefined"){
		mensaje="";
	}
	//Si ya existe un mensaje previo, debemos eliminar el mensaje anterior.
	if($("#mensajeSuccess").html())	$("#mensajeSuccess").remove();
	
	if(typeof tipoMensaje=="undefined")	tipoMensaje="alert-success";
	//seccion
	if($('body').length>0)
		$('body').prepend('<div class="alert '+tipoMensaje+'" id="mensajeSuccess"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>'+mensaje+'</strong></div>');
	$('html, body').animate({ scrollTop:0}, 1000);
	$("#mensajeSuccess").delay(7000).fadeOut(2000).delay(9000).queue(function() { $(this).remove(); });
}

function mensajeParaMantenedor(mensaje,tipoMensaje){
	//tipoMensaje: alert,alert-danger,alert-success,alert-info
	if(typeof mensaje =="undefined"){
		mensaje="";
	}
	//Si ya existe un mensaje previo, debemos eliminar el mensaje anterior.
	if($("#mensajeSuccess").html())	$("#mensajeSuccess").remove();
	
	if(typeof tipoMensaje=="undefined")	tipoMensaje="alert-success";
	//seccion
	if($('#content').length>0){
		$('#content').prepend('<div class="alert '+tipoMensaje+'" id="mensajeSuccess"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>'+mensaje+'</strong></div>');
	}else if($('.page-header').length>0){
		$('.page-header').prepend('<div class="alert '+tipoMensaje+'" id="mensajeSuccess"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>'+mensaje+'</strong></div>');
	}
	$('html, body').animate({ scrollTop:0}, 1000);
	$("#mensajeSuccess").delay(7000).fadeOut(2000).delay(9000).queue(function() { $(this).remove(); });
}

//Función para validar el RUT Chileno
function validarut(rut){
	//rut=rut.split('.').join('');
    //var rut = ruti+"-"+dvi;
    if (rut.length<9)
    	return(false)
    i1=rut.indexOf("-");
    dv=rut.substr(i1+1);
    dv=dv.toUpperCase();
    nu=rut.substr(0,i1);
     
    cnt=0;
    suma=0;
    for (i=nu.length-1; i>=0; i--)
    {
	    dig=nu.substr(i,1);
	    fc=cnt+2;
	    suma += parseInt(dig)*fc;
	    cnt=(cnt+1) % 6;
    }
    dvok=11-(suma%11);
    if (dvok==11) dvokstr="0";
    if (dvok==10) dvokstr="K";
    if ((dvok!=11) && (dvok!=10)) dvokstr=""+dvok;
     
    if (dvokstr==dv)
    	return(true);
    else
    	return(false);
}

function limpiarRut(rut){
	strRut = rut.split('.').join('').split('-');
	strRut=(typeof(strRut)=="object")?strRut[0]:strRut;
	return strRut;	
}


//Función para entregar el digito verificador del RUT.
function entregarDigitoVerificador(rut){
    cnt=0;
    suma=0;
    rut=rut.toString();
    for (i=rut.length-1; i>=0; i--)
    {
	    dig=rut.substr(i,1);
	    fc=cnt+2;
	    suma += parseInt(dig)*fc;
	    cnt=(cnt+1) % 6;
    }
    dvok=11-(suma%11);
    if (dvok==11) dvokstr="0";
    if (dvok==10) dvokstr="K";
    if ((dvok!=11) && (dvok!=10)) dvokstr=""+dvok;
    
    return dvokstr;
}

//Formato de fecha YYYY-MM-DD
//Función para calcular la edad de una persona por medio de su fecha de nacimiento.
function calcularEdad(fecha) {
	
	if(typeof (fecha)!="undefined" && fecha!=null){
		if(fecha.length>0){
			// Si la fecha es correcta, calculamos la edad 
			var values=fecha.split("-"); 
			var dia = values[2]; 
			var mes = values[1]; 
			var ano = values[0]; 
			// cogemos los valores actuales 
			var fecha_hoy = new Date(); 
			var ahora_ano = fecha_hoy.getYear(); 
			var ahora_mes = fecha_hoy.getMonth(); 
			var ahora_dia = fecha_hoy.getDate(); 
			// realizamos el calculo 
			var edad = (ahora_ano + 1900) - ano; 
			if ( ahora_mes < (mes - 1))
				edad--;		
			if (((mes - 1) == ahora_mes) && (ahora_dia < dia))
				edad--; 
			if (edad > 1900)
				edad -= 1900;
			return edad;		
		}
	}else{
		return "";
	}	 
}


function formatFecha(fecha){
	// var fecha="2013-12-23 00:00:00";

	// console.log("FECHA ANTES : "+fecha);


	if(fecha == "" || typeof(fecha) == "undefined" || fecha === null){

		// console.log("undefined FECHA");

		return "";
	}else{
		date=fecha.split(' ');
		date=date[0].split('-');
		date=date[2]+'-'+date[1]+'-'+date[0];

		// console.log("FECHA :"+date);
		return date;	
	}

}

function formatFechaHora(fecha){
	// var fecha="2013-12-23 00:00:00";

	// console.log("FECHA ANTES : "+fecha);


	if(fecha == "" || typeof(fecha) == "undefined" || fecha === null){

		// console.log("undefined FECHA");

		return "";
	}else{
		date=fecha.split(' ');
		hora=date[1]
		date=date[0].split('-');
		date=date[2]+'-'+date[1]+'-'+date[0];

		// console.log("FECHA :"+date);
		return date+" ("+hora+")";	
	}

}


function mensajeCargando(mensaje){	
	if(typeof mensaje =="undefined"){
		mensaje="Espere un momento...";
	}
	$('.loader_message').remove();
	$('body').append('<div class="loader_message"><span class="icon-loading"></span><h1>'+mensaje+'</h1></div>');
}

function ocultarMensajeCargando(){
	$('[data-toggle="tooltip"]').tooltip({'placement': 'top'});
	$('.loader_message').remove();
}


function fetchAbort(){
	for (var i = 0; i < App.xhrPool.length; i++) {
	    if (App.xhrPool[i]['readyState'] > 0 && App.xhrPool[i]['readyState'] < 4) {
	      App.xhrPool[i].abort();
	      App.xhrPool.splice(i, 1);
	    }
	}
   ocultarMensajeCargando();
}

//From YII
function afterAjaxUpdateSuccess(){
	$('.loader_message').remove();//Eliminar cargando
	if(typeof($('.glyphicon').parent('a[title!=\"\"]').tooltip)!="undefined")
		$('.glyphicon').parent('a[title!=\"\"]').tooltip({'placement': 'top'});//Agregar tooltip
}

function beforeAjaxUpdateSuccess(){
	mensajeCargando();
}

function afterAjaxSaveSuccess(){
	afterAjaxUpdateSuccess();
	mensajeParaMantenedor("Los registros fueron almacenados correctamente.");
}



function confirmSubmit(idForm,question,heading,cancelButtonTxt, okButtonTxt) {
	question=(typeof question!='undefined')?question:'Una vez enviada la información, no podrá ser modificada posteriormente.<br/>¿Está seguro de enviar la información ingresada?';
	heading=(typeof heading!='undefined')?heading:'Mensaje de confirmación';
	cancelButtonTxt=(typeof cancelButtonTxt!='undefined')?cancelButtonTxt:'Cancelar';
	okButtonTxt=(typeof okButtonTxt!='undefined')?okButtonTxt:'Aceptar';
    var confirmModal = 
      $('<div class="modal fade">' +
    	  '<div class="modal-dialog">' +	
    	  	  '<div class="modal-content">' +
		          '<div class="modal-header">' +
		            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
		            '<h4 class="modal-title">' + heading +'</h4>' +
		          '</div>' +
		
		          '<div class="modal-body text-center">' +
		            '<p>' + question + '</p>' +
		          '</div>' +
		
		          '<div class="modal-footer">' +
		            '<button type="button" class="btn btn-primary" data-dismiss="modal">'+cancelButtonTxt+'</button>' +
		            '<button id="okButton" type="button" class="btn btn-primary">'+okButtonTxt+'</button>' +
		          '</div>' +
		      '</div>' +
          '</div>' +
        '</div>');

    confirmModal.find('#okButton').click(function(event) {
      $('#'+idForm).submit();
      confirmModal.modal('hide');
    });
    confirmModal.modal('show');     
}


function iframeModal(url,heading,height,width) {
	var heading=(typeof heading!='undefined')?heading:'';
	var altoPantalla=$(window).height()-150;
	var headVisible="";
	
	if(heading==""){
		headVisible=" style ='display:none;'";
	}else{
		altoPantalla-=30;
	}
	var height = (typeof height!='undefined')?height:altoPantalla;
    var width = (typeof width!='undefined')?width:820;
    var confirmModal = 
      $('<div class="modal fade" style="overflow-y: hidden;">' +
    	  '<div class="modal-dialog" style="width: 850px;">' +	
    	  	  '<div class="modal-content">' +
		          '<div class="modal-header" '+headVisible+'>' +
		            '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
		            '<h4 class="modal-title">' + heading +'</h4>' +
		          '</div>' +
		
		          '<div class="modal-body text-center">' +
		            '<iframe src="'+url+'" height="'+height+'" width="'+width+'" frameborder="0"></iframe>' +
		          '</div>' +
		
		          '<div class="modal-footer">' +
		            '<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>' +
		          '</div>' +
		      '</div>' +
          '</div>' +
        '</div>');
    confirmModal.modal('show'); 
}


function actualizarFiltro(filterID,value){
	$('#searchForm')[0].reset();
	$('#'+filterID).val(value);
	$('#botonBuscar').click();
	$('html, body').stop().animate({scrollTop: $('#preguntas-grid').offset().top}, 2000);	
}

function actializarListadoFiltros(filterID1,value1,filterID2,value2,filterID3,value3){
	$('#searchForm')[0].reset();
	if (typeof(filterID1) !== "undefined" && filterID1){
		$('#'+filterID1).val(value1);
	}
	if (typeof(filterID2) !== "undefined" && filterID2){
		$('#'+filterID2).val(value2);
	}
	if (typeof(filterID3) !== "undefined" && filterID3){
		$('#'+filterID3).val(value3);
	}
	$('#botonBuscar').click();
	$('html, body').stop().animate({scrollTop: $('#preguntas-grid').offset().top}, 2000);	
}


//utilizado para mostrar o ocultar la información del usuario. Siempre debe estar visible el nombre, email, RUT
function mostrarOcultarInformacion(){
	$('button.mostrarInformacion').click(function() {
		var nombreTabla=$(this).attr('tablename');
		var nombreIcono="glyphicon-zoom-out";
		if($('table.'+nombreTabla+' tbody tr:last').is(':visible')){
			nombreIcono="glyphicon-zoom-in";
			$('table.'+nombreTabla+' tbody tr:first').nextAll().hide();
		}else{
			$('table.'+nombreTabla+' tbody tr:first').nextAll().show();
		}
		$(this).html('<span class="glyphicon '+nombreIcono+'"></span>');
	});
	$('button.mostrarInformacion').click();
}

//Utilizado para ocultar/mostrar información extra enviada por ingresa o por el usuario
function VerOcultarInformacion(idButton){
	if($('.infoExtra:first').is(':visible')){
		$(idButton).html('<span class="glyphicon glyphicon-zoom-in"></span> Ver información extra');
		$('.infoExtra').hide();	
	}else{
		$(idButton).html('<span class="glyphicon glyphicon-zoom-out"></span> Ocultar información extra');
		$('.infoExtra').show();
	}
	
}


function contentEditableTinyMCE(bool){
	if(typeof(tinymce)!="undefined"){
		if(typeof(tinyMCE.get('Preguntas_pregt_pregunta'))!="undefined")
			tinyMCE.get('Preguntas_pregt_pregunta').getBody().setAttribute('contenteditable', bool);
			
		if(typeof(tinyMCE.get('Preguntas_pregt_respuesta'))!="undefined")
			tinyMCE.get('Preguntas_pregt_respuesta').getBody().setAttribute('contenteditable', bool);	
	}	
}

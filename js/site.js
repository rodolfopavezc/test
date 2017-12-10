var discreteChart="";
var intervaloDeConfianza='1.96';
var tiposRespuestaList=['1','2','3','4'];//No es posible agregar un nuevo tipo, sin modificar el código HTML
var startYear=2014;
var stopYear=2014;
var firstYear=0;
var lastYear=0;
var selectedYear=0;
var timeoutGraph;
var timeSpeed=4000;
var options = {
		  height:330,		  
		  chartArea:{width:'100%',height:300,left:40},
	      legend: 'none',
	      tooltip: {isHtml: true},
	      animation:{
	        duration: timeSpeed/2,
	        easing: 'linear',
	      },
	      /**explorer: {
	            axis: 'vertical',
	            keepInBounds: true,
	            maxZoomIn:0.2,
	            actions: ['dragToZoom', 'rightClickToReset']
	        },*/
	     hAxis: {
	    	    viewWindow: {
	    	        max:5,
	    	        min:0
	    	    },
	    	    gridlines:{
		        	//count:5,
	    	        color: 'transparent'
		        }
		  },  
	      vAxis: {
	    	    viewWindow: {
	    	        max:4,
	    	        min:1
	    	    },
	        gridlines:{
	        	count:4
	        }
	      },
	      series: {
	    	  0: {},
	    	  1: {},
	    	  2: {},
	    	  3: {},
	    	  4: {},
	    	  5: {},
	    	  6: {},
	    	  7: {}
	      },
	      
	      //vAxis: {minValue:1, maxValue:4}
	    };

var nombreTipoReporte=new Array();
nombreTipoReporte[1]='Oportunidades de Aprendizaje Teóricas por Asignatura';
nombreTipoReporte[2]='Oportunidades de Aprendizaje Prácticas por Asignatura';
nombreTipoReporte[3]='Oportunidades de Aprendizaje para Aprender la Disciplina';
nombreTipoReporte[4]='Oportunidades de Aprendizaje para Aprender cómo Enseñar la Disciplina';
nombreTipoReporte[5]='Oportunidades de Aprendizaje Teóricas para Aprender cómo Enseñar la Disciplina';
nombreTipoReporte[6]='Oportunidades de Aprendizaje Prácticas para Aprender cómo Enseñar la Disciplina';
nombreTipoReporte[7]='Oportunidades de Aprendizaje Teóricas para Aprender la Disciplina';
nombreTipoReporte[8]='Oportunidades de Aprendizaje Prácticas para Aprender la Disciplina';

	    /*pointSize : 20,
    		      lineWidth:0,
    		      annotations: {
	    	      //textStyle: {fontSize: 12, color: 'red' }*/
var dataJSON;
var cantidadAgnos=new Array();
var disciplinasList={};
var runOnce;//variable para almacenar el listener de google chart, para ser ejecutado una unica vez.
//Se debe incorporar el color de acuerdo al ID de la disciplina
var coloresDisciplinas=['','#e86720','#3097d3','#378d3e','#7d5d9b','#959fa1'];

$( document ).ready(function() {
	
	// $.xhrPool and $.ajaxSetup are the solution
	$.xhrPool = [];
	$.xhrPool.abortAll = function() {
	    $(this).each(function(idx, jqXHR) {
	        jqXHR.abort();
	    });
	    $.xhrPool = [];
	};

	$.ajaxSetup({
	    beforeSend: function(jqXHR) {
	        $.xhrPool.push(jqXHR);
	    },
	    complete: function(jqXHR) {
	        var index = $.xhrPool.indexOf(jqXHR);
	        if (index > -1) {
	            $.xhrPool.splice(index, 1);
	        }
	    }
	});
	
	
	
	
	
	
	//Debemos limpiar el gráfico.
	$('#reporteInstitucion,#reporteTipo,#filtroResultados,#filtroMencion').change(function() {
		if($(this).attr('id')=='reporteInstitucion' || $(this).attr('id')=='reporteTipo'){
			$('#divFiltroResultados').hide();
			$('#divMencion').hide();
			$('#filtroResultados').val(1);
			$('#filtroMencion').val('');
		}
		
		if(typeof $.xhrPool !="undefined" && $.xhrPool.length>0){
			$.xhrPool.abortAll();
		}		
		ocultarMensajeCargando();
		$('.instrucciones').hide();
	    $('.exportarGrafico').hide();
	    $('#flecha_vertical').hide();
		//eliminar slider
		$('#sliderMotion .slider').remove();
		$('#chart_legend').html('');
		$('#tituloGrafico').html('');
		$('#sliderMotion').html('<div class="slider"></div>');
		$('#chart_div').html('<div class="mensaje text-center" style="margin-top: 150px;"><h5>Para desplegar un resultado, previamente debe seleccionar una opción para cada filtro que se encuentra en el panel izquierdo de su pantalla. </h5></div>');
		$('#myModal').remove();
		
	});	
});


function drawChart() {
	    $('.instrucciones').hide();
	    $('.exportarGrafico').hide();
	    $('#flecha_vertical').hide();	    
		//eliminar slider
		$('#sliderMotion .slider').remove();
		$('#chart_legend').html('');
		$('#tituloGrafico').html('');
		$('#sliderMotion').html('<div class="slider"></div>');
		$('#chart_div').html('<div class="mensaje text-center" style="margin-top: 150px;"><h5>Para desplegar un resultado, previamente debe seleccionar una opción para cada filtro que se encuentra en el panel izquierdo de su pantalla. </h5></div>');
		if($('#reporteInstitucion').val()!="" && $('#reporteTipo').val()!="" && (($('#filtroMencion').is(':visible') && $('#filtroMencion').val()!="") || (!$('#filtroMencion').is(':visible') && $('#filtroMencion').val()==""))){
		//if($('#reporteInstitucion').val()!="" && $('#reporteTipo').val()!=""){
			if(google) {					
					mensajeCargando();
					$.ajax({
						dataType: "json",
						url: 'site/json',
						type: "GET",
						cache: false,
						data: {id_institucion: $('#reporteInstitucion').val(),id_sede:$('#reporteInstitucion option:selected').attr('id_sede'),tipo_grafico:$('#reporteTipo').val(),filtro_resultados:$('#filtroResultados').val(),filtro_mencion:$('#filtroMencion').val()},
						success: function(dataResponse) {
							$('#divFiltroResultados').show();
							$('.instrucciones.tipo_'+$('#reporteTipo').val()).show();
							
							var tituloPrincipalReporte="";
							var subtitulo="";
							if(typeof(nombreTipoReporte[$('#reporteTipo').val()])!="undefined"){
								tituloPrincipalReporte=nombreTipoReporte[$('#reporteTipo').val()];
							}
							
							if($('#filtroResultados').val()=='1'){
								subtitulo="Resultados Generales";
							}else if($('#filtroResultados').val()=='2'){
								subtitulo="Resultados Alumnos Esfuerzo Académico";
							}else if($('#filtroResultados').val()=='3'){
								subtitulo="Resultados Alumnos Motivados";
							}else if($('#filtroResultados').val()=='4'){
								subtitulo="Resultados Alumnos Mención "+$('#filtroMencion option:selected').text();
							}
							
							$('#tituloGrafico').html('<h4>'+tituloPrincipalReporte+'</h4><h5>'+$('#reporteInstitucion option:selected').text()+'</h5><h6>'+subtitulo+'</h6>');
							dataJSON=dataResponse;
							disciplinasList={};
							cantidadAgnos=new Array();
							if(typeof(dataJSON[0]) !="undefined"){
									$('#flecha_vertical').show();									
									google.load('visualization', '1', {
								        packages: ['corechart'],
								        callback: function() {
									        //Debemos calcular la cantidad de años a mostrar								        	
									        $.each(dataJSON, function(key,val){
									        	cantidadAgnos.push(val['ano_proceso']);
									        	if(!disciplinasList.hasOwnProperty(val['ano_proceso'])){
									        		disciplinasList[val['ano_proceso']]={};									        		
									        	}
									        	if(!disciplinasList[val['ano_proceso']].hasOwnProperty(val['id_disciplina'])){									        		
									        		disciplinasList[val['ano_proceso']][val['id_disciplina']]={};
										        	disciplinasList[val['ano_proceso']][val['id_disciplina']]['id_disciplina']=val['id_disciplina'];
										        	disciplinasList[val['ano_proceso']][val['id_disciplina']]['pos_disciplina']=Object.keys(disciplinasList[val['ano_proceso']]).length;//Calculamos la cantidad de disciplinas actual. Como debe empezar desde 0, debemos restar 1
										        	disciplinasList[val['ano_proceso']][val['id_disciplina']]['nombre']=val['disciplina'];										        	
									        	}
									        	disciplinasList[val['ano_proceso']][val['id_disciplina']]['cantidad']=(typeof disciplinasList[val['ano_proceso']][val['id_disciplina']]['cantidad']=="number")?parseInt(disciplinasList[val['ano_proceso']][val['id_disciplina']]['cantidad'])+1:1;
									        });							        
									        cantidadAgnos = unique(cantidadAgnos).sort();									        
									        if(cantidadAgnos.length>0){
									        	//asignamos el primer y último año a las variables para ser usadas en el slider
									        	firstYear=parseInt(cantidadAgnos[0]);		
									        	lastYear=parseInt(cantidadAgnos[cantidadAgnos.length-1]);
									        	selectedYear=parseInt(lastYear);
									        									        
										        crearGraficoPorAnio(selectedYear);
										        if(cantidadAgnos.length>1){
										        	//Crear Slider
											        sliderMotion();
											        $("#sliderMotion .slider").slider( "option", "value", selectedYear);
											        $("#sliderMotion .slider").find('div.progress_bar_default').stop(true,false).animate({ width:'100%'});
										        }
										        $('.exportarGrafico').show();
									        }
								        }
								    });
							}else{
								$('.exportarGrafico').hide();
								$('#chart_div').html('<div class="mensaje text-center" style="margin-top: 150px;"><h5>No se pueden mostrar los resultados, debido a que la cantidad de alumnos que contestaron el cuestionario no es representativa del total de alumnos de último año.	Chequear si el número total de alumnos de último año es el correcto, en la plataforma del aplicador, en la sección “Modificar datos de su sede/carrera”</h5></div>');
								$('#flecha_vertical').hide();
								//eliminar slider
								$('#sliderMotion .slider').remove();
							}	
								
							ocultarMensajeCargando();
						}
					});
				    
			}			
		}else if ($('#reporteInstitucion').val()==""){			
			mensajeModal('<p>Para poder continuar, es necesario seleccionar un valor para el filtro: <strong>Alcance del Reporte</strong></p>','Mensaje','modal-md');
			$('#reporteInstitucion').focus();
		}else if ($('#reporteTipo').val()==""){			
			mensajeModal('<p>Para poder continuar, es necesario seleccionar un valor para el filtro: <strong>Tipo de Reporte</strong></p>','Mensaje','modal-md');
			$('#reporteTipo').focus();
		}else if ($('#filtroMencion').is(':visible') && $('#filtroMencion').val()==""){			
			mensajeModal('<p>Para poder continuar, es necesario seleccionar un valor para el filtro: <strong>Menciones</strong></p>','Mensaje','modal-md');
			$('#filtroMencion').focus();
		}
}

function abrirModalListadoPreguntas(id_disciplina,tipoGrafico,disciplinaName,anio,parametroExtra){
	
	var promedioDisciplina=0;
	var desviacionDisciplina=0;
	
	parametroExtra=(parametroExtra==null)?null:parametroExtra.toString();
	//Debemos obtener los valores asociados al promedio y desv del punto seleccionado
	$.each(dataJSON,function(k,v){
		if(v['ano_proceso']==anio && v['id_disciplina']==id_disciplina && (parametroExtra==null || parametroExtra=="")){
			promedioDisciplina=parseFloat(v['promedio']).toFixed(3);
			desviacionDisciplina=parseFloat(v['desviacion_estandar']).toFixed(3);
		}else if(v['ano_proceso']==anio && v['id_disciplina']==id_disciplina && (parametroExtra!=null && parametroExtra.length>0) && (typeof v['alumno_motivado']!="undefined") && v['alumno_motivado']==parametroExtra){
			promedioDisciplina=parseFloat(v['promedio']).toFixed(3);
			desviacionDisciplina=parseFloat(v['desviacion_estandar']).toFixed(3);
		}else if(v['ano_proceso']==anio && v['id_disciplina']==id_disciplina && (parametroExtra!=null && parametroExtra.length>0) && (typeof v['esfuerzo_estudiante']!="undefined") && v['esfuerzo_estudiante']==parametroExtra){
			promedioDisciplina=parseFloat(v['promedio']).toFixed(3);
			desviacionDisciplina=parseFloat(v['desviacion_estandar']).toFixed(3);
		}		
	});
	
	//tipoGrafico= 1:sede, 3:nacional
	mensajeCargando();
	$.ajax({
		dataType: "json",
		url: 'site/json',
		type: "GET",
		data: {id_institucion: $('#reporteInstitucion').val(),id_sede:$('#reporteInstitucion option:selected').attr('id_sede'),tipo_grafico:$('#reporteTipo').val(),id_disciplina:id_disciplina,es_listado_preguntas:1,anio:selectedYear,filtro_resultados:$('#filtroResultados').val(),filtro_mencion:$('#filtroMencion').val(),parametro_extra:parametroExtra},
		success: function(dataResponse){
			var preguntasList={};
			var cantidadEstudiantes=0;
			var cantidadEstudiantesNacional=0;
			$.each(dataResponse, function(key,val){
				if(!preguntasList.hasOwnProperty(val['id_pregunta'].toString())){
					preguntasList[val['id_pregunta'].toString()]=new Array();
					preguntasList[val['id_pregunta'].toString()]['respuestas']=new Array();
					cantidadEstudiantes=0;
					cantidadEstudiantesNacional=0;
				}
				cantidadEstudiantes+=parseInt(val['nro_estudiantes']);
				cantidadEstudiantesNacional+=parseInt(val['nro_estudiantes_nacional']);
				preguntasList[val['id_pregunta'].toString()]['pregunta']=val['pregunta'];				
				preguntasList[val['id_pregunta'].toString()]['nro_estudiantes']=cantidadEstudiantes;
				preguntasList[val['id_pregunta'].toString()]['nro_estudiantes_nacional']=cantidadEstudiantesNacional;
				preguntasList[val['id_pregunta'].toString()]['respuestas'][val['tipoRespuesta']]=val['nro_estudiantes'];
				preguntasList[val['id_pregunta'].toString()]['respuestas'][val['tipoRespuesta']+'ID']=val['tipoRespuesta'];
				preguntasList[val['id_pregunta'].toString()]['respuestas'][val['tipoRespuesta']+'N']=val['nro_estudiantes_nacional'];
	        });	
			
			var htmlPreguntas="<table class='table table-striped' style='font-size: 12px;'><tr><td width='55%' style='text-align: justify;'>Preguntas consideradas para el indicador de frecuencia de '"+$('#reporteTipo option:selected').text()+"' en la asignatura de '"+ UpperFirstCharacter(disciplinaName)+"'</td>";
			var tooltipPromedio='<i class="glyphicon glyphicon-question-sign" data-placement="bottom" data-toggle="tooltip" data-html="true" title="Las preguntas que tienen una flecha son las que en su promedio existe una diferencia significativa en relación al promedio de todas las preguntas consideradas, por sobre (flecha verde) o bajo (flecha roja) este promedio.'+
					'<br /> En cada pregunta, por cada nivel de oportunidades se muestra el porcentaje de alumnos que respondieron ese nivel y a cuántos alumnos corresponde."></i>';
			
			var sumaRespuestasGeneral=0;
			var cantidadRespuestasGeneral=0;
			//var promedioGeneral=0;
			var htmlPreguntasDetalle="";
			var porcentajePorOportunidad={};
			$.each(preguntasList, function(key,val){
				htmlPreguntasDetalle+="<tr><td style='text-align: justify;'>"+UpperFirstCharacter(val['pregunta'])+"</td>";
				var sumaRespuestas=0;
				var cantidadRespuestas=0;
				var htmlPreguntasTMP="";
				$.each(tiposRespuestaList, function(k,v){
					if(!porcentajePorOportunidad.hasOwnProperty(v)){
						porcentajePorOportunidad[v]=new Array();
					}					
					if(!val['respuestas'].hasOwnProperty(v)){						
						htmlPreguntasTMP+="<td style='font-size: 11px; text-align: center;'>0% <br/>0</td>";
					}else{
						sumaRespuestas=sumaRespuestas+parseInt(parseInt(val['respuestas'][v+'ID'])*parseInt(val['respuestas'][v]));
						cantidadRespuestas=cantidadRespuestas+parseInt(val['respuestas'][v]);						
						porcentaje=parseFloat(val['respuestas'][v]*100/val['nro_estudiantes']).toFixed(1);
						porcentajePorOportunidad[v].push(parseFloat(val['respuestas'][v]*100/val['nro_estudiantes']).toFixed(2));
						htmlPreguntasTMP+="<td style='font-size: 11px; text-align: center;'>"+porcentaje+"% <br/>"+val['respuestas'][v]+"</td>";
					}
				});
				//console.log(sumaRespuestas+"/"+cantidadRespuestas);
				var promedioRespuestasDePregunta=parseFloat(sumaRespuestas/cantidadRespuestas).toFixed(1);
				
				var imagenPromedio="";
				var difSignificativaBajo=parseFloat(promedioDisciplina)-(parseFloat(intervaloDeConfianza)*parseFloat(desviacionDisciplina));
				var difSignificativaSuperior=parseFloat(promedioDisciplina)+(parseFloat(intervaloDeConfianza)*parseFloat(desviacionDisciplina));
				//promedio - 1.96 * desviación
				if(promedioRespuestasDePregunta>=difSignificativaSuperior){
					imagenPromedio="<img src='images/up.png' />";
				}else if(promedioRespuestasDePregunta<=difSignificativaBajo){
					imagenPromedio="<img src='images/down.png' />";
				}
				sumaRespuestasGeneral+=sumaRespuestas;
				cantidadRespuestasGeneral+=cantidadRespuestas;
				
				htmlPreguntasDetalle+="<td><div class='text-center'>"+promedioRespuestasDePregunta+"<br/>"+imagenPromedio+"</div></td>"+htmlPreguntasTMP+"</tr>";
			});
			
			/*if(cantidadRespuestasGeneral>0){
				promedioGeneral=parseFloat(sumaRespuestasGeneral/cantidadRespuestasGeneral).toFixed(3);
			}*/
			
			//Calculando el porcentaje total por columna
			var porcentajeTotal_1=porcentajeTotal_2=porcentajeTotal_3=porcentajeTotal_4=0;
			$.each(porcentajePorOportunidad,function(k,v){				
				$.each(v,function(key,val){
					eval('porcentajeTotal_'+k+'+=parseFloat('+val+');');
				});
				eval('porcentajeTotal_'+k+'=parseFloat(porcentajeTotal_'+k+'/'+v.length+').toFixed(1);');
			});
			htmlPreguntas+="<td width='13%'>" +
								"<div class='text-center'>" +
									"<small>Promedio</small> "+tooltipPromedio+"<br/> <img src='images/up.png' /> <img src='images/down.png' /><br/> <strong><small>"+promedioDisciplina+"</small></strong>" +
								"</div>" +
							"</td>" +
							"<td width='32%' colspan='4'>" +
								"<table width='100%' border=0>" +
									"<tr>" +
										"<td colspan='4'><img src='images/alternativas.png' width='100%'></td>" +
									"</tr>" +
									"<tr class='text-center'>" +
										"<td style='padding-left: 2px;padding-right: 12px'><strong><small>"+porcentajeTotal_1+"%</small></strong></td>" +
										"<td style='padding-left: 8px;padding-right: 8px'><strong><small>"+porcentajeTotal_2+"%</small></strong></td>" +
										"<td style='padding-left: 8px;padding-right: 8px'><strong><small>"+porcentajeTotal_3+"%</small></strong></td>" +
										"<td style='padding-left: 8px;padding-right: 8px'><strong><small>"+porcentajeTotal_4+"%</small></strong></td>" +
									"</tr>" +
								"</table>" +
							"</td>" +
							"</tr>";
			
			htmlPreguntas+=htmlPreguntasDetalle+"</table>";
			ocultarMensajeCargando();
			mensajeModal(htmlPreguntas,'Resultados por Pregunta','modal-lg');
		}
	});
	
    
}



function crearGraficoPorAnio(anio){
	//Debemos validar en caso de que no existan valores para el año seleccionado
	if(!disciplinasList.hasOwnProperty(anio)){
		alert('No existen valores para el año '+anio);
		return false;
	}
	//Ingresando promedios correspondientes a cada disciplina
    $('#chart_legend').html('<div class="text-center"></div>');
    
	var data = new google.visualization.DataTable();
	//var cantidadPuntosPorDisciplina=2;
	var cantidadDisciplinas=Object.keys(disciplinasList[anio]).length;
	
	data.addColumn('number', 'nro_disciplina_in_array');
    //Debemos crear las columnas en base a la cantidad de disciplinas encontradas para el año especificado
    
    $.each(disciplinasList[anio],function(key,val){    	
    	data.addColumn('number', 'Promedio'+key);
        data.addColumn({type:'string',role:'tooltip', 'p': {'html': true}});//annotationText
        data.addColumn({type:'string',role:'style'});//annotationText
        //Debemos agregar la leyenda del gráfico
        $('#chart_legend').children('div').append('<span><span style="background-color:'+coloresDisciplinas[val['id_disciplina']]+'" class="glyphicon">&nbsp;</span>&nbsp; '+val['nombre']+'</span>&nbsp;&nbsp;&nbsp;&nbsp;');
	});

    data.addColumn('number', 'Promedio Nacional');
    data.addColumn({type:'string',role:'tooltip', 'p': {'html': true}});
    data.addColumn({type:'string',role:'style'});//annotationText    
    /////////////////////////////////////////////////////////////////////
    ////////////////////////AGREGANDO VALORES AL GRÁFICO////////////////
    ///////////////////////////////////////////////////////////////////
    //Ingresando valores como null
    /*var rows=new Array();	
    
    for(var x=0;x<=cantidadDisciplinas+1;x++){
    	for(var y=0;y<=cantidadPuntosPorDisciplina;y++){
    		var valueRow=new Array();
        	valueRow.push(x);
        	$.each(disciplinasList,function(keyD,valD){
        		valueRow.push(null);//Value
        		valueRow.push(null);//tooltip
    		});
        	valueRow.push(null);//valor promedio nacional
        	valueRow.push(null);//tooltip
        	rows[x]=valueRow;
    	}    	
    } */   
    
    var valueRow=createEmptyRow(0,anio);
    data.addRow(valueRow);
   
    //Crear fila inicial para el promedio de disciplinas(inicio)
    
    var valoresPromedioDisciplinas=new Array();
    var posicionValorNacional=((cantidadDisciplinas+1)*3)-2;
	var posicionTooltipNacional=((cantidadDisciplinas+1)*3)-1;
	var posicionStyleNacional=((cantidadDisciplinas+1)*3);
	var valorNacional=null;
	var tooltipNacional=null;
	var cantidadDeDisciplinasAGraficar=1;
	var cantidadPorDisciplina=new Array();
    $.each(dataJSON, function(key,val){
    	if(anio==val['ano_proceso']){    		
    		        	
        	var posicionDisciplinaInArray=parseInt(disciplinasList[anio][val['id_disciplina']]['pos_disciplina']);
    		var posicionValor=(posicionDisciplinaInArray*3)-2;
    		var posicionTooltip=(posicionDisciplinaInArray*3)-1;
    		var posicionStyle=(posicionDisciplinaInArray*3);
    		cantidadPorDisciplina[posicionDisciplinaInArray]=(typeof cantidadPorDisciplina[posicionDisciplinaInArray]=="number")?cantidadPorDisciplina[posicionDisciplinaInArray]+1:1;
    		
    		//cantidad
    		cantidadDeDisciplinasAGraficar++;
    		
    		var valorAsignacionPrimeraColumna=posicionDisciplinaInArray;
    		
    		if(disciplinasList[anio][val['id_disciplina']]['cantidad']>1){
    			valorAsignacionPrimeraColumna=(parseInt(valorAsignacionPrimeraColumna)-0.45)+(0.30*cantidadPorDisciplina[posicionDisciplinaInArray]);
    		}
    		
    		//Debemos limpiar la fila que vamos a crear asignando en primera instancia valores con null
    		var valueRow=createEmptyRow(valorAsignacionPrimeraColumna,anio);
    		//var valueRow2=createEmptyRow(posicionDisciplinaInArray+0.15,anio);cantidadDeDisciplinasAGraficar++;//Eliminar linea con ambas expresiones
    		
    		var textPromedio="Promedio Sede";
    		if($('#reporteInstitucion').val()==0){
    			textPromedio="Promedio Nacional";
    		}
    		
    		//Agregar salto de linea a los span par
    		/*if((($('#chart_legend div>span').length) % 2) ==0){
    			$('#chart_legend').children('div').append('<br/>');
    		}*/
    		
    		valorNacional=parseFloat(val['promedio_nacional']);
    		tooltipNacional="<strong>"+textPromedio+"</strong><br/>N° Alumnos: "+val['nro_estudiantes_nacional']+"<br/>Promedio: "+parseFloat(val['promedio_nacional']).toFixed(3)+"<br/>Desv. Std: "+parseFloat(val['desviacion_estandar_nacional']).toFixed(3);
    		//asignando promedios nacionales
    		data.setCell(0, posicionValorNacional,valorNacional);
    		data.setCell(0, posicionTooltipNacional,tooltipNacional);
    		data.setCell(0, posicionStyleNacional,null);    		
    		
    		//rows[0][posicionValorNacional]=parseFloat(val['promedio_nacional']);
    		//rows[0][posicionTooltipNacional]="<strong>"+textPromedio+"</strong><br/>N° Alumnos: "+val['nro_estudiantes_nacional']+"<br/>Promedio: "+parseFloat(val['promedio_nacional']).toFixed(3)+"<br/>Desv. Std: "+parseFloat(val['desviacion_estandar_nacional']).toFixed(3);
    		valueRow[posicionValor]=parseFloat(val['promedio']);
    		valueRow[posicionTooltip]="<strong>"+val['disciplina']+"</strong><br/> N° Alumnos: "+val['nro_estudiantes']+"<br/>Promedio: "+parseFloat(val['promedio']).toFixed(3)+"<br/>Desv. Std: "+parseFloat(val['desviacion_estandar']).toFixed(3);
    		valueRow[posicionStyle]=null;
    		
    		
    		
    		if(typeof val['alumno_motivado'] !="undefined"){
    			//Debemos agregar el valor de motivacion positiva o negativa según corresponda.
    			if(val['alumno_motivado']==0){
    				valueRow[posicionTooltip]+="<br/>Motivación: No";
    				valueRow[posicionStyle]="point { size: 18; shape-type: triangle;shape-rotation: 180}";
    			}else if(val['alumno_motivado']==1){
    				valueRow[posicionTooltip]+="<br/>Motivación: Si";
    				valueRow[posicionStyle]="point { size: 18; shape-type: triangle; }";
    			}
    		}else if(typeof val['esfuerzo_estudiante'] !="undefined"){
    			//Debemos agregar el valor de motivacion positiva o negativa según corresponda.
    			if(val['esfuerzo_estudiante']==0){
    				valueRow[posicionTooltip]+="<br/>Esfuerzo académico: Inferior";
    				valueRow[posicionStyle]="point { size: 18; shape-type: triangle;shape-rotation: 180}";
    			}else if(val['esfuerzo_estudiante']==1){
    				valueRow[posicionTooltip]+="<br/>Esfuerzo académico: Superior";
    				valueRow[posicionStyle]="point { size: 18; shape-type: triangle; }";
    			}
    		}
    		
    		
    		valueRow[posicionValorNacional]=valorNacional;    		
    		valueRow[posicionTooltipNacional]='<div></div>';//tooltipNacional;
    		valueRow[posicionStyleNacional]="point { size:0; visible:'none';opacity:0;}";
    		
    		   		
    		
    		valoresPromedioDisciplinas.push(val['promedio']);
	        
    		//Calculando intervalos de confianza
    		var difSignificativaBajo=parseFloat(val['promedio_nacional'])-(parseFloat(intervaloDeConfianza)*parseFloat(val['desviacion_estandar_nacional']));
			var difSignificativaSuperior=parseFloat(val['promedio_nacional'])+(parseFloat(intervaloDeConfianza)*parseFloat(val['desviacion_estandar_nacional']));
			
			
			//Debemos editar los options la variable asociada a las series
    		//Serie 0 es el primer valor de una disciplina
			/*options['series'][posicionDisciplinaInArray-1]={pointSize:20,color: coloresDisciplinas[val['id_disciplina']],lineWidth:0,pointShape: { type: 'triangle', rotation: 0 }};
			
			if(parseFloat(val['promedio'])>=2.7){
				options['series'][posicionDisciplinaInArray-1]={pointSize:20,color: coloresDisciplinas[val['id_disciplina']],lineWidth:0,pointShape: { type: 'triangle', rotation: 180 }};
			}
			*/
			
			options['series'][posicionDisciplinaInArray-1]={pointSize:20,color: coloresDisciplinas[val['id_disciplina']]};
			
			if(parseFloat(val['promedio'])>=difSignificativaSuperior || parseFloat(val['promedio'])<=difSignificativaBajo){
				options['series'][posicionDisciplinaInArray-1]={pointSize:20,color: coloresDisciplinas[val['id_disciplina']],lineWidth:0,pointShape: { type: 'star', sides: 6, dent: 0.6}};
			}
			
			
			data.addRow(valueRow);
			//data.addRow(valueRow2);
			
    	}
    });
    
    valueRow=createEmptyRow(cantidadDisciplinas+1,anio);
    data.addRow(valueRow);
    
    
    
    //asignando promedios nacionales
	data.setCell(cantidadDeDisciplinasAGraficar, posicionValorNacional,valorNacional);
	data.setCell(cantidadDeDisciplinasAGraficar, posicionTooltipNacional,tooltipNacional);
	
    valoresPromedioDisciplinas.sort();
    
    
    
    options['series'][cantidadDisciplinas]={lineWidth:3,color:'#06ff00',pointSize:10}; //Serie correspondiente al la línea del promedio   
    options['hAxis']['viewWindow']['max']=cantidadDisciplinas+1;
    options['hAxis']['gridlines']['count']=cantidadDisciplinas+1;
    //options['hAxis']['gridlines']['color']='#06ff00';
    
  //if($("input[name='inlineRadioOptions']:checked").val()==0){
    if($('#zoom').children('span.zoom-out').is(':visible')){ 
    	options['vAxis']['viewWindow']['min']=parseFloat(valoresPromedioDisciplinas[0])-0.1;
    	options['vAxis']['viewWindow']['max']=parseFloat(valoresPromedioDisciplinas[valoresPromedioDisciplinas.length-1])+0.1;
    	options['vAxis']['gridlines']['count']=8;    	
    }else{
    	options['vAxis']['viewWindow']['min']=1;
    	options['vAxis']['viewWindow']['max']=4;
    	options['vAxis']['gridlines']['count']=4;
    }
    
    
    //data.addRows(rows);
    if((typeof(discreteChart)!="object") || ($('#chart_div div.mensaje').length>0))
    	discreteChart = new google.visualization.LineChart(document.getElementById('chart_div'));
    
    //console.log(data);
    discreteChart.draw(data, options);
    
    google.visualization.events.removeListener(runOnce);
    runOnce=google.visualization.events.addListener(discreteChart, 'select', function() {
		  
		  var selectedItem = discreteChart.getSelection()[0];
		  		 
          if (selectedItem) {
        	  if(selectedItem.column!=((cantidadDisciplinas*3)+1)){//En caso contrario es promedio de sede
        		  
        		  var pointValueStyle=data.getValue(selectedItem.row,selectedItem.column+2);
        		  var parametroExtra="";
        		  if(pointValueStyle!=null && pointValueStyle.length>0){
        			  parametroExtra=(pointValueStyle.indexOf("rotation")>=0)?0:1;
        		  }
        		  var KeyName = data.getValue(selectedItem.row, 0).toFixed(0);//Debemos aproximar en caso de tener más de un punto graficado en una disciplina //.toUpperCase()).split(' ').join('');
		          var disciplinaName=disciplinasList[anio][KeyName]['nombre'];//data.getValue(selectedItem.row, 0).toUpperCase();
		          //var promedioDisciplina=data.getValue(selectedItem.row, selectedItem.column);
		          var disciplinaID=disciplinasList[anio][KeyName]['id_disciplina'];
		            
		          abrirModalListadoPreguntas(disciplinaID,selectedItem.column,disciplinaName,anio,parametroExtra);
        	  }
          }
	});
}


function mensajeCargando(mensaje){	
	if(typeof mensaje =="undefined"){
		mensaje="Espere un momento...";
	}
	$('.loader_message').remove();
	$('body').append('<div class="loader_message"><span class="icon-loading"></span><h1>'+mensaje+'</h1></div>');
}

function ocultarMensajeCargando(){
	$('[data-toggle="tooltip"]').tooltip({'placement': 'top',html: true});
	$('.loader_message').remove();
}

function mensajeModal(mensaje,titulo,size){
	var size = typeof size !== 'undefined' ? size : '';
	var html="<div id='myModal' class='modal fade'>" +
				"<div class='modal-dialog "+size+"'>" +
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
	$("[data-toggle=tooltip]").tooltip({html: true});
}

function verImagen(){
	window.open(discreteChart.getImageURI());
}


function unique(array) {
    return $.grep(array, function(el, index) {
        return index == $.inArray(el, array);
    });
}


function arraySearch(arr,val) {
    for (var i=0; i<arr.length; i++)
        if (arr[i] === val)                    
            return i;
    return false;
}

function UpperFirstCharacter(string){ return string.charAt(0).toUpperCase() + string.slice(1); } 


function createEmptyRow(value,anio){
	var valueRow=new Array();
	valueRow.push(parseFloat(value));
	$.each(disciplinasList[anio],function(keyD,valD){
		valueRow.push(null);//Value
		valueRow.push(null);//tooltip
		valueRow.push(null);//style
	});
	valueRow.push(null);//valor promedio nacional
	valueRow.push(null);//tooltip nacional
	valueRow.push(null);//style nacional
	return valueRow;
}

/*google.load("visualization", "1", {packages: ["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable
            ([['X', '1', '2', '3', '4','5'],
              [0, null, null, null, null,3],
              [1, 2, null, null, null,3],
              [2, null, 3, null, null,3],
              [3, null, null, 4, null,3],
              [4, null, null, null, 5,3],
              [5, null, null, null, null,3]
        ]);

        var options = {
          legend: 'none',
          title: 'Company Performance',
          series: {
                0: { pointSize: 30,pointShape: 'circle' },
                1: { pointSize: 30,pointShape: 'triangle' },
                2: { pointSize: 30,pointShape: 'square' },
                3: { pointSize: 30,pointShape: 'diamond' }
            }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
}*/

function saveAsImg() {
	mensajeCargando('Exportando imagen, favor espere unos segundos...');
    var imgData = discreteChart.getImageURI(); 
    var divContent=$('<div/>', {
      id: 'tmpChart',height:450,style:'background-color:#FFF'       
    }).html('<div class="text-center">'+$('#tituloGrafico').html()+'</div><div style="height: 350px;position: relative;"><div style="position: absolute;width:70px;left:0px;font-size: 10px;"><div class="text-center">Amplias Oportunidades <br> <img height="290" src="images/flecha_grafico.png"> <br> Nunca o Casi Nunca</div></div><div style="position: absolute; left:70px;"><img src="'+imgData+'"></div></div><div class="text-center">'+$('#chart_legend').html()+'</div>').appendTo('#panelGrafico');
    
    html2canvas(document.getElementById("tmpChart"), {
        onrendered: function(canvas) {
        	var img = canvas.toDataURL("image/png");
            var output = encodeURIComponent(img);            
        	$.ajax({
                type: "POST",
                url: "site/saveimage",
                data: "image="+output,
                success : function(data)
                {
                	ocultarMensajeCargando();
                	window.open(data, '_blank');
                }
            });
        	$('#tmpChart').remove();
        }
   });
}
 
function sliderMotion(){
	$('#sliderMotion').html('<div class="slider"></div>');	
	$("#sliderMotion .slider").slider({ 
	    min: parseInt(firstYear), 
	    max: parseInt(lastYear),
	    animate: 'slow',
	    start: function(event, ui) {startYear=parseInt(ui.value);},
	    stop: function( event, ui ) {stopYear=parseInt(ui.value);progressBar();}
	    //step: 1 
	}).slider("pips", {
	    rest: "label",
	    step: 1
	}).slider("float");
	$("#sliderMotion .slider").append('<div class="progress_bar_default"></div>');
}

function progressBar(){		
	var differenceYears=stopYear-startYear;	
	var percentByEach=100/parseInt(cantidadAgnos.length-1);//uncomment	
	var TMPYear=startYear;
	clearInterval(timeoutGraph);
	//var percentByEach=100/parseInt(3);//DELETE
	if(startYear>stopYear){
		differenceYears=startYear-stopYear;		
	}
	
	//firstYear=2014;//DELETE
	var widthPercent=percentByEach*(stopYear-firstYear);
	var progressBarWidth = widthPercent * $("#sliderMotion .slider").width() / 100;
	$("#sliderMotion .slider").find('div.progress_bar_default').stop(true,false).animate({ width: progressBarWidth }, timeSpeed*differenceYears);
	//$("#sliderMotion .slider").slider( "option", "disabled", true );
	//
	TMPYear=(startYear<stopYear)?TMPYear+=1:TMPYear-=1;
	selectedYear=TMPYear;
	crearGraficoPorAnio(TMPYear);
	if(TMPYear==stopYear){
		clearInterval(timeoutGraph);
	}else{
		timeoutGraph=setInterval(function(){
			//console.log(TMPYear);
			TMPYear=(startYear<stopYear)?TMPYear+=1:TMPYear-=1;
			selectedYear=TMPYear;
			crearGraficoPorAnio(TMPYear);
			if(TMPYear==stopYear){clearInterval(timeoutGraph);}
		}, timeSpeed);
	}		
}


function ayuda(){
	var htmlAyuda="<ul>" +
			"<li><strong>Indicador círculo:</strong> Indica el promedio de la especialidad para el tipo reporte seleccionado, el  que  no posee diferencia significativa respecto del promedio.</li>" +
			"<li><strong>Indicador estrella:</strong> Indica el promedio de la especialidad para el tipo reporte seleccionado, el  que  posee diferencia significativa respecto del promedio.</li>" +
			"<li><strong>Zoom:</strong> Seleccione Zoom + o Zoom – , según corresponda, para disminuir  la escala visualizando los indicadores con mayor amplitud o volver a la escala original.</li>" +
			"<li><strong>Barra de tiempo:</strong> Para generar el gráfico correspondiente a otro período, se debe seleccionar dicho período  en la barra ubicada en la parte inferior del gráfico.</li>" +
			"<li><strong>Ver Preguntas:</strong> Al hacer clic sobre el indicador (círculo o estrella) se desplegará una ventana con todas las preguntas correspondientes al tipo de gráfico que obtuvieron respuesta.</li>" +
			"</ul>";
	mensajeModal(htmlAyuda,'Ayuda del sitio','modal-md');
	
}



function zoomActivar(buttonDom){	
	if($(buttonDom).children('span.zoom-in').is(':visible')){
		$(buttonDom).children('span.zoom-in').hide();
		$(buttonDom).children('span.zoom-out').show();
	}else{
		$(buttonDom).children('span.zoom-out').hide();
		$(buttonDom).children('span.zoom-in').show();
	}	
	
	if(typeof(dataJSON[0]) !="undefined"){
		//Quiere decir que ya se encuentra un JSON en ejecución			
		crearGraficoPorAnio(selectedYear);
	}
}


function verMenciones(){
	var valorFiltroResultados=$('#filtroResultados').val();
	$('#filtroMencion').val('');//Seleccionamos el valor por defecto	
	$('#divMencion').hide();//Ocultamos siempre el filtro de menciones
	if(valorFiltroResultados==4){		
		$('#divMencion').show();		
	}	
}

<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

?>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        //grafico 1
        var data1 = google.visualization.arrayToDataTable([
            ["Estado", "Cantidad", { role: "style" } ],
            ["Nuevas", 8, "green"],
            ["Resueltas", 10, "gold"],
            ["Reincide", 19, "silver"]
        ]);
        var view1 = new google.visualization.DataView(data1);
        view1.setColumns([0, 1,
            { calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation" },
            2]);

        var options1 = {
            title: "Cantidad de incidencias por estado",
            bar: {groupWidth: "95%"},
            legend: { position: "none" }
        };
        //grafico 2
        var data2 = google.visualization.arrayToDataTable([
            ['Fecha', 'Cantidad'],
            ['20170806',  4],
            ['20170815',  10],
            ['20170820',  1],
            ['20170910',  25]
        ]);

        var options2 = {
            title: 'Cantidad de incidencias en el tiempo',
            curveType: 'function',
            legend: { position: 'bottom' }
        };
        //grafico 3
        var data3 = google.visualization.arrayToDataTable([
            ["módulo", "Cantidad"],
            ["Login", 0],
            ["Administración",7],
            ["Reportes", 8],
            ["Facturación", 25],
            ["Venta", 2]
        ]);
        var view3 = new google.visualization.DataView(data3);
        view3.setColumns([0, 1]);

        var options3 = {
            title: "Cantidad de incidencias por módulo",
            bar: {groupWidth: "95%"},
            legend: { position: "none" }
        };
        //grafico4
        var data4 = google.visualization.arrayToDataTable([
            ['Fecha', 'Cantidad'],
            ['20170806',  3],
            ['20170815',  1],
            ['20170820',  9],
            ['20170910',  1]
        ]);

        var options4 = {
            title: 'Cantidad de incidencias reiteradas',
            curveType: 'function',
            legend: { position: 'bottom' }
        };
        var chart1 = new google.visualization.ColumnChart(document.getElementById("chart1"));
        chart1.draw(view1, options1);
        var chart2 = new google.visualization.LineChart(document.getElementById('chart2'));
        chart2.draw(data2, options2);
        var chart3 = new google.visualization.ColumnChart(document.getElementById("chart3"));
        chart3.draw(view3, options3);
        var chart4 = new google.visualization.LineChart(document.getElementById('chart4'));
        chart4.draw(data4, options4);
    }
</script>
<br/>
<div class="row">

	<div class="col-lg-6 col-xs-12">
	    <div class="panel panel-default">	 
		  <div class="panel-heading"><strong>Cantidad de incidencias por estado</strong>
              <div class="btn-group pull-right" style="margin-top: -5px;">
                  <button class="btn btn-default toggle-dropdown" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true"> <span class="glyphicon glyphicon-cog"></span>
                    </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                      <li><a href="#">Descargar XLS</a></li>
                      <li><a href="#">Descargar PNG</a></li>
                  </ul>
              </div>
          </div>
		  <div class="panel-body">
		  	    <div id="chart1"></div>
		  </div>
		</div>
	</div>
    <div class="col-lg-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Cantidad de incidencias en el tiempo</strong>
                <div class="btn-group pull-right" style="margin-top: -5px;">
                    <button class="btn btn-default toggle-dropdown" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true"> <span class="glyphicon glyphicon-cog"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#">Descargar XLS</a></li>
                        <li><a href="#">Descargar PNG</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div id="chart2"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Cantidad de incidencias por módulo</strong>
                <div class="btn-group pull-right" style="margin-top: -5px;">
                    <button class="btn btn-default toggle-dropdown" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true"> <span class="glyphicon glyphicon-cog"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#">Descargar XLS</a></li>
                        <li><a href="#">Descargar PNG</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div id="chart3"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Cantidad de incidencias reiteradas</strong>
                <div class="btn-group pull-right" style="margin-top: -5px;">
                    <button class="btn btn-default toggle-dropdown" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true"> <span class="glyphicon glyphicon-cog"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#">Descargar XLS</a></li>
                        <li><a href="#">Descargar PNG</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div id="chart4"></div>
            </div>
        </div>
    </div>
</div>
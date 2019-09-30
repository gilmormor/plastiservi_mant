$(document).ready(function() {

	mostrarResultados(2019);


});

function mostrarResultados(año){

	$('.resultados').html('<canvas id="grafico"></canvas>');
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_grafico.php',
			data 	: 
			{
				accion : "mostrarGrafico",
				año    : año
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		var valores = eval(datos);

		var e   = valores[0];
		var f   = valores[1];
		var m   = valores[2];
		var a   = valores[3];
		var m   = valores[4];
		var j   = valores[5];
		var jl  = valores[6];
		var ag  = valores[7];
		var s   = valores[8];
		var o   = valores[9];
		var n   = valores[10];
		var d   = valores[11];

/*
		fillColor : 'rgba(91,228,146,0.6)', //COLOR DE LAS BARRAS
		strokeColor : 'rgba(57,194,112,0.7)', // COLOR DEL BORDE DE LAS BARRAS
		highlightFill : 'rgba(73,206,180,0.6)', // COLOR "HOVER" DE LAS BARRAS
		highlightStroke : 'rgba(66,196,157,0.7)', // COLOR "HOVER" DEL BORDE DE LAS BARRAS
*/
/*
						backgroundColor: 'rgba(0,116,217,0.6)',
						borderColor: 'rgba(0,0,255,0.7)',
						borderWidth: 1,
*/

		var color = Chart.helpers.color;
		var Datos = {
				labels : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				datasets : [{
						label: 'Santa Ester: '+año,
						backgroundColor: 'rgba(0,116,217,0.6)',
						borderColor: 'rgba(0,0,255,0.7)',
						borderWidth: 1,
						data : [e,f,m,a,m,j,jl,ag,s,o,n,d]
					}
				]
			}
		//var contexto = $("#grafico").getContext('2d');
		var ctx = document.getElementById('grafico').getContext('2d');
		//window.Barra = new Chart(ctx).Bar(Datos, {responsive : true});

		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: Datos,
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: 'Ventas'
				}
			}
		});
		myBar.clear();


	});
}
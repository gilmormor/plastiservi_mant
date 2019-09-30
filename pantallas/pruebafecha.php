<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.min.css" />
    <script src="../bootstrap/js/bootstrap-datepicker.js" charset="UTF-8"></script>
	<script>
        $( document ).ready(function() {
        	/*
			$('#fecha').datepicker({
				  showOn: 'button',
				  buttonText: 'Escoja una fecha',
				  buttonImage: 'imagenes/calendar.png',
				  buttonImageOnly: true,
				  dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
				  numberOfMonths: 1,
				  yearRange:'1960:2000',
				  changeMonth: true,
				  changeYear:true,
				  dateFormat: 'dd/mm/yy',
				  maxDate: '0y',
				  monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
				  showButtonPanel: true,
				  showAnim: 'fadeIn'
			});
			*/
			$('.datepicker').datepicker({
			    language: 'es'
			});
			$('#fecha').datepicker({titleFormat: 'yy-mm-dd' });
			var confirm= alertify.confirm('Probando confirm','Confirmar solicitud?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
			 
			confirm.set({transition:'slide'});   	
			 
			confirm.set('onok', function(){ //callbak al pulsar botón positivo
			    	alertify.success('Has confirmado');
			});
			 
			confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
			    alertify.error('Has Cancelado el dialog');
			});
		});
    </script>
</head>
</head>
<body>
    <form ...>
		<div class="container">
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="referencia">N&uacute;mero de Deposito:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="referencia" id="referencia" placeholder="Referencia" maxlength="10" required>
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="referencia">N&uacute;mero de Deposito:</label>
				</div>

				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" id="fecha" name="fecha" />
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="nombre">Nombre:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Referencia" maxlength="10" required>
				</div>
			</div>			
		</div>
    </form>
</body>
</html>
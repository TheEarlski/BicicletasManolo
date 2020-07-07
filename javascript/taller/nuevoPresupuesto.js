$(document).ready(function() {
	
	var cont = 1;
	var componentes;
	
	$.ajax({
        url: '../../aplicacion/taller/componentes.php',
        type: 'GET',
        dataType:'JSON',
        success: function(json)
        {
            componentes = json;
        },
        error: function(jqXHR, status, error) {
        	console.log(error);
        }
    })
	
	$("#añadirComp").click(function() {
		
		var codigo = 
			'<div class="form-group">'+
				'<div class="componente">'+
					'<div>'+
						'<label for="componente'+cont+'">Componente</label><br>'+
						'<select name="componente'+cont+'" id="componente'+cont+'" class="form-control">'+
							'<option>- Elige un componente -</option>'+
						'</select>'+
					'</div>'+
					'<div class="unidades">'+
        				'<label for="unidades'+cont+'">Unidades</label>'+
	        			'<input type="number" name="unidades'+cont+'" id="unidades'+cont+'" class="form-control" min="1"  value="1"/>'+
	    			'</div>'+
				'</div>'+
			'</div>';
		
		$(codigo).insertBefore($('#crearPresu'));
		
		$.each(componentes, function(key, value) {
			  $("#componente"+cont).append('<option value="'+value["codArticulo"]+'">'+value["nombre"]+' ('+value["precioVenta"]+'€)</option>');
		});
		
		cont++;
	});
	
})
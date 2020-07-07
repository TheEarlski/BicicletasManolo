$(document).ready(function() {
		
	$("#unidades").keyup(function() {
		$("#importeFinal").val((( parseFloat($("#importeBase").val()) + parseFloat($("#iva").val()) ) * ( 1 - (parseFloat($("#descuento").val())/100 ))) * parseInt($("#unidades").val()));
	})
	
	
	
	if(parseInt($("#tipoArt").val()) == 3) {
		$("#importeBase").keyup(function() {
			$("#importeFinal").val((( parseFloat($("#importeBase").val()) + parseFloat($("#iva").val()) ) * ( 1 - (parseFloat($("#descuento").val())/100 ))) * parseInt($("#unidades").val()));
		})
	}
	else {
		$("#importeBase").keyup(function() {
			$("#iva").val((parseFloat($("#importeBase").val()) * 0.21).toFixed(2));
			$("#importeFinal").val((( parseFloat($("#importeBase").val()) + parseFloat($("#iva").val()) ) * ( 1 - (parseFloat($("#descuento").val())/100 ))) * parseInt($("#unidades").val()));
		})
	}
	
	$("#descuento").keyup(function() {
		$("#importeFinal").val(((( parseFloat($("#importeBase").val()) + parseFloat($("#iva").val()) ) * ( 1 - (parseFloat($("#descuento").val())/100 ))) * parseInt($("#unidades").val())).toFixed(2));
	})
	
})
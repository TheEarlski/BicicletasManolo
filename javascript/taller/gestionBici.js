$(document).ready(function() {
	
	$("#btnModificar").click(function() {
		$("#modificar").attr('hidden', false);
		$("#borrar").attr('hidden', true);
	})
	
	$("#btnBorrar").click(function() {
		$("#modificar").attr('hidden', true);
		$("#borrar").attr('hidden', false);
	})
	
})
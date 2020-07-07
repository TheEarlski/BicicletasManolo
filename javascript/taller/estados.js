$(document).ready(function() {
	
	$("#switchReparado").click(function() {
		if($("#switchReparado").prop('checked')) {
			$("#switchRecogido").attr('disabled', false);
		}
		else {
			$("#switchRecogido").attr('disabled', true);
		}
	})
	
	$("#switchRecogido").click(function() {
		if($("#switchRecogido").prop('checked')) {
			$("#switchReparado").attr('disabled', true);
		}
		else {
			$("#switchReparado").attr('disabled', false);
		}
	})
	
	
})
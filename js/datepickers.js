/**
 *	Controladores de campos de tipo fecha
 *
 *
 */
$(function() { $('#dtpick1').datetimepicker({
		autoclose: true,
	}).on('changeDate', function(ev){
		$(this).datetimepicker('hide');
	}); 
});
$(function() { $('#dtpick2').datetimepicker({
		autoclose: true,
	}).on('changeDate', function(ev){
		$(this).datetimepicker('hide');
	}); 
});

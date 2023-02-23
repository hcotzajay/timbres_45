	function CargarGrid(page){
		var datos = "reporte="+page+"&usuario="+$('#usrname').val()+"&tabla="+$("#tabla").val()+
		"&accion="+$("#accion").val()+"&fechaIni="+$("#fechaIni").val()+"&fechaFin="+$("#fechaFin").val();
		
		$.ajax({
			type: "POST",
			url: "BitacoraI.php",
			data: datos,
                        dataType: "xml",
			success: function(msg){
                            listbita.xml = msg;
                            listbita.doTable();
                            listbita.doPagineo();
			}
		});
	}

$('#listbita li').live('click', function(){
    var a = $(this).text();
    CargarGrid(a);	
});

$('#listbita .dg2_form').live('submit', function(){
    var a = $('#listbita .dg2_in').val();
    CargarGrid(a);
    $('#listbita .dg2_in').val("");
});


$(document).ready(function(){
    ShowOne('forma', 'forma1');
    listbita = new DataGrid2('listbita');
	CargarCombos("BitacoraI.php", "operacion", "accion");
	CargarCombos("BitacoraI.php", "tablas", "tabla");
	
	$('form').submit(function(){
		if($("#tabla").val() == "-1")
			setMensaje("Debe seleccionar una tabla", "info");
		else
			CargarGrid(1);
	});
	
	$('.linkXLS').click(function(){
		var url = "BitacoraI.php?generareporte=&formato=xls&usuario="+$('#usrname').val()+"&tabla="+$("#tabla").val()+
		"&accion="+$("#accion").val()+"&fechaIni="+$("#fechaIni").val()+"&fechaFin="+$("#fechaFin").val();
		window.open(url);
	});
	
	Calendar.setup({
		inputField : "fechaIni",
		ifFormat : "%d/%m/%Y %H:%M",
		button : "butFechaIni",
		showsTime : true
	});
	
	Calendar.setup({
		inputField : "fechaFin",
		ifFormat : "%d/%m/%Y %H:%M",
		button : "butFechaFin",
		showsTime : true
	});
	
});
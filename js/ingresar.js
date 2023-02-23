$(document).ready(function(){
    ShowOne('forma', 'panel');
    /*
     * 
    fecha =  new Date();
    $('#infecha').val(fecha.getDate() + "/" + (fecha.getMonth() +1) + "/" + fecha.getFullYear());
    */
    ///nuevo
    listadonotario = new DataGrid3({
        contenedor: 'listadonotario',
        url: "iface/timbres.php",
        paraListar: "SelectNotario",
        actions: [
            {clase:"seleccion", funcion: "CargarNotario"}
        ]
    });
    listadonotario.doEventos();
    
    GBM_notario = new DataGrid3({
        contenedor: "GBM_notario",
        url: "iface/timbres.php",

        actions: [
            {clase:"seleccion", funcion: "CargarNotario"}
        ]
    });
    GBM_notario.doEventos();
    
   /*para la fecha
   Calendar.setup({
       inputField: "infecha",
       ifFormat: "%d/%m/%Y",
       button: "butfecha"
   });
   */
   $('#bus_gbm').click(function(){
       bus_gbm();
   });
   
   $('#buscar_notario').click(function(){
       ShowHide('forma','listanotario','in_listadonotario');
   });
   $('#bus_notario').click(function(){
       ShowHide('forma', 'panel', 'listanotario');
       SelectNotario(1);        
   });
   $('#notario_nombre').click(function(){
       ShowHide('forma', 'panel', 'listanotario');
       SelectNotario(1);
   });
   
   $('#backLink01').click(function(){
       ShowHide('forma', 'listanotario', 'panel');
   });
   $('#backLink02').click(function(){
      ShowHide('forma', 'in_listadonotario', 'listanotario') ;
   });
   $('#ingreso').live('click',function(){
      IngresarTimbre(); 
   });
   $('#buscar').live('click',function(){
       SelectNotario();
   });
});
/*$('#ingreso').live('click',function(){
      IngresarTimbre(); 
   });*/
   
//buscar con gbm
function bus_gbm(){
    var datos="&colegiado="+$('#buscole_notario').val();
    GBM_notario.setData('bus_gbm='+datos);
    GBM_notario.makeDataGrid(1);

}
//esto es para el listado de notarios
function SelectNotario(page){
    var datos="&colegiado="+$('#buscarcolegiado').val()+
            "&nombre="+$('#buscarnombre').val()+
            "&apellido="+$('#buscarapellido').val();
    listadonotario.setData("selectnotario="+datos);
    listadonotario.makeDataGrid(page);
}

///esto es para el ingreso de timbres
function IngresarTimbre(){
    if($('#nodoc').val().length == 12){
        if( $('#notario_id').val() != ""){
        var datos="&numero_documento="+$('#nodoc').val().toUpperCase()+
                "&sticker="+$("#nosti").val()+
                "&valor="+$("#valor").val()+".00"+
                "&notario_colegiado="+$("#notario_id").val();
                //"&fecha_ingreso="+$("#infecha").val();
              $.ajax({
                 type:"POST",
                 url:'iface/timbres.php',
                 data:'ingresotimbres='+datos,
                 dataType:'XML',
                 success: function(xml){
                    var res = setXMLMensaje(xml);   
                  }
               });       
        }else{//$("#mensaje").html("El Numero de Documento no es correcto");
            //$("#mensaje").css("style","display: block;");}
            setMensaje("Seleccione un Notario","info");
         }
    }else{//$("#mensaje").html("El Numero de Documento no es correcto");
            //$("#mensaje").css("style","display: block;");}
            setMensaje("El Numero de Documento no cumple con 12 digitos","info");
         }
}

//esto es para cargar a los notarios
function CargarNotario(id){
   var datos="&colegiado="+id;
   $.ajax({
     type:"POST",
     url:"iface/timbres.php",
     data:"lognotario="+datos,
     dataType:"XML",
     success: function(xml){
         var datos = GetCampos('notarios',xml);
         $('#notario_id').val(datos['colegiado']);
         $('#notario_nombre').val(datos['colegiado']);
         ShowHide('forma', 'listanotario', 'panel');
     }
  });
  $('#buscarcolegiado').val('');
  $('#buscarnombre').val('');
  $('#buscarapellido').val('');
  $('#buscole_notario').val('');
}
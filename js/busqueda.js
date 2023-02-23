$(document).ready(function(){
    ShowOne('forma', 'panel');
   ///para la fecha
    Calendar.setup({
        inputField: "bus_fecha01",
        ifFormat: "%d/%m/%Y",
        button:"butfecha01"
    });
    
    
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
    listadonotario2 = new DataGrid3({
        contenedor: 'listadonotario2',
        url: "iface/timbres.php",
        paraListar: "SelectNotario2",
        actions: [
            {clase:"seleccion", funcion: "CargarNotario2"}
        ]
    });
    listadonotario2.doEventos();
    //listado de documentos buscados
    listadordocumentos = new DataGrid3({
       contenedor: 'listadordocumentos',
       url: 'iface/timbres.php',
       paraListar: 'BuscarDocumento',
       actions:[
           {clase:"editar", funcion: "EditarDocumento"}
       ]
    });
    listadordocumentos.doEventos();
    
   $('#bus_notario').click(function(){
       ShowHide('forma', 'panel', 'listanotario');
       SelectNotario(1);
   });
   $('#notario_nombre').click(function(){
       ShowHide('forma', 'panel', 'listanotario');
       SelectNotario(1);
   });
   $('#bus_notario2').click(function(){
       ShowHide('forma', 'editform', 'listanotario2');
       SelectNotario2(1);
   });
   $('#notario_edit').click(function(){
       ShowHide('forma', 'editform', 'listanotario2');
       SelectNotario2(1);
   });
   
   $('#backtolist').click(function(){
       ShowHide('forma', 'listanotario', 'panel');
   });
   $('#backtolist2').click(function(){
       ShowHide('forma', 'listanotario2', 'editform');
   });
   $('#backtolist3').click(function(){
       ShowHide('forma', 'editform', 'panel');
   });
   $('#bus_ingreso').click(function(){
       BuscarDocumento(1);
   });
   BuscarDocumento(1);
   $('#buscar_notario').click(function(){
       SelectNotario(1);
   });
   $('#buscar_notario2').click(function(){
      SelectNotario2(1); 
   });
   $('#save_edit').click(function(){
      GuardarEdicion();
   });
});
//esto es para el listado de notarios
function SelectNotario(page){
   var datos="&colegiado="+$("#buscarcolegiado").val()+
               "&nombre="+$("#buscarnombre").val()+
               "&apellido="+$("#buscarapellido").val();
    listadonotario.setData("selectnotario="+datos);
    listadonotario.makeDataGrid(page);
}
function SelectNotario2(page){
   var datos="&colegiado="+$("#buscarcolegiado2").val()+
               "&nombre="+$("#buscarnombre2").val()+
               "&apellido="+$("#buscarapellido2").val();
    listadonotario2.setData("selectnotario="+datos);
    listadonotario2.makeDataGrid(page);
}
// esto es para la busqueda de documentos
function BuscarDocumento(page){
    var datos="&numero_documento="+$("#bus_nodoc").val()+
            "&sticker="+$("#bus_nosti").val()+
            "&notario="+$("#notario_id").val()+
            "&fecha1="+$("#bus_fecha01").val()+
            "&fecha2="+$("#bus_fecha02").val()+
            "&editar=1";
    listadordocumentos.setData("bus_documento="+datos);
    listadordocumentos.makeDataGrid(page);
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
         var datos = GetCampos('documento',xml);
         $('#notario_id').val(datos['colegiado']);
         $('#notario_nombre').val(datos['colegiado']);
         ShowHide('forma', 'listanotario', 'panel');
     }
  });
  $("#buscarcolegiado").val("");
  $("#buscarnombre").val("");
  $("#buscarapellido").val("");
}
function CargarNotario2(id){
   var datos="&colegiado="+id;
   $.ajax({
     type:"POST",
     url:"iface/timbres.php",
     data:"lognotario="+datos,
     dataType:"XML",
     success: function(xml){
         var datos = GetCampos('documento',xml);
         $('#notario_id_edit').val(datos['colegiado']);
         $('#notario_edit').val(datos['nombre_notario']);
         ShowHide('forma', 'listanotario2', 'editform');
     }
  });
  $("#buscarcolegiado2").val("");
  $("#buscarnombre2").val("");
  $("#buscarapellido2").val("");
}
///esto es para editar los datos del documento  
function GuardarEdicion(){
    var datos="&id="+$('#do_id_edit').val()+
            "&numero_documento="+$('#do_edit').val().toUpperCase()+
            "&sticker="+$('#stiker_edit').val()+
            "&valor="+$('#valor_edit').val()+
            "&notario_colegiado="+$('#notario_id_edit').val()+
            "&fecha_ingreso="+$('#fecha_edit').val();
    $.ajax({
       type:'POST',
       url:'iface/timbres.php',
       data:'editor_documento='+datos,
       dataType:'XML',
       success: function(xml){
           var res = setXMLMensaje(xml);
       }
    });
}
///esto es para editar los documentos
function EditarDocumento(id){
    var datos="&id="+id;
    $.ajax({
     type:"POST",
     url:"iface/timbres.php",
     data:"edit_document="+datos,
     dataType:"XML",
     success: function(xml){
         var datos = GetCampos('notario',xml);
         $('#do_id_edit').val(datos['id']);
         $('#do_edit').val(datos['numero_documento']);
         $('#stiker_edit').val(datos['sticker']);
         $('#valor_edit').val(datos['valor']);
         $('#fecha_edit').val(datos['fecha_ingreso']);
         $('#notario_edit').val(datos['notariostr']);
         $('#notario_id_edit').val(datos['notario_colegiado']);
         ShowHide('forma', 'panel', 'editform');
     }
  });
}

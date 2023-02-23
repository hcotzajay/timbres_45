function reload() {
    location.reload();
}

$(document).ready(function(){
   ShowOne('forma', 'panel');
   $('#in_egreso').click(function(){
       if($("#nombre_recibe").val()!=""){
           var datos="&receptor_documento_dpi="+$("#id_recep").val()+
       "&id="+$('#id_doc').val();
        IngresoEgreso(datos);
       }else{setMensaje("Seleccione a la persona que retira el documento","error");}
   });
   $('#no_notario').click(function(){
      ShowOne('forma','bus_documento','bus_notario');
   });
   $('#bus_doc').click(function(){
       BuscarDocumento(1);
      ShowOne('forma','bus_documento','panel');
   });
   $('#documento').click(function(){
       BuscarDocumento(1);
      ShowOne('forma','bus_documento','panel');
   });
   $('#bus_doc_not').click(function(){
       BuscarNotario(1);
      ShowOne('forma','bus_notario','panel');
   });
   $('#doc_notario').click(function(){
       BuscarNotario(1);
      ShowOne('forma','bus_notario','panel');
   });
   $('#nombre_recibe').click(function(){
       BuscarReceptor(1);
      ShowOne('forma','bus_receptor','panel');
   });
   $('#bus_rec').click(function(){
       BuscarReceptor(1);
      ShowOne('forma','bus_receptor','panel');
   });
   $('#no_buscar').click(function(){
      ShowOne('forma','panel','bus_documento') ;
   });
   $('#no_recep').click(function(){
      ShowOne('forma','panel','bus_receptor') ;
   });
   $('#no_recep2').click(function(){
      ShowOne('forma','bus_receptor','ingreso_recep') ;
   });
   $('#buscar1').click(function(){
       BuscarDocumento(1);
   });
   $('#buscar2').click(function(){
       BuscarReceptor(1);
   });
   $("#buscar3").click(function(){
       BuscarNotario(1);
   });
   $("#nuevo_recep").click(function(){
      ShowOne('forma','ingreso_recep','panel');
   });
   $("#grabar_resep").click(function(){
      var datos="&nombre="+$("#in_nombre").val()+
              "&apellido="+$("#in_ape").val()+
              "&dpi="+$("#in_dpi").val()+
              "&cedula="+$("#in_cedu").val()+
              "&numero_telefonico="+$("#in_numero").val()+
              "&correo="+$("#in_correo").val()+
              "&direccion="+$("#in_dire").val();
       IngresodeReceptor(datos);
   });
   listadorecep =  new DataGrid3({
      contenedor: "resu_doc2",
      url:"iface/timbres.php",
      paraListar:"BuscarReceptor",
      actions:[
          {clase:"seleccion", funcion:"CargarReceptor"}
      ]
   });
   listadorecep.doEventos();
   listadonota = new DataGrid3({
       contenedor:"resu_doc3",
       url:"iface/timbres.php",
       paraListar:"BuscarNotario",
       actions:[
           {clase:"seleccion", funcion:"CargarNotario"}
       ]
   });
   listadonota.doEventos();
   listadodocumento = new DataGrid3({
      contenedor: 'resu_doc',
      url:"iface/timbres.php",
      paraListar:"BuscarDocumento",
      actions:[
          {clase:"seleccion", funcion:"CargarDocumento"}
      ]
   });
   listadodocumento.doEventos();

});
function BuscarNotario(page){
   var datos="&colegiado="+$("#not_cole").val()+
           "&nombre_notario="+$("#not_name").val()+
           "&apellido_notario="+$("#not_ape").val();
   listadonota.setData('selectnotario='+datos);
   listadonota.makeDataGrid(page);
}
function BuscarDocumento(page){
   var datos="&numero_documento="+$("#doc_bus").val()+
           "&notario_colegiado="+$("#doc_notario2").val()+
           "&selec=1";
   listadodocumento.setData('bus_documento='+datos);
   listadodocumento.makeDataGrid(page);
}
function BuscarReceptor(page){
   var datos="&nombre="+$('#recep_name').val()+
           "&apellido="+$('#recep_ape').val()+
           "&dpi="+$('#recep_dpi').val()+
           "&cedula="+$('#recep_cedu').val();
   listadorecep.setData("selectrecep="+datos);
   listadorecep.makeDataGrid(page);
}
function CargarNotario(id){
    var datos="&colegiado="+id;
    $.ajax({
       type:'POST',
       url:'iface/timbres.php',
       data:'lognotario='+datos,
       dataType:'XML',
       success: function(xml){
           if (xml != null) {
                var datos = GetCampos('notario', xml);
                $('#doc_notario2').val(datos['colegiado']);
                $('#doc_notario').val(datos['colegiado']);
                ShowOne('forma', 'bus_documento', 'bus_notario');
            }
            else
                reload();
       }
    });
    $('#not_cole').val('');
    $('#not_name').val('');
    $('#not_ape').val('');
}
function CargarDocumento(id){
    var datos= "&id="+id;
    $.ajax({
       type:'POST',
       url:'iface/timbres.php',
       data:'egre_document='+datos,
       dataType:'XML',
       success: function(xml){
           if (xml != null) {
                var datos = GetCampos('documento', xml);
                $('#id_doc').val(datos['id']);
                $('#documento').val(datos['numero_documento']);
                ShowOne('forma', 'panel', 'bus_documento');
            }
            else
                reload();
       }
    });
    $('#doc_bus').val('');
    $('#doc_notario').val('');
}
function CargarReceptor(id){
    var datos="&dpi="+id;
    $.ajax({
       type:"POST",
       url:'iface/timbres.php',
       data:"cargarreceptor="+datos,
       dataType:'XML',
       success:function(xml){
           if (xml != null) {
                var datos = GetCampos('receptor_documento', xml);
                $('#id_recep').val(datos['dpi']);
                $('#nombre_recibe').val(datos['nombre']);
                ShowOne('forma', 'panel', 'bus_receptor');
            }
            else
                reload();
       }
    });
    $('#recep_name').val('');
    $('#recep_ape').val('');
    $('#recep_dpi').val('');
    $('#recep_cedu').val('');
}
function IngresoEgreso(datos){
   $.ajax({
       type:"POST",
       url:"iface/timbres.php",
       data: "in_egreso="+datos,
       dataType:"XML",
       success:function(xml){
           if (xml != null)
                var res = setXMLMensaje(xml);
            else
                reload();
       }
   });
}
function IngresodeReceptor(datos){
   if(/^[0-9]+$/.test($('#in_numero').val())){
    if(/^[0-9]+$/.test($('#in_dpi').val())){
         if($('#in_dpi').val().length == 13){
              $.ajax({
                 type:"POST",
                 url:"iface/timbres.php",
                 data:'in_egresor='+datos,
                 dataType:"XML",
                 success:function(xml){
                     if (xml != null) {
                            var res = setXMLMensaje(xml);
                            CargarReceptor(res['id']);
                        }
                        else
                            reload();
                 }
              });
         }else{//$("#mensaje").html("El Numero de Documento no es correcto");
                  //$("#mensaje").css("style","display: block;");}
                  $('#in_dpi').focus();
                  setMensaje("El Numero de DPI no cumple con 13 digitos","info");
         }
     }else{
         $('#in_dpi').focus();
         setMensaje("El DPI es solo numerico por favor ingrese denuevo el numero de DPI","error");
     }
   }else{
         $('#in_numero').focus();
         setMensaje("El Numero Telefonico es solo numerico por favor ingrese denuevo el numero de telefono","error");
     }
}
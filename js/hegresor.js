function reload() {
    location.reload();
}

$(document).ready(function(){
    ShowOne('forma','panel');
    $('#bus_doc').click(function(){
       ShowOne('forma','for_doc','panel');
       Listado(1);
    });
    $("#back").click(function(){
       ShowOne('forma','panel','for_doc');
    });
    $("#doc").click(function(){
       ShowOne('forma','for_doc','panel');
       Listado(1);
    });
    $("#buscar").click(function(){
        Listado();
    });
    $('#in_egreso').click(function(){
        Egresar();
    });
    listadodocumento = new DataGrid3({
        contenedor:"resu_doc",
        url:"iface/egresoh.php",
        paraListar:"Listado",
        actions:[
            {clase:"selec", funcion:"CargarDoc"}
        ]
    });
    listadodocumento.doEventos();
});
function Listado(page){
    var datos = "&documento="+$("#doc_bus").val()+
                "&sticker="+$("#doc_stiker").val();
        listadodocumento.setData('listar='+datos);
        listadodocumento.makeDataGrid(page);
}
function CargarDoc(id){
    var datas="&id="+id;
    $.ajax({
       type:"POST",
       url:"iface/egresoh.php",
       data:"bus_doc="+datas,
       dataType:"XML",
       success: function(xml){
            if (xml != null) {
                var datos = GetCampos('documento', xml);
                $('#id_doc').val(datos['id']);
                $('#doc').val((datos['nodocumento']));
                ShowOne('forma', 'panel', 'for_doc');
            }
            else
                reload();
       }
    });
    $("#doc_bus").val('');
    $("#doc_stiker").val('');
}
function Egresar(){
   var datos="&id="+$('#id_doc').val()+
           "&nombrerecibe="+$('#name').val()+
           "&nocedula="+$('#nocedula').val();
   $.ajax({
       type:'POST',
       url:'iface/egresoh.php',
       data:'egreso='+datos,
       dataType:"XML",
       success: function(xml){
            if (xml != null)
              var res = setXMLMensaje(xml);
            else
                reload();
       }
   });
}


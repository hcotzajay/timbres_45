$(document).ready(function(){
    ShowOne('forma','panel');
    Calendar.setup({
       inputField:"fech" ,
       ifFormat:"%d/%m/%Y",
       button:"bus_fech",
       showsTime:true
    });
    Calendar.setup({
       inputField:"fech2" ,
       ifFormat:"%d/%m/%Y",
       button:"bus_fech2",
       showTime:true
    });
    $("#bus_not").click(function(){
        Notario();
    });
    $('.backLink').click(function(){
       ShowOne('forma','panel');
    });
    $('#submit_exel').click(function(){
        CargarReporte2();
    });
    $('#submit_pdf').click(function(){
        CargarPDF2();
    });
    $('#submit_repo').click(function(){
        BuscarHistorico();
    });
    $('#bus_notario').click(function(){
        BuscarNotario();
    });
    $('#not').click(function(){
        Notario();
    });
    listadonotario= new DataGrid3({
       contenedor:"div_not" ,
       url:"iface/timbres.php",
       paraListar:"BuscarNotario",
       actions:[
           {clase:"seleccion", funcion:"CargarNotario"}
       ]
    });
    listadonotario.doEventos();
    listadocumento = new DataGrid3({
        contenedor:'div_repo',
        url:"iface/historico.php",
        paraListar:"BuscarHistorico",
        actions:[
            {clase:"reporte", funcion:"CargarReporte"},
            {clase:"reportep", funcion:"CargarPDF"}
        ]
    });
    listadocumento.doEventos();
    BuscarHistorico(1);
});
function BuscarHistorico(page){
    var datos="&documento="+$("#doc").val()+
                "&notario="+$("#not").val()+
                "&valor="+$("#valor").val()+
                "&valora="+$("#valor_a").val()+
                "&fecha1="+$("#fech").val()+
                "&fecha2="+$("#fech2").val()+
                "&estados="+$("#estado").val();
        listadocumento.setData('listar='+datos);
        listadocumento.makeDataGrid(page);
}
function Notario(){
    ShowOne('forma','div_notario','panel');
    BuscarNotario();
}
function BuscarNotario(page){
    var datos="&colegiado="+$("#buscarcolegiado").val()+
            "&nombre="+$("#buscarnombre").val()+
            "&apellido="+$("#buscarapellido").val();
    listadonotario.setData('selectnotario='+datos);
    listadonotario.makeDataGrid(page);
}
function CargarNotario(id){
    var datos="&colegiado="+id;
    $.ajax({
       type:"POST",
       url:"iface/timbres.php",
       data:"lognotario="+datos,
       dataType:"XML",
       success: function(xml){
           var datos= GetCampos('notario',xml);
           $("#id_not").val(datos['colegiado']);
           $("#not").val(datos['colegiado']);
           ShowOne('forma','panel','div_notario');
       }
    });
    $("#buscarapellido").val('');
    $("#buscarnombre").val('');
    $("#buscarcolegiado").val('');
}
function CargarReporte(id){
   window.open("iface/historico.php?generarcvr=&reporte="+id);
}
function CargarReporte2(){
    window.open("iface/historico.php?generarcvr=&documento="
           +$("#doc").val()+
                "&notario="+$("#not").val()+
                "&valor="+$("#valor").val()+
                "&valora="+$("#valor_a").val()+
                "&fecha1="+$("#fech").val()+
                "&fecha2="+$("#fech2").val()+
                "&estados="+$('#estado').val()+
                "&inicio1="+$('span.dg2_actual').html()+
                "&cant=100000");
}
function CargarPDF(id){
    window.open("iface/historico.php?generarpdf=&reporte="+id);
}
function CargarPDF2(){
    window.open("iface/historico.php?generarpdf=&documento="
           +$("#doc").val()+
                "&notario="+$("#not").val()+
                "&valor="+$("#valor").val()+
                "&valora="+$("#valor_a").val()+
                "&fecha1="+$("#fech").val()+
                "&fecha2="+$("#fech2").val()+
               "&estados="+$("#estado").val()+
                "&inicio1="+$('span.dg2_actual').html()+
                "&cant=100000");
}
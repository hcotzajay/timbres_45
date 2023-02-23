$(document).ready(function(){
    ShowOne('forma','panel');
    Calendar.setup({
       inputField:"repo_fech" ,
       ifFormat:"%d/%m/%Y",
       button:"bus_repo_fech",
       showsTime:true
    });
    Calendar.setup({
       inputField:"repo_fech2" ,
       ifFormat:"%d/%m/%Y",
       button:"bus_repo_fech2",
       showsTime:true
    });
    $('#bus_repo_doc').click(function(){
        BuscarDocumento(1);
        ShowOne('forma','div_documento','panel');
    });
    $('#repo_doc').click(function(){
        BuscarDocumento(1);
        ShowOne('forma','div_documento','panel');
    });
    $('#bus_repo_not').click(function(){
       BuscarNotario(1);
       ShowOne('forma','div_notario','panel');
    });
    $('#repo_not').click(function(){
       BuscarNotario(1);
       ShowOne('forma','div_notario','panel');
    });
    $('#bus_repo_egre').click(function(){
       BuscarEgresor(1);
       ShowOne('forma','div_egresor','panel');
    });
    $('#repo_egre').click(function(){
       BuscarEgresor(1);
       ShowOne('forma','div_egresor','panel');
    });
    $(".backLink").click(function(){
       ShowOne('forma','panel');
    });
    $("#bus_documento").click(function(){
       BuscarDocumento(1);
    });
    $("#bus_notario").click(function(){
       BuscarNotario(1);
    });
    $("#bus_egresor").click(function(){
        BuscarEgresor(1);
    });
    $('#submit_repo').click(function(){
        BuscarReporte(1);
    });
    $('#submit_exel').click(function(){
        CargarReporte2();
    });
    $('#submit_pdf').click(function(){
        CargarPDF2();
    });
    listadodocumento= new DataGrid3({
        contenedor:'div_bus_doc',
        url:"iface/timbres.php",
        paraListar:"BuscarDocumento",
        actions:[
            {clase:"seleccion", funcion:"CargarDocumento"}
        ]
    });
    listadodocumento.doEventos();
    listadonotario= new DataGrid3({
       contenedor:"div_bus_not" ,
       url:"iface/timbres.php",
       paraListar:"BuscarNotario",
       actions:[
           {clase:"seleccion", funcion:"CargarNotario"}
       ]
    });
    listadonotario.doEventos();
    listadoegresor= new DataGrid3({
       contenedor:"div_bus_egresor",
       url:"iface/timbres.php",
       paraListar:"BuscarEgresor",
       actions:[
           {clase:"seleccion", funcion:"CargarEgresor"}
       ]
    });
    listadoegresor.doEventos();
    listadoreporte= new DataGrid3({
        contenedor:"div_bus_repo",
        url:"iface/report.php",
        paraListar:"BuscarReporte",
        actions:[
            {clase:"reporte", funcion:"CargarReporte"},
            {clase:"reportep", funcion:"CargarPDF"}
        ]
    });
    listadoreporte.doEventos();
    BuscarReporte();
});
function BuscarReporte(page){
    var datos="&documento="+$("#repo_doc").val()+
                "&notario="+$("#repo_not").val()+
                "&valor="+$("#repo_valor").val()+
                "&valora="+$("#repo_valor_a").val()+
                "&fecha1="+$("#repo_fech").val()+
                "&fecha2="+$("#repo_fech2").val()+
                "&egresor="+$("#repo_egre").val()+
                "&estados="+$("#repo_estado").val();
    listadoreporte.setData('bus_report='+datos);
    listadoreporte.makeDataGrid(page);
}
function BuscarDocumento(page){
    var datos="&numero_documento="+$("#bus_doc").val()+
            "&sticker="+$("#bus_stiker").val()+
            "&selec=1";
    listadodocumento.setData('bus_documento='+datos);
    listadodocumento.makeDataGrid(page);
}
function BuscarNotario(page){
    var datos="&colegiado="+$("#buscarcolegiado").val()+
            "&nombre="+$("#buscarnombre").val()+
            "&apellido="+$("#buscarapellido").val();
    listadonotario.setData('selectnotario='+datos);
    listadonotario.makeDataGrid(page);
}
function BuscarEgresor(page){
    var datos="&nombre="+$("#recep_name").val()+
            "&apellido="+$("#recep_ape").val()+
            "&dpi="+$("#recep_dpi").val()+
            "&cedula="+$("#recep_cedu").val();
    listadoegresor.setData('selectrecep='+datos);
    listadoegresor.makeDataGrid(page);
}
function CargarDocumento(id){
    var datos="&id="+id;
    $.ajax({
        type:"POST",
        url:"iface/timbres.php",
        data:"edit_document="+datos,
        dataType:"XML",
        success: function(xml){
            var datos= GetCampos('documento', xml);
            $("#id_repo_doc").val(datos['id']);
            $("#repo_doc").val(datos['numero_documento']);
            ShowOne('forma','panel','div_documento');
        }
    });
    $('#bus_doc').val('');
    $("#bus_stiker").val('');
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
           $("#id_repo_not").val(datos['colegiado']);
           $("#repo_not").val(datos['colegiado']);
           ShowOne('forma','panel','div_notario');
       }
    });
    $('#buscarcolegiado').val('');
    $('#buscarnombre').val('');
    $('#buscarapellido').val('');
}
function CargarEgresor(id){
    var datos="&dpi="+id;
    $.ajax({
       type:"POST",
       url:"iface/timbres.php",
       data:"cargarreceptor="+datos,
       dataType:"XML",
       success: function(xml){
           var datos= GetCampos('receptor_documento',xml);
           $("#id_repo_egre").val(datos['dpi']);
           $("#repo_egre").val(datos['nombre']);
           ShowOne('forma','panel','div_egresor');
       }
    });
    $("#recep_name").val('');
    $("#recep_ape").val('');
    $("#recep_dpi").val('');
    $("#recep_cedu").val('');
}
function CargarReporte(id){
   window.open("iface/report.php?generarcvr=&reporte="+id);
}
function CargarReporte2(){
    window.open("iface/report.php?generarcvr=&documento="
           +$("#repo_doc").val()+
                "&notario="+$("#repo_not").val()+
                "&valor="+$("#repo_valor").val()+
                "&valora="+$("#repo_valor_a").val()+
                "&fecha1="+$("#repo_fech").val()+
                "&fecha2="+$("#repo_fech2").val()+
                "&egresor="+$("#repo_egre").val()+
                "&estados="+$('#repo_estado').val()+
                "&inicio1="+$('span.dg2_actual').html()+
                "&cant=2000");
}
function CargarPDF(id){
    window.open("iface/report.php?generarpdf=&reporte="+id);
}
function CargarPDF2(){
    window.open("iface/report.php?generarpdf=&documento="
           +$("#repo_doc").val()+
                "&notario="+$("#repo_not").val()+
                "&valor="+$("#repo_valor").val()+
                "&valora="+$("#repo_valor_a").val()+
                "&fecha1="+$("#repo_fech").val()+
                "&fecha2="+$("#repo_fech2").val()+
                "&egresor="+$("#repo_egre").val()+
                "&estados="+$("#repo_estado").val()+
                "&inicio1="+$('span.dg2_actual').html()+
                "&cant=2000");
}

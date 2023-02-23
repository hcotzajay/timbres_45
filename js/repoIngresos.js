/**
 * Created by lorozco on 16/08/2016.
 */

function listarRepoIngresos(page) {

    var datos = getDateParameter();
    listadoRepoIngresos.setData('repoIngresos=' + datos);
    listadoRepoIngresos.makeDataGrid(page);

}

function getReport(option) {

    var datos = getDateParameter();
    window.open('iface/report.php?'+option+'=&option=ingresos' + datos);

}

function getDateParameter() {

    var datos = '';

    var date = $('#fecha').val();
    var dateI = $('#fechaInicio').val();
    var dateF = $('#fechaFin').val();
    var optionA = $('#optionA');
    var optionI = $('#optionI');
    var doc = $('#documento').val();
    var sticker = $('#sticker').val();

    if (dateI != '' && dateF != '') {
        datos = '&dateI=' + dateI;
        datos += '&dateF=' + dateF;
    }
    else {
        datos = '&date=' + date;
    }

    if (optionA.is(':checked'))
        datos += '&opt=a';
    else if (optionI.is(':checked'))
        datos += '&opt=i';

    if (doc != '')
        datos = '&doc=' + doc;

    if (sticker != '')
        datos = '&sticker=' + sticker;

    return datos;

}

$(document).ready(function () {

    Calendar.setup({
        inputField: "fecha",
        ifFormat: "%d/%m/%Y",
        button: "btnCalendar"
    });
    Calendar.setup({
        inputField: "fechaInicio",
        ifFormat: "%d/%m/%Y",
        button: "btnCalendarI"
    });
    Calendar.setup({
        inputField: "fechaFin",
        ifFormat: "%d/%m/%Y",
        button: "btnCalendarF"
    });

    $('#btnRepoIngresos').click(function () {
        listarRepoIngresos(1);
    });

    $('#submit_pdf').click(function () {
        getReport('getPDF');
    });
    
    $('#submit_exel').click(function () {
        getReport('getExcel');
    });

    listadoRepoIngresos = new DataGrid3({
        contenedor: "div_bus_repo",
        url: "iface/report.php",
        paraListar: "listarRepoIngresos",
        actions: [
            {clase: "seleccion", funcion: "timbresIngresados"}
        ]
    });
    listadoRepoIngresos.doEventos();

    listarRepoIngresos();

});
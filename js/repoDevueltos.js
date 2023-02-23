/**
 * Created by lorozco on 17/08/2016.
 */

function listarRepoEgresos(page) {

    var datos = getDateParameter();
    listadoRepoEgresos.setData('repoEgresos=' + datos);
    listadoRepoEgresos.makeDataGrid(page);

}

function getReport(option) {

    var datos = getDateParameter();
    window.open('iface/report.php?'+option+'=&option=egresos' + datos);

}

function getDateParameter() {

    var datos = '';

    var date = $('#fecha').val();
    var dateI = $('#fechaInicio').val();
    var dateF = $('#fechaFin').val();
    var doc = $('#documento').val();
    var sticker = $('#sticker').val();

    if (dateI != '' && dateF != '') {
        datos = '&dateI=' + dateI;
        datos += '&dateF=' + dateF;
    }
    else {
        datos = '&date=' + date;
    }

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


    $('#btnRepoEgresos').click(function () {
        listarRepoEgresos(1);
    });

    $('#submit_pdf').click(function () {
        getReport('getPDF');
    });

    $('#submit_exel').click(function () {
        getReport('getExcel');
    });

    listadoRepoEgresos = new DataGrid3({
        contenedor: "div_bus_repo",
        url: "iface/report.php",
        paraListar: "listarRepoEgresos",
        actions: [
            {clase: "seleccion", funcion: "timbresDevueltos"}
        ]
    });
    listadoRepoEgresos.doEventos();

    listarRepoEgresos();

});
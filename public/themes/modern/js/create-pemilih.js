/**
 * Written by: Agus Prawoto Hadi
 * Year		: 2021
 * Website	: jagowebdev.com
 */

jQuery(document).ready(function () {

    $(".select2").select2();

    var mytable = document.getElementById("table-result");

    if (mytable != null) {
        column = $.parseJSON($('#dataTables-column').html());
        url = $('#dataTables-url').text();

        var settings = {
            "processing": true,
            "serverSide": true,
            "scrollX": true,
            "ajax": {
                "url": url,
                "type": "POST"
            },
            "columns": column,
            "initComplete": function (settings, json) {
                table.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    $row = $(this.node());
                    /* this
                     .child(
                     $(
                     '<tr>'+
                     '<td>'+rowIdx+'.1</td>'+
                     '<td>'+rowIdx+'.2</td>'+
                     '<td>'+rowIdx+'.3</td>'+
                     '<td>'+rowIdx+'.4</td>'+
                     '</tr>'
                     )
                     )
                     .show(); */
                });
            }
        }

        $add_setting = $('#dataTables-setting');
        if ($add_setting.length > 0) {
            add_setting = $.parseJSON($('#dataTables-setting').html());
            for (k in add_setting) {
                settings[k] = add_setting[k];
            }
        }

        table = $('#table-result').DataTable(settings);
    }
    
    $("#id_prov").on("change", function(e) {
        provid = $("#id_prov").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getDataKab',
            data: {
                filterid: provid,
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
    
    $("#id_kab").on("change", function(e) {
        kabid = $("#id_kab").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getDataKec',
            data: {
                filterid: kabid,
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
    
    $("#id_kec").on("change", function(e) {
        kabid = $("#id_kec").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getDataKel',
            data: {
                filterid: kabid,
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
    
    $("#id_kel").on("change", function(e) {
        kelid = $("#id_kel").val();
        provid = $("#id_prov").val();
        kabid = $("#id_kab").val();
        kecid = $("#id_kec").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getDataTps',
            data: {
                filterid: kelid,
                idprov: provid,
                idkab: kabid,
                idkec: kecid,
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
    
    $("#noTps").on("change", function(e) {
        kelid = $("#id_kel").val();
        provid = $("#id_prov").val();
        kabid = $("#id_kab").val();
        kecid = $("#id_kec").val();
        noTps = $("#noTps").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getDataDpt',
            data: {
                filterid: noTps,
                idkel: kelid,
                idprov: provid,
                idkab: kabid,
                idkec: kecid,
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
});
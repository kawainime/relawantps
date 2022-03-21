jQuery(document).ready(function () {
    $(".select2").select2();
    
    $("#provinsi").on("change", function(e) {
        provid = $("#provinsi").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getDataKab',
            data: {
                filterid: provid
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
    
    $("#kabupaten").on("change", function(e) {
        kabid = $("#kabupaten").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getDataDapilkab',
            data: {
                filterid: kabid
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
    
    $("#targetsuara").on("change", function(e) {
        targetsuara = $("#targetsuara").val();
        jmltps = $("#jmltps").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getJmlTpsBySuara',
            data: {
                target: targetsuara,
                jml: jmltps
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
    
    $("#dapilkab").on("change", function(e) {
        kabid = $("#dapilkab").val();
        targetsuara = $("#targetsuara").val();
        //        alert(provid);
        $.ajax({
            type: "GET",
            url: module_url + '/getJmlTps',
            data: {
                filterid: kabid,
                target: targetsuara
            },
            success: function(response) {
                if (response.length > 0) {
                    eval(response);
                }
            },
        });
    });
});
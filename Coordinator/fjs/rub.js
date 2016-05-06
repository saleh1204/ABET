$(document).ready(function () {
    $('#percentage').val('3');
    document.cookie = "Value =" + 3;
    $("#inputC1A").val("");
    $("#inputC2A").val("");
    $("#inputC3A").val("");
    $('#PName').text(getCookie('PName') + '-Program');
    $('#username').text(getCookie('email'));
    fillSelect();
    function fillSelect() {
        var parameters = {
            grp: "Coordinator",
            cmd: "getSO",
            PName: getCookie('PName')

        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    var s1 = $('#inputC1A');
                    // AJAX HERE
                    //var s1L = ["a", "b", "c", "d"];
                    for (var i = 0; i < data.length; i++) {
                        s1.append('<option value = "' + data[i].SOCode + '">' + data[i].SOCode + '</option>');
                    }
                    s1.val('');
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                    //return cl;
                });
        var parameters1 = {
            grp: "DBA",
            cmd: "getSemesters"
        };
        $.getJSON("../index.php", parameters1).done(
                function (data, textStatus, jqXHR)
                {
                    var s2 = $('#inputC2A');
                    // AJAX HERE
                    //var s2L = ["152", "142"];
                    for (var j = 0; j < data.length; j++) {
                        s2.append('<option value = "' + data[j].semesterNum + '">' + data[j].semesterNum + '</option>');
                    }
                    s2.val("");
                    var s3 = $('#inputC3A');
                    // AJAX HERE
                    // var s3L = ["152", "142"];
                    for (var j = 0; j < data.length; j++) {
                        s3.append('<option value = "' + data[j].semesterNum + '">' + data[j].semesterNum + '</option>');
                    }
                    s3.val("");
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                    //return cl;
                });
    }
    function generateTable() {
        // flush the table
        $("#tbody tr").remove();
        var col1 = [];//["ICS", "ICS"];
        var col2 = [];//["253", "254"];
        var col3 = [];//['141', '141'];
        var col4 = [];//["15", "11"];
        var col5 = [];//["1", "2"];
        var col6 = [];//["2.8", "2.82"];
        var col7 = [];//["73.3", "72.7"];
        /*************************************
         SELECT * FROM TABLE, TABLE, TABLE
         THIS SELECT NEEDS ALL COOKIES
         *************************************
         */
        var newC1 = document.getElementById("inputC1A").value;
        var newC2 = document.getElementById("inputC2A").value;
        var newC3 = document.getElementById("inputC3A").value;
        alert('Great');
        var parameters1 = {
            grp: "Coordinator",
            cmd: "getPCReport",
            beginTerm: newC2,
            endTerm: newC3,
            SOCode: newC1,
            pname: getCookie('PName'),
            surveyName: 'Rubrics-Based',
            value: 3
        };
        $.getJSON("../index.php", parameters1).done(
                function (data, textStatus, jqXHR)
                {
                    //[{"course":"ICS_102","semesternum":152,"pcnum":0,"avg":"1.7500","percent":"25.0000"}]
                    for (var j = 0; j < data.length; j++)
                    {
                        col2[j] = data[j].course;
                        col3[j] = data[j].semesternum;
                        col4[j] = data[j].pcnum;
                        col6[j] = data[j].avg;
                        col7[j] = data[j].percent;

                    }

                    var tb = $('#tbody');
                    var i = 0;
                    for (i = 0; i < col2.length; i++) {
                        var tr = $('<tr>').appendTo(tb);
                        tr.append('<td class = "col1">' + col1[i] + '-' + col2[i] + '</td>');
                        tr.append('<td class = "col2" style = "text-align: center;">' + col3[i] + '</td>');
                        tr.append('<td class = "col3" style = "text-align: center;">' + col4[i] + '</td>');
                        tr.append('<td class = "col4" style = "text-align: center;">' + col5[i] + '</td>');
                        tr.append('<td class = "col4" style = "text-align: center;">' + col6[i] + '</td>');
                        tr.append('<td class = "col4" style = "text-align: center;">' + col7[i] + '</td>');
                    }
                    $('#example').dataTable();
                    $('#example-keytable').DataTable({
                        keys: true
                    });
                    $('#example-responsive').DataTable();
                    $('#example-scroller').DataTable({
                        ajax: "./js/datatables/json/scroller-demo.json",
                        deferRender: true,
                        scrollY: 380,
                        scrollCollapse: true,
                        scroller: true
                    });
                    var table = $('#example-fixed-header').DataTable({
                        fixedHeader: true
                    });
                    TableManageButtons.init();
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                    //return cl;
                });

    }
    var $col1;
    var $col2;
    var $col3;
    var $col4;
    $("#demo-form2").on('click', '#save', function () {
        //alert("save 1");
        var newC1 = document.getElementById("inputC1A").value;
        var newC2 = document.getElementById("inputC2A").value;
        var newC3 = document.getElementById("inputC3A").value;
        //alert(newC1 + " " + newC2 + " " + newC3);
        //generateTable();
    });
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    $('#percentage').keyup(function (e) {
        if (e.which === 13) {
            var x = $('#percentage').val();
            // USE THIS IN THE SELECT STATEMENT
            document.cookie = "Value=" + x;
            generateTable();
        }
    });
});
$(document).ready(function () {

    $("#inputC1A").val("");
    $("#inputC2A").val("");
    $('#PName').text(getCookie('PName') + '-Program');
    $('#username').text(getCookie('email'));
    fillSelect();
    function fillSelect() {
        alert('there');
        var parameters1 = {
            grp: "DBA",
            cmd: "getSemesters"
        };
        $.getJSON("../index.php", parameters1).done(
                function (data, textStatus, jqXHR)
                {
                    var s1 = $('#inputC1A');
                    // AJAX HERE

                    for (var i = 0; i < data.length; i++) {
                        s1.append('<option value = "' + data[i].semesterNum + '">' + data[i].semesterNum + '</option>');
                    }
                    s1.val('');
                    var s2 = $('#inputC2A');
                    for (var i = 0; i < data.length; i++) {
                        s2.append('<option value = "' + data[i].semesterNum + '">' + data[i].semesterNum + '</option>');
                    }
                    s2.val('');
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                    //return cl;
                });

    }

    // FILL THE HEADER BY THE CLO ANSWERS PLUS THE AVERAGE FROM QUERY


    function generateTable() {
        // flush the table
        var parameters1 = {
            grp: "Coordinator",
            cmd: "getPCReport",
            beginTerm: newC2,
            endTerm: newC3,
            SOCode: newC1,
            pname: getCookie('PName')
        };
        $.getJSON("../index.php", parameters1).done(
                function (data, textStatus, jqXHR)
                {
                    $("#tbody tr").remove();
                    // THESE VALUES WILL COME FROM A SELECT
                    var col1 = [];//["a", "b", "c", "d"];
                    var col2 = [];//["3.8125", "3.8056", "3.9167", "2.99"];
                    for (var j=0; j<data.length; j++)
                    {
                        
                    }
                    var tb = $('#tbody');
                    for (var i = 0; i < col1.length; i++) {
                        var tr = $('<tr>').appendTo(tb);
                        tr.append('<td class = "col1">' + (i + 1) + '</td>');
                        tr.append('<td class = "col1">' + col1[i] + '</td>');
                        tr.append('<td class = "col2" style = "text-align: center;">' + col2[i] + '</td>');

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

    $("#demo-form2").on('click', '#save', function () {
        alert("save 1");
        var newC1 = document.getElementById("inputC1A").value;
        // USE THESE IN THE SELECT PLUS THE PNAME FROM THE COOKIE
        document.cookie = "Term =" + newC1


        alert(newC1);

        generateTable();

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



});
$(document).ready(function () {
    generateTable();
    $('#coursedetails').text('T' + getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    $('#username').text(getCookie('email'));
    document.cookie = "Value =" + 3;
    $('#percentage').val("3");
    function generateTable() {
        // flush the table
        var parameters = {
            grp: "Faculty",
            cmd: "getPCSummary",
            surveyType: "Rubrics-Based",
            semester: getCookie('Term'),
            courseCode: getCookie('CCode'),
            pname: getCookie('PName'),
            femail: getCookie('email'),
            value: $('#percentage').val()
        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    $("#tbody").remove();
                    var table = $("#example");
                    table.empty();
                    var col1 = [];//["a", "b", "c", "d"];
                    var col2 = [];//["1", "2", "3", "1"];
                    // ARRAY BASED ON THE RECORD FROM THE SELECT
                    //var col3 = ["12", "5", "5", "3"];
                    // var col4 = ["12", "5", "5", "5"];
                    // var col5 = ["12", "5", "5", "4"];
                    //var col6 = ["12", "5", "5", "4"];
                    // var col7 = ["3.2", "2.7", "3.3", "2.5"];
                    // 
                    /*************************************
                     SELECT * FROM TABLE, TABLE, TABLE
                     THIS SELECT NEEDS ALL COOKIES
                     *************************************
                     */
                    var th = $('<thead> <tr>');//.appendTo(table)
                    th.append('<th> PCnum </th>');
                    th.append('<th> SOCode </th>');
                    th.append('<th> Performance Criteria </th>');
                    for (var j = 0; j < data[0].NumberAnswers; j++)
                    {
                        th.append('<th> ' + data[0].answerNames[j] + ' </th>');
                    }
                    th.append('<th> Average </th>');
                    th.append('<th> (%) >= ' + $('#percentage').val() + ' </th>');
                    th.append('</tr> </thead>');
                    table.append(th);

                    var tb = $('#tbody');
                    var i = 0;
                    for (i = 0; i < data.length; i++) {
                        var tr = $('<tr>');
                        tr.append('<td>' + data[i].pcnum + '</td>');
                        
                        tr.append('<td>' + data[i].SOCode + '</td>');
                        tr.append('<td>' + data[i].Question + '</td>');
                        for (var j = 0; j < data[i].NumberAnswers; j++)
                        {
                            tr.append('<td> ' + data[i].answersValues[j] + ' </td>');
                        }

                        tr.append('<td>' + data[i].avg + '</td>');
                        tr.append('<td>' + data[i].percent + '</td>');
                        table.append(tr);
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
        ;
    }
    $('#percentage').keyup(function (e) {
        if (e.which === 13) {
            var x = $('#percentage').val();
            // USE THIS IN THE SELECT STATEMENT
            document.cookie = "Value=" + x;
            generateTable();
        }

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
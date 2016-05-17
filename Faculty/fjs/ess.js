$(document).ready(function () {
    generateTable();
    $('#coursedetails').text('T' + getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    $('#username').text(getCookie('email'));

    function generateTable() {

        /*
         * 
         * @type type
         * grp: "Faculty",
         cmd: "getSummary",
         surveyName: "CLO-Based",
         semester: getCookie('Term'),
         courseCode: getCookie('CCode'),
         pname: getCookie('PName'),
         femail: getCookie('email')
         */
        var parameters = {
            grp: "Faculty",
            cmd: "getSummary",
            surveyName: "Exit-Based",
            semester: getCookie('Term'),
            courseCode: getCookie('CCode'),
            pname: getCookie('PName'),
            femail: getCookie('email')
        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    // alert('retrieved data');
                    //$("#tbody tr").remove();
                    var table = $("#example");
                    table.empty();
                    var col1 = [];//["a", "b", "c", "d"];
                    var col2 = [];//["1", "2", "3", "1"];

                    var th = $('<thead> <tr>');//.appendTo(table)
                    th.append('<th> # </th>');
                    th.append('<th> Question </th>');
                    th.append('<th> SOCode </th>');
                    for (var j = 0; j < data[0].NumberAnswers; j++)
                    {
                        th.append('<th> ' + data[0].answerNames[j] + ' </th>');
                    }
                    th.append('<th> Average </th>');
                    th.append('</tr> </thead>');
                    table.append(th);

                    var tb = $('#tbody');
                    var i = 0;
                    for (i = 0; i < data.length; i++) {
                        var tr = $('<tr>');
                        tr.append('<td>' + data[i].Order + '</td>');
                        tr.append('<td>' + data[i].Question + '</td>');
                        tr.append('<td>' + data[i].SOCode + '</td>');

                        for (var j = 0; j < data[i].NumberAnswers; j++)
                        {
                            tr.append('<td> ' + data[i].answersValues[j] + ' </td>');
                        }

                        tr.append('<td>' + data[i].avg + '</td>');
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
    }
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
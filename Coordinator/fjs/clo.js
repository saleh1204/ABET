$(document).ready(function () {

    $("#inputC1A").val("");
    $('#PName').text(getCookie('PName') + '-Program');
    $('#username').text(getCookie('email'));
    fillSelect();
    $("#demo-form2").on('click', '#save', function () {
        //alert("save 1");
        var newC1 = document.getElementById("inputC1A").value;
        // USE THESE IN THE SELECT PLUS THE PNAME FROM THE COOKIE
        document.cookie = "Term =" + newC1
        //alert(newC1);
        generateTable();

    });




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

function generateTable() {
    // $request->get('semester'), $request->get('surveyName'), $request->get('pname')

    var newC1 = document.getElementById("inputC1A").value;
    var PName = getCookie("PName");
    //alert('updated');
    var parameters1 = {
        grp: "Coordinator",
        cmd: "getCLOReport",
        semester: newC1,
        surveyName: "CLO-Based",
        pname: getCookie("PName")
    };


    $.getJSON("../index.php", parameters1).done(
            function (data, textStatus, jqXHR)
            {
                //alert(data.length);
                var head, table, row;
                var tmpTableID = '';
                var courseCode = '';
                $("#tables").empty();
                var myTable = $("#tables");
                for (var j = 0; j < data.length; j++)
                {
                    //alert("JSON_CourseCode: " + data[j].courseCode + " HTML_CourseCode: " + courseCode);
                    var tmp = "" + data[j].courseCode;
                    if (tmp !== courseCode)
                    {
                        if (courseCode.length > 0)
                        {
                            // close the previous table
                            //alert('closing table');
                            table.append("</tbody> </table>");
                            myTable.append(table);
                         
                        }
                        courseCode = "" + data[j].courseCode;
                        // Create the table
                        table = '';
                        head = '';
                        head = $('<h4 id = "' + courseCode + '" style="text-align: center;">');
                        head.text(PName + "-" + courseCode);
                        myTable.append(head);
                        tmpTableID = PName + courseCode;
                        table = $('<table id= "' + tmpTableID + '" class="table table-striped table-bordered" style="text-align: center;">');//.appendTo($('#tables'));
                        var header = $('<tr>');

                        header.append('<th style="text-align: center;"> Question </th>');
                        for (var k = 0; k < data[j].answerCount; k++)
                        {
                            header.append('<th style="text-align: center;"> ' + data[j].answerNames[k] + " (" + data[j].answerValues[k] + ")</th>");
                        }
                        header.append('<th style="text-align: center;"> Average </th>');
                        header.append("</tr> </thead> <tbody>");
                        header.appendTo(table);
                    }
                    row = $('<tr>');
                    row.append('<td>' + data[j].Question + "</td>");
                    for (var k = 0; k < data[j].answerCount; k++)
                    {
                        row.append('<td> ' + data[j].answers[k] + " </td>");
                    }
                    row.append('<td>' + data[j].avg + "</td> </tr>");
                    row.appendTo(table);
                }
                table.append("</tbody> </table>");
                myTable.append(table);

            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                console.log(errorThrown.toString()); 
            });



}
function fillSelect() {
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
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(errorThrown.toString());
                //return cl;
            });


}
/*
 function beautifyTable(tableID)
 {
 $('#' + tableID).dataTable();
 $('#' + tableID + '-keytable').DataTable({
 keys: true
 });
 $('#' + tableID + '-responsive').DataTable();
 $('#' + tableID + '-scroller').DataTable({
 ajax: "./js/datatables/json/scroller-demo.json",
 deferRender: true,
 scrollY: 380,
 scrollCollapse: true,
 scroller: true
 });
 // var table = $('#' + tableID + '-fixed-header').DataTable({
 //    fixedHeader: true
 // });
 // TableManageButtons.init();
 }
 */
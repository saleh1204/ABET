$(document).ready(function () {

    $("#inputC1A").val("");
    $('#PName').text(getCookie('PName') + '-Program');
    $('#username').text(getCookie('email'));
    fillSelect();
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

    // FILL THE HEADER BY THE CLO ANSWERS PLUS THE AVERAGE FROM QUERY




    $("#demo-form2").on('click', '#save', function () {
        var newC1 = document.getElementById("inputC1A").value;
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
    /* LOOP OVER THE ABOVE ARRAY AND CALL A VIEW:
     EACH TIME PUT A HEADER OF THE CCODE AND THEN LOOP ON THE RECORDS UNTIL CCODE CHANGES
     NO EXAMPLE IS GIVEN HERE BECAUSE THIS IS MUCH SIMPLER THROUGH SQL
     JUST PUT THE AJAX STRUCTURE AND LEAVE THE REST
     {  ICS 324
     (Weakness, Action),
     (Weakness, Action),
     (Weakness, Action),
     .....
     ......
     ICS 353
     (.....)
     (.....)
     }
     
     ALL THIS IS DONE TO DIV WITH ID = "MAIN"
     THE FINAL RESULT IS IDENTICAL TO THE CLI-142.PDF FILE GIVEN BY DR.SALAH EXEPT THAT THE FORM IS TABULAR INSTEAD OF BULLET POINTS
     
     */
    var newC1 = document.getElementById("inputC1A").value;
    var PName = getCookie("PName");
    var parameters1 = {
        grp: "Coordinator",
        cmd: "getCLIReport",
        pname: getCookie("PName"),
        semester: newC1
    };
    $.getJSON("../index.php", parameters1).done(
            function (data, textStatus, jqXHR)
            {
                var courseCode = '';
                var tableDiv = $("#main");
                var weaknessCell, actionsCell;
                tableDiv.empty();
                var head, table, row;
                var tmpTableID = '';
                var courseCode = '';
                for (var j = 0; j < data.length; j++)
                {
                    //alert("JSON_CourseCode: " + data[j].courseCode + " HTML_CourseCode: " + courseCode);
                    var tmp = "" + data[j].CourseCode;
                    if (tmp !== courseCode)
                    {
                        if (courseCode.length > 0)
                        {
                            // close the previous table
                            //alert('closing table');
                            weaknessCell.append("</ul> </td>");
                            actionsCell.append("</ul> </td> </tr>");
                            table.append(weaknessCell);
                            table.append(actionsCell);
                            tableDiv.append(table);

                        }
                        courseCode = "" + data[j].CourseCode;
                        // Create the table
                        table = '';
                        head = '';
                        head = $('<h4 id = "' + courseCode + '" style="text-align: center;">');
                        head.text(PName + "-" + courseCode);
                        tableDiv.append(head);
                        tmpTableID = PName + courseCode;
                        table = $('<table id= "' + tmpTableID + '" class="table table-striped table-bordered" style="text-align: center;">');//.appendTo($('#tables'));
                        var header = $('<tr>');

                        header.append('<th style="text-align: center;"> Weakness </th>');
                        header.append('<th style="text-align: center;"> Actions </th>');
                        header.append("</tr> </thead> <tbody>");
                        header.append("<tr>");
                        weaknessCell = $("<td > <ul style='text-align: left;'>");
                        actionsCell = $("<td > <ul style='text-align: left;'>");
                        header.appendTo(table);
                    }
                    weaknessCell.append("<li>" + data[j].Weakness + "</li>");
                    actionsCell.append("<li>" + data[j].Actions + "</li>");

                }
                if (data.length > 0)
                {
                    weaknessCell.append("</ul> </td>");
                    actionsCell.append("</ul> </td> </tr>");
                    table.append(weaknessCell);
                    table.append(actionsCell);
                    tableDiv.append(table);
                }
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(errorThrown.toString());
                //return cl;
            });


}
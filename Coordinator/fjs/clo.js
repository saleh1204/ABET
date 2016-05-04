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


    function generateTable() {
        // flush the table
        //$("#main ").remove();
        // INITIALIZE AN ARRAY FROM ALL COURSES OF PNAME IN  A GIVEN TERM
        /* LOOP OVER THE ABOVE ARRAY AND CALL A VIEW:
         EACH TIME PUT A HEADER OF THE CCODE AND THEN LOOP ON THE RECORDS UNTIL CCODE CHANGES
         NO EXAMPLE IS GIVEN HERE BECAUSE THIS IS MUCH SIMPLER THROUGH SQL
         JUST PUT THE AJAX STRUCTURE AND LEAVE THE REST
         {  ICS 324
         (#, AFTER TAKING THIS ..., 4, 3, 2, 1, 0, AVERAGE),
         (1, ABLE TO DO ...., 49, 15, 17, 10, 5, 3.2)
         (2, ABLE TO ..., 15, 20, 10, 20, 4, 2.4)
         ......
         ICS 353
         (.....)
         (.....)
         }
         
         ALL THIS IS DONE TO DIV WITH ID = "MAIN"
         THE FINAL RESULT IS IDENTICAL TO THE CLO-ICS-CORE.PDF FILE GIVEN BY DR.SALAH
         
         */
        // $request->get('semester'), $request->get('surveyName'), $request->get('pname')

        var newC1 = document.getElementById("inputC1A").value;
        var PName = getCookie("PName");
        //alert('updated');
        var parameters1 = {
            grp: "Coordinator",
            cmd: "getReport",
            semester: newC1,
            surveyName: "CLO-Based",
            pname: getCookie("PName")
        };


        $.getJSON("../index.php", parameters1).done(
                function (data, textStatus, jqXHR)
                {
                    //alert(data.length);
                    var head, table, row;
                    var courseCode = '';
                    $("#tables div").empty();
                    var myTable = $("#tables");
                    for (var j = 0; j < data.length; j++)
                    {
                        if (data[j].courseCode !== courseCode)
                        {
                            //alert('inside');
                            if (courseCode != '')
                            {
                                // close the previous table
                                table.append("</tbody></table>")
                                myTable.append(table);
                            }
                            courseCode = data[j].courseCode;
                            // Create the table
                            head = $('<h4 id = "' + courseCode + '">');//.appendTo($('#tables'));
                            head.text(PName + "-" + courseCode);
                            myTable.append(head);
                            table = $('<table id= "' + PName + courseCode + '" class="questionTable" style="border: 2px solid black; cellpadding: 3px; width: 90%;">');//.appendTo($('#tables'));
                            var header = $('<tr> <th style="border: 2px solid black; cellpadding: 3px; "> # </th>');

                            header.append('<th style="border: 2px solid black; cellpadding: 3px;"> Question </th>');
                            for (var k = 0; k < data[j].answerCount; k++)
                            {
                                header.append('<th style="border: 2px solid black; cellpadding: 3px; "> ' + data[j].answerNames[k] + " (" + data[j].answerValues[k] + ")</th>");
                            }
                            header.append('<th style="border: 2px solid black; cellpadding: 3px;"> Average </th>');
                            header.append("</tr> </thead> <tbody>");
                            header.appendTo(table);
                            header = 'laalaldalallalalalalalalal';
                        }
                        row = $('<tr>');

                        row.append('<td style="border: 2px solid black; cellpadding: 3px;">' + (j + 1) + "</td>");
                        row.append('<td style="border: 2px solid black; cellpadding: 3px;">' + data[j].Question + "</td>");
                        for (var k = 0; k < data[j].answerCount; k++)
                        {
                            row.append('<td style="border: 2px solid black; cellpadding: 3px;"> ' + data[j].answers[k] + " </td>");
                        }
                        row.append('<td style="border: 2px solid black; cellpadding: 3px;">' + data[j].avg + "</td> </tr>");
                        row.appendTo(table);
                    }
                    // out
                    //alert('out');
                    table.append("</tbody> </table>");
                    myTable.append(table);

                    $('.questionTable').dataTable();
                    $('.questionTable').DataTable({
                        keys: true
                    });
                    $('.questionTable').DataTable();
                    $('.questionTable').DataTable({
                        ajax: "./js/datatables/json/scroller-demo.json",
                        deferRender: true,
                        scrollY: 380,
                        scrollCollapse: true,
                        scroller: true
                    });
                    var table1 = $('.questionTable').DataTable({
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
        //alert("save 1");
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
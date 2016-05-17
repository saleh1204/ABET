$(document).ready(function () {

    generateTable();
    function generateTable() {
        // flush the table
        var parameters = {
            grp: "DBA",
            cmd: "getSemesters"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {

            $("#tbody tr").remove();
            var col1 = [];//["142", "151"];
            var col2 = [];//["2015-10-1", "2015-12-31"];
            var col3 = [];//["1234567", "123456"];
            /*************************************
             SELECT * FROM TABLE JOIN TABLE
             *************************************
             */
            for (i = 0; i < data.length; i++)
            {

                col1[i] = data[i].semesterNum;
                col2[i] = data[i].startDate;
                col3[i] = data[i].endDate;
            }
            $("#tbody tr").remove();
            /*************************************
             SELECT * FROM TABLE JOIN TABLE
             *************************************
             */
            var tb = $('#tbody');
            var i = 0;
            for (i = 0; i < col2.length; i++) {
                var tr = $('<tr>').appendTo(tb);
                tr.append('<td class = "sr">' + (i + 1) + '</td>');
                tr.append('<td class = "col1">' + col1[i] + '</td>');
                tr.append('<td class = "col2">' + col2[i] + '</td>');
                tr.append('<td class = "col3">' + col3[i] + '</td>');
                tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' + '<button class="btn btn-default" name = "del' + i + '" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
            }
        });
    }
    $('#myT').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        var $col1 = $row.find(".col1").text();
        var $col2 = $row.find(".col2").text();
        var $col3 = $row.find(".col3").text();
        if ($(this).text().length === 6) {
            // DELETE THE RECORD FROM DATABSE PLEASE
            var parameters = {
                grp: "DBA",
                cmd: "deleteSemester",
                semesterNum: $col1
            };
            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                //alert("Deleted Successfully!!! "+ data);
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(textStatus + "\n" + errorThrown.toString());

            });
        } else { // it is an update
            document.getElementById("inputC1U").value = $col1;
            document.getElementById("inputC2U").value = $col2;
            document.getElementById("inputC3U").value = $col3;
            $('#update').unbind().click(function () {
                var newC1 = document.getElementById("inputC1U").value;
                var newC2 = document.getElementById("inputC2U").value;
                var newC3 = document.getElementById("inputC3U").value;
                // THE UPDATE STATEMENT GOES HERE
                var parameters = {
                    grp: "DBA",
                    cmd: "updateSemester",
                    oldSemester: $col1,
                    newSemester: newC1,
                    startDate: newC2,
                    endDate: newC2
                };

                $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                    //alert("Updated Successfully!!!");
                    generateTable();
                }
                ).fail(function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(textStatus + "\n" + errorThrown.toString());

                });
            });
        }
        generateTable();
    });
    $('#addR').click(function () {

    });
    $('#save').click(function () {
        //alert("INSIDE SAVE");
        var newC1 = document.getElementById("inputC1A").value;
        var newC2 = document.getElementById("inputC2A").value;
        var newC3 = document.getElementById("inputC3A").value;
        /**************************
         INSERT STATEMENT GOES HERE
         **************************
         */

        var parameters = {
            grp: "DBA",
            cmd: "addSemester",
            semesterNum: newC1,
            startDate: newC2,
            endDate: newC3

        };

        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
            //alert("Added Successfully!!!");
            generateTable();
        }
        ).fail(function (jqXHR, textStatus, errorThrown)
        {
            // log error to browser's console
            console.log(textStatus + "\n" + errorThrown.toString());

        });
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("inputC3A").value = "";
        generateTable();
    });
});
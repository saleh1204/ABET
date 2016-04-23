$(document).ready(function () {

    generateTable();
    function generateTable() {
        // flush the table
        var parameters = {
            grp: "DBA",
            cmd: "getEmployers"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {


            var col1 = []; 
            var col2 = []; 
            var col3 = []; 
            /*************************************
             SELECT * FROM TABLE JOIN TABLE
             *************************************
             */
            for (i = 0; i < data.length; i++)
            {

                col1[i] = data[i].empName;
                col2[i] = data[i].email;
                col3[i] = data[i].password;
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
            alert("delete: " + $col1 + $col2 + $col3);
            // DELETE THE RECORD FROM DATABSE PLEASE
            var parameters = {
                grp: "DBA",
                cmd: "deleteEmployer",
                name: $col1
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
            s
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
                    cmd: "updateEmployer",
                    oldName: $col1,
                    newName: newC1,
                    email: newC2,
                    password: newC3
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
            cmd: "addEmployer",
            empName: newC1,
            email: newC2,
            password: newC3

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
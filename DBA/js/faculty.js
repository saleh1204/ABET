$(document).ready(function () {
    getDepartmentNames();
    generateTable();
    function generateTable() {
        // flush the table

        var parameters = {
            grp: "DBA",
            cmd: "getFaculties"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {

            $("#tbody tr").remove();
            var col1 = [];//"ICS";
            var col2 = [];//["Adam", "Kanaan"];
            var col3 = [];//["Salahadin Adam Mohammad", "Kanaan Faisal Abed"];
            var col4 = [];//["Active", "Active"];
            /*************************************
             SELECT * FROM TABLE JOIN TABLE
             *************************************
             */
            for (i = 0; i < data.length; i++)
            {

                col1[i] = data[i].DNameShort;
                col2[i] = data[i].FacultyName;
                col3[i] = data[i].Email;
                col4[i] = "Active";

            }
            var tb = $('#tbody');
            var i = 0;
            for (i = 0; i < col2.length; i++) {
                var tr = $('<tr>').appendTo(tb);
                tr.append('<td class = "sr">' + (i + 1) + '</td>');
                tr.append('<td class = "col1">' + col1[i] + '</td>');
                tr.append('<td class = "col2">' + col2[i] + '</td>');
                tr.append('<td class = "col3">' + col3[i] + '</td>');
                tr.append('<td class = "col4">' + col4[i] + '</td>');
                tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' + '<button class="btn btn-default" name = "del' + i + '" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
            }

        });
    }
    $('#myT').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        var $col1 = $row.find(".col1").text();
        var $col2 = $row.find(".col2").text();
        var $col3 = $row.find(".col3").text();
        var $col4 = $row.find(".col4").text();
        if ($(this).text().length === 6) {
            // DELETE THE RECORD FROM DATABSE PLEASE
            var parameters = {
                grp: "DBA",
                cmd: "deleteFaculty",
                Name: $col2
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
            document.getElementById("mySelectU1").value = $col1;
            document.getElementById("inputC1U").value = $col2;
            document.getElementById("inputC2U").value = $col3;
            document.getElementById("mySelectU2").value = $col4;
            $('#update').unbind().click(function () {
                var newC1 = document.getElementById("mySelectU1").value;
                var newC2 = document.getElementById("inputC1U").value;
                var newC3 = document.getElementById("inputC2U").value;
                var newC4 = document.getElementById("mySelectU2").value;
                //alert("UPDATING: " + newC1 + " " + newC2 + " " + newC3 + newC4);
                // THE UPDATE STATEMENT GOES HERE

                var parameters = {
                    grp: "DBA",
                    cmd: "updateFaculty",
                    oldName: $col3,
                    DNameShort: newC1,
                    oldEmail: $col2,
                    Email: newC2,
                    newName: newC3
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
        document.getElementById("mySelectA1").value = "";
        document.getElementById("mySelectA2").value = "";
    });
    $('#save').click(function () {
        // alert("INSIDE SAVE");
        var newC1 = document.getElementById("mySelectA1").value;
        var newC2 = document.getElementById("inputC1A").value;
        var newC3 = document.getElementById("inputC2A").value;
        var newC4 = document.getElementById("mySelectA2").value;
        /**************************
         INSERT STATEMENT GOES HERE
         **************************
         */

        var parameters = {
            grp: "DBA",
            cmd: "addFaculty",
            facultyEmail: newC2,
            facultyName: newC3,
            DNameShort: newC1

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
        document.getElementById("mySelectA1").value = "";
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("mySelectA2").value = "";
        generateTable();
    });
});
function getDepartmentNames()
{
    var newOptions = [];
    var parameters = {
        grp: "DBA",
        cmd: "getDepartments"
    };
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                var x = $("#mySelectU1");
                var y = $("#mySelectA1");
                x.empty();
                y.empty();
                for (i = 0; i < data.length; i++)
                {
                    //newOptions[i] = {text: data[i].DnameShort, value: data[i].DnameShort};
                    x.append('<option value="' + data[i].DnameShort + '">' + data[i].DnameShort + '</option>');
                    y.append('<option value="' + data[i].DnameShort + '">' + data[i].DnameShort + '</option>');
                    //alert(data[i].DnameShort);
                }
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(errorThrown.toString());
                //return cl;
            });
}
        
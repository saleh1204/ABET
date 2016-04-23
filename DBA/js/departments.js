$(document).ready(function () {

    generateTable();
    getCollegeNames();
    function generateTable() {
        // flush the table
        $("#tbody tr").remove();

        var parameters = {
            grp: "DBA",
            cmd: "getDepartments"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {
            var dsnKFUPM = [];
            var dnKFUPM = [];
            var col = [];
            for (i = 0; i < data.length; i++)
            {

                dnKFUPM[i] = data[i].Dname;
                dsnKFUPM[i] = data[i].DnameShort;
                col[i] = data[i].CnameShort;
            }
            var tb = $('#tbody');
            var i = 0;
            for (i = 0; i < dsnKFUPM.length; i++) {
                var tr = $('<tr>').appendTo(tb);
                tr.append('<td class = "sr">' + (i + 1) + '</td>');
                tr.append('<td class = "col1">' + col[i] + '</td>');
                tr.append('<td class = "col2">' + dnKFUPM[i] + '</td>');
                tr.append('<td class = "col3">' + dsnKFUPM[i] + '</td>');
                tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' + '<button class="btn btn-default" name = "del' + i + '" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
            }

        }).fail(function (jqXHR, textStatus, errorThrown)
        {
            // log error to browser's console
            console.log(errorThrown.toString());
        });

    }
    $('#myT').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        var $col1 = $row.find(".col1").text();
        var $col2 = $row.find(".col2").text();
        var $col3 = $row.find(".col3").text();
        if ($(this).text().length === 6) {
            // DELETE THE RECORD FROM DATABSE
            var parameters = {
                grp: "DBA",
                cmd: "deleteDepartment",
                DNameShort: $col3
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
            document.getElementById("mySelectU").value = $col1;
            document.getElementById("inputC1U").value = $col2;
            document.getElementById("inputC2U").value = $col3;
            $('#update').unbind().click(function () {
                var newC1 = document.getElementById("mySelectU").value;
                var newC2 = document.getElementById("inputC1U").value;
                var newC3 = document.getElementById("inputC2U").value;
                alert("UPDATING: " + newC1 + " " + newC2 + " " + newC3);
                // THE UPDATE STATEMENT GOES HERE

                var parameters = {
                    grp: "DBA",
                    cmd: "updateDepartment",
                    oldDNameShort: $col3,
                    DName: newC2,
                    newDNameShort: newC3,
                    CNameShort: newC1
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
                document.getElementById("inputC1U").value = "";
                document.getElementById("inputC2U").value = "";
                document.getElementById("mySelectU").value = "";

            });
        }
        generateTable();
    });
    $('#addR').click(function () {
        document.getElementById("mySelectA").value = "";
        alert("inside add");
    });
    $('#save').click(function () {
        //alert("INSIDE SAVE");
        var newC1 = document.getElementById("mySelectA").value;
        var newC2 = document.getElementById("inputC1A").value;
        var newC3 = document.getElementById("inputC2A").value;
        /**************************
         INSERT STATEMENT GOES HERE
         **************************
         */

        var parameters = {
            grp: "DBA",
            cmd: "addDepartment",
            DName: newC2,
            DNameShort: newC3,
            CNameShort: newC1

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
        document.getElementById("mySelectA").value = "";
        generateTable();

    });



});

function getCollegeNames()
{
    var parameters = {
        grp: "DBA",
        cmd: "getColleges"
    };
    $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
    {
        var x = $("#mySelectU");
        var y = $("#mySelectA");
        x.empty();
        y.empty();
        for (i = 0; i < data.length; i++)
        {
            x.append('<option value="' + data[i].cnameShort + '">' + data[i].cnameShort + '</option>');
            y.append('<option value="' + data[i].cnameShort + '">' + data[i].cnameShort + '</option>');
        }

    }).fail(function (jqXHR, textStatus, errorThrown)
    {
        // log error to browser's console
        console.log(errorThrown.toString());
    });
}
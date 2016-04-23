$(document).ready(function () {
    
    generateTable();
    getDepartmentNames();
    function generateTable() {
        // flush the table
        $("#tbody tr").remove();
        //var col1 = "ICS";
        //var col2 = ["ICS", "SWE"];
        // var col3 = ["Computer Science", "Software Engineering"];
        /*************************************
         SELECT * FROM TABLE JOIN TABLE
         *************************************
         */

        var parameters = {
            grp: "DBA",
            cmd: "getPrograms"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {
            $("#tbody tr").remove();
            var col1 = [];
            var col2 = [];
            var col3 = [];
            for (i = 0; i < data.length; i++)
            {

                col1[i] = data[i].DNameShort;
                col2[i] = data[i].PNameShort;
                col3[i] = data[i].PName;
            }
            var tb = $('#tbody');
            var i = 0;
            for (i = 0; i < col2.length; i++) {
                var tr = $('<tr>').appendTo(tb);
                tr.append('<td class = "sr">' + (i + 1) + '</td>');
                tr.append('<td class = "col1">' + col1[i] + '</td>');
                tr.append('<td class = "col2">' + col3[i] + '</td>');
                tr.append('<td class = "col3">' + col2[i] + '</td>');
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
                cmd: "deleteProgram",
                PNameShort: $col3
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
                // THE UPDATE STATEMENT GOES HERE

                var parameters = {
                    grp: "DBA",
                    cmd: "updateProgram",
                    oldPNameShort: $col3,
                    PName: newC2,
                    newPNameShort: newC3,
                    DNameShort: newC1
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
    });
    $('#save').click(function () {
        var newC1 = document.getElementById("mySelectA").value;
        var newC2 = document.getElementById("inputC1A").value;
        var newC3 = document.getElementById("inputC2A").value;
        /**************************
         INSERT STATEMENT GOES HERE
         **************************
         */


        var parameters = {
            grp: "DBA",
            cmd: "addProgram",
            PName: newC2,
            PNameShort: newC3,
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
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("mySelectA").value = "";
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
                var x = $("#mySelectU");
                var y = $("#mySelectA");
                x.empty();
                y.empty();
                for (i = 0; i < data.length; i++)
                {
                    //newOptions[i] = {text: data[i].DnameShort, value: data[i].DnameShort};
                    x.append('<option value="'+data[i].DnameShort+'">'+data[i].DnameShort+'</option>');
                    y.append('<option value="'+data[i].DnameShort+'">'+data[i].DnameShort+'</option>');
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
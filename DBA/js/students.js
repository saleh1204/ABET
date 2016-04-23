$(document).ready(function () {
    generateTable();
    function generateTable() {
        // flush the table first
        /*********************
         SELECT * FROM Student
         *********************
         */
        var parameters = {
            grp: "DBA",
            cmd: "getStudents"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {
            var names = [];
            var snames = [];
            //  alert("SUID: " + data[0].SUID + " STUName: " + data[0].STUName);
            for (i = 0; i < data.length; i++)
            {
                // alert(data[i].SUID);
                // alert(data[i].STUName);
                names[i] = data[i].SUID;
                snames[i] = data[i].STUName;
            }
            var i = 0;
            $("#tbody tr").remove();
            var tb = $('#tbody');
            for (i = 0; i < snames.length; i++) {
                var tr = $('<tr>').appendTo(tb);
                tr.append('<td class = "sr">' + (i + 1) + '</td>');
                tr.append('<td class = "nname">' + names[i] + '</td>');
                tr.append('<td class = "sname">' + snames[i] + '</td>');
                tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' + '<button class="btn btn-default" name = "del' + i + '" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');


            }
        }).fail(function (jqXHR, textStatus, errorThrown)
        {
            // log error to browser's console
            console.log(errorThrown.toString());
        });
        ;
    }
    $('#myT').on('click', 'button', function () {
        var t = $(this).text();
        var row = $(this).closest('tr');
        var nname = row.find(".nname").text();
        var sname = row.find(".sname").text();
        var sr = row.find(".sr").text();

        if ($(this).text().length === 6) {
            // DELETE FROM THE DATABASE
            var parameters = {
                grp: "DBA",
                cmd: "deleteStudent",
                ID: nname
            };
            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                //alert("Deleted Successfully!!!");
                 generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(textStatus + "\n" + errorThrown.toString());

            });
            ;
        }
        else { // it is an update button
            document.getElementById("inputC1U").value = nname;
            document.getElementById("inputC2U").value = sname;
            // ON SUBMITTING THE UPDATES
            $('#update').unbind().click(function () {
                var newName = document.getElementById("inputC1U").value;
                var newSName = document.getElementById("inputC2U").value;
                // THE UPDATE STATEMENT GOES HERE
                //alert("Starting the Update Request");
                var parameters = {
                    grp: "DBA",
                    cmd: "updateStudent",
                    oldID: nname,
                    newID: newName,
                    newName: newSName
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

                //  }




            });



        }
        //generateTable();
    });
    $('#addR').click(function () {
    });
    $('#save').click(function () {
        var newName = document.getElementById("inputC1A").value;
        var newSName = document.getElementById("inputC2A").value;
        var rowCount = $('#myT tr').length;

        /**************************
         INSERT STATEMENT GOES HERE
         **************************
         */
        var parameters = {
            grp: "DBA",
            cmd: "addStudent",
            studentID: newName,
            studentName: newSName

        };

        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
           // alert("Added Successfully!!!");
            generateTable();
        }
        ).fail(function (jqXHR, textStatus, errorThrown)
        {
            // log error to browser's console
            console.log(textStatus + "\n" + errorThrown.toString());

        });
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
       // generateTable();

    });

});
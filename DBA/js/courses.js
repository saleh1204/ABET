$(document).ready(function () {
    //alert("Hello");
    generateTable();
    getProgramsNames();
    function generateTable() {
        // flush the table
        var parameters = {
            grp: "DBA",
            cmd: "getCourses"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {
            $("#tbody tr").remove();
            var col1 = [];
            var col2 = [];
            var col3 = [];
            var col4 = [];
            var col5 = [];
            var col6 = [];
            for (i = 0; i < data.length; i++)
            {

                col1[i] = data[i].PName;
                col2[i] = data[i].courseCode;
                col3[i] = data[i].courseName;
                col4[i] = data[i].dateActivated;
                col5[i] = data[i].dateDeactivated;
                col6[i] = "Active";

            }
            $("#tbody tr").remove();

            var tb = $('#tbody');
            var i = 0;
            for (i = 0; i < col2.length; i++) {
                var tr = $('<tr>').appendTo(tb);
                tr.append('<td class = "sr">' + (i + 1) + '</td>');
                tr.append('<td class = "col1">' + col1[i] + '</td>');
                tr.append('<td class = "col2">' + col2[i] + '</td>');
                tr.append('<td class = "col3">' + col3[i] + '</td>');
                tr.append('<td class = "col4">' + col4[i] + '</td>');
                tr.append('<td class = "col5">' + col5[i] + '</td>');
                tr.append('<td class = "col6">' + col6[i] + '</td>');
                tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' + '<button class="btn btn-default" name = "del' + i + '" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
            }
        });
    }
    var isAdd = 1;
    $('#myT').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        var $col1 = $row.find(".col1").text();
        var $col2 = $row.find(".col2").text();
        var $col3 = $row.find(".col3").text();
        var $col4 = $row.find(".col4").text();
        var $col5 = $row.find(".col5").text();
        var $col6 = $row.find(".col6").text();



        if ($(this).text().length === 6) {
            // DELETE THE RECORD FROM DATABSE PLEASE
            var parameters = {
                grp: "DBA",
                cmd: "deleteCourse",
                PNameShort: $col1,
                courseCode: $col2

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
            document.getElementById("inputC3U").value = $col4;
            document.getElementById("inputC4U").value = $col5;
            
            document.getElementById("mySelectU2").value = $col6;
            $('#update').unbind().click(function () {
                var newC1 = document.getElementById("mySelectU1").value;
                var newC2 = document.getElementById("inputC1U").value;
                var newC3 = document.getElementById("inputC2U").value;
                var newC4 = document.getElementById("inputC3U").value;
                var newC5 = document.getElementById("inputC4U").value;
                var newC5 = document.getElementById("inputC7U").value;
                var newC7 = document.getElementById("mySelectU2").value;
                // if (isAdd === 0){
                //alert("UPDATING: " + newC1 + " " + newC2 + " " + newC3 + newC5 + newC4 + newC6);
                // THE UPDATE STATEMENT GOES HERE

                var parameters = {
                    grp: "DBA",
                    cmd: "updateCourse",
                    oldPNameShort: $col1,
                    oldcourseCode: $col2,
                    newCourseCode: newC2,
                    newPNameShort: newC1,
                    courseName: newC3,
                    dateActivated: newC4,
                    dateDeactivated: newC5,
                    courseCredit: newC7
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
        // alert("inside add");
    });
    $('#save').click(function () {
        // alert("INSIDE SAVE");
        var newC1 = document.getElementById("mySelectA1").value;
        var newC2 = document.getElementById("inputC1A").value;
        var newC3 = document.getElementById("inputC2A").value;
        var newC4 = document.getElementById("inputC3A").value;
        var newC5 = document.getElementById("inputC4A").value;
        var newC6 = document.getElementById("mySelectA2").value;
        var newC7 = document.getElementById("inputC5A").value;
        // alert(newC4);
        /**************************
         INSERT STATEMENT GOES HERE
         **************************
         */
        var parameters = {
            grp: "DBA",
            cmd: "addCourse",
            courseCode: newC2,
            pnameShort: newC1,
            courseName: newC3,
            dateActivated: newC4,
            dateDeactivated: newC5,
            courseCredit: newC7
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
        document.getElementById("inputC3A").value = "";
        document.getElementById("inputC4A").value = "";
        document.getElementById("inputC5A").value = "";
        document.getElementById("mySelectA2").value = "";
        generateTable();

    });

    function getProgramsNames()
    {
        var parameters = {
            grp: "DBA",
            cmd: "getPrograms"
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
                        x.append('<option value="' + data[i].PNameShort + '">' + data[i].PNameShort + '</option>');
                        y.append('<option value="' + data[i].PNameShort + '">' + data[i].PNameShort + '</option>');
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

});
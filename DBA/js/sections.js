$(document).ready(function () {
    getProgramsNames();
    generateTable();
    getSemesterNames();
    getFacultyNames();
    function generateTable() {
        // flush the table

        var parameters = {
            grp: "DBA",
            cmd: "getSections"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {
            $("#tbody tr").remove();
            var col1 = [];//["ICS", "SWE", "EE"];
            var col2 = [];//["324", "363", "207"];
            var col3 = [];//["152", "153", "142"];
            var col4 = [];//["FacultyA", "FacultyB", "FacultyC"];
            var col5 = [];//["1", "2", "3"];
            for (i = 0; i < data.length; i++)
            {

                col1[i] = data[i].PName;
                col2[i] = data[i].courseCode;
                col3[i] = data[i].semester;
                col4[i] = data[i].FacultyName;
                col5[i] = data[i].SectionNum;

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
                tr.append('<td class = "col5">' + col5[i] + '</td>');
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
        var $row = $(this).closest('tr');
        var $col1 = $row.find(".col1").text();
        var $col2 = $row.find(".col2").text();
        var $col3 = $row.find(".col3").text();
        var $col4 = $row.find(".col4").text();
        var $col5 = $row.find(".col5").text();



        if ($(this).text().length === 6) {
            // DELETE THE RECORD FROM DATABSE PLEASE

            var parameters = {
                grp: "DBA",
                cmd: "deleteSection",
                sectionNum: $col5,
                semesterNum: $col3,
                facultyName: $col4,
                pname: $col1,
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
            document.getElementById("mySelectU2").value = $col3;
            document.getElementById("mySelectU3").value = $col4;
            document.getElementById("inputC2U").value = $col5;
            $('#update').unbind().click(function () {
                var newC1 = document.getElementById("mySelectU1").value;
                var newC2 = document.getElementById("inputC1U").value;
                var newC3 = document.getElementById("mySelectU2").value;
                var newC4 = document.getElementById("mySelectU3").value;
                var newC5 = document.getElementById("inputC2U").value;
                // THE UPDATE STATEMENT GOES HERE
                var parameters = {
                    grp: "DBA",
                    cmd: "updateSection",
                    sectionNum: $col5,
                    semesterNum: $col3,
                    facultyName: $col4,
                    pname: $col1,
                    courseCode: $col2,
                    newSectionNum: newC5,
                    newFacultyName: newC4,
                    newSemester: newC3,
                    newProgramName: newC1,
                    newCourseCode: newC2
                    
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
    }
    );
    $('#addR').click(function () {
        document.getElementById("mySelectA1").value = "";
        document.getElementById("mySelectA2").value = "";
        document.getElementById("mySelectA3").value = "";
    });
    $('#save').click(function () {
        var newC1 = document.getElementById("mySelectA1").value;
        var newC2 = document.getElementById("inputC1A").value;
        var newC3 = document.getElementById("mySelectA2").value;
        var newC4 = document.getElementById("mySelectA3").value;
        var newC5 = document.getElementById("inputC2A").value;
        // INSERT STATEMENT GOES HERE

        var parameters = {
            grp: "DBA",
            cmd: "addSection",
            courseCode: newC2,
            sectionNum: newC5,
            semesterNum: newC3,
            facultyName: newC4,
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
        document.getElementById("mySelectA2").value = "";
        document.getElementById("mySelectA3").value = "";
        document.getElementById("inputC2A").value = "";
        generateTable();

    });



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

function getSemesterNames()
{
    var parameters = {
        grp: "DBA",
        cmd: "getSemesters"
    };
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                var x = $("#mySelectU2");
                var y = $("#mySelectA2");
                x.empty();
                y.empty();
                for (i = 0; i < data.length; i++)
                {
                    //newOptions[i] = {text: data[i].DnameShort, value: data[i].DnameShort};
                    x.append('<option value="' + data[i].semesterNum + '">' + data[i].semesterNum + '</option>');
                    y.append('<option value="' + data[i].semesterNum + '">' + data[i].semesterNum + '</option>');
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

function getFacultyNames()
{
    var parameters = {
        grp: "DBA",
        cmd: "getFaculties"
    };
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                var x = $("#mySelectU3");
                var y = $("#mySelectA3");
                x.empty();
                y.empty();
                for (i = 0; i < data.length; i++)
                {
                    //newOptions[i] = {text: data[i].DnameShort, value: data[i].DnameShort};
                    x.append('<option value="' + data[i].FacultyName + '">' + data[i].FacultyName + '</option>');
                    y.append('<option value="' + data[i].FacultyName + '">' + data[i].FacultyName + '</option>');
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
$(document).ready(function () {

    generateTable();
    getStatus();
    function generateTable() {
        // flush the table
        var parameters = {
            grp: "DBA",
            cmd: "getSurveyType"
        };
        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR)
        {
            $("#tbody tr").remove();
            var col1 = [];//["Rubrics-Based", "CLO-Based"];
            var col2 = [];//["This survey.....", "This type is....."];
            var col3 = [];//["123456", "123456"];
            var col4 = [];//["7890", "0987654"];
            var col5 = [];//["Deacitvated", "Acitvated"];
            /*************************************
             SELECT * FROM TABLE JOIN TABLE
             *************************************
             */
            for (i = 0; i < data.length; i++)
            {

                col1[i] = data[i].SurveyType;
                col2[i] = data[i].description;
                col3[i] = data[i].dateActivated;
                col4[i] = data[i].dateDeactivated;
                col5[i] = data[i].Status;


            }
            var tb = $('#tbody');
            var i = 0;
            for (i = 0; i < col1.length; i++) {
                var tr = $('<tr>').appendTo(tb);
                tr.append('<td class = "sr">' + (i + 1) + '</td>');
                tr.append('<td class = "col1">' + col1[i] + '</td>');
                tr.append('<td class = "col2">' + col2[i] + '</td>');
                tr.append('<td class = "col3">' + col3[i] + '</td>');
                tr.append('<td class = "col4">' + col4[i] + '</td>');
                tr.append('<td class = "col5">' + col5[i] + '</td>');
                tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' + '<button class="btn btn-default" name = "del' + i + '" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
            }
        }
        ).fail(function (jqXHR, textStatus, errorThrown)
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
                cmd: "deleteSurveyType",
                surveyName: $col1
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
            document.getElementById("inputC4U").value = $col4;
            document.getElementById("mySelectU1").value = $col5;
            $('#update').unbind().click(function () {
                var newC1 = document.getElementById("inputC1U").value;
                var newC2 = document.getElementById("inputC2U").value;
                var newC3 = document.getElementById("inputC3U").value;
                var newC4 = document.getElementById("inputC4U").value;
                var newC5 = document.getElementById("mySelectU1").value;
                // THE UPDATE STATEMENT GOES HERE
                var parameters = {
                    grp: "DBA",
                    cmd: "updateSurveyType",
                    oldSurveyName: $col1,
                    surveyName: newC1,
                    dateActivated: newC3,
                    dateDeactivated: newC4,
                    description: newC2,
                    status: newC5
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
    });
    $('#save').click(function () {
        var newC1 = document.getElementById("inputC1A").value;
        var newC2 = document.getElementById("inputC2A").value;
        var newC3 = document.getElementById("inputC3A").value;
        var newC4 = document.getElementById("inputC4A").value;
        var newC5 = document.getElementById("mySelectA1").value;
        /**************************
         INSERT STATEMENT GOES HERE
         **************************
         */
        alert(newC2);
        var parameters = {
            grp: "DBA",
            cmd: "addSurveyType",
            surveyName: newC1,
            statusType: newC5,
            description: newC2, 
            dateActivated: newC3,
            dateDeactivated: newC4

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
        document.getElementById("inputC4A").value = "";
        document.getElementById("mySelectA1").value = "";
        generateTable();
    });
});
function getStatus()
{
    var parameters = {
        grp: "DBA",
        cmd: "getStatus"
    };
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                var x = $("#mySelectU1");
                var y = $("#mySelectA1");
                x.empty();
                y.empty();
                //alert("Here");
                for (i = 0; i < data.length; i++)
                {
                    //newOptions[i] = {text: data[i].DnameShort, value: data[i].DnameShort};
                    x.append('<option value="' + data[i].StatusType + '">' + data[i].StatusType + '</option>');
                    y.append('<option value="' + data[i].StatusType + '">' + data[i].StatusType + '</option>');
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
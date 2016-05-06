$(document).ready(function () {
    addQuestionTexts();
    addAnswerValues();
    generateTable();

    $('#coursedetails').text('T' + getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    $('#username').text(getCookie('email'));


    var $col1;
    var $col2;
    var $col3;
    var $col4;
    var $col5;
    $('#example').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        // PUT AN ARRAY BASED ON THE RECORD LENGTH
        // LOOP TO INITIALIZE THE ARRAY
        $col1 = $row.find(".col1").text();
        $col2 = $row.find(".col2").text();
        $col3 = $row.find(".col3").text();
        $col4 = $row.find(".col4").text();
        $col5 = $row.find(".col5").text();
        if ($(this).text().length === 6) {
// DELETE THE RECORD FROM DATABSE PLEASE
// THIS DELETE WILL USE ALL THE VALUES IN THE COOKIES INCLUDING: EMAIL, SEMESTER, PNAMESHORT, COURSECODE
// IN ADDITION TO COL1, COL2,....
            var parameters = {
                grp: "Faculty",
                cmd: "deleteStudentAnswers",
                surveyType: 'CLO-Based',
                SUID: $col1,
                pnameShort: getCookie('PName'),
                courseCode: getCookie('CCode'),
            };

            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(textStatus + "\n" + errorThrown.toString());

            });
        } else { // it is an update

// LOOP HERE
            document.getElementById("inputC1A").value = $col1;
            document.getElementById("inputC2A").value = $col2;
            document.getElementById("inputC3A").value = $col3;
            document.getElementById("inputC4A").value = $col4;
            document.getElementById("inputC5A").value = $col5;
        }
        generateTable();
    });
    $("#demo-form2").on('click', '#save', function () {
        alert("save 1");
        // LOOP HERE WITH ARRAY OF SIZE = (RECORD LENGTH) TO STORE VARIABLES
        /*
        var newC1 = document.getElementById("inputC1A").value;
        var newC2 = document.getElementById("inputC2A").value;
        var newC3 = document.getElementById("inputC3A").value;
        var newC4 = document.getElementById("inputC4A").value;
        var newC5 = document.getElementById("inputC4A").value;
        alert(newC1 + " " + newC2 + " " + newC3);
        
        /**************************
         DELETE GOES HERE (in case of update just to be safe)
         INSERT STATEMENT GOES HERE
         **************************
         */
        var answers = $(".answerIN");
        var questions = $(".questionLB");
        alert('Answers ' + answers[0].value());
        alert('Question: ' + questions[0]);
        var parameters = {
            grp: "Faculty",
            cmd: "addStudentAnswers",
            questionText: newC2,
            SurveyName: 'CLO-Based',
            statusType: 'Survey',
            pnameShort: getCookie('PName'),
            courseCode: getCookie('CCode'),
        };

        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
            alert("Added Successfully!!!");
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
        document.getElementById("inputC5A").value = "";
        generateTable();
    });
    $("#demo-form2").on('click', '#cancel', function () {

        alert("cancel");
        // LOOP HERE
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("inputC3A").value = "";
        document.getElementById("inputC4A").value = "";
        document.getElementById("inputC5A").value = "";
    });




});

function generateTable() {
    // flush the table
    var parameters = {
        grp: "Faculty",
        cmd: "getStudentAnswers",
        semester: getCookie('Term'),
        courseCode: getCookie('CCode'),
        pname: getCookie('PName'),
        email: getCookie('email'),
        surveyType: "CLO-Based",
        sectionNum: getCookie('Section')
    };
    //alert('GREAT');
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                var myTable = $("#example").empty();
                myTable.append("<thead>");

                var th = $('<tr>').appendTo(myTable);
                th.append("<th> # </th> <th> SUID </th>");
                for (var k = 0; k < data[0].count; k++)
                {
                    th.append("<th> Question " + (k + 1) + " Answer </th>");
                }
                th.append("</tr>");
                myTable.append("</thead>");

                myTable.append("<tbody>");

                for (var j = 0; j < data.length; j++)
                {
                    var tr = $('<tr>').appendTo(myTable);
                    tr.append('<td class = "sr">' + (j + 1) + '</td>');
                    tr.append("<td> " + data[j].SUID + "</td>");
                    for (var k = 0; k < data[j].count; k++)
                    {
                        tr.append("<td> " + data[j].answers[k] + "</td>");

                    }
                    tr.append("</tr>");
                }

                myTable.append("</tbody>");
                $('#example').dataTable();
                $('#example-keytable').DataTable({
                    keys: true
                });
                $('#example-responsive').DataTable();
                $('#example-scroller').DataTable({
                    ajax: "./js/datatables/json/scroller-demo.json",
                    deferRender: true,
                    scrollY: 380,
                    scrollCollapse: true,
                    scroller: true
                });
                var table = $('#example-fixed-header').DataTable({
                    fixedHeader: true
                });
                TableManageButtons.init();
            }
    ).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(errorThrown.toString());
                //return cl;
            });

} 
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
function addQuestionTexts()
{
    // $request->get("courseCode"), $request->get("pname"), $request->get("surveyName"), $request->get("statusName"), 'Active'
    var parameters = {
        grp: "Faculty",
        cmd: "getDynamicQuestion",
        semester: getCookie('Term'),
        courseCode: getCookie('CCode'),
        pname: getCookie('PName'),
        email: getCookie('email'),
        surveyName: "CLO-Based",
        statusName: "Survey",
        sectionNum: getCookie('Section')
    };
    //alert('GREAT');
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                //alert('Questions Loaded'); id="inputC' + (i + 2) + 'A"
                var questionDiv = $("#questions div").empty();
                questionDiv = $("#questions");
                for (var i = 0; i < data.length; i++) {
                    questionDiv.append('<div class="form-group">');
                    questionDiv.append('<label class="control-label col-md-3 col-sm-3 col-xs-12 questionLB" for="question">' + data[i].questiontext + '<span class="required">*</span>');
                    questionDiv.append('</label>');
                    questionDiv.append('<div class="col-md-6 col-sm-6 col-xs-12">');
                    questionDiv.append('<input type="text" required="required" class="form-control col-md-7 col-xs-12 answerIN" >');
                    questionDiv.append('</div></div>');
                }
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(errorThrown.toString());
                //return cl;
            });
    /*
     * 
     document.writeln('<div class="form-group">');
     document.writeln('<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Question' + (i + 1) + ' Answer <span class="required">*</span>');
     document.writeln('</label>');
     document.writeln('<div class="col-md-6 col-sm-6 col-xs-12">');
     document.writeln('<input type="text" id="inputC' + (i + 2) + 'A" required="required" class="form-control col-md-7 col-xs-12" >');
     document.writeln('</div></div>');
     */
}

function addAnswerValues()
{
    var parameters = {
        grp: "Faculty",
        cmd: "getDynamicAnswers",
        semester: getCookie('Term'),
        courseCode: getCookie('CCode'),
        pname: getCookie('PName'),
        email: getCookie('email'),
        surveyName: "CLO-Based",
        statusName: "Survey",
        sectionNum: getCookie('Section')
    };
    //alert('GREAT');
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                //alert('Answers Loaded');
                // var answersDiv = $("#answers").empty();
                // answersDiv.append('<table style = "width: 50%; margin-left: auto; margin-right: auto;" id="myTable">');
                var answers = $("#myTable").empty();
                //answers = $("#answers");
                //answers.append("<table id='myTable'>");

                var myTemp = $('<tr style = "text-align: center;">').appendTo(answers);
                //myTemp.append('<tr style = "text-align: center;">');
                for (var i = 0; i < data.length; i++) {
                    myTemp.append('<td>' + data[i].weight_name + '</td>');
                }
                myTemp.append('</tr>');
                var myTemp1 = $('<tr style = "text-align: center;">').appendTo(answers);
                //myTemp.append('<tr style="text-align: center;">');
                for (var i = 0; i < data.length; i++) {
                    myTemp1.append('<td style="padding-right:5em">' + data[i].weight_value + '</td>');
                }
                myTemp1.append('</tr>');
                answers.append('</table>');
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(errorThrown.toString());
                //return cl;
            });
    /*
     * 
     document.writeln('<table style = "width: 50%; margin-left: auto; margin-right: auto;" id="myTable">');
     
     document.writeln('<tr style = "text-align: center;">');
     for (var j = 0; j < v.length; j++) {
     document.writeln('<td >' + a[j] + '</td>');
     }
     document.writeln('</tr>');
     document.writeln('<tr style="text-align: center;">');
     for (var j = 0; j < v.length; j++) {
     document.writeln('<td>' + v[j] + '</td>');
     }
     document.writeln('</tr>');
     document.writeln('</table>');
     */
}
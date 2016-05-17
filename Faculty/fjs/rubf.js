var numQuestions = 0;
$(document).ready(function () {
    addQuestionTexts();
    addAnswerValues();
    generateTable();
    $('#coursedetails').text('T' + getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    $('#username').text(getCookie('email'));
    function generateTable() {
        // flush the table
        var parameters = {
            grp: "Faculty",
            cmd: "getStudentAnswers",
            semester: getCookie('Term'),
            courseCode: getCookie('CCode'),
            pname: getCookie('PName'),
            email: getCookie('email'),
            surveyType: "Rubrics-Based",
            sectionNum: getCookie('Section')
        };
        //alert('GREAT');
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    var myTable = $("#example").empty();
                    if (data.length > 0)
                    {
                        myTable.append("<thead>");
                        var th = $('<tr>').appendTo(myTable);
                        th.append("<th> # </th> <th> SUID </th>");
                        for (var k = 0; k < data[0].count; k++)
                        {
                            th.append("<th> Question " + (k + 1) + " Answer </th>");
                        }
                        th.append("<th> Actions </th></tr>");
                        myTable.append("</thead>");
                        myTable.append("<tbody>");
                        for (var j = 0; j < data.length; j++)
                        {

                            var tr = $('<tr>').appendTo(myTable);
                            tr.append('<td class = "sr">' + (j + 1) + '</td>');
                            tr.append("<td class='col1'> " + data[j].SUID + "</td>");
                            for (var k = 0; k < data[j].count; k++)
                            {
                                tr.append("<td class='col" + (k + 2) + "'> " + data[j].answers[k] + "</td>");
                            }
                            tr.append('<td>' + '<button class="btn btn-default"  ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
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
                }
        ).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                    //return cl;
                });
    }
    var $col1;
    $('#example').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        // PUT AN ARRAY BASED ON THE RECORD LENGTH
        // LOOP TO INITIALIZE THE ARRAY
        //alert('Delete')
        $col1 = $row.find(".col1").text();
        //alert($col1);
        var Sanswers = [];
        var Squestions = [];
        if ($(this).text().length === 6) {
            for (var i = 0; i < numQuestions; i++)
            {
                Squestions[i] = $.trim($("#question" + i).html());
                // alert(Squestions[i]);
                Sanswers[i] = $.trim($row.find(".col" + (i + 2)).text());
                // alert(Sanswers[i]);
            }
            // DELETE THE RECORD FROM DATABSE PLEASE
            var parameters = {
                grp: "Faculty",
                cmd: "deleteStudentAnswers",
                pname: getCookie('PName'),
                courseCode: getCookie('CCode'),
                ID: $.trim($col1),
                surveyName: 'Rubrics-Based',
                section: getCookie('Section'),
                answers: Sanswers,
                questions: Squestions
            };
            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                // alert('Success');
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(textStatus + "\n" + errorThrown.toString());
            });
        }
        generateTable();
        $col1 = '';
    });
    $("#demo-form2").on('click', '#save', function () {
        //alert("save 1");
        // LOOP HERE WITH ARRAY OF SIZE = (RECORD LENGTH) TO STORE VARIABLES
        var newC1 = document.getElementById("inputC1A").value;
        var questionAnswered = true;
        var questions = [];
        var answers = [];
        for (var i = 0; i < numQuestions; i++)
        {
            questions[i] = $("#question" + i).html();
            answers[i] = $("#answer" + i).val();
            $("#answer" + i).val("");
            //alert("Question: " + questions[i] + " Answer: " + answers[i]);
            if (answers[i] == "")
            {
                alert("Please Answer all questions");
                questionAnswered = false;
                break;
            }
        }
        if (newC1 == "")
        {
            questionsAnswered = false;
            alert("Please Add Student ID");
        }
        var parameters = {
            grp: "Faculty",
            cmd: "addStudentAnswers",
            pname: getCookie('PName'),
            courseCode: getCookie('CCode'),
            ID: newC1,
            surveyName: 'Rubrics-Based',
            section: getCookie('Section'),
            answers: answers,
            questions: questions
        };
        if (questionAnswered)
        {
            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                //alert("Added Successfully!!!");
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(textStatus + "\n" + errorThrown.toString());
            });
        }
        document.getElementById("inputC1A").value = "";
        generateTable();
    });
    $("#demo-form2").on('click', '#cancel', function () {

        //alert("cancel");
        // LOOP HERE
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("inputC3A").value = "";
        document.getElementById("inputC4A").value = "";
        document.getElementById("inputC5A").value = "";
    });
});
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
        surveyName: "Rubrics-Based",
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
                    questionDiv.append($('<div>', {class: 'form-group'}).append($('<label>', {class: 'control-label col-md-3 col-sm-3 col-xs-12', id: "question" + i}).text(data[i].questiontext)).append($('<div>', {class: 'col-md-6 col-sm-6 col-xs-12'}).append($('<input/>', {id: "answer" + i, class: 'form-control col-md-7 col-xs-12', type: 'text'}))));
                }
                numQuestions = data.length;
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                console.log(errorThrown.toString());
            });
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
        surveyName: "Rubrics-Based",
        statusName: "Survey",
        sectionNum: getCookie('Section')
    };
    //alert('GREAT');
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                var answers = $("#answersTable");
                answers.empty();
                var myTemp = $('<tr style = "text-align: center;">').appendTo(answers);
                for (var i = 0; i < data.length; i++) {
                    myTemp.append('<td>' + data[i].weight_name + '</td>');
                }
                myTemp.append('</tr>');
                var myTemp1 = $('<tr style = "text-align: center;">').appendTo(answers);
                for (var i = 0; i < data.length; i++) {
                    myTemp1.append('<td style="padding-right:5em">' + data[i].weight_value + '</td>');
                }
                myTemp1.append('</tr>');
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                console.log(errorThrown.toString());
            });
}
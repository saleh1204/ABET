$(document).ready(function () {
// modify later
    $('#coursetitle').text(getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    // SELECT BASED ON COOKIES
    //var q1 = ["After taking this course I am able to model graphs and trees:", "Ans 1", "Ans 2", "Ans 3"];
    //var q2 = ["Q2 Text", "Ans 1", "Ans 2", "Ans 3"];
    var qheader = []; // APPEND IN THIS ARRAY THE FIRST VALUE OF EACH RECORD - TO BE USED IN INSERT STATMENT
    // THIS SHOULD BE INSIDE THE FOR LOOP
    //qheader.push(q1[0]);
    //qheader.push(q2[0]);
    //alert('nothing');
    var parameters = {
        grp: "Student",
        cmd: "getSurveyStudent",
        term: getCookie('Term'),
        pname: getCookie('PName'),
        courseCode: getCookie('CCode'),
        ID: getCookie('email')
    };
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                //alert(data[0].count);
                for (var j = 0; j < data.length; j++)
                {
                    qheader.push(data[j].question);
                    head = $('<h4 id = "q' + i + '">').appendTo($('#survey'));
                    head.text(data[j].question);
                    for (var i = 0; i < data[j].count; i++) {
                        $('#survey').append('<input type="radio" name = "q' + j + '" value = "' + data[j].answers[i] + '">   ' + data[j].answers[i]);
                        if (i !== (data[j].count - 1)) {
                            $('#survey').append('<br>');
                        }
                        // INSIDE THE FOR LOOP OF THE RECORDS
                        $('#survey').append('<h3 visibility: hidden></h3>');
                    }
                }
                var qanswers = [];
                // APPEND A BUTTON TO THE DOCUMENT
                // FOR SPACING
                $('#survey').append('<h1 visibility: hidden>T</h1>');
                $('#survey').append('<p></p>');
                $('#survey').append('<p></p>');
                $('#survey').append('<p></p>');
                $('#survey').append('<button type="button" class="btn btn-default" id = "save">Submit</button>');
                $('#save').click(function () {
                    var buttons = document.getElementsByTagName('input');
                    // THIS IS THE NUMBER OF QUESSTIONS
                    var numQ = data.length;
                    for (var i = 0; i < buttons.length; i++) {
                        if (buttons[i].checked)
                            numQ = numQ - 1;
                    }

                    if (numQ !== 0) {
                        alert('Please Answer all the questions in the survey');
                    } else {
                        for (var i = 0; i < buttons.length; i++) {
                            if (buttons[i].checked) {
                                qanswers.push(buttons[i].value);
                            }
                        }
                        // INSERT IN STUDENTQA IN A LOOB
                        /*for (var i = 0; i < qheader.length; i++) {
                            alert('inserting: ' + qheader[i] + '-' + qanswers[i]);
                        }*/
                        var parameters = {
                            grp: "Student",
                            cmd: "addStudentQA1",
                            pname: getCookie('PName'),
                            courseCode: getCookie('CCode'),
                            ID: getCookie('email'),
                            section: getCookie('Section'), 
                            answers: qanswers,
                            questions: qheader
                        };
                        $.getJSON("../index.php", parameters).done(
                                function (data, textStatus, jqXHR)
                                {
                                    alert("Success");
                                    window.location = 'studentcourses.html';
                                }).fail(
                                function (jqXHR, textStatus, errorThrown)
                                {
                                    // log error to browser's console
                                    console.log(errorThrown.toString());
                                    //return cl;
                                });
                    }
                    // UPDATE THE STUDENT_SECTION TABLE. SET THE VALUE OF ISCLOFILLED = 1
                });
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(errorThrown.toString());
                //return cl;
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
});
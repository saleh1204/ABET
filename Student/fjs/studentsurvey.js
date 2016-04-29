$(document).ready(function () {
    // modify later
    $('#coursetitle').text(getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    // SELECT BASED ON COOKIES
    var q1 = ["After taking this course I am able to model graphs and trees:", "Ans 1", "Ans 2", "Ans 3"];
    var q2 = ["Q2 Text", "Ans 1", "Ans 2", "Ans 3"];
    var qheader = [];// APPEND IN THIS ARRAY THE FIRST VALUE OF EACH RECORD - TO BE USED IN INSERT STATMENT
    // THIS SHOULD BE INSIDE THE FOR LOOP
    qheader.push(q1[0]);
    qheader.push(q2[0]);
    var qanswers = [];// TO BE FILLED LATER
    // REPALCE THE TWO FOR LOOP BY THE NUMBER OF RECORDS
    var head;
    var q;
    for (var i = 0; i < q1.length; i++) {
        if (i === 0) {
            head = $('<h4 id = "q' + i + '">').appendTo($('#survey'));
            head.text(q1[i]);
        } else {
            // REPLACE THE ONE(1) BY ANOTHER COUNTER
            $('#survey').append('<input type="radio" name = "q' + 1 + '" value = "' + q1[i] + '">   ' + q1[i]);
            if (i !== (q1.length - 1)) {
                $('#survey').append('<br>');
            }
        }

        // INSIDE THE FOR LOOP OF THE RECORDS
        $('#survey').append('<h3 visibility: hidden></h3>');
    }
    // THIS FOR LOOP SHOULD BE REOMVED
    for (var i = 0; i < q2.length; i++) {
        if (i === 0) {
            head = $('<h4 id = "q' + i + '">').appendTo($('#survey'));
            head.text(q2[i]);
        } else {
            // REPLACE THE TWO(2) BY ANOTHER COUNTER
            $('#survey').append('<input type="radio" name = "q' + 2 + '" value = "' + q2[i] + '">   ' + q2[i]);
            if (i !== (q2.length - 1)) {
                $('#survey').append('<br>');
            }
        }

    }

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
        var numQ = 2;
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
            for (var i = 0; i < qheader.length; i++) {
                alert('inserting: ' + qheader[i] + '-' + qanswers[i]);
            }
        }
        // UPDATE THE STUDENT_SECTION TABLE. SET THE VALUE OF ISCLOFILLED = 1
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
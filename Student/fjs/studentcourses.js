$(document).ready(function() {


    btnClick();
    getCourses(getCookie("email"));
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

function btnClick() {
    $('.list-group-item').click(function() {
        var cname = $(this).val();
        var PName = getPName(cname);
        var cc = getCourseCode(cname);
        var tt = getTerm(cname);
        var sc = getSection(cname);
        document.cookie = "PName =" + PName;
        document.cookie = "CCode =" + cc;
        document.cookie = "Term =" + tt;
        document.cookie = "Section =" + sc;
        window.location = 'studentsurvey.html';
        return false;
    });
}

function getPName(cname) {
    var x = cname.indexOf("-") + 1;
    cname = cname.substring(x);
    x = cname.indexOf("-");
    return cname.substring(0, x);
}

function getCourseCode(cname) {
    //var c = cname.substring();
    // return cname.substring(cname.lastIndexOf("-") + 1);
    var x = cname.indexOf("-") + 1;
    cname = cname.substring(x);
    x = cname.indexOf("-") + 1;
    cname = cname.substring(x);
    x = cname.indexOf("-");
    return cname.substring(0, x);
}

function getSection(cname) {
    return cname.substring(cname.lastIndexOf("-") + 1);
}

function getTerm(cname) {
    return cname.substring(1, cname.indexOf("-"));
}

function getCourses(SUID) {

    //alert(document.cookie);
    //alert(SUID);
    //alert('Email: ' + getCookie("email"));
    var parameters = {
        grp: "Student",
        cmd: "getCourses",
        ID: SUID
    };
    $.getJSON("../index.php", parameters).done(
        function(data, textStatus, jqXHR) {
            //alert("Success!");
            var x = $('#courseList');
            //alert("He2y");
            var courses = [];
            x.empty();
            for (var i = 0; i < data.length; i++) {
                // T152-ICS-324
                courses[i] = "T" + data[i].semester + "-" + data[i].pnameShort + "-" + data[i].courseCode + "-" + data[i].section;

                x.append('<button style = "text-align: center; color: blue; font-size: 16;" type="button" class="list-group-item" value = "' + courses[i] + '">' + courses[i] + '</button>');

            }
            btnClick();
        }).fail(
        function(jqXHR, textStatus, errorThrown) {
            // log error to browser's console
            console.log(errorThrown.toString());
            //return cl;
        });
}

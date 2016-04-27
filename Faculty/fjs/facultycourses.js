$(document).ready(function () {
    //getCourses('adam@kfupm.edu.sa');
    getCourses(getCookie('email'));
    btnClick();
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
function getPName(cname) {
    var x = cname.indexOf("-") + 1;
    cname = cname.substring(x);
    x = cname.indexOf("-");
    return cname.substring(0, x);
}
function getCourseCode(cname) {
    return cname.substring(cname.lastIndexOf("-") + 1);
}
function getTerm(cname) {
    return cname.substring(1, cname.indexOf("-"));
}
function btnClick()
{
    $('.list-group-item').click(function () {
        var cname = $(this).val();
        var PName = getPName(cname);
        var cc = getCourseCode(cname);
        var tt = getTerm(cname);
        document.cookie = "PName =" + PName;
        document.cookie = "CCode =" + cc;
        document.cookie = "Term =" + tt;
        window.location = 'ss.html';
        return false;
    });
}
function getCourses(fEmail) {


    var parameters = {
        grp: "Faculty",
        cmd: "getAllCourses",
        email: fEmail
    };
    $.getJSON("../index.php", parameters).done(
            function (data, textStatus, jqXHR)
            {
                var x = $('#courseList');
                //alert("He2y");
                var courses = [];
                x.empty();
                for (var i = 0; i < data.length; i++)
                {
                    // T152-ICS-324
                    courses[i] = "T" + data[i].semester + "-" + data[i].pnameShort + "-" + data[i].courseCode;
                    //alert(courses[i]);
                    //  document.writeln('  <button style = "text-align: center; color: blue; font-size: 16;" type="button" class="list-group-item" value = "' + courses[i % 3] + '">' + courses[i % 3] + '</button>');
                    x.append('<button style = "text-align: center; color: blue; font-size: 16;" type="button" class="list-group-item" value = "' + courses[i] + '">' + courses[i] + '</button>');

                }
                btnClick();
            }).fail(
            function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(errorThrown.toString());
                //return cl;
            });
}
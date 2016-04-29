$(document).ready(function () {
    $('.list-group-item').click(function () {
        var cname = $(this).val();
        var PName = getPName(cname);
        var cc = getCourseCode(cname);
        var tt = getTerm(cname);
        document.cookie = "PName =" + PName;
        document.cookie = "CCode =" + cc;
        document.cookie = "Term =" + tt;
        window.location = 'studentsurvey.html';
        return false;
    });
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.lengtFh; i++) {
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
});
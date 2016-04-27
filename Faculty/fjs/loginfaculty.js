$(document).ready(function () {
    $('.btn').click(function () {
        var email = $('#id1').val();
        var password = $('#pass').val();
        //alert("Email: " + email + " Password: " + password);

        /*
         SELECT GOES HERE
         THIS IS FROM THE USERS TABLE
         */
        // IF RESULT OF QUERY IS NOT NOLL AND THE TYPE IS ADMIN

        var parameters = {
            grp: "DisplayLogin",
            cmd: "FacultyLogin",
            femail: email,
            fpassword: password

        };

        $.getJSON("index.php", parameters).done(function (data, textStatus, jqXHR) {
            if (data[0].correct == true)
            {
                document.cookie = "email =" + email;
                //CHANGE LATER
                window.location = 'Faculty/facultycourses.html';
                return false;
            }
            else
            {
                $('#pp').text("Invalid user name or password");
                $('#pp').css('color', 'red');
            }
        }
        ).fail(function (jqXHR, textStatus, errorThrown)
        {
            // log error to browser's console
            console.log(textStatus + "\n" + errorThrown.toString());
            $('#pp').text("Cannot Connect to DB");
            $('#pp').css('color', 'red');

        });

        /*
         if (email === 'abd' && password === '123') {
         email = 'adam@kfupm.edu.sa';
         document.cookie = "email =" + email;
         //CHANGE LATER
         window.location = 'Faculty/facultycourses.html';
         return false;
         
         }
         else {
         
         $('#pp').text("Invalid user name or password");
         $('#pp').css('color', 'red');
         alert("inside else");
         
         }
         */
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
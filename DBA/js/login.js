$(document).ready(function () {
    $('.btn').click(function () {
        var email = $('#id1').val();
        var password = $('#pass').val();
        /*
         SELECT GOES HERE
         THIS IS FROM THE USERS TABLE
         */
        // IF RESULT OF QUERY IS NOT NOLL AND THE TYPE IS ADMIN
        var parameters = {
            grp: "DisplayLogin",
            cmd: "DBALogin",
            demail: email,
            dpassword: password

        };

        $.getJSON("index.php", parameters).done(function (data, textStatus, jqXHR) {
            if (data[0].correct == true)
            {
                document.cookie = "email =" + email;
                document.cookie = "password=" + password;
                window.location = 'DBA/students.html';
            }
            else
            {
                $('#pp').text("Invalid user name or password");
                $('#pp').css('color', 'red');
                $.cookie('email', null);
                $.cookie('password', null);
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
         document.cookie = "email =" + email;
         document.cookie = "password=" + password;
         window.location = 'DBA/students.html';
         }
         else {
         $('#pp').text("Invalid user name or password");
         $('#pp').css('color', 'red');
         $.cookie('email', null);
         $.cookie('password', null);
         
         }
         */
    });
});
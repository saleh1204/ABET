$(document).ready(function () {
    $('.btn').click(function () {
        var email = $('#id1').val();
        var password = $('#pass').val();
        /*
         SELECT GOES HERE
         THIS IS FROM THE USERS TABLE
         */
        // IF RESULT OF QUERY IS NOT NOLL AND THE TYPE IS ADMIN
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
    });
});
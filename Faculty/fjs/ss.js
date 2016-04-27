$(document).ready(function () {
    // REPLACE WITH COOKIE LATER
    $('#username').text(getCookie('email'));
    $('#coursedetails').text('T' + getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    generateTable();
    function generateTable() {
        // flush the table

//        getStudents
        var parameters = {
            grp: "Faculty",
            cmd: "getStudents",
            semester: getCookie('Term'),
            courseCode: getCookie('CCode'),
            pnameShort: getCookie('PName')
        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    var tb = $('#tbody');
                    $("#tbody tr").remove();
                    var col1 = [];//["12345", "45679", "12345", "45679"];
                    var col2 = [];//["name1", "name2", "name1", "name2"];

                    for (var i = 0; i < data.length; i++)
                    {
                        col1[i] = data[i].SUID;
                        col2[i] = data[i].STUName;

                    }
                    for (var i = 0; i < col1.length; i++) {
                        var tr = $('<tr>').appendTo(tb);
                        tr.append('<td >' + (i + 1) + '</td>');
                        tr.append('<td class = "col1">' + col1[i] + '</td>');
                        tr.append('<td class = "col2">' + col2[i] + '</td>');
                        // '<button class="btn btn-default" ><i class="glyphicon glyphicon-edit"></i>Edit</button>' 
                        tr.append('<td>' + '<button class="btn btn-default"  ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
                    }
                    $('#example').dataTable();
                    $('#example-keytable').DataTable({
                        keys: true
                    });
                    $('#example-responsive').DataTable();
                    $('#example-scroller').DataTable({
                        ajax: "js/datatables/json/scroller-demo.json",
                        deferRender: true,
                        scrollY: 380,
                        scrollCollapse: true,
                        scroller: true
                    });
                    var table = $('#example-fixed-header').DataTable({
                        fixedHeader: true
                    });
                    TableManageButtons.init();
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                    //return cl;
                });

        /*************************************
         SELECT * FROM TABLE, TABLE, TABLE
         THIS SELECT NEEDS ALL COOKIES
         *************************************
         */

    }
    var $col1;
    var $col2;
    $('#example').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        $col1 = $row.find(".col1").text();
        $col2 = $row.find(".col2").text();




        if ($(this).text().length === 6) {
            //alert("Delete: " + $col1 + $col2);
            // DELETE THE RECORD FROM DATABSE PLEASE
            // THIS DELETE WILL USE ALL THE VALUES IN THE COOKIES INCLUDING: EMAIL, SEMESTER, PNAMESHORT, COURSECODE
            // IN ADDITION TO COL1, COL2
            //alert('Term: ' + getCookie('Term'));
            //alert('Email: ' + getCookie('email'));
            //alert('ID: ' + $col1);
            //alert('PName: ' + getCookie('PName'));
            // alert('CCode: ' + getCookie('CCode'));
            var parameters = {
                grp: "Faculty",
                cmd: "deleteStudentSection",
                semester: getCookie('Term'),
                email: getCookie('email'),
                studentID: $col1,
                pnameShort: getCookie('PName'),
                courseCode: getCookie('CCode')

            };

            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                //alert("Added Successfully!!!");
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(textStatus + "\n" + errorThrown.toString());

            });
        } else {
            //alert("update" + $col1 + $col2);
            document.getElementById("inputC1A").value = $col1;
            document.getElementById("inputC2A").value = $col2;



        }
        generateTable();
    });
    $("#demo-form2").on('click', '#save', function () {




        //DELETE $col1, $col2 regardless of the operation, then insert the new values
        var newC1 = document.getElementById("inputC1A").value;
        //var newC2 = document.getElementById("inputC2A").value;
        //alert(newC1);

        /**************************
         DELETE GOES HERE
         INSERT STATEMENT GOES HERE
         **************************
         */

        var parameters = {
            grp: "Faculty",
            cmd: "addStudentSection",
            semester: getCookie('Term'),
            email: getCookie('email'),
            studentID: newC1,
            pnameShort: getCookie('PName'),
            courseCode: getCookie('CCode')

        };

        $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
            //alert("Added Successfully!!!");
            generateTable();
        }
        ).fail(function (jqXHR, textStatus, errorThrown)
        {
            // log error to browser's console
            console.log(textStatus + "\n" + errorThrown.toString());

        });

        document.getElementById("inputC1A").value = "";
        //document.getElementById("inputC2A").value = "";

        generateTable();

    });
    $("#demo-form2").on('click', '#cancel', function () {

        //alert("cancel");
        document.getElementById("inputC1A").value = "";
        //document.getElementById("inputC2A").value = "";

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
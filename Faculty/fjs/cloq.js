$(document).ready(function () {
    $("#inputC2A").val("");
    $("#inputC3A").val("");
    $('#coursedetails').text('T' + getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    $('#username').text(getCookie('email'));
    generateTable();
    fillSelect();
    function fillSelect() {

        // AJAX HERE
        // getStatus
        //alert("Here");
        var parameters1 = {
            grp: "Faculty",
            cmd: "getSO"
        };
        $.getJSON("../index.php", parameters1).done(
                function (data, textStatus, jqXHR)
                {

                    var s1 = $('#inputC3A');
                    s1.empty();
                    var s1L = [];
                    for (var i = 0; i < data.length; i++)
                    {
                        s1L[i] = data[i].SOCode;
                        // alert(data[i].SOCode);
                    }
                    for (var i = 0; i < s1L.length; i++) {
                        s1.append('<option value = "' + s1L[i] + '">' + s1L[i] + '</option>');
                    }
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                });


        var parameters = {
            grp: "Faculty",
            cmd: "getStatus",
            StatusType: "Survey"
        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {

                    var s2 = $('#inputC4A');
                    s2.empty();
                    var s1L = [];
                    for (var i = 0; i < data.length; i++)
                    {
                        //s1L[i] = data[i].StatusType;
                        s1L[i] = data[i].StatusName;
                        //alert(data[i].StatusType);
                        //alert
                    }
                    for (var i = 0; i < s1L.length; i++) {
                        s2.append('<option value = "' + s1L[i] + '">' + s1L[i] + '</option>');
                    }
                    // s1.val('');
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                });
    }
    function generateTable() {
// flush the table
        // alert('1');
        var parameters = {
            grp: "Faculty",
            cmd: "getCLOQuestions",
            semester: getCookie('Term'),
            courseCode: getCookie('CCode'),
            pnameShort: getCookie('PName')
        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    $("#tbody tr").remove();
                    var col1 = [];//["1", "2", "3", "4"];
                    var col2 = [];//["Question 1 text", "Question 2 text", "Question 3 text", "Question text 4"];
                    var col3 = [];//["a", "b", "c", "d"];
                    var col4 = [];//["Active", "Active", "Inactive", "Inactive"];
                    /*************************************
                     SELECT * FROM TABLE, TABLE, TABLE
                     THIS SELECT NEEDS ALL COOKIES
                     *************************************
                     */
                    var i = 0;
                    // alert(data);
                    for (i = 0; i < data.length; i++)
                    {
                        col1[i] = data[i].order;
                        col2[i] = data[i].question;
                        col3[i] = data[i].SOCode;
                        col4[i] = data[i].status;
                        // alert(i);
                    }
                    var tb = $('#tbody');

                    for (i = 0; i < col2.length; i++) {
                        var tr = $('<tr>').appendTo(tb);
                        tr.append('<td class = "col1">' + col1[i] + '</td>');
                        tr.append('<td class = "col2" style = "text-align: center;">' + col2[i] + '</td>');
                        tr.append('<td class = "col3" style = "text-align: center;">' + col3[i] + '</td>');
                        tr.append('<td class = "col4" style = "text-align: center;">' + col4[i] + '</td>');
                        tr.append('<td style = "text-align: center;">' + '<button class="btn btn-default" ><i class="glyphicon glyphicon-edit"></i>Edit</button>' + '<button class="btn btn-default" name = "del' + i + '" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
                    }
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
        ).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                    //return cl;
                });
        ;
    }
    var $col1;
    var $col2;
    var $col3;
    var $col4;
    $('#example').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        $col1 = $row.find(".col1").text();
        $col2 = $row.find(".col2").text();
        $col3 = $row.find(".col3").text();
        $col4 = $row.find(".col4").text();
        if ($(this).text().length === 6) {
// DELETE THE RECORD FROM DATABSE PLEASE
// THIS DELETE WILL USE ALL THE VALUES IN THE COOKIES INCLUDING: EMAIL, SEMESTER, PNAMESHORT, COURSECODE
// IN ADDITION TO COL1, COL2
            var parameters = {
                grp: "Faculty",
                cmd: "deleteQuestion",
                surveyType: 'CLO-Based',
                questionText: $col2,
                pnameShort: getCookie('PName'),
                courseCode: getCookie('CCode'),
                SOCode: $col3

            };

            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                // log error to browser's console
                console.log(textStatus + "\n" + errorThrown.toString());

            });
        } else { // it is an update
            document.getElementById("inputC1A").value = $col1;
            document.getElementById("inputC2A").value = $col2;
            $("#inputC3A").val($col3);
            $("#inputC4A").val($col4);
        }
        //generateTable();
    });
    $("#demo-form2").on('click', '#save', function () {
        //alert("save 1");
        var newC1 = document.getElementById("inputC1A").value;
        var newC2 = document.getElementById("inputC2A").value;
        var newC3 = document.getElementById("inputC3A").value;
        var newC4 = document.getElementById("inputC4A").value;
        // $dao->query($query, $request->get('orderNo'), $request->get('questionText'), $request->get('SurveyName'), $request->get('statusName'), $request->get('statusType'), $request->get('SOCode'), $request->get('courseCode'), $request->get('pnameShort'));
        /*
         *   $request->set("orderNo", '6');
         $request->set("questionText", 'Hello, Test2');
         $request->set("statusType", 'Survey');
         $request->set("statusName", 'Active');
         $request->set("SurveyName", 'CLO-Based');
         $request->set("pnameShort", 'ICS');
         $request->set("courseCode", '102');
         $request->set("SOCode", 'a');
         */
        var parameters = {
            grp: "Faculty",
            cmd: "addQuestion",
            orderNo: newC1,
            questionText: newC2,
            SurveyName: 'CLO-Based',
            statusName: newC4,
            statusType: 'Survey',
            pnameShort: getCookie('PName'),
            courseCode: getCookie('CCode'),
            SOCode: newC3

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
        document.getElementById("inputC2A").value = "";
        $("#inputC3A").val('');
        $("#inputC4A").val("");
        //generateTable();
    });
    $("#demo-form2").on('click', '#cancel', function () {

        alert("cancel");
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        $("#inputC3A").val('');
        $("#inputC4A").val("");
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
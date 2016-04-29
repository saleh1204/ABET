$(document).ready(function () {
    generateTable();
    $('#coursedetails').text('T' + getCookie('Term') + '-' + getCookie('PName') + '-' + getCookie('CCode'));
    $('#username').text(getCookie('email'));

    function generateTable() {
        // flush the table
        $("#tbody tr").remove();
        var col1 = ["a", "b", "c", "d"];
        var col2 = ["Question 1 text", "Question 2 text", "Question 3 text", "Question text 4"];
        // ARRAY BASED ON THE RECORD FROM THE SELECT
        var col3 = ["12", "5", "5", "3"];
        var col4 = ["12", "5", "5", "5"];
        var col5 = ["12", "5", "5", "4"];
        var col6 = ["12", "5", "5", "4"];
        // 
        /*************************************
         SELECT * FROM TABLE, TABLE, TABLE
         THIS SELECT NEEDS ALL COOKIES
         *************************************
         */
        var tb = $('#tbody');
        var i = 0;
        for (i = 0; i < col2.length; i++) {
            var tr = $('<tr>').appendTo(tb);
            tr.append('<td class = "col1">' + (i + 1) + '</td>');
            tr.append('<td class = "col1">' + col1[i] + '</td>');
            tr.append('<td class = "col2" style = "text-align: center;">' + col2[i] + '</td>');
            tr.append('<td class = "col3" style = "text-align: center;">' + col3[i] + '</td>');
            tr.append('<td class = "col4" style = "text-align: center;">' + col4[i] + '</td>');
            tr.append('<td class = "col4" style = "text-align: center;">' + col5[i] + '</td>');
            tr.append('<td class = "col4" style = "text-align: center;">' + col6[i] + '</td>');
            // tr.append('<td style = "text-align: center;">' + '<button class="btn btn-default" ><i class="glyphicon glyphicon-edit"></i>Edit</button>' +'<button class="btn btn-default" name = "del'+i+'" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>'+ '</td>');
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
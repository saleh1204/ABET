var isUpdate = false;
$(document).ready(function () {
    $("#inputC5A").val("");
    $('#PName').text(getCookie('PName') + '-Program');
    $('#username').text(getCookie('email'));
    generateTable();
    fillSelect();
    function fillSelect() {
        var s1 = $('#inputC5A');
        // AJAX HERE
        var s1L = ["Active", "Inactive"];
        for (var i = 0; i < s1L.length; i++) {
            s1.append('<option value = "' + s1L[i] + '">' + s1L[i] + '</option>');
        }
        s1.val('');
    }
    function generateTable() {

        var parameters = {
            grp: "Coordinator",
            cmd: "getCLOAnswers",
            pname: getCookie('PName')

        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    $("#tbody tr").remove();
                    var col1 = []; //["Strongly agree", "Agree"];
                    var col2 = []; //["4", "3"];
                    var col3 = []; //["201412", "32515"];
                    var col4 = []; //["12441", "5125"];
                    var col5 = []; //["Active", "Active"];
                    /*************************************
                     SELECT * FROM TABLE, TABLE, TABLE
                     THIS SELECT NEEDS ALL COOKIES
                     *************************************
                     */
                    for (var j = 0; j < data.length; j++)
                    {

                        col1[j] = data[j].weightName;
                        col2[j] = data[j].weightValue;
                        col3[j] = data[j].dateActivated;
                        col4[j] = data[j].dateDeactivated;
                        col5[j] = data[j].StatusName;
                        //alert(col1[j]);
                    }
                    var tb = $('#tbody');
                    var i = 0;
                    for (i = 0; i < col2.length; i++) {
                        var tr = $('<tr>').appendTo(tb);
                        tr.append('<td class = "col1" style = "text-align: center;">' + col1[i] + '</td>');
                        tr.append('<td class = "col2" style = "text-align: center;">' + col2[i] + '</td>');
                        tr.append('<td class = "col3" style = "text-align: center;">' + col3[i] + '</td>');
                        tr.append('<td class = "col4" style = "text-align: center;">' + col4[i] + '</td>');
                        tr.append('<td class = "col5" style = "text-align: center;">' + col5[i] + '</td>');
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
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    // log error to browser's console
                    console.log(errorThrown.toString());
                    //return cl;
                });
    }
    var $col1;
    var $col2;
    var $col3;
    var $col4;
    var $col5;
    $('#example').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        $col1 = $row.find(".col1").text();
        $col2 = $row.find(".col2").text();
        $col3 = $row.find(".col3").text();
        $col4 = $row.find(".col4").text();
        $col5 = $row.find(".col5").text();
        if ($(this).text().length === 6) {
// DELETE THE RECORD FROM DATABSE PLEASE
// THIS DELETE WILL USE ALL THE VALUES IN THE COOKIES 
            var parameters = {
                grp: "Coordinator",
                cmd: "deleteCLOAnswer",
                pname: getCookie('PName'),
                weightName: $col1,
                weightValue: $col2

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
            document.getElementById("inputC3A").value = $col3;
            document.getElementById("inputC4A").value = $col4;
            $("#inputC5A").val($col5);
            isUpdate = true;
        }
        generateTable();
    });
    $("#demo-form2").on('click', '#save', function () {
        //alert("save 1");
        var newC1 = document.getElementById("inputC1A").value;
        var newC2 = document.getElementById("inputC2A").value;
        var newC3 = document.getElementById("inputC3A").value;
        var newC4 = document.getElementById("inputC4A").value;
        var newC5 = document.getElementById("inputC5A").value;
        //alert(newC1 + " " + newC2 + " " + newC3 + newC5);
        /**************************
         DELETE GOES HERE (in case of update just to be safe)
         INSERT STATEMENT GOES HERE
         **************************
         */
        if (isUpdate)
        {
            var parameters = {
                grp: "Coordinator",
                cmd: "updateCLOAnswer",
                statusName: newC5,
                dateActivated: newC3,
                dateDeactivated: newC4,
                weightName: $col1,
                weightValue: $col2,
                newWeightName: newC1,
                newWeightValue: newC2,
                pname: getCookie('PName'),
            };
            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                //alert("Updated Successfully!!!");
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                console.log(textStatus + "\n" + errorThrown.toString());
            });
            isUpdate = false;
        }
        else
        {
            var parameters = {
                grp: "Coordinator",
                cmd: "addCLOAnswer",
                statusName: newC5,
                dateActivated: newC3,
                dateDeactivated: newC4,
                weightName: newC1,
                weightValue: newC2,
                pname: getCookie('PName'),
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
        }
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("inputC3A").value = "";
        document.getElementById("inputC4A").value = "";
        $("#inputC5A").val("");
        generateTable();
    });
    $("#demo-form2").on('click', '#cancel', function () {

        //alert("cancel");
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("inputC3A").value = "";
        document.getElementById("inputC4A").value = "";
        $("#inputC5A").val("");
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
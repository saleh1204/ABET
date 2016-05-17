var isUpdate = false;
$(document).ready(function () {
    $("#inputC8A").val("");
    $('#PName').text(getCookie('PName') + '-Program');
    $('#username').text(getCookie('email'));
    generateTable();
    fillSelect();
    function fillSelect() {
        var s1 = $('#inputC8A');
        // AJAX HERE
        var s1L = ["Active", "Inactive"];
        for (var i = 0; i < s1L.length; i++) {
            s1.append('<option value = "' + s1L[i] + '">' + s1L[i] + '</option>');
        }
        s1.val('');
        var parameters = {
            grp: "Coordinator",
            cmd: "getSO",
            PName: getCookie('PName')

        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    var s2 = $('#inputC1A');
                    for (var i = 0; i < data.length; i++) {
                        s2.append('<option value = "' + data[i].SOCode + '">' + data[i].SOCode + '</option>');
                    }
                    s2.val('');
                }).fail(
                function (jqXHR, textStatus, errorThrown)
                {
                    console.log(errorThrown.toString());
                });


    }
    function generateTable() {
        // flush the table
        var parameters = {
            grp: "Coordinator",
            cmd: "getPCAnswers",
            pname: getCookie('PName')

        };
        $.getJSON("../index.php", parameters).done(
                function (data, textStatus, jqXHR)
                {
                    $("#tbody tr").remove();
                    var col1 = [];//["a", "a"];
                    var col2 = [];//["1", "1"];         
                    var col3 = [];//["Model basic ...", "Solve advanced ...."];
                    var col4 = [];//["Devloping", "Satisfactory"];
                    var col5 = [];//["1", "1"];
                    var col6 = [];//["201412", "32515"];
                    var col7 = [];//["12441", "5125"];
                    var col8 = [];//["Active", "Active"];
                    /*************************************
                     SELECT * FROM TABLE, TABLE, TABLE
                     THIS SELECT NEEDS ALL COOKIES
                     *************************************
                     */
                    for (var j = 0; j < data.length; j++)
                    {
                        col1[j] = data[j].SOCode;
                        col2[j] = data[j].PCNum;
                        col3[j] = data[j].Description;
                        col4[j] = data[j].weightName;
                        col5[j] = data[j].weightValue;
                        col6[j] = data[j].dateActivated;
                        col7[j] = data[j].dateDeactivated;
                        col8[j] = data[j].StatusName;
                    }
                    var tb = $('#tbody');
                    var i = 0;
                    for (i = 0; i < col2.length; i++) {
                        var tr = $('<tr>').appendTo(tb);
                        tr.append('<td class = "col1">' + col1[i] + '</td>');
                        tr.append('<td class = "col2" style = "text-align: center;">' + col2[i] + '</td>');
                        tr.append('<td class = "col3" style = "text-align: center;">' + col3[i] + '</td>');
                        tr.append('<td class = "col4" style = "text-align: center;">' + col4[i] + '</td>');
                        tr.append('<td class = "col5" style = "text-align: center;">' + col5[i] + '</td>');
                        tr.append('<td class = "col6" style = "text-align: center;">' + col6[i] + '</td>');
                        tr.append('<td class = "col7" style = "text-align: center;">' + col7[i] + '</td>');
                        tr.append('<td class = "col8" style = "text-align: center;">' + col8[i] + '</td>');
                        tr.append('<td style = "text-align: center;">' + '<button class="btn btn-default" ><i class="glyphicon glyphicon-edit"></i>Edit</button>' + '<button class="btn btn-default" name = "del' + i + '" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>' + '</td>');
                    }
                    $('#example').dataTable();
                    $('#example-keytable').DataTable({
                        keys: true
                    });
                    $('#example-responsive').DataTable();
                    $('#example-scroller').DataTable({
                        ajax: "./js/datatables/json/scroller-demo.json",
                        deferRender: true, scrollY: 380,
                        scrollCollapse: true, scroller: true
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
    var $col6;
    var $col7;
    var $col8;
    $('#example').on('click', 'button', function () {
        var $row = $(this).closest('tr');
        $col1 = $row.find(".col1").text();
        $col2 = $row.find(".col2").text();
        $col3 = $row.find(".col3").text();
        $col4 = $row.find(".col4").text();
        $col5 = $row.find(".col5").text();
        $col6 = $row.find(".col6").text();
        $col7 = $row.find(".col7").text();
        $col8 = $row.find(".col8").text();



        //alert($(this).text());
        if ($(this).text().length === 6) {
            //alert('Delete1');
            // DELETE THE RECORD FROM DATABSE PLEASE
            // THIS DELETE WILL USE ALL THE VALUES IN THE COOKIES 
            // $request->get('weightName'), $request->get('weightValue'), 'Rubrics-Based', $request->get('pname')
            var parameters = {
                grp: "Coordinator",
                cmd: "deletePCAnswer",
                pname: getCookie('PName'),
                weightName: $col4,
                weightValue: $col5,
                pcnum: $col2, 
                SOCode: $col1
            };
            //alert('Delete2');
            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                console.log(jqXHR + "\n" + textStatus + "\n" + errorThrown.toString());

            });

        } else { // it is an update
            document.getElementById("inputC1A").value = $col1;
            document.getElementById("inputC2A").value = $col2;
            document.getElementById("inputC3A").value = $col3;
            document.getElementById("inputC4A").value = $col4;
            document.getElementById("inputC5A").value = $col5;
            document.getElementById("inputC6A").value = $col6;
            document.getElementById("inputC7A").value = $col7;
            isUpdate = true;
            $("#inputC8A").val($col8);



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
        var newC6 = document.getElementById("inputC6A").value;
        var newC7 = document.getElementById("inputC7A").value;
        var newC8 = document.getElementById("inputC8A").value;
        //alert(newC1 + " " + newC2 + " " + newC3 + newC5 + newC8);
        /**************************
         DELETE GOES HERE (in case of update just to be safe)
         INSERT STATEMENT GOES HERE
         **************************
         */
        if (isUpdate)
        {
            isUpdate = false;
            var parameters = {
                grp: "Coordinator",
                cmd: "updatePCAnswer",
                oldSOCode: $col1,
                SOCode: newC1,
                pcnum: newC2,
                answer: newC3,
                statusName: newC8,
                dateActivated: newC6,
                dateDeactivated: newC7,
                weightName: newC4,
                weightValue: newC5,
                oldWeightName: $col4,
                oldWeightValue: $col3,
                pname: getCookie('PName')
            };
            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                //alert("Added Successfully!!!");
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                console.log(textStatus + "\n" + errorThrown.toString());
            });
        }
        else
        {
            // $request->get('SOCode'), $request->get('pname'), $request->get('pname'), $request->get('pcnum'), $request->get('answer'), $request->get('weightName'), $request->get('weightValue'), $request->get('dateActivated'), $request->get('dateDeactivated'), 'Rubrics-Based', 'Rubrics-Based', $request->get('statusName')
            //alert('Add');
            var parameters = {
                grp: "Coordinator",
                cmd: "addPCAnswer",
                SOCode: newC1,
                pcnum: newC2,
                answer: newC3,
                statusName: newC8,
                dateActivated: newC6,
                dateDeactivated: newC7,
                weightName: newC4,
                weightValue: newC5,
                pname: getCookie('PName'),
            };
            $.getJSON("../index.php", parameters).done(function (data, textStatus, jqXHR) {
                //alert("Added Successfully!!!");
                generateTable();
            }
            ).fail(function (jqXHR, textStatus, errorThrown)
            {
                console.log(textStatus + "\n" + errorThrown.toString());
            });
        }
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("inputC3A").value = "";
        document.getElementById("inputC4A").value = "";
        document.getElementById("inputC5A").value = "";
        document.getElementById("inputC6A").value = "";
        document.getElementById("inputC7A").value = "";

        $("#inputC8A").val("");
        generateTable();

    });
    $("#demo-form2").on('click', '#cancel', function () {

        //alert("cancel");
        document.getElementById("inputC1A").value = "";
        document.getElementById("inputC2A").value = "";
        document.getElementById("inputC3A").value = "";
        document.getElementById("inputC4A").value = "";
        document.getElementById("inputC5A").value = "";
        document.getElementById("inputC6A").value = "";
        document.getElementById("inputC7A").value = "";
        $("#inputC8A").val("");

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
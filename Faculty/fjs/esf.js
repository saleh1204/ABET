$(document).ready(function() {
                        
                         generateTable();
                         $('#coursedetails').text('T' + getCookie('Term') + '-'+getCookie('PName') + '-'+getCookie('CCode'));
                        $('#username').text(getCookie('email'));
                 function generateTable(){
                 // flush the table
                 $("#tbody tr").remove();
                 var col1 = ["Stduent ID1", "Stduent ID3", "Stduent ID2"];
                 var col2 = ["4", "3", "2"];
                 var col3 = ["4", "3", "2"];
                 var col4 = ["4", "3", "2"];
                 var col5 = ["4", "3", "2"];
                 /*************************************
                  SELECT * FROM TABLE, TABLE, TABLE
                  THIS SELECT NEEDS ALL COOKIES
                  This is the RAW DATA select statement for Employer
                  *************************************
                  */
                 var tb = $('#tbody');
                  var i = 0;
                 for (i = 0; i < col2.length; i++){
                     var tr = $('<tr>').appendTo(tb);
                     tr.append('<td class = "sr">' + (i+1) + '</td>');
                     // PUT A FOR LOOP THAT IS BASED ON THE RECORD LENGTH
                     // THIS CAN'T BE DONE WITHOUT DATABASE CONNECTION
                     tr.append('<td class = "col1" style = "text-align: center;">' + col1[i] + '</td>');
                     tr.append('<td class = "col2" style = "text-align: center;">' + col2[i] + '</td>');
                     tr.append('<td class = "col3" style = "text-align: center;">' + col3[i] + '</td>');
                     tr.append('<td class = "col4" style = "text-align: center;">' + col4[i] + '</td>');
                     tr.append('<td class = "col5" style = "text-align: center;">' + col5[i] + '</td>');
                     tr.append('<td style = "text-align: center;">' + '<button class="btn btn-default" ><i class="glyphicon glyphicon-edit"></i>Edit</button>' +'<button class="btn btn-default" name = "del'+i+'" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>'+ '</td>');
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
                  var $col1;
                  var $col2;
                  var $col3;
                  var $col4;
                  var $col5;
                  $('#example').on('click', 'button', function(){
                               var $row = $(this).closest('tr');
                               // PUT AN ARRAY BASED ON THE RECORD LENGTH
                               // LOOP TO INITIALIZE THE ARRAY
                              $col1 = $row.find(".col1").text();
                              $col2 = $row.find(".col2").text();
                              $col3 = $row.find(".col3").text();
                              $col4 = $row.find(".col4").text();
                              $col5 = $row.find(".col5").text();




                               if ($(this).text().length === 6){
                               // DELETE THE RECORD FROM DATABSE PLEASE
                               // THIS DELETE WILL USE ALL THE VALUES IN THE COOKIES INCLUDING: EMAIL, SEMESTER, PNAMESHORT, COURSECODE
                               // IN ADDITION TO COL1, COL2,....
                               } else{ // it is an update

                                // LOOP HERE
                               document.getElementById("inputC1A").value = $col1;
                               document.getElementById("inputC2A").value = $col2;
                               document.getElementById("inputC3A").value = $col3;
                               document.getElementById("inputC4A").value = $col4;
                               document.getElementById("inputC5A").value = $col5;



                             
                               }
                               generateTable();
                               });
                                              $("#demo-form2").on('click', '#save', function () {
                                                  alert("save 1");
                                                  // LOOP HERE WITH ARRAY OF SIZE = (RECORD LENGTH) TO STORE VARIABLES
                                                var newC1 = document.getElementById("inputC1A").value;
                                                var newC2 = document.getElementById("inputC2A").value;
                                                var newC3 = document.getElementById("inputC3A").value;
                                                var newC4 = document.getElementById("inputC4A").value;
                                                var newC5 = document.getElementById("inputC4A").value;


                                                alert(newC1 + " "+newC2+" "+newC3);
                                                /**************************
                                                 DELETE GOES HERE (in case of update just to be safe)
                                                 INSERT STATEMENT GOES HERE
                                                 **************************
                                                 */
                                                document.getElementById("inputC1A").value = "";
                                                document.getElementById("inputC2A").value = "";
                                                document.getElementById("inputC3A").value = "";
                                                document.getElementById("inputC4A").value = "";
                                                document.getElementById("inputC5A").value = "";
                                                generateTable();
                                             
                                                });
                                          $("#demo-form2").on('click', '#cancel', function () {

                                                  alert("cancel");
                                                  // LOOP HERE
                                                    document.getElementById("inputC1A").value = "";
                                                document.getElementById("inputC2A").value = "";
                                                document.getElementById("inputC3A").value = "";
                                                document.getElementById("inputC4A").value = "";
                                                document.getElementById("inputC5A").value = "";

                                                });
                                          function getCookie(cname) {
                          var name = cname + "=";
                          var ca = document.cookie.split(';');
                          for(var i = 0; i <ca.length; i++) {
                              var c = ca[i];
                              while (c.charAt(0)==' ') {
                                  c = c.substring(1);
                              }
                              if (c.indexOf(name) == 0) {
                                  return c.substring(name.length,c.length);
                              }
                          }
                          return "";
                      }



} );
$(document).ready(function() {
                        $("#inputC1A").val("");
                        $('#coursedetails').text('T' + getCookie('Term') + '-'+getCookie('PName') + '-'+getCookie('CCode'));
                        $('#username').text(getCookie('email'));

                         generateTable();
                         fillSelect();
                         function fillSelect(){
                          var s1 = $('#inputC1A');
                          // AJAX HERE based on COOKIES
                          var s1L = ["Q 1 text", "Q 2 text", "Q 3 text"];
                          for (var i = 0; i < s1L.length; i++){
                            s1.append('<option value = "'+s1L[i]+'">'+s1L[i]+'</option>');
                          }
                          s1.val('');
                         
                         }
                 function generateTable(){
                 // flush the table
                 $("#tbody tr").remove();
                 var col1 = ["Q 1 text", "Q 2 text", "Q 3 text"];
                 var col2 = ["wekness 1 ...", "wekness 2 ...", "wekness 3 ..."];
                 var col3 = ["action 1 ...", "action 2 ...", "actoin 3 ..."];
                 /*************************************
                  SELECT * FROM TABLE, TABLE, TABLE
                  THIS SELECT NEEDS ALL COOKIES
                  *************************************
                  */
                 var tb = $('#tbody');
                  var i = 0;
                 for (i = 0; i < col2.length; i++){
                     var tr = $('<tr>').appendTo(tb);
                     tr.append('<td >' + (i + 1) + '</td>');
                     tr.append('<td class = "col1" style = "text-align: center;">' + col1[i] + '</td>');
                     tr.append('<td class = "col2" style = "text-align: center;">' + col2[i] + '</td>');
                     tr.append('<td class = "col3" style = "text-align: center;">' + col3[i] + '</td>');
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
                  $('#example').on('click', 'button', function(){
                               var $row = $(this).closest('tr');
                                $col1 = $row.find(".col1").text();
                                $col2 = $row.find(".col2").text();
                                $col3 = $row.find(".col3").text();




                               if ($(this).text().length === 6){
                               // DELETE THE RECORD FROM DATABSE PLEASE
                               // THIS DELETE WILL USE ALL THE VALUES IN THE COOKIES INCLUDING: EMAIL, SEMESTER, PNAMESHORT, COURSECODE
                               // IN ADDITION TO COL1, COL2
                               } else{ // it is an update
                               document.getElementById("inputC2A").value = $col2;
                               document.getElementById("inputC3A").value = $col3;
                               $("#inputC1A").val($col1);


                               
                               }
                               generateTable();
                               });
                              $("#demo-form2").on('click', '#save', function () {
                                                  alert("save 1");
                                                var newC1 = document.getElementById("inputC1A").value;
                                                var newC2 = document.getElementById("inputC2A").value;
                                                var newC3 = document.getElementById("inputC3A").value;


                                                alert(newC1 + " "+newC2+" "+newC3);
                                                /**************************
                                                 DELETE GOES HERE (in case of update just to be safe)
                                                 INSERT STATEMENT GOES HERE
                                                 **************************
                                                 */
                                                document.getElementById("inputC2A").value = "";
                                                document.getElementById("inputC3A").value = "";
                                                $("#inputC1A").val('');
                                                generateTable();
                                             
                                                });
                                          $("#demo-form2").on('click', '#cancel', function () {

                                                  alert("cancel");
                                                    document.getElementById("inputC2A").value = "";
                                                    document.getElementById("inputC3A").value = "";
                                                    $("#inputC1A").val('');

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
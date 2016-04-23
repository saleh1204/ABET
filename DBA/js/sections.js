$(document).ready(function(){
                  
                 generateTable();
                 function generateTable(){
                 // flush the table
                 $("#tbody tr").remove();
                 var col1 = ["ICS", "SWE", "EE"];
                 var col2 = ["324","363", "207"];
                 var col3 = ["152", "153", "142"];
                  var col4 = ["FacultyA", "FacultyB", "FacultyC"];
                  var col5 = ["1", "2", "3"];
                 /*************************************
                  SELECT * FROM TABLE JOIN TABLE
                  *************************************
                  */
                 var tb = $('#tbody');
                  var i = 0;
                 for (i = 0; i < col2.length; i++){
                     var tr = $('<tr>').appendTo(tb);
                     tr.append('<td class = "sr">' + (i+1) + '</td>');
                     tr.append('<td class = "col1">' + col1[i] + '</td>');
                     tr.append('<td class = "col2">' + col2[i] + '</td>');
                     tr.append('<td class = "col3">' + col3[i] + '</td>');
                     tr.append('<td class = "col4">' + col4[i] + '</td>');
                     tr.append('<td class = "col5">' + col5[i] + '</td>');
                     tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' +'<button class="btn btn-default" name = "del'+i+'" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>'+ '</td>');
                  }
                  }
                  
                  $('#myT').on('click', 'button', function(){
                               var $row = $(this).closest('tr');
                               var $col1 = $row.find(".col1").text();
                               var $col2 = $row.find(".col2").text();
                               var $col3 = $row.find(".col3").text();
                               var $col4 = $row.find(".col4").text();
                               var $col5 = $row.find(".col5").text();



                               if ($(this).text().length === 6){
                               // DELETE THE RECORD FROM DATABSE PLEASE
                               } else{ // it is an update
                               document.getElementById("mySelectU1").value = $col1;
                               document.getElementById("inputC1U").value = $col2;
                               document.getElementById("mySelectU2").value = $col3;
                               document.getElementById("mySelectU3").value = $col4;
                               document.getElementById("inputC2U").value = $col5;
                               $('#update').unbind().click(function(){
                                                         var newC1 = document.getElementById("mySelectU1").value;
                                                         var newC2 = document.getElementById("inputC1U").value;
                                                         var newC3 = document.getElementById("mySelectU2").value;
                                                         var newC4 = document.getElementById("mySelectU3").value;
                                                         var newC5 =  document.getElementById("inputC2U").value;
                                                         // THE UPDATE STATEMENT GOES HERE
                                                      
  

                                                         });
                               }
                               generateTable();
                               });
                  
                               $('#addR').click(function(){
                                                 document.getElementById("mySelectA1").value = "";
                                                 document.getElementById("mySelectA2").value = "";
                                                 document.getElementById("mySelectA3").value = "";
                                                });
                               $('#save').click(function(){
                                                var newC1 = document.getElementById("mySelectA1").value;
                                                var newC2 = document.getElementById("inputC1A").value;
                                                var newC3 = document.getElementById("mySelectA2").value;
                                                var newC4 = document.getElementById("mySelectA3").value;
                                                var newC5 = document.getElementById("inputC2A").value;
                                                // INSERT STATEMENT GOES HERE
                                                document.getElementById("mySelectA1").value = "";
                                                document.getElementById("inputC1A").value = "";
                                                document.getElementById("mySelectA2").value = "";
                                                document.getElementById("mySelectA3").value = "";
                                                document.getElementById("inputC2A").value = "";
                                                generateTable();
                                             
                                                });

                 
                 
                 });
$(document).ready(function(){
                  
                 generateTable();
                 function generateTable(){
                 // flush the table
                 $("#tbody tr").remove();
                 var col1 = "ICS";
                 var col2 = ["Adam","Kanaan"];
                 var col3 = ["Salahadin Adam Mohammad", "Kanaan Faisal Abed"];
                  var col4 = ["Active", "Active"];
                 /*************************************
                  SELECT * FROM TABLE JOIN TABLE
                  *************************************
                  */
                 var tb = $('#tbody');
                  var i = 0;
                 for (i = 0; i < col2.length; i++){
                     var tr = $('<tr>').appendTo(tb);
                     tr.append('<td class = "sr">' + (i+1) + '</td>');
                     tr.append('<td class = "col1">' + col1 + '</td>');
                     tr.append('<td class = "col2">' + col2[i] + '</td>');
                     tr.append('<td class = "col3">' + col3[i] + '</td>');
                     tr.append('<td class = "col4">' + col4[i] + '</td>');
                     tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' +'<button class="btn btn-default" name = "del'+i+'" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>'+ '</td>');
                  }
                  }
                  $('#myT').on('click', 'button', function(){
                               var $row = $(this).closest('tr');
                               var $col1 = $row.find(".col1").text();
                               var $col2 = $row.find(".col2").text();
                               var $col3 = $row.find(".col3").text();
                               var $col4 = $row.find(".col4").text();



                               if ($(this).text().length === 6){
                               // DELETE THE RECORD FROM DATABSE PLEASE
                               } else{ // it is an update
                               document.getElementById("mySelectU1").value = $col1;
                               document.getElementById("inputC1U").value = $col2;
                               document.getElementById("inputC2U").value = $col3;
                               document.getElementById("mySelectU2").value = $col4;
                               $('#update').unbind().click(function(){
                                                         var newC1 = document.getElementById("mySelectU1").value;
                                                         var newC2 = document.getElementById("inputC1U").value;
                                                         var newC3 = document.getElementById("inputC2U").value;
                                                         var newC4 = document.getElementById("mySelectU2").value;
                                                         alert("UPDATING: "+newC1 + " "+newC2 + " " + newC3 +  newC4 );
                                                         // THE UPDATE STATEMENT GOES HERE

                                                         });
                               }
                               generateTable();
                               });
                               $('#addR').click(function(){
                                                 document.getElementById("mySelectA1").value = "";
                                                 document.getElementById("mySelectA2").value = "";
                                                });
                               $('#save').click(function(){
                                                alert("INSIDE SAVE");
                                                var newC1 = document.getElementById("mySelectA1").value;
                                                var newC2 = document.getElementById("inputC1A").value;
                                                var newC3 = document.getElementById("inputC2A").value;
                                                var newC4 = document.getElementById("mySelectA2").value;
                                                /**************************
                                                 INSERT STATEMENT GOES HERE
                                                 **************************
                                                 */
                                                document.getElementById("mySelectA1").value = "";
                                                document.getElementById("inputC1A").value = "";
                                                document.getElementById("inputC2A").value = "";
                                                document.getElementById("mySelectA2").value = "";
                                                generateTable();
                                             
                                                });

                 
                 
                 });
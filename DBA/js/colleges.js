$(document).ready(function(){
                 generateTable();
                 function generateTable(){
                 // flush the table
                 $("#tbody tr").remove();
                 var csnKFUPM = ["CCSE", "CAE", "Science"];
                 var clnKFUPM = ["College Of Computer Science and Enginnereing", "Applied Enginnering", "Scinece "];
                 /*************************************
                  SELECT * FROM COLLEGE
                  *************************************
                  */
                 var tb = $('#tbody');
                  var i = 0;
                 for (i = 0; i < csnKFUPM.length; i++){
                 var tr = $('<tr>').appendTo(tb);
                 tr.append('<td class = "sr">' + (i+1) + '</td>');
                 tr.append('<td class = "name">' + clnKFUPM[i] + '</td>');
                 tr.append('<td class = "sname">' + csnKFUPM[i] + '</td>');
                 tr.append('<td>' + '<button class="btn btn-default" data-toggle="modal" data-target="#myModalU"><i class="glyphicon glyphicon-edit"></i>Edit</button>' +'<button class="btn btn-default" name = "del'+i+'" ><i class="glyphicon glyphicon-remove\"></i>Delete</button>'+ '</td>');
                  }
                  }
                  var isAdd = 1;
                  $('#myT').on('click', 'button', function(){
                               var $row = $(this).closest('tr');
                               var $name = $row.find(".name").text();
                               var $sname = $row.find(".sname").text();
                               if ($(this).text().length === 6){
                               // DELETE THE RECORD FROM DATABSE
                               } else{ // it is an update
                               document.getElementById("inputCNameU").value = $name;
                               document.getElementById("inputCSNameU").value = $sname;
                               $('#update').unbind().click(function(){
                                                         var newName = document.getElementById("inputCNameU").value;
                                                         var newSName = document.getElementById("inputCSNameU").value;
                                                        // if (isAdd === 0){
                                                         alert("UPDATING: "+newName + " "+newSName + " " );
                                                         // THE UPDATE STATEMENT GOES HERE
                                                         /*
                                                          ********
                                                          ********
                                                          ********
                                                          */
                                                         document.getElementById("inputCNameU").value = "";
                                                         document.getElementById("inputCSNameU").value = "";
                                                        // }

                                                         });
                               }
                               generateTable();
                               });
                               $('#addR').click(function(){
                                                });
                               $('#save').click(function(){
                                                alert("INSIDE SAVE");
                                                var newName = document.getElementById("inputCNameA").value;
                                                var newSName = document.getElementById("inputCSNameA").value;
                                                alert("INSERTING " + newName + " " + newSName+" " + newU);
                                                /**************************
                                                 INSERT STATEMENT GOES HERE
                                                 **************************
                                                 */
                                                document.getElementById("inputCNameA").value = "";
                                                document.getElementById("inputCSNameA").value = "";
                                                generateTable();
                                             
                                                });

                 
                 
                 });
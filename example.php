<?php

$arr = array();
$arr[] = array("id" => 1, "user" => "alexis.walker@example.net");
$arr[] = array("id" => 2, "user" => "gottlieb.chase@example.org");
$arr[] = array("id" => 3, "user" => "rhianna.champlin@example.com");
$arr[] = array("id" => 4, "user" => "daniel.braeden@example.com");

//equal with
//if (isset($_GET["id"])) {
//    $find_sql = "select * from users";
//    $stmt      = $db->prepare($find_sql);
//    $stmt->execute();
//    $arr = $stmt->fetchAll();
//}

?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />


    <script src="assets/jquery-3.1.1.min.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src="assets/bootstrap-selector.js"></script>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
      
    <script>
        $(function() {
            
            //////////////// CHOOSER
            //**attach the event
            $('#jobs_users').chooser();
            $('#jobs_countries').chooser();
            
            //convert PHP array to JSON
            var jArray_USERS = <?php echo json_encode($arr); ?> ;
            
            //if has items
            if (jArray_USERS)
            {
                //give the json array to chooser and write Users to header!
                $("#jobs_users").fillList(jArray_USERS, "Users", "id", "user");
                
                //select all by default!
                $("#jobs_users").setSelected(jArray_USERS, "id");
            }
            
            
            //////////////// CHOOSER + - 
            //select all
            $('#btn_jobs_users_select_all').on('click', function(e) {
                e.preventDefault();

                $('#jobs_users').setAll(true);
            });

            //deselect all
            $('#btn_jobs_users_deselect_all').on('click', function(e) {
                e.preventDefault();

                $('#jobs_users').setAll(false);
            });
        }) // jQuery end
        
        //when FORM SUBMIT with native way
        //**before form submit**
        function validate(){
            
            //get #selected users#
            var users = $("#jobs_users").getSelected();

            if (!users[0])
            {
                alert("Please choose users");
                return false;
            } else {
                //print it to HIDDEN inputbox
                $("#users").val(users);
            }


            return true;
        }
        
        //when FORM SUBMIT with AJAX way
        //save form
        $('#form_APPOINTMENTS').submit(function(e)
            {
                e.preventDefault();
 
                //get #selected ids#
                var get_selected_users = $("#users").getSelected();
 
                if (get_selected_users.length==0)
                {
                    alert("Please choose users!");
                    return;
                }
 
                var postData = $(this).serializeArray();
                var formURL = $(this).attr("action"); //get the destination filename from form
 
                //merge to serialization - stringify #selected ids#
                postData.push({name: "users", value : JSON.stringify(get_selected_users)});
 
                $.ajax(
                    {
                        url : formURL,
                        type: "POST",
                        data : postData,
                        success:function(data, textStatus, jqXHR)
                        {
                            //depends on your PHP implementation
                            if (data=="00000")
                                //refresh
                                //loadUSERS();
                                alert("success");
                            else
                                alert("ERROR");
                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            alert("ERROR - connection error");
                        }
                    });
            });
      </script>
      
<body>
    <div class="container">
      <div class="row" style="margin-top:10px">
        <div class="col-md-6">
            <form id="theform" method="post" action="second.php" onsubmit="return validate()">
                <button class="btn btn-success" id="btn_jobs_users_select_all" style="margin-bottom:10px">+</button>
                <button class="btn btn-success" id="btn_jobs_users_deselect_all" style="margin-bottom:10px">-</button>

                <div id="jobs_users" class="list-group centre" ></div>
                <input id="users" name="users" type="hidden"> <!-- NO NEEDED when you submit with AJAX -->
                
                <button class="btn btn-success" style="float:right" type="submit">save</button>
            </form>
        </div>
        <div class="col-md-6">
        </div>
      </div>
    </div>
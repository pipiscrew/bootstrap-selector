<?php
 
$rows_USERS=null;
///////////////////READ USERS
if (isset($_GET["id"])) {
    $find_sql = "select * from users";
    $stmt      = $db->prepare($find_sql);
    $stmt->execute();
    $rows_USERS = $stmt->fetchAll();
}
///////////////////READ USERS
?>
 
<script>
    $(function() {
        //**attach the event
        $('#client_s_users').chooser();
 
        //**fill list by PHP/jSON Array
        var jArray_USERS = <? php echo json_encode($rows_USERS); ?> ;
 
        if (jArray_USERS)
            $("#client_s_users").fillList((jArray_USERS), "Participants", "user_id", "fullname");
 
        // MODAL FUNCTIONALITIES [START]
        //when modal closed, hide the warning messages + reset
        $('#modalCLIENT_S').on('hidden.bs.modal', function() {
            //when close - clear elements
            $('#formCLIENT_S').trigger("reset");
 
            //**reset users list
            $("#client_s_users").clearList();
 
            //clear validator error on form
            validatorCLIENT_S.resetForm();
        });
 
 
 
        //edit button - read record 
        function query_CLIENT_S_modal(rec_id){
            $.ajax(
            {
                url : "x_fetch.php",
                type: "POST",
                data : { x_id : rec_id },
                success:function(dataO, textStatus, jqXHR)
                {
                    if (dataO!='null')
                    {
                        var data = dataO.appointment; //the record [ONE]
                        var dataP = dataO.participants; // the details-record [MANY] (aka appear on chooser)
                         
                        //** USE of - select the values from DBASE^
                        $("#client_s_users").setSelected(dataP,"user_id")
                         
                        $('#modalCLIENT_S').modal('toggle');
                    }
                    else
                        alert("ERROR - Cant read the record.");
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    alert("ERROR");
                }
            });
        }
 
        //save form
        $('#form_APPOINTMENTS').submit(function(e)
            {
                e.preventDefault();
 
                ////////////////////////// validation
                var form = $(this);
                form.validate();
 
                if (!form.valid())
                return;
                ////////////////////////// validation
 
                //get #selected ids#
                var get_selected_participants = $("#client_appointments_users").getSelected();
 
                if (get_selected_participants.length==0)
                {
                    alert("Please choose participants!");
                    return;
                }
 
                var postData = $(this).serializeArray();
                var formURL = $(this).attr("action");
 
                //merge to serialization - stringify #selected ids#
                postData.push({name: "participants", value : JSON.stringify(get_selected_participants)});
 
                $.ajax(
                    {
                        url : formURL,
                        type: "POST",
                        data : postData,
                        success:function(data, textStatus, jqXHR)
                        {
                            if (data=="00000")
                                //refresh
                                loadAPPOINTMENTSrecs();
                            else
                                alert("ERROR");
                        },
                        error: function(jqXHR, textStatus, errorThrown)
                        {
                            alert("ERROR - connection error");
                        }
                    });
            });
    }); //jQuery end
</script>
<body>
    <div id="client_s_users" class="list-group centre"></div>
</body>

//x_fetch.php ends as :
$json = array('appointment'=> $r,'participants' => $x);
echo json_encode($json);
 
//x_save.php as :
$arr = json_decode($_POST['participants'],true);
 
$participants ="0";
foreach ($arr as $userID) {
    $participants.= ",{$userID}";
}
 
//use it to find fullname
$partSET = getSet($db,"select fullname from users where user_id in ({$participants})",null);
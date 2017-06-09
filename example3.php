<?php

/*
PK - USERS
FK - USER_LNGS
$_POST['lngs_txt'] - from HTML --> .getSelected()
*/

----------------

$user_id =0;

/////////////////////
// USERS TABLE
$pwd_md5 = md5($_POST["user_password"]); //convert plain text to md5

$ret_val="";
if(isset($_POST['usersFORM_updateID']) && !empty($_POST['usersFORM_updateID']))
{
	$sql = "UPDATE `users` set user_name=:user_name, user_password=:user_password, user_level=:user_level, date_rec=:date_rec WHERE user_id=:user_id";
	$stmt = $db->getConnection()->prepare($sql);
	$stmt->bindValue(':user_id', $_POST['usersFORM_updateID']);
	$ret_val = "isupdate";
    
    $user_id = $_POST['usersFORM_updateID'];
}
else
{
    //validate if the same user_name exists
    $validation = $db->getScalar("select user_id from users where user_name = ?", array($_POST['user_name']));
    
    if ($validation){
                echo "<link href='css/bootstrap.min.css' rel='stylesheet'><div class='container'><div class='alert alert-danger'>Found a user with the same 'User name', please click BACK and fix the user name</div></div>";
                exit;
    }
        
    //validate if the same user_name exists
    
	$sql = "INSERT INTO `users` (user_name, user_password, user_level, date_rec) VALUES (:user_name, :user_password, :user_level, :date_rec)";
	$stmt = $db->getConnection()->prepare($sql);
	$ret_val = "isnew";
}

$stmt->bindValue(':user_name' , $_POST['user_name']);
$stmt->bindValue(':user_password' , $pwd_md5);
$stmt->bindValue(':user_level' , $_POST['user_level']);
$stmt->bindValue(':date_rec' , date("Y-m-d H:i:s"));

$stmt->execute();

$res = $stmt->rowCount();

////languages
if($res == 1) {
    
    if ($user_id==0) //coming from INSERT
        $user_id = $db->getConnection()->lastInsertId(); //set the new user_id to the variable
    
    
   if (add_languages($user_id, $_POST['lngs_txt'])) {
       echo "languages added success";
   }
    else 
        echo "languages failed";
}

if($res == 1)
	header("Location: tab_users.php?$ret_val=1");
else
	header("Location: tab_users.php?iserror=1");


function add_languages($user_id, $lngs_csv){
    global $db;
            
            //delete all the recs for this user
            $db->executeSQL("delete from user_lngs where user_id=?", array($user_id));
    
    
            //////////////////////////////////
            //add user to languages table
            $lngs = explode(',', $lngs_csv);

            //prepare the statement
            $sql = "INSERT INTO `user_lngs` (user_id, language_id) VALUES (:user_id, :language_id)";
            $stmt = $db->getConnection()->prepare($sql);

            foreach ($lngs as $lng) {

                //bind the values
                $stmt->bindValue(":user_id" , $user_id);
                $stmt->bindValue(":language_id" , $lng);

                //execute the prepared statement
                $stmt->execute();	
                if($stmt->errorCode() != "00000"){
                    $message = "Error while adding the language record to dbase.";
                    //exit;
                }
            }

            if ($stmt->errorCode()=="00000")
            {
                return true;
            } else {
                return false;
            }
}

?>
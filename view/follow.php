<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php
//the current logged in user
$currentuser=$_SESSION["username"];
//the member current user wants to follow.
$membername = $_GET['q'];

if ($currentuser==$membername){
    echo "you can't follow yourslef";
}else{
    if(check_if_member_in_following_list($currentuser,$membername)===false){
        add_to_member_into_following_list($currentuser,$membername);
        
    }else{
        delete_member_form_following_list($currentuser,$membername);
    }
}
?>

<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php
	$_SESSION["username"] = null;
	$_SESSION["member"] = null;
	redirect_to("../index.php");
?>
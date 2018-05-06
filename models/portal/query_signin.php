<?php
require_once('../../connection.php');
require_once('../user.php');
require_once('../user_log.php');
require_once('../ip_block.php');

  // if SESSION not exists, create session
session_start();

// The query page is split from display page for easy expansion and copying.

// By default no redirecting is needed
$ajaxRedirect = "no";

$errorType = "Branching";

$success = false;

// This boolean is used to skip remaining procedures if fatal error has occured.
$forceStop = false;

// "False" value is used for debug
if(!isset($_POST['cred'])||!isset($_POST['password']))
{
	// If trepassing, force return to homepage
	$ajaxRedirect = "index.html";
    header("Location: ../index.php");
}
else
{	
	$ban_remain = Ip_block::getBanRemain();
	if ($ban_remain>0) {
		$forceStop = true;
		$errorType = "Access denied due to repeated failure to sign in.<br/>Remaining time: ".$ban_remain;
		User_log::insert(NULL, "Login", "Banned IP access attempt", 3, NULL);
	}

	$cred = $_POST['cred'];
	$password = $_POST['password'];

	// Whenever a fatal error might happen, forceStop is checked to ensure quickest execution.	
	if (!$forceStop)
	{
		if (strcmp($cred, "")==0 || strcmp($password, "")==0) {
			$forceStop = true;
			$errorType = "Credential not inputted";
		}
	}
	if (!$forceStop)
	{
		$errorType = User::verifyCred($cred, $password);
		if (strcmp($errorType, 'Success') == 0) {

			$_SESSION['uid'] = User::findUid($cred);
			$updateResult = User::refreshLoginTime($_SESSION['uid']);
			Ip_block::reset();
			$errorType = $_SESSION['uid'];
			$_SESSION['need_session'] = true;
			$success = true;
			$ajaxRedirect = "?controller=game_menu&action=signin_success";
			
		}
		else {
			$forceStop = true;
			$errorType = "Credential invalid";
			User_log::insert(NULL, "Login", "Invalid login attempt, cred: ".$cred, 2, NULL);
			Ip_block::addCount();
		}
	}
	
	$ajaxReturn = array('errorType' => $errorType,
	'success' => $success,
    'redirect' => $ajaxRedirect);
	
	echo json_encode($ajaxReturn);
}
?>
<?php
require_once('../../connection.php');
require_once('../user.php');
require_once('../user_log.php');
session_start();

// The query page is split from display page for easy expansion and copying.

// By default no redirecting is needed
$ajaxRedirect = "no";

$errorType = "Branching";

$success = false;

// This boolean is used to skip remaining procedures if fatal error has occured.
$forceStop = false;

// "False" value is used for debug
if(!isset($_POST['email'])||!isset($_POST['username'])||!isset($_POST['password'])||!isset($_POST['nickname']))
{
	// If trepassing, force return to homepage
	$ajaxRedirect = "index.html";
    header("Location: ../index.php");
}
else
{	
		$joindate = date("Y-m-d");
		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$nickname = $_POST['nickname'];

	// Whenever a fatal error might happen, forceStop is checked to ensure quickest execution.	
	if (!$forceStop)
	{
		// PHP regex needs delimiters, identical sign is used to check output
		// First character must be letter, then any number of letter/number/underscore/hyphen/dot, then @ sign, then at least 1 letter/number/underscore/hyphen/dot, then dot, finally 2-3 letters.
		if (preg_match("/^[_\-a-zA-Z0-9\.!#$%&'*+-\/=?^_`{|}~]{1,}@[_\-a-zA-Z0-9\.]{1,}\.[a-zA-Z]{1,}$/", $email)===0)
		{
			$forceStop = true;
			$errorType = "Email invalid";
		}
		if (preg_match("/^[a-zA-Z]\w{5,19}$/", $username)===0)
		{
			$forceStop = true;
			$errorType = "Username invalid";
		}
		if (preg_match("/^\S{6,20}$/", $password)===0)
		{
			$forceStop = true;
			$errorType = "Password invalid";
		}
		/*
		if (strcmp($password, $password2)!=0)
		{
			$forceStop = true;
			$errorType = "Confirmed password invalid";
		}
		*/
		if (preg_match("/^[a-zA-Z][\w_\- ]{2,19}/", $nickname)===0)
		{
			$forceStop = true;
			$errorType = "Nickname invalid";
		}
	}
	if (!$forceStop)
	{
		$usernameDup = User::usernameExists($username);
		if ($usernameDup == true) {
			$forceStop = true;
			$errorType = "Username used";
		}
	}
	if (!$forceStop)
	{
		$emailDup = User::emailExists($email);
		if ($emailDup == true) {
			$forceStop = true;
			$errorType = "Email used";
		}
	}
	if (!$forceStop)
	{
		$errorType = User::insert($username, $email, $password, $nickname);
		if (strcmp($errorType, 'Success') == 0) {
			$success = true;
			$ajaxRedirect = "?action=register_success";
			$_SESSION['need_register'] = true;

			$uid = User::findUid($username);
			User_log::insert($uid, "Login", "User registered");
		}
	}
	// This variable is fed back as JSON to the page asking for response.
	// All echo statements and printf and print_r statements must be eventually converted into part of ajaxReturn, or be commented out to allow solid and streamline performance.
	// The variable is supposed to be evolved to a JSON file at the end of development.
	
	$ajaxReturn = array('errorType' => $errorType,
	'success' => $success,
    'redirect' => $ajaxRedirect);
	
	echo json_encode($ajaxReturn);
}
?>
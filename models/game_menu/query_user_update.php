<?php
require_once('../../connection.php');
require_once('../session.php');
require_once('../user.php');
require_once('../user_log.php');
require_once('../auth.php');
session_start();

$sessions = Session::getSessionsByUid($_SESSION['uid']);
$auth = new Auth($sessions);

if (strcmp($auth->getAuth(), 'player') != 0 && strcmp($auth->getAuth(), 'admin') != 0 ) {
	$new_location = $auth->manageUnauthorized(true);
}
else {
	// The query page is split from display page for easy expansion and copying.

	// By default no redirecting is needed
	$ajaxRedirect = "no";
	$success = false;
	$errorType = "Branching";

	// "False" value is used for debug
	if (!isset($_POST['username'])||!isset($_POST['email'])||!isset($_POST['nickname']))
	{
		$errorType = 'Parameters invalid';
	}
	else {
		$old = User::find($_POST['uid']);
		User::update($_POST['uid'], $_POST['username'], $_POST['email'], $_POST['nickname']);
		$success = true;
		$errorType = 'success';
		$ajaxRedirect = '?controller=game_menu&action=setting';
		User_log::insert($_SESSION["uid"], "Admin", "Updated User: ".$_POST['username'].",".$_POST['email'].",".$_POST['nickname'], 3, $old->username.",".$old->email.",".$old->nickname);
	}
	$ajaxReturn = array('uid' => $_POST['uid'],
						'username' => $_POST['username'],
						'email' => $_POST['email'],
						'nickname' => $_POST['nickname'],
						'errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
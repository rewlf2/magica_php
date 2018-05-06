<?php
require_once('../../connection.php');
require_once('../session.php');
require_once('../user.php');
require_once('../user_log.php');
require_once('../auth.php');
session_start();

$sessions = Session::getSessionsByUid($_SESSION['uid']);
$auth = new Auth($sessions);

if (strcmp($auth->getAuth(), 'admin') != 0) {
	$new_location = $auth->manageUnauthorized(true);
}
else {
	// The query page is split from display page for easy expansion and copying.

	// By default no redirecting is needed
	$ajaxRedirect = "no";
	$success = false;
	$errorType = "Branching";

	// "False" value is used for debug
	if (!isset($_POST['session_id'])||!isset($_POST['target_uid']))
	{
		$errorType = 'Parameters invalid';
	}
	else {
		$old = Session::getSession($_POST['session_id']);
		Session::destroySession($_POST['session_id']);
		$success = true;
		$errorType = 'success';
		$ajaxRedirect = '?controller=admin&action=session';
		User_log::insert($_SESSION["uid"], "Admin", "Destroyed session: ".$_POST['session_id']." owned by user ".$_POST["target_uid"], 4, $old->uid.",".$old->ip);
	}
	$ajaxReturn = array('uid' => $_SESSION['uid'],
						'session_id' => $_POST['session_id'],
						'target_uid' => $_POST['target_uid'],
						'errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
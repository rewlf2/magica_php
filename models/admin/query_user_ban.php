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
	if (!isset($_POST['uid'])||!isset($_POST['ban_time']))
	{
		$errorType = 'Parameters invalid';
	}
	else {
		$old = User::findBanTime($_POST['uid']);
		User::manageBan($_POST['uid'], $_POST['ban_time']);
		$success = true;
		$errorType = 'success';
		$ajaxRedirect = '?controller=admin&action=user';
		User_log::insert($_SESSION["uid"], "Admin", "Updated User ban time: ".$_POST['ban_time'], 4, "Old ban time: ".$old);
	}
	$ajaxReturn = array('uid' => $_POST['uid'],
						'ban_time' => $_POST['ban_time'],
						'errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
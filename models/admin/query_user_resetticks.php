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
	if (!isset($_POST['uid'])) {
		$errorType = 'Parameters invalid';
	}
	else {
		$old = User::findTicks($_POST['uid']);
		User::resetTicks($_POST['uid']);
		$success = true;
		$errorType = 'success';
		$ajaxRedirect = '?controller=admin&action=user';
		User_log::insert($_SESSION["uid"], "Admin", "Reset ticks for user", 3, "Old ticks: ".$old['ap_tick'].",".$old['hp_tick']);
	}
	$ajaxReturn = array('uid' => $_POST['uid'],
						'errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
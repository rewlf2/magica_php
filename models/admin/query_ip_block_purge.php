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
	require_once('../ip_block.php');
	// The query page is split from display page for easy expansion and copying.

	// By default no redirecting is needed
	$ajaxRedirect = "no";
	$success = false;
	$errorType = "Branching";

	// "False" value is used for debug
	Ip_block::deleteAllExpiredUnmarked();
	$success = true;
	$errorType = 'success';
	$ajaxRedirect = '?controller=admin&action=ip_block';
	User_log::insert($_SESSION["uid"], "Admin", "Purged all expired unmarked IP blocks", 4, NULL);

	$ajaxReturn = array('errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
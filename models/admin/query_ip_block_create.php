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
	if (!isset($_POST['ip'])||!isset($_POST['ban_time'])||!isset($_POST['remarks']))
	{
		$errorType = 'Parameters invalid';
	}
	else {
		Ip_block::insert($_POST['ip'], $_POST['ban_time'], $_POST['remarks']);
		$success = true;
		$errorType = 'success';
		$ajaxRedirect = '?controller=admin&action=ip_block';
		User_log::insert($_SESSION["uid"], "Admin", "Issued IP block: ".$_POST['ip'].",".$_POST['ban_time'].",".$_POST['remarks'], 3, NULL);
	}
	$ajaxReturn = array('ip' => $_POST['ip'],
						'ban_time' => $_POST['ban_time'],
						'remarks' => $_POST['remarks'],
						'errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
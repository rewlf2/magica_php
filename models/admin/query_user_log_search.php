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
	if (!isset($_POST['cred'])||!isset($_POST['type'])||!isset($_POST['ip'])||!isset($_POST['min_date'])||!isset($_POST['max_date'])||!isset($_POST['min_importance'])||!isset($_POST['limit'])||!isset($_POST['offset'])) {
		$errorType = 'Parameters invalid';
	}
	else {
		$success = true;
		$errorType = 'success';
		$ajaxRedirect = '?controller=admin&action=user_log';

		if (isset($_POST['cred']))
			$ajaxRedirect = '&cred='.$_POST['cred'];
		if (isset($_POST['type']))
			$ajaxRedirect = '&type='.$_POST['type'];
		if (isset($_POST['ip']))
			$ajaxRedirect = '&ip='.$_POST['ip'];
		if (isset($_POST['min_date']))
			$ajaxRedirect = '&min_date='.$_POST['min_date'];
		if (isset($_POST['max_date']))
			$ajaxRedirect = '&max_date='.$_POST['max_date'];
		if (isset($_POST['min_importance']))
			$ajaxRedirect = '&min_importance='.$_POST['min_importance'];
		if (isset($_POST['limit']))
			$ajaxRedirect = '&limit='.$_POST['limit'];
		if (isset($_POST['offset']))
			$ajaxRedirect = '&offset='.$_POST['offset'];
	}
    $ajaxReturn = array('errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
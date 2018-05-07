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
	$olds = Session::getSessionsByUid($_SESSION["uid"]);
	$old_string = "";
	foreach ($olds as $old) {
		$old_string .= $old->uid.",".$old->ip.";";
	}
	Session::isolateSessionByIp($_SESSION["uid"]);
	$success = true;
	$errorType = 'success';
	$ajaxRedirect = '?controller=game_menu&action=setting_session';
	User_log::insert($_SESSION["uid"], "Setting", "Destroyed all sessions from other locations", 4, $old_string);

	$ajaxReturn = array('uid' => $_SESSION['uid'],
						'errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
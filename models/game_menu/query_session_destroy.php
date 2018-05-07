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
	if (!isset($_POST['session_id']))
	{
		$errorType = 'Parameters invalid';
	}
	else {
		$old = Session::getSession($_POST['session_id']);
		//echo $old->uid;

		if($old->uid != $_SESSION["uid"]) {
			$errorType = 'Illegal access';
			User_log::insert($_SESSION["uid"], "Setting", "Attempted to destroyed session: ".$_POST['session_id']." owned by another user", 4, $old->uid.",".$old->ip);
		}
		else {
			Session::destroySession($_POST['session_id']);
			$success = true;
			$errorType = 'success';
			$ajaxRedirect = '?controller=game_menu&action=setting_session';
			User_log::insert($_SESSION["uid"], "Setting", "Destroyed own session: ".$_POST['session_id'], 3, $old->uid.",".$old->ip);
		}
	}
	$ajaxReturn = array('uid' => $_SESSION['uid'],
						'session_id' => $_POST['session_id'],
						'errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
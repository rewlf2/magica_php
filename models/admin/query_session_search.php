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
	if (!isset($_POST['cred'])) {
		$errorType = 'Parameters invalid';
	}
	else {
		$need_uid = true;

		if (strcmp($_POST['cred'], "")!=0)
			$uid = User::findUid($_POST['cred']);
		else
			$need_uid = false;

        $limit = isset($_POST['limit']) && strcmp($_POST['limit'], "")!=0 && strcmp($_POST['limit'], "undefined")!=0 ? $_POST['limit']: 10;
        $offset = isset($_POST['offset']) && strcmp($_POST['offset'], "")!=0 && strcmp($_POST['offset'], "undefined")!=0 ? $_POST['offset']: 0;

		$success = true;
		$errorType = 'success';

		$ajaxRedirect = '?controller=admin&action=session&limit='.$limit."&offset=".$offset;
		if ($need_uid)
			$ajaxRedirect .= '&uid='.$uid;
	}
    $ajaxReturn = array('cred' => $_POST['cred'],
                        'result' => $sessions,
						'errorType' => $errorType,
						'success' => $success,
						'redirect' => $ajaxRedirect,
					);
	echo json_encode($ajaxReturn);
}
?>
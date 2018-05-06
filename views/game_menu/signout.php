<?php
    if (isset($_SESSION['session_id'])) {
        $result = Session::destroySession($_SESSION['session_id']);
        User_log::insert($_SESSION["uid"], "Login", "User signed out", 1, NULL);
    }
    session_destroy();
    header("Location: ?");
?>
<?php
// echo User::getRole($_SESSION['uid']);
$role = User::getRole($_SESSION['uid']);
if (strcmp($role, 'admin') !=0) {
    header("Location: ?");
}
else {
}
?>
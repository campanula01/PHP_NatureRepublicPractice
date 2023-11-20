<?php
include '../inc/dbconfig.php';
include '../inc/member.php';
$member = new Member($db);
$member->logout();
?>

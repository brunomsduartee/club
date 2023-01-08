<?php
session_start();
session_destroy();


$_SESSION['status'] = "Logged out";
header("Location: login.php");
exit(0);

?>
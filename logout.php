
<?php
session_start();
$_SESSION = array();
session_destroy();e
header("Location: login.php");
exit();
?>

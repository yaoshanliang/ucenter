<?php
session_start();
session_destroy();
header('Location:http://localhost/ids/example/index.php');
?>

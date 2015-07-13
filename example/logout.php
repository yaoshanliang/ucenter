<?php
include_once('config.php');
session_start();
session_destroy();
header('Location:' . site_home);
?>

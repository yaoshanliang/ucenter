<?php
session_start();
if($_SESSION['username']) {
	echo $_SESSION['username'], ' login';
} else {
	header('Location:http://ids.com/auth/login?app=xxx');
}
?>

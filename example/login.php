<script src='jquery-2.1.1.js'></script>
<?php
session_start();
var_dump($_SESSION);
if(isset($_SESSION['username'])) {
	echo $_SESSION['username'];
	echo 'login';
	header('Location:http://localhost/ids/example/index.php');
} else {
	echo 'not login';
?>
	<script>
		document.cookie="token="+1;
		/*$.ajax({
			type: 'GET',
			url : 'http://localhost/ids/public/api/validate_token',
			async : false,
			success : function (data) {
				alert(data);
				document.cookie="token="+data;
			},
		});*/
	</script>
<?php
	$token = file_get_contents('http://localhost/ids/public/api/validate_token');
	if($token === $_GET['token']) {
		$_SESSION['username'] = 122;
	header('Location:http://localhost/ids/example/index.php');
	} else {
		echo 'token invalidate';
	}
	var_dump($_GET['token']);
}
?>

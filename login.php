<?php
	session_start();
	
	$con = mysqli_connect('localhost', 'root', 'my third eye!', 'yikkit');

	$username = mysqli_real_escape_string($con, $_POST['username']);
	$password = md5(md5('goonbafoon92' . mysqli_real_escape_string($con, $_POST['password']) . 'you live with racoons'));
	$response = Array();

	

	$result = $con->query("SELECT * FROM users WHERE name = '" . $username . "'");

	if ($result->num_rows){
		$user = $result->fetch_assoc();
		if($password === $user['password']){
			$_SESSION['username'] = $user['name'];
			$_SESSION['password'] = $user['password'];
			$_SESSION['userId'] = $user['id'];

			$response['success'] = 1;
			$response['id'] = $user['id'];
			$response['email'] = $user['email'];
			$response['bio'] = $user['bio'];

		} else {
			$response['success'] = 0;
			$response['msg'] = 'wrong password';
		}
	} else {
		$response['success'] = 0;
		$response['msg'] = 'user not found';
	}

	echo json_encode($response);
?>

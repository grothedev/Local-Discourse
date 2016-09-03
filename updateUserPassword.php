<?php

	session_start();

	$con = mysqli_connect("localhost", "root", "my third eye!", "yikkit");
	$userId = $_SESSION['userId'];
	$password1 = md5(md5('goonbafoon92' . mysqli_real_escape_string($con, $_POST['password1']) . 'you live with racoons'));
	$password2 = md5(md5('goonbafoon92' . mysqli_real_escape_string($con, $_POST['password2']) . 'you live with racoons'));

	$response = Array();

	if ($userId){
		if ($password1 === $password2){
			if (mysqli_query($con, "UPDATE users SET password = '$password' WHERE id = '$userId'")){
				$response['success'] = 1;
			} else {
				$response['success'] = 0;
				$response['msg'] = 'mysql query failed to update password';
			}
		} else {
			$response['success'] = 0;
			$response['msg'] = 'passwords do not match';
		}
	} else {
		$response['success'] = 0;
		$response['msg'] = 'user not logged in';
	}

	echo json_encode($response);

?>

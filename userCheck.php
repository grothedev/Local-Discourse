<?php

	session_start();

	$userId = $_SESSION['userId'];
	$response = Array();
	
	if ($userId){
		$response['id'] = $userId;
		
		$con = mysqli_connect("localhost", "root", "my third eye!", "yikkit");
		$result = $con->query("SELECT * FROM users WHERE id = '$userId'");
		
		if ($user = $result->fetch_array()){
			$response['name'] = $user['name'];
			$response['bio'] = $user['bio'];
			$response['email'] = $user['email'];
			$response['success'] = 1;
		} else {
			$response['success'] = 0;
			$response['msg'] = 'mysql query failed';
		}
	} else {
		$response['id'] = -1;
		$response['msg'] = 'no user logged in';
	}

	echo json_encode($response);

?>

<?php
	
	$con = mysqli_connect("localhost", "root", "my third eye!", "yikkit");

	$userId = mysqli_real_escape_string($con, $_GET['id']);
	$response = Array();

	if ($userId){
		$result = $con->query("SELECT * FROM users WHERE id = '$userId'");

		if ($user = $result->fetch_array()){
			$response['name'] = $user['name'];
			$response['bio'] = $user['bio'];
			$response['email'] = $user['email'];
			$response['success'] = 1;
		}
	} else {
		$response['success'] = 0;
		$response['msg'] = 'no user id provided';
	}

	echo json_encode($response);
	

?>

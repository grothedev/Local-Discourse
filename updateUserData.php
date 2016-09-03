<?php
	
	session_start();

	$con = mysqli_connect("localhost", "root", "my third eye!", "yikkit");
	$userId = $_SESSION['userId'];
	$lat = mysqli_real_escape_string($con, $_POST['lat']);
	$lon = mysqli_real_escape_string($con, $_POST['lon']);
	$bio = mysqli_real_escape_string($con, $_POST['bio']);
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$successfulSoFar = 1;

	$response = Array();

	if ($userId){
		if ($lat){
			if (!mysqli_query($con, "UPDATE users SET lat = '$lat' WHERE id = '$userId'")){
				$response['success'] = 0;
				$response['msg'] = 'was not able to update latitude';
				$successfulSoFar = 0;
			} 
		}
		if ($lon){
			if (!mysqli_query($con, "UPDATE users SET lon = '$lon' WHERE id = '$userId'")){
				$response['success'] = 0;
				$response['msg'] = 'was not able to update longitude';
				$successfulSoFar = 0;
			} 
		}
		if ($bio){
			if (!mysqli_query($con, "UPDATE users SET bio = '$bio' WHERE id = '$userId'")){
				$response['success'] = 0;
				$response['msg'] = 'was not able to update biography';
				$successfulSoFar = 0;
			} 
		}
		if ($email){
			if (!mysqli_query($con, "UPDATE users SET email = '$email' WHERE id = '$userId'")){
				$response['success'] = 0;
				$response['msg'] = 'was not able to update email';
				$successfulSoFar = 0;
			} 
		}
		
		if ($successfulSoFar){
			$response['success'] = 1;
		}

	} else {
		$response['success'] = 0;
		$response['msg'] = 'user not authenticated';
	}

	echo json_encode($response);
?>

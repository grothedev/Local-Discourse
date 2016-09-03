<?php

	session_start();

	$con = mysqli_connect('localhost', 'root', 'my third eye!', 'yikkit');

	$username = mysqli_real_escape_string($con, $_POST['username']);
	$password = md5(md5('goonbafoon92' . mysqli_real_escape_string($con, $_POST['password']) . 'you live with racoons'));
	$pass2 = md5(md5('goonbafoon92' . mysqli_real_escape_string($con, $_POST['pass2']) . 'you live with racoons'));
	$email = mysqli_real_escape_string($con, $_POST['email']);
	$bio = mysqli_real_escape_string($con, $_POST['bio']);
	$lat = mysqli_real_escape_string($con, $_POST['lat']);
	$lon = mysqli_real_escape_string($con, $_POST['lon']);

	$response = Array();


	if ($username){
		if ($password === $pass2){
			

			$result = mysqli_query($con, "SELECT * FROM users WHERE name = '$username'");

			if (mysqli_fetch_array($result)){
				$response['success'] = 0;
				$response['msg'] = 'user already exists';
			} else {
				if (mysqli_query($con, "INSERT INTO users(name, password, email, bio, lat, lon) VALUES('$username', '$password', '$email', '$bio', '$lat', '$lon')")){
					$response['success'] = 1;
					$response['userId'] = mysqli_insert_id($con);

					$_SESSION['username'] = $user['name'];
					$_SESSION['password'] = $user['password'];
					$_SESSION['userId'] = mysqli_insert_id($con);

				} else {
					$response['success'] = 0;
					$response['wasnt able to add user for some reason'];
				}
			}


			
			
			
		} else {
			$response['success'] = 0;
			$response['msg'] = 'no password';
		}
	} else {
		$response['success'] = 0;
		$response['msg'] = 'no username';
	}

	echo json_encode($response);
?>

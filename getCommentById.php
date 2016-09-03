<?php
	$con = mysqli_connect('localhost', 'root', 'my third eye!', 'yikkit');
	
	$id = mysqli_real_escape_string($con, $_GET['id']);
	$response = Array();

	$query = $con->query("SELECT * FROM comments WHERE id = '$id'");

	if ($result = $query->fetch_array()){
		$response = $result;
	} else {
		$response['success'] = 0;
		$response['msg'] = 'no comment found';
	}

	echo json_encode($response);
?>

<?php
	session_start();

	$con = mysqli_connect("localhost", "root", "my third eye!", "yikkit");

	$str_json = $_POST['str_json'];	
	$j = json_decode($str_json, true);
	if ($_SESSION['userId']){
		$userId = $_SESSION['userId'];
	} else {
		$userId = -1;
	}
	$text = mysqli_real_escape_string($con, $j['text']);
	$postId = mysqli_real_escape_string($con, $j['postId']);
	$parentId = mysqli_real_escape_string($con, $j['parentId']); //0 for OP
	$time = date('Y.m.d.H.i');
	$anon = mysqli_real_escape_string($con, $j['anon']);

	$response = Array();

	if (mysqli_query($con, "INSERT INTO comments(text, postId, parentId, userId, time) VALUES('$text', '$postId', '$parentId', '$userId', '$time')")){
		$response['success'] = 1;
		$commentId = mysqli_insert_id($con);
	} else {
		$response['success'] = 0;
	}
	
	
	



	//deal with urls later
	

	echo json_encode($response);

	
?>

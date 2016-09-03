<?php

	$con = mysqli_connect('localhost', 'root', 'my third eye!', 'yikkit');
	
	$postId = mysqli_real_escape_string($con, $_GET['postId']);
	$parentId = mysqli_real_escape_string($con, $_GET['parentId']);
	
	$response = Array();

	if ($postId){
		if ($parentId == null){
			$response['success'] = 0;
			$response['msg'] = 'no parent id';
		} else {
			

			$result = mysqli_query($con, "SELECT * FROM comments WHERE postId = '$postId' AND parentId = '$parentId'");

			while ($comment = mysqli_fetch_array($result)){
				array_push($response, $comment);
			}
		}
	} else {
		$response['success'] = 0;
		$response['msg'] = 'no post id';
	}

	echo json_encode($response);
?>

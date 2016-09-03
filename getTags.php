<?php

	$con = mysqli_connect('localhost', 'root', 'my third eye!', 'yikkit');

	$postId = mysqli_real_escape_string($con, $_GET['postId']);
	$response = Array();

	if ($postId){
		$result = $con->query("SELECT tagId FROM postTagIndex WHERE postId = '$postId'");
		$tags = Array();
		
		while ($tagId = $result->fetch_array()){
			$resultTag = $con->query("SELECT tag FROM tags WHERE id = '$tagId[0]'");
			if ($tag = $resultTag->fetch_array()){
				array_push($response, $tag[0]);
			}
		}



	} else {
		$response['succcess'] = 0;
		$response['msg'] = 'post id not set';
	}

	echo json_encode($response);

?>

<?php
	session_start();

	$con = mysqli_connect("localhost", "root", "my third eye!", "yikkit");

	$str_json = $_POST['str_json'];	
	$j = json_decode($str_json, true);
	
	$response = Array();

	$anon = mysqli_real_escape_string($con, $j['anon']);
	if ($anon){
		$userId = -1;
	} else {
		if ($_SESSION['userId']){
			$userId = $_SESSION['userId'];
		} else {
			//no user logged in currently
			$response['success'] = 0;
			$response['msg'] = 'no user currently logged in';

			echo json_encode($response);
			return;
		}
	}


	$title = mysqli_real_escape_string($con, $j['title']);
	$text = mysqli_real_escape_string($con, $j['text']);
	$tags = $j['tags'];
	$lat = mysqli_real_escape_string($con, $j['lat']);
	$lon = mysqli_real_escape_string($con, $j['lon']);
	$time = date('Y.m.d.H.i');
	$hasFile = 0;

	$files = $_FILES['files_in'];

	if ($files){
		$hasFile = 1;
		$filesToDB = Array();
		for ($i = 0; $i < sizeof($files['name']); $i++){

			$filename = $files['name'][$i];

			if ( strlen($filename) > 144){
				//the name of this file is too long. it will not be uploaded
				$response['msg'] .= 'the name of file ' . $filename . ' is too long';
				continue;
			} else {
				if ( $files['size'][$i] > 256000000 ){
					echo 'file too big';
					//the size of the file is too big, will not be uploaded
					$response['msg'] .= 'the size of file ' . $filename . ' is too big';
					continue;
				} else {
					$newFilename = microtime(true) . '-' . $files['name'][$i];
					if (move_uploaded_file($files['tmp_name'][$i], './files/' . $newFilename)){
						
						array_push($filesToDB, $newFilename);
					
					} else {
						$response['msg'] = 'was not able to save file';
					}
				}
			}
		}
	}
	

	for ($i = 0; $i < sizeof($tags); $i++){
		$tags[$i] = mysqli_real_escape_string($con, $tags[$i]);
	}


	$tagIds = Array();

	//deal with tags
	for ($i = 0; $i < sizeof($tags); $i++){

		if ($tags[$i] == ""){ //blank tag
			continue;
		}

		$result = mysqli_query($con, "SELECT id FROM tags WHERE tag = '$tags[$i]'") or die(mysqli_error($con));
		if ($id = mysqli_fetch_array($result)[0]){
			array_push($tagIds, $id);
		} else {
			mysqli_query($con, "INSERT INTO tags(tag) VALUES('$tags[$i]')") or die(mysqli_error($con));
			$result = mysqli_query($con, "SELECT id FROM tags WHERE tag = '" . $tags[$i] . "'");

			array_push($tagIds, mysqli_fetch_array($result)[0]);
		}

	}


	if ( mysqli_query($con, "INSERT INTO posts(lat, lon, text, title, userId, time, hasFile) VALUES('$lat', '$lon', '$text', '$title', '$userId', '$time', '$hasFile')") ){
		$response['success'] = 1;
	} else {
		$response['success'] = 0;
		$response['msg'] = 'something went wrong with mysql INSERT INTO';
	}
	
	
	$postId = mysqli_insert_id($con);

	for ($i = 0; $i < sizeof($tagIds); $i++){
		mysqli_query($con, "INSERT INTO postTagIndex(postId, tagId) VALUES('" . $postId . "', '" . $tagIds[$i] . "')");
	}
	
	for ($i = 0; $i < sizeof($filesToDB); $i++){
		//add file to file table
		$con->query("INSERT INTO files(name, time) VALUES('$filesToDB[$i]', '$time')");
		
		//add post assoc to index
		$fileId = mysqli_insert_id($con);
		$con->query("INSERT INTO postFileIndex(fileId, postId) VALUES($fileId, $postId)");
	}

	//deal with urls later
	
	echo json_encode($response);


?>

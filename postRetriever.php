<?php

	//getting all posts within r of lat, lon with tags
	
	$con = mysqli_connect("localhost", "root", "my third eye!", "yikkit");
	
	$RE = 3959; //radius of earth in miles
	
	$str_json = $_GET['str_json'];	
	$jsonObj = json_decode($str_json, true);
	$lat = mysqli_real_escape_string($con, $jsonObj['lat']);
	$lon = mysqli_real_escape_string($con, $jsonObj['lon']);
	$r = mysqli_real_escape_string($con, $jsonObj['r']);
	$tags = $jsonObj['tags'];
	$users = $jsonObj['users'];
	$keywords = $jsonObj['keywords'];
	
	for ($i = 0; $i < sizeof($tags); $i++){
		$tags[$i] = mysqli_real_escape_string($con, $tags[$i]);
	}
	for ($i = 0; $i < sizeof($users); $i++){
		$users[$i] = mysqli_real_escape_string($con, $users[$i]);
	}
	for ($i = 0; $i < sizeof($keywords); $i++){
		$keywords[$i] = mysqli_real_escape_string($con, $keywords[$i]);
	}
	
	if (mysqli_connect_errno()){
			//echo mysqli_connect_error();
	} else {
		
		
		
		
		
		$desiredPostIds = Array();
		$desiredPosts = Array();
		$tagIds = Array();
		
		//getting posts with relevant tags from DB
		
			
		for ($i = 0; $i < sizeof($tags); $i++){
			
			$result = mysqli_query($con, "SELECT id FROM tags WHERE tag = '" . $tags[$i] . "'");

			if ($result){
				array_push($tagIds, mysqli_fetch_array($result)['id']);
				
				
			}
			
		}
		
		
		if ($tags[0] == '*'){ //will use all tags in query
			$result = mysqli_query($con, "SELECT id FROM tags");
			
			while($row = mysqli_fetch_array($result)){
				array_push($tagIds, $row['id']);
			}
		}
		
		for ($i = 0; $i < sizeof($tagIds); $i++){
	
			$result = mysqli_query($con, "SELECT postId FROM postTagIndex WHERE tagId = '" . $tagIds[$i] . "'");
			
			
			
			while ($postIdsTemp = mysqli_fetch_array($result)){
				array_push($desiredPostIds, $postIdsTemp['postId']);
				
			}		
				
		}
	
		
		
		
		for ($i = 0; $i < sizeof($users); $i++){
			$result = mysqli_query($con, "SELECT id FROM users WHERE name = '" . $users[$i] . "'");
			$userId = mysqli_fetch_array($result)[0];
			
			$result = mysqli_query($con, "SELECT id FROM posts WHERE userId = '" . $userId . "'");
			
			
			
			while ($postIdsTemp = mysqli_fetch_array($result)){
				array_push($desiredPostIds, $postIdsTemp['id']);
			}	
		}
		
		
					
		
			
		for ($i = 0; $i < sizeof($keywords); $i++){
			//figure out a good algorithm for this
		}
			
		$desiredPostIds = array_values(array_unique($desiredPostIds));

		for ($i = 0; $i < sizeof($desiredPostIds); $i++){
				
				
				$result = mysqli_query($con, "SELECT * FROM posts WHERE id = '" . $desiredPostIds[$i] . "'");
				
				while($postTemp = mysqli_fetch_array($result)){
					array_push($desiredPosts, $postTemp);
				}
		}

		
		
		//eliminating out of range posts
		
		if ($r > 0){
			$n = sizeof($desiredPosts);
			
			for ($i = 0; $i < $n; $i++){
				$p = $desiredPosts[$i];
				$pLat = $p['lat'];
				$pLon = $p['lon'];
				
				$aLat = $r/$RE * 180/pi(); //miles to angles
				$newEarthRad = $RE * cos($pLat * pi() / 180);
				$aLon = ($r / $newEarthRad) * 180/pi();
				
				if ($pLat > $lat && $pLon > $lon){
					if ($pLat > $lat + $aLat || $pLon > $lon + $aLon){
						unset($desiredPosts[$i]);
					}
				} else if ($pLat < $lat && $pLon > $lon){
					if ($pLat < $lat - $aLat || $pLon > $lon + $aLon){
						unset($desiredPosts[$i]);
					}
				} else if ($pLat < $lat && $pLon < $lon){
					if ($pLat < $lat - $aLat || $pLon < $lon -$aLon){
						unset($desiredPosts[$i]);
					}
				} else if ($pLat > $lat && $pLon < $lon){
					if ($pLat > $lat + $aLat || $pLon < $lon - $aLon){
						unset($desiredPosts[$i]);

					}
				}
				
			}
		}
		
		//getting tags
		for ($i = 0; $i < sizeof($desiredPosts); $i++){
			$postId = $desiredPosts[$i]['id'];
			$result = $con->query("SELECT tagId FROM postTagIndex WHERE postId = '$postId'");
			$postTags = Array();
			$desiredPosts[$i]['tags'] = Array();

			while ($tagId = $result->fetch_array()){
				
				$resultTag = $con->query("SELECT tag FROM tags WHERE id = '$tagId[0]'");
				
				if ($tag = $resultTag->fetch_array()){
					array_push($desiredPosts[$i]['tags'], $tag[0]);
				}
			}
		}

		//getting usernames
		for ($i = 0; $i < sizeof($desiredPosts); $i++){
			$userId = $desiredPosts[$i]['userId'];
			
			
			if ($userId == -1){
				$desiredPosts[$i]['userName'] = 'anonymous';
			} else {
				$result = $con->query("SELECT name FROM users WHERE id = '$userId'");
				$userName = $result->fetch_array()[0];
				$desiredPosts[$i]['userName'] = $userName;
			}
			
			
		}

		//getting files
		for ($i = 0; $i < sizeof($desiredPosts); $i++){
			if ($desiredPosts[$i]['hasFile']){
				$fileIds = Array();
				$pId = $desiredPosts[$i]['id'];
				$desiredPosts[$i]['files'] = Array();
				
				$r = $con->query("SELECT fileId FROM postFileIndex WHERE postId = '$pId'");
				while ($row = $r->fetch_assoc()){
					array_push($fileIds, $row['fileId']);
				}
				
				for ($j = 0; $j < sizeof($fileIds); $j++){
					$r2 = $con->query("SELECT name FROM files WHERE id = '$fileIds[$j]'");
					while ($row = $r2->fetch_assoc()){
						array_push($desiredPosts[$i]['files'], $row['name']);
					}
					
				}
			}
		}

		$desiredPosts = array_values($desiredPosts);

		echo json_encode($desiredPosts);
	}
	

?>

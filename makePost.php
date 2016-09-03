<!DOCTYPE html>
<html>
	<head>
		<link href = "css/bootstrap.css" rel = "stylesheet">
		<link href = "css/style.css" rel = "stylesheet">
		<script type = "text/javascript" src = "js/main.js" ></script>
		<script type = "text/javascript" src = "js/inputMakePost.js" async></script>
		<script type = "text/javascript" src = "js/viewNavbar.js" async></script>
		<script type = "text/javascript" src = "js/inputNavbar.js" async></script>
		<script src = "js/jquery.js" ></script>
	</head>
						
	<?php
		include('navbar.html');
	?>

	<body onload = "init()"></body>


	
	<div class = "container">
		<div class = "row">
			<div class = "col-md-6" id = "makePostBox">
				<center><h3>Make a Post</h3></center>
				<form onsubmit = "return false">
				
					<ul style = "list-style-type:none">
						<li><textarea  rows = "3" cols = "50" id = "text" onchange = "updatePostStatusText()" ></textarea></li>
						<li>Add a title <input type = "text" size = "40" id = "title" onchange = "updatePostStatusTitle()" /></li>
						<li>Add some tags <input type = "text" size = "40" id = "tags_post" onchange = "updatePostStatusTags()" />(show popular tags)</li>
						<li>Add files<input type = "file" id = "files_in" multiple = "multiple" onchange = "updatePostStatusFiles()" /></li>
						<li id = "anonCheckBox">Posting as anonymous? <input type = "checkbox" id = "anon" onchange = "updatePostStatusAnon()" /></li>
						<li><button type = "submit" id = "postSubmitButton" onclick = "inputMakePost()" disabled >Submit Post</button></li>
					</ul>
			
					
				</form>
			</div>
			<div class = "col-md-6" id = "makePostStatus">
				
				<ul style = "list-style-type: none">
					<li id = "postStatusAnon"></li>
					<li id = "postStatusTitle"></li>
					<li id = "postStatusText"></li>
					<li id = "postStatusTags"></li>
					<li id = "postStatusFiles"></li>
				</ul>
			</div>
		</div>
	</div>
	
	<script type = "text/javascript" src = "js/viewMakePost.js" async></script>
	
</html>

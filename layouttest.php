<!DOCTYPE html>
<html>
	
	<?php
		include('navbar.html');
	?>

	<link href = "css/bootstrap.css" rel = "stylesheet">
	<link href = "css/style.css" rel = "stylesheet">

	<script type = "text/javascript">
		
		function showComments(id){
			var i;
			for (i = 0; i < 100000000; i++){
				document.getElementById(id + ".c" + i).style.visibility = "visible";
				alert(id + ".c" + i);
			}
			
		}
	</script>

	<body>
		<div class = "container">
			<div class = "row">
				<div class = "col-md-5" id = "feed">
					<div class = "post" id = "p23">
						
						<div class = "postTitle">
							Discussion on Free Will
						</div>

						<div class = "stamp">
							Ross 2016/8/30
						</div>

						<div class = "postText">
							What is free will? Do we have it? This post is associated with a Wake Up discussion.
						</div>
	
						<div class = "postFiles">
							<font>Attached Files: <a href = "freewill.pdf">freewill.pdf</a></font>
						</div>
						<div class = "postTags">
							<div class = "tag">WakeUpISU -</div>
							<div class = "tag">philosophy +</div>
						</div>
						<br>
						<div class = "commentToggleButton">
							<button onclick = "toggleViewComments(p23, 0)">Show Comments</button>
						</div>

						<div class = "commentBox">

							<form onsubmit = "return false">
								Make a reply: <br><textarea height = "50%" id = "cIn-p23"></textarea>
								<br><button type = "submit" onclick = "makeComment(23, 0);">Reply</button>
							</form>

						</div>
						<center>________ _____ ___ __ _ _._ _ __ ___ _____ ________</center>
						<div class = "comments">
						
							<div class = "comment" id = "p23c1">
								<div class = "stamp">Thomas 2016/8/30</div>
								<div class = "commentText">When considering free will, what is it that is doing the choosing?</div>
								
								<div class = "commentBox">
									<form onsubmit = "return false">
										Make a reply: <br><textarea height = "50%" id = "cIn-c1"></textarea>
										<br><button type = "submit" onclick = "makeComment(23, 1);">Reply</button>
									</form>
								</div>
							
								<center> _____ ___ __ _ _ _ _ __ ___ _____ </center>

								<div class = "comments"></div>
						
							</div>			

						</div>

					</div>
				</div>
			</div>
		</div>

	</body>
</html>

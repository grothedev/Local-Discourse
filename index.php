<!DOCTYPE html>
<html>
	
	<link href = "css/bootstrap.css" rel = "stylesheet">
	<link href = "css/style.css" rel = "stylesheet">

	<?php
		include('navbar.html');
	?>
	
	<script src = "js/jquery.js" ></script>
	<script type = "text/javascript" src = "js/main.js" async></script>
	<script type = "text/javascript" src = "js/inputIndex.js" async></script>
	<script type = "text/javascript" src = "js/viewIndex.js" async></script>
	<script type = "text/javascript" src = "js/viewNavbar.js" async></script>
	<script type = "text/javascript" src = "js/inputNavbar.js" async></script>
	<script src = "js/bootstrap.min.js"></script>
	
	<body onload = 'init(<?php echo $_GET["str_json"]; ?>)'></body>
	
	<div class = "container">
		<div class = "row">
			<div class = "col-md-3">
				<?php include('queryPanel.html'); ?>
			</div>


			<div class = "col-md-9">
				
				<div id = "feed">
					<?php include('postTemplate.html'); ?>
				</div>
			</div>

		</div>
		

	
	</div>
	
	
</html>

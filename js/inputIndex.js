//this file is for functions which are called on user interaction with the UI


//Search
function inputSearchQuery(){ //when the user clicks the search button
	//updating the model of the query
	query.r = document.getElementById("r").value;
	query.tags = document.getElementById("tags_get").value.split(" ");
	query.users = document.getElementById("users").value.split(" ");
	
	requestPosts();
}

function inputMakeComment(pId, parentId){ //uploads a comment to the server (post id, parent id which is 0 if the parent is the post itself)
	var inputBox;
	
	if (parentId == 0){
		inputBox = document.getElementById("cIn-p" + pId);
		var text = inputBox.value;
	} else {
		inputBox = document.getElementById("cIn-c" + parentId);
		var text = inputBox.value;
	}
	
	var postId = pId;
	var anon = user.anon;

	var jsonObj = {"text": text, "lat": user.lat, "lon": user.lon, "postId": postId, "parentId": parentId, "anon": anon};
	var jsonStr = JSON.stringify(jsonObj);

	xhr = new XMLHttpRequest();
	var url = "commentAdder.php";
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4 && xhr.status == 200){
			inputBox.value = "";
			alert("thank you for your comment");
		}
	}
	xhr.send("str_json=" + jsonStr);
}

function inputIndexRadiusChanged(){
	if (R_UNIT == 0){
		query.r = document.getElementById("r").value;
	} else {
		//TODO unit conversion
	}
	viewIndexRadiusChanged();
}

function setRMi(){
	
}

function setRKm(){
	
}

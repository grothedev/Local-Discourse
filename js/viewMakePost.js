var makePostBoxHTML;
var postTitle = document.getElementById("title").value;
var postText = document.getElementById("text").value;
var anon = document.getElementById("anon").checked;
var postTags = document.getElementById("tags_post").value.split(" ");
var postFiles = document.getElementById("files_in").files;

var postStatusAnon = document.getElementById("postStatusAnon");
var postStatusTitle = document.getElementById("postStatusTitle");
var postStatusText = document.getElementById("postStatusText");
var postStatusTags = document.getElementById("postStatusTags");
var postStatusFiles = document.getElementById("postStatusFiles");
var postSubmitButton = document.getElementById("postSubmitButton");

var textOk, titleOk, tagsOk, filesOk;

function initView(){
	initNavbarView();
}

function viewPostComplete(success){ //this function is called after a response is recieved from postAdder.php. success = response['success']
	
	
	
	if (success){
		document.getElementById("makePostBox").innerHTML = "<center><h4>Thank you for your post.</h4></center>"; //TODO update this 
	} else {
		document.getElementById("makePostBox").innerHTML = "<center><h4>post was not successful</h4></center>";
	}
}

function updatePostStatusText(){

	postText = document.getElementById("text").value;
	if (postText.length == 0){
		postStatusText.innerHTML = "You must post some words.";
		textOk = false;
		updatePostButton();
	} else if (postText.length > 4096){
		postStatusText.innerHTML = "Post must be less than 4096 characters";
		textOk = false;
		updatePostButton();
	} else {
		postStatusText.innerHTML = "";
		textOk = true;
		updatePostButton();
	}
}

function updatePostStatusTitle(){
	postTitle = document.getElementById("title").value;
	if (postTitle.length == 0){
		postStatusTitle.innerHTML = "Post must have a title";
		titleOk = false;
		updatePostButton();
	} else if (postTitle.length > 64){
		postStatusTitle.innerHTML = "Title must be less than 64 characters";
		titleOk = false;
		updatePostButton();
	} else {
		titleOk = true;
		postStatusTitle.innerHTML = "";
		updatePostButton();
	}
}

function updatePostStatusTags(){ //TODO suggested tags
	var postTags = document.getElementById("tags_post").value.split(" ");
	
	if (postTags[0] == ""){
		postStatusTags.innerHTML = "Post must have at least one tag";
		tagsOk = false;
		updatePostButton();
	} else {
		postStatusTitle.innerHTML = "";
		tagsOk = true;
		updatePostButton();
	}
}

function updatePostStatusAnon(){
	var anon = document.getElementById("anon").checked;

	if (anon){
		postStatusAnon.innerHTML = "You are posting anonymously";
	} else {
		if (user.id){
			postStatusAnon.innerHTML = "You are posting as " + user.name;
		} else {
			postStatusAnon.innerHTML = "You are posting anonymously"; 
			document.getElementById("anon").checked = true;
		}
		
	}
	
}

function updatePostStatusFiles(){
	postFiles = document.getElementById("files_in").files;
	
	postStatusFiles.innerHTML = "";
	
	var i;
	for (i = 0; i < files.length; i++){
		if (files[i].size > 256000000) {
			postStatusFiles.innerHTML += files[i].name + " is too big. it will not be uploaded <br>";
		} else if (files[i].name.length > 144) {
			postStatusFiles.innerHTML += files[i].name + " has too long of a name. it will not be uploaded <br>";
		} else {
			
		}
	}
	
}

function updatePostButton(){
	if (textOk && titleOk && tagsOk){
		postSubmitButton.disabled = false;
	} else {
		postSubmitButton.disabled = true;
	}
}

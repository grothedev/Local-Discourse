var posts;
var COMMENT_UPDATE_TIME = 15000; //time in milliseconds until a set of comments is no longer considered fresh
var query = {}; //the user's search query (radius, tags, users)
var locationNotFound; //boolean. 
var masterCommentList = []; //array of all comments. all of them are on the same level
var user = {};

function init(json){

		
	getUserLocation();
	checkUserAuth();
	
	initView();

	if (json){
		query.tags = json.tags;
		query.r = json.r;
		query.users = json.users
		requestPosts();
	}

}

function checkUserAuth(){

	xhr = new XMLHttpRequest();
	var url = "userCheck.php"
	xhr.open("GET", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4 && xhr.status == 200){
			json = JSON.parse(xhr.responseText);
			
			if (json.id == -1){
				user.anon = true;
			} else {
				user.id = json.id;
				user.name = json.name;
				user.bio = json.bio;
				user.email = json.email;
				user.anon = false;
				
				viewSetUserLoggedIn();
			}
		}
	}
	xhr.send();
}

function getUserLocation(){
	navigator.geolocation.getCurrentPosition(foundLocation, noLocation);
}
function foundLocation(position){
	user.lat = position.coords.latitude;
	user.lon = position.coords.longitude;
}
function noLocation(){
	location = null;
	locationNotFound = true;
}

function requestPosts(){ //gets posts from server and populates the post array
		
				var jsonObj = {"lat": user.lat, "lon": user.lon, "r": query.r, "tags": query.tags, "users": query.users};
				
				var str_json = JSON.stringify(jsonObj);
				
				
				xhr = new XMLHttpRequest();
				var url = "postRetriever.php?str_json=" + str_json;
				xhr.open("GET", url, true);
				xhr.setRequestHeader("Content-type", "application/json");
				xhr.onreadystatechange = function(){
					if (xhr.readyState == 4 && xhr.status == 200){
						posts = JSON.parse(xhr.responseText);

						var i;
						
						//viewClearFeed();
						
						for (i = 0; i < posts.length; i++){
							var p = posts[i];
							p.comments = [];
							p.commentsHidden = true;
							p.lastCommentUpdateTime = null; //last time the comments for this post were requested
							viewAddPostToFeed(p);
						}
						
						
					}
				}
				xhr.send();				
}


function setUser(id){
	xhr = new XMLHttpRequest();
	var url = "getUserById.php?id=" + id;
	xhr.open("GET", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4 && xhr.status == 200){
			json = JSON.parse(xhr.responseText);
			user.name = json.name;
			user.bio = json.bio;
			user.email = json.email;
		}
	}
	xhr.send();
}

function showPostComments(pId){

	var p = postOfId(pId);
	if (p.comments == null){
		//get comments from server
	}
	var i;
	for(i = 0; i < p.comments.length; i++){
		p.comments[i].hidden = false;
	}
	//TODO analyze this process further
}


function toggleCommentsView(parentId, objectId){ //parentId is -1 for post (objectId is postId), 0 for comment of post, or parentId for comment of comment (objectId is commentId); 
	var o; //to represent either a post or a comment

	if (parentId == -1){ //we are trying to toggle the view of the comments of a post
		o = postOfId(objectId);
		$("#p" + objectId + " .postExtra").toggle(120);
		$("#p" + objectId + " .postExtra > .comments").toggle(120);
	} else { //it is a comment
		o = commentOfId(objectId);
		$("#c" + objectId + " > .comments").toggle(120);
	}
	
	if (o.commentsHidden){

		curTime = (new Date()).getTime();
		if (o.lastCommentUpdateTime == null || curTime - o.lastCommentUpdateTime > COMMENT_UPDATE_TIME ){ //comments are not fresh (haven't been updated in a while)
			getComments(parentId, objectId);
			//added to feed in getComments()
			
			o.commentsHidden = false;
			//document.getElementById("commentToggle-p" + parentId + "o" + objectId).innerHTML = "- Comments";
			
		} else { //comments are fresh
			var i;
			for (i = 0; i < o.comments.length; i++){
				viewAddCommentToFeed(o.comments[i]);
			}
			o.commentsHidden = false;
			//document.getElementById("commentToggle-p" + parentId + "o" + objectId).innerHTML = "- Comments";
		}
		
	} else {
		o.commentsHidden = true;
		//document.getElementById("commentToggle-p" + parentId + "o" + objectId).innerHTML = "+ Comments";
		viewHideComments(parentId, objectId);
	}
}

function getComments(parentId, objectId){ //gets comments from php script and puts them in json object
	xhr = new XMLHttpRequest();
	var url, o;
	
	
	if (parentId != -1){
		o = commentOfId(objectId);
		
		url = 	"commentRetriever.php?parentId=" + objectId + "&postId=" + o.postId;
	} else {
		o = postOfId(objectId);
		url = 	"commentRetriever.php?parentId=0&postId=" + objectId;
	}
	xhr.open("GET", url, true);
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4 && xhr.status == 200){
			
			responseComments = JSON.parse(xhr.responseText);
			
		
			var i;

			if (o.comments.length == responseComments.length){ //no new comments
				for (i = 0; i < o.comments.length; i++){
					viewAddCommentToFeed(o.comments[i]);
				}
				return;
			}

			
			o.comments = responseComments;
			for (i = 0; i < o.comments.length; i++){
				o.comments[i].commentsHidden = true;
				o.comments[i].comments = [];
				viewAddCommentToFeed(o.comments[i]);
				masterCommentList.push(o.comments[i]);
			}
			o.lastCommentUpdateTime = (new Date()).getTime();

			
		}
	}
	xhr.send();
}


function addTagToQuery(tag){
	query.tags.push(tag);
	requestPosts();	
}

function removeTagFromQuery(tag){
	index = query.tags.indexOf(tag);
	if (index > -1){
		query.tags.splice(index, 1);
	}
	requestPosts(); //TODO there is obviously no need to query the server again, just set the posts as hidden
}

function postOfId(id){
	var i;
	for (i = 0; i < posts.length; i++){
		if (posts[i].id == id){
			return posts[i];
		}
	}
	return null;
}

function commentOfId(cId){ 
    var i;
	for (i = 0; i < masterCommentList.length; i++){
		if (masterCommentList[i].id == cId){
			tempComment = masterCommentList[i];

			var j;
			if (tempComment.parentId == 0){
				p = postOfId(tempComment.postId);
				
				for (j = 0; j < p.comments.length; j++){
					if (p.comments[j].id == cId){
						return p.comments[j];
					}
				}
			} else {
				parentComment = commentOfId(tempComment.parentId);
				for (j = 0; j < parentComment.comments.length; j++){
					if (parentComment.comments[j].id == cId){
						return parentComment.comments[j];
					}
				}
			}

		}
	}
	return null;
}

function fileInputChanged(){
	var files = document.getElementById("files_in").files;

	var i;
	for (i = 0; i < files.length; i++){
		if (files[i].size > 256000000) {
			alert(files[i].name + " is too big. it will not be uploaded");
		} else if (files[i].name.length > 144) {
			alert(files[i].name + " has too long of a name. it will not be uploaded");
		} else {
			
		}
	}
}

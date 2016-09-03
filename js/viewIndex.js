//this file is for functions which modify the view.
//the functions will be given data from the model or from user input


var postTemplateHTML; //template of a post 
var commentsTemplateHTML; //the comments div which contains all the comments for a post or comment
var commentTemplateHTML; //the comment div which is a comment 

var shownRadius;

function initView(){ 
	commentsTemplateHTML = document.getElementsByClassName("comments")[0].innerHTML;
	commentTemplateHTML = document.getElementById("cId").outerHTML; //i don't remember why this is outer html
	//document.getElementsByClassName("comments")[0].innerHTML = "";

	postTemplateHTML = document.getElementById("feed").innerHTML;
	//document.getElementById("feed").innerHTML = "";

	initNavbarView();
	
	shownRadius = document.getElementById("shownRadius");
	

	$('.postTitle').click(function(){
		$('.postExtra').toggle(200);
	})

	
}



function viewAddPostToFeed(p){ //add post p to the feed
	var feed = document.getElementById("feed");
	feed.style.visibility = "hidden"; 
	
	
	feed.innerHTML += postTemplateHTML;
	document.getElementById("pId").id = "p" + p.id;
	
	var post = document.getElementById("p" + p.id);
	post.getElementsByClassName("postTitle")[0].innerHTML = p.title;
	post.style.display = "inline"; 
	
	if (p.userId){
		post.getElementsByClassName("stamp")[0].innerHTML = p.time + " -- " + p.userName + " -- " + p.lat + ", " + p.lon;
	} else {
		post.getElementsByClassName("stamp")[0].innerHTML = p.time +  " -- " + p.lat + ", " + p.lon;
	}
	post.getElementsByClassName("postText")[0].innerHTML = p.text;
	
	if (p.hasFile == 1){
		var i;
		for (i = 0; i < p.files.length; i++){
			filename = p.files[i];
			displayname = filename.slice(16); //cutting off the timestamp at the beginning
			post.getElementsByClassName("postFiles")[0].innerHTML += "<a href = 'files/" + filename + "' >" + displayname + "</a><br>";
			post.getElementsByClassName("postFiles")[0].setAttribute("display", "inline");
		}
	}
	
	var i;
	for (i = 0; i < p.tags.length; i++){ 
		if (query.tags.indexOf(p.tags[i]) == -1){ //currently not in user's search query
			post.getElementsByClassName("postTags")[0].innerHTML += "<div class = 'tag' onclick = 'addTagToQuery(\"" + p.tags[i] + "\");'>" + p.tags[i] + " +</div>";
		} else {
			post.getElementsByClassName("postTags")[0].innerHTML += "<div class = 'tag' onclick = 'removeTagFromQuery(\"" + p.tags[i] + "\");'>" + p.tags[i] + " -</div>";
		}
		 
	}
	

	document.getElementById("cIn-pId").id = "cIn-p" + p.id;
	document.getElementById("button-cIn-pId").id = "button-cIn-p" + p.id;
	document.getElementById("button-cIn-p" + p.id).setAttribute("onclick", "inputMakeComment(" + p.id + ", 0)");
	
	post.getElementsByClassName("postClickBox")[0].setAttribute("onclick", "toggleCommentsView( -1, " + p.id + ")");
	
	
	$('#' + p.Id + ' .postExtra .postOperations #reply').click(function(){
		viewToggleCommentBox(-1, pId);
	});
	document.getElementById("commentToggle-pIdoId").id = "commentToggle-p0o" + p.id;
	document.getElementById("commentToggle-p0o" + p.id).setAttribute("onclick", "toggleCommentsView(-1, " + p.id + ")");

	
	feed.style.visibility = "visible";
}

function viewAddCommentToFeed(c){
	if (c.parentId != 0){ //TODO fix this shit. it might be fixed now. 
		document.getElementById("c" + c.parentId).getElementsByClassName("comments")[0].innerHTML += commentTemplateHTML;
	} else {
		document.getElementById("p" + c.postId).getElementsByClassName("comments")[0].innerHTML += commentTemplateHTML;
	}
	

	var commentForm = "<form onsubmit = 'return false'> " 
						+	"<input type = 'text' id = 'cIn-p" + c.parentId + "'>"
						+ 	"<button type = 'submit' onclick = 'makeComment(" + c.parentId + ", " + c.postId + ")'>Post Comment</button>"
					+	"</form>";

	document.getElementById("cId").id = "c" + c.id;
	comment = document.getElementById("c" + c.id);
	comment.style.display = "inline";
	comment.getElementsByClassName("commentText")[0].innerHTML = c.text;
	comment.getElementsByClassName("stamp")[0].innerHTML = c.time + " - " + c.userId;
	document.getElementById("cIn-cId").id = "cIn-c" + c.id;

	document.getElementById("button-cIn-cId").id = "button-cIn-c" + c.id;
	document.getElementById("button-cIn-c" + c.id).setAttribute("onclick", "inputMakeComment(" + c.postId + ", " + c.id + ")");

	document.getElementById("commentToggle-pIdoId").id = "commentToggle-p" + c.parentId + "o" + c.id;
	document.getElementById("commentToggle-p" + c.parentId + "o" + c.id).setAttribute("onclick", "toggleCommentsView(" + c.parentId + ", " + c.id + ")");
}

function viewClearFeed(){
	document.getElementById("feed").innerHTML = null;
}

//pretty sure these functions aren't set up properly right now
function viewShowComments(parentId, objectId){
	if (parentId){
		document.getElementById("c" + objectId).getElementsByClassName("comments")[0].style.visibility = "visible";
	} else {
		document.getElementById("p" + objectId).getElementsByClassName("comments")[0].style.visibility = "visible";
	}
}
function viewHideComments(parentId, objectId){
	if (parentId != -1){
		document.getElementById("c" + objectId).getElementsByClassName("comments")[0].innerHTML = "";
	} else {
		document.getElementById("p" + objectId).getElementsByClassName("comments")[0].innerHTML = "";
	}
}

function viewIndexRadiusChanged(){
	shownRadius.innerHTML = query.r;
}

function viewToggleCommentBox(parentId, objectId){
	var o;
	
	if (parentId == -1){ //we are trying to toggle the reply box for a post
		o = postOfId(objectId);
		
		$('#p' + o.id + '.commentBox').toggle();
		
		alert("t");
		
	} else { //it is a comment
		o = commentOfId(objectId);
	}
	
}

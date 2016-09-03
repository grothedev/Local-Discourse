var userLoginBox;

function initNavbarView(){
	userLoginBoxHTML = document.getElementById("userAccessBox").innerHTML;
	
	
	
	
	if (user.id){
		userLoginBox.innerHTML = "login()";
	} else {

	}
}

function viewSetUserLoggedIn(){
	document.getElementById("userAccessBox").innerHTML = "<h3>" + user.name + "</h3> <button type = 'submit' onclick = 'logout()'>Log Out</button>";
}

function viewSetUserLoggedOut(){
	document.getElementById("userAccessBox").innerHTML = userLoginBoxHTML;
}

function viewMakeBioBox(){ //writes the html for bio input box
	document.getElementById("userAccessBox").innerHTML = "<textarea cols = '50' rows = '3' id = 'bioIn'></textarea>"
															+ "<br><button type = 'submit' onclick = 'submitBio()'>Submit Bio</button>";
}

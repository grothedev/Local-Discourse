function login(){
	user.name = document.getElementById("username").value;
	var password = document.getElementById("pass1").value;
	
	xhr = new XMLHttpRequest();
	var url = "login.php";
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4 && xhr.status == 200){
			json = JSON.parse(xhr.responseText);
			
			if (json.success == 1){
				user.id = json.id;
				user.email = json.email;
				user.bio = json.bio;
				user.anon = false;
				
				viewSetUserLoggedIn();
				
			} else {
				alert(json.msg);
			}
		}
	}
	xhr.send("username=" + user.name + "&password=" + password);
}

function logout(){
	xhr = new XMLHttpRequest();
	var url = "logout.php";
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4 && xhr.status == 200){
			viewSetUserLoggedOut();
		}
	}
	xhr.send();
}

function userRegister(pass1){
	if (!pass1){
		username = document.getElementById("username").value;
		password = document.getElementById("pass1").value;
	
		document.getElementById("userAccessTitle").innerHTML = "Register";
	
	
		if (password == ""){
			alert("Enter a password");
			return;
		}
		if (username == ""){
			alert("Enter a username");
			return;
		}

		$("#userAccessBox li:eq(0)").html("Verify Password <input style = 'padding: 4px;' type = 'password' id = 'pass2' />");
		$("#userAccessBox li:eq(1)").html("Email (optional) <input style = 'padding: 4px;' type = 'text' id = 'email' /><button onclick = \"userRegister('" + password + "')\">Register</button>");
		
	} else {
		
		if (document.getElementById("pass2").value === pass1){
			email = document.getElementById("email").value;
			var pass2 = document.getElementById("pass2").value;
			
			xhr = new XMLHttpRequest();
			var url = "register_user.php";
			xhr.open("POST", url, true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function(){
				if (xhr.readyState == 4 && xhr.status == 200){


					var jsonResponse = JSON.parse(xhr.responseText);

					if (jsonResponse.success == 1){
						document.getElementById("userAccessBox").innerHTML = "<h4>You are logged in. Would you like to <a href = '#' onclick = 'viewMakeBioBox()'><b>create a bio?</b></a></h4>";
						user.id = jsonResponse.userId;
						setUser(user.id);
					} else {
						document.getElementById("userAccessBox").innerHTML = "<h4>Something went wrong. Try again.</h4>";
					}
				} else {
					
				}
			}
			xhr.send("username=" + username + "&password=" + pass1 + "&pass2=" + pass2+"&email="+email + "&lat=" + user.lat + "&lon=" + user.lon);
		} else { 
			alert("passwords don't match. try again");
			viewSetUserLoggedOut();
		}
	}
}

function submitBio(){
	user.bio = document.getElementById("bioIn").value;

	xhr = new XMLHttpRequest();
	var url = "updateUserData.php"
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){
		if (xhr.readyState == 4 && xhr.status == 200){
			viewSetUserLoggedIn();
		}
	}
	xhr.send("bio=" + user.bio);
}

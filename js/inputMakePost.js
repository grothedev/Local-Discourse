function inputMakePost(){
	var title = document.getElementById("title").value;
	var text = document.getElementById("text").value;
	var anon = document.getElementById("anon").checked;
	var tags = document.getElementById("tags_post").value.split(" ");
	var files = document.getElementById("files_in").files;

	var finalFiles = [];
	var i;
	for (i = 0; i < files.length; i++){
		if (files[i].size > 256000000) {
			continue;
		} else if (files[i].name.length > 144) {
			continue;
		} else {
			finalFiles.push(files[i]);
		}
	}
	
	var form = new FormData();
	for (i = 0; i < finalFiles.length; i++){
		form.append("files_in[]", finalFiles[i]);
	}
	
	if (!title){
		alert("You must make a title");
		return;
	} 
	if (!text){
		alert("You must write some words");
		return;
	}
	if (tags == ""){
		alert("You must pick at least one tag");
		return;
	} 

	var jsonObj = {"title": title, "text": text, "lat": user.lat, "lon": user.lon, "anon": anon, "tags": tags};
	var jsonStr = JSON.stringify(jsonObj);
	form.append("str_json", jsonStr);
	
	xhr = new XMLHttpRequest();
	var url = "postAdder.php";
	xhr.open("POST", url, true);
	//xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.onreadystatechange = function(){ 
		if (xhr.readyState == 4 && xhr.status == 200){
			json = JSON.parse(xhr.responseText);
			
			if (json.success == 1){
				viewPostComplete(1);
			} else {
				viewPostComplete(0);
			}
		}
	}
	xhr.send(form);
}

var user = {};

function init(){

	getUserLocation();
	
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

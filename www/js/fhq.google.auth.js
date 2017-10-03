if(!window.fhq) window.fhq = {};

// Enter an API key from the Google API Console:
//   https://console.developers.google.com/apis/credentials
// var apiKey = 'AIzaSyACJ8ur6sSjDwF_az2ptqU9_bHJo2RKgRA';
var apiKey = 'AIzaSyACJ8ur6sSjDwF_az2ptqU9_bHJo2RKgRA';

// Enter the API Discovery Docs that describes the APIs you want to
// access. In this example, we are accessing the People API, so we load
// Discovery Doc found here: https://developers.google.com/people/api/rest/
var discoveryDocs = ["https://people.googleapis.com/$discovery/rest?version=v1"];
// Enter a client ID for a web application from the Google API Console:
//   https://console.developers.google.com/apis/credentials?project=_
// In your API Console project, add a JavaScript origin that corresponds
//   to the domain where you will be running the script.
var clientId = '859780982276-qoj6d7de8sp7vasm6iqe1aeias98okb6.apps.googleusercontent.com';
// Enter one or more authorization scopes. Refer to the documentation for
// the API or https://developers.google.com/people/v1/how-tos/authorizing
// for details.
var scopes = '';
// var authorizeButton = document.getElementById('authorize-button');
// var signoutButton = document.getElementById('signout-button');
/*function handleClientLoad() {
// Load the API client and auth2 library
gapi.load('client:auth2', initClient);
}*/


function initClient() {
	gapi.client.init({
		apiKey: apiKey,
		discoveryDocs: discoveryDocs,
		clientId: clientId,
		scope: scopes
	}).then(function () {
	  // Listen for sign-in state changes.
	  gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
	  // Handle the initial sign-in state.
	  updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
	  // authorizeButton.onclick = handleAuthClick;
	  // signoutButton.onclick = handleSignoutClick;
	});
}



/*function updateSigninStatus(isSignedIn) {
	if (isSignedIn) {
	  authorizeButton.style.display = 'none';
	  signoutButton.style.display = 'block';
	  makeApiCall();
	} else {
	  authorizeButton.style.display = 'block';
	  signoutButton.style.display = 'none';
	}
}*/


function handleAuthClick(event) {
	gapi.auth2.getAuthInstance().signIn();
}
function handleSignoutClick(event) {
	gapi.auth2.getAuthInstance().signOut();
}
// Load the API and make an API call.  Display the results on the screen.
function makeApiCall() {
	gapi.client.people.people.get({
	  'resourceName': 'people/me',
	  'requestMask.includeField': 'person.names'
	}).then(function(resp) {
	  var p = document.createElement('p');
	  var name = resp.result.names[0].givenName;
	  p.appendChild(document.createTextNode('Hello, '+name+'!'));
	  document.getElementById('content').appendChild(p);
	});
}


gapi.load('client:auth2', initClient);

// handleClientLoad();

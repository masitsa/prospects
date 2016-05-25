var base_url = $('#base_url').val();

/* Called after page loads */
$( document ).ready(function() {
	var googleUser = {};
	var startApp = function() {
		gapi.load('auth2', function(){
			// Retrieve the singleton for the GoogleAuth library and set up the client.
			auth2 = gapi.auth2.init({
				//client_id: '463566759044-bprdg1744mn7rjfsroo49o90chp19c5s.apps.googleusercontent.com',
				client_id: '463566759044-4mf1schrlisc33n1pgf12er3qp1oags3.apps.googleusercontent.com',
				cookiepolicy: 'single_host_origin',
				// Request scopes in addition to 'profile' and 'email'
				//scope: 'additional_scope'
			});
			attachSignin(document.getElementById('google_signin'));
		});
	};
	
	function attachSignin(element) {
		console.log(element.id);
		auth2.attachClickHandler(element, {},
		function(googleUser) {
			onSignIn(googleUser);
			}, function(error) {
			alert(JSON.stringify(error, undefined, 2));
		});
	}
	
	startApp();
});

/* Login via google API */
function onSignIn(googleUser) {
	var profile = googleUser.getBasicProfile();
	/*console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
	console.log('Name: ' + profile.getName());
	console.log('Image URL: ' + profile.getImageUrl());
	console.log('Email: ' + profile.getEmail());*/
	var id = profile.getId();
	var name = profile.getName();
	var image = profile.getImageUrl();
	var email = profile.getEmail();
	
	$.ajax({
		type:'POST',
		url: base_url+'site/sign_in_customer',
		dataType: 'json',
		data: {name: name, image: image, email: email},
		success:function(data){
			//alert(data.message);
			if(data.message == "true")
			{
				window.location.href = base_url+'my-account';
			}
			else
			{
				var response = 
				'<span>'+
					data.result+
				'</span>';
				Materialize.toast(response, 5000);
				$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
					$(this).remove();
				});
			}
		},
		error: function(xhr, status, error) {
			Materialize.toast(error, 5000);
			$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();
			});
		}
	});
}

/*
* Logout via google API
*/
function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
}
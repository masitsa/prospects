$( document ).ready(function() {
    /* Timeout alert messages */
	window.setTimeout(function() { 
		$(".alert").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();
		});
		
	}, 5000);
	
	/* Initialize variables */
	(function($){
		$(function(){
			$('ul.tabs').tabs();
			$('.button-collapse').sideNav();//initialize side nav
			$('.collapsible').collapsible();//initialize nav collapse
			$('select').material_select();//initialize select
			$('.modal-trigger').leanModal();//initialize modal
			$('.collapsible').collapsible({
				accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
			});
		}); // end of document ready
	})(jQuery); // end of jQuery name space
	
});

/* Add new banner */
$(document).on("submit","form#add_new_banner",function(e) 
{
	e.preventDefault();
	var base_url = $('#base_url').val();
	var website = $('input[name="website"]').val();
	var res = website.replace("http://", '');
	var res2 = res.replace("https://", '');
	var website = res2.replace("www.", '');

	var formData = new FormData(this);
	
	$.ajax({
		type:'POST',
		url: base_url+'site/account/add_banner',
		data:formData,
		cache:false,
		contentType: false,
		processData: false,
		dataType: 'json',
		success:function(data){
			//alert(data.message);
			if(data.message == "true")
			{
				window.location.href = base_url+'banner/'+website;
			}
			else
			{
				var response = 
				'<span>'+
					data.response+
				'</span>';
				Materialize.toast(response, 5000);
			}
		},
		error: function(xhr, status, error) {
			console.log("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
			var response = 
			'<span>'+
				error+
			'</span>';
			Materialize.toast(response, 5000);
		}
	});
	return false;
	window.location.href = base_url+'banner/'+website;
});
/* End add new card */

/* Add new card */
$(document).on("submit","form#add_new_card",function(e) 
{
	$('#expiry_year').html(
		'<div class="preloader-wrapper active">'+
    		'<div class="spinner-layer spinner-red-only">'+
				'<div class="circle-clipper left">'+
					'<div class="circle"></div>'+
				'</div>'+
    			'<div class="gap-patch">'+
					'<div class="circle"></div>'+
				'</div>'+
    			'<div class="circle-clipper right">'+
					'<div class="circle"></div>'+
				'</div>'+
    		'</div>'+
    	'</div>'
	);
	e.preventDefault();
	
	//get stripe.js script
	$.getScript( 'https://js.stripe.com/v2/', function() 
	{
		var card_number = $('input[name="card_number"]').val();
		var card_expiry_year = $('input[name="card_expiry_year"]').val();
		var card_expiry_month = $('input[name="card_expiry_month"]').val();
		var card_cvc = $('input[name="card_cvc"]').val();
		var stripe_publishable_key = $('#stripe_publishable_key').val();
		
		//create a new card in stripe
		Stripe.setPublishableKey(stripe_publishable_key);
		Stripe.card.createToken({
			number: card_number,
			cvc: card_cvc,
			exp_year: card_expiry_year,
			exp_month: card_expiry_month
		}, stripeResponseHandler);
	});
	return false;
});
/* End add new card */

function stripeResponseHandler(status, response) 
{
	var base_url = $('#base_url').val();
	var $form = $('#add_new_card');

	if (response.error) {
		// Show the errors on the form
		var response = 
		'<span>'+
			response.error.message+
		'</span>';
		Materialize.toast(response, 5000);
		$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();
		});
	} else {
		// response contains id and card, which contains additional card details
		var token = response.id;
		// Insert the token into the form so it gets submitted to the server
		$form.append($('<input type="hidden" name="stripe_token" />').val(token));
		// and submit
		//$form.get(0).submit();
		
		var stripe_token = $('input[name="stripe_token"]').val();
		var plan_id = $('input[name="plan_id"]').val();
		$.ajax({
			type:'POST',
			url: base_url+'site/account/add_card',
			dataType: 'json',
			data: {stripe_token: stripe_token, plan_id: plan_id},
			success:function(data){
				//alert(data.message);
				if(data.message == "true")
				{
					window.location.href = base_url+'subscribe';
				}
				else
				{
					var response = 
					'<span>'+
						data.response+
					'</span>';
					Materialize.toast(response, 5000);
					$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
						$(this).remove();
					});
				}
			},
			error: function(xhr, status, error) {
				console.log("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				var response = 
				'<span>'+
					error+
				'</span>';
				Materialize.toast(response, 5000);
				
				$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
					$(this).remove();
				});
			}
		});
	}
}

/* Subscribe */
$(document).on("click","a.subscribe",function(e) 
{
	var base_url = $('#base_url').val();
	$('.preloader_subscribe').prepend(
		'<div class="preloader-wrapper active">'+
    		'<div class="spinner-layer spinner-red-only">'+
				'<div class="circle-clipper left">'+
					'<div class="circle"></div>'+
				'</div>'+
    			'<div class="gap-patch">'+
					'<div class="circle"></div>'+
				'</div>'+
    			'<div class="circle-clipper right">'+
					'<div class="circle"></div>'+
				'</div>'+
    		'</div>'+
    	'</div>'
	);
	e.preventDefault();
	var plan_id = $(this).attr('href');
	
	//check if customer has been registered in stripe
	$.ajax({
		type:'POST',
		url: base_url+'check-customer/'+plan_id,
		dataType: 'json',
		success:function(data){
			//alert(data.message);
			if(data.message == "true")
			{
				window.location.href = base_url+'subscribe';
			}
			else
			{
				if(data.response == 'Customer does not exist')
				{
					//show modal to add new card
					$('#newcard').openModal();
					var $form = $('#add_new_card');
					$form.append($('<input type="hidden" name="plan_id" />').val(plan_id));
				}
				
				else
				{
					var response = 
					'<span>'+
						data.response+
					'</span>';
					Materialize.toast(response, 5000);
					$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
						$(this).remove();
					});
				}
			}
		},
		error: function(xhr, status, error) {
			console.log("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
			var response = 
			'<span>'+
				error+
			'</span>';
			Materialize.toast(response, 5000);
			
			$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();
			});
		}
	});
	return false;
});
/* End subscribe */

/* Subscribe */
$(document).on("click","a.upgrade",function(e) 
{
	var base_url = $('#base_url').val();
	$('.preloader_subscribe').prepend(
		'<div class="preloader-wrapper active">'+
    		'<div class="spinner-layer spinner-red-only">'+
				'<div class="circle-clipper left">'+
					'<div class="circle"></div>'+
				'</div>'+
    			'<div class="gap-patch">'+
					'<div class="circle"></div>'+
				'</div>'+
    			'<div class="circle-clipper right">'+
					'<div class="circle"></div>'+
				'</div>'+
    		'</div>'+
    	'</div>'
	);
	e.preventDefault();
	var plan_id = $(this).attr('href');
	
	//check if customer has been registered in stripe
	$.ajax({
		type:'POST',
		url: base_url+'check-customer/'+plan_id,
		dataType: 'json',
		success:function(data){
			//alert(data.message);
			if(data.message == "true")
			{
				window.location.href = base_url+'subscribe';
			}
			else
			{
				if(data.response == 'Customer does not exist')
				{
					//show modal to add new card
					$('#newcard').openModal();
					var $form = $('#add_new_card');
					$form.append($('<input type="hidden" name="plan_id" />').val(plan_id));
				}
				
				else
				{
					var response = 
					'<span>'+
						data.response+
					'</span>';
					Materialize.toast(response, 5000);
					$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
						$(this).remove();
					});
				}
			}
		},
		error: function(xhr, status, error) {
			console.log("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
			var response = 
			'<span>'+
				error+
			'</span>';
			Materialize.toast(response, 5000);
			
			$(".preloader-wrapper").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();
			});
		}
	});
	return false;
});
/* End subscribe */
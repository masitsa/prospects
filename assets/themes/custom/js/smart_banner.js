/*
*	Page variables
*/
var banner_title, banner_author, banner_price, banner_langauge, banner_app_store_lang, banner_play_store_lang, banner_amazon_store_lang, banner_windows_store_lang, banner_play_store_params, banner_icon_url, banner_ios_icon_gloss, banner_url, banner_button_text, banner_auto_scale, banner_speed_in, banner_speed_out, banner_days_hidden, banner_days_reminder, banner_force_display, banner_hide_on_install, banner_overlay_layer, banner_ios_universall_app, banner_append_to_selector, banner_install_message, banner_close_message;

var base_url = $('#base_url').val();

/*
* Smart banner functions
*/
var Banner = function() 
{
    var url;

    this.initialize = function(serviceURL) {
        /*url = serviceURL ? serviceURL : base_url;
        var deferred = $.Deferred();
        deferred.resolve();
        return deferred.promise();*/
    }
	
	/*
	*	validate values before they are used to generate the smart banner
	*	@param text: the text to be validated
	*	@param return_value: the default return value
	*/
    this.check_text = function(text, return_value)
	{
		//Check if text is a number. Convert to number and return number
		var number_check = parseInt(text);
		if(number_check > 0)
		{
			text = parseInt(text);
		}
		
		//check boolean
		else if(text == 'true')
		{
			text = true;
		}
		
		//check boolean
		else if(text == 'false')
		{
			text = false;
		}
		
		//Check if text is empty. Use return value
		else if(text == '')
		{
			text = return_value;
		}
		
		else
		{
			//No action required
		}
		
		return text;
    }

    this.validate_parameters = function() {
		var validate = new Banner();//initialize banner object
		
		/* Banner title */
		banner_title = $('#title').val();
		banner_title = validate.check_text(banner_title, 'Enter title');
		
		/* Banner author */
		banner_author = $('#author').val();
		banner_author = validate.check_text(banner_author, 'Enter author');
		
		/* Banner price */
		banner_price = $('#price').val();
		banner_price = validate.check_text(banner_price, 'Free');
		
		/* Banner language */
		banner_langauge = $('#language').val();
		banner_langauge = validate.check_text(banner_langauge, 'us');
		
		/* Banner app_store_lang */
		banner_app_store_lang = $('#app_store_lang').val();
		banner_app_store_lang = validate.check_text(banner_app_store_lang, 'On the App Store');
		
		/* Banner play_store_lang */
		banner_play_store_lang = $('#play_store_lang').val();
		banner_play_store_lang = validate.check_text(banner_play_store_lang, 'In Google Play');
		
		/* Banner amazon_store_lang */
		banner_amazon_store_lang = $('#amazon_store_lang').val();
		banner_amazon_store_lang = validate.check_text(banner_amazon_store_lang, 'In the Amazon Appstore');
		
		/* Banner windows_store_lang */
		banner_windows_store_lang = $('#windows_store_lang').val();
		banner_windows_store_lang = validate.check_text(banner_windows_store_lang, 'In the Windows store');
		
		/* Banner play_store_params */
		banner_play_store_params = $('#play_store_params').val();
		banner_play_store_params = validate.check_text(banner_play_store_params, null);
		
		/* Banner icon_url */
		banner_icon_url = $('#icon_url').val();
		banner_icon_url = validate.check_text(banner_icon_url, null);
		
		/* Banner ios_icon_gloss */
		banner_ios_icon_gloss = $('#ios_icon_gloss').val();
		banner_ios_icon_gloss = validate.check_text(banner_ios_icon_gloss, null);
		
		/* Banner url */
		banner_url = $('#url').val();
		banner_url = validate.check_text(banner_url, null);
		
		/* Banner button_text */
		banner_button_text = $('#button_text').val();
		banner_button_text = validate.check_text(banner_button_text, 'VIEW');
		
		/* Banner auto_scale */
		banner_auto_scale = $('#auto_scale').val();
		banner_auto_scale = validate.check_text(banner_auto_scale, 'auto');
		
		/* Banner speed_in */
		banner_speed_in = $('#speed_in').val();
		banner_speed_in = validate.check_text(banner_speed_in, 300);
		
		/* Banner speed_out */
		banner_speed_out = $('#speed_out').val();
		banner_speed_out = validate.check_text(banner_speed_out, 400);
		
		/* Banner days_hidden */
		banner_days_hidden = $('#days_hidden').val();
		banner_days_hidden = validate.check_text(banner_days_hidden, 15);
		
		/* Banner days_reminder */
		banner_days_reminder = $('#days_reminder').val();
		banner_days_reminder = validate.check_text(banner_days_reminder, 15);
		
		/* Banner force_display */
		banner_force_display = $('#force_display').val();
		banner_force_display = validate.check_text(banner_force_display, null);
		
		/* Banner hide_on_install */
		banner_hide_on_install = $('input[name=hide_on_install]:checked', '#banner_form').val();//$('#hide_on_install').val();
		banner_hide_on_install = validate.check_text(banner_hide_on_install, true);
		
		/* Banner overlay_layer */
		banner_overlay_layer = $('#overlay_layer').val();
		banner_overlay_layer = validate.check_text(banner_overlay_layer, false);
		
		/* Banner ios_universall_app */
		banner_ios_universall_app = $('#ios_universall_app').val();
		banner_ios_universall_app = validate.check_text(banner_ios_universall_app, true);
		
		/* Banner append_to_selector */
		banner_append_to_selector = $('#append_to_selector').val();
		banner_append_to_selector = validate.check_text(banner_append_to_selector, 'smartbanner_display');
		
		/* Banner install_message */
		banner_install_message = $('#install_message').val();
		banner_install_message = validate.check_text(banner_install_message, 'Installation successfull');
		
		/* Banner close_message */
		banner_close_message = $('#close_message').val();
		banner_close_message = validate.check_text(banner_close_message, 'Closed');
		
		return true;
    }

    this.generate_banner = function() {
		/* Clear smart banner */
		$('smartbanner_display').html('');
		/* Generate banner using validated variables *///alert(banner_title);
		$.smartbanner({
			title: banner_title, // What the title of the app should be in the banner (defaults to <title>)
			author: banner_author, // What the author of the app should be in the banner (defaults to <meta name="author"> or hostname)
			price: banner_price, // Price of the app
			appStoreLanguage: banner_langauge, // Language code for App Store
			inAppStore: banner_app_store_lang, // Text of price for iOS
			inGooglePlay: banner_play_store_lang, // Text of price for Android
			inAmazonAppStore: banner_amazon_store_lang,
			inWindowsStore: banner_windows_store_lang, // Text of price for Windows
			GooglePlayParams: banner_play_store_params, // Aditional parameters for the market
			icon: banner_icon_url, // The URL of the icon (defaults to <meta name="apple-touch-icon">)
			iconGloss: banner_ios_icon_gloss, // Force gloss effect for iOS even for precomposed
			url: banner_url, // The URL for the button. Keep null if you want the button to link to the app store.
			button: banner_button_text, // Text for the install button
			scale: banner_auto_scale, // Scale based on viewport size (set to 1 to disable)
			speedIn: banner_speed_in, // Show animation speed of the banner
			speedOut: banner_speed_out, // Close animation speed of the banner
			daysHidden: banner_days_hidden, // Duration to hide the banner after being closed (0 = always show banner)
			daysReminder: banner_days_reminder, // Duration to hide the banner after "VIEW" is clicked *separate from when the close button is clicked* (0 = always show banner)
			force: banner_force_display, // Choose 'ios', 'android' or 'windows'. Don't do a browser check, just always show this banner
			hideOnInstall: banner_hide_on_install, // Hide the banner after "VIEW" is clicked.
			layer: banner_overlay_layer, // Display as overlay layer or slide down the page
			iOSUniversalApp: banner_ios_universall_app, // If the iOS App is a universal app for both iPad and iPhone, display Smart Banner to iPad users, too.      
			appendToSelector: banner_append_to_selector, //Append the banner to a specific selector
			onInstall: function() {
				var response = 
				'<span>'+
					banner_install_message+
				'</span>';
				Materialize.toast(response, 5000);
			},
			onClose: function() {
				var response = 
				'<span>'+
					banner_close_message+
				'</span>';
				Materialize.toast(response, 5000);
			}
		});
		
		//apply banner styles
		var top_border_color = $('#top_border_color').val();
		$('#smartbanner.android').css('border-top', '5px solid #'+top_border_color);
		$('#smartbanner.ios').css('border-top', '5px solid #'+top_border_color);
		$('#smartbanner.windows').css('border-top', '5px solid #'+top_border_color);
		
		var top_gradient_color = $('#top_gradient_color').val();
		var bottom_gradient_color = $('#bottom_gradient_color').val();
		$('#smartbanner.android').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
		$('#smartbanner.ios').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
		$('#smartbanner.windows').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
		
		var text_color = $('#text_color').val();
		$('#smartbanner.android .sb-info strong').css('color', '#'+text_color);
		$('#smartbanner.ios .sb-info strong').css('color', '#'+text_color);
		$('#smartbanner.windows .sb-info strong').css('color', '#'+text_color);
		$('#smartbanner .sb-info > span').css('color', '#'+text_color);
		
		var button_color = $('#button_color').val();
		$('#smartbanner.android .sb-button span').css('background-color', '#'+button_color);
		$('#smartbanner.android .sb-button span').css('background-image', '-moz-linear-gradient(center top , #'+button_color+', #'+button_color+')');
		
		$('#smartbanner.ios .sb-button').css('background-color', '#'+button_color);
		$('#smartbanner.ios .sb-button').css('background-image', '-moz-linear-gradient(center top , #'+button_color+', #'+button_color+')');
		
		$('#smartbanner.windows .sb-button').css('background-color', '#'+button_color);
		$('#smartbanner.windows .sb-button').css('background-image', '-moz-linear-gradient(center top , #'+button_color+', #'+button_color+')');
		
		var button_text_color = $('#button_text_color').val();
		$('#smartbanner.android .sb-button span').css('color', '#'+button_text_color);
		$('#smartbanner.ios .sb-button span').css('color', '#'+button_text_color);
		$('#smartbanner.windows .sb-button span').css('color', '#'+button_text_color);
    }
}

/*
* Initialize banner generating object
*/
generate = new Banner();

/* Called after page loads */
$( document ).ready(function() {
	
	//generate smart banner
	generate.validate_parameters();
	generate.generate_banner();
});

/* Generate banner on all focus out and radio clicks */
$(document).on("focusout","input#title",function() 
{
	var response = generate.validate_parameters();
	
	if(response == true)
	{
		//alert('true');
		generate.generate_banner();
	}
	else
	{
		//alert('false');
	}
});
$(document).on("focusout","input#author",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#price",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#language",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#app_store_lang",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#play_store_lang",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#amazon_store_lang",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#windows_store_lang",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#play_store_params",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#icon_url",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#ios_icon_gloss",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#url",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#speed_in",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#speed_out",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#days_hidden",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#days_reminder",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#button_text",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#auto_scale",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("change","select#force_display",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("click","input#hide_on_install",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("click","input#hide_on_install2",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("click","input#overlay_layer",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("click","input#overlay_layer2",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#append_to_selector",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#install_message",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("focusout","input#close_message",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
$(document).on("change","select#platform",function() 
{
	generate.validate_parameters();
	generate.generate_banner();
});
/* End generate banner on all focus out and radio clicks */

/* Change banner colors */
$(document).on("change","input#top_border_color",function() 
{
	var top_border_color = $(this).val();
	$('#smartbanner.android').css('border-top', '5px solid #'+top_border_color);
	$('#smartbanner.ios').css('border-top', '5px solid #'+top_border_color);
	$('#smartbanner.windows').css('border-top', '5px solid #'+top_border_color);
});

$(document).on("change","input#top_gradient_color",function() 
{
	var top_gradient_color = $(this).val();
	var bottom_gradient_color = $('#bottom_gradient_color').val();
	if(bottom_gradient_color == '')
	{
		bottom_gradient_color = 'ffffff';
	}
	$('#smartbanner.android').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
	$('#smartbanner.ios').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
	$('#smartbanner.windows').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
});

$(document).on("change","input#bottom_gradient_color",function() 
{
	var bottom_gradient_color = $(this).val();
	var top_gradient_color = $('#top_gradient_color').val();
	if(top_gradient_color == '')
	{
		top_gradient_color = 'ffffff';
	}
	$('#smartbanner.android').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
	$('#smartbanner.ios').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
	$('#smartbanner.windows').css('background', 'linear-gradient(#'+top_gradient_color+', #'+bottom_gradient_color+')');
});

$(document).on("change","input#text_color",function() 
{
	var text_color = $(this).val();
	$('#smartbanner.android .sb-info strong').css('color', '#'+text_color);
	$('#smartbanner.ios .sb-info strong').css('color', '#'+text_color);
	$('#smartbanner.windows .sb-info strong').css('color', '#'+text_color);
	$('#smartbanner .sb-info > span').css('color', '#'+text_color);
});

$(document).on("change","input#button_color",function() 
{
	var button_color = $(this).val();
	$('#smartbanner.android .sb-button span').css('background-color', '#'+button_color);
	$('#smartbanner.android .sb-button span').css('background-image', '-moz-linear-gradient(center top , #'+button_color+', #'+button_color+')');
	
	$('#smartbanner.ios .sb-button span').css('background-color', '#'+button_color);
	$('#smartbanner.ios .sb-button span').css('background-image', '-moz-linear-gradient(center top , #'+button_color+', #'+button_color+')');
	
	$('#smartbanner.windows .sb-button span').css('background-color', '#'+button_color);
	$('#smartbanner.windows .sb-button span').css('background-image', '-moz-linear-gradient(center top , #'+button_color+', #'+button_color+')');
});

$(document).on("change","input#button_text_color",function() 
{
	var button_text_color = $(this).val();
	$('#smartbanner.android .sb-button span').css('color', '#'+button_text_color);
	$('#smartbanner.ios .sb-button span').css('color', '#'+button_text_color);
	$('#smartbanner.windows .sb-button span').css('color', '#'+button_text_color);
});

/* Add new banner */
$(document).on("submit","form#add_new_banner",function(e) 
{
	e.preventDefault();
	var form_data = new FormData(this);
	
	$.ajax({
		type:'POST',
		url: base_url+'site/account/add_banner',
		dataType: 'json',
		data: form_data,
		processData: false,
    	contentType: false,
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
					data.response+
				'</span>';
				Materialize.toast(response, 5000);
			}
		},
		error: function(xhr, status, error) {
			console.log("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		}
	});
	return false;
});
/* End add new banner */

/* Update banner */
$(document).on("submit","form#banner_form",function(e) 
{
	e.preventDefault();
	var form_data = new FormData(this);
	var action_url = $(this).attr('action');
	
	$.ajax({
		type:'POST',
		url: action_url,
		dataType: 'json',
		data: form_data,
		processData: false,
    	contentType: false,
		success:function(data){
			//alert(data.message);
			if(data.message == "true")
			{
				var response = 
				'<span>'+
					'Banner updated successfully'+
				'</span>';
				Materialize.toast(response, 5000);
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
		}
	});
	return false;
});
/* End update banner */

/* Change active banner */
$(document).on("click","ul#banner_select a",function() 
{
	var website_name = $(this).val();
	window.location.href = base_url+'my-account/'+website_name;
});
/* End change active banner */
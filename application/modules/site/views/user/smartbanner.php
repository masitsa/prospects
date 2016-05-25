<?php
if($latest_banner->num_rows() > 0)
{
	$banner = $latest_banner->row();
	
	$smart_banner_id = $banner->smart_banner_id;
	$smart_banner_website = $banner->smart_banner_website;
	$customer_id = $banner->customer_id;
	$smart_banner_status = $banner->smart_banner_status;
	$smart_banner_created = $banner->smart_banner_created;
	$smart_banner_last_modified = $banner->smart_banner_last_modified;
	$title = $banner->title;
	$author = $banner->author;
	$price = $banner->price;
	$language = $banner->language;
	$app_store_lang = $banner->app_store_lang;
	$play_store_lang = $banner->play_store_lang;
	$amazon_store_lang = $banner->amazon_store_lang;
	$windows_store_lang = $banner->windows_store_lang;
	$play_store_params = $banner->play_store_params;
	$icon_url = $banner->icon_url;
	$ios_icon_gloss = $banner->ios_icon_gloss;
	$url = $banner->url;
	$speed_in = $banner->speed_in;
	$speed_out = $banner->speed_out;
	$days_hidden = $banner->days_hidden;
	$days_reminder = $banner->days_reminder;
	$button_text = $banner->button_text;
	$auto_scale = $banner->auto_scale;
	$force_display = $banner->force_display;
	$hide_on_install = $banner->hide_on_install;
	$overlay_layer = $banner->overlay_layer;
	$ios_universall_app = $banner->ios_universall_app;
	$append_to_selector = $banner->append_to_selector;
	$install_message = $banner->install_message;
	$close_message = $banner->close_message;
	$top_border_color = $banner->top_border_color;
	$top_gradient_color = $banner->top_gradient_color;
	$bottom_gradient_color = $banner->bottom_gradient_color;
	$text_color = $banner->text_color;
	$button_color = $banner->button_color;
	$button_text_color = $banner->button_text_color;
	
	if(empty($top_border_color))
	{
		$top_border_color = '88B131';
	}
	if(empty($top_gradient_color))
	{
		$top_gradient_color = '555555';
	}
	if(empty($bottom_gradient_color))
	{
		$bottom_gradient_color = '555555';
	}
	if(empty($text_color))
	{
		$text_color = 'ffffff';
	}
	if(empty($button_color))
	{
		$button_color = '2196F3';
	}
	if(empty($button_text_color))
	{
		$button_text_color ='ffffff';
	}
}

else
{
	$smart_banner_id = '';
	$smart_banner_website = '';
	$customer_id = '';
	$smart_banner_status = '';
	$smart_banner_created = '';
	$smart_banner_last_modified = '';
	$title = '';
	$author = '';
	$price = '';
	$language = '';
	$app_store_lang = '';
	$play_store_lang = '';
	$amazon_store_lang = '';
	$windows_store_lang = '';
	$play_store_params = '';
	$icon_url = '';
	$ios_icon_gloss = '';
	$url = '';
	$speed_in = '';
	$speed_out = '';
	$days_hidden = '';
	$days_reminder = '';
	$button_text = '';
	$auto_scale = '';
	$force_display = '';
	$hide_on_install = '';
	$overlay_layer = '';
	$ios_universall_app = '';
	$append_to_selector = '';
	$install_message = '';
	$close_message = '';
	$top_border_color = '88B131';
	$top_gradient_color = '555555';
	$bottom_gradient_color = '555555';
	$text_color = 'ffffff';
	$button_color = '2196F3';
	$button_text_color ='ffffff';
}
?>
				
            <!-- title -->
            <div class="row">
                <div class="col s12">
                    <h4 class="header center-align"><?php echo $title;?></h4>
                </div>
            </div>
            <!-- end title -->
            
        	<div class="row">
                
                <div class="col l6 m12">
                    <!--<div class="col m4 offset-m8">
                    	<form action="" method="POST">
                            <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="<?php echo $this->config->item('stripe_publishable_key');?>"
                                data-amount="2000"
                                data-name="Installify"
                                data-description="1 Banner ($20.00)"
                                data-image="<?php echo base_url().'assets/logo/'.$logo;?>"
                                data-locale="auto">
                            </script>
                        </form>
                    </div>-->
                    <ul class="collapsible" data-collapsible="accordion" style="margin-top:0;">
                        <li>
                            <div class="collapsible-header"><i class="material-icons">settings</i> Settings</div>
                            <div class="collapsible-body" style="padding-bottom: 20px;">
                                <h5 class="center-align header">Banner status</h5>
                                <?php
                                if($smart_banner_status == 1)
                                {
                                    ?>
                                    <p>This banner is currently <span class="blue-text">active</span>. Click here to deactivate this banner. Deactivated banners will not be accessible through the Installify API.</p>
                                    <div class="col m4 offset-m4"><a class="waves-effect waves-light btn grey lighten-2 grey-text text-darken-2" href="<?php echo site_url().'deactivate-banner/'.$smart_banner_website;?>" onclick="return confirm('Do you want to deactivate this banner?');" title="Deactivate banner"><i class="fa fa-eye-slash"></i> Deactivate</a></div>
                                    <div class="clear"></div>
                                    <?php
                                }
                                
                                else
                                {
                                    ?>
                                    <p>This banner is currently <span class="blue-text">inactive</span>. Click here to activate it. Active banners can be accessed through the Installify API.</p>
                                    <div class="col m4 offset-m4"><a class="waves-effect waves-light btn blue lighten-2" href="<?php echo site_url().'activate-banner/'.$smart_banner_website;?>" onclick="return confirm('Do you want to activate this banner?');" title="Deactivate banner"><i class="fa fa-eye-slash"></i> Activate</a></div>
                                    <div class="clear"></div>
                                    <?php
                                }
                                ?>
                                
                                <h5 class="center-align header">Install banner</h5>
                                
                                <p>Copy & paste this code before the &lt;/body&gt; tag of your website on every page that you would like the banner to appear</p>
                                <pre class=" language-markup"><code class=" language-markup"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>script</span><span class="token attr-name"> src</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span><?php echo site_url();?>installify.js<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>/script</span> <span class="token punctuation">&gt;</span></span>
<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>script</span><span class="token punctuation">&gt;</span></span>
$( document ).ready(function() {
	var generate = new Banner();
    generate.generate_banner('<?php echo $smart_banner_website;?>', '<?php echo $this->session->userdata('customer_api_key');?>');
});
<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>/script</span><span class="token punctuation">&gt;</span></span></code>
                                </pre>
                                
                                <h5 class="center-align header">Delete banner</h5>
                                
                                <p>Click here to delete this banner. Be cautious. This action cannot be undone!</p>
                                <div class="col m4 offset-m4"><a class="waves-effect waves-light btn red lighten-2" href="<?php echo site_url().'delete-banner/'.$smart_banner_website;?>" onclick="return confirm('Do you want to delete this banner? It cannot be undone!');" title="Deactivate banner"><i class="fa fa-trash"></i> Delete</a></div>
                                <div class="clear"></div>
                            </div>
                        </li>
                    </ul>
                        
                    <div class="input-field col s12" style="margin-top:0;">
                        <select id="platform">
                            <option value="" disabled selected>Choose your platform</option>
                            <option value="ios">IOS</option>
                            <option value="android">Android</option>
                            <option value="windows">Windows</option>
                        </select>
                    </div>
                	<h5 class="header center-align" id="platform-display">Android display</h5>
                    <div id="update_banner_response"></div>
                    <smartbanner_display></smartbanner_display>
                </div>
                
                <div class="col l6 m12">
                    <form class="form-horizontal sidebar_form" id="banner_form" action="<?php echo site_url('site/account/update_banner/'.$smart_banner_id);?>" method="POST">
                        <ul class="tabs">
                            <li class="tab col s4"><a class="active" href="#setup">Setup</a></li>
                            <li class="tab col s4"><a href="#appearance">Appearance</a></li>
                            <li class="tab col s4"><a href="#store">Store</a></li>
                        </ul>
                        <div id="setup" class="col s12">
                            <div class="row">
                                <h5 class="header center-align">General setup</h5>
                                <div class="input-field col m6">
                                    <input type="text"  id="title" name="title" value="<?php echo $title;?>">
                                    <label for="title">Title <span class="required">*</span></label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text"  id="author" name="author" value="<?php echo $author;?>">
                                    <label for="title">Author <span class="required">*</span></label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text"  id="price" name="price" value="<?php echo $price;?>">
                                    <label for="title">Price <span class="required">*</span></label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text"  id="url" name="url" value="<?php echo $smart_banner_website;?>">
                                    <label for="title">URL <span class="required">*</span></label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text"  id="icon_url" name="icon_url" value="<?php echo $icon_url;?>">
                                    <label for="title">Icon url (57px X 57px)<span class="required">*</span></label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text"  id="speed_in" name="speed_in" value="<?php echo $speed_in;?>">
                                    <label for="title">Speed in</label>
                                </div>
                                <div class="input-field col m6">
                                    <label for="title">Speed out</label>
                                    <input type="text"  id="speed_out" name="speed_out" value="<?php echo $speed_out;?>">
                                </div>
                                <div class="input-field col m6">
                                    <input type="text"  id="days_hidden" name="days_hidden" value="<?php echo $days_hidden;?>">
                                    <label for="title">Days hidden after close</label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text"  id="days_reminder" name="days_reminder" value="<?php echo $days_reminder;?>">
                                    <label for="title">Days hidden after view</label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text"  id="button_text" name="button_text" value="<?php echo $button_text;?>">
                                    <label for="title">Button text</label>
                                </div>
                            </div>
                        </div>
                        
                        <div id="appearance" class="col s12">
                            <div class="row">
                                <h5 class="header center-align">Edit appearance</h5>
                                <div class="input-field col m6">
                                    <input type="text" class="jscolor" id="top_gradient_color" name="top_gradient_color" value="<?php echo $top_gradient_color;?>">
                                    <label>Top gradient color</label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text" class="jscolor" id="bottom_gradient_color" name="bottom_gradient_color" value="<?php echo $bottom_gradient_color;?>">
                                    <label>Bottom gradient color</label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text" class="jscolor" id="top_border_color" name="top_border_color" value="<?php echo $top_border_color;?>">
                                    <label>Top border color</label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text" class="jscolor" id="text_color" name="text_color" value="<?php echo $text_color;?>">
                                    <label>Text color</label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text" class="jscolor" id="button_color" name="button_color" value="<?php echo $button_color;?>">
                                    <label>Button color</label>
                                </div>
                                <div class="input-field col m6">
                                    <input type="text" class="jscolor" id="button_text_color" name="button_text_color" value="<?php echo $button_text_color;?>">
                                    <label>Button text color</label>
                                </div>
                            </div>
                        </div>
                        
                        <div id="store" class="col s12">
                            <div class="row">
                                <h5 class="header center-align">IOS</h5>
                                <div class="input-field col s12">
                                    <input type="text"  id="ios_icon_gloss" name="ios_icon_gloss" value="<?php echo $ios_icon_gloss;?>">
                                    <label for="title">IOS icon gloss (57px X 57px)</label>
                                </div>
                                <div class="input-field col s12">
                                    <input type="text"  id="app_store_lang" name="app_store_lang" value="<?php echo $app_store_lang;?>">
                                    <label for="title">App Store price text</label>
                                </div>
                                
                                <h5 class="header center-align">Android</h5>
                                <div class="input-field col s12">
                                    <input type="text"  id="play_store_lang" name="play_store_lang" value="<?php echo $play_store_lang;?>">
                                    <label for="title">Google Play Store price text</label>
                                </div>
                                <div class="input-field col s12">
                                    <input type="text"  id="play_store_params" name="play_store_params" value="<?php echo $play_store_params;?>">
                                    <label for="title">Google Play Store params</label>
                                </div>
                                
                                <h5 class="header center-align">Windows</h5>
                                <div class="input-field col s12">
                                    <input type="text"  id="windows_store_lang" name="windows_store_lang" value="<?php echo $windows_store_lang;?>">
                                    <label for="title">Windows Store price text</label>
                                    
                                <!--
                                <div class="input-field col s12">
                                    <label for="title">Amazon Appstore price text</label>
                                    <input type="text"  id="amazon_store_lang" placeholder="Amazon Appstore price text" value="<?php echo $amazon_store_lang;?>">
                                </div>
                                <div class="input-field col s12">
                                    <label for="title">App store language</label>
                                    <input type="text"  id="language" placeholder="App store language" value="<?php echo $language;?>">
                                </div>
                                <div class="input-field col s12">
                                    <label for="title" class="col-sm-12">Auto scale</label>
                                    <div class="radio col-sm-6">
                                        <label>
                                            <input type="radio" name="auto_scale" id="auto_scale" value="auto">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="radio col-sm-6">
                                        <label>
                                            <input type="radio" name="auto_scale" id="auto_scale2" value="1">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="input-field col s12">
                                    <label for="title" class="col-sm-12">Force display</label>
                                    <div class="radio col-sm-12">
                                        <select id="force_display" >
                                            <option value="">None</option>
                                            <option value="ios">IOS</option>
                                            <option value="android">Android</option>
                                            <option value="Windows">Windows</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="input-field col s12">
                                    <label for="title" class="col-sm-12">Hide after install</label>
                                    <div class="radio col-sm-6">
                                        <label>
                                            <input type="radio" name="hide_on_install" id="hide_on_install" value="true" checked="checked">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="radio col-sm-6">
                                        <label>
                                            <input type="radio" name="hide_on_install" id="hide_on_install2" value="false">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="input-field col s12">
                                    <label for="title" class="col-sm-12">Overlay layer</label>
                                    <div class="radio col-sm-6">
                                        <label>
                                            <input type="radio" name="overlay_layer" id="overlay_layer" value="true">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="radio col-sm-6">
                                        <label>
                                            <input type="radio" name="overlay_layer" id="overlay_layer2" value="false" checked="checked">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="input-field col s12">
                                    <label for="title" class="col-sm-12">IOS Universall app</label>
                                    <div class="radio col-sm-6">
                                        <label>
                                            <input type="radio" name="ios_universall_app" id="ios_universall_app" value="true" checked="checked">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="radio col-sm-6">
                                        <label>
                                            <input type="radio" name="ios_universall_app" id="ios_universall_app2" value="false">
                                            No
                                        </label>
                                    </div>
                                </div>
                                <div class="input-field col s12">
                                    <label for="title">Append to selector</label>
                                    <input type="text"  id="append_to_selector" placeholder="Append to selector">
                                </div>
                                <div class="input-field col s12">
                                    <label for="title">On install message</label>
                                    <input type="text"  id="install_message" placeholder="On install message">
                                </div>
                                <div class="input-field col s12">
                                    <label for="title">On close message</label>
                                    <input type="text"  id="close_message" placeholder="On close message">
                                </div>-->
                                
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="smartbanner_display" id="append_to_selector" />
                        <button type="submit" class="btn blue">Update</button>
                    </form>
                </div>
            </div>
<script src="<?php echo base_url().'assets/themes/jquery.smartbanner/';?>jquery.smartbanner-account.js"></script>
<script src="<?php echo base_url()."assets/themes/custom/";?>js/smart_banner.js"></script>
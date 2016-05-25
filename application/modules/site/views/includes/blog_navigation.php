<?php
	if(count($contacts) > 0)
	{
		$email = $contacts['email'];
		$email2 = $contacts['email'];
		$facebook = $contacts['facebook'];
		$twitter = $contacts['twitter'];
		$linkedin = $contacts['linkedin'];
		$logo = $contacts['logo'];
		$company_name = $contacts['company_name'];
		$phone = $contacts['phone'];
		
		if(!empty($facebook))
		{
			$facebook = '<li class="facebook"><a href="'.$facebook.'" target="_blank" title="Facebook">Facebook</a></li>';
		}
		
	}
	else
	{
		$email = '';
		$facebook = '';
		$twitter = '';
		$linkedin = '';
		$logo = '';
		$company_name = '';
		$google = '';
	}
?>

<!--<header class="blue lighten-2">-->
<header class="header-blog">
	<div class="container">
        <!--<nav class="blue lighten-2">-->
        <nav>
            <div class="nav-wrapper">
                <a href="<?php echo site_url();?>" class="brand-logo pull-left">
                    <img src="<?php echo base_url().'assets/logo/white.png';?>" class="responsive-img" alt="<?php echo $company_name;?>">
                </a>
                <div class="get-installs-header header-signup pull-right">
                    	<div class="install-form">
                            <input placeholder="Your website URL" id="website_url2" type="text" class="validate">
                            <button type="button" class="btn amber" id="google_signup2">
                                <!--<span class="icon"><i class="fa fa-google"></i></span>-->
                                Get more installs
                            </button>
                        </div>
                    </div>
               
            </div>
        </nav>

		<li class="divider"></li>
    </div>
</header>
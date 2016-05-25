<div class="row">
	<div class="col s12">
        <!-- title -->
        <div class="row">
            <div class="col s12">
                <h4 class="header center-align">My banners</h4>
            </div>
        </div>
        <!-- end title -->
        
        <div class="row">
        	<div class="col m10 center-align">
            	<div id="expiry_year"></div>
            </div>
        	<div class="col m2 offset-m10">
                <a href="#new_banner" class="btn grey lighten-1 modal-trigger"><i class="fa fa-plus"></i> New banner</a>
            </div>
            
        	<div class="col m12">
				<?php
                //display payments
                if($banners->num_rows() > 0)
                {
                    $count = 0;
                    ?>
                    
                    <table>
                        <thead>
                            <tr>
                                <th data-field="id">#</th>
                                <th data-field="name">Website</th>
                                <th data-field="name">Age</th>
                                <th data-field="Expiry">Status</th>
                                <th data-field="Created">Installed</th>
                                <th data-field="Default">Views</th>
                                <th data-field=""></th>
                                <th data-field=""></th>
                            </tr>
                        </thead>
                        
                        <tbody>
                    <?php
                    foreach($banners->result() as $res2)
                    {
						$smart_banner_id = $res2->smart_banner_id;
						$website_name = $res2->smart_banner_website;
						$smart_banner_status = $res2->smart_banner_status;
						$banner_installed = $res2->banner_installed;
						$smart_banner_created = $res2->smart_banner_created;
						$clicks = $this->banner_model->get_maximum_clicks($smart_banner_id);
						$age = $this->site_model->get_days($smart_banner_created);
        
                        if($smart_banner_status == 1)
                        {
                            $status = 'Active';
							$button = '<a href="'.site_url().'banner-deactivation/"'.$smart_banner_id.'" class="btn grey lighten-1">Deactivate</a>';
                        }
                        else
                        {
                            $status = 'Disabled';
							$button = '<a href="'.site_url().'banner-activation/"'.$smart_banner_id.'" class="btn grey lighten-1">Aactivate</a>';
                        }
						
						if($banner_installed == 1)
                        {
                            $installed = 'Yes';
                        }
                        else
                        {
                            $installed = 'No';
                        }
                        $count++
                        ?>
                        <tr>
                            <td><?php echo $count;?></td>
                            <td><?php echo $website_name;?></td>
                            <td><?php echo $age;?> days</td>
                            <td><?php echo $status;?></td>
                            <td><?php echo $installed;?></td>
                            <td><?php echo $clicks.'/'.$clicks;?></td>
                            <td><?php echo $button;?></td>
                            <td><a href="<?php echo site_url().'subscribe';?>" class="btn grey lighten-1">Upgrade</a></td>
                            <td><a href="<?php echo site_url().'banner/'.$website_name;?>" class="btn grey lighten-1">Edit</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                            </tbody>
                        </table>
                    <?php
                }
				
				else
				{
					?>
                    <p class="center-align">You have not added any banners.</p>
                    <?php
				}
                ?>
            </div>
			
        </div>
        
	</div>
</div>

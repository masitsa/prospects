<?php
		$result = '';
		
		//if users exist display them
		if ($dobis->num_rows() > 0)
		{
			//$count = $page;
			$count = 0;
			
			foreach ($dobis->result() as $row)
			{
				$dobi_id = $row->dobi_id;
				$dobi_first_name = $row->dobi_first_name;
				$neighbourhood_name = $row->neighbourhood_name;
				$location = $row->location;
				$street = $row->street;
				$estate = $row->estate;
				$house = $row->house;
				$fold = $row->fold;
				$iron = $row->iron;
				$deliver = $row->deliver;
				$fold_cost = $row->fold_cost;
				$iron_cost = $row->iron_cost;
				$delivery_cost = $row->delivery_cost;
				
				//iron
				if($iron == 1)
				{
					$iron = '<i class="fa fa-check"></i> + Kes '.number_format($iron_cost, 0);
				}
				else
				{
					$iron = '';
				}
				
				//deliver
				if($deliver == 1)
				{
					$deliver = '<i class="fa fa-check"></i> + Kes '.number_format($delivery_cost, 0);
				}
				else
				{
					$deliver = '';
				}
				
				//fold
				if($fold == 1)
				{
					$fold = '<i class="fa fa-check"></i> + Kes '.number_format($fold_cost, 0);
				}
				else
				{
					$fold = '';
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$dobi_first_name.'</td>
						<td>'.$neighbourhood_name.'</td>
						<td>'.$location.'</td>
						<td>'.$street.'</td>
						<td>'.$estate.'</td>
						<td>'.$house.'</td>
						<td>'.$fold.'</td>
						<td>'.$iron.'</td>
						<td>'.$deliver.'</td>
						<td>
							<a href="'.site_url().'hire-dobi/'.$dobi_id.'" class="btn btn-sm btn-success" title="Hire '.$dobi_first_name.'"><i class="fa fa-plus"></i> Select</a>
						</td>
					</tr> 
				';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no Dobis in ".$title." :-(";
		}
?>
            <div role="main" class="main shop">

				<section class="page-header">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<ul class="breadcrumb">
									<?php echo $this->site_model->get_breadcrumbs();?>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<h1><?php echo $title;?></h1>
							</div>
						</div>
					</div>
				</section>

				<div class="container">
					<div class="row">
						<div class="col-sm-6">
                            <div class="row">
                                <?php
                                $success_message = $this->session->userdata('success_message');
                                $this->session->unset_userdata('success_message');
                                
                                if(!empty($success_message))
                                {
                                    echo '<div class="alert alert-success">'.$success_message.'</div>';
                                }
                                ?>
                                
                                <div class="col-md-12">
                                    <div class="heading heading-border heading-bottom-border">
                                        <h4>Select Dobi (washer) closest to you</h4>
                                    </div>
                                </div>
                                
                                <div class="col-md-12 numbered-list">
                                    <ol>
                                        <li>Select your local area from the drop down</li>
                                        <li>Click on your preferred Dobi from the map</li>
                                        <li>Send your clothes to them</li>
                                    </ol>
                                </div>
        
                            </div>
                        </div>
                        <div class="col-sm-6">    
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="heading heading-border heading-bottom-border">
                                        <h4>Filter location</h4>
                                    </div>
                                </div>
                                
                                <?php echo form_open('filter-dobi');?>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <?php
                                                //case of an input error
                                                $js = 'class="form-control populate" data-plugin-selectTwo';
                                                $options = '';
                                                if($neighbourhoods->num_rows() > 0)
                                                {
                                                    $neighbourhoods_array = array();
                                                    $neighbourhoods_array[''] = '--All locations--';
                                                    
                                                    foreach($neighbourhoods->result() as $res)
                                                    {
                                                        $neighbourhood_id = $res->neighbourhood_id;
                                                        $neighbourhood_name = $res->neighbourhood_name;
														
														$options .= '<option value="'.$neighbourhood_id.'">'.$neighbourhood_name.'</option>';
                                                        
                                                        //$neighbourhoods_array[$neighbourhood_id] = $neighbourhood_name;
                                                    }
													echo '<select class="form-control populate" data-plugin-selecttwo name="neighbourhood_id">';
                                                    //echo form_dropdown('neighbourhood_id', $neighbourhoods_array, $neighbourhood_id, $js);
													echo $options;
													echo '</select>';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="center-align">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                                <?php echo form_close();?>
                            </div>
                        </div>
                    </div>
                
                    <div class="featured-boxes">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="featured-box featured-box-primary align-left mt-sm">
                                    <div class="box-content">
                                        <table class="table table-condensed table-responsive table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        #
                                                    </th>
                                                    <th>
                                                        Name
                                                    </th>
                                                    <th>
                                                        Neighbourhood
                                                    </th>
                                                    <th>Location</th>
                                                    <th>Street</th>
                                                    <th>Estate</th>
                                                    <th>House</th>
                                                    <th>
                                                        Folds
                                                    </th>
                                                    <th>
                                                        Irons
                                                    </th>
                                                    <th>
                                                        Delivers
                                                    </th>
                                                    <th>
                                                        
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo $result;?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

				</div>
				
                <!--<div id="map-canvas"></div>
                
                <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBp40n1V_ff69582Ev08jqCCitM4WgGUGM"></script>
    <script type="text/javascript">
		var berlin = new google.maps.LatLng(52.520816, 13.410186);
		var parliament =new google.maps.LatLng(52.511467, 13.447179);

		var neighborhoods = [,
			new google.maps.LatLng(52.549061, 13.422975),
			new google.maps.LatLng(52.497622, 13.396110),
			new google.maps.LatLng(52.517683, 13.394393)
		];
		
		var markers = [];
		var map;
		
		function initialize() {
			
			var mapOptions = {
				zoom: 13,
				center: berlin,
				//mapTypeId: google.maps.MapTypeId.SATELLITE
			};
			map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			
			marker = new google.maps.Marker({
				map:map,
				draggable:true,
				animation: google.maps.Animation.DROP,
				position: parliament,
				title: 'Hello World!'
			});
			google.maps.event.addListener(marker, 'click', toggleBounce);
			
			marker.setMap(map);
			drop();
		}
		function toggleBounce() {
		
			if (marker.getAnimation() != null) 
			{
				marker.setAnimation(null);
			} 
			else 
			{
				marker.setAnimation(google.maps.Animation.BOUNCE);
			}
		}
		
		//drop multiple markers one at a time
		function drop() {
			//clearMarkers();
			
			for (var i = 0; i < neighborhoods.length; i++) {//alert(i);
				addMarkerWithTimeout(neighborhoods[i], i * 200);
			}
		}
		
		function addMarkerWithTimeout(position, timeout) {
			window.setTimeout(function() {
				markers.push(new google.maps.Marker({
					position: position,
					map: map,
					animation: google.maps.Animation.DROP
				}));
			}, timeout);
		}
		
		//clear all markers
		function clearMarkers() {
			for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(null);
			}
			markers = [];
		}
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>-->
			</div>
<script type="text/javascript">

$(document).on("change","select#filter_neighbourhood2",function()
{
	var category_parent = $(this).val();
	
	$.ajax({
		type:'POST',
		url: '<?php echo site_url();?>customer/auth/get_neighbourhood_children2/'+category_parent,
		cache:false,
		contentType: false,
		processData: false,
		dataType: 'json',
		success:function(data)
		{	
			$("#children_section2").html(data);
		},
		error: function(xhr, status, error) 
		{
			alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		}
	});
	
	return false;
});
</script>
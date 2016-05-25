<?php
		$result = '';
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
						<div class="col-sm-12">
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
                    </div>

				</div>
				<input id="pac-input" class="form-control" type="text" placeholder="Search Box">
                <div id="map-canvas" style="height:600px;"></div>
                
                <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRL4A7M9ZGM7GIPaZqbfv67xtcPFLc2xc&libraries=places"></script>
    <script type="text/javascript">
	
		var latitude, longitude;
		latitude = -1.2920659;
		longitude = 36.82194619999996;
		// Try HTML5 geolocation.
		if (navigator.geolocation) 
		{
			navigator.geolocation.getCurrentPosition(function(position) 
			{
				latitude = position.coords.latitude;
				longitude = position.coords.longitude;
			}, function() {
				handleLocationError(true, infoWindow, map.getCenter());
			});
		} 
		else {
			// Browser doesn't support Geolocation
			handleLocationError(false, infoWindow, map.getCenter());
		}
		
		//set default location
		var default_location = new google.maps.LatLng(latitude, longitude);
		
		var markers = [];
		var map;
			
		var mapOptions = {
			zoom: 13,
			center: default_location,
			//mapTypeId: google.maps.MapTypeId.SATELLITE
		};
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		
		function initialize() {
			
			/*marker = new google.maps.Marker({
				map:map,
				draggable:true,
				animation: google.maps.Animation.DROP,
				position: default_location,
				title: 'Hello World!'
			});
			google.maps.event.addListener(marker, 'click', toggleBounce);
			
			marker.setMap(map);*/
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
			
			/*for (var i = 0; i < neighborhoods.length; i++) {//alert(i);
				addMarkerWithTimeout(neighborhoods[i], i * 200);
			}*/
			
			//get dobis
			$.ajax({
				type:'POST',
				url: '<?php echo site_url();?>site/all_dobis',
				cache:false,
				contentType: false,
				processData: false,
				dataType: 'json',
				statusCode: {
					302: function() {
						window.location.href = '<?php echo site_url();?>error';
					}
				},
				success:function(data){
					
					if(data.message == "success")
					{
						var arr = $.map(data.result, function(el) { return el; });
						
						for (var i = 0; i < arr.length; i++)
						{
							var longitude = arr[i].longitude;
							var latitude = arr[i].latitude;
							var dobi = arr[i].dobi_first_name;
							var dobi_id = arr[i].dobi_id;
							var fold = arr[i].fold;
							var iron = arr[i].iron;
							var deliver = arr[i].deliver;
							var street = arr[i].street;
							var estate = arr[i].estate;
							var house = arr[i].house;
							
							if(fold == '1')
							{
								fold = 'folds, ';
							}
							else
							{
								fold = '';
							}
							
							if(iron == '1')
							{
								iron = 'irons, ';
							}
							else
							{
								iron = '';
							}
							
							if(deliver == '1')
							{
								deliver = 'delivers, ';
							}
							else
							{
								deliver = '';
							}
							
							var after_service = fold+iron+deliver;
							var dobi_location = street+' '+estate+' '+house;
							
							if(!longitude || longitude == '')
							{
							}
							
							else
							{
								longitude = parseFloat(longitude).toFixed(16);
								latitude = parseFloat(latitude).toFixed(16);
								
								addMarkerWithTimeout(new google.maps.LatLng(latitude, longitude), i * 200, dobi, dobi_id, after_service, dobi_location);
								
							}
						}
						
						//alert(markers.length);
						//add event listener
						for (var r = 0; r < markers.length; r++) {
							//markers[i].setMap(null);
							google.maps.event.addListener(markers[r], 'click', function() { 
							   alert("I am marker " + markers.title); 
							});
						}
					}
					else
					{
						console.log(data);
					}
				},
				error: function(xhr, status, error) {
					console.log("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				}
			});
		}
		
		function addMarkerWithTimeout(position, timeout, dobi, dobi_id, after_service, dobi_location) {
			window.setTimeout(function() {
				
				//create marker
				var marker = new google.maps.Marker({
									position: position,
									map: map,
									animation: google.maps.Animation.DROP,
									title: dobi
								});
				
				//add marker description
				var contentString = '<span itemprop="streetAddress">'+dobi+'</span><br/><span itemprop="addressLocality">'+dobi_location+'</span><br/><span itemprop="addressLocality">'+after_service+'</span>';
    			var infowindow = new google.maps.InfoWindow({
					content: contentString
				});
       			infowindow.open(map,marker);
				
				//on click listener;
				marker.addListener('click', function() 
				{
					infowindow.open(map,marker);
					var title = marker.getTitle();
					var conf = confirm('Are you sure you want to select '+title+' as your Dobi?');
					
					if(conf)
					{
						window.location.href = '<?php echo site_url();?>hire-dobi/'+dobi_id;
					}
				  });
				/*markers.push(new google.maps.Marker({
					position: position,
					map: map,
					animation: google.maps.Animation.DROP,
					title: dobi
				}));*/
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
	  
	  // Create the search box and link it to the UI element.
	  var input = document.getElementById('pac-input');
	  var searchBox = new google.maps.places.SearchBox(input);
	  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
	
	  // Bias the SearchBox results towards current map's viewport.
	  map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
	  });
	
	  var markers = [];
	  // Listen for the event fired when the user selects a prediction and retrieve
	  // more details for that place.
	  searchBox.addListener('places_changed', function() {
		var places = searchBox.getPlaces();
	
		if (places.length == 0) {
		  return;
		}
	
		// Clear out the old markers.
		markers.forEach(function(marker) {
		  marker.setMap(null);
		});
		markers = [];
	
		// For each place, get the icon, name and location.
		var bounds = new google.maps.LatLngBounds();
		places.forEach(function(place) {
		  var icon = {
			url: place.icon,
			size: new google.maps.Size(71, 71),
			origin: new google.maps.Point(0, 0),
			anchor: new google.maps.Point(17, 34),
			scaledSize: new google.maps.Size(25, 25)
		  };
	
		  // Create a marker for each place.
		  markers.push(new google.maps.Marker({
			map: map,
			icon: icon,
			title: place.name,
			position: place.geometry.location
		  }));
	
		  if (place.geometry.viewport) {
			// Only geocodes have viewport.
			bounds.union(place.geometry.viewport);
		  } else {
			bounds.extend(place.geometry.location);
		  }
		});
		map.fitBounds(bounds);
		drop();
	  });
	  
	  
    </script>
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
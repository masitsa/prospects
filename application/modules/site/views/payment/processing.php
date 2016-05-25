
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
                        
                        <div class="col-md-12">
                            <div class="heading heading-border heading-bottom-border">
                                <h4>Processing payment</h4>
                            </div>
                        
                            <div class="alert alert-info">
                                <p class="center-align">Please wait while we process your payment</p>
                            </div>
                        </div>
                        
						<div class="col-md-12">
						<?php
						$success_message = $this->session->userdata('success_message');
						$this->session->unset_userdata('success_message');
						
						if(!empty($success_message))
						{
							echo '<div class="alert alert-success">'.$success_message.'</div>';
						}
						
						$error_message = $this->session->userdata('error_message');
						$this->session->unset_userdata('error_message');
						if(!empty($error_message))
						{
							echo '<div class="alert alert-danger">'.$error_message.'</div>';
						}
						?>

						<div class="center-align" id="processing_payment">
                            <img src="<?php echo base_url();?>assets/img/332.GIF">
                            <input type="hidden" id="check_count" value="0" />
                        </div>
					</div>

				</div>

			</div>

<script type="text/javascript">
	$( document ).ready(function() 
	{
		(function worker() {
			
			var check_count = parseInt($('#check_count').val());
			$.ajax({
				type:'POST',
				url: '<?php echo site_url();?>site/subscription/check_payment/'+check_count+'/<?php echo $transaction_tracking_id;?>/<?php echo $order_id;?>',
				cache:false,
				contentType: false,
				processData: false,
				success:function(data)
				{
					if(data == 'true')
					{
						window.location.href = '<?php echo site_url();?>customer-account';
					}
					
					else
					{
						$('#check_count').val(data);
					}
				},
				error: function(xhr, status, error) 
				{
					console.log("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				},
				complete: function() 
				{
					// Schedule the next request when the current one's complete
					setTimeout(worker, 3000);
				}
			});
				
		})();
	});
</script>
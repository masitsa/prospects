<?php
$res = $invoices->row();
$invoice_id = $res->invoice_id;
$invoice_date = date('jS M Y',strtotime($res->invoice_date));
$invoice_status_name = $res->invoice_status_name;
$member_first_name = $res->member_first_name;
$member_surname = $res->member_surname;
$invoice_status = $res->invoice_status;
$invoice_number = $res->invoice_number;
$invoice_items = $this->invoices_model->get_invoice_items($invoice_id);

$button2 = $display_invoice_items = '';
if($invoice_status == 0)
{
	$button2 = '<span class="label label-danger">'.$invoice_status_name.'</span>';
}
else
{
	$button2 = '<span class="label label-success">'.$invoice_status_name.'</span>';
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Institute of Directors Kenya | Invoice</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/";?>bootstrap/css/bootstrap.css" media="all"/>
        <style type="text/css">
			.receipt_spacing{letter-spacing:0px; font-size: 12px;}
			.center-align{margin:0 auto; text-align:center;}
			
			.receipt_bottom_border{border-bottom: #888888 medium solid;}
			.row .col-md-12 table {
				border:solid #000 !important;
				border-width:1px 0 0 1px !important;
				font-size:10px;
			}
			.row .col-md-12 th, .row .col-md-12 td {
				border:solid #000 !important;
				border-width:0 1px 1px 0 !important;
			}
			.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td
			{
				 padding: 2px;
			}
			
			.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
			.title-img{float:left; padding-left:30px;}
			img.logo{max-height:70px; margin:0 auto;}
		</style>
    </head>
    <body class="receipt_spacing">
    	<div class="row">
        	<div class="col-xs-12">
            	<img src="<?php echo base_url().'assets/logo/iod.png';?>" alt="Institute of Directors Kenya" class="img-responsive logo"/>
            </div>
        </div>
    	<div class="row">
        	<div class="col-md-12 center-align receipt_bottom_border">
            	<strong>
                	Institute of Directors Kenya<br/>
                    All Africa Conference of Churches<br/>
                    Waiyaki Way, Westlands<br/>
                    Bishop Josiah Kibira House, Rm 22<br/>
                    
                    P.O. Box 13490 - 00800<br/>
                    Nairobi, Kenya<br/>
                    E-mail: info@iodkenya.co.ke. Tel : (+254) 020-2190131	Mobile (+254) 0703516285 / 0718759918<br/>
                </strong>
            </div>
        </div>
        
      	<div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
            	<strong>INVOICE</strong>
            </div>
        </div>
        
        <!-- Patient Details -->
    	<div class="row receipt_bottom_border" style="margin-bottom: 10px;">
        	<div class="col-md-4 pull-left">
            	<div class="row">
                	<div class="col-md-12">
                    	
                    	<div class="title-item">member:</div>
                        
                    	<?php echo $member_first_name.' '.$member_surname; ?>
                    </div>
                </div>
            
            </div>
            
        	<div class="col-md-4">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice Number:</div>
                    	<?php echo $invoice_number; ?>
                    </div>
                </div>
            </div>
            
        	<div class="col-md-4 pull-right">
            	<div class="row">
                	<div class="col-md-12">
                    	<div class="title-item">Invoice Date:</div>
                        
                    	<?php echo $invoice_date; ?>
                    </div>
                </div>
            </div>
        </div>
        
    	<div class="row receipt_bottom_border">
        	<div class="col-md-12 center-align">
            	<strong>BILLED ITEMS</strong>
            </div>
        </div>
        
    	<div class="row">
        	<div class="col-md-12">
            	<table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Amount</th>
                        <th>Units</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
            	<?php
                if($invoice_items->num_rows() > 0)
				{
					$counter = 0;
					$total = 0;
					foreach($invoice_items->result() as $row2)
					{
						$invoice_item_amount = $row2->invoice_item_amount;
						$invoice_item_description = $row2->invoice_item_description;
						$units = 1;
						$counter++;
						$total+=$invoice_item_amount;
						$display_invoice_items .= '
						<tr>
							<td>'.$counter.'</td>
							<td>'.$invoice_item_description.'</td>
							<td>'.number_format($invoice_item_amount).'</td>
							<td>'.$units.'</td>
							<td>'.number_format($invoice_item_amount).'</td>
						</tr>
						';
					}
					$display_invoice_items .= '
					<tr>
						<th colspan="4">Total</th>
						<td>'.number_format($total).'</td>
					</tr>
					';
				}
				echo $display_invoice_items;
				?>
                    </tbody>
                </table>
            </div>
        </div>
        
    	<div class="row" style="font-style:italic; font-size:11px;">
        	<div class="col-md-2 pull-right">
            	<?php echo date('jS M Y H:i a'); ?> Thank you
            </div>
        </div>
    </body>
    
</html>
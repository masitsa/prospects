<?php	
	$attendees = '<table class="table table-striped table-hover table-condenses">
		<tr>
			<td>#</td>
			<td>First Name</td>
			<td>Middle Name</td>
			<td>Last Name</td>
			<td>Company</td>
			<td>Role</td>
			<td>Email</td>
			<td>Phone</td>
			<td>Registration Date</td>
		</tr>
	';		
	$beginner = '';$intermediate = '';$advanced = '';
	$beginner_total = $intermediate_total = $advanced_total = 0;
	//display results for individuals
	if($trainees->num_rows() > 0)
	{
		$count = 0;
		foreach($trainees->result() as $res)
		{
			$trainee_id = $res->trainee_id;
			$trainee_fname = $res->trainee_fname;
			$trainee_mname = $res->trainee_mname;
			$trainee_lname = $res->trainee_lname;
			$trainee_company = $res->trainee_company;
			$trainee_role = $res->trainee_role;
			$trainee_email = $res->trainee_email;
			$trainee_phone = $res->trainee_phone;
			$created = $res->created;
			$last_modified = $res->last_modified;
			$count++;
			
			$attendees .= '
				<tr>
					<td>'.$count.'</td>
					<td>'.$trainee_fname.'</td>
					<td>'.$trainee_mname.'</td>
					<td>'.$trainee_lname.'</td>
					<td>'.$trainee_company.'</td>
					<td>'.$trainee_role.'</td>
					<td>'.$trainee_email.'</td>
					<td>'.$trainee_phone.'</td>
					<td>'.date('jS M Y H:i:a',strtotime($created)).'</td>
				</tr>
			';
		}
	}
	$attendees .= '</table>';
?>

<div class="row statistics">
	<div class="col-md-4 col-lg-4 col-xl-4">
        <section class="panel panel-featured-left panel-featured-primary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-primary">
                            <i class="fa fa-life-ring"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Total Attendees</h4>
                            <div class="info">
                                <strong class="amount"><?php echo $trainees->num_rows();?></strong>
                                <span class="text-primary">(1 new)</span>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <a class="text-muted text-uppercase">(view all)</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-4 col-lg-4 col-xl-4">
        <section class="panel panel-featured-left panel-featured-secondary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-secondary">
                            <i class="fa fa-usd"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Total Collected</h4>
                            <div class="info">
                                <strong class="amount">Kes 14,890</strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <a class="text-muted text-uppercase">(withdraw)</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-4 col-lg-4 col-xl-4">
        <section class="panel panel-featured-left panel-featured-tertiary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-tertiary">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Today's Bookings</h4>
                            <div class="info">
                                <strong class="amount">38</strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <a class="text-muted text-uppercase">(statement)</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="row">
	<div class="col-md-12">
    	<?php echo $attendees;?>
    </div>
</div>
<h3 class="header center-align">Dashboard</h3>

<?php echo $this->load->view('user/dashboard/summary', '', TRUE);?>
<!-- Today status ends -->

<div class="row">
	<div class="col m12">
	<?php echo $this->load->view('user/dashboard/line_graph', '', TRUE);?>
	</div>
</div>  

<div class="row">
	<div class="col m12">
	<?php echo $this->load->view('user/dashboard/bar_graph', '', TRUE);?>
	</div>
</div>  

<!-- Full Google Calendar - Calendar -->
<script src="<?php echo base_url()."assets/themes/bluish/"?>js/fullcalendar.min.js"></script> 
<!-- jQuery Flot -->
<script src="<?php echo base_url()."assets/themes/bluish/"?>js/excanvas.min.js"></script>
<script src="<?php echo base_url()."assets/themes/bluish/"?>js/jquery.flot.js"></script>
<script src="<?php echo base_url()."assets/themes/bluish/"?>js/jquery.flot.resize.js"></script>
<script src="<?php echo base_url()."assets/themes/bluish/"?>js/jquery.flot.axislabels.js"></script>
<script src="<?php echo base_url()."assets/themes/bluish/"?>js/jquery.flot.pie.js"></script>
<script src="<?php echo base_url()."assets/themes/bluish/"?>js/jquery.flot.stack.js"></script>
<?php 
	$total_clicks_android = number_format($this->reports_model->get_total_clicks('android'), 0, '.', ',');
	$total_clicks_ios = number_format($this->reports_model->get_total_clicks('ios'), 0, '.', ',');
	$total_clicks_windows = number_format($this->reports_model->get_total_clicks('windows'), 0, '.', ',');
 ?>
 <div class="row">
    <div class="col l4 m6 s12">
        <section class="panel panel-featured-left panel-featured-tertiary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-tertiary">
                            <i class="fa fa-android"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Android clicks</h4>
                            <div class="info">
                                <strong class="amount"><?php echo $total_clicks_android;?></strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <!--<a class="text-muted text-uppercase">(statement)</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col l4 m6 s12">
        <section class="panel panel-featured-left panel-featured-quartenary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-quartenary">
                            <i class="fa fa-apple"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">IOS clicks</h4>
                            <div class="info">
                                <strong class="amount"><?php echo $total_clicks_ios;?></strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <!--<a class="text-muted text-uppercase" href="<?php echo base_url()."data/reports/patients.php";?>">(report)</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col l4 m6 s12">
        <section class="panel panel-featured-left panel-featured-secondary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-secondary">
                            <i class="fa fa-windows"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Windows clicks</h4>
                            <div class="info">
                                <strong class="amount"><?php echo $total_clicks_windows;?></strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <!--<a class="text-muted text-uppercase">(withdraw)</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
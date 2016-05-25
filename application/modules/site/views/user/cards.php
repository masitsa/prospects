<div class="row">
	<div class="col s12">
        <!-- title -->
        <div class="row">
            <div class="col s12">
                <h4 class="header center-align">My cards</h4>
            </div>
        </div>
        <!-- end title -->
        
        <div class="row">
        	<div class="col m10 center-align">
            	<div id="expiry_year"></div>
            </div>
        	<div class="col m2 offset-m10">
            	<a class="waves-effect waves-light btn modal-trigger blue lighten-2" href="#newcard"><i class="fa fa-plus"></i> Add card</a>
            </div>
            
        	<div class="col m12">
				<?php
                //display payments
                if($cards->num_rows() > 0)
                {
                    $count = 0;
                    ?>
                    
                    <table>
                        <thead>
                            <tr>
                                <th data-field="id">#</th>
                                <th data-field="name">Card number</th>
                                <th data-field="Expiry">Expiry</th>
                                <th data-field="Created">Created</th>
                                <th data-field="Default">Default</th>
                                <th data-field=""></th>
                            </tr>
                        </thead>
                        
                        <tbody>
                    <?php
                    foreach($cards->result() as $res2)
                    {
                        $card_id = $res2->card_id;
                        $card_number = $res2->card_number;
						$card_number2 = 'xxxx xxxx xxxx '.substr($card_number, -4);
                        $card_expiry_year = $res2->card_expiry_year;
                        $card_expiry_month = $res2->card_expiry_month;
                        $card_cvc = $res2->card_cvc;
                        $created = $res2->created;
                        $card_default = $res2->card_default;
        
                        if($card_default == 1)
                        {
                            $card_default = '<span class="blue-text">Default</span>';
							$button = '';
                        }
                        else
                        {
                            $card_default = '';
							$button = '<a id="update_default_card" href="'.$card_id.'" class="waves-effect waves-light btn btn-small blue lighten-2">Set default</a>';
                        }
                        $count++
                        ?>
                        <tr>
                            <td><?php echo $count;?></td>
                            <td><?php echo $card_number2;?></td>
                            <td><?php echo $card_expiry_month.'/'.$card_expiry_year;?></td>
                            <td><?php echo date('jS M Y',strtotime($created));?></td>
                            <td><?php echo $card_default;?></td>
                            <td>
                            	<input type="hidden" id="card_number<?php echo $card_id;?>" value="<?php echo $card_number;?>" />
                            	<input type="hidden" id="card_cvc<?php echo $card_id;?>" value="<?php echo $card_cvc;?>" />
                            	<input type="hidden" id="card_expiry_year<?php echo $card_id;?>" value="<?php echo $card_expiry_year;?>" />
                            	<input type="hidden" id="card_expiry_month<?php echo $card_id;?>" value="<?php echo $card_expiry_month;?>" />
								<?php echo $button;?>
                            </td>
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
                    <p class="center-align">You have not added any cards.</p>
                    <?php
				}
                ?>
            </div>
			
        </div>
        
	</div>
</div>

<!-- New card modal -->
<div id="newcard" class="modal modal-fixed-footer">
    <form class="form-horizontal sidebar_form" id="add_new_card" method="POST" action="<?php echo site_url().'site/account/add_card'?>">
        <div class="modal-content">
            <h4 class="header center-align">Add card</h4>
            <div class="row">
                <div class="input-field col m6">
                    <input type="text" name="card_number" id="card_number" size="20" autocomplete="off">
                    <label for="card_number">Card number <span class="required">*</span></label>
                </div>
                <div class="input-field col m6">
                    <input type="text"  name="card_cvc" id="card_cvc" size="4" autocomplete="off">
                    <label for="card_cvc">CVC <span class="required">*</span></label>
                </div>
                <div class="input-field col m6">
                    <input type="text"  name="card_expiry_month" id="card_expiry_month" size="2">
                    <label for="card_expiry_month">Expiry month (MM)<span class="required">*</span></label>
                </div>
                <div class="input-field col m6">
                    <input type="text"  name="card_expiry_year" id="card_expiry_year" size="4">
                    <label for="card_expiry_year">Expiry year (YYYY)<span class="required">*</span></label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div id="expiry_year" class="center-align"></div>
            <div class="row">
                <div class="col m6">
                    <button type="button" class="modal-action modal-close waves-effect waves-green btn red" data-dismiss="modal">Close</button>
                </div>
                <div class="col m6">
                    <button type="submit" class="waves-effect waves-green btn blue" id="add_banner">Add card</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End new card modal -->
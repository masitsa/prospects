
<div class="panel panel-default">
    <div class="panel-heading"> Upload documents </div>
    <div class="panel-body">
    	<?php echo form_open_multipart('member/upload_documents/'.$member_id, array("class" => "form-horizontal", "role" => "form"));?>
    
	    <div class="row" style="margin-top:10px;">
	        <div class="col-md-12">
	        	<div class="col-md-4">
		            <div class="form-group">
		                <label class="col-md-6 control-label ">Document Name *: </label>
		                <div class="col-md-6">
		                    <input type="text" class="form-control " name="document_item_name"  placeholder="Document Title Name">
		                </div>
		            </div>
		        </div>
	        	<div class="col-md-5">
		            <div class="form-group">
		                <label class="col-md-6 control-label ">Document Scan *: </label>
		                
		                <div class="col-md-6">
		                    <input type="file" name="document_scan"  value="">
		                </div>
		            </div>
		        </div>
	        	<div class="col-md-3">
                	<button class="submit btn btn-primary" type="submit">
	                    Upload document scan
	                </button>
		        </div>
	        </div>
	    </div>
		<?php echo form_close();?>
	</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"> Uploaded documents </div>
    <div class="panel-body">
		<div class="row" style="margin-top:10px;">
	   		<div class="col-md-12">
		       <?php
		       if($uploads->num_rows() > 0)
		       {
		            $count = 0;
		                
		            $identification_result = 
		            '
		            <table class="table table-bordered table-striped table-condensed">
		                <thead>
		                    <tr>
		                        <th>#</th>
		                        <th>Document Name</th>
		                        <th>Download Link</th>
		                        <th colspan="3">Actions</th>
		                    </tr>
		                </thead>
		                  <tbody>
		                  
		            ';
		            
		            foreach ($uploads->result() as $row)
		            {
		                $document_upload_id = $row->document_upload_id;
		                $document_name = $row->document_name;
		                $document_upload_name = $row->document_upload_name;
		                $document_status = $row->document_status;
		                
		                //create deactivated status display
		                if($document_status == 0)
		                {
		                    $status = '<span class="label label-default">Deactivated</span>';
		                    $button = '<a class="btn btn-info" href="'.site_url().'microfinance/activate-member-identification/'.$document_upload_id.'/'.$member_id.'" onclick="return confirm(\'Do you want to activate?\');" title="Activate "><i class="fa fa-thumbs-up"></i></a>';
		                }
		                //create activated status display
		                else if($document_status == 1)
		                {
		                    $status = '<span class="label label-success">Active</span>';
		                    $button = '<a class="btn btn-default" href="'.site_url().'microfinance/deactivate-member-identification/'.$document_upload_id.'/'.$member_id.'" onclick="return confirm(\'Do you want to deactivate ?\');" title="Deactivate "><i class="fa fa-thumbs-down"></i></a>';
		                }
		                
		                $count++;
		                $identification_result .= 
		                '
		                    <tr>
		                        <td>'.$count.'</td>
		                        <td>'.$document_name.'</td>
		                        <td><a href="'.$this->uploads_location.''.$document_upload_name.'" target="_blank" >Download Here</a></td>
		                        <td>'.$status.'</td>
		                        <!--<td>'.$button.'</td>-->
		                        <td><a href="'.site_url().'member/delete_document_scan/'.$document_upload_name.'/'.$document_upload_id.'/'.$member_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete ?\');" title="Delete"><i class="fa fa-trash"></i></a></td>
		                    </tr> 
		                ';
		            }
		            
		            $identification_result .= 
		            '
		                          </tbody>
		                        </table>
		            ';
		        }
		        
		        else
		        {
		            $identification_result = "<p>No documents have been uploaded</p>";
		        }
		        echo $identification_result;
		       ?>
	       </div>
       </div>
	</div>
</div>
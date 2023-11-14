<?php
/*
* overtime Detail view
*/
$session = $this->session->userdata('username');
?>
<?php $get_animate = $this->Core_model->get_content_animate();?>

<div class="row m-b-1 <?php echo $get_animate;?>">
  
  <div class="col-md-4 <?php echo $get_animate;?>">

          <div class="box">
            <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_overtime_details');?> </h3>
          </div>
          <div class="card-header">
            </div>
            <div class="box-body">
              <div class="box-block box-dashboard">
                <div class="table-responsive" data-pattern="priority-columns">
                  <table class="datatables-demo table table-striped table-bordered">
                    <tbody>
                     
                      <tr>
                        <th scope="row"><?php echo $this->lang->line('xin_e_details_create_date');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($created_at);?></td>
                      </tr>
                     
                      <tr>
                        <th scope="row"><?php echo $this->lang->line('xin_e_details_date_overtime');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($attendance_date_m);?></td>
                      </tr>

                      <tr>
                        <th scope="row"><?php echo $this->lang->line('xin_reports_overtime');?></th>                        
                        <td class="text-right"><?php echo $manager_name;?></td>
                      </tr>

                       <tr>
                        <th scope="row" style="border-top: 0px;"><?php echo $this->lang->line('left_overtime_type');?></th>
                        <td class="text-right"><?php echo $type;?></td>
                      </tr>

                       <tr>
                        <th scope="row" colspan="2">Lembur 1</th>                        
                      </tr>

                      <tr>
                        <th scope="row"><?php echo $this->lang->line('xin_in_time');?></th>
                        <td class="text-right"><?php echo $clock_in_m;?></td>
                      </tr>
                      
                      <tr>
                        <th scope="row"><?php echo $this->lang->line('xin_out_time');?></th>
                        <td class="text-right"><?php echo $clock_out_m;?></td>
                      </tr>

                      

                       <tr>
                        <th scope="row" colspan="2">Lembur 2</th>                        
                      </tr>

                       <tr>
                        <th scope="row"><?php echo $this->lang->line('xin_in_time');?></th>
                        <td class="text-right"><?php echo $clock_in_n;?></td>
                      </tr>
                      
                      <tr>
                        <th scope="row"><?php echo $this->lang->line('xin_out_time');?></th>
                        <td class="text-right"><?php echo $clock_out_n;?></td>
                      </tr>
                      
                       <tr>
                        <th scope="row" colspan="2"><?php echo $ov_status;?></th>                        
                      </tr>
                      
                      <?php if($overtime_status=='2'){?>
                      <tr>
                        <th scope="row"><?php echo $this->lang->line('dashboard_xin_status');?></th>
                        <td class="text-right"><?php echo '<span class="badge bg-green">'.$this->lang->line('xin_completed').'</span>';?></td>
                      </tr>
                      <?php }?>
                
                          <?php $count_module_attributes = $this->Custom_fields_model->count_overtime_module_attributes();?>
						              <?php $module_attributes       = $this->Custom_fields_model->overtime_hris_module_attributes();?>
                          <?php foreach($module_attributes as $mattribute):?>
                          <?php $attribute_info = $this->Custom_fields_model->get_employee_custom_data($overtime_id,$mattribute->custom_field_id);?>
                          <?php
                                if(!is_null($attribute_info)){
                                    $attr_val = $attribute_info->attribute_value;
                                } else {
                                    $attr_val = '';
                                }
                            ?>
                          <?php if($mattribute->attribute_type == 'date'){?>
                            <tr>
                                <th><?php echo $mattribute->attribute_label;?></th>
                                <td style="display: table-cell;"><?php echo $attr_val;?></td>
                            </tr>
                          
                          <?php } else if($mattribute->attribute_type == 'select'){?>
                          
                              <?php $iselc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                              <tr>
                                    <th><?php echo $mattribute->attribute_label;?></th>
                                    <td style="display: table-cell;"><?php foreach($iselc_val as $selc_val) {?> <?php if($attr_val==$selc_val->attributes_select_value_id):?> <?php echo $selc_val->select_label?> <?php endif;?><?php } ?></td>
                              </tr>
                            
                          <?php } else if($mattribute->attribute_type == 'multiselect'){?>
                          
                              <?php $multiselect_values = explode(',',$attr_val);?>
                              <?php $imulti_selc_val = $this->Custom_fields_model->get_attribute_selection_values($mattribute->custom_field_id);?>
                              <tr>
                                    <th><?php echo $mattribute->attribute_label;?></th>
                                    <td style="display: table-cell;"><?php foreach($imulti_selc_val as $multi_selc_val) {?> <?php if(in_array($multi_selc_val->attributes_select_value_id,$multiselect_values)):?><br /> <?php echo $multi_selc_val->select_label?> <?php endif;?><?php } ?></td>
                              </tr>
                          
                          <?php } else if($mattribute->attribute_type == 'textarea'){?>
                              <tr>
                                    <th><?php echo $mattribute->attribute_label;?></th>
                                    <td style="display: table-cell;"><?php echo $attr_val;?></td>
                              </tr>
                          <?php } else if($mattribute->attribute_type == 'fileupload'){?>
                              <tr>
                                    <th><?php echo $mattribute->attribute_label;?></th>
                                    <td style="display: table-cell;"><?php if($attr_val!='' && $attr_val!='no file') {?>
                                  <img src="<?php echo base_url().'uploads/custom_files/'.$attr_val;?>" width="70px" id="u_file">&nbsp; <a href="<?php echo site_url('admin/download');?>?type=custom_files&filename=<?php echo $attr_val;?>"><?php echo $this->lang->line('xin_download');?></a>
                                  <?php } ?></td>
                              </tr>
                          <?php } else { ?>
                              <tr>
                                    <th><?php echo $mattribute->attribute_label;?></th>
                                    <td style="display: table-cell;"><?php echo $attr_val;?></td>
                              </tr>
                          <?php } ?>
                          
                          <?php endforeach;?>
                    </tbody>
                  </table>


                  <?php if($description!='' && $description!='<p><br></p>'):?>
                  <div class="bs-callout-success callout-border-left callout-square callout-transparent mt-1 p-1"> <?php echo $description;?> </div>
                  <?php endif;?>
                </div>
              </div>
            </div>
          </div>
  </div>

  <div class="col-md-8 <?php echo $get_animate;?>">
          <div class="box">
              <div class="box-header with-border">
            <h3 class="box-title"> <?php echo $this->lang->line('xin_details');?> </h3>
          </div>
            <div class="box-body">
              <div class="box-block card-dashboard">
                <div class="row">
                    
                    <div class="col-md-6">
                      <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo $this->lang->line('xin_overtime_employees_s');?> </h3>
                      </div>
                      <div class="media-list" id="all_employees_list">
                      <ul class="list-group list-group-flush">
                        <?php if($employee_id!='') { ?>
                                
                                <?php $employee_ids = explode(',',$employee_id); foreach($employee_ids as $assign_id) {?>

                                      <?php $e_name       = $this->Core_model->read_user_info($assign_id);?>
                                      
                                      <?php if(!is_null($e_name)){ ?>
                                          
                                          <?php $_designation = $this->Designation_model->read_designation_information($e_name[0]->designation_id);?>
                                          
                                          <?php
                              						  if(!is_null($_designation)){
                              							   $designation_name = $_designation[0]->designation_name;
                              						  } else {
                              							   $designation_name = '--';	
                              						  }
                              						?>
                                          
                                          <?php
                                							if($e_name[0]->profile_picture!='' && $e_name[0]->profile_picture!='no file') {

                                								  $u_file = base_url().'uploads/profile/'.$e_name[0]->profile_picture;
                                							
                                              } else {
                                  						
                                              		if($e_name[0]->gender=='Male') { 
                                  						  			$u_file = base_url().'uploads/profile/default_male.jpg';
                                  								} else {
                                  							   		$u_file = base_url().'uploads/profile/default_female.jpg';
                                  								}
                                							} 
                                          ?>
                                      	  
                                          <li class="list-group-item" style="border:0px;">
                                            <div class="media align-items-center"> <img src="<?php echo $u_file;?>" class="user-image-hr-prj ui-w-30 rounded-circle" alt="">
                                              <div class="media-body px-2">                                    
                                                <!-- <a href="<?php echo site_url()?>admin/employees/detail/<?php echo $e_name[0]->user_id;?>" class="text-dark"> -->
                                                     <?php echo $e_name[0]->first_name.' '.$e_name[0]->last_name;?>
                                                <!-- </a> -->
                                                <br>
                                                <p class="font-small-2 mb-0 text-muted"><?php echo $designation_name;?></p>
                                              </div>
                                            </div>
                                          </li>
                                         
                                      <?php } ?>

                              <?php } ?>

                        <?php } else { ?>
                              <li class="list-group-item" style="border:0px;">&nbsp;</li>
                        <?php } ?>
                       </ul> 
                      </div>
                    </div>

                    <div class="col-md-6">
                        <?php $attributes = array('name' => 'update_status_bulanan', 'id' => 'update_status_bulanan', 'autocomplete' => 'off');?>
      				          <?php $hidden = array('user_id' => $session['user_id']);?>
                        <?php echo form_open('admin/overtime_bulanan/update_status_bulanan', $attributes, $hidden);?>
                        <?php
                					$data = array(
                					  'name'        => 'token_status',
                					  'id'          => 'token_status',
                					  'type'        => 'hidden',
                					  'value'   	   => $overtime_id,
                					  'class'       => 'form-control',
                					);
                					echo form_input($data);
                					?>
                          <div class="box-header with-border">
                              <h3 class="box-title"> <?php echo $this->lang->line('xin_update_status');?> </h3>
                          </div>
                         <br>
                          <div class="clearfix margin-top-10"></div>

                          <div class="clearfix margin-bottom-10"></div>

                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                                  <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                                    <option value="1" <?php if($overtime_status=='1'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_pending');?></option>
                                    <option value="2" <?php if($overtime_status=='2'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_approved');?></option>
                                    <option value="3" <?php if($overtime_status=='3'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_rejected');?></option>
                                  </select>
                               
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="status"><?php echo $this->lang->line('xin_remarks');?></label>
                                <textarea class="form-control" name="remarks" rows="4" cols="15" placeholder="<?php echo $this->lang->line('xin_remarks');?>"><?php echo $remarks;?></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="form-actions box-footer">

                             <button type="reset" class="btn btn-default" onclick="location.href='<?php echo base_url();?>/admin/overtime_bulanan'"  > <i class="fa fa-power-off "></i> <?php echo $this->lang->line('xin_close');?> </button>
                            <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
                          </div>
                        <?php echo form_close(); ?>
                    </div>

                    <div>&nbsp;</div>

                </div>
              </div>
              <!-- tab --> 
            </div>
          </div>
  </div>

</div>

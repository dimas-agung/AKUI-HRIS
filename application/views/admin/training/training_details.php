<?php
/*
* Training Detail view
*/
$session = $this->session->userdata('username');
?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<div class="row m-b-1 <?php echo $get_animate;?>">
  <div class="col-md-4 <?php echo $get_animate;?>">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"> <?php echo $this->lang->line('xin_training_details');?> </h3>
        </div>
        <div class="card-header"></div>
        <div class="box-body">
          <div class="box-block box-dashboard">
            <div class="table-responsive" data-pattern="priority-columns">
              <table class="datatables-demo table table-striped table-bordered">
                <tbody>
                  <tr>
                    <th scope="row" style="border-top: 0px;" class="text-right">Jenis Pelatihan : </th>
                    <td class="text-right"><?php echo $type;?></td>
                  </tr>
                  <?php $user = $this->Core_model->read_user_info($session['user_id']); ?>
		  	        
                  <tr>
                    <th scope="row" class="text-right"><?php echo $this->lang->line('xin_trainer');?> : </th>
                    <td class="text-right"><?php echo $trainer_name;?></td>
                  </tr>

                  <tr>
                    <th scope="row" class="text-right"><?php echo $this->lang->line('xin_start_date');?> :</th>
                    <td class="text-right"> <?php echo $this->Core_model->set_date_format($start_date);?></td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-right"><?php echo $this->lang->line('xin_end_date');?> : </th>
                    <td class="text-right"> <?php echo $this->Core_model->set_date_format($finish_date);?></td>
                  </tr>
                  <tr>
                    <th scope="row" class="text-right"><?php echo $this->lang->line('xin_e_details_date');?> : </th>
                    <td class="text-right"><?php echo $this->Core_model->set_date_format($created_at);?></td>
                  </tr>
                  <?php if($training_status=='2'){?>
                  <tr>
                    <th scope="row" class="text-right"><?php echo $this->lang->line('dashboard_xin_status');?> : </th>
                    <td class="text-right"><?php echo '<span class="badge bg-green">'.$this->lang->line('xin_completed').'</span>';?></td>
                  </tr>
                  <?php }?>
            
               
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
                  <div class="col-md-12">
                  <?php $attributes = array('name' => 'update_status', 'id' => 'update_status', 'autocomplete' => 'off');?>
                    <?php $hidden = array('user_id' => $session['user_id']);?>
                            <?php echo form_open('admin/training/update_status', $attributes, $hidden);?>
                            <?php
                    $data = array(
                      'name'        => 'token_status',
                      'id'          => 'token_status',
                      'type'        => 'hidden',
                      'value'        => $training_id,
                      'class'       => 'form-control',
                    );
                    echo form_input($data);
                    ?>
                      <div class="box-header with-border">
                         <h3 class="box-title"> <?php echo $this->lang->line('xin_update_status');?> </h3>
                      </div>
                      
                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="status"><?php echo $this->lang->line('left_performance');?></label>
                              <select class="form-control" name="performance" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_performance');?>">
                                <option value="0" <?php if($performance=='0'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_not_included');?></option>
                                <option value="1" <?php if($performance=='1'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_satisfactory');?></option>
                                <option value="2" <?php if($performance=='2'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_average');?></option>
                                <option value="3" <?php if($performance=='3'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_poor');?></option>
                                <option value="4" <?php if($performance=='4'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_excellent');?></option>
                              </select>
                            </div>
                          </div>
                      </div>
                      
                      <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                              <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                                <option value="0" <?php if($training_status=='0'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_pending');?></option>
                                <option value="1" <?php if($training_status=='1'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_started');?></option>
                                <option value="2" <?php if($training_status=='2'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_completed');?></option>
                                <option value="3" <?php if($training_status=='3'):?> selected <?php endif;?>><?php echo $this->lang->line('xin_terminated');?></option>
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
                          <button type="reset" class="btn btn-default" onclick="location.href='<?php echo base_url();?>/admin/training'"  > <i class="fa fa-power-off "></i> <?php echo $this->lang->line('xin_close');?> </button>
                          <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
                      </div>
                      <?php echo form_close(); ?>
                  </div>
                
                <div>&nbsp;</div>
              </div></div>
              <!-- tab --> 
            </div>
          </div>
      </div>
</div>

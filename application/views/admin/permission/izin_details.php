<?php
/* izin Detail view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $user = $this->Core_model->read_user_info($session['user_id']);?>
<?php
$datetime1 = new DateTime($from_date);
$datetime2 = new DateTime($to_date);
$interval = $datetime1->diff($datetime2);

if(strtotime($from_date) == strtotime($to_date)){
	$no_of_days =1;
} else {
	$no_of_days = $interval->format('%a') +1;
}
$izin_user = $this->Core_model->read_user_info($employee_id);

//department head
$department = $this->Department_model->read_department_information($user[0]->department_id);
?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<div class="row m-b-1">
  <div class="col-md-4">
    <section id="decimal">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_izin_detail');?> </h3>
              </div>
            <div class="box-body">
                <div class="table-responsive" data-pattern="priority-columns">
                  <table class="table table-striped ">
                    <tbody>
                      <tr>
                        <th class="text-right" scope="row" style="border-top:0px;"><?php echo $this->lang->line('xin_employee');?></th>
                        <td class="text-right"><?php echo $full_name;?></td>
                      </tr>
                      <tr>
                        <th class="text-right" scope="row" style="border-top:0px;"><?php echo $this->lang->line('left_department');?></th>
                        <td class="text-right"><?php echo $department_name;?></td>
                      </tr>
                      <tr>
                        <th class="text-right" scope="row"><?php echo $this->lang->line('xin_izin_type');?></th>
                        <td class="text-right"><?php echo $type;?></td>
                      </tr>
                      <tr>
                        <th class="text-right" scope="row"><?php echo $this->lang->line('xin_applied_on');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($created_at);?></td>
                      </tr>
                      <tr>
                        <th class="text-right" scope="row"><?php echo $this->lang->line('xin_start_date');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($from_date);?></td>
                      </tr>
                      <tr>
                        <th class="text-right" scope="row"><?php echo $this->lang->line('xin_end_date');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($to_date);?></td>
                      </tr>
                      <tr>
                        <th class="text-right" scope="row"><?php echo $this->lang->line('xin_attachment');?></th>
                        <td class="text-right">
                        <?php if($izin_attachment!='' && $izin_attachment!='NULL'):?>
                        <a href="<?php echo site_url()?>admin/download?type=izin&filename=<?php echo $izin_attachment;?>"><?php echo $this->lang->line('xin_download');?></a>
                        <?php else:?>
                        
                        <?php endif;?></td>
                      </tr>
                      <tr>
                        <th class="text-right" scope="row"><?php echo $this->lang->line('xin_hris_total_days');?></th>
                        <td class="text-right">
                        <?php 
                        if($is_half_day == 1){
                          $izin_day_info = $this->lang->line('xin_hr_izin_half_day');
                        } else {
                          $izin_day_info = $no_of_days;
                        }
                        echo $izin_day_info;?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!-- <div class="bs-callout-success callout-border-left callout-square callout-transparent mt-1 p-1"> <?php echo $reason;?> </div> -->
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <div class="col-md-8">
    <section id="decimal">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_update_status');?> </h3>
              </div>
              <div class="box-body">
                  <?php $attributes = array('name' => 'update_status', 'id' => 'update_status', 'autocomplete' => 'off');?>
				          <?php $hidden = array('user_id' => $session['user_id'], '_token_status' => $izin_id);?>
                  <?php echo form_open('admin/permission/update_izin_status/'.$izin_id, $attributes, $hidden);?>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="status"><?php echo $this->lang->line('dashboard_xin_status');?></label>
                        <select class="form-control" name="status" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('dashboard_xin_status');?>">
                          <option value="1" <?php if($status=='1'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_pending');?></option>
                          <option value="2" <?php if($status=='2'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_approved');?></option>
                          <option value="3" <?php if($status=='3'):?> selected <?php endif; ?>><?php echo $this->lang->line('xin_rejected');?></option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="remarks"><?php echo $this->lang->line('xin_description');?></label>
                        <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="remarks" id="remarks" cols="30" rows="12"><?php echo $remarks;?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="form-actions box-footer">
                    <!-- onclick="window.location.href('<?php echo base_url();?>/admin/permission/izin');" -->
                    <button type="reset" class="btn btn-default" onclick="location.href='<?php echo base_url();?>/admin/permission/izin'"  > <i class="fa fa-power-off "></i> <?php echo $this->lang->line('xin_close');?> </button>
                    
                    <button type="submit" class="btn btn-primary"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_save');?> </button>
                  
                  </div>
                <?php echo form_close(); ?>
              </div>
            </div>
        </div>
      </div>
    </section>
  </div>
 

  </div>
<style type="text/css">
.trumbowyg-editor { min-height:110px !important; }
</style>
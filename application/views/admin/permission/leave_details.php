<?php
/* Leave Detail view
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
$leave_user = $this->Core_model->read_user_info($employee_id);

//department head
$department = $this->Department_model->read_department_information($user[0]->department_id);

$tahun = date('Y', strtotime($created_at));
$tanggal = date('Y-m-d', strtotime($created_at));

?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<div class="row m-b-1">
  <div class="col-md-4">
    <section id="decimal">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_leave_detail');?> </h3>
              </div>
            <div class="box-body">
                <div class="table-responsive" data-pattern="priority-columns" style="height: 580px;">
                  <table class="table table-striped m-md-b-0">
                    <tbody>

                      <tr>
                        <th colspan="2" style="background-color: #ccc; ">Data Karyawan</th>
                       
                      </tr>
                      <tr>
                        <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_employee');?></th>
                        <td class="text-right"><?php echo $full_name;?></td>
                      </tr>
                      <tr>
                        <th scope="row" style="float: right;">Departemen</th>
                        <td  width="60%" class="text-right"><?php echo $department_name;?></td>
                      </tr>
                       <tr>
                        <th scope="row" style="float: right;">Posisi</th>
                        <td class="text-right"><?php echo $designation_name;?></td>
                      </tr>
                       <tr>
                        <th scope="row" style="float: right;">Tanggal Mulai Kerja</th>
                        <td class="text-right"><?php echo $date_of_joining; ?></td>
                      </tr>

                      <tr>
                        <th scope="row" style="float: right;">Lama Bekerja</th>
                        <td class="text-right"><?php echo $lama_kerja; ?></td>
                      </tr>

                      <tr>
                        <th scope="row" style="float: right;">Hak Cuti Tahunan</th>
                        <td class="text-right"><?php echo $hak_cuti; ?></td>
                      </tr>                     

                      <tr>
                        <th colspan="2" ><br></th>                       
                      </tr>

                      <tr>
                        <th colspan="2" style="background-color: #ccc; ">Pengajuan Cuti</th>                       
                      </tr>

                      <tr>
                         <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_leave_type');?></th>
                        <td class="text-right"><?php echo $type;?></td>
                      </tr>
                      <tr>
                         <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_applied_on');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($created_at);?></td>
                      </tr>
                      <tr>
                         <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_start_date');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($from_date);?></td>
                      </tr>
                      <tr>
                         <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_end_date');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($to_date);?></td>
                      </tr>
                      <tr hidden="true">
                         <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_attachment');?></th>
                        <td class="text-right">
                        <?php if($leave_attachment!='' && $leave_attachment!='NULL'):?>
                        <a href="<?php echo site_url()?>admin/download?type=leave&filename=<?php echo $leave_attachment;?>"><?php echo $this->lang->line('xin_download');?></a>
                        <?php else:?>
                        
                        <?php endif;?></td>
                      </tr>
                      <tr>
                         <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_hris_total_days');?> </th>
                        <td class="text-right">
                          <?php 
              						
                          if($is_half_day == 1){
              							$leave_day_info = $this->lang->line('xin_hr_leave_half_day');
              						} else {
              							$leave_day_info = $no_of_days;
              						}
              						echo $leave_day_info;

                          ?> Hari 
                        </td>
                      </tr>

                       <tr>
                        <th colspan="2" ><br></th>                       
                      </tr>

                      <tr>
                        <th colspan="2" style="background-color: #ccc; "><?php echo $this->lang->line('xin_leave_remark');?></th>                       
                      </tr>

                      <tr>
                          <td colspan="2" class="text-left"><?php echo $reason;?></td>
                      </tr>
                    </tbody>
                  </table>
                 
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
 
 <div class="col-md-4">
    <section id="decimal">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
                  <h3 class="box-title"> 
                    <?php echo $this->lang->line('xin_last_taken_leave_title');?> di <?php echo $tahun; ?>
                      
                    </h3>  

              </div>
            <div class="box-body">
              <div class="box-block card-dashboard">
              <div class="table-responsive" data-pattern="priority-columns">
                  <table class="table table-striped m-md-b-0">
                    <tbody>
                      <?php $show_last_leave = $this->Permission_model->employee_show_last_leave($tahun,$tanggal,$employee_id,$leave_id); ?>
                      <?php foreach($show_last_leave as $last_leave) {?>   
                      <?php
        
                        $type = $this->Permission_model->read_leave_type_information($last_leave->leave_type_id);
                        if(!is_null($type)){
                          $type_name = $type[0]->type_name;
                        } else {
                          $type_name = '--';  
                        }
                        $datetime1 = new DateTime($last_leave->from_date);
                        $datetime2 = new DateTime($last_leave->to_date);
                        $interval = $datetime1->diff($datetime2);
                        
                        if(strtotime($last_leave->from_date) == strtotime($last_leave->to_date)){
                          $last_leave_no_of_days =1;
                        } else {
                          $last_leave_no_of_days = $interval->format('%a') +1;
                        }
                        if($last_leave->is_half_day == 1){
                          $last_leave_day_info = $this->lang->line('xin_hr_leave_half_day');
                        } else {
                          $last_leave_day_info = $last_leave_no_of_days;
                        }
                      ?>            
                      <tr>
                        <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_leave_type');?></th>
                        <td class="text-right"><?php echo $type_name;?></td>
                      </tr>
                      <tr>
                        <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_applied_on');?></th>
                        <td class="text-right"><?php echo $this->Core_model->set_date_format($last_leave->created_at);?></td>
                      </tr>
                      <tr>
                        <th scope="row" style="float: right;"><?php echo $this->lang->line('xin_hris_total_days');?></th>
                        <td class="text-right"><?php echo $last_leave_day_info;?> Hari</td>
                      </tr> 

                <?php } ?>
                </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="decimal">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"> <?php echo $this->lang->line('xin_leave_statistics');?> di <?php echo $tahun; ?></h3>
              </div>
            <div class="box-body">
              <div class="box-block card-dashboard">
                
                 <?php $leave_categories_ids = explode(',',$leave_user[0]->leave_categories); ?>

                    <?php foreach($all_leave_types as $type) {
                         
                         if(in_array($type->leave_type_id,$leave_categories_ids)){ ?>
                            <?php
                                $hlfcount =0;
                                $leave_halfday_cal = employee_leave_halfday_cal($tahun,$type->leave_type_id,$employee_id);
                                foreach($leave_halfday_cal as $lhalfday):
                                  $hlfcount += 0.5;
                                endforeach;
                                $count_l = count_leaves_info($tahun,$type->leave_type_id,$employee_id);
                                $count_l = $count_l - $hlfcount;
                            ?>
                            <?php
                                $edays_per_year = $type->days_per_year;
                          
                                  if($count_l == 0){
                                    $progress_class = '';
                                    $count_data = 0;
                                  } else {
                                    if($edays_per_year > 0){
                                      $count_data = $count_l / $edays_per_year * 100;
                                    } else {
                                      $count_data = 0;
                                    }
                                    // progress
                                    if($count_data <= 20) {
                                      $progress_class = 'progress-success';
                                    } else if($count_data > 20 && $count_data <= 50){
                                      $progress_class = 'progress-info';
                                    } else if($count_data > 50 && $count_data <= 75){
                                      $progress_class = 'progress-warning';
                                    } else {
                                      $progress_class = 'progress-danger';
                                    }
                                  }
                           ?>
                          <div id="leave-statistics">
                              <p><strong><?php echo $type->type_name;?> (<?php echo $count_l;?>/<?php echo $edays_per_year;?>)</strong></p>
                              <div class="progress" style="height: 6px;">
                                  <div class="progress-bar" style="width: <?php echo $count_data;?>%;"></div>
                              </div>
                              <?php } }?>
                          </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
 <div class="col-md-4">
    <section id="decimal">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"> Persetujuan Cuti </h3>
              </div>
            <div class="box-body">
                  <?php $attributes = array('name' => 'update_status', 'id' => 'update_status', 'autocomplete' => 'off');?>
				          <?php $hidden = array('user_id' => $session['user_id'], '_token_status' => $leave_id);?>
                  <?php echo form_open('admin/permission/update_leave_status/'.$leave_id, $attributes, $hidden);?>
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
                        <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_description');?>" name="remarks" id="remarks" cols="30" rows="23"><?php echo $remarks;?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="form-actions box-footer">
                     <button type="reset" class="btn btn-default" onclick="location.href='<?php echo base_url();?>/admin/permission/leave'"  > <i class="fa fa-power-off "></i> <?php echo $this->lang->line('xin_close');?> </button>
                    
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
<?php
/* Attendance view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="box mb-4 <?php echo $get_animate;?>">
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <?php $attributes = array('name' => 'attendance_recap_report', 'id' => 'attendance_recap_report', 'autocomplete' => 'off', 'class' => 'add form-hrm');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/timesheet/attendance_list', $attributes, $hidden);?>
        <?php
      $data = array(
        'type'        => 'hidden',
        'name'        => 'date_format',
        'id'          => 'date_format',
        'value'       => $this->Core_model->set_date_format(date('Y-m-d')),
        'class'       => 'form-control',
      );
      echo form_input($data);
      ?>
        <div class="row">         

            <?php if($user_info[0]->user_role_id==1){ ?>
              <div class="col-md-3">
                  <div class="form-group">
                  <label for="name"><?php echo $this->lang->line('left_location');?></label>
                  <select name="location_id" id="location_id" class="form-control input-sm" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_location');?>">
                  <!-- <option value="0"><?php echo $this->lang->line('xin_acc_all');?></option> -->
                  <?php foreach($all_office_shifts as $elocation) {?>
                  <option value="<?php echo $elocation->location_id?>"><?php echo $elocation->location_name?></option>
                  <?php } ?>
                  </select>
                  </div>
              </div>
            <?php } else {?>
               <input type="hidden" value="0" name="location_id" id="location_id" />
            <?php } ?>

            <div class="col-md-2">
              <div class="form-group">
                <label for="first_name"><?php echo $this->lang->line('xin_e_details_date');?></label>
                <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="attendance_date" name="attendance_date" type="text" value="<?php echo date('Y-m-d');?>">
              </div>
            </div>
            
          <div class="col-md-4">
            <div class="form-group"> &nbsp;
              <label for="first_name">&nbsp;</label><br />
              <!-- <button type="submit" class="btn btn-primary save"><i class="fa fa-filter"></i> <?php echo $this->lang->line('xin_get');?></button> -->

               <button type="submit" class="btn  btn-success save"><i class="fa fa-save"></i> <?php echo $this->lang->line('xin_save_recap');?></button>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?> </div>
    </div>
  </div>
</div>
<div class="box <?php echo $get_animate;?>">
  
  <div class="box-body">
    <div class="row">
        <div class="col-md-12 <?php echo $get_animate;?>"> 
          <!-- Custom Tabs (Pulled to the right) -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
             
              <li class="active"><a href="#tab_1-1" data-toggle="tab">
                <?php echo $this->lang->line('left_attendance_day');?></a>
              </li>
              
              
              <li ><a href="#tab_2-2" data-toggle="tab">
                <?php echo $this->lang->line('left_attendance_day_recap');?></a>
              </li>

            </ul>
            <div class="tab-content">
           
              <div class="tab-pane active" id="tab_1-1">
                <div class="box <?php echo $get_animate;?>">                  
                    <div class="box-body">
                      <div class="box-datatable table-responsive">
                        <table class="datatables-demo table table-striped table-bordered" id="xin_table_tanggal" style="width:100%;">
                          <thead>                           
                            <tr>
                              <th style="width:20px;vertical-align: center;">No</th>
                              <th style="width:80px;"><?php echo $this->lang->line('xin_e_details_date');?></th>
                              <th style="width:200px;"><?php echo $this->lang->line('left_location');?></th>
                              <th style="width:80px;"><?php echo $this->lang->line('xin_present');?></th>
                              <th style="width:80px;"><?php echo $this->lang->line('xin_absent');?></th>
                              <th style="width:80px;"><?php echo $this->lang->line('xin_on_leave');?></th>
                              <th style="width:80px;"><?php echo $this->lang->line('xin_on_izin');?></th>
                              <th style="width:80px;"><?php echo $this->lang->line('xin_travels');?></th> 
                              <th style="width:80px;"><?php echo $this->lang->line('dashboard_overtime');?></th>           
                              <th style="width:120px;"><?php echo $this->lang->line('dashboard_total_work');?></th>            
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                </div>
              </div>
             
              <div class="tab-pane" id="tab_2-2">
                <div class="box <?php echo $get_animate;?>">     
                  <div class="box-body">
                    <div class="box-datatable table-responsive">
                      <table class="datatables-demo table table-striped table-bordered" id="xin_table_recap" style="width:120%;">
                        <thead>                           
                            <tr>
                              <th style="width:20px;vertical-align: center;">No</th>          
                              <th style="width:300px;"><?php echo $this->lang->line('xin_employee_name');?></th>       
                              <th style="width:180px;"><?php echo $this->lang->line('dashboard_employee_jam');?></th>
                              <th style="width:80px;"><?php echo $this->lang->line('xin_e_details_date');?></th>
                              <th style="width:120px;"><?php echo $this->lang->line('dashboard_xin_status');?></th>
                              <th style="width:120px;"><?php echo $this->lang->line('dashboard_clock_in');?></th>
                              <th style="width:120px;"><?php echo $this->lang->line('dashboard_clock_out');?></th>
                              <th style="width:120px;"><?php echo $this->lang->line('dashboard_late');?></th>
                              <th style="width:120px;"><?php echo $this->lang->line('dashboard_early_leaving');?></th> 
                              <th style="width:120px;"><?php echo $this->lang->line('dashboard_overtime');?></th>           
                              <th style="width:150px;vertical-align: center;" ><?php echo $this->lang->line('dashboard_total_work');?></th>            
                            </tr>
                          </thead>                  
                        </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane --> 
            </div>
            <!-- /.tab-content --> 
          </div>
          <!-- nav-tabs-custom --> 
        </div>
        <!-- /.col --> 
    </div>

    
  </div>
</div>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php if(isset($_GET['jd']) && isset($_GET['office_shift_id']) && $_GET['data']=='shift'){ ?>

      <?php $assigned_ids = explode(',',$employee_id);  ?>

      <?php $session = $this->session->userdata('username');?>
      <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
        <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Pola Kerja Shift</h4>
      </div>
        
      <?php $attributes = array('name' => 'edit_office_shift', 'id' => 'edit_office_shift', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
      <?php $hidden     = array('_method' => 'EDIT', '_token' => $office_shift_id, 'ext_name' => $office_shift_id);?>

      <?php echo form_open('admin/timesheet/edit_office_shift/'.$office_shift_id, $attributes, $hidden);?>
          
      <div class="modal-body">
          
            <div class="row">
              <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Nama Pola Kerja</label>
                        <input class="form-control" placeholder="Nama Pola Kerja Shift" name="shift_name" type="text" id="name" value="<?php echo $shift_name;?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Perusahaan</label>
                        <select class="form-control input-sm" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
                        <option value=""></option>
                        <?php foreach($get_all_companies as $company) {?>
                        <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$company_id):?> selected="selected"<?php endif;?>><?php echo $company->name?></option>
                        <?php } ?>
                      </select>
                      </div>
                    </div>
                  </div>
              </div>

              <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="date"><?php echo $this->lang->line('xin_e_details_month_work');?></label>
                         <select class="form-control input-sm" name="payroll_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month_work');?>">
                           <?php foreach($all_bulan_gaji as $bulan_gaji) {?>
                          <option value="<?php echo $bulan_gaji->payroll_id?>" <?php if($bulan_gaji->payroll_id==$payroll_id):?> selected="selected"<?php endif;?>><?php echo $bulan_gaji->desc?></option>
                       <?php } ?>
                      </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="date"><?php echo $this->lang->line('xin_e_details_timeperiod');?></label>
                          <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <input class="form-control input-sm attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly="1"  name="start_date" type="text"  value="<?php echo $start_date;?>" >
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <input class="form-control input-sm attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly="1"  name="end_date" type="text" value="<?php echo $end_date;?>" >
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>

           <?php $result = $this->Department_model->ajax_company_employee_info_shift($company_id);?>
            
            <div class="row">
              <div class="col-md-12"> 
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group" id="employee_ajax">
                        <label for="employee" class="control-label"><?php echo $this->lang->line('xin_employee');?> Shift</label>
                        <select multiple class="form-control input-sm" name="employee_id[]" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_employee_list');?>">
                          <option value=""></option>
                          <?php foreach($result as $employee) {?>
                          <option value="<?php echo $employee->user_id;?>" <?php if(in_array($employee->user_id,$assigned_ids)):?> selected <?php endif; ?>><?php echo $employee->first_name.' '.$employee->last_name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
              </div>          
            </div>

            <div class="row">
                <div class="col-md-12">
                  
                   <div style="background-color: #cbf3e6;padding: 10px;margin-bottom: 15px;">
                      Jadwal Kerja Harian :
                    </div>

                    <div class="form-group row">                 
                      <div class="col-md-12">
                         <div class="box-datatable table-responsive">
                         <table class="datatables-demo table table-striped table-bordered"  width="100%">
                          <thead>                        
                            <tr>
                              <th width="5%">Minggu </th>
                              <th width="5%" colspan="7">Tanggal</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>1</th>
                              <th width="5%">21</th>
                              <th width="5%">22</th>
                              <th width="5%">23</th>
                              <th width="5%">24</th>
                              <th width="5%">25</th>
                              <th width="5%">26</th>
                              <th width="5%">27</th>                         
                            </tr>
                            <tr>
                              <td width="5%" style="text-align: center;"> 
                                 <select class="form-control" name="T27" data-plugin="select_hrm" data-placeholder="Tanggal 27">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T27) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>
                                      
                                  </select>                            
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T28" data-plugin="select_hrm" data-placeholder="Tanggal 28">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T28) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T29" data-plugin="select_hrm" data-placeholder="Tanggal 29">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T29) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T30" data-plugin="select_hrm" data-placeholder="Tanggal 30">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T30) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T31" data-plugin="select_hrm" data-placeholder="Tanggal 31">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T31) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T01" data-plugin="select_hrm" data-placeholder="Tanggal 1">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T01) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                   
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T02" data-plugin="select_hrm" data-placeholder="Tanggal 2">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T02) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                   
                                  </select>
                              </td>                         
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>2</th>
                              <th width="5%">3</th>
                              <th width="5%">4</th>
                              <th width="5%">5</th>
                              <th width="5%">6</th>
                              <th width="5%">7</th>
                              <th width="5%">8</th>
                              <th width="5%">9</th>                         
                            </tr>

                            <tr>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T03" data-plugin="select_hrm" data-placeholder="Tanggal 3">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T03) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                               </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T04" data-plugin="select_hrm" data-placeholder="Tanggal 4">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T04) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                               </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T05" data-plugin="select_hrm" data-placeholder="Tanggal 5">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T05) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                   
                                  </select>
                               </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T06" data-plugin="select_hrm" data-placeholder="Tanggal 6">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T06) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                               </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T07" data-plugin="select_hrm" data-placeholder="Tanggal 7">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T07) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                               </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T08" data-plugin="select_hrm" data-placeholder="Tanggal 8">
                                     <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T08) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                    
                                  </select>
                               </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T09" data-plugin="select_hrm" data-placeholder="Tanggal 9">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T09) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                   
                                  </select>
                              </td>                         
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>3</th>
                              <th width="5%">10</th>
                              <th width="5%">11</th>
                              <th width="5%">12</th>
                              <th width="5%">13</th>
                              <th width="5%">14</th>
                              <th width="5%">15</th>
                              <th width="5%">16</th>                         
                            </tr>

                            <tr>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T10" data-plugin="select_hrm" data-placeholder="Tanggal 10">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T10) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T11" data-plugin="select_hrm" data-placeholder="Tanggal 11">
                                   <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T11) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T12" data-plugin="select_hrm" data-placeholder="Tanggal 12">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T12) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                   
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T13" data-plugin="select_hrm" data-placeholder="Tanggal 13">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T13) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T14" data-plugin="select_hrm" data-placeholder="Tanggal 14">
                                   <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T14) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                    
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T15" data-plugin="select_hrm" data-placeholder="Tanggal 15">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T15) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T16" data-plugin="select_hrm" data-placeholder="Tanggal 16">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T16) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>                         
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>4</th>
                              <th width="5%">17</th>
                              <th width="5%">18</th>
                              <th width="5%">19</th>
                              <th width="5%">20</th>
                              <th width="5%">21</th>
                              <th width="5%">22</th>
                              <th width="5%">23</th>                         
                            </tr>

                            <tr>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T17" data-plugin="select_hrm" data-placeholder="Tanggal 17">
                                   <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T17) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T18" data-plugin="select_hrm" data-placeholder="Tanggal 18">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T18) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T19" data-plugin="select_hrm" data-placeholder="Tanggal 19">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T19) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T20" data-plugin="select_hrm" data-placeholder="Tanggal 20">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T20) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T21" data-plugin="select_hrm" data-placeholder="Tanggal 21">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T21) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                 <select class="form-control" name="T22" data-plugin="select_hrm" data-placeholder="Tanggal 22">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T22) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                    
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T23" data-plugin="select_hrm" data-placeholder="Tanggal 23">
                                   <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T23) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                              </td>                         
                            </tr>

                            <tr>
                              <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>5</th>
                              <th width="5%">24</th>
                              <th width="5%">25</th>
                              <th width="5%">26</th>
                              <th width="5%"></th>
                              <th width="5%"></th>
                              <th width="5%"></th>
                              <th width="5%"></th>                         
                            </tr>

                            <tr>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T24" data-plugin="select_hrm" data-placeholder="Tanggal 24">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T24) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T25" data-plugin="select_hrm" data-placeholder="Tanggal 25">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T25) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                      
                                  </select>
                              </td>
                               <td width="5%" style="text-align: center;">  
                                  <select class="form-control" name="T26" data-plugin="select_hrm" data-placeholder="Tanggal 26">
                                    <option value="K" > K </option>
                                    <?php foreach($all_jam_shift as $jam_shift) {?>
                                    <option value="<?php echo $jam_shift->kode;?>" <?php if($jam_shift->kode==$T26) {?> selected="selected" <?php } ?>> <?php echo $jam_shift->kode;?></option>
                                    <?php } ?>                                     
                                  </select>
                              </td>
                              <th> 
                                 
                              </th>
                              <th> 
                                 
                              </th>
                              <th> 
                                  
                              </th>
                              <th> 
                                  
                              </th>                         
                            </tr>
                            
                          </tbody>
                        </table>
                      </div>
                        
                      </div>
                     
                    </div>
                </div>

                </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
        <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> <?php echo $this->lang->line('xin_update');?></button>
      </div>

      <?php echo form_close(); ?>

    <script type="text/javascript">
     
         $(document).ready(function(){

            jQuery("#ajx_company").change(function(){
                jQuery.get(base_url+"/get_employees_office/"+jQuery(this).val(), function(data, status){
                  jQuery('#employee_ajx').html(data);
                });            
            });                     
            
            // Month & Year
            $('.attendance_date').datepicker({
                changeMonth: true,
                changeYear: true,
                // maxDate: '0',
                dateFormat:'yy-mm-dd',
                altField: "#date_format",
                altFormat: js_date_format,
                yearRange: '1970:' + new Date().getFullYear(),
                beforeShow: function(input) {
                  $(input).datepicker("widget").show();
                }
            });

            $('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
            $('[data-plugin="select_hrm"]').select2({ width:'100%' });
            
            /* Edit data */
    
            $("#edit_office_shift").submit(function(e){
                  /*Form Submit*/
                  e.preventDefault();
                  
                  var obj = $(this), action = obj.attr('name');
                  
                  $('.save').prop('disabled', true);
                  
                  $.ajax({
                    type: "POST",
                    url: e.target.action,
                    data: obj.serialize()+"&is_ajax=3&edit_type=shift&form="+action,
                    cache: false,
                    success: function (JSON) {
                      if (JSON.error != '') {
                        alert_fail('Gagal',JSON.error);
                        $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                        $('.save').prop('disabled', false);
                      } else {
                          $('.edit-modal-data-shift').modal('toggle');
                          
                          var xin_table = $('#xin_table').dataTable({
                            "bDestroy": true,
                            "ajax": {
                              url : "<?php echo site_url("admin/timesheet/office_shift_list") ?>",
                              type : 'GET'
                            },
                            dom: 'lBfrtip',
                           buttons: [
                              'print', {
                                extend: 'pdf',
                                orientation: 'landscape'
                              },
                              'excel'
                            ],
                            "fnDrawCallback": function(settings){
                            $('[data-toggle="tooltip"]').tooltip();          
                            }
                          });
                          
                          xin_table.api().ajax.reload(function(){ 
                            alert_success('Sukses',JSON.result);
                          }, true);
                          
                          $('input[name="csrf_hris"]').val(JSON.csrf_hash);
                          $('.save').prop('disabled', false);
                      }
                    }
                  });
            });                 
        }); 

    </script>
<?php } ?>

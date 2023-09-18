<?php
/* Office Shift view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php if(in_array('0852',$role_resources_ids)) {?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="box mb-4 <?php echo $get_animate;?>">
  <div id="accordion">
    <div class="box-header with-border">
      <h3 class="box-title"><i class="fa fa-plus-circle"></i> <?php echo $this->lang->line('xin_add_new');?> Pola Kerja Shift</h3>
      <div class="box-tools pull-right"> <a class="text-dark collapsed" data-toggle="collapse" href="#add_form" aria-expanded="false">
        <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-plus-circle"></span> <?php echo $this->lang->line('xin_add_new');?></button>
        </a> </div>
    </div>
    <div id="add_form" class="collapse add-form <?php echo $get_animate;?>" data-parent="#accordion" style="">
      <div class="box-body">
        <?php $attributes = array('name' => 'add_office_shift', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('user_id' => $session['user_id']);?>
        <?php echo form_open('admin/timesheet/add_office_shift', $attributes, $hidden);?>
        <div class="bg-white">
          <div class="box-block">

            <div class="row">

              <div class="col-md-6">     

                   <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Perusahaan</label>
                        <select class="form-control input-sm" name="company_id" id="aj_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('module_company_title');?>">
                          <option value=""><?php echo $this->lang->line('xin_select_one');?></option>
                          <?php foreach($get_all_companies as $company) {?>
                          <option value="<?php echo $company->company_id;?>"> <?php echo $company->name;?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>                 

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Nama Pola Kerja</label>
                        <input class="form-control" placeholder="Nama Pola Kerja Shift" name="shift_name" type="text" value="" id="name">
                      </div>
                    </div>
                  </div>

              </div>

              <div class="col-md-6">

                 <div class="row">
                    <div class="col-md-12">
                      <div class="form-group" >
                        <label for="employee" class="control-label">Bulan Kerja</label>
                        <select onchange="cekBulanKerja()" class="form-control input-sm" name="payroll_id" id="payroll_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month_work');?>" required>
                             <option value=""></option>
                             <?php foreach($all_bulan_gaji as $bulan_gaji) {?>
                             <option value="<?php echo $bulan_gaji->payroll_id?>"><?php echo $bulan_gaji->desc?></option>
                             <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="employee" class="control-label">Periode Tanggal</label>
                        <div class=" row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="start_date" name="start_date" type="text" value="<?php echo date('Y-m-d');?>">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <input class="form-control attendance_date" placeholder="<?php echo $this->lang->line('xin_select_date');?>" readonly id="end_date" name="end_date" type="text" value="<?php echo date('Y-m-d');?>">
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>           

              </div>

            </div>

            <div class="row">

              <div class="col-md-12">
              
               <div style="background-color: #cbf3e6;padding: 10px;margin-bottom: 15px;">
                  Jadwal Kerja Harian : <span class="info"></span>
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
                              <select class="form-control input-sm" name="T21" data-plugin="select_hrm" data-placeholder="Tanggal 21" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T22" data-plugin="select_hrm" data-placeholder="Tanggal 22" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T23" data-plugin="select_hrm" data-placeholder="Tanggal 23" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>

                          <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T24" data-plugin="select_hrm" data-placeholder="Tanggal 24" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T25" data-plugin="select_hrm" data-placeholder="Tanggal 25" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T26" data-plugin="select_hrm" data-placeholder="Tanggal 26" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>

                          <td width="5%" style="text-align: center;"> 
                              <select class="form-control input-sm" name="T27" id="T27" data-plugin="select_hrm" data-placeholder="Tanggal 27" >
                                   <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>                           
                          </td>
                                                    
                        </tr>

                        <tr>
                          <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>2</th>
                          <th width="5%">28</th>
                          <th width="5%">29</th>
                          <th width="5%">30</th>
                          <th width="5%">31</th>
                          <th width="5%">1</th>
                          <th width="5%">2</th>
                          <th width="5%">3</th>                         
                        </tr>

                        <tr>
                          <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T28" data-plugin="select_hrm" data-placeholder="Tanggal 28" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T29" id="T29"  data-plugin="select_hrm" data-placeholder="Tanggal 29" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T30" id="T30" data-plugin="select_hrm" data-placeholder="Tanggal 30" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T31" id="T31" data-plugin="select_hrm" data-placeholder="Tanggal 31" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T01" data-plugin="select_hrm" data-placeholder="Tanggal 1" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T02" data-plugin="select_hrm" data-placeholder="Tanggal 2" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>

                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T03" data-plugin="select_hrm" data-placeholder="Tanggal 3" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                                                    
                        </tr>

                        <tr>
                          <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>3</th>
                          <th width="5%">4</th>
                          <th width="5%">5</th>
                          <th width="5%">6</th>
                          <th width="5%">7</th>
                          <th width="5%">8</th>
                          <th width="5%">9</th>
                          <th width="5%">10</th>                         
                        </tr>

                        <tr>
                          <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T04" data-plugin="select_hrm" data-placeholder="Tanggal 4" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T05" data-plugin="select_hrm" data-placeholder="Tanggal 5" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T06" data-plugin="select_hrm" data-placeholder="Tanggal 6" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T07" data-plugin="select_hrm" data-placeholder="Tanggal 7" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T08" data-plugin="select_hrm" data-placeholder="Tanggal 8" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T09" data-plugin="select_hrm" data-placeholder="Tanggal 9" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T10" data-plugin="select_hrm" data-placeholder="Tanggal 10" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                          

                        </tr>

                        <tr>
                          <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>4</th>
                          <th width="5%">11</th>
                          <th width="5%">12</th>
                          <th width="5%">13</th>
                          <th width="5%">14</th>
                          <th width="5%">15</th>
                          <th width="5%">16</th>
                          <th width="5%">17</th>                         
                        </tr>

                        <tr>
                          <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T11" data-plugin="select_hrm" data-placeholder="Tanggal 11" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T12" data-plugin="select_hrm" data-placeholder="Tanggal 12" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T13" data-plugin="select_hrm" data-placeholder="Tanggal 13" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T14" data-plugin="select_hrm" data-placeholder="Tanggal 14" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T15" data-plugin="select_hrm" data-placeholder="Tanggal 15" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T16" data-plugin="select_hrm" data-placeholder="Tanggal 16" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                           <td width="5%" style="text-align: center;">  
                              <select class="form-control input-sm" name="T17" data-plugin="select_hrm" data-placeholder="Tanggal 17" >
                                  <option value="K" > K - Kosong </option>
                                   <?php foreach($all_jam_shift as $jam_shift) {?>
                                   <option value="<?php echo $jam_shift->kode?>"><?php echo $jam_shift->kode; ?> - <?php echo $jam_shift->start_date; ?> s/d <?php echo $jam_shift->end_date; ?></option>
                                   <?php } ?>
                              </select>
                          </td>
                          
                                                   
                        </tr>

                        <tr>
                          <th width="5%" rowspan="2" style="text-align: center;">Minggu <br>5</th>
                          <th width="5%">18</th>
                          <th width="5%">19</th>
                          <th width="5%">20</th>
                          <th width="5%"></th>
                          <th width="5%"></th>
                          <th width="5%"></th>
                          <th width="5%"></th>                         
                        </tr>

                        <tr>
                           
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

            <div class="form-actions box-footer">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-save"></i> <?php echo $this->lang->line('xin_save');?> </button>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?> </div>
    </div>
  </div>
</div>
<?php } ?>
<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
    <h3 class="box-title"> <b>POLA KERJA SHIFT </b> </h3>
    <h5>
       <span style="float: left;">
     Jika anda ingin menambah. merubah, menghapus Jam Pola Kerja Shift ini, silahkan klik tombol "Jam Kerja Shift" </span>
     <span style="float: right;">
      <?php if(in_array('0855',$role_resources_ids)) { ?>
          <a href="<?php echo site_url('admin/timesheet/office_shift_jam');?>" target="_blank" class="btn btn-xs btn-success"> <i class="fa fa-clock-o"></i> Jam Kerja Shift</a> 
      <?php } ?>
      </span>
   </h5>
  </div>
  <div class="box-body">
    <div class="box-datatable table-responsive">
      <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="180%">
        <thead>
          <tr>
            <th width ="80px" rowspan="2"  style="text-align: center;"><?php echo $this->lang->line('xin_option');?></th>
            <th width ="600px" rowspan="2" style="text-align: center;"><?php echo $this->lang->line('xin_day');?><br>(Daftar Karyawan)</th>
            <th colspan="31" style="text-align: center;">Tanggal</th>
          </tr>

          <tr>
            <th width ="90px" style="text-align: center !important;"><center>27</center></th>
            <th width ="90px" style="text-align: center !important;"><center>28</center></th>
            <th width ="90px" style="text-align: center !important;"><center>29</center></th>
            <th width ="90px" style="text-align: center !important;"><center>30</center></th>
            <th width ="90px" style="text-align: center !important;"><center>31</center></th>
            <th width ="90px" style="text-align: center !important;"><center>01</center></th>
            <th width ="90px" style="text-align: center !important;"><center>02</center></th>
            <th width ="90px" style="text-align: center !important;"><center>03</center></th>
            <th width ="90px" style="text-align: center !important;"><center>04</center></th>
            <th width ="90px" style="text-align: center !important;"><center>05</center></th>
            <th width ="90px" style="text-align: center !important;"><center>06</center></th>
            <th width ="90px" style="text-align: center !important;"><center>07</center></th>
            <th width ="90px" style="text-align: center !important;"><center>08</center></th>
            <th width ="90px" style="text-align: center !important;"><center>09</center></th>
            <th width ="90px" style="text-align: center !important;"><center>10</center></th>
            <th width ="90px" style="text-align: center !important;"><center>11</center></th>
            <th width ="90px" style="text-align: center !important;"><center>12</center></th>
            <th width ="90px" style="text-align: center !important;"><center>13</center></th>
            <th width ="90px" style="text-align: center !important;"><center>14</center></th>
            <th width ="90px" style="text-align: center !important;"><center>15</center></th>
            <th width ="90px" style="text-align: center !important;"><center>16</center></th>
            <th width ="90px" style="text-align: center !important;"><center>17</center></th>
            <th width ="90px" style="text-align: center !important;"><center>18</center></th>
            <th width ="90px" style="text-align: center !important;"><center>19</center></th>
            <th width ="90px" style="text-align: center !important;"><center>20</center></th>
            <th width ="90px" style="text-align: center !important;"><center>21</center></th>
            <th width ="90px" style="text-align: center !important;"><center>22</center></th>
            <th width ="90px" style="text-align: center !important;"><center>23</center></th>
            <th width ="90px" style="text-align: center !important;"><center>24</center></th>
            <th width ="90px" style="text-align: center !important;"><center>25</center></th>
            <th width ="90px" style="text-align: center !important;"><center>26</center></th>
           
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>
<style type="text/css">
.box-tools {
    margin-right: -5px !important;
}
.col-md-8 {
  padding-left:0px !important;
  padding-right: 0px !important;
}
.dataTables_length {
  float:left;
}
.dt-buttons {
    position: relative;
    float: right;
    margin-left: 10px;
}
.hide-calendar .ui-datepicker-calendar { display:none !important; }
.hide-calendar .ui-priority-secondary { display:none !important; }
</style>


<script type="text/javascript">

     // $(document).ready(function(){
     //      document.getElementById("T29").disabled = false;             
     //       document.getElementById("T30").disabled = false; 
     //       document.getElementById("T31").disabled = false; 

          
                        
     // });

     

    function cekBulanKerja() {
        var payroll_id = document.getElementsByName("payroll_id")[0].value;

          document.getElementById("T29").disabled = false;             
           document.getElementById("T30").disabled = false; 
           document.getElementById("T31").disabled = false; 

        if (payroll_id == '1'){

          $(".info").html('Januari 2022');

        } else if (payroll_id == '2'){

          $(".info").html('Februari 2022');

        } else if (payroll_id == '3'){

          $(".info").html('Maret 2022');

        } else if (payroll_id == '4'){

          $(".info").html('April 2022');

           // document.getElementById("T29").style.backgroundColor = "#BFE3F2";  
           // document.getElementById("T29").disabled = true;  

           // document.getElementById("T30").style.backgroundColor = "#BFE3F2";  
           // document.getElementById("T30").disabled = true;  

           // document.getElementById("T31").style.backgroundColor = "#BFE3F2";  
           // document.getElementById("T31").disabled = true;  

        } else if (payroll_id == '5'){

          $(".info").html(''+ payroll_id +'');

        }
        
        
    }   
    
</script>
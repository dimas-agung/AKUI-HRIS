<?php
/* Employee Details view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<?php //$default_currency = $this->Core_model->read_currency_con_info($system[0]->default_currency_id);?>
<?php
$eid = $this->uri->segment(4);
$eresult = $this->Employees_model->read_employee_information($eid);
?>
<?php
$ar_sc = explode('- ',$system[0]->default_currency_symbol);
$sc_show = $ar_sc[1];
$leave_user = $this->Core_model->read_user_info($eid);
?>
<?php $get_animate          = $this->Core_model->get_content_animate();?>
<?php $leave_categories_ids = explode(',',$leave_categories);?>
<?php $view_companies_ids   = explode(',',$view_companies_id);?>
<?php $user_info            = $this->Core_model->read_user_info($session['user_id']);?>
<?php $role_resources_ids   = $this->Core_model->user_role_resource(); ?>
<?php

$sql         = 'SELECT * FROM xin_employees WHERE user_id = ?';
$binds       = array($user_id);
$query       = $this->db->query($sql, $binds);
$rw_password = $query->result();
$password    = $rw_password[0]->password;
$full_name   = $rw_password[0]->first_name." ".$rw_password[0]->last_name;

?>


<div class="row">
  <div class="col-md-12">

    <div class="nav-tabs-custom mb-4">
      
      <div class="content">                       
          
          <div class="box-header with-border">            
          <i class="fa fa-user"></i> <h3 class="box-title"> <b> <?php echo strtoupper($full_name); ?> </b></h3>
          </div>
          <br/>
          <ul class="nav nav-tabs">
            <li class="nav-item active"> 
              <a class="nav-link active show" data-toggle="tab" href="#xin_core_hr">
                  Personalia            
              </a> 
            </li>
          </ul>
          <div class="tab-content">

            <div class="tab-pane <?php echo $get_animate;?> active" id="xin_core_hr" >
              <div class="card-body">
                <div class="card overflow-hidden">
                  <div class="row no-gutters row-bordered row-border-light" >
                    
                    <div class="col-md-2 pt-0">
                                       
                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> Kinerja Karyawan </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link active" data-toggle="list" href="javascript:void(0);" data-profile="6" data-profile-block="award" aria-expanded="true" id="user_profile_6"> <i class="fa fa-gift"></i> Daftar Penghargaan </a> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="7" data-profile-block="warning" aria-expanded="true" id="user_profile_7"> <i class="fa fa-warning"></i> Daftar Peringatan </a> 
                            </div>
                        </div>
                      </div>

                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> Aset Karyawan </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="11" data-profile-block="aset" aria-expanded="true" id="user_profile_11"> <i class="fa fa-cubes"></i> Daftar Aset </a> 
                            </div>
                        </div>
                      </div> 

                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> Dinas Karyawan </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="8" data-profile-block="travel" aria-expanded="true" id="user_profile_8"> <i class="fa fa-car"></i> Daftar Dinas </a> 
                            </div>
                        </div>
                      </div> 

                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> Mutasi Karyawan </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="9" data-profile-block="transfer" aria-expanded="true" id="user_profile_9"> <i class="fa fa-send"></i> Daftar Mutasi </a> 
                            </div>
                        </div>
                      </div> 

                      <div class="box" style="margin-bottom: 20px;">
                        <div class="box-header with-border" style="background-color: #e0f3e5;">
                          <h3 class="box-title"> Promosi Karyawan </h3>
                        </div>
                        <div class="list-group">
                            <div class="list-group list-group-flush account-settings-links"> 
                              <a class="list-group-item list-group-item-action nav-tabs-link" data-toggle="list" href="javascript:void(0);" data-profile="10" data-profile-block="promotion" aria-expanded="true" id="user_profile_10"> <i class="fa fa-cloud-upload"></i> Daftar Promosi </a> 
                            </div>
                        </div>
                      </div>                    

                    </div>
                    
                    <div class="col-md-10">
                      
                        <div class="tab-content " style="margin-left: 20px; border: 1px solid #d3d2d2; padding: 10px">                        
                           
                            <!-- Penghargaan -->
                            <div class="tab-pane active current-tab <?php echo $get_animate;?>" id="award" >
                              <div class="box-header with-border">
                                  <h3 class="box-title"> <i class="fa fa-gift"></i> <?php echo $this->lang->line('xin_list_all');?> Penghargaan </h3>
                              </div>
                              <div class="box-body pb-2">
                                  <input class="form-control" id="user_id" name="user_id" type="hidden" value="<?php echo $user_id;?>">
                              </div>
                              <div class="box">                               
                                <div class="box-body">
                                  <div class="box-datatable table-responsive">
                                    <table class="table table-striped table-bordered dataTable" id="xin_table_award" style="width:100%;">
                                      <thead>
                                        <tr>       
                                          <th width="40px"><?php echo $this->lang->line('xin_action');?></th>     
                                          <th width="120px"><center> Tanggal Penghargaan </center></th>
                                          <th width="120px"><center> Bulan Penghargaan </center></th>
                                          <th width="200"><center><i class="fa fa-trophy"></i> <?php echo $this->lang->line('xin_award_name');?></center></th>
                                          <th width="150"><center><i class="fa fa-gift"></i> <?php echo $this->lang->line('xin_gift');?></center></th>                                          
                                          <th ><center>Keterangan Penghargaan</center></th>                                        
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>

                             <!-- Peringatan -->
                            <div class="tab-pane current-tab <?php echo $get_animate;?>" id="warning" style="display:none;">
                              <div class="box-header with-border">
                                  <h3 class="box-title"> <i class="fa fa-warning"></i> <?php echo $this->lang->line('xin_list_all');?> Peringatan </h3>
                              </div>
                              <div class="box-body pb-2">
                                  <input class="form-control" id="user_id" name="user_id" type="hidden" value="<?php echo $user_id;?>">
                              </div>
                              <div class="box">                               
                                <div class="box-body">
                                  <div class="box-datatable table-responsive">
                                    <table class="table table-striped table-bordered dataTable" id="xin_table_warning" style="width:100%;">
                                      <thead>
                                        <tr>       
                                          <th width="40px"><?php echo $this->lang->line('xin_action');?></th>     
                                          <th width="120px"><center> Tanggal Peringatan </center></th>
                                          <th width="120px"><center> No Peringatan </center></th>
                                          <th width="200"><center> Jenis Peringatan </center></th>
                                          <th width="150"><center> Oleh </center></th>                                          
                                          <th ><center>Keterangan Peringatan</center></th>                                        
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>

                             <!-- Aset on Hand -->
                            <div class="tab-pane current-tab <?php echo $get_animate;?>" id="aset" style="display:none;">
                              <div class="box-header with-border">
                                  <h3 class="box-title"> <i class="fa fa-cubes"></i> Daftar Aset yang dipinjam </h3>
                              </div>
                              <div class="box-body pb-2">
                                  <input class="form-control" id="user_id" name="user_id" type="hidden" value="<?php echo $user_id;?>">
                              </div>
                              <div class="box">                               
                                <div class="box-body">
                                  <div class="box-datatable table-responsive">
                                    <table class="table table-striped table-bordered dataTable" id="xin_table_aset" style="width:100%;">
                                      <thead>
                                        <tr>       
                                          <th width="4%"><?php echo $this->lang->line('xin_action');?></th>     
                                          <th width="12%"><center> Tanggal Pinjam </center></th>
                                          <th width="12%"><center> Tanggal Kembali </center></th>
                                          <th width="12%"><center> Status Aset </center></th>
                                          <th width="15%"><center> Jenis Aset</center></th>                                                                                   
                                          <th width="20%"><center> Nama Aset</center></th>
                                          <th ><center> Catatan Aset</center></th>                                        
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Perjalanan Dinas -->
                            <div class="tab-pane current-tab <?php echo $get_animate;?>" id="travel" style="display:none;">
                              <div class="box-header with-border">
                                  <h3 class="box-title"> <i class="fa fa-car"></i> <?php echo $this->lang->line('xin_list_all');?> Dinas </h3>
                              </div>
                              <div class="box-body pb-2">
                                  <input class="form-control" id="user_id" name="user_id" type="hidden" value="<?php echo $user_id;?>">
                              </div>
                              <div class="box">                               
                                <div class="box-body">
                                  <div class="box-datatable table-responsive">
                                    <table class="table table-striped table-bordered dataTable" id="xin_table_travel" style="width:100%;">
                                      <thead>
                                        <tr>       
                                          <th width="40px"><?php echo $this->lang->line('xin_action');?></th>     
                                          <th width="120px"><center> Tanggal Mulai </center></th>
                                          <th width="120px"><center> Tanggal Selesai </center></th>
                                          <th width="120px"><center> Tempat Kunjungan </center></th>
                                          <th width="200"><center> Transportasi </center></th>                                                                                   
                                          <th ><center>Keterangan Dinas</center></th>                                        
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Mutasi -->
                            <div class="tab-pane current-tab <?php echo $get_animate;?>" id="transfer" style="display:none;">
                              <div class="box-header with-border">
                                  <h3 class="box-title"> <i class="fa fa-car"></i> <?php echo $this->lang->line('xin_list_all');?> Mutasi </h3>
                              </div>
                              <div class="box-body pb-2">
                                  <input class="form-control" id="user_id" name="user_id" type="hidden" value="<?php echo $user_id;?>">
                              </div>
                              <div class="box">                               
                                <div class="box-body">
                                  <div class="box-datatable table-responsive">
                                    <table class="table table-striped table-bordered dataTable" id="xin_table_transfer" style="width:100%;">
                                      <thead>
                                        <tr>       
                                          <th width="4%"><?php echo $this->lang->line('xin_action');?></th>     
                                          <th width="12%"><center> Tanggal Mutasi </center></th>                                         
                                          <th width="25%"><center> Mutasi Dari </center></th>
                                          <th width="25%"><center> Mutasi ke  </center></th>                                                                                   
                                          <th ><center>Keterangan Mutasi</center></th>                                        
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Promosi -->
                            <div class="tab-pane current-tab <?php echo $get_animate;?>" id="promotion" style="display:none;">
                              <div class="box-header with-border">
                                  <h3 class="box-title"> <i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('xin_list_all');?> Promosi </h3>
                              </div>
                              <div class="box-body pb-2">
                                  <input class="form-control" id="user_id" name="user_id" type="hidden" value="<?php echo $user_id;?>">
                              </div>
                              <div class="box">                               
                                <div class="box-body">
                                  <div class="box-datatable table-responsive">
                                    <table class="table table-striped table-bordered dataTable" id="xin_table_promotion" style="width:100%;">
                                      <thead>
                                        <tr>       
                                          <th width="40px"><?php echo $this->lang->line('xin_action');?></th>     
                                          <th width="120px"><center> Tanggal Promosi </center></th>                                         
                                          <th width="250px"><center> Promosi Posisi </center></th>
                                          <th width="250"><center> Promosi  </center></th>                                                                                   
                                          <th ><center>Keterangan Promosi</center></th>                                        
                                        </tr>
                                      </thead>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                                            
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
       </div>
    </div>
  </div>
</div>


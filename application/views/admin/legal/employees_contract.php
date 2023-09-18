<?php
/* Employees view
*/
?>

<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<?php $system = $this->Core_model->read_setting_info(1);?>

<?php

// reports to 
$reports_to = get_reports_team_data($session['user_id']); ?>

 <?php if(in_array('131',$role_resources_ids)) {?>

  <div class="row">  
    <div class="col-xs-12 col-md-12 col-sm-12">
      <div class="box box-info">
        <div class="box-header with-border">

         <h3 class="box-title"> <i class="fa fa-info-circle"></i> Manajemen Kontrak Karyawan, 
               <span class="hijau"><?php echo jum_status_kontrak(); ?> karyawan kontrak</span> 
            </h3>
          <div class="box-tools pull-right">
          </div>
        </div>
        <div class="box-body">
            <div class="row">
                 <div class="col-xs-12 col-md-3 col-sm-12">
                    <div class="card hris-box-three hris-dash-purple">
                        <div class="card-body">
                            <i class="fa fa-users hris-dash-icon"></i>
                            <div class="hris-box-three-content">
                                <p class="m-0 text-uppercase text-white font-600 font-secondary text-overflow" title="<?php echo $this->lang->line('xin_people');?>">
                                 Kontrak Belum Dibuat                                 
                                </p>
                                <h3 class="text-white">
                                  <span data-plugin="counterup">
                                    
                                       <?php echo jum_status_kontrak_belum_ada(); ?>                                   
                                  
                                  </span> 
                                  <small><i class="mdi mdi-arrow-up text-white"></i></small>
                                </h3>
                                
                                <p class="text-white m-0">
                                  <span class="badge badge-info"> 
                                    Pria <?php echo jum_status_belum_ada_kontrak_male();?> 
                                  </span>
                                  <span class="ml-2"> 
                                    <span class="badge bg-red"> 
                                      Perempuan <?php echo jum_status_belum_ada_kontrak_female();?> 
                                    </span>
                                  </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-12">
                    <div class="card hris-box-three hris-dash-purple">
                        <div class="card-body">
                            <i class="fa fa-users hris-dash-icon"></i>
                            <div class="hris-box-three-content">
                                <p class="m-0 text-uppercase text-white font-600 font-secondary text-overflow" title="<?php echo $this->lang->line('xin_people');?>">
                                 Kontrak Berlangsung                                 
                                </p>
                                <h3 class="text-white">
                                  <span data-plugin="counterup">
                                    
                                       <?php echo jum_status_kontrak_berlangsung();?>                                    
                                  
                                  </span> 
                                  <small><i class="mdi mdi-arrow-up text-white"></i></small>
                                </h3>
                                
                                <p class="text-white m-0">
                                  <span class="badge badge-info"> 
                                    Pria <?php echo jum_status_kontrak_berlangsung_male();?> 
                                  </span>
                                  <span class="ml-2"> 
                                    <span class="badge bg-red"> 
                                      Perempuan <?php echo jum_status_kontrak_berlangsung_female();?> 
                                    </span>
                                  </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-12">
                    <div class="card hris-box-three hris-dash-purple">
                        <div class="card-body">
                            <i class="fa fa-users hris-dash-icon"></i>
                            <div class="hris-box-three-content">
                                <p class="m-0 text-uppercase text-white font-600 font-secondary text-overflow" title="<?php echo $this->lang->line('xin_people');?>">
                                 Kontrak Akan Berakhir                                 
                                </p>
                                <h3 class="text-white">
                                  <span data-plugin="counterup">
                                    
                                       <?php echo jum_status_kontrak_akan_habis();?>                                  
                                  
                                  </span> 
                                  <small><i class="mdi mdi-arrow-up text-white"></i></small>
                                </h3>
                                
                                <p class="text-white m-0">
                                  <span class="badge badge-info"> 
                                    Pria <?php echo jum_status_kontrak_akan_habis_male();?> 
                                  </span>
                                  <span class="ml-2"> 
                                    <span class="badge bg-red"> 
                                      Perempuan <?php echo jum_status_kontrak_akan_habis_female();?> 
                                    </span>
                                  </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-12">
                    <div class="card hris-box-three hris-dash-purple">
                        <div class="card-body">
                            <i class="fa fa-users hris-dash-icon"></i>
                            <div class="hris-box-three-content">
                                <p class="m-0 text-uppercase text-white font-600 font-secondary text-overflow" title="<?php echo $this->lang->line('xin_people');?>">
                                 Kontrak Sudah Berakhir                                  
                                </p>
                                <h3 class="text-white">
                                  <span data-plugin="counterup">
                                    
                                       <?php echo jum_status_kontrak_sudah_habis();?>                                    
                                  
                                  </span> 
                                  <small><i class="mdi mdi-arrow-up text-white"></i></small>
                                </h3>
                                
                                <p class="text-white m-0">
                                  <span class="badge badge-info"> 
                                    Pria <?php echo jum_status_kontrak_sudah_habis_male();?> 
                                  </span>
                                  <span class="ml-2"> 
                                    <span class="badge bg-red"> 
                                      Perempuan <?php echo jum_status_kontrak_sudah_habis_female();?> 
                                    </span>
                                  </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-3 col-sm-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title"> <i class="fa fa-users"></i> Kontrak Belum Dibuat</h3>
                    </div>        
                    <div class="box-body" style="">
                      <div class="overflow-scrolls" style="overflow:auto; height:640px;">
                        <ul class="products-list product-list-in-box">
                          <?php if (count($last_five_kontrak_belum_dibuat) > 0) {?>
                                <?php $no = 1; ?>
                                <?php foreach($last_five_kontrak_belum_dibuat as $employee) {?>
                                      <?php 
                                            if($employee->profile_picture!='' && $employee->profile_picture!='no file') {
                                                $de_file = base_url().'uploads/profile/'.$employee->profile_picture;
                                            } else { 
                                                if($employee->gender=='Male') {  
                                                $de_file = base_url().'uploads/profile/default_male.jpg'; 
                                                } else {  
                                                $de_file = base_url().'uploads/profile/default_female.jpg';
                                                }
                                            }
                                            
                                            $company = $this->Core_model->read_company_info($employee->company_id);
                                            if(!is_null($company)){
                                              $comp_name = $company[0]->name;
                                            } else {
                                              $comp_name = '<span class="badge bg-red"> ? </span>';
                                            }
                                            
                                            $fname = substr($employee->first_name.' '.$employee->last_name,0,17);                
                                            
                                            $designation = $this->Designation_model->read_designation_information($employee->designation_id);
                                            
                                            if(!is_null($designation)){
                                              $designation_name = $designation[0]->designation_name;
                                            } else {
                                              $designation_name = '--'; 
                                            }
                                            
                                           $department_designation =substr($designation_name,0,30);

                                            $date_of_joining = $this->Core_model->set_date_format($employee->date_of_joining);
                                            ?>
                                      <li class="item">
                                        <div class="product-img"> <img src="<?php echo $de_file;?>" alt="<?php echo $fname;?>" class="rounded-circle-img"> </div>
                                        <div class="product-info"> 
                                             

                                             <b><?php echo $no;?>. <?php echo strtoupper($fname);?> ...</b> 
                                            <span class="product-description"> 
                                              <small>
                                                  <p style="margin-top: 10px;">
                                                    Mulai bekerja pada tanggal <b><?php echo $date_of_joining; ?></b>,
                                                    dengan posisi level sebagai <b><?php echo $department_designation;?></b>
                                                    berlokasi kerja di <b><?php echo $comp_name; ?></b>. 
                                                    </p> 
                                                    <p class ="badge bg-green" style="padding: 5px;margin-top: 5px;margin-bottom: 2px;">
                                                      <i class="fa fa-bell"></i> Kontrak Belum Dibuat &nbsp;
                                                    </p>
                                                    <p style="margin-top: 5px;margin-bottom: 2px;"> 
                                                     <span data-toggle="tooltip" data-placement="top" title="Perpanjang">
                                                      <a  target="_blank" href="<?php echo site_url('admin/legal/kontrak/').$employee->user_id.'/';?>">
                                                        <button type="button" class="btn icon-btn btn-xs bg-gray waves-effect waves-light">
                                                        <i class="fa fa-plus"></i> Buat Kontrak Baru
                                                        </button>
                                                      </a>                                     
                                                    </span>                                    
                                                  </p>
                                              </small>
                                            </span> 

                                        </div>
                                      </li>
                                       <?php $no++; ?>
                                <?php } ?>
                          <?php } else { ?>
                                <li >                                     
                                    <div class="box-header bg-gray text-center">
                                      <h3 class="box-title"> <i class="fa fa-info-circle"></i> Tidak ada </h3>
                                    </div>
                                </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xs-12 col-md-3 col-sm-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title"> <i class="fa fa-users"></i>                       
                        Kontrak Berlangsung  
                      </h3>
                    </div>        
                    <div class="box-body" style="">
                        <div class="overflow-scrolls" style="overflow:auto; height:640px;">
                        <ul class="products-list product-list-in-box">
                          <?php if (count($last_five_kontrak_belum_berakhir) > 0) {?>
                              <?php $no = 1; ?>
                              <?php foreach($last_five_kontrak_belum_berakhir as $employee) {?>
                                  <?php 
                                        if($employee->profile_picture!='' && $employee->profile_picture!='no file') {
                                            $de_file = base_url().'uploads/profile/'.$employee->profile_picture;
                                        } else { 
                                            if($employee->gender=='Male') {  
                                            $de_file = base_url().'uploads/profile/default_male.jpg'; 
                                            } else {  
                                            $de_file = base_url().'uploads/profile/default_female.jpg';
                                            }
                                        }
                                        $company = $this->Core_model->read_company_info($employee->company_id);
                                        if(!is_null($company)){
                                          $comp_name = $company[0]->name;
                                        } else {
                                          $comp_name = '<span class="badge bg-red"> ? </span>';
                                        }
                                        $fname = substr($employee->first_name.' '.$employee->last_name,0,17);    

                                        $designation = $this->Designation_model->read_designation_information($employee->designation_id);
                                        if(!is_null($designation)){
                                          $designation_name = $designation[0]->designation_name;
                                        } else {
                                          $designation_name = '--'; 
                                        }
                                       $department_designation =substr($designation_name,0,30);

                                        $date_of_joining = $this->Core_model->set_date_format($employee->date_of_joining);

                                        $reminder    = $this->Core_model->set_date_format($employee->notif);

                                        $selisih     = $employee->selisih;

                                        // $name_durasi = $employee->name_durasi;

                                        $kontrak     = $this->Core_model->set_date_format($employee->kontrak_from_date).' s/d '.$this->Core_model->set_date_format($employee->kontrak_end_date);

                                        ?>
                                  <li class="item">
                                    <div class="product-img"> <img src="<?php echo $de_file;?>" alt="<?php echo $fname;?>" class="rounded-circle-img"> </div>
                                    <div class="product-info"> 
                                        <b><?php echo $no;?>. <?php echo strtoupper($fname);?> ...</b> 
                                        <span class="product-description"> 
                                          <small>
                                              <p style="margin-top: 10px;">
                                                Mulai bekerja pada tanggal <b><?php echo $date_of_joining; ?></b>,
                                                dengan posisi level sebagai <b><?php echo $department_designation;?></b>
                                                berlokasi kerja di <b><?php echo $comp_name; ?></b>
                                                dengan kontrak terakhir : <b><?php echo $employee->name_type; ?> (<?php echo $employee->kontrak_no; ?>)</b>,
                                                periode kontrak <b><?php echo $kontrak; ?></b>. 
                                                </p> 
                                                <p class ="badge bg-green" style="padding: 5px;margin-top: 5px;margin-bottom: 2px;">
                                                  <i class="fa fa-bell"></i> Kontrak Berlangsung &nbsp;
                                                </p>
                                                <p style="margin-top: 5px;margin-bottom: 2px;"> 
                                                 <span data-toggle="tooltip" data-placement="top" title="Perpanjang">
                                                  <a  target="_blank" href="<?php echo site_url('admin/legal/kontrak/').$employee->user_id.'/';?>">
                                                    <button  type="button" class="btn icon-btn btn-xs bg-gray waves-effect waves-light">
                                                    <i class="fa fa-plus"></i> Lihat Kontrak
                                                    </button>
                                                  </a>                                     
                                                </span>                                    
                                              </p>
                                          </small>
                                        </span> 
                                    </div>
                                  </li>
                                  <?php $no++; ?>
                              <?php } ?>
                          <?php } else { ?>
                                <li >                                     
                                    <div class="box-header bg-gray text-center">
                                      <h3 class="box-title"> <i class="fa fa-info-circle"></i> Tidak ada </h3>
                                    </div>
                                </li>
                          <?php } ?>
                        </ul>
                    </div>
                    </div>
                  </div>
                </div>
                
                <div class="col-xs-12 col-md-3 col-sm-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title"> <i class="fa fa-users"></i> Kontrak Akan Berakhir </h3>
                    </div>        
                    <div class="box-body" style="">
                      <div class="overflow-scrolls" style="overflow:auto; height:640px;">
                        <ul class="products-list product-list-in-box">
                          <?php if (count($last_five_kontrak_akan_berakhir) > 0) {?>
                              <?php $no = 1; ?>
                              <?php foreach($last_five_kontrak_akan_berakhir as $employee) {?>
                                  <?php 
                                        if($employee->profile_picture!='' && $employee->profile_picture!='no file') {
                                            $de_file = base_url().'uploads/profile/'.$employee->profile_picture;
                                        } else { 
                                            if($employee->gender=='Male') {  
                                            $de_file = base_url().'uploads/profile/default_male.jpg'; 
                                            } else {  
                                            $de_file = base_url().'uploads/profile/default_female.jpg';
                                            }
                                        }
                                        
                                        $company = $this->Core_model->read_company_info($employee->company_id);
                                        if(!is_null($company)){
                                          $comp_name = $company[0]->name;
                                        } else {
                                          $comp_name = '<span class="badge bg-red"> ? </span>';
                                        }
                                        
                                        $fname = substr($employee->first_name.' '.$employee->last_name,0,17);

                                        $designation = $this->Designation_model->read_designation_information($employee->designation_id);
                                        if(!is_null($designation)){
                                          $designation_name = $designation[0]->designation_name;
                                        } else {
                                          $designation_name = '--'; 
                                        }
                                        
                                       $department_designation =substr($designation_name,0,30);

                                        $date_of_joining = $this->Core_model->set_date_format($employee->date_of_joining);

                                        $reminder    = $this->Core_model->set_date_format($employee->notif);

                                        $selisih     = $employee->selisih;

                                        // $name_durasi = $employee->name_durasi;

                                        $kontrak     = $this->Core_model->set_date_format($employee->kontrak_from_date).' s/d '.$this->Core_model->set_date_format($employee->kontrak_end_date);

                                        ?>
                                  <li class="item">
                                    <div class="product-img"> <img src="<?php echo $de_file;?>" alt="<?php echo $fname;?>" class="rounded-circle-img"> </div>
                                    <div class="product-info"> 
                                        <b><?php echo $no;?>. <?php echo strtoupper($fname);?> ...</b> 
                                        <span class="product-description"> 
                                          <small>
                                              <p style="margin-top: 10px;">
                                              Mulai bekerja pada tanggal <b><?php echo $date_of_joining; ?></b>,
                                              dengan posisi level sebagai <b><?php echo $department_designation;?></b>
                                              berlokasi kerja di <b><?php echo $comp_name; ?></b>
                                              dengan kontrak terakhir : <b><?php echo $employee->name_type; ?> (<?php echo $employee->kontrak_no; ?>)</b>,
                                              periode kontrak <b><?php echo $kontrak; ?></b>.
                                              </p> 
                                              <p class ="blink blink-one badge bg-yellow" style="padding: 5px; margin-top: 5px;margin-bottom: 2px;"><i class="fa fa-bell"></i> 
                                                Kontrak <?php echo $selisih ?> hari akan berakhir &nbsp;
                                              </p>
                                              <p style="margin-top: 5px;margin-bottom: 2px;"> 
                                                 <span data-toggle="tooltip" data-placement="top" title="Perpanjang">
                                                  <a target="_blank" href="<?php echo site_url('admin/legal/kontrak/').$employee->user_id.'/';?>">
                                                    <button type="button" class="btn icon-btn btn-xs btn-warning waves-effect waves-light">
                                                    <i class="fa fa-plus"></i> Perpanjang Kontrak
                                                    </button>
                                                  </a>                                     
                                                </span>                                    
                                              </p>
                                          </small>
                                        </span> 
                                    </div>
                                  </li>
                                  <?php $no++; ?>
                              <?php } ?>
                          <?php } else { ?>
                                <li >                                     
                                    <div class="box-header bg-gray text-center">
                                      <h3 class="box-title"> <i class="fa fa-info-circle"></i> Tidak ada </h3>
                                    </div>
                                </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-xs-12 col-md-3 col-sm-12">
                  <div class="box box-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title"> <i class="fa fa-users"></i> Kontrak Sudah Berakhir</h3>
                    </div>        
                    <div class="box-body" style="">
                      <div class="overflow-scrolls" style="overflow:auto; height:640px;">
                        <ul class="products-list product-list-in-box">
                          <?php if (count($last_five_kontrak_sudah_berakhir) > 0) {?>
                              <?php $no = 1; ?>
                              <?php foreach($last_five_kontrak_sudah_berakhir as $employee) {?>
                                  <?php 
                                        if($employee->profile_picture!='' && $employee->profile_picture!='no file') {
                                            $de_file = base_url().'uploads/profile/'.$employee->profile_picture;
                                        } else { 
                                            if($employee->gender=='Male') {  
                                            $de_file = base_url().'uploads/profile/default_male.jpg'; 
                                            } else {  
                                            $de_file = base_url().'uploads/profile/default_female.jpg';
                                            }
                                        }
                                        
                                        $company = $this->Core_model->read_company_info($employee->company_id);
                                        if(!is_null($company)){
                                          $comp_name = $company[0]->name;
                                        } else {
                                          $comp_name = '<span class="badge bg-red"> ? </span>';
                                        }
                                        
                                        $fname = substr($employee->first_name.' '.$employee->last_name,0,17);                
                                        
                                        $designation = $this->Designation_model->read_designation_information($employee->designation_id);
                                        if(!is_null($designation)){
                                          $designation_name = $designation[0]->designation_name;
                                        } else {
                                          $designation_name = '--'; 
                                        }
                                        
                                       $department_designation =substr($designation_name,0,30);

                                        $date_of_joining = $this->Core_model->set_date_format($employee->date_of_joining);

                                        $reminder    = $this->Core_model->set_date_format($employee->notif);

                                        $selisih     = $employee->selisih;

                                        // $name_durasi = $employee->name_durasi;

                                        $kontrak     = $this->Core_model->set_date_format($employee->kontrak_from_date).' s/d '.$this->Core_model->set_date_format($employee->kontrak_end_date);
                                        ?>
                                  <li class="item">
                                    <div class="product-img"> <img src="<?php echo $de_file;?>" alt="<?php echo $fname;?>" class="rounded-circle-img"> </div>
                                    <div class="product-info"> 
                                        <b><?php echo $no;?>. <?php echo strtoupper($fname);?> ...</b> 
                                        <span class="product-description"> 
                                          <small>
                                              <p style="margin-top: 10px;">
                                              Mulai bekerja pada tanggal <b><?php echo $date_of_joining; ?></b>,
                                              dengan posisi level sebagai <b><?php echo $department_designation;?></b>
                                              berlokasi kerja di <b><?php echo $comp_name; ?></b>
                                              dengan kontrak terakhir : <b><?php echo $employee->name_type; ?> (<?php echo $employee->kontrak_no; ?>)</b>,
                                              periode kontrak <b><?php echo $kontrak; ?></b>.
                                              </p> 
                                              <p class ="blink blink-two badge bg-red" style="padding: 5px; margin-top: 5px;margin-bottom: 2px;"><i class="fa fa-bell"></i> 
                                                Kontrak <?php echo $selisih ?> hari sudah berakhir &nbsp;
                                              </p>

                                              <p style="margin-top: 5px;margin-bottom: 2px;"> 
                                                 <span data-toggle="tooltip" data-placement="top" title="Perpanjang">
                                                  <a target="_blank" href="<?php echo site_url('admin/legal/kontrak/').$employee->user_id.'/';?>">
                                                    <button type="button" class="btn icon-btn btn-xs btn-danger waves-effect waves-light">
                                                    <i class="fa fa-plus"></i> Perpanjang Kontrak
                                                    </button>
                                                  </a>                                     
                                                </span>                                    
                                              </p>

                                          </small>
                                        </span> 
                                    </div>
                                  </li>
                                  <?php $no++; ?>
                              <?php } ?>
                          <?php } else { ?>
                              <li >                                     
                                  <div class="box-header bg-gray text-center">
                                    <h3 class="box-title"> <i class="fa fa-info-circle"></i> Tidak ada </h3>
                                  </div>
                              </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                
            </div>
         </div>
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
<?php } ?> 

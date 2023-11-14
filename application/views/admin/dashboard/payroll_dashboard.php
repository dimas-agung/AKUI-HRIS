<?php 
$session = $this->session->userdata('username');
$user_info = $this->Core_model->read_user_info_detail($session['user_id']);
$theme = $this->Core_model->read_theme_info(1);
if($user_info[0]->profile_picture!='' && $user_info[0]->profile_picture!='no file') {
  $lde_file = base_url().'uploads/profile/'.$user_info[0]->profile_picture;
} else { 
  if($user_info[0]->gender=='Male') {  
    $lde_file = base_url().'uploads/profile/default_male.jpg'; 
  } else {  
    $lde_file = base_url().'uploads/profile/default_female.jpg';
  }
}
$last_login =  new DateTime($user_info[0]->last_login_date);
// get designation
$designation = $this->Designation_model->read_designation_information($user_info[0]->designation_id);
if(!is_null($designation)){
  $designation_name = $designation[0]->designation_name;
} else {
  $designation_name = '--'; 
}
$role_user = $this->Core_model->read_user_role_info($user_info[0]->user_role_id);
if(!is_null($role_user)){
  $role_resources_ids = explode(',',$role_user[0]->role_resources);
} else {
  $role_resources_ids = explode(',',0); 
}
?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $system = $this->Core_model->read_setting_info(1);?>

<div class="box-widget widget-user-2">
  <div class="widget-user-header">
    <h4 class="widget-user-username welcome-hris-user"><?php echo $this->lang->line('xin_title_wcb');?>, <?php echo $user_info[0]->first_name.' '.$user_info[0]->last_name;?>!</h4>
    <h5 class="widget-user-desc welcome-hris-user-text"> 
       <i class="fa fa-briefcase"></i>&nbsp;<?php echo $user_info[0]->department_name.', '.$user_info[0]->designation_name.' - '.$user_info[0]->company_name ;?>
     <div class="pull-right" >
      <i class="fa fa-clock-o"></i>&nbsp;
      <span class="pull-right" id="clock"></span>
    </div>
    </h5>
  </div>
</div>

<div class="row" >
    <div class="col-xl-6 col-md-3 col-12 hr-mini-state">
     
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
                TOTAL KARYAWAN AKTIF           
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Total  <?php echo $this->Employees_model->get_total_employees();?> 
                  </span>
                  
                  <span class="badge badge-info"> 
                     Pria <?php echo active_employees_male();?> 
                  </span>
                  
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Perempuan <?php echo active_employees_female();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
        
    </div>
    <div class="col-xl-6 col-md-3 col-12 hr-mini-state">
      
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
                 PT AKUI BIRD NEST INDONESIA             
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Total <?php echo $this->Employees_model->get_total_employees_1();?>  
                  </span>
                  <span class="badge badge-info"> 
                     Pria <?php echo active_employees_male_1();?> 
                  </span>
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Perempuan <?php echo active_employees_female_1();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
         
    </div>
    <div class="col-xl-6 col-md-3 col-12 hr-mini-state">
     
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
                PT ORIGINAL BERKAH INDONESIA              
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Total  <?php echo $this->Employees_model->get_total_employees_2();?>  
                  </span>
                  <span class="badge badge-info"> 
                     Pria <?php echo active_employees_male_2();?> 
                  </span>
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Perempuan <?php echo active_employees_female_2();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
          
    </div>
    <div class="col-xl-6 col-md-3 col-12 hr-mini-state">
      <a class="text-muted" href="<?php echo site_url('admin/overtime');?>">
        <div class="info-box hrsalle-mini-stat"> <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
            <div class="info-box-content"> 
              <span class="info-box-number">
                PT WALET ABDILLAH JABLI             
              </span> 
              <span class="info-box-text">
                  <span class="badge badge-primary"> 
                     Total   <?php echo $this->Employees_model->get_total_employees_3();?> 
                  </span>
                  <span class="badge badge-info"> 
                     Pria <?php echo active_employees_male_3();?> 
                    </span>
                  <span class="ml-2"> 
                    <span class="badge bg-red"> 
                      Perempuan <?php echo active_employees_female_3();?> 
                    </span>
                  </span> 
              </span> 
            </div>
        </div>
      </a>    
    </div>
</div>



<div class="row">
    <div class="col-xs-12 col-md-6 col-sm-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-briefcase"></i> Grade Karyawan</h3>
        </div>
        <div class="box-body">
          <div class="box-block">
            <div class="col-md-8">
              <canvas id="employee_grade"  height="260" width="" style="display: block;  height: 350px"/></canvas>             
            </div>
            <div class="col-md-4">
              <div class="overflow-scrolls" style="overflow:auto; height:350px;">
                <div class="table-responsive">
                  <table border ="0" class="table mb-0 table-dashboard">
                    <tbody>
                      <?php $c_color3 = array('#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                      <?php $ck=0;foreach($this->Core_model->all_grade_dash() as $grade) { ?>
                      <?php
                        $conditione1 = "grade_type =" . "'" . $grade->jenis_grade_keterangan . "'";
                        $conditione2 = "is_active = 1";
                        $this->db->select('*');
                        $this->db->from('xin_employees');
                        $this->db->where($conditione1);
                        $this->db->where($conditione2);
                        $cquery1 = $this->db->get();
                        // check if department available
                        if ($cquery1->num_rows() > 0) {
                      ?>
                      <tr>
                        <td width="2%"><div style="width:4px;border:5px solid <?php echo $c_color3[$ck];?>;"></div></td>
                        <td><?php echo htmlspecialchars_decode($grade->jenis_grade_keterangan);?> (<?php echo $cquery1->num_rows();?>)</td>
                      </tr>
                      <?php $ck++; } ?>
                      <?php  } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>            
          </div>
        </div>
      </div>
    </div>    
    <div class="col-xs-12 col-md-6 col-sm-12">
    <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-money"></i> Jenis Gaji</h3>
        </div>
        <div class="box-body">
          <div class="box-block">
            <div class="row">
              <div class="col-md-12">
                <canvas id="employee_jenis_payroll"  height="120" width="" style="display: block;  height: 200px;"></canvas>             
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="overflow-scrolls" style="overflow:auto; height:150px;">
                  <div class="table-responsive">
                    <table border ="0" class="table mb-0 table-dashboard">
                      <thead>
                        <tr>
                          <th style ="background-color: #fff;font-size:10px;"  colspan="2">PT AKUI BIRDNEST INDONESIA</th>
                        </tr>                     
                      </thead>
                      <tbody>
                        <?php $c_color4 = array('#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                        <?php $ck=0;foreach($this->Core_model->all_jenis_payroll_dash() as $jenis_gaji) { ?>
                        <?php
                          $conditione1 = "wages_type =" . "'" . $jenis_gaji->jenis_gaji_id . "'";
                          $conditione2 = "is_active = 1";
                          $conditione3 = "company_id = 1";
                          $this->db->select('*');
                          $this->db->from('xin_employees');
                          $this->db->where($conditione1);
                          $this->db->where($conditione2);
                          $this->db->where($conditione3);
                          $cquery1 = $this->db->get();
                          // check if department available
                          if ($cquery1->num_rows() > 0) {
                        ?>
                        <tr>
                          <td width="2%"><div style="width:4px;border:5px solid <?php echo $c_color4[$ck];?>;"></div></td>
                          <td><?php echo htmlspecialchars_decode($jenis_gaji->jenis_gaji_keterangan);?> (<?php echo $cquery1->num_rows();?>)</td>
                        </tr>
                        <?php $ck++; } ?>
                        <?php  } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div> 
              <div class="col-md-4">
                <div class="overflow-scrolls" style="overflow:auto; height:150px;">
                  <div class="table-responsive">
                     <table border ="0" class="table mb-0 table-dashboard">
                      <thead>
                        <tr>
                          <th style ="background-color: #fff;font-size:10px;"  colspan="2">PT ORIGINAL BERKAH INDONESIA</th>
                        </tr>                     
                      </thead>
                      <tbody>
                        <?php $c_color4 = array('#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                        <?php $ck=0;foreach($this->Core_model->all_jenis_payroll_dash() as $jenis_gaji) { ?>
                        <?php
                          $conditione1 = "wages_type =" . "'" . $jenis_gaji->jenis_gaji_id . "'";
                          $conditione2 = "is_active = 1";
                          $conditione3 = "company_id = 2";
                          $this->db->select('*');
                          $this->db->from('xin_employees');
                          $this->db->where($conditione1);
                          $this->db->where($conditione2);
                          $this->db->where($conditione3);
                          $cquery1 = $this->db->get();
                          // check if department available
                          if ($cquery1->num_rows() > 0) {
                        ?>
                        <tr>
                          <td width="2%"><div style="width:4px;border:5px solid <?php echo $c_color4[$ck];?>;"></div></td>
                          <td><?php echo htmlspecialchars_decode($jenis_gaji->jenis_gaji_keterangan);?> (<?php echo $cquery1->num_rows();?>)</td>
                        </tr>
                        <?php $ck++; } ?>
                        <?php  } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div> 
              <div class="col-md-4">
                <div class="overflow-scrolls" style="overflow:auto; height:150px;">
                  <div class="table-responsive">
                    <table border ="0" class="table mb-0 table-dashboard">
                      <thead>
                        <tr>
                          <th style ="background-color: #fff;font-size:10px;"  colspan="2">PT WALET ABDILLAH JABLI</th>
                        </tr>                     
                      </thead>
                      <table border ="0" class="table mb-0 table-dashboard">
                        <tbody>
                          <?php $c_color4 = array('#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                          <?php $ck=0;foreach($this->Core_model->all_jenis_payroll_dash() as $jenis_gaji) { ?>
                          <?php
                            $conditione1 = "wages_type =" . "'" . $jenis_gaji->jenis_gaji_id . "'";
                            $conditione2 = "is_active = 1";
                            $conditione3 = "company_id = 3";
                            $this->db->select('*');
                            $this->db->from('xin_employees');
                            $this->db->where($conditione1);
                            $this->db->where($conditione2);
                            $this->db->where($conditione3);
                            $cquery1 = $this->db->get();
                            // check if department available
                            if ($cquery1->num_rows() > 0) {
                          ?>
                          <tr>
                            <td width="2%"><div style="width:4px;border:5px solid <?php echo $c_color4[$ck];?>;"></div></td>
                            <td><?php echo htmlspecialchars_decode($jenis_gaji->jenis_gaji_keterangan);?> (<?php echo $cquery1->num_rows();?>)</td>
                          </tr>
                          <?php $ck++; } ?>
                          <?php  } ?>
                        </tbody>
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

<style type="text/css">
.btn-group {
  margin-top:5px !important;
}
</style>

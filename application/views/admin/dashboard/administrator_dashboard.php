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
        <h3 class="box-title">
          <i class="fa fa-bar-chart-o"></i> PT AKUI BIRDNEST INDONESIA                       
        </h3>
        <div class="pull-right"> 
           Per Departemen <?php echo date('Y');?>
        </div>
      </div>
      <div class="box-body">
        <div class="box-block">
          <div class="col-xs-12 col-md-12 col-sm-12">
            <canvas id="employee_department_birdnest" height="390" width="" style="display: block;  height: 398px;"></canvas>
            <!-- <canvas id="employee_department_trading" height="390" width="" style="display: block;  height: 398px;"></canvas> -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-sm-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-bar-chart-o"></i> PT ORIGINAL BERKAH INDONESIA                      
        </h3>
         <div class="pull-right"> 
           Per Departemen <?php echo date('Y');?>
        </div>
      </div>
      <div class="box-body">
        <div class="box-block">
          <div class="col-xs-12 col-md-12 col-sm-12">
            <canvas id="employee_department_trading" height="100" width="" style="display: block;  height: 100px;"></canvas>
            <!-- <canvas id="employee_department_trading" height="390" width="" style="display: block;  height: 398px;"></canvas> -->
          </div>
        </div>
      </div>
    </div>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <i class="fa fa-bar-chart-o"></i> PT WALET ABDILLAH JABLI                      
        </h3>
         <div class="pull-right"> 
           Per Departemen <?php echo date('Y');?>
        </div>
      </div>
      <div class="box-body">
        <div class="box-block">
          <div class="col-xs-12 col-md-12 col-sm-12">
            <canvas id="employee_department_asa" height="200" width="" style="display: block;  height: 205px;"></canvas>
            <!-- <canvas id="employee_department_trading" height="390" width="" style="display: block;  height: 398px;"></canvas> -->
          </div>
        </div>
      </div>
    </div>
  </div> 
 
</div> 

<div class="row">
    <div class="col-xs-12 col-md-6 col-sm-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-heart"></i> Agama Karyawan</h3>
        </div>
        <div class="box-body">
          <div class="box-block">
            <div class="col-md-8">
              <canvas id="employee_religi"  height="180" width="" style="display: block;  height: 250px;"></canvas>             
            </div>
            <div class="col-md-4">
              <div class="overflow-scrolls" style="overflow:auto; height:250px;">
                <div class="table-responsive">
                  <table border ="0" class="table mb-0 table-dashboard">
                    <tbody>
                      <?php $c_color1 = array('#975df3','#001f3f','#39cccc','#3c8dbc','#006400','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                      <?php $ck=0;foreach($this->Core_model->all_religi_dash() as $religi) { ?>
                      <?php
                        $conditione1 = "ethnicity_type =" . "'" . $religi->ethnicity_type_id . "'";
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
                        <td width="2%"><div style="width:4px;border:5px solid <?php echo $c_color1[$ck];?>;"></div></td>
                        <td><?php echo htmlspecialchars_decode($religi->type);?> (<?php echo $cquery1->num_rows();?>)</td>
                        <td> </td>
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
          <h3 class="box-title"><i class="fa fa-graduation-cap"></i> Pendidikan Karyawan</h3>
        </div>
        <div class="box-body">
          <div class="box-block">
            <div class="col-md-8">
              <canvas id="employee_education"  height="180" width="" style="display: block;  height: 250px;"></canvas>             
            </div>
            <div class="col-md-4">
              <div class="overflow-scrolls" style="overflow:auto; height:250px;">
                <div class="table-responsive">
                  <table border ="0" class="table mb-0 table-dashboard">
                    <tbody>
                      <?php $c_color2 = array('#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc');?>
                      <?php $ck=0;foreach($this->Core_model->all_education_dash() as $education) { ?>
                      <?php
                        $conditione1 = "education_level_id =" . "'" . $education->education_level_id . "'";
                         $conditione2 = "is_active = 1";
                        $this->db->select('*');
                        $this->db->from('view_employee_education');
                        $this->db->where($conditione1);
                        $this->db->where($conditione2);
                        $cquery1 = $this->db->get();
                        // check if department available
                        if ($cquery1->num_rows() > 0) {
                      ?>
                      <tr>
                        <td width="2%"><div style="width:4px;border:5px solid <?php echo $c_color2[$ck];?>;"></div></td>
                        <td><?php echo htmlspecialchars_decode($education->name);?> (<?php echo $cquery1->num_rows();?>)</td>
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
  
<div class="row">
    <div class="col-xs-12 col-md-6 col-sm-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-sign-in"></i> Karyawan Masuk (Rekrutmen)</h3>
        </div>
        <div class="box-body">
          <div class="box-block">
            <div class="col-md-8">
              <canvas id="employee_masuk"  height="180" width="" style="display: block;  height: 250px;"></canvas>             
            </div>
            <div class="col-md-4">
              <div class="overflow-scrolls" style="overflow:auto; height:250px;">
                <div class="table-responsive">
                  <table border ="0" class="table mb-0 table-dashboard">
                    <tbody>
                      <?php $c_color5 = array('#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                      <?php $ck=0;foreach($this->Core_model->all_masuk_dash() as $bulan) { ?>
                      <?php
                        $conditione1 = "bulan =" . "'" . $bulan->bulan . "'";
                        $conditione2 = "tahun =" . "'" . $bulan->tahun . "'";
                        // $conditione3 = "is_active = 1";
                        $this->db->select('*');
                        $this->db->from('view_statistik_karyawan_masuk');
                        $this->db->where($conditione1);
                        $this->db->where($conditione2);
                        // $this->db->where($conditione3);
                        $cquery1 = $this->db->get();
                        // check if department available
                        if ($cquery1->num_rows() > 0) {
                      ?>
                      <tr>
                        <td width="2%"><div style="width:4px;border:5px solid <?php echo $c_color5[$ck];?>;"></div></td>
                        <td><?php echo htmlspecialchars_decode($bulan->desc);?> (<?php echo $cquery1->num_rows();?>)</td>
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
          <h3 class="box-title"><i class="fa fa-sign-out"></i> Karyawan Keluar (Resign)</h3>
        </div>
        <div class="box-body">
          <div class="box-block">
            <div class="col-md-8">
              <canvas id="employee_keluar"  height="180" width="" style="display: block;  height: 250px;"></canvas>             
            </div>
            <div class="col-md-4">
              <div class="overflow-scrolls" style="overflow:auto; height:250px;">
                <div class="table-responsive">
                  <table border ="0" class="table mb-0 table-dashboard">
                    <tbody>
                      <?php $c_color6 = array('#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                      <?php $ck=0;foreach($this->Core_model->all_keluar_dash() as $bulan) { ?>
                      <?php
                        $conditione1 = "bulan =" . "'" . $bulan->bulan . "'";
                        $conditione2 = "tahun =" . "'" . $bulan->tahun . "'";
                        // $conditione3 = "is_active = 1";
                        $this->db->select('*');
                        $this->db->from('view_statistik_karyawan_keluar');
                        $this->db->where($conditione1);
                        $this->db->where($conditione2);
                        // $this->db->where($conditione3);
                        $cquery1 = $this->db->get();
                        // check if department available
                        if ($cquery1->num_rows() > 0) {
                      ?>
                      <tr>
                        <td width="2%"><div style="width:4px;border:5px solid <?php echo $c_color6[$ck];?>;"></div></td>
                        <td><?php echo htmlspecialchars_decode($bulan->desc);?> (<?php echo $cquery1->num_rows();?>)</td>
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
  
<div class="row">
  <div class="col-xs-12 col-md-12 col-sm-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"> <i class="fa fa-info-circle"></i> Informasi Pengajuan Karyawan Bulan ini</h3>
        <div class="box-tools pull-right">              
        </div>
      </div>
      <div class="box-body">
          
          <div class="row">
              <div class="col-xs-12 col-md-3 col-sm-12">
                  <div class="card hris-box-three hris-dash-info">
                      <div class="card-body">
                          <i class="fa fa-tags hris-dash-icon"></i>
                          <div class="hris-box-three-content">
                              
                              <p class="m-0 text-uppercase text-white font-600 font-secondary text-overflow" title="Karyawan Cuti">
                               Cuti                                 
                              </p>

                              <h3 class="text-white">
                                <span data-plugin="counterup">                                  
                                     <?php echo jum_cuti_bulan_ini();?>
                                </span> 
                                <small><i class="mdi mdi-arrow-up text-white"></i></small>
                              </h3>
                              
                              <p class="text-white m-0">
                                <span class="badge bg-green"> 
                                  Pria <?php echo jum_cuti_bulan_ini_male();?> 
                                </span>
                                <span class="ml-2"> 
                                  <span class="badge bg-red"> 
                                    Perempuan <?php echo jum_cuti_bulan_ini_female();?> 
                                  </span>
                                </span>
                              </p>

                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-md-3 col-sm-12">
                  <div class="card hris-box-three hris-dash-info">
                      <div class="card-body">
                          <i class="fa fa-medkit hris-dash-icon"></i>
                          <div class="hris-box-three-content">
                              <p class="m-0 text-uppercase text-white font-600 font-secondary text-overflow" title="Karyawan Sakit">
                               Sakit                                 
                              </p>
                              <h3 class="text-white">
                                <span data-plugin="counterup">                                  
                                     <?php echo jum_sakit_bulan_ini();?>                                  
                                </span> 
                                <small><i class="mdi mdi-arrow-up text-white"></i></small>
                              </h3>
                              
                              <p class="text-white m-0">
                                <span class="badge bg-green"> 
                                  Pria <?php echo jum_sakit_bulan_ini_male();?> 
                                </span>
                                <span class="ml-2"> 
                                  <span class="badge bg-red"> 
                                    Perempuan <?php echo jum_sakit_bulan_ini_female();?> 
                                  </span>
                                </span>
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-md-3 col-sm-12">
                  <div class="card hris-box-three hris-dash-info">
                      <div class="card-body">
                          <i class="fa fa-info hris-dash-icon"></i>
                          <div class="hris-box-three-content">
                              <p class="m-0 text-uppercase text-white font-600 font-secondary text-overflow" title="<?php echo $this->lang->line('xin_people');?>">
                                Izin                                  
                              </p>
                              <h3 class="text-white">
                                <span data-plugin="counterup">
                                  
                                     <?php echo jum_izin_bulan_ini();?>                                    
                                
                                </span> 
                                <small><i class="mdi mdi-arrow-up text-white"></i></small>
                              </h3>
                              
                              <p class="text-white m-0">
                                <span class="badge bg-green"> 
                                  Pria <?php echo jum_izin_bulan_ini_male();?> 
                                </span>
                                <span class="ml-2"> 
                                  <span class="badge bg-red"> 
                                    Perempuan <?php echo jum_izin_bulan_ini_female();?> 
                                  </span>
                                </span>
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-xs-12 col-md-3 col-sm-12">
                  <div class="card hris-box-three hris-dash-info">
                      <div class="card-body">
                          <i class="fa fa-calendar hris-dash-icon"></i>
                          <div class="hris-box-three-content">
                              <p class="m-0 text-uppercase text-white font-600 font-secondary text-overflow" title="<?php echo $this->lang->line('xin_people');?>">
                               Lembur                                 
                              </p>
                              <h3 class="text-white">
                                <span data-plugin="counterup">
                                  
                                     <?php echo jum_lembur_bulan_ini(); ?>                                   
                                
                                </span> 
                                <small><i class="mdi mdi-arrow-up text-white"></i></small>
                              </h3>
                              
                              <p class="text-white m-0">
                                <span class="badge badge-info"> 
                                  Pria <?php echo jum_lembur_bulan_ini_male();?> 
                                </span>
                                <span class="ml-2"> 
                                  <span class="badge bg-red"> 
                                    Perempuan <?php echo jum_lembur_bulan_ini_female();?> 
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
                    <h3 class="box-title"> <i class="fa fa-users"></i> <b><?php echo jum_cuti_bulan_ini();?></b> Karyawan Cuti Bulan ini </h3>
                  </div>        
                  <div class="box-body" style="">
                    <div class="overflow-scrolls" style="overflow:auto;  height:290px;">
                      <ul class="products-list product-list-in-box">
                        <?php $no = 1; ?>
                        <?php if (count($last_five_leave) > 0) {?>
                              <?php foreach($last_five_leave as $employee) {?>
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

                                    $join = $this->Core_model->set_date_format($employee->from_date).' s/d '.$this->Core_model->set_date_format($employee->to_date)
                                    ?>
                                    <li class="item">
                                      <div class="product-img"> <img src="<?php echo $de_file;?>" alt="<?php echo $fname;?>" class="rounded-circle-img"> </div>
                                      <div class="product-info"> 
                                          <b><?php echo $no; ?>. <?php echo strtoupper($fname);?> ...</b> 
                                          <span class="product-description"> 
                                            <small>
                                            <?php echo $comp_name; ?><br>
                                            <?php echo $department_designation;?> ... <br>
                                            <?php echo $join; ?>
                                            </small>
                                          </span> 
                                      </div>
                                    </li>
                              <?php $no++; ?>                        
                              <?php }  ?>
                          <?php } else { ?>
                                <li >                                     
                                    <div class="box-header alert-warning text-center">
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
                    <h3 class="box-title"> <i class="fa fa-users"></i> <b><?php echo jum_sakit_bulan_ini();?></b> Karyawan Sakit Bulan ini </h3>
                  </div>        
                  <div class="box-body" style="">
                    <div class="overflow-scrolls" style="overflow:auto;  height:290px;">
                      <ul class="products-list product-list-in-box">
                        <?php $no = 1; ?>
                        
                        <?php if (count($last_five_sick) > 0) {?>
                          
                              <?php foreach($last_five_sick as $employee) {?>
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

                                    $join = $this->Core_model->set_date_format($employee->from_date).' s/d '.$this->Core_model->set_date_format($employee->to_date);
                                    ?>
                              <li class="item">
                                <div class="product-img"> <img src="<?php echo $de_file;?>" alt="<?php echo $fname;?>" class="rounded-circle-img"> </div>
                                <div class="product-info"> 
                                   <b><?php echo $no; ?>. <?php echo strtoupper($fname);?> ...</b> 
                                    <span class="product-description"> 
                                      <small>
                                      <?php echo $comp_name; ?><br>
                                      <?php echo $department_designation;?> ... <br>
                                      <?php echo $join; ?>
                                      </small>
                                    </span> 
                                </div>
                              </li>
                              <?php $no++; ?>
                              <?php } ?>

                          <?php } else { ?>
                                <li >                                     
                                    <div class="box-header alert-warning text-center">
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
                    <h3 class="box-title"> <i class="fa fa-users"></i> <b><?php echo jum_izin_bulan_ini();?></b> Karyawan Izin Bulan ini</h3>
                  </div>        
                  <div class="box-body" style="">
                    <div class="overflow-scrolls" style="overflow:auto;  height:290px;">
                      <ul class="products-list product-list-in-box">
                        <?php $no = 1; ?>

                        <?php if (count($last_five_izin) > 0) {?>
                        
                              <?php foreach($last_five_izin as $employee) {?>
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

                                        $join = $this->Core_model->set_date_format($employee->from_date).' s/d '.$this->Core_model->set_date_format($employee->to_date);
                                        ?>
                                  <li class="item">
                                    <div class="product-img"> <img src="<?php echo $de_file;?>" alt="<?php echo $fname;?>" class="rounded-circle-img"> </div>
                                    <div class="product-info"> 
                                        <b><?php echo $no; ?>. <?php echo strtoupper($fname);?> ...</b> 
                                        <span class="product-description"> 
                                          <small>
                                          <?php echo $comp_name; ?><br>
                                          <?php echo $department_designation;?> ... <br>
                                          <?php echo $join; ?>
                                          </small>
                                        </span> 
                                    </div>
                                  </li>
                                  <?php $no++; ?>
                              <?php } ?>
                        
                        <?php } else { ?>
                                <li >                                     
                                    <div class="box-header alert-warning text-center">
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
                    <h3 class="box-title"> <i class="fa fa-users"></i> <b><?php echo jum_lembur_bulan_ini();?></b> Karyawan Lembur Bulan ini</h3>
                  </div>        
                  <div class="box-body" style="">
                    <div class="overflow-scrolls" style="overflow:auto; height:290px;">
                      <ul class="products-list product-list-in-box">
                        <?php $no = 1; ?>
                        
                        <?php if (count($last_five_lembur) > 0) {?>
                              
                              <?php foreach($last_five_lembur as $employee) {?>
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
                                          $fname = substr($employee->first_name.' '.$employee->last_name,0,15);                
                                          $designation = $this->Designation_model->read_designation_information($employee->designation_id);
                                          if(!is_null($designation)){
                                            $designation_name = $designation[0]->designation_name;
                                          } else {
                                            $designation_name = '--'; 
                                          }
                                          
                                          $department_designation =substr($designation_name,0,30);

                                          $join = $this->Core_model->set_date_format($employee->overtime_date);
                                          ?>
                                    <li class="item">
                                      <div class="product-img"> <img src="<?php echo $de_file;?>" alt="<?php echo $fname;?>" class="rounded-circle-img"> </div>
                                      <div class="product-info"> 
                                           <b><?php echo $no; ?>. <?php echo strtoupper($fname);?> ...</b>  
                                          <span class="product-description"> 
                                            <small>
                                            <?php echo $comp_name; ?><br>
                                            <?php echo $department_designation;?> ... <br>
                                            <?php echo $join; ?>
                                            </small>
                                          </span> 
                                      </div>
                                    </li>
                                    <?php $no++; ?>
                              <?php } ?>

                        <?php } else { ?>
                                <li >                                     
                                    <div class="box-header alert-warning text-center">
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

<div class="row" style="display: none;">
  <div class="col-md-6">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-tags"></i> <?php echo $this->lang->line('xin_latest_leave');?></h3>
        <div class="box-tools pull-right"> <a href="<?php echo site_url('admin/permission/leave/');?>">
            <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-eye"></span> <?php echo $this->lang->line('xin_view_all');?></button>
            </a> </div>
      </div>
      <div class="box-body">
        <table class="table table-striped table-bordered">
          <tbody>
            <tr>
                <th><?php echo $this->lang->line('xin_leave_type');?></th>
                <th><?php echo $this->lang->line('xin_employee');?></th>
                <th><i class="fa fa-calendar"></i> <?php echo $this->lang->line('xin_leave_duration');?></th>
                <th>Tanggal<br>Pengajuan</th>
               
            </tr>
            <?php $role_resources_ids = $this->Core_model->user_role_resource(); foreach(total_last_leaves() as $ls_leaves):?>
                <?php
                         // get start date and end date
                $user = $this->Core_model->read_user_info_detail($ls_leaves->employee_id);
                if(!is_null($user)){
                  $full_name = $user[0]->first_name. ' '.$user[0]->last_name.' <br><i class="fa fa-briefcase"></i> '.$user[0]->designation_name;
                } else {
                  $full_name = '--';  
                }
                 
                // get leave type
                $leave_type = $this->Timesheet_model->read_leave_type_information($ls_leaves->leave_type_id);
                if(!is_null($leave_type)){
                  $type_name = $leave_type[0]->type_name;
                } else {
                  $type_name = '--';  
                }
                 
                $datetime1 = new DateTime($ls_leaves->from_date);
                $datetime2 = new DateTime($ls_leaves->to_date);
                $interval = $datetime1->diff($datetime2);
                if(strtotime($ls_leaves->from_date) == strtotime($ls_leaves->to_date)){
                  $no_of_days =1;
                } else {
                  $no_of_days = $interval->format('%a') + 1;
                }
                $applied_on = $this->Core_model->set_date_format($ls_leaves->applied_on);
                 /*$duration = $this->Core_model->set_date_format($ls_leaves->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($ls_leaves->to_date).'<br>'.$this->lang->line('xin_hris_total_days').': '.$no_of_days;*/
                
                 if($ls_leaves->is_half_day == 1){
                $duration = $this->Core_model->set_date_format($ls_leaves->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($ls_leaves->to_date).'<br><i class="fa fa-angle-double-right"></i>  '.$this->lang->line('xin_hris_total_days').': '.$this->lang->line('xin_hr_leave_half_day');
                } else {
                  $duration = $this->Core_model->set_date_format($ls_leaves->from_date).' '.$this->lang->line('dashboard_to').' '.$this->Core_model->set_date_format($ls_leaves->to_date).'<br><i class="fa fa-angle-double-right"></i> '.$this->lang->line('xin_hris_total_days').': '.$no_of_days;
                }
                
                if($ls_leaves->status==1): $status = '<span class="badge bg-orange">'.$this->lang->line('xin_pending').'</span>';
                elseif($ls_leaves->status==2): $status = '<span class="badge bg-green">'.$this->lang->line('xin_approved').'</span>';
                elseif($ls_leaves->status==4): $status = '<span class="badge bg-green">'.$this->lang->line('xin_role_first_level_approved').'</span>';
                else: $status = '<span class="badge bg-red">'.$this->lang->line('xin_rejected').'</span>'; endif;
                
                $itype_name = $type_name.'<br><small class="text-muted"><i>'.$this->lang->line('xin_reason').': '.$ls_leaves->reason.'<i></i></i></small>';
                ?>
            <tr>
                <td><a href="<?php echo site_url('admin/permission/leave_details/id/').$ls_leaves->leave_id.'/';?>"><?php echo $type_name;?></a></td>
                <td><?php echo $full_name;?></td>
                <td><?php echo $duration;?></td>
                <td><center><?php echo $applied_on;?></center></td>
                
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
  
    <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-child"></i> Hari libur bulan ini </h3>
              <div class="box-tools pull-right"> <a href="<?php echo site_url('admin/pengaturan/holidays/');?>">
                <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-eye"></span> <?php echo $this->lang->line('xin_view_all');?></button>
                </a> </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             <table class="table table-striped table-bordered">
              <tbody>
                <tr>
                    <th><?php echo $this->lang->line('xin_event_name');?></th>
                    <th>Tanggal<br>Mulai</th>
                    <th>Tanggal<br>Sampai</th>
                </tr>
                   <?php $role_resources_ids = $this->Core_model->user_role_resource();  ?>

                   <?php if (count(total_last_holidays()) > 0 ){ ?>
                          <?php    foreach(total_last_holidays() as $ls_holidays):
                          ?>
                          
                          <?php                           
                             $sdate = $this->Core_model->set_date_format($ls_holidays->start_date);
                             $edate = $this->Core_model->set_date_format($ls_holidays->end_date);
                          ?>
                      <tr>
                          <td><?php echo $ls_holidays->event_name;?></td>
                          <td width="17%"><center><?php echo $sdate;?></center></td>
                          <td width="17%"><center><?php echo $edate;?></center></td>
                      </tr>
                      <?php endforeach;?>
                  <?php } else{ ?>
                    <tr>
                          <td colspan="3"><div class="box-header bg-gray text-center">
                            <h3 class="box-title"> <i class="fa fa-info-circle"></i> Tidak ada hari libur bulan ini </h3>
                          </div></td>
                         
                      </tr>
                  <?php } ?>
                </tbody>
            </table>    
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-tasks"></i> Lembur Terbaru</h3>
              <div class="box-tools pull-right"> <a href="<?php echo site_url('admin/overtime/');?>">
                <button type="button" class="btn btn-xs btn-primary"> <span class="fa fa-eye"></span> <?php echo $this->lang->line('xin_view_all');?></button>
                </a> </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-striped table-bordered">
              <tbody>
                <tr>
                    <th><?php echo $this->lang->line('xin_employee');?></th>
                    <th>Durasi<br>Lembur</th>
                    <th>Tanggal<br>Lembur</th>
                </tr>
                 <?php if (count(total_last_overtime_request()) > 0 ){ ?>

                      <?php foreach(total_last_overtime_request() as $ls_overtime):?>
                          <?php
                                  // total work
                          $in_time = new DateTime($ls_overtime->clock_in_m);
                          $out_time = new DateTime($ls_overtime->clock_out_m);
                          
                          $employee_id = $this->Core_model->read_user_info_detail($ls_overtime->employee_id);  
                          if(!is_null($employee_id)) {
                            $full_name = $employee_id[0]->first_name.' '.$employee_id[0]->last_name.' ('.$employee_id[0]->designation_name.')';
                          } else {
                            $full_name = '';
                          }
                          
                          
                          $clock_in = $in_time->format('h:i a');      
                          // attendance date
                          $att_date_in = explode(' ',$ls_overtime->clock_in_m);
                          $att_date_out = explode(' ',$ls_overtime->clock_out_m);
                          $request_date = $this->Core_model->set_date_format($ls_overtime->overtime_date);
                          $cin_date = $clock_in;
                          if($ls_overtime->clock_out_m=='') {
                            $cout_date = '-';
                            $total_time = '-';
                          } else {
                            $clock_out = $out_time->format('h:i a');
                            $interval = $in_time->diff($out_time);
                            $hours  = $interval->format('%h');
                            $minutes = $interval->format('%i');     
                            $total_time = $hours ."h ".$minutes."m";
                            $cout_date = $clock_out;
                          }
                          
                         
                            $status =$ls_overtime->description;
                         
                          
                          ?>
                      <tr>
                          <td><?php echo $full_name;?><br><i class="fa fa-calendar-plus-o"></i> <?php echo $ls_overtime->description;?></td>
                          <td width="17%"><center><?php echo $total_time;?></center></td>
                          <td width="17%"><center><?php echo $request_date;?></center></td>
                      </tr>
                      <?php endforeach;?>
                <?php } else{ ?>
                    <tr>
                          <td colspan="3"><div class="box-header bg-gray text-center">
                            <h3 class="box-title"> <i class="fa fa-info-circle"></i> Tidak ada lembur baru</h3>
                          </div></td>
                         
                      </tr>
                  <?php } ?>
                </tbody>
            </table>   
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </div>
  </div>
</div>
<style type="text/css">
.btn-group {
  margin-top:5px !important;
}
</style>

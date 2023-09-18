<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php
    $session = $this->session->userdata('username');
    $theme = $this->Core_model->read_theme_info(1);
    $user_info = $this->Core_model->read_user_info($session['user_id']);
    if($user_info[0]->is_active!=1) {
      redirect('admin/');
    }

?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $system = $this->Core_model->read_setting_info(1);?>


<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?php echo $this->lang->line('xin_hr_statistik_title');?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row <?php echo $get_animate;?>">
              <div class="col-md-6">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('xin_employee_department_txt');?></h3>
                  </div>
                  <div class="box-body">
                    <div class="box-block">
                      <div class="col-md-7">
                        <div class="overflow-scrolls" style="overflow:auto; height:320px;">
                          <div class="table-responsive">
                            <table class="table mb-0 table-dashboard">
                              <tbody>
                                <?php $c_color = array('#00A5A8','#FF4558','#16D39A','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC','#00A5A8','#FF4558','#16D39A','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC','#00A5A8','#FF4558','#16D39A','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC','#00A5A8','#FF4558','#16D39A','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC','#00A5A8','#FF4558','#16D39A','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC','#00A5A8','#FF4558','#16D39A','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC','#00A5A8','#FF4558','#16D39A','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC','#00A5A8','#FF4558','#16D39A','#8A2BE2','#D2691E','#6495ED','#DC143C','#006400','#556B2F','#9932CC');?>
                                <?php $j=0;foreach($this->Department_model->all_departments() as $department) { ?>
                                <?php
                                  $condition1 = "department_id =" . "'" . $department->department_id . "'";
                                  $condition2 = "is_active = 1";
                                  $this->db->select('*');
                                  $this->db->from('xin_employees');
                                  $this->db->where($condition1);
                                  $this->db->where($condition2);
                                  $query = $this->db->get();
                                  // check if department available
                                  if ($query->num_rows() > 0) {
                                ?>
                                <tr>
                                  <td><div style="width:4px;border:5px solid <?php echo $c_color[$j];?>;"></div></td>
                                  <td><?php echo htmlspecialchars_decode($department->department_name);?> (<?php echo $query->num_rows();?>)</td>
                                </tr>
                                <?php $j++; } ?>
                                <?php  } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <canvas id="employee_department" height="200" width="" style="display: block;  height: 320px;"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('xin_employee_designation_txt');?></h3>
                  </div>
                  <div class="box-body">
                    <div class="box-block">
                      <div class="col-md-7">
                        <div class="overflow-scrolls" style="overflow:auto; height:320px;">
                          <div class="table-responsive">
                            <table class="table mb-0 table-dashboard">
                              <tbody>
                                <?php $c_color2 = array('#9932CC','#556B2F','#16D39A','#DC143C','#D2691E','#8A2BE2','#FF976A','#FF4558','#00A5A8','#6495ED','#9932CC','#556B2F','#16D39A','#DC143C','#D2691E','#8A2BE2','#FF976A','#FF4558','#00A5A8','#6495ED','#9932CC','#556B2F','#16D39A','#DC143C','#D2691E','#8A2BE2','#FF976A','#FF4558','#00A5A8','#6495ED','#9932CC','#556B2F','#16D39A','#DC143C','#D2691E','#8A2BE2','#FF976A','#FF4558','#00A5A8','#6495ED','#9932CC','#556B2F','#16D39A','#DC143C','#D2691E','#8A2BE2','#FF976A','#FF4558','#00A5A8','#6495ED','#9932CC','#556B2F','#16D39A','#DC143C','#D2691E','#8A2BE2','#FF976A','#FF4558','#00A5A8','#6495ED','#9932CC','#556B2F','#16D39A','#DC143C','#D2691E','#8A2BE2','#FF976A','#FF4558','#00A5A8','#6495ED','#9932CC','#556B2F','#16D39A','#DC143C','#D2691E','#8A2BE2','#FF976A','#FF4558','#00A5A8','#6495ED');?>
                                <?php $k=0;foreach($this->Designation_model->all_designations() as $designation) { ?>
                                <?php
                                  $condition3 = "designation_id =" . "'" . $designation->designation_id . "'";
                                  $condition4 = "is_active = 1";
                                  $this->db->select('*');
                                  $this->db->from('xin_employees');
                                  $this->db->where($condition3);
                                  $this->db->where($condition4);
                                  $query1 = $this->db->get();
                                  // check if department available
                                  if ($query1->num_rows() > 0) {
                                ?>
                                <tr>
                                  <td><div style="width:4px;border:5px solid <?php echo $c_color2[$k];?>;"></div></td>
                                  <td><?php echo htmlspecialchars_decode($designation->designation_name);?> (<?php echo $query1->num_rows();?>)</td>
                                </tr>
                                <?php $k++; } ?>
                                <?php  } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                         <canvas id="employee_designation" height="200" width="" style="display: block;  height: 320px;"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row <?php echo $get_animate;?>">
              <div class="col-md-6">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('xin_employee_location_txt');?></h3>
                  </div>
                  <div class="box-body">
                    <div class="box-block">
                      <div class="col-md-7">
                        <div class="overflow-scrolls" style="overflow:auto; height:200px;">
                          <div class="table-responsive">
                            <table class="table mb-0 table-dashboard">
                              <tbody>
                                <?php $c_color3 = array('#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                                <?php $lj=0;foreach($this->Core_model->all_locations() as $location) { ?>
                                <?php
                                  $lcondition1 = "location_id =" . "'" . $location->location_id . "'";
                                   $lcondition2 = "is_active = 1";
                                  $this->db->select('*');
                                  $this->db->from('xin_employees');
                                  $this->db->where($lcondition1);
                                  $this->db->where($lcondition2);
                                  $lquery = $this->db->get();
                                  // check if department available
                                  if ($lquery->num_rows() > 0) {

                                    $total = 1;
                                ?>
                                <tr>
                                  <td><div style="width:4px;border:5px solid <?php echo $c_color3[$lj];?>;"></div></td>
                                  <td><?php echo htmlspecialchars_decode($location->location_name);?> (<?php echo $lquery->num_rows();?>)</td>
                                </tr>
                                
                                <?php $lj++; } ?>
                                <?php  } ?>

                                <!-- <tr>
                                  <td colspan="2"> Jumlah : <?php echo $total; ?></td>                     
                                </tr> -->
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <canvas id="employee_location" height="200" width="" style="display: block;  height: 200px;"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('xin_employee_company_txt');?></h3>
                  </div>
                  <div class="box-body">
                    <div class="box-block">
                      <div class="col-md-7">
                        <div class="overflow-scrolls" style="overflow:auto; height:200px;">
                          <div class="table-responsive">
                            <table class="table mb-0 table-dashboard">
                              <tbody>
                                <?php $c_color4 = array('#975df3','#001f3f','#39cccc','#3c8dbc','#006400','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b','#46be8a','#f96868','#00c0ef','#3c8dbc','#f39c12','#605ca8','#d81b60','#001f3f','#39cccc','#3c8dbc','#006400','#dd4b39','#a98852','#b26fc2','#66456e','#c674ad','#975df3','#61a3ca','#6bddbd','#6bdd74','#95b655','#668b20','#bea034','#d3733b');?>
                                <?php $ck=0;foreach($this->Core_model->all_companies_dash() as $ecompany) { ?>
                                <?php
                                  $conditione1 = "company_id =" . "'" . $ecompany->company_id . "'";
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
                                  <td><div style="width:4px;border:5px solid <?php echo $c_color4[$ck];?>;"></div></td>
                                  <td><?php echo htmlspecialchars_decode($ecompany->name);?> (<?php echo $cquery1->num_rows();?>)</td>
                                </tr>
                                <?php $ck++; } ?>
                                <?php  } ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <canvas id="employee_company" height="200" width="" style="display: block; height: 200px;"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>  
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
</div>

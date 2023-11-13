<?php

    $session = $this->session->userdata('username');
    $user    = $this->Core_model->read_employee_info($session['user_id']);
    $theme   = $this->Core_model->read_theme_info(1);
    
    // set layout / fixed or static
    if($theme[0]->right_side_icons=='true') {
      $icons_right = 'expanded menu-icon-right';
    } else {
      $icons_right = '';
    }
    
    if($theme[0]->bordered_menu=='true') {
      $menu_bordered = 'menu-bordered';
    } else {
      $menu_bordered = '';
    }

    $user_info = $this->Core_model->read_user_info($session['user_id']);
    if($user_info[0]->is_active!=1) {
      redirect('admin/');
    }
    
    $role_user = $this->Core_model->read_user_role_info($user_info[0]->user_role_id);
    if(!is_null($role_user)){
      $role_resources_ids = explode(',',$role_user[0]->role_resources);
    } else {
      $role_resources_ids = explode(',',0); 
    }
?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<?php $arr_mod = $this->Core_model->select_module_class($this->router->fetch_class(),$this->router->fetch_method()); ?>
<?php 
    if($theme[0]->sub_menu_icons != ''){
      $submenuicon = $theme[0]->sub_menu_icons;
    } else {
      $submenuicon = 'fa-circle-o';
    }
?>
<?php  if($user_info[0]->profile_picture!='' && $user_info[0]->profile_picture!='no file') {?>
<?php $cpimg = base_url().'uploads/profile/'.$user_info[0]->profile_picture;?>
<?php } else {?>
<?php  if($user_info[0]->gender=='Male') { ?>
<?php   $de_file = base_url().'uploads/profile/default_male.jpg';?>
<?php } else { ?>
<?php   $de_file = base_url().'uploads/profile/default_female.jpg';?>
<?php } ?>
<?php $cpimg = $de_file;?>
<?php  } ?>
<section class="sidebar">
  <!-- Sidebar user panel -->
  
  <div class="user-panel text-center">    
    <div class="image text-center">
      <img src="<?php echo $cpimg;?>" class="img-circle" alt="<?php echo $user_info[0]->first_name. ' '.$user_info[0]->last_name;?>"> 
    </div>
      <div class="info">       
        <p><?php echo $role_user[0]->role_name ;?></p>      
        <a href="<?php echo site_url('admin/auth/lock');?>"><i class="fa fa-lock"></i></a>
        <a href="<?php echo site_url('admin/profile?change_password=true');?>"> <i class="fa fa-key"></i></a>       
      </div>
  </div>
  
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu" data-widget="tree">
    
    <li class="header nav-small-cap"><?php echo $this->lang->line('dashboard_main');?></li>    
    
    <!-- ======================================================================================================== -->
    <!-- DASHBOARD  -->
    <!-- ======================================================================================================== -->

          <li class="<?php if(!empty($arr_mod['active']))echo $arr_mod['active'];?>"> 
            <a href="<?php echo site_url('admin/dashboard');?>"> 
              <i class="fa fa-dashboard"></i> 
              <span><?php echo $this->lang->line('dashboard_title');?></span> 
            </a> 
          </li>    

    <!-- ======================================================================================================== -->
    <!-- DATA  -->
    <!-- ======================================================================================================== -->
        <?php  if( in_array('0100',$role_resources_ids) ||
                   // Sub
                   in_array('0101',$role_resources_ids) || in_array('0102',$role_resources_ids) || in_array('0103',$role_resources_ids) 
                    || in_array('0104',$role_resources_ids) 
                ) 
              { ?>    
        
            <li class="<?php if(!empty($arr_mod['system_open']))echo $arr_mod['system_open'];?> treeview"> 
                <a href="#"> <i class="fa fa-cubes"></i> 
                  <span><?php echo $this->lang->line('xin_system');?></span> 
                  <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
                </a>

              <ul class="treeview-menu">
                
                <?php if(in_array('0101',$role_resources_ids)) { ?>
                    <li class="sidenav-link <?php if(!empty($arr_mod['constants_active']))echo $arr_mod['constants_active'];?>"> <a href="<?php echo site_url('admin/settings/constants');?>"> <i class="fa fa-cube"></i> Master Data </a> </li>
                <?php } ?>
                
                <?php if(in_array('0102',$role_resources_ids)) { ?>
                    <li class="sidenav-link <?php if(!empty($arr_mod['db_active']))echo $arr_mod['db_active'];?>"> <a href="<?php echo site_url('admin/settings/database_backup');?>"> <i class="fa fa-database"></i> Backup Data </a> </li>
                <?php } ?>

                <?php if(in_array('0103',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['import_active']))echo $arr_mod['import_active'];?>"> <a href="<?php echo site_url('admin/import');?>"> <i class="fa fa-cloud-download"></i> Impor Produktifitas </a> </li>
                <?php } ?>

                <?php if(in_array('0104',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['rekap_active']))echo $arr_mod['rekap_active'];?>"> <a href="<?php echo site_url('admin/rekap');?>"> <i class="fa fa-briefcase"></i> Rekap Produktifitas </a> </li>
                <?php } ?>    

               

              </ul>
            </li>

        <?php } ?>
   
    <!-- ======================================================================================================== -->
    <!-- ORGANISASI  -->
    <!-- ======================================================================================================== -->

        <?php  if(  in_array('0200',$role_resources_ids) ||
                    // Sub 
                    in_array('0210',$role_resources_ids) || 
                    in_array('0220',$role_resources_ids) || 
                    in_array('0230',$role_resources_ids) || 
                    in_array('0240',$role_resources_ids) ||
                    in_array('0250',$role_resources_ids) 
                                        
                  )
                { ?>

                <li class="<?php if(!empty($arr_mod['adm_open']))echo $arr_mod['adm_open'];?> treeview"> <a href="#"> <i class="fa fa-institution"></i> <span><?php echo $this->lang->line('left_organization');?></span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
                  
                  <ul class="treeview-menu">
                    
                    <?php if(in_array('0210',$role_resources_ids)) { ?>        
                        <li class="sidenav-link <?php if(!empty($arr_mod['com_active']))echo $arr_mod['com_active'];?>"><a href="<?php echo site_url('admin/company')?>"><i class="fa fa-building"></i> <?php echo $this->lang->line('left_company');?></a></li>
                   <?php } ?>
                    
                    <?php if(in_array('0220',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['loc_active']))echo $arr_mod['loc_active'];?>"><a href="<?php echo site_url('admin/location');?>"><i class="fa fa-globe"></i> <?php echo $this->lang->line('left_location');?></a></li>
                    <?php } ?>

                    <?php if(in_array('0230',$role_resources_ids)) { ?>
                       <li class="sidenav-link <?php if(!empty($arr_mod['dep_active']))echo $arr_mod['dep_active'];?>"><a href="<?php echo site_url('admin/department');?>"><i class="fa fa-briefcase"></i> <?php echo $this->lang->line('left_department');?></a></li>
                    <?php } ?>
                   
                    <?php if(in_array('0250',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['work_active']))echo $arr_mod['work_active'];?>"><a href="<?php echo site_url('admin/workstation');?>"><i class="fa fa-filter"></i> <?php echo $this->lang->line('left_workstation');?></a></li>
                    <?php } ?>  
                             
                    <?php if(in_array('0240',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['des_active']))echo $arr_mod['des_active'];?>"><a href="<?php echo site_url('admin/designation');?>"><i class="fa fa-tags"></i> <?php echo $this->lang->line('left_designation');?></a></li>
                    <?php } ?>                                                     
                   
                  </ul>
                </li>
        <?php } ?>

    <!-- ======================================================================================================== -->
    <!-- REKRUTMEN  -->
    <!-- ======================================================================================================== -->

        <?php if(in_array('0300',$role_resources_ids) || 
                 // Sub
                 in_array('0310',$role_resources_ids) || $user_info[0]->user_role_id == 1 
               )
            { ?>

            <li class="<?php if(!empty($arr_mod['rek_open']))echo $arr_mod['rek_open'];?> treeview"> 

              <a href="#"> <i class="fa fa-user"></i> 
                  <span><?php echo $this->lang->line('left_recruitment');?></span> 
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i> 
                  </span> 
              </a>
              
              <ul class="treeview-menu">                
                <?php if(in_array('0310',$role_resources_ids)) { ?>
                     <li class="<?php if(!empty($arr_mod['rek_active']))echo $arr_mod['rek_active'];?>"><a href="<?php echo site_url('admin/employees_new');?>"><i class="fa fa-plus"></i> <?php echo $this->lang->line('dashboard_employees_new');?></a></li>
                <?php } ?>
              </ul>
              
            </li>
        <?php } ?>   

    <!-- ======================================================================================================== -->
    <!-- ATRIBUT KERJA  -->
    <!-- ======================================================================================================== -->
        
        <?php  if(
                  in_array('24',$role_resources_ids) || 
                  in_array('25',$role_resources_ids) || 
                  in_array('26',$role_resources_ids) ||
                  in_array('27',$role_resources_ids) ||
                  in_array('28',$role_resources_ids)
                ) 
                {
            ?>
            <li class="<?php if(!empty($arr_mod['asst_open']))echo $arr_mod['asst_open'];?> treeview"> 
              <a href="#"> <i class="fa fa-flask"></i> <span> Aset  </span> 
                <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
              </a>
              <ul class="treeview-menu">
                
                <?php if(in_array('26',$role_resources_ids)) { ?>
                    <li class="sidenav-link <?php if(!empty($arr_mod['asst_cat_active']))echo $arr_mod['asst_cat_active'];?>"> 
                      <a href="<?php echo site_url('admin/assets/category');?>"> 
                       <i class="fa fa-cube"></i> Kategori
                      </a> 
                    </li>
                <?php } ?>

                <?php if(in_array('25',$role_resources_ids)) { ?>
                    <li class="sidenav-link <?php if(!empty($arr_mod['asst_active']))echo $arr_mod['asst_active'];?>"> 
                      <a href="<?php echo site_url('admin/assets');?>"> 
                          <i class="fa fa-cubes"></i> Atribut Kerja
                        </a> 
                      </li>
                <?php } ?>

                <?php if(in_array('27',$role_resources_ids)) { ?>
                    <li class="sidenav-link <?php if(!empty($arr_mod['asst_pinjam_active']))echo $arr_mod['asst_pinjam_active'];?>"> 
                      <a href="<?php echo site_url('admin/assets/pinjam');?>"> 
                          <i class="fa fa-mail-forward"></i> Pinjam 
                        </a> 
                      </li>
                <?php } ?>

                 <?php if(in_array('28',$role_resources_ids)) { ?>
                    <li class="sidenav-link <?php if(!empty($arr_mod['asst_kembali_active']))echo $arr_mod['asst_kembali_active'];?>"> 
                      <a href="<?php echo site_url('admin/assets/kembali');?>"> 
                          <i class="fa fa-mail-reply"></i> Kembali
                        </a> 
                      </li>
                <?php } ?>
                
                

              </ul>
            </li>
        <?php } ?>

    <!-- ======================================================================================================== -->
    <!-- KARYAWAN  --> 
    <!-- ======================================================================================================== -->

        <?php  if(  in_array('0500',$role_resources_ids) || 
                    // Sub
                    in_array('0510',$role_resources_ids) ||  in_array('0511',$role_resources_ids) ||  

                    in_array('0520',$role_resources_ids) ||  

                    in_array('0530',$role_resources_ids) ||  
                    // Superadmin  
                    $user_info[0]->user_role_id==1 
                )
              { ?>

        <li class="<?php if(!empty($arr_mod['stff_open']))echo $arr_mod['stff_open'];?> treeview"> 

          <a href="#"> <i class="fa fa-users"></i> 
              <span> Karyawan </span> 
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i> 
              </span> 
          </a>
          
          <ul class="treeview-menu">
            
            <?php if(in_array('0511',$role_resources_ids)) { ?>
                 <li class="<?php if(!empty($arr_mod['emp_active']))echo $arr_mod['emp_active'];?>"><a href="<?php echo site_url('admin/employees_active');?>"><i class="fa fa-ticket"></i> Karyawan Aktif</a></li>
            <?php } ?>

            <?php if(in_array('0520',$role_resources_ids)) { ?>
                  <li class="<?php if(!empty($arr_mod['hremp_active']))echo $arr_mod['hremp_active'];?>"><a href="<?php echo site_url('admin/employees_resign');?>"><i class="fa fa-thumb-tack"></i> Karyawan Resign </a></li>
            <?php } ?>

            <?php if(in_array('0530',$role_resources_ids)) { ?>
                  <li class="<?php if(!empty($arr_mod['history_active']))echo $arr_mod['history_active'];?>"><a href="<?php echo site_url('admin/employees_history');?>"><i class="fa fa-sign-out"></i> Karyawan History </a></li>
            <?php } ?>                
           
            <?php if(in_array('0540',$role_resources_ids)) { ?>
                  <li class="<?php if(!empty($arr_mod['emp_ll_active']))echo $arr_mod['emp_ll_active'];?>"><a href="<?php echo site_url('admin/employees_last_login');?>"><i class="fa fa-key"></i> Info Login </a></li>
            <?php } ?>

          </ul>
        </li>
        <?php } ?>  
  
    <!-- ======================================================================================================== -->
    <!-- GA & LEGAL  -->
    <!-- ======================================================================================================== -->

        <?php if( in_array('0400',$role_resources_ids) || 
                  // Sub
                  in_array('0410',$role_resources_ids) ||

                   in_array('0420',$role_resources_ids) ||

                    in_array('0430',$role_resources_ids) || 

                     in_array('0440',$role_resources_ids) ||$user_info[0]->user_role_id == 1 
              )
            { ?>

        <li class="<?php if(!empty($arr_mod['ga_legal']))echo $arr_mod['ga_legal'];?> treeview"> 

          <a href="#"> <i class="fa fa-gavel"></i> 
              <span>GA & Legal</span> 
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i> 
              </span> 
          </a>
          
          <ul class="treeview-menu">
          
            <?php if(in_array('0410',$role_resources_ids)) { ?>
                 <li class="<?php if(!empty($arr_mod['emp_all']))echo $arr_mod['emp_all'];?>"><a href="<?php echo site_url('admin/legal/employees_all');?>"><i class="fa fa-files-o"></i> Kontrak  </a></li>
            <?php } ?>

            <?php if(in_array('0420',$role_resources_ids)) { ?>
                 <li class="<?php if(!empty($arr_mod['instansi_all']))echo $arr_mod['instansi_all'];?>"><a href="<?php echo site_url('admin/instansi');?>"><i class="fa fa-files-o"></i> Instansi  </a></li>
            <?php } ?>


            <?php if(in_array('0430',$role_resources_ids)) { ?>
                 <li class="<?php if(!empty($arr_mod['perizinan_all']))echo $arr_mod['perizinan_all'];?>"><a href="<?php echo site_url('admin/perizinan');?>"><i class="fa fa-files-o"></i>  Perizinan </a></li>
            <?php } ?>

            <?php if(in_array('0440',$role_resources_ids)) { ?>
                 <li class="<?php if(!empty($arr_mod['perjanjian_all']))echo $arr_mod['perjanjian_all'];?>"><a href="<?php echo site_url('admin/perjanjian');?>"><i class="fa fa-files-o"></i> Perjanjian  </a></li>
            <?php } ?>
            
          </ul>
        
        </li>

        <?php } ?>   

    <!-- ======================================================================================================== -->
    <!-- PERSONALIA  -->
    <!-- ======================================================================================================== -->
        <?php  if( 
                  in_array('0600',$role_resources_ids) ||
                  in_array('0610',$role_resources_ids) || 
                  in_array('0620',$role_resources_ids) ||
                  in_array('0630',$role_resources_ids) ||                 
                  in_array('0640',$role_resources_ids) ||               
                  in_array('0650',$role_resources_ids) || 
                  in_array('0660',$role_resources_ids) ||
                  in_array('0670',$role_resources_ids) || 
                  in_array('0680',$role_resources_ids) ||  $user_info[0]->user_role_id==1
           
                )
              {
        ?>
        <li class="<?php if(!empty($arr_mod['emp_open']))echo $arr_mod['emp_open'];?> treeview"> 
          <a href="#"> <i class="fa fa-tasks"></i> 
            <span><?php echo $this->lang->line('xin_hr');?></span> 
            <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
          </a>
          <ul class="treeview-menu">               
            
            <?php if(in_array('0610',$role_resources_ids)) { ?>
                 <li class="<?php if(!empty($arr_mod['emp_ex_active']))echo $arr_mod['emp_ex_active'];?>"><a href="<?php echo site_url('admin/employee_exit');?>"><i class="fa fa-sign-out"></i> <?php echo $this->lang->line('left_employees_exit');?></a></li>
            <?php } ?>
                  
            <?php if(in_array('0620',$role_resources_ids)) { ?>
                 <li class="sidenav-link <?php if(!empty($arr_mod['awar_active']))echo $arr_mod['awar_active'];?>"> <a href="<?php echo site_url('admin/awards');?>" > <i class="fa fa-trophy"></i> <?php echo $this->lang->line('left_awards');?> </a> </li>
            <?php } ?>
        
            <?php if(in_array('0630',$role_resources_ids)) { ?>
                 <li class="sidenav-link <?php if(!empty($arr_mod['warn_active']))echo $arr_mod['warn_active'];?>"> <a href="<?php echo site_url('admin/warning');?>"> <i class="fa fa-warning"></i> <?php echo $this->lang->line('left_warnings');?> </a> </li>
            <?php } ?>

            <?php if(in_array('0640',$role_resources_ids)) { ?>
                 <li class="sidenav-link <?php if(!empty($arr_mod['trav_active']))echo $arr_mod['trav_active'];?>"> <a href="<?php echo site_url('admin/travel');?>"> <i class="fa fa-car"></i> <?php echo $this->lang->line('left_travels');?> </a> </li>
            <?php } ?> 
           
            <?php if(in_array('0650',$role_resources_ids)) { ?>
                 <li class="sidenav-link <?php if(!empty($arr_mod['tra_active']))echo $arr_mod['tra_active'];?>"> <a href="<?php echo site_url('admin/transfers');?>" > <i class="fa fa-send"></i> <?php echo $this->lang->line('left_transfers');?> </a> </li>
            <?php } ?>
        
            <?php if(in_array('0660',$role_resources_ids)) { ?>
                 <li class="sidenav-link <?php if(!empty($arr_mod['pro_active']))echo $arr_mod['pro_active'];?>"> <a href="<?php echo site_url('admin/promotion');?>"> <i class="fa fa-cloud-upload"></i> Promosi </a> </li>
            <?php } ?>

            <?php if(in_array('0670',$role_resources_ids)) { ?>
                 <li class="sidenav-link <?php if(!empty($arr_mod['dmo_active']))echo $arr_mod['dmo_active'];?>"> <a href="<?php echo site_url('admin/demotion');?>"> <i class="fa fa-cloud-download"></i> Demosi </a> </li>
            <?php } ?>
            
            <?php if(in_array('0680',$role_resources_ids)) { ?>
                <li class="sidenav-link <?php if(!empty($arr_mod['overtime_bulanan_active']))echo $arr_mod['overtime_bulanan_active'];?>"> <a href="<?php echo site_url('admin/overtime_bulanan');?>"> <i class="fa fa-tasks"></i> Lembur Bulanan </a> </li>
            <?php } ?> 

             <?php if(in_array('0690',$role_resources_ids)) { ?>
                <li class="sidenav-link <?php if(!empty($arr_mod['overtime_harian_active']))echo $arr_mod['overtime_harian_active'];?>"> <a href="<?php echo site_url('admin/overtime_harian');?>"> <i class="fa fa-tasks"></i> Lembur Harian </a> </li>
            <?php } ?>
           
          </ul>
        </li>
        <?php } ?>  

    <!-- ======================================================================================================== -->
    <!-- PENGAJUAN  -->
    <!-- ======================================================================================================== -->

        <?php  if( 
                           
              in_array('0700',$role_resources_ids) ||
              // Sub
              in_array('0710',$role_resources_ids) || 
              in_array('0720',$role_resources_ids) || 
              in_array('0730',$role_resources_ids) || 
              in_array('0740',$role_resources_ids) |
              // Super admin
              $user_info[0]->user_role_id==1
       
            )
              {
        ?>
        <li class="<?php if(!empty($arr_mod['pengajuan_open']))echo $arr_mod['pengajuan_open'];?> treeview"> 
          <a href="#"> <i class="fa fa-files-o"></i> 
            <span><?php echo $this->lang->line('xin_hr_izin');?></span> 
            <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
          </a>
          <ul class="treeview-menu"> 

            <?php if(in_array('0710',$role_resources_ids)) { ?>
                <li class="sidenav-link <?php if(!empty($arr_mod['leave_active']))echo $arr_mod['leave_active'];?>"> <a href="<?php echo site_url('admin/permission/leave');?>"> <i class="fa <?php echo $submenuicon;?>"></i> <?php echo $this->lang->line('xin_manage_leaves');?> </a> </li>
            <?php } ?>

            <?php if(in_array('0720',$role_resources_ids)) { ?>
                <li class="sidenav-link <?php if(!empty($arr_mod['sick_active']))echo $arr_mod['sick_active'];?>"> <a href="<?php echo site_url('admin/permission/sick');?>"> <i class="fa <?php echo $submenuicon;?>"></i> <?php echo $this->lang->line('xin_manage_sicks');?> </a> </li>
            <?php } ?>

            <?php if(in_array('0730',$role_resources_ids)) { ?>
                <li class="sidenav-link <?php if(!empty($arr_mod['izin_active']))echo $arr_mod['izin_active'];?>"> <a href="<?php echo site_url('admin/permission/izin');?>"> <i class="fa <?php echo $submenuicon;?>"></i> <?php echo $this->lang->line('xin_manage_izin');?> </a> </li>
            <?php } ?>

             <?php if(in_array('0740',$role_resources_ids)) { ?>
                <li class="sidenav-link <?php if(!empty($arr_mod['res_active']))echo $arr_mod['res_active'];?>"> <a href="<?php echo site_url('admin/permission/resign');?>" > <i class="fa <?php echo $submenuicon;?>"></i> <?php echo $this->lang->line('left_resignations');?> </a> </li>
            <?php } ?>

           <?php if(in_array('0750',$role_resources_ids)) { ?>
                <li class="sidenav-link <?php if(!empty($arr_mod['libur_active']))echo $arr_mod['libur_active'];?>"> <a href="<?php echo site_url('admin/permission/libur');?>"> <i class="fa <?php echo $submenuicon;?>"></i> <?php echo $this->lang->line('xin_manage_libur');?> </a> </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>  
   
    <!-- ======================================================================================================== -->
    <!-- PENGATURAN  -->
    <!-- ======================================================================================================== -->

        <?php  if(in_array('0800',$role_resources_ids) || 
                  // Sub                           
                  in_array('0810',$role_resources_ids) || 
                  in_array('0820',$role_resources_ids) ||
                  in_array('0830',$role_resources_ids) ||
                  in_array('0840',$role_resources_ids) ||
                  in_array('0850',$role_resources_ids) ||
                  in_array('0870',$role_resources_ids) ||
                  in_array('0860',$role_resources_ids) 
                   
                ) 
              {?>
            <li class="<?php if(!empty($arr_mod['atur_open']))echo $arr_mod['atur_open'];?> treeview"> 
              <a href="#"> <i class="fa fa-gears"></i> 
                <span>Pengaturan</span> 
                <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
              </a>
              <ul class="treeview-menu">                

                 <?php if(in_array('0810',$role_resources_ids)) { ?>
                      <li class="sidenav-link <?php if(!empty($arr_mod['offsh_reguler_active']))echo $arr_mod['offsh_reguler_active'];?>"> <a href="<?php echo site_url('admin/pengaturan/office_reguler');?>"> <i class="fa fa-share-alt"></i> Atur Jadwal Reguler </a> </li>
                <?php } ?>

                <?php if(in_array('0820',$role_resources_ids)) { ?>
                      <li class="sidenav-link <?php if(!empty($arr_mod['offsh_shift_active']))echo $arr_mod['offsh_shift_active'];?>"> <a href="<?php echo site_url('admin/pengaturan/office_shift');?>"> <i class="fa fa-sliders"></i> Atur Jadwal Shift </a> </li>
                <?php } ?>

                <?php if(in_array('0830',$role_resources_ids)) { ?>
                      <li class="sidenav-link <?php if(!empty($arr_mod['offsh_shift_jam_active']))echo $arr_mod['offsh_shift_jam_active'];?>"> <a href="<?php echo site_url('admin/pengaturan/office_shift_jam');?>"> <i class="fa fa-history"></i> Atur Jam Shift </a> </li>
                <?php } ?>

                <?php if(in_array('0840',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['hol_active']))echo $arr_mod['hol_active'];?>"> <a href="<?php echo site_url('admin/pengaturan/holidays');?>"> <i class="fa fa-child"></i> Atur Hari Libur </a> </li>
                <?php } ?>

                <?php if(in_array('0850',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['ker_active']))echo $arr_mod['ker_active'];?>"> <a href="<?php echo site_url('admin/pengaturan/works');?>"> <i class="fa fa-calendar"></i> Atur Bulan Kerja </a> </li>
                <?php } ?>

                <?php if(in_array('0870',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['periode_active']))echo $arr_mod['periode_active'];?>"> <a href="<?php echo site_url('admin/pengaturan/periode');?>"> <i class="fa fa-calendar"></i> Atur Periode Kerja </a> </li>
                <?php } ?>
               
                <?php if(in_array('0860',$role_resources_ids)) { ?>
                    <li class="sidenav-link <?php if(!empty($arr_mod['work_upah_active']))echo $arr_mod['work_upah_active'];?>"><a href="<?php echo site_url('admin/pengaturan/skala_upah');?>"><i class="fa fa-money"></i> Atur Skala Upah </a></li>
                <?php } ?>

              </ul>
            </li>
        <?php } ?>
    <!-- ======================================================================================================== -->
    <!-- PENGATURAN GAJI KARYAWAN BULANAN  -->
    <!-- ======================================================================================================== -->

        <?php  if(in_array('0800',$role_resources_ids) || 
                  // Sub                           
                  in_array('0810',$role_resources_ids) || 
                  in_array('0820',$role_resources_ids) ||
                  in_array('0830',$role_resources_ids) ||
                  in_array('0840',$role_resources_ids) ||
                  in_array('0850',$role_resources_ids) ||
                  in_array('0870',$role_resources_ids) ||
                  in_array('0860',$role_resources_ids) 
                   
                ) 
              {?>
            <li class="<?php if(!empty($arr_mod['atur_open']))echo $arr_mod['atur_open'];?> treeview"> 
              <a href="#"> <i class="fa fa-gears"></i> 
                <span>Master Gaji Bulanan</span> 
                <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
              </a>
              <ul class="treeview-menu">                

                 <?php if(in_array('0810',$role_resources_ids)) { ?>
                      <li class="sidenav-link <?php if(!empty($arr_mod['offsh_reguler_active']))echo $arr_mod['offsh_reguler_active'];?>"> <a href="<?php echo site_url('admin/master_gaji_bulanan/gajipokok');?>"> <i class="fa fa-share-alt"></i> Atur Gaji Pokok </a> </li>
                      <li class="sidenav-link <?php if(!empty($arr_mod['offsh_reguler_active']))echo $arr_mod['offsh_reguler_active'];?>"> <a href="<?php echo site_url('admin/master_gaji_bulanan/tunjangan_karyawan');?>"> <i class="fa fa-share-alt"></i> Atur Tunjangan Karyawan </a> </li>
                      <li class="sidenav-link <?php if(!empty($arr_mod['offsh_reguler_active']))echo $arr_mod['offsh_reguler_active'];?>"> <a href="<?php echo site_url('admin/master_gaji_bulanan/master_grade');?>"> <i class="fa fa-share-alt"></i> Master Grade Level </a> </li>
                      <li class="sidenav-link <?php if(!empty($arr_mod['offsh_reguler_active']))echo $arr_mod['offsh_reguler_active'];?>"> <a href="<?php echo site_url('admin/master_gaji_bulanan/grade_karyawan');?>"> <i class="fa fa-share-alt"></i> Atur Grade Karyawan </a> </li>
                <?php } ?>
              </ul>
            </li>
        <?php } ?>

    <!-- ======================================================================================================== -->
    <!-- KEHADIRAN  -->
    <!-- ======================================================================================================== -->

        <?php  if(in_array('0900',$role_resources_ids) || 
                  // Sub
                  in_array('0910',$role_resources_ids) || 
                  in_array('0920',$role_resources_ids) 
                             
                
                ) 
              {?>
            <li class="<?php if(!empty($arr_mod['attnd_open']))echo $arr_mod['attnd_open'];?> treeview"> 
              <a href="#"> <i class="fa fa-clock-o"></i> 
                <span>Kehadiran</span> 
                <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
              </a>
              <ul class="treeview-menu">

                <?php if(in_array('0910',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['attnd_reguler_active']))echo $arr_mod['attnd_reguler_active'];?>"> <a href="<?php echo site_url('admin/timesheet/attendance_reguler');?>"> <i class="fa fa-hand-o-up"></i> Tarik Absensi Reguler </a> </li>
                <?php } ?>

                <?php if(in_array('0920',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['attnd_shift_active']))echo $arr_mod['attnd_shift_active'];?>"> <a href="<?php echo site_url('admin/timesheet/attendance_shift');?>"> <i class="fa fa-hand-o-up"></i> Tarik Absensi Shift </a> </li>
                <?php } ?>

              </ul>
            </li>
        <?php } ?>

    <!-- ======================================================================================================== -->
    <!-- REKAP  -->
    <!-- ======================================================================================================== -->

        <?php  if(
                  in_array('0930',$role_resources_ids) || 
                  in_array('0940',$role_resources_ids) ||
                  in_array('0950',$role_resources_ids)                
                
                ) 
              {?>
            <li class="<?php if(!empty($arr_mod['p_rekap_open']))echo $arr_mod['p_rekap_open'];?> treeview"> 
              <a href="#"> <i class="fa fa-magic"></i> 
                <span>Proses Rekap</span> 
                <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
              </a>
              <ul class="treeview-menu">

               <?php if(in_array('0930',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['rekap_bulanan_active']))echo $arr_mod['rekap_bulanan_active'];?>"> <a href="<?php echo site_url('admin/timesheet/attendance_rekap_bulanan');?>"> <i class="fa fa-magic"></i> Rekap Absensi Bulanan</a> </li>
                <?php } ?> 

                <?php if(in_array('0950',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['rekap_harian_active']))echo $arr_mod['rekap_harian_active'];?>"> <a href="<?php echo site_url('admin/timesheet/attendance_rekap_harian');?>"> <i class="fa fa-magic"></i> Rekap Absensi Harian</a> </li>
                <?php } ?>

                <?php if(in_array('0950',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['rekap_borongan_active']))echo $arr_mod['rekap_borongan_active'];?>"> <a href="<?php echo site_url('admin/timesheet/attendance_rekap_borongan');?>"> <i class="fa fa-magic"></i> Rekap Absensi Borongan</a> </li>
                <?php } ?> 

                <?php if(in_array('0940',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['lembur_active']))echo $arr_mod['lembur_active'];?>"> <a href="<?php echo site_url('admin/timesheet/lembur_rekap');?>"> <i class="fa fa-magic"></i> Rekap Lembur</a> </li>
                <?php } ?> 



              </ul>
            </li>
        <?php } ?>
    
    <!-- ======================================================================================================== -->
    <!-- PENGGAJIAN  -->
    <!-- ======================================================================================================== -->
    
        <?php  if(
                 
                  in_array('1000',$role_resources_ids) ||
                  // Sub
                   in_array('1010',$role_resources_ids) ||  in_array('1020',$role_resources_ids) ||  in_array('1030',$role_resources_ids) 
                  // Super admin
                  // $user_info[0]->user_role_id==1

                ) 
                {?>
              <li class="<?php if(!empty($arr_mod['payrl_open']))echo $arr_mod['payrl_open'];?> treeview"> 
                <a href="#"> <i class="fa fa-calculator"></i> 
                  <span><?php echo $this->lang->line('left_payroll');?></span> 
                  <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
                </a>
                <ul class="treeview-menu">            
                  
                  <?php if(in_array('1010',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['pay_generate_bulanan_active']))echo $arr_mod['pay_generate_bulanan_active'];?>"> <a href="<?php echo site_url('admin/payroll/bulanan');?>"> <i class="fa fa-money"></i> Gaji Bulanan </a> </li>
                  <?php } ?>

                  <?php if(in_array('1020',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['pay_generate_harian_active']))echo $arr_mod['pay_generate_harian_active'];?>"> <a href="<?php echo site_url('admin/payroll/harian');?>"> <i class="fa fa-money"></i> Gaji Harian </a> </li>
                  <?php } ?>

                  <?php if(in_array('1030',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['pay_generate_borongan_active']))echo $arr_mod['pay_generate_borongan_active'];?>"> <a href="<?php echo site_url('admin/payroll/borongan');?>"> <i class="fa fa-money"></i> Gaji Borongan </a> </li>
                  <?php } ?>

                </ul>
              </li>
        <?php } ?>

    <!-- ======================================================================================================== -->
    <!-- THR  -->
    <!-- ======================================================================================================== -->
    
        <?php  if(
                 
                  in_array('10100',$role_resources_ids) ||
                  // Sub
                   in_array('10110',$role_resources_ids) ||
                    in_array('10120',$role_resources_ids) ||
                     in_array('10130',$role_resources_ids)  
                  // Super admin
                  // $user_info[0]->user_role_id==1

                ) 
                {?>
              <li class="<?php if(!empty($arr_mod['thr_open']))echo $arr_mod['thr_open'];?> treeview"> 
                <a href="#"> <i class="fa fa-calculator"></i> 
                  <span><?php echo $this->lang->line('left_thr');?></span> 
                  <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
                </a>
                <ul class="treeview-menu">            
                  
                  <?php if(in_array('10110',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['pay_thr_bulanan_active']))echo $arr_mod['pay_thr_bulanan_active'];?>"> <a href="<?php echo site_url('admin/thr/bulanan');?>"> <i class="fa fa-money"></i> THR Bulanan </a> </li>
                  <?php } ?>

                  <?php if(in_array('10120',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['pay_thr_harian_active']))echo $arr_mod['pay_thr_harian_active'];?>"> <a href="<?php echo site_url('admin/thr/harian');?>"> <i class="fa fa-money"></i> THR Harian </a> </li>
                  <?php } ?>

                  <?php if(in_array('10130',$role_resources_ids)) { ?>
                        <li class="sidenav-link <?php if(!empty($arr_mod['pay_thr_borongan_active']))echo $arr_mod['pay_thr_borongan_active'];?>"> <a href="<?php echo site_url('admin/thr/borongan');?>"> <i class="fa fa-money"></i> THR Borongan </a> </li>
                  <?php } ?>

                </ul>
              </li>
        <?php } ?>

    <!-- ======================================================================================================== -->
    <!-- FINANCE  -->
    <!-- ======================================================================================================== -->

        <?php if(in_array('1100',$role_resources_ids)  || in_array('1110',$role_resources_ids) || in_array('1120',$role_resources_ids)  || in_array('1130',$role_resources_ids)            
               
               ) 
               {?>
             <li class="<?php if(!empty($arr_mod['fins_open']))echo $arr_mod['fins_open'];?> treeview"> 
               <a href="#"> <i class="fa fa-bookmark"></i> 
                 <span><?php echo $this->lang->line('left_finance');?></span> 
                 <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
               </a>
               <ul class="treeview-menu">
                 
                 <?php if(in_array('1110',$role_resources_ids)) { ?>
                       <li class="sidenav-link <?php if(!empty($arr_mod['pay_bulanan_active']))echo $arr_mod['pay_bulanan_active'];?>"> <a href="<?php echo site_url('admin/finance/gaji_bulanan');?>"> <i class="fa fa-money"></i> Gaji Bulanan </a> </li>
                 <?php } ?>

                  <?php if(in_array('1120',$role_resources_ids)) { ?>
                       <li class="sidenav-link <?php if(!empty($arr_mod['thr_bulanan_active']))echo $arr_mod['thr_bulanan_active'];?>"> <a href="<?php echo site_url('admin/finance/thr_bulanan');?>"> <i class="fa fa-money"></i> THR Bulanan </a> </li>
                 <?php } ?>

                 <?php if(in_array('1130',$role_resources_ids)) { ?>
                       <li class="sidenav-link <?php if(!empty($arr_mod['pay_harian_active']))echo $arr_mod['pay_harian_active'];?>"> <a href="<?php echo site_url('admin/finance/gaji_harian');?>"> <i class="fa fa-money"></i> Gaji Harian </a> </li>
                 <?php } ?>

               </ul>
             </li>
       <?php } ?>

    <!-- ======================================================================================================== -->
    <!-- PELATIHAN  -->
    <!-- ======================================================================================================== -->
   
       <?php  if(
                  
                  in_array('54',$role_resources_ids) || 
                  in_array('55',$role_resources_ids) || 
                  in_array('56',$role_resources_ids) ||
                  in_array('57',$role_resources_ids)
                ) 
              {?>
            <li class="<?php if(!empty($arr_mod['training_open']))echo $arr_mod['training_open'];?> treeview"> 
              <a href="#"> <i class="fa fa-graduation-cap"></i> 
                <span><?php echo $this->lang->line('left_training');?></span> 
                <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> 
              </a>
              <ul class="treeview-menu">
              
                <?php if(in_array('55',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['tr_type_active']))echo $arr_mod['tr_type_active'];?>"> <a href="<?php echo site_url('admin/training_type');?>"> <i class="fa fa-tasks"></i> Jenis Pelatihan </a> </li>
                <?php } ?>

                <?php if(in_array('55',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['tr_posisi_active']))echo $arr_mod['tr_posisi_active'];?>"> <a href="<?php echo site_url('admin/training_posisi');?>"> <i class="fa fa-tasks"></i> Posisi Dilatih </a> </li>
                <?php } ?>
                
                 <!-- <?php if(in_array('57',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['vendors_active']))echo $arr_mod['vendors_active'];?>"> <a href="<?php echo site_url('admin/vendors');?>"> <i class="fa fa-building"></i> Vendor Pelatihan </a> </li>
                <?php } ?> -->

                <?php if(in_array('56',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['trainers_active']))echo $arr_mod['trainers_active'];?>"> <a href="<?php echo site_url('admin/trainers');?>"> <i class="fa fa-user"></i> Pelatih Pelatihan  </a> </li>
                <?php } ?>               

                <?php if(in_array('54',$role_resources_ids)) { ?>
                     <li class="sidenav-link <?php if(!empty($arr_mod['training_active']))echo $arr_mod['training_active'];?>"> <a href="<?php echo site_url('admin/training');?>"> <i class="fa fa-users"></i> <?php echo $this->lang->line('left_training_list');?> </a> </li>
                <?php } ?>

              </ul>
            </li>
        <?php } ?>

    <!-- ======================================================================================================== -->
    <!-- LAPORAN  -->
    <!-- ======================================================================================================== -->

      <?php if( in_array('1300',$role_resources_ids) ||
               // Sub
               in_array('1310',$role_resources_ids) ||
               in_array('1320',$role_resources_ids) ||
               in_array('1330',$role_resources_ids) ||
               in_array('1340',$role_resources_ids) || 
               in_array('1350',$role_resources_ids) ||
               in_array('1360',$role_resources_ids) ||
                $user_info[0]->user_role_id==1 
              ) 
          { ?>
          <li class="<?php if(!empty($arr_mod['reports_active']))echo $arr_mod['reports_active'];?>"> 
            <a href="<?php echo site_url('admin/reports');?>"> 
                  <i class="fa fa-print"></i> <span><?php echo $this->lang->line('xin_hr_report_title');?></span> 
            </a> 
          </li>
      <?php } ?>
    
   
    <li> &nbsp; </li>
  </ul>
</section>

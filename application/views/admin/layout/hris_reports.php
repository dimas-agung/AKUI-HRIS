<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php
$session = $this->session->userdata('username');
$theme = $this->Core_model->read_theme_info(1);
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

<?php if(
    in_array('1310',$role_resources_ids) 
   
) { ?>

<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-print"></i> <?php echo $this->lang->line('xin_hr_report_title');?> Karyawan</h3>
        </div>        
        <div class="box-body">
            <div class="row">

                <?php if(in_array('1310',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>
                                <?php if(in_array('1310',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan <?php echo $this->lang->line('dashboard_single_employee');?> </td>
                                    </tr>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-user"></i> Karyawan </td>
                                    </tr>
                                    
                                    <?php if(in_array('1311',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_active');?>">  Karyawan Aktif </a></td>
                                        </tr>

                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_active_not');?>">  Karyawan Tidak Aktif </a></td>
                                        </tr>
                                    <?php } ?>
                                                                      
                                
                                <?php } ?>                                                                
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>
                                <?php if(in_array('1310',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan <?php echo $this->lang->line('dashboard_single_employee');?> </td>
                                    </tr>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-user"></i> Karyawan </td>
                                    </tr>
                                    
                                                                        
                                    <?php if(in_array('1312',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_resign');?>">  Karyawan Resign </a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1312',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_exit');?>">  Karyawan Exit Interview </a></td>
                                        </tr>
                                    <?php } ?>

                                    
                                
                                <?php } ?>                                                                
                            </tbody>
                        </table>
                    </div>

                     <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>
                                <?php if(in_array('1310',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan <?php echo $this->lang->line('dashboard_single_employee');?> </td>
                                    </tr>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-tasks"></i> Pelatihan </td>
                                    </tr>
                                    
                                                                        
                                    <?php if(in_array('1312',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_pelatihan_sudah');?>">  Karyawan Sudah Pelatihan </a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1312',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_pelatihan_belum');?>">  Karyawan Belum Pelatihan </a></td>
                                        </tr>
                                    <?php } ?>

                                    
                                
                                <?php } ?>                                                                
                            </tbody>
                        </table>
                    </div>

                <?php } ?>             

            </div>
        </div>          
      </div>     
    </div>   
</div>

<?php } ?>

<?php if(
    
    in_array('1330',$role_resources_ids) || 
     
    in_array('1340',$role_resources_ids) || 
    in_array('1350',$role_resources_ids) ||
    in_array('1400',$role_resources_ids) ||
    in_array('1500',$role_resources_ids)

) { ?>

<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-print"></i> <?php echo $this->lang->line('xin_hr_report_title');?> Kehadiran </h3>
        </div>        
        <div class="box-body">
            <div class="row">

                <?php if(in_array('1330',$role_resources_ids)) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>

                                 <tr>
                                        <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan <?php echo $this->lang->line('dashboard_attendance');?> </td>
                                    </tr>
                               
                                <?php if(in_array('1330',$role_resources_ids)) { ?>
                                    <tr>
                                    <td colspan="3"><i class="fa fa-users"></i> Personalia </td>
                                    </tr>

                                    <?php if(in_array('1331',$role_resources_ids)) { ?>
                                        
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/rekap_lembur');?>"> Rekap Lembur</a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1332',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_leave');?>"> Rekap Cuti Tahunan</a></td>
                                        </tr>
                                        <tr>
                                        <td width="30px">3.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_timelate');?>"> Laporan Karyawan Terlambat</a></td>
                                        </tr>
                                        <td width="30px">3.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_timelate_rekap');?>"> Rekap Karyawan Terlambat</a></td>
                                        </tr>
                                    <?php } ?>

                                <?php } ?>
                                                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?>             

                <?php if(in_array('1340',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody> 
                                <?php if(in_array('1340',$role_resources_ids) ) { ?>
                                    
                                    <tr>
                                        <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan <?php echo $this->lang->line('dashboard_attendance');?> </td>
                                    </tr>

                                    <?php if(in_array('1340',$role_resources_ids)) { ?>

                                        <tr>
                                            <td colspan="3"><i class="fa fa-share-alt"></i> Pola Kerja : Reguler (Bulanan)</td>
                                        </tr>                                        

                                        <?php if(in_array('1341',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">1.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/employee_attendance');?>">  <?php echo $this->lang->line('xin_hr_reports_attendance_employee');?> </a></td>
                                            </tr>
                                        <?php } ?>
                                        
                                         
                                        <?php if(in_array('1342',$role_resources_ids)) { ?>                                            
                                            <tr>
                                            <td width="30px">2.</td>                                    
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/attendance_rekap_bulanan');?>"> Kehadiran Bulanan</a></td>
                                            </tr>

                                        <?php } ?>

                                        <?php if(in_array('1342',$role_resources_ids)) { ?>                                            
                                            <tr>
                                            <td width="30px">3.</td>                                    
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/attendance_rekap_tahunan');?>"> Kehadiran Tahunan</a></td>
                                            </tr>

                                        <?php } ?>

                                       
                                        
                                    <?php } ?>  

                                    
                                <?php } ?>              
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if(in_array('1340',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody> 
                                <?php if(in_array('1340',$role_resources_ids) ) { ?>
                                    
                                    <tr>
                                        <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan <?php echo $this->lang->line('dashboard_attendance');?> </td>
                                    </tr>

                                    <?php if(in_array('1340',$role_resources_ids)) { ?>

                                        <tr>
                                            <td colspan="3"><i class="fa fa-share-alt"></i> Pola Kerja : Reguler (Harian)</td>
                                        </tr>                                        

                                        <?php if(in_array('1341',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">1.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/employee_attendance_harian');?>">  <?php echo $this->lang->line('xin_hr_reports_attendance_employee');?> </a></td>
                                            </tr>
                                        <?php } ?>
                                       

                                        <?php if(in_array('1343',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">2.</td>                                    
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/attendance_rekap_harian');?>">  Kehadiran Harian </a></td>
                                            </tr>
                                        <?php } ?>

                                                                                
                                    <?php } ?>  

                                    
                                <?php } ?>              
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                  <?php if(in_array('1340',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody> 
                                <?php if(in_array('1340',$role_resources_ids) ) { ?>
                                    
                                    <tr>
                                        <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan <?php echo $this->lang->line('dashboard_attendance');?> </td>
                                    </tr>

                                    <?php if(in_array('1340',$role_resources_ids)) { ?>

                                        <tr>
                                            <td colspan="3"><i class="fa fa-share-alt"></i> Pola Kerja : Reguler (Borongan)</td>
                                        </tr>                                        

                                        <?php if(in_array('1341',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">1.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/employee_attendance_borongan');?>">  <?php echo $this->lang->line('xin_hr_reports_attendance_employee');?> </a></td>
                                            </tr>
                                        <?php } ?>                                       

                                        <?php if(in_array('1344',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">2.</td>                                    
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/attendance_rekap_borongan');?>">  Kehadiran Borongan </a></td>
                                            </tr>
                                        <?php } ?>

                                        
                                    <?php } ?>  

                                    
                                <?php } ?>              
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if(in_array('1350',$role_resources_ids)) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody> 
                                <?php if(in_array('1350',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                        <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan <?php echo $this->lang->line('dashboard_attendance');?> </td>
                                    </tr>

                                   

                                    <?php if(in_array('1350',$role_resources_ids)) { ?>
                                        <tr>
                                        <td colspan="3"><i class="fa fa-sliders"></i> Pola Kerja : Shift (Bulanan)</td>
                                        </tr>
                                        <?php if(in_array('1351',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">1.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/employee_attendance_shift');?>">  <?php echo $this->lang->line('xin_hr_reports_attendance_employee');?> </a></td>
                                            </tr>
                                        <?php } ?> 
                                        <?php if(in_array('1352',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">2.</td>                                    
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/employee_attendance_month_shift');?>">  <?php echo $this->lang->line('xin_hr_reports_attendance_employee_bulan');?> </a></td>
                                            </tr>
                                        <?php } ?> 

                                    <?php } ?>
                                <?php } ?>              
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if(in_array('1500',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>

                                <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan Produktifitas </td>
                                </tr>                                    
                                
                                <?php if(in_array('1500',$role_resources_ids)) { ?>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-money"></i> Kerja Borongan </td>
                                    </tr>

                                    
                                   <?php if(in_array('1501',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">1.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/report_produktifitas_per_periode');?>"> Detail Produktifitas </a></td>
                                            </tr>

                                            <tr>
                                            <td width="30px">2.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/report_produktifitas_per_periode_rekap');?>"> Rekap Produktifitas </a></td>
                                            </tr>

                                    <?php } ?>

                                    <?php if(in_array('1502',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">3.</td>                                    
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/report_produktifitas_per_month');?>">  Produktifitas Biaya Per Bulan </a></td>
                                            </tr>
                                    <?php } ?>

                                    
                                                                      
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?>              
              
            </div>
        </div>          
      </div>     
    </div>   
</div>

<?php } ?>

<?php if(
    in_array('1360',$role_resources_ids) || 
    in_array('1370',$role_resources_ids) || 
    in_array('1380',$role_resources_ids) || 
    in_array('1390',$role_resources_ids)  
   
) { ?>

<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-print"></i> <?php echo $this->lang->line('xin_hr_report_title');?> Penggajian & THR</h3>
        </div>        
        <div class="box-body">
            <div class="row">
               
                <?php if(in_array('1360',$role_resources_ids)) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>

                                <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan Penggajian </td>
                                </tr>
                                    
                                <?php if(in_array('1360',$role_resources_ids)) { ?>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-money"></i> Gaji Bulanan </td>
                                    </tr>

                                     <?php if(in_array('1361',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/karyawan_bulanan');?>">  Karyawan Gaji Bulanan </a></td>
                                        </tr>
                                    <?php } ?>


                                     <?php if(in_array('1362',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_bulanan');?>">  Detail Gaji Bulanan </a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1363',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">3.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/payslip_bulanan');?>">  Rekap Gaji Bulanan</a></td>
                                        </tr>
                                    <?php } ?>

                                      <?php if(in_array('1364',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">4.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/resume');?>">  Resume Gaji Tahunan </a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1365',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">5.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/resume_pengajuan');?>">  Resume Pengajuan</a></td>
                                        </tr>
                                    <?php } ?>

                                     <?php if(in_array('1366',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">6.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_bulanan_slip');?>"> 
                                                Slip Gaji Bulanan 
                                            </a>
                                        </td>
                                        </tr>
                                    <?php } ?>

                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if(in_array('1370',$role_resources_ids)) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>

                                <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan Penggajian </td>
                                </tr>                                    
                               
                                <?php if(in_array('1370',$role_resources_ids)) { ?>

                                    <tr>
                                         <td colspan="3"><i class="fa fa-money"></i> Gaji Harian </td>
                                    </tr>

                                     <?php if(in_array('1371',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/karyawan_harian');?>">  Karyawan Gaji Harian </a></td>
                                        </tr>
                                    <?php } ?>

                                     <?php if(in_array('1372',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_harian_periode');?>"> 
                                                Detail Gaji Harian Periode 
                                            </a>
                                        </td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1373',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">3.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_harian_periode_rekap');?>"> 
                                                Rekap Gaji Harian Periode 
                                            </a>
                                        </td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1374',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">4.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_harian_bulanan');?>"> 
                                                Rekap Gaji Harian Bulanan 
                                            </a>
                                        </td>
                                        </tr>
                                    <?php } ?>

                                     <?php if(in_array('1375',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">5.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_harian_resume');?>"> 
                                                Resume Gaji Harian 
                                            </a>
                                        </td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1376',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">6.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_harian_slip');?>"> 
                                                Slip Gaji Harian 
                                            </a>
                                        </td>
                                        </tr>
                                    <?php } ?>
                                                                      
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if(in_array('1380',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>

                                <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan Penggajian </td>
                                </tr>                                    
                                
                                <?php if(in_array('1380',$role_resources_ids)) { ?>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-money"></i> Gaji Borongan </td>
                                    </tr>

                                     <?php if(in_array('1381',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">1.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/karyawan_borongan');?>">  
                                                    Karyawan Gaji Borongan 
                                                </a>
                                            </td>
                                            </tr>
                                    <?php } ?>

                                    <?php if(in_array('1382',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">2.</td>                                    
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_borongan_periode');?>">  
                                                    Detail Gaji Borongan Periode 
                                                </a>
                                            </td>
                                            </tr>
                                    <?php } ?>

                                    <?php if(in_array('1383',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">3.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_borongan_periode_rekap');?>"> 
                                                    Rekap Gaji Borongan Periode 
                                                </a>
                                            </td>
                                            </tr>
                                    <?php } ?>

                                    <?php if(in_array('1384',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">4.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_borongan_bulanan');?>"> 
                                                    Rekap Gaji Borongan Bulanan 
                                                </a>
                                            </td>
                                            </tr>
                                    <?php } ?>


                                    <?php if(in_array('1385',$role_resources_ids)) { ?>
                                            <tr>
                                            <td width="30px">5.</td>
                                            <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_borongan_resume');?>"> 
                                                Resume Gaji Borongan 
                                                </a>
                                            </td>
                                            </tr>
                                    <?php } ?>

                                    <?php if(in_array('1386',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">6.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/gaji_borongan_slip');?>"> 
                                                Slip Gaji Borongan 
                                            </a>
                                        </td>
                                        </tr>
                                    <?php } ?>
                                                                      
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if(in_array('1390',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>

                                <?php if(in_array('1390',$role_resources_ids)) { ?>
                                     <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan THR </td>
                                    </tr>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-money"></i> THR : Bulanan </td>
                                    </tr>

                                     <?php if(in_array('1391',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/detail_thr');?>">  Detail THR </a></td>
                                        </tr>
                                    <?php } ?>

                                     <?php if(in_array('1392',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/rekap_thr');?>">  Rekap THR </a></td>
                                        </tr>
                                    <?php } ?>


                                     <?php if(in_array('1393',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">3.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/resume_thr_bulanan');?>"> Resume THR </a></td>
                                        </tr>
                                    <?php } ?>                                  
                                    
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?> 

                <?php if(in_array('1390',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>

                                <?php if(in_array('1390',$role_resources_ids)) { ?>
                                     <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan THR </td>
                                    </tr>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-money"></i> THR : Harian </td>
                                    </tr>

                                     <?php if(in_array('1391',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/detail_thr');?>">  Detail THR </a></td>
                                        </tr>
                                    <?php } ?>

                                     <?php if(in_array('1392',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/rekap_thr');?>">  Rekap THR </a></td>
                                        </tr>
                                    <?php } ?>


                                     <?php if(in_array('1393',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">3.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/resume_thr_bulanan');?>"> Resume THR </a></td>
                                        </tr>
                                    <?php } ?>                                  
                                    
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?> 

                <?php if(in_array('1390',$role_resources_ids) ) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>

                                <?php if(in_array('1390',$role_resources_ids)) { ?>
                                     <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;"><i class="fa fa-info-circle"></i> Laporan THR </td>
                                    </tr>

                                    <tr>
                                    <td colspan="3"><i class="fa fa-money"></i> THR : Borongan </td>
                                    </tr>

                                     <?php if(in_array('1391',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/detail_thr');?>">  Detail THR </a></td>
                                        </tr>
                                    <?php } ?>

                                     <?php if(in_array('1392',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/rekap_thr');?>">  Rekap THR </a></td>
                                        </tr>
                                    <?php } ?>


                                     <?php if(in_array('1393',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">3.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/resume_thr_bulanan');?>"> Resume THR </a></td>
                                        </tr>
                                    <?php } ?>                                  
                                    
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?>              

            </div>
        </div>          
      </div>     
    </div>   
</div>

<?php } ?>

<?php if( 
    in_array('1321',$role_resources_ids) 
) { ?>

<div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-print"></i> <?php echo $this->lang->line('xin_hr_report_title');?> GA & Legal</h3>
        </div>        
        <div class="box-body">
            <div class="row">               

                 <?php if(in_array('1321',$role_resources_ids)) { ?>
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>
                                <?php if(in_array('1321',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;">
                                        <i class="fa fa-info-circle"></i> 
                                        Laporan GA 
                                    </td>
                                    </tr>                                    
                                
                                <?php } ?>

                               
                                <?php if(in_array('13211',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3"><i class="fa fa-gavel"></i> GA </td>
                                    </tr>

                                    <?php if(in_array('13212',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/perjanjian');?>">  Perjanjian </a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('13213',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/perizinan');?>">  Perizinan </a></td>
                                        </tr>
                                    <?php } ?>                                   

                                <?php } ?>                                  
                                                                
                            </tbody>
                        </table>
                    </div>
                <?php } ?>

                <?php if(in_array('1321',$role_resources_ids)) { ?>
                    
                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>
                                <?php if(in_array('1321',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;">
                                        <i class="fa fa-info-circle"></i> 
                                        Laporan Legal 
                                    </td>
                                    </tr>                                    
                                
                                <?php } ?>

                                <?php if(in_array('1321',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3"><i class="fa fa-gavel"></i> Legal (Kontrak)</td>
                                    </tr>

                                    <?php if(in_array('1322',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_contract_end');?>">  Kontrak Sudah Habis </a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1323',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">2.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_contract_end_will');?>">  Kontrak Akan Habis </a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1324',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">3.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_contract_do');?>">  Kontrak Berlangsung </a></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(in_array('1325',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">4.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_contract_not');?>">  Kontrak Belum Dibuat </a></td>
                                        </tr>  
                                    <?php } ?>
                                    

                                <?php } ?>

                                                                 
                                                                
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-2">
                        <table class="table table-bordered" >
                            <tbody>
                                <?php if(in_array('1321',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3" style="background-color: #f2f2f2;">
                                        <i class="fa fa-info-circle"></i> 
                                        Laporan Legal 
                                    </td>
                                    </tr>                                    
                                
                                <?php } ?>

                                <?php if(in_array('1321',$role_resources_ids)) { ?>
                                    
                                    <tr>
                                    <td colspan="3"><i class="fa fa-gavel"></i> Legal (Tetap) </td>
                                    </tr>

                                    <?php if(in_array('1326',$role_resources_ids)) { ?>
                                        <tr>
                                        <td width="30px">1.</td>
                                        <td><a target="_blank" href="<?php echo site_url('admin/reports/employees_permanent');?>">  Karyawan Tetap </a></td>
                                        </tr>  
                                    <?php } ?>                                    

                                <?php } ?>                                                                 
                                                                
                            </tbody>
                        </table>
                    </div>

                <?php } ?>

                              

            </div>
        </div>          
      </div>     
    </div>   
</div>

<?php } ?>
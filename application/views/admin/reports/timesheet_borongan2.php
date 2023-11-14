<?php
/* Monthly Timesheet view > hris
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>

<?php
$user_info          = $this->Core_model->read_user_info($session['user_id']);
$role_resources_ids = $this->Core_model->user_role_resource();

$jenis_company		= $this->input->post('company_id');

$month_year         = $this->input->post('month_year');



	if(!isset($month_year)){

		$skrg     = date('Y-m-d');

		$xin_bulan     = $this->Timesheet_model->get_xin_employees_bulan($skrg);
		$month_year    = $xin_bulan[0]->month_payroll;
		$bulan         = $xin_bulan[0]->month_payroll;

		$jenis_company = 1;
			
		
		$xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal($bulan);
		$tanggal       = $this->Timesheet_model->read_tanggal_information($bulan);
		if(!is_null($tanggal)){
			$start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
			$end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

			$start_date    = new DateTime($tanggal[0]->start_date);
			$end_date      = new DateTime($tanggal[0]->end_date);
			$interval_date = $end_date->diff($start_date);

		} else {
			$start_att = '';	
			$end_att = '';	

			$start_date    = '';
			$end_date      = '';
			$interval_date = '';
		}		

		if ($jenis_company == '1') {
			$company_name     = 'PT Akui Bird Nest Indonesia';
		} else if ($jenis_company == '2') {
			$company_name     = 'PT ORIGINAL BERKAH INDONESIA';
		} else if ($jenis_company == '3') {
			$company_name     = 'PT Asa Fadil Sejahtera';
		}
		
		$xin_employees = $this->Timesheet_model->get_xin_employees_borongan($jenis_company);
		$gaji_name     = 'Karyawan Borongan';
		
	} else {
		
		$xin_tanggal   = $this->Timesheet_model->get_xin_employees_tanggal($month_year);

		$tanggal = $this->Timesheet_model->read_tanggal_information($month_year);
		if(!is_null($tanggal)){
			$start_att = date("d-m-Y", strtotime($tanggal[0]->start_date));
			$end_att   = date("d-m-Y", strtotime($tanggal[0]->end_date));

			$start_date    = new DateTime($tanggal[0]->start_date);
			$end_date      = new DateTime($tanggal[0]->end_date);
			$interval_date = $end_date->diff($start_date);


		} else {
			$start_att = '';	
			$end_att = '';	

			$start_date    = '';
			$end_date      = '';
			$interval_date = '';
		}		

		if ($jenis_company == '1') {
			$company_name     = 'PT Akui Bird Nest Indonesia';
		} else if ($jenis_company == '2') {
			$company_name     = 'PT ORIGINAL BERKAH INDONESIA';
		} else if ($jenis_company == '3') {
			$company_name     = 'PT Asa Fadil Sejahtera';
		}		
		
		$xin_employees = $this->Timesheet_model->get_xin_employees_borongan($jenis_company);
		$gaji_name     = 'Karyawan Borongan';
	
	}
?>

<div class="box mb-4 <?php echo $get_animate;?>">
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <?php $attributes = array('name' => 'xin-form', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('_user' => $session['user_id']);?>
        <?php echo form_open('admin/reports/employee_attendance_borongan/', $attributes, $hidden);?>
        <div class="box">
	        <div class="box-body">
		        <div class="row">
		        	
		        	 <div class="col-md-3">
			            <div class="form-group">
			              <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
			              <select class="form-control input-sm" name="company_id" id="company_id" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
			                <?php foreach($get_all_companies as $company) {?>			                
			                <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$jenis_company): ?> selected="selected" <?php endif; ?>><?php echo strtoupper($company->name);?></option>
			                <?php } ?>
			              </select>
			            </div>
			        </div> 
                 
		            <div class="col-md-2">
			            <div class="form-group">
			              <label for="first_name"><?php echo $this->lang->line('xin_e_details_month');?></label>
			              <select class="form-control input-sm" name="month_year" id="month_year" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_e_details_month');?>" required>			              
			                <?php foreach($all_bulan_gaji as $bulan_gaji) {?>			               		
			               		<option value="<?php echo $bulan_gaji->month_payroll;?>" <?php if($bulan_gaji->month_payroll==$month_year): ?> selected="selected" <?php endif; ?>><?php echo strtoupper($bulan_gaji->desc); ?></option>
			                <?php } ?>
			              </select>
			            </div>
		            </div>

		          	<div class="col-md-3">
			            <div class="form-group">
			              <label >&nbsp;</label>
			              <br />
			              <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => 'btn btn-primary', 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_get_filter'))); ?> 
			          	</div>
		          	</div>

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
      		<div class="col-md-12">
      			<div class="box">
				<div class="box-header with-border">
				    <h3 class="box-title text-uppercase text-bold"> Kehadiran Borongan : <?php if(isset($month_year)): echo date('F Y', strtotime($month_year)); else: echo date('F Y',strtotime($bulan)); endif;?> </h3>
				    <h5>
				     Periode Kehadiran : <?php echo $start_att; ?> s/d <?php echo $end_att; ?> (<?php echo $interval_date->d; ?> hari) - Jenis : <?php echo $gaji_name ; ?> - 
				     Pola Kerja : Reguler  Di <?php echo ucfirst($company_name); ?>
				    </h5>
				    <div class="box-tools pull-right"> </div>
				</div>
				
			    <div class="box-body">
				    <div class="box-datatable table-responsive">
				      <table class="datatables-demo table table-striped table-bordered" id="xin_table_borongan" width="190%">
				        <thead>
				           	<tr>
				             	<th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor');?></th>
					            <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_employee');?></th>	            
					            <th colspan="<?php echo $interval_date->d+1; ?>"> <?php if(isset($month_year)): echo date('F Y', strtotime($month_year)); else: echo date('F Y',strtotime($bulan)); endif;?></th>
					            <th colspan="7" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_timesheet_workdays_jumlah');?></th>

					            <th colspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_absent_late');?></th>

					            <th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_timesheet_workdays_total');?></th>
					        </tr>
					        <tr>
					            <?php foreach($xin_tanggal as $t):?>  
					            	<?php $start = date("M d",strtotime($t->tanggal)); ?>
					          		<?php $day   = $this->Timesheet_model->conHari(date("D",strtotime($t->tanggal))); ?>
					          		<?php $warna   = $this->Timesheet_model->conWarna($day); ?>

					             	<th style="<?php echo $warna; ?>"><center><?php echo $start;?><br><?php echo $day;?> </center></th>      
						        <?php endforeach;?>

						        
						        <th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_holiday_judul');?></center></th>
								<th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_present_judul');?></center></th>
								<th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_on_sick_simbol');?></center></th>
						        <th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_on_izin_simbol');?></center></th>
						        <th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_on_leave_simbol');?></center></th>
						        <th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_absent_simbol');?></center></th>
						        <th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_travels_simbol');?></center></th>

						        <th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_absent_menit');?></center></th>
						        <th width="12px" style="vertical-align: middle !important;"><center><?php echo $this->lang->line('xin_absent_jam');?></center></th>

					        </tr>
				        </thead>
				        <tbody>
				        		<?php $no=1; ?>

				          		<?php $j=0;foreach($xin_employees as $r):?>
				         
					          		<?php

					          			if ($r->flag == 0) {
											$warna_flag = '';
										} else {
											$warna_flag = 'background-color : #efedae';
										}

									  	// user full name 
										$full_name = $r->first_name.' '.$r->last_name;
																				
										$tanggal1 = date("Y-m-d",strtotime($start_att));
										$tanggal2 = date("Y-m-d",strtotime($end_att));

										$cek_libur = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'L');
										$jumlah_libur = $cek_libur[0]->jumlah;

										$cek_hadir = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'H');
										if ($r->flag == 0) {
											$jumlah_hadir = $cek_hadir[0]->jumlah;
										} else {
											$jumlah_hadir = $interval_date->d-$jumlah_libur;
										}

										$cek_sakit = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'S');
										$jumlah_sakit = $cek_sakit[0]->jumlah;

										$cek_izin = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'I');
										$jumlah_izin = $cek_izin[0]->jumlah;
										
										$cek_cuti = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'C');
										$jumlah_cuti = $cek_cuti[0]->jumlah;

										$cek_alpa = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'A');
										
										if ($r->flag == 0) {
											$jumlah_alpa = $cek_alpa[0]->jumlah;
										} else {
											$jumlah_alpa = 0;
										}
										
										
										$cek_dinas = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'D');
										$jumlah_dinas = $cek_dinas[0]->jumlah;

										$cek_lembur = $this->Timesheet_model->hitung_jumlah_status_kehadiran($r->user_id,$tanggal1,$tanggal2,'O');
										$jumlah_lembur = $cek_lembur[0]->jumlah;
										
										$cek_terlambat = $this->Timesheet_model->hitung_jumlah_terlambat_kehadiran($r->user_id,$tanggal1,$tanggal2);
										$jumlah_terlambat= $cek_terlambat[0]->jumlah;

										$jumlah_terlambat_menit = $jumlah_terlambat;
										$jumlah_terlambat_jam = round($jumlah_terlambat/60,2);

										$pcount=0;
										$pcount= $jumlah_hadir + $jumlah_libur + $jumlah_sakit + $jumlah_izin + $jumlah_cuti + $jumlah_alpa + $jumlah_dinas + $jumlah_lembur;
									?>
					          		<?php $employee_name = $full_name;?>
				          
							        <tr style="<?php echo $warna_flag; ?>">

							          	<td width="20px" class="text-center"><?php echo $no;?></td>
							            <td width="400px"><?php echo $full_name;?></td>

							            <?php foreach($xin_tanggal as $t):?>  
							            <?php $attendance_date = $t->tanggal; ?>
							             <?php $day   = $this->Timesheet_model->conHari(date("D",strtotime($t->tanggal))); ?>
					          			<?php $warna   = $this->Timesheet_model->conWarna($day); ?>   
								        <?php 
								       
									        $cek_status = $this->Timesheet_model->cek_status_kehadiran($r->user_id,$attendance_date); 

									        
									        if(!is_null($cek_status)){
									        	
									        	if ($cek_status[0]->attendance_status_simbol == 'H' || $cek_status[0]->attendance_status_simbol == 'Hadir') {
									        	
									        		$cek_status_name = '.';
									        	
									        	} else {

									        		if ($r->flag == 0) {

									        			$cek_status_name = $cek_status[0]->attendance_status_simbol;
									        			
									        		} else {
									        			if ($cek_status[0]->attendance_status_simbol == 'L'){
									        				$cek_status_name = $cek_status[0]->attendance_status_simbol;
									        			} else {
									        				$cek_status_name = '.';
									        			}
									        			
									        		}
									        		
									        	}
												
											} else {
												$cek_status_name = '?';	
											}

								        ?>
							           
							            <td width="50px" style="<?php echo $warna; ?>"><center><?php echo $cek_status_name; ?></center></td>

							            <?php endforeach;?>

							            <td width="70px" style="text-align: center; background-color : #ddc3c3 !important;"> <?php echo $jumlah_libur;?> </td>
							            <td width="70px" style="text-align: center; background-color : #c6edd1 !important;"> <?php echo $jumlah_hadir;?> </td>			            
							            
							            <td width="70px" style="text-align: center; background-color : #c6dbed !important;"> <?php echo $jumlah_sakit;?> </td>
							            <td width="70px" style="text-align: center; background-color : #e7debd !important;"> <?php echo $jumlah_izin;?>  </td>
							            <td width="70px" style="text-align: center; background-color : #bde7e7 !important;"> <?php echo $jumlah_cuti;?>  </td>
							            <td width="70px" style="text-align: center; background-color : #edeac6 !important;"> <?php echo $jumlah_alpa;?>  </td>
							            <td width="70px" style="text-align: center; background-color : #c6e9ed !important;"> <?php echo $jumlah_dinas;?> </td>

							            <td width="70px" style="text-align: center; background-color : #edc6eb !important;"> <?php echo $jumlah_terlambat_menit;?> </td>
							            <td width="70px" style="text-align: center; background-color : #edc6eb !important;"> <?php echo $jumlah_terlambat_jam;?> </td>

							            <td width="70px" style="text-align: center; background-color : #cfc6ed !important;"> <?php echo $pcount; ?> </td>
							        </tr>
				           <?php $no++;  ?>
				          <?php endforeach;?>
				        </tbody>
				      </table>
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

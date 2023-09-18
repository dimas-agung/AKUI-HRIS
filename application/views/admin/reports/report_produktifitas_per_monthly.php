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
			$company_name     = 'PT WALET ABDILLAH JABLI';
		}
		
		$xin_employees = $this->Timesheet_model->get_xin_employees_borongan_rekap($jenis_company);
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
			$company_name     = 'PT WALET ABDILLAH JABLI';
		}		
		
		$xin_employees = $this->Timesheet_model->get_xin_employees_borongan_rekap($jenis_company);
		$gaji_name     = 'Karyawan Borongan';
	
	}
?>

<div class="box mb-4 <?php echo $get_animate;?>">
  <div class="box-body">
    <div class="row">
      <div class="col-md-12">
        <?php $attributes = array('name' => 'xin-form', 'id' => 'xin-form', 'autocomplete' => 'off');?>
        <?php $hidden = array('_user' => $session['user_id']);?>
        <?php echo form_open('admin/reports/report_produktifitas_per_month/', $attributes, $hidden);?>
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
				    <h3 class="box-title text-uppercase text-bold"> Produktifitas Biaya Bulan : <?php if(isset($month_year)): echo date('F Y', strtotime($month_year)); else: echo date('F Y',strtotime($bulan)); endif;?> </h3>
				    <h5>
				     Periode Produktifitas : <?php echo $start_att; ?> s/d <?php echo $end_att; ?> (<?php echo $interval_date->d; ?> hari) - 
				     Jenis : <?php echo $gaji_name ; ?> - 
				     Di <?php echo ucfirst($company_name); ?>
				    </h5>
				    <div class="box-tools pull-right"> </div>
				</div>
				
			    <div class="box-body">
				    <div class="box-datatable table-responsive">
				      <table class="datatables-demo table table-striped table-bordered" id="xin_table_borongan" width="200%">
				        <thead>
				           	<tr>
				             	
				             	<th rowspan="2" style="vertical-align: middle !important;"><?php echo $this->lang->line('xin_nomor');?></th>
					            <th rowspan="2" style="vertical-align: middle !important;"> NIP </th>
					            <th rowspan="2" style="vertical-align: middle !important;"> Karyawan </th>	 
					             <th rowspan="2" style="vertical-align: middle !important;"> Posisi </th>	            
					            <th colspan="<?php echo $interval_date->d+1; ?>"> Produktifitas Bulan <?php if(isset($month_year)): echo date('F Y', strtotime($month_year)); else: echo date('F Y',strtotime($bulan)); endif;?></th>
					        	<th rowspan="2" style="vertical-align: middle !important;"> Jumlah </th>
					        </tr>
					        <tr>
					            
					            <?php foreach($xin_tanggal as $t):?>  
					            	<?php $tgl  = date("d",strtotime($t->tanggal)); ?>
					            	<?php $bln  = date("M",strtotime($t->tanggal)); ?>
					          		<?php $day    = $this->Timesheet_model->conHari(date("D",strtotime($t->tanggal))); ?>
					          		<?php $warna  = $this->Timesheet_model->conWarna($day); ?>
					             	<th style="<?php echo $warna; ?>"><center><?php echo $bln;?><br><?php echo $tgl;?><br><?php echo $day;?> </center></th>      
						        <?php endforeach;?>
						    
						    </tr>
				        </thead>
				        <tbody>
				        		<?php $no=1; ?>

				          		<?php $j=0;foreach($xin_employees as $r):?>
				         
					          		<?php					          			

									  	// user full name 
										$full_name = $r->first_name.' '.$r->last_name;
																				
										$tanggal1 = date("Y-m-d",strtotime($start_att));
										$tanggal2 = date("Y-m-d",strtotime($end_att));

										$employee_name = $r->employee_id;
										
										$designation = $this->Designation_model->read_designation_information($r->designation_id);
										if(!is_null($designation)){
											$designation_name = $designation[0]->designation_name;
										} else {
											$designation_name = '<span class="badge bg-red"> ? </span>';	
										}

										// ================================================================================================================
										// Jumlah
										// ================================================================================================================
										$cek_jumlah_biaya = $this->Timesheet_model->hitung_jumlah_status_produktifitas($r->employee_id,$tanggal1,$tanggal2);
										$jumlah_biaya = $cek_jumlah_biaya[0]->jumlah;


									?>
					          		<?php?>

					          		
				          
							        <tr >

							          	<td width="20px" class="text-center"><?php echo $no;?></td>
							            <td width="150px" class="text-center"><?php echo $employee_name;?></td>
							            <td width="450px"><?php echo $full_name;?></td>	
							            <td width="200px"><?php echo $designation_name;?></td>	
							           

							            <?php foreach($xin_tanggal as $t):?> 

							            <?php $gram_tanggal = $t->tanggal; ?>
							             <?php $day   = $this->Timesheet_model->conHari(date("D",strtotime($t->tanggal))); ?>
					          			<?php $warna   = $this->Timesheet_model->conWarna($day); ?>   
								        <?php 
								       
									        $cek_status = $this->Timesheet_model->cek_status_produktifitas($r->employee_id,$gram_tanggal); 

									        
									        if(!is_null($cek_status)){

									        	$gram_biaya = number_format($cek_status[0]->jumlah_biaya, 0, ',', '.');
									        	
												
											} else {
												$gram_biaya = '';	
											}

											 

								        ?>
							           
							            <td width="100px" class="text-right" style="<?php echo $warna; ?>"><?php echo $gram_biaya; ?></td>



							            <?php endforeach;?>

							            <td width="100px" class="text-right"><?php echo number_format($jumlah_biaya, 0, ',', '.');?></td>
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

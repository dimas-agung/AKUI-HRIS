<?php
/* Payslip view
*/
?>
<?php $session            = $this->session->userdata('username');?>
<?php $system             = $this->Core_model->read_setting_info(1);?>
<?php $role_resources_ids = $this->Core_model->user_role_resource();?>
<?php $user_info          = $this->Core_model->read_user_info($session['user_id']);
// check
$half_title = '';


	$payment_check1 = $this->Payroll_model->read_make_payment_payslip_half_month_check_first($euser_id,$payment_date);
	$payment_check2 = $this->Payroll_model->read_make_payment_payslip_half_month_check_last($euser_id,$payment_date);

	$payment_check = $this->Payroll_model->read_make_payment_payslip_half_month_check($euser_id,$payment_date);
	
  if($payment_check->num_rows() > 1) {

		if($payment_check2[0]->payslip_key == $this->uri->segment(5)){
			$half_title = '('.$this->lang->line('xin_title_second_half').')';

		} else if($payment_check1[0]->payslip_key == $this->uri->segment(5)){
			$half_title = '('.$this->lang->line('xin_title_first_half').')';
		
    } else {
			$half_title = '';
		}

	} else {
		$half_title = '('.$this->lang->line('xin_title_first_half').')';
	}
	$half_title = $half_title;

?>
<?php
if($user_info[0]->user_role_id==1 || in_array('1011',$role_resources_ids)){
	$cmdp_1st = 'col-md-9';
	$cmdp_2nd = 'col-md-3';
} else {
	$cmdp_1st = 'col-md-12';
	$cmdp_2nd = '';
}
?>
<div class="row">
  <div class="col-md-12">
    <div class="box mb-4">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo $half_title.' - '.$this->lang->line('xin_payslip');?> - </strong><?php echo date("F, Y", strtotime($payment_date));?></h3>
        <div class="box-tools mb-4"> 
          <a target="_blank" href="<?php echo site_url();?>admin/payroll/pdf_create/p/<?php echo $payslip_key;?>/" class="btn btn-info btn-xs btn-outline-github" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $this->lang->line('xin_payroll_download_payslip');?>">
             <i class="fa fa-file-pdf-o"></i>  Slip Gaji
          </a> 
        </div>
      </div>
      <div class="box-body">
        <div class="table-responsive" data-pattern="priority-columns">
          <table class="datatables-demo table table-striped  dataTable no-footer">
            <tbody>
              <tr>
                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('dashboard_employee_id');?></strong></td> 
                <td width="18%">:&nbsp;#<?php echo $employee_id;?></td>

                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('xin_employee_name');?></strong></td> 
                <td width="18%">:&nbsp;<?php echo $first_name.' '.$last_name;?></td>
                
                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('xin_payslip_number');?></strong></td> 
                <td width="18%">:&nbsp;<strong>#<?php echo $make_payment_id;?></strong></td>
              </tr>

              <tr>
                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('xin_joining_date');?></strong></td>
                <td width="18%">:&nbsp;<?php echo $this->Core_model->set_date_format($date_of_joining);?></td>

                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('xin_phone');?></strong></td> 
                <td width="18%">:&nbsp;<?php echo $contact_no;?></td>
                
                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('xin_email');?></strong></td> 
                <td width="18%">:&nbsp;<?php echo $email;?></td>
              </tr>
              
              <tr>
                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('left_company');?></strong></td> 
                <td width="18%">:&nbsp;<?php echo $company_name;?></td>
                
                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('left_department');?></strong></td> 
                <td width="18%">:&nbsp;<?php echo $department_name;?></td>

                <td width="8%"><strong class="help-split"><?php echo $this->lang->line('left_designation');?></strong></td> 
                <td width="18%">:&nbsp;<?php echo $designation_name;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<?php $user_id = $employee_id;?>
  <div class="row m-b-1">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"> DETAIL </h3>
        </div>

        <div class="box-body">
          <div id="accordion">
            <div class="box-header with-border">
              <h3 class="box-title text-bold"><i class="fa fa-plus-circle"></i> PENGHASILAN </h3>
            </div>

            <div class="card hris-payslip mb-2">
              <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#basic_salary" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_payroll_basic_salary');?></strong> </a> </div>
              <div id="basic_salary" class="collapse" data-parent="#accordion" style="">
                <div class="box-body">
                  <div class="table-responsive" data-pattern="priority-columns">
                    <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                      <tbody>
                        <tr>
                          <td><strong><?php echo $this->lang->line('xin_payroll_basic_salary');?>: 
                            <span class="pull-right"><?php echo number_format($basic_salary, 0, ',', '.');?></span>
                            </strong>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tunjangan -->   
            <?php $count_allowances = $this->Employees_model->count_employee_allowances_payslip($make_payment_id);?>
            <?php $allowances       = $this->Employees_model->set_employee_allowances_payslip($make_payment_id);?>            
            <?php if($count_allowances > 0):?>
              <div class="card hris-payslip mb-2">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_allowances" aria-expanded="false"> 
                  <strong><?php echo $this->lang->line('xin_employee_set_allowances');?></strong> </a> 
                </div>
                <div id="set_allowances" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          
                          <?php $allowance_amount = 0; ?>
                          <?php foreach($allowances->result() as $sl_allowances) { ?>
                         
                          
                          <tr>
                            <td>
                              <strong>Tnj. Jabatan</strong> 
                              <span class="pull-right">
                                <?php echo number_format($sl_allowances->jumlah_tunj_jabatan, 0, ',', '.');?>
                                  
                                </span>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <strong>Tnj. Produktifitas</strong> 
                              <span class="pull-right">
                                <?php echo number_format($sl_allowances->jumlah_tunj_produktifitas, 0, ',', '.');?>
                                  
                                </span>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <strong>Tnj. Transportasi</strong> 
                              <span class="pull-right">
                                <?php echo number_format($sl_allowances->jumlah_tunj_transportasi, 0, ',', '.');?>
                                  
                                </span>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <strong>Tnj. Komunikasi</strong> 
                              <span class="pull-right">
                                <?php echo number_format($sl_allowances->jumlah_tunj_komunikasi, 0, ',', '.');?>                                
                              </span>
                            </td>
                          </tr>
                          
                          <?php } ?>
                          
                          <tr>
                            <td><strong> Total Tunjangan : 
                               <?php $allowance_amount = $sl_allowances->jumlah_tunj_jabatan+$sl_allowances->jumlah_tunj_produktifitas+$sl_allowances->jumlah_tunj_transportasi+$sl_allowances->jumlah_tunj_komunikasi; ?>
                                <span class="pull-right">
                                  <?php echo number_format($allowance_amount, 0, ',', '.');?>                                
                                </span>
                                </strong>
                            </td>
                          </tr>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif;?>

            <!-- Insentif -->
            <?php $count_commissions = $this->Employees_model->count_employee_commissions_payslip($make_payment_id);?>
            <?php $commissions = $this->Employees_model->set_employee_commissions_payslip($make_payment_id);?>
            <?php if($count_commissions > 0):?>
                <div class="card hris-payslip mb-2">
                  <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_commissions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_hr_commissions');?></strong> </a> </div>
                  <div id="set_commissions" class="collapse" data-parent="#accordion" style="">
                    <div class="box-body">
                      <div class="table-responsive" data-pattern="priority-columns">
                        <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                          <tbody>
                            <?php $commissions_amount = 0; foreach($commissions->result() as $sl_commissions) { ?>
                            <?php $commissions_amount += $sl_commissions->commissions_amount;?>
                            <tr>
                              <td>
                                <strong> Insentif : </strong> 
                                <span class="pull-right">
                                  <?php echo number_format($sl_commissions->commissions_amount, 0, ',', '.');?>
                                </span>
                              </td>
                            </tr>
                            <?php } ?>
                            <tr>
                              <td><strong>Total Insentif :
                                     <span class="pull-right">
                                      <?php echo number_format($commissions_amount, 0, ',', '.'); ?>
                                     </span>
                                   </strong>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            <?php else:?>
            <?php $commissions_amount = 0;?>
            <?php endif;?>

            <!-- Lembur -->
            <?php $count_overtime = $this->Employees_model->count_employee_overtime_payslip($make_payment_id);?>
            <?php $overtime = $this->Employees_model->set_employee_overtime_payslip($make_payment_id);?>
            <?php if($count_overtime > 0):?>
              <div class="card hris-payslip mb-2">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#overtime" aria-expanded="false"><strong>Lembur</strong></a></div>
                <div id="overtime" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $overtime_amount = 0; foreach($overtime->result() as $sl_overtime) { ?>
                          <?php $overtime_amount += $sl_overtime->overtime_amount;?>
                          <tr>
                            <td>
                              <strong>Lembur :</strong> 
                              <span class="pull-right"><?php echo number_format($sl_overtime->overtime_amount, 0, ',', '.');?></span>
                            </td>
                          </tr>
                          <?php } ?>
                          <tr>
                            <td><strong>Total Lembur : 
                                <span class="pull-right"><?php echo number_format($overtime_amount, 0, ',', '.');?></span>
                                </strong>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>           
            <?php else:?>
            <?php $overtime_amount = 0;?>
            <?php endif; ?>

          
            <div class="box-header with-border">
              <h3 class="box-title text-bold"><i class="fa fa-minus-circle"></i> PEMOTONG </h3>
            </div>

            <!-- BPJS -->
            <?php $count_statutory_deductions = $this->Employees_model->count_employee_statutory_deductions_payslip($make_payment_id);?>
            <?php $statutory_deductions = $this->Employees_model->set_employee_statutory_deductions_payslip($make_payment_id);?>
            <?php if($count_statutory_deductions > 0):?>
              <div class="card hris-payslip mb-2">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_statutory_deductions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_statutory_deductions');?></strong> </a> </div>
                <div id="set_statutory_deductions" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $statutory_deductions_amount = 0; foreach($statutory_deductions->result() as $sl_statutory_deductions) { ?>
                          

                          <tr>
                            <td><strong>BPJS Kesehatan :</strong> 
                                <span class="pull-right"><?php echo number_format($sl_statutory_deductions->bpjs_kes_amount, 0, ',', '.');?></span>
                            </td>
                          </tr>

                          <tr>
                            <td><strong>BPJS Tenaga Kerja :</strong> 
                                <span class="pull-right"><?php echo number_format($sl_statutory_deductions->bpjs_tk_amount, 0, ',', '.') ;?></span>
                            </td>
                          </tr>


                          <?php } ?>
                          <tr>
                            <td>
                              <strong>Total BPJS Kes & TK : 
                              <?php $statutory_deductions_amount = $sl_statutory_deductions->bpjs_kes_amount+$sl_statutory_deductions->bpjs_tk_amount;?>
                              <span class="pull-right"><?php echo number_format($statutory_deductions_amount, 0, ',', '.');?></span>
                              </strong>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php else:?>
            <?php $statutory_deductions_amount = 0;?>
            <?php endif;?>

            <!-- Pinjaman -->
            <?php $count_loan = $this->Employees_model->count_employee_deductions_payslip($make_payment_id);?>
            <?php $loan = $this->Employees_model->set_employee_deductions_payslip($make_payment_id);?>
            <?php if($count_loan > 0):?>
              <div class="card hris-payslip mb-2">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_loan_deductions" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_loan_deductions');?></strong> </a> </div>
                <div id="set_loan_deductions" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $loan_de_amount = 0; foreach($loan->result() as $r_loan) { ?>
                          <?php $loan_de_amount += $r_loan->loan_de_amount;?>
                          <tr>
                            <td><strong>Pinjaman :</strong> 
                              <span class="pull-right"><?php echo number_format($r_loan->loan_de_amount, 0, ',', '.');?></span></td>
                          </tr>
                          <?php } ?>
                          <tr>
                            <td><strong>
                                Total Pinjaman :
                                <span class="pull-right"><?php echo number_format($loan_de_amount, 0, ',', '.');?></span>
                                </strong> 
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php else:?>
            <?php $loan_de_amount = 0;?>
            <?php endif;?>

            <!-- Pajak -->
            <?php $count_other_payments = $this->Employees_model->count_employee_other_payments_payslip($make_payment_id);?>
            <?php $other_payments = $this->Employees_model->set_employee_other_payments_payslip($make_payment_id);?>
            <?php if($count_other_payments > 0):?>
              <div class="card hris-payslip mb-2">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#set_other_payments" aria-expanded="false"> <strong><?php echo $this->lang->line('xin_employee_set_other_payment');?></strong> </a> </div>
                <div id="set_other_payments" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $other_payments_amount = 0; foreach($other_payments->result() as $sl_other_payments) { ?>
                          <?php $other_payments_amount += $sl_other_payments->other_payments_amount;?>
                          <tr>
                            <td>
                              <strong>PPh 21 :</strong> 
                              <span class="pull-right"><?php echo $this->Core_model->currency_sign($sl_other_payments->other_payments_amount);?></span>
                            </td>
                          </tr>
                          <?php } ?>
                          <tr>
                            <td><strong>Total PPh 21 :
                                <span class="pull-right"><?php echo number_format($other_payments_amount, 0, ',', '.');?></span>
                                </strong> 
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            <?php else:?>
            <?php $other_payments_amount = 0;?>
            <?php endif;?>          

            <!-- Kehadiran -->
            <?php $count_attedance = $this->Employees_model->count_employee_overtime_payslip($make_payment_id);?>
            <?php $attedance = $this->Employees_model->set_employee_overtime_payslip($make_payment_id);?>
            <?php if($count_attedance > 0):?>
              <div class="card hris-payslip mb-2">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#attedance" aria-expanded="false"><strong>Potongan Absensi </strong></a></div>
                <div id="attedance" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>
                          <?php $attedance_amount = 0; foreach($attedance->result() as $sl_attedance) { ?>
                          <?php $attedance_amount += $sl_attedance->potongan_absen;?>
                          
                          <tr>
                            <td>
                              <strong>Potongan Alpa : <?php echo $sl_attedance->jumlah_alpa; ?> Hari </strong> 
                              <span class="pull-right"><?php echo number_format($sl_attedance->potongan_alpa, 0, ',', '.');?></span>
                            </td>
                          </tr>
                          
                          <tr>
                            <td>
                              <strong>Potongan Izin : <?php echo $sl_attedance->jumlah_izin; ?> Hari </strong> 
                              <span class="pull-right"><?php echo number_format($sl_attedance->potongan_izin, 0, ',', '.');?> </span>
                            </td>
                          </tr>

                          <tr>
                            <td>
                              <strong>Potongan Libur : <?php echo $sl_attedance->jumlah_libur; ?> Hari </strong> 
                              <span class="pull-right"><?php echo number_format($sl_attedance->potongan_libur, 0, ',', '.');?></span>
                            </td>
                          </tr>

                          <?php } ?>
                          <tr>
                            <td><strong>Total Potongan Absensi :                              
                                <span class="pull-right"><?php echo number_format($attedance_amount, 0, ',', '.');?></span>
                                </strong>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>           
            <?php else:?>
            <?php $attedance_amount = 0;?>
            <?php endif; ?>

            <!-- Potongan Lain -->
            <?php $count_potongan_lain = $this->Employees_model->count_employee_potongan_lain_payslip($make_payment_id);?>
            <?php $potongan_lain       = $this->Employees_model->set_employee_potongan_lain_payslip($make_payment_id);?>
            <?php if($count_potongan_lain > 0):?>
              <div class="card hris-payslip mb-2">
                <div class="card-header"> <a class="text-dark collapsed" data-toggle="collapse" href="#potongan_lain" aria-expanded="false"><strong>Potongan Lain </strong></a></div>
                <div id="potongan_lain" class="collapse" data-parent="#accordion" style="">
                  <div class="box-body">
                    <div class="table-responsive" data-pattern="priority-columns">
                      <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                        <tbody>

                          <?php $potongan_lain_amount = 0; foreach($potongan_lain->result() as $sl_potongan_lain) { ?>
                          <?php $potongan_lain_amount += $sl_potongan_lain->potongan_lain;?>
                          
                          <tr>
                            <td>
                              <strong>Potongan Lain : </strong> 
                              <span class="pull-right"><?php echo number_format($sl_potongan_lain->potongan_lain, 0, ',', '.');?></span>
                            </td>
                          </tr>

                          <?php } ?>
                          <tr>
                            <td><strong>Total Potongan Lain :                              
                                <span class="pull-right"><?php echo number_format($potongan_lain_amount, 0, ',', '.');?></span>
                                </strong>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>           
            <?php else:?>
            <?php $potongan_lain_amount = 0;?>
            <?php endif; ?>


          </div>
        </div>

      </div>
    </div>
    <div class="col-md-4">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"> REKAP DETAIL </h3>
            </div>
            <div class="box-body">
              <div class="table-responsive" data-pattern="priority-columns">
                <table class="datatables-demo table table-striped table-bordered dataTable no-footer">
                  <tbody>
                    
                    <tr>
                      <td><strong>Komponen Penghasilan :</strong></td>
                    </tr>

                    <tr>
                      <td><strong><?php echo $this->lang->line('xin_payroll_basic_salary');?>:</strong> 
                        <span class="pull-right"><?php echo number_format($basic_salary, 0, ',', '.');?></span>
                      </td>
                    </tr>
                    
                    <!-- Tunjangan -->                   
                    <tr>
                        <td><strong> Total Tunjangan :</strong> 
                            <span class="pull-right"><?php echo number_format($total_allowances, 0, ',', '.');?></span>
                        </td>
                    </tr>
                    
                    <!-- Insentif -->                   
                    <tr>
                      <td><strong> Total Insentif:</strong> 
                        <span class="pull-right"><?php echo number_format($total_commissions, 0, ',', '.');?></span>
                      </td>
                    </tr>                                       

                    <!-- Overtime -->                   
                    <tr>
                      <td><strong> Total Lembur :</strong> 
                          <span class="pull-right"><?php echo number_format($total_overtime, 0, ',', '.');?></span>
                      </td>
                    </tr>
                    
                    <tr>
                      <td>
                        
                      </td>
                    </tr>

                    <tr>
                      <td><strong>Komponen Pemotong :</strong> 
                        
                      </td>
                    </tr>

                    <!-- BPJS -->                 
                    <tr>
                      <td><strong> Total BPJS Kes & TK :</strong> 
                          <span class="pull-right"><?php echo number_format($total_statutory_deductions, 0, ',', '.');?></span>
                      </td>
                    </tr>
                    
                     <!-- Pinjaman -->                   
                    <tr>
                      <td><strong> Total Pinjaman :</strong> 
                        <span class="pull-right"><?php echo number_format($total_loan, 0, ',', '.');?></span>
                      </td>
                    </tr>

                    <!-- Pajak -->
                    <tr>
                      <td><strong> Total Pajak (PPh 21) :</strong> 
                          <span class="pull-right"><?php echo number_format($total_other_payments, 0, ',', '.');?></span>
                      </td>
                    </tr>

                    <!-- Potongan -->
                    <tr>
                      <td><strong> Total Potongan Absensi :</strong> 
                          <span class="pull-right"><?php echo number_format($total_attedance, 0, ',', '.');?></span>
                      </td>
                    </tr>

                     <!-- Potongan Lain -->
                    <tr>
                      <td><strong> Total Potongan Lain :</strong> 
                          <span class="pull-right"><?php echo number_format($total_potongan_lain, 0, ',', '.');?></span>
                      </td>
                    </tr>
                    
                     <tr>
                      <td>
                        
                      </td>
                    </tr>
                    
                    <tr>
                      <td><strong> Total THP :</strong> <span class="blink blink-one pull-right text-bold"> <?php echo number_format($net_salary, 0, ',', '.');?></span></td>
                    </tr>
                 
                  </tbody>
                </table>
              </div>

            </div>
             
          </div>
          
        </div>
      </div>
    </div>
  </div>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['izin_id']) && $_GET['data']=='izin'){
?>
<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_edit_izin');?></h4>
</div>
<?php $attributes = array('name' => 'edit_izin', 'id' => 'edit_izin', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $izin_id, 'ext_name' => $izin_id);?>
<?php echo form_open('admin/permission/edit_izin/'.$izin_id, $attributes, $hidden);?>
<?php $session = $this->session->userdata('username');?>
<?php $user = $this->Core_model->read_employee_info($session['user_id']);?>

  <div class="modal-body">
    <div class="row">       
      <div class="col-md-12">
        <div class="form-group">
          <label for="description"><?php echo $this->lang->line('xin_remarks');?></label>
          <textarea class="form-control textarea" placeholder="<?php echo $this->lang->line('xin_remarks');?>" name="remarks" cols="30" rows="3"><?php echo $remarks;?></textarea>
        </div>
      </div>
    <div class="col-md-12">
        <div class="form-group">
          <label for="reason"><?php echo $this->lang->line('xin_izin_reason');?></label>
          <textarea class="form-control" placeholder="<?php echo $this->lang->line('xin_izin_reason');?>" name="reason" cols="30" rows="3" id="reason"><?php echo $reason;?></textarea>
        </div>
    </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('xin_update');?></button>
  </div>
<?php echo form_close(); ?>
<script type="text/javascript">
 $(document).ready(function(){
							
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	jQuery("#ajx_company").change(function(){
		jQuery.get(base_url+"/get_update_employees/"+jQuery(this).val(), function(data, status){
			jQuery('#employee_ajx').html(data);
		});
	});
	$('#remarks2').trumbowyg();	
	// Date
	$('.e_date').datepicker({
	  changeMonth: true,
	  changeYear: true,
	  dateFormat:'yy-mm-dd',
	  yearRange: '1900:' + (new Date().getFullYear() + 15),
	});
	/* Edit*/
	$("#edit_izin").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=2&edit_type=izin&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data').modal('toggle');
					var xin_table = $('#xin_table').dataTable({
						"bDestroy": true,
						"ajax": {
							url : "<?php echo site_url("admin/permission/izin_list") ?>",
							type : 'GET'
						},
						// dom: 'lBfrtip',
						//// // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
             "columns": [
                  {"name": "kolom_0", "className": "text-center"},
              {"name": "kolom_1", "className": "text-center"},
              {"name": "kolom_2", "className": "text-center"},
              {"name": "kolom_3", "className": "text-center"},  
              {"name": "kolom_4", "className": "text-left"},
              {"name": "kolom_5", "className": "text-left"},
              {"name": "kolom_6", "className": "text-left"}
                          
            ],
             "language": {
                    "aria": {
                        "sortAscending" : ": activate to sort column ascending",
                        "sortDescending": ": activate to sort column descending"
                    },
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "loadingRecords": "Silahkan Tunggu...",
                "processing": "Sedang memproses...",
                 "search"      : "Pencarian : ", "searchPlaceholder": "Masukan Kata Pencarian ...",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "thousands": "'",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
              },
						"fnDrawCallback": function(settings){
						$('[data-toggle="tooltip"]').tooltip();          
						}
					});
					xin_table.api().ajax.reload(function(){ 
						alert_success('Sukses',JSON.result);
					}, true);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php } else if(isset($_GET['jd']) && isset($_GET['izin_id']) && $_GET['data']=='view_izin'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><?php echo $this->lang->line('xin_view');?> <?php echo $this->lang->line('left_izin');?></h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <table class="footable-details table table-striped table-hover toggle-circle">
      <tbody>
        <tr>
          <th><?php echo $this->lang->line('module_company_title');?></th>
          <td style="display: table-cell;"><?php foreach($get_all_companies as $company) {?>
            <?php if($company_id==$company->company_id):?>
            <?php echo $company->name;?>
            <?php endif;?>
            <?php } ?></td>
        </tr>
        <?php $employee = $this->Core_model->read_user_info($employee_id); ?>
			<?php if(!is_null($employee)):?><?php $eName = $employee[0]->first_name. ' '.$employee[0]->last_name;?>
			<?php else:?><?php $eName='';?><?php endif;?>
        <tr>
          <th><?php echo $this->lang->line('xin_employee');?></th>
          <td style="display: table-cell;"><?php echo $eName;?></td>
        </tr>    
        <tr>
          <th><?php echo $this->lang->line('xin_izin_type');?></th>
          <td style="display: table-cell;"><?php foreach($all_izin_types as $type) {?>
            <?php if($type->izin_type_id==$izin_type_id):?> <?php echo $type->type_name;?> <?php endif;?>
            <?php } ?></td>
        </tr>
        <tr>
          <th><?php echo $this->lang->line('xin_start_date');?></th>
          <td style="display: table-cell;"><?php echo $this->Core_model->set_date_format($from_date);?></td>
        </tr>
        <tr>
          <th><?php echo $this->lang->line('xin_end_date');?></th>
          <td style="display: table-cell;"><?php echo $this->Core_model->set_date_format($to_date);?></td>
        </tr>
        <tr>
          <th><?php echo $this->lang->line('xin_remarks');?></th>
          <td style="display: table-cell;"><?php echo html_entity_decode($remarks);?></td>
        </tr>
        <tr>
          <th><?php echo $this->lang->line('xin_izin_reason');?></th>
          <td style="display: table-cell;"><?php echo html_entity_decode($reason);?></td>
        </tr>
        <?php if($status=='1'):?> <?php $status_lv = $this->lang->line('xin_pending');?> <?php endif; ?>
        <?php if($status=='2'):?> <?php $status_lv = $this->lang->line('xin_approved');?> <?php endif; ?>
        <?php if($status=='3'):?> <?php $status_lv = $this->lang->line('xin_rejected');?> <?php endif; ?>
        <tr>
          <th><?php echo $this->lang->line('dashboard_xin_status');?></th>
          <td style="display: table-cell;"><?php echo $status_lv;?></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>
<?php echo form_close(); ?>
<?php }?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['skala_upah_id']) && $_GET['data']=='skala_upah'){
?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Skala Upah Pekerjaan </h4>
</div>
<?php $attributes = array('name' => 'edit_skala_upah', 'id' => 'edit_skala_upah', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $skala_upah_id, 'ext_name' => $skala_upah_name);?>
<?php echo form_open('admin/pengaturan/update_skala_upah/'.$skala_upah_id, $attributes, $hidden);?>
  <div class="modal-body">
     <div class="row">

      <div class="col-md-6">
        <div class="form-group">
          <label for="first_name"><?php echo $this->lang->line('left_company');?></label>
          <select class="form-control" name="company_id" id="ajx_company" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('left_company');?>">
            <option value=""></option>
            <?php foreach($get_all_companies as $company) {?>
            <option value="<?php echo $company->company_id?>" <?php if($company->company_id==$company_id) {?> selected="selected" <?php } ?>><?php echo $company->name?></option>
            <?php } ?>
          </select>
        </div>       
      </div>      
            
      <div class="col-md-6">
        <div class="form-group" id="ajx_workstation_modal">
          <label for="name"><?php echo $this->lang->line('xin_hr_main_workstation');?></label>
          <?php $result_workstation = $this->Company_model->ajax_company_workstations_info($company_id);?>
          <select name="workstation_id" class="form-control" data-plugin="select_hrm" data-placeholder="<?php echo $this->lang->line('xin_select_workstation');?>">
            <option value=""></option>
            <?php foreach($result_workstation as $workstation) {?>
            <option value="<?php echo $workstation->workstation_id?>" <?php if($workstation->workstation_id==$workstation_id) {?> selected="selected" <?php } ?>><?php echo strtoupper($workstation->workstation_name);?></option>
            <?php } ?>
          </select>
        </div>
      </div>

      
     
    </div>

    <div class="row">
      
     <div class="col-md-6">   
        <div class="form-group">
        <label for="skala_upah"><?php echo $this->lang->line('xin_skala_upah_name');?></label>
        <input type="text" class="form-control" name="skala_upah_name" value="<?php echo $skala_upah_name;?>">
        </div>
      </div>

      <div class="col-md-6">   
        <div class="form-group">
        <label for="skala_upah"><?php echo $this->lang->line('xin_skala_upah_ongkos');?></label>
        <input type="text" class="form-control" name="skala_upah_ongkos" value="<?php echo $skala_upah_ongkos;?>">
        </div>
      </div>

    </div>

    <div class="row">
      
     <div class="col-md-6">   
        <div class="form-group">
        <label for="skala_upah"><?php echo $this->lang->line('xin_skala_upah_kode');?></label>
        <input type="text" class="form-control" name="skala_upah_kode" value="<?php echo $skala_upah_kode;?>">
        </div>
      </div>

      

    </div>

  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('xin_update');?></button>
  </div>
<?php echo form_close(); ?>
<script type="text/javascript">
$(document).ready(function(){
	
	$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
	$('[data-plugin="select_hrm"]').select2({ width:'100%' });
	
	jQuery("#ajx_company").change(function(){
		jQuery.get(base_url+"/get_model_departments/"+jQuery(this).val(), function(data, status){
			jQuery('#ajx_department_modal').html(data);
		});

    jQuery.get(base_url+"/get_model_workstations/"+jQuery(this).val(), function(data, status){
      jQuery('#ajx_workstation_modal').html(data);
    });

	});	
	
	/* Edit data */
	$("#edit_skala_upah").submit(function(e){
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=1&edit_type=skala_upah&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				} else {
					// On page load: datatable
					var xin_table = $('#xin_table').dataTable({
						"bDestroy": true,
						"ajax": {
							url : "<?php echo site_url("admin/pengaturan/skala_upah_list") ?>",
							type : 'GET'
						},
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
						// dom: 'lBfrtip',
						// // // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                            
            if ( aData[4] == 'PT AKUI BIRD NEST INDONESIA' ) {
                $('td', nRow).css('background-color', '#e0f9e9' );
            } else if ( aData[4] == 'PT ORIGINAL BERKAH INDONESIA' ) {
                $('td', nRow).css('background-color', '#f9f7e0' );
            } else if ( aData[4] == 'PT WALET ABDILLAH JABLI' ) {
                $('td', nRow).css('background-color', '#f8e0f9' );
            }             
        }
						
					});
					xin_table.api().ajax.reload(function(){ 
						alert_success('Sukses',JSON.result);
					}, true);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.edit-modal-data').modal('toggle');
					$('.save').prop('disabled', false);
				}
			}
		});
	});
});	
</script>
<?php }
?>

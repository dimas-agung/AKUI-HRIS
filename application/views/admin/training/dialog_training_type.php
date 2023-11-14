<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['training_type_id']) && $_GET['data']=='training'){
?>

<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data"><i class="fa fa-pencil"></i> Edit Jenis Pelatihan </h4>
</div>
<?php $attributes = array('name' => 'edit_type', 'id' => 'edit_type', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $training_type_id, 'ext_name' => $training_type_id);?>
<?php echo form_open('admin/training_type/update/'.$training_type_id, $attributes, $hidden);?>
  <div class="modal-body">

  	<div class="row">
        <div class="col-md-12">

          <div class="form-group">
            <label for="kategori">Kategori </label>
            <select class="form-control" name="kategori" data-plugin="select_hrm" data-placeholder="Kategori">
              <option value=""></option>
              <?php foreach($all_kategori as $kategori_list) {?>
              <option value="<?php echo $kategori_list->training_type_id;?>" <?php if($kategori==$kategori_list->training_type_id):?> selected="selected" <?php endif;?>> <?php echo $kategori_list->type;?></option>
              <?php } ?>
            </select>
          </div>

           <div class="form-group">
		      <label for="type_name" class="form-control-label">Jenis Pelatihan </label>
		      <input type="text" class="form-control" name="type_name" value="<?php echo $type?>" placeholder="Jenis Pelatihan ">
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
	/* Edit data */
	$("#edit_type").submit(function(e){
	/*Form Submit*/
	e.preventDefault();
		var obj = $(this), action = obj.attr('name');
		$('.save').prop('disabled', true);
		$.ajax({
			type: "POST",
			url: e.target.action,
			data: obj.serialize()+"&is_ajax=2&edit_type=training&edit=1&form="+action,
			cache: false,
			success: function (JSON) {
				if (JSON.error != '') {
					alert_fail('Gagal',JSON.error);
					$('input[name="csrf_hris"]').val(JSON.csrf_hash);
					$('.save').prop('disabled', false);
				} else {
					$('.edit-modal-data-min').modal('toggle');
					// On page load: datatable
					var xin_table = $('#xin_table').dataTable({
						"bDestroy": true,
						"ajax": {
							url : "<?php echo site_url("admin/training_type/type_list") ?>",
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
<?php }
?>

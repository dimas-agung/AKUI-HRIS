<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['instansi_id']) && $_GET['data']=='instansi'){
?>
<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data-min"> <i class="fa fa-pencil"></i> Edit Instansi</h4>
</div>
<?php $attributes = array('name' => 'edit_instansi', 'id' => 'edit_instansi', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $instansi_name, 'ext_name' => $instansi_name);?>
<?php echo form_open('admin/instansi/update/'.$instansi_id, $attributes, $hidden);?>
<div class="modal-body">
    <div class="row">

      
      <div class="col-sm-12">        
        <div class="form-group">
          <label for="name">Nama Instansi</label>
          <input class="form-control" placeholder="Nama Instansi" name="instansi_name" type="text" value="<?php echo $instansi_name;?>">
        </div>        
      </div>

      <div class="col-sm-12">        
        <div class="form-group">
          <label for="name">Kontak Instansi</label>
          <input class="form-control" placeholder="Kontak Instansi" name="instansi_contact" type="text" value="<?php echo $instansi_contact;?>">
        </div>        
      </div>

     

      <div class="col-sm-12">        
        <div class="form-group">
          <label for="name">No Telp Instansi</label>
          <input class="form-control" placeholder="No Telp Instansi" name="instansi_phone" type="text" value="<?php echo $instansi_phone;?>">
        </div>        
      </div>

       <div class="col-sm-12">        
        <div class="form-group">
          <label for="name">Alamat Instansi</label>
          <textarea class="form-control" placeholder="Alamat Instansi" name="instansi_address" cols="30" rows="10" value=""><?php echo $instansi_address;?></textarea>          
        </div>        
      </div>
      
     
    </div>
  </div>
  <div class="modal-footer"> 
    <?php echo form_button(array('data-dismiss' => 'modal', 'type' => 'button', 'class' => $this->Core_model->form_button_close_class(), 'content' => '<i class="fa fa fa-check-square-o"></i> '.$this->lang->line('xin_close'))); ?> 
    <?php echo form_button(array('name' => 'hris_form', 'type' => 'submit', 'class' => $this->Core_model->form_button_class(), 'content' => '<i class="fa fa-save"></i> '.$this->lang->line('xin_update'))); ?> 
  </div>

<?php echo form_close(); ?>

<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/select2/dist/css/select2.min.css">
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/select2/dist/js/select2.min.js"></script> 

<script type="text/javascript">
 $(document).ready(function(){
							
		$('[data-plugin="select_hrm"]').select2($(this).attr('data-options'));
		$('[data-plugin="select_hrm"]').select2({ width:'100%' });
			 

		/* Edit data */
		$("#edit_instansi").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=instansi&form="+action,
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
								url : "<?php echo site_url("admin/instansi/instansi_list") ?>",
								type : 'GET'
							},
							// dom: 'lBfrtip',
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
							// // // "buttons": ['csv', 'excel', 'pdf', 'print'], // colvis > if needed
							"fnDrawCallback": function(settings){
							$('[data-toggle="tooltip"]').tooltip();          
							}
						});
						xin_table.api().ajax.reload(function(){ 
							alert_success('Sukses',JSON.result);
						}, true);
						$('input[name="csrf_hris"]').val(JSON.csrf_hash);
						$('.edit-modal-data-min').modal('toggle');
						$('.save').prop('disabled', false);
					}
				}
			});
		});
	});	
  </script>
<?php } else if(isset($_GET['jd']) && isset($_GET['instansi_id']) && $_GET['data']=='view_instansi'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data-min"><?php echo $this->lang->line('xin_view_instansi');?></h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="table-responsive" data-pattern="priority-columns">
      <table class="footable-details table table-striped table-hover toggle-circle">
        <tbody>
          
          <tr>
            <th>Nama Instansi</th>
            <td style="display: table-cell;"><?php echo $instansi_name;?></td>
          </tr>
          
        </tbody>
      </table>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('xin_close');?></button>
  </div>
<?php echo form_close(); ?>
<?php }
?>

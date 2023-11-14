<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($_GET['jd']) && isset($_GET['perizinan_id']) && $_GET['data']=='perizinan'){
?>
<?php $session = $this->session->userdata('username');?>
<?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data-min"> <i class="fa fa-pencil"></i> Edit Perizinan</h4>
</div>
<?php $attributes = array('name' => 'edit_perizinan', 'id' => 'edit_perizinan', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
<?php $hidden = array('_method' => 'EDIT', '_token' => $perizinan_no, 'ext_name' => $perizinan_no);?>
<?php echo form_open('admin/perizinan/update/'.$perizinan_id, $attributes, $hidden);?>
<div class="modal-body">
    <div class="row">

      <div class="col-sm-12">
          <div class="form-group">
            <label for="first_name">Jenis Perizinan</label>
            <select class="form-control" name="perizinan_type_id" data-plugin="select_hrm" data-placeholder="Pilih Jenis Perizinan">
              <option value=""></option>
              <?php foreach($get_all_perizinan_type as $perizinan_type) {?>
              <option value="<?php echo $perizinan_type->perizinan_type_id?>"<?php if($perizinan_type_id==$perizinan_type->perizinan_type_id):?> selected="selected"<?php endif;?>><?php echo strtoupper($perizinan_type->perizinan_type_name);?></option>
              <?php } ?>
            </select>
          </div>
      </div>

      <div class="col-sm-12">
          <div class="form-group">
            <label for="first_name">Nama Instansi</label>
            <select class="form-control" name="instansi_id" data-plugin="select_hrm" data-placeholder="Pilih Nama Instansi">
              <option value=""></option>
              <?php foreach($get_all_instansi as $instansi) {?>
              <option value="<?php echo $instansi->instansi_id?>"<?php if($instansi_id==$instansi->instansi_id):?> selected="selected"<?php endif;?>><?php echo strtoupper($instansi->instansi_name);?></option>
              <?php } ?>
            </select>
          </div>
      </div>

      <div class="col-sm-12">        
        <div class="form-group">
          <label for="name">No Perizinan</label>
          <input class="form-control" placeholder="No Perizinan" name="perizinan_no" type="text" value="<?php echo $perizinan_no;?>">
        </div>        
      </div>

      <div class="col-sm-12">        
        <div class="form-group">
          <label for="name">Nama Perizinan</label>
          <input class="form-control" placeholder="Nama Perizinan" name="perizinan_nama" type="text" value="<?php echo $perizinan_nama;?>">
        </div>        
      </div>

      <div class="col-sm-12">        
        <div class="form-group">
          <label for="name">Tanggal Diperoleh</label>
          <input class="form-control date1" placeholder="Tanggal Diperoleh" name="start_date" type="text" value="<?php echo $start_date;?>">
        </div>        
      </div>

      <div class="col-sm-12">        
        <div class="form-group">
          <label for="name">Berlaku Sampai</label>
          <input class="form-control date2" placeholder="Berlaku Sampai" name="end_date" type="text" value="<?php echo $end_date;?>">
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
			 
    $('.date1').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
      yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10),
    });

    $('.date2').datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
      yearRange: new Date().getFullYear() + ':' + (new Date().getFullYear() + 10),
    });

		/* Edit data */
		$("#edit_perizinan").submit(function(e){
		e.preventDefault();
			var obj = $(this), action = obj.attr('name');
			$('.save').prop('disabled', true);
			
			$.ajax({
				type: "POST",
				url: e.target.action,
				data: obj.serialize()+"&is_ajax=1&edit_type=perizinan&form="+action,
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
								url : "<?php echo site_url("admin/perizinan/perizinan_list") ?>",
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
<?php } else if(isset($_GET['jd']) && isset($_GET['perizinan_id']) && $_GET['data']=='view_perizinan'){
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
  <h4 class="modal-title" id="edit-modal-data-min"><?php echo $this->lang->line('xin_view_perizinan');?></h4>
</div>
<form class="m-b-1">
  <div class="modal-body">
    <div class="table-responsive" data-pattern="priority-columns">
      <table class="footable-details table table-striped table-hover toggle-circle">
        <tbody>
          
          <tr>
            <th>Nama Instansi</th>
            <td style="display: table-cell;"><?php echo $perizinan_no;?></td>
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

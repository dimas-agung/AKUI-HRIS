<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php if(isset($_GET['jd']) && isset($_GET['vendor_id']) && $_GET['data']=='vendor'){  ?>

    <?php $session = $this->session->userdata('username');?>
    <?php $user_info = $this->Core_model->read_user_info($session['user_id']);?>

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data-min"> <i class="fa fa-pencil"></i> Edit Vendor</h4>
    </div>

    <?php $attributes = array('name' => 'edit_vendor', 'id' => 'edit_vendor', 'autocomplete' => 'off', 'class'=>'m-b-1');?>
    <?php $hidden     = array('_method' => 'EDIT', '_token' => $vendor_id, 'ext_name' => $vendor_id);?>

    <?php echo form_open('admin/vendors/update/'.$vendor_id, $attributes, $hidden);?>

    <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="name">Nama Vendor</label>
                <input class="form-control" placeholder="Nama Vendor" name="name" type="text" value="<?php echo $name;?>">
              </div>
            </div>        
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
    	
    	//$('#expertise2').trumbowyg();
    	/* Edit data */
    	$("#edit_vendor").submit(function(e){
    	  e.preventDefault();
    		var obj = $(this), action = obj.attr('name');
    		$('.save').prop('disabled', true);
    		$.ajax({
    			type: "POST",
    			url: e.target.action,
    			data: obj.serialize()+"&is_ajax=1&edit_type=vendor&form="+action,
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
    							url : "<?php echo site_url("admin/vendors/vendor_list") ?>",
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
    					$('.edit-modal-data-min').modal('toggle');
    					$('.save').prop('disabled', false);
    				}
    			}
    		});
    	});

    });	
    </script>

<?php } else if(isset($_GET['jd']) && isset($_GET['vendor_id']) && $_GET['data']=='view_vendor'){ ?>

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      <h4 class="modal-title" id="edit-modal-data-min"><i class="fa fa-eye"></i> Lihat Vendor </h4>
    </div>
    <form class="m-b-1">
    <div class="modal-body">
      <table class="footable-details table table-striped table-hover toggle-circle">
        <tbody>
          
          <tr>
            <th>Nama Vendor</th>
            <td style="display: table-cell;"><?php echo $name;?></td>
          </tr>
          
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('xin_close');?></button>
    </div>

    <?php echo form_close(); ?>

<?php } ?>

<?php
/* Assets view
*/
?>
<?php $session = $this->session->userdata('username');?>
<?php $get_animate = $this->Core_model->get_content_animate();?>
<?php $role_resources_ids = $this->Core_model->user_role_resource(); ?>



<div class="box <?php echo $get_animate;?>">
  <div class="box-header with-border">
        <h3 class="box-title">Daftar Semua Aset Status : Dikembalikan</h3>
      </div>
  <div class="box-body">
  <div class="box-datatable table-responsive">
    <table class="datatables-demo table table-striped table-bordered" id="xin_table" width="100%">
      <thead>
        <tr>
          <th width="8%" class="text-center"><?php echo $this->lang->line('xin_action');?></th>
          <th width="10%" class="text-center"> <i class="fa fa-clock-o"></i> Tanggal Pinjam</th>
           <th width="10%" class="text-center"> <i class="fa fa-clock-o"></i> Tanggal Kembali</th>
          <th width="10%" class="text-center"> NIP </th>
          <th width="20%" class="text-center"> Nama Karyawan </th>
          <th width="10%" class="text-center"> Kode Aset </th>
          <th class="text-center"> Nama Aset Dipinjam </th>
          <th width="10%" class="text-center"> Status Aset </th>          
        </tr>
      </thead>
    </table>
  </div>
</div>
</div>
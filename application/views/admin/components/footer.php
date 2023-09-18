<?php $system = $this->Core_model->read_setting_info(1);?>
<?php $theme = $this->Core_model->read_theme_info(1);?>
<?php
if($theme[0]->fixed_layout=='true') {
	$lay_fixed = 'navbar-fixed-bottom';
} else {
	$lay_fixed = 'footer-static';
}
?>

<footer class="main-footer <?php echo $theme[0]->footer_layout;?>"> <strong>
  <?php if($system[0]->enable_current_year=='yes'):?>
  HRIS <?php echo date('Y');?> <?php echo $this->Core_model->hris_version();?>
  <?php endif;?>
  © <b><?php echo $system[0]->footer_text;?> </b>
  <?php if($system[0]->enable_page_rendered=='yes'):?>
  - <?php echo $this->lang->line('xin_page_rendered_text');?> <strong>{elapsed_time}</strong> <?php echo $this->lang->line('xin_rendered_seconds');?>. <?php echo  (ENVIRONMENT === 'development') ?  ''.$this->lang->line('xin_codeigniter_version').' <strong>' . CI_VERSION . '</strong>' : '' ?>
  <?php endif; ?>
  </strong> </footer>


<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark"> 
  <!-- Tab panes -->
  <div class="tab-content"> 
    <!-- Home tab content -->
    <div class="tab-pane" id="control-sidebar-home-tab"> </div>
    <!-- /.tab-pane --> 
  </div>
</aside>
<style type="text/css">
.info-box-text-hris-modal {
    font-size: 16px !important;
	text-transform: none;
	font-weight: 500 !important;
}
.info-box-content-hris-modal {
	font-size: 20px !important;
	padding: 24px 0px 0px 0 !important;
    margin-left: 70px !important;
}
.info-box-icon-hris-modal {
    background: none !important;
	font-size: 40px !important;
}
.modal-hris-modal {
    background: rgba(34,37,42,0.95);
}
.hris-close-button {
	color:#fff;
	opacity: 1.2;
	font-size: 35px !important;
	background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    width: 35px;
    border-radius: 4px;
}
.info-box-text-hris-modal a{
	color:#000 !important;
}
</style>


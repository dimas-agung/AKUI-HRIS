<?php $session = $this->session->userdata('username'); ?>
<?php $company = $this->Core_model->read_company_setting_info(1);?>
<?php $user = $this->Core_model->read_user_info($session['user_id']); ?>
<?php $system = $this->Core_model->read_setting_info(1);?>
<?php $theme = $this->Core_model->read_theme_info(1);?>
<?php $this->load->view('admin/components/vendors/del_dialog');?>
<!-- jQuery 3 -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/jquery/jquery-3.2.1.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/jquery-ui/jquery-ui.min.js"></script>
  
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/raphael/raphael.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/bootstrap-select/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/select2/dist/js/select2.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/Trumbowyg/dist/trumbowyg.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/toastr/toastr.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/sweet-alert/dist/sweetalert.min.js"></script>
<!-- Slimscroll -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/fastclick/lib/fastclick.js"></script>
<!-- App -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/dist/js/adminlte.min.js"></script>
<!-- App -->
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/nizar.js"></script>

<?php if($theme[0]->theme_option == 'template_1'):?>
	<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/dist/js/demo.js"></script>
<?php else:?>
	<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/dist/js/demo_template2.js"></script>
<?php endif;?>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.11.3/af-2.3.7/b-2.0.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.3/sr-1.0.0/datatables.min.js"></script>

<!-- 
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/theme/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
 -->
<script type="text/javascript">var user_role = '<?php //echo $user[0]->user_role_id;?>';</script>
<script type="text/javascript">var user_session_id = '<?php echo $session['user_id'];?>';</script>
<script type="text/javascript">var js_date_format = '<?php echo $this->Core_model->set_date_format_js();?>';</script>
<script type="text/javascript">var site_url = '<?php echo site_url(); ?>admin/';</script>
<script type="text/javascript">var base_url = '<?php echo site_url().'admin/'.$this->router->fetch_class(); ?>';</script>
<script type="text/javascript">var processing_request = '<?php echo $this->lang->line('xin_processing_request');?>';</script>
<script type="text/javascript">var request_submitted = '<?php echo $this->lang->line('xin_hr_request_submitted');?>';</script>

<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/bootstrap-checkbox.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
	toastr.options.closeButton = <?php echo $system[0]->notification_close_btn;?>;
	toastr.options.progressBar = <?php echo $system[0]->notification_bar;?>;
	toastr.options.timeOut = 3000;
	toastr.options.showMethod = 'slideDown';
	toastr.options.hideMethod = 'slideUp';
	toastr.options.preventDuplicates = true;
	toastr.options.positionClass = "<?php echo $system[0]->notification_position;?>";
   // setTimeout(refreshChatMsgs, 5000);
   $('[data-toggle="popover"]').popover();
});
function escapeHtmlSecure(str)
{
	var map =
	{
		'alert': '&lt;',
		'313': '&lt;',
		'bzps': '&lt;',
		'<': '&lt;',
		'>': '&gt;',
		'script': '&lt;',
		'html': '&lt;',
		'php': '&lt;',
	};
	return str.replace(/[<>]/g, function(m) {return map[m];});
}	
</script>

<!-- <script type="text/javascript">
        function zoom() {
            document.body.style.zoom = "80%";
        }
</script> -->
<script type="text/javascript">
$(document).ready(function(){
	
	/*  Toggle Starts   */
	//iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    });
	$('.js-switch:checkbox').checkboxpicker();
	$('.date').datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat:'yy-mm-dd',
	yearRange: '1900:' + (new Date().getFullYear() + 15),
	beforeShow: function(input) {
		$(input).datepicker("widget").show();
	}
	});
});
</script>

<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo base_url().'skin/assets/scripts/'.$path_url.'.js'; ?>"></script>
<?php if($this->router->fetch_class() =='dashboard') { ?>
	<?php if($system[0]->is_ssl_available=='yes'){?>
	<script src="<?php echo base_url();?>skin/assets/scripts/user/set_clocking_ssl.js"></script>
    <?php } else {?>
    <script src="<?php echo base_url();?>skin/assets/scripts/user/set_clocking_non_ssl.js"></script>
    <?php } ?>
<?php } ?>
<script src="<?php echo base_url();?>skin/assets/scripts/custom.js"></script>
<?php if($this->router->fetch_class() =='roles') { ?>
<script type="text/javascript" src="<?php echo base_url();?>skin/assets/vendor/kendo/kendo.all.min.js"></script>
<?php $this->load->view('admin/roles/role_values');?>
<?php } ?>
<?php if($this->router->fetch_class() =='organization'){?>
<?php $this->load->view('admin/components/vendors/organization_chart');?>
<?php } ?>


<script src="<?php echo base_url();?>skin/assets/vendor/libs/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

<?php if($this->router->fetch_class() =='dashboard') { ?>
<script src="<?php echo base_url();?>skin/assets/vendor/chart/chart.min.js" type="text/javascript"></script>   

<!-- <script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_company.js" type="text/javascript"></script> -->

<!-- <script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_department.js" type="text/javascript"></script> -->

<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_department_birdnest.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_department_trading.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_department_asa.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_religi.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_education.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_grade.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_jenis_payroll.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_masuk.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_keluar.js" type="text/javascript"></script>
<!-- <script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_designation.js" type="text/javascript"></script> -->
<!-- <script src="<?php echo base_url();?>skin/assets/scripts/xchart/employee_location.js" type="text/javascript"></script> -->
<?php } ?>
    	

<?php if($system[0]->module_chat_box=='true'){?>
<?php if($this->router->fetch_class() =='chat'){?>
<script>  
  $('#chat-app, #chat-contact').slimScroll({
	height: '420px'
  });
</script>
<?php } ?>
 <script type="text/javascript">
 $(document).ready(function(){
   setTimeout(refreshChatMsgs, 5000);
});
function refreshChatMsgs() {
	  $.ajax({
		url: site_url + "chat/refresh_chat_users_msg/",
		type: 'GET',
		dataType: 'html',
		success: function(data) {
			setTimeout(refreshChatMsgs, 5000);
		  	jQuery('#msgs_count').html(data);
		},
		error: function() {
		  
		}
	  });
}
</script>
<?php } ?>
<!-- <?php if($this->router->fetch_class() =='theme'){?> -->
<script>
  function testAnim(x) {
    $('#animationSandbox').removeClass().addClass(x + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
      $(this).removeClass();
    });
  };

  $(document).ready(function(){
    $('.js--triggerAnimation').click(function(e){
      e.preventDefault();
      var anim = $('.js--animations').val();
      testAnim(anim);
    });

    $('.js--animations').change(function(){
      var anim = $(this).val();
      testAnim(anim);
    });
  });
</script>
<!-- <?php } ?> -->

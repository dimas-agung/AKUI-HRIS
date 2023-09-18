<?php $company = $this->Core_model->read_company_setting_info(1);?>
<?php $favicon = base_url().'uploads/logo/favicon/'.$company[0]->favicon;?>
<?php $theme = $this->Core_model->read_theme_info(1);?>
<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title." ".date('Y');?></title>

<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>

<meta name="viewport"     content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
<meta name="description"  content="HRIS - Human Resources Information System">    
<meta name="author"       content="Nizar Basyrewan">        
<meta name="mobile-web-app-capable" content="yes">	

<!-- Tell the browser to be responsive to screen width -->
<link rel="icon" type="image/x-icon" href="<?php echo $favicon;?>">
<!-- ================================================= -->
<!-- Bootstrap  -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/bootstrap/dist/css/bootstrap.min.css">
<!-- ================================================= -->
<!-- Font Awesome -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/font-awesome/css/font-awesome.min.css">
<!-- ================================================= -->
<!-- Ionicons -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/Ionicons/css/ionicons.min.css">
<!-- ================================================= -->
<!-- AdminLTE Skins -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/dist/css/skins/_all-skins.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/dist/css/AdminLTE.min.css">
<!-- ================================================= -->
<!-- Morris chart -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/morris.js/morris.css">
<!-- ================================================= -->
<!-- jvectormap -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/jvectormap/jquery-jvectormap.css">
<!-- ================================================= -->
<!-- Date Picker -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- ================================================= -->
<!-- Daterange picker -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<!-- ================================================= -->
<!-- bootstrap wysihtml5 - text editor -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<!-- ================================================= -->
<!-- Theme style -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/plugins/iCheck/all.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/select2/dist/css/select2.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/toastr/toastr.min.css">
<!-- Sweetalert style -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/sweet-alert/dist/sweetalert.css">
<!-- Kendo style -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/kendo/kendo.common.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/kendo/kendo.default.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/Trumbowyg/dist/ui/trumbowyg.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/clockpicker/dist/bootstrap-clockpicker.min.css">
<!-- animate style -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/animate.css">
<!-- nizar style -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/nizar.css">

<!-- ================================================= -->
<!-- Style hris -->
<!-- ================================================= -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_custom.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_hris.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_ihris.css">

<?php if($this->router->fetch_class() =='chat'){?>
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_hris_chat.css">
<?php } ?>

<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/switch.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_hris_custom.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<link rel="stylesheet" href="<?php echo base_url();?>/skin/assets/vendor/libs/bootstrap-tagsinput/bootstrap-tagsinput.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.11.3/af-2.3.7/b-2.0.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.0/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.3/sr-1.0.0/datatables.min.css"/>

<!-- <link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> -->

<?php if($this->router->fetch_class() =='roles') { ?>
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/kendo/kendo.common.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/kendo/kendo.default.min.css">
<?php } ?>

<?php if($theme[0]->form_design=='modern_form'):?>
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_modern_form.css">
<?php elseif($theme[0]->form_design=='rounded_form'):?>
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_rounded_form.css">
<?php elseif($theme[0]->form_design=='default_square_form'):?>
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_default_square_form.css">
<?php elseif($theme[0]->form_design=='medium_square_form'):?>
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/css/hris/xin_medium_square_form.css">
<?php endif;?>

<!-- <?php if($this->router->fetch_class() =='goal_tracking' || $this->router->fetch_method() =='task_details' || $this->router->fetch_class() =='project' || $this->router->fetch_class() =='quoted_projects' || $this->router->fetch_method() =='project_details'){?> -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/ion.rangeSlider/css/ion.rangeSlider.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/vendor/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css">
<!-- <?php } ?> -->

<!-- <?php if($this->router->fetch_class() =='calendar' || $this->router->fetch_class() =='dashboard' || $this->router->fetch_method() =='timecalendar' || $this->router->fetch_method() =='projects_calendar' || $this->router->fetch_method() =='tasks_calendar' || $this->router->fetch_method() =='quote_calendar' || $this->router->fetch_method() =='invoice_calendar' || $this->router->fetch_method() =='projects_dashboard' || $this->router->fetch_method() =='accounting_dashboard'){?> -->
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/fullcalendar/dist/fullcalendar.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>skin/assets/theme/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
<!-- <?php } ?> -->


</head>
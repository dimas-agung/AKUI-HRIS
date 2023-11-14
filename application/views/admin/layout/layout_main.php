<?php
$session = $this->session->userdata('username');
$system = $this->Core_model->read_setting_info(1);
$company_info = $this->Core_model->read_company_setting_info(1);
$layout = $this->Core_model->system_layout();
$user_info = $this->Core_model->read_user_info($session['user_id']);
//material-design
$theme = $this->Core_model->read_theme_info(1);
// set layout / fixed or static
if($user_info[0]->fixed_header=='fixed_layout_hris') {
	$fixed_header = 'fixed';
} else {
	$fixed_header = '';
}
if($user_info[0]->boxed_wrapper=='boxed_layout_hris') {
	$boxed_wrapper = 'layout-boxed';
} else {
	$boxed_wrapper = '';
}
if($user_info[0]->compact_sidebar=='sidebar_layout_hris') {
	$compact_sidebar = 'sidebar-collapse';
} else {
	$compact_sidebar = '';
}
/*
if($this->router->fetch_class() =='chat'){
	$chat_app = 'chat-application';
} else {
	$chat_app = '';
}*/
$role_user = $this->Core_model->read_user_role_info($user_info[0]->user_role_id);
if(!is_null($role_user)){
	$role_resources_ids = explode(',',$role_user[0]->role_resources);
} else {
	$role_resources_ids = explode(',',0);	
}
?>
<?php $this->load->view('admin/components/htmlheader');?>



<body  class="hris-layout hold-transition sidebar-mini skin-green <?php echo $fixed_header;?> <?php echo $boxed_wrapper;?> <?php echo $compact_sidebar;?>">

  
<style type="text/css">
#hrload-img {
    display: none;
    z-index: 87896969;
    float: right;
    margin-right: 25px;
    margin-top: 0px;
}
</style>
    <div class="wrapper"> 
          <?php $this->load->view('admin/components/header');?>  
          
          <aside class="main-sidebar">       
            <?php $this->load->view('admin/components/left_menu');?>
          </aside>
      
          <div class="content-wrapper">

              <?php if($this->router->fetch_class() =='dashboard' || $this->router->fetch_class() =='chat' || $this->router->fetch_class() =='profile' ){?>
              <div id="header_wrapper" class="header-lg overlay ecom-header">
                <div class="container"></div>
              </div>
              <?php } ?>
             
        
              <?php if($this->router->fetch_class() !='dashboard' && $this->router->fetch_class() !='chat' && $this->router->fetch_class() !='profile'){?>
                  
                  <section class="<?php echo $theme[0]->page_header;?> content-header">
                    <h1>
                      
                      <?php if(isset($icon)) echo $icon; ?> <?php echo strtoupper($breadcrumbs);?> 
                      
                      <div class="row breadcrumbs-hr-top">
                        <div class="breadcrumb-wrapper">
                          <ol class="breadcrumb">                                
                              <li class="breadcrumb-item active">
                                <?php if(isset($desc)) { ?>
                                   <?php echo $desc;?>
                                <?php } else  { ?>
                                   <?php echo $breadcrumbs;?>
                                <?php } ?>
                              </li>
                          </ol>
                        </div>
                      </div>
                      
                    </h1>
                    <img id="hrload-img" src="<?php echo base_url()?>skin/img/loading.gif" style="">
              	    <style type="text/css">
                        #hrload-img {
                            display: none;
                            z-index: 87896969;
                            float: right;
                            margin-right: 25px;
                            margin-top: -32px;
                        }
                    </style>               
                  </section>
              <?php } ?> 

              <!-- Main content -->
              <section class="content">
                <!-- Small boxes (Stat box) -->            
                <!-- /.row -->
                <!-- Main row -->          
          	    <?php echo $subview;?>
                <!-- /.row (main row) -->
              </section>
              <!-- /.content -->
          </div>

          <!-- /.content-wrapper -->
          <?php $this->load->view('admin/components/footer');?>
         
          <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
          <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    <!-- Layout footer -->
    <?php $this->load->view('admin/components/htmlfooter');?>
    <!-- / Layout footer -->
</body>
</html>
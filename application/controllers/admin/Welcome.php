<?php
 /**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the PortalHR License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.karyasoftware.com
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to hris@karyasoftware.com so we can send you a copy immediately.
 *
 * @author   Nizar Basyrewan
 * @author-email  hris@karyasoftware.com
 * @copyright  Copyright © karyasoftware.com. All Rights Reserved
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		 $this->load->model('Employees_model');
		 $this->load->model('Core_model');
	}
	
	public function index()
	{		
		$data['title'] = $this->Core_model->site_title().' | Log in';
		$theme = $this->Core_model->read_theme_info(1);
		if($theme[0]->login_page_options == 'login_page_1'):
			$this->load->view('admin/auth/login-1', $data);
		elseif($theme[0]->login_page_options == 'login_page_2'):
			$this->load->view('admin/auth/login-2', $data);
		elseif($theme[0]->login_page_options == 'login_page_3'):
			$this->load->view('admin/auth/login-3', $data);
		elseif($theme[0]->login_page_options == 'login_page_4'):
			$this->load->view('admin/auth/login-4', $data);
		elseif($theme[0]->login_page_options == 'login_page_5'):
			$this->load->view('admin/auth/login-5', $data);				
		else:
			$this->load->view('admin/auth/login-1', $data);	
		endif;
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

	// get payslip list> reports
	public function get_payslip_list($cid,$re_date) {
	  		
		$sql = 'SELECT * from xin_salary_payslips_month where salary_month = ? and company_id = ? ORDER BY doj DESC';
		$binds = array($re_date,$cid);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	  
	}
	// get training list> reports
	public function get_training_list($cid,$sdate,$edate) {
		
		$sql = 'SELECT * from `xin_training` where company_id = ? and start_date >= ? and finish_date <= ?';
		$binds = array($cid,$sdate,$edate);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	// get leave list> reports
	public function get_leave_application_list() {
		
		$sql = 'SELECT * from `xin_leave_applications` group by employee_id';
		$query = $this->db->query($sql);
		return $query;
	}
	// get filter leave list> reports
	public function get_leave_application_filter_list($sd,$ed,$user_id,$company_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where company_id = ? and employee_id = ? and from_date >= ? and to_date <= ? group by employee_id';
		$binds = array($company_id,$user_id,$sd,$ed);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// get pending leave list> reports
	public function get_pending_leave_application_list($employee_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,1);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// get approved leave list> reports
	public function get_approved_leave_application_list($employee_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,2);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// get upcoming leave list> reports
	public function get_upcoming_leave_application_list($employee_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,4);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// get rejected leave list> reports
	public function get_rejected_leave_application_list($employee_id) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,3);
		$query = $this->db->query($sql, $binds);
		return $query->num_rows();
	}
	// get only pending leave list> reports
	public function get_pending_leave_list($employee_id,$status) {
		
		$sql = 'SELECT * from `xin_leave_applications` where employee_id = ? and status = ?';
		$binds = array($employee_id,$status);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get project list> reports
	public function get_project_list($projId,$projStatus) {
		
		if($projId==0 && $projStatus=='all') {
			return $query = $this->db->query("SELECT * FROM `xin_projects`");
		} else if($projId==0 && $projStatus!='all') {
			$sql = 'SELECT * from `xin_projects` where status = ?';
			$binds = array($projStatus);
			$query = $this->db->query($sql, $binds);
			return $query;
		} else if($projId!=0 && $projStatus=='all') {
			$sql = 'SELECT * from `xin_projects` where project_id = ?';
			$binds = array($projId);
			$query = $this->db->query($sql, $binds);
			return $query;
		} else if($projId!=0 && $projStatus!='all') {
			$sql = 'SELECT * from `xin_projects` where project_id = ? and status = ?';
			$binds = array($projId,$projStatus);
			$query = $this->db->query($sql, $binds);
			return $query;
		}
	}
	// get employee projects
	public function get_employee_projectsx($id) {
	
		$sql = "SELECT * FROM `xin_projects` where assigned_to like '%$id,%' or assigned_to like '%,$id%' or assigned_to = '$id'";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	
	// get task list> reports
	public function get_task_list($taskId,$taskStatus) {
		
		  if($taskId==0 && $taskStatus==4) {
			  return $query = $this->db->query("SELECT * FROM xin_tasks");
		  } else if($taskId==0 && $taskStatus!=4) {
			  $sql = 'SELECT * from xin_tasks where task_status = ?';
			  $binds = array($taskStatus);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($taskId!=0 && $taskStatus==4) {
			  $sql = 'SELECT * from xin_tasks where task_id = ?';
			  $binds = array($taskId);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($taskId!=0 && $taskStatus!=4) {
		  	  $sql = 'SELECT * from xin_tasks where task_id = ? and task_status = ?';
			  $binds = array($taskId,$taskStatus);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  }
	}
	
	// get roles list> reports
	public function get_roles_employees($role_id) {
		  if($role_id==0) {
			  return $query = $this->db->query("SELECT * FROM xin_employees");
		  } else {
			  $sql = 'SELECT * from xin_employees where user_role_id = ?';
			  $binds = array($role_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  }
	}
	
	// get employees list> reports
	public function get_employees_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM xin_employees");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from xin_employees where company_id = ?';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from xin_employees where company_id = ? and department_id = ?';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from xin_employees where company_id = ? and department_id = ? and designation_id = ?';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM xin_employees");
		  }
	}

	// get employees list> reports
	public function get_employees_active_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_karyawan_aktif ORDER BY date_of_joining DESC");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? ORDER BY date_of_joining DESC';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? and department_id = ? ORDER BY date_of_joining DESC';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? and department_id = ? and designation_id = ? ORDER BY date_of_joining DESC';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_karyawan_aktif ORDER BY date_of_joining DESC");
		  }
	}

	// get employees list> reports
	public function get_employees_pelatihan_sudah_reports($training_type_id) {
		  
		  if($training_type_id==0) {
		 	 
		 	 return $query = $this->db->query("SELECT * FROM xin_training_employee ORDER BY start_date DESC");
		  
		  } else if($training_type_id!=0 ) {
		 	  
		 	  $sql = 'SELECT * from xin_training_employee where training_type_id = ? ORDER BY start_date DESC';
			  $binds = array($training_type_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;	  
		
		  } else {
			  return $query = $this->db->query("SELECT * FROM xin_training_employee ORDER BY start_date DESC");
		  }
	}

	public function get_employees_pelatihan_belum_reports($training_type_id) {
		$this->load->model('Training_model');

		// get trainings
		$trainings = $this->db
			->select(array(
				'training_id',
				'train.start_date',
				'train.finish_date',
				'employee_id',
				'cat.type category',
				'types.type',
				'trainer.first_name',
				'trainer.last_name',
			))
			->where('train.training_status', $this->Training_model->STATUS_YET)
			->join('xin_trainers trainer', 'trainer.trainer_id = train.trainer_id', 'left')
			->join('xin_training_types types', 'types.training_type_id = train.training_type_id', 'left')
			->join('xin_training_kategori cat', 'cat.training_type_id = types.kategori', 'left');

		if ($training_type_id != 0) {
			$trainings = $trainings->where('train.training_type_id', $training_type_id);
		}

		$emp_tra = array();
		foreach ($trainings->get('xin_training train')->result() as $t) {
			$t->start_date = date("d-m-Y", strtotime($t->start_date));
			$t->finish_date = date("d-m-Y", strtotime($t->finish_date));

			$employees = $this->db
				->select('full_name, designation_name, employee_id')
				->where_in('user_id', explode(',', $t->employee_id))
				->get('view_employee')
				->result();

			$emp_tra[$t->training_id] = array_merge(
				array(
					'training' => $t,
				),
				array(
					'employees' => $employees,
				)
			);
		}

		return $emp_tra;
		  
		  if($training_type_id==0) {
		 	 
		 	 return $query = $this->db->query("SELECT * FROM xin_training_employee ORDER BY start_date DESC");
		  
		  } else if($training_type_id!=0 ) {
		 	  
		 	  $sql = 'SELECT * from xin_training_employee where training_type_id = ? ORDER BY start_date DESC';
			  $binds = array($training_type_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;	  
		
		  } else {
			  return $query = $this->db->query("SELECT * FROM xin_training_employee ORDER BY start_date DESC");
		  }
	}

	// get employees list> reports
	public function get_employees_active_not_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_karyawan_aktif_tidak ORDER BY date_of_joining DESC");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif_tidak where company_id = ? ORDER BY date_of_joining DESC';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif_tidak where company_id = ? and department_id = ? ORDER BY date_of_joining DESC';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif_tidak where company_id = ? and department_id = ? and designation_id = ? ORDER BY date_of_joining DESC';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_karyawan_aktif_tidak");
		  }
	}

	public function get_karyawan_bulanan_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_karyawan_aktif where wages_type = 1 ");
		  
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? and wages_type = ?';
			  $binds = array($company_id, 1);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? and department_id = ? and wages_type = ? ';
			  $binds = array($company_id,$department_id, 1);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? and department_id = ? and designation_id = ? and wages_type = ? ';
			  $binds = array($company_id,$department_id,$designation_id, 1);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		 
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_karyawan_aktif WHERE  wages_type = 1 ");
		  }
	}

	public function get_karyawan_harian_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_karyawan_aktif where wages_type = 2 ");
		  
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? and wages_type = ?';
			  $binds = array($company_id, 2);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? and department_id = ? and wages_type = ? ';
			  $binds = array($company_id,$department_id, 2);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_karyawan_aktif where company_id = ? and department_id = ? and designation_id = ? and wages_type = ? ';
			  $binds = array($company_id,$department_id,$designation_id, 2);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		 
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_karyawan_aktif WHERE  wages_type = 2 ");
		  }
	}

	public function get_karyawan_borongan_reports($company_id,$workstation_id,$designation_id) {

		  if($company_id==0 && $workstation_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_karyawan_workstation_aktif where wages_type = 3 ORDER BY date_of_joining DESC");
		  
		  } else if($company_id!=0 && $workstation_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_workstation_aktif where company_id = ? and wages_type = ? ORDER BY date_of_joining DESC';
			  $binds = array($company_id, 3);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  
		  } else if($company_id!=0 && $workstation_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_karyawan_workstation_aktif where company_id = ? and workstation_id = ? and wages_type = ? ORDER BY date_of_joining DESC';
			  $binds = array($company_id,$workstation_id, 3);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  
		  } else if($company_id!=0 && $workstation_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_karyawan_workstation_aktif where company_id = ? and workstation_id = ? and designation_id = ? and wages_type = ?  ORDER BY date_of_joining DESC';
			  $binds = array($company_id,$workstation_id,$designation_id, 3);
			  $query = $this->db->query($sql, $binds);

			  // echo "<pre>";
			  // print_r($this->db->last_query());
			  // echo "</pre>";
			  // die();

			  return $query;
		 
		  } else {

			  return $query = $this->db->query("SELECT * FROM view_karyawan_workstation_aktif WHERE  wages_type = 3 ORDER BY date_of_joining DESC ");
		  }
	}

	// get employees list> reports
	public function get_employees_resign_reports($company_id,$month_year) {
		
		if($company_id==0 && $month_year==0 ) {
		    
		    return $query = $this->db->query("SELECT * FROM view_karyawan_resign");
		
		} else if($company_id!=0 && $month_year==0 ) {
		    $sql = 'SELECT * from view_karyawan_resign where company_id = ?';
		    $binds = array($company_id);
		    $query = $this->db->query($sql, $binds);
		    return $query;

		} else if($company_id==0 && $month_year!=0 ) {
		    $sql = 'SELECT * from view_karyawan_resign where bulan = ?';
		    $binds = array($month_year);
		    $query = $this->db->query($sql, $binds);
			return $query;
			
		
		} else if($company_id!=0 && $month_year!=0 ) {
		    $sql = 'SELECT * from view_karyawan_resign where company_id = ? and bulan = ?';
		    $binds = array($company_id,$month_year);
		    $query = $this->db->query($sql, $binds);
			return $query;

		
		} else {
		    return $query = $this->db->query("SELECT * FROM view_karyawan_resign");
		}
	}

	public function get_employees_exit_reports($company_id,$month_year) {
		
		if($company_id==0 && $month_year==0 ) {
		    
		    return $query = $this->db->query("SELECT * FROM view_karyawan_exit");
		
		} else if($company_id!=0 && $month_year==0 ) {
		    $sql = 'SELECT * from view_karyawan_exit where company_id = ?';
		    $binds = array($company_id);
		    $query = $this->db->query($sql, $binds);
		    return $query;

		} else if($company_id==0 && $month_year!=0 ) {
		    $sql = 'SELECT * from view_karyawan_exit where bulan = ?';
		    $binds = array($month_year);
		    $query = $this->db->query($sql, $binds);
			return $query;
			
		
		} else if($company_id!=0 && $month_year!=0 ) {
		    $sql = 'SELECT * from view_karyawan_exit where company_id = ? and bulan = ?';
		    $binds = array($company_id,$month_year);
		    $query = $this->db->query($sql, $binds);
			return $query;

		
		} else {
		    return $query = $this->db->query("SELECT * FROM view_karyawan_exit");
		}
	}

	public function get_employees_contract_end_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_report_karyawan_kontrak_habis");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis where company_id = ?';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis where company_id = ? and department_id = ?';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis where company_id = ? and department_id = ? and designation_id = ?';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_report_karyawan_kontrak_habis");
		  }
	}
	public function get_employees_contract_end_will_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_report_karyawan_kontrak_habis_akan");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis_akan where company_id = ?';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis_akan where company_id = ? and department_id = ?';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis_akan where company_id = ? and department_id = ? and designation_id = ?';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_report_karyawan_kontrak_habis_akan");
		  }
	}

	public function get_employees_contract_do_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_report_karyawan_kontrak_habis_belum");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis_belum where company_id = ?';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis_belum where company_id = ? and department_id = ?';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_habis_belum where company_id = ? and department_id = ? and designation_id = ?';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_report_karyawan_kontrak_habis_belum");
		  }
	}

	public function get_employees_contract_not_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_hris_karyawan_status_kontrak_belum_dibuat");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_hris_karyawan_status_kontrak_belum_dibuat where company_id = ?';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_hris_karyawan_status_kontrak_belum_dibuat where company_id = ? and department_id = ?';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_hris_karyawan_status_kontrak_belum_dibuat where company_id = ? and department_id = ? and designation_id = ?';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_hris_karyawan_status_kontrak_belum_dibuat");
		  }
	}

	public function get_employees_contract_not_activate_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_report_karyawan_kontrak_belum_ada");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_belum_ada where company_id = ?';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_belum_ada where company_id = ? and department_id = ?';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_report_karyawan_kontrak_belum_ada where company_id = ? and department_id = ? and designation_id = ?';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_report_karyawan_kontrak_belum_ada");
		  }
	}

	public function get_employees_permanent_reports($company_id,$department_id,$designation_id) {
		  if($company_id==0 && $department_id==0 && $designation_id==0) {
		 	 return $query = $this->db->query("SELECT * FROM view_report_karyawan_permanent");
		  } else if($company_id!=0 && $department_id==0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_permanent where company_id = ?';
			  $binds = array($company_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id==0) {
		 	  $sql = 'SELECT * from view_report_karyawan_permanent where company_id = ? and department_id = ?';
			  $binds = array($company_id,$department_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else if($company_id!=0 && $department_id!=0 && $designation_id!=0) {
		 	  $sql = 'SELECT * from view_report_karyawan_permanent where company_id = ? and department_id = ? and designation_id = ?';
			  $binds = array($company_id,$department_id,$designation_id);
			  $query = $this->db->query($sql, $binds);
			  return $query;
		  } else {
			  return $query = $this->db->query("SELECT * FROM view_report_karyawan_permanent");
		  }
	}
	// =====================================================================================================
	// GA
	// ======================================================================================================
	public function get_perjanjian_reports($jenis_id,$start_date,$end_date) {
		  
		  if($jenis_id == 0 && $start_date==0 && $end_date==0) {

		  	 return $query = $this->db->query("SELECT * FROM view_report_perjanjian ORDER BY start_date DESC");

		   } else if($jenis_id == 0 && $start_date !=0 && $end_date !=0) {

		  	$sql = 'SELECT * from view_report_perjanjian where start_date >= ? AND end_date <= ? ORDER BY start_date DESC';
			$binds = array($start_date,$end_date);
			$query = $this->db->query($sql, $binds);

			// echo "<pre>";
			// print_r($this->db->last_query());
			// echo "</pre>";
			// die();

			return $query;
		  
		  } else if($jenis_id !=0 && $start_date !=0 && $end_date !=0) {
		 	 
		 	  $sql = 'SELECT * from view_report_perjanjian where perjanjian_type_id = ? AND start_date >= ? AND end_date <= ? ORDER BY start_date DESC';
			  $binds = array($jenis_id,$start_date,$end_date);
			  $query = $this->db->query($sql, $binds);

			  // echo "<pre>";
			  // print_r($this->db->last_query());
			  // echo "</pre>";
			  // die();

			  return $query;	
		  
		  } else {

		  	 return $query = $this->db->query("SELECT * FROM view_report_perjanjian ORDER BY start_date DESC");
		  
		  }
	}

	public function get_perizinan_reports($jenis_id,$start_date,$end_date) {
		  
		  if($jenis_id == 0 && $start_date==0 && $end_date==0) {

		  	 return $query = $this->db->query("SELECT * FROM view_report_perizinan ORDER BY start_date DESC");

		   } else if($jenis_id == 0 && $start_date !=0 && $end_date !=0) {

		  	$sql = 'SELECT * from view_report_perizinan where start_date >= ? AND end_date <= ? ORDER BY start_date DESC';
			$binds = array($start_date,$end_date);
			$query = $this->db->query($sql, $binds);

			// echo "<pre>";
			// print_r($this->db->last_query());
			// echo "</pre>";
			// die();

			return $query;
		  
		  } else if($jenis_id !=0 && $start_date !=0 && $end_date !=0) {
		 	 
		 	  $sql = 'SELECT * from view_report_perizinan where perizinan_type_id = ? AND start_date >= ? AND end_date <= ? ORDER BY start_date DESC';
			  $binds = array($jenis_id,$start_date,$end_date);
			  $query = $this->db->query($sql, $binds);

			  // echo "<pre>";
			  // print_r($this->db->last_query());
			  // echo "</pre>";
			  // die();

			  return $query;	
		  
		  } else {

		  	 return $query = $this->db->query("SELECT * FROM view_report_perizinan ORDER BY start_date DESC");
		  
		  }
	}
	
}
?>
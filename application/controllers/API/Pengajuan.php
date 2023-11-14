<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pengajuan extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		//load the models
		$this->load->model("Employees_model");
		$this->load->model("Core_model");
		$this->load->model("Department_model");
		$this->load->model("Designation_model");
		$this->load->model("Roles_model");
		$this->load->model("Location_model");
		$this->load->model("Company_model");
		$this->load->model("Timesheet_model");
		$this->load->model("Timesheet_model");
		$this->load->library("pagination");
		$this->load->library('Pdf');
		$this->load->helper('string');
	}

	/*Function to set JSON output*/
	public function output($Return = array())
	{
		/*Set response header*/
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		/*Final JSON response*/
		exit(json_encode($Return));
	}

	public function index()
	{
		$employeeAll    = $this->Employees_model->get_employees()->result();
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($employeeAll);
	}
	public function tipe_izin()
	{
        $sql = "SELECT izin_type_id as id,type_name as tipe FROM xin_izin_type";

        $data = $this->db->query($sql)->result();
       
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
	}
	public function tipe_cuti()
	{
        $sql = "SELECT leave_type_id as id,type_name as tipe, days_per_year as jumlah_hari FROM xin_leave_type";
        $data = $this->db->query($sql)->result();
       
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
	}
	public function tipe_sakit()
	{
        $sql = "SELECT sick_type_id as id,type_name as tipe FROM xin_sick_type";
        $data = $this->db->query($sql)->result();
       
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
	}
	public function DataBawahanTidakLangsung()
	{
        $employee_id = $this->input->get('id');
        $sql = "SELECT * FROM xin_employees WHERE superior_reports_to ='" . $employee_id . "' ";
        $data = $this->db->query($sql)->result();
       
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
	}

	public function CheckEmployeeExist()
	{
        $employee_id = $this->input->get('id');
        $sql = "SELECT employee_id,first_name,
		last_name,
		(CASE
			WHEN grade_type = 'Grade 1' and grade_type != '' then 0
			ELSE 1
		END) as approval_access,
		reports_to as atasan_langsung_id, superior_reports_to as superior_atasan_langsung_id FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $data = $this->db->query($sql)->result();
       
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
	}
	public function savePengajuanIzin()
	{
        $employee_id = $this->input->post('employee_id');
		$sql = "SELECT * FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $employee = $this->db->query($sql)->row();
		$employee_id = $employee->user_id;
		$company_id = $employee->company_id;
		$department_id = $employee->department_id;
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $izin_type_id = $this->input->post('izin_type_id');
        $reason = $this->input->post('reason');
        $remarks = $this->input->post('reason');
		$data = array(
			'employee_id'      => $employee_id,
			'company_id'       => $company_id,
			'department_id'       => $department_id,
			'izin_type_id'    => $izin_type_id,
			'from_date'        => $from_date,
			'to_date'          => $to_date,
			'applied_on'       => date('Y-m-d h:i'),
			'reason'           => $reason,		
			'remarks'           => $reason,		
			'status'           => '1',
			'is_notify'        => '1',
			'is_half_day'      => '0',
			'day'      => '0',
			'created_at'       => date('Y-m-d h:i')
		);

		$result = $this->db->insert('xin_izin_applications', $data);
		 
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result);
	}
	public function savePengajuanSakit()
	{
        $employee_id = $this->input->post('employee_id');
		$sql = "SELECT * FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $employee = $this->db->query($sql)->row();
		$employee_id = $employee->user_id;
		$company_id = $employee->company_id;
		$department_id = $employee->department_id;
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $sick_type_id = $this->input->post('sick_type_id');
        $reason = $this->input->post('reason');
        $remarks = $this->input->post('reason');
       
		$data = array(
			'employee_id'      => $employee_id,
			'company_id'       => $company_id,
			'department_id'       => $department_id,
			'sick_type_id'    => $sick_type_id,
			'from_date'        => $from_date,
			'to_date'          => $to_date,
			'applied_on'       => date('Y-m-d h:i'),
			'reason'           => $reason,		
			'remarks'           => $reason,		
			'status'           => '1',
			'is_notify'        => '1',
			'is_half_day'      => '0',
			'created_at'       => date('Y-m-d h:i')
		);

		$result = $this->db->insert('xin_sick_applications', $data);
		 
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result);
       
	}
	public function savePengajuanCuti()
	{
        $employee_id = $this->input->post('employee_id');
		$sql = "SELECT * FROM xin_employees WHERE employee_id ='" . $employee_id . "' ";
		// echo $sql;return;
        $employee = $this->db->query($sql)->row();
		$employee_id = $employee->user_id;
		$company_id = $employee->company_id;
		$department_id = $employee->department_id;
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $leave_type_id = $this->input->post('leave_type_id');
        $reason = $this->input->post('reason');
        $remarks = $this->input->post('reason');

		$data = array(
			'employee_id'      => $employee_id,
			'company_id'       => $company_id,
			'department_id'       => $department_id,
			'leave_type_id'    => $leave_type_id,
			'from_date'        => $from_date,
			'to_date'          => $to_date,
			'applied_on'       => date('Y-m-d h:i'),
			'reason'           => $reason,		
			'remarks'           => $reason,		
			'status'           => '1',
			'is_notify'        => '1',
			'is_half_day'      => '0',
			'created_at'       => date('Y-m-d h:i')
		);

		$result = $this->db->insert('xin_leave_applications', $data);
       
       
        header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($result);
	}

}
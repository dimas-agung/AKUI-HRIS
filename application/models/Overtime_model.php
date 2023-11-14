<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Overtime_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	// get overtime
	public function get_overtime() {
		return $this->db->get("view_employee_overtime");		
	}

	public function get_overtime_bulanan() {
		return $this->db->get("view_employee_overtime_bulanan");		
	}

	public function get_overtime_harian() {
		return $this->db->get("view_employee_overtime_harian");		
	}
	
	// get overtime type
	public function get_overtime_type()
	{
	  return $this->db->get("xin_overtime_types");
	}
	
	// all overtime_types
	public function all_overtime_types() {
	  $query = $this->db->query("SELECT * from xin_overtime_types ");
  	  return $query->result();
	}
	 
	public function read_overtime_information($id) {
	
		$sql = 'SELECT * FROM xin_overtime WHERE overtime_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		// echo "<pre>";
		// print_r( $this->db->last_query() );
		// echo "</pre>";
		// die();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	// get overtime type by id
	public function read_overtime_type_information($id) {
	
		$sql = 'SELECT * FROM xin_overtime_types WHERE overtime_type_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_overtime', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}

	public function add_salary_overtime($data){
		$this->db->insert('xin_salary_overtime', $data);
		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id();
		} else {
			return false;
		}
	}	
	
	// Function to add record in table
	public function add_type($data){
		$this->db->insert('xin_overtime_types', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('overtime_id', $id);
		$this->db->delete('xin_overtime');
		
	}

	public function delete_record_salary_overtime($uid,$overtime_date){
		$this->db->where('employee_id', $uid);
		$this->db->where('overtime_date', $overtime_date);			
		$this->db->delete('xin_salary_overtime');
		
	}
	
	// Function to Delete selected record from table
	public function delete_type_record($id){
		$this->db->where('overtime_type_id', $id);
		$this->db->delete('xin_overtime_types');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('overtime_id', $id);
		if( $this->db->update('xin_overtime',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_status($data, $id){
		$this->db->where('overtime_id', $id);
		if( $this->db->update('xin_overtime',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	
	// Function to update record in table
	public function update_type_record($data, $id){
		$this->db->where('overtime_type_id', $id);
		if( $this->db->update('xin_overtime_types',$data)) {
			return true;
		} else {
			return false;
		}		
	}
	// get company projects
	public function get_company_overtime($company_id) {
	
		$sql = 'SELECT * FROM xin_overtime WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	// get employee overtime
	public function get_employee_overtime($id) {
	
		$sql = "SELECT * FROM `xin_overtime` where employee_id like '%$id,%' or employee_id like '%,$id%' or employee_id = '$id'";
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}

	// get overtime request>admin>all
	public function get_overtime_count($employee_id,$pay_date) {
		
		$sql = 'SELECT * FROM `xin_overtime` where employee_id = ? and overtime_status = ? and attendance_date_m = ?';
		$binds = array($employee_id,2,$pay_date);
		$query = $this->db->query($sql, $binds);
		$result = $query->result();
		return $result;
	}
}
?>
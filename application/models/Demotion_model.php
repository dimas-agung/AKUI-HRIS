<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demotion_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_demotions() {
	  return $this->db->get("xin_employee_demotions");
	}
	
	public function get_employee_demotions($id) {
	 	
		$sql = 'SELECT * FROM xin_employee_demotions WHERE employee_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		return $query;
	}
	// get company demotions
	public function get_company_demotions($company_id) {
	
		$sql = 'SELECT * FROM xin_employee_demotions WHERE company_id = ?';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
	 
	 public function read_demotion_information($id) {
	
		$sql = 'SELECT * FROM xin_employee_demotions WHERE demotion_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	
	// Function to add record in table
	public function add($data){
		$this->db->insert('xin_employee_demotions', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('demotion_id', $id);
		$this->db->delete('xin_employee_demotions');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('demotion_id', $id);
		if( $this->db->update('xin_employee_demotions',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>
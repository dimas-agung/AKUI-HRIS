<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

class skala_upah_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_skala_upah()
	{
	  return $this->db->get("xin_workstation_skala_upah");
	}
	 
	 public function read_skala_upah_information($id) {
	
		$sql = 'SELECT * FROM xin_workstation_skala_upah WHERE skala_upah_id = ?';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return null;
		}
	}

	public function read_skala_upah_workstation_information($id) {
	
		$sql = 'SELECT * FROM view_skala_upah_workstation WHERE skala_upah_id = ?';
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
		$this->db->insert('xin_workstation_skala_upah', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('skala_upah_id', $id);
		$this->db->delete('xin_workstation_skala_upah');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('skala_upah_id', $id);
		if( $this->db->update('xin_workstation_skala_upah',$data)) {
			return true;
		} else {
			return false;
		}		
	}

	
	// get all skala_upah
	public function all_skala_upah()
	{
	  $query = $this->db->query("SELECT * from xin_workstation_skala_upah  ORDER BY skala_upah_name ASC");
  	  return $query->result();
	}
	
	
	public function ajax_skala_upah_departments_info($id,$yd) 
	{	
		$sql = 'SELECT * FROM xin_workstation_skala_upah WHERE company_id = ? AND workstation_id = ?  ORDER BY skala_upah_name ASC';
		$binds = array($id,$yd);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}		
	}

	// get department > skala_upah
	public function ajax_is_skala_upah_information($id) {
	
		$sql = 'SELECT * FROM xin_workstation_skala_upah WHERE workstation_id = ? ORDER BY skala_upah_name ASC';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	// get company > skala_upah
	public function ajax_company_skala_upah_info($id) {
	
		$sql = 'SELECT * FROM xin_workstation_skala_upah WHERE company_id = ? ORDER BY skala_upah_name ASC';
		$binds = array($id);
		$query = $this->db->query($sql, $binds);
		
		// echo "<pre>";
		// print_r($this->db->last_query());
		// echo "</pre>";
		// die();

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}
	
	public function get_company_skala_upah($company_id) {
	
		$sql = 'SELECT * FROM xin_workstation_skala_upah WHERE company_id = ? ORDER BY skala_upah_name ASC';
		$binds = array($company_id);
		$query = $this->db->query($sql, $binds);
		return $query;
	}
}
?>
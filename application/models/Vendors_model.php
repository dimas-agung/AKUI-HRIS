<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendors_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
	public function get_vendors() {
	  return $this->db->get("xin_training_vendors");
	}
	
	// all vendors
	public function all_vendors() {
	  $query = $this->db->query("SELECT * from xin_training_vendors");
  	  return $query->result();
	}
	
	// get company vendors
	public function read_vendor_information($id) {
	
		$sql = 'SELECT * FROM xin_training_vendors WHERE vendor_id = ?';
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
		$this->db->insert('xin_training_vendors', $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	// Function to Delete selected record from table
	public function delete_record($id){
		$this->db->where('vendor_id', $id);
		$this->db->delete('xin_training_vendors');
		
	}
	
	// Function to update record in table
	public function update_record($data, $id){
		$this->db->where('vendor_id', $id);
		if( $this->db->update('xin_training_vendors',$data)) {
			return true;
		} else {
			return false;
		}		
	}
}
?>
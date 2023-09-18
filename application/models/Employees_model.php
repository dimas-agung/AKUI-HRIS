<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employees_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // get all employes
    public function get_employees()
    {
        $this->db->order_by("employee_id desc");
        $query = $this->db->get("xin_employees");
        return $query;
    }

    public function get_employees_all()
    {
        $query = $this->db->get("view_karyawan_all");
        return $query;
    }

    // get all employes
    public function get_employees_active()
    {
        $session = $this->session->userdata('username');

        $sql = "SELECT * FROM view_karyawan_aktif WHERE company_id in (" . $session['companies'] . ")  ORDER BY employee_id DESC";
        $binds = array();
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_employees_history()
    {
        $session = $this->session->userdata('username');

        $sql = "SELECT * FROM view_karyawan_history WHERE company_id in (" . $session['companies'] . ")  ORDER BY employee_id DESC";
        $binds = array();
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // =============================================================================
    // RESIGN
    // ===============================================================================
    public function get_employees_resign()
    {

        $session = $this->session->userdata('username');

        $sql = "SELECT * FROM view_karyawan_resign WHERE company_id in (" . $session['companies'] . ") ORDER BY exit_date DESC ";
        $binds = array();
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_employees_resign($cid)
    {

        $sql = 'SELECT * FROM view_karyawan_resign WHERE company_id = ? order by exit_date desc';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_location_employees_resign($cid, $lid)
    {

        $sql = 'SELECT * FROM view_karyawan_resign WHERE company_id = ? and location_id = ? order by exit_date desc';
        $binds = array($cid, $lid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_location_department_employees_resign($cid, $lid, $dep_id)
    {

        $sql = 'SELECT * FROM view_karyawan_resign WHERE company_id = ? and location_id = ? and department_id = ? order by exit_date desc';
        $binds = array($cid, $lid, $dep_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_location_department_designation_employees_resign($cid, $lid, $dep_id, $des_id)
    {

        $sql = 'SELECT * FROM view_karyawan_resign WHERE company_id = ? and location_id = ? and department_id = ? and designation_id = ? order by exit_date desc';
        $binds = array($cid, $lid, $dep_id, $des_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // ===============================================================================================================================

    public function get_employees_active_kontrak()
    {
        $query = $this->db->get("view_karyawan_aktif_kontrak");
        return $query;
    }
    public function get_employees_login()
    {

        $sql = 'SELECT * FROM view_karyawan_login ';
        $query = $this->db->query($sql);
        return $query;
    }
    // get all my team employes > not super admin
    public function get_employees_my_team($cid)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE reports_to = ? order by date_of_joining desc ';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get all employes > not super admin
    public function get_employees_for_other($cid)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? order by date_of_joining desc';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get all employes > not super admin
    public function get_employees_for_location($cid)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE location_id = ? order by date_of_joining desc';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get all employes|company>
    public function get_company_employees_flt($cid)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? order by date_of_joining desc';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }



    public function get_company_employees_flt_all($cid)
    {

        $sql = 'SELECT * FROM view_karyawan_all WHERE company_id = ? order by date_of_joining desc';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }


    // get all MY TEAM employes
    public function get_my_team_employees($reports_to)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE reports_to = ? order by date_of_joining desc';
        $binds = array($reports_to);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get all employes>company|location >
    public function get_company_location_employees_flt($cid, $lid)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? and location_id = ? order by date_of_joining desc';
        $binds = array($cid, $lid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_location_employees_flt_all($cid, $lid)
    {

        $sql = 'SELECT * FROM view_karyawan_all WHERE company_id = ? and location_id = ? order by date_of_joining desc';
        $binds = array($cid, $lid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get all employes>company|location|department >
    public function get_company_location_department_employees_flt($cid, $lid, $dep_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? and location_id = ? and department_id = ? order by date_of_joining desc';
        $binds = array($cid, $lid, $dep_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_location_department_employees_history($cid, $lid, $dep_id)
    {

        $sql = 'SELECT * FROM view_karyawan_all WHERE company_id = ? and location_id = ? and department_id = ? order by date_of_joining desc';
        $binds = array($cid, $lid, $dep_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_location_department_employees_flt_all($cid, $lid, $dep_id)
    {

        $sql = 'SELECT * FROM view_karyawan_all WHERE company_id = ? and location_id = ? and department_id = ? order by date_of_joining desc';
        $binds = array($cid, $lid, $dep_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get all employes>company|location|department|designation >
    public function get_company_location_department_designation_employees_flt($cid, $lid, $dep_id, $des_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? and location_id = ? and department_id = ? and designation_id = ? order by date_of_joining desc';
        $binds = array($cid, $lid, $dep_id, $des_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_location_department_designation_employees_flt_all($cid, $lid, $dep_id, $des_id)
    {

        $sql = 'SELECT * FROM view_karyawan_all WHERE company_id = ? and location_id = ? and department_id = ? and designation_id = ? order by date_of_joining desc';
        $binds = array($cid, $lid, $dep_id, $des_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get all employes >
    public function get_employees_payslip()
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE is_active = ? AND wages_type = 1 order by date_of_joining desc';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_employees_payslip_bulanan()
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE is_active = ? AND wages_type = 1 order by date_of_joining desc';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query;
    }


    public function get_employees_payslip_bulanan_lhat($bulan)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE salary_month = ? AND wages_type = 1 order by doj desc';
        $binds = array($bulan);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_employees_payslip_harian_lhat($bulan, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE start_date = ? and end_date = ? AND wages_type = 2 order by doj desc';
        $binds = array($bulan, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_employees_payslip_harian_reguler()
    {


        $sql = "SELECT * FROM view_employees_wages WHERE wages_type = 2 and is_active='1' and office_id ='R' ORDER BY first_name ASC";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employees_payslip_borongan_reguler()
    {


        $sql = "SELECT * FROM view_employees_wages WHERE wages_type = 3 and is_active='1' and office_id ='R' ORDER BY first_name ASC";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    public function get_employees_payslip_bulanan_reguler()
    {


        $sql = "SELECT * FROM view_employees_wages WHERE wages_type = 1 and is_active='1' and office_id ='R' ORDER BY first_name ASC";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employees_payslip_harian()
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE is_active = ? AND wages_type = 2 order by date_of_joining desc';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_employees_payslip_borongan()
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE is_active = ? AND wages_type = 3 order by date_of_joining desc';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // get employes
    public function get_attendance_employees()
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE is_active = ? order by date_of_joining desc';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    // get employes with location
    public function get_attendance_location_employees($location_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE location_id = ? and is_active = ? order by date_of_joining desc';
        $binds = array($location_id, 1);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function get_attendance_jenis_gaji_employees_reguler_recap_bulanan($pola_kerja, $company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = 1 and office_id = ? and company_id = ?  and is_active = ? order by date_of_joining desc';
        $binds = array($pola_kerja, $company_id, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_attendance_jenis_gaji_employees_reguler_recap_harian($pola_kerja, $company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = 2 and office_id = ? and company_id = ?  and is_active = ? order by date_of_joining desc';
        $binds = array($pola_kerja, $company_id, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_attendance_jenis_gaji_employees_reguler_recap_borongan($pola_kerja, $company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = 3 and office_id = ? and company_id = ?  and is_active = ? order by date_of_joining desc';
        $binds = array($pola_kerja, $company_id, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_attendance_jenis_gaji_employees_reguler_recap($jenis_gaji, $pola_kerja, $company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = ? and office_id = ? and company_id = ?  and is_active = ? order by date_of_joining desc';
        $binds = array($jenis_gaji, $pola_kerja, $company_id, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }


    public function get_lembur_jenis_gaji_employees_reguler_recap($jenis_gaji, $company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = ? and company_id = ?  and is_active = ? order by date_of_joining desc';
        $binds = array($jenis_gaji, $company_id, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }


    public function get_attendance_jenis_gaji_employees_reguler_load($jenis_gaji, $company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = ? and company_id = ? and office_id ="R" and is_active = ? order by date_of_joining desc';
        $binds = array($jenis_gaji, $company_id, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    // ================================================================================================================================
    // REKAP
    // ================================================================================================================================
    public function get_rekap_kehadiran_bulanan($company_id, $month_year, $pola_kerja)
    {

        $sql   = 'SELECT * FROM view_karyawan_aktif_rekap WHERE company_id = ? and wages_type = 1 and  month_year = ?  and is_active = ? and office_id = ? order by date_of_joining desc';
        $binds = array($company_id, $month_year, 1, $pola_kerja);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_rekap_kehadiran($company_id, $wages_type, $month_year, $pola_kerja)
    {

        $sql   = 'SELECT * FROM view_karyawan_aktif_rekap WHERE company_id = ? and wages_type = ? and  month_year = ?  and is_active = ? and office_id = ? order by date_of_joining desc';
        $binds = array($company_id, $wages_type, $month_year, 1, $pola_kerja);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_rekap_lembur($company_id, $wages_type, $month_year)
    {

        $sql   = 'SELECT * FROM view_karyawan_aktif_rekap_lembur WHERE company_id = ? and wages_type = ? and  month_year = ?  and is_active = ?  order by date_of_joining desc';
        $binds = array($company_id, $wages_type, $month_year, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    // ================================================================================================================================
    // REPORTS
    // ================================================================================================================================
    public function get_rekap_kehadiran_bulanan_reguler($company_id, $month_year)
    {

        $sql   = 'SELECT * FROM view_karyawan_aktif_rekap WHERE company_id = ? and wages_type = "1" and  month_year = ?  and is_active = "1" and office_id = "R" order by date_of_joining desc';
        $binds = array($company_id, $month_year);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_rekap_kehadiran_harian_reguler($company_id, $month_year)
    {

        $sql   = 'SELECT * FROM view_karyawan_aktif_rekap WHERE company_id = ? and wages_type = "2" and  month_year = ?  and is_active = "1" and office_id = "R" order by date_of_joining desc';
        $binds = array($company_id, $month_year);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_rekap_reguler($company_id, $wages_type, $month_year)
    {

        $sql   = 'SELECT * FROM view_karyawan_aktif_rekap WHERE company_id = ? and wages_type = ? and  month_year = ?  and is_active = ? and office_id ="R" order by date_of_joining desc';
        $binds = array($company_id, $wages_type, $month_year, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_attendance_jenis_gaji_employees_reguler($jenis_gaji)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = ? and office_id ="R" and is_active = ? order by date_of_joining desc';
        $binds = array($jenis_gaji, 1);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function get_attendance_jenis_gaji_employees_shift($jenis_gaji)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = ? and office_id ="S" and is_active = ? order by date_of_joining desc';
        $binds = array($jenis_gaji, 1);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function get_attendance_jenis_gaji_employees_shift_load($jenis_gaji, $company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = ? and company_id = ? and office_id ="S" and is_active = ? order by date_of_joining desc';
        $binds = array($jenis_gaji, $company_id, 1);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    // get total number of employees
    public function get_total_employees()
    {
        $sql = 'SELECT * FROM view_karyawan_aktif';
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function get_total_employees_1()
    {
        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = 1';
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function get_total_employees_2()
    {
        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = 2';
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function get_total_employees_3()
    {
        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = 3';
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function get_total_employees_leave()
    {

        date_default_timezone_set("Asia/Jakarta");
        $now_year  = date("Y");

        $sql = 'SELECT * FROM view_hris_info_leave WHERE tahun_cuti = ' . $now_year . ' ';
        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_total_employees_sick()
    {

        date_default_timezone_set("Asia/Jakarta");
        $now_year  = date("Y");

        $sql = 'SELECT * FROM view_hris_info_sick WHERE tahun_sick = ' . $now_year . '';
        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_total_employees_izin()
    {

        date_default_timezone_set("Asia/Jakarta");
        $now_year  = date("Y");

        $sql = 'SELECT * FROM view_hris_info_izin WHERE tahun_izin = ' . $now_year . '';
        $query = $this->db->query($sql);

        return $query->num_rows();
    }

    public function get_total_employees_lembur()
    {

        date_default_timezone_set("Asia/Jakarta");
        $now_year  = date("Y");

        $sql = 'SELECT * FROM view_hris_info_lembur WHERE tahun_lembur = ' . $now_year . '';
        $query = $this->db->query($sql);

        return $query->num_rows();
    }


    // ============================================================================================================
    public function get_total_employees_departemen($department_id)
    {

        $sql = 'SELECT count(*) as jumlah FROM view_karyawan_aktif WHERE department_id = ' . $department_id . '';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
        return $query->num_rows();
    }


    public function get_total_employees_posisi($department_id)
    {

        $sql = 'SELECT count(*) as jumlah FROM xin_designations WHERE department_id = ' . $department_id . '';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
        return $query->num_rows();
    }

    public function get_total_employees_workstation($company_id, $workstation_id)
    {

        $sql = 'SELECT count(*) as jumlah FROM view_employee_workstation WHERE company_id = ' . $company_id . ' and wages_type = 3 and  workstation_id = ' . $workstation_id . ' AND is_active = 1 ';
        $query = $this->db->query($sql);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
        return $query->num_rows();
    }

    public function get_total_employees_location($location_id)
    {

        $sql = 'SELECT count(*) as jumlah FROM view_karyawan_aktif WHERE location_id = ' . $location_id . '';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
        return $query->num_rows();
    }

    public function get_total_employees_designation($designation_id)
    {

        $sql = 'SELECT count(*) as jumlah FROM view_karyawan_aktif WHERE designation_id = ' . $designation_id . '';
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
        return $query->num_rows();
    }

    public function get_total_employees_designation_gaji_harian($designation_id)
    {

        return $this->db->where(array('designation_id' => $designation_id, 'wages_type' => 2,))->count_all_results('view_karyawan_aktif');
        //$sql = 'SELECT count(*) as jumlah FROM view_karyawan_aktif WHERE designation_id = '.$designation_id.'' ;
        // $query = $this->db->query($sql);
        // if ($query->num_rows() > 0) {
        // 	return $query->result();
        // } else {
        // 	return null;
        // }return $query->num_rows();
    }

    public function get_total_employees_designation_gaji_month($designation_id)
    {

        return $this->db->where(array('designation_id' => $designation_id, 'wages_type' => 1,))->count_all_results('view_karyawan_aktif');
        // $sql = 'SELECT count(*) as jumlah FROM xin_salary_payslips_month WHERE designation_id = '.$designation_id.'';
        // $query = $this->db->query($sql);
        // if ($query->num_rows() > 0) {
        // 	return $query->result();
        // } else {
        // 	return null;
        // }return $query->num_rows();
    }

    public function get_total_employees_designation_gaji_borongan($designation_id)
    {

        return $this->db->where(array('designation_id' => $designation_id, 'wages_type' => 3,))->count_all_results('view_karyawan_aktif');
    }

    public function read_employee_information($id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE user_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function read_employee_information_id($id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // check employeeID
    public function check_employee_id($id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->num_rows();
    }
    // check old password
    public function check_old_password($old_password, $user_id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE user_id = ?';
        $binds = array($user_id);
        $query = $this->db->query($sql, $binds);
        //$rw_password = $query->result();
        $options = array('cost' => 12);
        $password_hash = password_hash($old_password, PASSWORD_BCRYPT, $options);
        if ($query->num_rows() > 0) {
            $rw_password = $query->result();
            if (password_verify($old_password, $rw_password[0]->password)) {
                return 1;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    // check username
    public function check_employee_username($id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE username = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->num_rows();
    }
    // check email
    public function check_employee_email($id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE email = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->num_rows();
    }

    // Function to add record in table
    public function add($data)
    {
        $this->db->insert('xin_employees', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    // Function to Delete selected record from table
    public function delete_record($id)
    {
        $this->db->where('user_id', $id);
        $this->db->delete('xin_employees');
    }

    /*  Update Employee Record */

    // Function to update record in table
    public function update_record($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > basic_info
    public function basic_info($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > change_password
    public function change_password($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > social_info
    public function social_info($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > profile picture
    public function profile_picture($data, $id)
    {
        $this->db->where('user_id', $id);
        if ($this->db->update('xin_employees', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > contact_info
    public function contact_info_add($data)
    {
        $this->db->insert('xin_employee_contact', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > contact_info
    public function contact_info_update($data, $id)
    {
        $this->db->where('contact_id', $id);
        if ($this->db->update('xin_employee_contact', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > document_info_update
    public function document_info_update($data, $id)
    {
        $this->db->where('document_id', $id);
        if ($this->db->update('xin_employee_documents', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > document_info_update
    public function img_document_info_update($data, $id)
    {
        $this->db->where('immigration_id', $id);
        if ($this->db->update('xin_employee_immigration', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > document info
    public function document_info_add($data)
    {
        $this->db->insert('xin_employee_documents', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > immigration info
    public function immigration_info_add($data)
    {
        $this->db->insert('xin_employee_immigration', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    // Function to add record in table > qualification_info_add
    public function qualification_info_add($data)
    {
        $this->db->insert('xin_employee_qualification', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > qualification_info_update
    public function qualification_info_update($data, $id)
    {
        $this->db->where('qualification_id', $id);
        if ($this->db->update('xin_employee_qualification', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > work_experience_info_add
    public function work_experience_info_add($data)
    {
        $this->db->insert('xin_employee_work_experience', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > work_experience_info_update
    public function work_experience_info_update($data, $id)
    {
        $this->db->where('work_experience_id', $id);
        if ($this->db->update('xin_employee_work_experience', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > bank_account_info_add
    public function bank_account_info_add($data)
    {
        $this->db->insert('xin_employee_bankaccount', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table > security level info_add
    public function security_level_info_add($data)
    {
        $this->db->insert('xin_employee_security_level', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > bank_account_info_update
    public function bank_account_info_update($data, $id)
    {
        $this->db->where('bankaccount_id', $id);
        if ($this->db->update('xin_employee_bankaccount', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table > security_level_info_update
    public function security_level_info_update($data, $id)
    {
        $this->db->where('security_level_id', $id);
        if ($this->db->update('xin_employee_security_level', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > contract_info_add
    public function contract_info_add($data)
    {
        $this->db->insert('xin_employee_contract', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //for current contact > employee
    public function check_employee_contact_current($id)
    {

        $sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ? and contact_type = ? limit 1';
        $binds = array($id, 'current');
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    //for permanent contact > employee
    public function check_employee_contact_permanent($id)
    {

        $sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ? and contact_type = ? limit 1';
        $binds = array($id, 'permanent');
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get current contacts by id
    public function read_contact_info_current($id)
    {

        $sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? and contact_type = ? limit 1';
        $binds = array($id, 'current');
        $query = $this->db->query($sql, $binds);

        $row = $query->row();
        return $row;
    }

    // get permanent contacts by id
    public function read_contact_info_permanent($id)
    {

        $sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? and contact_type = ? limit 1';
        $binds = array($id, 'permanent');
        $query = $this->db->query($sql, $binds);

        $row = $query->row();
        return $row;
    }

    // Function to update record in table > contract_info_update
    public function contract_info_update($data, $id)
    {
        $this->db->where('contract_id', $id);
        if ($this->db->update('xin_employee_contract', $data)) {
            return true;
        } else {
            return false;
        }
    }




    // Function to add record in table > leave_info_add
    public function leave_info_add($data)
    {
        $this->db->insert('xin_employee_leave', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table > leave_info_update
    public function leave_info_update($data, $id)
    {
        $this->db->where('leave_id', $id);
        if ($this->db->update('xin_employee_leave', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > shift_info_add
    public function shift_info_add($data)
    {
        $this->db->insert('xin_employee_shift', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > shift_info_update
    public function shift_info_update($data, $id)
    {
        $this->db->where('emp_shift_id', $id);
        if ($this->db->update('xin_employee_shift', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > location_info_add
    public function location_info_add($data)
    {
        $this->db->insert('xin_employee_location', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > location_info_update
    public function location_info_update($data, $id)
    {
        $this->db->where('office_location_id', $id);
        if ($this->db->update('xin_employee_location', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // get all office shifts
    public function all_office_shifts()
    {
        $query = $this->db->query("SELECT * from xin_office_shift");
        return $query->result();
    }

    // get contacts
    public function set_employee_contacts($id)
    {

        $sql = 'SELECT * FROM xin_employee_contacts WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get documents
    public function set_employee_documents($id)
    {

        $sql = 'SELECT * FROM xin_employee_documents WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get documents
    public function get_documents_expired_all()
    {

        $curr_date = date('Y-m-d');
        $query = $this->db->query("SELECT * from xin_employee_documents where date_of_expiry < '" . $curr_date . "' ORDER BY `date_of_expiry` asc");
        return $query;
    }
    // user/
    public function get_user_documents_expired_all($employee_id)
    {

        $curr_date = date('Y-m-d');
        $query = $this->db->query("SELECT * from xin_employee_documents where employee_id = '" . $employee_id . "' and date_of_expiry < '" . $curr_date . "' ORDER BY `date_of_expiry` asc");
        return $query;
    }
    // get immigration documents
    public function get_img_documents_expired_all()
    {

        $curr_date = date('Y-m-d');
        $query = $this->db->query("SELECT * from xin_employee_immigration where expiry_date < '" . $curr_date . "' ORDER BY `expiry_date` asc");
        return $query;
    }
    //user // get immigration documents
    public function get_user_img_documents_expired_all($employee_id)
    {

        $curr_date = date('Y-m-d');
        $query = $this->db->query("SELECT * from xin_employee_immigration where employee_id = '" . $employee_id . "' and expiry_date < '" . $curr_date . "' ORDER BY `expiry_date` asc");
        return $query;
    }
    public function company_license_expired_all()
    {
        $curr_date = date('Y-m-d');
        $query = $this->db->query("SELECT * from xin_company_documents where expiry_date < '" . $curr_date . "' ORDER BY `expiry_date` asc");
        return $query;
    }
    public function get_company_license_expired($company_id)
    {

        $curr_date = date('Y-m-d');
        $sql = "SELECT * FROM xin_company_documents WHERE expiry_date < '" . $curr_date . "' and company_id = ?";
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // assets warranty all
    public function warranty_assets_expired_all()
    {
        $curr_date = date('Y-m-d');
        $query = $this->db->query("SELECT * from xin_assets where warranty_end_date < '" . $curr_date . "' ORDER BY `warranty_end_date` asc");
        return $query;
    }
    // user assets warranty all
    public function user_warranty_assets_expired_all($employee_id)
    {
        $curr_date = date('Y-m-d');
        $query = $this->db->query("SELECT * from xin_assets where employee_id = '" . $employee_id . "' and warranty_end_date < '" . $curr_date . "' ORDER BY `warranty_end_date` asc");
        return $query;
    }
    // company assets warranty all
    public function company_warranty_assets_expired_all($company_id)
    {
        $curr_date = date('Y-m-d');
        $query = $this->db->query("SELECT * from xin_assets where company_id = '" . $company_id . "' and warranty_end_date < '" . $curr_date . "' ORDER BY `warranty_end_date` asc");
        return $query;
    }
    // get immigration
    public function set_employee_immigration($id)
    {

        $sql = 'SELECT * FROM xin_employee_immigration WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }


    // get employee qualification
    public function set_employee_qualification($id)
    {

        $sql = 'SELECT * FROM xin_employee_qualification WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee work experience
    public function set_employee_experience($id)
    {

        $sql = 'SELECT * FROM xin_employee_work_experience WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee bank account
    public function set_employee_bank_account($id)
    {

        $sql = 'SELECT * FROM xin_employee_bankaccount WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    // get employee bank account
    public function set_employee_security_level($id)
    {

        $sql = 'SELECT * FROM xin_employee_security_level WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    // get employee bank account > Last
    public function get_employee_bank_account_last($id)
    {

        $sql = 'SELECT * FROM xin_employee_bankaccount WHERE employee_id = ? ORDER BY is_primary DESC, created_at DESC limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_all_employee_bank_account($id)
    {
        $sub_query = $this->db->select("@rn := 0, @Id := ''", FALSE)->get_compiled_select();

        $query = $this->db
            ->select('xin_employee_bankaccount.*')
            ->join("({$sub_query}) params", "1=1")
            ->where_in('employee_id', $id)
            ->where(1, "(@rn := IF(@Id = employee_id, @rn + 1, IF(@Id := employee_id, 1, 1)))", FALSE)
            ->get('xin_employee_bankaccount');
        // ->get_compiled_select('xin_employee_bankaccount');

        return $query->result();
    }

    public function get_employee_by_department_company($cid, $did)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_dep WHERE company_id = ? AND department_id = ?';
        $binds = array($cid, $did);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_department_company_bulanan_thr($cid, $did, $tahun_thr, $tanggal_thr)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_dep_bulanan_thr WHERE company_id = ? AND department_id = ? and tahun_thr = ? and tanggal_thr = ?';
        $binds = array($cid, $did, $tahun_thr, $tanggal_thr);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_department_company_bulanan($cid, $did, $salary_month)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_dep_bulanan_gaji WHERE company_id = ? AND department_id = ? and salary_month = ?';
        $binds = array($cid, $did, $salary_month);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    public function get_employee_by_designation_company_harian($cid, $did, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_designation_harian WHERE company_id = ? AND designation_id = ? and start_date = ? and end_date = ? ';
        $binds = array($cid, $did, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_workstation_company_borongan_detail($cid, $did, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_designation_borongan WHERE company_id = ? AND workstation_id = ? and start_date = ? and end_date = ? ';
        $binds = array($cid, $did, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);


        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    public function get_employee_by_workstation_company_harian($cid, $did, $start_date, $end_date)
    {

        $sql = 'SELECT sum(jumlah) as jumlah_works FROM view_karyawan_aktif_workstation_harian WHERE company_id = ? AND workstation_id = ? and start_date >= ? and end_date <= ? ';
        $binds = array($cid, $did, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_workstation_company_borongan($cid, $did, $start_date, $end_date)
    {

        $sql = 'SELECT sum(jumlah) as jumlah_works FROM view_karyawan_aktif_workstation_borongan WHERE company_id = ? AND workstation_id = ? and start_date >= ? and end_date <= ? ';
        $binds = array($cid, $did, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    public function get_employee_by_company($cid)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_company WHERE company_id = ?';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_company_bulanan_thr($cid, $tahun_thr, $tanggal_thr)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_company_bulanan_thr WHERE company_id = ? and tahun_thr = ? AND tanggal_thr = ? ';
        $binds = array($cid, $tahun_thr, $tanggal_thr);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_company_bulanan($cid, $salary_month)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_company_bulanan WHERE company_id = ? and salary_month = ?';
        $binds = array($cid, $salary_month);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_company_harian($cid, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_company_harian WHERE company_id = ? and start_date = ? and end_date = ? ';
        $binds = array($cid, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_company_borongan($cid, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_company_borongan WHERE company_id = ? and start_date = ? and end_date = ? ';
        $binds = array($cid, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_company_harian_workstation($cid, $start_date, $end_date)
    {

        $sql = 'SELECT sum(jumlah) as jumlah_karyawan FROM view_karyawan_aktif_company_harian WHERE company_id = ? and start_date >= ? and end_date <= ? ';
        $binds = array($cid, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_employee_by_company_borongan_workstation($cid, $start_date, $end_date)
    {

        $sql = 'SELECT sum(jumlah) as jumlah_karyawan FROM view_karyawan_aktif_company_borongan WHERE company_id = ? and start_date >= ? and end_date <= ? ';
        $binds = array($cid, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    // get employee contract
    public function set_employee_contract($id)
    {

        $sql = 'SELECT * FROM view_hris_karyawan_status_kontrak WHERE employee_id = ? ORDER BY from_date ASC';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee contract
    public function read_employee_contract_information($id)
    {

        date_default_timezone_set("Asia/Jakarta");
        $tanggal     = date("Y-m-d");

        $sql = "SELECT * FROM view_employee_contract WHERE employee_id = ? AND from_date <= '" . $tanggal . "' AND to_date >= '" . $tanggal . "'  ";
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_employee_contract_information2($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggal = date("Y-m-d");

        $query = $this->db
            ->where('from_date <=', $tanggal)
            ->where('to_date >=', $tanggal);

        if (is_array($id)) {
            $query = $query->where_in('employee_id', $id);
        } else {
            $query = $query->where('employee_id', $id);
        }

        $query = $query->get('view_employee_contract');
        // $sql = "SELECT * FROM view_employee_contract WHERE employee_id = ? AND from_date <= '".$tanggal."' AND to_date >= '".$tanggal."'  ";
        // $binds = array($id);
        // $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    // get employee office shift
    public function set_employee_shift($id)
    {

        $sql = 'SELECT * FROM xin_employee_shift WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee leave
    public function set_employee_leave($id)
    {

        $sql = 'SELECT * FROM xin_employee_leave WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee location
    public function set_employee_location($id)
    {

        $sql = 'SELECT * FROM xin_employee_location WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get document type by id
    public function read_document_type_information($id)
    {

        $sql = 'SELECT * FROM xin_document_type WHERE document_type_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // contract type
    public function read_contract_type_information($id)
    {

        $sql = 'SELECT * FROM xin_contract_type WHERE contract_type_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // contract type
    public function read_contract_durasi_information($id)
    {

        $sql = 'SELECT * FROM xin_contract_durasi WHERE contract_durasi_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_contract_status_information($id)
    {

        $sql = 'SELECT * FROM view_hris_karyawan_status_kontrak WHERE contract_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_contract_status_ada_information($id)
    {

        $sql = 'SELECT * FROM view_hris_karyawan_status_kontrak WHERE employee_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // contract employee
    public function read_contract_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_contract WHERE contract_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_contract_emp_information($id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE kontrak_id = ? ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // office shift
    public function read_shift_information($id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }



    // get all contract types
    public function all_contract_types()
    {
        $query = $this->db->query("SELECT * from xin_contract_type");
        return $query->result();
    }

    // get all contract types
    public function all_contract_durasi()
    {
        $query = $this->db->query("SELECT * from xin_contract_durasi ORDER BY urut ASC");
        return $query->result();
    }

    // get all contracts
    public function all_contracts()
    {
        $query = $this->db->query("SELECT * from xin_employee_contract");
        return $query->result();
    }

    // get all document types
    public function all_document_types()
    {
        $query = $this->db->query("SELECT * from xin_document_type");
        return $query->result();
    }

    // get all education level
    public function all_education_level()
    {
        $query = $this->db->query("SELECT * from xin_qualification_education_level");
        return $query->result();
    }

    // get education level by id
    public function read_education_information($id)
    {

        $sql = 'SELECT * FROM xin_qualification_education_level WHERE education_level_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get all qualification languages
    public function all_qualification_language()
    {
        $query = $this->db->query("SELECT * from xin_qualification_language");
        return $query->result();
    }

    // get languages by id
    public function read_qualification_language_information($id)
    {

        $sql = 'SELECT * FROM xin_qualification_language WHERE language_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get all qualification skills
    public function all_qualification_skill()
    {
        $query = $this->db->query("SELECT * from xin_qualification_skill");
        return $query->result();
    }

    // get qualification by id
    public function read_qualification_skill_information($id)
    {

        $sql = 'SELECT * FROM xin_qualification_skill WHERE skill_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get contacts by id
    public function read_contact_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_contacts WHERE contact_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get documents by id
    public function read_document_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_documents WHERE document_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get documents by id
    public function read_imgdocument_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_immigration WHERE immigration_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get qualifications by id
    public function read_qualification_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_qualification WHERE qualification_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get qualifications by id
    public function read_work_experience_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_work_experience WHERE work_experience_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get bank account by id
    public function read_bank_account_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_bankaccount WHERE bankaccount_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get sc level by id
    public function read_security_level_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_security_level WHERE security_level_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get leave by id
    public function read_leave_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_leave WHERE leave_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get shift by id
    public function read_emp_shift_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_shift WHERE emp_shift_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // Function to Delete selected record from table
    public function delete_contact_record($id)
    {
        $this->db->where('contact_id', $id);
        $this->db->delete('xin_employee_contact');
    }

    // Function to Delete selected record from table
    public function delete_document_record($id)
    {
        $this->db->where('document_id', $id);
        $this->db->delete('xin_employee_documents');
    }

    // Function to Delete selected record from table
    public function delete_imgdocument_record($id)
    {
        $this->db->where('immigration_id', $id);
        $this->db->delete('xin_employee_immigration');
    }

    // Function to Delete selected record from table
    public function delete_qualification_record($id)
    {
        $this->db->where('qualification_id', $id);
        $this->db->delete('xin_employee_qualification');
    }

    // Function to Delete selected record from table
    public function delete_work_experience_record($id)
    {
        $this->db->where('work_experience_id', $id);
        $this->db->delete('xin_employee_work_experience');
    }

    // Function to Delete selected record from table
    public function delete_bank_account_record($id)
    {
        $this->db->where('bankaccount_id', $id);
        $this->db->delete('xin_employee_bankaccount');
    }
    // Function to Delete selected record from table
    public function delete_security_level_record($id)
    {
        $this->db->where('security_level_id', $id);
        $this->db->delete('xin_employee_security_level');
    }

    // Function to Delete selected record from table
    public function delete_contract_record($id)
    {
        $this->db->where('contract_id', $id);
        $this->db->delete('xin_employee_contract');
    }

    // Function to Delete selected record from table
    public function delete_leave_record($id)
    {
        $this->db->where('leave_id', $id);
        $this->db->delete('xin_employee_leave');
    }

    // Function to Delete selected record from table
    public function delete_shift_record($id)
    {
        $this->db->where('emp_shift_id', $id);
        $this->db->delete('xin_employee_shift');
    }

    // Function to Delete selected record from table
    public function delete_location_record($id)
    {
        $this->db->where('office_location_id', $id);
        $this->db->delete('xin_employee_location');
    }

    // get location by id
    public function read_location_information($id)
    {

        $sql = 'SELECT * FROM xin_employee_location WHERE office_location_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function record_count()
    {
        $sql = 'SELECT * FROM xin_employees where is_active = 1';
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    public function record_count_myteam($reports_to)
    {
        $sql = 'SELECT * FROM xin_employees where is_active = 1 and reports_to = ' . $reports_to . '';
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    // read filter record
    public function get_employee_by_department($cid)
    {

        $sql = 'SELECT * FROM xin_employees WHERE department_id = ?';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // read filter record
    public function record_count_company_employees($cid)
    {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ?';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query->num_rows();
    }
    // read filter record
    public function record_count_company_location_employees($cid, $lid)
    {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id= ?';
        $binds = array($cid, $lid);
        $query = $this->db->query($sql, $binds);
        return $query->num_rows();
    }
    // read filter record
    public function record_count_company_location_department_employees($cid, $lid, $dep_id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id= ? and department_id= ?';
        $binds = array($cid, $lid, $dep_id);
        $query = $this->db->query($sql, $binds);
        return $query->num_rows();
    }
    // read filter record
    public function record_count_company_location_department_designation_employees($cid, $lid, $dep_id, $des_id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? and location_id= ? and department_id= ? and designation_id= ?';
        $binds = array($cid, $lid, $dep_id, $des_id);
        $query = $this->db->query($sql, $binds);
        return $query->num_rows();
    }
    //reports_to -> my employees
    public function fetch_all_team_employees($limit, $start)
    {
        $session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
        $this->db->order_by("designation_id asc");
        //$this->db->where("user_role_id!=",1);
        $user_info = $this->Core_model->read_user_info($session['user_id']);
        $this->db->where("reports_to", $session['user_id']);
        $this->db->where(" is_active = 1 ");
        $query = $this->db->get("xin_employees");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    public function fetch_all_employees($limit, $start)
    {
        $session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
        $this->db->order_by("date_of_joining desc");
        //$this->db->where("user_role_id!=",1);
        $user_info = $this->Core_model->read_user_info($session['user_id']);
        if ($user_info[0]->user_role_id != 1) {
            $this->db->where("company_id", $user_info[0]->company_id);
        }
        $this->db->where("is_active = 1");
        $query = $this->db->get("xin_employees");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    // get company employees
    public function fetch_all_company_employees_flt($limit, $start, $cid)
    {
        $session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
        $this->db->order_by("designation_id asc");
        $this->db->where("company_id", $cid);
        $query = $this->db->get("xin_employees");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    // get company|location employees
    public function fetch_all_company_location_employees_flt($limit, $start, $cid, $lid)
    {
        $session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
        $this->db->order_by("designation_id asc");
        $this->db->where("company_id=", $cid);
        $this->db->where("location_id=", $lid);
        $query = $this->db->get("xin_employees");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    // get company|location|department employees
    public function fetch_all_company_location_department_employees_flt($limit, $start, $cid, $lid, $dep_id)
    {
        $session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
        $this->db->order_by("designation_id asc");
        $this->db->where("company_id=", $cid);
        $this->db->where("location_id=", $lid);
        $this->db->where("department_id=", $dep_id);
        $query = $this->db->get("xin_employees");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    // get company|location|department|designation employees
    public function fetch_all_company_location_department_designation_employees_flt($limit, $start, $cid, $lid, $dep_id, $des_id)
    {
        $session = $this->session->userdata('username');
        $this->db->limit($limit, $start);
        $this->db->order_by("designation_id asc");
        $this->db->where("company_id=", $cid);
        $this->db->where("location_id=", $lid);
        $this->db->where("department_id=", $dep_id);
        $this->db->where("designation_id=", $des_id);
        $query = $this->db->get("xin_employees");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function des_fetch_all_employees($limit, $start)
    {
        // $this->db->limit($limit, $start);

        $sql = 'SELECT * FROM xin_employees order by designation_id asc limit ?, ?';
        $binds = array($limit, $start);
        $query = $this->db->query($sql, $binds);

        //  $query = $this->db->get("xin_employees");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
    // get employee allowances
    public function set_employee_allowances($id)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    // get employee commissions
    public function set_employee_gapok($id)
    {

        $sql = 'SELECT * FROM xin_salary_gapok WHERE employe_id = ? ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee commissions
    public function set_employee_commissions($id)
    {

        $sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ? and flag = 0';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee commissions
    public function set_employee_commissions_help($id)
    {

        $sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ? and flag = 1 ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee commissions
    public function set_employee_minus($id)
    {

        $sql = 'SELECT * FROM xin_salary_minus WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee statutory deductions
    public function set_employee_statutory_deductions($id)
    {

        $sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }



    // get employee other payments
    public function set_employee_other_payments($id)
    {

        $sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function set_employee_lain_payments($id)
    {

        $sql = 'SELECT * FROM xin_salary_lain_payments WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee overtime
    public function set_employee_overtime($id)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ? ORDER BY overtime_date DESC';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee overtime
    public function set_employee_libur($id)
    {

        $sql = 'SELECT * FROM xin_libur_applications WHERE  employee_id = ? AND overtime_date >= ? AND overtime_date <= ? ORDER BY from_date DESC';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function set_employee_overtime_harian($id, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ? AND overtime_date >= ? AND overtime_date <= ? ORDER BY overtime_date DESC';
        $binds = array($id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function set_employee_overtime_borongan($id, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ? AND overtime_date >= ? AND overtime_date <= ? ORDER BY overtime_date DESC';
        $binds = array($id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee allowances
    public function set_employee_deductions($id)
    {

        $sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    //-- payslip data
    // get employee allowances
    public function set_employee_allowances_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee statutory_deductions
    public function set_employee_statutory_deductions_payslip_harian($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function set_employee_statutory_deductions_payslip_borongan($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function set_employee_minus_payslip_harian($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function set_employee_minus_payslip_borongan($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function set_employee_commissions_payslip_harian($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function set_employee_commissions_payslip_borongan($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee commissions
    public function set_employee_commissions_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    // get employee other payments
    public function set_employee_other_payments_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    // get employee statutory_deductions
    public function set_employee_statutory_deductions_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    // get employee overtime
    public function set_employee_overtime_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    // get employee overtime
    public function set_employee_potongan_lain_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    public function set_employee_overtime_payslip_harian($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get employee allowances
    public function set_employee_deductions_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    //------
    // get employee allowances
    public function count_employee_allowances_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->num_rows();
    }
    // get employee commissions
    public function count_employee_commissions_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // get employee statutory_deductions
    public function count_employee_statutory_deductions_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // get employee other payments
    public function count_employee_other_payments_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // get employee overtime
    public function count_employee_overtime_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    // get employee overtime
    public function count_employee_potongan_lain_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function count_employee_statutory_deductions_payslip_harian($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function count_employee_statutory_deductions_payslip_borongan($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function count_employee_minus_payslip_harian($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function count_employee_minus_payslip_borongan($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function count_employee_commissions_payslip_harian($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }


    public function count_employee_commissions_payslip_borongan($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function count_employee_overtime_payslip_harian($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    // get employee allowances
    public function count_employee_deductions_payslip($id)
    {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    //////////////////////
    // get employee allowances
    public function count_employee_allowances($id)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }


    // get employee commissions

    // get employee other payments
    public function count_employee_other_payments($id)
    {

        $sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // get employee statutory deduction
    public function count_employee_statutory_deductions($id)
    {

        $sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function count_employee_bpjs_tk($id, $deduction_date)
    {

        $sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ? and statutory_options = 1  and deduction_date <= ? ORDER BY deduction_date DESC LIMIT 1';
        $binds = array($id, $deduction_date);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function set_employee_bpjs_tk($id, $deduction_date)
    {

        $sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ? and statutory_options = 1  and deduction_date <= ? ORDER BY deduction_date DESC LIMIT 1';
        $binds = array($id, $deduction_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function count_employee_bpjs_kes($id, $deduction_date)
    {
        return $this->db
            ->where(array(
                'employee_id' => $id,
                'statutory_options' => 2,
            ))
            ->where('deduction_date <=', $deduction_date)
            ->order_by('deduction_date', 'DESC')
            ->limit(1)
            ->get('xin_salary_statutory_deductions')
            ->num_rows();
    }

    public function set_employee_bpjs_kes($id, $deduction_date)
    {
        return $this->db
            ->where(array(
                'employee_id' => $id,
                'statutory_options' => 2,
            ))
            ->where('deduction_date <=', $deduction_date)
            ->order_by('deduction_date', 'DESC')
            ->limit(1)
            ->get('xin_salary_statutory_deductions');
    }

    public function sum_all_employee_bpjs($id, $deduction_date)
    {
        // SELECT p1.*
        // FROM (SELECT * FROM xin_salary_statutory_deductions where deduction_date < '2023-07-01' ) p1 LEFT JOIN (SELECT * FROM xin_salary_statutory_deductions where deduction_date < '2023-07-01' ) p2
        // ON (p1.employee_id = p2.employee_id AND p1.statutory_options = p2.statutory_options AND p1.statutory_deductions_id < p2.statutory_deductions_id)
        // WHERE p2.statutory_deductions_id IS NULL
        // -- AND p1.employee_id = 69
        // -- AND p1.deduction_date <= '2023-07-01'
        // ORDER BY employee_id ASC;

        $sub_query = $this->db
            ->where('deduction_date <=', $deduction_date)
            ->where_in('statutory_options', array(1, 2))
            ->get_compiled_select('xin_salary_statutory_deductions');

        $query = $this->db
            ->select(array(
                'p1.employee_id',
                'SUM(CASE WHEN p1.statutory_options = 1 THEN p1.deduction_amount ELSE 0 END) total_bpjstk',
                'SUM(CASE WHEN p1.statutory_options = 2 THEN p1.deduction_amount ELSE 0 END) total_bpjskes'
            ))
            ->from("({$sub_query}) AS p1")
            ->join(
                "({$sub_query}) AS p2",
                "p1.employee_id = p2.employee_id AND p1.statutory_options = p2.statutory_options AND p1.statutory_deductions_id < p2.statutory_deductions_id",
                "LEFT"
            )
            ->where_in('p1.employee_id', $id)
            ->where('p2.statutory_deductions_id IS NULL', NULL, FALSE)
            ->group_by(array('p1.employee_id'))
            ->get();

        return $query->result();
    }


    // get employee overtime
    public function count_employee_overtime($id)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }




    // get employee allowances


    // get employee salary allowances
    public function read_salary_allowances($id)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // ====================================================================================================================
    // KOMPONEN GAJI - TAMBAH
    // ====================================================================================================================


    // =====================================================================================================================================================
    // TUNJANGAN
    // =====================================================================================================================================================
    // =================================================================================================================================================
    // Tunj Jabatan Tahun
    // =================================================================================================================================================
    public function read_salary_allowances_jabatan_tahun($id, $tahun)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND YEAR(allowance_date) <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $tahun);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function count_employee_allowances_jabatan_tahun($id, $tahun)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND YEAR(allowance_date) <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $tahun);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // =================================================================================================================================================
    // Tunj Jabatan
    // =================================================================================================================================================
    public function read_salary_allowances_jabatan($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND allowance_date <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function count_employee_allowances_jabatan($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND allowance_date <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // =================================================================================================================================================
    // Tunj produktifitas
    // =================================================================================================================================================
    public function read_salary_allowances_produktifitas($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND allowance_date <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function count_employee_allowances_produktifitas($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND allowance_date <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // =================================================================================================================================================
    // Tunj Komunikasi
    // =================================================================================================================================================
    public function read_salary_allowances_komunikasi($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND allowance_date <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function count_employee_allowances_komunikasi($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND allowance_date <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // =================================================================================================================================================
    // Tunj Transportasi
    // =================================================================================================================================================
    public function read_salary_allowances_transportasi($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ? AND allowance_date <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function count_employee_allowances_transportasi($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE employee_id = ?  AND allowance_date <= ? ORDER BY allowance_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }


    // ****************************************************************************************************************
    // TIDAK TETAP
    // ****************************************************************************************************************

    // =====================================================================================================================================================
    // GAPOK
    // =====================================================================================================================================================

    public function read_payroll_salary_gapok($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_gapok WHERE employe_id = ?  AND gapok_date <= ? ORDER BY gapok_date DESC LIMIT 1 ';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function count_employee_gapok($id, $start)
    {

        $sql = 'SELECT * FROM xin_salary_gapok WHERE employe_id = ?  AND gapok_date <= ? ORDER BY gapok_date DESC LIMIT 1';
        $binds = array($id, $start);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function read_all_payroll_salary_gapok($id, $date)
    {
        $sub_query = $this->db
            ->select('employe_id, MAX(gapok_date) latest_gapok')
            ->where_in('employe_id', $id)
            ->where('gapok_date <=', $date)
            ->group_by('employe_id')
            ->get_compiled_select('xin_salary_gapok');

        $sql = "SELECT sg.* FROM xin_salary_gapok sg JOIN ({$sub_query}) l ON l.employe_id = sg.employe_id AND l.latest_gapok = sg.gapok_date";

        return $this->db->query($sql)->result();
    }

    // =====================================================================================================================================================
    // INSENTIF
    // =====================================================================================================================================================

    public function read_payroll_salary_commissions($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ? AND flag = 0 AND commission_date >= ? AND commission_date <= ? ORDER BY commission_date DESC ';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function count_employee_commissions($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ? AND flag = 0 AND commission_date >= ? AND commission_date <= ? ORDER BY commission_date DESC';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function sum_all_payroll_salary_commissions($id, $start, $end)
    {
        $query = $this->db
            ->select('employee_id, SUM(commission_amount) total_amount')
            ->where_in('employee_id', $id)
            ->where('commission_date >=', $start)
            ->where('commission_date <=', $end)
            ->where('flag', 0)
            ->group_by('employee_id');

        return $query->get('xin_salary_commissions')->result();
    }

    // =====================================================================================================================================================
    // INSENTIF
    // =====================================================================================================================================================

    public function read_payroll_salary_commissions_help($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_salary_commissions WHERE flag = 1 AND employee_id = ? AND commission_date >= ? AND commission_date <= ? ORDER BY commission_date DESC ';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function count_employee_commissions_help($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_salary_commissions WHERE flag = 1 AND employee_id = ? AND commission_date >= ? AND commission_date <= ? ORDER BY commission_date DESC';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    // =====================================================================================================================================================
    // MINUS
    // =====================================================================================================================================================

    public function read_payroll_salary_minus($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_salary_minus WHERE employee_id = ? AND minus_date >= ? AND minus_date <= ? ORDER BY minus_date DESC ';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function count_employee_minus($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_salary_minus WHERE employee_id = ? AND minus_date >= ? AND minus_date <= ? ORDER BY minus_date DESC';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    public function sum_payroll_salary_minus($id, $start, $end)
    {
        $query = $this->db
            ->select('employee_id, SUM(minus_amount) total_minus')
            ->where_in('employee_id', $id)
            ->where('minus_date >=', $start)
            ->where('minus_date <=', $end)
            ->group_by('employee_id');

        return $query->get('xin_salary_minus')->result();
    }


    // =====================================================================================================================================================
    // LEMBUR
    // =====================================================================================================================================================

    public function read_payroll_salary_overtime($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ? AND overtime_date >= ? AND overtime_date <= ? ORDER BY overtime_date DESC';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function sum_payroll_salary_overtime($id, $start, $end)
    {
        $query = $this->db
            ->select('employee_id, SUM(overtime_total) total_overtime, SUM(overtime_hours_total) total_overtime_hour')
            ->where_in('employee_id', $id)
            ->where('overtime_date >=', $start)
            ->where('overtime_date <=', $end)
            ->group_by('employee_id');

        return $query->get('xin_salary_overtime')->result();
    }

    public function read_izin_info($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_izin_applications WHERE employee_id = ? AND from_date = ? AND to_date = ? ORDER BY overtime_date DESC';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get employee overtime
    public function count_payroll_employee_overtime($id, $start, $end)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ? AND overtime_date >= ? AND overtime_date <= ?';
        $binds = array($id, $start, $end);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->num_rows();
    }

    // get employee salary commissions
    public function read_salary_commissions($id)
    {

        $sql = 'SELECT * FROM xin_salary_commissions WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get employee salary other payments
    public function read_salary_other_payments($id)
    {

        $sql = 'SELECT * FROM xin_salary_other_payments WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get employee statutory deductions
    public function read_salary_statutory_deductions($id)
    {

        $sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get employee overtime
    public function read_salary_overtime($id)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }



    // get employee salary loan_deduction
    public function read_salary_loan_deductions($id, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ? and start_date <= "' . $start_date . '" and end_date >= "' . $end_date . '" ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function count_employee_deductions($id, $start_date, $end_date)
    {

        $sql = 'SELECT * FROM xin_salary_loan_deductions WHERE employee_id = ? and start_date <= "' . $start_date . '" and end_date >= "' . $end_date . '" ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }

    // get employee salary loan_deduction
    public function read_single_loan_deductions($id)
    {

        $sql = 'SELECT * FROM xin_salary_loan_deductions WHERE loan_deduction_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    //Calculates how many months is past between two timestamps.
    public function get_month_diff($start, $end = FALSE)
    {
        $end or $end = time();
        $start = new DateTime($start);
        $end   = new DateTime($end);
        $diff  = $start->diff($end);
        return $diff->format('%y') * 12 + $diff->format('%m');
    }
    // get employee salary allowances
    public function read_single_salary_allowance($id)
    {

        $sql = 'SELECT * FROM xin_salary_allowance WHERE allowance_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get employee commissions
    public function read_single_salary_gapok($id)
    {

        $sql = 'SELECT * FROM xin_salary_gapok WHERE salary_gapok_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function read_single_salary_commissions($id)
    {

        $sql = 'SELECT * FROM xin_salary_commissions WHERE salary_commissions_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get employee commissions
    public function read_single_salary_minus($id)
    {

        $sql = 'SELECT * FROM xin_salary_minus WHERE salary_minus_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get
    public function read_single_salary_statutory_deduction($id)
    {

        $sql = 'SELECT * FROM xin_salary_statutory_deductions WHERE statutory_deductions_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function read_single_salary_other_payment($id)
    {

        $sql = 'SELECT * FROM xin_salary_other_payments WHERE other_payments_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    public function read_single_salary_lain_payment($id)
    {

        $sql = 'SELECT * FROM xin_salary_lain_payments WHERE lain_payments_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get employee overtime record
    public function read_salary_overtime_record($id)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE salary_overtime_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // Function to add record in table > allowance
    public function add_salary_allowances($data)
    {
        $this->db->insert('xin_salary_allowance', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table > commissions
    public function add_salary_gapok($data)
    {
        $this->db->insert('xin_salary_gapok', $data);
        if ($this->db->affected_rows() > 0) {
            $this->db->update('xin_employees', array('basic_salary' => $data['gapok_amount']), array('user_id' => $data['employe_id']));
            return true;
        } else {
            return false;
        }
    }

    public function add_salary_commissions($data)
    {
        $this->db->insert('xin_salary_commissions', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table > commissions
    public function add_salary_minus($data)
    {
        $this->db->insert('xin_salary_minus', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table > statutory_deductions
    public function add_salary_statutory_deductions($data)
    {
        $this->db->insert('xin_salary_statutory_deductions', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table > other payments
    public function add_salary_other_payments($data)
    {
        $this->db->insert('xin_salary_other_payments', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function add_salary_lain_payments($data)
    {
        $this->db->insert('xin_salary_lain_payments', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table > loan
    public function add_salary_loan($data)
    {
        $this->db->insert('xin_salary_loan_deductions', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table > overtime
    public function add_salary_overtime($data)
    {
        $this->db->insert('xin_salary_overtime', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to Delete selected record from table
    public function delete_allowance_record($id)
    {
        $this->db->where('allowance_id', $id);
        $this->db->delete('xin_salary_allowance');
    }
    // Function to Delete selected record from table
    public function delete_commission_record($id)
    {
        $this->db->where('salary_commissions_id', $id);
        $this->db->delete('xin_salary_commissions');
    }
    public function delete_gapok_record($id)
    {
        $gapok = $this->db->where('salary_gapok_id', $id)->get('xin_salary_gapok')->row();

        $this->db->where('salary_gapok_id', $id);
        $this->db->delete('xin_salary_gapok');

        $last_salary = $this->db
            ->where('employe_id', $gapok->employe_id)
            ->order_by('gapok_date', 'desc')
            ->limit(1)
            ->get('xin_salary_gapok')
            ->row();

        if ($last_salary->gapok_date) {
            $this->db->update('xin_employees', array('basic_salary' => $last_salary->gapok_amount), array('user_id' => $last_salary->employe_id));
        }
    }

    // Function to Delete selected record from table
    public function delete_minus_record($id)
    {
        $this->db->where('salary_minus_id', $id);
        $this->db->delete('xin_salary_minus');
    }
    // Function to Delete selected record from table
    public function delete_salary_overtime_record($id)
    {
        $this->db->where('salary_overtime_id', $id);
        $this->db->delete('xin_salary_overtime');
    }

    public function delete_statutory_deductions_record($id)
    {
        $this->db->where('statutory_deductions_id', $id);
        $this->db->delete('xin_salary_statutory_deductions');
    }
    // Function to Delete selected record from table
    public function delete_other_payments_record($id)
    {
        $this->db->where('other_payments_id', $id);
        $this->db->delete('xin_salary_other_payments');
    }

    public function delete_lain_payments_record($id)
    {
        $this->db->where('lain_payments_id', $id);
        $this->db->delete('xin_salary_lain_payments');
    }
    // Function to Delete selected record from table
    public function delete_loan_record($id)
    {
        $this->db->where('loan_deduction_id', $id);
        $this->db->delete('xin_salary_loan_deductions');
    }
    // Function to Delete selected record from table
    public function delete_overtime_record($id)
    {
        $this->db->where('salary_overtime_id', $id);
        $this->db->delete('xin_salary_overtime');
    }
    // Function to update record in table > update allowance record
    public function salary_allowance_update_record($data, $id)
    {
        $this->db->where('allowance_id', $id);
        if ($this->db->update('xin_salary_allowance', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table >
    public function salary_gapok_update_record($data, $id)
    {
        $this->db->where('salary_gapok_id', $id);
        if ($this->db->update('xin_salary_gapok', $data)) {
            $gapok = $this->db->where('salary_gapok_id', $id)->get('xin_salary_gapok')->row();
            $last_salary = $this->db
                ->where('employe_id', $gapok->employe_id)
                ->order_by('gapok_date', 'desc')
                ->limit(1)
                ->get('xin_salary_gapok')
                ->row();

            if ($last_salary->gapok_date == $gapok->gapok_date) {
                $this->db->update('xin_employees', array('basic_salary' => $last_salary->gapok_amount), array('user_id' => $last_salary->employe_id));
            }

            return true;
        } else {
            return false;
        }
    }

    public function salary_commissions_update_record($data, $id)
    {
        $this->db->where('salary_commissions_id', $id);
        if ($this->db->update('xin_salary_commissions', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table >
    public function salary_minus_update_record($data, $id)
    {
        $this->db->where('salary_minus_id', $id);
        if ($this->db->update('xin_salary_minus', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table >
    public function salary_statutory_deduction_update_record($data, $id)
    {
        $this->db->where('statutory_deductions_id', $id);
        if ($this->db->update('xin_salary_statutory_deductions', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table >
    public function salary_other_payment_update_record($data, $id)
    {
        $this->db->where('other_payments_id', $id);
        if ($this->db->update('xin_salary_other_payments', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function salary_lain_payment_update_record($data, $id)
    {
        $this->db->where('lain_payments_id', $id);
        if ($this->db->update('xin_salary_lain_payments', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table > update allowance record
    public function salary_loan_update_record($data, $id)
    {
        $this->db->where('loan_deduction_id', $id);
        if ($this->db->update('xin_salary_loan_deductions', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table > update allowance record
    public function salary_overtime_update_record($data, $id)
    {
        $this->db->where('salary_overtime_id', $id);
        if ($this->db->update('xin_salary_overtime', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // get single record > company | office shift
    public function ajax_company_officeshift_information($id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE company_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
}

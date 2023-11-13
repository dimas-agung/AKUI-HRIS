<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    class payroll_model extends CI_Model
    {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // get payroll templates
    public function get_templates() {
      return $this->db->get("xin_salary_templates");
    }

    // get payroll templates > for companies
    public function get_comp_template($cid,$id) {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? and user_role_id!=?';
        $binds = array($cid,1);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_comp_template_bulanan($cid) {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? AND wages_type = 1 AND is_active = 1 ORDER BY date_of_joining DESC';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_kehadiran_template_bulanan($cid) {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? AND wages_type = 1 AND is_active = 1 ORDER BY date_of_joining DESC';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_comp_template_harian($cid) {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? AND wages_type = 2 AND is_active = 1 ORDER BY date_of_joining DESC';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_comp_template_harian_new($cid,$lid) {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? AND location_id = ? AND wages_type = 2 AND is_active = 1 ORDER BY date_of_joining DESC';
        $binds = array($cid,$lid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_comp_template_borongan($cid,$wid) {

        $sql = 'SELECT * FROM view_karyawan_workstation_aktif WHERE company_id = ? AND workstation_id = ? AND wages_type = 3 AND is_active = 1 ORDER BY date_of_joining DESC';
        $binds = array($cid,$wid);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_bulanan_promosi_demosi($cid,$did) {

        $sql = '
        SELECT employee_id from xin_employee_promotions WHERE promotion_date <= "'.$did.'"
        UNION
        SELECT employee_id from view_karyawan_aktif WHERE company_id = "'.$cid.'" is_active = 1 AND wages_type = 1 AND employee_id NOT IN (SELECT employee_id from xin_employee_promotions) ';

        $query = $this->db->query($sql);
        return $query;
    }




    // get payroll templates > employee/company
    public function get_employee_comp_template($cid,$id) {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? and user_id = ?';
        $binds = array($cid,$id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_comp_templates($cid) {

        $sql = 'SELECT * FROM xin_employees WHERE company_id = ? ';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_comp_template_bulanan_dep_lihat_thr($cid,$id,$p_date,$tanggal_thr,$dept) {

        $sql = 'SELECT * FROM xin_thr_payslips_month WHERE company_id = ? AND wages_type = ? AND tahun_thr = ? AND tanggal_thr = ? AND department_id = ? ORDER BY doj ASC ';
        $binds = array($cid,$id,$p_date,$tanggal_thr,$dept);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }


    public function get_comp_template_bulanan_dep_lihat($cid,$id,$p_date,$dept) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? AND wages_type = ? AND salary_month = ? AND department_id = ? ORDER BY doj ASC ';
        $binds = array($cid,$id,$p_date,$dept);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_harian_designation_lihat($cid,$id,$start_date,$end_date,$designation) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE company_id = ? AND wages_type = ? AND start_date = ? AND end_date = ? AND designation_id = ? ORDER BY doj ASC ';
        $binds = array($cid,$id,$start_date,$end_date,$designation);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_borongan_workstation_detail($cid,$id,$start_date,$end_date,$workstation) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE company_id = ? AND wages_type = ? AND start_date = ? AND end_date = ? AND workstation_id = ? ORDER BY doj ASC ';
        $binds = array($cid,$id,$start_date,$end_date,$workstation);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_harian_workstation_lihat($cid,$start_date,$end_date,$workstation) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE company_id = ? AND start_date >= ? AND end_date <= ? AND workstation_id = ? ORDER BY doj ASC ';
        $binds = array($cid,$start_date,$end_date,$workstation);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_borongan_workstation_lihat($cid,$start_date,$end_date,$workstation) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE company_id = ? AND start_date >= ? AND end_date <= ? AND workstation_id = ? ORDER BY doj ASC ';
        $binds = array($cid,$start_date,$end_date,$workstation);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_bulanan_company($cid,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? AND wages_type = 1 AND salary_month = ? ORDER BY doj ASC ';
        $binds = array($cid,$p_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_harian_company($cid,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE company_id = ? AND wages_type = 2 AND start_date = ? and end_date = ? ORDER BY doj ASC ';
        $binds = array($cid,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_borongan_company_report($cid,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE company_id = ? AND wages_type = 3 AND start_date = ? and end_date = ? ORDER BY doj ASC ';
        $binds = array($cid,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }




    public function get_comp_import_borongan_company($start_date) {

        $sql = 'SELECT * FROM view_karyawan_borongan_gramasi_import WHERE gram_tanggal = ?  ORDER BY gram_no_job DESC ';
        $binds = array($start_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }
    public function get_comp_import_borongan_company2($company_id,$workstation_id,$start_date) {

        $sql = 'SELECT * FROM view_karyawan_borongan_gramasi_import WHERE gram_tanggal = ? and company_id = ? and workstation_id = ?  ORDER BY gram_no_job DESC ';
        $binds = array($start_date,$company_id,$workstation_id);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_import_borongan_company_diterima($start_date) {

        $sql = 'SELECT * FROM xin_workstation_gram_terima WHERE gram_tanggal = ?  ORDER BY gram_no_job DESC ';
        $binds = array($start_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_borongan_company($cid,$start_date) {

        $sql = 'SELECT * FROM view_karyawan_borongan_gramasi WHERE company_id = ? AND wages_type = ? AND gram_tanggal = ?  ORDER BY gram_no_job DESC ';
        $binds = array($cid,3,$start_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_borongan_company($cid,$wid) {

        $sql = 'SELECT * FROM view_karyawan_workstation_aktif WHERE company_id = ? AND wages_type = 3 AND workstation_id = ? ORDER BY date_of_joining DESC ';
        $binds = array($cid,$wid);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_bulanan_lihat_thr($cid,$id,$p_date) {

        $sql = 'SELECT * FROM view_thr_month WHERE company_id = ? AND wages_type = ? AND tahun_thr = ?  ';
        $binds = array($cid,$id,$p_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_bulanan_lihat($cid,$id,$p_date) {

        $sql = 'SELECT * FROM view_salary_month WHERE company_id = ? AND wages_type = ? AND salary_month = ?  ';
        $binds = array($cid,$id,$p_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_harian_lihat($cid,$id,$start_date,$end_date) {

        $sql = 'SELECT * FROM view_salary_harian WHERE company_id = ? AND wages_type = ? AND start_date = ? AND end_date = ?  ';
        $binds = array($cid,$id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_borongan_lihat($cid,$id,$start_date,$end_date) {

        $sql = 'SELECT * FROM view_salary_borongan WHERE company_id = ? AND wages_type = ? AND start_date = ? AND end_date = ?  ';
        $binds = array($cid,$id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_harian_lihat_workstation($cid,$start_date,$end_date) {

        $sql = 'SELECT sum(net_salary) as besar_gaji FROM view_salary_harian WHERE company_id = ?  AND start_date >= ? AND end_date <= ?  ';
        $binds = array($cid,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_borongan_lihat_workstation($cid,$start_date,$end_date) {

        $sql = 'SELECT sum(net_salary) as besar_gaji FROM view_salary_borongan WHERE company_id = ?  AND start_date >= ? AND end_date <= ?  ';
        $binds = array($cid,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_dept_bulanan_lihat_thr($cid,$tahun_thr) {

        $sql = 'SELECT * FROM view_group_departmen_thr WHERE company_id = ? and tahun_thr = ? ';
        $binds = array($cid,$tahun_thr);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_dept_bulanan_lihat($cid,$salary_month) {

        $sql = 'SELECT * FROM view_group_departmen WHERE company_id = ? and salary_month = ? ';
        $binds = array($cid,$salary_month);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_dept_bulanan_slip($cid) {

        $sql = 'SELECT * FROM xin_departments WHERE company_id = ? ORDER BY urut ASC ';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_dept_harian_slip($cid) {

        $sql = 'SELECT * FROM xin_departments WHERE company_id = ? ORDER BY urut ASC ';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_dept_bulanan_resume($cid) {

        $sql = 'SELECT * FROM xin_departments WHERE company_id = ? ORDER BY urut ASC ';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_comp_template_dept_harian_resume($cid) {

        $sql = 'SELECT * FROM xin_departments WHERE company_id = ? ORDER BY urut ASC ';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_employee_by_designation_company_harian_group($cid,$start_date,$end_date) {

        $sql = 'SELECT * FROM view_karyawan_aktif_designation_harian WHERE company_id = ? and start_date = ? and end_date = ? group BY designation_id';
        $binds = array($cid,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_employee_by_workstation_company_borongan_group($cid,$start_date,$end_date) {

        $sql = 'SELECT * FROM view_karyawan_aktif_designation_borongan WHERE company_id = ? and start_date = ? and end_date = ? ';
        $binds = array($cid,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }


    public function get_comp_template_workstation_harian_resume($cid) {

        $sql = 'SELECT * FROM xin_workstation WHERE company_id = ? ORDER BY urut ASC ';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payslip_workstation($company_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM view_report_gaji_harian_periode_rekap WHERE company_id = ? and is_active = 1 and start_date >= ? and end_date <= ?
               GROUP BY workstation_id';

        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payslip_workstation_borongan($company_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM view_report_gaji_borongan_periode_rekap WHERE company_id = ? and is_active = 1 and start_date >= ? and end_date <= ?
               GROUP BY workstation_id';

        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payslip($company_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE company_id = ? and is_active = 1 and start_date >= ? and end_date <= ? ORDER BY start_date, end_date, employee_id ASC';
        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payslip_borongan($company_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE company_id = ? and is_active = 1 and start_date >= ? and end_date <= ? ORDER BY start_date, end_date, employee_id ASC';
        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payslip_perbulan($company_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE company_id = ? and start_date = ? and end_date = ? GROUP BY employee_id ORDER BY doj ASC';
        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payslip_perbulan_borongan($company_id,$workstation_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE company_id = ? and workstation_id = ? and start_date >= ? and end_date <= ? GROUP BY employee_id ORDER BY doj ASC';
        $binds = array($company_id,$workstation_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payslip_perbulan_cetak($company_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE company_id = ? and start_date = ? and end_date = ? GROUP BY employee_id ORDER BY doj ASC';
        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }
    public function get_employee_payslip_perbulan_cetak($employee_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE employee_id = ? and start_date = ? and end_date = ? GROUP BY employee_id ORDER BY doj ASC';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payslip_perbulan_cetak_borongan($company_id,$workstation_id,$start_date,$end_date) {

        $sql = 'SELECT *,xin_employees.first_name,xin_employees.last_name,workstation.workstation_name FROM xin_salary_payslips_borongan
        --  LEFT JOIN xin_companies as company ON xin_salary_payslips_borongan.company_id = company.company_id
         LEFT JOIN xin_employees AS xin_employees ON xin_employees.user_id = xin_salary_payslips_borongan.employee_id
         LEFT JOIN xin_workstation AS workstation ON xin_salary_payslips_borongan.workstation_id = workstation.workstation_id
        -- LEFT JOIN xin_designations as designation ON designation.designation_id = kry.designation_id
         WHERE xin_salary_payslips_borongan.company_id = ? and xin_salary_payslips_borongan.workstation_id = ? and start_date >= ? and end_date <= ? GROUP BY xin_salary_payslips_borongan.employee_id ORDER BY doj ASC';
        $binds = array($company_id,$workstation_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }




     public function get_company_payslip_periode_jumlah($company_id,$start_date,$end_date) {

        $sql = 'SELECT COUNT(*) AS jumlah FROM (SELECT count(*) as jumlah FROM xin_salary_payslips_harian WHERE company_id = ? and start_date = ? and end_date = ? GROUP BY employee_id ORDER BY doj ASC) a';
        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

     public function get_company_payslip_perbulan_jumlah($company_id,$start_date,$end_date) {

        $sql = 'SELECT COUNT(*) AS jumlah FROM (SELECT count(*) as jumlah FROM xin_salary_payslips_harian WHERE company_id = ? and start_date = ? and end_date = ? GROUP BY employee_id ORDER BY doj ASC) a';
        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_company_payslip_perbulan_jumlah_borongan($company_id,$start_date,$end_date) {

        $sql = 'SELECT COUNT(*) AS jumlah FROM (SELECT count(*) as jumlah FROM xin_salary_payslips_borongan WHERE company_id = ? and start_date >= ? and end_date <= ? GROUP BY employee_id ORDER BY doj ASC) a';
        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    public function get_comp_template_designation_harian_lihat_detail($cid) {

        $sql = 'SELECT * FROM xin_designations WHERE company_id = ? ORDER BY department_id ASC ';
        $binds = array($cid);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function check_bulan_gaji($id) {

        $sql = 'SELECT * FROM xin_payroll_date WHERE month_payroll = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }

    public function check_bulan_lembur($id) {

        $sql = 'SELECT * FROM xin_payroll_date WHERE month_payroll = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }

    public function check_company($id) {

        $sql = 'SELECT * FROM xin_companies WHERE company_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }

    public function check_jenis($id) {

        $sql = 'SELECT * FROM xin_payroll_jenis WHERE jenis_gaji_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }

    public function check_log_lembur($company_id,$wages_type,$month_year) {

        $sql = 'SELECT * FROM view_lembur_log WHERE company_id = ? AND wages_type = ? AND month_year = ?';
        $binds = array($company_id,$wages_type,$month_year);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }

    }


    public function get_employee_comp_template_lihat($cid,$id,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? and employee_id = ? AND salary_month = ? ';
        $binds = array($cid,$id,$p_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_employee_comp_template_lihat_harian($cid,$id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE company_id = ? and employee_id = ? AND start_date = ? AND end_date = ? ';
        $binds = array($cid,$id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // get total hours work > hourly template > payroll generate
    public function total_hours_worked($id,$attendance_date) {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date like ?';
        $binds = array($id, '%'.$attendance_date.'%');
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // get total hours work > hourly template > payroll generate
    public function total_hours_worked_payslip($id,$attendance_date) {
        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date like ?';
        $binds = array($id, '%'.$attendance_date.'%');
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // get advance salaries > all employee
    public function get_advance_salaries() {
      return $this->db->get("xin_advance_salaries");
    }

    // get advance salaries > single employee
    public function get_advance_salaries_single($id) {

        $sql = 'SELECT * FROM xin_advance_salaries WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // get advance salaries report
    public function get_advance_salaries_report() {
      return $this->db->query("SELECT advance_salary_id,employee_id,company_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 group by employee_id");
    }

    // get advance salaries report >> single employee > current user
    public function advance_salaries_report_single($id) {
      $sql = 'SELECT advance_salary_id,employee_id,company_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and employee_id = ? group by employee_id';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }


    // get payment history > all payslips
    public function all_payment_history() {
      return $this->db->get("xin_make_payment");
    }
    // new payroll > payslip
    public function employees_payment_history() {
        $sql = 'SELECT * FROM xin_salary_payslips_month ORDER BY doj DESC';

        $query = $this->db->query($sql);
        return $query;


    }
    // currency_converter
    public function get_currency_converter() {
      return $this->db->get("xin_currency_converter");
    }

    // get payslips of single employee
    public function get_payroll_slip($id) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE employee_id = ? and status = ? ORDER BY doj DESC';
        $binds = array($id,2);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_payslips($id) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? and status = ? ORDER BY doj DESC';
        $binds = array($id,2);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // new payroll > payslip
    public function all_employees_payment_history() {
          $sql = 'SELECT * FROM xin_salary_payslips_month ORDER BY doj DESC';
        $query = $this->db->query($sql);
        return $query;
    }
    // new payroll > payslip
    public function all_employees_payment_history_month($salary_month) {
          $sql = 'SELECT * FROM xin_salary_payslips_month WHERE salary_month = ? ORDER BY doj DESC';
        $binds = array($salary_month);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get payslip history > company
    public function get_company_payslip_history($company_id) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? ORDER BY doj DESC';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get payslip history > company
    public function get_company_thr($company_id,$tahun_thr,$tanggal_thr) {

        $sql = 'SELECT * FROM xin_thr_payslips_month WHERE company_id = ? and tahun_thr = ? and tanggal_thr = ? ORDER BY doj DESC';
        $binds = array($company_id,$tahun_thr,$tanggal_thr);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_payslip_history_month($company_id,$salary_month) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? and salary_month = ? ORDER BY doj DESC';
        $binds = array($company_id,$salary_month);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_payslip_history_month_departemen($company_id,$salary_month) {

        $sql = 'SELECT sum(net_salary) as jumlah_gaji, department_id, count(*) as jumlah_karyawan, salary_month FROM xin_salary_payslips_month WHERE company_id = ? and salary_month = ? GROUP BY department_id ORDER BY department_id DESC';
        $binds = array($company_id,$salary_month);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function cek_jumlah_gaji($emp_id,$start_date,$end_date) {

        $sql = 'SELECT sum(net_salary) as jumlah FROM xin_salary_payslips_harian WHERE employee_id = ? and start_date >= ? and end_date <= ?';



        $binds = array($emp_id,$start_date,$end_date);
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

    public function cek_jumlah_gaji_borongan($emp_id,$start_date,$end_date) {

        $sql = 'SELECT sum(net_salary) as jumlah FROM xin_salary_payslips_borongan WHERE employee_id = ? and start_date >= ? and end_date <= ?';



        $binds = array($emp_id,$start_date,$end_date);
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

    public function read_employee_kerja($emp_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE employee_id = ? and start_date >= ? and end_date <= ? ORDER BY start_date ASC';
        $binds = array($emp_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        return $query;

    }

    public function read_employee_kerja_borongan($emp_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE employee_id = ? and start_date >= ? and end_date <= ? ORDER BY start_date ASC';
        $binds = array($emp_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        return $query;

    }

    public function cek_jumlah_kerja($emp_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE employee_id = ? and start_date >= ? and end_date <= ?';



        $binds = array($emp_id,$start_date,$end_date);
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

    public function get_company_payslip_history_harian($company_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE company_id = ? and start_date = ? and end_date = ? ORDER BY doj DESC';
        $binds = array($company_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }



    public function get_company_thr_history_year($company_id,$tahun_thr,$tanggal_thr) {

        $sql = 'SELECT * FROM view_thr_month WHERE company_id = ? and tahun_thr = ? and tanggal_thr = ? ORDER BY tahun_thr ASC';
        $binds = array($company_id,$tahun_thr,$tanggal_thr);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_thr_resume_year($tahun_thr) {

        $sql = 'SELECT * FROM view_thr_month WHERE tahun_thr = ? ORDER BY tanggal_thr ASC';
        $binds = array($tahun_thr);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_payslip_history_year($company_id,$salary_year) {

        $sql = 'SELECT * FROM view_salary_month WHERE company_id = ? and tahun = ? ORDER BY salary_month ASC';
        $binds = array($company_id,$salary_year);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_company_payslip_history_year_month($salary_year) {

        $sql = 'SELECT * FROM view_salary_month WHERE salary_month = ? ORDER BY company_id ASC';
        $binds = array($salary_year);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company/location payslips
    public function get_company_location_payslips($company_id,$location_id) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? and location_id = ?';
        $binds = array($company_id,$location_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company/location payslips
    public function get_company_location_payslips_month($company_id,$location_id,$salary_month) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? and location_id = ? and salary_month = ?';
        $binds = array($company_id,$location_id,$salary_month);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company/location/departments payslips
    public function get_company_location_department_payslips($company_id,$location_id,$department_id) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? and location_id = ? and department_id = ?';
        $binds = array($company_id,$location_id,$department_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company/location/departments payslips
    public function get_company_location_department_payslips_month($company_id,$location_id,$department_id,$salary_month) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? and location_id = ? and department_id = ? and salary_month = ?';
        $binds = array($company_id,$location_id,$department_id,$salary_month);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company/location/departments payslips
    public function get_company_location_department_designation_payslips($company_id,$location_id,$department_id,$designation_id) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE company_id = ? and location_id = ? and department_id = ? and designation_id = ?';
        $binds = array($company_id,$location_id,$department_id,$designation_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    /// pay to all
    // get all employees
    public function get_all_employees() {
        $sql = 'SELECT * FROM xin_employees WHERE is_active = ?';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_all_employees_bulanan() {
        $sql = 'SELECT * FROM xin_employees WHERE wages_type =1 AND is_active = ?';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_all_employees_harian() {
        $sql = 'SELECT * FROM xin_employees WHERE wages_type =2 AND is_active = ?';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get payslip bulk > company
    public function get_company_payroll_employees_bulanan($company_id) {

        $sql = 'SELECT * FROM xin_employees WHERE wages_type = 1 AND is_active = 1 and company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    public function get_company_payroll_employees_harian($company_id, $start_date = null, $end_date = null) {
        if (!is_null($start_date) && !is_null($end_date)) {
            $get_paid_employees = $this->db->select('employee_id')
                ->from('xin_salary_payslips_harian')
                ->where('start_date', $start_date)
                ->where('end_date', $end_date)
                ->get_compiled_select();
        }

        $query = $this->db
            ->where('wages_type',  2)
            ->where('is_active', 1)
            ->where('company_id', $company_id);

        if (isset($get_paid_employees)) {
            $query = $query->where("user_id NOT IN ({$get_paid_employees})");
        }

        return $query->get('xin_employees');

        $sql = 'SELECT * FROM xin_employees WHERE wages_type =2 AND is_active = 1 and company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_payroll_employees_borongan($company_id) {

        $sql = 'SELECT * FROM xin_employees WHERE wages_type =3 AND is_active = 1 and company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // get payslip bulk > company|location
    public function get_company_location_payroll_employees($company_id,$location_id) {

        $sql = 'SELECT * FROM xin_employees WHERE is_active = 1 and company_id = ? and location_id = ?';
        $binds = array($company_id,$location_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_location_payroll_employees_bulanan($company_id,$location_id) {

        $sql = 'SELECT * FROM xin_employees WHERE wages_type =1 AND is_active = 1 and company_id = ? and location_id = ?';
        $binds = array($company_id,$location_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_location_payroll_employees_harian($company_id,$location_id) {

        $sql = 'SELECT * FROM xin_employees WHERE wages_type =1 AND is_active = 1 and company_id = ? and location_id = ?';
        $binds = array($company_id,$location_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get payslip bulk > company|location|department
    public function get_company_location_dep_payroll_employees($company_id,$location_id,$department_id) {

        $sql = 'SELECT * FROM xin_employees WHERE is_active = 1 and company_id = ? and location_id = ? and department_id = ?';
        $binds = array($company_id,$location_id,$department_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_location_dep_payroll_employees_bulanan($company_id,$location_id,$department_id) {

        $sql = 'SELECT * FROM xin_employees WHERE wages_type =1 AND is_active = 1 and company_id = ? and location_id = ? and department_id = ?';
        $binds = array($company_id,$location_id,$department_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_location_dep_payroll_employees_harian($company_id,$location_id,$department_id) {

        $sql = 'SELECT * FROM xin_employees WHERE wages_type =2 AND is_active = 1 and company_id = ? and location_id = ? and department_id = ?';
        $binds = array($company_id,$location_id,$department_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get hourly wages
    public function get_hourly_wages()
    {
      return $this->db->get("xin_hourly_templates");
    }

     public function read_template_information($id) {

        $sql = 'SELECT * FROM xin_salary_templates WHERE salary_template_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get request date details > advance salary
    public function requested_date_details($id) {

        $sql = 'SELECT * FROM `xin_advance_salaries` WHERE employee_id = ? and status = ?';
        $binds = array($id,1);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function read_hourly_wage_information($id) {

        $sql = 'SELECT * FROM xin_hourly_templates WHERE hourly_rate_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_currency_converter_information($id) {

        $sql = 'SELECT * FROM xin_currency_converter WHERE currency_converter_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get advance salaries report > view all
    public function advance_salaries_report_view($id) {
      $sql = 'SELECT advance_salary_id,company_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and employee_id= ? group by employee_id';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function read_make_payment_information($id) {

        $sql = 'SELECT * FROM xin_make_payment WHERE make_payment_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_payslip_information($id) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // Function to Delete selected record from table
    public function delete_record($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslips_month');

    }
    // THR

    public function delete_thr_bulanan($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_thr_payslips_month');

    }

    // GAJI
    public function delete_record_bulanan($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslips_month');

    }

    public function delete_record_harian($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslips_harian');

    }

    public function delete_record_borongan($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslips_borongan');

    }

    public function delete_record_bulanan_finance($id){
        $this->db->where('payslip_key', $id);
        $this->db->delete('xin_finance_transaction');

    }

    public function delete_record_harian_finance($id){
        $this->db->where('payslip_key', $id);
        $this->db->delete('xin_finance_transaction');

    }


    // Function to Delete selected record from table
    public function delete_payslip_allowances_items($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslip_allowances');

    }
    // Function to Delete selected record from table
    public function delete_payslip_commissions_items($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslip_commissions');

    }
    // Function to Delete selected record from table
    public function delete_payslip_loan_items($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslip_loan');

    }
    // Function to Delete selected record from table
    public function delete_payslip_other_payment_items($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslip_other_payments');

    }
    // Function to Delete selected record from table
    public function delete_payslip_overtime_items($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslip_overtime');

    }
    // Function to Delete selected record from table
    public function delete_payslip_statutory_deductions_items($id){
        $this->db->where('payslip_id', $id);
        $this->db->delete('xin_salary_payslip_statutory_deductions');

    }

    public function read_advance_salary_info($id) {

        $sql = 'SELECT * FROM xin_advance_salaries WHERE advance_salary_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get advance salary by employee id >paid.total
    public function get_paid_salary_by_employee_id($id) {

        $sql = 'SELECT advance_salary_id,employee_id,month_year,one_time_deduct,monthly_installment,reason,status,total_paid,is_deducted_from_salary,created_at,SUM(`xin_advance_salaries`.advance_amount) AS advance_amount FROM `xin_advance_salaries` where status=1 and employee_id=? group by employee_id';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    // get advance salary by employee id
    public function advance_salary_by_employee_id($id) {

        $sql = 'SELECT * FROM xin_advance_salaries WHERE employee_id = ? and status = ? order by advance_salary_id desc';
        $binds = array($id,1);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    // Function to add record in table
    public function add_template($data){
        $this->db->insert('xin_salary_templates', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table > advance salary
    public function add_advance_salary_payroll($data){
        $this->db->insert('xin_advance_salaries', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_hourly_wages($data){
        $this->db->insert('xin_hourly_templates', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_currency_converter($data){
        $this->db->insert('xin_currency_converter', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_monthly_payment_payslip($data){
        $this->db->insert('xin_make_payment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_hourly_payment_payslip($data){
        $this->db->insert('xin_make_payment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to Delete selected record from table
    public function delete_template_record($id){
        $this->db->where('salary_template_id', $id);
        $this->db->delete('xin_salary_templates');

    }

    // Function to Delete selected record from table
    public function delete_hourly_wage_record($id){
        $this->db->where('hourly_rate_id', $id);
        $this->db->delete('xin_hourly_templates');

    }

    // Function to Delete selected record from table
    public function delete_currency_converter_record($id){
        $this->db->where('currency_converter_id', $id);
        $this->db->delete('xin_currency_converter');

    }

    // Function to Delete selected record from table
    public function delete_advance_salary_record($id){
        $this->db->where('advance_salary_id', $id);
        $this->db->delete('xin_advance_salaries');

    }

    // Function to update record in table
    public function update_template_record($data, $id){
        $this->db->where('salary_template_id', $id);
        if( $this->db->update('xin_salary_templates',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // get all hourly templates
    public function all_hourly_templates()
    {
      $query = $this->db->query("SELECT * from xin_hourly_templates");
        return $query->result();
    }

    // get all salary tempaltes > payroll templates
    public function all_salary_templates()
    {
      $query = $this->db->query("SELECT * from xin_salary_templates");
        return $query->result();
    }

    // Function to update record in table
    public function update_hourly_wages_record($data, $id){
        $this->db->where('hourly_rate_id', $id);
        if( $this->db->update('xin_hourly_templates',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_currency_converter_record($data, $id){
        $this->db->where('currency_converter_id', $id);
        if( $this->db->update('xin_currency_converter',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > manage salary
    public function update_salary_template($data, $id){
        $this->db->where('user_id', $id);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > deduction of advance salary
    public function updated_advance_salary_paid_amount($data, $id){
        $this->db->where('employee_id', $id);
        if( $this->db->update('xin_advance_salaries',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > advance salary
    public function updated_advance_salary_payroll($data, $id){
        $this->db->where('advance_salary_id', $id);
        if( $this->db->update('xin_advance_salaries',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > empty grade status
    public function update_empty_salary_template($data, $id){
        $this->db->where('user_id', $id);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > set hourly grade
    public function update_hourlygrade_salary_template($data, $id){
        $this->db->where('user_id', $id);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > set monthly grade
    public function update_monthlygrade_salary_template($data, $id){
        $this->db->where('user_id', $id);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table > zero hourly grade
    public function update_hourlygrade_zero($data, $id){
        $this->db->where('user_id', $id);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table > zero monthly grade
    public function update_monthlygrade_zero($data, $id){
        $this->db->where('user_id', $id);
        if( $this->db->update('xin_employees',$data)) {
            return true;
        } else {
            return false;
        }
    }
    // ===========================================================================================================================
    // THR
    // ===========================================================================================================================

    public function read_make_payment_thr_check_bulanan($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_thr_payslips_month WHERE employee_id = ? and tahun_thr = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function read_make_payment_thr_bulanan($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_thr_payslips_month WHERE employee_id = ? and tahun_thr = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    // ===========================================================================================================================
    // GAJIAN
    // ===========================================================================================================================


    public function read_make_payment_payslip_check($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE employee_id = ? and salary_month = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }



    public function read_make_payment_payslip_check_bulanan($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE employee_id = ? and salary_month = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function read_make_payment_payslip_check_harian($employee_id,$start_date,$end_date) {

        if (is_array($employee_id)) {
            return $this->db
                ->where_in('employee_id', $employee_id)
                ->where('start_date', $start_date)
                ->where('end_date', $end_date)
                ->get('xin_salary_payslips_harian')
                ->result();
        }

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE employee_id = ? AND start_date = ? and end_date = ?';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function read_make_payment_payslip_check_borongan($employee_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE employee_id = ? AND start_date = ? and end_date = ?';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }


    // THR
    public function read_make_payment_thr_check_bulanan_company($employee_id,$tahun) {

        $sql = 'SELECT * FROM xin_thr_payslips_month WHERE employee_id = ? and tahun_thr = ?';
        $binds = array($employee_id,$tahun);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function read_make_payment_thr_bulanan_company($employee_id,$tahun) {

        $sql = 'SELECT * FROM xin_thr_payslips_month WHERE employee_id = ? and tahun_thr = ?';
        $binds = array($employee_id,$tahun);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    // GAJI

    public function read_make_payment_payslip_check_bulanan_company($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE employee_id = ? and salary_month = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function read_make_payment_payslip_check_harian_company($employee_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE employee_id = ? and start_date = ? and end_date = ? ';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function read_make_payment_payslip_check_borongan_company($employee_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE employee_id = ? and start_date = ? and end_date = ? ';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function read_make_payment_payslip_check_borongan_company_workstation($employee_id,$workstation_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE employee_id = ? and workstation_id = ? and start_date = ? and end_date = ? ';
        $binds = array($employee_id,$workstation_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }



    public function read_make_payment_payslip_half_month_check($employee_id,$p_date) {

        $sql = "SELECT * FROM xin_salary_payslips_month WHERE is_half_monthly_payroll = '1' and employee_id = ? and salary_month = ?";
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function read_make_payment_payslip_half_month_check_last($employee_id,$p_date) {

        $sql = "SELECT * FROM xin_salary_payslips_month WHERE is_half_monthly_payroll = '1' and employee_id = ? and salary_month = ? order by payslip_id desc";
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }
    public function read_make_payment_payslip_half_month_check_first($employee_id,$p_date) {

        $sql = "SELECT * FROM xin_salary_payslips_month WHERE is_half_monthly_payroll = '1' and employee_id = ? and salary_month = ? order by payslip_id asc";
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function read_make_payment_payslip($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE employee_id = ? and salary_month = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }



    // GAJI

    public function read_make_payment_payslip_bulanan($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE employee_id = ? and salary_month = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    public function read_make_payment_payslip_harian($employee_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE employee_id = ? AND start_date = ? and end_date = ?';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    public function read_make_payment_payslip_borongan($employee_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE employee_id = ? AND start_date = ? and end_date = ?';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    public function read_make_payment_payslip_bulanan_company($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE employee_id = ? and salary_month = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    public function read_make_payment_payslip_harian_company($employee_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE employee_id = ?  AND start_date = ? and end_date = ?';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    public function read_make_payment_payslip_borongan_company($employee_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE employee_id = ?  AND start_date = ? and end_date = ?';
        $binds = array($employee_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    public function read_make_payment_payslip_borongan_company_workstation($employee_id,$workstation_id,$start_date,$end_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE employee_id = ?  AND workstation_id = ? AND start_date = ? and end_date = ?';
        $binds = array($employee_id,$workstation_id,$start_date,$end_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    public function read_count_make_payment_payslip($employee_id,$p_date) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE employee_id = ? and salary_month = ?';
        $binds = array($employee_id,$p_date);
        $query = $this->db->query($sql, $binds);

        return $query->num_rows();
    }
    // Function to add record in table> salary payslip record
    public function add_salary_payslip($data){
        $this->db->insert('xin_salary_payslips_month', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }
    // THR
    public function add_thr_payslip_month($data){
        $this->db->insert('xin_thr_payslips_month', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    // GAJI
    public function add_salary_payslip_month($data){
        $this->db->insert('xin_salary_payslips_month', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_salary_payslip_harian($data, $batch = FALSE){
        if ($batch) {
            $this->db->insert_batch('xin_salary_payslips_harian', $data);
        } else {
            $this->db->insert('xin_salary_payslips_harian', $data);
        }

        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_produktifitas_harian($data){
        $this->db->insert('xin_workstation_gram_terima', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_produktifitas_harian_rekap($data){
        $this->db->insert('xin_workstation_gram_rekap', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function delete_produktifitas_harian_rekap($company_id, $workstation_id, $start_date, $end_date)
    {
        $this->db->where(array(
            'company_id' => $company_id,
            'workstation_id' => $workstation_id,
            'start_date >=' => $start_date,
            'end_date <=' => $end_date,
        ))->delete('xin_workstation_gram_rekap');
    }

    public function view_gram_produktifitas($employee_id, $start_date, $end_date)
    {
        return $this->db->where(array(
            'employee_id' => $employee_id,
            'gram_tanggal >=' => $start_date,
            'gram_tanggal <=' => $end_date,
        ))->get('view_xin_workstation_gram_terima');
    }

    public function add_naik_gapok($data){
        $this->db->insert('xin_salary_gapok', $data);
        if ($this->db->affected_rows() > 0) {
            $this->db->update('xin_employees', array('basic_salary' => $data['gapok_amount']), array('user_id' => $data['employe_id']));
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_produktifitas_impor($data){
        $insert = $this->db->insert_batch('xin_workstation_gram_impor', $data);
        if($insert){
            return true;
        }
    }



    public function add_salary_payslip_borongan($data){
        $this->db->insert('xin_salary_payslips_borongan', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_salary_payslip_month_draft($data){
        $this->db->insert('xin_salary_payslips_month_draft', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    // Function to add record in table> salary payslip record
    public function add_salary_payslip_allowances($data){
        $this->db->insert('xin_salary_payslip_allowances', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // 1
    public function add_salary_payslip_tunj_jabatan($data){
        $this->db->insert('xin_salary_payslip_tunj_jabatan', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // 2
    public function add_salary_payslip_tunj_produktifitasi($data){
        $this->db->insert('xin_salary_payslip_tunj_produktifitasi', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // 3
    public function add_salary_payslip_tunj_transportasi($data){
        $this->db->insert('xin_salary_payslip_tunj_transportasi', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // 4
    public function add_salary_payslip_tunj_komunikasi($data){
        $this->db->insert('xin_salary_payslip_tunj_komunikasi', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table>
    public function add_salary_payslip_commissions($data){
        $this->db->insert('xin_salary_payslip_commissions', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table>
    public function add_salary_payslip_other_payments($data){
        $this->db->insert('xin_salary_payslip_other_payments', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table>
    public function add_salary_payslip_statutory_deductions($data){
        $this->db->insert('xin_salary_payslip_statutory_deductions', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table> salary payslip record
    public function add_salary_payslip_loan($data){
        $this->db->insert('xin_salary_payslip_loan', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table> salary payslip record
    public function add_salary_payslip_overtime($data){
        $this->db->insert('xin_salary_payslip_overtime', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function read_salary_payslip_info($id) {

        $sql = 'SELECT * FROM xin_salary_payslips_month WHERE payslip_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // THR
    public function read_thr_payslip_info_key($id) {

        $sql = 'SELECT * FROM xin_thr_payslips_month WHERE payslip_key = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // GAJI
    public function read_salary_payslip_info_key($key, $employee_id = null) {
        $query = $this->db->where('payslip_key', $key);

        if (!is_null($employee_id)) {
            $query = $query->where('employee_id', $employee_id);
        }

        $query = $query->get('xin_salary_payslips_month');

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_salary_payslip_harian_info_key($id) {

        $sql = 'SELECT * FROM xin_salary_payslips_harian WHERE payslip_key = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_salary_payslip_borongan_info_key($id) {

        $sql = 'SELECT * FROM xin_salary_payslips_borongan WHERE payslip_key = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // Function to update record in table > set hourly grade
    public function update_payroll_status($data, $id){
        $this->db->where('payslip_key', $id);
        if( $this->db->update('xin_salary_payslips_month',$data)) {
            return true;
        } else {
            return false;
        }
    }
}

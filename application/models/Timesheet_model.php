<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Timesheet_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db2 = $this->load->database('finger', TRUE);
    }

    // get office shifts
    public function get_office()
    {

        $sql = 'SELECT * FROM xin_office_shift ORDER BY start_date DESC';
        $query = $this->db->query($sql);
        return $query;
    }

    public function get_office_reguler()
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE jenis = 1';
        $query = $this->db->query($sql);
        return $query;
    }

    // get company offshifts
    public function get_company_reguler($company_id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE company_id = ? AND jenis = 1';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_employee_reguler_office($id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return null;
        }
    }

    public function get_office_shifts()
    {
        $sql = 'SELECT * FROM xin_office_shift WHERE jenis = 2 order by payroll_id desc ';
        $query = $this->db->query($sql);
        return $query;
    }

    public function get_company_shifts($company_id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE company_id = ? AND jenis = 2';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    public function get_employee_shift_office($id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return null;
        }
    }

    // get all tasks
    public function get_tasks()
    {
        return $this->db->get("xin_tasks");
    }

    public function get_employee_leaves($id)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // get all project tasks
    public function get_project_tasks($id)
    {
        $sql = 'SELECT * FROM xin_tasks WHERE project_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get all project variations
    public function get_project_variations($id)
    {
        $sql = 'SELECT * FROM xin_project_variations WHERE project_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // check if check-in available
    public function attendance_first_in_check($employee_id, $attendance_date)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? limit 1';
        $binds = array($employee_id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // ==============================================================================================================

    public function read_attendance_info($employee_id, $attendance_date)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ?';
        $binds = array($employee_id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }



    // ==============================================================================================================
    // Masuk
    public function attendance_first_in_check_new($employee_nik, $attendance_date)
    {

        $sql = 'SELECT * FROM view_absen_masuk WHERE pin = ? and attendance_date = ?';
        $binds = array($employee_nik, $attendance_date);
        $query = $this->db2->query($sql, $binds);

        return $query;
    }
    public function attendance_first_in_check_by_date($attendance_date)
    {

        $sql = 'SELECT pin,nik,attendance_date,MIN(clock_in) AS clock_in FROM view_absen_masuk WHERE  attendance_date = ? GROUP BY pin,nik,attendance_date';
        $binds = array($attendance_date);
        $query = $this->db2->query($sql, $binds);

        return $query->result();
    }
    public function attendance_first_out_check_by_date($attendance_date)
    {

        $sql = 'SELECT pin,nik,attendance_date,MAX(clock_out) AS clock_out FROM view_absen_pulang WHERE  attendance_date = ? GROUP BY pin,nik,attendance_date';
        $binds = array($attendance_date);
        $query = $this->db2->query($sql, $binds);

        return $query->result();
    }

    public function attendance_first_in_new($employee_nik, $attendance_date)
    {

        $sql = 'SELECT * FROM view_absen_masuk WHERE pin = ? and attendance_date = ? limit 1';
        $binds = array($employee_nik, $attendance_date);
        $query = $this->db2->query($sql, $binds);

        return $query->result();
    }

    // Pulang
    public function attendance_first_out_check_new_shift($employee_nik, $attendance_date)
    {

        $sql = 'SELECT * FROM view_absen_pulang_shift WHERE pin = ? and attendance_date = ?  limit 1';
        $binds = array($employee_nik, $attendance_date);
        $query = $this->db2->query($sql, $binds);



        return $query;
    }

    public function attendance_first_out_check_new($employee_nik, $attendance_date)
    {

        $sql = 'SELECT * FROM view_absen_pulang WHERE pin = ? and attendance_date = ?  limit 1';
        $binds = array($employee_nik, $attendance_date);
        $query = $this->db2->query($sql, $binds);



        return $query;
    }

    public function attendance_first_out_check_new_case($employee_nik, $attendance_date, $clock_in)
    {

        $sql = 'SELECT * FROM view_absen_pulang WHERE pin = ? and attendance_date = ?  and attendance_time >= ? limit 1';
        $binds = array($employee_nik, $attendance_date, $clock_in);
        $query = $this->db2->query($sql, $binds);



        return $query;
    }

    public function attendance_first_out_new_shift($employee_nik, $attendance_date)
    {

        $sql = 'SELECT * FROM view_absen_pulang_shift WHERE pin = ? and attendance_date = ? limit 1';
        $binds = array($employee_nik, $attendance_date);
        $query = $this->db2->query($sql, $binds);

        return $query->result();
    }

    public function attendance_first_out_new($employee_nik, $attendance_date)
    {

        $sql = 'SELECT * FROM view_absen_pulang WHERE pin = ? and attendance_date = ? limit 1';
        $binds = array($employee_nik, $attendance_date);
        $query = $this->db2->query($sql, $binds);

        return $query->result();
    }

    public function attendance_first_out_new_case($employee_nik, $attendance_date, $clock_in)
    {

        $sql = 'SELECT * FROM view_absen_pulang WHERE pin = ? and attendance_date = ? and attendance_time >= ? limit 1';
        $binds = array($employee_nik, $attendance_date, $clock_in);
        $query = $this->db2->query($sql, $binds);

        return $query->result();
    }

    // get user attendance
    public function attendance_time_check($employee_id)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ?';
        $binds = array($employee_id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    // check if check-in available
    public function attendance_first_in($employee_id, $attendance_date)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ?';
        $binds = array($employee_id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }



    public function attendance_tanggal($location_id, $attendance_date)
    {

        $sql = 'SELECT * FROM view_xin_attendance_time_recap WHERE location_id = ? and bulan = ? ';
        $binds = array($location_id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }


    // check if check-out available
    public function attendance_first_out_check($employee_id, $attendance_date)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? order by time_attendance_id desc limit 1';
        $binds = array($employee_id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }



    // get leave types
    public function all_leave_types()
    {
        $query = $this->db->get("xin_leave_type");
        return $query->result();
    }

    public function all_sick_types()
    {
        $query = $this->db->get("xin_sick_type");
        return $query->result();
    }

    // get company holidays
    public function get_company_holidays($company_id)
    {

        $sql = 'SELECT * FROM xin_holidays WHERE company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function get_company_works($company_id)
    {

        $sql = 'SELECT * FROM xin_works WHERE company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // filter company holidays
    public function filter_company_holidays($company_id)
    {

        $sql = 'SELECT * FROM xin_holidays WHERE company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function filter_company_works($company_id)
    {

        $sql = 'SELECT * FROM xin_works WHERE company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // filter company|status holidays
    public function filter_company_publish_holidays($company_id, $is_publish)
    {

        $sql = 'SELECT * FROM xin_holidays WHERE company_id = ? and is_publish = ?';
        $binds = array($company_id, $is_publish);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function filter_company_publish_works($company_id, $is_publish)
    {

        $sql = 'SELECT * FROM xin_works WHERE company_id = ? and is_publish = ?';
        $binds = array($company_id, $is_publish);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // filter company|status holidays
    public function filter_notcompany_publish_holidays($is_publish)
    {

        $sql = 'SELECT * FROM xin_holidays WHERE is_publish = ?';
        $binds = array($is_publish);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    public function filter_notcompany_publish_works($is_publish)
    {

        $sql = 'SELECT * FROM xin_works WHERE is_publish = ?';
        $binds = array($is_publish);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company leaves
    public function get_company_leaves($company_id)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get multi company leaves
    public function get_multi_company_leaves($company_ids)
    {

        $sql = 'SELECT * FROM xin_leave_applications where company_id IN ?';
        $binds = array($company_ids);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company tasks
    public function get_company_tasks($company_id)
    {

        $sql = 'SELECT * FROM xin_tasks WHERE company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get employee tasks
    public function get_employee_tasks($id)
    {

        $sql = "SELECT * FROM `xin_tasks` where assigned_to like '%$id,%' or assigned_to like '%,$id%' or assigned_to = '$id'";
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // check if check-out available
    public function attendance_first_out($employee_id, $attendance_date)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? order by time_attendance_id desc limit 1';
        $binds = array($employee_id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    // get total hours work > attendance
    public function total_hours_worked_attendance($id, $attendance_date)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? and total_work != ""';
        $binds = array($id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get total rest > attendance
    public function total_rest_attendance($id, $attendance_date)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? and attendance_date = ? and total_rest != ""';
        $binds = array($id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // check if holiday available
    public function holiday_date_check($attendance_date)
    {

        $sql = 'SELECT * FROM xin_holidays WHERE start_date = ? AND end_date = ? ';
        $binds = array($attendance_date, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    public function work_date_check($attendance_date)
    {

        $sql = 'SELECT * FROM xin_works WHERE start_date = ? AND end_date = ? ';
        $binds = array($attendance_date, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }
    public function get_sicks()
    {
        return $this->db->get("xin_sick_applications");
    }

    // get all leaves
    public function get_leaves()
    {
        return $this->db->get("xin_leave_applications");
    }

    // get company leaves
    public function filter_company_leaves($company_id)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ?';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company|employees leaves
    public function filter_company_employees_leaves($company_id, $employee_id)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and employee_id = ?';
        $binds = array($company_id, $employee_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company|employees leaves
    public function filter_company_employees_status_leaves($company_id, $employee_id, $status)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and employee_id = ? and status = ?';
        $binds = array($company_id, $employee_id, $status);
        $query = $this->db->query($sql, $binds);
        return $query;
    }
    // get company|employees leaves
    public function filter_company_only_status_leaves($company_id, $status)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE company_id = ? and status = ?';
        $binds = array($company_id, $status);
        $query = $this->db->query($sql, $binds);
        return $query;
    }


    // check if holiday available
    public function holiday_date($attendance_date)
    {

        $sql = 'SELECT * FROM xin_holidays WHERE (start_date between start_date and end_date) or (start_date = ? or end_date = ?) limit 1';
        $binds = array($attendance_date, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }
    public function work_date($attendance_date)
    {

        $sql = 'SELECT * FROM xin_works WHERE (start_date between start_date and end_date) or (start_date = ? or end_date = ?) limit 1';
        $binds = array($attendance_date, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }


    // get all holidays
    public function get_holidays()
    {
        $sql = 'SELECT * FROM xin_holidays ORDER BY start_date ASC';
        $query = $this->db->query($sql);
        return $query;
    }

    // get all holidays
    public function get_works()
    {
        $sql = 'SELECT * FROM xin_payroll_date ORDER BY start_date ASC';
        $query = $this->db->query($sql);
        return $query;
    }

    public function get_bulan_periode()
    {
        $sql = 'SELECT * FROM view_bulan_periode ORDER BY bulan DESC';
        $query = $this->db->query($sql);
        return $query;
    }

    public function get_bulan_periode_resume($bulan)
    {

        $sql = "SELECT * FROM view_bulan_periode WHERE month_payroll = ? ";

        $binds = array($bulan);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();


        return $query;
    }

    public function get_periode()
    {
        $sql = 'SELECT * FROM xin_payroll_date_periode ORDER BY start_date, month_payroll DESC';
        $query = $this->db->query($sql);
        return $query;
    }

    public function get_skala_upah()
    {
        $sql = 'SELECT * FROM xin_workstation_skala_upah ORDER BY skala_id DESC';
        $query = $this->db->query($sql);
        return $query;
    }

    // get all holidays>calendar
    public function get_holidays_calendar()
    {

        $sql = 'SELECT * FROM xin_holidays WHERE is_publish = ?';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get all holidays>calendar
    public function get_works_calendar()
    {

        $sql = 'SELECT * FROM xin_works WHERE is_publish = ?';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get all leaves>calendar
    public function get_leaves_request_calendar()
    {
        return $query = $this->db->query("SELECT * from xin_leave_applications");
    }

    // get all sick>calendar
    public function get_sick_request_calendar()
    {
        return $query = $this->db->query("SELECT * from xin_sick_applications");
    }

    // check if leave available

    public function cek_status_kehadiran($emp_id, $attendance_date)
    {
        $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? AND attendance_date = ? ';
        $binds = array($emp_id, $attendance_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function cek_multi_status_kehadiran($emp_ids, $attendance_dates)
    {
        $query = $this->db
            ->where_in('employee_id', $emp_ids)
            ->where_in('attendance_date', $attendance_dates)
            ->order_by('date_of_joining DESC, employee_id ASC, attendance_date ASC')
            ->group_by(['employee_id', 'attendance_date'])
            ->get('xin_attendance_time');
//   print_r($this->db->last_query());
        // $sql = 'SELECT * FROM xin_attendance_time WHERE employee_id = ? AND attendance_date = ? ';
        // $binds = array($emp_id, $attendance_date);
        // $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function cek_status_produktifitas($emp_id, $gram_tanggal)
    {

        $sql = 'SELECT sum(gram_biaya) as jumlah_biaya FROM xin_workstation_gram_terima WHERE employee_id = ? AND gram_tanggal = ? GROUP By gram_tanggal,employee_id ';
        $binds = array($emp_id, $gram_tanggal);
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

    public function cek_status_kehadiran_lembur($emp_id, $overtime_date)
    {

        $sql = 'SELECT * FROM xin_salary_overtime WHERE employee_id = ? AND overtime_date = ? ';
        $binds = array($emp_id, $overtime_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function cek_jumlah_lembur($emp_id, $overtime_date)
    {

        $sql = 'SELECT sum(overtime_hours_total) as jumlah FROM xin_salary_overtime WHERE employee_id = ? AND overtime_date = ? ';
        $binds = array($emp_id, $overtime_date);
        $query = $this->db->query($sql, $binds);


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }


    public function cek_jumlah_cuti_bulan($emp_id, $month_year, $leave_year)
    {

        $sql = 'SELECT sum(cuti) as jumlah FROM view_hris_jumlah_cuti_cek WHERE employee_id = ? AND bulan = ? AND tahun = ? ';
        $binds = array($emp_id, $month_year, $leave_year);
        $query = $this->db->query($sql, $binds);


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function cek_detail_cuti_bulan($emp_id, $month_year, $leave_year)
    {

        $sql = 'SELECT * FROM view_hris_jumlah_cuti_cek WHERE employee_id = ? AND bulan = ? AND tahun = ? ';
        $binds = array($emp_id, $month_year, $leave_year);
        $query = $this->db->query($sql, $binds);


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function cek_jumlah_cuti_tahun($emp_id, $leave_year)
    {

        $sql = 'SELECT sum(cuti) as jumlah FROM view_hris_jumlah_cuti_cek WHERE employee_id = ? AND tahun = ? ';
        $binds = array($emp_id, $leave_year);
        $query = $this->db->query($sql, $binds);


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function hitung_jumlah_status_kehadiran($emp_id, $start_date, $end_date, $jenis)
    {
        $query = $this->db
            ->select('attendance_status_simbol, COUNT(*) AS jumlah')
            ->where('employee_id', $emp_id)
            ->where('attendance_date >=', $start_date)
            ->where('attendance_date <=', $end_date)
            ->group_by('employee_id');

        // $sql = 'SELECT count(*) as jumlah FROM xin_attendance_time WHERE employee_id = ? and attendance_date >= ? and attendance_date <= ? and attendance_status_simbol = ?';
        // $binds = array($emp_id, $start_date, $end_date, $jenis);

        if (is_array($jenis)) {
            $query = $query->where_in('attendance_status_simbol', $jenis)->group_by('attendance_status_simbol');
        } else {
            $query = $query->where('attendance_status_simbol', $jenis);
        }

        return $query->get('xin_attendance_time')->result();
    }

    public function hitung_multi_jumlah_status_kehadiran($emp_id, $start_date, $end_date, $jenis)
    {
        $emp_id = !is_array($emp_id) ? [$emp_id] : $emp_id;
        $query = $this->db
            ->select('employee_id, attendance_status_simbol, COUNT(*) AS jumlah')
            ->where_in('employee_id', $emp_id)
            ->where('attendance_date >=', $start_date)
            ->where('attendance_date <=', $end_date)
            ->group_by('employee_id');

        if (is_array($jenis)) {
            $query = $query->where_in('attendance_status_simbol', $jenis)->group_by('attendance_status_simbol');
        } else {
            $query = $query->where('attendance_status_simbol', $jenis);
        }

        return $query->get('xin_attendance_time')->result();
    }



    public function hitung_jumlah_status_kehadiran_libur($emp_id, $start_date, $end_date, $jenis)
    {

        $sql = 'SELECT count(*) as jumlah FROM xin_attendance_time WHERE flag ="L" and  employee_id = ? and attendance_date >= ? and attendance_date <= ? and attendance_status_simbol = ?';
        $binds = array($emp_id, $start_date, $end_date, $jenis);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    /**
     * Hitung jumlah lembur di hari libur
     */
    public function hitung_multi_jumlah_lembur_libur($emp_id, $start_date, $end_date)
    {
        $emp_id = !is_array($emp_id) ? [$emp_id] : $emp_id;
        $query = $this->db
            ->select('employee_id, attendance_status_simbol, COUNT(*) AS jumlah')
            ->where_in('employee_id', $emp_id)
            ->where('attendance_date >=', $start_date)
            ->where('attendance_date <=', $end_date)
            ->where('attendance_status_simbol', 'O')
            ->where('attendance_jadwal', 'Libur')
            ->group_by('employee_id');

        return $query->get('xin_attendance_time')->result();
    }

    public function hitung_jumlah_status_produktifitas($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(gram_biaya) as jumlah FROM xin_workstation_gram_terima WHERE employee_id = ? and gram_tanggal >= ? and gram_tanggal <= ? ';
        $binds = array($emp_id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    public function hitung_jumlah_produktifitas_job($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(jumlah) as jumlah_hari FROM (SELECT count(*) as jumlah FROM xin_workstation_gram_terima WHERE employee_id = ? and gram_tanggal >= ? and gram_tanggal <= ? GROUP BY gram_tanggal, employee_id) a ';
        $binds = array($emp_id, $start_date, $end_date);
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

    public function hitung_jumlah_produktifitas_kehadiran($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT count(*) as jumlah_hari FROM (SELECT count(*) as jumlah FROM xin_workstation_gram_terima WHERE employee_id = ? and gram_tanggal >= ? and gram_tanggal <= ? GROUP BY gram_tanggal, employee_id) a ';
        $binds = array($emp_id, $start_date, $end_date);
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

    public function hitung_jumlah_produktifitas_gram($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(jumlah) as jumlah_gram FROM (SELECT sum(gram_nilai) as jumlah FROM xin_workstation_gram_terima WHERE employee_id = ? and gram_tanggal >= ? and gram_tanggal <= ? GROUP BY gram_tanggal, employee_id) a ';
        $binds = array($emp_id, $start_date, $end_date);
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

    public function hitung_jumlah_produktifitas_biaya($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(jumlah) as jumlah_biaya FROM (SELECT sum(gram_biaya) as jumlah FROM xin_workstation_gram_terima WHERE employee_id = ? and gram_tanggal >= ? and gram_tanggal <= ? GROUP BY gram_tanggal, employee_id ) a ';
        $binds = array($emp_id, $start_date, $end_date);
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

    public function tampil_produktifitas_rekap($emp_id, $start_date, $end_date)
    {
        $query = $this->db->where(array(
            'employee_id' => $emp_id,
            'start_date >=' => $start_date,
            'end_date <=' => $end_date,
        ))->get('xin_workstation_gram_rekap');


        // $sql = 'SELECT * FROM xin_workstation_gram_rekap WHERE employee_id = ? and start_date = ? and end_date = ?  ';
        // $binds = array($emp_id, $start_date, $end_date);
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

    public function get_produktifitas_rekap($emp_id, $start_date, $end_date)
    {
        $sql = 'SELECT count(gram_tanggal) as rekap_day, IFNULL(SUM(gram_biaya),0) as rekap_amount, IFNULL(SUM(gram_nilai),0) as rekap_gram , IFNULL(SUM(insentif),0) as rekap_insentif FROM xin_workstation_gram_terima 
        WHERE employee_id = ? and gram_tanggal >= ? and gram_tanggal <= ? ';
        $binds = array($emp_id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        
        return $query->result();
    }


    public function hitung_jumlah_produktifitas_biaya_jumlah($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(jumlah) as jumlah_biaya FROM (SELECT sum(gram_jumlah) as jumlah FROM xin_workstation_gram_terima WHERE employee_id = ? and gram_tanggal >= ? and gram_tanggal <= ? GROUP BY gram_tanggal, employee_id ) a ';
        $binds = array($emp_id, $start_date, $end_date);
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

    public function hitung_jumlah_status_kehadiran_setengah_hari($emp_id, $start_date, $end_date, $jenis)
    {

        $sql = 'SELECT sum(jenis) as jumlah FROM xin_attendance_time WHERE employee_id = ? and attendance_date >= ? and attendance_date <= ? and attendance_status_simbol = ?';
        $binds = array($emp_id, $start_date, $end_date, $jenis);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    public function hitung_jumlah_terlambat_kehadiran($emp_id, $start_date, $end_date)
    {
        $query = $this->db
            ->select('employee_id, SUM(time_late) as jumlah')
            ->where('attendance_date >=', $start_date)
            ->where('attendance_date <=', $end_date)
            ->group_by('employee_id');

        if (is_array($emp_id)) {
            $query = $query->where_in('employee_id', $emp_id);
        } else {
            $query = $query->where('employee_id', $emp_id);
        }

        return $query->get('xin_attendance_time')->result();
    }

    public function hitung_jumlah_jam_lembur($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(overtime_hours_total) as jumlah FROM xin_salary_overtime WHERE employee_id = ? and overtime_date >= ? and overtime_date <= ? ';
        $binds = array($emp_id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    public function hitung_jumlah_jam_lembur_1($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(overtime_hours) as jumlah FROM xin_salary_overtime WHERE employee_id = ? and overtime_date >= ? and overtime_date <= ? ';
        $binds = array($emp_id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    public function hitung_jumlah_jam_lembur_2($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(overtime_hours_next) as jumlah FROM xin_salary_overtime WHERE employee_id = ? and overtime_date >= ? and overtime_date <= ? ';
        $binds = array($emp_id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    public function hitung_jumlah_biaya_lembur_1($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(overtime_rate) as jumlah FROM xin_salary_overtime WHERE employee_id = ? and overtime_date >= ? and overtime_date <= ? ';
        $binds = array($emp_id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    public function hitung_jumlah_biaya_lembur_2($emp_id, $start_date, $end_date)
    {

        $sql = 'SELECT sum(overtime_rate_next) as jumlah FROM xin_salary_overtime WHERE employee_id = ? and overtime_date >= ? and overtime_date <= ? ';
        $binds = array($emp_id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    public function hitung_jumlah_status_lembur_tanggal($emp_id, $overtime_date)
    {

        $sql = 'SELECT count(*) as jumlah FROM xin_salary_overtime WHERE employee_id = ? and overtime_date = ? ';
        $binds = array($emp_id, $overtime_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    // check if leave available
    public function leave_date_check($emp_id, $attendance_date)
    {

        $sql = 'SELECT * from xin_leave_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
        $binds = array($emp_id, $attendance_date, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // check if sick available
    public function sick_date_check($emp_id, $attendance_date)
    {

        $sql   = 'SELECT * FROM xin_sick_applications  where  (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
        $binds = array($emp_id, $attendance_date, $attendance_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query;
    }

    // check if leave available
    public function leave_date($emp_id, $attendance_date)
    {

        $sql = 'SELECT * from xin_leave_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
        $binds = array($emp_id, $attendance_date, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    // check if sick available
    public function sick_date($emp_id, $attendance_date)
    {

        $sql = 'SELECT * from xin_sick_applications where (from_date between from_date and to_date) and employee_id = ? or from_date = ? and to_date = ? limit 1';
        $binds = array($emp_id, $attendance_date, $attendance_date);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    // get total number of leave > employee
    public function count_total_leaves($leave_type_id, $employee_id)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ? and leave_type_id = ? and status = ?';
        $binds = array($employee_id, $leave_type_id, 2);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    // get total number of sick > employee
    public function count_total_sicks($sick_type_id, $employee_id)
    {

        $sql = 'SELECT * FROM xin_sick_applications WHERE employee_id = ? and sick_type_id = ? and status = ?';
        $binds = array($employee_id, $sick_type_id, 2);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }




    // get payroll templates > NOT USED
    public function attendance_employee_with_date($emp_id, $attendance_date)
    {

        $sql = 'SELECT * FROM xin_attendance_time where attendance_date = ? and employee_id = ?';
        $binds = array($attendance_date, $emp_id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get record of office shift > by id
    public function read_office_shift_information($id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ?  ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_office_jadwal_information_reguler($id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ? AND jenis = 1  ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_office_jadwal_information_shift($id)
    {

        $sql = 'SELECT * FROM xin_office_shift WHERE office_shift_id = ? AND jenis = 2  ';
        $binds = array($id);
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

    public function read_office_jadwal_jam_shift($id)
    {

        $sql = 'SELECT * FROM xin_office_shift_jam WHERE kode = ? ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);


        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get record of leave > by id
    public function read_leave_information($id)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE leave_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get leave type by id
    public function read_leave_type_information($id)
    {

        $sql = 'SELECT * FROM xin_leave_type WHERE leave_type_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get record of leave > by id
    public function read_sick_information($id)
    {

        $sql = 'SELECT * FROM xin_sick_applications WHERE sick_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get sick type by id
    public function read_sick_type_information($id)
    {

        $sql = 'SELECT * FROM xin_sick_type WHERE sick_type_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function add_employee_attendance_rekap_reguler($data)
    {
        $table_name = "xin_attendance_time_rekap";

        // is multiple data
        if (isset($data[0]) && is_array($data[0])) {
            $this->db->insert_batch($table_name, $data);
        } else {
            $this->db->insert($table_name, $data);
        }

        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_employee_attendance_rekap_harian_reguler($data)
    {
        $table_name = "xin_attendance_time_rekap_harian";
        // is multiple data
        if (isset($data[0]) && is_array($data[0])) {
            $this->db->insert_batch($table_name, $data);
        } else {
            $this->db->insert($table_name, $data);
        }
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_employee_attendance_rekap_borongan_reguler($data)
    {
        // $this->db->insert('xin_attendance_time_rekap_borongan', $data);
        // if ($this->db->affected_rows() > 0) {
        //     return $this->db->insert_id();
        // } else {
        //     return false;
        // }
        $table_name = "xin_attendance_time_rekap_borongan";

        // is multiple data
        if (isset($data[0]) && is_array($data[0])) {
            $this->db->insert_batch($table_name, $data);
        } else {
            $this->db->insert($table_name, $data);
        }
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_employee_lembur_rekap_reguler($data)
    {
        $this->db->insert('xin_attendance_time_rekap_lembur', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function add_employee_lembur_rekap_reguler_log($data)
    {
        $this->db->insert('xin_attendance_time_rekap_lembur_log', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function update_bulan_gaji($data, $id)
    {
        $this->db->where('month_payroll', $id);
        if ($this->db->update('xin_payroll_date', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_periode_gaji($data, $id)
    {
        $this->db->where('month_payroll', $id);
        if ($this->db->update('xin_payroll_date_periode', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_employee_attendance($data)
    {
        $this->db->insert('xin_attendance_time', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_leave_record($data)
    {
        $this->db->insert('xin_leave_applications', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_sick_record($data)
    {
        $this->db->insert('xin_sick_applications', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Function to add record in table
    public function add_task_record($data)
    {
        $this->db->insert('xin_tasks', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_project_variations($data)
    {
        $this->db->insert('xin_project_variations', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_office_shift_record($data)
    {
        $this->db->insert('xin_office_shift', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to add record in table
    public function add_holiday_record($data)
    {
        $this->db->insert('xin_holidays', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function add_work_record($data)
    {
        $this->db->insert('xin_payroll_date', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function add_periode_record($data)
    {
        $this->db->insert('xin_payroll_date_periode', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get record of task by id
    public function read_task_information($id)
    {

        $sql = 'SELECT * FROM xin_tasks WHERE task_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }
    // get record of variation by id
    public function read_variation_information($id)
    {

        $sql = 'SELECT * FROM xin_project_variations WHERE variation_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get record of holiday by id
    public function read_holiday_information($id)
    {

        $sql = 'SELECT * FROM xin_holidays WHERE holiday_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_work_information($id)
    {

        $sql = 'SELECT * FROM xin_payroll_date WHERE payroll_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function read_periode_information($id)
    {

        $sql = 'SELECT * FROM xin_payroll_date_periode WHERE payroll_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // get record of attendance by id
    public function read_attendance_information($id)
    {

        $sql = 'SELECT * FROM xin_attendance_time WHERE time_attendance_id = ? limit 1';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    // Function to Delete selected record from table
    public function delete_attendance_record($id)
    {
        $this->db->where('time_attendance_id', $id);
        $this->db->delete('xin_attendance_time');
    }

    // Function to Delete selected record from table
    public function delete_task_record($id)
    {
        $this->db->where('task_id', $id);
        $this->db->delete('xin_tasks');
    }
    // Function to Delete selected record from table
    public function delete_variation_record($id)
    {
        $this->db->where('variation_id', $id);
        $this->db->delete('xin_project_variations');
    }

    // Function to Delete selected record from table
    public function delete_holiday_record($id)
    {
        $this->db->where('holiday_id', $id);
        $this->db->delete('xin_holidays');
    }

    public function delete_work_record($id)
    {
        $this->db->where('payroll_id', $id);
        $this->db->delete('xin_payroll_date');
    }

    public function delete_periode_record($id)
    {
        $this->db->where('payroll_id', $id);
        $this->db->delete('xin_payroll_date_periode');
    }

    // Function to Delete selected record from table
    public function delete_shift_record($id)
    {
        $this->db->where('office_shift_id', $id);
        $this->db->delete('xin_office_shift');
    }

    // Function to Delete selected record from table
    public function delete_leave_record($id)
    {
        $this->db->where('leave_id', $id);
        $this->db->delete('xin_leave_applications');
    }

    // Function to update record in table
    public function update_task_record($data, $id)
    {
        $this->db->where('task_id', $id);
        if ($this->db->update('xin_tasks', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // Function to update record in table
    public function update_project_variations($data, $id)
    {
        $this->db->where('variation_id', $id);
        if ($this->db->update('xin_project_variations', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_leave_record($data, $id)
    {
        $this->db->where('leave_id', $id);
        if ($this->db->update('xin_leave_applications', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_sick_record($data, $id)
    {
        $this->db->where('sick_id', $id);
        if ($this->db->update('xin_sick_applications', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_holiday_record($data, $id)
    {
        $this->db->where('holiday_id', $id);
        if ($this->db->update('xin_holidays', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_work_record($data, $id)
    {
        $this->db->where('payroll_id', $id);
        if ($this->db->update('xin_payroll_date', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update_periode_record($data, $id)
    {
        $this->db->where('payroll_id', $id);
        if ($this->db->update('xin_payroll_date_periode', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_attendance_record($data, $id)
    {
        $this->db->where('time_attendance_id', $id);
        if ($this->db->update('xin_attendance_time', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_shift_record($data, $id)
    {
        $this->db->where('office_shift_id', $id);
        if ($this->db->update('xin_office_shift', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_default_shift_record($data, $id)
    {
        $this->db->where('office_shift_id', $id);
        if ($this->db->update('xin_office_shift', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function update_default_shift_zero($data)
    {
        $this->db->where("office_shift_id!=''");
        if ($this->db->update('xin_office_shift', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // Function to update record in table
    public function assign_task_user($data, $id)
    {
        $this->db->where('task_id', $id);
        if ($this->db->update('xin_tasks', $data)) {
            return true;
        } else {
            return false;
        }
    }

    // get comments
    public function get_comments($id)
    {

        $sql = 'SELECT * FROM xin_tasks_comments WHERE task_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // get comments
    public function get_attachments($id)
    {

        $sql = 'SELECT * FROM xin_tasks_attachment WHERE task_id = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // Function to add record in table > add comment
    public function add_comment($data)
    {
        $this->db->insert('xin_tasks_comments', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Function to Delete selected record from table
    public function delete_comment_record($id)
    {
        $this->db->where('comment_id', $id);
        $this->db->delete('xin_tasks_comments');
    }

    // Function to Delete selected record from table
    public function delete_attachment_record($id)
    {
        $this->db->where('task_attachment_id', $id);
        $this->db->delete('xin_tasks_attachment');
    }

    // Function to add record in table > add attachment
    public function add_new_attachment($data)
    {
        $this->db->insert('xin_tasks_attachment', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // check user attendance
    public function check_user_attendance()
    {
        $today_date = date('Y-m-d');
        $session = $this->session->userdata('username');
        $sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and `attendance_date` = ? order by time_attendance_id desc limit 1';
        $binds = array($session['user_id'], $today_date);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // check user attendance
    public function check_user_attendance_clockout()
    {
        $today_date = date('Y-m-d');
        $session = $this->session->userdata('username');
        $sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? and `attendance_date` = ? and clock_out = ? order by time_attendance_id desc limit 1';
        $binds = array($session['user_id'], $today_date, '');
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    //  set clock in- attendance > user
    public function add_new_attendance($data)
    {
        $this->db->insert('xin_attendance_time', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // get last user attendance
    public function get_last_user_attendance()
    {

        $session = $this->session->userdata('username');
        $sql = 'SELECT * FROM xin_attendance_time where `employee_id` = ? order by time_attendance_id desc limit 1';
        $binds = array($session['user_id']);
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    // get last user attendance > check if loged in-
    public function attendance_time_checks($id)
    {

        $session = $this->session->userdata('username');
        $sql = 'SELECT * FROM xin_attendance_time WHERE `employee_id` = ? and clock_out = ? order by time_attendance_id desc limit 1';
        $binds = array($id, '');
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    // Function to update record in table > update attendace.
    public function update_attendance_clockedout($data, $id)
    {
        //$this->db->where("time_attendance_id!=''");
        $this->db->where('time_attendance_id', $id);
        if ($this->db->update('xin_attendance_time', $data)) {
            return true;
        } else {
            return false;
        }
    }
    // get employees > active
    public function get_xin_employees_cuti($id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif_cuti WHERE company_id = ? AND wages_type = 1 AND is_active = 1 order by date_of_joining desc ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_company()
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE  office_id = "R" and wages_type = 1 and is_active = ? order by date_of_joining desc ';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }







    public function get_xin_employees_bulanan_company($id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = 1  and company_id = ? and is_active = 1 order by date_of_joining desc ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }



    public function get_xin_employees_harian_shift()
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE office_id = "S" and wages_type = 2 and is_active = ? order by date_of_joining desc ';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }


    public function get_xin_employees_harian_company($id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE office_id = "R" and wages_type = 2 and company_id = ? and is_active = 1 order by date_of_joining desc ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    // =============================================================================================================================================
    // BORONGAN
    // =============================================================================================================================================
    public function get_xin_employees_borongan_workstation_birdnest($workstation_id)
    {

        $sql = 'SELECT * FROM view_karyawan_workstation_aktif WHERE company_id = 1 AND workstation_id = ? and  office_id = "R" and wages_type = 3 and is_active = 1 order by date_of_joining desc ';
        $binds = array($workstation_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    // =============================================================================================================================================
    // HARIAN
    // =============================================================================================================================================
    public function get_xin_employees_harian_department_birdnest($department_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = 1 AND department_id = ? and  office_id = "R" and wages_type = 2 and is_active = 1 order by date_of_joining desc ';
        $binds = array($department_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    // =============================================================================================================================================
    // REGULER
    // =============================================================================================================================================
    public function get_xin_employees_bulanan_department_birdnest($department_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = 1 AND department_id = ? and  office_id = "R" and wages_type = 1 and is_active = 1 order by date_of_joining desc ';
        $binds = array($department_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_bulanan_department_trading($department_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = 2 AND department_id = ? and  office_id = "R" and wages_type = 1 and is_active = 1 order by date_of_joining desc ';
        $binds = array($department_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    // =============================================================================================================================================
    // REGULER
    // =============================================================================================================================================
    public function get_xin_employees_harian_rekap($company_id, $start_date, $end_date, $with_employees = false, $office_id = NULL)
    {
        /**
         * @var CI_DB_query_builder
         */
        $query = $this->db->where(array(
            'rekap.company_id' => $company_id,
            'rekap.start_date' => $start_date,
            'rekap.end_date' => $end_date,
            'rekap.wages_type' => 2,
            'rekap.is_active' => 1
        ))->order_by('rekap.date_of_joining', 'desc');

        if (!is_null($office_id)) {
            $query = $query->where('rekap.office_id', $office_id);
        }

        if ($with_employees) {
            $query = $query
                ->select('rekap.*, emp.first_name, emp.last_name')
                ->join('xin_employees emp', 'emp.user_id = rekap.employee_id');
        }

        return $query->get('xin_attendance_time_rekap_harian rekap')->result();
    }
    public function get_xin_employees_harian_rekap_month($company_id, $month_year, $with_employees = false, $office_id = NULL)
    {
        /**
         * @var CI_DB_query_builder
         */
        $query = $this->db->where(array(
            'rekap.company_id' => $company_id,
            'rekap.month_year' => $month_year,
            'rekap.wages_type' => 2,
            'rekap.is_active' => 1
        ))->order_by('rekap.date_of_joining', 'desc');

        if (!is_null($office_id)) {
            $query = $query->where('rekap.office_id', $office_id);
        }

        if ($with_employees) {
            $query = $query
                ->select('rekap.*, emp.first_name, emp.last_name')
                ->join('xin_employees emp', 'emp.user_id = rekap.employee_id');
        }

        return $query->get('xin_attendance_time_rekap_harian rekap')->result();
    }

    // =============================================================================================================================================
    // REGULER
    // =============================================================================================================================================
    public function get_employees_borongan_rekap($company_id, $start_date, $end_date, $with_employees = false, $office_id = 'R')
    {
        $query = $this->db->where(array(
            'rekap.company_id' => $company_id,
            'rekap.start_date' => $start_date,
            'rekap.end_date' => $end_date,
            'rekap.office_id' => $office_id,
            'rekap.is_active' => 1
        ))
            ->where_in('rekap.wages_type', array(2, 3))
            ->order_by('rekap.date_of_joining', 'desc');


        if ($with_employees) {
            $query = $query
                ->select('rekap.*, emp.first_name, emp.last_name')
                ->join('xin_employees emp', 'emp.user_id = rekap.employee_id');
        }

        return $query->get('xin_attendance_time_rekap_borongan rekap')->result();
    }
    public function get_employees_borongan_rekap_month($company_id, $month_year, $with_employees = false, $office_id = 'R')
    {
        $query = $this->db->where(array(
            'rekap.company_id' => $company_id,
            'rekap.month_year' => $month_year,
            'rekap.office_id' => $office_id,
            'rekap.is_active' => 1
        ))
            ->where_in('rekap.wages_type', array(2, 3))
            ->order_by('rekap.date_of_joining', 'desc');


        if ($with_employees) {
            $query = $query
                ->select('rekap.*, emp.first_name, emp.last_name')
                ->join('xin_employees emp', 'emp.user_id = rekap.employee_id');
        }

        return $query->get('xin_attendance_time_rekap_borongan rekap')->result();
    }

    // =============================================================================================================================================
    // TAHUNAN
    // =============================================================================================================================================

    public function get_xin_employees_tahunan_rekap($company_id, $tahun)
    {

        $sql = 'SELECT * FROM view_attendance_time_rekap_tahun WHERE company_id = ? and tahun = ? and office_id = "R" and wages_type = 1 and is_active = 1 order by date_of_joining desc ';
        $binds = array($company_id, $tahun);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    // =============================================================================================================================================
    // REGULER
    // =============================================================================================================================================
    public function get_xin_employees_bulanan_rekap_lembur($company_id, $month_year)
    {

        $sql = 'SELECT * FROM view_attendance_time_rekap_lembur WHERE company_id = ? and month_year = ? and office_id = "R" and wages_type = 1   and is_active = 1 order by date_of_joining desc ';
        $binds = array($company_id, $month_year);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }


    public function get_xin_employees_bulanan_rekap($company_id, $month_year)
    {

        $sql = 'SELECT * FROM xin_attendance_time_rekap WHERE company_id = ? and month_year = ? and office_id = "R" and wages_type = 1   and is_active = 1 order by date_of_joining desc ';
        $binds = array($company_id, $month_year);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }


    public function get_xin_employees_bulanan($company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? and  office_id = "R" and wages_type = 1   and is_active = 1 order by date_of_joining desc ';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_harian($company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? and  office_id = "R" and wages_type = 2  and is_active = 1 order by date_of_joining desc ';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_borongan_rekap($company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? AND wages_type = 3  and is_active = 1 order by date_of_joining desc ';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_borongan($company_id, $department_id, $designation_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE company_id = ? and department_id = ? and designation_id = ? and  office_id = "R" and wages_type = 3  and is_active = 1 order by date_of_joining desc ';
        $binds = array($company_id, $department_id, $designation_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    // =================================================================================================================================================

    // =============================================================================================================================================
    // SHIFT
    // =============================================================================================================================================

    public function get_xin_employees_bulanan_shift($company_id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE  company_id = ? and office_id = "S" and wages_type = 1 and is_active = 1 order by date_of_joining desc ';
        $binds = array($company_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_borongan_shift()
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE office_id = "S" and wages_type = 3 and is_active = ? order by date_of_joining desc ';
        $binds = array(1);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_borongan_company($id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = 3 and company_id = ? and is_active = 1 order by date_of_joining desc ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_bulanan_pola($id)
    {
        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = 1 and office_id = ? and is_active = 1 order by date_of_joining desc ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_harian_pola($id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = 2 and office_id = ? and is_active = 1 order by date_of_joining desc ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function get_xin_employees_borongan_pola($id)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = 3 and office_id = ? and is_active = 1 order by date_of_joining desc ';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }



    public function get_xin_employees_jenis_gaji($jenis_gaji)
    {

        $sql = 'SELECT * FROM view_karyawan_aktif WHERE wages_type = ? and is_active = ? order by date_of_joining desc';
        $binds = array($jenis_gaji, 1);
        $query = $this->db->query($sql, $binds);

        return $query;
    }

    public function get_xin_employees_att($company_id)
    {

        $sql = 'SELECT * FROM xin_employees WHERE location_id = ? and is_active = ? order by date_of_joining desc ';
        $binds = array($company_id, 1);
        $query = $this->db->query($sql, $binds);

        // echo '<pre>';
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    // get all employee leaves>department wise
    public function get_employee_leaves_department_wise($department_id)
    {

        $sql = 'SELECT * FROM xin_leave_applications WHERE department_id = ?';
        $binds = array($department_id);
        $query = $this->db->query($sql, $binds);
        return $query;
    }

    // get total number of leave > employee
    // public function employee_count_total_leaves($leave_type_id,$employee_id) {

    // 	//$sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ? and leave_type_id = ? and status = ? and created_at >= DATE_SUB(NOW(),INTERVAL 1 YEAR)';
    // 	$sql = 'SELECT * FROM xin_leave_applications WHERE employee_id = ? and leave_type_id = ? and status = ?';
    // 	$binds = array($employee_id,$leave_type_id,2);
    // 	$query = $this->db->query($sql, $binds);

    // 	return $query->num_rows();
    // }


    // get total number of sick > employee
    public function employee_show_last_sick($employee_id, $sick_id)
    {
        $sql = "SELECT * FROM xin_sick_applications WHERE sick_id != '" . $sick_id . "' and employee_id = ? order by sick_id desc limit 1";
        $binds = array($employee_id);
        $query = $this->db->query($sql, $binds);
        return $query->result();
    }

    public function count_attendance_hadir($location_id, $attendance_date)
    {

        $sql = 'SELECT count(*) as jumlah FROM xin_attendance_time WHERE location_id = ? and attendance_date = ? and attendance_status = ?';
        $binds = array($location_id, $attendance_date, 'Hadir');
        $query = $this->db->query($sql, $binds);

        return $query->result();
    }

    public function read_bulan_information($id)
    {

        $sql = 'SELECT * FROM xin_payroll_calendar_bulan WHERE tahun = ?';
        $binds = array($id);
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

    public function read_tanggal_information($id)
    {

        $sql = 'SELECT * FROM xin_payroll_date WHERE month_payroll = ?';
        $binds = array($id);
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

    public function read_periode_bulan($id)
    {

        $sql = 'SELECT * FROM xin_payroll_date_periode WHERE payroll_id = ?';
        $binds = array($id);
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

    public function get_xin_employees_tahun($id)
    {

        $sql   = "SELECT * FROM xin_payroll_year WHERE tahun >= '" . $id . "' ";

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
    }

    public function get_xin_employees_bulan($id)
    {
        $sql   = "SELECT * FROM xin_payroll_date WHERE DATE_FORMAT(start_date, '%y-%m') <= '" . $id . "' AND DATE_FORMAT(end_date, '%y-%m') >= '" . $id . "' ";

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
    }

    public function get_xin_employees_periode($id)
    {

        $sql   = "SELECT * FROM xin_payroll_date_periode WHERE start_date <= '" . $id . "' AND end_date >= '" . $id . "' ";

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
    }


    public function get_xin_employees_bulan_gaji($id)
    {

        $sql   = "SELECT * FROM xin_payroll_date WHERE month_payroll = '" . $id . "' ";

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
    }

    public function get_xin_employees_periode_gaji($id)
    {

        $sql   = "SELECT * FROM xin_payroll_date_periode WHERE month_payroll = '" . $id . "' ";

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
    }



    public function get_xin_tanggal($id)
    {

        $sql = 'SELECT * FROM view_tanggal WHERE bulan = ? ORDER BY tanggal ASC';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();
        return $query->result();
    }

    public function get_xin_periode($id)
    {

        $sql = 'SELECT * FROM view_periode WHERE payroll_id = ? ORDER BY tanggal ASC';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();
        return $query->result();
    }

    public function get_xin_employees_tanggal_periode($start_date, $end_date)
    {

        $sql = 'SELECT * FROM xin_payroll_calendar WHERE tanggal >= ? and tanggal <= ? ORDER BY tanggal ASC';
        $binds = array($start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();

        return $query->result();
    }

    public function get_xin_employees_tanggal($id)
    {

        $sql = 'SELECT * FROM xin_payroll_calendar WHERE bulan = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();
        return $query->result();
    }


    public function get_xin_calendar_tahun($id)
    {

        $sql = 'SELECT * FROM xin_payroll_calendar_bulan WHERE tahun = ?';
        $binds = array($id);
        $query = $this->db->query($sql, $binds);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();
        return $query->result();
    }

    public function get_xin_status_tahun()
    {

        $sql = 'SELECT * FROM xin_payroll_status ';
        $query = $this->db->query($sql);

        // echo "<pre>";
        // print_r($this->db->last_query());
        // echo "</pre>";
        // die();
        return $query->result();
    }

    public function conHari($hari)
    {
        switch ($hari) {
            case 'Sun':
                $getHari = "Min";
                break;
            case 'Mon':
                $getHari = "Sen";
                break;
            case 'Tue':
                $getHari = "Sel";
                break;
            case 'Wed':
                $getHari = "Rab";
                break;
            case 'Thu':
                $getHari = "Kam";
                break;
            case 'Fri':
                $getHari = "Jum";
                break;
            case 'Sat':
                $getHari = "Sab";
                break;
            case '':
                $getHari = "-";
                break;
            default:
                $getHari = "Salah";
                break;
        }

        return $getHari;
    }

    public function conHariNama($hari)
    {
        switch ($hari) {
            case 'Sun':
                $getHari = "Minggu";
                break;
            case 'Mon':
                $getHari = "Senin";
                break;
            case 'Tue':
                $getHari = "Selasa";
                break;
            case 'Wed':
                $getHari = "Rabu";
                break;
            case 'Thu':
                $getHari = "Kamis";
                break;
            case 'Fri':
                $getHari = "Jumat";
                break;
            case 'Sat':
                $getHari = "Sabtu";
                break;
            case '':
                $getHari = "-";
                break;
            default:
                $getHari = "Salah";
                break;
        }

        return $getHari;
    }

    public function conWarna($hari)
    {
        switch ($hari) {
            case 'Min':
                $getWarna = "background-color : #e1d4d7;";
                break;
            case 'Sen':
                $getWarna = "";
                break;
            case 'Sel':
                $getWarna = "";
                break;
            case 'Rab':
                $getWarna = "";
                break;
            case 'Kam':
                $getWarna = "";
                break;
            case 'Jum':
                $getWarna = "";
                break;
            case 'Sab':
                $getWarna = "";
                break;
            case '':
                $getWarna = "";
                break;
            default:
                $getWarna = "";
                break;
        }

        return $getWarna;
    }

    public function conWarnaBulanNama($bulan)
    {
        switch ($bulan) {
            case 'Jan':
                $getWarna = "background-color : #dcb7c0;";
                break;
            case 'Feb':
                $getWarna = "background-color : #dcb7d3;";
                break;
            case 'Mar':
                $getWarna = "background-color : #d8d4e1;";
                break;
            case 'Apr':
                $getWarna = "background-color : #d4dae1;";
                break;
            case 'Mei':
                $getWarna = "background-color : #d4e1de;";
                break;
            case 'Jun':
                $getWarna = "background-color : #d4e1d7;";
                break;

            case 'Jul':
                $getWarna = "background-color : #dae1d4;";
                break;

            case 'Ags':
                $getWarna = "background-color : #e1dcd4;";
                break;

            case 'Sep':
                $getWarna = "background-color : #d4dce1;";
                break;

            case 'Okt':
                $getWarna = "background-color : #e1ddd4;";
                break;

            case 'Nov':
                $getWarna = "background-color : #e1d4d4;";
                break;

            case 'Des':
                $getWarna = "background-color : #e1d4dd;";
                break;

            case '':
                $getWarna = "";
                break;
            default:
                $getWarna = "";
                break;
        }

        return $getWarna;
    }

    public function conWarnaBulan($bulan)
    {
        switch ($bulan) {
            case 'Jan':
                $getWarna = "background-color : #e1d4d7;";
                break;
            case 'Feb':
                $getWarna = "background-color : #e0d4e1;";
                break;
            case 'Mar':
                $getWarna = "background-color : #d8d4e1;";
                break;
            case 'Apr':
                $getWarna = "background-color : #d4dae1;";
                break;
            case 'Mei':
                $getWarna = "background-color : #d4e1de;";
                break;
            case 'Jun':
                $getWarna = "background-color : #d4e1d7;";
                break;

            case 'Jul':
                $getWarna = "background-color : #dae1d4;";
                break;

            case 'Ags':
                $getWarna = "background-color : #e1dcd4;";
                break;

            case 'Sep':
                $getWarna = "background-color : #d4dce1;";
                break;

            case 'Okt':
                $getWarna = "background-color : #e1ddd4;";
                break;

            case 'Nov':
                $getWarna = "background-color : #e1d4d4;";
                break;

            case 'Des':
                $getWarna = "background-color : #e1d4dd;";
                break;

            case '':
                $getWarna = "";
                break;
            default:
                $getWarna = "";
                break;
        }

        return $getWarna;
    }

    public function conWarnaSub($hari)
    {
        switch ($hari) {
            case 'Min':
                $getWarna = "background-color : #eee3e5;";
                break;
            case 'Sen':
                $getWarna = "";
                break;
            case 'Sel':
                $getWarna = "";
                break;
            case 'Rab':
                $getWarna = "";
                break;
            case 'Kam':
                $getWarna = "";
                break;
            case 'Jum':
                $getWarna = "";
                break;
            case 'Sab':
                $getWarna = "";
                break;
            case '':
                $getWarna = "";
                break;
            default:
                $getWarna = "";
                break;
        }

        return $getWarna;
    }

    public function get_daily_recap($employee_ids, $start_date, $end_date)
    {
        $attendance_types = array('L', 'LK', 'H', 'S', 'I', 'C', 'A', 'D', 'O');
        $attends = $this->hitung_multi_jumlah_status_kehadiran($employee_ids, $start_date, $end_date, $attendance_types);

        $all_total_attendance = array();
        foreach ($attends as $gata) {
            if (!isset($all_total_attendance[$gata->employee_id])) {
                $all_total_attendance[$gata->employee_id] = array();
            }

            $all_total_attendance[$gata->employee_id][$gata->attendance_status_simbol] = $gata->jumlah;
        }

        $get_all_late = $this->hitung_jumlah_terlambat_kehadiran($employee_ids, $start_date, $end_date);
        foreach ($get_all_late as $gal) {
            if (!isset($all_total_attendance[$gal->employee_id])) {
                $all_total_attendance[$gal->employee_id] = array();
            }

            $all_total_attendance[$gal->employee_id]['late'] = $gal->jumlah;
        }

        $get_overtime_ooo = $this->hitung_multi_jumlah_lembur_libur($employee_ids, $start_date, $end_date);
        foreach ($get_overtime_ooo as $ooo) {
            if (!isset($all_total_attendance[$ooo->employee_id])) {
                $all_total_attendance[$ooo->employee_id] = array();
            }

            $all_total_attendance[$ooo->employee_id]['ooo'] = $ooo->jumlah;
        }

        return $all_total_attendance;
    }
    public function hitung_rekap_kehadiran($emp_id, $start_date, $end_date){
        $sql = "SELECT 
                SUM(
                CASE WHEN attendance_status_simbol = 'H' THEN 1 ELSE 0 END
                ) AS jumlah_hadir, 
                SUM(
                CASE WHEN attendance_status_simbol = 'I' THEN 1 ELSE 0 END
                ) AS jumlah_izin, 
                SUM(
                CASE WHEN attendance_status_simbol = 'C' THEN 1 ELSE 0 END
                ) AS jumlah_cuti, 
                SUM(
                CASE WHEN attendance_status_simbol = 'S' THEN 1 ELSE 0 END
                ) AS jumlah_sakit, 
                SUM(
                CASE WHEN attendance_status_simbol = 'A' THEN 1 ELSE 0 END
                ) AS jumlah_alpa, 
                SUM(
                CASE WHEN attendance_status_simbol = 'L' THEN 1 ELSE 0 END
                ) AS jumlah_libur, 
                SUM(
                CASE WHEN attendance_status_simbol = 'LK' THEN 1 ELSE 0 END
                ) AS jumlah_libur_kantor,
                SUM(
                CASE WHEN attendance_status_simbol = '0' THEN 1 ELSE 0 END
                ) AS jumlah_lembur,
                SUM(
                CASE WHEN attendance_status_simbol = 'D' THEN 1 ELSE 0 END
                ) AS jumlah_dinas,
                SUM(time_late) as jumlah_terlambat
            FROM 
                xin_attendance_time 
            WHERE 
                employee_id = ? 
                AND attendance_date >= ? 
                AND attendance_date <= ?
        ";
        $binds = array($emp_id, $start_date, $end_date);
        $query = $this->db->query($sql, $binds);

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

class THR_model extends CI_Model
{
    public function get_list_daily_payroll($thr_date, $company_id)
    {
        $query = "SELECT
            e.user_id,
            e.employee_id,
            e.first_name,
            e.last_name,
            e.department_id,
            e.designation_id,
            e.company_id,
            e.location_id,
            e.date_of_joining,
            e.wages_type,
            MIN(start_date) AS start_date,
            MAX(end_date) AS end_date,
            SUM(ph.jumlah_hadir) AS total_day,
            MONTH(start_date) AS pay_month,
            YEAR(start_date) AS pay_year,
            SUM(ph.net_salary) AS salary,
            ph.basic_salary,
            thr_pd.payslip_id
        FROM
            `xin_employees` e
            LEFT JOIN `xin_salary_payslips_harian` ph ON e.user_id = ph.employee_id AND ph.start_date <= ? AND ph.end_date >= ?
            LEFT JOIN `xin_thr_payslips_daily` thr_pd ON thr_pd.employee_id = e.user_id AND thr_pd.tanggal_thr = ?
        WHERE e.wages_type = 2
        AND e.is_active = 1
        AND e.company_id = ?
        AND e.date_of_joining <= ?
        GROUP BY YEAR(start_date), MONTH(start_date), employee_id
        ORDER BY e.date_of_joining DESC, e.user_id DESC";

        $limit_date = new DateTimeImmutable($thr_date);
        $start_date = $limit_date->modify("-1 year");

        $binds = [
            $limit_date->format("Y-m-d"), // ph.start_date
            $start_date->format("Y-m-d"), // ph.end_date
            $limit_date->format("Y-m-d"), // thr_pd.tanggal_thr
            $company_id, // e.company_id
            $limit_date->format("Y-m-d"), // e.date_of_joining
        ];
        $sql = $this->db->query($query, $binds);

        return $sql->result();
    }

    public function get_detail_daily_payroll($thr_date, $user_id, $company_id)
    {
        $query = "SELECT
            e.user_id,
            e.employee_id,
            e.first_name,
            e.last_name,
            e.department_id,
            e.designation_id,
            e.company_id,
            e.location_id,
            e.date_of_joining,
            e.wages_type,
            MIN(start_date) AS start_date,
            MAX(end_date) AS end_date,
            SUM(ph.jumlah_hadir) AS total_day,
            MONTH(start_date) AS pay_month,
            YEAR(start_date) AS pay_year,
            SUM(ph.net_salary) AS salary,
            ph.basic_salary,
            thr_pd.payslip_id
        FROM
            `xin_employees` e
            LEFT JOIN `xin_salary_payslips_harian` ph ON e.user_id = ph.employee_id AND ph.start_date <= ? AND ph.end_date >= ?
            LEFT JOIN `xin_thr_payslips_daily` thr_pd ON thr_pd.employee_id = e.user_id AND thr_pd.tanggal_thr = ?
        WHERE e.wages_type = 2
        AND e.is_active = 1
        AND e.company_id = ?
        AND e.date_of_joining <= ?
        AND e.user_id = ?
        GROUP BY YEAR(start_date), MONTH(start_date), employee_id
        ORDER BY e.date_of_joining DESC";

        $limit_date = new DateTimeImmutable($thr_date);
        $start_date = $limit_date->modify("-1 year");

        $binds = [
            $limit_date->format("Y-m-d"), // ph.start_date
            $start_date->format("Y-m-d"), // ph.end_date
            $limit_date->format("Y-m-d"), // thr_pd.tanggal_thr
            $company_id, // e.company_id
            $limit_date->format("Y-m-d"), // e.date_of_joining
            $user_id, // e.user_id
        ];
        $sql = $this->db->query($query, $binds);

        return $sql->result();
    }

    public function get_detail_daily_thr($payslip_id)
    {
        return $this->db
            ->select([
                'xin_thr_payslips_daily.*',
                'xin_employees.first_name',
                'xin_employees.last_name',
                'xin_designations.designation_name',
                'xin_departments.department_name',
            ])
            ->where('payslip_id', $payslip_id)
            ->join('xin_employees', 'xin_thr_payslips_daily.employee_id = xin_employees.user_id', 'left')
            ->join('xin_designations', 'xin_thr_payslips_daily.designation_id = xin_designations.designation_id', 'left')
            ->join('xin_departments', 'xin_thr_payslips_daily.department_id = xin_departments.department_id', 'left')
            ->get('xin_thr_payslips_daily')
            ->row();
    }

    public function get_total_attendance($filters)
    {
        $params = $binds = [];
        foreach ($filters as $filter) {
            $params[] = "(
                attendance_date >= ?
                AND attendance_date <= ?
                AND employee_id = ?
            )";

            $binds = array_merge($binds, [
                $filter['start_date'],
                $filter['end_date'],
                $filter['user_id'],
            ]);
        }

        $params = implode(" OR ", $params);

        $query = "SELECT
            employee_id,
            count(attendance_date) AS total,
            MIN(attendance_date) AS start_date,
            MAX(attendance_date) AS end_date
        FROM
            xin_attendance_time
        WHERE
            attendance_status_simbol IN ('H', 'O')
        AND jenis_gaji = 2
        AND ({$params})
        GROUP BY employee_id, YEAR(attendance_date), MONTH(attendance_date)
        ORDER BY
            employee_id,
            attendance_date ASC";

        $sql = $this->db->query($query, $binds);
        return $sql->result();
    }

    public function get_all_primary_bank_account($employee_ids, $is_single = false)
    {
        $sql = $this->db
            ->select('account_number, bank_name, ba.employee_id, account_title')
            ->from("xin_employee_bankaccount AS ba")
            ->join("(SELECT employee_id, MAX(bankaccount_id) max_id, MAX(is_primary) AS latest_status FROM xin_employee_bankaccount GROUP BY employee_id) baf", "baf.employee_id = ba.employee_id", "inner")
            ->where_in('ba.employee_id', $employee_ids)
            ->where('`ba`.`is_primary`', '`baf`.`latest_status`', false)
            ->group_by('ba.employee_id')
            ->get();

        if ($is_single) return $sql->row();

        return $sql->result();
    }

    public function get_designations($ids, $is_single = false)
    {
        $sql = $this->db
            ->where_in('designation_id', $ids)
            ->select('designation_id, designation_name')
            ->get('xin_designations');

        if ($is_single) return $sql->row();

        return $sql->result();
    }

    public function get_departments($ids, $is_single = false)
    {
        $sql = $this->db
            ->where_in('department_id', $ids)
            ->select('department_id, department_name')
            ->get('xin_departments');

        if ($is_single) return $sql->row();

        return $sql->result();
    }

    public function check_thr_daily($user_id, $year)
    {
        return $this->db
            ->from('xin_thr_payslips_daily')
            ->where('employee_id', $user_id)
            ->where('tahun_thr', $year)
            ->count_all_results();
    }

    public function delete_thr_daily($id, $year, $is_payslip = true)
    {
        if ($is_payslip) {
            return $this->db
                ->where('payslip_id', $id)
                ->delete('xin_thr_payslips_daily');
        } else {
            return $this->db
                ->where('employee_id', $id)
                ->where('tahun_thr', $year)
                ->delete('xin_thr_payslips_daily');
        }
    }

    public function add_thr_daily($data, $bulk = false)
    {
        if ($bulk) {
            $this->db->insert_batch('xin_thr_payslips_daily', $data);
        } else {
            $this->db->insert('xin_thr_payslips_daily', $data);
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function pay_daily_thr_del()
    {
        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title'] = $this->Core_model->site_title();
        $payslip_id = $this->input->get('payslip_id');

        $thr = $this->THR_model->get_detail_daily_thr($payslip_id);

        $data = compact('thr');

        if (!empty($session)) {
            $this->load->view('admin/thr/dialog_make_payment_daily_delete', $data);
        } else {
            redirect('admin/');
        }
    }
}

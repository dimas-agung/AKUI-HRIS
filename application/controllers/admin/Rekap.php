<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property Timesheet_model $Timesheet_model
 * @property Core_model $Core_model
 * @property Company_model $Company_model
 * @property Payroll_model $Payroll_model
 * @property Department_model $Department_model
 * @property Designation_model $Designation_model
 */
class Rekap extends MY_Controller
{

    /*Function to set JSON output*/
    public function output($Return = array())
    {
        /*Set response header*/
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        /*Final JSON response*/
        exit(json_encode($Return));
    }

    public function __construct()
    {
        parent::__construct();

        //load the models
        $this->load->model("Timesheet_model");
        $this->load->model("Core_model");
        $this->load->model("Company_model");
        $this->load->model("Payroll_model");
        $this->load->model("Department_model");
        $this->load->model("Designation_model");

        $this->load->library("pagination");
        $this->load->library('Pdf');
        $this->load->helper('string');
    }

    // import
    public function index()
    {

        $session = $this->session->userdata('username');
        if (empty($session)) {
            redirect('admin/');
        }
        $data['title']         = 'Rekap Produktifitas | ' . $this->Core_model->site_title();
        $data['icon']          = '<i class="fa fa-briefcase"></i>';
        $data['breadcrumbs']   = 'Rekap Produktifitas ';
        $data['desc']          = 'PROSES : Rekap Produktifitas  ';
        $data['path_url']      = 'hris_rekap';

        $data['all_companies']       = $this->Company_model->get_company();

        $data['get_all_workstation'] = $this->Company_model->get_workstation();

        $role_resources_ids    = $this->Core_model->user_role_resource();
        if (in_array('01041', $role_resources_ids)) {
            if (!empty($session)) {
                $data['subview'] = $this->load->view("admin/layout/hris_rekap", $data, TRUE);
                $this->load->view('admin/layout/layout_main', $data); //page load
            } else {
                redirect('admin/');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function produktifitas_per_periode_rekap_list()
    {
        $data['title'] = $this->Core_model->site_title();
        $session = $this->session->userdata('username');

        if (!empty($session)) {
            $this->load->view("admin/layout/hris_rekap", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw   = intval($this->input->get("draw"));
        $start  = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        // date and employee id/company id

        $company_id     = $this->input->get("company_id");
        $workstation_id = $this->input->get("workstation_id");
        $start_date     = $this->input->get("start_date");
        $end_date       = $this->input->get("end_date");

        $this->Payroll_model->delete_produktifitas_harian_rekap($company_id, $workstation_id, $start_date, $end_date);
        $payslip = $this->Payroll_model->get_borongan_company($company_id, $workstation_id);
        $data   = array();
        $no     = 1;

        foreach ($payslip->result() as $r) {
            // ====================================================================================================================
            // DATA KARYAWAN
            // ====================================================================================================================

            // Karyawan ID
            $emp_id         = $r->employee_id;

            $user_info      = $this->Core_model->read_employee_info_data($emp_id);

            if (!is_null($user_info)) {
                $user_id        = $user_info[0]->user_id;
                $emp_nik        = $user_info[0]->employee_id;
                $full_name      = $user_info[0]->first_name . ' ' . $user_info[0]->last_name;
                $department_id  = $user_info[0]->department_id;
                $designation_id = $user_info[0]->designation_id;
                $start_join = $user_info[0]->date_of_joining;
            } else {
                $user_id        = '';
                $emp_nik        = '';
                $full_name      = '';
                $department_id  = '';
                $designation_id = '';
                $designation_id = '';
            }

            // ====================================================================================================================
            // DATA LAIN
            // ====================================================================================================================

            // Karyawan Departemen
            $department = $this->Department_model->read_department_information($department_id);
            if (!is_null($department)) {
                $department_name = $department[0]->department_name;
            } else {
                $department_name = '';
            }

            // get workstation
            $workstation = $this->Core_model->read_designation_workstation_info($designation_id);
            if (!is_null($workstation)) {
                $workstation_name = $workstation[0]->workstation_name;
                $workstation_id   = $workstation[0]->workstation_id;
            } else {
                $workstation_name = '';
                $workstation_id   = '';
            }

            // Karyawan Posisi
            $designation = $this->Designation_model->read_designation_information($designation_id);
            if (!is_null($designation)) {
                $designation_name = $designation[0]->designation_name;
            } else {
                $designation_name = '';
            }



            $job = '';

            // $sql_job = "SELECT
            //                         *
            //                     FROM
            //                          view_xin_workstation_gram_terima
            //                     WHERE
            //                         1 = 1
            //                     AND employee_id  = '" . $r->employee_id . "'
            //                     AND gram_tanggal >= '" . $start_date . "' and gram_tanggal <= '" . $end_date . "'
            //                     ORDER BY gram_tanggal ASC";

            // echo "<pre>";
            // print_r( $sql_job );
            // echo "</pre>";
            // die;

            // $query_job = $this->db->query($sql_job);
            $query_job = $this->Payroll_model->view_gram_produktifitas($r->employee_id, $start_date, $end_date);

            // $total_gram = $total_jumlah_gram = $total_biaya = $jumlah_hadir = $total_insentif = 0 ;
            $total_gram = $total_jumlah_gram = $total_biaya = $jumlah_hadir = $total_insentif = 0 ;
            if ($query_job->num_rows() > 0) {
                // $job = "<table class=\"datatables-demo table table-striped table-bordered\" id=\"xin_table\">
                //     <thead>
                //         <tr>
                //         <th class=\"text-center\" width=\"50px\">No.</th>
                //         <th class=\"text-center\" width=\"30%\"> Tanggal </th>
                //         <th class=\"text-center\" width=\"30%\"> Jumlah (Gram)</th>
                //         <th class=\"text-center\" width=\"30%\"> Ongkos (Rp)</th>
                //         </tr>
                //     </thead>
                // <tbody>";
                $job = "<table class=\"datatables-demo table table-striped table-bordered\" id=\"xin_table\">
                    <thead>
                        <tr>
                        <th class=\"text-center\" width=\"50px\">No.</th>
                        <th class=\"text-center\" width=\"30%\"> Tanggal </th>
                        <th class=\"text-center\" width=\"30%\"> Jumlah (Gram)</th>
                        <th class=\"text-center\" width=\"30%\"> Ongkos (Rp)</th>
                        <th class=\"text-center\" width=\"30%\"> Insentif (Rp)</th>
                        </tr>
                    </thead>
                <tbody>";

                $total_biaya = 0;
                $total_gram  = 0;
                $total_jumlah_gram = 0;
                $total_insentif = 0;
                $mo = 1;

                $cek_hadir      = $this->Timesheet_model->hitung_jumlah_produktifitas_kehadiran($r->employee_id, $start_date, $end_date);
                if (!is_null($cek_hadir)) {

                    if ($cek_hadir[0]->jumlah_hari != '') {

                        $jumlah_hadir   = $cek_hadir[0]->jumlah_hari;
                    } else {

                        $jumlah_hadir   = 0;
                    }
                } else {
                    $jumlah_hadir   = 0;
                }


                foreach ($query_job->result() as $row_job) :

                    $jum_biaya      = $row_job->info_biaya;
                    $gram_biaya     = number_format($row_job->info_biaya, 0, ',', '.');
                    $target         = '-';
                    $nilai_target   = '-';
                    $jumlah_biaya   = $jum_biaya;
                    $gram_target    = number_format($jumlah_biaya, 0, ',', '.');
                    $info_target    = '';

                    $_date = date("d-m-Y", strtotime($row_job->gram_tanggal));
                    $_gram = number_format($row_job->info_nilai, 0, ',', '.');
                    $_insentif = number_format($row_job->insentif, 0, ',', '.');
                    // $job .= "<tr>
                    //     <td width=\"2%\" align=\"center\">{$mo}</td>
                    //     <td align=\"center\">{$_date}</td>
                    //     <td align=\"right\">{$_gram}</td>
                    //     <td align=\"right\">{$gram_target}</td>
                    // </tr>";
                    $job .= "<tr>
                        <td width=\"2%\" align=\"center\">{$mo}</td>
                        <td align=\"center\">{$_date}</td>
                        <td align=\"right\">{$_gram}</td>
                        <td align=\"right\">{$gram_target}</td>
                        <td align=\"right\">{$_insentif}</td>
                    </tr>";
                    
                    $total_jumlah_gram += $row_job->info_nilai;
                    $total_gram   += $jum_biaya;
                    $total_biaya  += $jumlah_biaya;
                    $total_insentif  += $row_job->insentif;

                    $mo++;
                endforeach;

                $_tgram = number_format($total_jumlah_gram, 0, ',', '.');
                $_tbiaya = number_format($total_biaya, 0, ',', '.');
                $_tinsentif = number_format($total_insentif, 0, ',', '.');
                // $job .= "<tr>
                //     <td colspan=\"2\" width=\"12%\" align=\"right\">{$jumlah_hadir} hari kerja</td>
                //     <td align=\"right\">{$_tgram}</td>
                //     <td align=\"right\">{$_tbiaya}</td>
                // </tr>";
                $job .= "<tr>
                    <td colspan=\"2\" width=\"12%\" align=\"right\">{$jumlah_hadir} hari kerja</td>
                    <td align=\"right\">{$_tgram}</td>
                    <td align=\"right\">{$_tbiaya}</td>
                    <td align=\"right\">{$_tinsentif}</td>
                </tr>";

                // $job = $job . '
                //                                 <tr">


                //                                     <td colspan ="2" width="12%" align="right">
                //                                         ' . $jumlah_hadir . ' hari kerja
                //                                     </td>
                //                                     <td width="12%" align="right">
                //                                         ' . number_format($total_gram, 0, ',', '.') . '
                //                                     </td>

                //                                     <td width="12%" align="right">
                //                                     </td>

                //                                     <td width="12%" align="right">
                //                                         ' . number_format($total_biaya, 0, ',', '.') . '
                //                                     </td>
                //                                     <td colspan ="1" align="right">

                //                                     </td>
                //                                 </tr>';

                $job .= "</tbody></table>";
            } else {
                $job = "<div class=\"warning-msg\" style=\"padding:5px;\">
                    <i class =\"fa fa-question-circle\"></i> Tidak Ada Produktifitas
                </div>";
            }




            $session_id = $this->session->userdata('user_id');
            $user_create = $session_id['user_id'];


            $data_rekap = array(
                'company_id'        => $company_id,
                'workstation_id'    => $workstation_id,
                'start_date'        => $start_date,
                'end_date'          => $end_date,

                'employee_id'       => $user_id,
                'rekap_day'         => $jumlah_hadir,
                'rekap_gram'        => $total_jumlah_gram,
                'rekap_amount'      => $total_biaya,
                'rekap_insentif'      => $total_insentif,

                'created_at'        => date('Y-m-d h:i:s'),
                'created_by'        => $user_create
            );

            $this->Payroll_model->add_produktifitas_harian_rekap($data_rekap);



            $data[] = array(
                $no,
                $emp_nik,
                $full_name . '<br> <i class="fa fa-angle-double-right"></i> ' . $workstation_name . ' (' .    $workstation_id . ') <br><i class="fa fa-angle-double-right"></i> ' . $designation_name .'<br>'.$start_join,
                $job
            );
            $no++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $payslip->num_rows(),
            "recordsFiltered" => $payslip->num_rows(),
            "data" => $data
        );
        $this->output->set_output(json_encode($output));
    }

    public function get_workstations()
    {

        $data['title'] = $this->Core_model->site_title();
        $id = $this->uri->segment(4);

        $data = array(
            'company_id' => $id
        );
        $session = $this->session->userdata('username');
        if (!empty($session)) {
            $this->load->view("admin/layout/get_workstations", $data);
        } else {
            redirect('admin/');
        }
        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
    }
}

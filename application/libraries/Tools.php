<?php


class Tools
{	
	
	function exportdata($tgl1, $tgl2, $location_id, $table, $title, $desc, $namafile, $folderpath = '')
	{
		$CI=& get_instance();
		$CI->load->library('phpexcel');
		$CI->load->database();
		$CI->load->library('PHPExcel/iofactory');
		
		$periode = $tgl1."To".$tgl2;

        // Set document properties
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setTitle("Laporan : " . $location_id);
		$objPHPExcel->getProperties()->setDescription("Periode : " . $periode);
		$objPHPExcel->getProperties()->setCreator($location_id);
		$objPHPExcel->getProperties()->setLastModifiedBy($location_id);
		$objPHPExcel->getProperties()->setSubject("Attendance Report : " . $periode);
		$objPHPExcel->getProperties()->setKeywords($location_id);
		$objPHPExcel->getProperties()->setCategory("Report");		

		$objPHPExcel->getSheet(0)->setTitle('TES');	
		$objPHPExcel->setActiveSheetIndex(0);

		$CI->load->model('mod');
		$results = $CI->mod->get_attendance_to_excel($tgl1, $tgl2, $location_id);   	

		$arrResults = array();
		$no = 1;
		foreach ($results as $keys => $values) {
			$userid = $values->userid;
			$check_in = $results1 = $CI->mod->get_attendance_checkIn($tgl1, $tgl2, $userid);
			$check_out = $CI->mod->get_attendance_checkOut($tgl1, $tgl2, $userid);				

			$arrResults[] = array(
				$no . ".",
				$values->badgenumber,
				$values->Card,
				$values->name,
				$values->Gender,
				$values->eDate,
				$values->eDate,
				$values->eDate,		
			);

			$no++;
		}
    
        // echo '<pre>';
        // print_r($arrResults);
    
		// create title
		$objPHPExcel->getActiveSheet()->mergeCells('A1:Z1')->setCellValue('A1', 'Attendance Report ');
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(false)->setSize(12);

		$objPHPExcel->getActiveSheet()->mergeCells('A2:Z2')->setCellValue('A2', 'CIAS 2019');
		$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(false)->setSize(12);

		$objPHPExcel->getActiveSheet()->mergeCells('A3:Z3')->setCellValue('A3', $title);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(false)->setSize(12);

		$objPHPExcel->getActiveSheet()->mergeCells('A4:Z4')->setCellValue('A4', $desc);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(false)->setSize(12);

		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($style);
		$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($style);

		$style1 = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
				'rotation' => 0,
				'wrap' => true
			)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A5:AA5')->applyFromArray($style1);
		$objPHPExcel->getActiveSheet()->getStyle('A6:AA6')->applyFromArray($style1);
	    
	    // Set title row bold;
		$objPHPExcel->getActiveSheet()->getStyle('A5:AA5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A6:AA6')->getFont()->setBold(true);
	    
	    // Set fills
		$objPHPExcel->getActiveSheet()->getStyle('A5:AA5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A6:AA6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

		$objPHPExcel->getActiveSheet()->getStyle('A5:AA5')->getFill()->getStartColor()->setARGB('FFEEEEEE');
		$objPHPExcel->getActiveSheet()->getStyle('A6:AA6')->getFill()->getStartColor()->setARGB('FFEEEEEE');
		
		// Add 1rst header
		$objPHPExcel->getActiveSheet()->setCellValue('A5', 'NO.');
		$objPHPExcel->getActiveSheet()->mergeCells('A5:A6');

		$objPHPExcel->getActiveSheet()->setCellValue('B5', 'LOCATION');
		$objPHPExcel->getActiveSheet()->mergeCells('B5:B6');

		$objPHPExcel->getActiveSheet()->setCellValue('C5', 'ID CARD');
		$objPHPExcel->getActiveSheet()->mergeCells('C5:C6');

		$objPHPExcel->getActiveSheet()->setCellValue('D5', 'NAME');
		$objPHPExcel->getActiveSheet()->mergeCells('D5:D6');

		$objPHPExcel->getActiveSheet()->setCellValue('E5', 'GENDER');
		$objPHPExcel->getActiveSheet()->mergeCells('E5:E6');

		$objPHPExcel->getActiveSheet()->setCellValue('F5', 'DATE');
		$objPHPExcel->getActiveSheet()->mergeCells('F5:F6');

		$objPHPExcel->getActiveSheet()->setCellValue('G5', 'IN');
		$objPHPExcel->getActiveSheet()->mergeCells('G5:G6');

		$objPHPExcel->getActiveSheet()->setCellValue('H5', 'OUT');
		$objPHPExcel->getActiveSheet()->mergeCells('H5:H6');		
		
		// create width for header
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
				
		// insert data to excel
		$rowNum = 7;
		foreach ($arrResults as $keys => $values) {
			$columnName = 'A';
			$style2 = array(
				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				)
			);

			foreach ($values as $key => $value) {
				if ($columnName === 'G' || $columnName === 'H' ) {
					$objPHPExcel->getActiveSheet()
						->getStyle($columnName . $rowNum)
						->getNumberFormat()
						->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				}
	 			// CENTER
				if ($columnName === 'A' || $columnName === 'B' || $columnName === 'C' || $columnName === 'D' || $columnName === 'E' || $columnName === 'F') {
					$objPHPExcel->getActiveSheet()
						->getStyle($columnName . $rowNum)
						->applyFromArray($style2);
				}

				$objPHPExcel->setActiveSheetIndex()->setCellValue("{$columnName}{$rowNum}", $value);
				$columnName++;
			}

			$rowNum++;
		}
 		
 		// set border
		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		// create style for border
		$objPHPExcel->getActiveSheet()
					->getStyle('A5:' . $objPHPExcel->getActiveSheet()
					->getHighestColumn() . $objPHPExcel->getActiveSheet()->getHighestRow())->applyFromArray($styleArray);

		$filename = $namafile . '.xls';
		if ($folderpath != "") {

			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save($folderpath . '/' . $filename);

		} else {

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');

			$objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');

		}

	}	

	function importdata($file,$table,$startRow,$checkPrimary=FALSE)
    {
        $CI=& get_instance();
        $CI->load->database();
        $CI->load->library('phpexcel');
        $CI->load->library('PHPExcel/iofactory');
        $objPHPExcel = new PHPExcel();
        try{
            $inputFileType = IOFactory::identify($file);
            $objReader = IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($file);
        } catch (Exception $ex) {
            die("Tidak dapat mengakses file ".$ex->getMessage());
        }
        
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        for ($row = $startRow; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
            $startCol=0;
            if($checkPrimary==TRUE)
            {
                $startCol=0;
            }else{
                $startCol=1;
            }
                    
		    $fields = $CI->db->list_fields('xin_workstation_gram');
            $countfield=count($fields)-1;
            
            $datacol=array();
            $dataxl=array();
            $colXl=-1;
            for($col=$startCol;$col<=$countfield;$col++)
            {
                $colXl+=1;                
                $datacol[]=$fields[$col];
                $dataxl[]=$rowData[0][$colXl];
            }
            $data=array_combine($datacol,$dataxl);
            $CI->db->insert($table,$data);
            
            
        }
    } 
}
?>
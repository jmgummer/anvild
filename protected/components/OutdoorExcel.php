<?php

class OutdoorExcel{
	public static function ExcelBook($result,$period,$company_name,$my_brand_name)
	{
		$PHPExcel = new PHPExcel();
		$title = 'The following is an Outdoor Summary Report for the period: '.$period;
		/* 
		** Set properties of the Excel Workbook 
		*/
		$PHPExcel->getProperties()->setCreator("Reelforge Systems")
		->setTitle("Reelforge Outdoor Summary Reports")
		->setSubject("Reelforge Outdoor Summary Reports")
		->setDescription("Reelforge Outdoor SummaryReports");
		$sheet_index = 0;
		$sheet_name = 'Outdoor Report';
		$PHPExcel->createSheet(NULL, $sheet_index);
		$PHPExcel->setActiveSheetIndex($sheet_index)
		->setCellValue('A1', 'Outdoor Summary Report')
		->setCellValue('B1', ' ')
		->setCellValue('C1', ' ')
		->setCellValue('A2', 'Company : '.$company_name)
		->setCellValue('B2', ' ')
		->setCellValue('C2', ' ')
		->setCellValue('A3', $title)
		->setCellValue('B3', ' ')
		->setCellValue('C3', ' ')
		->setCellValue('A4', 'Brand : '.$my_brand_name)
		->setCellValue('B4', ' ')
		->setCellValue('C4', ' ')
		->setCellValue('A5', ' ')
		->setCellValue('B5', ' ')
		->setCellValue('C5', ' ')
		->setCellValue('A6', 'SITE')
		->setCellValue('B6', 'REGION')
		->setCellValue('C6', 'TYPE')
		->setCellValue('D6', 'TOWN')
		->setCellValue('E6', 'DATE')
		->setCellValue('F6', 'TIME')
		->setCellValue('G6', 'COMMENTS')
		->setCellValue('H6', 'MEN')
		->setCellValue('I6', 'WOMEN')
		->setCellValue('J6', 'CHILDREN');
		$PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
		$PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		/* 
		** Now to Add Values to the Spreadsheet
		** Start from 7th row
		** Pick Elements From The Array and Start Working The List
		*/
		$count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
		foreach ($result as $key_main_run) {
			$my_company_name=$key_main_run["company_name"];
		    $brand_name=$key_main_run["brand_name"];
		    $inspection_date=$key_main_run["inspection_date"];
		    $inspection_time=$key_main_run["inspection_time"];
		    $quality=$key_main_run["quality"];
		    $comment=$key_main_run["comment"];
		    $inspector_name=$key_main_run["inspector_name"];
		    $audience_male=$key_main_run["audience_male"];
		    $audience_female=$key_main_run["audience_female"];
		    $audience_children=$key_main_run["audience_children"];
		    $entry_type=$key_main_run["entry_type"];
		    $length=$key_main_run["length"];
		    $incantation_id=$key_main_run["incantation_id"];
		    $site_name=$key_main_run["site_name"];
		    $site_location=$key_main_run["site_location"];
		    $site_town=$key_main_run["town_name"];
		    $site_type=$key_main_run["site_type"];
		    $site_province=$key_main_run["province_name"];
			$PHPExcel->getActiveSheet()
	        ->setCellValue("A$count", $site_name)
	        ->setCellValue("B$count", $site_province)
	        ->setCellValue("C$count", $site_type)
	        ->setCellValue("D$count", substr($site_town,0,10))
	        ->setCellValue("E$count", $inspection_date)
	        ->setCellValue("F$count", $inspection_time)
	        ->setCellValue("G$count", $comment)
	        ->setCellValue("H$count", $audience_male)
	        ->setCellValue("I$count", $audience_female)
	        ->setCellValue("J$count", $audience_children);
	        $count++;
		}
		unset($styleArray);
		// Rename sheet
		$PHPExcel->getActiveSheet()->setTitle($sheet_name);
		// Set active sheet index to the right sheet, depending on the options,
		// so Excel opens this as the first sheet
		$PHPExcel->setActiveSheetIndex(0);
			
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        /* Save to File */
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/outdoor/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = $filename.'_'.'Outdoor_'.date("Ymdhis").'.xls';
        $objWriter->save($upload_path.$filename);
		return $filename;
	}
}



<?php

class BrandsSpendsExcel{
	public static function ExcelData($array,$period,$agency_name)
	{
		$PHPExcel = new PHPExcel();

		$title = 'The following is a Brand Summary Report for the period: '.$period;
				
		/* 
		** Set properties of the Excel Workbook 
		*/

		$PHPExcel->getProperties()->setCreator("Reelforge Systems")
		->setTitle("Reelforge Brand Summary Reports")
		->setSubject("Reelforge Brand Summary Reports")
		->setDescription("Reelforge Brand Summary Reports");

		$sheet_index = 0;
		$sheet_name = 'Brand Summary Report';


		$PHPExcel->createSheet(NULL, $sheet_index);

		$PHPExcel->setActiveSheetIndex($sheet_index)
		->setCellValue('A1', 'Brand Summary Report')
		->setCellValue('B1', ' ')
		->setCellValue('C1', ' ')
		->setCellValue('A2', $agency_name)
		->setCellValue('B2', ' ')
		->setCellValue('C2', ' ')
		->setCellValue('A3', $title)
		->setCellValue('B3', ' ')
		->setCellValue('C3', ' ')
		->setCellValue('A4', ' ')
		->setCellValue('B4', ' ')
		->setCellValue('C4', ' ')
		->setCellValue('A5', ' ')
		->setCellValue('B5', ' ')
		->setCellValue('C5', ' ')
		->setCellValue('A6', 'BRAND')
		->setCellValue('B6', 'MEDIA TYPE')
		->setCellValue('C6', 'RATE');

		$PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');

		$PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
		$PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);

		$count = 7;
		$styleArray = array('font'  => array('bold'  => true));

		$PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray);

        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);

        $this_total=0;

		foreach ($array as $key_main_run) {
			$brand_name=$key_main_run["brand_name"];
			$media_type=$key_main_run["station_type"];
		    $value=$key_main_run["sum"];
		    if($value){
                $this_total=$this_total+$value;
            }
			$PHPExcel->getActiveSheet()
	        ->setCellValue("A$count", $brand_name)
	        ->setCellValue("B$count", strtoupper($media_type))
	        ->setCellValue("C$count", number_format($value));
	        $count++;
		}
		$count=$count+2;
        $PHPExcel->getActiveSheet()
        ->setCellValue("A$count", 'Total')
        ->setCellValue("C$count", number_format($this_total));


		unset($styleArray);
		// Rename sheet
		$PHPExcel->getActiveSheet()->setTitle($sheet_name);

		// Set active sheet index to the right sheet, depending on the options,
		// so Excel opens this as the first sheet
		$PHPExcel->setActiveSheetIndex(0);
			
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        /* Save to File */
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/stationspends/excel/";
        $agency_name =str_replace(" ","_",$agency_name);
        $filename = $agency_name.'_Brand_Spends_'.date("Ymdhis").'.xls';
        $objWriter->save($upload_path.$filename);
		return $filename;

	}
	public static function AggregatedExcelData($array,$period,$agency_name)
	{
		$PHPExcel = new PHPExcel();

		$title = 'The following is a Brand Summary Report for the period: '.$period;
				
		/* 
		** Set properties of the Excel Workbook 
		*/

		$PHPExcel->getProperties()->setCreator("Reelforge Systems")
		->setTitle("Reelforge Brand Summary Reports")
		->setSubject("Reelforge Brand Summary Reports")
		->setDescription("Reelforge Brand Summary Reports");

		$sheet_index = 0;
		$sheet_name = 'Brand Summary Report';


		$PHPExcel->createSheet(NULL, $sheet_index);

		$PHPExcel->setActiveSheetIndex($sheet_index)
		->setCellValue('A1', 'Brand Summary Report')
		->setCellValue('B1', ' ')
		->setCellValue('C1', ' ')
		->setCellValue('A2', $agency_name)
		->setCellValue('B2', ' ')
		->setCellValue('C2', ' ')
		->setCellValue('A3', $title)
		->setCellValue('B3', ' ')
		->setCellValue('C3', ' ')
		->setCellValue('A4', ' ')
		->setCellValue('B4', ' ')
		->setCellValue('C4', ' ')
		->setCellValue('A5', ' ')
		->setCellValue('B5', ' ')
		->setCellValue('C5', ' ')
		->setCellValue('A6', 'BRAND')
		->setCellValue('B6', 'MEDIA TYPE')
		->setCellValue('C6', 'RATE');

		$PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');

		$PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(70);
		$PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);

		$count = 7;
		$styleArray = array('font'  => array('bold'  => true));

		$PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray);

        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);

        $this_total=0;

		foreach ($array as $key_main_run) {
			$media_type=$key_main_run["station_type"];
		    $value=$key_main_run["sum"];
		    if($value){
                $this_total=$this_total+$value;
            }
			$PHPExcel->getActiveSheet()
	        ->setCellValue("A$count", $media_type)
	        ->setCellValue("B$count", strtoupper($value));
	        $count++;
		}
		$count=$count+2;
        $PHPExcel->getActiveSheet()
        ->setCellValue("A$count", 'Total')
        ->setCellValue("B$count", number_format($this_total));


		unset($styleArray);
		// Rename sheet
		$PHPExcel->getActiveSheet()->setTitle($sheet_name);

		// Set active sheet index to the right sheet, depending on the options,
		// so Excel opens this as the first sheet
		$PHPExcel->setActiveSheetIndex(0);
			
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        /* Save to File */
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/stationspends/excel/";
        $agency_name =str_replace(" ","_",$agency_name);
        $filename = $agency_name.'_Agency_Summary_Spends_'.date("Ymdhis").'.xls';
        $objWriter->save($upload_path.$filename);
		return $filename;

	}
}
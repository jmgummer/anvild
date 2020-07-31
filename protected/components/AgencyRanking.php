<?php

class AgencyRanking{
	public static function CreateExcel($array,$startdate,$enddate){
		$PHPExcel = new PHPExcel();
	    $title = 'Agency Ranking Report for the period: between '.$startdate.' and '.$enddate;
	    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
	    ->setTitle("Reelforge Anvil Agency Ranking Report Reports")
	    ->setSubject("Reelforge Anvil Agency Ranking Report Reports")
	    ->setDescription("Reelforge Anvil Agency Ranking Report Reports");
	    
	    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
	    $sheet_index = 0;
		$PHPExcel->createSheet(NULL, $sheet_index);
		$PHPExcel->setActiveSheetIndex($sheet_index)
		->setCellValue('A1', 'Agency Ranking Report')
		->setCellValue('A2', "From $startdate to $enddate")
		->setCellValue('A4', 'Agency')
		->setCellValue('B4', 'Brand')
		->setCellValue('C4', 'Start Date')
		->setCellValue('D4', 'End Date')
		->setCellValue('E4', 'Print Spend')
		->setCellValue('F4', 'TV Spend')
		->setCellValue('G4', 'Radio Spend');

		$PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
		$PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
		$PHPExcel->getActiveSheet()->mergeCells('A3:Z3');

		$PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
		$PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
		$PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$count = 5;

	    foreach ($array as $brand_data) {
			$agency_name = $brand_data['agency_name'];
			$brand_name = $brand_data['brand_name'];
			$agencystartdate = $brand_data['agencystartdate'];
			$agencyenddate = $brand_data['agencyenddate'];
			$printspend = $brand_data['printspend'];
			$tvspend = $brand_data['tvspend'];
			$radiospend = $brand_data['radiospend'];

			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $agency_name)
			->setCellValue("B$count", $brand_name)
			->setCellValue("C$count", $agencystartdate)
			->setCellValue("D$count", $agencyenddate)
			->setCellValue("E$count", $printspend)
			->setCellValue("F$count", $tvspend)
			->setCellValue("G$count", $radiospend);
			$count++;
	    }
	    unset($styleArray);
	    $PHPExcel->getActiveSheet()->setTitle("Agency Ranking Report");

	    $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename = 'Agency_Ranking_Report_'.date("Ymdhis").'_'.'.xlsx';
        $objWriter->save($upload_path.$filename);
        return $filename;
	}
}
<?php

/**
* SummarySpendsExcel Component Class
* This Class Is Used To Return The Users/Company SummarySpendsExcel
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Anvil
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version         v.1.0
* @since       July 2008
*/

class SummarySpendsExcel{
	public static function MediaExcel($radio_array,$tv_array,$print_array,$industry)
	{
		$PHPExcel = new PHPExcel();
		$client = Yii::app()->user->company_name;
	    
        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");

        /* 
        Start with Radio Excelsheet 
        */
        $sheet_index = 0;
        $title = 'Radio Spend Summary';
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Media Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $cellstyleArray1 = array('font'  => array('width'  => 30));
        $cellstyleArray2 = array('font'  => array('width'  => 15));

        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);
        
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($radio_array as $key) {
			$media_name=$key["station_name"];
			$media_rate=number_format($key["station_rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $media_name)
			->setCellValue("B$count", $media_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("Radio Spend Summary");
		/* Radio Summary Ends Here */

		/*  TV Excelsheet  */
        $sheet_index = 1;
        $title = 'TV Spend Summary';
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Media Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $cellstyleArray1 = array('font'  => array('width'  => 30));
        $cellstyleArray2 = array('font'  => array('width'  => 15));

        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);
        
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($tv_array as $key) {
			$media_name=$key["station_name"];
			$media_rate=number_format($key["station_rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $media_name)
			->setCellValue("B$count", $media_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("TV Spend Summary");
		/* TV Summary Ends Here */

		/* 
        Print Excelsheet 
        */
        $sheet_index = 2;
        $title = 'Print Spend Summary';
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Media Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $cellstyleArray1 = array('font'  => array('width'  => 30));
        $cellstyleArray2 = array('font'  => array('width'  => 15));

        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);
        
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($print_array as $key) {
			$media_name=$key["station_name"];
			$media_rate=number_format($key["station_rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $media_name)
			->setCellValue("B$count", $media_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("Print Spend Summary");
		/* Print Summary Ends Here */

		/* Close off the Excel Workbook */
        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/summaryspends/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'_summary_spends_media.xls';
        $objWriter->save($upload_path.$filename);
		return $filename;
	}

	public static function IndustryExcel($radio_array,$tv_array,$print_array,$total_array,$industry)
	{
		$PHPExcel = new PHPExcel();
		$client = Yii::app()->user->company_name;
	    
        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");

        /* 
        Start with Radio Excelsheet 
        */
        $sheet_index = 0;
        $title = 'Radio Breakdown';
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Industry Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $cellstyleArray1 = array('font'  => array('width'  => 30));
        $cellstyleArray2 = array('font'  => array('width'  => 15));

        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);
        
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($radio_array as $key) {
			$industry_name=$key["industry_name"];
			$industry_rate=number_format($key["industry_rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $industry_name)
			->setCellValue("B$count", $industry_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("Radio Breakdown");
		/* Radio Summary Ends Here */

		/*  TV Excelsheet  */
        $sheet_index = 1;
        $title = 'TV Spend Summary';
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Industry Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $cellstyleArray1 = array('font'  => array('width'  => 30));
        $cellstyleArray2 = array('font'  => array('width'  => 15));

        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);
        
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($tv_array as $key) {
			$industry_name=$key["industry_name"];
			$industry_rate=number_format($key["industry_rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $industry_name)
			->setCellValue("B$count", $industry_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("TV Breakdown");
		/* TV Summary Ends Here */

		/* 
        Print Excelsheet 
        */
        $sheet_index = 2;
        $title = 'Print Spend Summary';
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Industry Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $cellstyleArray1 = array('font'  => array('width'  => 30));
        $cellstyleArray2 = array('font'  => array('width'  => 15));

        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);
        
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($print_array as $key) {
			$industry_name=$key["industry_name"];
			$industry_rate=number_format($key["industry_rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $industry_name)
			->setCellValue("B$count", $industry_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("Print Breakdown");
		/* Print Summary Ends Here */

		/* 
        Print Excelsheet 
        */
        $sheet_index = 3;
        $title = 'Total Breakdown Summary';
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Industry Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $cellstyleArray1 = array('font'  => array('width'  => 30));
        $cellstyleArray2 = array('font'  => array('width'  => 15));

        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);
        
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($total_array as $key) {
			$industry_name=$key["industry_name"];
			$industry_rate=number_format($key["industry_rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $industry_name)
			->setCellValue("B$count", $industry_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("Total Breakdown Summary");
		/* Print Summary Ends Here */

		/* Close off the Excel Workbook */
        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/summaryspends/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'_summary_spends_industry_breakdown.xls';
        $objWriter->save($upload_path.$filename);
		return $filename;
	}
}
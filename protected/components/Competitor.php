<?php
ini_set('memory_limit', '1024M');
/**
* Competitor Component Class
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Anvil
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/

class Competitor{
    public static function BrandPrintTable($temp_table,$query_IN,$brands,$industry_id,$report_start_date,$report_end_date)
    {
        /** 
        ** Obtain the list of companies
        ** Once you get to 20 Just stop
        */
        $x=0;
        $data = '';
        /* 
        ** This section if for the Tables for the Browser View 
        */
        $data .= '<div class="widget-body"><ul id="tabs" class="nav nav-tabs bordered">';
        $first_element = $brands[0];
        $active_tab = $first_element ['brand_id'];
        /* 
        ** Obtain the list of Brands
        ** Once you get to 20 Just stop
        */
        $tabindex=0;
        foreach ($brands as $brands_header){
            if($tabindex<20){
                $tab_id = $brands_header['brand_id'];
                $tab_name = $brands_header['brand_name'];
                if($tab_id==$active_tab){
                    $data .= '<li class="active"><a href="#'.$tab_id.'" data-toggle="tab">'.$tab_name.'</a></li>';
                }else{
                   $data .= '<li><a href="#'.$tab_id.'" data-toggle="tab">'.$tab_name.'</a></li>';
                }
                $tabindex++;
            }
        }
        $data .= '</ul>';
        $data .= '<div id="myTabContent1" class="tab-content padding-10">';

        foreach ($brands as $brandkey){
            $this_total=0;
            $this_spots_total=0;
            $this_brand_id = $brandkey['brand_id'];

            $sql_station_totals="select count(*) as count, sum(rate) as  rate, station_name 
            from $temp_table 
            where $temp_table.brand_id=$this_brand_id and $temp_table.mediatype='print' 
            group by $temp_table.station_id order by rate desc";
            if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                if($this_brand_id==$active_tab){
                    $data .= '<div class="tab-pane fade active in" id="'.$this_brand_id.'">';
                }else{
                    $data .= '<div class="tab-pane fade" id="'.$this_brand_id.'">';
                }
                $data .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                $data .= "<tr><td><strong>Station name</strong></td><td><strong>Total (".Yii::app()->user->country_code.")</strong></td><td><strong>Total Number of Spots</strong></td></tr>";
                foreach ($stationquery as $key) {
                    $station_totals_name=$key["station_name"];
                    $station_totals_total=$key["rate"];
                    if($station_totals_total){
                        $data .= "<tr><td>". $station_totals_name."</td><td>".number_format($station_totals_total)."</td><td>".$key["count"]."</td></tr>";
                        $this_total=$this_total+$station_totals_total;
                        $this_spots_total = $this_spots_total+$key["count"];
                    }
                }
                $data .= "<tr><td><strong>TOTAL</strong></td><td><strong>".number_format($this_total)."</strong></td><td><strong>".$this_spots_total."</strong></td></tr>";
                $data .= '</table>';
                $data .= '</div>';
            }

            $x++;
            
        }
        $data .= '</div>';
        $data .= '</div>';
        return $data;
    }

    public static function BrandPrintExcel($temp_table,$query_IN,$brands,$industry_id,$report_start_date,$report_end_date,$type){
        $PHPExcel = new PHPExcel();
        $title = "Industry Competitor (Brand) Report between: ".$report_start_date." and ".$report_end_date . "\n";

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        
        // Print Starts Here
        $sheet_index = 0;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Industry Competitor Reports')
        ->setCellValue('A2', 'Brand Report - Print')
        ->setCellValue('A3', $title);
        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $count = 6;
        $excelstations="SELECT distinct station_id, sum(rate) as rate, station_name FROM $temp_table WHERE $temp_table.mediatype='print' and rate!=0 
        group by station_id order by station_name ASC";
        if($stationsquery = Yii::app()->db3->createCommand($excelstations)->queryAll()){
            $totalstations = count($stationsquery);
            $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Brand Name');
            $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_name = $keyman['station_name'];
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_name);
                $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
                $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
                $stcount++;
            }
            $stcount=$stcount++;
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", 'Total');
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
        }else{
            $totalstations = 0;
        }

        $count = 7;
        foreach ($brands as $brandkey){
            $excel_brand_id = $brandkey['brand_id'];
            $excel_brand_name = $brandkey['brand_name'];
            $PHPExcel->getActiveSheet()->setCellValue("A$count", $excel_brand_name);
            $this_total=0;
            $this_brand_id = $brandkey['brand_id'];
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_id = $keyman['station_id'];
                $sql_station_totals="SELECT sum(rate) as rate from  $temp_table 
                where $temp_table.brand_id=$this_brand_id and 
                $temp_table.mediatype='print' and $temp_table.station_id=$st_id";
                if($stationbrand = Yii::app()->db3->createCommand($sql_station_totals)->queryRow()){
                    $stvalue = (int)$stationbrand['rate'];
                }else{
                    $stvalue = 0;
                }
                $this_total = $this_total+$stvalue;
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $stvalue);
                $stcount++;
            }
            $stcount=$stcount++;

            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $this_total);
            $count++;
        }
        $count=$count++;

        $grandtotal = 0;
        $PHPExcel->getActiveSheet()->setCellValue("A$count", ' ');
        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
        $stcount = 'B';
        foreach ($stationsquery as $keyman) {
            $st_valu = (int)$keyman['rate'];
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_valu);
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
            $grandtotal = $grandtotal+$st_valu;
            $stcount++;
        }
        $stcount=$stcount++;
        $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $grandtotal);
        $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);

        $PHPExcel->getActiveSheet()->setTitle('Print Media');

        // Electronic Starts Here

        $sheet_index = 1;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Industry Competitor Reports')
        ->setCellValue('A2', 'Brand Report - Electronic')
        ->setCellValue('A3', $title);
        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $count = 6;
        $excelstations="SELECT distinct station_id, sum(rate) as rate, station_name FROM $temp_table WHERE $temp_table.mediatype!='print' and rate!=0  
        group by station_id order by station_name ASC";
        if($stationsquery = Yii::app()->db3->createCommand($excelstations)->queryAll()){
            $totalstations = count($stationsquery);
            $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Brand Name');
            $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_name = $keyman['station_name'];
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_name);
                $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
                $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
                $stcount++;
            }
            $stcount=$stcount++;
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", 'Total');
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
        }else{
            $totalstations = 0;
        }

        $count = 7;
        $grandtotal = 0;
        foreach ($brands as $brandkey){
            $excel_brand_id = $brandkey['brand_id'];
            $excel_brand_name = $brandkey['brand_name'];
            $PHPExcel->getActiveSheet()->setCellValue("A$count", $excel_brand_name);
            $this_total=0;
            $this_brand_id = $brandkey['brand_id'];
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_id = $keyman['station_id'];
                $sql_station_totals="SELECT sum(rate) as rate from  $temp_table 
                where $temp_table.brand_id=$this_brand_id and 
                $temp_table.mediatype!='print' and $temp_table.station_id=$st_id";
                if($stationbrand = Yii::app()->db3->createCommand($sql_station_totals)->queryRow()){
                    $stvalue = (int)$stationbrand['rate'];
                }else{
                    $stvalue = 0;
                }
                $this_total = $this_total+$stvalue;
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $stvalue);
                $stcount++;
            }
            $stcount=$stcount++;

            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $this_total);
            $grandtotal = $grandtotal+$this_total;
            $count++;
        }
        $count=$count++;

        $grandtotal = 0;
        $PHPExcel->getActiveSheet()->setCellValue("A$count", ' ');
        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
        $stcount = 'B';
        foreach ($stationsquery as $keyman) {
            $st_valu = (int)$keyman['rate'];
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_valu);
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
            $grandtotal = $grandtotal+$st_valu;
            $stcount++;
        }
        $stcount=$stcount++;
        $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $grandtotal);
        $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);

        $PHPExcel->getActiveSheet()->setTitle('Electronic Media');

        // Summary Page

        $sheet_index = 2;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Industry Competitor Reports')
        ->setCellValue('A2', 'Brand Report - Summary')
        ->setCellValue('A3', $title);
        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $count = 6;
        $excelstations="SELECT distinct stationtype, sum(rate) as rate FROM $temp_table group by stationtype order by stationtype ASC";
        if($stationsquery = Yii::app()->db3->createCommand($excelstations)->queryAll()){
            $totalstations = count($stationsquery);
            $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Brand Name');
            $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_type = $keyman['stationtype'];
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_type);
                $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
                $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
                $stcount++;
            }
            $stcount=$stcount++;
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", 'Total');
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
        }else{
            $totalstations = 0;
        }

        $count = 7;
        $grandtotal = 0;
        foreach ($brands as $brandkey){
            $excel_brand_id = $brandkey['brand_id'];
            $excel_brand_name = $brandkey['brand_name'];
            $PHPExcel->getActiveSheet()->setCellValue("A$count", $excel_brand_name);
            $this_total=0;
            $this_brand_id = $brandkey['brand_id'];
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_type = $keyman['stationtype'];
                $sql_station_totals="SELECT sum(rate) as rate from  $temp_table 
                where $temp_table.brand_id=$this_brand_id AND $temp_table.stationtype='$st_type' ";
                if($stationbrand = Yii::app()->db3->createCommand($sql_station_totals)->queryRow()){
                    $stvalue = (int)$stationbrand['rate'];
                }else{
                    $stvalue = 0;
                }
                $this_total = $this_total+$stvalue;
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $stvalue);
                $stcount++;
            }
            $stcount=$stcount++;

            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $this_total);
            $grandtotal = $grandtotal+$this_total;
            $count++;
        }
        $count = $count++;

        $grandtotal = 0;
        $PHPExcel->getActiveSheet()->setCellValue("A$count", ' ');
        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
        $stcount = 'B';
        foreach ($stationsquery as $keyman) {
            $st_valu = (int)$keyman['rate'];
            $st_tt = $keyman['stationtype'];
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_valu);
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
            $grandtotal = $grandtotal+$st_valu;
            $stcount++;
        }
        $stcount=$stcount++;
        $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $grandtotal);
        $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);

        $PHPExcel->getActiveSheet()->setTitle('Summary Report');

        // Set active sheet index to the right sheet, depending on the options,
        // so Excel opens this as the first sheet
        $PHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        // /* Save to File */
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/competitor/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'competitor_brand_'.$type.'.xls';
        $objWriter->save($upload_path.$filename);
        return $filename;
        // return 'test.xls';
    }

    public static function BrandElectronicExcel($temp_table,$query_IN,$brands,$industry_id,$report_start_date,$report_end_date,$type)
    {
        /* 
        ** Obtain the list of Brands
        ** Once you get to 20 Just stop
        */
        $x=0;

        /* 
        ** This Section is for Excel
        ** Use the Results for Excel Processing 
        */

        $PHPExcel = new PHPExcel();

        $title = "Industry Competitor (Brand) Report between: ".$report_start_date." and ".$report_end_date . "\n";
            
        /* 
        ** Set properties of the Excel Workbook 
        */

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        
        $sheet_index = 0;
        foreach ($brands as $brandkey){
            // if($x<20){
                $excel_brand_id = $brandkey['brand_id'];
                $excel_brand_name = $brandkey['brand_name'];
                $PHPExcel->createSheet(NULL, $sheet_index);
                $PHPExcel->setActiveSheetIndex($sheet_index)
                ->setCellValue('A1', 'Reelforge Industry Competitor Reports (Brand Report)')
                ->setCellValue('B1', ' ')
                ->setCellValue('C1', ' ')
                ->setCellValue('A2', 'Brand : '.$excel_brand_name)
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
                ->setCellValue('A6', 'Station Name')
                ->setCellValue('B6', 'Total ('.Yii::app()->user->country_code.')')
                ->setCellValue('C6', 'Total Number of Spots');

                $styleArray = array('font'  => array('bold'  => true));
                $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
                $cellstyleArray1 = array('font'  => array('width'  => 30));
                $cellstyleArray2 = array('font'  => array('width'  => 15));

                

                $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
                $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
                $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);

                
                $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
                $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
                $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
                $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
                $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
                $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

                /* 
                ** Now to Add Values to the Spreadsheet
                ** Start from 7th row
                ** Pick Elements From The Array and Start Working The List
                */

                $count = 7;

                $this_total=0;
                $this_spots_total=0;
            
                // $data .= '<h6>'.$companykey['company_name'].'</h6>';
                $this_brand_id = $brandkey['brand_id'];
                $sql_station_totals="select count(*) as count, sum(rate) as  rate, station_name 
                from  $temp_table 
                where $temp_table.brand_id=$this_brand_id and $temp_table.mediatype='electronic' 
                group by $temp_table.station_id order by rate desc";
                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    foreach ($stationquery as $key) {
                        $station_totals_name=$key["station_name"];
                        $excel_totals_total=$key["rate"];
                        $station_totals_total=number_format($key["rate"]);
                        if($station_totals_total){
                            $this_total=$this_total+$excel_totals_total;
                            $this_spots_total = $this_spots_total + $key["count"];
                        }

                        $PHPExcel->getActiveSheet()
                        ->setCellValue("A$count", $station_totals_name)
                        ->setCellValue("B$count", $station_totals_total)
                        ->setCellValue("C$count", $key["count"]);
                        $count++;

                    }
                    unset($styleArray);
                }
                $count=$count+2;
                $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Total')->setCellValue("B$count", number_format($this_total))->setCellValue("C$count", $this_spots_total);
                $x++;
            // }
            // Rename sheet
            /* 
            ** Rename sheet, Remove any HTML Tags, truncate string
            ** make sure it ends in a word so assassinate doesn't become ass...
            */
            $string = strip_tags($excel_brand_name);
            $string =str_replace("/","_",$string);

            if (strlen($string) > 25) {
                $stringCut = substr($string, 0, 25);
                $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... '; 
            }
            
            $PHPExcel->getActiveSheet()->setTitle($string);
            $sheet_index++;
        }
        // Set active sheet index to the right sheet, depending on the options,
        // so Excel opens this as the first sheet
        $PHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        /* Save to File */
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/competitor/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'_competitor_brand_'.$type.'.xls';
        $objWriter->save($upload_path.$filename);
        return $filename;
    }

    public static function BrandElectronicTable($temp_table,$query_IN,$brands,$industry_id,$report_start_date,$report_end_date)
    {
        /* 
        ** Obtain the list of companies
        ** Once you get to 20 Just stop
        */
        $data = '';
        /* 
        ** This section if for the Tables for the Browser View 
        */
        $data .= '<div class="widget-body"><ul id="tabs" class="nav nav-tabs bordered">';
        $first_element = $brands[0];
        $active_tab = ($first_element ['brand_id']+21).'brandtab';
        /* 
        ** Obtain the list of Brands
        ** Once you get to 20 Just stop
        */
        $tabindex=21;
        foreach ($brands as $brands_header){
            if($tabindex<41){
                $tab_id = $brands_header['brand_id']+$tabindex.'brandtab';
                $tab_name = $brands_header['brand_name'];
                if($tab_id==$active_tab){
                    $data .= '<li class="active"><a href="#'.$tab_id.'" data-toggle="tab">'.$tab_name.'</a></li>';
                }else{
                   $data .= '<li><a href="#'.$tab_id.'" data-toggle="tab">'.$tab_name.'</a></li>';
                }
                $tabindex++;
            }
        }
        $data .= '</ul>';
        $data .= '<div id="myTabContent1" class="tab-content padding-10">';

        $x=21;

        foreach ($brands as $brandkey){
            $this_total=0;
            $this_spots_total=0;
            if($x<41){
                $this_brand_id = $brandkey['brand_id'];
                $sql_station_totals="select count(*) as count, sum(rate) as  rate, station_name 
                from  $temp_table 
                where $temp_table.brand_id=$this_brand_id and $temp_table.mediatype='electronic' 
                group by $temp_table.station_id order by rate desc";
                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    $tab_section_id = ($this_brand_id+$x).'brandtab';
                    if($tab_section_id==$active_tab){
                        $data .= '<div class="tab-pane fade active in" id="'.$tab_section_id.'">';
                    }else{
                        $data .= '<div class="tab-pane fade" id="'.$tab_section_id.'">';
                    }
                    $data .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                    $data .= "<tr><td><strong>Station name</strong></td><td><strong>Total (".Yii::app()->user->country_code.")</strong></td><td><strong>Total Number of Spots</strong></td></tr>";
                    foreach ($stationquery as $key) {
                        $station_totals_name=$key["station_name"];
                        $station_totals_total=$key["rate"];
                        if($station_totals_total){
                            $data .= "<tr><td>". $station_totals_name."</td><td>".number_format($station_totals_total)."</td><td>".$key["count"]."</td></tr>";
                            $this_total=$this_total+$station_totals_total;
                            $this_spots_total = $this_spots_total+$key["count"];
                        }
                    }
                    $data .= "<tr><td><strong>TOTAL</strong></td><td><strong>".number_format($this_total)."</strong></td><td><strong>".$this_spots_total."</strong></td></tr>";
                    $data .= '</table>';
                    $data .= '</div>';
                }
                $x++;
            }
        }
        $data .= '</div>';
        $data .= '</div>';
        return $data;
    }

    public static function CompetitorExcel($temp_table,$query_IN,$companies,$industry_id,$report_start_date,$report_end_date,$type){
        $PHPExcel = new PHPExcel();
        $title = "Industry Competitor (Company) Report between: ".$report_start_date." and ".$report_end_date . "\n";

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        
        // Print Starts Here
        $sheet_index = 0;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Industry Competitor Reports')
        ->setCellValue('A2', 'Company Report - Print')
        ->setCellValue('A3', $title);
        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $count = 6;
        $excelstations="SELECT distinct station_id, station_name, sum(rate) as rate FROM $temp_table WHERE $temp_table.mediatype='print' and rate!=0 
        group by station_id order by station_name ASC";
        if($stationsquery = Yii::app()->db3->createCommand($excelstations)->queryAll()){
            $totalstations = count($stationsquery);
            $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Company Name');
            $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_name = $keyman['station_name'];
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_name);
                $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
                $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
                $stcount++;
            }
            $stcount=$stcount++;
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", 'Total');
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
        }else{
            $totalstations = 0;
        }

        $count = 7;
        foreach ($companies as $companykey){
            $excel_brand_id = $companykey['company_id'];
            $excel_brand_name = $companykey['company_name'];
            $PHPExcel->getActiveSheet()->setCellValue("A$count", $excel_brand_name);
            $this_total=0;
            $this_company_id = $companykey['company_id'];
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_id = $keyman['station_id'];
                $sql_station_totals="SELECT sum(rate) as rate from  $temp_table 
                where $temp_table.company_id=$this_company_id and 
                $temp_table.mediatype='print' and $temp_table.station_id=$st_id";
                if($stationbrand = Yii::app()->db3->createCommand($sql_station_totals)->queryRow()){
                    $stvalue = (int)$stationbrand['rate'];
                }else{
                    $stvalue = 0;
                }
                $this_total = $this_total+$stvalue;
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $stvalue);
                $stcount++;
            }
            $stcount=$stcount++;

            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $this_total);
            $count++;
        }
        $count=$count++;

        $grandtotal = 0;
        $PHPExcel->getActiveSheet()->setCellValue("A$count", ' ');
        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
        $stcount = 'B';
        foreach ($stationsquery as $keyman) {
            $st_valu = (int)$keyman['rate'];
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_valu);
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
            $grandtotal = $grandtotal+$st_valu;
            $stcount++;
        }
        $stcount=$stcount++;
        $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $grandtotal);
        $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);

        $PHPExcel->getActiveSheet()->setTitle('Print Media');

        // Electronic Starts Here

        $sheet_index = 1;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Industry Competitor Reports')
        ->setCellValue('A2', 'Company Report - Electronic')
        ->setCellValue('A3', $title);
        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $count = 6;
        $excelstations="SELECT distinct station_id, sum(rate) as rate, station_name FROM $temp_table WHERE $temp_table.mediatype!='print' and rate!=0  
        group by station_id order by station_name ASC";
        if($stationsquery = Yii::app()->db3->createCommand($excelstations)->queryAll()){
            $totalstations = count($stationsquery);
            $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Company Name');
            $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_name = $keyman['station_name'];
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_name);
                $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
                $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
                $stcount++;
            }
            $stcount=$stcount++;
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", 'Total');
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
        }else{
            $totalstations = 0;
        }

        $count = 7;
        $grandtotal = 0;
        foreach ($companies as $companykey){
            $excel_brand_id = $companykey['company_id'];
            $excel_brand_name = $companykey['company_name'];
            $PHPExcel->getActiveSheet()->setCellValue("A$count", $excel_brand_name);
            $this_total=0;
            $this_company_id = $companykey['company_id'];
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_id = $keyman['station_id'];
                $sql_station_totals="SELECT sum(rate) as rate from  $temp_table 
                where $temp_table.company_id=$this_company_id and 
                $temp_table.mediatype!='print' and $temp_table.station_id=$st_id";
                if($stationbrand = Yii::app()->db3->createCommand($sql_station_totals)->queryRow()){
                    $stvalue = (int)$stationbrand['rate'];
                }else{
                    $stvalue = 0;
                }
                $this_total = $this_total+$stvalue;
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $stvalue);
                $stcount++;
            }
            $stcount=$stcount++;

            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $this_total);
            $grandtotal = $grandtotal+$this_total;
            $count++;
        }
        $count=$count++;

        $grandtotal = 0;
        $PHPExcel->getActiveSheet()->setCellValue("A$count", ' ');
        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
        $stcount = 'B';
        foreach ($stationsquery as $keyman) {
            $st_valu = (int)$keyman['rate'];
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_valu);
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
            $grandtotal = $grandtotal+$st_valu;
            $stcount++;
        }
        $stcount=$stcount++;
        $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $grandtotal);
        $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);

        $PHPExcel->getActiveSheet()->setTitle('Electronic Media');

        // Summary Page

        $sheet_index = 2;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Industry Competitor Reports')
        ->setCellValue('A2', 'Company Report - Summary')
        ->setCellValue('A3', $title);
        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);

        $count = 6;
        $excelstations="SELECT distinct station_type, sum(rate) as rate FROM $temp_table group by station_type order by station_type ASC";
        if($stationsquery = Yii::app()->db3->createCommand($excelstations)->queryAll()){
            $totalstations = count($stationsquery);
            $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Company Name');
            $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_type = $keyman['station_type'];
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_type);
                $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
                $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
                $stcount++;
            }
            $stcount=$stcount++;
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", 'Total');
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
        }else{
            $totalstations = 0;
        }

        $count = 7;
        $grandtotal = 0;
        foreach ($companies as $companykey){
            $excel_brand_id = $companykey['company_id'];
            $excel_brand_name = $companykey['company_name'];
            $PHPExcel->getActiveSheet()->setCellValue("A$count", $excel_brand_name);
            $this_total=0;
            $this_company_id = $companykey['company_id'];
            $stcount = 'B';
            foreach ($stationsquery as $keyman) {
                $st_type = $keyman['station_type'];
                $sql_station_totals="SELECT sum(rate) as rate from  $temp_table 
                where $temp_table.company_id=$this_company_id AND $temp_table.station_type='$st_type' ";
                if($stationbrand = Yii::app()->db3->createCommand($sql_station_totals)->queryRow()){
                    $stvalue = (int)$stationbrand['rate'];
                }else{
                    $stvalue = 0;
                }
                $this_total = $this_total+$stvalue;
                $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $stvalue);
                $stcount++;
            }
            $stcount=$stcount++;

            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $this_total);
            $grandtotal = $grandtotal+$this_total;
            $count++;
        }
        $count = $count++;

        $grandtotal = 0;
        $PHPExcel->getActiveSheet()->setCellValue("A$count", ' ');
        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray2);
        $stcount = 'B';
        foreach ($stationsquery as $keyman) {
            $st_valu = (int)$keyman['rate'];
            $st_tt = $keyman['station_type'];
            $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $st_valu);
            $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
            $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);
            $grandtotal = $grandtotal+$st_valu;
            $stcount++;
        }
        $stcount=$stcount++;
        $PHPExcel->getActiveSheet()->setCellValue("$stcount$count", $grandtotal);
        $PHPExcel->getActiveSheet()->getStyle("$stcount$count")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getColumnDimension("$stcount")->setWidth(15);

        $PHPExcel->getActiveSheet()->setTitle('Summary Report');

        // Set active sheet index to the right sheet, depending on the options,
        // so Excel opens this as the first sheet
        $PHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        // /* Save to File */
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/competitor/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'competitor_brand_'.$type.'.xlsx';
        $objWriter->save($upload_path.$filename);
        return $filename;
        // return 'test.xls';
    }

    
}
<?php

/**
* Common Component Class
* This Class Is Used To Return General, Common PHP Functions Running Across the Whole Application
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

class CommonFunctions{
	
	public static function CompanyAirplayTable($temp_table,$query_IN,$companies,$industry_id,$report_start_date,$report_end_date)
	{
		/* 
		** Obtain the list of companies
		** Once you get to 20 Just stop
		*/
		$x=0;
		$data = '';
		/* 
	    ** This section if for the Tables for the Browser View 
	    */
	    $data .= '<div class="widget-body"><ul id="tabs" class="nav nav-tabs bordered">';
	    $first_element = $companies[0];
	    $active_tab = $first_element ['company_id'];
	    /* 
		** Obtain the list of companies
		** Once you get to 20 Just stop
		*/
	    $tabindex=0;
	    foreach ($companies as $companies_header){
	    	if($tabindex<20){
	    		$tab_id = $companies_header['company_id'];
		        $tab_name = $companies_header['company_name'];
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

		foreach ($companies as $companykey){
			$this_total=0;
			$this_spots_total=0;
			if($x<20){
				// $data .= '<h6>'.$companykey['company_name'].'</h6>';
				$this_company_id = $companykey['company_id'];
				/* Mysql and MariaDB Made Changes so you cant do an empty IN() Query */
				$query_IN=str_replace("reelforge_sample",$temp_table,$query_IN);
				if(!empty($query_IN)){ $sub_industry_query = ' and brand_table.sub_industry_id IN ('.$query_IN.')'; }else{ $sub_industry_query = ''; }

				$sql_station_totals="select sum(duration) as  rate, count($temp_table.brand_id) as spots, station_name, station.station_id 
				from  $temp_table, brand_table, station where 
				$temp_table.company_id=$this_company_id and 
				brand_table.industry_id=$industry_id and 
				brand_table.brand_id=$temp_table.brand_id and 
				$temp_table.station_id=station.station_id and 
				date between '$report_start_date' and '$report_end_date' $sub_industry_query group by $temp_table.station_id order by rate desc";
				if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
					if($this_company_id==$active_tab){
		                $data .= '<div class="tab-pane fade active in" id="'.$this_company_id.'">';
		            }else{
		                $data .= '<div class="tab-pane fade" id="'.$this_company_id.'">';
		            }
					$data .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
					$data .= "<tr><td><strong>Station name</strong></td><td><strong>Airplay Time</strong></td><td><strong>No. of Spots</strong></td></tr>";
					foreach ($stationquery as $key) {
						$station_totals_name=$key["station_name"];
						$station_totals_id=$key["station_id"];
						$station_totals_total=$key["rate"];
						$station_totals_spots=$key["spots"];
						if($station_totals_total){
							$data .= "<tr><td>". $station_totals_name."</td><td>". BillBoardForm::FormattedTime($station_totals_total)."</td><td>". $station_totals_spots."</td></tr>";
							$this_total=$this_total+$station_totals_total;
							$this_spots_total=$this_spots_total+$station_totals_spots;
						}
					}
					$data .= "<tr><td><strong>TOTAL</strong></td><td><strong>". BillBoardForm::FormattedTime($this_total)."</strong></td><td><strong>". number_format($this_spots_total)."</strong></td></tr>";
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

    public static function CompanyCompetitionPrintTable($temp_table,$query_IN,$companies,$industry_id,$report_start_date,$report_end_date)
    {
        /* 
        ** Obtain the list of companies
        ** Once you get to 20 Just stop
        */
        $this_country_code = Yii::app()->user->country_code;
        $x=0;
        $data = '';
        /* 
        ** This section if for the Tables for the Browser View 
        */
        $data .= '<div class="widget-body"><ul id="tabs" class="nav nav-tabs bordered">';
        $first_element = $companies[0];
        $active_tab = $first_element ['company_id'];
        /* 
        ** Obtain the list of companies
        ** Once you get to 20 Just stop
        */
        $tabindex=0;
        foreach ($companies as $companies_header){
            if($tabindex<20){
                $tab_id = $companies_header['company_id'];
                $tab_name = $companies_header['company_name'];
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

        foreach ($companies as $companykey){
            $this_total=0;
            $this_spots_total=0;
            if($x<20){
                $this_company_id = $companykey['company_id'];

                $sql_station_totals="select count(*) as count, sum(rate) as  rate, station_name 
                from $temp_table 
                where $temp_table.company_id=$this_company_id and $temp_table.mediatype='print' 
                group by $temp_table.station_id order by rate desc";

                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    if($this_company_id==$active_tab){
                        $data .= '<div class="tab-pane fade active in" id="'.$this_company_id.'">';
                    }else{
                        $data .= '<div class="tab-pane fade" id="'.$this_company_id.'">';
                    }
                    $data .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                    $data .= "<tr><td><strong>Station name</strong></td><td><strong>Total (".Yii::app()->user->country_code.")</strong></td><td><strong>Total Number of Spots</strong></td></tr>";
                    foreach ($stationquery as $key) {
                        $station_totals_name=$key["station_name"];
                        $station_totals_total=$key["rate"];
                        if($station_totals_total){
                            $data .= "<tr><td>". $station_totals_name."</td><td>".number_format($station_totals_total)."</td><td>".$key["count"]."</td></tr>";
                            $this_total=$this_total+$station_totals_total;
                            $this_spots_total = $this_spots_total + $key["count"];
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

    public static function AnvilCompetitorPrintExcel($temp_table,$query_IN,$companies,$industry_id,$report_start_date,$report_end_date)
    {
        /* 
        ** Obtain the list of companies
        ** Once you get to 20 Just stop
        */
        $x=0;

        /* 
        ** This Section is for Excel
        ** Use the Results for Excel Processing 
        */

        $PHPExcel = new PHPExcel();

        $title = "Industry Competitor (Company) Report between: ".$report_start_date." and ".$report_end_date . "\n";
            
        /* 
        ** Set properties of the Excel Workbook 
        */

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        
        $sheet_index = 0;
        foreach ($companies as $companykey){
            // if($x<20){
                $excel_company_id = $companykey['company_id'];
                $excel_company_name = $companykey['company_name'];
                $PHPExcel->createSheet(NULL, $sheet_index);
                $PHPExcel->setActiveSheetIndex($sheet_index)
                ->setCellValue('A1', 'Reelforge Industry Competitor (Company) Report')
                ->setCellValue('B1', ' ')
                ->setCellValue('C1', ' ')
                ->setCellValue('A2', 'Company : '.$excel_company_name)
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
                $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

                $this_total=0;
                $this_spots_total=0;
                $this_company_id = $companykey['company_id'];

                $sql_station_totals="select count(*) as count, sum(rate) as  rate, station_name 
                from $temp_table 
                where $temp_table.company_id=$this_company_id and $temp_table.mediatype='print' 
                group by $temp_table.station_id order by rate desc";

                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    foreach ($stationquery as $key) {
                        $station_totals_name=$key["station_name"];
                        $station_totals_total=$key["rate"];
                        $excel_totals_total=$station_totals_total;
                        $station_totals_total=number_format($station_totals_total);
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
                    $count=$count+2;
                    $PHPExcel->getActiveSheet()
                    ->setCellValue("A$count", 'Total')
                    ->setCellValue("B$count", number_format($this_total))
                    ->setCellValue("C$count", $this_spots_total);

                    unset($styleArray);
                }
                $x++;

                
            // Rename sheet
            /* 
            ** Rename sheet, Remove any HTML Tags, truncate string
            ** make sure it ends in a word so assassinate doesn't become ass...
            */
            $string = strip_tags($excel_company_name);

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
        $filename = date("Ymdhis").'_'.$filename.'competitor_company_print.xls';
        $objWriter->save($upload_path.$filename);
        return $filename;
    }

    public static function CompanyCompetitionElectronicTable($temp_table,$query_IN,$companies,$industry_id,$report_start_date,$report_end_date)
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
        $first_element = $companies[0];
        $active_tab_name = str_replace(" ", "_", $first_element['company_name']);
        $active_tab = ($first_element ['company_id']+21).'companytab'.$active_tab_name;
        /* 
        ** Obtain the list of companies
        ** Once you get to 20 Just stop
        */
        $tabindex=21;
        foreach ($companies as $companies_header){
            if($tabindex<41){
                $tab_name = str_replace(" ", "_", $companies_header['company_name']);
                $tab_id = $companies_header['company_id']+$tabindex.'companytab'.$tab_name;
                if($tab_id==$active_tab){
                    $data .= '<li class="active"><a href="#'.$tab_id.'" data-toggle="tab">'.str_replace("_", " ", $tab_name).'</a></li>';
                }else{
                   $data .= '<li><a href="#'.$tab_id.'" data-toggle="tab">'.str_replace("_", " ", $tab_name).'</a></li>';
                }
                $tabindex++;
            }
        }
        $data .= '</ul>';
        $data .= '<div id="myTabContent1" class="tab-content padding-10">';

        $x=21;

        foreach ($companies as $tcompanykey){
            $section_name = str_replace(" ", "_", $tcompanykey['company_name']);
            $this_total=0;
            $this_spots_total=0;
            if($x<41){
                $this_company_id = $tcompanykey['company_id'];

                $sql_station_totals="select count(*) as count, sum(rate) as  rate, station_name 
                from $temp_table 
                where $temp_table.company_id=$this_company_id and $temp_table.mediatype='electronic' 
                group by $temp_table.station_id order by rate desc";

                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    $tab_section_id = ($this_company_id+$x).'companytab'.$section_name;
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
                            $this_spots_total = $this_spots_total + $key["count"];
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

    public static function CompanyAirplayBrandTable($temp_table,$query_IN,$brands,$industry_id,$report_start_date,$report_end_date)
    {
        /* 
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
            if($x<20){
                // $data .= '<h6>'.$companykey['company_name'].'</h6>';
                $this_brand_id = $brandkey['brand_id'];
                /* Mysql and MariaDB Made Changes so you cant do an empty IN() Query */

                $sql_station_totals="select sum(duration) as  rate, count($temp_table.brand_id) as spots, station_name, station.station_id 
                from  $temp_table, brand_table, station where 
                $temp_table.brand_id=$this_brand_id and 
                brand_table.industry_id=$industry_id and 
                brand_table.brand_id=$temp_table.brand_id and 
                $temp_table.station_id=station.station_id and 
                date between '$report_start_date' and '$report_end_date' group by $temp_table.station_id order by rate desc";
                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    if($this_brand_id==$active_tab){
                        $data .= '<div class="tab-pane fade active in" id="'.$this_brand_id.'">';
                    }else{
                        $data .= '<div class="tab-pane fade" id="'.$this_brand_id.'">';
                    }
                    $data .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                    $data .= "<tr><td><strong>Station name</strong></td><td><strong>Airplay Time</strong></td><td><strong>No. of Spots</strong></td></tr>";
                    foreach ($stationquery as $key) {
                        $station_totals_name=$key["station_name"];
                        $station_totals_id=$key["station_id"];
                        $station_totals_total=$key["rate"];
                        $station_totals_spots=$key["spots"];
                        if($station_totals_total){
                            $data .= "<tr><td>". $station_totals_name."</td><td>". BillBoardForm::FormattedTime($station_totals_total)."</td><td>". $station_totals_spots."</td></tr>";
                            $this_total=$this_total+$station_totals_total;
                            $this_spots_total=$this_spots_total+$station_totals_spots;
                        }
                    }
                    $data .= "<tr><td><strong>TOTAL</strong></td><td><strong>". BillBoardForm::FormattedTime($this_total)."</strong></td><td><strong>". number_format($this_spots_total)."</strong></td></tr>";
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

	public static function AnvilAirplayExcel($temp_table,$query_IN,$companies,$industry_id,$report_start_date,$report_end_date)
	{
		/* 
		** Obtain the list of companies
		** Once you get to 20 Just stop
		*/
		$x=0;

	    /* 
	    ** This Section is for Excel
	    ** Use the Results for Excel Processing 
	    */

	    $PHPExcel = new PHPExcel();

	    $title = "Industry Competitor (Total Airplay) Report between: ".$report_start_date." and ".$report_end_date . "\n";
            
        /* 
        ** Set properties of the Excel Workbook 
        */

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        
        $sheet_index = 0;
        foreach ($companies as $companykey){
            if($x<20){
            	$excel_company_id = $companykey['company_id'];
                $excel_company_name = $companykey['company_name'];
                $PHPExcel->createSheet(NULL, $sheet_index);
                $PHPExcel->setActiveSheetIndex($sheet_index)
                ->setCellValue('A1', 'Reelforge Industry Competitor (Total Airplay) Report')
                ->setCellValue('B1', ' ')
                ->setCellValue('C1', ' ')
                ->setCellValue('A2', 'Company : '.$excel_company_name)
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
                ->setCellValue('B6', 'Airplay Time')
                ->setCellValue('C6', 'No. of Spots');

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
                $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

                $this_total=0;
                $this_spots_total=0;
            
                // $data .= '<h6>'.$companykey['company_name'].'</h6>';
                $this_company_id = $companykey['company_id'];
                /* Mysql and MariaDB Made Changes so you cant do an empty IN() Query */
                $query_IN=str_replace("reelforge_sample",$temp_table,$query_IN);
                if(!empty($query_IN)){ $sub_industry_query = ' and brand_table.sub_industry_id IN ('.$query_IN.')'; }else{ $sub_industry_query = ''; }

                $sql_station_totals="select sum(duration) as  rate, count($temp_table.brand_id) as spots, station_name, station.station_id 
                from  $temp_table, brand_table, station where 
                $temp_table.company_id=$this_company_id and 
                brand_table.industry_id=$industry_id and 
                brand_table.brand_id=$temp_table.brand_id and 
                $temp_table.station_id=station.station_id and 
                date between '$report_start_date' and '$report_end_date' $sub_industry_query group by $temp_table.station_id order by rate desc";
                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    foreach ($stationquery as $key) {
                        $station_totals_name=$key["station_name"];
                        $station_totals_id=$key["station_id"];
                        $station_totals_total=$key["rate"];
                        $station_totals_spots=$key["spots"];

                        $PHPExcel->getActiveSheet()
                        ->setCellValue("A$count", $station_totals_name)
                        ->setCellValue("B$count", $station_totals_total)
                        ->setCellValue("C$count", $station_totals_spots);
                        $count++;

                    }
                    unset($styleArray);
                }
                $x++;
            }
            // Rename sheet
            /* 
            ** Rename sheet, Remove any HTML Tags, truncate string
            ** make sure it ends in a word so assassinate doesn't become ass...
            */
            $string = strip_tags($excel_company_name);

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
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/airplay/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'airplay.xls';
        $objWriter->save($upload_path.$filename);
		return $filename;
	}

    public static function AnvilAirplayBrandExcel($temp_table,$query_IN,$brands,$industry_id,$report_start_date,$report_end_date)
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

        $title = "Industry Airplay Reports (Brand Report) Report between: ".$report_start_date." and ".$report_end_date . "\n";
            
        /* 
        ** Set properties of the Excel Workbook 
        */

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        
        $sheet_index = 0;
        foreach ($brands as $brandkey){
            if($x<20){
                $excel_brand_id = $brandkey['brand_id'];
                $excel_brand_name = $brandkey['brand_name'];
                $PHPExcel->createSheet(NULL, $sheet_index);
                $PHPExcel->setActiveSheetIndex($sheet_index)
                ->setCellValue('A1', 'Reelforge Industry Airplay Reports (Brand Report)')
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
                ->setCellValue('B6', 'Airplay Time')
                ->setCellValue('C6', 'No. of Spots');

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
                /* Mysql and MariaDB Made Changes so you cant do an empty IN() Query */

                $sql_station_totals="select sum(duration) as  rate, count($temp_table.brand_id) as spots, station_name, station.station_id 
                from  $temp_table, brand_table, station where 
                $temp_table.brand_id=$this_brand_id and 
                brand_table.industry_id=$industry_id and 
                brand_table.brand_id=$temp_table.brand_id and 
                $temp_table.station_id=station.station_id and 
                date between '$report_start_date' and '$report_end_date' group by $temp_table.station_id order by rate desc";
                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    foreach ($stationquery as $key) {
                        $station_totals_name=$key["station_name"];
                        $station_totals_id=$key["station_id"];
                        $station_totals_total=BillBoardForm::FormattedTime($key["rate"]);
                        $station_totals_spots=$key["spots"];

                        $PHPExcel->getActiveSheet()
                        ->setCellValue("A$count", $station_totals_name)
                        ->setCellValue("B$count", $station_totals_total)
                        ->setCellValue("C$count", $station_totals_spots);
                        $count++;

                    }
                    unset($styleArray);
                }
                $x++;
            }
            // Rename sheet
            /* 
            ** Rename sheet, Remove any HTML Tags, truncate string
            ** make sure it ends in a word so assassinate doesn't become ass...
            */
            $string = strip_tags($excel_brand_name);

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
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/airplay/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'airplay.xls';
        $objWriter->save($upload_path.$filename);
        return $filename;
    }

    public static function AnvilCompetitorElectronicExcel($temp_table,$query_IN,$companies,$industry_id,$report_start_date,$report_end_date)
    {
        /* 
        ** Obtain the list of companies
        ** Once you get to 20 Just stop
        */
        $x=0;

        /* 
        ** This Section is for Excel
        ** Use the Results for Excel Processing 
        */

        $PHPExcel = new PHPExcel();

        $title = "Industry Competitor (Company) Report between: ".$report_start_date." and ".$report_end_date . "\n";
            
        /* 
        ** Set properties of the Excel Workbook 
        */

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        
        $sheet_index = 0;
        foreach ($companies as $companykey){
            // if($x<20){
                $excel_company_id = $companykey['company_id'];
                $excel_company_name = $companykey['company_name'];
                $PHPExcel->createSheet(NULL, $sheet_index);
                $PHPExcel->setActiveSheetIndex($sheet_index)
                ->setCellValue('A1', 'Reelforge Industry Competitor (Company) Report')
                ->setCellValue('B1', ' ')
                ->setCellValue('C1', ' ')
                ->setCellValue('A2', 'Company : '.$excel_company_name)
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
                $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

                $this_total=0;
                $this_spots_total=0;
                $this_company_id = $companykey['company_id'];

                $sql_station_totals="select count(*) as count, sum(rate) as  rate, station_name 
                from $temp_table 
                where $temp_table.company_id=$this_company_id and $temp_table.mediatype='electronic' 
                group by $temp_table.station_id order by rate desc";

                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
                    foreach ($stationquery as $key) {
                        $station_totals_name=$key["station_name"];
                        $station_totals_total=$key["rate"];
                        $excel_totals_total=$station_totals_total;
                        $station_totals_total=number_format($station_totals_total);
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
                    $count=$count+2;
                    $PHPExcel->getActiveSheet()
                    ->setCellValue("A$count", 'Total')
                    ->setCellValue("B$count", number_format($this_total))
                    ->setCellValue("C$count", $this_spots_total);

                    unset($styleArray);
                }
                $x++;
            // }
            // Rename sheet
            /* 
            ** Rename sheet, Remove any HTML Tags, truncate string
            ** make sure it ends in a word so assassinate doesn't become ass...
            */
            $string = strip_tags($excel_company_name);

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
        $filename = date("Ymdhis").'_'.$filename.'competitor_company_electronic.xls';
        $objWriter->save($upload_path.$filename);
        return $filename;
    }

    
}
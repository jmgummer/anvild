<?php
/**
* POFExcel Component Class
* This Class Is Used To Return POF Excel Data
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Anvil
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class POFExcel{
	
	public static function POFExcel1($array,$period,$client)
	{
		$PHPExcel = new PHPExcel();

		$title = 'The following is a Proof of Flight Summary Report  the period: '.$period;
				
		/* 
		** Set properties of the Excel Workbook 
		*/

		$PHPExcel->getProperties()->setCreator("Reelforge Systems")
		->setTitle("Reelforge Anvil Proof of Flight Reports")
		->setSubject("Reelforge Anvil Proof of Flight Reports")
		->setDescription("Reelforge Anvil Proof of Flight Reports");

		/* 
		** The Summary Page
		** Add some data
		*/

		$PHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Reelforge Proof of Flight Report')
		->setCellValue('B1', ' ')
		->setCellValue('C1', ' ')
		->setCellValue('A2', 'Summary')
		->setCellValue('B2', ' ')
		->setCellValue('C2', ' ')
		->setCellValue('A3', $title)
		->setCellValue('B3', ' ')
		->setCellValue('C3', ' ')
		->setCellValue('A4', 'Client : '.Yii::app()->user->company_name)
		->setCellValue('B4', ' ')
		->setCellValue('C4', ' ')
		->setCellValue('A5', ' ')
		->setCellValue('B5', ' ')
		->setCellValue('C5', ' ')
		->setCellValue('A6', 'Ad Name')
		->setCellValue('B6', 'Brand Name')
		->setCellValue('C6', 'Date')
		->setCellValue('D6', 'Time')
		->setCellValue('E6', 'Type')
		->setCellValue('F6', 'Duration(h:m:s)')
		->setCellValue('G6', 'Rate');

		$PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

		/* 
		** Now to Add Values to the Spreadsheet
		** Start from 7th row
		** Pick Elements From The Array and Start Working The List
		*/

		$count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

		foreach ($array as $elements) {
			$link = $elements->link;
			$PHPExcel->getActiveSheet()
	        ->setCellValue("A$count", $elements->adname)
	        ->setCellValue("B$count", $elements->brandname)
	        ->setCellValue("C$count", $elements->date)
	        ->setCellValue("D$count", $elements->time)
	        ->setCellValue("E$count", $elements->type)
	        ->setCellValue("F$count", $elements->duration)
	        ->setCellValue("G$count", $elements->rate);
	        $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
	        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
	        $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
	        $count++;
		}
		unset($styleArray);
		// Rename sheet
		$PHPExcel->getActiveSheet()->setTitle($station_name);
		

			
		
		// Set active sheet index to the right sheet, depending on the options,
		// so Excel opens this as the first sheet
		$PHPExcel->setActiveSheetIndex(0);
			
		// Redirect output to a clients web browser (Excel2003)
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment;filename="Proof_of_Flight_Report.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	public static function POFElectronic()
	{
		/* 
		Run The Query to Generate the Temp Table if it Doesn't Exist 
		Called it Union Because its one hell of a union, created a temporary table out of union selects
		Not sure if that was cost effective in the long run. This needs to be tested
		*/
		$set_brands	= $_SESSION['brands'];
		$startdate	= $_SESSION['startdate'];
		$enddate 	= $_SESSION['enddate'];
		$currency 	= $_SESSION['currency'];
		$station_query = $_SESSION['station_query'];
		$ad_query = $_SESSION["ad_query"];
		$reportformat = $_SESSION['reportformat'];
		$adtypes = $_SESSION['search_entry_type'];

		$linkurl = Yii::app()->params['eleclink'];

		/* Date Formating Starts Here */

		$year_start     = date('Y',strtotime($startdate));  
		$month_start    = date('m',strtotime($startdate));  
		$day_start      = date('d',strtotime($startdate));
		$year_end       = date('Y',strtotime($enddate)); 
		$month_end      = date('m',strtotime($enddate)); 
		$day_end        = date('d',strtotime($enddate));

		/* 
		** DJmentions
		** Query Preparation - Loop through years, months, days
		*/
		$temp_table = Common::POFTempTable();

		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="djmentions_"  .$x."_".$my_month;
		        $mentions_sql = 'insert into '.$temp_table.' (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,program_name) 
		        select 
		        distinct('.$temp_table_month.'.auto_id) as auto_id,
		        '.$temp_table_month.'.brand_id as brand_id,
		        '.$temp_table_month.'.entry_type_id as entry_type_id,
		        "incantation_id",
		        '.$temp_table_month.'.station_id as station_id,
		        '.$temp_table_month.'.date as date,
		        '.$temp_table_month.'.time as time,
		        '.$temp_table_month.'.comment as comment,
		        '.$temp_table_month.'.rate as rate,
		        brand_table.brand_name as brand_name,
		        djmentions_entry_types.entry_type as entry_type,
		        brand_table.brand_name as incantation_name,
		        '.$temp_table_month.'.duration as duration,
		        CONCAT('.$temp_table_month.'.file_path, "", '.$temp_table_month.'.filename) as file,
		        "video_file"  ,
        		'.$temp_table_month.'.Program as program_name
		        FROM '.$temp_table_month.', brand_table, djmentions_entry_types, station
		        WHERE '.$temp_table_month.'.'.$station_query.' 
		        AND '.$ad_query.'
		        AND '.$temp_table_month.'.brand_id IN ('.$set_brands.')
		        AND '.$temp_table_month.'.brand_id=brand_table.brand_id 
		        AND '.$temp_table_month.'.entry_type_id=djmentions_entry_types.entry_type_id AND '.$temp_table_month.'.active=1 
		        AND date between "'.$startdate.'" and "'.$enddate.'"';

		        $insertsql = Yii::app()->db3->createCommand($mentions_sql)->execute();
		    }
		}

		/* 
		** Reelforge Sample
		** Query Preparation - Loop through years, months, days
		*/

		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="reelforge_sample_"  .$x."_".$my_month;
		        $sample_sql = 'insert into '.$temp_table.' (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file) 
		        select 
		        distinct('.$temp_table_month.'.reel_auto_id) as auto_id,
		        '.$temp_table_month.'.brand_id as brand_id,
		        '.$temp_table_month.'.entry_type_id as entry_type_id,
		        '.$temp_table_month.'.incantation_id as incantation_id,
		        '.$temp_table_month.'.station_id as station_id,
		        '.$temp_table_month.'.reel_date as date,
		        '.$temp_table_month.'.reel_time as time,
		        '.$temp_table_month.'.comment as comment,
		        '.$temp_table_month.'.rate as rate,
		        brand_table.brand_name as brand_name,
		        djmentions_entry_types.entry_type as entry_type,
		        incantation.incantation_name as incantation_name,
		        incantation.incantation_length as duration,
		        CONCAT(incantation.file_path, "", incantation.incantation_file) as file,
		        CONCAT(incantation.file_path, "", incantation.mpg_path) as video_file
		        FROM '.$temp_table_month.', incantation,user_table,brand_table,djmentions_entry_types
		        WHERE '.$temp_table_month.'.'.$station_query.' 
		        AND '.$temp_table_month.'.brand_id IN ('.$set_brands.')
		        AND '.$ad_query.' 
		        AND '.$temp_table_month.'.brand_id=brand_table.brand_id 
		        AND '.$temp_table_month.'.incantation_id=incantation.incantation_id 
		        AND user_table.company_id='.$temp_table_month.'.company_id AND '.$temp_table_month.'.active=1  
		        AND '.$temp_table_month.'.entry_type_id=djmentions_entry_types.entry_type_id 
		        AND '.$temp_table_month.'.reel_date between "'.$startdate.'" and "'.$enddate.'" ';
		        $insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
		    }
		}

		/* Delete Records That Do Not Belong Here, 
		** Based on Brands & Stations Assigned to Them
		** Delete all where not in Stations Should Work
		** DELETE ONLY WHERE THE USER IS AN AGENCY
		*/
		if(Yii::app()->user->usertype=='agency'){
		    $brands = explode(',', $set_brands);
		    foreach ($brands as $brand_key) {
		        $strict_brands = $brand_key;
		        $agency_id = Yii::app()->user->company_id;
		        if($brand_stations = AgencyBrandStation::model()->findAll('agency_id=:a AND brand_id=:b', array(':a'=>$agency_id, ':b'=>$strict_brands))){
		            $stations_select = array();
		            foreach ($brand_stations as $delete_stations) {
		                $stations_select[] = $delete_stations->station_id;
		            }
		            $ok_stations = implode(', ', $stations_select);
		            $ok_stations_query = "DELETE FROM $temp_table WHERE brand_id =$strict_brands AND station_id NOT IN ($ok_stations)";
		            $deletesql = Yii::app()->db3->createCommand($ok_stations_query)->execute();
		        }
		    }
		}

		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		$stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll();

		$PHPExcel = new PHPExcel();
	    $title = 'The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate;
	    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
	    ->setTitle("Reelforge Anvil Proof of Flight Reports")
	    ->setSubject("Reelforge Anvil Proof of Flight Reports")
	    ->setDescription("Reelforge Anvil Proof of Flight Reports");
	    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);
	    
	    $sheet_index = 0;
	    foreach ($stored_stations as $excel_stations) {
	    	
	    	$adtypes = $_SESSION['search_entry_type'];

	        $station_id = $excel_stations['station_id'];
	        $station_details = 'SELECT station_name, station_type from station where station_id = '.$station_id;
	        if($station_details = Station::model()->findBySql($station_details)){
	            $station_name = $station_details->station_name;
	            $station_type = $station_details->station_type;
	        }else{
	            $station_type = 'Unknown';
	            $station_name = 'Unknown';
	        }
	        $excel_station_type = $station_type;
	        $excel_station_id = $excel_stations['station_id'];
	        $excel_station_name = $station_name;
	        $PHPExcel->createSheet(NULL, $sheet_index);
	        $PHPExcel->setActiveSheetIndex($sheet_index)
	        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
	        ->setCellValue('B1', ' ')
	        ->setCellValue('C1', ' ')
	        ->setCellValue('A2', 'Station : '.$excel_station_name)
	        ->setCellValue('B2', ' ')
	        ->setCellValue('C2', ' ')
	        ->setCellValue('A3', $title)
	        ->setCellValue('B3', ' ')
	        ->setCellValue('C3', ' ')
	        ->setCellValue('A4', 'Client : '.Yii::app()->user->company_name)
	        ->setCellValue('B4', ' ')
	        ->setCellValue('C4', ' ')
	        ->setCellValue('A5', ' ')
	        ->setCellValue('B5', ' ')
	        ->setCellValue('C5', ' ')
	        ->setCellValue('A6', 'Ad Name')
	        ->setCellValue('B6', 'Brand Name')
	        ->setCellValue('C6', 'Date')
	        ->setCellValue('D6', 'Time')
	        ->setCellValue('E6', 'Type')
	        ->setCellValue('F6', 'Duration(h:m:s)')
	        ->setCellValue('G6', 'Rate ('.$currency.')');

	        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
	        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
	        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
	        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');

	        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
	        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
	        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

	        /* 
	        ** Now to Add Values to the Spreadsheet
	        ** Start from 7th row
	        ** Pick Elements From The Array and Start Working The List
	        */

	        $count = 7;
	        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
	        if($adtypes==1){
		        $adtypes = 'SELECT distinct entry_type  FROM '.$temp_table.' WHERE station_id='.$excel_station_id.' order by date, time';
		        if($found_adtypes = Yii::app()->db3->createCommand($adtypes)->queryAll()){
		        	foreach ($found_adtypes as $adkey) {
	                    $adname = $adkey['entry_type'];
	                    $union_select1 = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$excel_station_id.' AND entry_type="'.$adname.'" order by date, time';
	                    if($excel_data = Yii::app()->db3->createCommand($union_select1)->queryAll())
				        {
				        	$count = $count+1;
			        		$PHPExcel->getActiveSheet()->setCellValue("A$count", $adname);
			        		$count = $count+1;
				            foreach ($excel_data as $elements) {

				            	$entry_identifier = $elements['entry_type_id'];

				                if($excel_station_type=='radio'){
				                    $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
				                    $link = $linkurl.$data_this_file_path;
				                    $link=str_replace("wav","mp3",$link);
				                }else{
				                    if($elements['video_file']=='video_file'){
				                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
				                        $link = $linkurl.$data_this_file_path;
				                        $link=str_replace("wav","mp3",$link);
				                    }else{
				                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['video_file']);
				                        $link = $linkurl.$data_this_file_path;
				                    }
				                }

				                if($entry_identifier==4){
				                	$elements['incantation_name'] = $elements['program_name'];
				                }

				                $PHPExcel->getActiveSheet()
				                ->setCellValue("A$count", $elements['incantation_name'])
				                ->setCellValue("B$count", $elements['brand_name'])
				                ->setCellValue("C$count", $elements['date'])
				                ->setCellValue("D$count", $elements['time'])
				                ->setCellValue("E$count", $elements['entry_type'])
				                ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
				                ->setCellValue("G$count", number_format((float)$elements['rate']));
				                $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
				                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
				                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
				                $count++;
				            }
				            // unset($styleArray);
				        }
	                }
		        }
		    }
		    else
		    {
		        $union_select1 = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$excel_station_id.' order by date, time';
		        if($excel_data = Yii::app()->db3->createCommand($union_select1)->queryAll())
		        {
		            foreach ($excel_data as $elements) {
		                if($excel_station_type=='radio'){
		                    $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
		                    $link = $linkurl.$data_this_file_path;
		                    $link=str_replace("wav","mp3",$link);
		                }else{
		                    if($elements['video_file']=='video_file'){
		                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
		                        $link = $linkurl.$data_this_file_path;
		                        $link=str_replace("wav","mp3",$link);
		                    }else{
		                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['video_file']);
		                        $link = $linkurl.$data_this_file_path;
		                    }
		                }
		                $entry_identifier = $elements['entry_type_id'];
						if($entry_identifier==4){
							$elements['incantation_name'] = $elements['program_name'];
						}

		                $PHPExcel->getActiveSheet()
		                ->setCellValue("A$count", $elements['incantation_name'])
		                ->setCellValue("B$count", $elements['brand_name'])
		                ->setCellValue("C$count", $elements['date'])
		                ->setCellValue("D$count", $elements['time'])
		                ->setCellValue("E$count", $elements['entry_type'])
		                ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
		                ->setCellValue("G$count", number_format((float)$elements['rate']));
		                $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
		                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
		                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
		                $count++;
		            }
		            unset($styleArray);
		        }
		    }
	        // Rename sheet
	        $PHPExcel->getActiveSheet()->setTitle($excel_station_name);
	        $sheet_index++;
	    }

	    $PHPExcel->setActiveSheetIndex(0);
		header('Content-Type: application/excel');
		header('Content-Disposition: attachment;filename="Proof_of_Flight_Report.xls"');
		header('Cache-Control: max-age=0');

	    $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
	    $objWriter->save('php://output');
	}

}
?>
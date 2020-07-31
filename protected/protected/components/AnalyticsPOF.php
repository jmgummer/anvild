<?php

/**
* 
*/
class AnalyticsPOF{
	public static function CompetitorReport($startdate,$enddate,$brand_query,$country_id,$station_query,$ad_query){
		$linkurl = Yii::app()->params['eleclink'];
		if($currency = Country::model()->find('country_code=:a', array(':a'=>Yii::app()->user->country_code))){
	        $currency = $currency->currency;
	    }else{
	        $currency = Yii::app()->params['country_currency'];
	    }
		$sqlstartdate = date('Y-m-d', strtotime($startdate));
		$sqlenddate = date('Y-m-d', strtotime($enddate));
		/* Date Formating Starts Here */
		$year_start     = date('Y',strtotime($startdate));  
		$month_start    = date('m',strtotime($startdate));  
		$day_start      = date('d',strtotime($startdate));
		$year_end       = date('Y',strtotime($enddate)); 
		$month_end      = date('m',strtotime($enddate)); 
		$day_end        = date('d',strtotime($enddate));

		$temp_table = Common::CompetitorPOFTempTable();
		// $temp_table = 'test_temp';
		/* Djmentions */
		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="djmentions_"  .$x."_".$my_month;
		        $mentions_sql = "INSERT into $temp_table 
		        (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,station_name,station_type,company_name) 
		        SELECT 
		        distinct($temp_table_month.auto_id) as auto_id,
		        $temp_table_month.brand_id as brand_id,
		        $temp_table_month.entry_type_id as entry_type_id,
		        'incantation_id',
		        $temp_table_month.station_id as station_id,
		        $temp_table_month.date as date,
		        $temp_table_month.time as time,
		        $temp_table_month.comment as comment,
		        $temp_table_month.rate as rate,
		        brand_table.brand_name as brand_name,
		        djmentions_entry_types.entry_type as entry_type,
		        brand_table.brand_name as incantation_name,
		        $temp_table_month.duration as duration,
		        CONCAT($temp_table_month.file_path, '', $temp_table_month.filename) as file,
		        'video_file',
		        station_name as station_name,
		        station_type as station_type,
		        company_name  
		        FROM $temp_table_month, brand_table, djmentions_entry_types, station, user_table
		        WHERE $temp_table_month.$brand_query 
		        AND $temp_table_month.$station_query
		        AND $ad_query 
		        AND $temp_table_month.brand_id=brand_table.brand_id 
		        AND $temp_table_month.entry_type_id=djmentions_entry_types.entry_type_id
		        AND $temp_table_month.station_id=station.station_id
		        AND brand_table.status = 1 AND $temp_table_month.active=1 
		        AND brand_table.company_id = user_table.company_id 
		        AND date between '$sqlstartdate' and '$sqlenddate' group by $temp_table_month.auto_id ";
		        // echo $mentions_sql;
		        $insertsql = Yii::app()->db3->createCommand($mentions_sql)->execute();
		    }
		}
		// echo "<br>";
		/* Reelforge Sample */

		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="reelforge_sample_"  .$x."_".$my_month;
		        $sample_sql = "INSERT into $temp_table 
		        (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,station_name,station_type,company_name) 
		        SELECT 
		        distinct($temp_table_month.reel_auto_id) as auto_id,
		        $temp_table_month.brand_id as brand_id,
		        $temp_table_month.entry_type_id as entry_type_id,
		        $temp_table_month.incantation_id as incantation_id,
		        $temp_table_month.station_id as station_id,
		        $temp_table_month.reel_date as date,
		        $temp_table_month.reel_time as time,
		        $temp_table_month.comment as comment,
		        $temp_table_month.rate as rate,
		        brand_table.brand_name as brand_name,
		        djmentions_entry_types.entry_type as entry_type,
		        incantation.incantation_name as incantation_name,
		        incantation.incantation_length as duration,
		        CONCAT(incantation.file_path, '', incantation.incantation_file) as file,
		        CONCAT(incantation.file_path, '', incantation.mpg_path) as video_file,
		        station_name as station_name,
		        station_type as station_type,
		        company_name
		        FROM $temp_table_month, incantation,brand_table,djmentions_entry_types, station, user_table
		        WHERE $temp_table_month.$brand_query 
		        AND $temp_table_month.$station_query
		        AND $ad_query 
		        AND $temp_table_month.brand_id=brand_table.brand_id 
		        AND $temp_table_month.incantation_id=incantation.incantation_id
		        AND $temp_table_month.entry_type_id=djmentions_entry_types.entry_type_id
		        AND $temp_table_month.station_id=station.station_id 
		        AND $temp_table_month.reel_date between '$sqlstartdate' and '$sqlenddate' 
		        AND brand_table.status = 1  AND $temp_table_month.active=1 
		        AND brand_table.company_id = user_table.company_id 
		        group by $temp_table_month.reel_auto_id " ;
		        // echo $sample_sql;
		        $insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
		    }
		}

		$file = AnalyticsPOF::CompetitorCompanies($temp_table,$currency,$linkurl);
		return $file;

	}

	public static function CompetitorCompanies($temp_table,$currency,$linkurl){
		ini_set('memory_limit', '1024M');
		$PHPExcel = new PHPExcel();
        $title = "Reelforge POF Report";

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $PHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("D5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("E5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("F5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("G5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("H5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("I5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("J5")->applyFromArray($styleArray);

        // Electonic Sheet
	    $PHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Reelforge Proof of Flight Report')
		->setCellValue('A2', 'Electronic Summary')
		->setCellValue('A3', ' ')
		->setCellValue('A5', 'Ad Name')
		->setCellValue('B5', 'Brand Name')
		->setCellValue('C5', 'Date')
		->setCellValue('D5', 'Time')
		->setCellValue('E5', 'Type')
		->setCellValue('F5', 'Duration(h:m:s)')
		->setCellValue('G5', 'Rate')
		->setCellValue('H5', 'Station Type')
		->setCellValue('I5', 'Station Name')
		->setCellValue('J5', 'Company Name');


		$audio_icon = Yii::app()->request->baseUrl .'/images/play_icon.jpeg';
		$video_icon = Yii::app()->request->baseUrl .'/images/vid_icon.jpg';
		$allsql = "SELECT * FROM $temp_table ORDER BY date DESC, time ASC";
		if($data = Yii::app()->db3->createCommand($allsql)->queryAll())
        {
            $count = 6;
            foreach ($data as $elements) {
                if($elements['station_type']=='radio'){
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
                $PHPExcel->getActiveSheet()
                ->setCellValue("A$count", $elements['incantation_name'])
                ->setCellValue("B$count", $elements['brand_name'])
                ->setCellValue("C$count", $elements['date'])
                ->setCellValue("D$count", $elements['time'])
                ->setCellValue("E$count", $elements['entry_type'])
                ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
                ->setCellValue("G$count", number_format((float)$elements['rate']))
                ->setCellValue("H$count", $elements['station_type'])
                ->setCellValue("I$count", $elements['station_name'])
                ->setCellValue("J$count", $elements['company_name'])
                ;
                $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
                $count++;
            }
        }
        $PHPExcel->getActiveSheet()->setTitle('POF - Electronic');

        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/competitor/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = 'analytics_pof_'.date("Ymdhis").'_'.$filename.'.xls';
        $objWriter->save($upload_path.$filename);
        return $filename;
	}
}

<?php

/**
* Handles Station Summary Data
*/
class StationSummarySpends
{
	
	public static function CreateTempTable(){
		$temptable = "stsum".rand().date("Ymdhis").rand();
		$sql = "CREATE TEMPORARY TABLE $temptable(
			id INT NOT NULL auto_increment,
			rate INT NOT NULL,
			station_id INT NOT NULL,
			station_name VARCHAR(150) NOT NULL,
			mediatype  VARCHAR(5) NOT NULL,
			station_type  VARCHAR(50) NOT NULL,
			PRIMARY KEY(id)
		)";
		$createtable = Yii::app()->db3->createCommand($sql)->execute();
		return $temptable;
	}

	public static function GetStationData($startdate,$enddate,$temptable){
		$year_start     = date('Y',strtotime($startdate));  
		$month_start    = date('m',strtotime($startdate));  
		$day_start      = date('d',strtotime($startdate));
		$year_end       = date('Y',strtotime($enddate)); 
		$month_end      = date('m',strtotime($enddate)); 
		$day_end        = date('d',strtotime($enddate));
		for ($x=$year_start;$x<=$year_end;$x++){
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}
		    $month_start_count=$month_start_count+0;
		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $period = $x."_".$my_month;
		        $mention_table_month="djmentions_"  .$x."_".$my_month;
		        $sample_table_month="reelforge_sample_"  .$x."_".$my_month;
		        // Insert Data
		        $insertsql = "INSERT INTO $temptable (rate,station_id,station_name,mediatype,station_type) SELECT SUM(CAST($mention_table_month.`rate` AS double) ) AS rate, station.station_id AS station_id, 
				station.station_name AS station_name, 
				'e' AS mediatype, station.station_type AS station_type
				from $mention_table_month 
				inner join station ON station.station_id=$mention_table_month.station_id
				WHERE $mention_table_month.active=1 AND ($mention_table_month.`date` BETWEEN '$startdate' AND '$enddate')
				group by $mention_table_month.station_id
				UNION 
				SELECT SUM(CAST($sample_table_month.`rate` AS double)) AS rate, station.station_id AS station_id, 
				station.station_name AS station_name,
				'e' AS mediatype, station.station_type AS station_type  
				from $sample_table_month 
				inner join station ON station.station_id=$sample_table_month.station_id
				WHERE $sample_table_month.active=1 AND ($sample_table_month.`reel_date` BETWEEN '$startdate' AND '$enddate')
				group by $sample_table_month.station_id
				UNION
				SELECT SUM(CAST(print_table.`ave` AS double)) AS rate, mediahouse.Media_House_ID AS station_id, 
				mediahouse.Media_House_List AS station_name,
				'p' AS mediatype, 'print' AS station_type 
				FROM print_table
				INNER JOIN mediahouse ON mediahouse.Media_House_ID=print_table.media_house_id
				WHERE (print_table.`date` BETWEEN '$startdate' AND '$enddate')
				GROUP BY print_table.media_house_id";
				$insertquery = Yii::app()->db3->createCommand($insertsql)->execute();
		    }
		}
	}

	public static function PrintData($temptable,$station_type){
		if($station_type=='0'){
			$subquery = " ";
		}else{
			$subquery = "WHERE station_type='$station_type'";
		}
		$sumsql = "SELECT SUM(rate) AS rate, station_name FROM $temptable $subquery GROUP BY station_name ORDER BY rate DESC";
		if($stationquery = Yii::app()->db3->createCommand($sumsql)->queryAll()){
			return $stationquery;
		}else{
			return false;
		}
	}

	public static function GenerateExcel($startdate,$enddate,$stationquery,$currency){
		ini_set('memory_limit', '1024M');
		$PHPExcel = new PHPExcel();
        $title = "Reelforge Station Spends Report";

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Station Spends Report")
        ->setSubject("Reelforge Station Spends Report")
        ->setDescription("Reelforge Station Spends Report");
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
        $PHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B5")->applyFromArray($styleArray);

        // Electonic Sheet
	    $PHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Reelforge Systems')
		->setCellValue('A2', 'Reelforge Station Spends Report')
		->setCellValue('A3', "Period : $startdate to $enddate")
		->setCellValue('A5', 'Station Name')
		->setCellValue('B5', "Rate ($currency)");

		$count = 6;
        foreach ($stationquery as $elements) {
            $PHPExcel->getActiveSheet()
            ->setCellValue("A$count", $elements['station_name'])
            ->setCellValue("B$count", number_format((float)$elements['rate']))
            ;
            $count++;
        }
        $PHPExcel->getActiveSheet()->setTitle('Station Spends');

        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = 'Station_Spends_'.date("Ymdhis").'_'.$filename.'.xlsx';
        $objWriter->save($upload_path.$filename);
        return $filename;
	}
}
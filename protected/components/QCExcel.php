<?php

class QCExcel
{
	public static function StandardExcel($temp_table,$startdate,$enddate,$clientname){
		$electronicplayer = Yii::app()->params['eleclink'];
		$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table ORDER BY station ASC";
		$stored_stations = Yii::app()->db3->createCommand($stationquery)->queryAll();

		$PHPExcel = new PHPExcel();
	    $title = 'The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate;
	    
	    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
	    ->setTitle("Reelforge Anvil Proof of Flight Reports")
	    ->setSubject("Reelforge Anvil Proof of Flight Reports")
	    ->setDescription("Reelforge Anvil Proof of Flight Reports");
	    
	    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);
	    $sheet_index = 0;
	    foreach ($stored_stations as $found_stations) {
	    	$fstation_id = $found_stations['station_id'];
	    	$fstation_name = $found_stations['station_name'];
	        $PHPExcel->createSheet(NULL, $sheet_index);
	        $PHPExcel->setActiveSheetIndex($sheet_index)
	        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
	        ->setCellValue('B1', ' ')
	        ->setCellValue('C1', ' ')
	        ->setCellValue('A2', 'Station : '.$fstation_name)
	        ->setCellValue('B2', ' ')
	        ->setCellValue('C2', ' ')
	        ->setCellValue('A3', $title)
	        ->setCellValue('B3', ' ')
	        ->setCellValue('C3', ' ')
	        ->setCellValue('A4', 'Client : '.$clientname)
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

	        $count = 7;
	        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
	        $stationsql = "SELECT * FROM $temp_table WHERE station_id=$fstation_id ORDER BY date, time ASC";
			if($excel_data = Yii::app()->db3->createCommand($stationsql)->queryAll())
			{
				foreach ($excel_data as $elements) {
					$ad_name = $elements['ad_name'];
					$brand_name = $elements['brand_name'];
					$date = $elements['date'];
					$time = $elements['time'];
					$type = $elements['type'];
					$duration = $elements['duration'];
					$rate = number_format( (int)$elements['rate'] );

					$file = $elements['file'];
					if($elements['videofile']!=''){
						$file = $elements['videofile'];
						$file = str_replace("wav","mp3",$file);
					}else{
						$file = str_replace("wav","mp3",$file);
					}
					if($type=='Caption' || $type=='Program' || $type=='Activation'){
						$url = "#";
						$target = "";
					}else{
						$url = $electronicplayer.str_replace('/home/srv/www/htdocs/','',$file) ;
						$target = "target='_blank'";
					}

					$PHPExcel->getActiveSheet()
					->setCellValue("A$count", $ad_name)
					->setCellValue("B$count", $brand_name)
					->setCellValue("C$count", $date)
					->setCellValue("D$count", $time)
					->setCellValue("E$count", $type)
					->setCellValue("F$count", $duration)
					->setCellValue("G$count", $rate);
					$PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($url);
					$PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
					$PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
					$count++;
				}
				unset($styleArray);
			}
	        $PHPExcel->getActiveSheet()->setTitle($fstation_name);
	        $sheet_index++;
	    }

	    $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename =str_replace(" ","_",$clientname);
        $filename = 'QC_POF_'.date("Ymdhis").'_'.$filename.'.xlsx';
        $objWriter->save($upload_path.$filename);
        return $filename;
	}

	public static function StationBrandExcel($temp_table,$startdate,$enddate,$clientname){
		$electronicplayer = Yii::app()->params['eleclink'];
		$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table ORDER BY station ASC";
		$stored_stations = Yii::app()->db3->createCommand($stationquery)->queryAll();

		$PHPExcel = new PHPExcel();
	    $title = 'The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate;
	    
	    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
	    ->setTitle("Reelforge Anvil Proof of Flight Reports")
	    ->setSubject("Reelforge Anvil Proof of Flight Reports")
	    ->setDescription("Reelforge Anvil Proof of Flight Reports");
	    
	    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);
	    $sheet_index = 0;
	    foreach ($stored_stations as $found_stations) {
	    	$fstation_id = $found_stations['station_id'];
	    	$fstation_name = $found_stations['station_name'];
	        $PHPExcel->createSheet(NULL, $sheet_index);
	        $PHPExcel->setActiveSheetIndex($sheet_index)
	        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
	        ->setCellValue('B1', ' ')
	        ->setCellValue('C1', ' ')
	        ->setCellValue('A2', 'Station : '.$fstation_name)
	        ->setCellValue('B2', ' ')
	        ->setCellValue('C2', ' ')
	        ->setCellValue('A3', $title)
	        ->setCellValue('B3', ' ')
	        ->setCellValue('C3', ' ')
	        ->setCellValue('A4', 'Client : '.$clientname)
	        ->setCellValue('B4', ' ')
	        ->setCellValue('C4', ' ')
	        ->setCellValue('A5', ' ')
	        ->setCellValue('B5', ' ')
	        ->setCellValue('C5', ' ');

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

	        $count = 7;
	        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

	        $brandquery = "SELECT DISTINCT brand_id, brand_name FROM $temp_table WHERE station_id=$fstation_id ORDER BY brand_name ASC";
	        if($brands = Yii::app()->db3->createCommand($brandquery)->queryAll()){
	        	foreach ($brands as $brandkey) {
					$fbrandid = $brandkey['brand_id'];
					$fbrandname = $brandkey['brand_name'];

					$PHPExcel->getActiveSheet()->setCellValue("A$count", $fbrandname);
					$count++;

					$PHPExcel->getActiveSheet()		
					->setCellValue("A$count", 'Ad Name')
					->setCellValue("B$count", 'Brand Name')
					->setCellValue("C$count", 'Date')
					->setCellValue("D$count", 'Time')
					->setCellValue("E$count", 'Type')
					->setCellValue("F$count", 'Duration(h:m:s)')
					->setCellValue("G$count", 'Rate');
					$count++;

					$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND brand_id=$fbrandid ORDER BY date,time ASC";
					if($excel_data = Yii::app()->db3->createCommand($brandsql)->queryAll()){
						foreach ($excel_data as $elements) {
							$ad_name = $elements['ad_name'];
							$brand_name = $elements['brand_name'];
							$date = $elements['date'];
							$time = $elements['time'];
							$type = $elements['type'];
							$duration = $elements['duration'];
							$rate = number_format( (int)$elements['rate'] );

							$file = $elements['file'];
							if($elements['videofile']!=''){
								$file = $elements['videofile'];
								$file = str_replace("wav","mp3",$file);
							}else{
								$file = str_replace("wav","mp3",$file);
							}

							if($type=='Caption' || $type=='Program'){
								$url = "#";
							}else{
								$url = $electronicplayer.str_replace('/home/srv/www/htdocs/','',$file) ;
							}

							$PHPExcel->getActiveSheet()
							->setCellValue("A$count", $ad_name)
							->setCellValue("B$count", $brand_name)
							->setCellValue("C$count", $date)
							->setCellValue("D$count", $time)
							->setCellValue("E$count", $type)
							->setCellValue("F$count", $duration)
							->setCellValue("G$count", $rate);
							$PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($url);
							$PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
							$PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
							$count++;
						}
					}
					$count++;
				}
	        }
	        unset($styleArray);
	        $PHPExcel->getActiveSheet()->setTitle($fstation_name);
	        $sheet_index++;
	    }

	    $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename =str_replace(" ","_",$clientname);
        $filename = 'QC_POF_'.date("Ymdhis").'_'.$filename.'.xlsx';
        $objWriter->save($upload_path.$filename);
        return $filename;
	}

	public static function BrandStationExcel($temp_table,$startdate,$enddate,$clientname){
		$electronicplayer = Yii::app()->params['eleclink'];
		$brandquery = "SELECT DISTINCT brand_id, brand_name FROM $temp_table ORDER BY brand_name ASC";
		$stored_brands = Yii::app()->db3->createCommand($brandquery)->queryAll();

		$PHPExcel = new PHPExcel();
	    $title = 'The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate;
	    
	    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
	    ->setTitle("Reelforge Anvil Proof of Flight Reports")
	    ->setSubject("Reelforge Anvil Proof of Flight Reports")
	    ->setDescription("Reelforge Anvil Proof of Flight Reports");
	    
	    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);
	    $sheet_index = 0;
	    foreach ($stored_brands as $found_brands) {
	    	$fbrandid = $found_brands['brand_id'];
			$fbrandname = $found_brands['brand_name'];

	        $PHPExcel->createSheet(NULL, $sheet_index);
	        $PHPExcel->setActiveSheetIndex($sheet_index)
	        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
	        ->setCellValue('B1', ' ')
	        ->setCellValue('C1', ' ')
	        ->setCellValue('A2', 'Brand : '.$fbrandname)
	        ->setCellValue('B2', ' ')
	        ->setCellValue('C2', ' ')
	        ->setCellValue('A3', $title)
	        ->setCellValue('B3', ' ')
	        ->setCellValue('C3', ' ')
	        ->setCellValue('A4', 'Client : '.$clientname)
	        ->setCellValue('B4', ' ')
	        ->setCellValue('C4', ' ')
	        ->setCellValue('A5', ' ')
	        ->setCellValue('B5', ' ')
	        ->setCellValue('C5', ' ');

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

	        $count = 7;
	        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

	        $stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table WHERE brand_id=$fbrandid ORDER BY station_name ASC";
	        if($stations = Yii::app()->db3->createCommand($stationquery)->queryAll()){
	        	foreach ($stations as $stationkey) {
	        		$fstationid = $stationkey['station_id'];
					$fstationname = $stationkey['station_name'];

					$PHPExcel->getActiveSheet()->setCellValue("A$count", $fstationname);
					$count++;

					$PHPExcel->getActiveSheet()		
					->setCellValue("A$count", 'Ad Name')
					->setCellValue("B$count", 'Brand Name')
					->setCellValue("C$count", 'Date')
					->setCellValue("D$count", 'Time')
					->setCellValue("E$count", 'Type')
					->setCellValue("F$count", 'Duration(h:m:s)')
					->setCellValue("G$count", 'Rate');
					$count++;

					$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstationid AND brand_id=$fbrandid ORDER BY date,time ASC";
					if($excel_data = Yii::app()->db3->createCommand($brandsql)->queryAll()){
						foreach ($excel_data as $elements) {
							$ad_name = $elements['ad_name'];
							$brand_name = $elements['brand_name'];
							$date = $elements['date'];
							$time = $elements['time'];
							$type = $elements['type'];
							$duration = $elements['duration'];
							$rate = number_format( (int)$elements['rate'] );

							$file = $elements['file'];
							if($elements['videofile']!=''){
								$file = $elements['videofile'];
								$file = str_replace("wav","mp3",$file);
							}else{
								$file = str_replace("wav","mp3",$file);
							}

							if($type=='Caption' || $type=='Program'){
								$url = "#";
							}else{
								$url = $electronicplayer.str_replace('/home/srv/www/htdocs/','',$file) ;
							}

							$PHPExcel->getActiveSheet()
							->setCellValue("A$count", $ad_name)
							->setCellValue("B$count", $brand_name)
							->setCellValue("C$count", $date)
							->setCellValue("D$count", $time)
							->setCellValue("E$count", $type)
							->setCellValue("F$count", $duration)
							->setCellValue("G$count", $rate);
							$PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($url);
							$PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
							$PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
							$count++;
						}
					}
					$count++;
	        	}
	        }
	        unset($styleArray);

			if(strlen($fbrandname) > 30 ){
				$fbrandname = substr($fbrandname,0,30);
			}

	        $PHPExcel->getActiveSheet()->setTitle($fbrandname);
	        $sheet_index++;
	    }

	    $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename =str_replace(" ","_",$clientname);
        $filename = 'QC_POF_'.date("Ymdhis").'_'.$filename.'.xlsx';
        $objWriter->save($upload_path.$filename);
        return $filename;
	}

	public static function AdTypeStationExcel($temp_table,$startdate,$enddate,$clientname){
		$electronicplayer = Yii::app()->params['eleclink'];
		$adtypequery = "SELECT DISTINCT `type` AS entry_type_name,entry_type_id FROM $temp_table,djmentions_entry_types WHERE djmentions_entry_types.entry_type = $temp_table.`type` ORDER BY entry_type_name ASC";
		$stored_adtypes = Yii::app()->db3->createCommand($adtypequery)->queryAll();

		$PHPExcel = new PHPExcel();
	    $title = 'The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate;
	    
	    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
	    ->setTitle("Reelforge Anvil Proof of Flight Reports")
	    ->setSubject("Reelforge Anvil Proof of Flight Reports")
	    ->setDescription("Reelforge Anvil Proof of Flight Reports");
	    
	    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(11);
	    $sheet_index = 0;
	    foreach ($stored_adtypes as $found_adtypes) {
	    	$entry_type_id = $found_adtypes['entry_type_id'];
			$entry_type_name = $found_adtypes['entry_type_name'];

	        $PHPExcel->createSheet(NULL, $sheet_index);
	        $PHPExcel->setActiveSheetIndex($sheet_index)
	        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
	        ->setCellValue('B1', ' ')
	        ->setCellValue('C1', ' ')
	        ->setCellValue('A2', 'Brand : '.$entry_type_name)
	        ->setCellValue('B2', ' ')
	        ->setCellValue('C2', ' ')
	        ->setCellValue('A3', $title)
	        ->setCellValue('B3', ' ')
	        ->setCellValue('C3', ' ')
	        ->setCellValue('A4', 'Client : '.$clientname)
	        ->setCellValue('B4', ' ')
	        ->setCellValue('C4', ' ')
	        ->setCellValue('A5', ' ')
	        ->setCellValue('B5', ' ')
	        ->setCellValue('C5', ' ');

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

	        $count = 7;
	        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

	        $stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table WHERE `type`='$entry_type_name' ORDER BY station_name ASC";
	        if($stations = Yii::app()->db3->createCommand($stationquery)->queryAll()){
	        	foreach ($stations as $stationkey) {
	        		$fstationid = $stationkey['station_id'];
					$fstationname = $stationkey['station_name'];

					$PHPExcel->getActiveSheet()->setCellValue("A$count", $fstationname);
					$count++;

					$PHPExcel->getActiveSheet()		
					->setCellValue("A$count", 'Ad Name')
					->setCellValue("B$count", 'Brand Name')
					->setCellValue("C$count", 'Date')
					->setCellValue("D$count", 'Time')
					->setCellValue("E$count", 'Type')
					->setCellValue("F$count", 'Duration(h:m:s)')
					->setCellValue("G$count", 'Rate');
					$count++;

					$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstationid AND `type`='$entry_type_name' ORDER BY date,time ASC";
					if($excel_data = Yii::app()->db3->createCommand($brandsql)->queryAll()){
						foreach ($excel_data as $elements) {
							$ad_name = $elements['ad_name'];
							$brand_name = $elements['brand_name'];
							$date = $elements['date'];
							$time = $elements['time'];
							$type = $elements['type'];
							$duration = $elements['duration'];
							$rate = number_format( (int)$elements['rate'] );

							$file = $elements['file'];
							if($elements['videofile']!=''){
								$file = $elements['videofile'];
								$file = str_replace("wav","mp3",$file);
							}else{
								$file = str_replace("wav","mp3",$file);
							}

							if($type=='Caption' || $type=='Program'){
								$url = "#";
							}else{
								$url = $electronicplayer.str_replace('/home/srv/www/htdocs/','',$file) ;
							}

							$PHPExcel->getActiveSheet()
							->setCellValue("A$count", $ad_name)
							->setCellValue("B$count", $brand_name)
							->setCellValue("C$count", $date)
							->setCellValue("D$count", $time)
							->setCellValue("E$count", $type)
							->setCellValue("F$count", $duration)
							->setCellValue("G$count", $rate);
							$PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($url);
							$PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
							$PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
							$count++;
						}
					}
					$count++;
	        	}
	        }
	        unset($styleArray);

			if(strlen($entry_type_name) > 30 ){
				$entry_type_name = substr($entry_type_name,0,30);
			}

	        $PHPExcel->getActiveSheet()->setTitle($entry_type_name);
	        $sheet_index++;
	    }

	    $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename =str_replace(" ","_",$clientname);
        $filename = 'QC_POF_'.date("Ymdhis").'_'.$filename.'.xlsx';
        $objWriter->save($upload_path.$filename);
        return $filename;
	}
}
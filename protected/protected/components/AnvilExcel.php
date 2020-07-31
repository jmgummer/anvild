<?php
ini_set('memory_limit', '1024M');
class AnvilExcel{
	public static function StandardExcel($temp_table,$reportname,$linkurl,$currency,$adtypes){
		$PHPExcel = new PHPExcel();
		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
		    $title = $reportname;
		    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
		    ->setTitle("Reelforge Anvil Proof of Flight Reports")
		    ->setSubject("Reelforge Anvil Proof of Flight Reports")
		    ->setDescription("Reelforge Anvil Proof of Flight Reports");
		    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		    $sheet_index = 0;
		    // Summary Page Starts Here
			$PHPExcel->createSheet(NULL, $sheet_index);
			$PHPExcel->setActiveSheetIndex($sheet_index)
			->setCellValue('A1', 'Reelforge Proof of Flight Report')
			->setCellValue('A2', 'Summary')
			->setCellValue('A3', 'Client : '.Yii::app()->user->company_name);
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A1:Z1');
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A2:Z2');
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A3:Z3');
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('A')->setWidth(25);
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('B')->setWidth(25);
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('C')->setWidth(25);
			$boldstyle = array('font'  => array('bold'  => true));
			$count = 5;
		    foreach ($stored_stations as $excel_stations) {
		    	$station_id = $excel_stations['station_id'];
		    	$station_name = $excel_stations['station_name'];
		    	$adtypes_sql = "SELECT count(entry_type) as adnumber, entry_type, sum(rate) AS rate FROM $temp_table
				WHERE station_id=$station_id GROUP BY entry_type";
				if($data = Yii::app()->db3->createCommand($adtypes_sql)->queryAll()){
					$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $station_name);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$count++;

					$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", 'Ad Type')->setCellValue("B$count", 'Number of Ads')->setCellValue("C$count", "Value of Ads ($currency) ");
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C$count")->applyFromArray($boldstyle);
					$count++;

					$stationtotal = 0;
					foreach ($data as $key) {
						$adtype = $key['entry_type'];
						$adnumber = $key['adnumber'];
						$adrate = number_format($key['rate']);
						$PHPExcel->getActiveSheet($sheet_index)
						->setCellValue("A$count", $adtype)
						->setCellValue("B$count", $adnumber)
						->setCellValue("C$count", $adrate);
						$stationtotal = $stationtotal+$key['rate'];
						$count++;
					}
					$PHPExcel->getActiveSheet($sheet_index)
					->setCellValue("A$count", "Station Total")
					->setCellValue("B$count", "")
					->setCellValue("C$count", number_format($stationtotal));
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C$count")->applyFromArray($boldstyle);
					$count++;
				}
				$count++;
		    }
		    $PHPExcel->getActiveSheet($sheet_index)->setTitle("Summary");
		    $sheet_index++;
		    // Summary Page Ends Here
		    foreach ($stored_stations as $excel_stations) {
				$station_id = $excel_stations['station_id'];
				$station_name = $excel_stations['station_name'];
				$station_type = $excel_stations['station_type'];
		        $excel_station_type = $station_type;
		        $excel_station_id = $excel_stations['station_id'];
		        $excel_station_name = $station_name;
		        $PHPExcel->createSheet(NULL, $sheet_index);
		        $PHPExcel->setActiveSheetIndex($sheet_index)
		        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
		        ->setCellValue('A2', 'Station : '.$excel_station_name)
		        ->setCellValue('A3', 'Client : '.Yii::app()->user->company_name)
		        ->setCellValue('A5', 'Campaign Name')
		        ->setCellValue('B5', 'Brand Name')
		        ->setCellValue('C5', 'Date')
		        ->setCellValue('D5', 'Time')
		        ->setCellValue('E5', 'Type')
		        ->setCellValue('F5', 'Duration(h:m:s)')
		        ->setCellValue('G5', "Rate ($currency) ");

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
		        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);

				$PHPExcel->getActiveSheet($sheet_index)->getStyle("A5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("B5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("C5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("D5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("E5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("F5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("G5")->applyFromArray($boldstyle);

		        $count = 6;
		        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
		        if($adtypes==1){
		        	$adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$excel_station_id order by date, time";
		        	if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
		        		foreach ($found_adtypes as $adkey) {
							$adname = $adkey['entry_type'];
							$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $adname);
							$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
							$count++;
							$union_select1 = "SELECT * FROM $temp_table WHERE station_id=$excel_station_id AND entry_type='$adname' order by date, time";
							if($excel_data = Yii::app()->db3->createCommand($union_select1)->queryAll()){
					            foreach ($excel_data as $elements) {
					            	$data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
                                    $link = $linkurl.$data_this_file_path;
					                $formated_rate = number_format((float)$elements['rate']);
					                if($elements['entry_type_id']==4){
					                	$elements['incantation_name'] = $elements['program_name'];
					                }
					                $PHPExcel->getActiveSheet()
					                ->setCellValue("A$count", $elements['incantation_name'])
					                ->setCellValue("B$count", $elements['brand_name'])
					                ->setCellValue("C$count", $elements['date'])
					                ->setCellValue("D$count", $elements['time'])
					                ->setCellValue("E$count", $elements['entry_type'])
					                ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
					                ->setCellValue("G$count", $formated_rate);
					                $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
					                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
					                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
					                $count++;
					            }
					        }
						}
		        	}
		        }else{
		        	$union_select1 = "SELECT * FROM $temp_table WHERE station_id=$excel_station_id order by date, time";
			        if($excel_data = Yii::app()->db3->createCommand($union_select1)->queryAll()){
			            foreach ($excel_data as $elements) {
			                $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
                            $link = $linkurl.$data_this_file_path;
			                $formated_rate = number_format((float)$elements['rate']);
			                if($elements['entry_type_id']==4){
			                	$elements['incantation_name'] = $elements['program_name'];
			                }
			                $PHPExcel->getActiveSheet()
			                ->setCellValue("A$count", $elements['incantation_name'])
			                ->setCellValue("B$count", $elements['brand_name'])
			                ->setCellValue("C$count", $elements['date'])
			                ->setCellValue("D$count", $elements['time'])
			                ->setCellValue("E$count", $elements['entry_type'])
			                ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
			                ->setCellValue("G$count", $formated_rate);
			                $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
			                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
			                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
			                $count++;
			            }
			            unset($styleArray);
			        }
		        }
		        
		        $PHPExcel->getActiveSheet()->setTitle($excel_station_name);
		        $sheet_index++;
		    }
		}
		$PHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename="Reelforge_Anvil_POF_Reports_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" .date('dmYhis');
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename).'.xlsx';
        $objWriter->save($upload_path.$filename);
        $file = Yii::app()->request->baseUrl . '/docs/misc/excel/'.$filename;
        $fppackage = "<a href='$file' class='btn btn-success btn-xs pdf-excel' target='_blank'><i class='fa fa-file-excel-o'></i> Download EXCEL</a>";
		return $fppackage;
	}

	public static function StandardKeywordExcel($temp_table,$reportname,$linkurl,$currency,$adtypes){
		$PHPExcel = new PHPExcel();
		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
		    $title = $reportname;
		    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
		    ->setTitle("Reelforge Anvil Proof of Flight Reports")
		    ->setSubject("Reelforge Anvil Proof of Flight Reports")
		    ->setDescription("Reelforge Anvil Proof of Flight Reports");
		    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		    $sheet_index = 0;
		    // Summary Page Starts Here
			$PHPExcel->createSheet(NULL, $sheet_index);
			$PHPExcel->setActiveSheetIndex($sheet_index)
			->setCellValue('A1', 'Reelforge Proof of Flight Report')
			->setCellValue('A2', 'Summary')
			->setCellValue('A3', 'Client : '.Yii::app()->user->company_name);
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A1:Z1');
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A2:Z2');
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A3:Z3');
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('A')->setWidth(25);
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('B')->setWidth(25);
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('C')->setWidth(25);
			$boldstyle = array('font'  => array('bold'  => true));
			$count = 5;
		    foreach ($stored_stations as $excel_stations) {
		    	$station_id = $excel_stations['station_id'];
		    	$station_name = $excel_stations['station_name'];
		    	$adtypes_sql = "SELECT count(entry_type) as adnumber, entry_type, sum(rate) AS rate FROM $temp_table
				WHERE station_id=$station_id GROUP BY entry_type";
				if($data = Yii::app()->db3->createCommand($adtypes_sql)->queryAll()){
					$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $station_name);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$count++;

					$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", 'Ad Type')->setCellValue("B$count", 'Number of Ads')->setCellValue("C$count", "Value of Ads ($currency) ");
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C$count")->applyFromArray($boldstyle);
					$count++;

					$stationtotal = 0;
					foreach ($data as $key) {
						$adtype = $key['entry_type'];
						$adnumber = $key['adnumber'];
						$adrate = number_format($key['rate']);
						$PHPExcel->getActiveSheet($sheet_index)
						->setCellValue("A$count", $adtype)
						->setCellValue("B$count", $adnumber)
						->setCellValue("C$count", $adrate);
						$stationtotal = $stationtotal+$key['rate'];
						$count++;
					}
					$PHPExcel->getActiveSheet($sheet_index)
					->setCellValue("A$count", "Station Total")
					->setCellValue("B$count", "")
					->setCellValue("C$count", number_format($stationtotal));
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C$count")->applyFromArray($boldstyle);
					$count++;
				}
				$count++;
		    }
		    $PHPExcel->getActiveSheet($sheet_index)->setTitle("Summary");
		    $sheet_index++;
		    // Summary Page Ends Here
		    foreach ($stored_stations as $excel_stations) {
				$station_id = $excel_stations['station_id'];
				$station_name = $excel_stations['station_name'];
				$station_type = $excel_stations['station_type'];
		        $excel_station_type = $station_type;
		        $excel_station_id = $excel_stations['station_id'];
		        $excel_station_name = $station_name;
		        $PHPExcel->createSheet(NULL, $sheet_index);
		        $PHPExcel->setActiveSheetIndex($sheet_index)
		        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
		        ->setCellValue('A2', 'Station : '.$excel_station_name)
		        ->setCellValue('A3', 'Client : '.Yii::app()->user->company_name)
		        ->setCellValue('A5', 'Campaign Name')
		        ->setCellValue('B5', 'Brand Name')
		        ->setCellValue('C5', 'Date')
		        ->setCellValue('D5', 'Time')
		        ->setCellValue('E5', 'Type')
		        ->setCellValue('F5', 'Duration(h:m:s)')
		        ->setCellValue('G5', "Rate ($currency) ")
		        ->setCellValue('H5', "Good Words ")
		        ->setCellValue('I5', "Bad Words ");

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
		        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);

				$PHPExcel->getActiveSheet($sheet_index)->getStyle("A5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("B5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("C5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("D5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("E5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("F5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("G5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("H5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("I5")->applyFromArray($boldstyle);

		        $count = 6;
		        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
		        if($adtypes==1){
		        	$adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$excel_station_id order by date, time";
		        	if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
		        		foreach ($found_adtypes as $adkey) {
							$adname = $adkey['entry_type'];
							$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $adname);
							$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
							$count++;
							$union_select1 = "SELECT * FROM $temp_table WHERE station_id=$excel_station_id AND entry_type='$adname' order by date, time";
							if($excel_data = Yii::app()->db3->createCommand($union_select1)->queryAll()){
					            foreach ($excel_data as $elements) {
					            	$data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
                                    $link = $linkurl.$data_this_file_path;
					                $formated_rate = number_format((float)$elements['rate']);
					                if($elements['entry_type_id']==4){
					                	$elements['incantation_name'] = $elements['program_name'];
					                }
					                $PHPExcel->getActiveSheet()
					                ->setCellValue("A$count", $elements['incantation_name'])
					                ->setCellValue("B$count", $elements['brand_name'])
					                ->setCellValue("C$count", $elements['date'])
					                ->setCellValue("D$count", $elements['time'])
					                ->setCellValue("E$count", $elements['entry_type'])
					                ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
					                ->setCellValue("G$count", $formated_rate);
					                if($adkey['entry_type_id']==2){
		                                $mentionid = $elements['auto_id'];
		                                $mentionbrandid = $elements['brand_id'];
		                                $badkeywordsql = "SELECT DISTINCT keyword_id FROM brand_keywords WHERE brand_id = $mentionbrandid AND type=0";
		                                $totalbadwords = BrandKeywords::model()->findAllBySql($badkeywordsql);
		                                if($totalbadwords = BrandKeywords::model()->findAllBySql($badkeywordsql)){
		                                    $totalbadwords_count = count($totalbadwords);
		                                }else{
		                                    $totalbadwords_count = 0;
		                                }

		                                $goodkeywordsql = "SELECT DISTINCT keyword_id FROM brand_keywords WHERE brand_id = $mentionbrandid AND type=1";
		                                if($totalgoodwords = BrandKeywords::model()->findAllBySql($goodkeywordsql)){
		                                    $totalgoodwords_count = count($totalgoodwords);
		                                }else{
		                                    $totalgoodwords_count = 0;
		                                }
		                                $goodmentionkeysql = "SELECT * FROM mention_keyword WHERE dj_auto_id=$mentionid AND keyword_id IN ($goodkeywordsql) AND mentioned=1";
		                                if($goodentries=MentionKeyword::model()->findAllBySql($goodmentionkeysql)){
		                                    $entrycount = count($goodentries);
		                                    $analogy = "Mentioned keys : ";
		                                    foreach ($goodentries as $foundkeys) {
		                                        $listarray[] = $foundkeys->KeywordName;
		                                    }
		                                    $analogy.= implode(', ', $listarray);
		                                    
		                                    $goodwordtext = "$entrycount/$totalgoodwords_count - $analogy";
		                                }else{
		                                    $goodwordtext = "0/$totalgoodwords_count";
		                                }
		                                unset($listarray);
		                                $badmentionkeysql = "SELECT * FROM mention_keyword WHERE dj_auto_id=$mentionid AND keyword_id IN ($badkeywordsql) AND mentioned=1";
		                                if($badentries=MentionKeyword::model()->findAllBySql($badmentionkeysql)){
		                                    $entrycount = count($badentries);
		                                    $analogy = "Mentioned keys : ";
		                                    foreach ($badentries as $foundkeys) {
		                                        $listarray[] = $foundkeys->KeywordName;
		                                    }
		                                    $analogy.= implode(', ', $listarray);
		                                    $badwordtext = "$entrycount/$totalbadwords_count -$analogy";
		                                }else{
		                                    $badwordtext = "0/$totalbadwords_count";
		                                }
		                                unset($listarray);

		                                $PHPExcel->getActiveSheet()
						                ->setCellValue("H$count", $goodwordtext)
						                ->setCellValue("I$count", $badwordtext);
		                            }
		                            // End Djmention Tings

					                $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
					                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
					                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
					                $count++;
					            }
					        }
						}
		        	}
		        }else{
		        	$union_select1 = "SELECT * FROM $temp_table WHERE station_id=$excel_station_id order by date, time";
			        if($excel_data = Yii::app()->db3->createCommand($union_select1)->queryAll()){
			            foreach ($excel_data as $elements) {
							$data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
							$link = $linkurl.$data_this_file_path;

			                $formated_rate = number_format((float)$elements['rate']);

			                if($elements['entry_type_id']==4){
			                	$elements['incantation_name'] = $elements['program_name'];
			                }

			                $PHPExcel->getActiveSheet()
			                ->setCellValue("A$count", $elements['incantation_name'])
			                ->setCellValue("B$count", $elements['brand_name'])
			                ->setCellValue("C$count", $elements['date'])
			                ->setCellValue("D$count", $elements['time'])
			                ->setCellValue("E$count", $elements['entry_type'])
			                ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
			                ->setCellValue("G$count", $formated_rate);

			                if($elements['entry_type_id']==2){
                                $mentionid = $elements['auto_id'];
                                $mentionbrandid = $elements['brand_id'];
                                $badkeywordsql = "SELECT DISTINCT keyword_id FROM brand_keywords WHERE brand_id = $mentionbrandid AND type=0";
                                $totalbadwords = BrandKeywords::model()->findAllBySql($badkeywordsql);
                                if($totalbadwords = BrandKeywords::model()->findAllBySql($badkeywordsql)){
                                    $totalbadwords_count = count($totalbadwords);
                                }else{
                                    $totalbadwords_count = 0;
                                }

                                $goodkeywordsql = "SELECT DISTINCT keyword_id FROM brand_keywords WHERE brand_id = $mentionbrandid AND type=1";
                                if($totalgoodwords = BrandKeywords::model()->findAllBySql($goodkeywordsql)){
                                    $totalgoodwords_count = count($totalgoodwords);
                                }else{
                                    $totalgoodwords_count = 0;
                                }
                                $goodmentionkeysql = "SELECT * FROM mention_keyword WHERE dj_auto_id=$mentionid AND keyword_id IN ($goodkeywordsql) AND mentioned=1";
                                if($goodentries=MentionKeyword::model()->findAllBySql($goodmentionkeysql)){
                                    $entrycount = count($goodentries);
                                    $analogy = "Mentioned keys : ";
                                    foreach ($goodentries as $foundkeys) {
                                        $listarray[] = $foundkeys->KeywordName;
                                    }
                                    $analogy.= implode(', ', $listarray);
                                    
                                    $goodwordtext = "$entrycount/$totalgoodwords_count - $analogy";
                                }else{
                                    $goodwordtext = "0/$totalgoodwords_count";
                                }
                                unset($listarray);
                                $badmentionkeysql = "SELECT * FROM mention_keyword WHERE dj_auto_id=$mentionid AND keyword_id IN ($badkeywordsql) AND mentioned=1";
                                if($badentries=MentionKeyword::model()->findAllBySql($badmentionkeysql)){
                                    $entrycount = count($badentries);
                                    $analogy = "Mentioned keys : ";
                                    foreach ($badentries as $foundkeys) {
                                        $listarray[] = $foundkeys->KeywordName;
                                    }
                                    $analogy.= implode(', ', $listarray);
                                    $badwordtext = "$entrycount/$totalbadwords_count -$analogy";
                                }else{
                                    $badwordtext = "0/$totalbadwords_count";
                                }
                                unset($listarray);

                                $PHPExcel->getActiveSheet()
				                ->setCellValue("H$count", $goodwordtext)
				                ->setCellValue("I$count", $badwordtext);
                            }
                            // End Djmention Tings
			                $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
			                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
			                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
			                $count++;
			            }
			            unset($styleArray);
			        }
		        }
		        
		        $PHPExcel->getActiveSheet()->setTitle($excel_station_name);
		        $sheet_index++;
		    }
		}
		$PHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename="Reelforge_Anvil_POF_Reports_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" .date('dmYhis');
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename).'.xlsx';
        $objWriter->save($upload_path.$filename);
        $file = Yii::app()->request->baseUrl . '/docs/misc/excel/'.$filename;
        $fppackage = "<a href='$file' class='btn btn-success btn-xs pdf-excel' target='_blank'><i class='fa fa-file-excel-o'></i> Download EXCEL</a>";
		return $fppackage;
	}

	public static function BrandExcel($temp_table,$reportname,$linkurl,$currency,$adtypes){
		$PHPExcel = new PHPExcel();
		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
		    $title = $reportname;
		    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
		    ->setTitle("Reelforge Anvil Proof of Flight Reports")
		    ->setSubject("Reelforge Anvil Proof of Flight Reports")
		    ->setDescription("Reelforge Anvil Proof of Flight Reports");
		    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		    $sheet_index = 0;
		    // Summary Page Starts Here
			$PHPExcel->createSheet(NULL, $sheet_index);
			$PHPExcel->setActiveSheetIndex($sheet_index)
			->setCellValue('A1', 'Reelforge Proof of Flight Report')
			->setCellValue('A2', 'Summary')
			->setCellValue('A3', 'Client : '.Yii::app()->user->company_name);
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A1:Z1');
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A2:Z2');
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A3:Z3');
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('A')->setWidth(25);
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('B')->setWidth(25);
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('C')->setWidth(25);
			$boldstyle = array('font'  => array('bold'  => true));
			$count = 5;
		    foreach ($stored_stations as $excel_stations) {
		    	$station_id = $excel_stations['station_id'];
		    	$station_name = $excel_stations['station_name'];
		    	$adtypes_sql = "SELECT count(entry_type) as adnumber, entry_type, sum(rate) AS rate FROM $temp_table
				WHERE station_id=$station_id GROUP BY entry_type";
				if($data = Yii::app()->db3->createCommand($adtypes_sql)->queryAll()){
					$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $station_name);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$count++;

					$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", 'Ad Type')->setCellValue("B$count", 'Number of Ads')->setCellValue("C$count", "Value of Ads ($currency) ");
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C$count")->applyFromArray($boldstyle);
					$count++;

					$stationtotal = 0;
					foreach ($data as $key) {
						$adtype = $key['entry_type'];
						$adnumber = $key['adnumber'];
						$adrate = number_format($key['rate']);
						$PHPExcel->getActiveSheet($sheet_index)
						->setCellValue("A$count", $adtype)
						->setCellValue("B$count", $adnumber)
						->setCellValue("C$count", $adrate);
						$stationtotal = $stationtotal+$key['rate'];
						$count++;
					}
					$PHPExcel->getActiveSheet($sheet_index)
					->setCellValue("A$count", "Station Total")
					->setCellValue("B$count", "")
					->setCellValue("C$count", number_format($stationtotal));
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C$count")->applyFromArray($boldstyle);
					$count++;
				}
				$count++;
		    }
		    $PHPExcel->getActiveSheet($sheet_index)->setTitle("Summary");
		    $sheet_index++;
		    // Summary Page Ends Here

		    // Brands Breakdown Starts Here
			$distinct_brands = "SELECT distinct brand_id, brand_name FROM $temp_table order by date, time";
			if($brand_select = Yii::app()->db3->createCommand($distinct_brands)->queryAll()){
				foreach ($brand_select as $found_brands) {
					$set_brand_id = $found_brands['brand_id'];
        			$set_brand_name = $found_brands['brand_name'];
					$PHPExcel->createSheet(NULL, $sheet_index);
					$PHPExcel->setActiveSheetIndex($sheet_index)
					->setCellValue('A1', 'Reelforge Proof of Flight Report')
					->setCellValue('A2', 'Brand : '.$set_brand_name)
					->setCellValue('A3', 'Client : '.Yii::app()->user->company_name)
					->setCellValue('A5', 'Campaign Name')
					->setCellValue('B5', 'Brand Name')
					->setCellValue('C5', 'Date')
					->setCellValue('D5', 'Time')
					->setCellValue('E5', 'Type')
					->setCellValue('F5', 'Duration(h:m:s)')
					->setCellValue('G5', "Rate ($currency) ");

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
					$PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
					$PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);

					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A5")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B5")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C5")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("D5")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("E5")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("F5")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("G5")->applyFromArray($boldstyle);

					$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
					$station_sql = "SELECT distinct $temp_table.station_id, station.station_name, station.station_type  
					from $temp_table inner join station on station.station_id = $temp_table.station_id
					where  $temp_table.brand_id = $set_brand_id order by station.station_name asc";
					$count = 6;
					if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
						foreach ($stored_stations as $found_stations) {
							$set_station_name = $found_stations['station_name'];
			                $set_station_id = $found_stations['station_id'];
			                $excel_station_type = $fstation_type = $found_stations['station_type'];
			                $PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $set_station_name);
							$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
							$count++;
							if($adtypes==1){
					        	$adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$set_station_id AND brand_id=$set_brand_id order by date, time";
					        	if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
					        		foreach ($found_adtypes as $adkey) {
										$adname = $adkey['entry_type'];
										$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $adname);
										$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
										$count++;
										$union_select = "SELECT * FROM $temp_table WHERE station_id=$set_station_id and brand_id = $set_brand_id AND entry_type='$adname' order by date, time";
										if($excel_data = Yii::app()->db3->createCommand($union_select)->queryAll()){
										    foreach ($excel_data as $elements) {
										        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
		                                        $link = $linkurl.$data_this_file_path;
										        $formated_rate = number_format((float)$elements['rate']);

										        if($elements['entry_type_id']==4){
								                	$elements['incantation_name'] = $elements['program_name'];
								                }

										        $PHPExcel->getActiveSheet()
										        ->setCellValue("A$count", $elements['incantation_name'])
										        ->setCellValue("B$count", $elements['brand_name'])
										        ->setCellValue("C$count", $elements['date'])
										        ->setCellValue("D$count", $elements['time'])
										        ->setCellValue("E$count", $elements['entry_type'])
										        ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
										        ->setCellValue("G$count", $formated_rate);
										        $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
										        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
										        $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
										        $count++;
										    }
										    $count++;
										}
									}
								}
							}else{
								$union_select = "SELECT * FROM $temp_table WHERE station_id=$set_station_id and brand_id = $set_brand_id order by date, time";
								if($excel_data = Yii::app()->db3->createCommand($union_select)->queryAll()){
								    foreach ($excel_data as $elements) {
								        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
                                        $link = $linkurl.$data_this_file_path;
								        $formated_rate = number_format((float)$elements['rate']);
								        if($elements['entry_type_id']==4){
						                	$elements['incantation_name'] = $elements['program_name'];
						                }
								        $PHPExcel->getActiveSheet()
								        ->setCellValue("A$count", $elements['incantation_name'])
								        ->setCellValue("B$count", $elements['brand_name'])
								        ->setCellValue("C$count", $elements['date'])
								        ->setCellValue("D$count", $elements['time'])
								        ->setCellValue("E$count", $elements['entry_type'])
								        ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
								        ->setCellValue("G$count", $formated_rate);
								        $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
								        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
								        $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
								        $count++;
								    }
								    $count++;
								}
							}
							
						}
					}
					$set_brand_name = substr($set_brand_name, 0, 31);
					$PHPExcel->getActiveSheet()->setTitle($set_brand_name);
					$sheet_index++;
        		}
			}
		}
		$PHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename="Reelforge_Anvil_POF_Reports_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" .date('dmYhis');
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename).'.xlsx';
        $objWriter->save($upload_path.$filename);
        $file = Yii::app()->request->baseUrl . '/docs/misc/excel/'.$filename;
        $fppackage = "<a href='$file' class='btn btn-success btn-xs pdf-excel' target='_blank'><i class='fa fa-file-excel-o'></i> Download EXCEL</a>";
		return $fppackage;
	}

	public static function StationExcel($temp_table,$reportname,$linkurl,$currency,$adtypes){
		$PHPExcel = new PHPExcel();
		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
		    $title = $reportname;
		    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
		    ->setTitle("Reelforge Anvil Proof of Flight Reports")
		    ->setSubject("Reelforge Anvil Proof of Flight Reports")
		    ->setDescription("Reelforge Anvil Proof of Flight Reports");
		    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		    $sheet_index = 0;
		    // Summary Page Starts Here
			$PHPExcel->createSheet(NULL, $sheet_index);
			$PHPExcel->setActiveSheetIndex($sheet_index)
			->setCellValue('A1', 'Reelforge Proof of Flight Report')
			->setCellValue('A2', 'Summary')
			->setCellValue('A3', 'Client : '.Yii::app()->user->company_name);
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A1:Z1');
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A2:Z2');
			$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A3:Z3');
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('A')->setWidth(25);
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('B')->setWidth(25);
			$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('C')->setWidth(25);
			$boldstyle = array('font'  => array('bold'  => true));
			$count = 5;
		    foreach ($stored_stations as $excel_stations) {
		    	$station_id = $excel_stations['station_id'];
		    	$station_name = $excel_stations['station_name'];
		    	$adtypes_sql = "SELECT count(entry_type) as adnumber, entry_type, sum(rate) AS rate FROM $temp_table
				WHERE station_id=$station_id GROUP BY entry_type";
				if($data = Yii::app()->db3->createCommand($adtypes_sql)->queryAll()){
					$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $station_name);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$count++;

					$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", 'Ad Type')->setCellValue("B$count", 'Number of Ads')->setCellValue("C$count", "Value of Ads ($currency) ");
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C$count")->applyFromArray($boldstyle);
					$count++;

					$stationtotal = 0;
					foreach ($data as $key) {
						$adtype = $key['entry_type'];
						$adnumber = $key['adnumber'];
						$adrate = number_format($key['rate']);
						$PHPExcel->getActiveSheet($sheet_index)
						->setCellValue("A$count", $adtype)
						->setCellValue("B$count", $adnumber)
						->setCellValue("C$count", $adrate);
						$stationtotal = $stationtotal+$key['rate'];
						$count++;
					}
					$PHPExcel->getActiveSheet($sheet_index)
					->setCellValue("A$count", "Station Total")
					->setCellValue("B$count", "")
					->setCellValue("C$count", number_format($stationtotal));
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("B$count")->applyFromArray($boldstyle);
					$PHPExcel->getActiveSheet($sheet_index)->getStyle("C$count")->applyFromArray($boldstyle);
					$count++;
				}
				$count++;
		    }
		    $PHPExcel->getActiveSheet($sheet_index)->setTitle("Summary");
		    $sheet_index++;
		    // Summary Page Ends Here
		    foreach ($stored_stations as $found_stations) {
			    $fstation_id = $found_stations['station_id'];
			    $fstation_name = $found_stations['station_name'];
			    $excel_station_type = $fstation_type = $found_stations['station_type'];
			    
			    $PHPExcel->createSheet(NULL, $sheet_index);
				$PHPExcel->setActiveSheetIndex($sheet_index)
				->setCellValue('A1', 'Reelforge Proof of Flight Report')
				->setCellValue('A2', 'Station : '.$fstation_name)
				->setCellValue('A3', 'Client : '.Yii::app()->user->company_name)
				->setCellValue('A5', 'Campaign Name')
				->setCellValue('B5', 'Brand Name')
				->setCellValue('C5', 'Date')
				->setCellValue('D5', 'Time')
				->setCellValue('E5', 'Type')
				->setCellValue('F5', 'Duration(h:m:s)')
				->setCellValue('G5', "Rate ($currency) ");

				$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A1:Z1');
				$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A2:Z2');
				$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A3:Z3');
				$PHPExcel->getActiveSheet($sheet_index)->mergeCells('A4:Z4');

				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('A')->setWidth(50);
				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('B')->setWidth(50);
				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('C')->setWidth(15);
				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('D')->setWidth(15);
				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('E')->setWidth(15);
				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('F')->setWidth(15);
				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('G')->setWidth(15);
				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('H')->setWidth(15);
				$PHPExcel->getActiveSheet($sheet_index)->getColumnDimension('I')->setWidth(15);

				$PHPExcel->getActiveSheet($sheet_index)->getStyle("A5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("B5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("C5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("D5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("E5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("F5")->applyFromArray($boldstyle);
				$PHPExcel->getActiveSheet($sheet_index)->getStyle("G5")->applyFromArray($boldstyle);

				$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
				$distinct_brands = "SELECT distinct brand_id, brand_name FROM $temp_table WHERE station_id=$fstation_id order by date, time";
				$count = 6;
			    if($brand_select = Yii::app()->db3->createCommand($distinct_brands)->queryAll()){
			    	foreach ($brand_select as $key) {
			    		$station_brand_id = $key['brand_id'];
        				$station_brand_name = $key['brand_name'];
        				$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $station_brand_name);
						$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
						$count++;

						if($adtypes==1){
				        	$adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$fstation_id AND brand_id=$station_brand_id order by date, time";
				        	if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
				        		foreach ($found_adtypes as $adkey) {
									$adname = $adkey['entry_type'];
									$PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $adname);
									$PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
									$count++;
									$union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id and brand_id = $station_brand_id AND entry_type='$adname' order by date, time";
									if($excel_data = Yii::app()->db3->createCommand($union_select)->queryAll()){
									    foreach ($excel_data as $elements) {
									        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
                                        	$link = $linkurl.$data_this_file_path;
									        $formated_rate = number_format((float)$elements['rate']);
									        if($elements['entry_type_id']==4){
							                	$elements['incantation_name'] = $elements['program_name'];
							                }
									        $PHPExcel->getActiveSheet()
									        ->setCellValue("A$count", $elements['incantation_name'])
									        ->setCellValue("B$count", $elements['brand_name'])
									        ->setCellValue("C$count", $elements['date'])
									        ->setCellValue("D$count", $elements['time'])
									        ->setCellValue("E$count", $elements['entry_type'])
									        ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
									        ->setCellValue("G$count", $formated_rate);
									        $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
									        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
									        $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
									        $count++;
									    }
									    $count++;
									}
								}
							}
						}else{
							$union_select = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$fstation_id.' and brand_id = '.$station_brand_id.' order by date, time';
	        				if($excel_data = Yii::app()->db3->createCommand($union_select)->queryAll()){
							    foreach ($excel_data as $elements) {
							        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$elements['file']);
                                    $link = $linkurl.$data_this_file_path;
							        $formated_rate = number_format((float)$elements['rate']);
							        if($elements['entry_type_id']==4){
					                	$elements['incantation_name'] = $elements['program_name'];
					                }
							        $PHPExcel->getActiveSheet()
							        ->setCellValue("A$count", $elements['incantation_name'])
							        ->setCellValue("B$count", $elements['brand_name'])
							        ->setCellValue("C$count", $elements['date'])
							        ->setCellValue("D$count", $elements['time'])
							        ->setCellValue("E$count", $elements['entry_type'])
							        ->setCellValue("F$count", gmdate("H:i:s", $elements['duration']))
							        ->setCellValue("G$count", $formated_rate);
							        $PHPExcel->getActiveSheet()->getCell("A$count")->getHyperlink()->setUrl($link);
							        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
							        $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
							        $count++;
							    }
							    $count++;
							}
						}
			    	}
			    }
				$fstation_name = substr($fstation_name, 0, 31);
				$PHPExcel->getActiveSheet()->setTitle($fstation_name);
				$sheet_index++;
			}

		}
		$PHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename="Reelforge_Anvil_POF_Reports_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" .date('dmYhis');
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename).'.xlsx';
        $objWriter->save($upload_path.$filename);
        $file = Yii::app()->request->baseUrl . '/docs/misc/excel/'.$filename;
        $fppackage = "<a href='$file' class='btn btn-success btn-xs pdf-excel' target='_blank'><i class='fa fa-file-excel-o'></i> Download EXCEL</a>";
		return $fppackage;
	}
}
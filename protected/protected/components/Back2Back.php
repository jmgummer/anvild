<?php

class Back2Back{
	public static function StandardPDF($temp_table,$reportname,$linkurl,$currency,$adtypes,$excludebrands){
		/* 
		** PDF Time
		*/
		$package = '';
		$station_sql = "SELECT DISTINCT $temp_table.station_id, station.station_name, station.station_type FROM $temp_table INNER JOIN station ON station.station_id = $temp_table.station_id WHERE $temp_table.brand_id IN ($excludebrands) order by station.station_name asc";

		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
			/*
			** Create Summary Section
			*/
			$package .= '<br>';
			$package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
			$package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Ad Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td><td>Back to Back</td><td>Time Bands</td></tr>';
			foreach ($stored_stations as $found_stations) {
			    $fstation_id = $found_stations['station_id'];
			    $fstation_name = $found_stations['station_name'];
			    $fstation_type = $found_stations['station_type'];
			    // Obtain Ad Types
			    $adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$fstation_id AND $temp_table.brand_id IN ($excludebrands) order by date, time";
	            if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
	                foreach ($found_adtypes as $adkey) {
	                    $adname = $adkey['entry_type'];
	                    // Obtain Results For Just The Company
	                    $union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND entry_type='$adname' AND $temp_table.brand_id IN ($excludebrands) order by date, time";
	                    if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
					        foreach ($data as $result) {
					        	$entry_identifier = $result['entry_type_id'];
					        	// Back to Back Evaluations
				                if($entry_identifier==2){
				                    $backtime = date('H:i:s',strtotime($result['time'])-1800);
				                    $fronttime = date('H:i:s',strtotime($result['time'])+1800);
				                }else{
				                    $backtime = date('H:i:s',strtotime($result['time'])-300);
				                    $fronttime = date('H:i:s',strtotime($result['time'])+300);
				                }
				                $ad_date = $result['date'];
				                $ad_brandid = $result['brand_id'];
				                $checksql = "SELECT * FROM $temp_table WHERE date='$ad_date' AND station_id=$fstation_id AND ( time BETWEEN '$backtime' AND '$fronttime' ) AND brand_id != $ad_brandid AND entry_type_id=$entry_identifier";
				                if($entries = Yii::app()->db3->createCommand($checksql)->queryAll()){
				                	$package .= '<tr>';
						            if($entry_identifier==3){
						            }else{
						                $popup_name = substr($result['incantation_name'],0,35);
						                if($fstation_type=='radio'){
						                    $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
						                    $media_link = $linkurl.$data_this_file_path;
						                    $media_link=str_replace("wav","mp3",$media_link);
						                }else{
						                    if($result['video_file']=='video_file'){
						                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
						                        $media_link = $linkurl.$data_this_file_path;
						                        $media_link=str_replace("wav","mp3",$media_link);
						                    }else{
						                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['video_file']);
						                        $media_link = $linkurl.$data_this_file_path;
						                    }
						                }
						            }
						            $formated_rate = number_format((float)$result['rate']);
					                $package .= '<td>'.$result['date'].'</td>';
						            $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
						            $package .= '<td>'.$result['time'].'</td>';
						            if($entry_identifier==3){ $package .= '<td class="fupisha">'.$result['incantation_name'].'</td>'; }else{ $package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>'; }
						            $package .= '<td>'.$result['brand_name'].'</td>';
						            $package .= '<td>'.$result['entry_type'].'</td>';
						            $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
						            $package .= '<td>'.$result['comment'].'</td>';
						            $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';
				                    $found = count($entries);
				                    $keyarray = array();
				                    $timearray = array();
				                    foreach ($entries as $ekeys) {
				                        // $keyarray[] = $ekeys['brand_name'].' - '.$ekeys['time'];
				                        $keyarray[] = $ekeys['brand_name'];
				                        $timearray[] = $ekeys['time'];
				                    }
				                    $keytext = "<br><small>".implode('<br>', $keyarray)."</small>";
				                    $keytime = "<br><small>".implode('<br>', $timearray)."</small>";
				                    $package .= '<td>'.$found.' Found '.$keytext.'</td>';
				                    $package .= '<td>Time Bound(s) '.$keytime.'</td>';
				                	$package .= '</tr>';
				                }
				                // End Back 2 Back
					        }
					    }
	                }
	            }
			    
			    // $union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND $temp_table.brand_id IN ($excludebrands) order by date, time";
				    
			}
			$package .= '</table>';
		}

		$anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
	    $pdf_header = '<img src="'.$anvil_header.'" width="100%" /><br>';
	    $generateddate = date('d-m-Y');
	    $pdf_header.= "<h2>Report : Proof of Flight</h2>";
	    $pdf_header.= "<p>Generated on $generateddate</p>";
	    $pdf_file = $pdf_header;
	    $pdf_file = $pdf_file.$package;
		$pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());
		$filename="Reelforge_Anvil_POF_Reports_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" .date('dmYhis');
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
		$filename_pdf=$filename.'.pdf';
		$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename_pdf;

		if(file_put_contents($location, $pdf)){
			$file = Yii::app()->request->baseUrl . '/docs/misc/pdf/'.$filename_pdf;
		    $fppackage = "<a href='$file' class='btn btn-danger btn-xs' target='_blank'><i class='fa fa-file-pdf-o'></i> Download PDF</a>";
		}else{
		    $fppackage = "";
		}
		return $fppackage;
	}
	public static function StandardExcel($temp_table,$reportname,$linkurl,$currency,$adtypes,$excludebrands){
		$PHPExcel = new PHPExcel();
		$station_sql = "SELECT DISTINCT $temp_table.station_id, station.station_name, station.station_type FROM $temp_table INNER JOIN station ON station.station_id = $temp_table.station_id WHERE $temp_table.brand_id IN ($excludebrands) order by station.station_name asc";
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
		    $title = $reportname;
		    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
		    ->setTitle("Reelforge Anvil Proof of Flight Reports")
		    ->setSubject("Reelforge Anvil Proof of Flight Reports")
		    ->setDescription("Reelforge Anvil Proof of Flight Reports");
		    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		    $sheet_index = 0;
		    $PHPExcel->createSheet(NULL, $sheet_index);
	        $PHPExcel->setActiveSheetIndex($sheet_index)
	        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
	        ->setCellValue('A2', 'Client : '.Yii::app()->user->company_name)
	        ->setCellValue('A3', 'Back to Back Report')
	        ->setCellValue('A5', 'Station Name')
	        ->setCellValue('B5', 'Ad Name')
	        ->setCellValue('C5', 'Brand Name')
	        ->setCellValue('D5', 'Date')
	        ->setCellValue('E5', 'Time')
	        ->setCellValue('F5', 'Type')
	        ->setCellValue('G5', 'Duration(h:m:s)')
	        ->setCellValue('H5', "Rate ($currency) ")
	        ->setCellValue('I5', "Back to Back")
	        ->setCellValue('J5', "Time Bands");
	        $boldstyle = array('font'  => array('bold'  => true));

	        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
	        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
	        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
	        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');

	        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
	        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
	        $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);

			$PHPExcel->getActiveSheet($sheet_index)->getStyle("A5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("B5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("C5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("D5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("E5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("F5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("G5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("H5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("I5")->applyFromArray($boldstyle);
			$PHPExcel->getActiveSheet($sheet_index)->getStyle("J5")->applyFromArray($boldstyle);
			$count = 6;
		    foreach ($stored_stations as $excel_stations) {
				$station_id = $excel_stations['station_id'];
				$station_name = $excel_stations['station_name'];
				$station_type = $excel_stations['station_type'];
		        $excel_station_type = $station_type;
		        $excel_station_id = $excel_stations['station_id'];
		        $excel_station_name = $station_name;
		        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
		        $adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$excel_station_id AND $temp_table.brand_id IN ($excludebrands) order by date, time";
	        	if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
	        		foreach ($found_adtypes as $adkey) {
						$adname = $adkey['entry_type'];
						// $PHPExcel->getActiveSheet($sheet_index)->setCellValue("A$count", $adname);
						// $PHPExcel->getActiveSheet($sheet_index)->getStyle("A$count")->applyFromArray($boldstyle);
						
						$union_select1 = "SELECT * FROM $temp_table WHERE station_id=$excel_station_id AND entry_type='$adname' AND $temp_table.brand_id IN ($excludebrands) order by date, time";
						if($excel_data = Yii::app()->db3->createCommand($union_select1)->queryAll()){
				            foreach ($excel_data as $elements) {
				            	$entry_identifier = $elements['entry_type_id'];
				            	// Back to Back Evaluations
		                        if($entry_identifier==2){
		                            $backtime = date('H:i:s',strtotime($elements['time'])-1800);
		                            $fronttime = date('H:i:s',strtotime($elements['time'])+1800);
		                        }else{
		                            $backtime = date('H:i:s',strtotime($elements['time'])-300);
		                            $fronttime = date('H:i:s',strtotime($elements['time'])+300);
		                        }
		                        $ad_date = $elements['date'];
		                        $ad_brandid = $elements['brand_id'];
		                        $checksql = "SELECT * FROM $temp_table WHERE date='$ad_date' AND station_id=$excel_station_id AND ( time BETWEEN '$backtime' AND '$fronttime' ) AND brand_id != $ad_brandid AND entry_type_id=$entry_identifier";
		                        if($entries = Yii::app()->db3->createCommand($checksql)->queryAll()){

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

					                $formated_rate = number_format((float)$elements['rate']);
					                $PHPExcel->getActiveSheet()
					                ->setCellValue("A$count", $excel_station_name)
					                ->setCellValue("B$count", $elements['incantation_name'])
					                ->setCellValue("C$count", $elements['brand_name'])
					                ->setCellValue("D$count", $elements['date'])
					                ->setCellValue("E$count", $elements['time'])
					                ->setCellValue("F$count", $elements['entry_type'])
					                ->setCellValue("G$count", gmdate("H:i:s", $elements['duration']))
					                ->setCellValue("H$count", $formated_rate);

		                            $found = count($entries);
		                            $keyarray = array();
		                            $timearray = array();
		                            foreach ($entries as $ekeys) {
		                                // $keyarray[] = $ekeys['brand_name'].' - '.$ekeys['time'];
		                                $keyarray[] = $ekeys['brand_name'];
		                                $timearray[] = $ekeys['time'];
		                            }
		                            $keytext = implode("\n", $keyarray);
		                            $keytime = implode("\n", $timearray);
		                            $PHPExcel->getActiveSheet()->setCellValue("I$count", $keytext);
		                            $PHPExcel->getActiveSheet()->setCellValue("J$count", $keytime);

		                            $PHPExcel->getActiveSheet()->getStyle("I$count")->getAlignment()->setWrapText(true);
		                            $PHPExcel->getActiveSheet()->getStyle("J$count")->getAlignment()->setWrapText(true);

		                            $PHPExcel->getActiveSheet()->getCell("B$count")->getHyperlink()->setUrl($link);
					                $PHPExcel->getActiveSheet()->getStyle("B$count")->applyFromArray($styleArray);
					                $PHPExcel->getActiveSheet()->getStyle("B$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
					                $count++;
		                        }
		                        

		                        // echo '<td>'.$found.' Found '.$keytext.'</td>';
		                        // End Back 2 Back

				                
				                // $count++;
				            }
				        }
					}
	        	}
		        
		        // $PHPExcel->getActiveSheet()->setTitle($excel_station_name);
		        // $sheet_index++;
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
	public static function CallinExcel($temp_table,$reportname,$linkurl,$currency,$adtypes,$excludebrands){
		$PHPExcel = new PHPExcel();
		$station_sql = "SELECT DISTINCT $temp_table.station_id, station.station_name, station.station_type FROM $temp_table INNER JOIN station ON station.station_id = $temp_table.station_id WHERE $temp_table.brand_id IN ($excludebrands) order by station.station_name asc";
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
		    $title = $reportname;
		    $PHPExcel->getProperties()->setCreator("Reelforge Systems")
		    ->setTitle("Reelforge Anvil Proof of Flight Reports")
		    ->setSubject("Reelforge Anvil Proof of Flight Reports")
		    ->setDescription("Reelforge Anvil Proof of Flight Reports");
		    $PHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
		    $sheet_index = 0;
		    $PHPExcel->createSheet(NULL, $sheet_index);
	        $PHPExcel->setActiveSheetIndex($sheet_index)
	        ->setCellValue('A1', 'Reelforge Proof of Flight Report')
	        ->setCellValue('A2', 'Client : '.Yii::app()->user->company_name)
	        ->setCellValue('A3', 'Proof of Flight Report - Call in')
	        ->setCellValue('A5', 'Station Name')
	        ->setCellValue('B5', 'Ad Name')
	        ->setCellValue('C5', 'Brand Name')
	        ->setCellValue('D5', 'Date')
	        ->setCellValue('E5', 'Time')
	        ->setCellValue('F5', 'Type')
	        ->setCellValue('G5', 'Duration(h:m:s)')
	        ->setCellValue('H5', "Rate ($currency) ");
	        $boldstyle = array('font'  => array('bold'  => true));

	        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
	        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
	        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
	        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');

	        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
	        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(50);

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
		    foreach ($stored_stations as $excel_stations) {
				$station_id = $excel_stations['station_id'];
				$station_name = $excel_stations['station_name'];
				$station_type = $excel_stations['station_type'];
		        $excel_station_type = $station_type;
		        $excel_station_id = $excel_stations['station_id'];
		        $excel_station_name = $station_name;
		        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
		        $adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$excel_station_id AND $temp_table.brand_id IN ($excludebrands) order by date, time";
	        	if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
	        		foreach ($found_adtypes as $adkey) {
						$adname = $adkey['entry_type'];
						
						$union_select1 = "SELECT * FROM $temp_table WHERE station_id=$excel_station_id AND entry_type='$adname' AND $temp_table.brand_id IN ($excludebrands) order by date, time";
						if($excel_data = Yii::app()->db3->createCommand($union_select1)->queryAll()){
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

				                $formated_rate = number_format((float)$elements['rate']);
				                $PHPExcel->getActiveSheet()
				                ->setCellValue("A$count", $excel_station_name)
				                ->setCellValue("B$count", $elements['incantation_name'])
				                ->setCellValue("C$count", $elements['brand_name'])
				                ->setCellValue("D$count", $elements['date'])
				                ->setCellValue("E$count", $elements['time'])
				                ->setCellValue("F$count", $elements['entry_type'])
				                ->setCellValue("G$count", gmdate("H:i:s", $elements['duration']))
				                ->setCellValue("H$count", $formated_rate);
	                            $PHPExcel->getActiveSheet()->getCell("B$count")->getHyperlink()->setUrl($link);
				                $PHPExcel->getActiveSheet()->getStyle("B$count")->applyFromArray($styleArray);
				                $PHPExcel->getActiveSheet()->getStyle("B$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
				                $count++;
				            }
				        }
					}
	        	}
		        
		        // $PHPExcel->getActiveSheet()->setTitle($excel_station_name);
		        // $sheet_index++;
		    }
		}
		$PHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename="Reelforge_Anvil_POF_Callin_Reports_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" .date('dmYhis');
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename).'.xlsx';
        $objWriter->save($upload_path.$filename);
        $file = Yii::app()->request->baseUrl . '/docs/misc/excel/'.$filename;
        $fppackage = "<a href='$file' class='btn btn-success btn-xs pdf-excel' target='_blank'><i class='fa fa-file-excel-o'></i> Download EXCEL</a>";
		return $fppackage;
	}
}
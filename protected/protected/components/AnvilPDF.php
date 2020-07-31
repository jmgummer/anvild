<?php
ini_set('memory_limit', '1024M');
class AnvilPDF{
	public static function StandardPDF($temp_table,$reportname,$linkurl,$currency,$adtypes){
		/* 
		** PDF Time
		*/
		$package = '';
		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
			/*
			** Create Summary Section
			*/
			$package .= '<p><strong>Summary</strong></p>';
			foreach ($stored_stations as $found_stations) {
				$fstation_id = $found_stations['station_id'];
				$fstation_name = $found_stations['station_name'];
				$adtypes_sql = "SELECT count(entry_type) as adnumber, entry_type, sum(rate) AS rate FROM $temp_table
				WHERE station_id=$fstation_id GROUP BY entry_type";
				if($data = Yii::app()->db3->createCommand($adtypes_sql)->queryAll()){
					$package .= '<p><strong>'.$fstation_name.'</strong></p>';
					$package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover"><tr><td width="40%"><strong>Ad Type</strong></td><td width="30%"><strong>Number of Ads</strong></td><td width="30%"><strong>Value of Ads ('.$currency.')</strong></td></tr>';
					$stationtotal = 0;
					foreach ($data as $key) {
						$adtype = $key['entry_type'];
						$adnumber = $key['adnumber'];
						$adrate = number_format($key['rate']);
						$package .= '<tr><td>'.$adtype.'</td><td>'.$adnumber.'</td><td>'.$adrate.'</td></tr>';
						$stationtotal = $stationtotal+$key['rate'];
					}
					$package .= '<tr><td><strong>Station Total</strong></td><td></td><td>'.number_format($stationtotal).'</td></tr>';

					$package .= '</table>';
				}
			}
			$package .= '<p style="page-break-after: always;">&nbsp;</p>';
			$pdfcount = count($stored_stations);
			$setcount = 1;
			foreach ($stored_stations as $found_stations) {
			    $fstation_id = $found_stations['station_id'];
			    $fstation_name = $found_stations['station_name'];
			    $fstation_type = $found_stations['station_type'];
			    $package .= '<p><strong>'.$fstation_name.'</strong></p>';
			    if($adtypes==1){
					$adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$fstation_id order by date, time";
					if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
						foreach ($found_adtypes as $adkey) {
							$adname = $adkey['entry_type'];
							$package .= '<p><u>'.$adname.'</u></p>';
							$union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND entry_type='$adname' order by date, time";
							if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
						        $package .= '<br>';
						        $package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
						        $package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Campaign Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td></tr>';
						        $sum = 0;
						        foreach ($data as $result) {
						            $package .= '<tr>';
						            $entry_identifier = $result['entry_type_id'];
									$popup_name = substr($result['incantation_name'],0,35);
									$data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
									$media_link = $linkurl.$data_this_file_path;
						            $formated_rate = number_format((float)$result['rate']);
						            if($result['entry_type_id']==4){
					                	$result['incantation_name'] = $result['program_name'];
					                }
						            $package .= '<td>'.$result['date'].'</td>';
						            $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
						            $package .= '<td>'.$result['time'].'</td>';
					            	$package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>'; 
						            $package .= '<td>'.$result['brand_name'].'</td>';
						            $package .= '<td>'.$result['entry_type'].'</td>';
						            $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
						            $package .= '<td>'.$result['comment'].'</td>';
						            $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';
						            $package .= '</tr>';
						            $sum = $sum + $result['rate'];
						        }
						        $package .= '</table>';
						        $package .= '<div class="row-fluid clearfix">';
						        $total = count($data);
						        $package .= '<p class="pull-left"><strong>AD TYPE TOTAL ('.$adname.') | Total Number of Ads '.$total.'</strong></p>';
						        $package .= '</div>';
						    }
						}
					}
				}else{
				    $union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id order by date, time";
				    if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
				        $package .= '<br>';
				        $package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
				        $package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Campaign Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td></tr>';
				        $sum = 0;
				        foreach ($data as $result) {
				            $package .= '<tr>';
				            $entry_identifier = $result['entry_type_id'];
			                $popup_name = substr($result['incantation_name'],0,35);
							$data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
							$media_link = $linkurl.$data_this_file_path;
				            $formated_rate = number_format((float)$result['rate']);
				            if($result['entry_type_id']==4){
			                	$result['incantation_name'] = $result['program_name'];
			                }
				            $package .= '<td>'.$result['date'].'</td>';
				            $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
				            $package .= '<td>'.$result['time'].'</td>';
				            $package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>';
				            $package .= '<td>'.$result['brand_name'].'</td>';
				            $package .= '<td>'.$result['entry_type'].'</td>';
				            $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
				            $package .= '<td>'.$result['comment'].'</td>';
				            $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';
				            $package .= '</tr>';
				            $sum = $sum + $result['rate'];
				        }
				        $package .= '</table>';
				        $package .= '<div class="row-fluid clearfix">';
				        $total = count($data);
				        $package .= '<p class="pull-left"><strong>STATION TOTAL ('.$fstation_name.') | Total Number of Ads '.$total.'</strong></p>';
				        $package .= '</div>';
				    }
			    }
			    if($setcount<$pdfcount){
			    	$package .= '<p style="page-break-after: always;">&nbsp;</p>';
			    }
			    $setcount++;
			}
		}

		$anvil_header = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
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

	public static function StandardKeywordsPDF($temp_table,$reportname,$linkurl,$currency,$adtypes){
		/* 
		** PDF Time
		*/
		$package = '';
		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
			/*
			** Create Summary Section
			*/
			$package .= '<p><strong>Summary</strong></p>';
			foreach ($stored_stations as $found_stations) {
				$fstation_id = $found_stations['station_id'];
				$fstation_name = $found_stations['station_name'];
				$adtypes_sql = "SELECT count(entry_type) as adnumber, entry_type, sum(rate) AS rate FROM $temp_table
				WHERE station_id=$fstation_id GROUP BY entry_type";
				if($data = Yii::app()->db3->createCommand($adtypes_sql)->queryAll()){
					$package .= '<p><strong>'.$fstation_name.'</strong></p>';
					$package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover"><tr><td width="40%"><strong>Ad Type</strong></td><td width="30%"><strong>Number of Ads</strong></td><td width="30%"><strong>Value of Ads ('.$currency.')</strong></td></tr>';
					$stationtotal = 0;
					foreach ($data as $key) {
						$adtype = $key['entry_type'];
						$adnumber = $key['adnumber'];
						$adrate = number_format($key['rate']);
						$package .= '<tr><td>'.$adtype.'</td><td>'.$adnumber.'</td><td>'.$adrate.'</td></tr>';
						$stationtotal = $stationtotal+$key['rate'];
					}
					$package .= '<tr><td><strong>Station Total</strong></td><td></td><td>'.number_format($stationtotal).'</td></tr>';

					$package .= '</table>';
				}
			}
			$package .= '<p style="page-break-after: always;">&nbsp;</p>';
			$pdfcount = count($stored_stations);
			$setcount = 1;
			foreach ($stored_stations as $found_stations) {
			    $fstation_id = $found_stations['station_id'];
			    $fstation_name = $found_stations['station_name'];
			    $fstation_type = $found_stations['station_type'];
			    $package .= '<p><strong>'.$fstation_name.'</strong></p>';
			    if($adtypes==1){
					$adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$fstation_id order by date, time";
					if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
						foreach ($found_adtypes as $adkey) {
							$adname = $adkey['entry_type'];
							$package .= '<p><u>'.$adname.'</u></p>';
							$union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND entry_type='$adname' order by date, time";
							if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
						        $package .= '<br>';
						        $package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
						        $package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Campaign Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td><td>Good Words</td><td>Bad Words</td></tr>';
						        $sum = 0;
						        foreach ($data as $result) {
						            $package .= '<tr>';
						            $entry_identifier = $result['entry_type_id'];
						            // if($entry_identifier==3){
						            // }else{
						                $popup_name = substr($result['incantation_name'],0,35);
						                $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
							$media_link = $linkurl.$data_this_file_path;

						            $formated_rate = number_format((float)$result['rate']);
						            if($result['entry_type_id']==4){
					                	$result['incantation_name'] = $result['program_name'];
					                }
					                
						            $package .= '<td>'.$result['date'].'</td>';
						            $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
						            $package .= '<td>'.$result['time'].'</td>';
						         	// if($entry_identifier==3){ 
						         	//   $package .= '<td class="fupisha">'.$result['incantation_name'].'</td>'; 
						        	// }else{ 
						        		$package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>'; 
						        	// }
						            $package .= '<td>'.$result['brand_name'].'</td>';
						            $package .= '<td>'.$result['entry_type'].'</td>';
						            $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
						            $package .= '<td>'.$result['comment'].'</td>';
						            $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';

						            // If Djmention Tings, Checking for Keywords
		                            if($entry_identifier==2){
		                                $mentionid = $result['auto_id'];
		                                $mentionbrandid = $result['brand_id'];
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
		                                    
		                                    $package .= "<td>$entrycount/$totalgoodwords_count <small><br>$analogy</small></td>";
		                                }else{
		                                    $package .= "<td>0/$totalgoodwords_count</td>";
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
		                                    $package .= "<td>$entrycount/$totalbadwords_count <small><br>$analogy</small></td>";
		                                }else{
		                                    $package .= "<td>0/$totalbadwords_count</td>";
		                                }
		                                unset($listarray);
		                            }
		                            // End Djmention Tings
						            $package .= '</tr>';
						            $sum = $sum + $result['rate'];
						        }
						        $package .= '</table>';
						        $package .= '<div class="row-fluid clearfix">';
						        $total = count($data);
						        $package .= '<p class="pull-left"><strong>AD TYPE TOTAL ('.$adname.') | Total Number of Ads '.$total.'</strong></p>';
						        $package .= '</div>';
						    }
						}
					}
				}else{
				    $union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id order by date, time";
				    if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
				        $package .= '<br>';
				        $package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
				        $package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Campaign Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td><td>Good Words</td><td>Bad Words</td></tr>';
				        $sum = 0;
				        foreach ($data as $result) {
				            $package .= '<tr>';
				            $entry_identifier = $result['entry_type_id'];
				            // if($entry_identifier==3){
				            // }else{
				                $popup_name = substr($result['incantation_name'],0,35);
				                $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
							$media_link = $linkurl.$data_this_file_path;

				            $formated_rate = number_format((float)$result['rate']);
				            if($result['entry_type_id']==4){
			                	$result['incantation_name'] = $result['program_name'];
			                }
			                
				            $package .= '<td>'.$result['date'].'</td>';
				            $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
				            $package .= '<td>'.$result['time'].'</td>';
				            // if($entry_identifier==3){ 
				            // 	$package .= '<td class="fupisha">'.$result['incantation_name'].'</td>'; 
				            // }else{ 
				            	$package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>'; 
				            // }
				            $package .= '<td>'.$result['brand_name'].'</td>';
				            $package .= '<td>'.$result['entry_type'].'</td>';
				            $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
				            $package .= '<td>'.$result['comment'].'</td>';
				            $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';

				            // If Djmention Tings, Checking for Keywords
                            if($entry_identifier==2){
                                $mentionid = $result['auto_id'];
                                $mentionbrandid = $result['brand_id'];
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
                                    
                                    $package .= "<td>$entrycount/$totalgoodwords_count <small><br>$analogy</small></td>";
                                }else{
                                    $package .= "<td>0/$totalgoodwords_count</td>";
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
                                    $package .= "<td>$entrycount/$totalbadwords_count <small><br>$analogy</small></td>";
                                }else{
                                    $package .= "<td>0/$totalbadwords_count</td>";
                                }
                                unset($listarray);
                            }
                            // End Djmention Tings
				            $package .= '</tr>';
				            $sum = $sum + $result['rate'];
				        }
				        $package .= '</table>';
				        $package .= '<div class="row-fluid clearfix">';
				        $total = count($data);
				        $package .= '<p class="pull-left"><strong>STATION TOTAL ('.$fstation_name.') | Total Number of Ads '.$total.'</strong></p>';
				        $package .= '</div>';
				    }
			    }
			    if($setcount<$pdfcount){
			    	$package .= '<p style="page-break-after: always;">&nbsp;</p>';
			    }
			    $setcount++;
			}
		}

		$anvil_header = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
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

	public static function BrandPDF($temp_table,$reportname,$linkurl,$currency,$adtypes){
		/* 
		** PDF Time
		*/
		$package = '';
		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
			/*
			** Create Summary Section
			*/
			$package .= '<p><strong>Summary</strong></p>';
			foreach ($stored_stations as $found_stations) {
				$fstation_id = $found_stations['station_id'];
				$fstation_name = $found_stations['station_name'];
				$adtypes_sql = "SELECT count(entry_type) as adnumber, entry_type, sum(rate) AS rate FROM $temp_table
				WHERE station_id=$fstation_id GROUP BY entry_type";
				if($data = Yii::app()->db3->createCommand($adtypes_sql)->queryAll()){
					$package .= '<p><strong>'.$fstation_name.'</strong></p>';
					$package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover"><tr><td width="40%"><strong>Ad Type</strong></td><td width="30%"><strong>Number of Ads</strong></td><td width="30%"><strong>Value of Ads ('.$currency.')</strong></td></tr>';
					$stationtotal = 0;
					foreach ($data as $key) {
						$adtype = $key['entry_type'];
						$adnumber = $key['adnumber'];
						$adrate = number_format($key['rate']);
						$package .= '<tr><td>'.$adtype.'</td><td>'.$adnumber.'</td><td>'.$adrate.'</td></tr>';
						$stationtotal = $stationtotal+$key['rate'];
					}
					$package .= '<tr><td><strong>Station Total</strong></td><td></td><td>'.number_format($stationtotal).'</td></tr>';

					$package .= '</table>';
				}
			}
			$package .= '<p style="page-break-after: always;">&nbsp;</p>';
			// Brands Breakdown Starts Here
			$distinct_brands = "SELECT distinct brand_id, brand_name FROM $temp_table order by date, time";
			if($brand_select = Yii::app()->db3->createCommand($distinct_brands)->queryAll()){
				$pdfcount = count($brand_select);
				$setcount = 1;
				foreach ($brand_select as $found_brands) {
					$set_brand_id = $found_brands['brand_id'];
        			$set_brand_name = $found_brands['brand_name'];
					$station_sql = "SELECT distinct $temp_table.station_id, station.station_name, station.station_type  
					from $temp_table inner join station on station.station_id = $temp_table.station_id
					where  $temp_table.brand_id = $set_brand_id order by station.station_name asc";
					$package .= '<p><strong>'.$set_brand_name.'</strong></p>';
					if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
						foreach ($stored_stations as $found_stations) {
							$set_station_name = $found_stations['station_name'];
			                $set_station_id = $found_stations['station_id'];
			                $fstation_type = $found_stations['station_type'];
			                $package .= '<p><strong>'.$set_station_name.'</strong></p>';
			                if($adtypes==1){
								$adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$set_station_id AND brand_id=$set_brand_id order by date, time";
								if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
									foreach ($found_adtypes as $adkey) {
										$adname = $adkey['entry_type'];
										$package .= '<p><u>'.$adname.'</u></p>';
										$union_select = "SELECT * FROM $temp_table WHERE station_id=$set_station_id AND entry_type='$adname' AND brand_id = $set_brand_id order by date, time";
										if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
										    $package .= '<br>';
										    $package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
										    $package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Campaign Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td></tr>';
										    $sum = 0;
										    foreach ($data as $result) {
										        $package .= '<tr>';
										        $entry_identifier = $result['entry_type_id'];
										        // if($entry_identifier==3){
										        // }else{
										            $popup_name = substr($result['incantation_name'],0,35);
										            $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
							$media_link = $linkurl.$data_this_file_path;

										        $formated_rate = number_format((float)$result['rate']);
										        if($result['entry_type_id']==4){
								                	$result['incantation_name'] = $result['program_name'];
								                }
										        
										        $package .= '<td>'.$result['date'].'</td>';
										        $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
										        $package .= '<td>'.$result['time'].'</td>';
										        // if($entry_identifier==3){ 
										        // 	$package .= '<td class="fupisha">'.$result['incantation_name'].'</td>'; 
										        // }else{ 
										        	$package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>'; 
										        // }
										        $package .= '<td>'.$result['brand_name'].'</td>';
										        $package .= '<td>'.$result['entry_type'].'</td>';
										        $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
										        $package .= '<td>'.$result['comment'].'</td>';
										        $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';
										        $package .= '</tr>';
										        $sum = $sum + $result['rate'];
										    }
										    $package .= '</table>';
										    $package .= '<div class="row-fluid clearfix">';
										    $total = count($data);
										    $package .= '<p class="pull-left"><strong>AD TYPE TOTAL ('.$adname.') | Total Number of Ads '.$total.'</strong></p>';
										    $package .= '</div>';
										}
									}
								}
							}else{
								$union_select = "SELECT * FROM $temp_table WHERE station_id=$set_station_id AND brand_id = $set_brand_id order by date, time";
				                if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
								    $package .= '<br>';
								    $package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
								    $package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Campaign Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td></tr>';
								    $sum = 0;
								    foreach ($data as $result) {
								        $package .= '<tr>';
								        $entry_identifier = $result['entry_type_id'];
								        // if($entry_identifier==3){
								        // }else{
								            $popup_name = substr($result['incantation_name'],0,35);
								            $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
							$media_link = $linkurl.$data_this_file_path;

								        $formated_rate = number_format((float)$result['rate']);
								        if($result['entry_type_id']==4){
						                	$result['incantation_name'] = $result['program_name'];
						                }
								        
								        $package .= '<td>'.$result['date'].'</td>';
								        $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
								        $package .= '<td>'.$result['time'].'</td>';
								        // if($entry_identifier==3){ 
								        // 	$package .= '<td class="fupisha">'.$result['incantation_name'].'</td>'; 
								        // }else{ 
								        	$package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>'; 
								        // }
								        $package .= '<td>'.$result['brand_name'].'</td>';
								        $package .= '<td>'.$result['entry_type'].'</td>';
								        $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
								        $package .= '<td>'.$result['comment'].'</td>';
								        $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';
								        $package .= '</tr>';
								        $sum = $sum + $result['rate'];
								    }
								    $package .= '</table>';
								    $package .= '<div class="row-fluid clearfix">';
								    $total = count($data);
								    $package .= '<p class="pull-left"><strong>STATION TOTAL ('.$set_station_name.') | Total Number of Ads '.$total.'</strong></p>';
								    $package .= '</div>';
								}
							}
						}
					}
					if($setcount<$pdfcount){
				    	$package .= '<p style="page-break-after: always;">&nbsp;</p>';
				    }
				    $setcount++;
				}
			}
		}
		$anvil_header = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
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

	public static function StationPDF($temp_table,$reportname,$linkurl,$currency,$adtypes){
		$package = '';
		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
			/*
			** Create Summary Section
			*/
			$package .= '<p><strong>Summary</strong></p>';
			foreach ($stored_stations as $found_stations) {
				$fstation_id = $found_stations['station_id'];
				$fstation_name = $found_stations['station_name'];
				$adtypes_sql = "SELECT count(entry_type) as adnumber, entry_type, sum(rate) AS rate FROM $temp_table
				WHERE station_id=$fstation_id GROUP BY entry_type";
				if($data = Yii::app()->db3->createCommand($adtypes_sql)->queryAll()){
					$package .= '<p><strong>'.$fstation_name.'</strong></p>';
					$package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover"><tr><td width="40%"><strong>Ad Type</strong></td><td width="30%"><strong>Number of Ads</strong></td><td width="30%"><strong>Value of Ads ('.$currency.')</strong></td></tr>';
					$stationtotal = 0;
					foreach ($data as $key) {
						$adtype = $key['entry_type'];
						$adnumber = $key['adnumber'];
						$adrate = number_format($key['rate']);
						$package .= '<tr><td>'.$adtype.'</td><td>'.$adnumber.'</td><td>'.$adrate.'</td></tr>';
						$stationtotal = $stationtotal+$key['rate'];
					}
					$package .= '<tr><td><strong>Station Total</strong></td><td></td><td>'.number_format($stationtotal).'</td></tr>';

					$package .= '</table>';
				}
			}
			$package .= '<p style="page-break-after: always;">&nbsp;</p>';
			$pdfcount = count($stored_stations);
			$setcount = 1;
			foreach ($stored_stations as $found_stations) {
			    $fstation_id = $found_stations['station_id'];
			    $fstation_name = $found_stations['station_name'];
			    $fstation_type = $found_stations['station_type'];
			    $package .= '<p><strong>'.$fstation_name.'</strong></p>';
			    $distinct_brands = 'SELECT distinct brand_id, brand_name FROM '.$temp_table.' WHERE station_id='.$fstation_id.' order by date, time';
			    if($brand_select = Yii::app()->db3->createCommand($distinct_brands)->queryAll()){
					foreach ($brand_select as $key) {
						$station_brand_id = $key['brand_id'];
        				$station_brand_name = $key['brand_name'];
        				$package .= '<p><br><strong>'.$station_brand_name.'</strong></p>';
        				if($adtypes==1){
							$adtype_query = "SELECT DISTINCT entry_type, entry_type_id FROM $temp_table WHERE station_id=$fstation_id order by date, time";
							if($found_adtypes = Yii::app()->db3->createCommand($adtype_query)->queryAll()){
								foreach ($found_adtypes as $adkey) {
									$adname = $adkey['entry_type'];
									$package .= '<p><u>'.$adname.'</u></p>';
									$union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND entry_type='$adname' AND brand_id = $station_brand_id order by date, time";
									if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
								        $package .= '<br>';
								        $package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
								        $package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Campaign Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td></tr>';
								        $sum = 0;
								        foreach ($data as $result) {
								            $package .= '<tr>';
								            $entry_identifier = $result['entry_type_id'];
								            // if($entry_identifier==3){
								            // }else{
								                $popup_name = substr($result['incantation_name'],0,35);
								                $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
							$media_link = $linkurl.$data_this_file_path;
								            $formated_rate = number_format((float)$result['rate']);
								            if($result['entry_type_id']==4){
							                	$result['incantation_name'] = $result['program_name'];
							                }
								            $package .= '<td>'.$result['date'].'</td>';
								            $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
								            $package .= '<td>'.$result['time'].'</td>';
								            // if($entry_identifier==3){ 
								            // 	$package .= '<td class="fupisha">'.$result['incantation_name'].'</td>'; 
								            // }else{ 
								            	$package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>'; 
								            // }
								            $package .= '<td>'.$result['brand_name'].'</td>';
								            $package .= '<td>'.$result['entry_type'].'</td>';
								            $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
								            $package .= '<td>'.$result['comment'].'</td>';
								            $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';
								            $package .= '</tr>';
								            $sum = $sum + $result['rate'];
								        }
								        $package .= '</table>';
								        $package .= '<div class="row-fluid clearfix">';
								        $total = count($data);
								        $package .= '<p class="pull-left"><strong>AD TYPE TOTAL ('.$adname.') | Total Number of Ads '.$total.'</strong></p>';
								        $package .= '</div>';
								    }
								}
							}
						}else{
							$union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id and brand_id = $station_brand_id order by date, time";
	        				if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
						        $package .= '<br>';
						        $package .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
						        $package .= '<tr><td>Date</td><td>Day</td><td>Time</td><td>Campaign Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td></tr>';
						        $sum = 0;
						        foreach ($data as $result) {
						            $package .= '<tr>';
						            $entry_identifier = $result['entry_type_id'];
						            // if($entry_identifier==3){
						            // }else{
						                $popup_name = substr($result['incantation_name'],0,35);
						                $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
							$media_link = $linkurl.$data_this_file_path;
						            $formated_rate = number_format((float)$result['rate']);
						            if($result['entry_type_id']==4){
					                	$result['incantation_name'] = $result['program_name'];
					                }
						            $package .= '<td>'.$result['date'].'</td>';
						            $package .= '<td>'.date('D',strtotime($result['date'])).'</td>';
						            $package .= '<td>'.$result['time'].'</td>';
						            // if($entry_identifier==3){ 
						            // 	$package .= '<td class="fupisha">'.$result['incantation_name'].'</td>'; 
						            // }else{ 
						            	$package .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>'; 
						            // }
						            $package .= '<td>'.$result['brand_name'].'</td>';
						            $package .= '<td>'.$result['entry_type'].'</td>';
						            $package .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
						            $package .= '<td>'.$result['comment'].'</td>';
						            $package .= '<td style="text-align:right;">'.$formated_rate.'</td>';
						            $package .= '</tr>';
						            $sum = $sum + $result['rate'];
						        }
						        $package .= '</table>';
						        $package .= '<div class="row-fluid clearfix">';
						        $total = count($data);
						        $package .= '<p class="pull-left"><strong>BRAND TOTAL ('.$station_brand_name.') | Total Number of Ads '.$total.'</strong></p>';
						        $package .= '</div>';
						    }
						}
					}
				}
				if($setcount<$pdfcount){
			    	$package .= '<p style="page-break-after: always;">&nbsp;</p>';
			    }
			    $setcount++;
			}
		}
		$anvil_header = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
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
}
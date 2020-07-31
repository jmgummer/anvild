<?php

class QCPDF
{
	public static function StandardPDF($temp_table,$startdate,$enddate,$clientname){
		$html = '';
		$electronicplayer = Yii::app()->params['eleclink'];
		$country_currency = Yii::app()->params['country_currency'];
		$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table ORDER BY station ASC";
		$stored_stations = Yii::app()->db3->createCommand($stationquery)->queryAll();

	    $html .= '<p>The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate.'</p><br>';
	    $html .= "<h4>Client: $clientname</h4>";
	    foreach ($stored_stations as $found_stations) {
	    	$fstation_id = $found_stations['station_id'];
	    	$fstation_name = $found_stations['station_name'];

	    	$html .= "<p><strong>$fstation_name</strong><br></p>";

	    	$html .= "<table class='table table-bordered table-condensed table-striped'> ";
			$html .= "<tr><td>#</td><td>Date</td><td>Day</td><td>Time</td><td>Ad Name</td><td>Brand Name</td><td>Ad Type</td><td>Duration</td><td>Comment</td><td>Rate</td></tr>";

	        $stationsql = "SELECT * FROM $temp_table WHERE station_id=$fstation_id ORDER BY date, time ASC";
			if($excel_data = Yii::app()->db3->createCommand($stationsql)->queryAll()){
				$count = 1;
				$stationsum = 0;
				foreach ($excel_data as $elements) {
					$ad_name = $elements['ad_name'];
					$brand = $elements['brand_name'];
					$date = $elements['date'];
					$time = $elements['time'];
					$type = $elements['type'];
					$duration = $elements['duration'];
					$rate = number_format( (int)$elements['rate'] );
					$day = date("D",strtotime($elements['date']));

					$file = $elements['file'];
					$comment = $elements['comment'];
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
					$html .= "<tr><td>$count</td><td>$date</td><td>$day</td><td>$time</td><td><a href='$url'>$ad_name</a></td><td>$brand</td><td>$type</td><td>$duration</td><td>$comment</td><td>$rate</td></tr>";
					$stationsum = $stationsum + (int)$elements['rate'];
					$count++;
				}
			}
			$html .= "</table> ";
			$format_total = number_format($stationsum);
			$adcount = count($excel_data);
			$html .= "<p><strong>Station Totals | Number of Ads - $adcount | Amount -  $format_total</strong></p><br>";
			$html .= "<hr>";
	    }
		$anvil_header = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
		$pdf_header = '<img src="'.$anvil_header.'" width="100%" /><br>';
		$pdf_file = $pdf_header.$html;
		$pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());
		$filename =str_replace(" ","_",$clientname);
        $filename = 'QC_POF_'.date("Ymdhis").'_'.$filename.'.pdf';
		$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename;
		file_put_contents($location, $pdf);

	    return $filename;
	}

	public static function StationBrandPDF($temp_table,$startdate,$enddate,$clientname){
		$html = '';
		$electronicplayer = Yii::app()->params['eleclink'];
		$country_currency = Yii::app()->params['country_currency'];
		$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table ORDER BY station ASC";
		$stored_stations = Yii::app()->db3->createCommand($stationquery)->queryAll();

	    $html .= '<p>The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate.'</p><br>';
	    $html .= "<h4>Client: $clientname</h4>";
	    foreach ($stored_stations as $found_stations) {
	    	$fstation_id = $found_stations['station_id'];
	    	$fstation_name = $found_stations['station_name'];
	    	$html .= "<p><strong>$fstation_name</strong><br></p>";
	    	$brandquery = "SELECT DISTINCT brand_id, brand_name FROM $temp_table WHERE station_id=$fstation_id ORDER BY brand_name ASC";
	    	if($brands = Yii::app()->db3->createCommand($brandquery)->queryAll()){
				foreach ($brands as $brandkey) {
					$fbrandid = $brandkey['brand_id'];
					$fbrandname = $brandkey['brand_name'];
					$html .= "<p><strong>$fbrandname</strong></p>";
					$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND brand_id=$fbrandid ORDER BY date,time ASC";
					if($excel_data = Yii::app()->db3->createCommand($brandsql)->queryAll()){
						$html .= "<table class='table table-bordered table-condensed table-striped'> ";
						$html .= "<tr><td>#</td><td>Date</td><td>Day</td><td>Time</td><td>Ad Name</td><td>Brand Name</td><td>Ad Type</td><td>Duration</td><td>Comment</td><td>Rate</td></tr>";
						$count = 1;
						$brandsum = 0;
						foreach ($excel_data as $elements) {
							$ad_name = $elements['ad_name'];
							$brand = $elements['brand_name'];
							$date = $elements['date'];
							$time = $elements['time'];
							$type = $elements['type'];
							$duration = $elements['duration'];
							$rate = number_format( (int)$elements['rate'] );
							$day = date("D",strtotime($elements['date']));

							$file = $elements['file'];
							$comment = $elements['comment'];
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
							$html .= "<tr><td>$count</td><td>$date</td><td>$day</td><td>$time</td><td><a href='$url'>$ad_name</a></td><td>$brand</td><td>$type</td><td>$duration</td><td>$comment</td><td>$rate</td></tr>";
							$brandsum = $brandsum + (int)$elements['rate'];
							$count++;
						}
						$html .= "</table> ";
						$format_total = number_format($brandsum);
						$adcount = count($excel_data);
						$html .= "<p><strong>Brand Totals | Number of Ads - $adcount | Amount -  $format_total</strong></p><br>";
					}
				}
			}
			$html .= "<hr>";
	    }
		$anvil_header = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
		$pdf_header = '<img src="'.$anvil_header.'" width="100%" /><br>';
		$pdf_file = $pdf_header.$html;
		$pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());
		$filename =str_replace(" ","_",$clientname);
        $filename = 'QC_POF_'.date("Ymdhis").'_'.$filename.'.pdf';
		$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename;
		file_put_contents($location, $pdf);
	    return $filename;
	}

	public static function BrandStationPDF($temp_table,$startdate,$enddate,$clientname){
		$html = '';
		$electronicplayer = Yii::app()->params['eleclink'];
		$country_currency = Yii::app()->params['country_currency'];

	    $html .= '<p>The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate.'</p><br>';
	    $html .= "<h4>Client: $clientname</h4>";
	    $html .= "<h4>Station - Ads Summaries</h4>";
	    $html .= "<div style='page-break-after: always'>";
	    $sql_summary = "SELECT DISTINCT(station_id), station FROM $temp_table";
	    $station_summary = Yii::app()->db3->createCommand($sql_summary)->queryAll();
	    foreach ($station_summary as $station_summary) {
	    	$station_id = $station_summary['station_id'];
	    	$station_name = $station_summary['station'];

	    	$html .= "<br><br><table class='table table-bordered table-condensed table-striped' style='page-break-inside: avoid'> ";
	    	$html .= "<tr><td colspan='4'><p><strong>$station_name</strong><br></p></td></tr>";
	    	
	    	$sql_adtype_summary = "SELECT `type`, COUNT(adid) AS `total`, SUM(rate) AS `value` FROM $temp_table WHERE station_id = '$station_id' GROUP BY `type`";
	    	if($adtype_summary = Yii::app()->db3->createCommand($sql_adtype_summary)->queryAll()){
				$html .= "<tr><td><strong>#</strong></td><td><strong>Ad Type</strong></td><td><strong>Number of Ads</strong></td><td><strong>Value of Ads ($country_currency)</strong></td></tr>";
				$count = 1;
				$stationsum = 0;
	    		foreach ($adtype_summary as $adtype_summary) {
	    			$adtype_name = $adtype_summary['type'];
	    			$total_ads = $adtype_summary['total'];
	    			$total_value = number_format( (int)$adtype_summary['value'] );

	    			$html .= "<tr><td>$count</td><td>$adtype_name</td><td>$total_ads</td><td>$total_value</td></tr>";
	    			$count++;
	    		}
	    		$html .= "</table>";
	    	}
	    }
    	$html .= "</div>";

		$brandquery = "SELECT DISTINCT brand_id, brand_name FROM $temp_table ORDER BY brand_name ASC";
		$stored_brands = Yii::app()->db3->createCommand($brandquery)->queryAll();
	    foreach ($stored_brands as $found_brands) {
	    	$fbrandid = $found_brands['brand_id'];
			$fbrandname = $found_brands['brand_name'];
	    	$html .= "<p><strong>$fbrandname</strong><br></p>";

	    	$adtypequery = "SELECT DISTINCT `type` AS entry_type_name,entry_type_id FROM $temp_table,djmentions_entry_types WHERE djmentions_entry_types.entry_type = $temp_table.`type` AND brand_id=$fbrandid ORDER BY entry_type_name ASC";
			if($stored_adtypes = Yii::app()->db3->createCommand($adtypequery)->queryAll()){
				foreach ($stored_adtypes as $found_adtypes) {
					$html .= "<div style='page-break-after: always'>";
					$entry_type_id = $found_adtypes['entry_type_id'];
					$entry_type_name = $found_adtypes['entry_type_name'];
					$html .= "<p><strong>$entry_type_name ($fbrandname)</strong></p>";

					$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table WHERE brand_id=$fbrandid AND `type`='$entry_type_name' ORDER BY station_name ASC";
			    	if($stations = Yii::app()->db3->createCommand($stationquery)->queryAll()){
			    		foreach ($stations as $stationkey) {
							$fstationid = $stationkey['station_id'];
							$fstationname = $stationkey['station_name'];
							
							$html .= "<table class='table table-bordered table-condensed table-striped'> ";
							$html .= "<tr><td colspan='10'><p><strong>$fstationname</strong></p></td></tr>";
							$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstationid AND brand_id=$fbrandid AND `type`='$entry_type_name' ORDER BY date,time ASC";
							if($excel_data = Yii::app()->db3->createCommand($brandsql)->queryAll()){
								$html .= "<tr><td><strong>#</strong></td><td><strong>Date</strong></td><td><strong>Day</strong></td><td><strong>Time</strong></td><td><strong>Ad Name</strong></td><td><strong>Brand Name</strong></td><td><strong>Ad Type</strong></td><td><strong>Duration</strong></td><td><strong>Comment</strong></td><td><strong>Rate</strong></td></tr>";
								$count = 1;
								$stationsum = 0;
								foreach ($excel_data as $elements) {
									$ad_name = $elements['ad_name'];
									$brand = $elements['brand_name'];
									$date = $elements['date'];
									$time = $elements['time'];
									$type = $elements['type'];
									$duration = $elements['duration'];
									$rate = number_format( (int)$elements['rate'] );
									$day = date("D",strtotime($elements['date']));

									$file = $elements['file'];
									$comment = $elements['comment'];
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
									$html .= "<tr><td>$count</td><td>$date</td><td>$day</td><td>$time</td><td><a href='$url'>$ad_name</a></td><td>$brand</td><td>$type</td><td>$duration</td><td>$comment</td><td>$rate</td></tr>";
									$stationsum = $stationsum + (int)$elements['rate'];
									$count++;
								}
								$html .= "</table> ";
								$format_total = number_format($stationsum);
								$adcount = count($excel_data);
								$html .= "<p><strong>Station Totals | Number of Ads - $adcount | Amount -  $format_total</strong></p><br>";
							}
						}
					}
					$html .= "</div>";

				}
			}
			$html .= "<hr>";
	    }
		$anvil_header = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
		$pdf_header = '<img src="'.$anvil_header.'" width="100%" /><br>';
		$pdf_file = $pdf_header.$html;
		$pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());
		$filename =str_replace(" ","_",$clientname);
        $filename = 'QC_POF_'.date("Ymdhis").'_'.$filename.'.pdf';
		$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename;
		file_put_contents($location, $pdf);
	    return $filename;
	}

	public static function AdTypeStationPDF($temp_table,$startdate,$enddate,$clientname){
		$html = '';
		$electronicplayer = Yii::app()->params['eleclink'];
		$country_currency = Yii::app()->params['country_currency'];

		$adtypequery = "SELECT DISTINCT `type` AS entry_type_name,entry_type_id FROM $temp_table,djmentions_entry_types WHERE djmentions_entry_types.entry_type = $temp_table.`type` ORDER BY entry_type_name ASC";
		$stored_adtypes = Yii::app()->db3->createCommand($adtypequery)->queryAll();
	    $html .= '<p>The following is a Proof of Flight Summary Report  the period: between '.$startdate.' and '.$enddate.'</p><br>';
	    $html .= "<h4>Client: $clientname</h4>";
	    foreach ($stored_adtypes as $found_adtypes) {
	    	$entry_type_id = $found_adtypes['entry_type_id'];
			$entry_type_name = $found_adtypes['entry_type_name'];
	    	$html .= "<p><strong>$entry_type_name</strong><br></p>";
	    	$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table WHERE `type`='$entry_type_name' ORDER BY station_name ASC";
	    	if($stations = Yii::app()->db3->createCommand($stationquery)->queryAll()){
	    		foreach ($stations as $stationkey) {
					$fstationid = $stationkey['station_id'];
					$fstationname = $stationkey['station_name'];
					$html .= "<p><strong>$fstationname</strong></p>";
					$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstationid AND `type`='$entry_type_name' ORDER BY date,time ASC";
					if($excel_data = Yii::app()->db3->createCommand($brandsql)->queryAll()){
						$html .= "<table class='table table-bordered table-condensed table-striped'> ";
						$html .= "<tr><td>#</td><td>Date</td><td>Day</td><td>Time</td><td>Ad Name</td><td>Brand Name</td><td>Ad Type</td><td>Duration</td><td>Comment</td><td>Rate</td></tr>";
						$count = 1;
						$stationsum = 0;
						foreach ($excel_data as $elements) {
							$ad_name = $elements['ad_name'];
							$brand = $elements['brand_name'];
							$date = $elements['date'];
							$time = $elements['time'];
							$type = $elements['type'];
							$duration = $elements['duration'];
							$rate = number_format( (int)$elements['rate'] );
							$day = date("D",strtotime($elements['date']));

							$file = $elements['file'];
							$comment = $elements['comment'];
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
							$html .= "<tr><td>$count</td><td>$date</td><td>$day</td><td>$time</td><td><a href='$url'>$ad_name</a></td><td>$brand</td><td>$type</td><td>$duration</td><td>$comment</td><td>$rate</td></tr>";
							$stationsum = $stationsum + (int)$elements['rate'];
							$count++;
						}
						$html .= "</table> ";
						$format_total = number_format($stationsum);
						$adcount = count($excel_data);
						$html .= "<p><strong>Station Totals | Number of Ads - $adcount | Amount -  $format_total</strong></p><br>";
					}
				}
			}
			$html .= "<hr>";
	    }
		$anvil_header = $_SERVER['DOCUMENT_ROOT'].Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
		$pdf_header = '<img src="'.$anvil_header.'" width="100%" /><br>';
		$pdf_file = $pdf_header.$html;
		$pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());
		$filename =str_replace(" ","_",$clientname);
        $filename = 'QC_POF_'.date("Ymdhis").'_'.$filename.'.pdf';
		$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename;
		file_put_contents($location, $pdf);
	    return $filename;
	}
}
<?php

class QCPOF
{
	public function GetLogs($stations,$brands,$adtypes,$startdate,$enddate,$reportformat,$company){
		$adsarray = array();
		$date_qry_auto = " AND (reel_date BETWEEN '$startdate' AND '$enddate')";
		$date_qry_manual = " AND (date BETWEEN '$startdate' AND '$enddate')";
		$clientname = UserTable::model()->findByPk($company)->company_name;
		// $brandquery = " AND brand_"

		$temp_table = Common::MediaHouseTempTable();

		/* Date Formating Starts Here */

		$year_start     = date('Y',strtotime($startdate));  
		$month_start    = date('m',strtotime($startdate));  
		$day_start      = date('d',strtotime($startdate));
		$year_end       = date('Y',strtotime($enddate)); 
		$month_end      = date('m',strtotime($enddate)); 
		$day_end        = date('d',strtotime($enddate));

		$array_counter = 0;
		for ($x=$year_start;$x<=$year_end;$x++){
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }

		        $temp_table_month="djmentions_"  .$x."_".$my_month;
		        $sample_table_month="reelforge_sample_"  .$x."_".$my_month;

		        $manualqry = "INSERT INTO $temp_table (adid,station,station_id,brand_id,ad_name,brand_name,date,time,type,duration,rate,date_time,active, adtype,comment,score,file,tabletype,videofile)
		        SELECT $temp_table_month.auto_id AS adid, station_name AS station,$temp_table_month.station_id AS station_id,
		        $temp_table_month.brand_id AS brand_id,brand_name ad_name,brand_name,date,time,
		        djmentions_entry_types.entry_type AS type,SEC_TO_TIME(duration) AS duration,rate,
		        concat(date,' ', time) AS date_time, $temp_table_month.active AS active,'m' as adtype,comment,'' as score,
		        concat(file_path,'', filename) AS file,'manual' AS tabletype,'' AS videofile
		        
				FROM $temp_table_month,station,brand_table,djmentions_entry_types
				WHERE $temp_table_month.brand_id=brand_table.brand_id 
				AND $temp_table_month.station_id=station.station_id 
				AND $temp_table_month.entry_type_id=djmentions_entry_types.entry_type_id
				AND $temp_table_month.station_id IN ($stations)
				AND $temp_table_month.brand_id IN ($brands)
				AND $temp_table_month.entry_type_id IN ($adtypes)
				AND $temp_table_month.active = 1
				$date_qry_manual";
				$insertsql = Yii::app()->db3->createCommand($manualqry)->execute();

				$sampleqry = "INSERT INTO $temp_table (adid,station,station_id,brand_id,ad_name,brand_name,date,time,type,duration,rate,date_time,active, adtype,comment,score,file,tabletype,videofile)
				SELECT $sample_table_month.reel_auto_id AS adid,station_name AS station,$sample_table_month.station_id AS station_id,
				$sample_table_month.brand_id AS brand_id,incantation_name ad_name,brand_name,reel_date date,reel_time time,
				djmentions_entry_types.entry_type type,SEC_TO_TIME(incantation_length) duration,rate,
				concat(reel_date,' ', reel_time) date_time, $sample_table_month.active active, 
				adtype,comment,score,concat(incantation.file_path,'', incantation.incantation_file) AS file,'spot'AS tabletype,
				concat(incantation.file_path,'', incantation.incantation_file) AS videofile

				FROM $sample_table_month,incantation,station,brand_table,djmentions_entry_types 
				WHERE $sample_table_month.brand_id=brand_table.brand_id 
				AND $sample_table_month.incantation_id=incantation.incantation_id 
				AND $sample_table_month.station_id=station.station_id 
				AND $sample_table_month.entry_type_id=djmentions_entry_types.entry_type_id
				AND $sample_table_month.station_id IN ($stations)
				AND $sample_table_month.brand_id IN ($brands)
				AND $sample_table_month.entry_type_id IN ($adtypes)
				AND $sample_table_month.active = 1
				$date_qry_auto";
				$insertsql = Yii::app()->db3->createCommand($sampleqry)->execute();
		    }
		}
		if($reportformat==0){
			// Select General Grouped By Station ONLY
			$excelfile = QCExcel::StandardExcel($temp_table,$startdate,$enddate,$clientname);
			$pdffile = QCPDF::StandardPDF($temp_table,$startdate,$enddate,$clientname);

			echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
			<header role="heading clearfix">
			<h2><span class="pull-left">The following is a Proof of Flight Report the period: '.$startdate.' and '.$enddate.'</span>
			<span class="pull-right">&nbsp;
			<a href="'.Yii::app()->request->baseUrl.'/docs/misc/pdf/'.$pdffile.'" class="btn btn-danger btn-xs pdf-excel" target="_blank">
			<i class="fa fa-file-pdf-o"></i> PDF</a>
			<a href="'.Yii::app()->request->baseUrl.'/docs/misc/excel/'.$excelfile.'" class="btn btn-success btn-xs pdf-excel" target="_blank">
			<i class="fa fa-file-excel-o"></i> EXCEL</a>
			</span></h2></header></div>';
			$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table ORDER BY station ASC";
			if($stored_stations = Yii::app()->db3->createCommand($stationquery)->queryAll()){
				foreach ($stored_stations as $found_stations) {
					$fstation_id = $found_stations['station_id'];
					$fstation_name = $found_stations['station_name'];
					echo '<div id="station_breakdown" class="row-fluid active nav nav-tabs bordered clearfix">';
					echo '<p class="station_header clearfix"><strong><div class="col-md-6">'.$fstation_name.'</strong> </div><div class="col-md-6"><a class="blinky" onClick="opendiv('.$fstation_id.');">Show/Hide Results</a></div></p>';
					echo '<div id="'.$fstation_id.'" class="col-md-12" style="display: none;">';

					$stationsql = "SELECT * FROM $temp_table WHERE station_id=$fstation_id ORDER BY date, time ASC";
					if($stationlog = Yii::app()->db3->createCommand($stationsql)->queryAll()){
						$logs = new QCPOF;
						echo $htmltable = $logs->HtmlLogs($stationlog);
					}else{
						echo "No Records Found";
					}

					echo '</div>';
					echo '</div>';
				}
			}
		}
		if($reportformat==1){
			// Select BY BRAND Grouped By Station FIRST
			$excelfile = QCExcel::StationBrandExcel($temp_table,$startdate,$enddate,$clientname);
			$pdffile = QCPDF::StationBrandPDF($temp_table,$startdate,$enddate,$clientname);

			echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
			<header role="heading clearfix">
			<h2><span class="pull-left">The following is a Proof of Flight Report the period: '.$startdate.' and '.$enddate.'</span>
			<span class="pull-right">&nbsp;
			<a href="'.Yii::app()->request->baseUrl.'/docs/misc/pdf/'.$pdffile.'" class="btn btn-danger btn-xs pdf-excel" target="_blank">
			<i class="fa fa-file-pdf-o"></i> PDF</a>
			<a href="'.Yii::app()->request->baseUrl.'/docs/misc/excel/'.$excelfile.'" class="btn btn-success btn-xs pdf-excel" target="_blank">
			<i class="fa fa-file-excel-o"></i> EXCEL</a>
			</span></h2></header></div>';
			$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table ORDER BY station ASC";
			if($stored_stations = Yii::app()->db3->createCommand($stationquery)->queryAll()){
				foreach ($stored_stations as $found_stations) {
					$fstation_id = $found_stations['station_id'];
					$fstation_name = $found_stations['station_name'];

					echo '<div id="station_breakdown" class="row-fluid active nav nav-tabs bordered clearfix">';
					echo '<p class="station_header clearfix"><strong><div class="col-md-6">'.$fstation_name.'</strong> </div><div class="col-md-6"><a class="blinky" onClick="opendiv('.$fstation_id.');">Show/Hide Results</a></div></p>';
					echo '<div id="'.$fstation_id.'" class="col-md-12" style="display: none;">';
					
					$brandquery = "SELECT DISTINCT brand_id, brand_name FROM $temp_table WHERE station_id=$fstation_id ORDER BY brand_name ASC";
					if($brands = Yii::app()->db3->createCommand($brandquery)->queryAll()){
						foreach ($brands as $brandkey) {
							$fbrandid = $brandkey['brand_id'];
							$fbrandname = $brandkey['brand_name'];
							echo "<br>";
							echo "<p><strong>$fbrandname</strong></p>";
							$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND brand_id=$fbrandid ORDER BY date,time ASC";
							if($brandlog = Yii::app()->db3->createCommand($brandsql)->queryAll()){
								$logs = new QCPOF;
								echo $htmltable = $logs->HtmlLogs($brandlog);
							}else{
								echo "No Records Found";
							}
						}
					}else{
						echo "No Brands Found";
					}

					echo '</div>';
					echo '</div>';
				}
			}
		}
		if($reportformat==2){
			// Select BY Station Grouped By Brand FIRST
			$excelfile = QCExcel::BrandStationExcel($temp_table,$startdate,$enddate,$clientname);
			$pdffile = QCPDF::BrandStationPDF($temp_table,$startdate,$enddate,$clientname);

			echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
			<header role="heading clearfix">
			<h2><span class="pull-left">The following is a Proof of Flight Report the period: '.$startdate.' and '.$enddate.'</span>
			<span class="pull-right">&nbsp;
			<a href="'.Yii::app()->request->baseUrl.'/docs/misc/pdf/'.$pdffile.'" class="btn btn-danger btn-xs pdf-excel" target="_blank">
			<i class="fa fa-file-pdf-o"></i> PDF</a>
			<a href="'.Yii::app()->request->baseUrl.'/docs/misc/excel/'.$excelfile.'" class="btn btn-success btn-xs pdf-excel" target="_blank">
			<i class="fa fa-file-excel-o"></i> EXCEL</a>
			</span></h2></header></div>';
			$brandquery = "SELECT DISTINCT brand_id, brand_name FROM $temp_table ORDER BY brand_name ASC";
			if($stored_brands = Yii::app()->db3->createCommand($brandquery)->queryAll()){
				foreach ($stored_brands as $found_brands) {
					$fbrandid = $found_brands['brand_id'];
					$fbrandname = $found_brands['brand_name'];

					echo '<div id="station_breakdown" class="row-fluid active nav nav-tabs bordered clearfix">';
					echo '<p class="station_header clearfix"><strong><div class="col-md-6">'.$fbrandname.'</strong> </div><div class="col-md-6"><a class="blinky" onClick="opendiv('.$fbrandid.');">Show/Hide Results</a></div></p>';
					echo '<div id="'.$fbrandid.'" class="col-md-12" style="display: none;">';
					
					$adtypequery = "SELECT DISTINCT `type` AS entry_type_name,entry_type_id FROM $temp_table,djmentions_entry_types WHERE djmentions_entry_types.entry_type = $temp_table.`type` AND brand_id=$fbrandid ORDER BY entry_type_name ASC";
					if($stored_adtypes = Yii::app()->db3->createCommand($adtypequery)->queryAll()){
						foreach ($stored_adtypes as $found_adtypes) {
							$entry_type_id = $found_adtypes['entry_type_id'];
							$entry_type_name = $found_adtypes['entry_type_name'];
							echo "<br>";
							echo "<p><strong>$entry_type_name</strong></p>";

							$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table WHERE brand_id=$fbrandid AND `type`='$entry_type_name' ORDER BY station_name ASC";
							if($stations = Yii::app()->db3->createCommand($stationquery)->queryAll()){
								foreach ($stations as $stationkey) {
									$fstationid = $stationkey['station_id'];
									$fstationname = $stationkey['station_name'];
									echo "<br>";
									echo "<p><strong>$fstationname</strong></p>";

									$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstationid AND brand_id=$fbrandid AND `type`='$entry_type_name' ORDER BY date,time ASC";
									if($brandlog = Yii::app()->db3->createCommand($brandsql)->queryAll()){
										$logs = new QCPOF;
										echo $htmltable = $logs->HtmlLogs($brandlog);
									}else{
										echo "No Records Found";
									}
								}
							}else{
								echo "No Stations Found";
							}

						}
					} else{
						echo "No Ad Types found";
					}

					echo '</div>';
					echo '</div>';
				}
			}

		}
		if($reportformat==3){
			// Select BY AdType Grouped By Brand FIRST
			$excelfile = QCExcel::AdTypeStationExcel($temp_table,$startdate,$enddate,$clientname);
			$pdffile = QCPDF::AdTypeStationPDF($temp_table,$startdate,$enddate,$clientname);

			echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
			<header role="heading clearfix">
			<h2><span class="pull-left">The following is a Proof of Flight Report the period: '.$startdate.' and '.$enddate.'</span>
			<span class="pull-right">&nbsp;
			<a href="'.Yii::app()->request->baseUrl.'/docs/misc/pdf/'.$pdffile.'" class="btn btn-danger btn-xs pdf-excel" target="_blank">
			<i class="fa fa-file-pdf-o"></i> PDF</a>
			<a href="'.Yii::app()->request->baseUrl.'/docs/misc/excel/'.$excelfile.'" class="btn btn-success btn-xs pdf-excel" target="_blank">
			<i class="fa fa-file-excel-o"></i> EXCEL</a>
			</span></h2></header></div>';
			$adtypequery = "SELECT DISTINCT `type` AS entry_type_name,entry_type_id FROM $temp_table,djmentions_entry_types WHERE djmentions_entry_types.entry_type = $temp_table.`type` ORDER BY entry_type_name ASC";
			if($stored_adtypes = Yii::app()->db3->createCommand($adtypequery)->queryAll()){
				foreach ($stored_adtypes as $found_adtypes) {
					$entry_type_id = $found_adtypes['entry_type_id'];
					$entry_type_name = $found_adtypes['entry_type_name'];

					echo '<div id="station_breakdown" class="row-fluid active nav nav-tabs bordered clearfix">';
					echo '<p class="station_header clearfix"><strong><div class="col-md-6">'.$entry_type_name.'</strong> </div><div class="col-md-6"><a class="blinky" onClick="opendiv('.$entry_type_id.');">Show/Hide Results</a></div></p>';
					echo '<div id="'.$entry_type_id.'" class="col-md-12" style="display: none;">';
					
					$stationquery = "SELECT DISTINCT station_id, station AS station_name FROM $temp_table WHERE `type`='$entry_type_name' ORDER BY station_name ASC";
					if($stations = Yii::app()->db3->createCommand($stationquery)->queryAll()){
						foreach ($stations as $stationkey) {
							$fstationid = $stationkey['station_id'];
							$fstationname = $stationkey['station_name'];
							echo "<br>";
							echo "<p><strong>$fstationname</strong></p>";

							$brandsql = "SELECT * FROM $temp_table WHERE station_id=$fstationid AND `type`='$entry_type_name' ORDER BY date,time ASC";
							if($brandlog = Yii::app()->db3->createCommand($brandsql)->queryAll()){
								$logs = new QCPOF;
								echo $htmltable = $logs->HtmlLogs($brandlog);
							}else{
								echo "No Records Found";
							}
						}
					}else{
						echo "No Stations Found";
					}


					echo '</div>';
					echo '</div>';
				}
			}
		}
		
		return $adsarray;
	}

	public function HtmlLogs($array){
		$electronicplayer = Yii::app()->params['eleclink'];
		$data = '';
		$data .= $this->TableHead();
		$adcount = 1;
		foreach ($array as $logkey) {
			$ad_id = $logkey['adid'];
			$tabletype = $logkey['tabletype'];
			$time = $logkey['time'];
			$date = $logkey['date'];
			$day = date("D",strtotime($logkey['date']));
			$brand = $logkey['brand_name'];
			$type = $logkey['type'];
			$endtime = $logkey['time'];
			$score = $logkey['score'];
			$adtype = $logkey['adtype'];
			$rate = number_format( (int)$logkey['rate'] );

			$file = $logkey['file'];
			if($logkey['videofile']!=''){
				$file = $logkey['videofile'];
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

			$str_time = $logkey['duration'];
			$duration = $logkey['duration'];
			sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
			$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
			$length = $time_seconds." secs";

			$ad_name = $logkey['ad_name'];
			$comment = $logkey['comment'];
			if($logkey['active']==1){
				$status = '<font style="color:green;">Active</font>';
			}elseif($logkey['active']==0){
				$status = '<font style="color:red;">Inactive</font>';
			}else{
				$status = '<font style="color:blue;">Confirm?</font>';
			}

			$data .= "<tr>
			<td>$adcount</td>
			<td>$date</td>
			<td>$day</td>
			<td>$time</td>
			<td><a href='$url' $target>$ad_name</a></td>
			<td>$brand</td>
			<td>$type</td>
			<td>$duration</td>
			<td>$comment</td>
			<td>$rate</td>
			</tr>";

			$adcount++;
		}
		$data .= $this->TableEnd();
		return $data;
	}

	public function TableHead(){
		$data = "<table class='table table-bordered table-condensed table-striped'> ";
		$data .= "<thead>
		<th>#</th>
		<th>Date</th>
		<th>Day</th>
		<th>Time</th>
		<th>Ad Name</th>
		<th>Brand Name</th>
		<th>Ad Type</th>
		<th>Duration</th>
		<th>Comment</th>
		<th>Rate</th>
		</thead>";
		return $data;
	}

	public function TableEnd(){
		$data = "</table> ";
		return $data;
	}
}




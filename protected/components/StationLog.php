<?php

class StationLog{
	public function GetLogs($station,$adtype,$addate){
		$adsarray = array();
		$date_qry_auto = " AND reel_date = '$addate'";
		$date_qry_manual = " AND date = '$addate'";
		// Manuals Query - Djmentions
		$manualqry = "SELECT auto_id id,station_name station,brand_name ad_name,brand_name,date,time,entry_type type,
		SEC_TO_TIME(duration) duration,rate,concat(date,' ', time) date_time, djmentions.active active, 
		'm' as adtype,comment,'' as score,concat(file_path,' ', filename) AS file,'manual' AS tabletype
		FROM djmentions,station,brand_table 
		WHERE djmentions.brand_id=brand_table.brand_id AND djmentions.station_id=station.station_id 
		AND  djmentions.station_id=$station $date_qry_manual";
		// Spots Query - Reelforge Sample
		$sampleqry = "SELECT reel_auto_id id,station_name station,incantation_name ad_name,brand_name,reel_date date,reel_time time,
		entry_type type,SEC_TO_TIME(incantation_length) duration,rate,
		concat(reel_date,' ', reel_time) date_time, reelforge_sample.active active, 
		adtype,comment,score,concat(incantation.file_path,' ', incantation.incantation_file) AS file,'spot'AS tabletype
		FROM reelforge_sample,incantation,station,brand_table,djmentions_entry_types 
		WHERE reelforge_sample.brand_id=brand_table.brand_id AND reelforge_sample.incantation_id=incantation.incantation_id 
		AND reelforge_sample.station_id=station.station_id 
		AND reelforge_sample.entry_type_id=djmentions_entry_types.entry_type_id
		AND  reelforge_sample.station_id=$station $date_qry_auto";
		/* Combine the Queries */
		$allsql = $manualqry." UNION ".$sampleqry." ORDER BY station ASC, date ASC, time ASC";
		if($adtype==2){
			$sql = $manualqry;
		}elseif ($adtype==3) {
			$sql = $sampleqry;
		}else{
			$sql = $allsql;
		}
		$array_counter = 0;
		if($logs = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($logs as $key) {
				$adsarray[$array_counter]['id'] = $key['id'];
				$adsarray[$array_counter]['station'] = $key['station'];
				$adsarray[$array_counter]['ad_name'] = $key['ad_name'];
				$adsarray[$array_counter]['brand_name'] = $key['brand_name'];
				$adsarray[$array_counter]['date'] = $key['date'];
				$adsarray[$array_counter]['time'] = $key['time'];
				$adsarray[$array_counter]['type'] = $key['type'];
				$adsarray[$array_counter]['duration'] = $key['duration'];
				$adsarray[$array_counter]['rate'] = $key['rate'];
				$adsarray[$array_counter]['date_time'] = $key['date_time'];
				$adsarray[$array_counter]['active'] = $key['active'];
				$adsarray[$array_counter]['tabletype'] = $key['tabletype'];
				
				$adsarray[$array_counter]['adtype'] = $key['adtype'];
				$adsarray[$array_counter]['comment'] = $key['comment'];
				$adsarray[$array_counter]['score'] = $key['score'];
				$adsarray[$array_counter]['file'] = $key['file'];
				$array_counter++;
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
			$ad_id = $logkey['id'];
			$tabletype = $logkey['tabletype'];
			$time = $logkey['time'];
			$brand = $logkey['brand_name'];
			$type = $logkey['type'];
			$endtime = $logkey['time'];
			$score = $logkey['score'];
			$adtype = $logkey['adtype'];

			$file = $logkey['file'];
			if($type=='Caption' || $type=='Program'){
				$url = "#";
			}else{
				$url = $electronicplayer.str_replace('/home/srv/www/htdocs/','',$file) ;
			}

			$str_time = $logkey['duration'];
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

			$data .= "<tr><td>$adcount</td><td>$time</td><td>$brand</td><td>$type</td><td>$endtime</td><td>$score</td><td>$adtype</td><td>$length</td><td><a href='$url'>$ad_name</a></td><td>$comment</td><td>$status</td></tr>";
			$adcount++;
		}
		$data .= $this->TableEnd();
		return $data;
	}

	public function TableHead(){
		$data = "<table class='table table-bordered table-condensed table-striped'> ";
		$data .= "<thead><th>#</th><th>Time</th><th>Brand Name</th><th>Ad Type</th><th>End Time</th><th>Score</th><th>Type</th><th>Length</th><th>Ad Name</th><th>Comment</th><th>Status</th></thead>";
		return $data;
	}

	public function TableEnd(){
		$data = "</table> ";
		return $data;
	}
}




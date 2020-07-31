<?php

class AgencySpends{
	/* Get All The Agencies */
	public static function GetAllAgencies()
	{
		if($agencies = AgencyCompany::model()->findAll()){
			return $agencies;
		}else{
			return false;
		}
	}
	/* Get the Agencies Brands */

	public static function GetAgencyBrands($agency_id)
	{
		$agency_brands = "SELECT  distinct(brand_agency.brand_id) FROM brand_agency, brand_table WHERE brand_agency.brand_id=brand_table.brand_id AND brand_agency.agency_id=$agency_id";
		if($brands = Yii::app()->db3->createCommand($agency_brands)->queryAll()){
			return $brands;
		}else{
			return false;
		}
	}

	/* Clean Up The Brands Accordingly */

	public static function BrandSplit($array)
	{
		$prefix = '';
		foreach ($array as $key) {
			$brands[] = $key['brand_id'];
		}
		$set_brands = implode(', ', $brands);
		return $set_brands;
	}

	/* Get The Agencies Spends */

	public static function GetData($enddate,$startdate,$sqlstartdate,$sqlenddate)
	{
		if($agencies = AgencySpends::GetAllAgencies()){
			$temp_table = AgencySpends::CreateTempTable();
			$agency_temp_table = AgencySpends::CreateAgencyTempTable();

			foreach ($agencies as $key) {
				$agency_id = $key->agency_id;
				if($agency_brands = AgencySpends::GetAgencyBrands($agency_id)){
					$set_brands = AgencySpends::BrandSplit($agency_brands);
					$spends = AgencySpends::AggregateTotalBrandSpends($agency_id,$enddate,$startdate,$sqlstartdate,$sqlenddate,$set_brands,$temp_table,$agency_temp_table);
				}
			}

			$agency_array = array();
			$count = 0;

			foreach ($agencies as $key) {
				$agency_id = $key->agency_id;
				$agency_array[$count]['id']=$agency_id;
				$agency_array[$count]['agency']=$key->agency_name;
				$spends_sql = "SELECT * FROM $agency_temp_table WHERE agency_id=$agency_id";
				if($spends_select = Yii::app()->db3->createCommand($spends_sql)->queryAll()){
					foreach ($spends_select as $array_value) {
						if($array_value['media_type']=='print' || $array_value['media_type']=='PRINT'){
							$agency_array[$count]['array_print']=$array_value['rate'];
						}

						if($array_value['media_type']=='tv' || $array_value['media_type']=='TV'){
							$agency_array[$count]['array_tv']=$array_value['rate'];
						}

						if($array_value['media_type']=='radio' || $array_value['media_type']=='RADIO'){
							$agency_array[$count]['array_radio']=$array_value['rate'];
						}
					}
				}
				$count++;
			}

			return $agency_array;

			
		}else{
			throw new CHttpException(404,'We currently dont have any Agencies to Display');
		}
	}

	

	public static function AggregateTotalBrandSpends($agency_id,$enddate,$startdate,$sqlstartdate,$sqlenddate,$set_brands,$temp_table,$agency_temp_table)
	{
		/* Date Formating Starts Here */

		$year_start     = date('Y',strtotime($startdate));  
		$month_start    = date('m',strtotime($startdate));  
		$day_start      = date('d',strtotime($startdate));
		$year_end       = date('Y',strtotime($enddate)); 
		$month_end      = date('m',strtotime($enddate)); 
		$day_end        = date('d',strtotime($enddate));

		/* Titles */

		$title_start_date=$day_start . " " . date("M",mktime(0,0,0,$month_start,1,2009)) . " " . $year_start;
		$title_end_date=$day_end . " " . date("M",mktime(0,0,0,$month_end,1,2009)) . " " . $year_end;

		/* Get Agency Name */

		if($agency_name = AgencyCompany::model()->find('agency_id=:a', array(':a'=>$agency_id))){
			$agency_name = $agency_name->agency_name;
		}else{
			$agency_name = 'Unknown Agency';
		}

		/* Loop through Months to Go through Sub Tables */

		$sql1='';
		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="djmentions_"  .$x."_".$my_month;
		        $sql1 .= "select 
		        $agency_id,
		        station.station_name,
		        $temp_table_month.station_id,
		        station.station_code,
		        $temp_table_month.rate,
		        station.station_type,
		        brand_table.brand_name as brand_name,
		        $temp_table_month.brand_id as brand_id
		        FROM $temp_table_month, brand_table, station
		        WHERE $temp_table_month.brand_id IN ($set_brands)
		        AND $temp_table_month.brand_id=brand_table.brand_id 
		        AND brand_table.country_id = 1
		        AND station.country_id = 1
		        AND $temp_table_month.station_id=station.station_id 
		        AND date between '$sqlstartdate' and '$sqlenddate' union ";
		    }
		}
		$sql1 = substr($sql1, 0, -6);

		$sql2= '';
		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="reelforge_sample_"  .$x."_".$my_month;
		        $sql2 .= "select 
		        $agency_id,
		        station.station_name,
		        $temp_table_month.station_id as station_id,
		        station.station_code,
		        $temp_table_month.rate,
		        station.station_type,
		        brand_table.brand_name as brand_name,
		        $temp_table_month.brand_id as brand_id
		        FROM $temp_table_month,brand_table,station
		        WHERE $temp_table_month.brand_id IN ($set_brands)
		        AND $temp_table_month.brand_id=brand_table.brand_id 
		        AND brand_table.country_id = 1
		        AND station.country_id = 1
		        AND $temp_table_month.station_id=station.station_id 
		        AND $temp_table_month.reel_date between '$sqlstartdate' and '$sqlenddate' union ";
		    }
		}
		$sql2 = substr($sql2, 0, -6);

		$union = "insert into $temp_table(agency_id,station_name,station_id,station_code,rate,station_type,brand_name,brand_id) ".$sql1.' union '.$sql2;
		$insertsql = Yii::app()->db3->createCommand($union)->execute();

		/* Print Query */

		$pintsql="insert into $temp_table(agency_id,station_name,station_id,station_code,rate,station_type,brand_name,brand_id)
		select
		$agency_id,
        mediahouse.Media_House_List, 
        print_table.media_house_id,
        mediahouse.media_code,
        print_table.ave, 
        'print' as station_type,
        brand_table.brand_name,
        print_table.brand_id
        from
        mediahouse,
        print_table, brand_table 
        where
        brand_table.brand_id=print_table.brand_id and
        brand_table.country_id = 1 and mediahouse.country_id = 1 and  
        print_table.media_house_id=mediahouse.media_house_id and
        date between '$sqlstartdate' and '$sqlenddate' and print_table.brand_id in(".$set_brands.") order by date asc";

		$insertsql = Yii::app()->db3->createCommand($pintsql)->execute();
		/* Select Results */
		$station_sql="select agency_id,station_type,sum(rate) as sum from $temp_table group by  station_type order by brand_id desc";
		if($station_query = Yii::app()->db3->createCommand($station_sql)->queryAll()){ 
			$agency_inserts= AgencySpends::AgencySpendsInserts($station_query,$agency_id,$agency_name,$agency_temp_table);
		}
	}

	public static function AgencySpendsInserts($resultarray,$agency_id,$agency_name,$agency_temp_table)
	{
		$total = 0;
		foreach ($resultarray as $stationkey) {
			$agency_name = $agency_name;
			$media_type = $stationkey['station_type'];
			$rate = $stationkey['sum'];
			$agency_sql = "insert into $agency_temp_table(agency_id,agency_name,media_type,rate) values($agency_id,'$agency_name','$media_type',$rate)";
			$insertsql = Yii::app()->db3->createCommand($agency_sql)->execute();
		}
		return true;
	}

	public static function CreateTempTable()
	{
		/* Create Temp table */

		$temp_table="agency_spend_temp_"  .date("Ymhmis");
		$temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
		`auto_id` INT  AUTO_INCREMENT PRIMARY KEY ,
		`agency_id` INT  ,
		`station_name` varchar(30)  ,
		`station_id` INT  ,
		`station_code` varchar(30)  ,
		`rate` INT  ,
		`station_type` varchar(30),
		`brand_name` varchar(255),
		`brand_id` int
		) ENGINE = MYISAM ;";
		Yii::app()->db3->createCommand($temp_sql)->execute();

		return $temp_table;
	}

	public static function CreateAgencyTempTable()
	{
		/* Create Temp table */

		$temp_table="agency_spend_summary_temp_"  .date("Ymhmis");
		$temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
		`auto_id` INT  AUTO_INCREMENT PRIMARY KEY ,
		`agency_id` INT  ,
		`agency_name` varchar(30),
		`media_type` varchar(30),
		`rate` INT 
		) ENGINE = MYISAM ;";
		Yii::app()->db3->createCommand($temp_sql)->execute();

		return $temp_table;
	}

}

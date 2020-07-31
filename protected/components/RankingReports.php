<?php

class RankingReports{
	public static function RankingTempTable()
    {
        /**
        * Create Temp table 
        */
        $temp_table="anvil_summary_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
        `id` INT  AUTO_INCREMENT PRIMARY KEY ,
        `auto_id` INT,
        `brand_id` INT  ,
        `entry_type_id` INT  ,
        `incantation_id` INT  ,
        `station_id` INT  ,
        `date` varchar(30)  ,
        `time` varchar(30)  ,
        `comment` varchar(30)  ,
        `rate` INT  ,
        `brand_name` varchar(255),
        `entry_type` varchar(255),
        `incantation_name` varchar(255),
        `duration` varchar(255),
        `file` varchar(255),
        `video_file` varchar(255),
        `station_name` varchar(255),
        `station_type` varchar(255),
        `company_name` varchar(255),
        `industry` varchar(255),
        `sub_industry_id` varchar(255)
        ) ENGINE = MYISAM ;";
        Yii::app()->db3->createCommand($temp_sql)->execute();

        return $temp_table;
    }

    public static function GetRecords($startdate,$enddate,$industry,$subindustries,$brand_query,$country_id){
		if(Yii::app()->user->country_code=='KE'){
		    $linkurl = 'beta';
		}else{
		    $linkurl = strtolower(Yii::app()->user->country_code);
		}
		if($currency = Country::model()->find('country_code=:a', array(':a'=>Yii::app()->user->country_code))){
	        $currency = $currency->currency;
	    }else{
	        $currency = 'KSH';
	    }

		$sqlstartdate = date('Y-m-d', strtotime($startdate));
		$sqlenddate = date('Y-m-d', strtotime($enddate));

		$setmonth = strtotime($sqlstartdate);
		$setmonth = date('M',$setmonth);
		$year = date('Y',strtotime($sqlenddate));
		$period = $setmonth.' '.$year;

		$year_start     = date('Y',strtotime($startdate));  
		$month_start    = date('m',strtotime($startdate));  
		$day_start      = date('d',strtotime($startdate));
		$year_end       = date('Y',strtotime($enddate)); 
		$month_end      = date('m',strtotime($enddate)); 
		$day_end        = date('d',strtotime($enddate));

		$temp_table = IndustrySummaries::IndustryPOFTempTable();
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
		        (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,station_name,station_type,sub_industry_id,company_name) 
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
		        brand_table.sub_industry_id AS sub_industry_id,
		        company_name  
		        FROM $temp_table_month, brand_table, djmentions_entry_types, station, user_table
		        WHERE $temp_table_month.$brand_query 
		        AND $temp_table_month.brand_id=brand_table.brand_id 
		        AND $temp_table_month.entry_type_id=djmentions_entry_types.entry_type_id
		        AND $temp_table_month.station_id=station.station_id
		        AND $temp_table_month.rate>0 
		        AND $temp_table_month.active=1  
		        and brand_table.country_id=$country_id
		        AND station.country_id = $country_id
		        AND brand_table.company_id = user_table.company_id 
		        AND date between '$sqlstartdate' and '$sqlenddate' ";
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
		        (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,station_name,station_type,sub_industry_id,company_name) 
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
		        brand_table.sub_industry_id AS sub_industry_id,
		        company_name
		        FROM $temp_table_month, incantation,brand_table,djmentions_entry_types, station, user_table
		        WHERE $temp_table_month.$brand_query 
		        AND $temp_table_month.brand_id=brand_table.brand_id 
		        AND $temp_table_month.incantation_id=incantation.incantation_id
		        AND $temp_table_month.entry_type_id=djmentions_entry_types.entry_type_id
		        AND $temp_table_month.station_id=station.station_id 
		        AND $temp_table_month.rate>0 
		        AND $temp_table_month.reel_date between '$sqlstartdate' and '$sqlenddate' 
		        AND $temp_table_month.active=1 
		        and brand_table.country_id=$country_id
		        AND station.country_id = $country_id
		        AND brand_table.company_id = user_table.company_id " ;
		        // echo $sample_sql;
		        $insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
		    }
		}

		$print_sql = "INSERT into $temp_table 
		(auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,station_name,station_type,sub_industry_id,company_name) 
		SELECT
		DISTINCT(print_table.auto_id) AS print_id,  
		print_table.brand_id, 
		print_table.entry_type, 
		'incantation_id',
		print_table.media_house_id AS station_id,
		date as this_date,
		'-',
		'comment',
		print_table.ave AS rate,
		brand_table.brand_name as brand_name, 
		'-', 
		'incantation_name',
		'00:00:00',
		print_table.file, 
		'video', 
		mediahouse.Media_House_List AS station_name, 
		'Print', 
		brand_table.sub_industry_id AS sub_industry_id,
		company_name
		FROM mediahouse,print_table, brand_table, user_table 
		WHERE print_table.$brand_query 
		AND brand_table.company_id = user_table.company_id 
		AND brand_table.brand_id=print_table.brand_id 
		and brand_table.country_id=$country_id
		AND print_table.media_house_id=mediahouse.media_house_id 
		AND print_table.ave>0 
		AND date between '$sqlstartdate' AND '$sqlenddate'  order by date asc";
        $insertsql = Yii::app()->db3->createCommand($print_sql)->execute();

        $file = IndustrySummaries::HtmlRecords($temp_table,$currency,$linkurl,$period);
		return $file;
	}
}
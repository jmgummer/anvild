<?php

class CompanySpends{
	public static function TotalCompanySpends($agency_id,$enddate,$startdate,$sqlstartdate,$sqlenddate,$set_brands)
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

		$temp_table = BrandSpends::SpendsTempTable();

		/* Get Agency Name */

		if($agency_name = AgencyCompany::model()->find('agency_id=:a', array(':a'=>$agency_id))){
			$agency_name = $agency_name->agency_name;
		}else{
			$agency_name = 'Unknown';
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
		        $sql1 .= "SELECT 
		        $agency_id,
		        $temp_table_month.company_id,
		        station.station_name,
		        $temp_table_month.station_id,
		        station.station_code,
		        $temp_table_month.rate,
		        station.station_type,
		        brand_table.brand_name as brand_name,
		        $temp_table_month.brand_id as brand_id
		        FROM $temp_table_month, brand_table, station
		        WHERE $temp_table_month.brand_id IN ($set_brands)
		        AND $temp_table_month.brand_id=brand_table.brand_id AND $temp_table_month.active=1 
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
		        $sql2 .= "SELECT 
		        $agency_id,
		        $temp_table_month.company_id,
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
		        AND $temp_table_month.station_id=station.station_id AND $temp_table_month.active=1 
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
		print_table.company_id,
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
        print_table.media_house_id=mediahouse.media_house_id and
        date between '$sqlstartdate' and '$sqlenddate' and print_table.brand_id in(".$set_brands.") order by date asc";

		$insertsql = Yii::app()->db3->createCommand($pintsql)->execute();
		/* Select Results */
		$station_sql="select agency_id,brand_id,brand_name,station_name,station_id,station_type,sum(rate) as sum from $temp_table group by brand_id,  station_type order by brand_id desc";
		if($station_query = Yii::app()->db3->createCommand($station_sql)->queryAll()){ 
			echo '<h4><strong>'.$agency_name.' Spends Report By Brand</strong></h4>';
			$period = $title_start_date.' - '.$title_end_date;
			$excel = BrandsSpendsExcel::ExcelData($station_query,$period,$agency_name);
			echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/stationspends/excel/'.$excel.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p><br>';
			echo $spendstable = BrandSpends::SpendsTableHead();
			echo $spendstable = BrandSpends::SpendsTableBody($station_query);
			echo '</table>';
		}else{
			echo '<h4><strong>Spends Report By Brand</strong></h4>';
			echo '<h4>No Results Found</h4>';
		}

	}

	public static function SpendsTableHead()
	{
		$data='<table id="dt_basic" class="table table-condensed table-bordered table-hover">';	
		$data.="<thead>";
		$data.="<th>#</th>";
		$data.="<th><strong>Brand</strong></th>"; 
		// $data.="<th><strong>Station</strong></th>"; 
		$data.="<th><strong>Media Type</strong></th>"; 
		$data.="<th><div align='right'><strong>Rate</strong></div></th>"; 
		$data.="</thead>";
		return $data;
	}

	public static function SpendsTableBody($resultarray)
	{
		$data="";
		$count = 1;
		$total = 0;
		foreach ($resultarray as $stationkey) {
			$data.="<tr>";
			$data.="<td>".$count."</td>";
			$data.="<td>".$stationkey['brand_name']."</td>";
			// $data.="<td>".$stationkey['station_name']."</td>";
			$data.="<td>".strtoupper($stationkey['station_type'])."</td>";
			$data.="<td  align='right'>".number_format($stationkey['sum'])."</td>";
			$data.="</tr>";
			$total = $total + $stationkey['sum'];
			$count++;
		}
		$data.="<tr>";
			$data.="<td></td>";
			$data.="<td></td>";
			// $data.="<td></td>";
			$data.="<td></td>";
			$data.="<td  align='right'>".number_format($total)."</td>";
			$data.="</tr>";

		return $data;
	}

	public static function SpendsTableClose()
	{

	}

	public static function SpendsTempTable()
	{
		/* Create Temp table */

		$temp_table="company_spend_temp_"  .date("Ymhmis");
		$temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
		`auto_id` INT  AUTO_INCREMENT PRIMARY KEY ,
		`agency_id` INT  ,
		`company_id` INT  ,
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
}
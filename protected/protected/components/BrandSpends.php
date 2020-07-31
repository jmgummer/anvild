<?php

class BrandSpends{
	public static function TotalBrandSpends($agency_id,$enddate,$startdate,$sqlstartdate,$sqlenddate,$set_brands)
	{
		echo 'Start Time - '.date('h:i:s').'<br>';
		// Get Agency Brands
		$sql_agency="select distinct brand_id, start_date, end_date from brand_agency where agency_id=$agency_id";
		if($agency_brands = Yii::app()->db3->createCommand($sql_agency)->queryAll()){
			$agencybrands = '';
			$real_brand_count = 0;
			foreach ($agency_brands as $brands_key) {
				$this_brand_id=$brands_key["brand_id"];
				$agencybrands .= $this_brand_id.',';
				$real_brand_count++;
			}
			echo 'Total Agency Brands '.$real_brand_count;
			$agencybrands = substr($agencybrands, 0, -1);
			$set_brands = $agencybrands;
		}

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
		$valid_brand_count = 0;
		foreach ($agency_brands as $brands_key) {
			$this_brand_id=$brands_key["brand_id"];
			$this_start_date = $brands_key['start_date'];
			$this_end_date = $brands_key['end_date'];
			// Check if Brand falls within the search dates
			if($sqlstartdate<= $this_end_date && $sqlenddate>=$this_start_date){
				echo 'valid brand id : '.$this_brand_id;

				
				if($sqlstartdate<$this_start_date){
					$sqlstartdate = $this_start_date;
				}

				if($sqlenddate>$this_end_date){
					$sqlenddate = $this_end_date;
				}

				$year_start     = date('Y',strtotime($sqlstartdate));  
				$month_start    = date('m',strtotime($sqlstartdate));  
				$day_start      = date('d',strtotime($sqlstartdate));
				$year_end       = date('Y',strtotime($sqlenddate)); 
				$month_end      = date('m',strtotime($sqlenddate)); 
				$day_end        = date('d',strtotime($sqlenddate));

				echo $sqlstartdate;
				echo $sqlenddate;

				// $temp_table_month="djmentions";
		  //       $mentions_sql = "insert into $temp_table(agency_id,station_name,station_id,station_code,rate,station_type,brand_name,brand_id) 
		  //       select 
		  //       $agency_id,
		  //       station.station_name,
		  //       $temp_table_month.station_id,
		  //       station.station_code,
		  //       $temp_table_month.rate,
		  //       station.station_type,
		  //       brand_table.brand_name as brand_name,
		  //       $temp_table_month.brand_id as brand_id
		  //       FROM  $temp_table_month, user_table, brand_table, station
				// WHERE
				// brand_table.brand_id = $this_brand_id AND 
				// brand_table.brand_id= $temp_table_month.brand_id and 
				// user_table.company_id=brand_table.company_id and 
				// station.station_id=$temp_table_month.station_id and 
				// date between '$sqlstartdate' and '$sqlenddate' ";

		  //       $insertsql = Yii::app()->db3->createCommand($mentions_sql)->execute();

				// for ($x=$year_start;$x<=$year_end;$x++)
				// {
				//     if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
				//     if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

				//     $month_start_count=$month_start_count+0;

				//     for ($y=$month_start_count;$y<=$month_end_count;$y++)
				//     {
				//     	if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
						
				//     }
				// }
				$valid_brand_count++;
				echo '<br><hr>';
			}else{
				echo 'invalid brand_id : '.$this_brand_id;
				echo 'startdate : '.$this_start_date = $brands_key['start_date'];
				echo 'enddate : '.$this_end_date = $brands_key['end_date'];
				echo '<br><hr>';
			}
		}
		echo 'Mentions : '.$valid_brand_count;

		$valid_brand_count = 0;
		foreach ($agency_brands as $brands_key) {
			$this_brand_id=$brands_key["brand_id"];
			$this_start_date = $brands_key['start_date'];
			$this_end_date = $brands_key['end_date'];
			// Check if Brand falls within the search dates
			
			if($sqlstartdate<= $this_end_date && $sqlenddate>=$this_start_date){
				if($sqlstartdate<$this_start_date){
					$sqlstartdate = $this_start_date;
				}

				if($sqlenddate>$this_end_date){
					$sqlenddate = $this_end_date;
				}

				$year_start     = date('Y',strtotime($sqlstartdate));  
				$month_start    = date('m',strtotime($sqlstartdate));  
				$day_start      = date('d',strtotime($sqlstartdate));
				$year_end       = date('Y',strtotime($sqlenddate)); 
				$month_end      = date('m',strtotime($sqlenddate)); 
				$day_end        = date('d',strtotime($sqlenddate));

				// $temp_table_month="reelforge_sample";
				// $sample_sql = "insert into $temp_table(agency_id,station_name,station_id,station_code,rate,station_type,brand_name,brand_id) 
				// select 
				// $agency_id,
				// station.station_name,
				// $temp_table_month.station_id as station_id,
				// station.station_code,
				// $temp_table_month.rate,
				// station.station_type,
				// brand_table.brand_name as brand_name,
				// $temp_table_month.brand_id as brand_id
				// FROM  $temp_table_month, user_table, brand_table, station, incantation
				// WHERE
				// incantation.incantation_id=$temp_table_month.incantation_id and 
				// station.station_id =$temp_table_month.station_id and 
				// brand_table.brand_id= $temp_table_month.brand_id and 
				// user_table.company_id=$temp_table_month.company_id and 
				// reel_date between '$sqlstartdate' and '$sqlenddate'  
				// AND brand_table.brand_id =$this_brand_id ";

				// $insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();

				// for ($x=$year_start;$x<=$year_end;$x++)
				// {
				//     if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
				//     if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

				//     $month_start_count=$month_start_count+0;

				//     for ($y=$month_start_count;$y<=$month_end_count;$y++)
				//     {
				//     	if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
						
				// 	}
				// }
				$valid_brand_count++;
			}
		}
		echo 'Samples : '.$valid_brand_count;

		/* 
		** Print Query 
		** Check if the Brand is valid and set strict start and stop dates
		*/

		$valid_brand_count = 0;
		foreach ($agency_brands as $brands_key) {
			$this_brand_id=$brands_key["brand_id"];
			$this_start_date = $brands_key['start_date'];
			$this_end_date = $brands_key['end_date'];
			// Check if Brand falls within the search dates
			if($sqlstartdate<= $this_end_date && $sqlenddate>=$this_start_date){
				if($sqlstartdate<$this_start_date){
					$sqlstartdate = $this_start_date;
				}

				if($sqlenddate>$this_end_date){
					$sqlenddate = $this_end_date;
				}

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
		        brand_table.brand_id  = $this_brand_id  and
		        brand_table.brand_id=print_table.brand_id and
		        brand_table.country_id = 1 and mediahouse.country_id = 1 and  
		        print_table.media_house_id=mediahouse.media_house_id and
		        date between '$sqlstartdate' and '$sqlenddate' order by date asc";

				$insertsql = Yii::app()->db3->createCommand($pintsql)->execute();
				$valid_brand_count++;
			}
		}
		echo 'Print : '.$valid_brand_count;



		// $pintsql="insert into $temp_table(agency_id,station_name,station_id,station_code,rate,station_type,brand_name,brand_id)
		// select
		// $agency_id,
  //       mediahouse.Media_House_List, 
  //       print_table.media_house_id,
  //       mediahouse.media_code,
  //       print_table.ave, 
  //       'print' as station_type,
  //       brand_table.brand_name,
  //       print_table.brand_id
  //       from
  //       mediahouse,
  //       print_table, brand_table 
  //       where
  //       brand_table.brand_id=print_table.brand_id and
  //       brand_table.country_id = 1 and mediahouse.country_id = 1 and  
  //       print_table.media_house_id=mediahouse.media_house_id and
  //       date between '$sqlstartdate' and '$sqlenddate' and print_table.brand_id in(".$set_brands.") order by date asc";

		// $insertsql = Yii::app()->db3->createCommand($pintsql)->execute();
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
		echo '<br>Stop Time - '.date('h:i:s');

	}

	public static function AggregateTotalBrandSpends($agency_id,$enddate,$startdate,$sqlstartdate,$sqlenddate,$set_brands)
	{
		echo 'Start Time - '.date('h:i:s').'<br>';
		// Get Agency Brands
		$sql_agency="select distinct brand_id, start_date, end_date from brand_agency where agency_id=$agency_id";
		if($agency_brands = Yii::app()->db3->createCommand($sql_agency)->queryAll()){
			$agencybrands = '';
			foreach ($agency_brands as $brands_key) {
				$this_brand_id=$brands_key["brand_id"];
				$agencybrands .= $this_brand_id.',';
			}
			$agencybrands = substr($agencybrands, 0, -1);
			$set_brands = $agencybrands;
		}

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

		foreach ($agency_brands as $brands_key) {
			$this_brand_id=$brands_key["brand_id"];
			$this_start_date = $brands_key['start_date'];
			$this_end_date = $brands_key['end_date'];
			// Check if Brand falls within the search dates
			if($sqlstartdate<= $this_end_date && $sqlenddate>=$this_start_date){
				if($sqlstartdate<$this_start_date){
					$sqlstartdate = $this_start_date;
				}

				if($sqlenddate>$this_end_date){
					$sqlenddate = $this_end_date;
				}

				$year_start     = date('Y',strtotime($sqlstartdate));  
				$month_start    = date('m',strtotime($sqlstartdate));  
				$day_start      = date('d',strtotime($sqlstartdate));
				$year_end       = date('Y',strtotime($sqlenddate)); 
				$month_end      = date('m',strtotime($sqlenddate)); 
				$day_end        = date('d',strtotime($sqlenddate));

				for ($x=$year_start;$x<=$year_end;$x++)
				{
				    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
				    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

				    $month_start_count=$month_start_count+0;

				    for ($y=$month_start_count;$y<=$month_end_count;$y++)
				    {
				    	if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
				    	$temp_table_month="djmentions_"  .$x."_".$my_month;
				        $mentions_sql = "INSERT into $temp_table(agency_id,station_name,station_id,station_code,rate,station_type,brand_name,brand_id) 
				        select 
				        $agency_id,
				        station.station_name,
				        $temp_table_month.station_id,
				        station.station_code,
				        $temp_table_month.rate,
				        station.station_type,
				        brand_table.brand_name as brand_name,
				        $temp_table_month.brand_id as brand_id
				        FROM  $temp_table_month, user_table, brand_table, station
						WHERE
						brand_table.brand_id= $temp_table_month.brand_id and 
						user_table.company_id=brand_table.company_id and 
						station.station_id=$temp_table_month.station_id and $temp_table_month.active=1 and 
						date between '$sqlstartdate' and '$sqlenddate' 
						AND brand_table.brand_id = $this_brand_id ";

				        $insertsql = Yii::app()->db3->createCommand($mentions_sql)->execute();
				    }
				}
			}
		}

		foreach ($agency_brands as $brands_key) {
			$this_brand_id=$brands_key["brand_id"];
			$this_start_date = $brands_key['start_date'];
			$this_end_date = $brands_key['end_date'];
			// Check if Brand falls within the search dates
			if($sqlstartdate<= $this_end_date && $sqlenddate>=$this_start_date){
				if($sqlstartdate<$this_start_date){
					$sqlstartdate = $this_start_date;
				}

				if($sqlenddate>$this_end_date){
					$sqlenddate = $this_end_date;
				}

				$year_start     = date('Y',strtotime($sqlstartdate));  
				$month_start    = date('m',strtotime($sqlstartdate));  
				$day_start      = date('d',strtotime($sqlstartdate));
				$year_end       = date('Y',strtotime($sqlenddate)); 
				$month_end      = date('m',strtotime($sqlenddate)); 
				$day_end        = date('d',strtotime($sqlenddate));

				for ($x=$year_start;$x<=$year_end;$x++)
				{
				    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
				    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

				    $month_start_count=$month_start_count+0;

				    for ($y=$month_start_count;$y<=$month_end_count;$y++)
				    {
				    	if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
				        $temp_table_month="reelforge_sample_"  .$x."_".$my_month;
				        $sample_sql = "INSERT into $temp_table(agency_id,station_name,station_id,station_code,rate,station_type,brand_name,brand_id) 
				        select 
				        $agency_id,
				        station.station_name,
				        $temp_table_month.station_id as station_id,
				        station.station_code,
				        $temp_table_month.rate,
				        station.station_type,
				        brand_table.brand_name as brand_name,
				        $temp_table_month.brand_id as brand_id
				        FROM  $temp_table_month, user_table, brand_table, station, incantation
						WHERE
						incantation.incantation_id=$temp_table_month.incantation_id and 
						station.station_id =$temp_table_month.station_id and 
						brand_table.brand_id= $temp_table_month.brand_id and $temp_table_month.active=1 and
						user_table.company_id=$temp_table_month.company_id and 
						reel_date between '$sqlstartdate' and '$sqlenddate'  
						AND brand_table.brand_id = $this_brand_id ";

						$insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
				    }
				}
			}
		}

		/* Print Query */

		foreach ($agency_brands as $brands_key) {
			$this_brand_id=$brands_key["brand_id"];
			$this_start_date = $brands_key['start_date'];
			$this_end_date = $brands_key['end_date'];
			// Check if Brand falls within the search dates
			if($sqlstartdate<= $this_end_date && $sqlenddate>=$this_start_date){
				if($sqlstartdate<$this_start_date){
					$sqlstartdate = $this_start_date;
				}

				if($sqlenddate>$this_end_date){
					$sqlenddate = $this_end_date;
				}

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
		        brand_table.brand_id  = $this_brand_id  and
		        brand_table.brand_id=print_table.brand_id and
		        brand_table.country_id = 1 and mediahouse.country_id = 1 and  
		        print_table.media_house_id=mediahouse.media_house_id and
		        date between '$sqlstartdate' and '$sqlenddate' order by date asc";
				$insertsql = Yii::app()->db3->createCommand($pintsql)->execute();
			}
		}

		
		/* Select Results */
		$station_sql="select agency_id,brand_id,brand_name,station_name,station_id,station_type,sum(rate) as sum from $temp_table group by  station_type order by brand_id desc";
		if($station_query = Yii::app()->db3->createCommand($station_sql)->queryAll()){ 
			echo '<h4><strong>'.$agency_name.' Spends Report By Brand</strong></h4>';
			$period = $title_start_date.' - '.$title_end_date;
			$excel = BrandsSpendsExcel::AggregatedExcelData($station_query,$period,$agency_name);
			echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/stationspends/excel/'.$excel.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p><br>';
			echo $spendstable = BrandSpends::AverageSpendsTableHead();
			echo $spendstable = BrandSpends::AverageSpendsTableBody($station_query);
			echo '</table>';
		}else{
			echo '<h4><strong>Spends Report By Brand</strong></h4>';
			echo '<h4>No Results Found</h4>';
		}
		echo '<br>Stop Time - '.date('h:i:s');
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

	public static function AverageSpendsTableHead()
	{
		$data='<table id="dt_basic" class="table table-condensed table-bordered table-hover">';	
		$data.="<thead>";
		$data.="<th>#</th>";
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

	public static function AverageSpendsTableBody($resultarray)
	{
		$data="";
		$count = 1;
		$total = 0;
		foreach ($resultarray as $stationkey) {
			$data.="<tr>";
			$data.="<td>".$count."</td>";
			$data.="<td>".strtoupper($stationkey['station_type'])."</td>";
			$data.="<td  align='right'>".number_format($stationkey['sum'])."</td>";
			$data.="</tr>";
			$total = $total + $stationkey['sum'];
			$count++;
		}
		$data.="<tr>";
			$data.="<td></td>";
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

		$temp_table="brand_spend_temp_"  .date("Ymhmis");
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
}
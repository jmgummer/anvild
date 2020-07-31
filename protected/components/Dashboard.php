<?php

class Dashboard{

	public static function TrendReport($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$show=0;
		$week1=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*4)),$year_end));
		$week2=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*3)),$year_end));
		$week3=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*2)),$year_end));
		$week4=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*1)),$year_end));
		$week5=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*0)),$year_end));

		$radio='';
		$tv='';
		$print='';
		$total='';
		$body = '';

		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-28),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));

		$data_array = array();
		$weekcount = 0;

		for($week=4;$week>=0;$week--) {
			$week_day_end=$week*7;
			$week_day_start =($week+1)*7;
			$this_total=0;
			$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-$week_day_start),$year_end));
			$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-$week_day_end),$year_end));
		 	$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end' and station_type='radio' and company_id='$this_company_id'";
			if($radio_results = Yii::app()->db3->createCommand($sql)->queryAll()){
				foreach ($radio_results as $key) {
					$this_value=$key["sum"];
					if($this_value==null){
						$this_value = 0;
					}else{
						$show++;
					}
				}
			}else{
				$this_value = "0";
			}
			$data_array['radio'][$weekcount] = intval($this_value);

			$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end'  and station_type='tv' and company_id='$this_company_id'";
			if($tv_results = Yii::app()->db3->createCommand($sql)->queryAll()){
				foreach ($tv_results as $key) {
					$this_value=$key["sum"];
					if($this_value==null){
						$this_value = 0;
					}else{
						$show++;
					}
				}
			}else{
				$this_value = "0";
			}
			$data_array['tv'][$weekcount] = intval($this_value);

			$sql="SELECT sum(ave)  as sum from print_table, brand_table where date between '$week_start' and '$week_end' and brand_table.brand_id=print_table.brand_id and brand_table.company_id='$this_company_id' $sub_query";
			if($print_results = Yii::app()->db3->createCommand($sql)->queryAll()){
				foreach ($print_results as $key) {
					$this_value=$key["sum"];
					if($this_value==null){
						$this_value = 0;
					}else{
						$show++;
					}
				}
			}else{
				$this_value = "0";
			}
			$data_array['print'][$weekcount] = intval($this_value);
			$data_array['total'][$weekcount] = $data_array['print'][$weekcount]+$data_array['tv'][$weekcount]+$data_array['radio'][$weekcount];

			$weekcount++;
		}

		
		return $data_array;
	}

	public static function SOVSummary($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$show=0;
		$total = 0;
		$body = '';

		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));

		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));

		$data_array = array();

		$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end'  and station_type='radio' and company_id='$this_company_id'";
		if($radio_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($radio_results as $key) {
				$this_value=$key["sum"];
				if($this_value==null){
					$this_value = 0;
				}
			}
		}else{
			$this_value = "0";
		}

		$data_array['radio'] = intval($this_value);

		$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end'  and station_type='tv' and company_id='$this_company_id'";
		if($tv_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($tv_results as $key) {
				$this_value=$key["sum"];
				if($this_value==null){
					$this_value = 0;
				}
			}
		}else{
			$this_value = "0";
		}

		$data_array['tv'] = intval($this_value);
				 
		$sql="SELECT sum(ave)  as sum from print_table, brand_table
		where date between '$week_start' and '$week_end' and
		brand_table.brand_id=print_table.brand_id and 
		brand_table.company_id='$this_company_id' $sub_query";

		if($print_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($print_results as $key) {
				$this_value=$key["sum"];
				if($this_value==null){
					$this_value = 0;
				}
			}
		}else{
			$this_value = "0";
		}

		$data_array['print'] = intval($this_value);

		return $data_array;
	}

	public static function SOVRadio($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$show=0;
		$body = '';
		$my_station_id = '';
		$total = 0;
		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));

		$data_array = array();
		$data_count = 0;

		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));

		$this_station_id=0;
		$sql="SELECT sum(rate)  as sum, station_name, station_id from $temp
		where reel_date between '$week_start' and '$week_end' and station_type='radio' and company_id='$this_company_id' group by station_name order by sum desc limit 10";
		if($radio_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($radio_results as $myrow) {
				$this_value=$myrow["sum"]; 
				$this_station=$myrow["station_name"]; 
				$my_station_id.=$myrow["station_id"].",";
				$data_array[$data_count]['station_name'] = $this_station;
				$data_array[$data_count]['station_value'] = intval($this_value);
				$data_count++;
			}
		}

		$my_station_id=substr($my_station_id,0,-1);
		if($my_station_id!=null){
			$sql="SELECT sum(rate)  as sum  from $temp
			where reel_date between '$week_start' and '$week_end' and
			station_type='radio' and station_id NOT IN ($my_station_id) and company_id='$this_company_id'";

			if($radio_results = Yii::app()->db3->createCommand($sql)->queryAll()){
				foreach ($radio_results as $myrow) {
					$this_value=$myrow["sum"]; 
					$this_station='Other'; 
					$data_array[$data_count]['station_name'] = $this_station;
					$data_array[$data_count]['station_value'] = intval($this_value);
					$data_count++;
				}
			}
		}

		return $data_array;

	}

	public static function SOVTV($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$show=0;
		$body = '';
		$my_station_id = '';
		$total = 0;

		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));

		$data_array = array();
		$data_count = 0;

		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));

		$sql="SELECT sum(rate)  as sum, station_name, station_id from $temp 
		where reel_date between '$week_start' and '$week_end' and
		station_type='tv' and company_id='$this_company_id' group by station_name order by sum desc limit 10";

		if($tv_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($tv_results as $myrow) {
				$this_value=$myrow["sum"]; 
				$this_station=$myrow["station_name"]; 
				$my_station_id.=$myrow["station_id"].",";
				$data_array[$data_count]['station_name'] = $this_station;
				$data_array[$data_count]['station_value'] = intval($this_value);
				$data_count++;
			}
		}

		$my_station_id=substr($my_station_id,0,-1);
		if($my_station_id!=null){
			$sql="SELECT sum(rate)  as sum  from $temp
			where reel_date between '$week_start' and '$week_end' and
			station_type='tv' and station_id NOT IN ($my_station_id) and company_id='$this_company_id'";

			if($tv_results = Yii::app()->db3->createCommand($sql)->queryAll()){
				foreach ($tv_results as $myrow) {
					$this_value=$myrow["sum"]; 
					$this_station='Other'; 
					$data_array[$data_count]['station_name'] = $this_station;
					$data_array[$data_count]['station_value'] = intval($this_value);
					$data_count++;
				}
			}
		}

		return $data_array;
		
	}

	public static function SOVPrint($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$show=0;
		$body = '';
		$my_station_id = '';
		$total = 0;

		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));

		$data_array = array();
		$data_count = 0;

		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-21),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));

		$sql="SELECT sum(ave)  as sum, Media_House_List  from print_table, brand_table,mediahouse
		where 
		brand_table.brand_id=print_table.brand_id and 
		date between '$week_start' and '$week_end' and
		brand_table.company_id='$this_company_id' and 
		mediahouse.media_house_id=print_table.media_house_id $sub_query
		 group by Media_House_List order by sum desc";

		 if($print_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($print_results as $myrow) {
				$this_value=$myrow["sum"]; 
				$this_station=$myrow["Media_House_List"]; 
				$data_array[$data_count]['station_name'] = $this_station;
				$data_array[$data_count]['station_value'] = intval($this_value);
				$data_count++;
			}
		}

		return $data_array;
	}

	public static function MediaSpendRadio($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$my_brand_id = '';
		$total = 0;
		$x = 0;
		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));
		$data_array = array();
		$data_count = 0;
		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));
		$sql_brand="SELECT distinct(brand_id), brand_name, sum(rate) as sum  from $temp where reel_date between '$week_start' and '$week_end' and station_type='radio' and company_id='$this_company_id' group by brand_name  order by sum desc limit 10;";
		if($radio_results = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
			foreach ($radio_results as $myrow_brand) {
				$brand[$x]=$myrow_brand["brand_id"]; 
				$my_brand_id.=$brand[$x].",";
				$brand_sum[$x]=$myrow_brand["sum"];
				$brand[$x] ;
				$brand_name[$x]=trim(str_replace("&"," ",$myrow_brand["brand_name"]));
				$brand_name[$x]=trim(str_replace(","," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("'"," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("  "," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("  "," ",$brand_name[$x]));
				$brand_name[$x]=substr($brand_name[$x],0,20);
				$data_array[$data_count]['brand_name'] = $brand_name[$x];
				$data_array[$data_count]['brand_value'] = intval($brand_sum[$x]);
				$data_count++;
				$x++;
			}
		}

		$my_brand_id=substr($my_brand_id,0,-1);
		if($my_brand_id!=null){
			$sql_brand="SELECT  sum(rate) as sum  from $temp where reel_date between '$week_start' and '$week_end' and  station_type='radio' and brand_id not in ($my_brand_id) and company_id='$this_company_id' ";
			if($radio_results = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
				foreach ($radio_results as $myrow_brand) {
					$data_array[$data_count]['brand_name']='Others';
					$data_array[$data_count]['brand_value'] = intval($myrow_brand["sum"]);
					$data_count++;
				}
			}
		}
		return $data_array;

	}

	public static function MediaSpendTV($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$my_brand_id = '';
		$total = 0;
		$x = 0;
		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));
		$data_array = array();
		$data_count = 0;
		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));
		$sql_brand="SELECT distinct(brand_id), brand_name, sum(rate) as sum  from $temp where reel_date between '$week_start' and '$week_end' and station_type='tv' and company_id='$this_company_id' group by brand_name  order by sum desc limit 10;";
		if($tv_results = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
			foreach ($tv_results as $myrow_brand) {
				$brand[$x]=$myrow_brand["brand_id"]; 
				$my_brand_id.=$brand[$x].",";
				$brand_sum[$x]=$myrow_brand["sum"];
				$brand[$x] ;
				$brand_name[$x]=trim(str_replace("&"," ",$myrow_brand["brand_name"]));
				$brand_name[$x]=trim(str_replace(","," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("'"," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("  "," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("  "," ",$brand_name[$x]));
				$brand_name[$x]=substr($brand_name[$x],0,20);
				$data_array[$data_count]['brand_name'] = $brand_name[$x];
				$data_array[$data_count]['brand_value'] = intval($brand_sum[$x]);
				$data_count++;
				$x++;
			}
		}

		$my_brand_id=substr($my_brand_id,0,-1);

		if($my_brand_id!=null){
			$sql_brand="SELECT  sum(rate) as sum, brand_id  from $temp where reel_date between '$week_start' and '$week_end' and station_type='tv' and brand_id not in ($my_brand_id)  and company_id='$this_company_id' ";
			if($tv_results = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
				foreach ($tv_results as $myrow_brand) {
					$data_array[$data_count]['brand_name']='Others';
					$data_array[$data_count]['brand_value'] = intval($myrow_brand["sum"]);
					$data_count++;
				}
			}
		}

		return $data_array;
	}

	public static function MediaPrint($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$my_brand_id = '';
		$total = 0;
		$x = 0;
		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));
		$data_array = array();
		$data_count = 0;
		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));
		$sql_brand="SELECT distinct(brand_table.brand_id), brand_name, sum(print_table.ave) as sum from brand_table, print_table where brand_table.company_id='$this_company_id' and  brand_table.brand_id=print_table.brand_id and print_table.date between '$week_start' and '$week_end'  $sub_query group by brand_name order by sum desc limit 10;";
		if($print_results = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
			foreach ($print_results as $myrow_brand) {
				$brand[$x]=$myrow_brand["brand_id"]; 
				$my_brand_id.=$brand[$x].",";
				$brand_sum[$x]=$myrow_brand["sum"];
				$brand[$x] ;
				$brand_name[$x]=trim(str_replace("&"," ",$myrow_brand["brand_name"]));
				$brand_name[$x]=trim(str_replace(","," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("'"," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("  "," ",$brand_name[$x]));
				$brand_name[$x]=trim(str_replace("  "," ",$brand_name[$x]));
				$brand_name[$x]=substr($brand_name[$x],0,20);
				$data_array[$data_count]['brand_name'] = $brand_name[$x];
				$data_array[$data_count]['brand_value'] = intval($brand_sum[$x]);
				$data_count++;
				$x++;
			}
		}

		$my_brand_id=substr($my_brand_id,0,-1);

		if($my_brand_id!=null){
			$sql_brand="SELECT  sum(print_table.ave) as sum  from brand_table, print_table where brand_table.company_id='$this_company_id' and  brand_table.brand_id=print_table.brand_id and print_table.date between '$week_start' and '$week_end' and brand_table.brand_id not in ($my_brand_id) ";
			if($print_results = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
				foreach ($print_results as $myrow_brand) {
					$data_array[$data_count]['brand_name']='Others';
					$data_array[$data_count]['brand_value'] = intval($myrow_brand["sum"]);
					$data_count++;
				}
			}
		}
		return $data_array;

		
	}

	public static function IndustryTrend($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$data_array = array();
		$company_count = 0;
		$data_count = 0;
		$week1=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*4)),$year_end));
		$week2=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*3)),$year_end));
		$week3=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*2)),$year_end));
		$week4=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*1)),$year_end));
		$week5=date("d/m/y", mktime(0,0,0,$month_end,($day_end-(7*0)),$year_end));
		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-28),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));
		$sql_company="SELECT company_id, company_name, sum(rate) as rate from $temp group by company_name order by rate desc limit 5";
		if($company_results = Yii::app()->db3->createCommand($sql_company)->queryAll()){
			foreach ($company_results as $myrow_company){
				$my_company_id=$myrow_company ["company_id"];
				$my_company_name=$myrow_company ["company_name"];
				$data_array[$company_count]['company']=$my_company_name;
				$company_week_array = array();
				$weekcount = 0;
				for($week=4;$week>=0;$week--) {
					$week_day_end=$week*7;
					$week_day_start =($week+1)*7;
					$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-$week_day_start),$year_end));
					$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-$week_day_end),$year_end));
					$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end' and company_id=$my_company_id and station_type='radio' ";
					if($radio_results = Yii::app()->db3->createCommand($sql)->queryAll()){
						foreach ($radio_results as $key) {
							$this_value=$key["sum"];
							if($this_value==null){
								$this_value = 0;
							}
						}
					}else{
						$this_value = "0";
					}
					$radio_value = intval($this_value);
					$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end' and company_id=$my_company_id and station_type='tv'";
					if($tv_results = Yii::app()->db3->createCommand($sql)->queryAll()){
						foreach ($tv_results as $key) {
							$this_value=$key["sum"];
							if($this_value==null){
								$this_value = 0;
							}
						}
					}else{
						$this_value = "0";
					}
					$tv_value=intval($this_value);
					$sql="SELECT sum(ave)  as sum from print_table, brand_table where date between '$week_start' and '$week_end' and brand_table.brand_id=print_table.brand_id and
					brand_table.company_id=$my_company_id $sub_query";
					if($print_results = Yii::app()->db3->createCommand($sql)->queryAll()){
						foreach ($print_results as $key) {
							$this_value=$key["sum"];
							if($this_value==null){
								$this_value = 0;
							}
						}
					}else{
						$this_value = "0";
					}
					$print_value=intval($this_value);
					$company_total = $tv_value + $radio_value + $print_value;
					$company_week_array[$weekcount] = $company_total;
					$weekcount++;
				}
				$data_array[$company_count]['company_array']=$company_week_array;
				$company_count++;
			}
		}
		return $data_array;
	}

	public static function IndustrySOVSummary($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));
		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));
		$data_array = array();
		$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end'  and station_type='radio' ";
		if($radio_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($radio_results as $key) {
				$this_value=$key["sum"];
				if($this_value==null){
					$this_value = 0;
				}
			}
		}else{
			$this_value = "0";
		}
		$data_array['radio'] = intval($this_value);
		$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end'  and station_type='tv' ";
		if($tv_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($tv_results as $key) {
				$this_value=$key["sum"];
				if($this_value==null){
					$this_value = 0;
				}
			}
		}else{
			$this_value = "0";
		}
		$data_array['tv'] = intval($this_value); 
		$sql="SELECT sum(ave)  as sum from print_table, brand_table where date between '$week_start' and '$week_end' and brand_table.brand_id=print_table.brand_id $sub_query";
		if($print_results = Yii::app()->db3->createCommand($sql)->queryAll()){
			foreach ($print_results as $key) {
				$this_value=$key["sum"];
				if($this_value==null){
					$this_value = 0;
				}
			}
		}else{
			$this_value = "0";
		}
		$data_array['print'] = intval($this_value);
		return $data_array;
	}

	public static function IndustryCompany($temp,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query)
	{
		$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));
		$week_start=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-7),$year_end));
		$week_end=date("Y-m-d", mktime(0,0,0,$month_end,($day_end),$year_end));
		$data_array = array();
		$company_count = 0;
		$data_count = 0;

		$sql_company="SELECT company_id, company_name, sum(rate) as rate from $temp group by company_name order by rate desc limit 5";
		if($company_results = Yii::app()->db3->createCommand($sql_company)->queryAll()){
			foreach ($company_results as $myrow_company){
				$my_company_id=$myrow_company ["company_id"];
				$my_company_name=$myrow_company ["company_name"];
				$data_array[$company_count]['company']=$my_company_name;
				$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end'  and company_id=$my_company_id and station_type='radio' ";
				if($radio_results = Yii::app()->db3->createCommand($sql)->queryAll()){
					foreach ($radio_results as $key) {
						$this_value=$key["sum"];
						if($this_value==null){$this_value = 0;}
					}
				}else{
					$this_value = "0";
				}
				$radio_value = intval($this_value);
				$data_array[$company_count]['radio']=$radio_value;
				$sql="SELECT sum(rate)  as sum from $temp where reel_date between '$week_start' and '$week_end'  and company_id=$my_company_id and station_type='tv'";
				if($tv_results = Yii::app()->db3->createCommand($sql)->queryAll()){
					foreach ($tv_results as $key) {
						$this_value=$key["sum"];
						if($this_value==null){$this_value = 0;}
					}
				}else{
					$this_value = "0";
				}
				$tv_value=intval($this_value);
				$data_array[$company_count]['tv']=$tv_value;
				$sql="SELECT sum(ave)  as sum from print_table, brand_table where date between '$week_start' and '$week_end' and brand_table.brand_id=print_table.brand_id and brand_table.company_id=$my_company_id $sub_query";
				if($print_results = Yii::app()->db3->createCommand($sql)->queryAll()){
					foreach ($print_results as $key) {
						$this_value=$key["sum"];
						if($this_value==null){$this_value = 0;}
					}
				}else{
					$this_value = "0";
				}
				$print_value=intval($this_value);
				$data_array[$company_count]['print']=$print_value;
				$company_count++;
			}
			
		}
		return $data_array;
	}
}
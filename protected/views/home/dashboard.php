<?php
$this->pageTitle=Yii::app()->name.' | Home';
$this->breadcrumbs=array('Dashboard'=>array('home/index'));
$company_name = Yii::app()->user->company_name;
$this_company_id = Yii::app()->user->company_id;
$industry_id = $_POST['industry'];
$adtype = $_POST['adtype'];
if(isset($_POST['country'])){
	$country_id = $_POST['country'];
}else{
	$country_id = Yii::app()->params['country_id'];
}
$startdate = $_POST['startdate'];
$sqlstartdate = date('Y-m-d', strtotime($startdate));
$format = 'p';

/* Date Formating Starts Here */

$year_end     = date('Y',strtotime($startdate));  
$month_end    = date('m',strtotime($startdate));  
$day_end      = date('d',strtotime($startdate));

//Prepare Dates
$date1=date("Y-m-d", mktime(0,0,0,$month_end,($day_end-(7*5)),$year_end));
$date2=$year_end ."-". $month_end."-".$day_end;

if($industry_name = AnvilIndustry::model()->find('industry_id=:a', array(':a'=>$industry_id))){
	$industry_name = $industry_name->industry_name;
}else{
	$industry_name = 'Unknown';
}

/* Sub Industry Text */
$sub_industry_names = '';
$sub_query = '';
$set_subs = '';

if(isset($_POST['sub_industry_name'])){
	foreach ($_POST['sub_industry_name'] as $sub_industry_id) {
		if($sub_industry_name= AnvilSubIndustry::model()->find('auto_id=:a', array(':a'=>$sub_industry_id))){
			$sub_industry_names .= ucwords(strtolower($sub_industry_name->sub_industry_name)).', ';
		}
	}
	$set_subs = implode(', ', $_POST['sub_industry_name']);
    $sub_query = ' and brand_table.sub_industry_id IN ('.$set_subs.')';
}

$temp_table = Common::DashboardTempTable();

/* 
** Queries Start Here
*/

if($adtype==1){
	/* Insert Reelforge_Sample Records into Temp Table */
	$sample_sql="insert into $temp_table (reel_auto_id ,company_name ,company_id ,brand_name ,brand_id ,reel_date ,reel_time ,station_name, station_id,station_type,rate) 
	select distinct(reel_auto_id), user_table.company_name, user_table.company_id, brand_table.brand_name, brand_table.brand_id, reel_date, reel_time, station.station_name, station.station_id, station.station_type,rate 
	from  user_table, reelforge_sample, brand_table, station 
	where
	reelforge_sample.reel_date between '$date1' and '$date2'  and
	reelforge_sample.company_id=user_table.company_id and 
	reelforge_sample.brand_id=brand_table.brand_id and
	reelforge_sample.station_id=station.station_id 
	$sub_query ";
	$insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
}else{
	/* Insert Reelforge_Sample Records into Temp Table */
	$sample_sql="insert into $temp_table (reel_auto_id ,company_name ,company_id ,brand_name ,brand_id ,reel_date ,reel_time ,station_name, station_id,station_type,rate) 
	select distinct(reel_auto_id), user_table.company_name, user_table.company_id, brand_table.brand_name, brand_table.brand_id, reel_date, reel_time, station.station_name, station.station_id, station.station_type,rate 
	from  user_table, reelforge_sample, brand_table, station 
	where
	reelforge_sample.reel_date between '$date1' and '$date2'  and
	reelforge_sample.company_id=user_table.company_id and  
	reelforge_sample.brand_id=brand_table.brand_id and
	reelforge_sample.station_id=station.station_id 
	$sub_query ";
	$insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();

	/* Insert Djmention Records into Temp Table */
	$mention_sql="insert into $temp_table (reel_auto_id ,company_name ,company_id ,brand_name ,brand_id ,reel_date ,reel_time ,station_name, station_id,station_type,rate)
	select distinct(auto_id), user_table.company_name, user_table.company_id, brand_table.brand_name, brand_table.brand_id, date, time, station.station_name, station.station_id, station.station_type,rate 
	from  user_table, djmentions, brand_table, station 
	where
	djmentions.date between '$date1' and '$date2'  and
	djmentions.brand_id=brand_table.brand_id and
	user_table.company_id=brand_table.company_id and  
	djmentions.station_id=station.station_id 
	$sub_query";
	$insertsql = Yii::app()->db3->createCommand($mention_sql)->execute();
} 

echo '<h3>Anvil Dashboard Reports</h3>';
echo "<div id='download_zip_file'></div>";

$title_start=date("d-M-Y", mktime(0,0,0,$month_end,($day_end-28),$year_end));
$title_end=date("d-M-Y", mktime(0,0,0,$month_end,($day_end),$year_end));			

$trend 	= Dashboard::TrendReport($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$radio 	= $trend['radio'];
$tv 	= $trend['tv'];
$print 	= $trend['print'];
$total 	= $trend['total'];
$title 	= ucwords(strtolower($company_name)). " - 4 Week Trend Report | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart = DashboardCharts::RenderTrend($radio,$tv,$print,$total,$title,$subtitle);
echo '<br><br>';

$sov = Dashboard::SOVSummary($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$radio 	= $sov['radio'];
$tv 	= $sov['tv'];
$print 	= $sov['print'];
$total 	= $radio + $tv + $print;
$title 	= ucwords(strtolower($company_name)). " - Share of Voice Report | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart2 = DashboardCharts::RenderSOVSummary($radio,$tv,$print,$total,$title,$subtitle);
echo '<br><br>';

$radiosov = Dashboard::SOVRadio($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$title 	= ucwords(strtolower($company_name)). " - Share of Voice Radio | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart3 = DashboardCharts::RenderSOVRadio($radiosov,$title,$subtitle);
echo '<br><br>';

$tvsov = Dashboard::SOVTV($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$title 	= ucwords(strtolower($company_name)). " - Share of Voice TV | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart3 = DashboardCharts::RenderSOVTV($tvsov,$title,$subtitle);
echo '<br><br>';

$printsov = Dashboard::SOVPrint($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$title 	= ucwords(strtolower($company_name)). " - Share of Voice Print | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart3 = DashboardCharts::RenderSOVPrint($printsov,$title,$subtitle);
echo '<br><br>';

$mediaradio = Dashboard::MediaSpendRadio($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$title 	= ucwords(strtolower($company_name)). " - Media Spend Radio | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart3 = DashboardCharts::RenderMediaSpendRadio($mediaradio,$title,$subtitle);
echo '<br><br>';

$mediatv = Dashboard::MediaSpendTV($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$title 	= ucwords(strtolower($company_name)). " - Media Spend TV | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart3 = DashboardCharts::RenderMediaSpendTV($mediatv,$title,$subtitle);
echo '<br><br>';

$mediaprint = Dashboard::MediaPrint($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$title 	= ucwords(strtolower($company_name)). " - Media Spend Print | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart3 = DashboardCharts::RenderMediaSpendPrint($mediaprint,$title,$subtitle);
echo '<br><br>';

$industrytrend = Dashboard::IndustryTrend($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$title 	= "Industry Trend Report | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart = DashboardCharts::RenderIndustryTrend($industrytrend,$title,$subtitle);
echo '<br><br>';

$industrysov = Dashboard::IndustrySOVSummary($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
$radio 	= $industrysov['radio'];
$tv 	= $industrysov['tv'];
$print 	= $industrysov['print'];
$total 	= $radio + $tv + $print;
$title 	= "Industry Share of Voice Report | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart2 = DashboardCharts::RenderIndustrySOVSummary($radio,$tv,$print,$total,$title,$subtitle);
echo '<br><br>';

$industrycompany = Dashboard::IndustryCompany($temp_table,$month_end,$day_end,$year_end,$company_name,$industry_name,$this_company_id,$sub_query);
// print_r($industrycompany);
$title 	= "Industry Spend Trend Report | " .$industry_name;
$subtitle = "$title_start to $title_end";
$chart = DashboardCharts::RenderIndustryBreakdown($industrycompany,$title,$subtitle);
echo '<br><br>';

// echo "<script>$( '#download_zip_file' ).html( '$compiled' );</script>";
?>
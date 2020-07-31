<?php
$this->breadcrumbs=array('New Rate Change'=>array('misc/newrates'));
$enddate = date('Y-m-d', strtotime($_POST['enddate']));
$startdate = date('Y-m-d', strtotime($_POST['startdate']));
/* Date Formating Starts Here */

$year_start     = date('Y',strtotime($startdate));  
$month_start    = date('m',strtotime($startdate));  
$day_start      = date('d',strtotime($startdate));
$year_end       = date('Y',strtotime($enddate)); 
$month_end      = date('m',strtotime($enddate)); 
$day_end        = date('d',strtotime($enddate));

$inv_start_date=$year_start."-".$month_start."-".$day_start ;
$inv_end_date=$year_end."-".$month_end."-".$day_end;

$report_start_date = $year_start."-".$month_start."-".$day_start ;
$report_end_date =$year_end."-".$month_end."-".$day_end ;

$counter=0;
$page=1;
// $Mystation_codeField=$station_codeField;
$karf=1;

echo '<h3>New Rate Changes</h3><br>';

/* Process the Ad Types */
$adtypes = array();

if(isset($_POST['adtype']) && !empty($_POST['adtype'])){
    foreach ($_POST['adtype'] as $key) {
      $adtypes[] = $key;
    }
    $adtype = implode(', ', $adtypes);
}else{
    $error_code = 1;
}

/* Process the brands */
$brands = array();

if(isset($_POST['brands']) && !empty($_POST['brands'])){
    $brandcount = 0;
	foreach ($_POST['brands'] as $key) {
		$brands[] = $key;
		$brandcount++;
	}
    $set_brands = implode(', ', $brands);
}else{
    $error_code = 2;
}

/* 
** Handle Both TV Stations and Radio Stations
*/
$tvstation = array();

if(isset($_POST['tvstation']) && !empty($_POST['tvstation'])){
    foreach ($_POST['tvstation'] as $key) {
      $tvstation[] = $key;
    }
    $set_tvstation = implode(', ', $tvstation);
    $station_query = 'station_id IN ('.$set_tvstation.')';
}

$radiostation = array();

if(isset($_POST['radiostation']) && !empty($_POST['radiostation'])){
    foreach ($_POST['radiostation'] as $key) {
      $radiostation[] = $key;
    }
    $set_radiostation = implode(', ', $radiostation);
    if(isset($station_query)){
        $station_query = 'station_id IN ('.$set_tvstation.', '.$set_radiostation.')';
    }else{
        $station_query = 'station_id IN ('.$set_radiostation.')';
    }
}

if(!isset($station_query)){
    $error_code = 3;
}

$entry_type_list=implode(",",$adtypes);
$adselection = " and entry_type_id in (" . $entry_type_list . ") ";
$log_temp="rates_log_temp_" . date("Ymdhis");

 $log_sql="CREATE   TEMPORARY  TABLE $log_temp (  
`auto_id` int(11) NOT NULL auto_increment,  
`reel_auto_id` int,
`this_company_name` varchar (100) NOT NULL ,
`this_brand_name` varchar (100) ,
`this_incantation_id` int ,
`this_incantation_file` varchar (100) ,
`this_incantation_name` varchar (100) ,
`this_data_date` date default NULL,
`this_data_time` time default NULL,
`this_data_station` varchar (100) ,
`this_data_station_id` int ,
`this_entry_type` varchar (100) ,
`this_entry_type_id` SMALLINT ,
`this_Program` varchar (100) ,
`this_Rate` varchar (100) ,
`this_duration` INT,
`this_file_path` varchar (100),
`this_mpg` char(1),
`this_mpg_path` varchar (100),
`this_comment` varchar (100),
`this_trp` char (3),
`this_media_link` char (1) default 1,
PRIMARY KEY  (`auto_id`))  DEFAULT CHARSET=utf8"; 
Yii::app()->db3->createCommand($log_sql)->execute();

// $sql_index_temp=" ALTER TABLE `$log_temp` ADD INDEX ( `this_brand_name` , `this_incantation_name` , `this_company_name` ,`this_entry_type_id` )  ";
// Yii::app()->db3->createCommand($sql_index_temp)->execute();

$selected_brands=implode(",",$brands);
$number_brands = $brandcount;

if($number_brands==1) {
	$sql_brand_name="select brand_name from brand_table where brand_id IN($set_brands) ";
	if($display_brand_name = BrandTable::model()->findBySql($sql_brand_name)){
		$display_brand_name = $display_brand_name->brand_name;
	}else{
		$display_brand_name = 'Unknown Brand';
	}
}


$incantation_list="";
$sql_incantation="select incantation.incantation_id  from incantation where incantation.incantation_brand_id in ($set_brands) ";
if($query_incantation = Incantation::model()->findAllBySql($sql_incantation)){
	foreach ($query_incantation as $incantationkey) {
		$incantation_list.=$incantationkey->incantation_id.","; 
	}
}

$incantation_list=substr($incantation_list,0,-1);

for ($x=$year_start;$x<=$year_end;$x++) {
		if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}
		for ($y=$month_start_count;$y<=$month_end_count;$y++){
			if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $my_month=substr($my_month,-2);	
				$data_table="reelforge_sample_"  .$x."_".$my_month;
				$sql=" INSERT into $log_temp (
				`reel_auto_id` ,`this_company_name` ,`this_brand_name`,`this_incantation_id` ,`this_incantation_file`,`this_incantation_name`,`this_data_date`,`this_data_time`,`this_data_station`,`this_data_station_id`,	`this_Rate`,`this_duration`,`this_file_path`,`this_mpg`,`this_mpg_path`,`this_comment`,`this_trp`,`this_entry_type_id`)
				select   
				distinct(reel_auto_id), user_table.company_name, brand_name, incantation.incantation_id, incantation.incantation_file,
				incantation.incantation_name,reel_date, reel_time, station_name , station.station_id,
				rate, incantation_length, file_path, mpg, mpg_path, comment, $data_table.trp,entry_type_id
				from  $data_table, incantation, user_table, brand_table, station
				where
				station.$station_query and 
				station.station_id =$data_table.station_id and 
				brand_table.brand_id= $data_table.brand_id and 
				user_table.company_id=$data_table.company_id and 
				incantation.incantation_id=$data_table.incantation_id and $data_table.active=1 and 
				incantation.incantation_id in ($incantation_list) and 
				reel_date between '$report_start_date' and '$report_end_date'  $adselection ";
				Yii::app()->db3->createCommand($sql)->execute();
		}//month loop
	}//year loop	

############# Manual Entries INSERT########################

for ($x=$year_start;$x<=$year_end;$x++) {
	if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
	if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}
	for ($y=$month_start_count;$y<=$month_end_count;$y++){
			if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
				$my_month=substr($my_month,-2);	
				$data_table="djmentions_"  .$x."_".$my_month;
				$sql="INSERT into $log_temp (
				`reel_auto_id` ,`this_company_name` ,`this_brand_name`,`this_incantation_file`,`this_incantation_name`,`this_data_date`,`this_data_time`,`this_data_station`,`this_data_station_id`,`this_Program`,`this_entry_type`,`this_Rate`,`this_duration`,`this_file_path`,`this_comment`,`this_trp`,`this_entry_type_id`)
				
				select   
				distinct($data_table.auto_id),	user_table.company_name, brand_name,
				filename ,	Program,date,  time, 
				station_name,station.station_id,Program,
				entry_type,	rate,$data_table.duration,file_path,
				comment ,$data_table.trp,entry_type_id
				from  $data_table, user_table, brand_table, station
				where
				station.$station_query and brand_table.brand_id= $data_table.brand_id AND $data_table.active=1
				and user_table.company_id=brand_table.company_id and 
				$data_table.brand_id in ($set_brands) and station.station_id=$data_table.station_id $adselection and
				date between '$report_start_date' and '$report_end_date' ";
				Yii::app()->db3->createCommand($sql)->execute();
	}//for	month
}// for year


$sql_index_temp=" ALTER TABLE `$log_temp` ADD INDEX ( `this_data_station`,`this_data_date` , `this_data_time` )  ";
Yii::app()->db3->createCommand($sql_index_temp)->execute();

$sql_entry_type="select entry_type_id, entry_type from djmentions_entry_types where auto_id";
if($query_entry_type = Yii::app()->db3->createCommand($sql_entry_type)->queryAll()){
	foreach ($query_entry_type as $myrow_entry_type) {
		$this_entry_type=$myrow_entry_type["entry_type"];
		$this_entry_type_id=$myrow_entry_type["entry_type_id"];
		
		$sql_update="update $log_temp set this_entry_type='$this_entry_type' where this_entry_type_id=$this_entry_type_id and this_entry_type is NULL";
		Yii::app()->db3->createCommand($sql_update)->execute();
	}
}

###################################################
######## Start Processing log_temp for display ####
###################################################
$body="";
$sql_station="select distinct(this_data_station), station_id from $log_temp, station where this_data_station=station.station_name order by this_data_station asc, this_data_date asc, this_data_time asc";

$z=0;
$my_count = 0;
$trp = 0;
$grand_trp_total = 0;
$station_ad_count = 0;
$grand_total = 0;


if($query_station = Yii::app()->db3->createCommand($sql_station)->queryAll()){
	foreach ($query_station as $myrow_station) {
		$station_total=0;
		$station_trp_total=0;
		$this_station_name=$myrow_station["this_data_station"];  
		$this_station_id=$myrow_station["station_id"];

		echo '<div id="station_breakdown" class="row-fluid active nav nav-tabs bordered clearfix">';
        echo '<div class="station_header clearfix"><strong><div class="col-md-6">'.strtoupper($this_station_name).'</strong> </div><div class="col-md-6"><a class="blinky" onClick="opendiv('.$this_station_id.');">Show/Hide Results</a></div></div>';
        echo '<div id="'.$this_station_id.'" class="col-md-12" style="display: none;">';

		echo "<table  class='table'>";
		echo  "<tr valign='top'   bgcolor='#ffffff'>
		<td><strong>Date</strong></td><td><strong>Day</strong></td><td><strong>Time</strong></td><td width=220><strong>Ad name</strong></td>
		<td width=220><strong>Brand Name</strong></td><td width=70><strong>Type</strong></td><td><strong>Duration</strong></td><td><strong><div align='left'>Ratecard(Kshs)</div></strong></td>
		<td><strong><div align='left'>Actual Value(Kshs)</div></strong></td>";

		$sql_data="select * from $log_temp where this_data_station='$this_station_name'   order by this_data_date asc, this_data_time asc ";
		if($query_data = Yii::app()->db3->createCommand($sql_data)->queryAll()){
			echo '<tr><td><form name="'.$this_station_id.'" method="post" action="'.Yii::app()->createUrl("misc/ratesave").'" target="_blank"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
			foreach ($query_data as $myrow_data) {
				$ad_name="";
				$data_reel_auto_id 			= $myrow_data["reel_auto_id"];        
				$data_this_company_name 	= $myrow_data["this_company_name"];   
				$data_this_brand_name 		= $myrow_data["this_brand_name"];      
				$data_this_incantation_id 	= $myrow_data["this_incantation_id"]; 
				$data_this_incantation_file = $myrow_data["this_incantation_file"];
				$data_this_incantation_name = $myrow_data["this_incantation_name"];
				$data_this_data_date		= $myrow_data["this_data_date"];      
				$data_this_data_time 		= $myrow_data["this_data_time"];       
				$data_this_data_station 	= $myrow_data["this_data_station"];
				$data_this_data_station_id 	= $myrow_data["this_data_station_id"];    
				$data_this_entry_type 		= $myrow_data["this_entry_type"];      
				$data_this_Program 			= $myrow_data["this_Program"];         
				$data_this_Rate 			= $myrow_data["this_Rate"];    
				$data_this_duration 		= $myrow_data["this_duration"];            
				$data_this_file_path 		= $myrow_data["this_file_path"]; 

				$this_name=strtolower($data_this_incantation_name);
				$not_show_entry=0;

				if (strpos($this_name, "voxpop") || strpos($this_name, "vox pop") || strpos($this_name, "vox-pop")) {
					$data_this_entry_type="Vox Pop";
					if(!$adtype[16]) { $not_show_entry=1;}
				}
				if (strpos($this_name, " prog") || strpos(strtolower($this_name), "program")  || strpos($data_this_incantation_name," Prog")) {
					$data_this_entry_type="Promo";
					if(!$adtype[23]) { $not_show_entry=1;}
				}
				if (strpos($this_name, "tips") || strpos($this_name, "tips")) {
					$data_this_entry_type="Tips";
					if(!$adtype[22]) { $not_show_entry=1;}
				}
				if (strpos($this_name, "skit") || strpos($this_name, "skits")) {
					$data_this_entry_type="Skit";
					if(!$adtype[17]) { $not_show_entry=1;}
				}
				if (strpos($this_name, "feature") || strpos($this_name, "features")) {
					$data_this_entry_type="Feature";
					if(!$adtype[24]) { $not_show_entry=1;}
				}
				if (strpos($this_name, "testim") || strpos($this_name, "testimonial")) {
					$data_this_entry_type="Testimonial";
					if(!$adtype[27]) { $not_show_entry=1;}
				}
				if (strpos($this_name, "drop") || strpos($this_name, "drops")) {
					$data_this_entry_type="Drop";
					if(!$adtype[11]) { $not_show_entry=1;}
				}
				if (strpos($this_name, "mentions") || strpos($this_name, "mention")) {
					$data_this_entry_type="Mentions";
					if(!$adtype[28]) { $not_show_entry=1;}
				}
				if (strpos($this_name, "boards") || strpos($this_name, "board")) {
					$data_this_entry_type="Board";
					if(!$adtype[25]) { $not_show_entry=1;}
				}
				if ( strpos($this_name, "informercial") )  {
				            $data_this_entry_type="Informercial";
					if(!$adtype[26]) { $not_show_entry=1;}
				}


				if ($data_this_entry_type=='Spot Ad' &&  $adtype[1]!=1) {
					$not_show_entry=1;
				}


				if($not_show_entry<1 ) {
					$data_this_incantation_name=ucwords(str_replace("_"," ",$data_this_incantation_name));
					if($data_this_entry_type=="Spot Ad") {
						$data_this_file_path=str_replace("/home/srv/www/htdocs","",$data_this_file_path);
						$data_this_incantation_file=str_replace(".wav", ".mp3",$data_this_incantation_file);
						$ad_name="<a href=\"javascript: void(0)\"  onclick=\"window.open('http://beta.reelforge.com/" .$data_this_file_path . 		
						$data_this_incantation_file . "',  'windowname1',  'width=200, height=90'); return false;\">" . substr($data_this_incantation_name,0,50) . "</a>";						
						$display_ad_name=substr($data_this_incantation_name,0,50);
						$my_ad_name=$data_this_incantation_name;
					}

					if($data_this_entry_type=="Program") {
						$display_ad_name=$ad_name=$data_this_brand_name;
						$my_ad_name=$data_this_brand_name;
					}

					if($data_this_entry_type=="DJ Mention" && (strlen($data_this_incantation_file)>10)) {
						$ad_name=$data_this_brand_name;
						$ad_name=$data_this_incantation_name;
						$data_this_file_path=str_replace("/home/srv/www/htdocs","",$data_this_file_path);
						$ad_name="<a href=\"javascript: void(0)\"  onclick=\"window.open('http://beta.reelforge.com/" .$data_this_file_path . 		
						$data_this_incantation_file . "',  'windowname1',  'width=200, height=90'); return false;\">" . substr($data_this_brand_name,0,50) . "</a>";	
						$display_ad_name=substr($data_this_brand_name,0,50);
						$my_ad_name=$data_this_brand_name;
					}

					if(!isset($my_ad_name)) {
						$my_ad_name=$data_this_brand_name;
					}
					if(!$ad_name) {
						$ad_name=$data_this_brand_name;
						$display_ad_name=$data_this_brand_name;
					}				
					$thisSlotMonth=substr($data_this_data_date,5,2); 
					$thisSlotDay=substr($data_this_data_date,8,2);
					$thisSlotYear=substr($data_this_data_date,0,4);
					$thisSlotHour=substr($data_this_data_time,0,2);
					$thisinfoMinute=substr($data_this_data_time,3,2);
					$data_this_data_day=strtoupper(date("D",mktime($thisSlotHour, $thisinfoMinute, 0, $thisSlotMonth,$thisSlotDay, $thisSlotYear)));


					################## Calculate Rate for those without####################
					if(!$data_this_Rate){
						$this_time_unix=mktime($thisSlotHour, $thisinfoMinute, 0, $thisSlotMonth,$thisSlotDay, $thisSlotYear);
						$myTime=$thisSlotHour.":".$thisinfoMinute.":00";
						$data_this_duration=ceil($data_this_duration/5)*5;
						$data_this_duration_check=$data_this_duration;
						if($data_this_duration_check>59){ 
							$data_this_duration_check=60;
						}
						$weekday_cost=date("D",	mktime($thisSlotHour, $thisinfoMinute, 0, $thisSlotMonth,$thisSlotDay, $thisSlotYear));



						$sql_rate_cost="select * from ratecard, station where 
						station.station_name='$this_station_name' and 
						station.station_id=ratecard.station_id and 
						weekday='$weekday_cost' and 
						duration>='$data_this_duration_check' and 
						time_start<='$myTime'  
						order by time_start desc, time_end asc, duration asc limit 1";

						if($myrate = Ratecard::model()->findBySql($sql_rate_cost)){
							$data_this_Rate = $myrate->rate;
						}else{
							$data_this_Rate = 0;
						}
					}

					############## No rate for Activations and Programs ###########################					
					if($data_this_entry_type=="Activation" || $data_this_entry_type=="Program") {
						$data_this_Rate="";
					}

					################## End of Rate calculation ###########################

					//If duration is null, display dash, strictly for aesthetics, we dont want the client to see a zero value
					if(!$data_this_duration || $data_this_duration=='0') {
						$data_this_duration="-";
					}

					$data_this_duration_show=Common::sec2hms($data_this_duration,0);

					$z++;
					$my_count++;


					if($this_entry_type=='Spot Ad'   ) { 
						$table_name='A';
					} else {
						$table_name='M';
					}
					echo  "<tr valign='top'   bgcolor='#f2f2f2'>
					<td>$data_this_data_date</td>
					<td>$data_this_data_day</td>
					<td>$data_this_data_time</td>
					<td>$ad_name</td>
					<td>$data_this_brand_name</td>
					<td>$data_this_entry_type</td>
					<td>$data_this_duration_show</td>			
					<td><div align='right'>". $data_this_Rate. "</div></td>";
					echo 	"<td><div align='right'>
					<input type='hidden' name='NewRate[$z][rcvalue]' value='$data_this_Rate'>
					<input type='hidden' name='NewRate[$z][startdate]' value='$startdate'>
					<input type='hidden' name='NewRate[$z][enddate]' value='$enddate'>
					<input type='hidden' name='NewRate[$z][date]' value='$data_this_data_date'>
					<input type='hidden' name='NewRate[$z][day]' value='$data_this_data_day'>
					<input type='hidden' name='NewRate[$z][time]' value='$data_this_data_time'>
					<input type='hidden' name='NewRate[$z][ad_name]' value='$my_ad_name'>
					<input type='hidden' name='NewRate[$z][brand_name]' value='$data_this_brand_name'>
					<input type='hidden' name='NewRate[$z][entry_type]' value='$data_this_entry_type'>
					<input type='hidden' name='NewRate[$z][duration]' value='$data_this_duration_show' >
					<input type='hidden' name='NewRate[$z][station_id]' value='$data_this_data_station_id'>
					<input type='hidden' name='NewRate[$z][table_name]' value='$table_name'>
					<input type='hidden' name='NewRate[$z][table_id]' value='$data_reel_auto_id' >
					<input type='text' name='NewRate[$z][value]'  ></div></td>";	
					echo "</tr>";

					$station_ad_count++;
					$station_trp_total=$station_trp_total+$trp;				
					$grand_trp_total=$grand_trp_total+$trp;		

					$station_total=$station_total+$data_this_Rate;
					$grand_total=$grand_total+$data_this_Rate;	
				}//not show entry	
			}
			

		}else{ 
			echo 'No Data For This Station';
		}
					

		echo  "<tr  bgcolor='#f2f2f2'  valign='top' >
		<td colspan=6><strong><div right='right'>
		<input type='hidden' id='station_count_".$data_this_data_station_id."' value='$station_ad_count' >
		$this_station_name | Total Number of Ads $station_ad_count     </div></strong></td>
		<td> <strong>TOTALS</strong></td>
		<td align='right'><strong><div align='right'>". number_format($station_total) . "</strong></div></td>";
		echo "<td align='right'></td>";
		echo "</tr><tr><td colspan='7'></td><td></td><td></td></tr>";
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>
		<input onClick="opendiv('.$this_station_id.');" type="submit" name="'.$this_station_id.'" value="Save Values" class="btn btn-info" />
		</form></td></tr>';
		echo "</table>";			
		$station_ad_count=0;
		$my_count=0;
		echo '</div>';
        echo '</div>';
	}


	

}else{
	echo 'No Results Found';
}


		
?>

<style type="text/css">
.station_header{
	padding: 10px 0px 10px 0px;
}
</style>
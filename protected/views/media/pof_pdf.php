<?php
/**
* POF PDF File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
echo '<img src="'.$anvil_header.'" width="100%" />';
/*if(Yii::app()->user->country_code=='KE'){
    $linkurl = 'beta';
}else{
    $linkurl = strtolower(Yii::app()->user->country_code);
}*/
$linkurl = Yii::app()->params['eleclink'];
/* 
Run The Query to Generate the Temp Table if it Doesn't Exist 
Called it Union Because its one hell of a union, created a temporary table out of union selects
Not sure if that was cost effective in the long run. This needs to be tested
*/
$set_brands     = $_SESSION['brands'];
$startdate      = $_SESSION['startdate'];
$enddate        = $_SESSION['enddate'];
$currency       = $_SESSION['currency'];
$station_query  = $_SESSION['station_query'];
$ad_query       = $_SESSION["ad_query"];
$reportformat   = $_SESSION['reportformat'];
$adtypes        = $_SESSION['search_entry_type'];

/* Date Formating Starts Here */

$year_start     = date('Y',strtotime($startdate));  
$month_start    = date('m',strtotime($startdate));  
$day_start      = date('d',strtotime($startdate));
$year_end       = date('Y',strtotime($enddate)); 
$month_end      = date('m',strtotime($enddate)); 
$day_end        = date('d',strtotime($enddate));

/* 
** DJmentions
** Query Preparation - Loop through years, months, days
*/
$temp_table = Common::POFTempTable();

for ($x=$year_start;$x<=$year_end;$x++)
{
    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

    $month_start_count=$month_start_count+0;

    for ($y=$month_start_count;$y<=$month_end_count;$y++)
    {
        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
        $temp_table_month="djmentions_"  .$x."_".$my_month;
        $mentions_sql = 'insert into '.$temp_table.' (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,program_name) 
        select 
        distinct('.$temp_table_month.'.auto_id) as auto_id,
        '.$temp_table_month.'.brand_id as brand_id,
        '.$temp_table_month.'.entry_type_id as entry_type_id,
        "incantation_id",
        '.$temp_table_month.'.station_id as station_id,
        '.$temp_table_month.'.date as date,
        '.$temp_table_month.'.time as time,
        '.$temp_table_month.'.comment as comment,
        '.$temp_table_month.'.rate as rate,
        brand_table.brand_name as brand_name,
        djmentions_entry_types.entry_type as entry_type,
        brand_table.brand_name as incantation_name,
        '.$temp_table_month.'.duration as duration,
        CONCAT('.$temp_table_month.'.file_path, "", '.$temp_table_month.'.filename) as file,
        "video_file"  ,
        '.$temp_table_month.'.Program as program_name 
        FROM '.$temp_table_month.', brand_table, djmentions_entry_types, station
        WHERE '.$temp_table_month.'.'.$station_query.' 
        AND '.$ad_query.'
        AND '.$temp_table_month.'.brand_id IN ('.$set_brands.')
        AND '.$temp_table_month.'.brand_id=brand_table.brand_id 
        AND '.$temp_table_month.'.entry_type_id=djmentions_entry_types.entry_type_id AND '.$temp_table_month.'.active=1 
        AND date between "'.$startdate.'" and "'.$enddate.'"';

        $insertsql = Yii::app()->db3->createCommand($mentions_sql)->execute();
    }
}

/* 
** Reelforge Sample
** Query Preparation - Loop through years, months, days
*/

for ($x=$year_start;$x<=$year_end;$x++)
{
    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

    $month_start_count=$month_start_count+0;

    for ($y=$month_start_count;$y<=$month_end_count;$y++)
    {
        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
        $temp_table_month="reelforge_sample_"  .$x."_".$my_month;
        $sample_sql = 'insert into '.$temp_table.' (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file) 
        select 
        distinct('.$temp_table_month.'.reel_auto_id) as auto_id,
        '.$temp_table_month.'.brand_id as brand_id,
        '.$temp_table_month.'.entry_type_id as entry_type_id,
        '.$temp_table_month.'.incantation_id as incantation_id,
        '.$temp_table_month.'.station_id as station_id,
        '.$temp_table_month.'.reel_date as date,
        '.$temp_table_month.'.reel_time as time,
        '.$temp_table_month.'.comment as comment,
        '.$temp_table_month.'.rate as rate,
        brand_table.brand_name as brand_name,
        djmentions_entry_types.entry_type as entry_type,
        incantation.incantation_name as incantation_name,
        incantation.incantation_length as duration,
        CONCAT(incantation.file_path, "", incantation.incantation_file) as file,
        CONCAT(incantation.file_path, "", incantation.mpg_path) as video_file
        FROM '.$temp_table_month.', incantation,user_table,brand_table,djmentions_entry_types
        WHERE '.$temp_table_month.'.'.$station_query.' 
        AND '.$temp_table_month.'.brand_id IN ('.$set_brands.')
        AND '.$ad_query.' 
        AND '.$temp_table_month.'.brand_id=brand_table.brand_id 
        AND '.$temp_table_month.'.incantation_id=incantation.incantation_id 
        AND user_table.company_id='.$temp_table_month.'.company_id 
        AND '.$temp_table_month.'.entry_type_id=djmentions_entry_types.entry_type_id AND '.$temp_table_month.'.active=1 
        AND '.$temp_table_month.'.reel_date between "'.$startdate.'" and "'.$enddate.'" ';
        $insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
    }
}

/* Delete Records That Do Not Belong Here, 
** Based on Brands & Stations Assigned to Them
** Delete all where not in Stations Should Work
** DELETE ONLY WHERE THE USER IS AN AGENCY
*/
if(Yii::app()->user->usertype=='agency'){
    $brands = explode(',', $set_brands);
    foreach ($brands as $brand_key) {
        $strict_brands = $brand_key;
        $agency_id = Yii::app()->user->company_id;
        if($brand_stations = AgencyBrandStation::model()->findAll('agency_id=:a AND brand_id=:b', array(':a'=>$agency_id, ':b'=>$strict_brands))){
            $stations_select = array();
            foreach ($brand_stations as $delete_stations) {
                $stations_select[] = $delete_stations->station_id;
            }
            $ok_stations = implode(', ', $stations_select);
            $ok_stations_query = "DELETE FROM $temp_table WHERE brand_id =$strict_brands AND station_id NOT IN ($ok_stations)";
            $deletesql = Yii::app()->db3->createCommand($ok_stations_query)->execute();
        }
    }
}


$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
from '.$temp_table.' inner join station 
on station.station_id = '.$temp_table.'.station_id 
order by station.station_name asc';
$stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll();

foreach ($stored_stations as $found_stations) {
    $fstation_id = $found_stations['station_id'];
    echo '<h2>'.$fstation_name = $found_stations['station_name'];
    echo '</h2>';
    $fstation_type = $found_stations['station_type'];
    $adtypes = $_SESSION['search_entry_type'];

    if($adtypes==1){
        $adtypes = 'SELECT distinct entry_type  FROM '.$temp_table.' WHERE station_id='.$fstation_id.' order by date, time';
        if($found_adtypes = Yii::app()->db3->createCommand($adtypes)->queryAll()){
            foreach ($found_adtypes as $adkey) {
                $adname = $adkey['entry_type'];
                echo '<p><strong>'.$adname.'</strong></p>';
                $union_select = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$fstation_id.' AND entry_type="'.$adname.'" order by date, time';
                if($data = Yii::app()->db3->createCommand($union_select)->queryAll())
                {
                    echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                    echo '<tr><td>Ad Name</td><td>Brand Name</td><td>Date</td><td>Time</td><td>Type</td><td>Duration(h:m:s)</td><td>Rate('.$currency.')</td></tr>';
                    $sum = 0;
                    foreach ($data as $result) {
                        if($fstation_type=='radio'){
                            $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
                            $media_link = $linkurl.$data_this_file_path;
                            $media_link=str_replace("wav","mp3",$media_link);
                        }else{
                            if($result['video_file']=='video_file'){
                                $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
                                $media_link = $linkurl.$data_this_file_path;
                                $media_link=str_replace("wav","mp3",$media_link);
                            }else{
                                $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['video_file']);
                                $media_link = $linkurl.$data_this_file_path;
                            }
                        }
                        echo '<tr>';
                        $entry_identifier = $result['entry_type_id'];
                        /*if($entry_identifier==3){
                            echo '<td class="fupisha">'.$result['incantation_name'].'</td>';
                        }else*/if($entry_identifier==4){
                            echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['program_name'].'</a></td>';
                        }else{
                            echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>';
                        }
                        echo '<td>'.$result['brand_name'].'</td>';
                        echo '<td>'.$result['date'].'</td>';
                        echo '<td>'.$result['time'].'</td>';
                        echo '<td>'.$result['entry_type'].'</td>';
                        echo '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
                        echo '<td style="text-align:right;">'.number_format((float)$result['rate']).'</td>';
                        echo '</tr>';
                        $sum = $sum + $result['rate'];
                    }
                    echo '</table>';
                    
                    $total = count($data);
                    echo "<p><strong>TOTAL (".$adname.") Total Number of Ads ".$total."</strong></p>";
                    echo '<p><strong>Total '.$currency.'. '.number_format($sum).'</strong></p>';
                }
            }
        }
    }else{
        $union_select = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$fstation_id.' order by date, time';
        
        if($data = Yii::app()->db3->createCommand($union_select)->queryAll())
        {
           
            
            echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
            echo '<tr><td>Ad Name</td><td>Brand Name</td><td>Date</td><td>Time</td><td>Type</td><td>Duration(h:m:s)</td><td>Rate('.$currency.')</td></tr>';
            $sum = 0;
            foreach ($data as $result) {
                if($fstation_type=='radio'){
                    $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
                    $media_link = $linkurl.$data_this_file_path;
                    $media_link=str_replace("wav","mp3",$media_link);
                }else{
                    if($result['video_file']=='video_file'){
                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
                        $media_link = $linkurl.$data_this_file_path;
                        $media_link=str_replace("wav","mp3",$media_link);
                    }else{
                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['video_file']);
                        $media_link = $linkurl.$data_this_file_path;
                    }
                }
                echo '<tr>';
                $entry_identifier = $result['entry_type_id'];
                /*if($entry_identifier==3){
                    echo '<td class="fupisha">'.$result['incantation_name'].'</td>';
                }else*/if($entry_identifier==4){
                    echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['program_name'].'</a></td>';
                }else{
                    echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>';
                }
                echo '<td>'.$result['brand_name'].'</td>';
                echo '<td>'.$result['date'].'</td>';
                echo '<td>'.$result['time'].'</td>';
                echo '<td>'.$result['entry_type'].'</td>';
                echo '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
                echo '<td style="text-align:right;">'.number_format((float)$result['rate']).'</td>';
                echo '</tr>';
                $sum = $sum + $result['rate'];
            }
            echo '</table>';
            
            $total = count($data);
            echo "<p><strong>STATION TOTAL (".$fstation_name.") Total Number of Ads ".$total."</strong></p>";
            echo '<p><strong>Total '.$currency.'. '.number_format($sum).'</strong></p>';
        }
    }
}
?>
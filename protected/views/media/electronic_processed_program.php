<?php
/**
* Electronic Processed File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/
$this->pageTitle=Yii::app()->name.' | Proof of Flight';
$this->breadcrumbs=array('Proof of Flight(Electronic)'=>array('media/electronic'));

/* Print The Top */
echo '<h3>Proof of Flight Report</h3>';

/* Process the dates to mysql types */

$enddate = date('Y-m-d', strtotime($_POST['enddate']));
$startdate = date('Y-m-d', strtotime($_POST['startdate']));

/* Obtain Currency */

if(isset($_POST['country'])){
    if($currency = Country::model()->find('country_code=:a', array(':a'=>$_POST['country']))){
        $currency = $currency->currency;
    }else{
        $currency = 'KSH';
    }
}else{
    $currency = 'KSH';
}

/* Process the Ad Types */
$adtypes = array();

if(isset($_POST['adtype']) && !empty($_POST['adtype'])){
    foreach ($_POST['adtype'] as $key) {
      $adtypes[] = $key;
    }
    $set_adtypes = implode(', ', $adtypes);
    $ad_query = 'djmentions_entry_types.entry_type_id IN ('.$set_adtypes.')';
}else{
    $error_code = 1;
}

/* Process the brands */
$brands = array();

if(isset($_POST['brands']) && !empty($_POST['brands'])){
    foreach ($_POST['brands'] as $key) {
      $brands[] = $key;
    }
    $set_brands = implode(', ', $brands);
    $brand_query = 'brand_id IN ('.$set_brands.')';
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

/* If there are any errors terminate execution at this point and redirect back to the form */
if(isset($error_code)){
    Yii::app()->user->setFlash('warning', "<strong>Error ! Please select at least one from each section </strong>");
    $this->redirect(array('electronic'));
}

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
        $mentions_sql = 'insert into '.$temp_table.' (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file) 
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
        "video_file" 
        FROM '.$temp_table_month.', brand_table, djmentions_entry_types, station
        WHERE '.$temp_table_month.'.'.$station_query.' 
        AND '.$temp_table_month.'.brand_id IN ('.$set_brands.')
        AND '.$temp_table_month.'.brand_id=brand_table.brand_id 
        AND '.$temp_table_month.'.entry_type_id=djmentions_entry_types.entry_type_id
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
        FROM '.$temp_table_month.', incantation,brand_table,djmentions_entry_types
        WHERE '.$temp_table_month.'.'.$station_query.' 
        AND '.$temp_table_month.'.brand_id IN ('.$set_brands.')
        AND '.$ad_query.' 
        AND '.$temp_table_month.'.brand_id=brand_table.brand_id 
        AND '.$temp_table_month.'.incantation_id=incantation.incantation_id 
        
        AND '.$temp_table_month.'.entry_type_id=djmentions_entry_types.entry_type_id 
        AND '.$temp_table_month.'.reel_date between "'.$startdate.'" and "'.$enddate.'" ';
        $insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
    }
}

/* 
Check if the Session has been started, this requires PHP 5.4 upwards, and we got that ;) 
To avoid reconstructing the Query we just bundled it into sessions
We should debate the viability of this, and if its ok to do it this way
*/
if (Common::CheckSession()==FALSE) {
    session_start();
}
$_SESSION["brands"] = $set_brands;
$_SESSION["ad_query"] = $ad_query;
$_SESSION["station_query"] = $station_query;
$_SESSION["startdate"] = $startdate;
$_SESSION["enddate"] = $enddate;
$_SESSION["currency"] = $currency;


/* 
** Data Grouping Begins Here
** This will be used to Group Data 
** Create an array to hold the station ID (@stationarray)
** Start Selecting the Station Data based on the records that exist on the temporary table 
*/

$audio_icon = Yii::app()->request->baseUrl .'/images/play_icon.jpeg';
$video_icon = Yii::app()->request->baseUrl .'/images/vid_icon.jpg';

$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
from '.$temp_table.' inner join station 
on station.station_id = '.$temp_table.'.station_id 
order by station.station_name asc';
if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll())
{

    




    

?>
    <div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
        <header role="heading clearfix">
            <?php echo '<h2><span class="pull-left">The following is a Proof of Flight Report the period: '.$startdate.' and '.$enddate.'</span><span class="pull-right">'; ?>
            <a href="<?=Yii::app()->createUrl("media/pdf");?>" class="btn btn-danger btn-xs pdf-excel"><i class="fa fa-file-pdf-o"></i> PDF</a>
            <a href="<?=Yii::app()->createUrl("media/excel");?>" class="btn btn-success btn-xs pdf-excel"><i class="fa fa-file-excel-o"></i> EXCEL</a>
            </span></h2>
        </header>
    </div>
    
    <?php




    /* 
    ** This section if for the Tables for the Browser View 
    */

    echo '<div class="widget-body">
    <ul id="tabs" class="nav nav-tabs bordered">';
    $first_element = $stored_stations[0];
    $active_tab = $first_element ['station_id'];
    foreach ($stored_stations as $station_header) {
        $tab_id = $station_header['station_id'];
        $tab_name = $station_header['station_name'];
        if($tab_id==$active_tab){
            echo '<li class="active"><a href="#'.$tab_id.'" data-toggle="tab">'.$tab_name.'</a></li>';
        }else{
            echo '<li><a href="#'.$tab_id.'" data-toggle="tab">'.$tab_name.'</a></li>';
        }
    }
    echo '</ul>';
    echo '<div id="myTabContent1" class="tab-content padding-10">';
    foreach ($stored_stations as $found_stations) {
        $fstation_id = $found_stations['station_id'];
        $fstation_name = $found_stations['station_name'];
        $fstation_type = $found_stations['station_type'];
        $union_select = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$fstation_id.' order by date, time';
        
        if($data = Yii::app()->db3->createCommand($union_select)->queryAll())
        {
            if($fstation_id==$active_tab){
                echo '<div class="tab-pane fade active in" id="'.$fstation_id.'">';
            }else{
                echo '<div class="tab-pane fade" id="'.$fstation_id.'">';
            }
            
            echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
            echo '<thead><th></th><th>Date</th><th>Day</th><th>Time</th><th>Ad Name</th><th>Brand Name</th><th>Type</th><th>Duration(h:m:s)</th><th>Comment</th><th>Rate('.$currency.')</th><th>Program</th></thead>';
            $sum = 0;
            foreach ($data as $result) {
                $program = StationProgramme::GetProgram($result['station_id'],$result['date'],$result['date']);
                echo '<tr>';
                
                $entry_identifier = $result['entry_type_id'];
                // if($entry_identifier==3){
                //     echo '<td></td>';
                // }else{
                    $popup_name = substr($result['incantation_name'],0,35);
                    echo '<td>';
                    if($fstation_type=='radio'){
                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
                        $media_link = 'http://www.reelforge.com/'.$data_this_file_path;
                        $media_link=str_replace("wav","mp3",$media_link);
                    }else{
                        if($result['video_file']=='video_file'){
                            $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
                            $media_link = 'http://www.reelforge.com/'.$data_this_file_path;
                            $media_link=str_replace("wav","mp3",$media_link);
                        }else{
                            $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['video_file']);
                            $media_link = 'http://www.reelforge.com/'.$data_this_file_path;
                        }
                    // }
                    ?>
                    <a href="javascript: void(0)" onclick="window.open('<?=$media_link;?>','<?=$popup_name;?>', 'width=200, height=90'); return false;" >
                    <?php

                    if($fstation_type=='radio'){
                        echo '<img src="'.$audio_icon.'" ></a></td>';
                    }else{
                        if($result['video_file']=='video_file'){
                            echo '<img src="'.$audio_icon.'" ></a></td>';
                        }else{
                            echo '<img src="'.$video_icon.'" ></a></td>';
                        }
                    }
                // }
                
                echo '<td>'.$result['date'].'</td>';
                echo '<td>'.date('D',strtotime($result['date'])).'</td>';
                echo '<td>'.$result['time'].'</td>';
                // if($entry_identifier==3){
                //     echo '<td class="fupisha">'.$result['incantation_name'].'</td>';
                // }else{
                    echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>';
                // }
                
                echo '<td>'.$result['brand_name'].'</td>';
                // echo '<td>'.$result['entry_type_id'].'</td>';
                echo '<td>'.$result['entry_type'].'</td>';
                // echo '<td>'.$result['video_file'].'</td>';
                echo '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
                echo '<td>'.$result['comment'].'</td>';
                echo '<td style="text-align:right;">'.number_format((float)$result['rate']).'</td>';
                echo '<td>'.$program.'</td>';
                echo '</tr>';
                $sum = $sum + $result['rate'];
            }
            echo '</table>';
            echo '<div class="row-fluid clearfix">';
            
            $total = count($data);
            echo '<p class="pull-left"><strong>STATION TOTAL ('.$fstation_name.') | Total Number of Ads '.$total.'</strong></p>';
            echo '<p class="pull-right"><strong>'.number_format($sum).'</strong></p>';
            echo '</div>';
            echo '</div>';
        }
    }
    echo '</div>';
    echo '</div>';

    

}

?>
<div id="bottom"></div>
<style type="text/css">


.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
</style>
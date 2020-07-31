<?php

/**
* Agency Rate Change Processing File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version     v.1.0
* @since       July 2008
*/

$this->pageTitle=Yii::app()->name.' | New Rate Change';
$this->breadcrumbs=array('New Rate Change'=>array('misc/newrates'));

/* Print The Top */
echo '<h3>New Rate Changes</h3>';

/* Process the dates to mysql types */

$enddate = date('Y-m-d', strtotime($_POST['enddate']));
$startdate = date('Y-m-d', strtotime($_POST['startdate']));

/* Report Format */
if(isset($_POST['reportformat'])){
    $reportformat = $_POST['reportformat'];
}else{
    $reportformat = 0;
}

/* Obtain Currency */

if(isset($_POST['country'])){
    if($currency = Country::model()->find('country_code=:a', array(':a'=>$_POST['country']))){
        $currency = $currency->currency;
    }else{
        $currency = Yii::app()->params['country_currency'];
    }
}else{
    $currency = Yii::app()->params['country_currency'];
}

/* Process the Ad Types */
$adtypes = array();

if(isset($_POST['adtype']) && !empty($_POST['adtype'])){
    $group_ad_types = $_POST['adtype'];
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
    $brandcount = 0;
    foreach ($_POST['brands'] as $key) {
      $brands[] = $key;
      $brandcount++;
    }
    // echo $brandcount;
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
    Yii::app()->user->setFlash('warning', "<strong>Error !</strong> Please SELECT at least one item from each section");
    $this->redirect(array('newrates'));
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
$temp_table = Common::RateTempTable();

for ($x=$year_start;$x<=$year_end;$x++)
{
    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

    $month_start_count=$month_start_count+0;

    for ($y=$month_start_count;$y<=$month_end_count;$y++)
    {
        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
        $temp_table_month="djmentions_"  .$x."_".$my_month;
        $mentions_sql = 'INSERT into '.$temp_table.' (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,program_name) 
        SELECT 
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
        AND '.$temp_table_month.'.brand_id IN ('.$set_brands.') AND '.$temp_table_month.'.active=1
        AND '.$temp_table_month.'.brand_id=brand_table.brand_id 
        AND '.$temp_table_month.'.entry_type_id=djmentions_entry_types.entry_type_id
        AND date between "'.$startdate.'" and "'.$enddate.'"';

        $INSERTsql = Yii::app()->db3->createCommand($mentions_sql)->execute();
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
        $sample_sql = 'INSERT into '.$temp_table.' (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file) 
        SELECT 
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
        AND '.$temp_table_month.'.brand_id IN ('.$set_brands.') AND '.$temp_table_month.'.active=1
        AND '.$ad_query.' 
        AND '.$temp_table_month.'.brand_id=brand_table.brand_id 
        AND '.$temp_table_month.'.incantation_id=incantation.incantation_id 
        AND '.$temp_table_month.'.entry_type_id=djmentions_entry_types.entry_type_id 
        AND '.$temp_table_month.'.reel_date between "'.$startdate.'" and "'.$enddate.'" ';
        $INSERTsql = Yii::app()->db3->createCommand($sample_sql)->execute();
    }
}

/* Delete Records That Do Not Belong Here, 
** Based on Brands & Stations Assigned to Them
** Delete all where not in Stations Should Work
*/

foreach ($brands as $brand_key) {
    $strict_brands = $brand_key;
    $agency_id = Yii::app()->user->company_id;
    if($brand_stations = AgencyBrandStation::model()->findAll('agency_id=:a AND brand_id=:b', array(':a'=>$agency_id, ':b'=>$strict_brands))){
        $stations_SELECT = array();
        foreach ($brand_stations as $delete_stations) {
            $stations_SELECT[] = $delete_stations->station_id;
        }
        $ok_stations = implode(', ', $stations_SELECT);
        $ok_stations_query = "DELETE FROM $temp_table WHERE brand_id =$strict_brands AND station_id NOT IN ($ok_stations)";
        $deletesql = Yii::app()->db3->createCommand($ok_stations_query)->execute();
    }
}

/* 
** Data Grouping Begins Here
** This will be used to Group Data 
** Create an array to hold the station ID (@stationarray)
** Start SELECTing the Station Data based on the records that exist on the temporary table 
*/

$audio_icon = Yii::app()->request->baseUrl .'/images/play_icon.jpeg';
$video_icon = Yii::app()->request->baseUrl .'/images/vid_icon.jpg';

$this->renderPartial('ratesforms',array('temp_table'=>$temp_table,'set_brands'=>$set_brands,'startdate'=>$startdate,'enddate'=>$enddate,'currency'=>$currency,'audio_icon'=>$audio_icon,'video_icon'=>$video_icon));

?>
<div id="bottom"></div>
<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
#station_breakdown{
    padding: 5px 0px 10px;
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
}
#station_breakdown h3 {
  display: block;
  font-size: 16px;
  font-weight: 400;
  margin: 5px 0;
  line-height: normal;
}
#station_breakdown h4{
    display: block;
    font-size: 16px;
    font-weight: 400;
    margin: 5px 0px;
}
#station_brand {
    border-top: 1px dashed rgba(0,0,0,.2);
    border-bottom: 1px dashed rgba(0,0,0,.2);
}
.station_header .col-md-6 a{
    cursor: pointer;
    text-decoration: none;
    color: #FF5A14;
    font-weight: normal;
}
.blinky{
    cursor: pointer;
    text-decoration: none;
    color: #FF5A14;
    font-weight: normal;
}
</style>
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
/* 
Run The Query to Generate the Temp Table if it Doesn't Exist 
Called it Union Because its one hell of a union, created a temporary table out of union selects
Not sure if that was cost effective in the long run. This needs to be tested
*/
$sql1       = $_SESSION['sql1'];
$sql2       = $_SESSION['sql2'];
$startdate  = $_SESSION['startdate'];
$enddate    = $_SESSION['enddate'];

$temp_table="anvil_log_temp_" . date("Ymdhis");
$query = 'create temporary table if not exists '.$temp_table.' AS '.$sql1.' union '.$sql2;
$union = Yii::app()->db3->createCommand($query)->execute();

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
    $union_select = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$fstation_id.' order by date, time';
    
    if($data = Yii::app()->db3->createCommand($union_select)->queryAll())
    {
       
        
        echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
        echo '<tr><td>Ad Name</td><td>Brand Name</td><td>Date</td><td>Time</td><td>Type</td><td>Duration(h:m:s)</td><td>Rate(Kshs)</td></tr>';
        $sum = 0;
        foreach ($data as $result) {
            echo '<tr>';
            $entry_identifier = $result['entry_type_id'];
            if($entry_identifier==3){
                echo '<td>'.$result['incantation_name'].'</td>';
            }else{
                echo '<td><a href="'.$media_link.'">'.$result['incantation_name'].'</a></td>';
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
        echo '<p><strong>Total Kshs. '.number_format($sum).'</strong></p>';
    }
}
?>
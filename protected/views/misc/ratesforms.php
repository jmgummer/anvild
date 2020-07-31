<?php

$linkurl = Yii::app()->params['eleclink'];
$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
from '.$temp_table.' inner join station 
on station.station_id = '.$temp_table.'.station_id 
order by station.station_name asc';
$z=0;
if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll())
{

    /* 
    ** This section if for the Tables for the Browser View 
    */

    foreach ($stored_stations as $found_stations) {
        $fstation_id = $found_stations['station_id'];
        $fstation_name = $found_stations['station_name'];
        $fstation_type = $found_stations['station_type'];
        echo '<div id="station_breakdown" class="row-fluid active nav nav-tabs bordered clearfix">';
        echo '<p class="station_header clearfix"><strong><div class="col-md-6">'.$fstation_name.'</strong> </div><div class="col-md-6"><a class="blinky" onClick="opendiv('.$fstation_id.');">Show Results</a> | <a class="blinky"  onClick="opendiv('.$fstation_id.');">Hide Results</a></div></p>';
        echo '<div id="'.$fstation_id.'" class="col-md-12" style="display: none;">';
        

        $union_select = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$fstation_id.' order by date, time';
        
            if($data = Yii::app()->db3->createCommand($union_select)->queryAll())
            {
            	echo '<form name="'.$fstation_id.'" method="post" action="'.Yii::app()->createUrl("misc/ratesave").'" target="_blank">';
                echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                echo '<thead><th></th><th>Date</th><th>Day</th><th>Time</th><th>Ad Name</th><th>Brand Name</th><th>Type</th><th>Duration(h:m:s)</th><th>Ratecard('.$currency.')</th><th>Value('.$currency.')</th></thead>';
                $sum = 0;
                foreach ($data as $result) {
                	$z++;
                    echo '<tr>';
                    
                    $entry_identifier = $result['entry_type_id'];
                    if($entry_identifier==3){
                        echo '<td></td>';
                    }else{
                        $popup_name = substr($result['incantation_name'],0,35);
                        echo '<td>';
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
                    }
                    
                    echo '<td>'.$result['date'].'</td>';
                    echo '<td>'.date('D',strtotime($result['date'])).'</td>';
                    echo '<td>'.$result['time'].'</td>';
                    
                    if($entry_identifier==3){
                        echo '<td class="fupisha">'.$result['incantation_name'].'</td>';
                    }elseif($entry_identifier==4){
                        echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['program_name'].'</a></td>';
                    }else{
                        echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>';
                    }
                    
                    echo '<td>'.$result['brand_name'].'</td>';
                    echo '<td>'.$result['entry_type'].'</td>';
                    echo '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
                    echo '<td style="text-align:right;">'.number_format((float)$result['rate']).'</td>';
                    echo "<td style='text-align:right;''><input type='text' name='NewRate[$z][value]'  >";
                   
                    $sum = $sum + $result['rate'];

                    $data_this_data_date = $result["date"];
					$data_this_data_time = $result["time"]; 
					$data_this_incantation_name = $result["incantation_name"];
					$data_this_brand_name 		= $result["brand_name"];
					$data_this_entry_type 		= $result["entry_type"];
					$data_this_duration_show = gmdate("H:i:s", $result['duration']);
					$data_this_data_station_id 	= $result["station_id"];
					if($data_this_entry_type=='Spot Ad'   ) { 
						$table_name='A';
					} else {
						$table_name='M';
					}
					$data_reel_auto_id= $result["auto_id"];
					$thisSlotMonth=substr($data_this_data_date,5,2); 
					$thisSlotDay=substr($data_this_data_date,8,2);
					$thisSlotYear=substr($data_this_data_date,0,4);
					$thisSlotHour=substr($data_this_data_time,0,2);
					$thisinfoMinute=substr($data_this_data_time,3,2);
					$data_this_data_day=strtoupper(date("D",mktime($thisSlotHour, $thisinfoMinute, 0, $thisSlotMonth,$thisSlotDay, $thisSlotYear)));
					$data_this_Rate = $result['rate']; 
					

                    echo "<input type='hidden' name='NewRate[$z][rcvalue]' value='$data_this_Rate'>
					<input type='hidden' name='NewRate[$z][startdate]' value='$startdate'>
					<input type='hidden' name='NewRate[$z][enddate]' value='$enddate'>
					<input type='hidden' name='NewRate[$z][date]' value='$data_this_data_date'>
					<input type='hidden' name='NewRate[$z][day]' value='$data_this_data_day'>
					<input type='hidden' name='NewRate[$z][time]' value='$data_this_data_time'>
					<input type='hidden' name='NewRate[$z][ad_name]' value='$data_this_incantation_name'>
					<input type='hidden' name='NewRate[$z][brand_name]' value='$data_this_brand_name'>
					<input type='hidden' name='NewRate[$z][entry_type]' value='$data_this_entry_type'>
					<input type='hidden' name='NewRate[$z][duration]' value='$data_this_duration_show' >
					<input type='hidden' name='NewRate[$z][station_id]' value='$data_this_data_station_id'>
					<input type='hidden' name='NewRate[$z][table_name]' value='$table_name'>
					<input type='hidden' name='NewRate[$z][table_id]' value='$data_reel_auto_id' >";
					 echo '</td></tr>';
                }
                echo '</table>';
                echo '<div class="row-fluid clearfix">';
                
                $total = count($data);
                echo '<p class="pull-left"><strong>STATION TOTAL ('.$fstation_name.') | Total Number of Ads '.$total.'</strong></p>';
                echo '<p class="pull-right"><strong>'.number_format($sum).'</strong></p>';
                echo '</div>';

                $z++;

     
		        echo '<input type="submit" name="'.$fstation_id.'" value="Save Values" class="btn btn-info" />';
		        echo '</form>';
            }
        echo '</div>';
        echo '</div>';
    }
}else{
	echo 'No Data Found';
}

?>
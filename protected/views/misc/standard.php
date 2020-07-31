<?php
// If the code is kenyan don't change
$linkurl = Yii::app()->params['eleclink'];
echo '<h5>The following is a Proof of Flight Report the period: '.$startdate.' and '.$enddate.'</h5>';
$pdf = AdFliteDownload::GeneratePDF($temp_table,$currency,$audio_icon,$video_icon);
$excel = AdFliteDownload::GenerateEXCEL($temp_table,$currency,$audio_icon,$video_icon);

$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
from '.$temp_table.' inner join station 
on station.station_id = '.$temp_table.'.station_id 
order by station.station_name asc';
if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll())
{
    echo "<p>$pdf &nbsp; ";
    echo "$excel </p>";
    /* 
    ** This section if for the Tables for the Browser View 
    */
    foreach ($stored_stations as $found_stations) {
        $fstation_id = $found_stations['station_id'];
        $fstation_name = $found_stations['station_name'];
        $fstation_type = $found_stations['station_type'];
        $adtypes = $_SESSION['search_entry_type'];
        echo '<div id="station_breakdown" class="row-fluid active nav nav-tabs bordered clearfix">';
        echo '<p class="station_header clearfix"><strong><div class="col-md-6">'.$fstation_name.'</strong> </div><div class="col-md-6"><a class="blinky" onClick="opendiv('.$fstation_id.');">Show Results</a> | <a class="blinky"  onClick="opendiv('.$fstation_id.');">Hide Results</a></div></p>';
        echo '<div id="'.$fstation_id.'" class="col-md-12" style="display: none;">';

        $distinctbrands = "SELECT DISTINCT brand_id, brand_name FROM $temp_table WHERE station_id=$fstation_id";
        if($branddata = Yii::app()->db3->createCommand($distinctbrands)->queryAll()){
            foreach ($branddata as $brandkey) {
                $id_ = $brandkey['brand_id'];
                $name_ = $brandkey['brand_name'];
                echo "<br><p><strong>$name_</strong></p><hr>";

                $union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND brand_id=$id_ order by date, time";
        
                if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
                    echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                    echo '<thead><th></th><th>Date</th><th>Day</th><th>Time</th><th>Ad Name</th><th>Brand Name</th><th>Type</th><th>Duration(h:m:s)</th><th>Comment</th><th>Rate('.$currency.')</th></thead>';
                    $sum = 0;
                    foreach ($data as $result) {
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
                                if($result['video_file']=='video_file'){ echo '<img src="'.$audio_icon.'" ></a></td>'; }else{ echo '<img src="'.$video_icon.'" ></a></td>'; }
                            }
                        }
                        echo '<td>'.$result['date'].'</td>';
                        echo '<td>'.date('D',strtotime($result['date'])).'</td>';
                        echo '<td>'.$result['time'].'</td>';
                        if($entry_identifier==3){
                            echo '<td class="fupisha">'.$result['incantation_name'].'</td>';
                        }else{
                            echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>';
                        }
                        echo '<td>'.$result['brand_name'].'</td>';
                        echo '<td>'.$result['entry_type'].'</td>';
                        echo '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
                        echo '<td>'.$result['comment'].'</td>';
                        echo '<td style="text-align:right;">'.number_format((float)$result['rate']).'</td>';
                        echo '</tr>';
                        $sum = $sum + $result['rate'];
                    }
                    echo '</table>';
                    echo '<div class="row-fluid clearfix">';
                    
                    $total = count($data);
                    echo '<p class="pull-left"><strong>BRAND TOTAL | '.$name_.' | Total Number of Ads '.$total.'</strong></p>';
                    echo '<p class="pull-right"><strong>'.number_format($sum).'</strong></p>';
                    echo '</div>';
                }
            }
        }
        echo '</div>';
        echo '</div>';
    }
}else{
    echo 'No Records Found';
}

?>
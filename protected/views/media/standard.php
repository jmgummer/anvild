<?php
// If the code is kenyan don't change
/*if(Yii::app()->user->country_code=='KE'){
    $linkurl = 'beta';
}else{
    $linkurl = strtolower(Yii::app()->user->country_code);
}*/
$period = "Between $startdate and $enddate";
$linkurl = Yii::app()->params['eleclink'];
$adtypes = $_SESSION['search_entry_type'];
$pdf_file = AnvilPDF::StandardPDF($temp_table,'POF Log',$linkurl,$currency,$adtypes,$period);
$excel_file = AnvilExcel::StandardExcel($temp_table,'POF Log',$linkurl,$currency,$adtypes,$period);

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
            &nbsp;
            <?php echo $pdf_file; ?>&nbsp;
            <?php echo $excel_file; ?>
            </span></h2>
        </header>
    </div>
    
    <?php

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
        
        if($adtypes==1){
            $adtypes = 'SELECT distinct entry_type  FROM '.$temp_table.' WHERE station_id='.$fstation_id.' order by date, time';
            if($found_adtypes = Yii::app()->db3->createCommand($adtypes)->queryAll()){
                foreach ($found_adtypes as $adkey) {
                    $adname = $adkey['entry_type'];

                    $union_select = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$fstation_id.' AND entry_type="'.$adname.'" order by date, time';
                    if($data = Yii::app()->db3->createCommand($union_select)->queryAll()){
                        echo '<br>';
                        echo '<p><strong>'.$adname.'</strong></p>';

                        echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                        echo '<thead><th></th><th>Date</th><th>Day</th><th>Time</th><th>Campaign Name</th><th>Brand Name</th><th>Type</th><th>Duration(h:m:s)</th><th>Comment</th><th>Rate('.$currency.')</th></thead>';
                        $sum = 0;
                        foreach ($data as $result) {
                            echo '<tr>';
                            
                            $entry_identifier = $result['entry_type_id'];
                            // if($entry_identifier===3){
                            //     echo '<td></td>';
                            // }else{
                                $popup_name = substr($result['incantation_name'],0,35);
                            echo '<td>';
                        $data_this_file_path=str_replace("/home/srv/www/htdocs","",$result['file']);
                        $media_link = $linkurl.$data_this_file_path;
                        ?>
                        <a href="javascript: void(0)" onclick="window.open('<?=$media_link;?>','<?=$popup_name;?>', 'width=200, height=90'); return false;" >
                        <?php
                        echo '<img src="'.$audio_icon.'" ></a></td>';
                            
                            echo '<td>'.$result['date'].'</td>';
                            echo '<td>'.date('D',strtotime($result['date'])).'</td>';
                            echo '<td>'.$result['time'].'</td>';
                            /*if($entry_identifier==3){
                                echo '<td class="fupisha">'.$result['incantation_name'].'</td>';
                            }else*/if($entry_identifier==4){
                                echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['program_name'].'</a></td>';
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
                        echo '<p class="pull-left"><strong>'.$adname.' | Total Number of Ads '.$total.'</strong></p>';
                        echo '<p class="pull-right"><strong>'.number_format($sum).'</strong></p>';
                        echo '</div>';
                    }
                }
            }
        }else{
            $union_select = 'SELECT * FROM '.$temp_table.' WHERE station_id='.$fstation_id.' order by date, time';
        
            if($data = Yii::app()->db3->createCommand($union_select)->queryAll())
            {
                echo '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
                echo '<thead><th></th><th>Date</th><th>Day</th><th>Time</th><th>Campaign Name</th><th>Brand Name</th><th>Type</th><th>Duration(h:m:s)</th><th>Comment</th><th>Rate('.$currency.')</th></thead>';
                $sum = 0;
                foreach ($data as $result) {
                    echo '<tr>';
                    
                    $entry_identifier = $result['entry_type_id'];
                    // if($entry_identifier==3){
                    //     echo '<td></td>';
                    // }else{
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
                    // }
                    
                    echo '<td>'.$result['date'].'</td>';
                    echo '<td>'.date('D',strtotime($result['date'])).'</td>';
                    echo '<td>'.$result['time'].'</td>';
                    /*if($entry_identifier==3){
                        echo '<td class="fupisha">'.$result['incantation_name'].'</td>';
                    }else*/if($entry_identifier==4){
                        echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['program_name'].'</a></td>';
                    }else{
                        echo '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>';
                    }
                    
                    echo '<td>'.$result['brand_name'].'</td>';
                    // echo '<td>'.$result['entry_type_id'].'</td>';
                    echo '<td>'.$result['entry_type'].'</td>';
                    // echo '<td>'.$result['video_file'].'</td>';
                    echo '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
                    echo '<td>'.$result['comment'].'</td>';
                    echo '<td style="text-align:right;">'.number_format((float)$result['rate']).'</td>';
                    echo '</tr>';
                    $sum = $sum + $result['rate'];
                }
                echo '</table>';
                echo '<div class="row-fluid clearfix">';
                
                $total = count($data);
                echo '<p class="pull-left"><strong>STATION TOTAL ('.$fstation_name.') | Total Number of Ads '.$total.'</strong></p>';
                echo '<p class="pull-right"><strong>'.number_format($sum).'</strong></p>';
                echo '</div>';
            }
        }

        echo '</div>';
        echo '</div>';
    }

    

}else{
    echo 'No Records Found';
}

?>
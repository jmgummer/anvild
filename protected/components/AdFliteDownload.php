<?php

/**
* This Class Handles Generation of AdFlite PDF & Excel Files
*/
class AdFliteDownload
{
	public static function GeneratePDF($temp_table,$currency,$audio_icon,$video_icon){
		$reportname = 'AdFlite Reconciliation Log';
		$data = '';
		$linkurl = Yii::app()->params['eleclink'];

		$station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
		    foreach ($stored_stations as $found_stations) {
		        $fstation_id = $found_stations['station_id'];
		        $fstation_name = $found_stations['station_name'];
		        $fstation_type = $found_stations['station_type'];
		        $data .= '<div id="station_breakdown" class="row-fluid active nav nav-tabs bordered clearfix">';
		        $data .= '<p class="station_header clearfix"><strong>'.$fstation_name.'</strong> </p>';
		        $distinctbrands = "SELECT DISTINCT brand_id, brand_name FROM $temp_table WHERE station_id=$fstation_id";
		        if($branddata = Yii::app()->db3->createCommand($distinctbrands)->queryAll()){
		            foreach ($branddata as $brandkey) {
		                $id_ = $brandkey['brand_id'];
		                $name_ = $brandkey['brand_name'];
		                $data .= "<br><p><strong>$name_<strong></p><hr>";
		                $union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND brand_id=$id_ order by date, time";
		                if($brand_station_data = Yii::app()->db3->createCommand($union_select)->queryAll()){
		                    $data .= '<table id="dt_basic" class="table table-condensed table-bordered table-hover">';
		                    $data .= '<tr><td>Date</td><td>Day</td><td>Time</td><th>Ad Name</td><td>Brand Name</td><td>Type</td><td>Duration(h:m:s)</td><td>Comment</td><td>Rate('.$currency.')</td></tr>';
		                    $sum = 0;
		                    foreach ($brand_station_data as $result) {
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
		                        $data .= '<tr>';
		                        $entry_identifier = $result['entry_type_id'];
		                        $data .= '<td>'.$result['date'].'</td>';
		                        $data .= '<td>'.date('D',strtotime($result['date'])).'</td>';
		                        $data .= '<td>'.$result['time'].'</td>';
		                        if($entry_identifier==3){
		                            $data .= '<td class="fupisha">'.$result['incantation_name'].'</td>';
		                        }else{
		                            $data .= '<td class="fupisha"><a href="'.$media_link.'" target="_blank" >'.$result['incantation_name'].'</a></td>';
		                        }
		                        $data .= '<td>'.$result['brand_name'].'</td>';
		                        $data .= '<td>'.$result['entry_type'].'</td>';
		                        $data .= '<td>'.gmdate("H:i:s", $result['duration']).'</td>';
		                        $data .= '<td>'.$result['comment'].'</td>';
		                        $data .= '<td style="text-align:right;">'.number_format((float)$result['rate']).'</td>';
		                        $data .= '</tr>';
		                        $sum = $sum + $result['rate'];
		                    }
		                    $data .= '</table>';
		                    $data .= '<div class="row-fluid clearfix">';
		                    
		                    $total = count($brand_station_data);
		                    $data .= '<p class="pull-left"><strong>BRAND TOTAL | '.$name_.' | Total Number of Ads '.$total.'</strong></p>';
		                    $data .= '<p class="pull-right"><strong>'.number_format($sum).'</strong></p>';
		                    $data .= '</div>';
		                }
		            }
		        }
		        $data .= '</div>';
		    }
		}else{
		    $data .= 'No Records Found';
		}
		$anvil_header = Yii::app()->request->baseUrl . '/images/anvil_header.jpg';
	    $pdf_header = '<img src="'.$anvil_header.'" width="100%" /><br>';
	    $pdf_header.= "<h2>$reportname</h2>";
	    $pdf_file = $pdf_header;
	    $pdf_file = $pdf_file.$data;
		$pdf = Yii::app()->ePdf2->WriteOutput($pdf_file,array());
		$filename="Reelforge_Adflite_Reports_"  .str_replace(" ","_",Yii::app()->user->company_name)."_" .date('dmYhis');
		$filename=str_replace(" ","_",$filename);
		$filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
		$filename_pdf=$filename.'.pdf';
		$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename_pdf;
		if(file_put_contents($location, $pdf)){
			$file = Yii::app()->request->baseUrl . '/docs/misc/pdf/'.$filename_pdf;
		    $fppackage = "<a href='$file' class='btn btn-danger btn-xs' target='_blank'><i class='fa fa-file-pdf-o'></i> Download PDF</a>";
		}else{
		    $fppackage = "";
		}
		return $fppackage;
	}

	public static function GenerateEXCEL($temp_table,$currency,$audio_icon,$video_icon){
		$reportname = 'AdFlite Reconciliation Log';
		$title = $reportname;
		$linkurl = Yii::app()->params['eleclink'];
		
		$PHPExcel = new PHPExcel();
		$PHPExcel->getProperties()->setCreator("Reelforge Systems")
		->setTitle("Reelforge Reconciliation Reports")
		->setSubject("Reelforge Reconciliation Reports")
		->setDescription("Reelforge Reconciliation Reports");
		$sheet_index = 0;
		$sheet_name = 'Reconciliation Reports';
		$PHPExcel->createSheet(NULL, $sheet_index);
		$PHPExcel->setActiveSheetIndex($sheet_index)
		->setCellValue('A1', 'Reelforge Reconciliation Reports')
		->setCellValue('A2', Yii::app()->user->company_name)
		->setCellValue('A3', $title);

		$PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
		$PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
		$PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
		$PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
		$PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
		$PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
		$PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);

		$count = 5;
        $this_total=0;
        $styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));
        $boldArray = array('font'  => array('bold'  => true));
		
        $station_sql = 'select distinct '.$temp_table.'.station_id, station.station_name, station.station_type  
		from '.$temp_table.' inner join station 
		on station.station_id = '.$temp_table.'.station_id 
		order by station.station_name asc';
		if($stored_stations = Yii::app()->db3->createCommand($station_sql)->queryAll()){
		    foreach ($stored_stations as $found_stations) {
		        $fstation_id = $found_stations['station_id'];
		        $fstation_name = $found_stations['station_name'];
		        $fstation_type = $found_stations['station_type'];
		        $PHPExcel->getActiveSheet()->setCellValue("A$count", $fstation_name);
		        $PHPExcel->getActiveSheet()->mergeCells("A$count:Z$count");
		        $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($boldArray);
		        $distinctbrands = "SELECT DISTINCT brand_id, brand_name FROM $temp_table WHERE station_id=$fstation_id";
		        if($branddata = Yii::app()->db3->createCommand($distinctbrands)->queryAll()){
		        	$count++;
		            foreach ($branddata as $brandkey) {
		                $id_ = $brandkey['brand_id'];
		                $name_ = $brandkey['brand_name'];
		                $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Brand - '.$name_);
		                $PHPExcel->getActiveSheet()->mergeCells("A$count:Z$count");
		                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($boldArray);
		                $union_select = "SELECT * FROM $temp_table WHERE station_id=$fstation_id AND brand_id=$id_ order by date, time";
		                if($brand_station_data = Yii::app()->db3->createCommand($union_select)->queryAll()){
		                	$count++;
		                	$PHPExcel->getActiveSheet()
		                	->setCellValue("A$count",'Date')
		                	->setCellValue("B$count",'Day')
		                	->setCellValue("C$count",'Time')
		                	->setCellValue("D$count",'Ad Name')
		                	->setCellValue("E$count",'Brand Name')
		                	->setCellValue("F$count",'Type')
		                	->setCellValue("G$count",'Duration(h:m:s)')
		                	->setCellValue("H$count",'Comment')
		                	->setCellValue("I$count",'Rate('.$currency.')');
		                	$count++;
		                    $sum = 0;
		                    foreach ($brand_station_data as $result) {
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
	                            $entry_identifier = $result['entry_type_id'];
								$PHPExcel->getActiveSheet()
								->setCellValue("A$count",$result['date'])
								->setCellValue("B$count",date('D',strtotime($result['date'])))
								->setCellValue("C$count",$result['time']);
								$PHPExcel->getActiveSheet()->setCellValue("D$count",$result['incantation_name']);
								if($entry_identifier!=3){
									$PHPExcel->getActiveSheet()->getCell("D$count")->getHyperlink()->setUrl($media_link);
									$PHPExcel->getActiveSheet()->getStyle("D$count")->applyFromArray($styleArray);
									$PHPExcel->getActiveSheet()->getStyle("D$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
		                        }
								$PHPExcel->getActiveSheet()
								->setCellValue("E$count",$result['brand_name'])
								->setCellValue("F$count",$result['entry_type'])
								->setCellValue("G$count",gmdate("H:i:s", $result['duration']))
								->setCellValue("H$count",$result['comment'])
								->setCellValue("I$count",number_format((float)$result['rate']));
		                        $sum = $sum + $result['rate'];
		                        $count++;
		                    }
		                    $count = $count+1;
		                    $total = count($brand_station_data);
		                    
		                    $PHPExcel->getActiveSheet()->setCellValue("A$count", 'Totals | Number of Ads - '.$total.' - '.number_format($sum));
		                    $PHPExcel->getActiveSheet()->mergeCells("A$count:Z$count");
		                    $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($boldArray);
		                    $count = $count+1;
		                    $count++;
		                }
		            }
		        }
		    }
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle($sheet_name);
		$PHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $agency_name =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = $agency_name.'_Reconciliation_Log_'.date("Ymdhis").'.xls';
        $objWriter->save($upload_path.$filename);
        $file = Yii::app()->request->baseUrl . '/docs/misc/excel/'.$filename;
        $fppackage = "<a href='$file' class='btn btn-success btn-xs' target='_blank'><i class='fa fa-file-excel-o'></i> Download Excel</a>";
		return $fppackage;
	}
}
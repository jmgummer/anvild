<?php

class CompanyMediaComparison{
	public static function GetRecords($startdate,$enddate,$industry,$subindustries,$brand_query,$country_id){
		$linkurl = Yii::app()->params['eleclink'];
		if($currency = Country::model()->find('country_code=:a', array(':a'=>Yii::app()->user->country_code))){
	        $currency = $currency->currency;
	    }else{
	       	$currency = Yii::app()->params['country_currency'];
	    }

		$sqlstartdate = date('Y-m-d', strtotime($startdate));
		$sqlenddate = date('Y-m-d', strtotime($enddate));

		$setmonth = strtotime($sqlstartdate);
		$setmonth = date('M',$setmonth);
		$year = date('Y',strtotime($sqlenddate));
		$period = $setmonth.' '.$year;

		$year_start     = date('Y',strtotime($startdate));  
		$month_start    = date('m',strtotime($startdate));  
		$day_start      = date('d',strtotime($startdate));
		$year_end       = date('Y',strtotime($enddate)); 
		$month_end      = date('m',strtotime($enddate)); 
		$day_end        = date('d',strtotime($enddate));

		$temp_table = IndustrySummaries::IndustryPOFTempTable();

		$companyidlist = "38,34,10754,6532,35,54,167,176,36,10473,165,139,173,550,40,82,9214,95,70,297,9482,10917,624,10390,9689,636,79,8385,540,337";
		// $temp_table = 'test_temp';
		/* Djmentions */
		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="djmentions_"  .$x."_".$my_month;
		        $mentions_sql = "INSERT into $temp_table 
		        (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,station_name,station_type,sub_industry_id,company_name) 
		        SELECT 
		        distinct($temp_table_month.auto_id) as auto_id,
		        $temp_table_month.brand_id as brand_id,
		        $temp_table_month.entry_type_id as entry_type_id,
		        'incantation_id',
		        $temp_table_month.station_id as station_id,
		        $temp_table_month.date as date,
		        $temp_table_month.time as time,
		        $temp_table_month.comment as comment,
		        $temp_table_month.rate as rate,
		        brand_table.brand_name as brand_name,
		        djmentions_entry_types.entry_type as entry_type,
		        brand_table.brand_name as incantation_name,
		        $temp_table_month.duration as duration,
		        CONCAT($temp_table_month.file_path, '', $temp_table_month.filename) as file,
		        'video_file',
		        station_name as station_name,
		        station_type as station_type,
		        brand_table.sub_industry_id AS sub_industry_id,
		        company_name  
		        FROM $temp_table_month, brand_table, djmentions_entry_types, station, user_table
		        WHERE $temp_table_month.$brand_query 
		        AND $temp_table_month.brand_id=brand_table.brand_id 
		        AND $temp_table_month.entry_type_id=djmentions_entry_types.entry_type_id
		        AND $temp_table_month.station_id=station.station_id
		        AND $temp_table_month.rate>0 
		        AND $temp_table_month.active=1  
		        and brand_table.country_id=$country_id
		        AND brand_table.company_id IN ($companyidlist)
		        AND station.country_id = $country_id
		        AND brand_table.company_id = user_table.company_id 
		        AND date between '$sqlstartdate' and '$sqlenddate' ";
		        // echo $mentions_sql;
		        $insertsql = Yii::app()->db3->createCommand($mentions_sql)->execute();
		    }
		}
		// echo "<br>";
		/* Reelforge Sample */

		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $temp_table_month="reelforge_sample_"  .$x."_".$my_month;
		        $sample_sql = "INSERT into $temp_table 
		        (auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,station_name,station_type,sub_industry_id,company_name) 
		        SELECT 
		        distinct($temp_table_month.reel_auto_id) as auto_id,
		        $temp_table_month.brand_id as brand_id,
		        $temp_table_month.entry_type_id as entry_type_id,
		        $temp_table_month.incantation_id as incantation_id,
		        $temp_table_month.station_id as station_id,
		        $temp_table_month.reel_date as date,
		        $temp_table_month.reel_time as time,
		        $temp_table_month.comment as comment,
		        $temp_table_month.rate as rate,
		        brand_table.brand_name as brand_name,
		        djmentions_entry_types.entry_type as entry_type,
		        incantation.incantation_name as incantation_name,
		        incantation.incantation_length as duration,
		        CONCAT(incantation.file_path, '', incantation.incantation_file) as file,
		        CONCAT(incantation.file_path, '', incantation.mpg_path) as video_file,
		        station_name as station_name,
		        station_type as station_type,
		        brand_table.sub_industry_id AS sub_industry_id,
		        company_name
		        FROM $temp_table_month, incantation,brand_table,djmentions_entry_types, station, user_table
		        WHERE $temp_table_month.$brand_query 
		        AND $temp_table_month.brand_id=brand_table.brand_id 
		        AND $temp_table_month.incantation_id=incantation.incantation_id
		        AND $temp_table_month.entry_type_id=djmentions_entry_types.entry_type_id
		        AND $temp_table_month.station_id=station.station_id 
		        AND $temp_table_month.rate>0 
		        AND $temp_table_month.reel_date between '$sqlstartdate' and '$sqlenddate' 
		        AND $temp_table_month.active=1 
		        and brand_table.country_id=$country_id
		        AND station.country_id = $country_id
		        AND brand_table.company_id IN ($companyidlist)
		        AND brand_table.company_id = user_table.company_id " ;
		        // echo $sample_sql;
		        $insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
		    }
		}

		$print_sql = "INSERT into $temp_table 
		(auto_id,brand_id,entry_type_id,incantation_id,station_id,date,time,comment,rate,brand_name,entry_type,incantation_name,duration,file,video_file,station_name,station_type,sub_industry_id,company_name) 
		SELECT
		DISTINCT(print_table.auto_id) AS print_id,  
		print_table.brand_id, 
		print_table.entry_type, 
		'incantation_id',
		print_table.media_house_id AS station_id,
		date as this_date,
		'-',
		'comment',
		print_table.ave AS rate,
		brand_table.brand_name as brand_name, 
		'-', 
		'incantation_name',
		'00:00:00',
		print_table.file, 
		'video', 
		mediahouse.Media_House_List AS station_name, 
		'Print', 
		brand_table.sub_industry_id AS sub_industry_id,
		company_name
		FROM mediahouse,print_table, brand_table, user_table 
		WHERE print_table.$brand_query 
		AND brand_table.company_id = user_table.company_id 
		AND brand_table.brand_id=print_table.brand_id 
		and brand_table.country_id=$country_id
		AND brand_table.company_id IN ($companyidlist)
		AND print_table.media_house_id=mediahouse.media_house_id 
		AND print_table.ave>0 
		AND date between '$sqlstartdate' AND '$sqlenddate'  order by date asc";
        $insertsql = Yii::app()->db3->createCommand($print_sql)->execute();

        $file = CompanyMediaComparison::HtmlRecords($temp_table,$currency,$linkurl,$period);
		return $file;
	}

	public static function HtmlRecords($temp_table,$currency,$linkurl,$period){
		ini_set('memory_limit', '1024M');
		$PHPExcel = new PHPExcel();
        // $title = "Reelforge Industry Summary Report";

        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $PHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("D5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("E5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("F5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("G5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("H5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("I5")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("J5")->applyFromArray($styleArray);

	    $PHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'Reelforge Systems')
		->setCellValue('A2', 'Company Media Comparison')
		->setCellValue('A3', 'Summary Report for the Period - '.$period)
		->setCellValue('A5', 'Company Name')
		->setCellValue('B5', 'Print')
		->setCellValue('C5', 'TV')
		->setCellValue('D5', 'Radio')
		->setCellValue('E5', 'Total');

		$audio_icon = Yii::app()->request->baseUrl .'/images/play_icon.jpeg';
		$video_icon = Yii::app()->request->baseUrl .'/images/vid_icon.jpg';

		$companysql="SELECT DISTINCT company_name FROM $temp_table ORDER BY company_name ASC";

		// $tabledata = CompanyMediaComparison::SummaryTableHead();

		if($data = Yii::app()->db3->createCommand($companysql)->queryAll())
        {
            $count = 6;
            foreach ($data as $elements) {
            	$cname = $elements['company_name'];
            	$printsql = 'SELECT sum(rate) AS print_rate FROM '.$temp_table.' WHERE company_name="'.$cname.'" AND station_type="print"';
            	if($printdata = Yii::app()->db3->createCommand($printsql)->queryRow()){
            		$print_rate = $printdata['print_rate'];
            	}else{
            		$print_rate = 0;
            	}

            	$radiosql = 'SELECT sum(rate) AS radio_rate FROM '.$temp_table.' WHERE company_name="'.$cname.'" AND station_type="radio"';
            	if($radiodata = Yii::app()->db3->createCommand($radiosql)->queryRow()){
            		$radio_rate = $radiodata['radio_rate'];
            	}else{
            		$radio_rate = 0;
            	}

            	$tvsql = 'SELECT sum(rate) AS tv_rate FROM '.$temp_table.' WHERE company_name="'.$cname.'" AND station_type="tv"';
            	if($tvdata = Yii::app()->db3->createCommand($tvsql)->queryRow()){
            		$tv_rate = $tvdata['tv_rate'];
            	}else{
            		$tv_rate = 0;
            	}

             	$total_rate = $print_rate + $radio_rate + $tv_rate;

                $PHPExcel->getActiveSheet()
                ->setCellValue("A$count", $cname)
                ->setCellValue("B$count", $print_rate)
                ->setCellValue("C$count", $tv_rate)
                ->setCellValue("D$count", $radio_rate)
                ->setCellValue("E$count", $total_rate);
                // $tabledata .= "<tr>
                // <td>".$cname."</td>
                // <td style='text-align:right'>".$print_rate."</td>
                // <td style='text-align:right'>".$tv_rate."</td>
                // <td style='text-align:right'>".$radio_rate."</td>
                // <td style='text-align:right'>".$total_rate."</td>
                // </tr>";
                $count++;
            }
        }
        // $tabledata .= "</table>";

        unset($styleArray);unset($styleArray2);
        $PHPExcel->getActiveSheet()->setTitle('Company Media Comparison');
        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/summaryspends/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = 'Reelforge_Systems_Industry_Report_'.date("Ymdhis").'_'.$filename.'.xlsx';
        $objWriter->save($upload_path.$filename);

        $fileurl = Yii::app()->createUrl("docs/summaryspends/excel").'/'.$filename;

        $filedata = "<p><a href='$fileurl' class='btn btn-success btn-xs pdf-excel' target='_blank'><i class='fa fa-file-pdf-o'></i> Download Excel</a></p>";
        $exportdata = $filedata;
        return $exportdata;
	}

	public static function SummaryTableHead()
	{
		$tablehead = "<table id='dt_basic' class='table table-condensed table-bordered table-hover'>
		<thead><tr>
		<td><strong>Company Name</strong></td>
		<td><strong><div align='right'>Print</div></strong></td>
		<td><strong><div align='right'>TV</div></strong></td>
		<td><strong><div align='right'>Radio</div></strong></td>
		<td><strong><div align='right'>Total</div></strong></td>
		</thead>";
		return $tablehead;
	}
}
<?php

/**
* IndustrySummaries Component Class
* Using this class for Miscellaneous Reports
* DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
* 
* @package     Anvil
* @subpackage  Components
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

/**
** 
**/
class IndustrySummaries{

    public static function IndustryPOFTempTable()
    {
        /**
        * Create Temp table 
        */
        $temp_table="anvil_summary_temp_" . date("Ymdhis");
        $temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
        `id` INT  AUTO_INCREMENT PRIMARY KEY ,
        `auto_id` INT,
        `brand_id` INT  ,
        `entry_type_id` INT  ,
        `incantation_id` INT  ,
        `station_id` INT  ,
        `date` varchar(30)  ,
        `time` varchar(30)  ,
        `comment` varchar(30)  ,
        `rate` INT  ,
        `brand_name` varchar(255),
        `entry_type` varchar(255),
        `incantation_name` varchar(255),
        `duration` varchar(255),
        `file` varchar(255),
        `video_file` varchar(255),
        `station_name` varchar(255),
        `station_type` varchar(255),
        `company_name` varchar(255),
        `industry` varchar(255),
        `sub_industry_id` varchar(255)
        ) ENGINE = MYISAM ;";
        Yii::app()->db3->createCommand($temp_sql)->execute();

        return $temp_table;
    }

    public static function GetRecords($startdate,$enddate,$industry,$subindustries,$brand_query,$country_id){
		if(Yii::app()->user->country_code=='KE'){
		    $linkurl = 'beta';
		}else{
		    $linkurl = strtolower(Yii::app()->user->country_code);
		}
		if($currency = Country::model()->find('country_code=:a', array(':a'=>Yii::app()->user->country_code))){
	        $currency = $currency->currency;
	    }else{
	        $currency = 'KSH';
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
		AND print_table.media_house_id=mediahouse.media_house_id 
		AND print_table.ave>0 
		AND date between '$sqlstartdate' AND '$sqlenddate'  order by date asc";
        $insertsql = Yii::app()->db3->createCommand($print_sql)->execute();

        $file = IndustrySummaries::HtmlRecords($temp_table,$currency,$linkurl,$period);
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
		->setCellValue('A2', 'Industry Summary Report')
		->setCellValue('A3', 'Summary Report for the Period - '.$period)
		->setCellValue('A5', 'Company Name')
		->setCellValue('B5', 'Brand Name')
		->setCellValue('C5', 'Time')
		->setCellValue('D5', 'Month')
		->setCellValue('E5', 'Year')
		->setCellValue('F5', 'Category')
		->setCellValue('G5', 'Ad Type')
		->setCellValue('H5', 'Station Type')
		->setCellValue('I5', 'Station Name')
		->setCellValue('J5', 'Rate');

		$audio_icon = Yii::app()->request->baseUrl .'/images/play_icon.jpeg';
		$video_icon = Yii::app()->request->baseUrl .'/images/vid_icon.jpg';

		$electronic_sql="SELECT company_name, date,  brand_name, time, sub_industry_name ,station_name,station_type, sum(rate)  as rate, entry_type  FROM $temp_table INNER JOIN sub_industry ON  $temp_table.sub_industry_id = sub_industry.auto_id GROUP BY brand_name, date, station_name  ORDER BY date ASC";

		$tabledata = IndustrySummaries::SummaryTableHead();

		if($data = Yii::app()->db3->createCommand($electronic_sql)->queryAll())
        {
            $count = 6;
            foreach ($data as $elements) {
                $PHPExcel->getActiveSheet()
                ->setCellValue("A$count", $elements['company_name'])
                ->setCellValue("B$count", $elements['brand_name'])
                ->setCellValue("C$count", $elements['time'])
                ->setCellValue("D$count", date('M', strtotime($elements['date'])))
                ->setCellValue("E$count", date('Y', strtotime($elements['date'])))
                ->setCellValue("F$count", ucwords(strtolower($elements['sub_industry_name'])))
                ->setCellValue("G$count", ucwords(strtolower($elements['entry_type'])))
                ->setCellValue("H$count", ucwords(strtolower($elements['station_type'])))
                ->setCellValue("I$count", ucwords(strtolower($elements['station_name'])))
                ->setCellValue("J$count", number_format((float)$elements['rate']));
                $tabledata .= "<tr>
                <td>".$elements['company_name']."</td>
                <td>".$elements['brand_name']."</td>
                <td>".$elements['time']."</td>
                <td>".date('M', strtotime($elements['date']))."</td>
                <td>".date('Y', strtotime($elements['date']))."</td>
                <td>".ucwords(strtolower($elements['sub_industry_name']))."</td>
                <td>".ucwords(strtolower($elements['entry_type']))."</td>
                <td>".ucwords(strtolower($elements['station_type']))."</td>
                <td>".ucwords(strtolower($elements['station_name']))."</td>
                <td style='text-align:right'>".number_format((float)$elements['rate'])."</td>
                </tr>";
                $PHPExcel->getActiveSheet()->getStyle("A$count")->applyFromArray($styleArray);
                $PHPExcel->getActiveSheet()->getStyle("A$count")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
                $count++;
            }
        }
        $tabledata .= "</table>";

        unset($styleArray);unset($styleArray2);
        $PHPExcel->getActiveSheet()->setTitle('Industry Summary');
        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/summaryspends/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = 'Reelforge_Systems_Industry_Report_'.date("Ymdhis").'_'.$filename.'.xlsx';
        $objWriter->save($upload_path.$filename);

        $fileurl = Yii::app()->createUrl("docs/summaryspends/excel").'/'.$filename;

        $filedata = "<p><a href='$fileurl' class='btn btn-success btn-xs pdf-excel' target='_blank'><i class='fa fa-file-pdf-o'></i> Download Excel</a></p>";
        $exportdata = $filedata.$tabledata;
        return $exportdata;
	}

	/* 
	*
	* @return  Return Industry Summaries
	* @throws  InvalidArgumentException
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/
	
	public static function SummaryTableHead()
	{
		$tablehead = "<table id='dt_basic' class='table table-condensed table-bordered table-hover'>
		<thead><tr>
		<td><strong>Company Name</strong></td>
		<td><strong>Brand Name</strong></td>
		<td><strong>Time</strong></td>
		<td><strong>Month</strong></td>
		<td><strong>Year</strong></td>
		<td><strong>Category</strong></td>
		<td><strong>Ad Type</strong></td>
		<td><strong>Vehicle</strong></td>
		<td><strong>Medium</strong></td>
		<td><strong><div align='right'>Rate</div></strong></td></tr></thead>";
		return $tablehead;
	}

	public static function InvoiceRates($agency_id)
	{
		$orderByQuery = " ORDER BY  inv_date_created desc";

		$invoice_sql = 'SELECT * FROM invoice, brand_table WHERE invoice.brand_id=brand_table.brand_id AND invoice.agency_id='.$agency_id.' '.$orderByQuery;
		if($invoices = Yii::app()->db3->createCommand($invoice_sql)->queryAll()){
			$table = IndustrySummaries::InvoiceTableHead();
			foreach ($invoices as $key) {
				$table .= '<tr>';
    			$table .= '<td>'.$brand_name = $key['brand_name'].'</td>';
    			$table .= '<td>'.$date_created = $key['inv_date_created'].'</td>';
    			$table .= '<td>'.$date_range  = $key['inv_start_date'].' - '.$key['inv_end_date'].'</td>';
    			$table .= '<td></td>';
    			$table .= '<td></td>';
    			$table .= '</tr>';
			}
			echo $table;
		}
	}

	public static function InvoiceTableHead()
	{
		$tablehead = "<table id='dt_basic' class='table table-condensed table-bordered table-hover'>
		<thead><tr>
		<td><strong>Brand Name</strong></td>
		<td><strong>Date Generated</strong></td>
		<td><strong>Date Range</strong></td>
		<td><strong>Edit</strong></td>
		<td><strong>Delete</strong></td></tr></thead>";
		return $tablehead;
	}

	public static function SummaryExcel($array,$excel_title)
	{
	    $PHPExcel = new PHPExcel();
	    $title = $excel_title;
        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Industry Reports")
        ->setSubject("Reelforge Anvil Industry Reports")
        ->setDescription("Reelforge Anvil Industry Reports");
        $sheet_index = 0;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', 'Industry Summary Report')
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $title)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', ' ')
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Brand Name')
        ->setCellValue('B6', 'Company Name')
        ->setCellValue('C6', 'Month')
        ->setCellValue('D6', 'Year')
        ->setCellValue('E6', 'Category')
        ->setCellValue('F6', 'Vehicle')
        ->setCellValue('G6', 'Media')
        ->setCellValue('H6', 'Rate');

        $styleArray = array('font'  => array('bold'  => true));
        $styleArray2 = array('font'  => array('bold'  => true,'width'  => 100),'alignment' => array('wrap'=> false));
        $cellstyleArray1 = array('font'  => array('width'  => 30));
        $cellstyleArray2 = array('font'  => array('width'  => 15));

        $PHPExcel->getActiveSheet()->getStyle("A1")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A2")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A3")->applyFromArray($styleArray2);
        $PHPExcel->getActiveSheet()->getStyle("A4")->applyFromArray($styleArray2);
        
        $PHPExcel->getActiveSheet()->getStyle("A6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("B6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("C6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("D6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("E6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("F6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("G6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->getStyle("H6")->applyFromArray($styleArray);
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($array as $key) {
			$brand_name 	=	$key["brand_name"];
			$company_name 	= 	$key["company_name"];
			$month 			= 	$key["month"];
			$year 			= 	$key["year"];
			$industry 		= 	$key["industry"];
			$station_type 	= 	$key["station_type"];
			$station_name	=	$key["station_name"];
			$rate 			= 	$key["rate"];

			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $brand_name)
			->setCellValue("B$count", $company_name)
			->setCellValue("C$count", $month)
			->setCellValue("D$count", $year)
			->setCellValue("E$count", $industry)
			->setCellValue("F$count", $station_type)
			->setCellValue("G$count", $station_name)
			->setCellValue("H$count", $rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("Industry Summary Reports");
        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'_industry_summary_report.xlsx';
        $objWriter->save($upload_path.$filename);
		return $filename;
	}

	
}
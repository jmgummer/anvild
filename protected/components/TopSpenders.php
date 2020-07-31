<?php

/**
* TopSpenders Component Class
* This Class Is Used To Return The Top Spenders
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

class TopSpenders{

	public static function CompanySpendsTable($title,$array)
	{
		$return_array = array();
		$excel_array = array();
		$data = '';
		$strXML  = "";
		$strXML .= "<chart exportEnabled='1' exportAtClient='0'  exportHandler='http://www.reelforge.com/FusionCharts/FusionCharts/ExportHandlers/PHP/FCExporter.php'  exportAction='download' caption='$title' xAxisName='Company' yAxisName='".Yii::app()->user->country_code."' showValues='0'    formatNumberScale='0' showBorder='1'  exportEnabled='1' exportAtClient='0'  exportHandler='http://localhost/FusionCharts/FusionCharts/ExportHandlers/PHP/FCExporter.php'  exportAction='download'>";

		$x=0;
		$excelcount = 0;
		$data .= TopSpenders::CompanySpendsTableHead();
		foreach ($array as $key) {
			$this_company_name=htmlspecialchars($key["company_name"]) ;  
			$this_company_id=$key["company_id"] ;  
			$this_rate=$key["rate"];   
			$x++;
			$data_company='';

			if($x<=20){
				$this_company_name=str_replace("&amp;","and",$this_company_name);
				$this_company_name=str_replace("&","and",$this_company_name);
				$data_company.=$this_company_id."*";
				$strXML .= "<set label='" . substr($this_company_name,0,15) . " (".number_format($this_rate).")' value='" . $this_rate ."' />";
			} 	
			$data.="<tr>";
			$data.="<td>" . $x . "</td>";
			$data.="<td><a title='$this_company_name'>" . $this_company_name . "</a></td>"; ;
			$data.="<td><div align='right'>" . number_format($this_rate,0) . "</div></td>"; ;
			$data.="</tr>";

			$excel_array[$excelcount]['company_name']=$this_company_name;
			$excel_array[$excelcount]['rate']=$this_rate;
			$excelcount++;
		}
		$data.='</table>';
		$strXML .= "</chart>";
		$return_array['tabledata'] = $data;
		$return_array['swfdata'] = $strXML;
		$return_array['exceldata'] = $excel_array;
		return $return_array;
	}

	public static function CompanySpendsTableHead()
	{
		$data='<table id="dt_basic" class="table table-condensed table-bordered table-hover">';	
		$data.="<tr>";
		$data.="<td>&nbsp;</td>";
		$data.="<td><strong>Company</strong></td>"; ;
		$data.="<td><div align='right'><strong>Spend(".Yii::app()->user->country_code.")</strong></div></td>"; ;
		$data.="</tr>";
		return $data;
	}

	public static function BrandSpendsTable($title,$array)
	{
		$return_array = array();
		$excel_array = array();
		$data = '';
		$strXML  = "";
		$strXML .= "<chart exportEnabled='1' exportAtClient='0'  exportHandler='http://www.reelforge.com/FusionCharts/FusionCharts/ExportHandlers/PHP/FCExporter.php'  exportAction='download' caption='$title' xAxisName='Company' yAxisName='".Yii::app()->user->country_code."' showValues='0'    formatNumberScale='0' showBorder='1'  exportEnabled='1' exportAtClient='0'  exportHandler='http://localhost/FusionCharts/FusionCharts/ExportHandlers/PHP/FCExporter.php'  exportAction='download'>";

		$x=0;
		$excelcount = 0;
		$data .= TopSpenders::BrandSpendsTableHead();
		foreach ($array as $key) {
			$this_brand_name=htmlspecialchars($key["brand_name"]) ;  
			$this_company_name=htmlspecialchars($key["company_name"]) ;  
			$this_rate=$key["rate"];   
			$x++;

			if($x<=20){
				$this_company_name=str_replace("&amp;","and",$this_company_name);
				$this_company_name=str_replace("&","and",$this_company_name);
				$this_brand_name=str_replace("&amp;","and",$this_brand_name);
				$this_brand_name=str_replace("&","and",$this_brand_name);
				$this_brand_name=str_replace("'","",$this_brand_name);
				
				$strXML .= "<set label='" . substr($this_brand_name,0,15) . " (".number_format($this_rate).")' value='" . $this_rate ."' ></set>";
			}
			$data.="<tr>";
			$data.="<td valign='top'>" . $x . "</td>";
			$data.="<td valign='top'><a title='$this_company_name'>" . $this_brand_name . "</a></td>";
			$data.="<td valign='top'><div align='right'>" . number_format($this_rate,0) . "</div></td>"; ;
			$data.="</tr>";

			$excel_array[$excelcount]['brand_name']=$this_brand_name;
			$excel_array[$excelcount]['rate']=$this_rate;
			$excelcount++;
		}
		$data.='</table>';
		$strXML .= "</chart>";
		$return_array['tabledata'] = $data;
		$return_array['swfdata'] = $strXML;
		$return_array['exceldata'] = $excel_array;
		return $return_array;
	}

	public static function BrandSpendsTableHead()
	{
		$data='<table id="dt_basic" class="table table-condensed table-bordered table-hover">';	
		$data.="<tr>";
		$data.="<td>&nbsp;</td>";
		$data.="<td><strong>Brand</strong></td>"; 
		$data.="<td><div align='right'><strong>Spend(".Yii::app()->user->country_code.")</strong></div></td>"; 
		$data.="</tr>";
		return $data;
	}

	public static function TotalMediaSpends($title,$array)
	{
		$return_array = array();
		$data = '';
		$strXML  = "";
		$strXML .= "<chart exportEnabled='1' exportAtClient='0'  exportHandler='http://www.reelforge.com/FusionCharts/FusionCharts/ExportHandlers/PHP/FCExporter.php'  exportAction='download' caption='$title' xAxisName='Company' yAxisName='".Yii::app()->user->country_code."' showValues='0'    formatNumberScale='0' showBorder='1'  exportEnabled='1' exportAtClient='0'  exportHandler='http://localhost/FusionCharts/FusionCharts/ExportHandlers/PHP/FCExporter.php'  exportAction='download'>";

		$x=0;
		foreach ($array as $key) {
			$this_media_type=htmlspecialchars($key["station_type"]) ;  
		 	$this_rate=$key["rate"];  
			$strXML .= "<set label='" . ucfirst($this_media_type) . " (".number_format($this_rate).")' value='" . $this_rate ."' />";
		}	
		$strXML .= "</chart>";
		$return_array['swfdata'] = $strXML;
		return $return_array;
	}

	public static function MediaSpendsTable($array)
	{
		$return_array = array();
		$data = '';
		$x=0;
		$data .= TopSpenders::MediaSpendsTableHead();
		$excel_array = array();
		foreach ($array as $key) {
			$this_media_type=htmlspecialchars($key["station_name"]) ;  
			$excel_array[$x]['station_name'] = $this_media_type;
			$this_rate=$key["rate"]; 
			$excel_array[$x]['station_rate'] = $this_rate;
			$x++;
			$this_company_name=str_replace("&amp;","and",$this_media_type);
			$this_company_name=str_replace("&","and",$this_company_name);
			$data.="<tr>";
			$data.="<td valign='top'>" . $x . "</td>";
			$data.="<td valign='top'><a title='$this_media_type'>" . $this_media_type . "</a></td>"; ;
			$data.="<td valign='top'><div align='right'>" . number_format($this_rate,0) . "</div></td>"; ;
			$data.="</tr>";
			
		}
		$data.='</table>';
		$return_array['exceldata'] = $excel_array;
		$return_array['tabledata'] = $data;
		return $return_array;
	}

	public static function PrintMediaSpendsTable($array)
	{
		$return_array = array();
		$data = '';
		$x=0;
		$data .= TopSpenders::MediaSpendsTableHead();
		$excel_array = array();
		foreach ($array as $key) {
			$this_media_type=htmlspecialchars($key["station_name"]) ; 
			$excel_array[$x]['station_name'] = $this_media_type; 
			$this_rate=$key["rate"];  
			$excel_array[$x]['station_rate'] = $this_rate; 
			$x++;
			$data.="<tr>";
			$data.="<td valign='top'>" . $x . "</td>";
			$data.="<td valign='top'><a TITLE='$this_media_type'>" . $this_media_type . "</a></td>"; ;
			$data.="<td valign='top'><div align='right'>" . number_format($this_rate,0) . "</div></td>"; ;
			$data.="</tr>";
			
		}
		$data.='</table>';
		$return_array['exceldata'] = $excel_array;
		$return_array['tabledata'] = $data;
		return $return_array;
	}

	public static function MediaSpendsTableHead()
	{
		$mylist_header='<table id="dt_basic" class="table table-condensed table-bordered table-hover">';	
		$mylist_header.="<tr>";
		$mylist_header.="<td>&nbsp;</td>";
		$mylist_header.="<td><strong>Media Name</strong></td>"; 
		$mylist_header.="<td><div align='right'><strong>Spend(".Yii::app()->user->country_code.")</strong></div></td>"; ;
		$mylist_header.="</tr>";
		return $mylist_header;
	}

	public static function TabbedTables($temp_table,$industry)
	{
		$tablearray = array();
		$radio_excel= array(); $tv_excel= array(); $print_excel= array(); 
		$sql_radio="SELECT station_name, sum(rate) as rate  from $temp_table where station_type='radio' and rate>0  group by station_name order by rate desc";
		$sql_tv="SELECT station_name, sum(rate) as rate  from $temp_table where station_type='tv' and rate>0  group by station_name order by rate desc";
		$sql_print="SELECT station_name , sum(rate) as rate  from $temp_table where station_type='print' and rate>0  group by station_name order by rate desc";
		
		$packaged_data = '';
		$packaged_data .= TopSpenders::Tabs();
		/* Radio */
		$packaged_data .= '<div class="tab-pane fade active in" id="1">';
		if($radio_query = Yii::app()->db3->createCommand($sql_radio)->queryAll()){
			$table = TopSpenders::MediaSpendsTable($radio_query);
			$radio_excel = $table['exceldata'];
			$packaged_data .= $table['tabledata'];
		}
		$packaged_data .= '</div>';
		/* TV */
		$packaged_data .= '<div class="tab-pane fade" id="2">';
		if($tv_query = Yii::app()->db3->createCommand($sql_tv)->queryAll()){
			$table = TopSpenders::MediaSpendsTable($tv_query);
			$tv_excel = $table['exceldata'];
			$packaged_data .= $table['tabledata'];
		}
		$packaged_data .= '</div>';
		/* Print */
		$packaged_data .= '<div class="tab-pane fade" id="3">';
		if($print_query = Yii::app()->db3->createCommand($sql_print)->queryAll()){
			$table = TopSpenders::PrintMediaSpendsTable($print_query);
			$print_excel = $table['exceldata'];
			$packaged_data .= $table['tabledata'];
		}
		
		$packaged_data .= '</div>';
		$packaged_data .= '</div>';
		$tablearray['data'] = $packaged_data;
		$tablearray['file'] = SummarySpendsExcel::MediaExcel($radio_excel,$tv_excel,$print_excel,$industry);

		return $tablearray; 

	}

	public static function Tabs()
	{
		$data = '';
		$data .= '<div class="widget-body"><ul id="tabs" class="nav nav-tabs bordered">';
		$data .= '<li class="active"><a href="#1" data-toggle="tab">Radio</a></li>';
		$data .= '<li><a href="#2" data-toggle="tab">TV</a></li>';
		$data .= '<li><a href="#3" data-toggle="tab">Print</a></li>';
		$data .= '</ul>';
		$data .= '<div id="myTabContent1" class="tab-content padding-10">';
		return $data;
	}

	public static function IndustryGraphsQueries($temp_table,$industry)
	{
		$grapharray = array();
		$radio_excel= array(); 
		$tv_excel= array(); 
		$print_excel= array(); 
		$total_excel = array();

		$sql_radio = "SELECT $temp_table.sub_industry_id as sub_industry_id, sum(rate) as rate,sub_industry_name,industry_name,industry.industry_id 
		FROM 
		$temp_table, sub_industry, industry 
		where station_type='radio' 
		and sub_industry.auto_id=$temp_table.sub_industry_id
		and industry.industry_id=sub_industry.industry_id
		group by industry.industry_id ORDER BY rate DESC";

		$sql_tv =	"SELECT $temp_table.sub_industry_id as sub_industry_id, sum(rate) as rate,sub_industry_name,industry_name,industry.industry_id 
		FROM 
		$temp_table, sub_industry, industry 
		where station_type='tv' 
		and sub_industry.auto_id=$temp_table.sub_industry_id
		and industry.industry_id=sub_industry.industry_id
		group by industry.industry_id ORDER BY rate DESC";
		
		$sql_print	=	"SELECT $temp_table.sub_industry_id as sub_industry_id, sum(rate) as rate,sub_industry_name,industry_name,industry.industry_id 
		FROM 
		$temp_table, sub_industry, industry 
		where station_type='print' 
		and sub_industry.auto_id=$temp_table.sub_industry_id
		and industry.industry_id=sub_industry.industry_id
		group by industry.industry_id ORDER BY rate DESC";

		$sql_all 	= 	"SELECT $temp_table.sub_industry_id as sub_industry_id, sum(rate) as rate,sub_industry_name,industry_name,industry.industry_id 
		FROM 
		$temp_table, sub_industry, industry 
		where sub_industry.auto_id=$temp_table.sub_industry_id
		and industry.industry_id=sub_industry.industry_id
		group by industry.industry_id ORDER BY rate DESC";
		$packaged_data = '';
		if($radio_query = Yii::app()->db3->createCommand($sql_radio)->queryAll()){
			$title = "Radio Graph"; 
			if($graph = TopSpenders::IndustryGraphs($title,$radio_query)){
				$packaged_data .= '<div class="row-fluid clearfix">';
				$packaged_data .= '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix"><h2><span class="pull-left"><strong>Breakdown of Summary Spends by Radio</strong></span><span class="pull-right"></span></h2></header></div>';
				$chart_name = "Radio_Graph"; 
				$inda_rates1 = $graph['rate_industry'];
				$packaged_data .= '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
				if($strXML = $graph['swfdata']){
					$charty = new FusionCharts;
					$packaged_data .= FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Bar2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
				}
				$packaged_data .= '</div>';
				$packaged_data .= '</div><br>';
				$radio_excel = $graph['exceldata'];
			}else{
				$packaged_data .= 'No Chart Data';
			}
		}

		if($tv_query = Yii::app()->db3->createCommand($sql_tv)->queryAll()){
			$title = "TV Graph"; 
			if($graph = TopSpenders::IndustryGraphs($title,$tv_query)){
				$packaged_data .= '<div class="row-fluid clearfix">';
				$packaged_data .= '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix"><h2><span class="pull-left"><strong>Breakdown of Summary Spends by TV</strong></span><span class="pull-right"></span></h2></header></div>';
				$chart_name = "TV_Graph"; 
				$inda_rates2 = $graph['rate_industry'];
				$packaged_data .= '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
				if($strXML = $graph['swfdata']){
					$charty = new FusionCharts;
					$packaged_data .= FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Bar2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
				}
				$packaged_data .= '</div>';
				$packaged_data .= '</div><br>';
				$tv_excel = $graph['exceldata'];
			}else{
				$packaged_data .= 'No Chart Data';
			}
		}

		if($print_query = Yii::app()->db3->createCommand($sql_print)->queryAll()){
			$title = "Print Graph"; 
			if($graph = TopSpenders::IndustryGraphs($title,$print_query)){
				$packaged_data .= '<div class="row-fluid clearfix">';
				$packaged_data .= '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix"><h2><span class="pull-left"><strong>Breakdown of Summary Spends by Print</strong></span><span class="pull-right"></span></h2></header></div>';
				$chart_name = "Print_Graph"; 
				$inda_rates3 = $graph['rate_industry'];
				$packaged_data .= '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
				if($strXML = $graph['swfdata']){
					$charty = new FusionCharts;
					$packaged_data .= FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Bar2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
				}
				$packaged_data .= '</div>';
				$packaged_data .= '</div><br>';
				$print_excel = $graph['exceldata'];
			}else{
				$packaged_data .= 'No Chart Data';
			}
		}
		
		if($total_query = Yii::app()->db3->createCommand($sql_all)->queryAll()){
			$title = "Total Cover Graph"; 
			if($graph = TopSpenders::IndustryGraphs($title,$total_query)){
				$packaged_data .= '<div class="row-fluid clearfix">';
				$packaged_data .= '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix"><h2><span class="pull-left"><strong>Total Breakdown of Summary Spends by all Media Types</strong></span><span class="pull-right"></span></h2></header></div>';
				$chart_name = "total_Graph"; 
				$inda_rates3 = $graph['rate_industry'];
				$packaged_data .= '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
				if($strXML = $graph['swfdata']){
					$charty = new FusionCharts;
					$packaged_data .= FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Bar2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
				}
				$packaged_data .= '</div>';
				$packaged_data .= '</div><br>';
				$total_excel = $graph['exceldata'];
			}else{
				$packaged_data .= 'No Chart Data';
			}
		}
		$grapharray['data'] = $packaged_data;
		$grapharray['file'] = SummarySpendsExcel::IndustryExcel($radio_excel,$tv_excel,$print_excel,$total_excel,$industry);
		return $grapharray;
	}


	public static function IndustryGraphs($title,$array)
	{
		$return_array = array();
		$rate_industry = array();
		$industry_excel = array();
		$strXML ="<chart exportEnabled='1' exportAtClient='0'  exportHandler='http://www.reelforge.com/FusionCharts/FusionCharts/ExportHandlers/PHP/FCExporter.php'  exportAction='download' caption=' ' xAxisName='Industry' yAxisName='".Yii::app()->user->country_code."' showValues='0' formatNumberScale='0' showBorder='1'>";
		$x=0;
		foreach ($array as $key) {
			$this_industry_name=htmlspecialchars($key["industry_name"]) ; 
			$this_industry_name=str_replace("&amp;","and",$this_industry_name);
			$this_industry_name=str_replace("&","and",$this_industry_name);
			$this_industry_name=str_replace("  ",' ',$this_industry_name);	
			$industry_excel[$x]['industry_name'] = $this_industry_name;
			$this_rate=$key["rate"];
			$industry_excel[$x]['industry_rate'] = $this_rate; 
			$strXML .= "<set label='" . $this_industry_name ."' value='" . $this_rate ."' />"; 
			$rate_industry[$x]['id']=$key['industry_id'];
			$rate_industry[$x]['industry_name']=$this_industry_name;
			$rate_industry[$x]['rate']=$this_rate;
			$x++;
		}	
		$strXML .= "</chart>";
		$return_array['swfdata'] = $strXML;
		$return_array['rate_industry'] = $rate_industry;
		$return_array['exceldata'] = $industry_excel;
		return $return_array;
	}

	public static function TopSpendersBrandExcel($array,$excel_title,$excel_industry,$client)
	{
	    $PHPExcel = new PHPExcel();
	    $title = $excel_title;
        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        $sheet_index = 0;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $excel_industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Brand Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

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
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($array as $key) {
			$brand_name=$key["brand_name"];
			$brand_rate=number_format($key["rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $brand_name)
			->setCellValue("B$count", $brand_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("Top Spenders By Brand");
        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/topspenders/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'_topspenders_brand.xls';
        $objWriter->save($upload_path.$filename);
		return $filename;
	}

	public static function TopSpendersCompanyExcel($array,$excel_title,$excel_industry,$client)
	{
	    $PHPExcel = new PHPExcel();
	    $title = $excel_title;
        $PHPExcel->getProperties()->setCreator("Reelforge Systems")
        ->setTitle("Reelforge Anvil Proof of Flight Reports")
        ->setSubject("Reelforge Anvil Proof of Flight Reports")
        ->setDescription("Reelforge Anvil Proof of Flight Reports");
        $sheet_index = 0;
        $PHPExcel->createSheet(NULL, $sheet_index);
        $PHPExcel->setActiveSheetIndex($sheet_index)
        ->setCellValue('A1', 'Reelforge Report')
        ->setCellValue('B1', ' ')
        ->setCellValue('C1', ' ')
        ->setCellValue('A2', $title)
        ->setCellValue('B2', ' ')
        ->setCellValue('C2', ' ')
        ->setCellValue('A3', $excel_industry)
        ->setCellValue('B3', ' ')
        ->setCellValue('C3', ' ')
        ->setCellValue('A4', 'Client : '.$client)
        ->setCellValue('B4', ' ')
        ->setCellValue('C4', ' ')
        ->setCellValue('A5', ' ')
        ->setCellValue('B5', ' ')
        ->setCellValue('C5', ' ')
        ->setCellValue('A6', 'Company Name')
        ->setCellValue('B6', 'Spend ('.Yii::app()->user->country_code.')');

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
        $PHPExcel->getActiveSheet()->mergeCells('A1:Z1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:Z2');
        $PHPExcel->getActiveSheet()->mergeCells('A3:Z3');
        $PHPExcel->getActiveSheet()->mergeCells('A4:Z4');
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(60);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);

        $count = 7;
		$styleArray = array('font' => array('underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE));

        foreach ($array as $key) {
			$brand_name=$key["company_name"];
			$brand_rate=number_format($key["rate"]);
			$PHPExcel->getActiveSheet()
			->setCellValue("A$count", $brand_name)
			->setCellValue("B$count", $brand_rate);
			$count++;
		}
		unset($styleArray);
		$PHPExcel->getActiveSheet()->setTitle("Top Spenders By Company");
        $PHPExcel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $upload_path = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/topspenders/excel/";
        $filename =str_replace(" ","_",Yii::app()->user->company_name);
        $filename = date("Ymdhis").'_'.$filename.'_topspenders_company.xls';
        $objWriter->save($upload_path.$filename);
		return $filename;
	}
}
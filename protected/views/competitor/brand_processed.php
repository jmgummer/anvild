<?php
/**
* Brand Processed File
*
* @package     Anvil
* @subpackage  Views
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/
$level = Yii::app()->user->company_level;
$company_name = Yii::app()->user->company_name;
$this_company_id = Yii::app()->user->company_id;
$this->pageTitle=Yii::app()->name.' | Industry Competitor Reports (Brand Report) ';
$this->breadcrumbs=array('Industry Competitor Reports (Brand Report) '=>array('competitor/brand'));
$currency =Common::CountryCurrency(Yii::app()->user->country_id);
?>
<script src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionCharts.js'; ?>"></script>
<script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionChartsExportComponent.js'; ?>"></script>
<?php
/*
** Required Variables
*/

$industry_id = $_POST['industry'];
$adtype = $_POST['adtype'];
$printtype = $_POST['printtype'];
$country_id = Yii::app()->user->country_id;
$enddate = $_POST['enddate'];
$startdate = $_POST['startdate'];
$sqlstartdate = date('Y-m-d', strtotime($startdate));
$sqlenddate = date('Y-m-d', strtotime($enddate));
$format = 'p';

/* Date Formating Starts Here */

$year_start     = date('Y',strtotime($startdate));  
$month_start    = date('m',strtotime($startdate));  
$day_start      = date('d',strtotime($startdate));
$year_end       = date('Y',strtotime($enddate)); 
$month_end      = date('m',strtotime($enddate)); 
$day_end        = date('d',strtotime($enddate));

/* Create the Temporary Table Now */
$temp_table="anvil_brand_temp".date("Ymhmis");
$temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
`auto_id` INT  AUTO_INCREMENT PRIMARY KEY ,
`brand_id` INT  ,
`company_id` INT  ,
`company_name` varchar(80),
`brand_name`  text,
`rate` BIGINT ,
`station_id` INT  ,
`station_name` varchar(80),
`mediatype`  varchar(100),
`stationtype`  varchar(100)
) ENGINE = MYISAM ;";
Yii::app()->db3->createCommand($temp_sql)->execute();

/* Industry */
if(isset($_POST['industry'])){
	if($industry_name = AnvilIndustry::model()->find('industry_id=:a', array(':a'=>$_POST['industry']))){
		$industry_name = $industry_name->industry_name;
	}else{
		$industry_name = 'Unknown';
	}
}else{
	$error_code = 1;
}
/* Sub Industry */
if(isset($_POST['subindustry'])){
	$subindustry = $_POST['subindustry'];
	if($sub_industry_name = AnvilSubIndustry::model()->find('auto_id=:a', array(':a'=>$_POST['subindustry']))){
		$sub_industry_name = $sub_industry_name->sub_industry_name;
	}else{
		$sub_industry_name = 'Unknown';
	}
}else{
	$error_code = 2;
}
/* Brand Text */
$brand_names = '';
$brand_query = '';
$set_brands = '';
if(isset($_POST['brands'])){
	foreach ($_POST['brands'] as $brand) {
		if($brand_name= BrandTable::model()->find('brand_id=:a', array(':a'=>$brand))){
			$brand_names .= ucwords(strtolower($brand_name->brand_name)).', ';
		}
	}
	$set_brands = implode(', ', $_POST['brands']);
	$brands_query = 'brand_id IN ('.$set_brands.')';
	if($adtype==2){
		$djbrandquery = 'brand_id IN ('.$set_brands.')';
	}
}else{
	$error_code = 3;
}

/* If there are any errors terminate execution at this point and redirect back to the form */
if(isset($error_code)){
    Yii::app()->user->setFlash('warning', "<strong>Error ! Please SELECT at least one from each section </strong>");
    $this->redirect(array('competitor/brand'));
}

/* 
** Electronic Query Starts Here
** Check if Adtype is set else go to print
*/

if(!empty($adtype)){
	if($adtype==1){
		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $data_table="reelforge_sample_"  .$x."_".$my_month;

				// $data_table="reelforge_sample";
			    $sample_sql = "INSERT into $temp_table (brand_id,company_id,company_name,brand_name,rate,station_id,station_name,mediatype,stationtype)
				SELECT 
				$data_table.brand_id,
				brand_table.company_id,
				company_name,
				brand_name,
				rate, 
				$data_table.station_id, 
				station_name,
				'electronic'  as mediatype,
				station.station_type  as stationtype 
				from $data_table,brand_table,user_table,station,djmentions_entry_types 
				where  $data_table.brand_id=brand_table.brand_id
				and $data_table.rate >0  
				and brand_table.company_id=user_table.company_id 
				and $data_table.$brands_query 
				and brand_table.country_id=$country_id and  $data_table.entry_type_id =1 
				and $data_table.station_id = station.station_id and $data_table.active=1 
				AND $data_table.entry_type_id=djmentions_entry_types.entry_type_id
				and station.country_id = $country_id
				and reel_date between '$sqlstartdate' and '$sqlenddate' 
				and rate !=0 
				order by brand_name asc;";
				$insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
			}
		}
	}else{
		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $data_table="reelforge_sample_"  .$x."_".$my_month;

				// $data_table="reelforge_sample";
			    $sample_sql = "INSERT into $temp_table (brand_id,company_id,company_name,brand_name,rate,station_id,station_name,mediatype,stationtype)
				SELECT 
				$data_table.brand_id,
				brand_table.company_id,
				company_name,
				brand_name,
				rate, 
				$data_table.station_id, 
				station_name,
				'electronic'  as mediatype,
				station.station_type  as stationtype  
				from $data_table,brand_table,user_table,station,djmentions_entry_types  
				where $data_table.brand_id=brand_table.brand_id 
				and $data_table.rate >0 
				and brand_table.company_id=user_table.company_id 
				and $data_table.$brands_query 
				and brand_table.country_id=$country_id 
				and $data_table.station_id = station.station_id 
				and $data_table.active=1
				AND $data_table.entry_type_id=djmentions_entry_types.entry_type_id
				and station.country_id = $country_id
				and reel_date between '$sqlstartdate' and '$sqlenddate' 
				and rate !=0 
				order by brand_name asc;";
				$insertsql = Yii::app()->db3->createCommand($sample_sql)->execute();
			}
		}

		for ($x=$year_start;$x<=$year_end;$x++)
		{
		    if($x==$year_start) { $month_start_count=$month_start; } else { $month_start_count='1';}
		    if($x==$year_end) { $month_end_count=$month_end; } else { $month_end_count='12';}

		    $month_start_count=$month_start_count+0;

		    for ($y=$month_start_count;$y<=$month_end_count;$y++)
		    {
		        if($y<10) { $my_month='0'.$y;   } else {  $my_month=$y; }
		        $data_table="djmentions_"  .$x."_".$my_month;
		
				// $data_table="djmentions";
				$mention_sql = "INSERT into $temp_table (brand_id,company_id,company_name,brand_name,rate,station_id,station_name,mediatype,stationtype)
				SELECT 
				$data_table.brand_id,
				brand_table.company_id,
				company_name,
				brand_name,
				rate, 
				$data_table.station_id, 
				station_name,
				'electronic'  as mediatype,
				station.station_type  as stationtype   
				from $data_table,brand_table,user_table,station,djmentions_entry_types  
				where $data_table.brand_id=brand_table.brand_id 
				and $data_table.rate >0 
				and brand_table.company_id=user_table.company_id 
				and $data_table.$brands_query 
				and brand_table.country_id=$country_id 
				and $data_table.active=1
				AND $data_table.entry_type_id=djmentions_entry_types.entry_type_id
				and $data_table.station_id = station.station_id 
				and station.country_id = $country_id 
				and date between '$sqlstartdate' and '$sqlenddate' 
				and rate !=0 
				order by brand_name asc;";
				$insertsql = Yii::app()->db3->createCommand($mention_sql)->execute();
			}
		}
	}
}

/* Electronic Query Ends Here */

/* Print Query Starts Here */
if(!empty($printtype)) {

	$print_sql = "INSERT into $temp_table (brand_id,company_id,company_name,brand_name,rate,station_id,station_name,mediatype,stationtype)
	SELECT 
	brand_table.brand_id as brand_id,
	brand_table.company_id as company_id,
	company_name,	 
	brand_table.brand_name as brand_name,  
	ave as rate,
	print_table.media_house_id as station_id,
	media_house_list as station_name,
	'print'  as mediatype,
	'print'  as stationtype 
	from brand_table, print_table,user_table,mediahouse 
	where 
	brand_table.brand_id=print_table.brand_id
	and brand_table.company_id=user_table.company_id 
	and print_table.brand_id IN ($set_brands) 
	and brand_table.country_id=$country_id 
	and print_table.media_house_id = mediahouse.media_house_id
	and date between '$sqlstartdate' and '$sqlenddate' 
	and ave !=0   
	order by brand_name asc;";

	$insertsql = Yii::app()->db3->createCommand($print_sql)->execute();
}

/* Print Query Ends Here */

$countsql = "SELECT count(*) FROM $temp_table";
if($recordcount = Yii::app()->db3->createCommand($countsql)->queryAll()!=0){
	echo '<h4><strong>Industry Competitor Reports (Brand Report)</strong></h4>';
	echo '<br>';
	$allbrandssql="SELECT distinct brand_id, brand_name from $temp_table order by brand_name asc";
	$all_brands = Yii::app()->db3->createCommand($allbrandssql)->queryAll();
	$filename = Competitor::BrandPrintExcel($temp_table,$set_brands,$all_brands,$industry_id,$sqlstartdate,$sqlenddate,'print');
	echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/competitor/excel/'.$filename.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p>';
	echo "<hr>";

	/* Start with Print, no big deal here, there's just one  */
	if(!empty($printtype)) { 
		echo '<div class="row-fluid clearfix">';
		echo '<p><strong>Print</strong></p><hr>';
		$chart_name = 'competition_report_print_spend';
		$chart_name2 = 'competition_report_print_spots';
		$x=0;
		$netspend = 0;
		$company_sql="SELECT distinct brand_id, brand_name from $temp_table where mediatype='print' order by rate asc, brand_name asc";
		if($print_brands = Yii::app()->db3->createCommand($company_sql)->queryAll()){
			$brand_array = array();
			$array_counter = 0;
			foreach ($print_brands as $key) {
				$this_total=0;
				$brand_array[$array_counter]['brand_name'] = $key['brand_name'];
				$individual_SELECTs = 'SELECT * FROM '.$temp_table.' WHERE brand_id='.$key['brand_id'].' and mediatype="print" ';
				if($brand_count = Yii::app()->db3->createCommand($individual_SELECTs)->queryAll()){
					$brand_array[$array_counter]['count']= count($brand_count);
					foreach ($brand_count as $key) {
                        $station_totals_total=$key["rate"];
                        if($station_totals_total){
                            $this_total=$this_total+$station_totals_total;
                        }
                    }
                    $brand_array[$array_counter]['totalspend']=$this_total;
				}else{
					$brand_array[$array_counter]['count']=0;
				}
				$array_counter++;
				$netspend =$netspend+$this_total;
			}
			echo '<div class="col-md-12">';

			/* Show Graph of Total Spend */
			echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
			/*if($strXML = FusionCharts::packagePrintBrandSpendXML("Graph Showing Total Spend in $currency", $brand_array,$netspend,$format)){
				$charty = new FusionCharts;
				echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name2, '100%', 400, false, true, true);
			}*/

			echo '<div style="" class="">';
			$companytitle = 'Graph Showing Total Spend in $currency';

			$chart_exists = false;
			$container_name = 'print_competitor_brand';
			$chart = new Highchart();
			$chart->chart->renderTo = "print_competitor_brand";
			$chart->chart->plotBackgroundColor = null;
			$chart->chart->plotBorderWidth = null;
			$chart->chart->plotShadow = false;
			$chart->title->text = '';
			$chart->tooltip->pointFormat = '{series.name}: <b>{this.y:.1f}</b>';
			$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.point.name +'</b>'; }");
			$chart->plotOptions->pie->allowPointSelect = 1;
			$chart->plotOptions->pie->cursor = "pointer";
			$chart->plotOptions->pie->dataLabels->enabled = true;
			$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);
			$chartarray = array();
			$count = 0;
			$others = 0;
			foreach ($brand_array as $key) {  
				$co_value = $key['totalspend'];
		        $co_valueformated = number_format($co_value,2);
		        $co_value2 = ($key['totalspend']/$netspend)*100;
		        $co_value2 = round($co_value2, 2);

				$chartarray[$count] = array( $key['brand_name'].' ('.number_format($co_value2,2).' %)', (int)str_replace(',', '', $co_value2));  
				$count++; if($count>19){ break; }
			}

			$count = 0;
			foreach ($brand_array as $key) {  
				$count++; 
				$co_value = $key['totalspend'];
		        $co_valueformated = number_format($co_value);
		        $co_value2 = ($key['totalspend']/$netspend)*100;
		        $co_value2 = round($co_value2, 2);
				if($count>19){ 
					$bigcount = true; 
					$others = $others + $co_value2; 
				} 
			}

			if(isset($bigcount)){
				$chartarray[20] = array( 'Others ('.number_format($others).')', (int)str_replace(',', '', $others)); 
			}
			
			$chart->series[] = array('type' => 'pie','name' => 'Competitor Spends Industry Brand - Print','data' => $chartarray);
			$chart->credits = array('enabled'=>false);
			if($chart_exists == false){
				$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
				$chart->includeExtraScripts(array('export'));
				$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
				$chart->includeExtraScripts(array('theme'));
				$chart->printScripts();
			}
			echo '<div id="'.$container_name.'"></div>';
			echo '<script type="text/javascript">';
			echo $chart->render("chart1");
	        echo '</script>';
	        echo '</div>';

			echo '</div>';
			/* End Graph of Total Spend */
			
			echo '</div>';
			// echo '<div class="col-md-6">';
			
			// /* Show Graph of Total Spots */
			// echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
			// if($strXML = FusionCharts::packageBrandCompetitorXML("Graph Showing Total Number of Ads", $brand_array)){
			// 	$charty = new FusionCharts;
			// 	echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
			// }
			// echo '</div>';
			// /* End Graph of Total Spots */

			// echo '</div>';
			echo '</div>';
			echo '<br>';
			echo '<div class="row-fluid clearfix">';
			echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix"><h2><span class="pull-left"><strong>Print Analysis</strong></span><span class="pull-right"></span></h2></header></div>';
			echo Competitor::BrandPrintTable($temp_table,$set_brands,$print_brands,$industry_id,$sqlstartdate,$sqlenddate);
			echo '</div>';
		}else{
			echo '<h4>No Results Found</h4>';
		}
	}

	echo '<br><hr>';

	if(!empty($adtype)) { 
		/* Start Row Fluid Electronic */
		echo '<div class="row-fluid clearfix">';
		echo '<p><strong>Electronic</strong></p><hr>';
		$chart_name = 'competition_report_electronic_spots';
		$chart_name2 = 'competition_report_electronic_spend';
		$x=0;
		$netspend = 0;
		$brand_sql="SELECT distinct brand_id,brand_name from $temp_table where mediatype='electronic' order by brand_name asc";
		if($electronic_brands = Yii::app()->db3->createCommand($brand_sql)->queryAll()){
			$brand_array = array();
			$array_counter = 0;
			foreach ($electronic_brands as $key) {
				$this_total=0;
				$brand_array[$array_counter]['brand_name'] = $key['brand_name'];
				$individual_SELECTs = 'SELECT * FROM '.$temp_table.' WHERE brand_id='.$key['brand_id'].' and mediatype="electronic" ';
				if($brand_count = Yii::app()->db3->createCommand($individual_SELECTs)->queryAll()){
					$brand_array[$array_counter]['count']= count($brand_count);
					foreach ($brand_count as $key) {
                        $station_totals_total=$key["rate"];
                        if($station_totals_total){
                            $this_total=$this_total+$station_totals_total;
                        }
                    }
                    $brand_array[$array_counter]['totalspend']=$this_total;
				}else{
					$brand_array[$array_counter]['count']=0;
				}
				$array_counter++;
				$netspend =$netspend+$this_total;
			}
	        /* Start Div */
			echo '<div class="col-md-12">';

			/* Show Graph of Total Spend */
			echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
			/*if($strXML = FusionCharts::packagePrintBrandSpendXML("Graph Showing Total Spend in $currency", $brand_array,$netspend,$format)){
				$charty = new FusionCharts;
				echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name2, '100%', 400, false, true, true);
			}*/

			echo '<div style="" class="">';
			$companytitle = "Graph Showing Total Spend in $currency";

			$chart_exists = false;
			$container_name = 'electronic_competitor_brand';
			$chart = new Highchart();
			$chart->chart->renderTo = "electronic_competitor_brand";
			$chart->chart->plotBackgroundColor = null;
			$chart->chart->plotBorderWidth = null;
			$chart->chart->plotShadow = false;
			$chart->title->text = '';
			$chart->tooltip->pointFormat = '{series.name}: <b>{this.y:.1f}</b>';
			$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.point.name +'</b>'; }");
			$chart->plotOptions->pie->allowPointSelect = 1;
			$chart->plotOptions->pie->cursor = "pointer";
			$chart->plotOptions->pie->dataLabels->enabled = true;
			$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);
			$chartarray = array();
			$count = 0;
			$others = 0;
			foreach ($brand_array as $key) {  
				$co_value = $key['totalspend'];
		        $co_valueformated = number_format($co_value,2);
		        $co_value2 = ($key['totalspend']/$netspend)*100;
		        $co_value2 = round($co_value2, 2);

				$chartarray[$count] = array( $key['brand_name'].' ('.number_format($co_value2,2).' %)', (int)str_replace(',', '', $co_value2));  
				$count++; if($count>19){ break; }
			}

			$count = 0;
			foreach ($brand_array as $key) {  
				$count++; 
				$co_value = $key['totalspend'];
		        $co_valueformated = number_format($co_value);
		        $co_value2 = ($key['totalspend']/$netspend)*100;
		        $co_value2 = round($co_value2, 2);
				if($count>19){ 
					$bigcount = true; 
					$others = $others + $co_value2; 
				} 
			}

			if(isset($bigcount)){
				$chartarray[20] = array( 'Others ('.number_format($others).')', (int)str_replace(',', '', $others)); 
			}
			
			$chart->series[] = array('type' => 'pie','name' => 'Competitor Spends Industry Brand - Electronic','data' => $chartarray);
			$chart->credits = array('enabled'=>false);
			if($chart_exists == false){
				$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
				$chart->includeExtraScripts(array('export'));
				$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
				$chart->includeExtraScripts(array('theme'));
				$chart->printScripts();
			}
			echo '<div id="'.$container_name.'"></div>';
			echo '<script type="text/javascript">';
			echo $chart->render("chart1");
	        echo '</script>';
	        echo '</div>';

			echo '</div>';
			/* End Graph of Total Spend */
			
			echo '</div>';
			// echo '<div class="col-md-6">';
			
			// /* Show Graph of Total Spots */
			// echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
			// if($strXML = FusionCharts::packageBrandCompetitorXML("Graph Showing Total Number of Ads", $brand_array)){
			// 	$charty = new FusionCharts;
			// 	echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
			// }
			// echo '</div>';
			// /* End Graph of Total Spots */

			// echo '</div>';

			/* End Div */
			echo '</div>';
			/* End Row Fluid Electronic */
			echo '<br>';
			echo '<div class="row-fluid clearfix">';
			echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix">
			<h2><span class="pull-left"><strong>Electronic Analysis</strong></span><span class="pull-right"></span></h2></header></div>';
			echo Competitor::BrandElectronicTable($temp_table,$set_brands,$electronic_brands,$industry_id,$sqlstartdate,$sqlenddate);
			echo '</div>';
		}else{
			echo '<h4>No Results Found</h4>';
		}
	}

}else{
	echo '<h4><strong>'.$industry_name.' Industry Competitor Reports</strong></h4>';
	echo '<h4>No Results Found</h4>';
}

?>
<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
</style>
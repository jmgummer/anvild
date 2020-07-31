<?php
/**
* Company Airplay Processed File
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
$this->pageTitle=Yii::app()->name.' | Total Airplay (Company)';
$this->breadcrumbs=array('Total Airplay (Company) Report'=>array('media/companyairplay'));
?>
<script src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionCharts.js'; ?>"></script>
<script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionChartsExportComponent.js'; ?>"></script>
<?php
/*
** Required Variables
*/

$industry_id = $_POST['industry'];
$adtype = $_POST['adtype'];
if(isset($_POST['country'])){
	$country_id = $_POST['country'];
}else{
	$country_id = 1;
}
$enddate = $_POST['enddate'];
$startdate = $_POST['startdate'];
$sqlstartdate = date('Y-m-d', strtotime($startdate));
$sqlenddate = date('Y-m-d', strtotime($enddate));

/* Date Formating Starts Here */

$year_start     = date('Y',strtotime($startdate));  
$month_start    = date('m',strtotime($startdate));  
$day_start      = date('d',strtotime($startdate));
$year_end       = date('Y',strtotime($enddate)); 
$month_end      = date('m',strtotime($enddate)); 
$day_end        = date('d',strtotime($enddate));

/* Create the Temporary Table Now */
$temp_table="anvil_industry_temp".date("Ymhmis");
$temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
`auto_id` INT  AUTO_INCREMENT PRIMARY KEY ,
`company_name` varchar(100) NOT NULL ,
`company_id` INT  ,
`rate` BIGINT ,
`date` DATE NULL,
`station_id` INT  ,
`brand_id` INT  ,
`duration` BIGINT
) ENGINE = MYISAM ;";
Yii::app()->db3->createCommand($temp_sql)->execute();

/* Print The Top */
if(isset($_POST['industry'])){
	if($industry_name = AnvilIndustry::model()->find('industry_id=:a', array(':a'=>$_POST['industry']))){
		$industry_name = $industry_name->industry_name;
	}else{
		$industry_name = 'Unknown';
	}
}else{
	$error_code = 1;
}

/* Sub Industry Text */
$sub_industry_names = '';
$sub_industry_query = '';
$set_subs = '';
if(isset($_POST['sub_industry_name'])){
	foreach ($_POST['sub_industry_name'] as $sub_industry_id) {
		if($sub_industry_name= AnvilSubIndustry::model()->find('auto_id=:a', array(':a'=>$sub_industry_id))){
			$sub_industry_names .= ucwords(strtolower($sub_industry_name->sub_industry_name)).', ';
		}
	}
	$set_subs = implode(', ', $_POST['sub_industry_name']);
    $sub_industry_query = ' and brand_table.sub_industry_id IN ('.$set_subs.')';
}

/* If there are any errors terminate execution at this point and redirect back to the form */
if(isset($error_code)){
    Yii::app()->user->setFlash('warning', "<strong>Error ! Please select at least one from each section </strong>");
    $this->redirect(array('media/companyairplay'));
}

if($adtype==1){
	$data_table="reelforge_sample";
	$main_sql="INSERT into $temp_table (company_name, company_id, rate,date, station_id, brand_id,duration)
	select user_table.company_name as company_name,
	user_table.company_id as company_id, 
	rate as rate, 
	reel_date as date, 
	station_id as station_id, 
	brand_table.brand_id as brand_id,
	incantation_length as duration  
	from user_table, $data_table , brand_table, industry,incantation where 
	$data_table.incantation_id=incantation.incantation_id and 
	$data_table.industry_id='$industry_id'  and  $data_table.entry_type_id =1  and 
	user_table.company_id=$data_table.company_id and  
	reel_date between '$sqlstartdate' and '$sqlenddate'  and 
	brand_table.brand_id =$data_table.brand_id and 
	brand_table.country_id=$country_id and $data_table.active=1 and
	brand_table.company_id=user_table.company_id and 
	brand_table.industry_id=industry.industry_id $sub_industry_query";
	$insertsql = Yii::app()->db3->createCommand($main_sql)->execute();
}else{
	$data_table="reelforge_sample";
	$main_sql="INSERT into $temp_table (company_name, company_id, rate,date, station_id, brand_id,duration)
	select user_table.company_name as company_name,
	user_table.company_id as company_id, 
	rate as rate, 
	reel_date as date, 
	station_id as station_id, 
	brand_table.brand_id as brand_id,
	incantation_length as duration  
	from user_table, $data_table , brand_table, industry,incantation where 
	$data_table.incantation_id=incantation.incantation_id and 
	$data_table.industry_id='$industry_id'  and 
	user_table.company_id=$data_table.company_id and  
	reel_date between '$sqlstartdate' and '$sqlenddate'  and 
	brand_table.brand_id =$data_table.brand_id and $data_table.active=1 and 
	brand_table.country_id=$country_id and 
	brand_table.company_id=user_table.company_id and 
	brand_table.industry_id=industry.industry_id $sub_industry_query";
	$insertsql = Yii::app()->db3->createCommand($main_sql)->execute();

	$data_table="djmentions";	
	$sql_manual="INSERT into $temp_table (company_name, company_id, rate,date, station_id, brand_id,duration)
	select user_table.company_name as company_name,
	user_table.company_id as company_id, 
	rate as rate, 
	date as date, 
	station_id as station_id, 
	brand_table.brand_id as brand_id,
	duration as duration 
	from user_table, $data_table , brand_table, industry  where 
	user_table.company_id=brand_table.company_id and  
	date between '$sqlstartdate' and '$sqlenddate'  and 
	brand_table.brand_id =$data_table.brand_id and $data_table.active=1 and 
	brand_table.country_id=$country_id and 
	brand_table.company_id=user_table.company_id and 
	brand_table.industry_id='$industry_id'  and 
	brand_table.industry_id=industry.industry_id $sub_industry_query";
	$insertsql = Yii::app()->db3->createCommand($sql_manual)->execute();
}

$countsql = "SELECT count(*) FROM $temp_table";
if($recordcount = Yii::app()->db3->createCommand($countsql)->queryAll()!=0){
    $chart_name = 'airplay_report';
    echo '<div class="row-fluid clearfix">';
	echo '<h4><strong>'.$industry_name.' Industry Airplay Reports (Company Report)</strong></h4>';
	echo '<br>';
	$narrative = 'Industry Airplay (Total Airplay) Report Between '.$startdate.' and '.$enddate.'. '.$industry_name.' Graph shown by total spend in Kshs.';
	// Get Array of Companies
	$x=0;
	$company_sql="select company_name,company_id, sum(duration) as rate from  $temp_table where station_id!=''   group by company_id order by rate desc";
	if($stored_companies = Yii::app()->db3->createCommand($company_sql)->queryAll()){
		$filename = CommonFunctions::AnvilAirplayExcel($temp_table,$set_subs,$stored_companies,$industry_id,$sqlstartdate,$sqlenddate);
		/* 
		** Divide this section in 3
		** Section 1 if for Data
		** Section 2 is for the graph
		** Section 3 is for Data
		*/
		echo '<div class="col-md-4">';
		echo '<p>'.substr($sub_industry_names,0,-2).'</p>';
		echo '<p>The following is a Industry Competitor Report for the period : '.$startdate.' and '.$enddate.'</p>';
		echo '<p>The graph displays the top 20 Companies reported in this industry.</p>';
		echo '<p>Industry : '.$industry_name.'</p>';
		echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/airplay/excel/'.$filename.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p>';

		echo '</div>';
		
		echo '<div class="col-md-8">';
		echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
		/*if($strXML = FusionCharts::packageAnvilAirplayXML("Industry Competitor Report (Total Airplay)", $stored_companies,$company_name)){
			$charty = new FusionCharts;
			echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, '100%', 600, false, true, true);
		}
		echo '</div></div>';*/


		echo '<div style="" class="">';
		$companytitle = 'Industry Competitor Report (Total Airplay)';
		
		$chart_exists = false;
		$container_name = 'airplay_company';
		$chart = new Highchart();
		$chart->chart->renderTo = "airplay_company";
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
		foreach ($stored_companies as $key) {  
			$chartarray[$count] = array( $key['company_name'].' <br>('.BillBoardForm::FormattedTime($key['rate']).')', (int)str_replace(',', '', $key['rate']));  
			$count++; if($count>19){ break; }
		}

		$count = 0;
		foreach ($stored_companies as $key) {  $count++; if($count>19){ $bigcount = true; $others = $others + $key['rate']; } }
		if(isset($bigcount)){
			$chartarray[20] = array( 'Others <br>('.BillBoardForm::FormattedTime($others).')', (int)str_replace(',', '', $others)); 
		}
		
		$chart->series[] = array('type' => 'pie','name' => 'Industry Competitor Report (Total Airplay)','data' => $chartarray);
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

		echo '</div></div>';
		echo '</div>';
		echo '<br>';
		echo '<div class="row-fluid clearfix">';
		
		?>

		<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget">
	        <header role="heading clearfix">
	        	<h2><span class="pull-left"><strong>Electronic (Radio & TV) Analysis</strong></span><span class="pull-right"></span></h2>
	        </header>
	    </div>
    	<?php
    	/* Station Summaries Begin Here */
    	echo CommonFunctions::CompanyAirplayTable($temp_table,$set_subs,$stored_companies,$industry_id,$sqlstartdate,$sqlenddate);

		echo '</div>';
	}
}else{
	echo '<h4><strong>'.$industry_name.' Industry Competitor Reports (Company Report)</strong></h4>';
	echo '<h4>No Results Found</h4>';
}

?>
<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
</style>
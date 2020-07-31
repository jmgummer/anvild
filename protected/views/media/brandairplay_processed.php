<?php
/**
* Brand Airplay Processed File
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
$this->pageTitle=Yii::app()->name.' | Total Airplay (Brand)';
$this->breadcrumbs=array('Total Airplay (Brand) Report'=>array('media/brandairplay'));
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

/* Create the Temporary Table Now */
$temp_table="anvil_brandairplay_temp".date("Ymhmis");
$temp_sql="CREATE TEMPORARY  TABLE `".$temp_table."` (
`auto_id` INT  AUTO_INCREMENT PRIMARY KEY ,
`brand_id` BIGINT NOT NULL ,
`company_id` INT  ,
`brand_name` varchar(100)  ,
`rate` INT ,
`station_id` INT  ,
`date` DATE  ,
`duration` BIGINT
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
	$brands_query = ' and reelforge_sample.brand_id IN ('.$set_brands.')';
	if($adtype==2){
		$djbrandquery = ' and djmentions.brand_id IN ('.$set_brands.')';
	}
}

/* If there are any errors terminate execution at this point and redirect back to the form */
if(isset($error_code)){
    Yii::app()->user->setFlash('warning', "<strong>Error ! Please select at least one from each section </strong>");
    $this->redirect(array('media/brandairplay'));
}


/* This is the main SQL If Adtype is 1 */
if($adtype==1){
	$main_sql="INSERT into $temp_table (brand_id, company_id, brand_name,rate, station_id, date,duration)
	SELECT brand_table.brand_id as brand_id, 
	brand_table.company_id as company_id, 
	brand_table.brand_name as brand_name, 
	rate as rate, 
	station_id as station_id, 
	reel_date as date,
	incantation_length as duration 
	from brand_table, reelforge_sample ,incantation
	where 
	reelforge_sample.incantation_id=incantation.incantation_id and reelforge_sample.entry_type_id =1 AND reelforge_sample.active = 1 
	and	brand_table.country_id=$country_id and  
	brand_table.brand_id=reelforge_sample.brand_id and  
	reel_date between '$sqlstartdate' and '$sqlenddate'  $brands_query ";
	$insertsql = Yii::app()->db3->createCommand($main_sql)->execute();
}else{
	$main_sql="INSERT into $temp_table (brand_id, company_id, brand_name,rate, station_id, date,duration)
	SELECT brand_table.brand_id as brand_id, 
	brand_table.company_id as company_id, 
	brand_table.brand_name as brand_name, 
	rate as rate, 
	station_id as station_id, 
	reel_date as date,
	incantation_length as duration 
	from brand_table, reelforge_sample ,incantation
	where 
	reelforge_sample.incantation_id=incantation.incantation_id and
	brand_table.country_id=$country_id and  
	brand_table.brand_id=reelforge_sample.brand_id and  reelforge_sample.active = 1 and 
	reel_date between '$sqlstartdate' and '$sqlenddate'  $brands_query ";
	$insertsql = Yii::app()->db3->createCommand($main_sql)->execute();

	$sql_manual="INSERT into $temp_table (brand_id, company_id, brand_name,rate, station_id, date,duration)
	SELECT djmentions.brand_id as brand_id,
	brand_table.company_id as company_id,
	brand_name as brand_name, 
	rate as rate, 
	station_id as station_id, 
	date as date, 
	duration as duration 
	from djmentions ,brand_table where 
	brand_table.brand_id=djmentions.brand_id  and 
	brand_table.country_id=$country_id and  djmentions.active = 1 and 
	date between '$sqlstartdate' and '$sqlenddate'  $djbrandquery "; 
	$insertsql = Yii::app()->db3->createCommand($sql_manual)->execute();
}

/* Create the Temporary Table Now */
// $temp_table="anvil_industry_temp".date("Ymhmis");
// $temp_sql="CREATE TEMPORARY TABLE IF NOT EXISTS ".$temp_table." AS ";
// if($adtype!=2){
// $temp_sql.= $main_sql;
// }else{
// 	$temp_sql.=$main_sql.' UNION '.$sql_manual;
// }
// echo $temp_sql;
// $countsql = "SELECT count(*) FROM $temp_table";
// if($recordcount = Yii::app()->db3->createCommand($countsql)->queryAll()!=0){
	$chart_name = 'airplay_report_brand';
    echo '<div class="row-fluid clearfix">';
	echo '<h4><strong>'.$industry_name.' Industry Airplay Reports (Brand Report)</strong></h4>';
	echo '<br>';
	$narrative = 'Industry Airplay Reports (Brand Report) Between '.$startdate.' and '.$enddate.'. Industry : '.$industry_name.', Sub Industry : '.$sub_industry_name.' Graph shown by total spend in Kshs.';
	// Get Array of Companies
	$x=0;
	$brand_sql="select brand_name,brand_id,company_id,sum(duration) as rate from $temp_table  group by brand_id order by brand_name asc"; 
	if($stored_brands = Yii::app()->db3->createCommand($brand_sql)->queryAll()){

		/* Generate the Excel File */
		$filename = CommonFunctions::AnvilAirplayBrandExcel($temp_table,$set_brands,$stored_brands,$industry_id,$sqlstartdate,$sqlenddate);
		/* 
		** Divide this section in 3
		** Section 1 if for Data
		** Section 2 is for the graph
		** Section 3 is for Data
		*/
		echo '<div class="col-md-4">';
		echo '<p>The following is an Industry Airplay Reports (Brand Report) for the period : '.$startdate.' and '.$enddate.'</p>';
		echo '<p>The graph displays the top brands reported in the selected industry and sub industry.</p>';
		echo '<p>Industry : '.$industry_name.'</p><p> Sub Industry : '.$sub_industry_name.'</p>';
		echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/airplay/excel/'.$filename.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p>';

		echo '</div>';
		
		echo '<div class="col-md-8">';
		echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
		/*if($strXML = FusionCharts::packageAnvilAirplayBrandXML("Industry Airplay Report (Brands)", $stored_brands)){
			$charty = new FusionCharts;
			echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
		}
		echo '</div></div>';*/

		echo '<div style="" class="">';
		$companytitle = 'Industry Airplay Report (Brands)';
		
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
		foreach ($stored_brands as $key) {  
			$chartarray[$count] = array( $key['brand_name'].' <br>('.BillBoardForm::FormattedTime($key['rate']).')', (int)str_replace(',', '', $key['rate']));  
			$count++; if($count>19){ break; }
		}

		$count = 0;
		foreach ($stored_brands as $key) {  $count++; if($count>19){ $bigcount = true; $others = $others + $key['rate']; } }
		if(isset($bigcount)){
			$chartarray[20] = array( 'Others <br>('.BillBoardForm::FormattedTime($others).')', (int)str_replace(',', '', $others)); 
		}
		
		$chart->series[] = array('type' => 'pie','name' => 'Industry Airplay Report (Brands)','data' => $chartarray);
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
    	echo CommonFunctions::CompanyAirplayBrandTable($temp_table,$set_brands,$stored_brands,$industry_id,$sqlstartdate,$sqlenddate);

		echo '</div>';
	}else{
		echo 'No Records Found';
	}

// }else{
// 	echo '<h4><strong>'.$industry_name.' Industry Airplay Reports (Brand Report)</strong></h4>';
// 	echo '<h4>No Results Found</h4>';
// }
?>
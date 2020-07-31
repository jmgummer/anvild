<?php
/**
* Summary Spends Processed File
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
$coid=$this_company_id = Yii::app()->user->company_id;
$this->pageTitle=Yii::app()->name.' | Top Spenders By Media';
$this->breadcrumbs=array('Summary Spends'=>array('ranking/summaryspends'));
?>
<script src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionCharts.js'; ?>"></script>
<script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionChartsExportComponent.js'; ?>"></script>
<?php
/*
** Required Variables
*/
$enddate = $_POST['enddate'];
$startdate = $_POST['startdate'];
$industry = $_POST['industry'];
if(empty($industry)){
	$industry = 'all';
}
$country_id = Yii::app()->user->country_id;
$country = Yii::app()->user->country_id;

if(isset($industry) && !empty($industry) && $industry!=''){
    if($industry=='all'){
    	$industryname = "All Industries";
        // $company = $_POST['competitor_company'];
        $sql_industry="SELECT industry.industry_name, industry.industry_id  from industry order by industry_name asc";
        // Select All Industries that the company is subscribed to
        if($subs = Yii::app()->db3->createCommand($sql_industry)->queryAll()){
            $subsarray = array();
            foreach ($subs as $subskey) { $subsarray[] = $subskey['industry_id']; }
            $splitsubs = implode(', ', $subsarray);
            // Select All The Sub Industries which fall under the companies' industries
            $sql_sub_industry="SELECT auto_id FROM  sub_industry where industry_id IN ($splitsubs)  ORDER BY sub_industry_name asc";
            $sub_ids = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll();
            $sub_industries = $sub_ids;
            $subids_array = array();
            foreach ($sub_ids as $idsskey) { $subids_array[] = $idsskey['auto_id']; }
            $set_subs = implode(', ', $subids_array);
            $brandarray = array();
            $sql_brands="SELECT * FROM  brand_table WHERE sub_industry_id IN ($set_subs) ORDER BY brand_name ASC";
            if($brands = BrandTable::model()->findAllBySql($sql_brands)){
                foreach ($brands as $key) { $brandarray[] = $key->brand_id; }
                $set_brands = implode(', ', $brandarray);
                $brand_query = "brand_id IN ($set_brands)";
                $temp_table = SummarySpends::GetRecords($startdate,$enddate,$industry,$sub_industries,$brand_query,$country);
            }else{
                echo "No Brands Found, Please Try Later!";
            }
        }
    }elseif ($industry!='all' && isset($_POST['sub_industry_name'])) {
    	$sql_industry="SELECT industry.industry_name, industry.industry_id  from industry where industry.industry_id ='$industry' ";
        $industryquery = Yii::app()->db3->createCommand($sql_industry)->queryRow();
        $industryname = $industryquery['industry_name'];
        $sub_industries = $_POST['sub_industry_name'];
        $set_subs = implode(', ', $_POST['sub_industry_name']);
        $brandarray = array();
        $sql_brands="SELECT * FROM  brand_table WHERE sub_industry_id IN ($set_subs) ORDER BY brand_name ASC";
        if($brands = BrandTable::model()->findAllBySql($sql_brands)){
            foreach ($brands as $key) { $brandarray[] = $key->brand_id; }
            $set_brands = implode(', ', $brandarray);
            $brand_query = "brand_id IN ($set_brands)";
            // echo $brand_query;
            $temp_table = SummarySpends::GetRecords($startdate,$enddate,$industry,$sub_industries,$brand_query,$country);
        }else{
            echo "No Brands Found, Please Try Later!";
        }
    }else{
        echo "You Need to Select Industies and/or Sub Industries for this Query to Work";
    }
    
}else{
    echo "No Industry Selected at All";
}

/* 
** Processing Begins Here 
*/
$media_sql="SELECT station_type, sum(rate) as rate  from $temp_table group by station_type ";
if($media_query = Yii::app()->db3->createCommand($media_sql)->queryAll()){
	echo '<p class="style2">
		<strong>The following is a Summary of Spends by Media Type for the period: 
		<font color=red>'.$startdate . ' to '. $enddate.'</font></strong><br />
	</p>';

	/* Print Row Starts Here */
	echo '<div class="row-fluid clearfix">';
	echo '<div class="col-md-6">';
	echo '<div class="row-fluid clearfix">';
	echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix">
		<h2><span class="pull-left"><strong>Graph Showing Summary Spends by Media Type</strong></span><span class="pull-right"></span></h2></header></div>';
	$title = " ";
	$chart_name = 'summaryspends';
	$table = TopSpenders::TotalMediaSpends($title,$media_query);
	/* Show Graph of Total Media Type Spends */
	/*echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
	if($strXML = $table['swfdata']){
		$charty = new FusionCharts;
		echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
	}
	echo '</div>';*/

	$chart_exists=false;
	$container_name = 'sovsummary';
	$chart = new Highchart();
	$chart->chart->renderTo = "sovsummary";
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
	foreach ($media_query as $key) {  
		$chartarray[$count] = array( $key['station_type'].'<br>'.number_format($key['rate']), (int)str_replace(',', '', $key['rate']));  
		$count++; if($count>19){ break; }
	}

	$count = 0;
	foreach ($media_query as $key) {  $count++; if($count>19){ $bigcount = true; $others = $others + $key['rate']; } }
	if(isset($bigcount)){
		$chartarray[20] = array( 'Others<br>'.number_format($others), (int)str_replace(',', '', $others)); 
	}
	
	$chart->series[] = array('type' => 'pie','name' => 'Summary Spends Media','data' => $chartarray);
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
	/* Graph Ends Here */

	/* Analysis */
	echo '<div class="col-md-6">';
	$tables = TopSpenders::TabbedTables($temp_table,'');
	$excel = $tables['file'];
		/* Print The Table */
	echo '<div class="row-fluid clearfix">';
	echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix">
	<h2><span class="pull-left"><strong>Analysis</strong></span><span class="pull-right excellink" >&nbsp;
	<a href="'.Yii::app()->request->baseUrl . '/docs/summaryspends/excel/'.$excel.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></span></h2></header></div>';
	echo '<div class="no-wrap">';

	
	echo $tables['data'];

	
	echo '</div>';
	echo '</div>';
	echo '<br>';
	echo '</div>';
	echo '</div>';
	echo '</div>';

	echo '<div class="row-fluid clearfix">';
	$graphs = TopSpenders::IndustryGraphsQueries($temp_table,'');
	$excel = $graphs['file'];
	echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/summaryspends/excel/'.$excel.'"><i class="fa fa-file-excel-o"></i> Download Industry Excel File</a></strong></p><br>';
	echo $graphs['data'];
	echo '</div>';
}


?>
<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
.no-wrap{
	max-height: 400px;
	width: 100%;
	overflow: auto;
	color: #333;
}
.excellink{
	font-size: 12px !important;
	padding-left: 10px !important;
}
</style>
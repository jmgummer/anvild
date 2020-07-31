<?php
/**
* Company Spends Processed File
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
$this->pageTitle=Yii::app()->name.' | Top Spenders By Company';
$this->breadcrumbs=array('Top Spenders By Company'=>array('ranking/companyspenders'));
?>
<script src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionCharts.js'; ?>"></script>
<script language="JavaScript" src="<?php echo Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/FusionChartsExportComponent.js'; ?>"></script>
<?php
/*
** Required Variables
*/
$enddate = $_POST['enddate'];
$startdate = $_POST['startdate'];
$industry = $industry_id = $_POST['industry'];
$adtype = $_POST['adtype'];
// $printtype = $_POST['printtype'];
$country_id = Yii::app()->user->country_id;
$format = 'p';
$media_type = $_POST['mediatype'];
if(isset($_POST['stations'])){ $Mystation_idField = $station = $_POST['stations']; }
// Evaluate and Obtain Data/Add to Temp Table
if(isset($industry)){
    if($industry=='all' || $industry==''){
        
        // Check User Type
        // Our Internal Admins Have Right to Everything
        // Regular Agency Users only have access to industries that their companies are assigned to
        if(Yii::app()->user->usertype=='agency' && Yii::app()->user->rpts_only!=1){
        	$company = $_POST['competitor_company'];
        	$sql_industry="SELECT industry.industry_name, industry.industry_id  from industry ,  industryreport where
	        industry.industry_id =industryreport.industry_id and
	        industryreport.company_id='$company'
	        order by industry_name asc";
        }else{
        	$sql_industry="SELECT industry.industry_name, industry.industry_id  from industry order by industry_name asc";
        }
        
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
                $temp_table = AllSpendsData::GetRecords($startdate,$enddate,$industry,$sub_industries,$brand_query,$country_id);
            }else{
                echo "No Brands Found, Please Try Later!";
            }
        }
    }elseif ($industry!='all' &&  $industry!='') {
    	// Select All The Sub Industries which fall under the companies' industries
        $sql_sub_industry="SELECT auto_id FROM  sub_industry where industry_id =$industry  ORDER BY sub_industry_name asc";
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
            // echo $brand_query;
            $temp_table = AllSpendsData::GetRecords($startdate,$enddate,$industry,$sub_industries,$brand_query,$country_id);
        }else{
            echo "No Brands Found, Please Try Later!";
        }
    }else{
        echo "You Need to Select Industies for this Query to Work";
    }
}else{
    echo "No Industry Selected at All";
}

if($adtype==1){
	$deletesql = "DELETE FROM $temp_table WHERE entry_type_id!=1 AND entry_type!='Spot Ad'";
	$delete_cmd = Yii::app()->db3->createCommand($deletesql)->execute();
}

if(isset($temp_table)){

	if($media_type=='p'){
		// Delete Non Print Entries
		$deletesql = "DELETE FROM $temp_table WHERE mediatype!='print'";
		$delete_cmd = Yii::app()->db3->createCommand($deletesql)->execute();
		// Delete Further if Not All Print is Set 
		if(isset($Mystation_idField) && $Mystation_idField!='10000003') {
			$deletesql = "DELETE FROM $temp_table WHERE station_id!=$Mystation_idField";
			$delete_cmd = Yii::app()->db3->createCommand($deletesql)->execute();
		} 
	}
	if($media_type=='e'){
		$deletesql = "DELETE FROM $temp_table WHERE mediatype='print'";
		$delete_cmd = Yii::app()->db3->createCommand($deletesql)->execute();
		// Delete Further if Not All Electronic is Set 
		if(isset($Mystation_idField) && ($Mystation_idField!='10000001' || $Mystation_idField!='10000002')) {
			if(isset($_POST['mediaoptions'])){
				// If Radio
				if($_POST['mediaoptions']=='radio'){
					$deletesql = "DELETE FROM $temp_table WHERE station_type!='radio'";
					$delete_cmd = Yii::app()->db3->createCommand($deletesql)->execute();
					// Delete Everything That Does Not Belong To The Station, But Check if You are not asking for all - 10000002
					if($Mystation_idField!='10000002'){
						$deletesql = "DELETE FROM $temp_table WHERE station_id!=$Mystation_idField";
						$delete_cmd = Yii::app()->db3->createCommand($deletesql)->execute();
					}
					
				}
				// If TV
				if($_POST['mediaoptions']=='tv'){
					$deletesql = "DELETE FROM $temp_table WHERE station_type!='tv'";
					$delete_cmd = Yii::app()->db3->createCommand($deletesql)->execute();
					// Delete Everything That Does Not Belong To The Station, But Check if You are not asking for all - 10000002
					if($Mystation_idField!='10000001'){
						$deletesql = "DELETE FROM $temp_table WHERE station_id!=$Mystation_idField";
						$delete_cmd = Yii::app()->db3->createCommand($deletesql)->execute();
					}
				}
			}
		} 
	}

	$countsql = "SELECT count(*) FROM $temp_table";
	if($recordcount = Yii::app()->db3->createCommand($countsql)->queryAll()!=0){
		$company_sql="SELECT $temp_table.company_name, $temp_table.company_id, sum(rate) as rate from $temp_table where 
		rate>0 group by company_name order by rate desc";
		if($company_query = Yii::app()->db3->createCommand($company_sql)->queryAll()){
			$excel_title = "Top Spenders by Company";
			$excel_industry = "";
			$excel_client = $company_name;
			$title = "Graph Showing Top 20 Spenders by Company";
			$chart_name = 'topspenders';
			$table = TopSpenders::CompanySpendsTable($title,$company_query);
			$excel = TopSpenders::TopSpendersCompanyExcel($table['exceldata'],$excel_title,$excel_industry,$excel_client);
			echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/topspenders/excel/'.$excel.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p>';
			/* Show Graph of Total Spends */
			/*echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
			if($strXML = $table['swfdata']){
				$charty = new FusionCharts;
				echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
			}
			echo '</div>';*/

			echo '<div style="" class="">';
			$companytitle = 'Company Ranking';
			echo"<h5>Companies Ranked From Highest to Lowest Spenders</h5>";
			echo RenderSOVCompanyChart($company_query,$companytitle,'');
			echo '</div>';

			echo '<br>';
			/* Print The Table */
			echo '<div class="row-fluid clearfix">';
			echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix">
			<h2><span class="pull-left"><strong>Analysis</strong></span><span class="pull-right"></span></h2></header></div>';
			echo '<div class="no-wrap">';
			echo $table['tabledata'];
			echo '</div>';
			echo '</div>';
			echo '<br>';
			/* Table Ends Here */
		}else{
			echo "No Companies Found";
		}
	}else{
		echo '<h4><strong>Top Spenders Report (Company Ranking)</strong></h4>';
		echo '<h4>No Results Found</h4>';
	}
}else{
	echo '<h4><strong>Top Spenders Report (Company Ranking)</strong></h4>';
	echo '<h4>No Results Found</h4>';
}


function RenderSOVCompanyChart($array,$title,$subtitle,$chart_exists=false){
	$container_name = 'sovcompany';
	$chart = new Highchart();
	$chart->chart->renderTo = "sovcompany";
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
	foreach ($array as $key) {  
		$chartarray[$count] = array( $key['company_name'].'<br>'.number_format($key['rate']), (int)str_replace(',', '', $key['rate']));  
		$count++; if($count>19){ break; }
	}

	$count = 0;
	foreach ($array as $key) {  $count++; if($count>19){ $bigcount = true; $others = $others + $key['rate']; } }
	if(isset($bigcount)){
		$chartarray[20] = array( 'Others<br>'.number_format($others), (int)str_replace(',', '', $others)); 
	}
	
	$chart->series[] = array('type' => 'pie','name' => 'Top Spenders - Company','data' => $chartarray);
	$chart->credits = array('enabled'=>false);
	if($chart_exists == false){
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
	}
	$package = '<div id="'.$container_name.'"></div>';
	$package.= '<script type="text/javascript">';
	$package.= $chart->render("chart1");
    $package.= '</script>';
    return $package;
}
<?php
/**
* Company Processed File
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
$this->pageTitle=Yii::app()->name.' | Industry Competitor Reports (Company Report) ';
$this->breadcrumbs=array('Industry Competitor Reports (Company Report) '=>array('competitor/company'));
$currency =Common::CountryCurrency(Yii::app()->user->country_id);
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
$printtype = $_POST['printtype'];
$country_id = Yii::app()->user->country_id;
$format = 'p';


if(isset($industry) && !empty($industry) && $industry!=''){
    if($industry=='all'){
        $company = $_POST['competitor_company'];
        $sql_industry="SELECT industry.industry_name, industry.industry_id  from industry ,  industryreport where
        industry.industry_id =industryreport.industry_id and
        industryreport.company_id='$company'
        order by industry_name asc";
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
    }elseif ($industry!='all' && isset($_POST['sub_industry_name'])) {
        $sub_industries = $_POST['sub_industry_name'];
        $set_subs = implode(', ', $_POST['sub_industry_name']);
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
        echo "You Need to Select Industies and/or Sub Industries for this Query to Work";
    }
}else{
    echo "No Industry Selected at All";
}

if(isset($temp_table)){
	$countsql = "SELECT count(*) FROM $temp_table";
	if($recordcount = Yii::app()->db3->createCommand($countsql)->queryAll()!=0){
		$allcompaniessql="SELECT distinct company_id, company_name from $temp_table WHERE company_id!=0 order by company_name asc";
		$all_companies = Yii::app()->db3->createCommand($allcompaniessql)->queryAll();
		$filename = Competitor::CompetitorExcel($temp_table,$set_subs,$all_companies,$industry,$startdate,$enddate,'print');
		echo '<p><strong><a href="'.Yii::app()->request->baseUrl . '/docs/competitor/excel/'.$filename.'"><i class="fa fa-file-excel-o"></i> Download Excel File</a></strong></p>';
		echo "<hr>";

		if(!empty($printtype)) {
			echo '<div class="row-fluid clearfix">';
			echo '<p><strong>Print</strong></p><hr>';
			$chart_name = 'competition_report_print';
			$x=0;
			$company_sql="SELECT distinct company_id, company_name from $temp_table where station_type='print' order by rate asc, company_name asc";
			if($print_companies = Yii::app()->db3->createCommand($company_sql)->queryAll()){
				echo '<div class="col-md-12">';
				/* Experiment with storing multidimensional arrays */
				$company_array = array();
				$array_counter = 0;
				foreach ($print_companies as $key) {
					$cname = $key['company_name'];
					$company_array[$array_counter]['company_name'] = $cname;

					$individual_selects = "SELECT * FROM $temp_table WHERE company_name=\"$cname\" AND station_type='print'";
					if($company_count = Yii::app()->db3->createCommand($individual_selects)->queryAll()){
						$company_array[$array_counter]['count']= count($company_count);
					}else{
						$company_array[$array_counter]['count']=0;
					}
					$array_counter++;
				}
				/*echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
				if($strXML = FusionCharts::packageAnvilCompetitorXML("Industry Competitor Report - Print", $company_array)){
					$charty = new FusionCharts;
					echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
				}
				echo '</div>';*/

				echo '<div style="" class="">';
				$companytitle = 'Industry Competitor Report - Print';
				echo PrintCompetitorGraph($company_array,$companytitle,'');
				echo '</div>';


				echo '</div>';
				echo '</div>';
				echo '<br>';
				echo '<div class="row-fluid clearfix">';
				echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix"><h2><span class="pull-left"><strong>Print Analysis</strong></span><span class="pull-right"></span></h2></header></div>';
				echo CommonFunctions::CompanyCompetitionPrintTable($temp_table,$set_subs,$print_companies,$industry,$startdate,$enddate);
				echo '</div>';
			}else{
				echo "<p><i>No Records Found</i></p>";
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
			$company_sql="SELECT distinct company_id, company_name from $temp_table where mediatype='electronic' AND company_id!=0 order by rate asc, company_name asc";
			if($stored_companies = Yii::app()->db3->createCommand($company_sql)->queryAll()){
				$station_companies = $stored_companies;
				$company_array = array();
				
				$array_counter = 0;
				
				foreach ($stored_companies as $key) {
					$company_array[$array_counter]['company_name'] = $key['company_name'];
					$individual_selects = 'SELECT * FROM '.$temp_table.' WHERE company_id='.$key['company_id'].' AND mediatype="electronic" ';
					if($company_count = Yii::app()->db3->createCommand($individual_selects)->queryAll()){
						$company_array[$array_counter]['count']= count($company_count);
					}else{
						$company_array[$array_counter]['count']=0;
					}
					$array_counter++;
				}

				$x1=0;
				$company_array2 = array();
				$array_counter2 = 0;
				

				foreach ($stored_companies as $companykey){
		            $this_total=0;
		            if($x1<20){
		            	$company_array2[$array_counter2]['company_name'] = $companykey['company_name'];
		                $this_company_id = $companykey['company_id'];
		                $sql_station_totals="SELECT sum(rate) as  rate from $temp_table where $temp_table.company_id=$this_company_id AND mediatype='electronic'";
		                if($stationquery = Yii::app()->db3->createCommand($sql_station_totals)->queryAll()){
		                    foreach ($stationquery as $key) {
		                        $station_totals_total=$key["rate"];
		                        if($station_totals_total){
		                            $this_total=$this_total+$station_totals_total;
		                        }
		                    }
		                    $company_array2[$array_counter2]['totalspend']=$this_total;
		                }
		                $array_counter2++;
		                $x1++;
		                $netspend =$netspend+$this_total;
		            }
		        }
		        /* Start Div */
				echo '<div class="col-md-12">';
				/*echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
				if($strXML = FusionCharts::packageAnvilCompetitorSpendXML("Graph Showing Total Spend in $currency", $company_array2,$netspend,$format)){
					$charty = new FusionCharts;
					echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name2, '100%', 400, false, true, true);
				}
				echo '</div>';*/

				echo '<div style="" class="">';
				$companytitle = 'Graph Showing Total Spend in Kshs';
				echo ElectronicCompetitorGraph($company_array2,$companytitle,'',false,$netspend);
				echo '</div>';

				echo '</div>';
				/* End Div */
				/* Start Div */
				// echo '<div class="col-md-6">';
				// echo '<div style="padding:0px; background-color:#fff; border:0px solid #745C92; width: 100%;">';
				// if($strXML = FusionCharts::packageAnvilCompetitorXML("Graph Showing Total Number of Spots", $company_array)){
				// 	$charty = new FusionCharts;
				// 	echo FusionCharts::renderChart(Yii::app()->request->baseUrl . '/FusionCharts/FusionCharts/FusionCharts/Pie2D.swf', "", $strXML, $chart_name, '100%', 400, false, true, true);
				// }
				// echo '</div>';
				// echo '</div>';
				/* End Div */
				echo '</div>';
				/* End Row Fluid Electronic */
				echo '<br>';
				echo '<div class="row-fluid clearfix">';
				echo '<div id="wid-id-0" class="jarviswidget jarviswidget-sortable"style="" role="widget"><header role="heading clearfix">
				<h2><span class="pull-left"><strong>Electronic Analysis</strong></span><span class="pull-right"></span></h2></header></div>';
				
				echo CommonFunctions::CompanyCompetitionElectronicTable($temp_table,$set_subs,$station_companies,$industry,$startdate,$enddate);
				echo '</div>';
			}else{
				echo "<p><i>No Records Found</i></p>";
			}
		}

	}else{
		echo '<h4><strong>Industry Competitor Reports</strong></h4>';
		echo '<h4>No Results Found</h4>';
	}
}else{
	echo '<h4><strong>Industry Competitor Reports</strong></h4>';
	echo '<h4>No Results Found</h4>';
}



function ElectronicCompetitorGraph($array,$title,$subtitle,$chart_exists=false,$netspend){
	$container_name = 'electronic_competitor';
	$chart = new Highchart();
	$chart->chart->renderTo = "electronic_competitor";
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
		$co_value = $key['totalspend'];
        $co_valueformated = number_format($co_value,2);
        $co_value2 = ($key['totalspend']/$netspend)*100;
        $co_value2 = round($co_value2, 2);

		$chartarray[$count] = array( $key['company_name'].' ('.number_format($co_value2,2).' %)', (int)str_replace(',', '', $co_value2));  
		$count++; if($count>19){ break; }
	}

	$count = 0;
	foreach ($array as $key) {  
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
		$chartarray[20] = array( 'Others ('.number_format($others,2).' %)', (int)str_replace(',', '', $others)); 
	}
	
	$chart->series[] = array('type' => 'pie','name' => 'Competitor Spends Industry - Electronic','data' => $chartarray);
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

function PrintCompetitorGraph($array,$title,$subtitle,$chart_exists=false){
	$container_name = 'print_competitor';
	$chart = new Highchart();
	$chart->chart->renderTo = "print_competitor";
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
		$chartarray[$count] = array( $key['company_name'].' ('.number_format($key['count']) .')', (int)str_replace(',', '', $key['count']));  
		$count++; if($count>19){ break; }
	}

	$count = 0;
	foreach ($array as $key) {  
		$count++; 
		if($count>19){ 
			$bigcount = true; 
			$others = $others + $key['count']; 
		} 
	}
	if(isset($bigcount)){
		$chartarray[20] = array( 'Others ('.number_format($others).')', (int)str_replace(',', '', $others)); 
	}
	
	$chart->series[] = array('type' => 'pie','name' => 'Competitor Spends Industry - Print','data' => $chartarray);
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

































?>
<style type="text/css">
.fupisha { max-width: 250px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis; }
.nav-tabs > li.active > a { box-shadow: 0px -2px 0px #DB4B39; }
#tabs { width: 100%; background-color: #CCC; }
.pdf-excel{margin: 5px 1px;}
</style>
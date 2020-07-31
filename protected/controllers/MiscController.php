<?php

/**
* Misc Controller Class
*
* @package     Anvil
* @subpackage  Controllers
* @category    Reelforge Client Systems
* @license     Licensed to Reelforge, Copying and Modification without prior permission is not allowed and can result in legal proceedings
* @author      Steve Ouma Oyugi - Reelforge Developers Team
* @version 	   v.1.0
* @since       July 2008
*/

class MiscController extends Controller
{

	/*
	** Load the default layout for this section
	*/

	public $layout='//layouts/column1';

	/** 
	*
	* @return  Filters
	* @throws  InvalidArgumentException
	* @todo    Manage Access Control
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	/** 
	*
	* @return  Boolean
	* @throws  InvalidArgumentException
	* @todo    Track all User Actions
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	protected function beforeAction($event)
    {
        $track = new Tracker;
        $track->Utrack();
        return true;
    }

	/** 
	*
	* @return  Boolean
	* @throws  InvalidArgumentException
	* @todo    Determines whether a user has access to a section or otherwise
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','ratechange','rateupdate','newrates','industrysummary','getdata','excel','pdf','stationlog','getlogs'),
				'users'=>array('admin'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('reconciliationlog','excel','pdf','agencyspends','mylog','competitorlog','ratesave','ratedelete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/** 
	*
	* @return  index page
	* @throws  InvalidArgumentException
	* @todo    Loads the Index Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionIndex()
	{
		$model = new StorySearch;
		$this->render('index', array('model'=>$model));
	}

	/** 
	*
	* @return  industrysummary page
	* @throws  InvalidArgumentException
	* @todo    Loads the Industry Summary Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionIndustrysummary()
	{
		$model = new StorySearch;
		if(isset($_POST['industry'])){
			$this->render('summaryprocessed');
		}else{
			$this->render('summary', array('model'=>$model));
		}
		
	}

	/** 
	*
	* @return  ratechange page
	* @throws  InvalidArgumentException
	* @todo    Loads the Rate change Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionRatechange()
	{
		$model = new StorySearch;
		$this->render('change', array('model'=>$model));
	}

	public function actionNewrates()
	{
		$model = new StorySearch;
		if(isset($_POST['enddate'])){
			// $this->render('newrates_processed', array('model'=>$model));
			$this->render('rates_processed', array('model'=>$model));
		}else{
			$this->render('newrates', array('model'=>$model));
		}
	}

	public function actionRatesave()
	{
		if(isset($_POST['NewRate'])){
			$sum = 0;
			$adcount = 0;
			/* SAVE THE VALUES FIRST */
			$table = "<table>";
			$table .= "<tr>
			<td><strong>Date</strong></td>
			<td><strong>Day</strong></td>
			<td><strong>Time</strong></td>
			<td><strong>Ad Name</strong></td>
			<td><strong>Brand Name</strong></td>
			<td><strong>Type</strong></td>
			<td><strong>Duration</strong></td>
			<td><strong>Actual Value(Kshs)</strong></td>
			</tr>";
			$invoices = new RateChange;
			$loginvoice = $invoices->LogInvoice(Yii::app()->user->company_id);
			foreach ($_POST['NewRate'] as $ratekeys) {
				/* Fix to Clean Up White Space */
				$ratekeys['ad_name'] = str_replace(' ', '_',$ratekeys['ad_name']);
				$ratekeys['brand_name'] = str_replace(' ', '_',$ratekeys['brand_name']);
				$ratekeys['entry_type'] = str_replace(' ', '_',$ratekeys['entry_type']);

				$new_entry = new RateChange;
				$saved = $new_entry->ProcessEntries(Yii::app()->user->company_id,$ratekeys,$loginvoice);
				
				/* Reverse The Changes */
				$ratekeys['ad_name'] = str_replace('_', ' ',$ratekeys['ad_name']);
				$ratekeys['brand_name'] = str_replace('_', ' ',$ratekeys['brand_name']);
				$ratekeys['entry_type'] = str_replace('_', ' ',$ratekeys['entry_type']);
				if($ratekeys['value']==0){
					$agency_value = 0;
				}else{
					$agency_value = (int)str_replace(',', '', $ratekeys['value']);
				}
				$table .= "<tr>
				<td>".$ratekeys['date']."</td>
				<td>".$ratekeys['day']."</td>
				<td>".$ratekeys['time']."</td>
				<td>".$ratekeys['ad_name']."</td>
				<td>".$ratekeys['brand_name']."</td>
				<td>".$ratekeys['entry_type']."</td>
				<td>".$ratekeys['duration']."</td>
				<td>".$agency_value."</td>
				</tr>";
				$sum = $sum + $agency_value;
				$adcount++;
			}
			$table .= "</table>";
			$distinct_station_id = $ratekeys['station_id'];
			if($station_name = Station::model()->find('station_id=:a', array(':a'=>$distinct_station_id))){
				$stationname = $station_name->station_name;
			}else{
				$stationname = 'Unknown';
			}
			$table .= "<table><tr><td>STATION TOTAL(".$stationname.") | Total Number of Ads ".$adcount."</td><td><strong>".$sum."</strong></td></tr></table>";
			$dategenerated = date('d-m-Y');
			$document_id = rand(5, 25).' - '.Yii::app()->user->company_id.'/'.$ratekeys['brand_name'].'/'.date('Ymd');
			$tableheader = "<p><strong>Report : Proof of Flight</strong></p>";
			$tableheader .= "<p><strong>Generated Date : ".$dategenerated." Document ID : ".$document_id." </strong></p>";
			$tableheader .= "<p><strong>".$ratekeys['brand_name']." </strong> Report is for the period: ".$ratekeys['startdate']." and ".$ratekeys['enddate']."</p>";
			$tableheader .= "<p><strong>".$stationname."</strong></p>";
			$table = $tableheader.$table;
			/* GENERATE THE PDF */
			$company_id=Yii::app()->user->company_id;
			$filename="rate_change_"  .$company_id ."_new_". $ratekeys['startdate'] . "_to_"  . $ratekeys['enddate']. "_".date("Ymdhis") ;
			$filename=str_replace(" ","_",$filename);
			$filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
			$pdf = Yii::app()->ePdf2->Output2('rate_pof',array('table'=>$table));
			$filename_pdf=$filename.'.pdf';
			$location1 = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename_pdf;
			file_put_contents($location1, $pdf);
			/* Log the Results into Invoices Table */
			if($brandid = BrandTable::model()->find('brand_name=:a', array(':a'=>$ratekeys['brand_name']))){
				$brandid = $brandid->brand_id;
			}else{
				$brandid = rand(5,25).date('dmY').date('his');
			}
			$pdf_file = "/anvild/docs/misc/pdf/".$filename_pdf;

			$new_invoice = new RateChange;
			$invoice = $new_invoice->AddInvoice($brandid,Yii::app()->user->company_id,$ratekeys['startdate'],$ratekeys['enddate'],$pdf_file,$loginvoice);
			if($invoice=='Added'){
				$file = Yii::app()->request->baseUrl . '/docs/misc/pdf/' . $filename_pdf;
				$this->render('saved', array('file'=>$file));
			}else{
				$this->render('error');
			}
		}else{
			echo 'error';
		}
	}

	/** 
	*
	* @return  rateupdate page
	* @throws  InvalidArgumentException
	* @todo    Handles Rate Updates
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionRateupdate($id){
		$invoice_sql = "SELECT distinct(invoice_reconciler.station_id), station_name,inv_id from invoice_reconciler, station  where inv_id='$id' and station.station_id=invoice_reconciler.station_id";
		if($invoices = Yii::app()->db3->createCommand($invoice_sql)->queryAll()){
			$inv_id = $id;
			if(isset($_POST['invoice'])){
				$ids = $_POST['invoice'];
				

				$today_date=date("Y-m-d");
				$today_time=date("H:i:s");
				$sql_search="SELECT update_number, inv_start_date,inv_end_date, brand_name from invoice, brand_table where invoice_id='$inv_id' and brand_table.brand_id = invoice.brand_id";

				if($invoice_search = Yii::app()->db3->createCommand($sql_search)->queryAll()){
					foreach ($invoice_search as $inv_key) {
						/* Create a New PDF and Update the Records */
						$display_brand_name = $brandname = $inv_key['brand_name'];
						$company_id = Yii::app()->user->company_id;
						$update_number = $inv_key["update_number"]; 
						$update_number++;
						$inv_start_date = $inv_key["inv_start_date"];
						$inv_end_date = $inv_key["inv_end_date"];
						$filename="rate_change_"  .$company_id ."_update_" .$update_number."_". $inv_start_date . "_to_"  . $inv_end_date. "_".date("Ymdhis") ;
						$filename=str_replace(" ","_",$filename);
						$filename=preg_replace('/[^\w\d_ -]/si', '', $filename);
						$filename=$filename.'.pdf';

						$report_start_date=$inv_start_date;
						$report_end_date=$inv_end_date;

						if($update_number<10) { $myversion="0".$update_number; } else { $myversion=$update_number; }
						$title = 'Document ID : '.$document_id=$inv_id."-".$company_id."/".$display_brand_name."/". date("Ymd") ."/".$myversion;
						$pdf_file = "/anvild/docs/misc/pdf/".$filename;
						$update = new RateChange;
						$run = $update->UpdateInvoice($today_date,$update_number,$pdf_file,$inv_id);
					}
				}
				$print_brand_name = $display_brand_name;
				/* Update the Invoice Items */
				
				// print_r($ids);
				foreach ($ids as $setkey) {
					$auto_id = key($ids);
					$inv_rate = $setkey;
					$update = new RateChange;
					$run = $update->UpdateReconciliation($inv_rate,$auto_id,$inv_id);
					/* Do not EVER, EVER Forget to Add this. You will remain with the First Key FOREVER! */
					next($ids);
				}

				$file = Yii::app()->request->baseUrl . '/docs/misc/pdf/' . $filename;
				Yii::app()->user->setFlash('success', "<strong>Success ! </strong> Invoice Updated <a href='$file' target='blank'>Download</a>");



				$pdf = Yii::app()->ePdf2->Output2('pdf',array('title'=>$title,'model'=>$invoices,'invoice'=>$inv_id,'brand'=>$print_brand_name));
				// $filename_pdf=$filename.'.pdf';
				// $location="/home/srv/www/htdocs/anvil/reports/rate_change/" . $company_id ."/".$filename_pdf;
				// file_put_contents($location, $pdf);

				// $filename_pdf=$filename.'.pdf';
				$location = $_SERVER['DOCUMENT_ROOT']."/anvild/docs/misc/pdf/".$filename;
				file_put_contents($location, $pdf);

				$this->redirect(array('misc/ratechange'));

			}else{
				$this->render('update', array('model'=>$invoices,'invoice'=>$inv_id));
			}
		}else{
			$this->redirect(array('misc/ratechange'));
		}
	}

	public function actionRatedelete($id){
		if($model = Invoice::model()->find('invoice_id=:a', array(':a'=>$id))){
			// echo 'found';
			Yii::app()->user->setFlash('success', "<strong>Success ! </strong> The Invoice has been Deleted");
			$model->delete();
			$this->redirect(array('ratechange'));
		}else{
			$this->redirect(array('ratechange'));
		}
	}

	/** 
	*
	* @return  stationlog page
	* @throws  InvalidArgumentException
	* @todo    Loads the Station Log Page
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2015-01-21 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionStationlog()
	{
		$model = new StorySearch;
		if(isset($_GET['country'])){
			$model->country =$_GET['country'];
		}
		if(!isset($_POST['StorySearch']))
		{
			$model->entrytype = "all";
		}else{
			$model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
		}
		$this->render('stationlog', array('model'=>$model));
	}

	public function actionMylog()
	{
		$model = new StorySearch;
		if(isset($_GET['country'])){
			$model->country =$_GET['country'];
		}
		if(!isset($_POST['StorySearch']))
		{
			$model->entrytype = "all";
		}else{
			$model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
		}

		if(isset($_POST['enddate'])){
			$this->render('mylog_processed');
		}else{

			$this->render('mylog', array('model'=>$model));
		}
	}

	public function actionCompetitorlog()
	{
		$model = new StorySearch;
		if(isset($_GET['country'])){
			$model->country =$_GET['country'];
		}
		if(!isset($_POST['StorySearch']))
		{
			$model->entrytype = "all";
		}else{
			$model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
		}

		if(isset($_POST['enddate'])){
			$this->render('competitorlog_processed');
		}else{

			$this->render('competitorlog', array('model'=>$model));
		}
	}

	public function actionGetlogs()
	{
		if(isset($_POST['entrytype']) && isset($_POST['logdate']) && isset($_POST['station']) ){
			echo $entry_type = $_POST['entrytype'];
			echo $this_date = date('Y-m-d',strtotime(str_replace('-', '/', $_POST['logdate'])));
			echo $searched_station_id = $_POST['station'];
			echo $logs = StationLogReports::CreateLog($entry_type,$this_date,$searched_station_id);
		}
		
	}

	public function actionReconciliationlog()
	{
		$model = new StorySearch;
		if(isset($_GET['country'])){
			$model->country =$_GET['country'];
		}
		if(!isset($_POST['StorySearch']))
		{
			$model->entrytype = "all";
		}else{
			$model->attributes=Yii::app()->input->stripClean($_POST['StorySearch']);
		}

		if(isset($_POST['enddate'])){
			$this->render('reclog_processed');
		}else{

			$this->render('reclog', array('model'=>$model));
		}

		// $this->render('reclog', array('model'=>$model));
	}

	public function actionAgencyspends()
	{
		if(isset($_GET['startdate']) && isset($_GET['enddate'])){
			echo date('d-m-Y h:i:s').'<hr>';
			$enddate = $_GET['enddate'];
			$startdate = $_GET['startdate'];
			$sqlstartdate = date('Y-m-d', strtotime($startdate));
			$sqlenddate = date('Y-m-d', strtotime($enddate));
			// if($spends = AgencySpends::GetData($enddate,$startdate,$sqlstartdate,$sqlenddate)){
			// 	// $array_number = 0;
			// 	// foreach ($spends as $agencyspends) {
			// 	// 	$array_inner=0;
			// 	// 	foreach ($agencyspends as $agencykey) {
			// 	// 		echo $agencykey[$array_number][$array_inner];
			// 	// 		$array_inner++;
			// 	// 	}
			// 	// 	// echo $agencyspends[$array_number]['array_tv'];
			// 	// 	// echo $agencyspends[$array_number]['array_radio'];
			// 	// 	// echo $agencyspends[$array_number]['array_print'];
			// 	// 	$array_number++;
			// 	// }
			// }
			$myArray = array ('at home' => array('laundry', 'dishes'),'shopping' => array('milk', 'bread','pasta'),'at work'=>array('Hans','copy folder 1'));

      // On the line below, output one of the values to the page:
      // echo $myArray ['shopping'][2].'<br />';

      // On the line below, loop through the array and output
      // *all* of the values to the page:
     foreach ($myArray as $place => $task) {
         foreach ($task as $thingToDo){
             echo $thingToDo.'<br />';
         }
     }
			// $keys = array_keys($spends);
			// $iterations = count($spends[$keys[0]]);

			// for($i = 0; $i < $iterations; $i++) {
			//     $data = array();
			//     foreach($array as $key => $value) {
			//         $data[$key] = $value[$i];
			//     }
			//     print_r($data);
			// }
			// print_r($spends);
			// echo '<hr>';
			// var_dump($spends);

			echo date('d-m-Y h:i:s');
		}
	}

	

	/** 
	*
	* @return  packaged data options
	* @throws  InvalidArgumentException
	* @todo    Returns Various Options for Ajax Loading
	*
	* @since   2008
	* @author  Steve Ouma Oyugi - Reelforge Development Team
	* @edit    2014-07-08 
	*	DO NOT ALTER UNLESS YOU UNDERSTAND WHAT YOU ARE DOING
	*/

	public function actionGetdata()
	{
		if(isset($_POST['channel'])){
			$channel = $_POST['channel'];
			$client_id = Yii::app()->user->company_id;
			$sql_site="SELECT distinct(brand_name),brand_table.brand_id from brand_table,outdoor_channel_entries where brand_table.brand_id=outdoor_channel_entries.brand_id and outdoor_channel_entries.company_id='$channel' and brand_table.company_id='$client_id' order by brand_name ; ";
			
			if($brands = Yii::app()->db3->createCommand($sql_site)->queryAll()){
				echo "<option value=''>-SELECT-</option>";
        		echo "<option value='all'>-ALL-</option>";
				foreach ($brands as $value) {
					$this_brand_id=$value["brand_id"];
					$this_brand_name=trim($value["brand_name"]);

					echo '<option value="'.$this_brand_id.'">'.$this_brand_name.'</option>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}
		if(isset($_POST['region']) && isset($_POST['company_id']) && !empty($_POST['company_id'])){
			$this_province_id 	= 	$_POST['region'];
			$this_company_id 	= 	$_POST['company_id'];

			if($this_province_id=="all") {
			$sql_site="SELECT ocs.auto_id, ocs.site_name, ocs.site_location from outdoor_channel_sites ocs, towns where ocs.company_id='$this_company_id' and ocs.site_town=towns.auto_id order by site_name asc ";
			} else {
			$sql_site="SELECT ocs.auto_id, ocs.site_name, ocs.site_location from outdoor_channel_sites ocs, towns where ocs.company_id='$this_company_id' and towns.province_id='$this_province_id' and ocs.site_town=towns.auto_id order by site_name asc ";
			}

			if($sites = Yii::app()->db3->createCommand($sql_site)->queryAll()){
        		echo "<option value='all'>-ALL-</option>";
				foreach ($sites as $value) {
					$this_site_id=$value["auto_id"];
					$this_site_name=trim($value["site_name"]);
					$this_site_location=trim($value["site_location"]);

					echo '<option value="'.$this_site_id.'">'.$this_site_name.' - '.$this_site_location.'</option>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['industry']) && !empty($_POST['industry'])){
			$industry 	= 	$_POST['industry'];
			$sql_sub_industry="SELECT *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";
			if($industry=='all' && Yii::app()->user->rpts_only==1) {
				$sql_sub_industry="SELECT *  from  sub_industry order by sub_industry_name asc";
				if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
					foreach ($subs as $value) {
						$this_sub_industry_name=ucwords(strtolower($value["sub_industry_name"]));  
						$this_sub_industry_id=$value["auto_id"];
						echo '<div class="col-md-6"><label class="checkbox">';
						echo "<input id='this_sub_industry_id' name='sub_industry_name[".$this_sub_industry_id."]'  type='checkbox' value='$this_sub_industry_id' />";
						echo '<label class="checkbox">'.$this_sub_industry_name.'</label></label></div>';
					}
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}elseif($industry=='all' && Yii::app()->user->rpts_only!=1){
				echo 'No Sub Industries';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
					foreach ($subs as $value) {
						$this_sub_industry_name=ucwords(strtolower($value["sub_industry_name"]));  
						$this_sub_industry_id=$value["auto_id"];
						echo '<div class="col-md-6"><label class="checkbox">';
						echo "<input id='this_sub_industry_id' name='sub_industry_name[".$this_sub_industry_id."]'  type='checkbox' value='$this_sub_industry_id' />";
						echo '<label class="checkbox">'.$this_sub_industry_name.'</label></label></div>';
					}
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}else{
					echo '<option>No Results Found</option>';
					echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
				}
			}			
		}

		if(isset($_POST['industry']) && empty($_POST['industry'])){
			$sql_sub_industry="SELECT *  from  sub_industry order by sub_industry_name asc";
			if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
				foreach ($subs as $value) {
					$this_sub_industry_name=ucwords(strtolower($value["sub_industry_name"]));  
					$this_sub_industry_id=$value["auto_id"];
					echo '<div class="col-md-6"><label class="checkbox">';
					echo "<input id='this_sub_industry_id' name='sub_industry_name[".$this_sub_industry_id."]'  type='checkbox' value='$this_sub_industry_id' />";
					echo '<label class="checkbox">'.$this_sub_industry_name.'</label></label></div>';
				}
			}
			echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
		}

		if(isset($_POST['mainindustry']) && !empty($_POST['mainindustry'])){
			$industry 	= 	$_POST['mainindustry'];
			$sql_sub_industry="SELECT *  from  sub_industry where industry_id='$industry'  order by sub_industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_sub_industry)->queryAll()){
				echo "<option value='all'>-SELECT-</option>";
				foreach ($subs as $value) {
					$this_sub_industry_name=ucwords(strtolower($value["sub_industry_name"]));  
					$this_sub_industry_id=$value["auto_id"];
					echo '<option value="'.$this_sub_industry_id.'">'.$this_sub_industry_name.'</option>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['subindustry']) && !empty($_POST['subindustry'])){
			$subindustry 	= 	$_POST['subindustry'];
			$sql_brand="SELECT *  from  brand_table where sub_industry_id='$subindustry'   order by brand_name asc";
			if($brands = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
				echo '<div class="no-wrap">';
				foreach ($brands as $value) {
					$this_brand_name=ucwords(strtolower($value["brand_name"]));  
					$this_brand_id=$value["brand_id"];
					echo '<div class="col-md-6"><label class="checkbox">';
					echo "<input id='this_brand_id' name='brands[]'  type='checkbox' value='$this_brand_id' />";
					echo '<label class="checkbox">'.$this_brand_name.'</label></label></div>';
				}
				echo '</div>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['recon']) && isset($_POST['company']) && !empty($_POST['company'])){
			$thisCompany_idField 	= 	$_POST['company'];
			$sql_brand="SELECT brand_name, brand_id  from brand_table where company_id='$thisCompany_idField' order by brand_name asc";
			if($brands = Yii::app()->db3->createCommand($sql_brand)->queryAll()){
				echo '<div class="no-wrap">';
				foreach ($brands as $value) {
					$this_brand_name=ucwords(strtolower($value["brand_name"]));  
					$this_brand_id=$value["brand_id"];
					echo '<div class="col-md-6">';
					echo "<input id='this_brand_id' name='brands[]'  type='checkbox' value='$this_brand_id' />";
					echo ''.$this_brand_name.'</div>';
				}
				echo '</div><div class="clearfix"></div>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		/* SELECT Company Industries
		** This Query Works For Agencies
		** Do not Modify Unless you Understand
		*/

		if(isset($_POST['ratecompany']) && !empty($_POST['ratecompany'])){
			$company 	= 	$_POST['ratecompany'];

			$sql_industry="SELECT industry.industry_name, industry.industry_id  from industry ,  industryreport where
			industry.industry_id =industryreport.industry_id and
			industryreport.company_id='$company'
			order by industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_industry)->queryAll()){
				echo "<option value=''>-- Please SELECT a Value --</option>";
				echo "<option value='all'>-- All Industries --</option>";
				foreach ($subs as $value) {
					$this_industry_name=ucwords(strtolower($value["industry_name"]));  
					$this_industry_id=$value["industry_id"];
					echo '<option value="'.$this_industry_id.'">'.$this_industry_name.'</option>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		/* SELECT Company Brands
		** This Query Works For Agencies
		** Do not Modify Unless you Understand
		*/

		if(isset($_POST['agency_industry']) && isset($_POST['agency_id']) && isset($_POST['agency_company'])){
			$company 			= 	$_POST['agency_company'];
			$agency_id 			= 	$_POST['agency_id'];
			$agency_industry 	= 	$_POST['agency_industry'];

			if($agency_industry=='all'){
				$sql_ad="SELECT distinct(brand_name), brand_table.brand_id from agency_brand, brand_table 
				where agency_brand.agency_id='$agency_id' and brand_table.brand_id=agency_brand.brand_id  and brand_table.company_id='$company' order by brand_name asc ";
			}else{
				$sql_ad="SELECT distinct(brand_name), brand_table.brand_id from agency_brand, brand_table 
				where agency_brand.agency_id='$agency_id' and brand_table.brand_id=agency_brand.brand_id  and 
				brand_table.industry_id='$agency_industry' and brand_table.company_id='$company' order by brand_name asc ";
			}

			if($brands = Yii::app()->db3->createCommand($sql_ad)->queryAll()){
				$count = 0;
				foreach ($brands as $key) {
					$brandid = $key['brand_id'];
					$brandname = $key['brand_name'];
					echo '<label class="checkbox">
					<input id="brands_'.$count.'" value="'.$brandid.'" name="brands[]" type="checkbox">
					<label for="brands_'.$count.'">	'.$brandname.' </label></label>';
					$count++;
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

		if(isset($_POST['competitor_company']) && !empty($_POST['competitor_company'])){
			$company 	= 	$_POST['competitor_company'];

			$sql_industry="SELECT industry.industry_name, industry.industry_id  from industry ,  industryreport where
			industry.industry_id =industryreport.industry_id and
			industryreport.company_id='$company'
			order by industry_name asc";

			if($subs = Yii::app()->db3->createCommand($sql_industry)->queryAll()){
				echo "<option value=''>-- Please SELECT a Value --</option>";
				if(Yii::app()->user->rpts_only==1){
					echo "<option value='all'>-- All Industries --</option>";
				}
				foreach ($subs as $value) {
					$this_industry_name=ucwords(strtolower($value["industry_name"]));  
					$this_industry_id=$value["industry_id"];
					echo '<option value="'.$this_industry_id.'">'.$this_industry_name.'</option>';
				}
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}else{
				echo '<option>No Results Found</option>';
				echo '<script type="text/javascript"> $(document).ready(function() { $("#imageloadstatus").hide(); }); </script>';
			}
		}

	}

	public function actionExcel()
	{
		$filename = POFExcel::POFElectronic();
		Yii::app()->end();
	}

	public function actionPdf()
	{
		$model = new StorySearch;
		$mPDF1 = Yii::app()->ePdf2->Download('pof_pdf',array('model'=>$model),'POF_PDF');
		Yii::app()->end();
	}
}